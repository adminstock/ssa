<?php
namespace Models
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

  /**
   * Represents a user.
   */
  class User
  {
    
    /**
     * Login.
     * 
     * @var string
     */
    public $Login;
    
    /**
     * Password.
     * 
     * @var string
     */
    public $Password;

    /**
     * User ID.
     * 
     * @var int
     */
    public $Id;
    
    /**
     * Group ID.
     * 
     * @var int
     */
    public $GroupId;
    
    /**
     * Home path.
     * 
     * @var string
     */
    public $HomePath;
        
    /**
     * Shell name.
     * 
     * @var string
     */
    public $Shell;
        
    /**
     * User full name.
     * 
     * @var string
     */
    public $FullName;

    /**
     * User email address.
     * 
     * @var string
     */
    public $Email;
    
    /**
     * User home or work address.
     * 
     * @var string
     */
    public $Address;
    
    /**
     * Work phone.
     * 
     * @var string
     */
    public $PhoneWork;
    
    /**
     * Home phone.
     * 
     * @var string
     */
    public $PhoneHome;
    
    /**
     * Member of groups.
     * 
     * @var string[]
     */
    public $Groups;

  }

}