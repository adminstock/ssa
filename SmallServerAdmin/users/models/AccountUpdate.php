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
   * Represents model of account update.
   */
  class AccountUpdate extends User
  {

    /**
     * User login.
     * 
     * @var string
     */
    public $Login;

    /**
     * Need set login.
     * 
     * @var bool
     */
    public $SetLogin;

    /**
     * New login.
     * 
     * @var string
     */
    public $NewLogin;
    
    /**
     * Need change password.
     * 
     * @var bool
     */
    public $SetPassword;

    /**
     * New password.
     * 
     * @var string
     */
    public $NewPassword;
   
    /**
     * Need change shell.
     * 
     * @var bool
     */
    public $SetShell;

    /**
     * New shell.
     * 
     * @var string
     */
    public $NewShell;

  }

}