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
      $versionUrl = 'https://raw.githubusercontent.com/adminstock/ssa/master/SmallServerAdmin/.version';
      $latestVersion = file_get_contents($versionUrl);

      if ($latestVersion === FALSE)
      {
        throw new \ErrorException('Cannot get latest version number from '.$versionUrl);
      }

      $currentVersion = file_get_contents(\Nemiro\Server::MapPath('~/.version'));

      if ($currentVersion === FALSE)
      {
        throw new \ErrorException('Cannot get current version from "'.\Nemiro\Server::MapPath('~/.version').'"');
      }

      // parse version
      if (preg_match('/\d+\.\d+\.\d+/', $latestVersion, $matches))
      {
        $latestVersion = $matches[0];
      }
      else
      {
        throw new \ErrorException('Cannot parse version number from string "'.$latestVersion.'"');
      }

      if (preg_match('/\d+\.\d+\.\d+/', $currentVersion, $matches))
      {
        $currentVersion = $matches[0];
      }
      else
      {
        throw new \ErrorException('Cannot parse version number from string "'.$currentVersion.'"');
      }

      if (version_compare($latestVersion, $currentVersion) > 0)
      {
        // current version is outdated
        // get changes log
        if (($changes = file_get_contents('https://raw.githubusercontent.com/adminstock/ssa/master/CHANGELOG.md')) !== FALSE)
        {
          // extract new version segment
          $start = strpos($changes, '## ['.$latestVersion);
          $end = strpos($changes, '## [', $start + 1);
          $changes = trim(substr($changes, $start, $end - $start));
        }

        return [
          'NeedUpdate' => TRUE, 
          'NewVersion' => $latestVersion, 
          'Changes' => $changes
        ];
      }
      else
      {
        // current version is actual
        return [
          'NeedUpdate' => FALSE, 
          'LatestVersion' => $latestVersion, 
          'CurrentVersion' => $currentVersion
        ];
      }
    }

    public function Update($data)
    {
      if (is_file(\Nemiro\Server::MapPath('~/settings/update.sh')) === FALSE)
      {
        throw new \ErrorException('File "'.\Nemiro\Server::MapPath('~/settings/update.sh').' not found.');
      }

      $sshClient = new SSH();
      $path = \Nemiro\Server::MapPath('~/');
      $scriptPath = \Nemiro\Server::MapPath('~/settings/update.sh');

      $command = 'sudo bash -c \'';
      $command .= 'updatePath="$(mktemp --dry-run /tmp/XXXXX.update.sh)"; ';
      $command .= 'cp '.$scriptPath.' \$updatePath && ';
      $command .= 'chmod +x \$updatePath && ';
      $command .= '\$updatePath "'.$path.'" && ';
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