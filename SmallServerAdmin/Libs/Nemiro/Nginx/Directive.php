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
   * Represents Nginx directive.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   */
  class Directive implements \ArrayAccess
  {
    
    /**
     * The directive name.
     * 
     * @var \string
     */
    public $Name;

    /**
     * The directive parameters.
     * 
     * @var \string[]
     */
    public $Parameters;

    /**
     * The list of directives.
     * 
     * @var DirectiveCollection
     */
    public $Directives;

    /**
     * Initializes a new instance of the class with the specified parameters.
     * 
     * @param \string $name The directive name.
     * @param \string|\string[] $parameters The list of parameters. Default: NULL.
     * @param DirectiveCollection $childs The collection of children. Default: NULL.
     */
    function __construct($name, $parameters = NULL, $childs = NULL)
    {
      if (is_null($name) || (gettype($name) != 'string' && gettype($name) != 'integer') || (string)$name == '')
      {
        throw new \InvalidArgumentException('Name is required. The name must be a string. Value can not be null or empty.');
      }
      if (!is_null($childs) && gettype($childs) != 'object' && get_class($childs) != 'Nemiro\Nginx\DirectiveCollection')
      {
        throw new \InvalidArgumentException('The parameter $childs expected values of the Nemiro\Nginx\DirectiveCollection type.');
      }

      $this->Name = $name;
      $this->Directives = ($childs != NULL ? $childs : new DirectiveCollection());

      if (!is_null($parameters))
      {
        $this->Parameters = array();
        if (is_array($parameters))
        {
          $this->Parameters = $parameters;
        }
        else
        {
          $this->Parameters[] = $parameters;
        }
      }
    }

    /**
     * Returns a string of parameters separated by a space.
     * 
     * @return \null|\string
     */
    public function ParametersAsString()
    {
      if (isset($this->Parameters) && count($this->Parameters) > 0)
      {
        // return implode(' ', $this->Parameters);
        $result = '';
        foreach ($this->Parameters as $parameter)
        {
          if ($parameter == '') continue;
          if (preg_match('/[\s\{\}\#\;]+/', $parameter) === 1)
          {
            $parameter = '"'.$parameter.'"';
          }
          if ($result != '') $result .= ' ';
          $result .= $parameter;
        }
        return $result;
      }
      else
      {
        return NULL;
      }
    }

    /**
     * Adds new parameter to the parameter collection of the instance.
     * 
     * @param \object|\array $value The value to add.
     * @return void
     */
    public function AddParameter($value)
    {
      if (!isset($this->Parameters) || $this->Parameters == NULL)
      {
        $this->Parameters = array();
      }

      if (is_array($value))
      {
        array_merge($this->Parameters, $value);
      }
      else
      {
        $this->Parameters[] = $value;
      }
    }

    /**
     * Adds a new directive to the directive collection of the instance.
     * 
     * @param \string|Directive|DirectiveGroup $nameOrInstance The directive name or an instance of the Directive or DirectiveGroup.
     * @param \string|\string[] $parameters The list of parameters. If it the $nameOrInstance specified instance, and this parameter is not NULL, it will use this value.
     * @param DirectiveCollection $childs The collection of children. If it the $nameOrInstance specified instance, and this parameter is not NULL, it will use this value.
     * @return Directive|DirectiveGroup
     */
    public function AddDirective($nameOrInstance, $parameters = NULL, $childs = NULL)
    {
      if (!is_null($childs) && gettype($childs) != 'object' && get_class($childs) != 'Nemiro\Nginx\DirectiveCollection')
      {
        throw new \InvalidArgumentException('The parameter $childs expected values of the Nemiro\Nginx\DirectiveCollection type.');
      }

      $is_group = FALSE;

      if (gettype($nameOrInstance) == 'object' && (($class = get_class($nameOrInstance)) == 'Nemiro\Nginx\Directive' || $class == 'Nemiro\Nginx\DirectiveGroup'))
      {
        // is directive instance
        if ($parameters == NULL)
        {
          $parameters = $nameOrInstance->Parameters;
        }

        if ($childs == NULL)
        {
          $childs = $nameOrInstance->Directives;
        }

        $is_group = $nameOrInstance->IsGroup();

        $nameOrInstance = $nameOrInstance->Name;
      }
      
      if ($this->ContainsChild($nameOrInstance))
      {
        // is not unique derictive name and not group, transform to group
        if (!$this->Directives[$nameOrInstance]->IsGroup())
        {
          $group = new DirectiveGroup($nameOrInstance);
          $group->AddDirective($this->Directives[$nameOrInstance]);
          $group->AddDirective(new Directive($nameOrInstance, $parameters, $childs));
          $this->Directives[$nameOrInstance] = $group;
        }
        else
        {
          // is group
          $this->Directives[$nameOrInstance]->Directives->Add(new Directive($this->Directives[$nameOrInstance]->ChildCount(), $parameters, $childs));
        }
        // return group
        return $this->Directives[$nameOrInstance];
      }
      else
      {
        // new derictive
        if ($is_group)
        {
          $g = $this->Directives->Add(new DirectiveGroup($nameOrInstance));
          $g->Directives = $childs;
        }
        else
        {
          $this->Directives->Add(new Directive($nameOrInstance, $parameters, $childs));
        }
        // return added directive
        return $this->LastChild();
      }
    }

    /**
     * Determines the directive is group or single.
     * 
     * @return \bool
     */
    public function IsGroup()
    {
      return get_class($this) == 'Nemiro\Nginx\DirectiveGroup';
    }

    /**
     * Determines the directive is block or not.
     * 
     * Block directives contain many nested directives.
     * 
     * @return \bool
     */
    public function IsBlock()
    {
      return !$this->IsSimple();
    }

    /**
     * Determines the directive is simple or not.
     * 
     * Simple directives contain only parameters.
     * 
     * @return \bool|\NULL
     */
    public function IsSimple()
    {
      if ($this->IsGroup())
      {
        if ($this->HasChild())
        {
          return $this->FirstChild()->IsSimple();
        }
        else
        {
          return NULL;
        }
      }
      else
      {
        return !$this->HasChild();
      }
    }

    /**
     * Returns true, if the directive has parameters. Otherwise, it returns false.
     * 
     * @return \bool
     */
    public function HasParameters()
    {
      return isset($this->Parameters) && count($this->Parameters) > 0;
    }
    
    /**
     * Returns parameters count.
     * 
     * @return \int
     */
    public function ParametersCount()
    {
      return isset($this->Parameters) ? count($this->Parameters) : 0;
    }

    /**
     * Returns true, if the directive has child elements. Otherwise, it returns false.
     * 
     * @return \bool
     */
    public function HasChild()
    {
      return $this->Directives->Count() > 0;
    }

    /**
     * Returns child directives count.
     * 
     * @return \int
     */
    public function ChildCount()
    {
      return $this->Directives->Count();
    }

    /**
     * Returns the first child directive of the current instance.
     * 
     * @return Directive|DirectiveGroup
     */
    public function FirstChild()
    {
      return $this->Directives->First();
    }

    /**
     * Returns the last child directive of the current instance.
     * 
     * @return Directive|DirectiveGroup
     */
    public function LastChild()
    {
      return $this->Directives->Last();
    }

    /**
     * Determines whether the child collection contains the specified directive name.
     * 
     * @param \string $name The key to locate in the DirectiveCollection.
     * @return \bool
     */
    public function ContainsChild($name)
    {
      return $this->Directives->ContainsDirective($name);
    }

    /**
     * Returns an array of directives.
     * 
     * @return Directive[]
     */
    public function ToArray()
    {
      if ($this->IsGroup())
      {
        return $this->Directives->Items;
      }
      else
      {
        return array($this);
      }
    }

    /**
     * Adds the current instance to separated instance of Directive.
     * 
     * @param Directive|DirectiveGroup $parent The Directive instance in which you want to add the current instance.
     * @return Directive|DirectiveGroup|\NULL
     */
    public function AddTo($parent)
    {
      if (is_null($parent))
      {
        throw new \InvalidArgumentException('Parent can not be null.');
      }

      return $parent->AddDirective($this);
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
      if (is_int($offset))
      {
        return isset($this->Parameters[$offset]);
      }
      else
      {
        return isset($this->Directives[$offset]);
      }
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
      if (is_int($offset))
      {
        if ($this->IsGroup())
        {
          return isset($this->Directives[$offset]) ? $this->Directives[$offset] : NULL;
        }
        else
        {
          return isset($this->Parameters[$offset]) ? $this->Parameters[$offset] : NULL;
        }
      }
      else
      {
        return isset($this->Directives[$offset]) ? $this->Directives[$offset] : NULL;
      }
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
      if (is_null($offset)) 
      {
        throw new \ErrorException('Unable to determine the type of value. Use an explicit assignment of values through the properties.');
      } 
      else if (is_int($offset))
      {
        $this->Parameters[$offset] = $value;
      }
      else
      {
        $this->Directives[$offset] = $value;
      }
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
      unset($this->Directives[$offset]);
    }

    #endregion

  }

}