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
	 * Represents the controller for the user groups management of the Subversion server.
	 */
	export class SvnGroupsController implements Nemiro.IController {

		public Scope: any;
		public Context: Nemiro.AngularContext;

		/** The list of groups. */
		public get Groups(): Array<Models.SvnGroup> {
			return this.Scope.Groups;
    }
		public set Groups(value: Array<Models.SvnGroup>) {
			this.Scope.Groups = value;
    }

		/** Current group. */
		public get CurrentGroup(): Models.SvnGroup {
			return this.Scope.CurrentGroup;
    }
		public set CurrentGroup(value: Models.SvnGroup) {
			this.Scope.CurrentGroup = value;
    }

		/** The source data of the current group. */
		public get SourceGroup(): Models.SvnGroup {
			return this.Scope.SourceGroup;
    }
		public set SourceGroup(value: Models.SvnGroup) {
			this.Scope.SourceGroup = value;
    }

		/**
		 * The list of all subversion users.
		 */
		public get Users(): Array<string> {
			return this.Scope.Users;
    }
		public set Users(value: Array<string>) {
			this.Scope.Users = value;
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

		public get SelectedGroupToRemove(): string {
			return this.Scope.SelectedGroupToRemove;
    }
		public set SelectedGroupToRemove(value: string) {
			this.Scope.SelectedGroupToRemove = value;
    }

		private Editor: Nemiro.UI.Dialog;
		private ConfirmRemove: Nemiro.UI.Dialog;

		constructor(context: Nemiro.AngularContext) {
			var $this = this;
			
			$this.Context = context;
			$this.Scope = $this.Context.Scope;
			$this.Editor = Nemiro.UI.Dialog.CreateFromElement($('#svnGroup'));
			$this.ConfirmRemove = Nemiro.UI.Dialog.CreateFromElement($('#confirmSvnGroupRemove'));

			$this.Scope.LoadGroups = () => { $this.LoadGroups($this); }

			$this.Scope.EditGroup = (g?: Models.SvnGroup) => { $this.EditGroup($this, g); }
			$this.Scope.SaveGroup = () => { $this.SaveGroup($this); }
			$this.Scope.DeleteGroup = () => { $this.DeleteGroup($this); }
			$this.Scope.UserClick = (user: string) => { $this.UserClick($this, user); }
			$this.Scope.ShowDialogToDeleteGroup = (group: string) => {
				$this.SelectedGroupToRemove = group;
				$this.ConfirmRemove.Show();
			};

			$this.LoadGroups($this);
		}

		private LoadGroups($this: SvnGroupsController): void {
			$this = $this || this;
			$this.Loading = true;

			// create request
			var apiRequest = new ApiRequest<Array<Models.SvnGroup>>($this.Context, 'Svn.GetGroups');

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				$this.Groups = response.data;
			};

			apiRequest.CompleteCallback = () => {
				$this.Loading = false;
				$this.Scope.$parent.CloseProgress();
			};

			// execute
			apiRequest.Execute();
		}

		private EditGroup($this: SvnGroupsController, group?: Models.SvnGroup): void {
			$this.Loading = true;
			var apiRequest = null;

			if (group === undefined || group == null) {

				$this.IsNew = true;
				$this.SourceGroup = new Models.SvnGroup();
        $this.SourceGroup.Name = App.Resources.NewGroup;
				$this.CurrentGroup = new Models.SvnGroup();

        $this.Scope.$parent.ShowProgress(App.Resources.PreparingFormWait, App.Resources.Preparing);

				apiRequest = new ApiRequest<Array<string>>($this.Context, 'Svn.GetLogins');

				apiRequest.SuccessCallback = (response) => {
					$this.Users = response.data;
					$this.Editor.Show();
				};

				apiRequest.CompleteCallback = () => {
					$this.Loading = false;
					$this.Scope.$parent.CloseProgress();
				};

				apiRequest.Execute();

			} else {

        $this.Scope.$parent.ShowProgress(App.Resources.ObtainingTheGroupWait, App.Resources.Loading);

				$this.IsNew = false;

				// load data from server
				apiRequest = new ApiRequest<Models.SvnGroupToEdit>($this.Context, 'Svn.GetGroup', { name: group.Name });

				// handler successful response to a request to api
				apiRequest.SuccessCallback = (response) => {
					$this.CurrentGroup = response.data.Group;
					$this.Users = response.data.Users;
					$this.SourceGroup = $.parseJSON($.toJSON(response.data.Group));
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

		private UserClick($this: SvnGroupsController, user: string): void {
			if ($this.CurrentGroup.Members == undefined || $this.CurrentGroup.Members == null) {
				$this.CurrentGroup.Members = new Array<string>();
			}

			if ($this.CurrentGroup.Members.indexOf(user) == -1) {
				$this.CurrentGroup.Members.push(user);
			} else {
				$this.CurrentGroup.Members.splice($this.CurrentGroup.Members.indexOf(user), 1);
			}
		}

		private SaveGroup($this: SvnGroupsController): void {
			if (Nemiro.Utility.NextInvalidField($('#svnGroupEditor', $this.Editor.$modal))) {
				return;
			}

			var g = new Models.SvnGroupToSave();
			g.Source = $this.SourceGroup;
			g.Current = $this.CurrentGroup;
			g.IsNew = $this.IsNew;

			// create request
			var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.SaveGroup', g);

      $this.Scope.$parent.ShowProgress(App.Resources.SavingTheGroupWait, App.Resources.Saving);

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
        $this.Scope.$parent.ShowProgress(App.Resources.SavedSuccessfullyLoadingListOfGroups, App.Resources.Loading);
				$this.Editor.Close();
				$this.LoadGroups($this);
			};

			// execute
			apiRequest.Execute();
		}

		private DeleteGroup($this: SvnGroupsController): void {
			$this = $this || this;

			if ($this.SelectedGroupToRemove == undefined || $this.SelectedGroupToRemove == null || $this.SelectedGroupToRemove == '') {
        Nemiro.UI.Dialog.Alert(App.Resources.IncorrectGroupName, App.Resources.Error);
				return;
			}

      $this.Scope.$parent.ShowProgress(Nemiro.Utility.Format(App.Resources.IsRemovedTheGroupWait, [$this.SelectedGroupToRemove]), App.Resources.Deleting);
			$this.ConfirmRemove.Close();

			// create request
			var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.DeleteGroup', { name: $this.SelectedGroupToRemove });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
        this.Scope.$parent.ShowProgress(App.Resources.LoadingListOfGroups, App.Resources.Loading);
				$this.SelectedGroupToRemove = '';
				$this.LoadGroups($this);
			};

			apiRequest.CompleteCallback = () => {
				$this.Scope.$parent.CloseProgress();
			};

			// execute
			apiRequest.Execute();
		}
	}

} 