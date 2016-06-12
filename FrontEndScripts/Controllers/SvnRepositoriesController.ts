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
	 * Represents the controller for repositories management of the Subversion server.
	 */
	export class SvnRepositoriesController implements Nemiro.IController {

		public Scope: any;
		public Context: Nemiro.AngularContext;

		/** The list of repositories. */
		public get Repositories(): Array<Models.SvnRepository> {
			return this.Scope.Repositories;
    }
		public set Repositories(value: Array<Models.SvnRepository>) {
			this.Scope.Repositories = value;
    }

		/** Current repository to edit. */
		public get Current(): Models.SvnRepository {
			return this.Scope.Current;
    }
		public set Current(value: Models.SvnRepository) {
			this.Scope.Current = value;
    }

		/** The source data of the current repository. */
		public get Source(): Models.SvnRepository {
			return this.Scope.Source;
    }
		public set Source(value: Models.SvnRepository) {
			this.Scope.Source = value;
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

		/**
		 * The repository name, which has been selected for deletion.
		 */
		public get SelectedItemToRemove(): string {
			return this.Scope.SelectedItemToRemove;
    }
		public set SelectedItemToRemove(value: string) {
			this.Scope.SelectedItemToRemove = value;
    }

		/**
		 * Repository name, to confirm the deletion.
		 */
		public get ConfirmNameToRemove(): string {
			return this.Scope.ConfirmNameToRemove;
    }
		public set ConfirmNameToRemove(value: string) {
			this.Scope.ConfirmNameToRemove = value;
    }

		/**
		 * Name of the object for which permissions will be added.
		 */
		public get PermissionsForObject(): string {
			return this.Scope.PermissionsForObject;
    }
		public set PermissionsForObject(value: string) {
			this.Scope.PermissionsForObject = value;
    }

		private Editor: Nemiro.UI.Dialog;
		private ConfirmToRemove: Nemiro.UI.Dialog;

		constructor(context: Nemiro.AngularContext) {
			var $this = this;
			
			$this.Context = context;
			$this.Scope = $this.Context.Scope;
			$this.Search = $this.Context.Location.search()['search'];
			$this.Editor = Nemiro.UI.Dialog.CreateFromElement($('#svnRep'));
			$this.ConfirmToRemove = Nemiro.UI.Dialog.CreateFromElement($('#confirmSvnRepositoryRemove'));
			
			$this.Scope.LoadRepositories = () => { $this.LoadRepositories($this); }

			$this.Scope.SearchRepositories = () => {
				$this.Context.Location.search('search', $this.Search);
				$this.LoadRepositories($this);
			}

			$this.Scope.ResetSearch = () => {
				$this.Search = '';
				$this.Context.Location.search('search', null);
				$this.LoadRepositories($this);
			}

			$this.Scope.Edit = (repName?: string) => { $this.Edit($this, repName); }
			$this.Scope.Save = () => { $this.Save($this); }
			$this.Scope.Delete = () => { $this.Delete($this); }
			$this.Scope.ShowDialogToDelete = (repName: string) => {
				$this.ConfirmNameToRemove = '';
				$this.SelectedItemToRemove = repName;
				$this.ConfirmToRemove.Show();
			};

			$this.Scope.AddPermission = () => {
				$this.AddPermission($this);
			};

			$this.Scope.DeletePermission = (p: Models.SvnRepositoryPermission) => {
				$this.DeletePermission($this, p);
			};

			$this.LoadRepositories($this);
		}

		private LoadRepositories($this: SvnRepositoriesController): void {
			$this = $this || this;
			$this.Loading = true;

			// create request
			var apiRequest = new ApiRequest<Array<Models.SvnRepository>>($this.Context, 'Svn.GetRepositories', { search: $this.Search });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				$this.Repositories = response.data;
			};

			apiRequest.CompleteCallback = () => {
				$this.Loading = false;
				$this.Scope.$parent.CloseProgress();
			};

			// execute
			apiRequest.Execute();
		}

		private Edit($this: SvnRepositoriesController, repName?: string): void {
			$this.Scope.ConfirmPassword = '';
			$this.PermissionsForObject = '';

			if (repName === undefined || repName == null) {

				$this.Source = new Models.SvnRepository();
        $this.Source.Name = App.Resources.NewRepository;
				$this.Current = new Models.SvnRepository();

				$this.Editor.Show();
				$this.Scope.$parent.CloseProgress();

			} else {

        $this.Scope.$parent.ShowProgress(App.Resources.ObtainingTheRepositoryWait, App.Resources.Loading);

				// load data from server
				var apiRequest = new ApiRequest<Models.SvnRepository>($this.Context, 'Svn.GetRepository', { name: repName });

				// handler successful response to a request to api
				apiRequest.SuccessCallback = (response) => {
					$this.Current = response.data;
					$this.Source = $.parseJSON($.toJSON(response.data));
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

		private Save($this: SvnRepositoriesController): void {
			if (Nemiro.Utility.NextInvalidField($('#svnRepEditor', $this.Editor.$modal))) {
				return;
			}

			// create request
			var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.SaveRepository', { Current: $this.Current, Source: $this.Source });

      $this.Scope.$parent.ShowProgress(App.Resources.SavingTheRepositoryWait, App.Resources.Saving);

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
        $this.Scope.$parent.ShowProgress(App.Resources.SavedSuccessfullyLoadingListOfRepositories, App.Resources.Loading);
				$this.Editor.Close();
				$this.LoadRepositories($this);
			};

			// execute
			apiRequest.Execute();
		}

		private Delete($this: SvnRepositoriesController): void {
			$this = $this || this;

			if ($this.SelectedItemToRemove == undefined || $this.SelectedItemToRemove == null || $this.SelectedItemToRemove == '' || $this.ConfirmNameToRemove != $this.SelectedItemToRemove) {
        Nemiro.UI.Dialog.Alert(App.Resources.IncorrectRepositoryName, App.Resources.Error);
				return;
			}

      $this.Scope.$parent.ShowProgress(Nemiro.Utility.Format(App.Resources.IsRemovedTheRepositoryWait, [$this.SelectedItemToRemove]), App.Resources.Deleting);
			$this.ConfirmToRemove.Close();

			// create request
			var apiRequest = new ApiRequest<boolean>($this.Context, 'Svn.DeleteRespository', { name: $this.SelectedItemToRemove });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
        this.Scope.$parent.ShowProgress(App.Resources.LoadingListOfRepositories, App.Resources.Loading);
				$this.SelectedItemToRemove = '';
				$this.LoadRepositories($this);
			};

			apiRequest.CompleteCallback = () => {
				$this.Scope.$parent.CloseProgress();
			};

			// execute
			apiRequest.Execute();
		}

		private AddPermission($this: SvnRepositoriesController): void {
			if ($this.PermissionsForObject == '') {
				return;
			}

			if ($this.Current.Permissions == undefined || $this.Current.Permissions == null) {
				$this.Current.Permissions = new Array<Models.SvnRepositoryPermission>();
			}

			for (var i = 0; i < $this.Current.Permissions.length; i++) {
				if ($this.Current.Permissions[i].ObjectName == $this.PermissionsForObject) {
					$this.PermissionsForObject = '';
					return;
				}
			}

			var p = new Models.SvnRepositoryPermission();
			p.Read = p.Write = false;
			p.ObjectName = $this.PermissionsForObject;

			$this.Current.Permissions.push(p);

			$this.PermissionsForObject = '';
		}

		private DeletePermission($this: SvnRepositoriesController, permission: Models.SvnRepositoryPermission): void {
			if ($this.Current.Permissions == undefined || $this.Current.Permissions == null) {
				$this.Current.Permissions = new Array<Models.SvnRepositoryPermission>();
			}

			for (var i = 0; i < $this.Current.Permissions.length; i++) {
				if ($this.Current.Permissions[i].ObjectName == permission.ObjectName) {
					$this.Current.Permissions.splice(i, 1);
					return;
				}
			}
		}

	}

} 