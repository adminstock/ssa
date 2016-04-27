/*!
 * SmallServerAdmin
 * ------------------------------------------
 * Aleksey Nemiro, 2016
 * http://aleksey.nemiro.ru
 */

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
module SmallServerAdmin {

	/** The main class of the Applicton. */
	export class App {
		
		/** Indicates local storage is available or not. */
		public static LocalStorageIsSupport: boolean = false;

		/** Context of the current application. */
		public static Current: Nemiro.AppContext = null;
		
		public static Init(): void {
			console.log('SmallServerAdmin.App.Init');

			try { 
				App.LocalStorageIsSupport = 'localStorage' in window && window['localStorage'] !== null;
			} catch (ex) { }

			// switch true-false
			$('.bit').bootstrapSwitch({
				onText: '<span class="glyphicon glyphicon-check" aria-hidden="true"></span>',
				offText: '<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>',
				offColor: 'default',
				onColor: 'default'
			});

			// switch yes-no
			$('.yesno').bootstrapSwitch({
				onText: '<span class="glyphicon glyphicon-check" aria-hidden="true"></span> Да',
				offText: '<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span> Нет',
				onColor: 'success',
				offColor: 'danger',
				handleWidth: '60px',
			});

			App.Current = new Nemiro.AppContext
			(
				'SmallServerAdmin', 'SmallServerAdmin',
				[
					'ngAnimate',
					'ngSanitize',
					'ui.bootstrap',
					'ui.codemirror',
					'frapontillo.bootstrap-switch',
					'treeControl',
					'highcharts-ng'
				]
			);
		}

	}

}

SmallServerAdmin.App.Init();