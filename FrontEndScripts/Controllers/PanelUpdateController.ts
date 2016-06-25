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
module SmallServerAdmin.Controllers {

  /**
   * Represents the SmallServerAdmin update controller.
   */
  export class PanelUpdateController implements Nemiro.IController {

    //#region Properties

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** SSA config. */
    public get Config(): Models.Config {
      return this.Scope.$parent.Config;
    }

    /** Checking updates indicator. */
    public get Checking(): boolean {
      return this.Scope.Checking;
    }
    public set Checking(value: boolean) {
      this.Scope.Checking = value;
    }
    
    /** Checking results. */
    public get CheckingResults(): Array<Models.PanelUpdateInfo> {
      return this.Scope.CheckingResults;
    }
    public set CheckingResults(value: Array<Models.PanelUpdateInfo>) {
      this.Scope.CheckingResults = value;
    }

    public get Stable(): Models.PanelUpdateInfo {
      return this.Scope.Stable;
    }
    public set Stable(value: Models.PanelUpdateInfo) {
      this.Scope.Stable = value;
    }

    public get AvailableDeveloperVersion(): boolean {
      return this.Scope.AvailableDeveloperVersion;
    }
    public set AvailableDeveloperVersion(value: boolean) {
      this.Scope.AvailableDeveloperVersion = value;
    }

    /** Updating indicator. */
    public get Updating(): boolean {
      return this.Scope.Updating;
    }
    public set Updating(value: boolean) {
      this.Scope.Updating = value;
    }

    //#endregion
    //#region Constructor

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      $this.Scope.UpdateTo = (info: Models.PanelUpdateInfo) => {
        $this.UpdateTo($this, info);
      }

      $this.CheckUpdates($this);
    }

    //#endregion
    //#region Methods

    private CheckUpdates($this: PanelUpdateController): void {
      if ($this.Checking) {
        return;
      }

      $this.Checking = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.PanelUpdateInfo>>($this.Context, 'Settings.CheckUpdates');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.CheckingResults = response.data;
        for (var i = 0; i < $this.CheckingResults.length; i++) {
          // search default branch
          if ($this.CheckingResults[i].Branch == $this.Config.DefaultBranch) {
            $this.Stable = $this.CheckingResults[i];
          }
          // check updates for developers
          if (!$this.AvailableDeveloperVersion && $this.CheckingResults[i].Branch != $this.Config.DefaultBranch && $this.CheckingResults[i].NeedUpdate) {
            $this.AvailableDeveloperVersion = true;
          }
        }
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.Checking = false;
      };

      // execute
      apiRequest.Execute();
    }

    private UpdateTo($this: PanelUpdateController, info: Models.PanelUpdateInfo): void {
      if ($this.Updating) {
        return;
      }

      $this.Updating = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Settings.Update', info);

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        info.Updated = true;
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.Updating = false;
      };

      // execute
      apiRequest.Execute();
    }

    //#endregion

  }

}