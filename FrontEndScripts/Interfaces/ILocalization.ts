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

  /** Implements localization resources. */
  export interface ILocalization {

    /**
     * Loading...
     */
    Loading: string;

    /**
     * Loading the file contents...
     */
    LoadingFileContents: string;

    /**
     * Getting the file info...
     */
    GettingFileInfo: string;

    /**
     * Error
     */
    Error: string;

    /**
     * Config not found.<br />Please check <code>$config[\'client\']</code> of the <strong>/ssa.config.php</strong>.
     */
    ConfigNotFound: string;

    /**
     * Servers controller not found.
     */
    ServersControllerNotFound: string;

    /** Total */
    Total: string;

    /** Kb */
    Kb: string;

    /** Mb */
    Mb: string;

    /** Gb */
    Gb: string;

    /** In Use */
    InUse: string;

    /** Unable to connect to the server.<br />Check the connection settings and try again. */
    UnableToConnectTheServer: string;

    /** Connection error */
    ConnectionError: string;

    /** No services to reload. */
    NoServicesToReload: string;

    /**
     * Cannot reload service {0}.
     */
    CannotReloadService: string;

    /** Incorrect site name! */
    IncorrectSiteName: string;

    /** Deleting... */
    Deleting: string;

    /**
     * Is removed the site <strong>{0}</strong>. Please wait...
     */
    IsRemovedSiteWait: string;

    /** Loading list of sites... */
    LoadingListOfSites: string;

    /** Preparing... */
    Preparing: string;

    /** Preparing form. Please wait... */
    PreparingFormWait: string;

    /** Obtaining the group data from the server. Please wait... */
    ObtainingTheGroupWait: string;

    /** Saving... */
    Saving: string;

    /** Saving the group. Please wait... */
    SavingTheGroupWait: string;

    /** Saved successfully!<br />Loading list of groups. Please wait... */
    SavedSuccessfullyLoadingListOfGroups: string;

    /** Incorrect group name! */
    IncorrectGroupName: string;

    /**
     * Is removed the group <strong>{0}</strong>. Please wait...
     */
    IsRemovedTheGroupWait: string;

    /** Loading list of groups... */
    LoadingListOfGroups: string;

    /** New Group */
    NewGroup: string;

    /** Obtaining the repository info from the server. Please wait... */
    ObtainingTheRepositoryWait: string;

    /** New Repository */
    NewRepository: string;

    /** Saving the respository. Please wait... */
    SavingTheRepositoryWait: string;

    /** Saved successfully!<br />Loading list of repositories. Please wait... */
    SavedSuccessfullyLoadingListOfRepositories: string;

    /** Incorrect repository name! */
    IncorrectRepositoryName: string;

    /** Is removed the repository <strong>{0}</strong>. Please wait... */
    IsRemovedTheRepositoryWait: string;

    /** Loading list of repositories... */
    LoadingListOfRepositories: string;

    /** New User */
    NewUser: string;

    /** Obtaining the user data from the server. Please wait... */
    ObtainingTheUserWait: string;

    /** Saving the user. Please wait... */
    SavingTheUserWait: string;

    /** Saved successfully!<br />Loading list of users. Please wait... */
    SavedSuccessfullyLoadingListOfUsers: string;

    /** Is removed the user <strong>{0}</strong>. Please wait... */
    IsRemovedUserWait: string;

    /** Loading list of users... */
    LoadingListOfUsers: string;

    /** Success */
    Success: string;

    /** Ok */
    Ok: string;

    /** The account has been successfully updated! */
    TheAccountHasBeenUpdated: string;

    /** The user data has been successfully updated! */
    TheUserHasBeenUpdated: string;

    /** The user groups list has been successfully updated! */
    TheUserGroupsHasBeenUpdated: string;

    /** The user has been successfully created! */
    TheUserHasBeenCreated: string;

    /** Incorrect user name! */
    IncorrectUserName: string;

    /**
     * Server is required.
     */
    ServerIsRequired: string;

  }

}