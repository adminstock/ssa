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
   * The class allows you to perform the specified action and automatically repeated if an error occurs.
   */
  export class TryAction {

    /** The action to execution. */
    public Action: { (): void; } = null;

    /** The maximum number of attempts. Default: 10. */
    public MaxAttempts: number = 10;

    /** 
     * The duration of pauses between attempts (in seconds).
     * Minus one - automatically (by default).
     */
    public Pause: number = -1;

    /** Attempts counter. */
    private Attempts: number = 0;

    constructor(action: { (): void; }) {
      if (action == null) {
        throw Error('Action is required.');
      }

      this.Action = action;
    }

    /** Executes the task. */
    public Run(): void {
      this.Attempts++;

      if (this.Attempts > this.MaxAttempts) {
        throw new Error('Reached the allowable limit of attempts to perform a task.');
      }

      // если это не первая попытка, делаем паузу
      if (this.Attempts > 1) {
        var pause = 0;

        if (this.Pause == -1) {
          if (this.Attempts <= 5) {
            pause = ((this.Attempts % 2) == 0 ? 2000 : 4000);
          } else {
            pause = ((this.Attempts % 2) == 0 ? 4000 : 8000);
          }
        } else {
          pause = this.Pause * 1000;
        }

        console.log('Pause', pause / 1000)
        window.setTimeout(this.Action, pause);
      } else {
        this.Action();
      }
    }

  }

} 