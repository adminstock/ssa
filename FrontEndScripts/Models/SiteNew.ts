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
   * Represents a new web site model.
   */
  export class SiteNew implements ISelectPath {

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
     * ASP.NET mode: Off | MOD | FASTCGI
     */
    public AspNetMode: string;

    /**
     * ASP.NET Version: 4.5 | 4.0 | 2.0
     */
    public AspNetVersion: string;

    public AspNetIOMapForAll: boolean;

    public AspNetDebug: boolean;

    /**
     * FastCGI process count.
     */
    public AspNetFastCgiProcessCount: number;

    /**
     * ASP.NET FastCGI socket type: Unix | TCP
     */
    public AspNetSocket: string;

    constructor() {
      this.Domain = '';
      this.AspNetIOMapForAll = true;
      this.AspNetDebug = false;
      this.AspNetMode = 'Off';
      this.AspNetVersion = '4.5';
      this.AspNetSocket = 'Unix';
      this.AspNetFastCgiProcessCount = 1;
      this.PhpMode = 'Off';
      this.SelectedPath = '/home';
    }

  }

}