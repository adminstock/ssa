<?php
namespace Nemiro\Collections
{

  /*
   * Copyright © Aleksey Nemiro, 2015. All rights reserved.
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
   * Represents a string value that can contain an array of data.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   */
  class StringValue
  {

    /**
     * The value.
     * 
     * @var \string|\string[]
     */
    private $Value;

    /**
     * The glue for string array.
     * 
     * @var \string
     */
    private $Glue;

    /**
     * Initializes a new instance of the class with the specified parameters.
     * 
     * @param \string|\string[] $value The value.
     * @param string $glue The glue for string array. Default space.
     */
    function __construct($value, $glue = ' ')
    {
      $this->Value = array();
      $this->Glue = $glue;
      $this->Add($value);
    }

    public function __toString()
    {
      return implode($this->Glue, $this->Value);
    }

    /**
     * Adds string to value.
     * 
     * @param string $value The string to add.
     */
    public function Add($value)
    {
      if (is_array($value))
      {
        $this->Value = array_merge($this->Value, $value);
      }
      else
      {
        $this->Value[] = $value;
      }
    }

  }

}
?>