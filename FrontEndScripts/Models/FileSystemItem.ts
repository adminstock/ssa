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
   * Represents a file or folder.
   */
  export class FileSystemItem {

    /**
     * The object name.
     */
    public Name: string;

    /**
     * The full path.
     */
    public Path: string;
   
    /**
     * The type of object: Folder | File.
     */
    public Type: string;

    /**
     * The list of child items (subfolders).
     */
    public Children: Array<FileSystemItem>;

    public Loading: boolean;

    public RenameMode: boolean;

    /**
     * The new object name for RenameMode.
     */
    public NewName: string;

    /**
     * The link to parent instance.
     */
    public Parent: FileSystemItem;

    /**
     * The size of the file or folder (bytes).
     */
    public Size: number;

    /**
     * Date and time of last modification of the file.
     */
    public LastModified: Date;

    /**
     * Symbolic link indicator.
     */
    public IsSymlink: boolean;

    public IsNew: boolean;

  }

}