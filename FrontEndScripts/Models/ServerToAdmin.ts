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
   * Represents server info.
   */
  export class ServerToAdmin {

    /** Config file name. */
    public Config: string;

    /** Server name. */
    public Name: string;

    /** Server description. */
    public Description: string;

    /** SSH host or IP address. */
    public Address: string;

    /** SSH port. Default: 22. */
    public Port: number;

    /** SSH username. */
    public Username: string;

    /** SSH password. */
    public Password: string;

    /** Use password for all commands. */
    public RequiredPassword: boolean;

    /** Status. */
    public Disabled: boolean;

  }

}