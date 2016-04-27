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
  \Nemiro\App::IncludeFile('~/services/models/Service.php');

  /**
   * Servies management.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Services
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

    function GetList($data)
    {
      $search = (isset($data) && isset($data['search']) ? $data['search'] : '');
      
      if ($search != '')
      {
        $arr = preg_split('/[\,\;\|]+/', $search);
        $search = '';
        foreach($arr as $item)
        {
          if ($search != '') { $search .= '|'; }
          $search .= \Nemiro\Text::EscapeString(trim($item));
        }
      }

      $shell_result = $this->SshClient->Execute('sudo service --status-all'.($search != '' ? ' | grep -E "'.$search.'"' : ''));
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      $result = [];

      $services = explode("\n", $shell_result->Result);

      foreach($services as $service)
      {
        if ($service == '')
        {
          continue;
        }

        $m = NULL;
        preg_match('/\s*\[\s*(?<status>\+|\-|\?)+\s*\]\s*(?<name>.+)/', $service, $m);
        $name = trim($m['name']);

        if (trim($m['status']) == '+')
        {
          $status = 'Started';
        }
        else if (trim($m['status']) == '-')
        {
          $status = 'Stopped';
        }
        else
        {
          $status = 'Unknown';
        }

        $result[] = new \Models\Service($name, $status);
      }

      return $result;
    }
    
    function SetStatus($data)
    {
      if (!isset($data['Name']) || $data['Name'] == '')
      {
        throw new \ErrorException('Name is required! Value cannot be empty.');
      }

      if (!isset($data['NewStatus']) || $data['NewStatus'] == '')
      {
        throw new \ErrorException('New status is required! Value cannot be empty.');
      }

      $status = '';

      if (strtolower($data['NewStatus']) == 'stopped' || strtolower($data['NewStatus']) == 'stop')
      {
        $status = 'stop';
      }
      else if (strtolower($data['NewStatus']) == 'started' || strtolower($data['NewStatus']) == 'start')
      {
        $status = 'start';
      }
      else if (strtolower($data['NewStatus']) == 'reload')
      {
        $status = 'reload';
      }

      $name = $data['Name'];

      $shell_result = $this->SshClient->Execute('sudo service "'.$name.'" '.$status);
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }

  }

}