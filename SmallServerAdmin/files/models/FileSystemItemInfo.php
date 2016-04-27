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
   * Represents info about file or folder.
   */
  class FileSystemItemInfo
  {
    
		/**
		 * The object name.
     * 
     * @var \string
		 */
		public $Name;

		/**
		 * The full path.
     * 
     * @var \string
		 */
		public $Path;

		/**
     * The target path for links ($Type = 'Link').
     * 
     * @var \string
     */
		public $TargetPath;

		/**
     * The type of object: Folder | File | Link.
     * 
     * @var \string
		 */
		public $Type;

    /**
     * The size of the file or folder (bytes).
     * 
     * @var \int
     */
    public $Size;

    /**
     * Date and time of creation.
     * 
     * @var \int
     */
    public $DateCreated;

    /**
     * Date and time of last modification of the object.
     * 
     * @var \int
     */
    public $DateLastModified;

    /**
     * Date and time of last last access to the object.
     * 
     * @var \int
     */
    public $DateLastAccess;

    /**
     * Group ID.
     * 
     * @var \int
     */
    public $GID;

    /**
     * Group name.
     * 
     * @var \string
     */
    public $GroupName;

    /**
     * Owner ID.
     * 
     * @var \int
     */
    public $UID;

    /**
     * Owner name.
     * 
     * @var \string
     */
    public $Username;

    /**
     * Permissions code.
     * 
     * @var \int
     */
    public $Permissions;

    function __construct()
    {
    }
    
  }

}