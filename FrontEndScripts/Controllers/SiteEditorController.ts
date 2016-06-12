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

  export class SiteEditorController implements Nemiro.IController {

    //#region ..properties.. 

    private LevelsList = ['Nginx', 'Apache', 'HTAN'];

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** SSA config. */
    public get Config(): Models.Config {
      return this.Scope.$parent.Config;
    }

    /** The site data. */
    public get Site(): Models.Site {
      return this.Scope.Site;
    }
    public set Site(value: Models.Site) {
      this.Scope.Site = value;
    }

    /**
     * The source site name.
     */
    public get SourceName(): string {
      return this.Scope.SourceName;
    }
    public set SourceName(value: string) {
      this.Scope.SourceName = value;
    }

    /** Loading indicator. */
    public get Loading(): boolean {
      return this.Scope.Loading;
    }
    public set Loading(value: boolean) {
      this.Scope.Loading = value;
    }

    public get Saving(): boolean {
      return this.Scope.Saving;
    }
    public set Saving(value: boolean) {
      this.Scope.Saving = value;
    }

    /** Reloading indicator. */
    public get Reloading(): boolean {
      return this.Scope.Reloading;
    }
    public set Reloading(value: boolean) {
      this.Scope.Reloading = value;
    }

    /** Gets or sets reloading items. */
    public get ReloadingItems(): Array<Models.ReloadingInfo> {
      return this.Scope.ReloadingItems;
    }
    public set ReloadingItems(value: Array<Models.ReloadingInfo>) {
      this.Scope.ReloadingItems = value;
    }

    /** Success result indicator. */
    public get Success(): boolean {
      return this.Scope.Success;
    }
    public set Success(value: boolean) {
      this.Scope.Success = value;
    }

    public get EditorChanged(): boolean {
      return this.Scope.EditorChanged;
    }
    public set EditorChanged(value: boolean) {
      this.Scope.EditorChanged = value;
      //console.log('EditorChanged', value);

      if (value) {
        var $this = this;
        this.Context.Timeout(() => {
          $this.Scope.EditorChanged = false;
          //console.log('EditorChanged', $this.Scope.EditorChanged);
        }, 500);
      }
    }

    /**
     * Selectec config to delete.
     */
    public get SelectecConfToDelete(): Models.SiteConf {
      return this.Scope.SelectecConfToDelete;
    }
    public set SelectecConfToDelete(value: Models.SiteConf) {
      this.Scope.SelectecConfToDelete = value;
    }

    public get CreateNew(): Models.SiteNew {
      return this.Scope.CreateNew;
    }
    public set CreateNew(value: Models.SiteNew) {
      this.Scope.CreateNew = value;
    }

    public get CreateNewNginx(): Models.SiteNewNginx {
      return this.Scope.CreateNewNginx;
    }
    public set CreateNewNginx(value: Models.SiteNewNginx) {
      this.Scope.CreateNewNginx = value;
    }

    public get CreateNewApache(): Models.SiteNewApache {
      return this.Scope.CreateNewApache;
    }
    public set CreateNewApache(value: Models.SiteNewApache) {
      this.Scope.CreateNewApache = value;
    }

    public get CreateNewHTAN(): Models.SiteNewHTAN {
      return this.Scope.CreateNewHTAN;
    }
    public set CreateNewHTAN(value: Models.SiteNewHTAN) {
      this.Scope.CreateNewHTAN = value;
    }

    public get ActiveTab(): any {
      return this.Scope.ActiveTab;
    }
    public set ActiveTab(value: any) {
      this.Scope.ActiveTab = value;
    }

    public get CanAddConf(): boolean {
      return this.Scope.CanAddConf;
    }
    public set CanAddConf(value: boolean) {
      this.Scope.CanAddConf = value;
    }

    public get Folders(): Array<Models.FileSystemItem> {
      return this.Scope.Folders;
    }
    public set Folders(value: Array<Models.FileSystemItem>) {
      this.Scope.Folders = value;
    }

    public get SelectedFolder(): Models.FileSystemItem {
      return this.Scope.SelectedFolder;
    }
    public set SelectedFolder(value: Models.FileSystemItem) {
      this.Scope.SelectedFolder = value;
    }

    public get SelectFolderTarget(): ISelectPath {
      return this.Scope.SelectFolderTarget;
    }
    public set SelectFolderTarget(value: ISelectPath) {
      this.Scope.SelectFolderTarget = value;
    }

    public get SelectedFolderToDelete(): Models.FileSystemItem {
      return this.Scope.SelectedFolderToDelete;
    }
    public set SelectedFolderToDelete(value: Models.FileSystemItem) {
      this.Scope.SelectedFolderToDelete = value;
    }

    public get NewFolderName(): string {
      return this.Scope.NewFolderName;
    }
    public set NewFolderName(value: string) {
      this.Scope.NewFolderName = value;
    }

    public get NewFolderOwnerName(): string {
      return this.Scope.NewFolderOwnerName;
    }
    public set NewFolderOwnerName(value: string) {
      this.Scope.NewFolderOwnerName = value;
    }

    public get CreationFolder(): boolean {
      return this.Scope.CreationFolder;
    }
    public set CreationFolder(value: boolean) {
      this.Scope.CreationFolder = value;
    }

    public get AvailableLevels(): Array<string> {
      return this.Scope.AvailableLevels;
    }
    public set AvailableLevels(value: Array<string>) {
      this.Scope.AvailableLevels = value;
    }

    public get SelectedConfToAdd(): string {
      return this.Scope.SelectedConfToAdd;
    }
    public set SelectedConfToAdd(value: string) {
      this.Scope.SelectedConfToAdd = value;
    }

    public get Help(): any {
      return this.Scope.Help;
    }
    public set Help(value: any) {
      this.Scope.Help = value;
    }

    private ConfirmDeleteConfDialog: Nemiro.UI.Dialog;
    private CreateConfigDialog: Nemiro.UI.Dialog;
    private SelectFolderDialog: Nemiro.UI.Dialog;
    private CreateFolderDialog: Nemiro.UI.Dialog;
    private ConfirmToDeleteFolder: Nemiro.UI.Dialog;
    private ReloadingDialog: Nemiro.UI.Dialog;

    //#endregion
    //#region ..constructor..

    constructor(context: Nemiro.AngularContext) {
      var $this = this;
      $this.Context = context;
      $this.Scope = $this.Context.Scope;
      $this.SelectedFolder = undefined;
      $this.Help = { IsOpened: false };

      $this.LevelsList = ['Nginx'];

      if ($this.Config.WebServer.indexOf('apache') != -1) {
        $this.LevelsList.push('Apache');
      }

      if ($this.Config.HtanEnabled) {
        $this.LevelsList.push('HTAN');
      }

      $this.ConfirmDeleteConfDialog = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteConf'));
      $this.ConfirmToDeleteFolder = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteFolder'));

      $this.CreateConfigDialog = Nemiro.UI.Dialog.CreateFromElement($('#createConfig'));
      $this.SelectFolderDialog = Nemiro.UI.Dialog.CreateFromElement($('#selectFolder'));
      $this.CreateFolderDialog = Nemiro.UI.Dialog.CreateFromElement($('#createFolder'));
      $this.CreateFolderDialog.DisableOverlayClose = true;

      $this.ReloadingDialog = Nemiro.UI.Dialog.CreateFromElement($('#reloading'));
      $this.ReloadingDialog.DisableOverlayClose = true;
      $this.ReloadingDialog.DisplayCloseButton = true;

      $this.Scope.Create = () => {
        $this.Create($this);
      }

      $this.Scope.ShowSelectFolder = (target: ISelectPath) => {
        $this.SelectFolderTarget = target;
        $this.Folders = new Array<Models.FileSystemItem>();
        $this.SelectedFolder = undefined;
        $this.GetFolders($this);
        $this.SelectFolderDialog.Show();
      }

      $this.Scope.CreateDefault = () => {
        $this.CreateDefault($this);
      }

      $this.Scope.ConfirmDeleteConf = (item: Models.SiteConf) => {
        $this.SelectecConfToDelete = item;
        $this.ConfirmDeleteConfDialog.Show();
      }

      $this.Scope.DeleteConf = () => {
        $this.DeleteConf($this);
      }

      $this.Scope.SelectConfToAdd =  (level: string) => {
        $this.SelectedConfToAdd = level;
      }

      $this.Scope.PrepareAddConf = () => {
        $this.PrepareAddConf($this);
      }

      $this.Scope.ReinitCurrentConf = () => {
        if ($this.SelectedConfToAdd === undefined || $this.SelectedConfToAdd == null || $this.SelectedConfToAdd == '') {
          return;
        }

        $this['CreateNew' + $this.SelectedConfToAdd] = null;
        $this.PrepareAddConf($this);
      }

      $this.Scope.AddConf = () => {
        $this.AddConf($this);
      }

      $this.Scope.ConfEnabledClick = (item: Models.SiteConf) => {
        //console.log('ConfEnabledClick', item);
        item.Enabled = !item.Enabled;
      }

      $this.Scope.SelectNginxEventLog =  (value: number) => {
        $this.CreateNewNginx.EventLogs = value;
      }

      $this.Scope.SelectApacheEventLog =  (value: number) => {
        $this.CreateNewApache.EventLogs = value;
      }

      $this.Scope.TabClick = (tab: string) => {
        $this.ActiveTab = {};
        $this.ActiveTab[tab] = true;
        $this.EditorChanged = true;
      }

      $this.Scope.ToggleFolder = (node: Models.FileSystemItem, expanded) => {
        //console.log('ToggleFolder', node, expanded);
        if (expanded && (node.Children == undefined || node.Children == null)) {
          if (node.Loading === undefined || !node.Loading) {
            $this.GetFolders($this, node);
          }
        } else if (!expanded) {
          node.Children = null;
        }
      }

      $this.Scope.ShowSelectedFolder = (node: Models.FileSystemItem, selected, $parentNode: Models.FileSystemItem) => {
        $this.SelectedFolder = node;
        $this.SelectedFolder.Parent = $parentNode;
        //$this.CreateNew.RootPath = node.Path;
      }

      $this.Scope.SelectFolderOptions = {
        isLeaf: (node: Models.FileSystemItem) => {
          return false;
        },
        isSelectable: (node: Models.FileSystemItem) => {
          return node.Loading === undefined || !node.Loading;
        },
        nodeChildren: 'Children'
      };

      $this.Scope.ShowCreateFolder = () => {
        //$this.SelectFolderDialog.Close();
        $this.NewFolderName = '';
        $this.CreateFolderDialog.Show();
      }

      $this.Scope.CreateFolder = () => {
        $this.CreateFolder($this);
      }

      $this.Scope.RenameFolder = (node: Models.FileSystemItem) => {
        $this.RenameFolder($this, node);
      }

      $this.Scope.ShowConfirmToDeleteFolder = (node: Models.FileSystemItem) => {
        $this.SelectedFolderToDelete = node;
        $this.ConfirmToDeleteFolder.Show();
      }

      $this.Scope.DeleteFolder = () => {
        $this.DeleteFolder($this);
      }

      $this.Scope.SelectPath = () => {
        $this.SelectFolderTarget.SelectedPath = $this.SelectedFolder.Path;
        $this.SelectFolderDialog.Close();
      }

      $this.Scope.Save = (needReloading: boolean) => {
        $this.Save($this, needReloading);
      }

      $this.Scope.SiteStatusChanged = () => {
        console.log('SiteStatusChanged');
        if ($this.Site.IsEnabled && $this.AvailableLevels.indexOf('Nginx') == -1) {
          for (var i = 0; i < $this.Site.Conf.length; i++) {
            if ($this.Site.Conf[i].Level == 'Nginx') {
              $this.Site.Conf[i].Enabled = true;
              return;
            }
          }
        }
      }
      // load site
      $this.Load($this);
    }

    //#endregion
    //#region ..methods..

    /**
     * Loads site data from the server.
     */
    private Load($this: SiteEditorController): void {
      $this = $this || this;
      $this.Loading = true;

      if ($this.Context.Location.search().name === undefined || $this.Context.Location.search().name == null || $this.Context.Location.search().name == '') {
        $this.Loading = false;
        $this.CreateNew = new Models.SiteNew();
        $this.CreateConfigDialog.Show();
        return;
      }

      $this.CreateNew = null;

      // create request
      var apiRequest = new ApiRequest<Models.Site>($this.Context, 'Sites.GetSite', { name: $this.Context.Location.search().name });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Scope.TabClick(response.data.Conf[0].Level);
        $this.Site = response.data;
        $this.SourceName = $this.Site.Name;
        $this.UpdateCanAddConf($this);
      };

      apiRequest.CompleteCallback = () => {
        $this.Loading = false;
      }

      // execute
      apiRequest.Execute();
    }

    private DeleteConf($this: SiteEditorController): void {
      $this.Site.Levels.splice($this.Site.Levels.indexOf($this.SelectecConfToDelete.Level), 1);
      $this.Site.Conf.splice($this.Site.Conf.indexOf($this.SelectecConfToDelete), 1);
      $this.ConfirmDeleteConfDialog.Close();
      $this.SelectecConfToDelete = null;
      $this.UpdateCanAddConf($this);
    }

    private Create($this: SiteEditorController): void {
      console.log('Create');

      $this.Site = new Models.Site();

      $this.Site.Name = $this.CreateNew.Domain;
      $this.Site.IsEnabled = true;
      $this.Site.Levels = new Array<string>();
      $this.Site.Conf = new Array<Models.SiteConf>();

      // nginx
      $this.SelectedConfToAdd = 'Nginx';
      $this.CreateNewNginx = new Models.SiteNewNginx();
      $this.CreateNewNginx.Domain = $this.CreateNew.Domain;
      $this.CreateNewNginx.SelectedPath = $this.CreateNew.SelectedPath;
      $this.CreateNewNginx.PhpMode = $this.CreateNew.PhpMode;
      $this.CreateNewNginx.AspNetMode = $this.CreateNew.AspNetMode;
      $this.CreateNewNginx.AspNetFastCgiProcessCount = $this.CreateNew.AspNetFastCgiProcessCount;
      $this.CreateNewNginx.AspNetSocket = $this.CreateNew.AspNetSocket;

      $this.AddConf($this);

      // apache
      if ($this.CreateNew.AspNetMode == 'MOD' || $this.CreateNew.PhpMode == 'MOD') {
        $this.SelectedConfToAdd = 'Apache';
        $this.CreateNewApache = new Models.SiteNewApache();
        $this.CreateNewApache.Domain = $this.CreateNew.Domain;
        $this.CreateNewApache.SelectedPath = $this.CreateNew.SelectedPath;

        if ($this.CreateNew.AspNetMode != 'Off') {
          $this.CreateNewApache.AspNetVersion = $this.CreateNew.AspNetVersion;
          $this.CreateNewApache.AspNetIOMapForAll = $this.CreateNew.AspNetIOMapForAll;
          $this.CreateNewApache.AspNetDebug = $this.CreateNew.AspNetDebug;
        }

        $this.AddConf($this);
      }

      // htan
      if ($this.CreateNew.AspNetMode == 'FASTCGI') {
        $this.SelectedConfToAdd = 'HTAN';
        $this.CreateNewHTAN = new Models.SiteNewHTAN();
        $this.CreateNewHTAN.Domain = $this.CreateNew.Domain;
        $this.CreateNewHTAN.SelectedPath = $this.CreateNew.SelectedPath;
        $this.CreateNewHTAN.FastCGI = new Array<Models.SiteHTANItem>();

        var hostParts = $this.CreateNew.Domain.split('.');
        if (hostParts.length > 1) { hostParts.pop(); }
        var hostFirst = hostParts.join('.');
        var hostLast = (hostParts = $this.CreateNew.Domain.split('.'))[hostParts.length - 1];
        var address = Nemiro.Utility.Replace(hostFirst, '\\.', '_') + '_aspnet';

        if ($this.CreateNew.AspNetFastCgiProcessCount == 1) {
          if ($this.CreateNew.AspNetSocket == 'TCP') {
            address = '127.0.0.1:' + $this.Config.AspNetFastCgiPort;
          } else {
            address = 'unix:/tmp/' + $this.CreateNew.Domain;
          }
        }

        var item = new Models.SiteHTANItem(address);
        item.AspNetVersion = $this.CreateNew.AspNetVersion;
        item.Enabled = true;
        item.Socket = address;
        $this.CreateNewHTAN.FastCGI.push(item);

        $this.AddConf($this);
      }

      $this.Scope.TabClick('Nginx');

      $this.CreateConfigDialog.Close();
    }

    private CreateDefault($this: SiteEditorController): void {
      $this.Site = new Models.Site();
      $this.Site.Name = 'New site';
      $this.Site.IsEnabled = true;
      $this.Site.Levels = new Array<string>();
      $this.Site.Conf = new Array<Models.SiteConf>();

      // nginx
      $this.SelectedConfToAdd = 'Nginx';
      $this.CreateNewNginx = new Models.SiteNewNginx();
      $this.CreateNewNginx.Domain = $this.CreateNew.Domain;
      $this.CreateNewNginx.SelectedPath = $this.CreateNew.SelectedPath;
      $this.CreateNewNginx.PhpMode = $this.CreateNew.PhpMode;
      $this.CreateNewNginx.AspNetMode = $this.CreateNew.AspNetMode;
      $this.CreateNewNginx.AspNetFastCgiProcessCount = $this.CreateNew.AspNetFastCgiProcessCount;
      $this.CreateNewNginx.AspNetSocket = $this.CreateNew.AspNetSocket;

      $this.AddConf($this);

      $this.Scope.TabClick('Nginx');
    }

    private GetFolders($this: SiteEditorController, parent?: Models.FileSystemItem): void {
      if ($this.Folders === undefined || $this.Folders == null || $this.Folders.length <= 0) {
        $this.Loading = true;
      }

      var path = '/';

      if (parent !== undefined && parent != null) {
        path = parent.Path;
        parent.Loading = true;
      }
      
      // create request
      var apiRequest = new ApiRequest<Array<Models.FileSystemItem>>($this.Context, 'Sites.GetFolders', { path: path });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        //$this.Context.Timeout(() => {
          if (parent === undefined || parent == null) {
            $this.Folders = response.data;
          } else {
            parent.Children = response.data;
          }
        //});
      };

      apiRequest.CompleteCallback = () => {
        $this.Loading = false;
        if (parent !== undefined && parent != null) {
          parent.Loading = false;
        }
      }

      // execute
      apiRequest.Execute();
    }

    private CreateFolder($this: SiteEditorController): void {
      $this.CreationFolder = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.CreateFolder', { path: $this.SelectedFolder.Path, name: $this.NewFolderName, owner: $this.NewFolderOwnerName });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.GetFolders($this, $this.SelectedFolder);
        $this.CreateFolderDialog.Close();
      };

      apiRequest.CompleteCallback = () => {
        $this.CreationFolder = false;
      }

      // execute
      apiRequest.Execute();

    }

    private RenameFolder($this: SiteEditorController, node: Models.FileSystemItem): void {
      node.Loading = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.RenameFolder', { path: node.Path, name: node.NewName });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        node.Path = response.data.Path;
        node.Name = node.NewName;
        node.RenameMode = false;
      };

      apiRequest.CompleteCallback = () => {
        node.Loading = false;
      }

      // execute
      apiRequest.Execute();
    }

    private DeleteFolder($this: SiteEditorController): void {
      $this.SelectedFolderToDelete.Loading = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.DeleteFolder', { path: $this.SelectedFolderToDelete.Path });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.ConfirmToDeleteFolder.Close();
        $this.GetFolders($this, $this.SelectedFolderToDelete.Parent);
      };

      apiRequest.CompleteCallback = () => {
        $this.SelectedFolderToDelete.Loading = false;
      }

      // execute
      apiRequest.Execute();
    }

    private Save($this: SiteEditorController, needReloading: boolean): void {
      if (Nemiro.Utility.NextInvalidField($('#siteForm'))) {
        return;
      }

      $this.Saving = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.SaveSite', { 'SourceName': $this.SourceName, 'Site': $this.Site, 'IsNew': $this.CreateNew != null });
      
      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Context.Window.location.hash = '#?name=' + $this.Site.Name;
        $this.CreateNew = null;
        $this.Success = true;

        // reloading services
        if (needReloading) {
          // get services to reload
          $this.ReloadingItems = new Array<Models.ReloadingInfo>();

          for (var i = 0; i < $this.Site.Conf.length; i++) {
            $this.ReloadingItems.push(new Models.ReloadingInfo($this.Site.Conf[i].Level));

            if ($this.Site.Conf[i].Level == 'Nginx') {
              if ($this.Site.Conf[i].Source.indexOf('php') != -1) {
                $this.ReloadingItems.push(new Models.ReloadingInfo('PHP-FPM'));
              }
            }
          }

          $this.ReloadServices($this);
        } else {
          $this.Context.Timeout(() => {
            $this.Load($this);
          });
        }
      };

      apiRequest.CompleteCallback = () => {
        $this.Saving = false;
      }

      // execute
      apiRequest.Execute();
    }

    private ReloadServices($this: SiteEditorController): void {
      if ($this.ReloadingItems === undefined || $this.ReloadingItems == null || $this.ReloadingItems.length <= 0) {
        Nemiro.UI.Dialog.Alert(App.Resources.NoServicesToReload, App.Resources.Error);
        return;
      }

      $this.Reloading = true;
      $this.ReloadingDialog.Show();

      for (var i = 0; i < $this.ReloadingItems.length; i++) {
        $this.ReloadService($this, $this.ReloadingItems[i]);
      }
    }

    private ReloadService($this: SiteEditorController, reloadingItem: Models.ReloadingInfo): void {
      if (reloadingItem.Status != '' && reloadingItem.Status != 'Waiting') {
        console.log('Service "' + reloadingItem.Name + '" already processing.');
        return;
      }

      console.log('Reloading "' + reloadingItem.Name + '"...');

      reloadingItem.Status = 'Processing';

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.ReloadServices', { 'services': reloadingItem.Name });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        reloadingItem.Status = 'Success';
      };

      apiRequest.ErrorCallback = (response) => {
        if (response.status == 502 || response.status == 504) {
          $this.Context.Timeout($this.WaitingServerResponse($this, reloadingItem), 1000);
        } else {
          var exceptionMessage = ApiRequest.GetExceptionMessage((<any>response).data);

          if (exceptionMessage != null) {
            exceptionMessage = '<pre>' + exceptionMessage + '</pre>';
          } else {
            exceptionMessage = '';
          }

          Nemiro.UI.Dialog.Alert(Nemiro.Utility.Format(App.Resources.CannotReloadService, [reloadingItem.Name]) + exceptionMessage, App.Resources.Error + ' ' + response.status);
          reloadingItem.Status = 'Error';
        }
      }

      apiRequest.CompleteCallback = () => {
        $this.UpdateReloadingStatus($this);
      }

      // execute
      apiRequest.Execute();
    }

    private WaitingServerResponse($this: SiteEditorController, reloadingItem: Models.ReloadingInfo): any {
      ApiRequest.Echo($this.Context, (response) => {
        if (response.status != 200) {
          Nemiro.UI.Dialog.Alert(Nemiro.Utility.Format(App.Resources.CannotReloadService, [reloadingItem.Name]), App.Resources.Error + ' ' + response.status);
          reloadingItem.Status = 'Error';
        } else {
          reloadingItem.Status = 'Success';
        }

        $this.UpdateReloadingStatus($this);
      }, 5);
    }

    private UpdateReloadingStatus($this: SiteEditorController): void {
      for (var i = 0; i < $this.ReloadingItems.length; i++) {
        if ($this.ReloadingItems[i].Status == '' || $this.ReloadingItems[i].Status == 'Waiting' || $this.ReloadingItems[i].Status == 'Processing') {
          $this.Reloading = true;
          return;
        }
      }

      $this.Reloading = false;
    }

    private PrepareAddConf($this: SiteEditorController): void {
      console.log('PrepareAddConf', $this.AvailableLevels);

      var host = '%DOMAIN HERE%';
      var path = '%PATH HERE%';

      if ($this.CreateNewNginx === undefined || $this.CreateNewNginx == null) {
        if ($this.AvailableLevels.indexOf('Apache') == -1) {
          // has apache, extract host and root path
          var r_host = /ServerName(\s+)(\S+)/i;
          var r_root = /DocumentRoot(\s+)(\S+)/i;

          var conf = $this.GetConf($this, 'Apache');

          if (conf != null) {
            var arr = r_host.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              host = arr[2];
            }

            arr = r_root.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              path = arr[2];
            }
          }
        }

        if (host == '%DOMAIN HERE%' || host == '') {
          host = $this.Site.Name;
        }

        $this.CreateNewNginx = new Models.SiteNewNginx();
        $this.CreateNewNginx.Domain = host;
        $this.CreateNewNginx.SelectedPath = path;
      }

      if ($this.CreateNewApache === undefined || $this.CreateNewApache == null) {
        if ($this.AvailableLevels.indexOf('Nginx') == -1) {
          // has nginx, extract host and root path
          var r_host = /server_name(\s+)([^;]+)/i;
          var r_root = /root(\s+)([^;]+)/i;

          var conf = $this.GetConf($this, 'Nginx');

          if (conf != null) {
            // masked \#
            conf = conf.replace(/(\\\#)/g, String.fromCharCode(1));
            // remove comments
            conf = conf.replace(/(\#([^\r\n]*))/g, '');
            // restore \#
            conf = conf.replace(/\x01/g, '\#');

            var arr = r_host.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              host = arr[2];
            }

            arr = r_root.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              path = arr[2];
            }
          } 
        }

        if (host == '%DOMAIN HERE%' || host == '') {
          host = $this.Site.Name;
        }

        $this.CreateNewApache = new Models.SiteNewApache();
        $this.CreateNewApache.Domain = host;
        $this.CreateNewApache.SelectedPath = path;
      }

      if ($this.CreateNewHTAN === undefined || $this.CreateNewHTAN == null) {
        var fastCgiList = new Array<Models.SiteHTANItem>();

        if ($this.AvailableLevels.indexOf('Nginx') == -1) {
          var r_host = /server_name(\s+)([^;]+)/i;
          var r_root = /root(\s+)([^;]+)/i;
          var r_fastcgi_pass = /fastcgi_pass(\s+)([^;]+)/gi;
          var r_fastcgi_pass_sgl = /fastcgi_pass(\s+)([^;]+)/i;

          var conf = $this.GetConf($this, 'Nginx');
          console.log('conf', conf);
          if (conf != null) {
            // masked \#
            conf = conf.replace(/(\\\#)/g, String.fromCharCode(1));
            // remove comments
            conf = conf.replace(/(\#([^\r\n]*))/g, '');
            // restore \#
            conf = conf.replace(/\x01/g, '\#');

            var arr = r_host.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              host = arr[2];
            }

            arr = r_root.exec(conf);

            if (arr != null && arr.length >= 2 && arr[2] != '') {
              path = arr[2];
            }

            var arr2 = conf.match(r_fastcgi_pass);
            if (arr2 != null) {
              for (var i = 0; i < arr2.length; i++) {
                arr = r_fastcgi_pass_sgl.exec(arr2[i]);
                // console.log('fastcgi_pass', i, arr2[i], arr);
                fastCgiList.push(new Models.SiteHTANItem(arr[2]));
              }
            }

            console.log('fastCgiList', fastCgiList);
          }
        }

        if (host == '%DOMAIN HERE%' || host == '') {
          host = $this.Site.Name;
        }

        $this.CreateNewHTAN = new Models.SiteNewHTAN();
        $this.CreateNewHTAN.Domain = host;
        $this.CreateNewHTAN.SelectedPath = path;
        $this.CreateNewHTAN.FastCGI = fastCgiList;
      }

      // only one available conf
      if ($this.AvailableLevels.length == 1) {
        // select it
        $this.SelectedConfToAdd = $this.AvailableLevels[0];
      } else {
        if ($this.SelectedConfToAdd !== undefined && $this.SelectedConfToAdd != null && $this.SelectedConfToAdd != '' && $this.AvailableLevels.indexOf($this.SelectedConfToAdd) == -1) {
          $this.SelectedConfToAdd = '';
        }
      }
    }

    private AddConf($this: SiteEditorController): void {
      console.log('AddConf', $this.SelectedConfToAdd, $this['CreateNew' + $this.SelectedConfToAdd]);

      if ($this.SelectedConfToAdd == 'Nginx') {
        var logPath = Nemiro.Utility.DirectoryName($this.CreateNewNginx.SelectedPath) + '/' + $this.Config.LogFolderName;

        var hostParts = $this.CreateNewNginx.Domain.split('.');
        if (hostParts.length > 1) { hostParts.pop(); }
        var hostFirst = hostParts.join('.');
        var hostLast = (hostParts = $this.CreateNewNginx.Domain.split('.'))[hostParts.length - 1];

        var upstreams = '';

        var conf = 'server {\n';
        conf += `  root ${$this.CreateNewNginx.SelectedPath};` + '\n';
        conf += `  server_name ${$this.CreateNewNginx.Domain};` + '\n';

        if (($this.CreateNewNginx.EventLogs & 1) == 1) {
          conf += `  error_log ${logPath}/nginx_${$this.CreateNewNginx.Domain}_error.log error` + '\n';
        }

        if (($this.CreateNewNginx.EventLogs & 2) == 2) {
          conf += `  access_log ${logPath}/nginx_${$this.CreateNewNginx.Domain}_access.log` + '\n';
        }

        //conf += '\n';

        //conf += '  location / {\n';
        conf += '  index ';

        if ($this.CreateNewNginx.PhpMode != 'Off') {
          conf += 'index.php ';
        }

        if ($this.CreateNewNginx.AspNetMode != 'Off') {
          conf += 'Default.aspx Index.aspx ';
        }

        conf += 'index.html index.htm;\n';
        //conf += '  }\n\n';
        conf += '\n';

        if ($this.CreateNewNginx.PhpMode != 'Off') {
          // PHP
          var upstreamName = Nemiro.Utility.Replace(hostFirst, '\\.', '_') + '_php';

          conf += $this.MakeNginxPhpConf
          (
            $this.CreateNewNginx.Domain,
            $this.CreateNewNginx.PhpMode,
            $this.CreateNewNginx.PhpFastCgiProcessCount,
            $this.CreateNewNginx.PhpSocket,
            upstreamName,
            $this.Config.PhpFastCgiPort
          );
          conf += '\n';

          if ($this.CreateNewNginx.PhpMode == 'FPM' && $this.CreateNewNginx.PhpFastCgiProcessCount > 1) {
            if (upstreams.length > 0) {
              upstreams += '\n';
            }

            upstreams += $this.MakeNginxUpstream
            (
              upstreamName,
              $this.CreateNewNginx.PhpFastCgiProcessCount,
              $this.CreateNewNginx.PhpSocket,
              (hostFirst + '-{0}.' + hostLast),
              $this.Config.PhpFastCgiPort
            );
          }
        }

        if ($this.CreateNewNginx.AspNetMode != 'Off') {
          // ASP.NET
          var upstreamName = Nemiro.Utility.Replace(hostFirst, '\\.', '_') + '_aspnet';

          conf += $this.MakeNginxAspNetConf
          (
            $this.CreateNewNginx.Domain,
            $this.CreateNewNginx.AspNetMode,
            $this.CreateNewNginx.AspNetFastCgiProcessCount,
            $this.CreateNewNginx.AspNetSocket,
            upstreamName,
            $this.Config.AspNetFastCgiPort
          );
          conf += '\n';

          if ($this.CreateNewNginx.AspNetMode == 'FASTCGI' && $this.CreateNewNginx.AspNetFastCgiProcessCount > 1) {
            if (upstreams.length > 0) {
              upstreams += '\n';
            }

            upstreams += $this.MakeNginxUpstream
            (
              upstreamName,
              $this.CreateNewNginx.AspNetFastCgiProcessCount,
              $this.CreateNewNginx.AspNetSocket,
              (hostFirst + '-{0}.' + hostLast),
              $this.Config.AspNetFastCgiPort
            );
          }
        }

        // other
        conf += '  # Static files caching\n';
        conf += '  location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {\n';
        conf += '    expires max;\n';
        conf += '    log_not_found off;\n';
        conf += '    access_log off;\n';
        conf += '  }\n\n';

        /*
        if ($this.CreateNewNginx.PhpMode != 'Off') {
          conf += '  # Disable viewing .htaccess and .htpassword\n';
          conf += '  location ~ /\.ht {\n';
          conf += '    deny all;\n';
          conf += '  }\n\n';
        }
        */
        /*
        if ($this.CreateNewNginx.AspNetMode != 'Off') {
          conf += '  # disable viewing .config\n';
          conf += '  location ~ /\.config {\n';
          conf += '    deny all;\n';
          conf += '  }\n\n';
        }*/
        // --

        conf += '}';

        if (upstreams.length > 0) {
          conf = upstreams + '\n' + conf;
        }

        $this.Site.Conf.push(new Models.SiteConf('Nginx', conf));
        $this.Site.Levels.push('Nginx');

        $this.CreateNewNginx = null;
      }
      else if ($this.SelectedConfToAdd == 'Apache') {
        var logPath = Nemiro.Utility.DirectoryName($this.CreateNewApache.SelectedPath) + '/' + $this.Config.LogFolderName;
        var conf = `<VirtualHost ${$this.Config.ApacheHost}:${$this.Config.ApachePort}>` + '\n';
        conf += `  DocumentRoot ${$this.CreateNewApache.SelectedPath}` + '\n';
        conf += `  ServerName ${$this.CreateNewApache.Domain}` + '\n';

        if (($this.CreateNewApache.EventLogs & 1) == 1) {
          conf += `  ErrorLog ${logPath}/apache_${$this.CreateNewApache.Domain}_error.log error` + '\n';
        }

        if (($this.CreateNewApache.EventLogs & 2) == 2) {
          conf += `  CustomLog ${logPath}/apache_${$this.CreateNewApache.Domain}_access.log` + ' common\n';
        }

        if ($this.CreateNewApache.AspNetVersion != 'Off') {
          conf += '\n';
          conf += $this.MakeApacheAspNetHeaders($this.CreateNewApache.AspNetVersion, $this.CreateNewApache.Domain, $this.CreateNewApache.SelectedPath, $this.CreateNewApache.AspNetIOMapForAll, $this.CreateNewApache.AspNetDebug);
          conf += '\n';
          conf += $this.MakeApacheAspNetLocation($this.CreateNewApache.Domain);
        }
        else {
          conf += '\n';
          conf += $this.MakeApacheDefaultLocation();
        }

        // mono-ctrl
        if ($this.CreateNewApache.MonoCtrl) {
          conf += '\n';
          conf += '  <Location /mono>\n';
          conf += '    SetHandler mono-ctrl\n';
          conf += '    AllowOverride All\n';
          conf += '    Order Allow,Deny\n';
          conf += '    Allow from all\n';
          conf += '    Require all granted\n';
          conf += '  </Location>\n';
        }

        // svn
        if ($this.CreateNewApache.WebDavSvn) {
          conf += '\n';
          conf += '  # You can specify custom home directory of site\n';
          conf += '  # for browse list of repositories:\n';
          conf += '  # <Directory /usr/share/svn-web>\n';
          conf += '  #  AllowOverride All\n';
          conf += '  #  Allow from all\n';
          conf += '  #  Require all granted\n';
          conf += '  # </Directory>\n\n';

          conf += '  <Location /svn>\n';
          conf += '    DAV svn\n';
          conf += '    # Enter the path where your repositories are located:\n';
          conf += '    SVNParentPath /var/svn/\n';
          conf += '    SVNListParentPath On\n';
          conf += '    # You can make your own styles for browsing a repositories:\n';
          conf += '    # SVNIndexXSLT /usr/share/svn-web/index.xsl\n\n';

          conf += '    # If you want to restrict access, you can enable authorization:\n';
          conf += '    # AuthType Basic\n';
          conf += '    # AuthName "Subversion Repository"\n';
          conf += '    # Enter the path to the password file\n';
          conf += '    # AuthUserFile /etc/apache2/dav_svn.passwd\n';
          conf += '    # Require valid-user\n\n';

          conf += '    # Specify a file of access rules to the repositories:\n';
          conf += '    # <IfModule mod_authz_svn.c>\n';
          conf += '    #  AuthzSVNAccessFile /etc/apache2/dav_svn.authz\n';
          conf += '    # </IfModule>\n';
          conf += '  </Location>\n';
        }

        conf += '</VirtualHost>';

        $this.Site.Conf.push(new Models.SiteConf('Apache', conf));
        $this.Site.Levels.push('Apache');

        $this.CreateNewApache = null;
      }
      else if ($this.SelectedConfToAdd == 'HTAN') {
        // get nginx config for parse upstreams
        var nginx = $this.GetConf($this, 'Nginx');
        if (nginx != null) {
          // masked \#
          nginx = nginx.replace(/(\\\#)/g, String.fromCharCode(1));
          // remove comments
          nginx = nginx.replace(/(\#([^\r\n]*))/g, '');
          // restore \#
          nginx = nginx.replace(/\x01/g, '\#');
        }
        // --

        // make htan config
        var commands = '', items = '';
        var commandKyes = [], commandNames = [];
        for (var i = 0; i < $this.CreateNewHTAN.FastCGI.length; i++) {
          var item = $this.CreateNewHTAN.FastCGI[i];
          if (!item.Enabled) {
            continue;
          }

          // make command key and name
          var key = item.AspNetVersion + ';' + (item.User != 'root' ? item.User : '') + ';' + (item.Group != 'root' ? item.Group : '');
          var name = 'command-' + (commandKyes.length + 1); // name for new command

          // search command by key
          if (commandKyes.indexOf(key) != -1) {
            // name for existing command
            name = commandNames[commandKyes.indexOf(key)];
          }

          // make items
          if (item.Socket.indexOf(':') == -1) {
            // is upstream name, parse nginx
            if (nginx != null) {
              // extract upstream
              var upstream = new RegExp('upstream(\\s+)' + item.Socket + '(\\s*)\\{([^\\}]+?)\\}', 'm').exec(nginx);
              if (upstream == null) {
                if (items.length > 0) { items += '\n'; }
                items += '    <!--Error: Upstream "' + item.Socket + '" not found in the Nginx config-->';
              } else {
                // parse addresses
                var arr = upstream[3].match(/server(\s+)([^\s\;]+)/g);
                if (arr == null) {
                  items += '    <!--Error: Cannot parse upstream "' + item.Socket + '"-->';
                } else {
                  for (var j = 0; j < arr.length; j++) {
                    if (items.length > 0) { items += '\n'; }
                    items += '    <add address="' + /server(\s+)([^\s\;]+)/.exec(arr[j])[2] + '" command="' + name + '" />';
                  }
                }
              }
            } else {
              if (items.length > 0) { items += '\n'; }
              items += '    <!--Error: Nginx is required for upstream-->';
            }
          }
          else {
            // single address
            if (items.length > 0) { items += '\n'; }
            items += '    <add address="' + item.Socket + '" command="' + name + '" />';
          }

          if (commandKyes.indexOf(key) != -1) {
            continue;
          }

          // make command
          if (commands.length > 0) { commands += '\n'; }

          commands += '    <add\n';
          commands += '      name="' + name + '"\n';

          if (item.User != '') {
            commands += '      user="' + item.User + '"\n';
          }

          if (item.Group != '') {
            commands += '      group="' + item.Group + '"\n';
          }

          var exec = '/usr/bin/fastcgi-mono-server4';

          if (item.AspNetVersion == '1.0') {
            exec = '/usr/bin/fastcgi-mono-server';
          }
          else if (item.AspNetVersion == '2.0') {
            exec = '/usr/bin/fastcgi-mono-server2';
          }

          commands += '      exec="' + exec + '"\n';
          commands += `      arguments="/applications=/:${$this.CreateNewHTAN.SelectedPath} /socket={socket} /multiplex=True /verbose=True"` + '\n'; //  /printlog=True

          commands += '    />';

          // add command key and name to existing commands
          commandNames.push(name);
          commandKyes.push(key);
        }

        var conf = '';
        conf += '<configuration>\n';
        conf += '  <fastCGI>\n';
        conf += items + '\n';
        conf += '  </fastCGI>\n';
        conf += '  <commands>\n';
        conf += commands + '\n';
        conf += '  </commands>\n';
        conf += '</configuration>';

        $this.Site.Conf.push(new Models.SiteConf('HTAN', conf));
        $this.Site.Levels.push('HTAN');

        $this.CreateNewHTAN = null;
      }

      $this.UpdateCanAddConf($this);

      $this.Scope.TabClick($this.Site.Conf[$this.Site.Conf.length - 1].Level);
    }

    private MakeNginxUpstream(name: string, processes: number, socket: string, unixName: string, port: number): string {
      var result = 'upstream ' + name + ' {';

      for (var i = 0; i < processes; i++) {
        if (result.length > 0) {
          result += '\n';
        }

        if (socket == 'TCP') {
          result += '  server 127.0.0.1:' + (port + i) + ';';
        } else {
          result += '  server unix:/tmp/' + Nemiro.Utility.Format(unixName, [i + 1]) + ';';
        }
      }
      result += '\n}\n';

      return result;
    }

    private MakeNginxPhpConf(host: string, mode: string, processes: number, socket: string, upstream: string, port: number): string {
      var conf = '';

      if (mode == 'FPM') {
        conf += '  # PHP-FPM\n';
        conf += '  location ~ \\.php$ {\n';
        if (processes == 1) {
          if (socket == 'TCP') {
            conf += '    fastcgi_pass  127.0.0.1:' + port.toString() + ';\n';
          } else {
            conf += '    fastcgi_pass  unix:/var/run/php5-fpm.sock;\n'; //TODO //unix:/tmp/' + host + ';\n';
          }
        } else {
          conf += '    fastcgi_pass  ' + upstream + ';\n';
        }
        
        conf += '    fastcgi_index index.php;\n\n';
        conf += '    include       fastcgi_params;\n\n';
        conf += '    fastcgi_split_path_info ^(.+\\.php)(/.+)$;\n';
        conf += '    fastcgi_param PATH_INFO       $fastcgi_path_info;\n';
        conf += '    fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;\n';
        conf += '    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n';
        conf += '  }\n';
      } else {
        conf += '  # PHP to Apache\n';
        conf += '  location ~ \\.php$ {\n';
        conf += '    proxy_set_header X-Real-IP  $remote_addr;\n';
        conf += '    proxy_set_header X-Forwarded-For $remote_addr;\n';
        conf += '    proxy_set_header Host $host;\n';
        conf += `    proxy_pass http://${this.Config.ApacheHost}:${this.Config.ApachePort};` + '\n';
        conf += '  }\n';
      }

      return conf;
    }

    private MakeNginxAspNetConf(host: string, mode: string, processes: number, socket: string, upstream: string, port: number): string {
      var conf = '';
      if (mode == 'FASTCGI') {
        conf += '  # ASP.NET FastCGI\n';
        conf += '  location / {\n'; // ~ \.(aspx|asmx|ashx|axd|asax|ascx|cshtml|vbhtml|soap|rem|axd|cs|vb|config|dll)$

        //conf += '    proxy_set_header X-Real-IP  $remote_addr;\n';
        //conf += '    proxy_set_header X-Forwarded-For $remote_addr;\n';

        if (processes == 1) {
          if (socket == 'TCP') {
            conf += '    fastcgi_pass  127.0.0.1:' + port.toString() + ';\n';
          } else {
            conf += '    fastcgi_pass  unix:/tmp/' + host + ';\n';
          }
        } else {
          conf += '    fastcgi_pass  ' + upstream + ';\n';
        }
        
        conf += '    include       fastcgi_params;\n';
        conf += '    # fastcgi_intercept_errors on; # disable asp.net error handlers\n';
        conf += '    fastcgi_param PATH_INFO $fastcgi_path_info;\n';
        conf += '    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n';
        conf += '  }\n';
      } else {
        conf += '  # ASP.NET to Apache\n';
        conf += '  location / {\n'; // ~ \.(aspx|asmx|ashx|axd|asax|ascx|cshtml|vbhtml|soap|rem|axd|cs|vb|config|dll)$
        conf += '    proxy_set_header X-Real-IP  $remote_addr;\n';
        conf += '    proxy_set_header X-Forwarded-For $remote_addr;\n';
        conf += '    proxy_set_header Host $host;\n';
        conf += '    proxy_pass http://127.0.0.1:8080;\n';
        conf += '  }\n';
      }

      return conf;
    }

    private MakeApacheAspNetHeaders(version: string, host: string, root: string, ioMapForAll: boolean, debug: boolean): string {
      var monoServer = 'mod-mono-server4';

      if (version == '2.0') {
        monoServer = 'mod-mono-server2';
      }
      else if (version == '1.0') {
        monoServer = 'mod-mono-server';
      }

      var conf = '';

      conf += `  MonoServerPath ${host} "/usr/bin/${monoServer}"` + '\n';

      if (debug) {
        conf += `  MonoDebug ${host}` + '\n';
      }

      if (ioMapForAll) {
        conf += `  MonoSetEnv ${host} MONO_IOMAP=all` + '\n';
      }

      conf += `  MonoApplications ${host} "/:${root}"` + '\n';

      return conf;
    }

    private MakeApacheAspNetLocation(host: string): string {
      var conf = '';

      conf += '  <Location />\n';
      conf += '    Options FollowSymLinks Indexes\n';
      conf += '    AllowOverride All\n';
      conf += '    Order Allow,Deny\n';
      conf += '    Allow from all\n';
      conf += '    Require all granted\n';
      conf += '    DirectoryIndex index.html index.htm\n';
      conf += `    MonoSetServerAlias ${host}` + '\n';
      conf += '    SetHandler mono\n';
      conf += '    SetOutputFilter DEFLATE\n';
      conf += '    SetEnvIfNoCase Request_URI ".(?:gif|jpe?g|png|js|css|txt|map|ttf|otf|woff|woff2|eot|svg)$" no$\n';
      conf += '    DirectoryIndex Default.aspx default.aspx Index.aspx index.aspx Index.cshtml index.cshtml Index.vbhtml index.vbhtml\n';
      conf += '  </Location>\n';

      return conf;
    }

    private MakeApacheDefaultLocation(): string {
      var conf = '';

      conf += '  <Location />\n';
      // conf += '    Options FollowSymLinks Indexes\n';
      conf += '    AllowOverride All\n';
      conf += '    Order Allow,Deny\n';
      conf += '    Allow from all\n';
      conf += '    Require all granted\n';
      conf += '    DirectoryIndex index.html index.htm index.php\n';
      conf += '  </Location>\n';

      return conf;
    }

    private GetConf($this: SiteEditorController, level: string): string {
      for (var i = 0; i < $this.Site.Conf.length; i++) {
        if ($this.Site.Conf[i].Level.toLowerCase() == level.toLowerCase()) {
          return $this.Site.Conf[i].Source;
        }
      }

      return null;
    }

    private UpdateCanAddConf($this: SiteEditorController): void {
      var result = [];

      angular.forEach($this.LevelsList, (item) => {
        if ($this.Site.Levels.indexOf(item) == -1) {
          result.push(item);
        }
      });

      $this.AvailableLevels = result;

      console.log('Levels', $this.Site.Levels, $this.AvailableLevels);

      $this.CanAddConf = ($this.AvailableLevels.length > 0);

      if ($this.Site.Conf.length <= 0) {
        $this.PrepareAddConf($this);
      }
    }

    //#endregion

  }

} 