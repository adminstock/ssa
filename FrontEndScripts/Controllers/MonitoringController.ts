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
	 * Represents server monitoring controller.
	 */
	export class MonitoringController implements Nemiro.IController {

		public Scope: any;
		public Context: Nemiro.AngularContext;

		/** The list of processes. */
		public get Processes(): Array<Models.Process> {
			return this.Scope.Processes;
    }
		public set Processes(value: Array<Models.Process>) {
			this.Scope.Processes = value;
    }

		/** Search string. */
		public get SearchString(): string {
			return this.Scope.SearchString;
    }
		public set SearchString(value: string) {
			this.Scope.SearchString = value;
    }

		public get SearchStringInput(): string {
			return this.Scope.SearchStringInput;
    }
		public set SearchStringInput(value: string) {
			this.Scope.SearchStringInput = value;
    }

		/** Info loading indicator. */
		public get InfoLoading(): boolean {
			return this.Scope.InfoLoading;
    }
		public set InfoLoading(value: boolean) {
			this.Scope.InfoLoading = value;
    }

		/** Loading indicator. */
		public get Loading(): boolean {
			return this.Scope.Loading;
    }
		public set Loading(value: boolean) {
			this.Scope.Loading = value;
    }

		public get DynamicsCPU(): Array<number> {
			return this.Scope.DynamicsCPU;
    }
		public set DynamicsCPU(value: Array<number>) {
			this.Scope.DynamicsCPU = value;
    }

		public get MemoryTotal(): number {
			return this.Scope.MemoryTotal;
    }
		public set MemoryTotal(value: number) {
			this.Scope.MemoryTotal = value;
    }

		public get MemoryFree(): number {
			return this.Scope.MemoryFree;
    }
		public set MemoryFree(value: number) {
			this.Scope.MemoryFree = value;
    }

		public get MemoryFreePercent(): number {
			return this.Scope.MemoryFreePercent;
    }
		public set MemoryFreePercent(value: number) {
			this.Scope.MemoryFreePercent = value;
    }

		public get DynamicsMemoryTotal(): Array<number> {
			return this.Scope.DynamicsMemoryTotal;
    }
		public set DynamicsMemoryTotal(value: Array<number>) {
			this.Scope.DynamicsMemoryTotal = value;
    }

		public get DynamicsMemoryUsage(): Array<number> {
			return this.Scope.DynamicsMemoryUsage;
    }
		public set DynamicsMemoryUsage(value: Array<number>) {
			this.Scope.DynamicsMemoryUsage = value;
    }

		public get DynamicsHDDTotal(): Array<number> {
			return this.Scope.DynamicsHDDTotal;
    }
		public set DynamicsHDDTotal(value: Array<number>) {
			this.Scope.DynamicsHDDTotal = value;
    }

		public get DynamicsHDDUsage(): Array<number> {
			return this.Scope.DynamicsHDDUsage;
    }
		public set DynamicsHDDUsage(value: Array<number>) {
			this.Scope.DynamicsHDDUsage = value;
    }

		public get HDDTotal(): number {
			return this.Scope.HDDTotal;
    }
		public set HDDTotal(value: number) {
			this.Scope.HDDTotal = value;
    }

		public get HDDFree(): number {
			return this.Scope.HDDFree;
    }
		public set HDDFree(value: number) {
			this.Scope.HDDFree = value;
    }

		public get SelectedProcessToKill(): Models.Process {
			return this.Scope.SelectedProcessToKill;
    }
		public set SelectedProcessToKill(value: Models.Process) {
			this.Scope.SelectedProcessToKill = value;
    }

		public get SortField(): string {
			return this.Scope.SortField;
    }
		public set SortField(value: string) {
			this.Scope.SortField = value;
    }

		public get SortReverse(): boolean {
			return this.Scope.SortReverse;
    }
		public set SortReverse(value: boolean) {
			this.Scope.SortReverse = value;
    }

		public get CPU(): Array<number> {
			return this.Scope.CPU;
    }
		public set CPU(value: Array<number>) {
			this.Scope.CPU = value;
    }

		public get IndicatorsRefreshInterval(): number {
			return this.Scope.IndicatorsRefreshInterval;
    }
		public set IndicatorsRefreshInterval(value: number) {
			this.Scope.IndicatorsRefreshInterval = value;
    }

		public get ProcessesRefreshInterval(): number {
			return this.Scope.ProcessesRefreshInterval;
    }
		public set ProcessesRefreshInterval(value: number) {
			this.Scope.ProcessesRefreshInterval = value;
    }

		public get RefreshDisabled(): boolean {
			return this.Scope.RefreshDisabled;
    }
		public set RefreshDisabled(value: boolean) {
			this.Scope.RefreshDisabled = value;
    }

		public get KillSignal(): string {
			return this.Scope.KillSignal;
    }
		public set KillSignal(value: string) {
			this.Scope.KillSignal = value;
    }

		private ConfirmToKillProcess: Nemiro.UI.Dialog;

		constructor(context: Nemiro.AngularContext) {
			var $this = this;

			$this.Context = context;
			$this.Scope = $this.Context.Scope;

			var settings = {};
			if ($this.Context.Window.localStorage['Monitoring'] !== undefined && $this.Context.Window.localStorage['Monitoring'] != null && $this.Context.Window.localStorage['Monitoring'] != '') {
				settings = $.parseJSON($this.Context.Window.localStorage['Monitoring']);
			}

			$this.DynamicsCPU = new Array<number>();
			$this.DynamicsMemoryUsage = new Array<number>();
			$this.DynamicsMemoryTotal = new Array<number>();
			$this.DynamicsHDDTotal = new Array<number>();
			$this.DynamicsHDDUsage = new Array<number>();
			$this.CPU = new Array<number>();

			$this.SortField = 'Name';
			$this.SortReverse = false;
			
			$this.IndicatorsRefreshInterval = (settings['IndicatorsRefreshInterval'] || 3);
			$this.ProcessesRefreshInterval = (settings['ProcessesRefreshInterval'] || 30);

			$this.SearchString = $this.SearchStringInput = $this.Context.Location.search()['search'];

			$this.ConfirmToKillProcess = Nemiro.UI.Dialog.CreateFromElement($('#confirmToKillProcess'));

			$this.Scope.SetIndicatorsRefreshInterval = (interval: number) => {
				$this.Context.Window.localStorage['Monitoring'] = $.toJSON({ IndicatorsRefreshInterval: interval, ProcessesRefreshInterval: $this.ProcessesRefreshInterval});
				$this.IndicatorsRefreshInterval = interval;

				if (interval > 0) {
					$this.Context.Timeout(() => { $this.GetInfo($this, false); }, interval * 1000);
				}
			}

			$this.Scope.SetProcessesRefreshInterval = (interval: number) => {
				$this.Context.Window.localStorage['Monitoring'] = $.toJSON({ IndicatorsRefreshInterval: $this.IndicatorsRefreshInterval, ProcessesRefreshInterval: interval });
				$this.ProcessesRefreshInterval = interval;

				if (interval > 0) {
					$this.Context.Timeout(() => { $this.Load($this); }, interval * 1000);
				}
			}

			$this.Scope.Load = () => { $this.Load($this); }
			$this.Scope.Refresh = () => {
				$this.RefreshDisabled = true;
				$this.Context.Timeout(() => { $this.RefreshDisabled = false; }, 1000);
				$this.GetInfo($this, false);
				$this.Load($this);
			}

			$this.Scope.Search = () => {
				$this.SearchString = $this.SearchStringInput;
				$this.Context.Location.search('search', $this.SearchString);
				$this.Load($this);
			}

			$this.Scope.ResetSearch = () => {
				$this.SearchString = $this.SearchStringInput = '';
				$this.Context.Location.search('search', null);
				$this.Load($this);
			}

			$this.Scope.GetProcessStatusCss = $this.GetProcessStatusCss;

			$this.Scope.ConfirmKill = (process: Models.Process) => {
				$this.KillSignal = 'SIGTERM';
				$this.SelectedProcessToKill = process;
				$this.ConfirmToKillProcess.Show();
			}

			$this.Scope.KillProcess = () => {
				$this.KillProcess($this);
			}

			// charts
			$this.InitCPUChart($this);
			$this.InitMemoryChart($this);
			$this.InitHDDChart($this);

			$this.GetInfo($this, true);
		}

		private InitCPUChart($this: MonitoringController): void {
			$this.Scope.ChartCPU = {
				options: {
					chart: {
						type: 'area',
						backgroundColor: null,
						borderWidth: 0,
						margin: [2, 0, 2, 0]
					},
					legend: {
						enabled: false
					},
					credits: {
						enabled: false
					},
					exporting: {
						enabled: false
					},
					tooltip: {
						backgroundColor: null,
						borderWidth: 0,
						shadow: false,
						useHTML: true,
						hideDelay: 0,
						shared: true,
						padding: 0,
						positioner: (w, h, point) => {
							return { x: point.plotX - w / 2, y: point.plotY - h };
						},
						//valueSuffix: '%'
						formatter: () => {
							var y = eval('this').y;
							return '<div class="chart-tooltip">' + y + '%</div>';
						}
					},
					plotOptions: {
						series: {
							marker: {
								enabled: false
							}
						}
					}
				},
				series: [{
					data: $this.DynamicsCPU
				}],
				title: {
					text: ''
				},
				loading: false,
				yAxis: {
					min: 0,
					max: 100,
					labels: {
						enabled: false
					},
					title: {
						text: null
					},
					startOnTick: false,
					endOnTick: false,
					tickPositions: []
				},
				useHighStocks: false
				/*size: {
					width: 120,
					height: 35
				}*/
			};
		}

		private InitMemoryChart($this: MonitoringController): void {
			$this.Scope.ChartMemory = {
				options: {
					chart: {
						type: 'area',
						backgroundColor: null,
						borderWidth: 0,
						margin: [2, 0, 2, 0]
					},
					legend: {
						enabled: false
					},
					credits: {
						enabled: false
					},
					exporting: {
						enabled: false
					},
					tooltip: {
						backgroundColor: null,
						borderWidth: 0,
						shadow: false,
						useHTML: true,
						hideDelay: 0,
						shared: true,
						padding: 0,
						positioner: (w, h, point) => {
							return { x: point.plotX - w / 2, y: point.plotY - h };
						},
						//valueSuffix: ' Mb',
						formatter: () => {
							var points = eval('this').points;
							return '<div class="chart-tooltip">' +
								'<strong>Total:</strong> ' + points[0].y + ' Mb<br />' +
								(points.length > 1 ? '<strong>In Use:</strong> ' + points[1].y + ' Mb' : '') +
								'</div>';
						}
					},
					plotOptions: {
						series: {
							marker: {
								enabled: false
							}
						}
					}
				},
				series: [{
					name: 'Total',
					data: $this.DynamicsMemoryTotal,
					color: '#90ed7d'
				}, {
					name: 'In Use',
					data: $this.DynamicsMemoryUsage,
					color: '#c0453c'
				}],
				title: {
					text: ''
				},
				loading: false,
				yAxis: {
					min: 0,
					max: $this.MemoryTotal,
					labels: {
						enabled: false
					},
					title: {
						text: null
					},
					startOnTick: false,
					endOnTick: false,
					tickPositions: []
				},
				useHighStocks: false
				/*size: {
					width: 120,
					height: 35
				}*/
			};
		}

		private InitHDDChart($this: MonitoringController): void {
			$this.Scope.ChartHDD = {
				options: {
					chart: {
						type: 'area',
						backgroundColor: null,
						borderWidth: 0,
						margin: [2, 0, 2, 0]
					},
					legend: {
						enabled: false
					},
					credits: {
						enabled: false
					},
					exporting: {
						enabled: false
					},
					tooltip: {
						backgroundColor: null,
						borderWidth: 0,
						shadow: false,
						useHTML: true,
						hideDelay: 0,
						shared: true,
						padding: 0,
						positioner: (w, h, point) => {
							return { x: point.plotX - w / 2, y: point.plotY - h };
						},
						//valueSuffix: ' Mb',
						formatter: () => {
							var points = eval('this').points;
							return '<div class="chart-tooltip">' +
								'<strong>Total:</strong> ' + points[0].y + ' Gb<br />' +
								(points.length > 1 ? '<strong>In Use:</strong> ' + points[1].y + ' Gb' : '') +
								'</div>';
						}
					},
					plotOptions: {
						series: {
							marker: {
								enabled: false
							}
						}
					}
				},
				series: [{
					name: 'Total',
					data: $this.DynamicsHDDTotal,
					color: '#90ed7d'
				}, {
						name: 'In Use',
						data: $this.DynamicsHDDUsage,
						color: '#c0453c'
					}],
				title: {
					text: ''
				},
				loading: false,
				yAxis: {
					min: 0,
					max: $this.MemoryTotal,
					labels: {
						enabled: false
					},
					title: {
						text: null
					},
					startOnTick: false,
					endOnTick: false,
					tickPositions: []
				},
				useHighStocks: false
				/*size: {
					width: 120,
					height: 35
				}*/
			};
		}

		private GetInfo($this: MonitoringController, getProcessList: boolean): void {
			$this = $this || this;

			if ($this.InfoLoading) {
				return;
			}
			
			$this.InfoLoading = true;

			// create request
			var apiRequest = new ApiRequest<Models.ServerInfo>($this.Context, 'Monitoring.GetInfo');

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				//$this.Processes = response.data;

				$this.CPU = response.data.CPU;
				$this.DynamicsCPU.push(response.data.CPU[0]);
				$this.MemoryTotal = Math.round((response.data.Memory.Total / 1024 / 1024) * 100) / 100; //.toFixed(2)
				$this.MemoryFree = Math.round((response.data.Memory.Free / 1024 / 1024) * 100) / 100;
				$this.MemoryFreePercent = ((response.data.Memory.Free * 100) / response.data.Memory.Total);
				$this.DynamicsMemoryUsage.push(Math.round(((response.data.Memory.Total - response.data.Memory.Free) / 1024 / 1024) * 100) / 100);
				//console.log('Memory', response.data.Memory.Total / 1024 / 1024, response.data.Memory.Free / 1024 / 1024, Math.round(((response.data.Memory.Total - response.data.Memory.Free) / 1024 / 1024) * 100) / 100);
				$this.DynamicsMemoryTotal.push(Math.round((response.data.Memory.Total / 1024 / 1024) * 100) / 100);

				var hddTotal = 0, hddAvailable = 0; 
				angular.forEach(response.data.HDD,(item: Models.HDDInfo) => {
					hddTotal += item.Total;
					hddAvailable += item.Available;
				});

				$this.DynamicsHDDTotal.push(Math.round((hddTotal / 1024 / 1024) * 100) / 100);
				$this.DynamicsHDDUsage.push(Math.round(((hddTotal - hddAvailable) / 1024 / 1024) * 100) / 100);

				$this.HDDTotal = Math.round((hddTotal / 1024 / 1024) * 100) / 100;
				$this.HDDFree = Math.round((hddAvailable / 1024 / 1024) * 100) / 100;

				if ($this.DynamicsCPU.length > 100) {
					$this.DynamicsCPU.shift();
				}

				if ($this.DynamicsMemoryUsage.length > 100) {
					$this.DynamicsMemoryUsage.shift();
				}

				if ($this.DynamicsMemoryTotal.length > 100) {
					$this.DynamicsMemoryTotal.shift();
				}

				if ($this.DynamicsHDDTotal.length > 100) {
					$this.DynamicsHDDTotal.shift();
				}

				if ($this.DynamicsHDDUsage.length > 100) {
					$this.DynamicsHDDUsage.shift();
				}

				if (getProcessList) {
					$this.Load($this);
				}
			};
			
			apiRequest.CompleteCallback = () => {
				$this.InfoLoading = false;

				if ($this.IndicatorsRefreshInterval > 0) {
					$this.Context.Timeout(() => {
						$this.GetInfo($this, false);
					}, $this.IndicatorsRefreshInterval * 1000);
				}
			};

			// execute
			apiRequest.Execute();
		}

		private Load($this: MonitoringController): void {
			$this = $this || this;

			if ($this.Loading) {
				return;
			}

			$this.Loading = true;

			// create request
			var apiRequest = new ApiRequest<Array<Models.Process>>($this.Context, 'Monitoring.GetProcesses', { search: $this.SearchString });

			// handler successful response to a request to api
			apiRequest.SuccessCallback = (response) => {
				$this.Processes = response.data;
			};
			
			apiRequest.CompleteCallback = () => {
				$this.Loading = false;

				if ($this.ProcessesRefreshInterval > 0) {
					$this.Context.Timeout(() => {
						$this.Load($this);
					}, $this.ProcessesRefreshInterval * 1000);
				}
			};

			// execute
			apiRequest.Execute();
		}

		private GetProcessStatusCss(status: string): string {
			if (status == null || status == '') {
				return '';
			}

			var result = '';

			if (status == 'R') {
				return 'process-status-running';
			}

			if (status == 'T') {
				return 'process-status-stopped';
			}

			if (status == 'S+') {
				return 'process-status-top';
			}

			if (status[0] == 'S') {
				return 'process-status-s';
			}

			return '';
		}

		private KillProcess($this: MonitoringController): void {
			$this.SelectedProcessToKill.Loading = true;
			$this.ConfirmToKillProcess.Close();

			var apiRequest = new ApiRequest<Array<Models.Process>>($this.Context, 'Monitoring.KillProcess', { pid: $this.SelectedProcessToKill.PID, signal: $this.KillSignal });

			apiRequest.SuccessCallback = (response) => {
				$this.Load($this);
			};

			apiRequest.CompleteCallback = () => {
				if ($this.SelectedProcessToKill != null) {
					$this.SelectedProcessToKill.Loading = false;
				}
			};

			// execute
			apiRequest.Execute();
		}

	}

} 