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
	 * Represents service list controller.
	 */
	export class ServiceListController implements Nemiro.IController {

		public Scope: any;
		public Context: Nemiro.AngularContext;

		/** The list of services. */
		public get Services(): Array<Models.Service> {
			return this.Scope.Services;
    }
		public set Services(value: Array<Models.Service>) {
			this.Scope.Services = value;
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

		public get SelectedServiceToStop(): Models.Service {
			return this.Scope.SelectedServiceToStop;
    }
		public set SelectedServiceToStop(value: Models.Service) {
			this.Scope.SelectedServiceToStop = value;
    }

		private ConfirmToStopService: Nemiro.UI.Dialog;

		constructor(context: Nemiro.AngularContext) {
			var $this = this;

			$this.Context = context;
			$this.Scope = $this.Context.Scope;
			$this.SearchString = $this.Context.Location.search()['search'];

			$this.ConfirmToStopService = Nemiro.UI.Dialog.CreateFromElement($('#confirmToStopService'));

			$this.Scope.Load = () => { $this.Load($this); }

			$this.Scope.Search = () => {
				$this.Context.Location.search('search', $this.SearchString);
				$this.Load($this);
			}

			$this.Scope.ResetSearch = () => {
				$this.SearchString = '';
				$this.Context.Location.search('search', null);
				$this.Load($this);
			}

			$this.Scope.SetStatus = (service: Models.Service, newStatus: string) => {
				if (newStatus == 'Stopped') {
					$this.SelectedServiceToStop = service;
					$this.ConfirmToStopService.Show();
					return;
				}

				$this.SetStatus($this, service, newStatus);
			}

			$this.Scope.StopService = () => {
				$this.SetStatus($this, $this.SelectedServiceToStop, 'Stopped');
				this.ConfirmToStopService.Close();
			}

			if ($('[ng-controller="ServiceListController"]').attr('ng-init') === undefined || $('[ng-controller="ServiceListController"]').attr('ng-init') == '') {
				$this.Load($this);
			}
		}

		private Load($this: ServiceListController): void {
			$this = $this || this;
			$this.Loading = true;

			// create request
			var apiRequest = new ApiRequest<Array<Models.Service>>($this.Context, 'Services.GetList', { search: $this.SearchString });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				$this.Services = response.data;
				$this.Loading = false;
				this.Scope.$parent.CloseProgress();
			};
			
			apiRequest.CompleteCallback = () => {
				$this.Loading = false;
			};

			// execute
			apiRequest.Execute();
		}

		private SetStatus($this: ServiceListController, service: Models.Service, newStatus: string): void {
			service.Loading = true;

			var apiRequest = new ApiRequest<boolean>($this.Context, 'Services.SetStatus', { Name: service.Name, NewStatus: newStatus });

			apiRequest.SuccessCallback = (response) => {
				if (newStatus.toLowerCase() != 'reload') {
					service.Status = newStatus;
				}
			};

			apiRequest.CompleteCallback = () => {
				service.Loading = false;
			};

			// execute
			apiRequest.Execute();
		}

	}

} 