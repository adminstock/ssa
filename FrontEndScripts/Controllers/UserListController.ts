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
	 * Represents the main controller.
	 */
	export class UserListController implements Nemiro.IController {

		public Scope: any;
		public Context: Nemiro.AngularContext;

		/** The list of users. */
		public get Users(): Models.UsersList {
			return this.Scope.Users;
    }
		public set Users(value: Models.UsersList) {
			this.Scope.Users = value;
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
			/*if (value) {
				this.Scope.$parent.ShowProgress('Loading list of users...', 'Loading...');
			} else {
				this.Scope.$parent.CloseProgress();
			}*/
    }

		/** Selected user login to remove. */
		public get SelectedUserToRemove(): string {
			return this.Scope.SelectedUserToRemove;
    }
		public set SelectedUserToRemove(value: string) {
			this.Scope.SelectedUserToRemove = value;
    }

		/** Confirm user to remove. */
		public get ConfirmLoginToRemove(): string {
			return this.Scope.ConfirmLoginToRemove;
    }
		public set ConfirmLoginToRemove(value: string) {
			this.Scope.ConfirmLoginToRemove = value;
    }

		/** Indicates the need to delete the home directory of the user. */
		public get RemoveHome(): boolean {
			return this.Scope.RemoveHome;
    }
		public set RemoveHome(value: boolean) {
			this.Scope.RemoveHome = value;
    }

		private ConfirmUserRemove: Nemiro.UI.Dialog;

		constructor(context: Nemiro.AngularContext) {
			var $this = this;
			
			$this.Context = context;
			$this.Scope = $this.Context.Scope;
			$this.Users = new Models.UsersList();
			$this.Users.CurrentPage = 1;
			$this.Users.DataPerPage = 100;
			$this.Search = $this.Context.Location.search()['search'];

			$this.ConfirmUserRemove = Nemiro.UI.Dialog.CreateFromElement($('#confirmUserRemove'));

			$this.Scope.LoadUsers = () => { $this.LoadUsers($this); }

			$this.Scope.ShowDialogToDeleteUser = (login: string) => {
				$this.RemoveHome = true;
				$this.ConfirmLoginToRemove = '';
				$this.SelectedUserToRemove = login;
				$this.ConfirmUserRemove.Show();
			};

			$this.Scope.DeleteUser = () => { $this.DeleteUser($this); }

			$this.Scope.SearchUsers = () => {
				$this.Users.CurrentPage = 1;
				$this.Context.Location.search('search', $this.Search);
				$this.LoadUsers($this);
			}

			$this.Scope.ResetSearch = () => {
				$this.Users.CurrentPage = 1;
				$this.Search = '';
				$this.Context.Location.search('search', null);
				$this.LoadUsers($this);
			}

			$this.LoadUsers($this);
		}

		private LoadUsers($this: UserListController): void {
			$this = $this || this;
			$this.Loading = true;

			// create request
			var apiRequest = new ApiRequest<Models.UsersList>($this.Context, 'Users.GetUsers', { page: $this.Users.CurrentPage, limit: $this.Users.DataPerPage, search: $this.Search });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				$this.Users = response.data;
				$this.Loading = false;
				this.Scope.$parent.CloseProgress();
			};

			// execute
			apiRequest.Execute();
		}

		private DeleteUser($this: UserListController): void {
			$this = $this || this;

			if ($this.ConfirmLoginToRemove != $this.SelectedUserToRemove) {
				Nemiro.UI.Dialog.Alert('Incorrect user name!', 'Error');
				return;
			}

			$this.ConfirmUserRemove.Close();
			$this.Scope.$parent.ShowProgress('Is removed the user <strong>' + $this.SelectedUserToRemove + '</strong>. Please wait...', 'Deleting...');

			// create request
			var apiRequest = new ApiRequest<boolean>($this.Context, 'Users.DeleteUser', { Login: $this.SelectedUserToRemove, RemoveHome: $this.RemoveHome });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				this.Scope.$parent.ShowProgress('Loading list of users...', 'Loading...');
				$this.LoadUsers($this);
			};

			// execute
			apiRequest.Execute();
		}

	}

} 