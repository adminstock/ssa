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
        return ['NeedUpdate' => FALSE];
      }
    }

    public function Update($data)
    {
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

      $path = \Nemiro\Server::MapPath('~/');
      $top_path = dirname($path);
      // export files
      $command = 'svn export https://github.com/adminstock/ssa.git/trunk/SmallServerAdmin /tmp/ssa && ';
      // full backup
      $command .= 'tar -zcf /var/backups/ssa-webpanel-v'.$currentVersion.'.tar.gz '.$path.' && ';
      // backup ssa.config.php
      $command .= 'mv '.\Nemiro\Server::MapPath('~/ssa.config.php').' '.$top_path.'/ssa.config.backup.php && ';
      // remove old version
      $command .= 'rm -r '.$path.' && ';
      // set new version
      $command .= 'mv /tmp/ssa '.$path.' && ';
      // restore ssa.config.php
      $command .= 'rm '.\Nemiro\Server::MapPath('~/ssa.config.php').' && ';
      $command .= 'mv '.$top_path.'/ssa.config.backup.php '.\Nemiro\Server::MapPath('~/ssa.config.php');

      $shell_result = $this->SshClient->Execute($command);

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }

    #endregion

  }

}