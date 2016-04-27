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
   * Represents a file or folder.
   */
  class FileSystemItem
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
		 * The type of object: Folder | File.
     * 
     * @var \string
		 */
		public $Type;

		/**
		 * The list of child items (subfolders).
     * 
     * @var FileSystemItem[]
		 */
		public $Children;

    /**
     * The size of the file or folder.
     * 
     * @var \int
     */
    public $Size;

    /**
     * Date and time of last modification of the file.
     * 
     * @var \DateTime
     */
    public $LastModified;

    /**
     * Symbolic link indicator.
     * 
     * @var \bool
     */
    public $IsSymlink;

    function __construct($type, $name, $path, $size = NULL, $lastModified = NULL, $isSymlink = NULL)
    {
      if (strtolower($type) == 'file')
      {
        $this->Type = 'File';
      }
      else
      {
        $this->Type = 'Folder';
      }

      $this->Name = $name;
      $this->Path = $path;
      $this->Size = $size;
      $this->LastModified = $lastModified;
      $this->IsSymlink = $isSymlink;
    }
    
  }

}