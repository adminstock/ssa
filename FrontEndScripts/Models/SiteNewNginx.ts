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
   * Represents model of the master of the Nginx config.
   */
  export class SiteNewNginx implements ISelectPath {

    /**
     * The main domain.
     */
    public Domain: string;

    /**
     * The root path.
     */
    public SelectedPath: string;

    /**
     * PHP mode: Off | MOD | FPM
     */
    public PhpMode: string;

    /**
     * FastCGI process count.
     */
    public PhpFastCgiProcessCount: number;

    /**
     * PHP FastCGI socket type: Unix | TCP
     */
    public PhpSocket: string;

    /**
     * ASP.NET mode: Off | MOD | FASTCGI
     */
    public AspNetMode: string;

    /**
     * FastCGI process count.
     */
    public AspNetFastCgiProcessCount: number;

    /**
     * ASP.NET FastCGI socket type: Unix | TCP
     */
    public AspNetSocket: string;

    /**
     * 0 - Off, 1 - Error log, 2 - Access log, 3 - Error and Access
     */
    public EventLogs: number;

    constructor() {
      this.Domain = '';
      this.AspNetMode = 'Off';
      this.AspNetSocket = 'Unix';
      this.AspNetFastCgiProcessCount = 1;
      this.PhpMode = 'Off';
      this.PhpSocket = 'Unix';
      this.PhpFastCgiProcessCount = 1;
      this.SelectedPath = '/home';
      this.EventLogs = 0;
    }

  }

}