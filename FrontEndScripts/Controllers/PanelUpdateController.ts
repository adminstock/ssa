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

    /** Checking updates indicator. */
    public get Checking(): boolean {
      return this.Scope.Checking;
    }
    public set Checking(value: boolean) {
      this.Scope.Checking = value;
    }

    /** Indicates the need to update. */
    public get NeedUpdate(): boolean {
      return this.Scope.NeedUpdate;
    }
    public set NeedUpdate(value: boolean) {
      this.Scope.NeedUpdate = value;
    }

    /** New version number. */
    public get NewVersion(): string {
      return this.Scope.NewVersion;
    }
    public set NewVersion(value: string) {
      this.Scope.NewVersion = value;
    }

    /** List of changes in the NewVersion. */
    public get Changes(): string {
      return this.Scope.Changes;
    }
    public set Changes(value: string) {
      this.Scope.Changes = value;
    }

    /** Updating indicator. */
    public get Updating(): boolean {
      return this.Scope.Updating;
    }
    public set Updating(value: boolean) {
      this.Scope.Updating = value;
    }

    public get Updated(): boolean {
      return this.Scope.Updated;
    }
    public set Updated(value: boolean) {
      this.Scope.Updated = value;
    }

    //#endregion
    //#region Constructor

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      $this.Scope.UpdateToNewVersion = () => {
        $this.UpdateToNewVersion($this);
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
      var apiRequest = new ApiRequest<any>($this.Context, 'Settings.CheckUpdates');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.NeedUpdate = response.data.NeedUpdate;
        $this.NewVersion = response.data.NewVersion;
        $this.Changes = response.data.Changes;
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.Checking = false;
      };

      // execute
      apiRequest.Execute();
    }

    private UpdateToNewVersion($this: PanelUpdateController): void {
      if ($this.Updating) {
        return;
      }

      $this.Updating = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Settings.Update');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Updated = true;
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