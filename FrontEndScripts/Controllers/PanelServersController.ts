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
      this.UpdateAllModulesSelectStatus(this);
    }

    /** Selected server to delete. */
    public get SelectedServerToDelete(): Models.ServerToAdmin {
      return this.Scope.SelectedServerToDelete;
    }
    public set SelectedServerToDelete(value: Models.ServerToAdmin) {
      this.Scope.SelectedServerToDelete = value;
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

    public get LoadingModules(): boolean {
      return this.Scope.LoadingModules;
    }
    public set LoadingModules(value: boolean) {
      this.Scope.LoadingModules = value;
    }

    public get PreparingServerForm(): boolean {
      return this.Scope.PreparingServerForm;
    }
    public set PreparingServerForm(value: boolean) {
      this.Scope.PreparingServerForm = value;
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

    public get Accordion(): any {
      return this.Scope.Accordion;
    }
    public set Accordion(value: any) {
      this.Scope.Accordion = value;
    }

    public get Modules(): Array<string> {
      return this.Scope.Modules;
    }
    public set Modules(value: Array<string>) {
      this.Scope.Modules = value;
    }

    public get AllModulesSelected(): boolean {
      return this.Scope.AllModulesSelected;
    }
    public set AllModulesSelected(value: boolean) {
      this.Scope.AllModulesSelected = value;
    }

    /** Server list dialog. */
    private ServerListDialog: Nemiro.UI.Dialog;

    /** Server editor. */
    private ServerDialog: Nemiro.UI.Dialog;

    private ConfirmServerDeleteDialog: Nemiro.UI.Dialog;

    // #endregion
    // #region Constructor

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      $this.Accordion = { SshOpened: true, InfoOpened: false, ModulesOpened: false };

      // select server dialog
      $this.ServerListDialog = Nemiro.UI.Dialog.CreateFromElement($('#servers'));

      // server editor dialog
      if ($('#serverDialog').length > 0) {
        $this.ServerDialog = Nemiro.UI.Dialog.CreateFromElement($('#serverDialog'));
        $this.ServerDialog.DisableOverlayClose = true;
      }

      // server delete dialog
      if ($('#confirmToDeleteServer').length > 0) {
        $this.ConfirmServerDeleteDialog = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteServer'));
      }

      // methods
      $this.Scope.SelectServer = () => {
        $this.SelectServer($this);
      };

      $this.Scope.GetServers = () => {
        $this.GetServers($this);
      };

      $this.Scope.ShowEditor = (server?: Models.ServerToAdmin) => {
        $this.Accordion.SshOpened = true;

        if (server === undefined || server == null) {
          $this.NewServer($this);
        } else {
          $this.GetServer($this, server);
        }
      };

      $this.Scope.SaveServer = () => {
        $this.SaveServer($this);
      };

      $this.Scope.ShowDialogToDelete = (server: Models.ServerToAdmin) => {
        $this.SelectedServerToDelete = server;
        $this.ConfirmServerDeleteDialog.Show();
      };

      $this.Scope.DeleteServer = () => {
        $this.DeleteServer($this, $this.SelectedServerToDelete);
      };

      $this.Scope.ConnectToServer = (server: Models.ServerToAdmin) => {
        // save server to cookies
        Nemiro.Utility.CreateCookies('currentServer', server.Config, 3650);
        // reload page
        $this.Context.Location.search({});
        $this.Context.Window.location.reload();
      };

      $this.Scope.ModuleClick = (module: Models.Module) => {
        module.Enabled = !module.Enabled;

        $this.UpdateAllModulesSelectStatus($this);
      };

      $this.Scope.SelectModules = (event) => {
        for (var i = 0; i < $this.Server.Modules.length; i++) {
          $this.Server.Modules[i].Enabled = event.target.checked;
        }

        $this.AllModulesSelected = event.target.checked;
      };

      $this.Scope.ModuleMoved = (index, module) => {
        //console.log('ModuleMoved', index, module);
        $this.Server.Modules.splice(index, 1);
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

    public GetModules($this: PanelServersController): void {
      if ($this.LoadingModules) {
        return;
      }

      $this.LoadingModules = true;

      // create request
      var apiRequest = new ApiRequest<Array<string>>($this.Context, 'Settings.GetModules');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Context.Timeout(() => {
          $this.Modules = response.data;

          $this.UpdateAllModulesSelectStatus($this);
        });
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.LoadingModules = false;
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

        if ($this.Modules === undefined || $this.Modules == null) {
          $this.GetModules($this);
        }
      };

      // handler request complete
      apiRequest.CompleteCallback = () => {
        $this.LoadingServer = false;
      };

      // execute
      apiRequest.Execute();
    }

    public NewServer($this: PanelServersController): void {
      $this.PreparingServerForm = true;

      if ($this.Modules === undefined || $this.Modules == null) {
        $this.GetModules($this);

        $this.Context.Timeout(() => {
          $this.NewServer($this);
        }, 1000);

        return;
      }

      $this.Server = new Models.ServerToAdmin();
      $this.Server.Port = 22;
      $this.Server.RequiredPassword = true;
      $this.Server.Modules = new Array<Models.Module>();

      for (var i = 0; i < $this.Modules.length; i++) {
        $this.Server.Modules.push(new Models.Module($this.Modules[i], true));
      }

      $this.UpdateAllModulesSelectStatus($this);

      $this.ServerDialog.Show();

      $this.PreparingServerForm = false;
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
        $this.ConfirmServerDeleteDialog.Close();
      };

      // execute
      apiRequest.Execute();
    }

    public UpdateAllModulesSelectStatus($this: PanelServersController): void {
      if (this.Modules == undefined || this.Modules == null) {
        $this.AllModulesSelected = false;
        return;
      }

      if ($this.Server === undefined || $this.Server == null || $this.Server.Modules === undefined || $this.Server.Modules == null) {
        $this.AllModulesSelected = false;
        return;
      }
      
      $this.AllModulesSelected = ($.grep($this.Server.Modules, (item: Models.Module) => { return item.Enabled; }).length == $this.Modules.length);
    }

    // #endregion

  }

} 