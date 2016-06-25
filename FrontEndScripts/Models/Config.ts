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
   * Represents config of the SSA.
   */
  export class Config {
    
    /**
     * Web server mode: nginx+apache | nginx | apache
     */
    public WebServer: string;

    /**
     * Apache host. For example: 127.0.0.1.
     */
    public ApacheHost: string;

    /**
     * Apache port. For example: 8080, 80.
     */
    public ApachePort: number;

    /**
     * Folder name for building LogPath.
     */
    public LogFolderName: string;

    public PhpFastCgiPort: number;

    public AspNetFastCgiPort: number;

    /** Current server name or IP. */
    public ServerAddress: string;

    /** Current server human name. */
    public ServerName: string;

    /** Current language. For example: en, ru, de. */
    public Lang: string;

    public HtanEnabled: boolean;

    /** Default branch. */
    public DefaultBranch: string;

  }

}