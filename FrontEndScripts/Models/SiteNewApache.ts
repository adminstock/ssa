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
   * Represents model of the master of the Apache config.
   */
  export class SiteNewApache implements ISelectPath {

    /**
     * The main domain.
     */
    public Domain: string;

    /**
     * The root path.
     */
    public SelectedPath: string;

    /**
     * 0 - Off, 1 - Error log, 2 - Access log, 3 - Error and Access
     */
    public EventLogs: number;

    /**
     * ASP.NET Version: Off | 4.5 | 4.0 | 2.0
     */
    public AspNetVersion: string;

    /**
     * MONO_IOMAP
     */
    public AspNetIOMapForAll: boolean;

    /**
     * Debug mode.
     */
    public AspNetDebug: boolean;

    /**
     * Mono control panel.
     */
    public MonoCtrl: boolean;

    /**
     * WebDAV SVN.
     */
    public WebDavSvn: boolean;

    /**
     * SSL.
     */
    public SSL: boolean;

    constructor() {
      this.Domain = '';
      this.AspNetVersion = 'Off';
      this.MonoCtrl = false;
      this.AspNetIOMapForAll = true;
      this.AspNetDebug = false;
      this.WebDavSvn = false;
      this.SSL = false;
      this.SelectedPath = '/home';
      this.EventLogs = 0;
    }

  }

}