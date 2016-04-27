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
   * Represents a process.
   */
  class Process
  {
    
    /**
     * The process ID.
     * 
     * @var \int
     */
    public $PID;

    /**
     * The pricess parent ID.
     * 
     * @var \int
     */
    public $PPID;

    /**
     * The process name.
     * 
     * @var \string
     */
    public $Name;

    /**
     * The owner name of the process.
     * 
     * @var \string
     */
    public $Username;

    /**
     * The percent of CPU usage.
     * 
     * @var \double
     */
    public $CPU;

    /**
     * The percent of RAM usage.
     * 
     * @var \double
     */
    public $Memory;

    /**
     * The parameter string of the process.
     * 
     * @var \string
     */
    public $Command;

    public $StartTime;

    public $ElapsedTime;

    /**
     * Virtual set size (Kb).
     * 
     * @var \int
     */
    public $VSZ;

    /**
     * Resident set size (Kb).
     * 
     * @var \int
     */
    public $RSS;
    
    /**
     * The status of the process.
     * 
     * @var \string
     */
    public $Status;

  }

}