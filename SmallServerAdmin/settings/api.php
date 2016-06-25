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
      if (is_file(\Nemiro\Server::MapPath('~/settings/update.sh')) === FALSE)
      {
        throw new \ErrorException('File "'.\Nemiro\Server::MapPath('~/settings/update.sh').' not found.');
      }

      if (!isset($data['SsaUrl']) || $data['SsaUrl'] == '')
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
      $command .= '\$updatePath "'.$path.'" "'.$data['SsaUrl'].'" && ';
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
      global $config;
      $servers = [];

      // default server
      $servers[] = $this->GetServerInfo(\Nemiro\Server::MapPath('~/ssa.config.php'), TRUE);

      // get others servers
      if (is_dir(\Nemiro\Server::MapPath('~/servers')))
      {
        foreach (scandir(\Nemiro\Server::MapPath('~/servers')) as $file) 
        {
          // NOTE: ssa.config.php - reserved for default (root) config
          if ($file == '.' || $file == '..' || $file == 'ssa.config.php' || pathinfo($file, PATHINFO_EXTENSION) != 'php') { continue; }
          $servers[] = $this->GetServerInfo(\Nemiro\Server::MapPath('~/servers/'.$file), FALSE);
        }

        usort($servers, function($a, $b) { return strcmp($a->Name, $b->Name); });
      }

      return $servers;
    }

    private function GetServerInfo($path, $default)
    {
      require $path;

      return [
        'Address' => $config['ssh_host'], 
        'Name' => $config['server_name'], 
        'Description' => $config['server_description'], 
        'Config' => basename($path, '.php'),
        'IsDefault' => $default,
        'Disabled' => ($default ? FALSE : (bool)$config['server_disabled'])
      ];
    }

    #endregion

  }

}