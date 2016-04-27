<?php
namespace Nemiro\UI
{

  /*
   * Copyright © Aleksey Nemiro, 2007-2009. All rights reserved.
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
   * Represents a control.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2007-2009. All rights reserved.
   */
  class Control
  {

    public static $ProtectedFields = array('ProtectedFields', 'Source', 'ID', 'TagPrefix', 'TagName', 'Name', 'Body', 'Parent');

    /**
     * List the default value for the properties.
     * 
     * @var mixed
     */
    public $DefaultValues;

    /**
     * File path.
     * 
     * @var \string
     */
    public $Source;

    /**
     * The programmatic identifier assigned to the server control.
     * 
     * @var \string
     */
    public $ID;

    /**
     * The tag prefix of the control.
     * 
     * @var \string
     */
    public $TagPrefix;

    /**
     * The tag name of the control.
     * 
     * @var \string
     */
    public $TagName;

    /**
     * The control name (ID).
     * 
     * @var \string
     */
    public $Name;

    /**
     * The control body.
     * 
     * @var \string
     */
    public $Body;

    /**
     * The reference to patrent of the control.
     * 
     * @var mixed
     */
    public $Parent;
    
    function __construct($src, $tagPrefix, $tagName, $parent)
    {
      if (!isset($src) || $src === '') { throw new \InvalidArgumentException('Error. File path is required!'); }
      if (!isset($tagPrefix) || $tagPrefix === '') { throw new \InvalidArgumentException('Error. Tag prefix is required!'); }
      if (!isset($tagName) || $tagName === '') { throw new \InvalidArgumentException('Error. Tag name is required!'); }

      $this->Source = $src;
      $this->TagPrefix = $tagPrefix;
      $this->TagName = $tagName;
      $this->Name = $tagPrefix.":".$tagName;
      $this->Parent = $parent;

    }

    function Render()
    {
      $this->Load();

      $src_html = \Nemiro\Server::MapPath(substr_replace($this->Source, '.html.php', -4));

      if (file_exists($src_html))
      {
        $this->Body = $this->IncludeFile($src_html);
      }
      else
      {
        $this->Body = $this->IncludeFile($this->Source);
      }

      $this->LoadComplete();
    }

    function IncludeFile($path)
    {
      $path = \Nemiro\Server::MapPath($path);

      if (is_file($path))
      {
        ob_start();
        require($path);
        $r = ob_get_contents();
        ob_end_clean();
      }
      else
      {
        $r = '<span style="color: Red">Ошибка. Объект <strong>'.$path.'</strong> не является файлом или отсутствует.</span>';
      }
      return $r;
    }

    /**
     * Control loading handler.
     */
    function Load()
    { /* individually for each control */ }

    /**
     * Control loaded handler.
     */
    function LoadComplete()
    { /* individually for each control */ }  

    /**
     * Clears all fields of the specifed instance.
     * 
     * @param \Nemiro\UI\Control $instance 
     */
    public static function ResetInstance($instance)
    {
      if (($fields = get_class_vars(get_class($instance))) === FALSE)
      {
        return;
      }

      foreach ($fields as $name => $value) 
      {
        if (array_search($name, \Nemiro\UI\Control::$ProtectedFields) !== FALSE)
        {
          continue;
        }

        $typeName = gettype($instance->$name);

        if ($typeName == 'object')
        {
          $instance->$name = new $typeName();
        }
        else if ($typeName == 'array')
        {
          $instance->$name = array();
        }
        else
        {
          $instance->$name = $instance->DefaultValues[$name];
        }

        /*

        switch ($typeName)
        {
          case 'string':
            $instance->$name = '';
            break;

          case 'integer':
          case 'double':
          case 'float':
            $instance->$name = 0;
            break;

          case 'boolean':
            $instance->$name = false;
            break;

          case 'array':
            $instance->$name = array();
            break;

          case 'object"':
            $instance->$name = new $typeName();
            break;

          default:
            $instance->$name = NULL;
        }*/
      }
    }

  }

}
?>