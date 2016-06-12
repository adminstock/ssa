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
  \Nemiro\App::IncludeFile('~/sites/models/Site.php');
  \Nemiro\App::IncludeFile('~/sites/models/SiteConf.php');
  \Nemiro\App::IncludeFile('~/sites/models/WebServer.php');
  \Nemiro\App::IncludeFile('~/files/models/FileSystemItem.php');

  /**
   * Web server management.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Sites
  {

    /**
     * SSH client.
     * 
     * @var SSH
     */
    private $SshClient = NULL;

    private $NginxAvailabesPath;
    private $NginxEnabledPath;

    private $ApacheAvailabesPath;
    private $ApacheEnabledPath;

    private $HtanAvailabesPath;
    private $HtanEnabledPath;
    private $HtanEnabled;

    function __construct()
    {
      global $config;

      $this->NginxAvailabesPath = $config['web_nginx_path'].(\Nemiro\Text::EndsWith($config['web_nginx_path'], '/') ? '' : '/').'sites-available/';
      $this->NginxEnabledPath = $config['web_nginx_path'].(\Nemiro\Text::EndsWith($config['web_nginx_path'], '/') ? '' : '/').'sites-enabled/';

      $this->ApacheAvailabesPath = $config['web_apache_path'].(\Nemiro\Text::EndsWith($config['web_apache_path'], '/') ? '' : '/').'sites-available/';
      $this->ApacheEnabledPath = $config['web_apache_path'].(\Nemiro\Text::EndsWith($config['web_apache_path'], '/') ? '' : '/').'sites-enabled/';

      $this->HtanEnabled = (bool)$config['web_htan_enabled'];
      $this->HtanAvailabesPath = $config['web_htan_path'].(\Nemiro\Text::EndsWith($config['web_htan_path'], '/') ? '' : '/').'app-available/';
      $this->HtanEnabledPath = $config['web_htan_path'].(\Nemiro\Text::EndsWith($config['web_htan_path'], '/') ? '' : '/').'app-enabled/';

      $this->SshClient = new SSH();
    }

    #region ..Sites..

    public function GetSites($data)
    {
      global $config;

      $search = (isset($data) && isset($data['search']) ? $data['search'] : '');

      $nginx = $apache = $htan = [];

      $nginx = $this->GetNginxSites($search);
      $apache = $this->GetApacheSites($search);
      $htan = $this->GetHtanApps($search);

      $result = $nginx;

      if ($result == NULL)
      {
        $result = [];
      }

      // merge with apache
      if ($apache != NULL)
      {
        foreach($apache as $apacheSite)
        {
          $hasSite = FALSE;

          // search by name
          for($i = 0; $i < count($result); $i++)
          {
            $site = $result[$i];

            if ($site->Name == $apacheSite->Name)
            {
              // add config info
              $result[$i]->Levels[] = \Models\WebServer::Apache;
              $result[$i]->Conf[] = $apacheSite->Conf[0]; // always one
              $hasSite = TRUE;
              break;
            }
          }

          if ($hasSite === TRUE)
          {
            continue;
          }

          /*if ($search != '' && stripos($apacheSite->Name, $search) === FALSE)
          {
            continue;
          }*/

          // is new site
          $result[] = $apacheSite;
        }
      }

      // merge with htan
      if ($htan != NULL)
      {
        foreach($htan as $htanApp)
        {
          $hasSite = FALSE;

          // search by name
          for($i = 0; $i < count($result); $i++)
          {
            $site = $result[$i];

            if ($site->Name == $htanApp->Name)
            {
              // add config info
              $result[$i]->Levels[] = \Models\WebServer::HTAN;
              $result[$i]->Conf[] = $htanApp->Conf[0]; // always one
              $hasSite = TRUE;
              break;
            }
          }

          if ($hasSite === TRUE)
          {
            continue;
          }

          // is new site
          $result[] = $htanApp;
        }
      }

      usort($result, function($a, $b) { return strcmp($a->Name, $b->Name); });

      return $result;
    }

    public function GetSite($data)
    {
      if (!isset($data['name']) || $data['name'] == '')
      {
        throw new \ErrorException('Name is required! Value cannot be empty.');
      }

      $name = $data['name'];

      global $config;

      //sudo bash -c "[[ -d /etc/apache2/sites-availabletc/apache2/sites-available/; (cat "svn.d2ebian2" || cat "svn.debian2.conf"))"
      $shell_result = $this->SshClient->Execute
      ([
        'sudo bash -c "[[ -d '.$this->ApacheAvailabesPath.' ]] && (cd '.$this->ApacheAvailabesPath.'; ([[ -f "'.$name.'" ]] && cat "'.$name.'" || ([[ -f "'.$name.'.conf" ]] && cat "'.$name.'.conf")))"',
        'sudo bash -c "[[ -d '.$this->ApacheEnabledPath.' ]] && (cd '.$this->ApacheEnabledPath.'; [[ -f "'.$name.'" || -f "'.$name.'.conf" ]] && echo "OK")"',
        'sudo bash -c "[[ -d '.$this->NginxAvailabesPath.' ]] && (cd '.$this->NginxAvailabesPath.'; ([[ -f "'.$name.'" ]] && cat "'.$name.'" || ([[ -f "'.$name.'.conf" ]] && cat "'.$name.'.conf")))"',
        'sudo bash -c "[[ -d '.$this->NginxEnabledPath.' ]] && (cd '.$this->NginxEnabledPath.'; [[ -f "'.$name.'" || -f "'.$name.'.conf" ]] && echo "OK")"',
        'sudo bash -c "[[ -d '.$this->HtanAvailabesPath.' ]] && (cd '.$this->HtanAvailabesPath.'; ([[ -f "'.$name.'" ]] && cat "'.$name.'" || ([[ -f "'.$name.'.conf" ]] && cat "'.$name.'.conf")))"',
        'sudo bash -c "[[ -d '.$this->HtanEnabledPath.' ]] && (cd '.$this->HtanEnabledPath.'; [[ -f "'.$name.'" || -f "'.$name.'.conf" ]] && echo "OK")"'
      ]);

      // errors
      if ($shell_result[0]->Error != '')
      {
        throw new \ErrorException($shell_result[0]->Error);
      }

      if ($shell_result[2]->Error != '')
      {
        throw new \ErrorException($shell_result[2]->Error);
      }

      $result = new \Models\Site();
      $result->Conf = [];
      $result->Name = basename($name, '.conf');

      // parse
      if ($shell_result[2]->Result != '')
      {
        $result->Levels[] = 'Nginx';
        $result->Conf[] = new \Models\SiteConf('Nginx', $shell_result[2]->Result, $shell_result[3]->Result == 'OK');
        $result->IsEnabled = ($shell_result[3]->Result == 'OK');
      }

      if ($shell_result[0]->Result != '')
      {
        $result->Levels[] = 'Apache';
        $result->Conf[] = new \Models\SiteConf('Apache', $shell_result[0]->Result, $shell_result[1]->Result == 'OK');
        //$result->IsEnabled = ($result->IsEnabled && $shell_result[1]->Result == 'OK');
      }

      if ($shell_result[4]->Result != '')
      {
        $result->Levels[] = 'HTAN';
        $result->Conf[] = new \Models\SiteConf('HTAN', $shell_result[4]->Result, $shell_result[5]->Result == 'OK');
        // $result->IsEnabled = ($result->IsEnabled && $shell_result[5]->Result == 'OK');
      }

      return $result;
    }
    
    public function SaveSite($data)
    {
      global $config;

      if (!isset($data['Site']))
      {
        throw new \ErrorException('Site data is required!');
      }

      if (!isset($data['Site']['Name']) || trim($data['Site']['Name']) == '')
      {
        throw new \ErrorException('Site name is required! Value cannot be empty.');
      }

      if (!isset($data['Site']['Conf']) || !is_array($data['Site']['Conf']) || count($data['Site']['Conf']) <= 0)
      {
        throw new \ErrorException('Site config is required! Value cannot be empty.');
      }

      if (preg_match('/'.$config["web_sitename_pattern"].'/', $data['Site']['Name']) !== 1)
      {
        throw new \ErrorException('Invalid site name.'.(isset($config["web_sitename_invalid_message"]) ? "\n\n".$config["web_sitename_invalid_message"] : ''));
      }

      if (!(bool)$data['IsNew'] && (!isset($data['SourceName']) || $data['SourceName'] == ''))
      {
        throw new \ErrorException('SourceName is required! Value cannot be empty.');
      }

      $name = $data['Site']['Name'];

      // check name
      if ((bool)$data['IsNew'] === TRUE || (!(bool)$data['IsNew'] && $data['SourceName'] != $name))
      {
        $shell_result = $this->SshClient->Execute('sudo bash -c "[[ -f \''.$this->NginxAvailabesPath.$name.'\' || -f \''.$this->NginxAvailabesPath.$name.'.conf\' || -f \''.$this->ApacheAvailabesPath.$name.'\' || -f \''.$this->ApacheAvailabesPath.$name.'.conf\' ]] && echo \'TRUE\' || echo \'FALSE\'"');

        if ($shell_result->Error != '')
        {
          throw new \ErrorException($shell_result->Error);
        }
        
        if ($shell_result->Result == 'TRUE')
        {
          throw new \ErrorException('Site "'.$name.'" already exists. Please enter other name.');
        }
      }

      // save
      $nginxEnabled = FALSE; $apacheEnabled = FALSE; $htanEnabled = FALSE;
      $hasNginx = FALSE; $hasApache = FALSE; $hasHtan = FALSE;

      foreach ($data['Site']['Conf'] as $item)
      {
        $item['Source'] = preg_replace('/((\r\n)|(\r))+/', "\n", $item['Source']);
        $item['Source'] = str_replace('\\', '\\\\', $item['Source']);
        $item['Source'] = str_replace('\'', '\'\\\'\'', $item['Source']);
        $item['Source'] = str_replace('`', '\\`', $item['Source']);
        $item['Source'] = str_replace('$', '\$', $item['Source']);
        //$item['Source'] = str_replace('EOF', '\\EOF', $item['Source']);

        $path = '';

        if (strtolower($item['Level']) == 'nginx')
        {
          $hasNginx = TRUE;
          $nginxEnabled = (bool)$item['Enabled'] && (bool)$data['Site']['IsEnabled'];
          $path = $this->NginxAvailabesPath;
        }
        else if (strtolower($item['Level']) == 'apache')
        {
          $hasApache = TRUE;
          $apacheEnabled = (bool)$item['Enabled'];
          $path = $this->ApacheAvailabesPath;
        }
        else if (strtolower($item['Level']) == 'htan')
        {
          $hasHtan = TRUE;
          $htanEnabled = (bool)$item['Enabled'];
          $path = $this->HtanAvailabesPath;
        }

        $shell_result = $this->SshClient->Execute
        ([
          "sudo bash -c 'cat <<\EOF > ".$path.$name.".conf\n".$item['Source']."\nEOF'",
          "sudo bash -c '[[ -f \"".$path.$name."\" ]] && rm \"".$path.$name."\"'"
        ]);
        
        if ($shell_result[0]->Error != '')
        {
          throw new \ErrorException($shell_result[0]->Error);
        }
      }

      // delete unused config

      $configList = 
      [
        'Nginx' => [$hasNginx, $this->NginxEnabledPath, $this->NginxAvailabesPath],
        'Apache' => [$hasApache, $this->ApacheEnabledPath, $this->ApacheAvailabesPath],
        'HTAN' => [$hasHtan, $this->HtanEnabledPath, $this->HtanAvailabesPath]
      ];

      foreach($configList as $k => $v)
      {
        if ($v[0] === TRUE) 
        {
          continue;
        }

        $shell_result = $this->SshClient->Execute
        (
          'sudo bash -c "'.
          '([[ -f \''.$v[1].$name.'\' ]] && rm \''.$v[1].$name.'\' || '.
          '([[ -f \''.$v[1].$name.'.conf\' ]] && rm \''.$v[1].$name.'.conf\'));'.
          '([[ -f \''.$v[2].$name.'\' ]] && rm \''.$v[2].$name.'\' || '.
          '([[ -f \''.$v[2].$name.'.conf\' ]] && rm \''.$v[2].$name.'.conf\'))'.
          '"'
        );

        if ($shell_result->Error != '')
        {
          throw new \ErrorException($k.': '.$shell_result->Error);
        }
      }

      // set status
      $this->SetSiteStatus(['Name' => $name, 'IsEnabled' => $nginxEnabled, 'Level' => 'Nginx']);
      $this->SetSiteStatus(['Name' => $name, 'IsEnabled' => $apacheEnabled, 'Level' => 'Apache']);
      $this->SetSiteStatus(['Name' => $name, 'IsEnabled' => $htanEnabled, 'Level' => 'HTAN']);

      // enable/disable
      // $this->SetSiteStatus(['Name' => $name, 'IsEnabled' => (bool)$data['Site']['IsEnabled']]);

      // delete old
      if (!(bool)$data['IsNew'] && $data['SourceName'] != $name)
      {
        $this->DeleteSite(['Name' => $data['SourceName']]);
      }

      // commit changes
      $this->CommitChanges();

      return ['Success' => TRUE];
    }

    public function SetSiteStatus($data)
    {
      if (!isset($data['Name']) || trim($data['Name']) == '')
      {
        throw new \ErrorException('Site name is required! Value cannot be empty.');
      }

      if (!isset($data['IsEnabled']))
      {
        throw new \ErrorException('IsEnabled property is required! Value cannot be empty.');
      }

      $name = $data['Name'];
      $commands = [];
      $conf = [];

      // enable/disable
      if ((bool)$data['IsEnabled'] === TRUE)
      {
        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'nginx')
        {
          $conf[] = ['Level' => 'Nginx', 'Enabled' => TRUE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->NginxAvailabesPath.$name.'.conf\' && ! -f \''.$this->NginxEnabledPath.$name.'.conf\' ]] && '.
                        'ln -s \''.$this->NginxAvailabesPath.$name.'.conf\' \''.$this->NginxEnabledPath.'\''.
                        '"';
        }

        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'apache')
        {
          $conf[] = ['Level' => 'Apache', 'Enabled' => TRUE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->ApacheAvailabesPath.$name.'.conf\' ]] && a2ensite \''.$name.'\''.
                        '"';
        }

        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'htan')
        {
          $conf[] = ['Level' => 'HTAN', 'Enabled' => TRUE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->HtanAvailabesPath.$name.'.conf\' && ! -f \''.$this->HtanEnabledPath.$name.'.conf\' ]] && '.
                        'ln -s \''.$this->HtanAvailabesPath.$name.'.conf\' \''.$this->HtanEnabledPath.'\''.
                        '"';
        }
      }
      else
      {
        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'nginx')
        {
          $conf[] = ['Level' => 'Nginx', 'Enabled' => FALSE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->NginxAvailabesPath.$name.'.conf\' && -f \''.$this->NginxEnabledPath.$name.'.conf\' ]] && '.
                        'rm \''.$this->NginxEnabledPath.$name.'.conf\''.
                        '"';
        }

        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'apache')
        {
          $conf[] = ['Level' => 'Apache', 'Enabled' => FALSE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->ApacheAvailabesPath.$name.'.conf\' ]] && a2dissite \''.$name.'\''.
                        '"';
        }

        if (!isset($data['Level']) || $data['Level'] == '' || strtolower($data['Level']) == 'all' || strtolower($data['Level']) == 'htan')
        {
          $conf[] = ['Level' => 'HTAN', 'Enabled' => FALSE];
          $commands[] = 'sudo bash -c "'.
                        '[[ -f \''.$this->HtanAvailabesPath.$name.'.conf\' && -f \''.$this->HtanEnabledPath.$name.'.conf\' ]] && '.
                        'rm \''.$this->HtanEnabledPath.$name.'.conf\''.
                        '"';
        }
      }

      $shell_result = $this->SshClient->Execute($commands);

      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      return ['Success' => TRUE, 'Status' => $conf];
    }

    public function DeleteSite($data)
    {
      if (!isset($data['Name']) || trim($data['Name']) == '')
      {
        throw new \ErrorException('Site name is required! Value cannot be empty.');
      }

      $name = $data['Name'];

      // delete
      $shell_result = $this->SshClient->Execute
      ([
        'sudo bash -c "'.
        '([[ -f \''.$this->NginxEnabledPath.$name.'\' ]] && rm \''.$this->NginxEnabledPath.$name.'\' || '.
        '([[ -f \''.$this->NginxEnabledPath.$name.'.conf\' ]] && rm \''.$this->NginxEnabledPath.$name.'.conf\'));'.
        '([[ -f \''.$this->NginxAvailabesPath.$name.'\' ]] && rm \''.$this->NginxAvailabesPath.$name.'\' || '.
        '([[ -f \''.$this->NginxAvailabesPath.$name.'.conf\' ]] && rm \''.$this->NginxAvailabesPath.$name.'.conf\'))'.
        '"',
        'sudo bash -c "'.
        '([[ -f \''.$this->ApacheEnabledPath.$name.'\' ]] && rm \''.$this->ApacheEnabledPath.$name.'\' || '.
        '([[ -f \''.$this->ApacheEnabledPath.$name.'.conf\' ]] && rm \''.$this->ApacheEnabledPath.$name.'.conf\')); '.
        '([[ -f \''.$this->ApacheAvailabesPath.$name.'\' ]] && rm \''.$this->ApacheAvailabesPath.$name.'\' || '.
        '([[ -f \''.$this->ApacheAvailabesPath.$name.'.conf\' ]] && rm \''.$this->ApacheAvailabesPath.$name.'.conf\'))'.
        '"',
        'sudo bash -c "'.
        '([[ -f \''.$this->HtanEnabledPath.$name.'\' ]] && rm \''.$this->HtanEnabledPath.$name.'\' || '.
        '([[ -f \''.$this->HtanEnabledPath.$name.'.conf\' ]] && rm \''.$this->HtanEnabledPath.$name.'.conf\'));'.
        '([[ -f \''.$this->HtanAvailabesPath.$name.'\' ]] && rm \''.$this->HtanAvailabesPath.$name.'\' || '.
        '([[ -f \''.$this->HtanAvailabesPath.$name.'.conf\' ]] && rm \''.$this->HtanAvailabesPath.$name.'.conf\'))'.
        '"'
      ]);

      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      $this->CommitChanges();

      return ['Success' => TRUE];
    }

    #endregion
    #region ..Folders..

    public function GetFolders($data)
    {
      $path = $this->NormalizaPath($data['path']);

      $shell_result = $this->SshClient->Execute('sudo bash -c "cd \''.$path.'\'; find * -maxdepth 0 -type d 2> /dev/null"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      $result = [];

      $items = explode("\n", $shell_result->Result);

      if ($path == '/') { $path = ''; }

      foreach($items as $item)
      {
        if ($item == '' || $item=='.')
        {
          continue;
        }

        $result[] = new \Models\FileSystemItem('Folder', trim($item, '/'), $path.'/'.trim($item, '/'));
      }

      return $result;
    }

    public function CreateFolder($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      if (!isset($data['name']) || $data['name'] == '')
      {
        throw new \ErrorException('Folder name is required.');
      }

      $path = $this->NormalizaPath($data['path']);
      $name = $data['name']; //$this->EscapePath($data['name']);
      $owner = $data['owner'];

      $shell_result = $this->SshClient->Execute('sudo bash -c "cd \''.$path.'\'; mkdir \''.$name.'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      if (isset($owner) && $owner != '')
      {
        $shell_result = $this->SshClient->Execute('sudo bash -c "cd \''.$path.'\'; chown \''.$owner.'\' \''.$name.'\'"');

        if ($shell_result->Error != '')
        {
          throw new \ErrorException($shell_result->Error);
        }
      }

      if ($path == '/') { $path = ''; }

      return [ 'Path' => $path.'/'.$name ];
    }

    public function RenameFolder($data)
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

      $path = $this->NormalizaPath($data['path']);
      $newName = dirname($path).'/'.$data['name'];

      $shell_result = $this->SshClient->Execute('sudo bash -c "[[ -d \''.$newName.'\' ]] && echo \'true\' || echo \'false\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      if ($shell_result->Result == 'true')
      {
        throw new \ErrorException('Folder "'.$newName.'" already exists. Please enter other name.');
      }

      $shell_result = $this->SshClient->Execute('sudo bash -c "mv \''.$path.'\' \''.$newName.'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return [ 'Path' => $newName ];
    }
    
    public function DeleteFolder($data)
    {
      if (!isset($data['path']) || $data['path'] == '')
      {
        throw new \ErrorException('Path is required.');
      }

      $path = $this->NormalizaPath($data['path']);

      $shell_result = $this->SshClient->Execute('sudo bash -c "rm --force --dir \''.$path.'\'"');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }

    public function ReloadServices($data)
    {
      $command = '';

      if (!isset($data) || count($data) <= 0 || !isset($data['services']) || $data['services'] == '' || strtolower($data['services']) == 'all')
      {
        $data = ['services' => 'nginx,apache2,php5-fpm,php7-fpm,htan-runner'];
      }

      $services = explode(',', $data['services']);

      foreach($services as $service)
      {
        $service = strtolower(trim($service));

        if ($command != '')
        {
          $command .= '; ';
        }

        // crate command
        if ($service == 'nginx')
        {
          $command .= '(dpkg-query -s "nginx" 2> /dev/null | grep -q "ok installed" && sudo nginx -t && sudo service nginx reload)';
        }
        else if ($service == 'apache2' || $service == 'apache')
        {
          $command .= '(dpkg-query -s "apache2" 2> /dev/null | grep -q "ok installed" && sudo apachectl -t && sudo service apache2 reload)';
        }
        else if ($service == 'htan-runner' || $service == 'htan')
        {
          $command .= '([[ -f "/etc/init.d/htan-runner" ]] && sudo service htan-runner reload)';
        }
        else if ($service == 'php-fpm') // all php-fpm
        {
          $command .= '(dpkg-query -s "php5-fpm" 2> /dev/null | grep -q "ok installed" && sudo service php5-fpm reload); ';
          $command .= '(dpkg-query -s "php7-fpm" 2> /dev/null | grep -q "ok installed" && sudo service php7-fpm reload)';
        }
        else
        {
          $command .= '(dpkg-query -s "'.$service.'" 2> /dev/null | grep -q "ok installed" && sudo service '.$service.' reload)';
        }
      }

      $shell_result = $this->SshClient->Execute($command);

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }
    }

    #endregion
    #region ..Private methods..
    
    /**
     * Returns list of Apache sites.
     * 
     * @param \string $search The search string (optional).
     * @throws \ErrorException 
     * @return \Models\Site[]
     */
    private function GetApacheSites($search)
    {
      if (!isset($search) || $search == '')
      {
        $search = '*';
      }
      else
      {
        $search = '*'.$search.'*';
      }

      $shell_result = $this->SshClient->Execute
      ([
        'sudo bash -c "[[ -d '.$this->ApacheAvailabesPath.' ]] && find '.$this->ApacheAvailabesPath.' -iname \''.$search.'\' -type f -maxdepth 1"',
        'sudo bash -c "[[ -d '.$this->ApacheEnabledPath.' ]] && find '.$this->ApacheEnabledPath.' -iname \''.$search.'\' -type f -maxdepth 1"'
      ]);
      
      if ($shell_result[0]->Error != '')
      {
        throw new \ErrorException($shell_result[0]->Error);
      }

      if ($shell_result[1]->Error != '')
      {
        throw new \ErrorException($shell_result[1]->Error);
      }

      $result = [];

      $all = explode("\n", $shell_result[0]->Result);
      $enabled = explode("\n", $shell_result[1]->Result);

      foreach($all as $site)
      {
        if ($site == '') { continue; }

        $siteConf = basename($site);

        $s = new \Models\Site();
        $s->Name = basename($site, '.conf');
        $s->Levels[] = \Models\WebServer::Apache;
        $s->IsEnabled = count(array_filter($enabled, function($item) use ($siteConf) {
          return basename($item) == $siteConf;
        })) > 0;

        $s->Conf[] = new \Models\SiteConf(\Models\WebServer::Apache, NULL, $s->IsEnabled);

        $result[] = $s;
      }

      return $result;
    }

    /**
     * Returns list of Nginx sites.
     * 
     * @param \string $search The search string (optional).
     * @throws \ErrorException 
     * @return \Models\Site[]
     */
    private function GetNginxSites($search)
    {
      if (!isset($search) || $search == '')
      {
        $search = '*';
      }
      else
      {
        $search = '*'.$search.'*';
      }

      $shell_result = $this->SshClient->Execute
      ([
        'sudo bash -c "[[ -d '.$this->NginxAvailabesPath.' ]] && find '.$this->NginxAvailabesPath.' -iname \''.$search.'\' -type f -maxdepth 1"',
        'sudo bash -c "[[ -d '.$this->NginxEnabledPath.' ]] && find '.$this->NginxEnabledPath.' -iname \''.$search.'\' -type f -maxdepth 1"'
      ]);
      
      if ($shell_result[0]->Error != '')
      {
        throw new \ErrorException($shell_result[0]->Error);
      }

      if ($shell_result[1]->Error != '')
      {
        throw new \ErrorException($shell_result[1]->Error);
      }

      $result = [];

      $all = explode("\n", $shell_result[0]->Result);
      $enabled = explode("\n", $shell_result[1]->Result);

      foreach($all as $site)
      {
        if ($site == '') { continue; }

        $siteConf = basename($site);

        $s = new \Models\Site();
        $s->Name = basename($site, '.conf');
        $s->Levels[] = \Models\WebServer::Nginx;
        $s->IsEnabled = count(array_filter($enabled, function($item) use ($siteConf) {
          return basename($item) == $siteConf;
        })) > 0;

        $s->Conf[] = new \Models\SiteConf(\Models\WebServer::Nginx, NULL, $s->IsEnabled);

        $result[] = $s;
      }

      return $result;
    }

    /**
     * Returns list of HTAN applications.
     * 
     * @param \string $search The search string (optional).
     * @throws \ErrorException 
     * @return \Models\Site[]
     */
    private function GetHtanApps($search)
    {
      global $config;

      if (!isset($search) || $search == '')
      {
        $search = '*';
      }
      else
      {
        $search = '*'.$search.'*';
      }

      $shell_result = $this->SshClient->Execute
      ([
        'sudo bash -c "[[ -d '.$this->HtanAvailabesPath.' ]] && find '.$this->HtanAvailabesPath.' -iname \''.$search.'\' -type f -maxdepth 1"',
        'sudo bash -c "[[ -d '.$this->HtanEnabledPath.' ]] && find '.$this->HtanEnabledPath.' -iname \''.$search.'\' -type f -maxdepth 1"'
      ]);
      
      if ($shell_result[0]->Error != '')
      {
        throw new \ErrorException($shell_result[0]->Error);
      }

      if ($shell_result[1]->Error != '')
      {
        throw new \ErrorException($shell_result[1]->Error);
      }

      $result = [];

      $all = explode("\n", $shell_result[0]->Result);
      $enabled = explode("\n", $shell_result[1]->Result);

      foreach($all as $site)
      {
        if ($site == '') { continue; }

        $siteConf = basename($site);

        $s = new \Models\Site();
        $s->Name = basename($site, '.conf');
        $s->Levels[] = \Models\WebServer::HTAN;
        $s->IsEnabled = count(array_filter($enabled, function($item) use ($siteConf) {
          return basename($item) == $siteConf;
        })) > 0;

        $s->Conf[] = new \Models\SiteConf(\Models\WebServer::HTAN, NULL, $s->IsEnabled);

        $result[] = $s;
      }

      return $result;
    }

    private function NormalizaPath($path)
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

      return $path; //$this->EscapePath($path);
    }

    /*private function EscapePath($path)
    {
      return str_replace(' ', '\ ', $path);
    }*/

    private function CommitChanges()
    {
      global $config;

      $shell_result = $this->SshClient->Execute
      (
        'sudo bash -c "dpkg-query -s \'etckeeper\' 2> /dev/null | grep -q \'ok installed\' && '.
         '(( $(sudo git -C /etc status --porcelain | wc -l) > 0 )) && '.
         '(cd /etc; etckeeper commit \'Auto commit SSA v'.file_get_contents(\Nemiro\Server::MapPath('~/.version')).'\')'.
        '"'
      );
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return TRUE;
    }

    #endregion

  }

}