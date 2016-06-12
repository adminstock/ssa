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
   * Вспомогательные полезняшки. 
   * 
   * -----------------------------------------------------
   * Aleksey Nemiro, Arimsoft Ltd., 13.08.2015
   * http://aleksey.nemiro.ru
   * http://arimsoft.ru
   */
  export class Utility {

    /** Проверяет, является указанное значение числом или нет. */
    public static IsNumeric(value: any): boolean {
      return (!isNaN(parseFloat(value)) && isFinite(value));
    }

    /**
     * Возвращает сумму в виде строки. Например: 3 рубля, 5 рублей.
     * 
     * @para value Количество.
     * @param word1 Одна штука.
     * @param word234 Деве штуки.
     * @param wordmore Десять штук.
     */
    public static GetCountAsString(value: number, word1: string, word234: string, wordmore: string) {
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

    /** Сравнивает две даты.*/
    public static CompareDates(a: any, b: any): number {
      return (
        isFinite(a = Utility.ConvertToDate(a).valueOf()) &&
          isFinite(b = Utility.ConvertToDate(b).valueOf()) ?
          <any>(a > b) - <any>(a < b) : NaN
      );
    }

    /** Преобразует указанное значение в дату. */
    public static ConvertToDate(d: any): Date {
      return <any>(
        d.constructor === Date ? new Date(d.getFullYear(), d.getMonth(), d.getDate()) :
          d.constructor === Array ? new Date(d[0], d[1], d[2]) :
            d.constructor === Number ? new Date(d) :
              d.constructor === String ? (/[0-9]{2}.[0-9]{2}.[0-9]{4}/.test(d) ?
                new Date(parseInt(d.split(".")[2], 10), parseInt(d.split(".")[1], 10) - 1, parseInt(d.split(".")[0], 10)) : new Date(d)) :
                typeof d === "object" ? new Date(d.year, d.month, d.date) : NaN
      );
    }

    /** 
     * Убирает (аннулирует) время из указанной даты.
     * 
     * @param datetime Дата, либо timeStamp.
     */
    public static ExcludeTime(datetime: Date|number): Date {
      if (typeof datetime === 'number') { datetime = new Date(<number>datetime); }
      return new Date((<Date>datetime).getFullYear(),(<Date>datetime).getMonth(),(<Date>datetime).getDate());
    }

    /** Преобразует строку, содержащую дату в формате ДД.ММ.ГГГГ, в дату. */
    public static GetDateFromString(dateString: string): Date {
      var dpg = $.fn.datepicker.DPGlobal;
      return dpg.parseDate(dateString, dpg.parseFormat('dd.mm.yyyy'));
    }

    /*
     * Возвращает строку представляющую дату в указанном формате.
     * 
     * @param date Дата, которую следует преобразовать в строку.
     * @param format Формат даты. По умолчанию: dd.mm.yyyy.
     */
    static DateToString(date: Date, format?: string): string {
      if (format == undefined || format == null || format == '') {
        format = 'dd.mm.yyyy';
      }

      var dpg = $.fn.datepicker.DPGlobal;

      return dpg.formatDate(date, dpg.parseFormat(format), 'ru');
    }

    /**
     * Проверяет корректность даты. 
     * 
     * @param value Значение, которое следует проверить.
     */
    public static IsValidDate(value: string): boolean {
      if (!value || value == undefined || value == '') { return false; }
      var arr = value.split('.');
      if (arr.length < 3) { return false; }
      var y = parseInt(arr[2], 10), m = parseInt(arr[1], 10), d = parseInt(arr[0], 10);
      var dt = new Date(y, m - 1, d);
      if (isNaN(dt.getTime())) { return false; }
      return dt.getFullYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d;
    }

    /**
     * Форматирует указанную строку.
     * @param value Строка, в которую следует вставить параметры.
     * @param args Пермеаметры, которые следует вставить в строку.
     */
    static Format(value: string, args: any): string {
      if (value === undefined || value === null || value === '') {
        console.log('Value is empty.');
        return null;
      }

      if (args.length === 1 && args[0] !== null && typeof args[0] === 'object') {
        args = args[0];
      }

      if (typeof args === 'string') {
        args = [args];
      }

      return value.replace(/{([^}]*)}/g, function (match, key) {
        return (typeof args[key] !== "undefined" ? args[key] : match);
      });
    }

    /*
     * Заменяет значение одной подстроки на другую.
     * 
     * @param str Строка, в которой следует произвести поиск и замену.
     * @param find Искомая подстрока.
     * @param repl Подстрока, на которую следует заменить найденные совпадения.
     */
    static Replace(str, find, repl): string {
      return str.replace(new RegExp("(" + find + ")+", "g"), repl);
    }

    /**
     * Кодирует сущености HTML.
     * 
     * @param value Строка, в которой следует закодировать сущности HTML.
     */
    static HtmlEncode(value: string): string {
      var r = new RegExp("\x22+", "g");
      var div = document.createElement("div");
      var text = document.createTextNode(value);
      div.appendChild(text);
      return div.innerHTML.replace(r, "&quot;");
    }

    /**
     * Декодирует сущености HTML.
     * 
     * @param value Строка, в которой следует декодировать сущности HTML.
     */
    static HtmlDecode(value: string): string {
      var div = document.createElement("div");
      div.innerHTML = value;
      return div.innerText;
    }

    /** 
     * Делает куку. 
     * 
     * @param name Имя куки.
     * @param value Значение.
     * @param days Срок хранения (дней).
     */
    public static CreateCookies(name: string, value: string, days?: number) {
      var expires = '';
      var date = new Date();

      if (days !== undefined && days !== null) {
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
      }

      document.cookie = Utility.Format('{name}={value}{expires}; path=/', { name: name, value: value, expires: expires });
    }

    /** 
     *  Зачитывает куку вслух, с выражением, стоя на одной ноге на табуретке на кухне, в шубе и шапке ушанке, летом, ночью, в полнолуние. 
     * 
     * @param name Имя куки.
     */
    public static ReadCookies(name: string): string {
      var ca = document.cookie.split(';');

      name = name + '=';

      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
      }

      return null;
    }

    /** 
     * Стирает куку. 
     * 
     * @param name Имя куки.
     */
    public static EraseCookies(name: string) {
      Utility.CreateCookies(name, '', -1);
    }

    /** Возвращает адрес без хеша (#). */
    public static GetUrlWithoutHash(url: string): string {
      if (url.indexOf('#') != -1) {
        url = url.substring(0, url.indexOf('#'));
      }
      return url;
    }

    /** Возвращает указанный параметр указанного url. */
    public static GetUrlParameter(name: string, url: string): string {
      if (url == undefined || url == null) {
        url = window.location.search;
      }
      return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(url) || [, ""])[1].replace(/\+/g, '%20')) || null;
    }

    /*
     * Возвращает высоту элемента с учетом полей и отступов.
     */
    public static GetElementHeight(element: string|JQuery|HTMLElement): number {
      var $element = $(element);
      if ($element.css("display") == "none") { return 0; }
      var pd = 0;
      pd += ($element.css("padding-top") && $element.css("padding-top") != "auto" ? parseInt($element.css("padding-top")) : 0);
      pd += ($element.css("padding-bottom") && $element.css("padding-bottom") != "auto" ? parseInt($element.css("padding-bottom")) : 0);
      pd += ($element.css("margin-top") && $element.css("margin-top") != "auto" ? parseInt($element.css("margin-top")) : 0);
      pd += ($element.css("margin-bottom") && $element.css("margin-bottom") != "auto" ? parseInt($element.css("margin-bottom")) : 0);
      return $element.height() + pd;
    }

    /*
     * Возвращает ширину элемента с учетом полей и отступов.
     */
    public static GetElementWidth(element: string|JQuery|HTMLElement): number {
      var $element = $(element);
      if ($element.css("display") == "none") { return 0; }
      var pd = 0;
      pd += ($element.css("padding-left") && $element.css("padding-left") != "auto" ? parseInt($element.css("padding-left")) : 0);
      pd += ($element.css("padding-right") && $element.css("padding-right") != "auto" ? parseInt($element.css("padding-right")) : 0);
      pd += ($element.css("margin-left") && $element.css("margin-left") != "auto" ? parseInt($element.css("margin-left")) : 0);
      pd += ($element.css("margin-right") && $element.css("margin-right") != "auto" ? parseInt($element.css("margin-right")) : 0);
      return $element.width() + pd;
    }

    /**
     * Проверяет, является указанное значение пустым или нет.
     * 
     * @param value Значение, которое требуется проверить.
     */
    public static IsNullOrEmpty(value: any): boolean {
      return value === undefined || value == null || value == '';
    }

    /**
     * Возвращает указанное число в виде строки с не менее, чем два знака.
     */
    public static NumberTo2DigitString(value: number): string {
      if (value < 10 && value >= 0) {
        return '0' + value.toString();
      } else {
        return value.toString();
      }
    }

    public static NextInvalidField(form: string|JQuery|HTMLFormElement, cssClass?: string): boolean {
      if ((<HTMLFormElement>$(form)[0]).checkValidity()) {
        return false;
      }

      var element = $('input:invalid, textarea:invalid', form).first();

      cssClass = cssClass || 'ng-invalid ng-invalid-required ng-touched';
      element.addClass(cssClass);

      //console.log(element);

      element.focus();

      return true;
    }
    
    /**
     * Return the directory name of a path.
     */
    public static DirectoryName(path: string): string {
      var separator = '/';
      if (path.indexOf('\\') != -1) { separator = '\\'; }
      var pathItems = path.split(separator);
      pathItems.pop();
      return pathItems.join(separator);
    }

  }

}