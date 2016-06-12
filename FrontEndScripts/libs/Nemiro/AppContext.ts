/*
 * Copyright © Aleksey Nemiro, 2015-2016. All rights reserved.
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
   * Represents contex of the application. 
   */
  export class AppContext {

    public Angular: ng.IModule;

    private Namespace: string;

    /** The list of registered controllers. */
    public RegisteredControllerList: Array<RegisteredController<any>> = new Array<RegisteredController<any>>();

    /** The application init handler. */
    public Initialize: Nemiro.EventHandlers<any> = null;

    /** The application controller registered handler. */
    public ControllerRegistered: Nemiro.EventHandlers<RegisteredController<any>> = null;

    /** The application filter registered handler. */
    public FilterRegistered: Nemiro.EventHandlers<IFilter> = null;

    constructor(namespace: string, app?: string|ng.IModule, requires?: Array<string>) {
      var $this = this;
      $this.Namespace = namespace;

      if (typeof app == 'undefined') {
        $this.Angular = angular.module('app', requires || []);
      }
      else if (typeof app == 'string') {
        $this.Angular = angular.module(app.toString() || 'app', requires || []);
      } else {
        $this.Angular = <ng.IModule>app;
      }

      // init handlers
      $this.Initialize = new Nemiro.EventHandlers('Initialize', $this);
      $this.FilterRegistered = new Nemiro.EventHandlers('FilterRegistered', $this);
      $this.ControllerRegistered = new Nemiro.EventHandlers('ControllerRegistered', $this);

      // init angular
      $this.InitAngularDirectives();
      $this.InitAngularFilters();
      $this.InitAngularControllers();

      $this.Initialize.Trigger();
    }
    
    /** Performs registration of directives. */
    private InitAngularDirectives(): void {
      var $this = this;
      // strong numbers
      $this.Angular.directive('convertToNumber',() => {
        return {
          require: 'ngModel',
          link: (scope, element, attrs, ngModel) => {
            ngModel.$parsers.push((val) => {
              return parseInt(val, 10);
            });
            ngModel.$formatters.push((val) => {
              return '' + val;
            });
          }
        };
      });

      // compare
      $this.Angular.directive('compareTo',() => {
        return {
          require: "ngModel",
          scope: {
            otherModelValue: "=compareTo"
          },
          link: (scope, element, attributes, ngModel) => {

            ngModel.$validators.compareTo = (modelValue) => {
              return modelValue == (<any>scope).otherModelValue;
            };

            scope.$watch("otherModelValue", () => {
              ngModel.$validate();
            });
          }
        };
      });
    }
    
    /** Performs registration of filters. */
    private InitAngularFilters(): void {
      var $this = this;

      if (window[$this.Namespace] === undefined || window[$this.Namespace]['Filters'] === undefined) {
        return;
      }

      for (var filterName in window[$this.Namespace]['Filters']) {
        console.log('InitAngularFilters', filterName);

        if (typeof filterName === 'undefined') {
          continue;
        }

        if (typeof window[$this.Namespace]['Filters'][filterName] !== 'function') {
          console.error('Filter <' + $this.Namespace + '.Filters.' + filterName + '> not found.');
          continue;
        }

        $this.RegisterFilter(filterName);
      }
    }

    private RegisterFilter(filterName: string): void {
      console.log('RegisterFilter', filterName);

      var $this = this;

      $this.Angular.filter(filterName, ['$filter', ($filter) => {
        // create filter instance
        var filter = <IFilter>Object.create(window[$this.Namespace]['Filters'][filterName].prototype);
        filter.constructor.apply(filter, [$filter]);

        filter.Name = filterName;

        // call handler
        $this.FilterRegistered.Trigger(filter);

        // return filter
        return (input: any, args: any) => {
          return filter.Execution(input, args);
        }
      }]);
    }

    /** Initializes controllers Angular. */
    private InitAngularControllers(): void {
      var $this = this;
      
      // add controller
      $('[ng-controller]').each((i, element) => {
        // TODO: I do not like this...
        var name = $(element).attr('ng-controller');

        if (window[$this.Namespace] !== undefined && window[$this.Namespace]['Controllers'] !== undefined && typeof window[$this.Namespace]['Controllers'][name] === 'function') {
          console.log('Registered controller <' + $this.Namespace + '.Controllers.' + name + '>.');
          $this.Angular.controller(name, ['$scope', '$filter', '$http', '$window', '$location', '$timeout', '$compile', '$anchorScroll', ($scope, $filter, $http, $window, $location, $timeout, $compile, $anchorScroll) => {
            var controller = Object.create(window[$this.Namespace]['Controllers'][name].prototype);
            controller.constructor.apply(controller, [new AngularContext($scope, $filter, $http, $window, $location, $timeout, $compile, $anchorScroll, element)]);

            // add controller to list
            var newRegisteredController = new RegisteredController(name, controller);
            $this.RegisteredControllerList.push(newRegisteredController);

            // call handler
            $this.ControllerRegistered.Trigger(newRegisteredController);

            return controller;
          }]);
        } else {
          console.error('Controller <' + $this.Namespace + '.Controllers.' + name + '> not found.');
        }
      });
    }

    /** 
     * Searches contoller by name and returns a found controller.
     * 
     * @param name The controller name to search.
     */
    public GetRegisteredController<T>(name: string): RegisteredController<T> {
      this.RegisteredControllerList.forEach((item: RegisteredController<T>) => {
        if (item.Name == name) {
          return item;
        }
      });

      return null;
    }

  }

} 