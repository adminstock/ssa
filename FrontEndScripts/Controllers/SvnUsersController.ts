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
   * Represents the controller for the users management of the Subversion server.
   */
  export class SvnUsersController implements Nemiro.IController {

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** The list of users. */
    public get Users(): Array<Models.SvnUser> {
      return this.Scope.Users;
    }
    public set Users(value: Array<Models.SvnUser>) {
      this.Scope.Users = value;
    }

    /** Current user. */
    public get CurrentUser(): Models.SvnUser {
      return this.Scope.CurrentUser;
    }
    public set CurrentUser(value: Models.SvnUser) {
      this.Scope.CurrentUser = value;
    }

    /** The source data of current user. */
    public get SourceUser(): Models.SvnUser {
      return this.Scope.SourceUser;
    }
    public set SourceUser(value: Models.SvnUser) {
      this.Scope.SourceUser = value;
    }

    /**
     * The list of all subversion groups.
     */
    public get Groups(): Array<string> {
      return this.Scope.Groups;
    }
    public set Groups(value: Array<string>) {
      this.Scope.Groups = value;
    }

    /** Search string. */
    public get Search(): string {
      return this.Scope.Search;
    }
    public set Search(value: string) {
      this.Scope.Search = value;
    }

    /** Loading indicator. */
    public get Loading(): boolean {
      return this.Scope.Loading;
    }
    public set Loading(value: boolean) {
      this.Scope.Loading = value;
    }

    public get IsNew(): boolean {
      return this.Scope.IsNew;
    }
    public set IsNew(value: boolean) {
      this.Scope.IsNew = value;
    }

    public get SetLogin(): boolean {
      return this.Scope.SetLogin;
    }
    public set SetLogin(value: boolean) {
      this.Scope.SetLogin = value;
    }

    public get SetPassword(): boolean {
      return this.Scope.SetPassword;
    }
    public set SetPassword(value: boolean) {
      this.Scope.SetPassword = value;
    }

    public get SelectedUserToRemove(): string {
      return this.Scope.SelectedUserToRemove;
    }
    public set SelectedUserToRemove(value: string) {
      this.Scope.SelectedUserToRemove = value;
    }

    private Editor: Nemiro.UI.Dialog;
    private ConfirmUserRemove: Nemiro.UI.Dialog;

    constructor(context: Nemiro.AngularContext) {
      var $this = this;
      
      $this.Context = context;
      $this.Scope = $this.Context.Scope;
      $this.Search = $this.Context.Location.search()['search'];
      $this.Editor = Nemiro.UI.Dialog.CreateFromElement($('#svnUser'));
      $this.ConfirmUserRemove = Nemiro.UI.Dialog.CreateFromElement($('#confirmSvnUserRemove'));

      $this.Scope.LoadUsers = () => { $this.LoadUsers($this); }

      $this.Scope.SearchUsers = () => {
        $this.Context.Location.search('search', $this.Search);
        $this.LoadUsers($this);
      }

      $this.Scope.ResetSearch = () => {
        $this.Search = '';
        $this.Context.Location.search('search', null);
        $this.LoadUsers($this);
      }

      $this.Scope.EditUser = (u?: Models.SvnUser) => { $this.EditUser($this, u); }
      $this.Scope.SaveUser = () => { $this.SaveUser($this); }
      $this.Scope.DeleteUser = () => { $this.DeleteUser($this); }
      $this.Scope.GroupClick = (group: string) => { $this.GroupClick($this, group); }
      $this.Scope.ShowDialogToDeleteUser = (login: string) => {
        $this.SelectedUserToRemove = login;
        $this.ConfirmUserRemove.Show();
      };

      $this.LoadUsers($this);
    }

    private LoadUsers($this: SvnUsersController): void {
      $this = $this || this;
      $this.Loading = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.SvnUser>>($this.Context, 'Svn.GetUsers', { search: $this.Search });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Users = response.data;
      };

      apiRequest.CompleteCallback = () => {
        $this.Loading = false;
        $this.Scope.$parent.CloseProgress();
      };

      // execute
      apiRequest.Execute();
    }

    private EditUser($this: SvnUsersController, user?: Models.SvnUser): void {
      $this.SetLogin = false;
      $this.SetPassword = false;
      $this.Scope.ConfirmPassword = '';
      $this.Loading = true;
      var apiRequest = null;

      if (user === undefined || user == null) {

        $this.IsNew = true;
        $this.SourceUser = new Models.SvnUser();
        $this.SourceUser.Login = App.Resources.NewUser;
        $this.CurrentUser = new Models.SvnUser();

        $this.Scope.$parent.ShowProgress(App.Resources.PreparingFormWait, App.Resources.Preparing);

        apiRequest = new ApiRequest<Array<string>>($this.Context, 'Svn.GetGroupNames');

        apiRequest.SuccessCallback = (response) => {
          $this.Groups = response.data;
          $this.Editor.Show();
        };

        apiRequest.CompleteCallback = () => {
          $this.Loading = false;
          $this.Scope.$parent.CloseProgress();
        };

        apiRequest.Execute();

      } else {

        $this.Scope.$parent.ShowProgress(App.Resources.ObtainingTheUserWait, App.Resources.Loading);

        $this.IsNew = false;

        // load data from server
        apiRequest = new ApiRequest<Models.SvnUserToEdit>($this.Context, 'Svn.GetUser', { login: user.Login });

        // handler successful response to a request to api
        apiRequest.SuccessCallback = (response) => {
          $this.CurrentUser = response.data.User;
          $this.Groups = response.data.Groups;
          $this.SourceUser = $.parseJSON($.toJSON(response.data.User));
          //$this.Scope.$apply();
          $this.Editor.Show();
        };

        apiRequest.CompleteCallback = () => {
          $this.Loading = false;
          $this.Scope.$parent.CloseProgress();
        };

        // execute request
        apiRequest.Execute();

      }
    }

    private GroupClick($this: SvnUsersController, group: string): void {
      if ($this.CurrentUser.Groups == undefined || $this.CurrentUser.Groups == null) {
        $this.CurrentUser.Groups = new Array<string>();
      }

      if ($this.CurrentUser.Groups.indexOf(group) == -1) {
        $this.CurrentUser.Groups.push(group);
      } else {
        $this.CurrentUser.Groups.splice($this.CurrentUser.Groups.indexOf(group), 1);
      }
    }

    private SaveUser($this: SvnUsersController): void {
      if (Nemiro.Utility.NextInvalidField($('#svnUserEditor', $this.Editor.$modal))) {
        return;
      }

      var u = new Models.SvnUserToSave();
      u.Source = $this.SourceUser;
      u.Current = $this.CurrentUser;
      u.IsNew = $this.IsNew;
      u.SetLogin = $this.SetLogin;
      u.SetPassword = $this.SetPassword;

      // create request
      var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.SaveUser', u);

      $this.Scope.$parent.ShowProgress(App.Resources.SavingTheUserWait, App.Resources.Saving);

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Scope.$parent.ShowProgress(App.Resources.SavedSuccessfullyLoadingListOfUsers, App.Resources.Loading);
        $this.Editor.Close();
        $this.LoadUsers($this);
      };

      // execute
      apiRequest.Execute();
    }

    private DeleteUser($this: SvnUsersController): void {
      $this = $this || this;

      if ($this.SelectedUserToRemove == undefined || $this.SelectedUserToRemove == null || $this.SelectedUserToRemove == '') {
        Nemiro.UI.Dialog.Alert(App.Resources.IncorrectUserName, App.Resources.Error);
        return;
      }

      $this.Scope.$parent.ShowProgress(Nemiro.Utility.Format(App.Resources.IsRemovedUserWait, [$this.SelectedUserToRemove]), App.Resources.Deleting);
      $this.ConfirmUserRemove.Close();

      // create request
      var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.DeleteUser', { login: $this.SelectedUserToRemove });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        this.Scope.$parent.ShowProgress(App.Resources.LoadingListOfUsers, App.Resources.Loading);
        $this.SelectedUserToRemove = '';
        $this.LoadUsers($this);
      };

      apiRequest.CompleteCallback = () => {
        $this.Scope.$parent.CloseProgress();
      };

      // execute
      apiRequest.Execute();
    }
  }

} 