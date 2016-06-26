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

    // #region Properties

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** List of all servers. */
    public get Servers(): Array<Models.ServerToAdmin> {
      return this.Scope.Servers;
    }
    public set Servers(value: Array<Models.ServerToAdmin>) {
      this.Scope.Servers = value;
    }

    /** Selected server to edit. */
    public get Server(): Models.ServerToAdmin {
      return this.Scope.Server;
    }
    public set Server(value: Models.ServerToAdmin) {
      this.Scope.Server = value;
    }

    public get LoadingServers(): boolean {
      return this.Scope.LoadingServers;
    }
    public set LoadingServers(value: boolean) {
      this.Scope.LoadingServers = value;
    }

    public get LoadingServer(): boolean {
      return this.Scope.LoadingServer;
    }
    public set LoadingServer(value: boolean) {
      this.Scope.LoadingServer = value;
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

    public get SavingServer(): boolean {
      return this.Scope.SavingServer;
    }
    public set SavingServer(value: boolean) {
      this.Scope.SavingServer = value;
    }

    public get DeletingServer(): boolean {
      return this.Scope.DeletingServer;
    }
    public set DeletingServer(value: boolean) {
      this.Scope.DeletingServer = value;
    }

    public get IsServerLoaded(): boolean {
      return this.Scope.IsServerLoaded;
    }
    public set IsServerLoaded(value: boolean) {
      this.Scope.IsServerLoaded = value;
    }

    /** Server list dialog. */
    private ServerListDialog: Nemiro.UI.Dialog;

    /** Server editor. */
    private ServerDialog: Nemiro.UI.Dialog;

    // #endregion
    // #region Constructor

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      // select server dialog
      $this.ServerListDialog = Nemiro.UI.Dialog.CreateFromElement($('#servers'));

      // server editor dialog
      $this.ServerDialog = Nemiro.UI.Dialog.CreateFromElement($('#serverDialog'));
      $this.ServerDialog.DisableOverlayClose = true;

      // methods
      $this.Scope.SelectServer = () => {
        $this.SelectServer($this);
      };

      $this.Scope.GetServers = () => {
        $this.GetServers($this);
      };

      $this.Scope.ShowEditor = (server?: Models.ServerToAdmin) => {
        $this.IsServerLoaded = true;

        if (server === undefined || server == null) {
          $this.Server = new Models.ServerToAdmin();
          $this.Server.Port = 22;
          $this.ServerDialog.Show();
        } else {
          $this.GetServer($this, server);
        }
      };

      $this.Scope.SaveServer = () => {
        $this.SaveServer($this);
      };

      $this.Scope.ShowDialogToDelete = (server: Models.ServerToAdmin) => {
        Nemiro.UI.Dialog.Alert('TODO', 'TODO');
        //$this.DeleteServer($this, server);
      };

      $this.Scope.ConnectToServer = (server: Models.ServerToAdmin) => {
        // save server to cookies
        Nemiro.Utility.CreateCookies('currentServer', server.Config, 3650);
        // reload page
        $this.Context.Location.search({});
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

      $this.Scope.$watch('Server.Port', (val, old) => {
        if ($this.Server !== undefined) {
          $this.Server.Port = parseInt(val);
        }
      });
    }

    // #endregion
    // #region Methods

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

    public GetServer($this: PanelServersController, server: Models.ServerToAdmin): void {
      if ($this.LoadingServer) {
        return;
      }

      $this.LoadingServer = true;
      $this.ServerDialog.Show();

      // create request
      var apiRequest = new ApiRequest<Models.ServerToAdmin>($this.Context, 'Settings.GetServer', { "Config": server.Config });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Context.Timeout(() => {
          $this.Server = response.data;
        });
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.LoadingServer = false;
      };

      // execute
      apiRequest.Execute();
    }

    public SaveServer($this: PanelServersController): void {
      if ($this.SavingServer) {
        return;
      }

      if ($this.Server === undefined || $this.Server == null) {
        Nemiro.UI.Dialog.Alert(App.Resources.ServerIsRequired, App.Resources.Error);
        return;
      }

      $this.SavingServer = true;

      // create request
      var apiRequest = new ApiRequest<Models.ServerToAdmin>($this.Context, 'Settings.SaveServer', $this.Server);

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Context.Timeout(() => {
          $this.Server = response.data;
        });

        // close dialog
        $this.ServerDialog.Close();

        // reload page
        $this.Context.Location.search({});
        $this.Context.Window.location.reload();
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.SavingServer = false;
      };

      // execute
      apiRequest.Execute();
    }

    public DeleteServer($this: PanelServersController, server: Models.ServerToAdmin): void {
      if ($this.DeletingServer) {
        return;
      }

      $this.DeletingServer = true;

      // create request
      var apiRequest = new ApiRequest<Models.ServerToAdmin>($this.Context, 'Settings.DeleteServer', { 'Config': server.Config });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        // reload page
        $this.Context.Location.search({});
        $this.Context.Window.location.reload();
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.DeletingServer = false;
      };

      // execute
      apiRequest.Execute();
    }

    // #endregion

  }

} 