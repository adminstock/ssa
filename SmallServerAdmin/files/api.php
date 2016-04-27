<?php
namespace Api
{

  /*
   * Copyright © Aleksey Nemiro, 2016. All rights reserved.
   * 
   * Licensed under the Apache License, Version 2.0 (the "License");
   * you may not use this file except in compliance with the License.
   * You may obtain a copy of the License at
   * 
   * http://www.apache.org/licenses/LICENSE-2.0
   * 
   * Unless required by applicable law or agreed to in writing, software
   * distributed under the License is distributed on an "AS IS" BASIS,
   * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   * See the License for the specific language governing permissions and
   * limitations under the License.
   */

  \Nemiro\App::IncludeFile('~/ssh/api.php');
  \Nemiro\App::IncludeFile('~/files/models/FileSystemItem.php');
  \Nemiro\App::IncludeFile('~/files/models/FileSystemItemInfo.php');

  /**
   * File Manager.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Files
  {

    /**
     * SSH client.
     * 
     * @var SSH
     */
    private $SshClient = NULL;

    function __construct()
    {
      $this->SshClient = new SSH();
    }

    public function GetList($data)
    {
      $path = $this->NormalizePath($data['path']);
      //$search_hidden = isset($data['search']) ? '/.[!.]*'.$data['search'].'*' : '/.[!.]*';
      //$search = isset($data['search']) ? '/*'.$data['search'].'*' : '/*';
      //$search_find = str_replace('\'', '\\\'', isset($data['search']) ? '*'.$data['search'].'*' : '*');

      // ls --all --color=never --group-directories-first --inode --size --width=1000 --quote-name --quoting-style=

      // TODO: Folders can not be detected properly.

      $shell_result = $this->SshClient->Execute
      ([
        // folders
        //'sudo bash -c "du --all --apparent-size --max-depth=0 --time --time-style=full-iso '.$this->EscapePath($path.$search_hidden).'/ '.$this->EscapePath($path.$search).'/ 2> /dev/null"',
        'sudo bash -c "cd '.$this->EscapePath($path).' && find -L -maxdepth 1 -type d -name \'*\' 2> /dev/null"',
        // files  --count-links
        'sudo bash -c "du --all --bytes --max-depth=0 --time --time-style=full-iso '.$this->EscapePath($path).'/.[!.]* '.$this->EscapePath($path).'/* 2> /dev/null"',
        // symlinks
        'sudo bash -c "cd '.$this->EscapePath($path).' && find -maxdepth 1 -type l -name \'*\' 2> /dev/null"',
      ]);

      foreach($shell_result as $r)
      {
        if ($r->Error != '')
        {
          throw new \ErrorException($r->Error);
        }
      }

      $result = [];

      if ($shell_result[1]->Result != '')
      {
        $folders = explode("\n", $shell_result[0]->Result);
        $items = explode("\n", $shell_result[1]->Result);
        $symlinks = explode("\n", $shell_result[2]->Result);

        foreach ($items as $item)
        {
          $row = preg_split('/\s+/', $item);
          $filePath = implode(' ', array_slice($row, 4));

          $result[] = new \Models\FileSystemItem
          (
            (array_search('./'.basename($filePath), $folders) !== FALSE ? 'Folder' : 'File'), 
            basename($filePath), 
            '/'.trim($filePath, '/'), 
            intval($row[0]), 
            $row[1].' '.$row[2].' '.$row[3], 
            array_search('./'.basename($filePath), $symlinks) !== FALSE
          );

          $inlist[] = '/'.trim($filePath, '/');
        }
      }
      
      /*$inlist = [];

      foreach($shell_result as $r)
      {
        if ($r->Result == '')
        {
          continue;
        }

        $items = explode("\n", $r->Result);

        foreach ($items as $item)
        {
          $data = preg_split('/\s+/', $item);
          $filePath = implode(' ', array_slice($data, 4));

          if (array_search($filePath, $inlist) !== FALSE)
          {
            continue;
          }

          $result[] = new \Models\FileSystemItem((substr($filePath, -1) == '/' ? 'Folder' : 'File'), basename($filePath), '/'.trim($filePath, '/'), intval($data[0]), $data[1].' '.$data[2].' '.$data[3]);

          $inlist[] = '/'.trim($filePath, '/');
        }
      }*/

      usort
      (
        $result, 
        function($a, $b) 
        { 
          if ($a->Type == $b->Type) { return strcmp($a->Name, $b->Name); }
          if ($a->Type == 'Folder') { return -1; }
          return 1;
        }
      );

      return $result;
    }

