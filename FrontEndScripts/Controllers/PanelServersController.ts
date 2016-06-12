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
   * Represents the servers controller.
   */
  export class PanelServersController implements Nemiro.IController {

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** Server list dialog. */
    private ServerListDialog: Nemiro.UI.Dialog;

    /** List of all servers. */
    public get Servers(): Array<Models.ServerToAdmin> {
      return this.Scope.Servers;
    }
    public set Servers(value: Array<Models.ServerToAdmin>) {
      this.Scope.Servers = value;
    }

    public get LoadingServers(): boolean {
      return this.Scope.LoadingServers;
    }
    public set LoadingServers(value: boolean) {
      this.Scope.LoadingServers = value;
    }

    public get ConnectionTesting(): boolean {
      return this.Scope.ConnectionTesting;
    }
    public set ConnectionTesting(value: boolean) {
      this.Scope.ConnectionTesting = value;
    }

    public get CurrentServerConnectionError(): boolean {
      return this.Scope.CurrentServerConnectionError;
    }
    public set CurrentServerConnectionError(value: boolean) {
      this.Scope.CurrentServerConnectionError = value;
    }

    public get DisableShowConnectionError(): boolean {
      return this.Scope.DisableShowConnectionError;
    }
    public set DisableShowConnectionError(value: boolean) {
      this.Scope.DisableShowConnectionError = value;
    }

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      // select server dialog
      $this.ServerListDialog = Nemiro.UI.Dialog.CreateFromElement($('#servers'));

      $this.Scope.SelectServer = () => {
        $this.SelectServer($this);
      };

      $this.Scope.GetServers = () => {
        $this.GetServers($this);
      };

      $this.Scope.ConnectToServer = (server: Models.ServerToAdmin) => {
        if (server.IsDefault) {
          // is default server, clear cookies
          Nemiro.Utility.EraseCookies('currentServer');
        } else {
          // save server to cookies
          Nemiro.Utility.CreateCookies('currentServer', server.Config, 3650);
        }
        // reload page
        $this.Context.Window.location.reload();
      };

      // delay for ng-init
      $this.Context.Timeout(() => {
        if (($this.Context.Location.search()['connection_failed'] !== undefined && $this.Context.Location.search()['connection_failed'] != null) || ($this.Context.Location.search()['authentication_failed'] !== undefined && $this.Context.Location.search()['authentication_failed'] != null)) {
          $this.CurrentServerConnectionError = true;
          if (!$this.DisableShowConnectionError) {
            Nemiro.UI.Dialog.Alert(App.Resources.UnableToConnectTheServer, App.Resources.ConnectionError);
          }
        } else {
          $this.CheckConnection($this);
        }
      }, 250);
    }

    public SelectServer($this: PanelServersController): void {
      //console.log('SelectServer', $this.Servers);
      if ($this.Servers === undefined || $this.Servers == null) {
        $this.GetServers($this);
      }

      $this.ServerListDialog.Show();
    }

    public GetServers($this: PanelServersController): void {
      if ($this.LoadingServers) {
        return;
      }

      $this.LoadingServers = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.ServerToAdmin>>($this.Context, 'Settings.GetServers');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Context.Timeout(() => {
          $this.Servers = response.data;
        });
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.LoadingServers = false;
      };

      // execute
      apiRequest.Execute();
    }

    public CheckConnection($this: PanelServersController): void {
      if ($this.ConnectionTesting) {
        return;
      }

      $this.ConnectionTesting = true;

      var apiRequest = new ApiRequest<Array<Models.ServerToAdmin>>($this.Context, 'Settings.CheckConnection');

      /*apiRequest.SuccessCallback = (response) => {

      };*/

      apiRequest.ErrorCallback = (response) => {
        $this.CurrentServerConnectionError = true;
      };

      apiRequest.CompleteCallback = () => {
        $this.ConnectionTesting = false;
      };

      apiRequest.Execute();
    }
  
  }

} 