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
module SmallServerAdmin.Models {

  /**
   * Represents info about file or folder.
   */
  export class FileSystemItemInfo {

    /**
     * The object name.
     */
    public Name: string;

    /**
     * The full path.
     */
    public Path: string;
   
    /**
     * The type of object: Folder | File | Link.
     */
    public Type: string;

    /**
     * The size of the file or folder (bytes).
     */
    public Size: number;

    /**
     * Date and time of last modification of the file.
     */
    public DateLastModified: number;
    
    /**
     * Date and time of creation.
     */
    public DateCreated: number;
    /**
     * Date and time of last last access to the object.
     * 
     * @var \DateTime
     */
    public DateLastAccess: number;

    /**
     * Group ID.
     */
    public GID: number;

    /**
     * Group name.
     */
    public GroupName: string;

    /**
     * Owner ID.
     */
    public UID: number;

    /**
     * Owner name.
     */
    public Username: string;

    public Permissions: number;

    public Saving: boolean;

  }

}