    public function CreateFolder($data)
    {
      if (!isset($data['Path']) || $data['Path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $this->NormalizePath($data['Path']);
      $owner = $data['Owner'];
      $group = $data['Group'];

      $shell_result = $this->SshClient->Execute('sudo bash -c "mkdir'.((bool)$data['Parents'] === TRUE ? ' --parents' : '').' \''.str_replace('\'', '\\\'', $path).'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      if (isset($owner) && $owner != '')
      {
        $shell_result = $this->SshClient->Execute('sudo bash -c "chown \''.$owner.''.(isset($group) && $group != '' ? ':'.$group : '').'\' \''.str_replace('\'', '\\\'', $path).'\'"');

        if ($shell_result->Error != '')
        {
          throw new \ErrorException($shell_result->Error);
        }
      }

      if ((!isset($owner) || $owner == '') && isset($group) && $group != '')
      {
        $shell_result = $this->SshClient->Execute('sudo bash -c "chown "$(stat --printf=\'%U\' \''.str_replace('\'', '\\\'', $path).'\'):'.$group.'" \''.str_replace('\'', '\\\'', $path).'\'"');

        if ($shell_result->Error != '')
        {
          throw new \ErrorException($shell_result->Error);
        }
      }

      return [ 'Path' => $path ];
    }

    public function Rename($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      if (!isset($data['name']) || $data['name'] == '')
      {
        throw new \ErrorException('New name of the "'.$data['path'].'" is required.');
      }

      if (basename($data['path']) == $data['name'])
      {
        throw new \ErrorException('The new name must be different from the original.');
      }

      $path = $this->NormalizePath($data['path']);
      $newName = dirname($path).'/'.$data['name'];

      $shell_result = $this->SshClient->Execute('sudo bash -c "[[ -d \''.$newName.'\' || -f \''.$newName.'\' ]] && echo \'TRUE\' || echo \'FALSE\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      if ($shell_result->Result == 'TRUE')
      {
        throw new \ErrorException('The "'.$newName.'" already exists. Please enter other name.');
      }

      $shell_result = $this->SshClient->Execute('sudo bash -c "mv \''.$path.'\' \''.$newName.'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return [ 'Path' => $newName ];
    }
    
    public function Delete($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $this->NormalizePath($data['path']);

      $shell_result = $this->SshClient->Execute('sudo bash -c "rm --force --dir --recursive \''.$path.'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }
    
    public function Get($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $this->NormalizePath($data['path']);
      $mode = isset($data['mode']) ? strtolower($data['mode']) : '';
      
      $shell_result = $this->SshClient->Execute('sudo bash -c "[[ -f \''.$path.'\' ]] && echo \'TRUE\' || echo \'FALSE\'"');

      if ($shell_result->Result == 'FALSE')
      {
        throw new \ErrorException('File "'.$path.'" not found.');
      }

      if ($mode == 'hex')
      {
        $cmd = 'sudo xxd \''.$path.'\'';
      }
      else
      {
        $cmd = 'sudo cat \''.$path.'\'';
      }

      $result = $this->SshClient->Execute2($cmd);

      if (mb_check_encoding($result, 'UTF-8'))
      {
        $result = \Nemiro\Text::ClearUTF8BOM($result);
      }

      return ['Content' => $result];
    }

    public function Info($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $this->NormalizePath($data['path']);

      // name | escaped name | permissions code | permissions | type code | GID | group name | UID | username | size | date creared | date last access | date updated | date state updated
      $shell_result = $this->SshClient->Execute('sudo bash -c "stat --printf=\'%n\n%N\n%a\n%A\n%f\n%g\n%G\n%u\n%U\n%s\n%W\n%X\n%Y\n%Z\n\' \''.str_replace('\'', '\\\'', $path).'\' && du --bytes --max-depth=0 \''.str_replace('\'', '\\\'', $path).'\' 2> /dev/null | cut -f1"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      $info = explode("\n", $shell_result->Result);

      $result = new \Models\FileSystemItemInfo();

      switch ($info[3][0])
      {
        case 'd':
          $result->Type = 'Folder';
          break;

        case 'l':
          $result->Type = 'Link';
          break;

        default:
          $result->Type = 'File';
      }

      $result->Name = basename($info[0]);
      $result->Path = dirname($info[0]);

      if (strpos($info[1], '->') !== FALSE)
      {
        if (mb_check_encoding($info[1], 'UTF-8'))
        {
          $result->TargetPath = substr(trim(explode('->', $info[1])[1]), 2, -2); //trim(explode('->', $info[1])[1], "\t\n\r\0\x0B\x20\x22\x27\x60«»"); //\u00AB\u00BB
        }
        else
        {
          $result->TargetPath = substr(trim(explode('->', $info[1])[1]), 1, -1);
        }
      }
      
      $result->GID = $info[5];
      $result->GroupName = $info[6];
      $result->UID = $info[7];
      $result->Username = $info[8];

      if (intval($info[14]) > 0)
      {
        $result->Size = $info[14];
      }
      else
      {
        $result->Size = $info[9];
      }

      $result->DateCreated = (intval($info[10]) > 0 ? $info[10] * 1000 : NULL); 
      $result->DateLastAccess = (intval($info[11]) > 0 ? $info[11] * 1000 : NULL);
      $result->DateLastModified = (intval($info[12]) > 0 ? $info[12] * 1000 : NULL);

      $result->Permissions = $info[2];

      return $result;
    }

    public function SaveInfo($data)
    {
      if (!isset($data['Source']))
      {
        throw new \ErrorException('Source is required.');
      }

      if (!isset($data['Current']))
      {
        throw new \ErrorException('Current is required.');
      }

      if (!isset($data['Source']['Path']) || $data['Source']['Path'] == '')
      {
        throw new \ErrorException('Source path is required.');
      }
      
      if (!isset($data['Current']['Path']) || $data['Current']['Path'] == '')
      {
        throw new \ErrorException('Current path is required.');
      }

      if (!isset($data['Source']['Name']) || $data['Source']['Name'] == '')
      {
        throw new \ErrorException('Source name is required.');
      }
      
      if (!isset($data['Current']['Name']) || $data['Current']['Name'] == '')
      {
        throw new \ErrorException('Current name is required.');
      }

      $sourcePath = $this->NormalizePath($data['Source']['Path']);
      $sourceName = $data['Source']['Name'];

      $currentPath = $this->NormalizePath($data['Current']['Path']);
      $currentName = $data['Current']['Name'];

      $commands = [];

      if ($currentName != $sourceName)
      {
        // check new name
        $commands[] = 'sudo bash -c "[[ -d \''.str_replace('\'', '\\\'', $currentPath.'/'.$currentName).'\' || -f \''.str_replace('\'', '\\\'', $currentPath.'/'.$currentName).'\' ]] && echo \'TRUE\' || echo \'FALSE\'"';
      }
      else
      {
        $commands[] = 'echo "FALSE"';
      }

      // check username
      $commands[] = 'sudo bash -c "id -u \''.$data['Current']['Username'].'\' >/dev/null 2>&1 && echo \'TRUE\' || echo \'FALSE\'"';

      // check group
      $commands[] = 'sudo bash -c "id -g \''.$data['Current']['GroupName'].'\' >/dev/null 2>&1 && echo \'TRUE\' || echo \'FALSE\'"';

      $shell_result = $this->SshClient->Execute($commands);

      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      if ($shell_result[0]->Result == 'TRUE')
      {
        throw new \ErrorException('The path "'.$currentPath.'/'.$currentName.'" already exists. Please enter other name.');
      }

      if ($shell_result[1]->Result == 'FALSE')
      {
        throw new \ErrorException('User "'.$data['Current']['Username'].'" is not found.');
      }
      
      if ($shell_result[2]->Result == 'FALSE')
      {
        throw new \ErrorException('Group "'.$data['Current']['Username'].'" is not found.');
      }

      // save properties
      $commands = [];

      if ($currentName != $sourceName)
      {
        $commands[] = 'sudo bash -c "mv \''.$sourcePath.'/'.$sourceName.'\' \''.$currentPath.'/'.$currentName.'\'"';
      }

      $commands[] = 'sudo bash -c "chown'.((bool)$data['Recursive'] === TRUE ? ' -R' : '').' \''.$data['Current']['Username'].':'.$data['Current']['GroupName'].'\' \''.$currentPath.'/'.$currentName.'\'"';

      $commands[] = 'sudo bash -c "chmod'.((bool)$data['Recursive'] === TRUE ? ' -R' : '').' '.$data['Current']['Permissions'].' \''.$currentPath.'/'.$currentName.'\'"';
      
      $shell_result = $this->SshClient->Execute($commands);

      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      return $this->Info(['path' => $currentPath.'/'.$currentName]);
    }

    public function Save($data)
    {
      global $config;

      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      if (!isset($data['content']))
      {
        throw new \ErrorException('Content is required.');
      }

      $path = $data['path'];
      $newPath = $data['newPath'];
      $overwrite = (bool)$data['overwrite'];
      $owner = $data['owner'];

      if (isset($data['group']) && $data['group'] != '')
      {
        $group = ':'.$data['group'];
      }
      else
      {
        $group = '';
      }

      if (isset($newPath) && $newPath != '')
      {
        // check path
        if ($newPath == $path)
        {
          // throw new \ErrorException('The new path must be different from the original.');
        }
        
        if (dirname($newPath) == '' || dirname($newPath) == '.')
        {
          $newPath = dirname($path).'/'.$newPath;
        }

        if ($overwrite === FALSE)
        {
          $shell_result = $this->SshClient->Execute('sudo bash -c "[[ -d \''.$newPath.'\' || -f \''.$newPath.'\' ]] && echo \'TRUE\' || echo \'FALSE\'"');

          if ($shell_result->Error != '')
          {
            throw new \ErrorException($shell_result->Error);
          }

          if ($shell_result->Result == 'TRUE')
          {
            return ['Success' => FALSE, 'OverwriteRequest' => TRUE, 'Message' => 'The file "'.$newPath.'" already exists. Do you want to overwrite the file?'];
          }
        }

        $path = $newPath;
      }

      $content = $data['content'];

      $content = preg_replace('/((\r\n)|(\r))+/', "\n", $content);
      $content = str_replace('\\', '\\\\', $content);
      $content = str_replace('\'', '\'\\\'\'', $content);
      $content = str_replace('`', '\\`', $content);
      $content = str_replace('$', '\\$', $content);
      // $content = str_replace('EOF', '\\EOF', $content);
      
      $shell_result = $this->SshClient->Execute("sudo bash -c 'cat <<\EOF > \"".str_replace('"', '\\"', $path)."\"\n".$content."\nEOF' && sudo bash -c 'truncate -s -1 \"".str_replace('"', '\\"', $path)."\"'");

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      if (isset($owner) && $owner != '')
      {
        $shell_result = $this->SshClient->Execute('sudo bash -c "chown \''.$owner.$group.'\' \''.str_replace('\'', '\\\'', $path).'\'"');

        if ($shell_result->Error != '')
        {
          throw new \ErrorException('Failed to set the owner: '.$shell_result->Error);
        }
      }

      #region autoreload
      
      if (FALSE)
      {
        if (strpos($path, '/etc/init.d') !== FALSE && isset($config['files_auto_reload']) && (bool)$config['files_auto_reload']['daemon'] === TRUE)
        {
          $shell_result = $this->SshClient->Execute('sudo systemctl daemon-reload');

          if ($shell_result->Error != '')
          {
            throw new \ErrorException('Failed to daemon-reload: '.$shell_result->Error);
          }
        }

        if (preg_match('#/etc/nginx/(sites-available|sites-enabled)#', $path) > 0 && isset($config['files_auto_reload']) && (bool)$config['files_auto_reload']['nginx'] === TRUE)
        {
          $shell_result = $this->SshClient->Execute('sudo nginx -t && sudo service nginx reload');

          if ($shell_result->Error != '')
          {
            throw new \ErrorException('Failed to nginx reload: '.$shell_result->Error);
          }
        }

        if (preg_match('#/etc/apache(2|)/(sites-available|sites-enabled)#', $path) > 0 && isset($config['files_auto_reload']) && (bool)$config['files_auto_reload']['apache'] === TRUE)
        {
          $shell_result = $this->SshClient->Execute('sudo apachectl -t && sudo service apache2 reload');

          if ($shell_result->Error != '')
          {
            throw new \ErrorException('Failed to apache reload: '.$shell_result->Error);
          }
        }
      }

      #endregion

      return ['Success' => TRUE];
    }

    public function Execute($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $data['path'];
      $args = $data['args'];
      $login = $data['login'];

      $shell_result = $this->SshClient->Execute('sudo'.(isset($login) && $login != '' ? ' -u '.$login : '').' bash -c "\''.$path.'\''.(isset($args) && $args != '' ? ' '.$args : '').'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Content' => $shell_result->Result];
    }

    #region ..Private methods..

    private function EscapePath($value)
    {
      return preg_replace('/([\x20\$]{1})/', '\\\\$1', $value);
    }

    private function NormalizePath($path)
    {
      if (!isset($path) || $path == '')
      {
        $path = '/';
      }

      if (\Nemiro\Text::EndsWith($path, '/') || \Nemiro\Text::EndsWith($path, '\\'))
      {
        $path = substr($path, 0, -1);
      }

      if (!\Nemiro\Text::StartsWith($path, '/'))
      {
        $path = '/'.$path;
      }

      return $path;
    }
    
    #endregion

  }

}