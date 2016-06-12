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

  /** 
   * Represents a request for the API.
   */
  export class ApiRequest<T> {

    public static get API_URL(): string { return '/api.php'; }

    public Context: Nemiro.AngularContext;

    /** The address to which to send the request. */
    public Url: string;

    /** The request parameters. */
    public Data: any;

    /*
    / ** The maximum number of attempts to fulfill the request. Default: 10. * /
    public MaxAttempts: number = 10;

    / ** 
     * Продолжительность пауз между попытками (секунд). 
     * Минус один - автоматически (по умолчанию).
     * /
    public Pause: number = -1;
    */

    /** The handler successful execution of the request. */
    public SuccessCallback: { (response: angular.IHttpPromiseCallbackArg<T>): void; } = null;

    /** The error handler. */
    public ErrorCallback: { (response: angular.IHttpPromiseCallbackArg<any>): void; } = null;

    /** The request complete handler. */
    public CompleteCallback: { (response: angular.IHttpPromiseCallbackArg<any>): void; } = null;

    constructor(context: Nemiro.AngularContext, method: string, data?: any, url?: string) {
      if (context == null) {
        throw new Error('Context is required.');
      }

      if (url === undefined || url == null || url == '') {
        url = ApiRequest.API_URL;
      }

      data = data || {};
      data = { Method: method, Data: data };

      this.Context = context;
      this.Url = url;
      this.Data = data;
    }

    /**
     * Sends a request to the API.
     */
    public Execute(): void {
      var $this = this;
      var data = $this.Data || null;

      var start = new Date().getTime();

      console.log('ApiRequest.Execute', $this.Url, data);

      var action = new Nemiro.TryAction(() => {
        $this.Context.Http.post<T>($this.Url, data).then((response) => {

          console.log('ApiRequest.Execute_Result', $this.Url, response.data, Math.abs((start - new Date().getTime()) / 1000) + 's');

          if ($this.SuccessCallback != null) {
            $this.SuccessCallback(response);
          }

          if ($this.CompleteCallback != null) {
            $this.CompleteCallback(response);
          }

        }, (response: ng.IHttpPromiseCallbackArg<any>) => {
          // ошибка
          console.warn(response);

          try {
            // пробуем еще раз
            action.Run();
          } catch (ex) {

            // попытки закончились
            if ($this.ErrorCallback != null) {
              // пользовательский обработчик
              $this.ErrorCallback(response);
            } else {
              // стандартный обработчик
              $this.ApiError(response);
            }

            if ($this.CompleteCallback != null) {
              $this.CompleteCallback(response);
            }

          }
        });
      });

      action.MaxAttempts = 1; //$this.MaxAttempts;
      // action.Pause = $this.Pause;

      action.Run();
    }

    /**
     * Универсальный обработчик ошибок API.
     * Выводит сообщение об ошибке в диалоговом окне.
     */
    public ApiError(response: ng.IHttpPromiseCallbackArg<any>): void {
      console.log('ApiError', response);

      if ((<any>response).data !== undefined && (<any>response).data != null && typeof (<any>response).data == 'object') {
        Nemiro.UI.Dialog.Alert(Nemiro.Utility.Replace(ApiRequest.GetExceptionMessage((<any>response).data), '\n', '<br />'), 'Error');
      } else {
        var details = '';
        if ((<any>response).config !== undefined && (<any>response).config != null) {
          details = 'Technical information: ' + (<any>response).config.method + ' ' + (<any>response).config.url + '\r\n\r\n';
          details += $.toJSON((<any>response).config.data);
        }
        Nemiro.UI.Dialog.Alert('<p>An unknown error occurred. Try again.</p><pre>' + Nemiro.Utility.Replace(details, '\n', '<br />') + '</pre>', 'Error');
      }
    }

    public static GetExceptionMessage(data: any): string {
      if (data !== undefined && data != null && typeof data == 'object') {
        if (data.ExceptionMessage !== undefined && data.ExceptionMessage != null && data.ExceptionMessage != '') {
          return data.ExceptionMessage;
        }
        else if (data.Message !== undefined && data.Message != null && data.Message != '') {
          return data.Message;
        }
        else if (data.Error !== undefined) {
          if (data.Error.Message !== undefined && data.Error.Message != null && data.Error.Message != '') {
            return data.Error.Message;
          }
          else {
            return data.Error;
          }
        }
      }

      return null;
    }

    /**
     * Sends echo request.
     * @param context
     * @param callback
     * @param maxAttempts
     * @param url
     */
    public static Echo(context: Nemiro.AngularContext, callback?: { (response: angular.IHttpPromiseCallbackArg<any>): void }, maxAttempts?: number, url?: string): void {
      if (url === undefined || url == null || url == '') {
        url = ApiRequest.API_URL;
      }

      var start = new Date().getTime();

      // create action
      var action = new Nemiro.TryAction(() => {
        context.Http.post<any>(url, []).then((response) => {
          console.log('ApiRequest.Echo', url, response.data, Math.abs((start - new Date().getTime()) / 1000) + 's');

          if (callback != undefined && callback != null) {
            callback(response);
          }
        }, (response: ng.IHttpPromiseCallbackArg<any>) => {
          // error
          console.warn(response);

          try {
            // try again
            action.Run();
          } catch (ex) {
            // attempts have ended
            if (callback != undefined && callback != null) {
              callback(response);
            }
          }
        });
      });

      action.MaxAttempts = (maxAttempts || 1);

      // start request
      action.Run();
    }

  }

} 