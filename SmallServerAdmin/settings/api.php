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
      if ($this->AllModules == NULL)
      {
        $this->GetModules();
      }

      global $config;

      $defaultModules = $config['modules'];

      require $path;

      $modules = [];
      $serverModules = NULL;
      $allModules = $this->AllModules;

      if (isset($config['modules']) && $config['modules'] != '')
      {
        $serverModules = explode(',', $config['modules']);
      }
      else
      {
        // default modules (to save order)
        $serverModules = $defaultModules;
      }

      // server modules (to save order)
      if ($serverModules != NULL)
      {
        foreach($serverModules as $module)
        {
          $modules[] = ['Name' => $module, 'Enabled' => TRUE];
          if (($idx = array_search($module, $allModules)) !== FALSE)
          {
            array_splice($allModules, $idx, 1);
          }
        }
      }

      // other modules
      foreach($allModules as $module)
      {
        $modules[] = ['Name' => $module, 'Enabled' => $serverModules == NULL];
      }

      return [
        'Address' => isset($config['ssh_host']) ? $config['ssh_host'] : '', 
        'Port' => (isset($config['ssh_port']) && (int)$config['ssh_port'] > 0 ? $config['ssh_port'] : 22), 
        'Username' => ($withoutPassword !== TRUE ? (isset($config['ssh_user']) ? $config['ssh_user'] : '') : NULL), 
        'Password' => ($withoutPassword !== TRUE ? (isset($config['ssh_password']) ? $config['ssh_password'] : '') : NULL), 
        'Name' => isset($config['server_name']) ? $config['server_name'] : NULL, 
        'Description' => isset($config['server_description']) ? $config['server_description'] : NULL, 
        'Config' => basename($path, '.php'),
        'Disabled' => (isset($config['server_disabled']) ? (bool)$config['server_disabled'] : FALSE),
        'RequiredPassword' => (isset($config['ssh_required_password']) ? (bool)$config['ssh_required_password'] : FALSE),
        'LogoutRedirect' => (isset($config['logout_redirect']) ? $config['logout_redirect'] : NULL),
        'Modules' => $modules
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

      // keys of configuration to the keys of the input data
      $mapper = 
      [
        [ 'ConfigKey' => 'ssh_host', 'DataKey' => 'Address', 'DefaultValue' => '', 'Required' => TRUE ],
        [ 'ConfigKey' => 'ssh_port', 'DataKey' => 'Port', 'DefaultValue' => '22', 'Required' => TRUE, 'Type' => 'int' ],
        [ 'ConfigKey' => 'ssh_user', 'DataKey' => 'Username', 'DefaultValue' => '', 'Required' => TRUE ],
        [ 'ConfigKey' => 'ssh_password', 'DataKey' => 'Password', 'DefaultValue' => '', 'Required' => TRUE ],
        [ 'ConfigKey' => 'ssh_required_password', 'DataKey' => 'RequiredPassword', 'DefaultValue' => '', 'Required' => TRUE, 'Type' => 'bool' ],
        [ 'ConfigKey' => 'server_name', 'DataKey' => 'Name', 'DefaultValue' => '', 'Required' => FALSE ],
        [ 'ConfigKey' => 'server_description', 'DataKey' => 'Description', 'DefaultValue' => '', 'Required' => FALSE ],
        [ 'ConfigKey' => 'logout_redirect', 'DataKey' => 'LogoutRedirect', 'DefaultValue' => '', 'Required' => FALSE ],
        [ 'ConfigKey' => 'modules', 'DataKey' => 'Modules', 'DefaultValue' => '', 'Required' => FALSE, 'Handler' => function($h_value) { return $this->GetEnabledModules($h_value); } ]
      ];

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
        foreach($mapper as $item)
        {
          $this->AddConfigItemToArray($item, $data, $lines);
        }
        
        $data['Config'] = basename($path, '.php');
      }
      else
      {
        $path = \Nemiro\Server::MapPath('~/servers/'.$data['Config'].'.php');

        #region parse existing file

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
              // search item in map
              if (count($options = array_filter($mapper, function ($item) use ($key) { return $item['ConfigKey'] == $key; })) <= 0)
              {
                // don't change
                $lines[] = $line;
              }
              else
              {
                // set value
                $this->AddConfigItemToArray(array_shift($options), $data, $lines);
              }

              if ($key != '')
              {
                $keys[] = $key;
              }
            }

            $line = '';
          }
        }

        #endregion
        #region parameters that were not found in the current config file

        foreach($mapper as $item)
        {
          if (array_search($item['ConfigKey'], $keys) !== FALSE)
          {
            continue;
          }

          $this->AddConfigItemToArray($item, $data, $lines);
        }

        #endregion
      }

      // save file
      if (file_put_contents ($path, '<?php'."\n".implode("\n", $lines)) === FALSE)
      {
        throw new \ErrorException('Unable to save file.');
      }

      return $data;
    }

    /**
     * Returns enabled modules from modules array.
     * 
     * @param mixed $modules Array: ['Name' => 'Example', 'Enabled' => TRUE|FALSE]
     * @return string|null
     */
    private function GetEnabledModules($modules)
    {
      if (!isset($modules) || count($modules) <= 0)
      {
        return NULL;
      }

      $result = [];

      foreach($modules as $module)
      {
        if ($module['Enabled'] === FALSE)
        {
          continue;
        }

        $result[] = $module['Name'];
      }

      if (count($result) > 0)
      {
        return implode(',', $result);
      }
      else
      {
        return NULL;
      }
    }

    private function AddConfigItemToArray($item, $data, &$lines)
    {
      $value = '';

      if (isset($data[$item['DataKey']]) && $data[$item['DataKey']] != '')
      {
        $value = $data[$item['DataKey']];
      }

      if (isset($item['Handler']))
      {
        $value = $item['Handler']($value);
      }

      if (($value == NULL || $value == '') && isset($item['Required']) && (bool)$item['Required'] === FALSE)
      {
        if (isset($item['DefaultValue']) && $item['DefaultValue'] != '')
        {
          $value = $item['DefaultValue'];
        }
        else
        {
          return;
        }
      }
            
      if (isset($item['Type']) && $item['Type'] == 'int')
      {
        $lines[] = '$config[\''.$item['ConfigKey'].'\'] = '.(int)$value.';';
      }
      else if (isset($item['Type']) && $item['Type'] == 'bool')
      {
        $lines[] = '$config[\''.$item['ConfigKey'].'\'] = '.((bool)$value ? 'TRUE' : 'FALSE').';';
      }
      else
      {
        $lines[] = '$config[\''.$item['ConfigKey'].'\'] = \''.str_replace('\'', '\\\'', $value).'\';';
      }
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

    private $AllModules = NULL;

    public function GetModules()
    {
      $result = [];
      $skipDirs = [ '.', '..', 'Content', 'Controls', 'Layouts', 'Libs', 'servers' ];

      foreach(scandir(\Nemiro\Server::MapPath('~/')) as $item)
      {
        if (is_dir(\Nemiro\Server::MapPath('~/'.$item)) === FALSE || array_search($item, $skipDirs) !== FALSE) 
        {
          continue;
        }

        $result[] = $item;
      }

      $this->AllModules = $result;

      return $result;
    }

    #endregion
    
  }

}