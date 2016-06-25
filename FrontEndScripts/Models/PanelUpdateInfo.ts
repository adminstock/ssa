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
module SmallServerAdmin.Models {

  /**
   * Represents item with information about the update of the SmallServerAdmin.
   */
  export class PanelUpdateInfo {

    public Branch: string;

    public BranchTitle: string;

    public BranchDescription: string;

    public NeedUpdate: boolean;

    public LatestVersion: string;

    public CurrentVersion: string;

    public Changes: string;

    public Updated: boolean;

  }

}