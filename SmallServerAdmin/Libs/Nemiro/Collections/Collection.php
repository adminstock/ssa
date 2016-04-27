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
   * Represents a universal collection.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   * @version     1.0 (2015-10-20) / PHP 5 >= 5.0
   */
  class Collection implements \ArrayAccess
  {

    #region fields

    /**
     * Gets or sets item array.
     * 
     * @var \array
     */
    public $Items;

    #endregion
    #region constructor

    public function __construct()
    {
      
    }

    #endregion
    #region public methods

    /**
     * Adds a new element to the collection.
     * 
     * @param mixed $item 
     */
    public function Add($item)
    {
      if ($this->Items == NULL) $this->Items = array();
      $this->Items[] = $item;
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
      return $this->Items != NULL ? count($this->Items) : 0;
    }

    /**
     * Determines whether any element of a sequence exists or satisfies a condition.
     * 
     * @param callable $predicate A function to test each element for a condition. 
     */
    public function Any($predicate = NULL)
    {
      if ($predicate != NULL && is_callable($predicate))
      {
        foreach($this->Items as $item)
        {
          if ($predicate($item) === TRUE)
          {
            return TRUE;
          }
        }

        return FALSE;
      }
      else
      {
        return $this->Count() > 0;
      }
    }

    /**
     * Returns the first element of a sequence, or a default value if no element is found.
     * 
     * @param \callable $predicate A function to test each element for a condition. 
     * @param mixed $default The default value. Default: NULL.
     */
    public function FirstOrDefault($predicate = NULL, $default = NULL)
    {
      if ($predicate != NULL && is_callable($predicate))
      {
        foreach($this->Items as $item)
        {
          if ($predicate($item) === TRUE)
          {
            return $item;
          }
        }

        return $default;
      }
      else
      {
        reset($this->Items);
        if (current($this->Items) === FALSE)
        {
          return $default;
        }
        else
        {
          return current($this->Items);
        }
      }
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