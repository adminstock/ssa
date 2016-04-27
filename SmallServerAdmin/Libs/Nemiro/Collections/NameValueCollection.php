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
   * Represents a collection of associated String keys and String values that can be accessed either with the key or with the index.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   * @version     1.0 (2015-10-26) / PHP 5 >= 5.0
   */
  class NameValueCollection implements \ArrayAccess
  {

    #region fields

    /**
     * The entries list.
     * 
     * @var \string[]|StringValue[]
     */
    private $Items;

    #endregion
    #region constructor

    public function __construct()
    {
      $this->Items = array();
    }

    #endregion
    #region public methods
    
    /**
     * Adds an entry with the specified name and value to the collection.
     * 
     * @param \string $key 
     * @param \string $value 
     */
    public function Add($key, $value)
    {
      if (!isset($this->Items[$key]))
      {
        $this->Items[$key] = $value;
      }
      else
      {
        if (is_object($this->Items[$key]) && get_class($this->Items[$key]) == 'Nemiro\Collections\StringValue')
        {
          $this->Items[$key]->Add($value);
        }
        else
        {
          $this->Items[$key] = new StringValue(array($this->Items[$key], $value), ',');
        }
      }
    }

    /**
     * Clears the collection.
     */
    public function Clear()
    {
      unset($this->Items);
      $this->Items = array();
    }

    /**
     * Returns elements count.
     * 
     * @return \int
     */
    public function Count()
    {
      return count($this->Items);
    }

    #endregion
    #region ArrayAccess Members

    /**
     * Whether a offset exists
     * Whether or not an offset exists.
     *
     * @param mixed $offset An offset to check for.
     *
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
     *
     * @return mixed
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
     *
     * @return void
     */
    function offsetSet($offset, $value)
    {
      if (is_null($offset)) 
      {
        $this->Items[] = $value;
      } 
      else 
      {
        $this->Items[$offset] = $value;
      }
    }

    /**
     * Offset to unset
     * Unsets an offset.
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    function offsetUnset($offset)
    {
      unset($this->Items[$offset]);
    }

    #endregion

  }

}
?>