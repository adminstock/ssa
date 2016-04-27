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
	 * Represents the event handlers for the specified object.
	 */
	export class EventHandlers<T> {

		/** The name of event. */
		public Name: string;

		/** The event handlers list. */
		private Items: { (sender: any, args?: T): void; }[] = [];

		/** The event subscriber. */
		private Subscriber: any = null;

		constructor(eventName: string, subscriber: any) {
			if (eventName === undefined || eventName == null || eventName == '') {
				throw new Error('The parameter "eventName" is required and can not be empty!');
			}
			if (subscriber === undefined || subscriber == null) {
				throw new Error('The parameter "subscriber" is requier! Enter a reference to the object that owns the event handlers.');
			} 

			this.Name = eventName;
			this.Subscriber = subscriber;
		}

		/** 
		 * Adds an event handler.
		 * 
		 * @params handler Function - the event handler.
		 */
		public Add(handler: { (sender: any, args?: T): void; }) {
			if (handler === undefined || handler == null) {
				throw new Error('The handler is required!');
			}
			if (typeof handler != 'function') {
				throw new Error('The "handler" should be a function!');
			}

			this.Items.push(handler);
		}

		/** 
		 * Removes the event handler.
		 * 
		 * @params handler Function - the event handler.
		 */
		public Remove(handler: { (sender: any, args?: T): void; }) {
			this.Items = this.Items.filter(h => h !== handler);
		}
	
		/** 
		 * Invokes the handler.
		 * 
		 * @params args Additional arguments that will be passed to the handler.
		 */
		public Trigger(args?: T) {
			console.log('Trigger ', this.Name, args);

			for (var i = 0; i < this.Items.length; i++) {
				this.Items[i](this.Subscriber, args);
			}
		}

	}
}