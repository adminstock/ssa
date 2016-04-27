<?php
namespace Nemiro
{

  /*
   * Copyright © Aleksey Nemiro, 2015-2016. All rights reserved.
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

  /**
   * The class provides methods for writing information to the client console (via JavaScript).
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  © Aleksey Nemiro, 2015-2016. All rights reserved.
   */
  class Console
  {

    /**
     * Trace log, if debug mode is enabled.
     * 
     * @var \array
     */
    private static $LogItems = array();

    /**
     * Time log.
     * 
     * @var \array
     */
    private static $TimeItems = array();

    /**
     * Adds message to log.
     * 
     * @param \string $msg The message text.
     * @param mixed $arg The argument.
     * @param mixed $args The arguments.
     * 
     * @return void
     */
    public static function Log($msg, $arg = NULL, $args = NULL)
    {
      \Nemiro\Console::AddMessage('log', $msg, $arg, isset($args) ? array_slice(func_get_args(), 2) : NULL);
    }

    /**
     * Adds error message to log.
     * 
     * @param \string $msg The message text.
     * @param mixed $arg The argument.
     * @param mixed $args The arguments.
     * 
     * @return void
     */
    public static function Error($msg, $arg = NULL, $args = NULL)
    {
      \Nemiro\Console::AddMessage('error', $msg, $arg, isset($args) ? array_slice(func_get_args(), 2) : NULL);
    }

    /**
     * Adds info message to log.
     * 
     * @param \string $msg The message text.
     * @param mixed $arg The argument.
     * @param mixed $args The arguments.
     * 
     * @return void
     */
    public static function Info($msg, $arg = NULL, $args = NULL)
    {
      \Nemiro\Console::AddMessage('info', $msg, $arg, isset($args) ? array_slice(func_get_args(), 2) : NULL);
    }

    /**
     * Adds warning message to log.
     * 
     * @param \string $msg The message text.
     * @param mixed $arg The argument.
     * @param mixed $args The arguments.
     * 
     * @return void
     */
    public static function Warning($msg, $arg = NULL, $args = NULL)
    {
      \Nemiro\Console::AddMessage('warn', $msg, $arg, isset($args) ? array_slice(func_get_args(), 2) : NULL);
    }

    private static function AddMessage($type, $msg, $arg = NULL, $args = NULL)
    {
      if (!\Nemiro\App::$Debug) { return; }  
      \Nemiro\Console::$LogItems[] = array($type, $msg, $arg, $args);
    }

    /**
     * If the specified expression is false, the message is written to the log. 
     * 
     * @param \bool|callable $expression 
     * @param \string $msg The message text.
     * @param mixed $arg The argument.
     * @param mixed $args The arguments.
     * 
     * @return void
     */
    public static function Assert($expression, $msg, $arg = NULL, $args = NULL)
    {
      if (!\Nemiro\App::$Debug) { return; }
      if (is_callable($expression))
      {
        if ($expression() === FALSE)
        {
          \Nemiro\Console::Error($msg, $arg, $args);
        }
      }
      else
      {
        if ($expression === FALSE)
        {
          \Nemiro\Console::Error($msg, $arg, $args);
        }
      }
    }

    /**
     * Starts a new timer with an associated label.
     * When TimeEnd() is called with the same label, the timer is stopped the elapsed time is logged.
     * 
     * @param \string $label The timer label.
     * 
     * @return void
     */
    public static function Time($label)
    {
      if (!\Nemiro\App::$Debug) { return; }
      $mtime = microtime();
      $mtime = explode(' ', $mtime);
      \Nemiro\Console::$TimeItems[$label] = $mtime[1] + $mtime[0];
    }
    
    /**
     * Stops the timer with the specified label and logged the elapsed time.
     * 
     * @param \string $label The timer label.
     * 
     * @return \double|\int
     */
    public static function TimeEnd($label) 
    {
      if (!\Nemiro\App::$Debug) { return 0; }
      $mtime = microtime();
      $mtime = explode(' ', $mtime);
      $timeEnd = $mtime[1] + $mtime[0];
      $ts = \Nemiro\Console::$TimeItems[$label];
      $result = $timeEnd - $ts;

      \Nemiro\Console::Info($label, $result);

      return $result;
    }

    public static function ToScript()
    {
      if (!\Nemiro\App::$Debug) { return ''; }
      $log = '<script type="text/javascript">';

      for ($i = 0; $i < count(\Nemiro\Console::$LogItems); $i++)
      {
        $item = \Nemiro\Console::$LogItems[$i];

        $log .= 'console.'.$item[0].'(';

        if (isset($item[1]))
        {
          $log .= '"'.\Nemiro\Console::GetNormalizedMessage($item[1]).'"';
        }

        if (isset($item[2]))
        {
          if (isset($item[3]) && is_array($item[3]))
          {
            $item[3] = array_merge(array($item[2]), $item[3]);
          }
          else if (isset($item[3]) && !is_array($item[3]))
          {
            $item[3] = array($item[2], $item[3]);
          }
          else
          {
            $item[3] = array($item[2]);
          }
          
          $jc = count($item[3]);
          for ($j = 0; $j < $jc; $j++)
          {
            if ($j != $jc) $log .= ', ';
            if (gettype($item[3][$j]) == 'array' || gettype($item[3][$j]) == 'object')
            {
              $log .= '"'.\Nemiro\Console::GetNormalizedMessage(var_dump($item[3][$j])).'"';
            }
            else
            {
              $log .= '"'.\Nemiro\Console::GetNormalizedMessage($item[3][$j]).'"';
            }
          }
        }

        $log .= ');'."\n";
      }
      $log .= '</script>';

      return $log;
    }

    private static function GetNormalizedMessage($value)
    {
      return preg_replace('/[\r\n]+/', '\\n', addslashes($value));
    }


  }

}
?>