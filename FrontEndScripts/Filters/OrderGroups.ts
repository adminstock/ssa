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
module SmallServerAdmin.Filters {

  /**
   * Filter to sort the list of groups.
   */
  export class OrderGroups implements Nemiro.IFilter {

    Name: string = 'OrderGroups';

    Filter: ng.IFilterService;

    constructor(filter: ng.IFilterService) {
      this.Filter = filter;
    }

    public Execution(groups: Array<Models.Group>, userGroups: Array<string>): any {
      if (groups === undefined) {
        return null;
      }

      var result = [];
      var userInGroups = [];
      
      if (userGroups !== undefined) {
        angular.forEach(groups, function (group) {
          if (userGroups.indexOf(group.Name) != -1) {
            userInGroups.push(group);
          } else {
            result.push(group);
          }
        });
      } else {
        result = groups;
      }

      result.sort((a: Models.Group, b: Models.Group) => {
        return a.Name.localeCompare(b.Name);
      });

      userInGroups.sort((a: Models.Group, b: Models.Group) => {
        return a.Name.localeCompare(b.Name);
      });
      
      return userInGroups.concat(result);
    }

  }

}