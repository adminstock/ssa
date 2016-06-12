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
   * Filter to format numbers.
   */
  export class CurrencyFormat implements Nemiro.IFilter {

    Name: string = 'CurrencyFormat';

    Filter: ng.IFilterService;

    constructor(filter: ng.IFilterService) {
      this.Filter = filter;
    }

    public Execution(value: number, args: any): any {
      if (isNaN(parseFloat(<any>value)) || !isFinite(value)) {
        return value;
      }

      // параметры по умолчанию
      args = args || {};
      var currencySymbol = args.currencySymbol || '';
      var decimalSeparator = args.decimalSeparator || '.';
      var thousandsSeparator = args.thousandsSeparator || ' ';
      var decimalDigits = args.decimalDigits || 0;

      if (decimalDigits <= 0) {
        decimalDigits = undefined;
      }
          
      // форматируем
      var formattedNumber = this.Filter('number')(value, decimalDigits);
          
      // копейки
      var numberParts = formattedNumber.split('.');

      // десятичный разделитель
      numberParts[0] = numberParts[0].split(',').join(thousandsSeparator);

      // собираем
      var result = numberParts[0];

      if (numberParts.length == 2) {
        result += decimalSeparator + numberParts[1];
      }

      result += ' ' + currencySymbol;

      return result;
    }

  }

}