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
module Nemiro.Collections {

	/**
	 * Represents a collection of associated String keys and String values.
	 */
	export class NameValueCollection extends Nemiro.Collections.Collection<Nemiro.Collections.KeyValueItem> {

		constructor() {
			super();
		}

		public AddItem(key: string, value: string): void {
			this.Items.push(new Nemiro.Collections.KeyValueItem(key, value));
		}

		/**
		 * Gets the values associated with the specified key.
		 */
		public Get(key: string): Nemiro.Collections.KeyValueItem {
			for (var i = 0; i < this.Count; i++)
			{
				if (key == this.Items[i].Key) {
					return this.Items[i];
				}
			}

			return null;
		}

	}

}