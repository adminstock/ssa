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
  \Nemiro\App::IncludeFile('~/monitoring/models/Process.php');
  \Nemiro\App::IncludeFile('~/monitoring/models/HDDInfo.php');
  \Nemiro\App::IncludeFile('~/monitoring/models/MemoryInfo.php');
  \Nemiro\App::IncludeFile('~/monitoring/models/ServerInfo.php');

  /**
   * Server monitoring.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Monitoring
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

    function GetInfo()
    {
      $shell_result = $this->SshClient->Execute
      ([
        // CPU
        'sudo mpstat -P ALL',
        // RAM
        'sudo cat /proc/meminfo | grep -E "MemTotal|MemFree|MemAvailable"',
        // HDD
        'sudo df' // -h
      ]);

      // MemFree: The sum of LowFree+HighFree
      // MemAvailable: An estimate of how much memory is available for starting new applications, without swapping.
      
      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      $result = new \Models\ServerInfo();

      #region CPU

      $skip = TRUE;

      $cpu = explode("\n", $shell_result[0]->Result);
      
      foreach ($cpu as $c)
      {
        if ($c == '' || $skip === TRUE)
        {
          $skip = (stripos($c, '%idle') === FALSE);
          continue;
        }

        $info = preg_split('/\s+/', $c);

        /*if (strtolower($info[1]) == 'all')
        {
          continue;
        }*/

        if (floatval(str_replace(',', '.', $info[11])) > 0)
        {
          $result->CPU[] = round(100 - floatval(str_replace(',', '.', $info[11])), 2);
        }
        else
        {
          $result->CPU[] = 0;
        }
      }

      #endregion
      #region RAM

      $ram = explode("\n", $shell_result[1]->Result);

      foreach ($ram as $item)
      {
        $info = preg_split('/\s+/', $item);
        $value = intval($info[1]);

        switch(strtolower($info[2]))
        {
          case 'kb':
            $value = $value * 1024;
            break;

          case 'mb';
            $value = $value * 1024 * 1024;
            break;

          case 'gb';
            $value = $value * 1024 * 1024 * 1024;
            break;

          case 'tb';
            $value = $value * 1024 * 1024 * 1024 * 1024;
            break;
        }

        if (stripos($info[0], 'MemTotal') !== FALSE)
        {
          $result->Memory->Total = $value;
        }
        else if (stripos($info[0], 'MemFree') !== FALSE)
        {
          $result->Memory->Free = $value;
        }
        else if (stripos($info[0], 'MemAvailable') !== FALSE)
        {
          $result->Memory->Available = $value;
        }
      }

      #endregion
      #region HDD

      $skip = TRUE;

      $hdd = explode("\n", $shell_result[2]->Result);
      
      foreach ($hdd as $item)
      {
        if ($skip === TRUE)
        {
          $skip = FALSE;
          continue;
        }

        $info = preg_split('/\s+/', $item);

        $h = new \Models\HDDInfo();

        $h->FileSystem = $info[0];
        $h->Available = intval($info[3]);
        $h->Total = intval($info[2]) + intval($info[3]);
        $h->Partition = $info[5];

        $result->HDD[] = $h;
      }

      #endregion

      return $result;
    }

    function GetProcesses($data)
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

      // ps aux
      // pid,fname,user,pcpu,pmem,vsz,rss,args,stime,etime
      // %p\t%c\t%U\t%C\t \t%z\t \t%a\t%x\t%t
      $shell_result = $this->SshClient->Execute
      (
        [
          'sudo ps -ww -eo pid:10,ppid:10,user:30,pcpu:10,pmem:10,vsz:20,rss:20,stime:20,etime:20,stat:5,fname'.($search != '' ? ' | grep -E "'.$search.'"' : '').'', // args:100,
          'sudo ps -ww -eo pid:10,args:1000' // Fix for arguments (on another it is impossible)
        ], TRUE
      );
      
      foreach($shell_result as $item)
      {
        if ($item->Error != '')
        {
          throw new \ErrorException($item->Error);
        }
      }

      $result = [];

      $processes = explode("\n", $shell_result[0]->Result);
      $processes_args = $shell_result[1]->Result;

      $skip = ($search == '');

      foreach($processes as $process)
      {
        if ($skip === TRUE)
        {
          $skip = FALSE;
          continue;
        }

        if ($process == '')
        {
          continue;
        }

        // pid:10,ppid:10,user:30,pcpu:10,pmem:10,vsz:20,rss:20,args:100,stime:20,etime:20,stat:5,fname
        $start = 0; $len = 10;
        $p = new \Models\Process();
        $p->PID = intval(trim(substr($process, $start = 0, $len = 10)));
        $p->PPID = intval(trim(substr($process, $start = ($start + $len + 1), $len = 10)));
        $p->Username = trim(substr($process, $start = ($start + $len + 1), $len = 30));
        $p->CPU = floatval(trim(substr($process, $start = ($start + $len + 1), $len = 10)));
        $p->Memory = floatval(trim(substr($process, $start = ($start + $len + 1), $len = 10)));
        $p->VSZ = intval(trim(substr($process, $start = ($start + $len + 1), $len = 20)));
        $p->RSS = intval(trim(substr($process, $start = ($start + $len + 1), $len = 20)));
        $p->StartTime = trim(substr($process, $start = ($start + $len + 1), $len = 20));
        $p->ElapsedTime = trim(substr($process, $start = ($start + $len + 1), $len = 20));
        $p->Status = trim(substr($process, $start = ($start + $len + 1), $len = 5));
        $p->Name = trim(substr($process, $start = ($start + $len + 1)));
        
        if ($search != '' && ($p->Command == 'grep -E "'.$search.'"' || $p->Command == 'grep -E '.$search))
        {
          continue;
        }

        // arguments
        if (preg_match('/^\s*'.trim(substr($process, $start = 0, $len = 10)).'(?<args>.+)$/m', $processes_args, $match) > 0)
        {
          $p->Command = trim($match['args']);
        }

        $result[] = $p;
      }

      return $result;
    }

    function KillProcess($data)
    {
      if (!isset($data['pid']) || intval($data['pid']) === 0)
      {
        throw new \ErrorException('PID is required.');
      }

      $signal = (isset($data['signal']) && $data['signal'] != '' ? ' -'.$data['signal'].' ' : ' ');

      $shell_result = $this->SshClient->Execute('sudo kill'.$signal.''.intval($data['pid']));
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE, 'Message' => $shell_result->Result];
    }

  }

}