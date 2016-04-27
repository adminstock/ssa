/*
 * Copyright © Aleksey Nemiro, 2015. All rights reserved.
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
module Nemiro {

	/** 
	 * Represents contex of the Angular.
	 */
	export class AngularContext {

		/** Работа с веб-запросами через Angular. */
		public Http: ng.IHttpService;

		/** Основной объект Angular. */
		public Scope: ng.IScope;

		/** Предоставляет доступ к фильтрам Angular. */
		public Filter: ng.IFilterService;

		/** Работа с объектом window через Angular. */
		public Window: ng.IWindowService;

		/** Работа с объектом location через Angular. */
		public Location: ng.ILocationService;

		/** Angular-ная обертка для window.setTimeout(). */
		public Timeout: ng.ITimeoutService;

		/** Представляет компилятор Angular. */
		public Compile: ng.ICompileService;

		public AnchorScroll: ng.IAnchorScrollService;

		/** HTML-элемент, к которому относится контроллер. */
		public Element: Element|HTMLElement|JQuery;
		
		private _InitParams: any = null;

		/** Пользовательские параметры инициализации контролера. Задается в атрибуте data-init-params */
		public get InitParams(): any {
			if ($(this.Element).attr('data-init-params') === undefined || $(this.Element).attr('data-init-params') == '') {
				return null;
			}

			if (this._InitParams == null) {
				this._InitParams = $.parseJSON($(this.Element).attr('data-init-params'));
			}

			return this._InitParams;
    }

		constructor($scope: ng.IScope, $filter: ng.IFilterService, $http: ng.IHttpService, $window: ng.IWindowService, $location: ng.ILocationService, $timeout: ng.ITimeoutService, $compile: ng.ICompileService, $anchorScroll: ng.IAnchorScrollService, $element?: Element|HTMLElement|JQuery) {
			this.Filter = $filter;
			this.Http = $http;
			this.Element = $element;
			this.Scope = $scope;
			this.Window = $window;
			this.Compile = $compile;
			this.Timeout = $timeout;
			this.Location = $location;
			this.AnchorScroll = $anchorScroll;
			// console.log(this);
		}

	}

}