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

  /**
   * Panel Settings.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Settings
  {

    function __construct()
    {
    }

    #region Update

    public function CheckUpdates($data)
    {
      global $config;

      // default (main) branch
      $stable = (isset($config['settings_default_branch']) && $config['settings_default_branch'] != '' ? $config['settings_default_branch'] : 'master');

      // get versions from remote server
      $versions = $config['settings_update_sources'];
      
      foreach($versions as $k => $v)
      {
        if (!isset($versions[$k]['VersionUrl']) || $versions[$k]['VersionUrl'] == '')
        {
          throw new \ErrorException('Cannot find "VersionUrl". Please check "settings_update_sources" in "ssa.config.php".');
        }

        $versions[$k]['Version'] = file_get_contents($versions[$k]['VersionUrl']);

        if ($versions[$k]['Version'] === FALSE)
        {
          throw new \ErrorException('Cannot get latest version number from '.$versions[$k]['VersionUrl']);
        }

        // parse version
        if (preg_match('/\d+\.\d+\.\d+/', $versions[$k]['Version'], $matches))
        {
          $versions[$k]['Version'] = $matches[0];
        }
        else
        {
          // exception only for master (because the stable version expected)
          if ($k == $stable)
          {
            throw new \ErrorException('Cannot parse version number from string "'.$versions[$k]['Version'].'"');
          }
        }
      }

      // get local version
      $currentVersion = file_get_contents(\Nemiro\Server::MapPath('~/.version'));

      if ($currentVersion === FALSE)
      {
        throw new \ErrorException('Cannot get current version from "'.\Nemiro\Server::MapPath('~/.version').'"');
      }

      if (preg_match('/\d+\.\d+\.\d+/', $currentVersion, $matches))
      {
        $currentVersion = $matches[0];
      }
      else
      {
        throw new \ErrorException('Cannot parse version number from string "'.$currentVersion.'"');
      }

      $result = [];

      // check versions
      foreach($versions as $k => $v)
      {
        if (version_compare($v['Version'], $currentVersion) > 0)
        {
          // current version is outdated
          // get changes log
          if (isset($v['ChangeLogUrl']) && $v['ChangeLogUrl'] != '' && ($changes = file_get_contents($v['ChangeLogUrl'])) !== FALSE)
          {
            // extract new version segment
            if (($start = strpos($changes, '## ['.$v['Version'])) === FALSE)
            {
              $start = strpos($changes, '## [');
            }
            $end = strpos($changes, '## [', $start + 1);
            $changes = trim(substr($changes, $start, $end - $start));
          }
          else 
          {
            $changes = NULL;
          }

          $result[] = 
          [
            'Branch' => $k,
            'BranchTitle' => isset($v['Title']) ? $v['Title'] : NULL,
            'BranchDescription' => isset($v['Description']) ? $v['Description'] : NULL,
            'NeedUpdate' => TRUE, 
            'CurrentVersion' => $currentVersion,
            'LatestVersion' => $v['Version'], 
            'Changes' => $changes
          ];
        }
        else
        {
          // current version is actual
          $result[] = 
          [
            'Branch' => $k,
            'BranchTitle' => isset($v['Title']) ? $v['Title'] : NULL,
            'BranchDescription' => isset($v['Description']) ? $v['Description'] : NULL,
            'NeedUpdate' => FALSE, 
            'CurrentVersion' => $currentVersion,
            'LatestVersion' => $v['Version']
          ];
        }
      }

      return $result;
    }

    public function Update($data)
    {
      global $config;

      if (is_file(\Nemiro\Server::MapPath('~/settings/update.sh')) === FALSE)
      {
        throw new \ErrorException('File "'.\Nemiro\Server::MapPath('~/settings/update.sh').' not found.');
      }

      if (!isset($config['settings_update_sources']))
      {
        throw new \ErrorException('"settings_update_sources" is required. Please check your "ssa.config.php".');
      }

      if (!isset($data['Branch']) || $data['Branch'] == '')
      {
        // default branch
        $data['Branch'] = (isset($config['settings_default_branch']) && $config['settings_default_branch'] != '' ? $config['settings_default_branch'] : 'master');
      }

      if (!isset($config['settings_update_sources'][$data['Branch']]) || !isset($config['settings_update_sources'][$data['Branch']]['SsaUrl']) || $config['settings_update_sources'][$data['Branch']]['SsaUrl'] == '')
      {
        throw new \ErrorException('SsaUrl is required. Value cannot be empty.');
      }

      $sshClient = new SSH();
      $path = \Nemiro\Server::MapPath('~/');
      $scriptPath = \Nemiro\Server::MapPath('~/settings/update.sh');

      $command = 'sudo bash -c \'';
      $command .= 'updatePath="$(mktemp --dry-run /tmp/XXXXX.update.sh)"; ';
      $command .= 'cp '.$scriptPath.' \$updatePath && ';
      $command .= 'chmod +x \$updatePath && ';
      $command .= '\$updatePath "'.$path.'" "'.$config['settings_update_sources'][$data['Branch']]['SsaUrl'].'" && ';
      $command .= 'rm \$updatePath';
      $command .= '\'';

      $shell_result = $sshClient->Execute($command);

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }

    #endregion
    #region Servers

    public function CheckConnection()
    {
      // create an instance
      new SSH();

      // no exceptions
      return ['Success' => TRUE];
    }

    /**
     * Returs servers list.
     */
    public function GetServers()
    {
      $servers = [];

      // get servers
      if (is_dir(\Nemiro\Server::MapPath('~/servers')))
      {
        foreach (scandir(\Nemiro\Server::MapPath('~/servers')) as $file) 
        {
          if ($file == '.' || $file == '..' || $file == 'ssa.config.php' || pathinfo($file, PATHINFO_EXTENSION) != 'php') { continue; }
          $servers[] = $this->GetServerInfo(\Nemiro\Server::MapPath('~/servers/'.$file), TRUE);
        }

        usort($servers, function($a, $b) { return strcmp($a->Name, $b->Name); });
      }

      return $servers;
    }

    /**
     * Returns specified server.
     * 
     * @param mixed $data 
     */
    public function GetServer($data)
    {
      if (!isset($data['Config']) || $data['Config'] == '')
      {
        throw new \ErrorException('Config is required. Value cannot be empty.');
      }

      $path = \Nemiro\Server::MapPath('~/servers/'.$data['Config'].'.php');

      if (!is_file($path))
      {
        throw new \ErrorException('"'.$path.'" not found.');
      }

      return $this->GetServerInfo($path, FALSE);
    }

    private function GetServerInfo($path, $withoutPassword)
    {
      require $path;

      return [
        'Address' => isset($config['ssh_host']) ? $config['ssh_host'] : '', 
        'Port' => (isset($config['ssh_port']) && (int)$config['ssh_port'] > 0 ? $config['ssh_port'] : 22), 
        'Username' => ($withoutPassword !== TRUE ? (isset($config['ssh_user']) ? $config['ssh_user'] : '') : NULL), 
        'Password' => ($withoutPassword !== TRUE ? (isset($config['ssh_password']) ? $config['ssh_password'] : '') : NULL), 
        'Name' => isset($config['server_name']) ? $config['server_name'] : NULL, 
        'Description' => isset($config['server_description']) ? $config['server_description'] : NULL, 
        'Config' => basename($path, '.php'),
        'Disabled' => (isset($config['server_disabled']) ? (bool)$config['server_disabled'] : FALSE),
        'RequiredPassword' => (isset($config['ssh_required_password']) ? (bool)$config['ssh_required_password'] : FALSE)
      ];
    }

    public function SaveServer($data)
    {
      if (!isset($data['Address']) || $data['Address'] == '')
      {
        throw new \ErrorException('Address is required. Value cannot be empty.');
      }
      
      if (!isset($data['Username']) || $data['Username'] == '')
      {
        throw new \ErrorException('Username is required. Value cannot be empty.');
      }
            
      if (!isset($data['Password']) || $data['Password'] == '')
      {
        throw new \ErrorException('Password is required. Value cannot be empty.');
      }

      if (is_dir(\Nemiro\Server::MapPath('~/servers')) === FALSE)
      {
        // make dir
        if (mkdir(\Nemiro\Server::MapPath('~/servers')) === FALSE)
        {
          throw new \ErrorException('Directory "'.\Nemiro\Server::MapPath('~/servers').'" not found. Please create the directory and try again.');
        }
      }

      if (!is_writable(\Nemiro\Server::MapPath('~/servers')))
      {
        throw new \ErrorException('Directory "'.\Nemiro\Server::MapPath('~/servers').'" is not writable. Please set the permissions to write to that directory and try again.');
      }

      // TODO: Less code

      $path = '';
      $lines = [];
      $keys = [];

      if (!isset($data['Config']) || $data['Config'] == '')
      {
        // is new config
        $path = \Nemiro\Server::MapPath('~/servers/'.$data['Address'].'.php');
        $i = 1;

        while (is_file($path) === TRUE)
        {
          $path = \Nemiro\Server::MapPath('~/servers/'.$data['Address'].'-'.$i.'.php');
          $i++;
        }

        // add lines
        if (isset($data['Name']) && $data['Name'] != '')
        {
          $lines[] = '$config[\'server_name\'] = \''.str_replace('\'', '\\\'', $data['Name']).'\';';
        }

        if (isset($data['Description']) && $data['Description'] != '')
        {
          $lines[] = '$config[\'server_description\'] = \''.str_replace("\n", ' ', str_replace('\'', '\\\'', $data['Description'])).'\';';
        }

        $lines[] = '$config[\'ssh_host\'] = \''.str_replace('\'', '\\\'', $data['Address']).'\';';
        $lines[] = '$config[\'ssh_port\'] = '.(isset($data['Port']) && (int)$data['Port'] > 0 ? $data['Port'] : 22).';';
        $lines[] = '$config[\'ssh_user\'] = \''.str_replace('\'', '\\\'', $data['Username']).'\';';
        $lines[] = '$config[\'ssh_password\'] = \''.str_replace('\'', '\\\'', $data['Password']).'\';';
        $lines[] = '$config[\'ssh_required_password\'] = '.(!isset($data['RequiredPassword']) || (bool)$data['RequiredPassword'] ? 'TRUE' : 'FALSE').';';

        $data['Config'] = basename($path, '.php');
      }
      else
      {
        $path = \Nemiro\Server::MapPath('~/servers/'.$data['Config'].'.php');

        // parse existing file
        $tokens = token_get_all(file_get_contents($path));
        
        $line = ''; $key = '';
        $isConfig = FALSE; $keyWaiting = FALSE;

        foreach($tokens as $token)
        {
          if (count($token) > 1 && $token[1] == '$config')
          {
            $isConfig = TRUE;
            $key = '';
          }

          if ($isConfig === TRUE)
          {
            if (count($token) > 1) 
            {
              $line .= $token[1];

              if ($keyWaiting && $token[0] == T_CONSTANT_ENCAPSED_STRING)
              {
                $key = trim($token[1], " \t\n\r\0\x0B'\"");
                $keyWaiting = FALSE;
              }
            }
            else 
            {
              $line .= $token;

              if ($token == '[' && $key == '')
              {
                $keyWaiting = TRUE;
              }
            }
          }
          
          if (count($token) == 1 && $token == ';' && $isConfig === TRUE)
          {
            $isConfig = FALSE;
            
            if (array_search($key, $keys) === FALSE)
            {
              // set value
              switch($key)
              {
                case 'server_name':
                  if (isset($data['Name']) && $data['Name'] != '')
                  {
                    $lines[] = '$config[\'server_name\'] = \''.str_replace('\'', '\\\'', $data['Name']).'\';';
                  }
                  break;

                case 'server_description':
                  if (isset($data['Description']) && $data['Description'] != '')
                  {
                    $lines[] = '$config[\'server_description\'] = \''.str_replace("\n", ' ', str_replace('\'', '\\\'', $data['Description'])).'\';';
                  }
                  break;

                case 'ssh_host':
                  $lines[] = '$config[\'ssh_host\'] = \''.str_replace('\'', '\\\'', $data['Address']).'\';';
                  break;

                case 'ssh_port':
                  $lines[] = '$config[\'ssh_port\'] = '.(isset($data['Port']) && (int)$data['Port'] > 0 ? $data['Port'] : 22).';';
                  break;

                case 'ssh_user':
                  $lines[] = '$config[\'ssh_user\'] = \''.str_replace('\'', '\\\'', $data['Username']).'\';';
                  break;

                case 'ssh_password':
                  $lines[] = '$config[\'ssh_password\'] = \''.str_replace('\'', '\\\'', $data['Password']).'\';';
                  break;

                case 'ssh_required_password':
                  $lines[] = '$config[\'ssh_required_password\'] = '.(!isset($data['RequiredPassword']) || (bool)$data['RequiredPassword'] ? 'TRUE' : 'FALSE').';';
                  break;

                default:
                  // don't change
                  $lines[] = $line;
              }

              if ($key != '')
              {
                $keys[] = $key;
              }
            }

            $line = '';
          }
        }
      }

      // save file
      if (file_put_contents ($path, '<?php'."\n".implode("\n", $lines)) === FALSE)
      {
        throw new \ErrorException('Unable to save file.');
      }

      return $data;
    }

    public function DeleteServer($data)
    {
      if (!isset($data['Config']) || $data['Config'] == '')
      {
        throw new \ErrorException('Config is required. Value cannot be empty.');
      }

      $path = \Nemiro\Server::MapPath('~/servers/'.$data['Config'].'.php');

      if (is_file($path))
      {
        if (unlink($path) === FALSE)
        {
          throw new \ErrorException('Unable to delete file: "'.$path.'"');
        }
      }

      return ['Success' => TRUE];
    }

    #endregion

  }

}