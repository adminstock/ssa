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
   * Represents Nginx directive group.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   */
  class DirectiveGroup extends Directive
  {
    
    /**
     * Initializes a new instance of the class with the specified parameters.
     * 
     * @param \string $groupName Name of the group.
     */
    function __construct($groupName)
    {
      parent::__construct($groupName, NULL);
    }
    
    /**
     * Adds a new directive with specified parameters to the group.
     * 
     * @param \string|\string[]|Directive $value List of parameters or directive instance.
     * @return Directive|DirectiveGroup
     */
    public function AddDirective($value)
    {
      if (is_null($value) || !is_object($value))
      {
        return $this->Directives->Add(new Directive($this->Directives->Count(), $value));
      }
      else if (get_class($value) == 'Nemiro\Nginx\Directive')
      {
        $d = new Directive($this->Directives->Count(), $value->Parameters, $value->Directives);
        return $this->Directives->Add($d);
      }
      else
      {
        throw new \ErrorException('The value of the specified type is not supported. Expected: string, string array or Directive instance.');
      }
    }
    
    /**
     * [NOT SUPPORED FOR GROUPS!] Adds new parameter to the parameter collection of the instance. 
     * 
     * @param \object|\array $value The value to add.
     * @return void
     */
    public function AddParameter($value)
    {
      throw new \ErrorException('Parameters not suppered for groups. It is only for single directives.');
    }

  }

}