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
module SmallServerAdmin.Filters {

	/**
	 * Count as string.
	 */
	export class CountAsString implements Nemiro.IFilter {

		Name: string = 'CountAsString';

		Filter: ng.IFilterService;

		constructor(filter: ng.IFilterService) {
			this.Filter = filter;
		}

		public Execution(value: number, args: any): any {
			if (isNaN(parseFloat(<any>value)) || !isFinite(value)) {
				return value;
			}

			args = args || {};
			var word1 = args.word1;
			var word234 = args.word234;
			var wordmore = args.wordmore;

			var decintpart = value;
			var intpart = decintpart;
			var endpart = (intpart % 100);

			if (endpart > 19) endpart = endpart % 10;

			switch (endpart) {
				case 1:
					return word1;

				case 2:
				case 3:
				case 4:
					return word234;

				default:
					return wordmore;
			}
		}

	}

}