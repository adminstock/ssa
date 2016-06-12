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

  export class UserEditorController implements Nemiro.IController {

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** The list of groups. */
    public get Groups(): Array<Models.Group> {
      return this.Scope.Groups;
    }
    public set Groups(value: Array<Models.Group>) {
      this.Scope.Groups = value;
    }

    /** The source data of user. */
    public get SourceUser(): Models.User {
      return this.Scope.SourceUser;
    }
    public set SourceUser(value: Models.User) {
      this.Scope.SourceUser = value;
    }

    /** The data of current user. */
    public get User(): Models.User {
      return this.Scope.User;
    }
    public set User(value: Models.User) {
      this.Scope.User = value;
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

    public get Creation(): boolean {
      return this.Scope.Creation;
    }
    public set Creation(value: boolean) {
      this.Scope.Creation = value;
    }

    /** Success result indicator. */
    public get Success(): boolean {
      return this.Scope.Success;
    }
    public set Success(value: boolean) {
      this.Scope.Success = value;
      //if (value) {
        //this.Context.Timeout(() => { this.Context.Location.hash(null); }, 3000);
        //this.Context.Location.hash('success');
        //this.Context.AnchorScroll();
      //} else {
        //this.Context.Location.hash(null);
      //}
    }

    /**
     * Is new or existing user.
     */
    public get IsNew(): boolean {
      return this.Scope.IsNew;
    }
    public set IsNew(value: boolean) {
      this.Scope.IsNew = value;
    }

    /**
     * Indicates the need for change the user login.
     */
    public get SetLogin(): boolean {
      return this.Scope.SetLogin;
    }
    public set SetLogin(value: boolean) {
      this.Scope.SetLogin = value;
    }

    /**
     * Indicates the need for change the user password.
     */
    public get SetPassword(): boolean {
      return this.Scope.SetPassword;
    }
    public set SetPassword(value: boolean) {
      this.Scope.SetPassword = value;
    }

    public get NewPassword(): string {
      return this.Scope.NewPassword;
    }
    public set NewPassword(value: string) {
      this.Scope.NewPassword = value;
    }

    public get ConfirmPassword(): string {
      return this.Scope.ConfirmPassword;
    }
    public set ConfirmPassword(value: string) {
      this.Scope.ConfirmPassword = value;
    }

    public get NoCreateHome(): boolean {
      return this.Scope.NoCreateHome;
    }
    public set NoCreateHome(value: boolean) {
      this.Scope.NoCreateHome = value;
    }

    public get IsSystem(): boolean {
      return this.Scope.IsSystem;
    }
    public set IsSystem(value: boolean) {
      this.Scope.IsSystem = value;
    }

    public get ShellList(): Nemiro.Collections.NameValueCollection {
      return this.Scope.ShellList;
    }
    public set ShellList(value: Nemiro.Collections.NameValueCollection) {
      this.Scope.ShellList = value;
    }

    constructor(context: Nemiro.AngularContext) {
      var $this = this;
      $this.Context = context;
      $this.Scope = $this.Context.Scope;
            
      // handlers
      $this.Scope.GroupClick = (group: Models.Group) => { $this.GroupClick($this, group); }
      $this.Scope.SaveAccount = () => { $this.SaveAccount($this); }
      $this.Scope.SaveGroups = () => { $this.SaveGroups($this); }
      $this.Scope.SaveGECOS = () => { $this.SaveGECOS($this); }
      $this.Scope.CreateUser = () => { $this.CreateUser($this); }

      // list of shell
      var shellList = new Nemiro.Collections.NameValueCollection();
      shellList.AddItem('/bin/false', 'false');
      shellList.AddItem('/usr/sbin/nologin', 'nologin');
      shellList.AddItem('/bin/bash', 'bash');
      $this.ShellList = shellList;
      
      // load groups list
      $this.GetGroups($this);
    }

    /**
     * Loads groups list from server.
     */
    private GetGroups($this: UserEditorController): void {
      $this = $this || this;
      $this.Loading = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.Group>>($this.Context, 'Users.GetGroups');

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Groups = response.data;
        // get user data
        $this.GetUser($this);
      };

      // execute
      apiRequest.Execute();
    }

    /**
     * Loads user data from server.
     */
    private GetUser($this: UserEditorController): void {
      $this = $this || this;
      $this.Loading = true;

      if ($this.Context.Location.search().login === undefined || $this.Context.Location.search().login == null || $this.Context.Location.search().login == '') {
        $this.IsNew = true;
        $this.User = new Models.User();
        $this.SourceUser = new Models.User();
        $this.SourceUser.Shell = $this.User.Shell = '/bin/false';
        $this.Groups = $this.Context.Filter('OrderGroups')($this.Groups, $this.User.Groups);
        $this.Loading = false;
        return;
      }

      $this.IsNew = false;

      // create request
      var apiRequest = new ApiRequest<Models.User>($this.Context, 'Users.GetUserByLogin', { login: $this.Context.Location.search().login });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        if ($this.ShellList.Get(response.data.Shell) == null) {
          $this.ShellList.AddItem(response.data.Shell, response.data.Shell);
        }

        // set user data
        $this.User = response.data;

        // clone user data
        $this.SourceUser = $.parseJSON($.toJSON(response.data));

        // sorting groups
        $this.Groups = $this.Context.Filter('OrderGroups')($this.Groups, $this.User.Groups);

        $this.Loading = false;
      };

      apiRequest.CompleteCallback = () => {
        $this.Loading = false;
      }

      // execute
      apiRequest.Execute();
    }

    private GroupClick($this: UserEditorController, group: Models.Group): void {
      if ($this.User.Groups == undefined || $this.User.Groups == null) {
        $this.User.Groups = new Array<string>();
      }

      if ($this.User.Groups.indexOf(group.Name) == -1) {
        $this.User.Groups.push(group.Name);
      } else {
        $this.User.Groups.splice($this.User.Groups.indexOf(group.Name), 1);
      }
    }

    private SaveAccount($this: UserEditorController): void {
      $this.Success = false;

      if (!(<HTMLFormElement>$('#accountForm')[0]).checkValidity()) {
        $('#accountForm').find(':invalid').first().focus();
        return;
      }

      $this.Saving = true;

      var account = new Models.AccountUpdate();
      account.Login = $this.SourceUser.Login;

      if ($this.SetLogin && $this.User.Login != $this.SourceUser.Login) {
        account.SetLogin = true;
        account.NewLogin = $this.User.Login;
      }

      if ($this.SetPassword && $this.NewPassword != '') {
        account.SetPassword = true;
        account.NewPassword = $this.NewPassword;
      }

      if ($this.User.Shell != $this.SourceUser.Shell) {
        account.SetShell = true;
        account.NewShell = $this.User.Shell;
      }

      var apiRequest = new ApiRequest<boolean>($this.Context, 'Users.UpdateUserAccount', account);

      apiRequest.SuccessCallback = (response) => {
        $this.Success = true;
        Nemiro.UI.Dialog.Alert(App.Resources.TheAccountHasBeenUpdated, App.Resources.Success, App.Resources.Ok,() => {
          $this.Context.Window.location.hash = '#?login=' + $this.User.Login;
          $this.SetPassword = $this.SetLogin = false;
          $this.NewPassword = $this.ConfirmPassword = '';
          $this.GetUser($this);
        });
      };

      apiRequest.CompleteCallback = () => {
        $this.Saving = false;
      };

      apiRequest.Execute();
    }

    private SaveGECOS($this: UserEditorController): void {
      $this.Success = false;

      if (Nemiro.Utility.NextInvalidField('#gecosForm')) {
        return;
      }

      $this.Saving = true;
      
      var apiRequest = new ApiRequest<boolean>($this.Context, 'Users.UpdateUserGECOS', $this.User);
      
      apiRequest.SuccessCallback = (response) => {
        $this.Success = true;
        Nemiro.UI.Dialog.Alert(App.Resources.TheUserHasBeenUpdated, App.Resources.Success, App.Resources.Ok,() => {
          $this.Context.Window.location.hash = '#?login=' + $this.User.Login;
          $this.GetUser($this);
        });
      };

      apiRequest.CompleteCallback = () => {
        $this.Saving = false;
      }

      apiRequest.Execute();
    }

    private SaveGroups($this: UserEditorController): void {
      $this.Success = false;
      $this.Saving = true;
      
      var apiRequest = new ApiRequest<boolean>($this.Context, 'Users.UpdateUserGroups', $this.User);
      
      apiRequest.SuccessCallback = (response) => {
        $this.Success = true;
        Nemiro.UI.Dialog.Alert(App.Resources.TheUserGroupsHasBeenUpdated, App.Resources.Success, App.Resources.Ok,() => {
          $this.Context.Window.location.hash = '#?login=' + $this.User.Login;
          $this.GetUser($this);
        });
      };

      apiRequest.CompleteCallback = () => {
        $this.Saving = false;
      }

      apiRequest.Execute();
    }

    /**
     * Sends request to create a new user.
     */
    private CreateUser($this: UserEditorController): void {
      if (Nemiro.Utility.NextInvalidField('#accountForm')) {
        return;
      }

      if (Nemiro.Utility.NextInvalidField('#gecosForm')) {
        return;
      }

      $this.Creation = true;
      $this.Success = false;

      var createUser = <Models.CreateUser>$.parseJSON($.toJSON($this.User));
      createUser.IsSystem = $this.IsSystem;
      createUser.NoCreateHome = $this.NoCreateHome;
      createUser.Password = $this.NewPassword;

      // create request
      var apiRequest = new ApiRequest<Models.User>($this.Context, 'Users.CreateUser', createUser);
      
      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        Nemiro.UI.Dialog.Alert(App.Resources.TheUserHasBeenCreated, App.Resources.Success, App.Resources.Ok,() => {
          $this.Context.Window.location.hash = '#?login=' + $this.User.Login;
          $this.GetUser($this);
        });
      };

      apiRequest.CompleteCallback = () => {
        $this.Creation = false;
      };

      // execute
      apiRequest.Execute();
    }

  }

} 