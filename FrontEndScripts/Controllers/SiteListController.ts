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
   * Represents the site list controller.
   */
  export class SiteListController implements Nemiro.IController {

    public LevelsList = ['Nginx', 'Apache', 'HTAN'];

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** SSA config. */
    public get Config(): Models.Config {
      return this.Scope.$parent.Config;
    }

    /** The list of sites. */
    public get Sites(): Array<Models.Site> {
      return this.Scope.Sites;
    }
    public set Sites(value: Array<Models.Site>) {
      this.Scope.Sites = value;
    }

    /** Search string. */
    public get SearchString(): string {
      return this.Scope.SearchString;
    }
    public set SearchString(value: string) {
      this.Scope.SearchString = value;
    }

    /** Loading indicator. */
    public get Loading(): boolean {
      return this.Scope.Loading;
    }
    public set Loading(value: boolean) {
      this.Scope.Loading = value;
    }

    /** Selected site name to remove. */
    public get SelectedItemToRemove(): string {
      return this.Scope.SelectedItemToRemove;
    }
    public set SelectedItemToRemove(value: string) {
      this.Scope.SelectedItemToRemove = value;
    }

    /** Confirm to remove. */
    public get ConfirmNameToRemove(): string {
      return this.Scope.ConfirmNameToRemove;
    }
    public set ConfirmNameToRemove(value: string) {
      this.Scope.ConfirmNameToRemove = value;
    }

    private ConfirmToDelete: Nemiro.UI.Dialog;

    constructor(context: Nemiro.AngularContext) {
      var $this = this;
      
      $this.Context = context;
      $this.Scope = $this.Context.Scope;
      $this.SearchString = $this.Context.Location.search()['search'];
      $this.Scope.LevelsList = $this.LevelsList;

      $this.ConfirmToDelete = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteSite'));

      $this.Scope.Load = () => { $this.Load($this); }

      $this.Scope.ShowDialogToDelete = (name: string) => {
        $this.ConfirmNameToRemove = '';
        $this.SelectedItemToRemove = name;
        $this.ConfirmToDelete.Show();
      };

      $this.Scope.Delete = () => { $this.Delete($this); }

      $this.Scope.Search = () => {
        $this.Context.Location.search('search', $this.SearchString);
        $this.Load($this);
      }

      $this.Scope.ResetSearch = () => {
        $this.SearchString = '';
        $this.Context.Location.search('search', null);
        $this.Load($this);
      }

      $this.Scope.SetStatus = (site: Models.Site, level: string, isEnabled: boolean) => {
        $this.SetStatus($this, site, level, isEnabled);
      }

      $this.Scope.ConfIsEnabled = (level: string, site: Models.Site) => {
        var result = (level.toLowerCase() == 'all');

        for (var i = 0; i < site.Conf.length; i++) {
          if (level.toLowerCase() == 'all') {
            if (!site.Conf[i].Enabled) {
              return false;
            }
          } else {
            if (site.Conf[i].Level.toLowerCase() == level.toLowerCase()) {
              return site.Conf[i].Enabled;
            }
          }
        }

        return result;
      }

      $this.Load($this);
    }

    private Load($this: SiteListController): void {
      $this = $this || this;
      $this.Loading = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.Site>>($this.Context, 'Sites.GetSites', { search: $this.SearchString });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.Sites = response.data;
        $this.Loading = false;
        this.Scope.$parent.CloseProgress();
      };
      
      apiRequest.CompleteCallback = () => {
        $this.Loading = false;
      };

      // execute
      apiRequest.Execute();
    }

    private Delete($this: SiteListController): void {
      $this = $this || this;

      if ($this.ConfirmNameToRemove != $this.SelectedItemToRemove) {
        Nemiro.UI.Dialog.Alert(App.Resources.IncorrectSiteName, App.Resources.Error);
        return;
      }

      $this.ConfirmToDelete.Close();
      $this.Scope.$parent.ShowProgress(Nemiro.Utility.Format(App.Resources.IsRemovedSiteWait, [$this.SelectedItemToRemove]), App.Resources.Deleting);

      // create request
      var apiRequest = new ApiRequest<boolean>($this.Context, 'Sites.DeleteSite', { Name: $this.SelectedItemToRemove });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        this.Scope.$parent.ShowProgress(App.Resources.LoadingListOfSites, App.Resources.Loading);
        $this.Load($this);
      };

      // execute
      apiRequest.Execute();
    }

    private SetStatus($this: SiteListController, site: Models.Site, level: string, isEnabled: boolean): void {
      site.Loading = true;

      // create request (todo: typed result)
      var apiRequest = new ApiRequest<any>($this.Context, 'Sites.SetSiteStatus', { Name: site.Name, IsEnabled: isEnabled, Level: level });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        var conf: Array<Models.SiteConf> = response.data.Status;
        var all = true;
        for (var i = 0; i < conf.length; i++) {
          for (var j = 0; j < site.Conf.length; j++) {
            if (conf[i].Level == site.Conf[j].Level) {
              site.Conf[j].Enabled = conf[i].Enabled;
              break;
            }
          }
          
          if (!conf[i].Enabled) {
            all = false;
          }
        }

        site.IsEnabled = all;
      };

      apiRequest.CompleteCallback = () => {
        site.Loading = false;
      };

      // execute
      apiRequest.Execute();
    }

  }

} 