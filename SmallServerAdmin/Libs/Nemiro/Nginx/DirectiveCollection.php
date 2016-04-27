<?php
namespace Nemiro\Nginx
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
   * Represents Nginx directive collection.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   */
  class DirectiveCollection implements \ArrayAccess
  {
    
    /**
     * The list of directives.
     * 
     * @var Directive[]|DirectiveGroup[]
     */
    public $Items;

    function __construct()
    {
      $this->Items = array();
    }

    /**
     * Determines whether the DirectiveCollection contains the specified directive name.
     * 
     * @param \string $name The key to locate in the DirectiveCollection.
     * @return \bool
     */
    public function ContainsDirective($name)
    {
      if (is_null($name) || (gettype($name) != 'string' && gettype($name) != 'integer') || (string)$name == '')
      {
        throw new \InvalidArgumentException('Name is required. The name must be a string. Value can not be null or empty.');
      }

      return array_key_exists($name, $this->Items);
    }

    /**
     * Adds a new directive or group to the collection.
     * 
     * @param Directive|DirectiveGroup[] $item The directive or group to add.
     * @return Directive|DirectiveGroup
     */
    public function Add($item)
    {
      if ($this->ContainsDirective($item->Name))
      {
        throw new \ErrorException(sprintf('Directive `%s` already exists.', $item->Name));
      }

      $this->Items[$item->Name] = $item;

      return end($this->Items);
    }

    /**
     * Returns elements count.
     * 
     * @return \int
     */
    public function Count()
    {
      return $this->Items != NULL ? count($this->Items) : 0;
    }

    /**
     * Returns the first directive of the collection.
     * 
     * @return Directive|DirectiveGroup
     */
    public function First()
    {
      reset($this->Items);
      return current($this->Items);
    }

    /**
     * Returns the last directive of the collection.
     * 
     * @return Directive|DirectiveGroup
     */
    public function Last()
    {
      return end($this->Items);
    }

    #region ArrayAccess Members

    /**
     * Whether a offset exists
     * Whether or not an offset exists.
     *
     * @param \string|\int $offset An offset to check for.
     * @return \bool
     */
    function offsetExists($offset)
    {
      return isset($this->Items[$offset]);
    }

    /**
     * Offset to retrieve
     * Returns the value at specified offset.
     *
     * @param mixed $offset The offset to retrieve.
     * @return Directive|DirectiveGroup
     */
    function offsetGet($offset)
    {
      return isset($this->Items[$offset]) ? $this->Items[$offset] : NULL;
    }

    /**
     * Offset to set
     * Assigns a value to the specified offset.
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    function offsetSet($offset, $value)
    {
      $this->Items[$offset] = $value;
    }

    /**
     * Offset to unset
     * Unsets an offset.
     *
     * @param mixed $offset The offset to unset.
     * @return void
     */
    function offsetUnset($offset)
    {
      unset($this->Items[$offset]);
    }

    #endregion

  }

}