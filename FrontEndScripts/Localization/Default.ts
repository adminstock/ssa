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
module SmallServerAdmin.Localization {

  /** Default localization resources. */
  export class Default implements ILocalization {

    //#region ..Common..

    /**
     * Loading...
     */
    public Loading: string = 'Loading...';

    /** Saving... */
    public Saving: string = 'Saving...';

    /** Deleting... */
    public Deleting: string = 'Deleting...';

    /** Preparing... */
    public Preparing: string = 'Preparing...';

    /** Preparing form. Please wait... */
    public PreparingFormWait: string = 'Preparing form. Please wait...';

    /** Success */
    public Success: string = 'Success';

    /**
     * Error
     */
    public Error: string = 'Error';

    /** Ok */
    public Ok: string = 'Ok';

    /** Total */
    public Total: string = 'Total';

    /** Kb */
    public Kb: string = 'Kb';

    /** Mb */
    public Mb: string = 'Mb';

    /** Gb */
    public Gb: string = 'Gb';

    /** In Use */
    public InUse: string = 'In Use';

    /** New Group */
    public NewGroup: string = 'New Group';

    /** New User */
    public NewUser: string = 'New User';


    //#endregion
    //#region ..MasterController..

    /**
     * Config not found.<br />Please check <code>$config[\'client\']</code> of the <strong>/ssa.config.php</strong>.
     */
    public ConfigNotFound: string = 'Config not found.<br />Please check <code>$config[\'client\']</code> of the <strong>/ssa.config.php</strong>.';

    /**
     * Servers controller not found.
     */
    public ServersControllerNotFound: string = 'Servers controller not found.';

    //#endregion
    //#region ..FileListController..

    /**
     * Loading the file contents...
     */
    public LoadingFileContents: string = 'Loading the file contents...';

    /**
     * Getting the file info...
     */
    public GettingFileInfo: string = 'Getting the file info...';
    
    //#endregion
    //#region ..PanelServersController..

    /** Unable to connect to the server.<br />Check the connection settings and try again. */
    public UnableToConnectTheServer: string = 'Unable to connect to the server.<br />Check the connection settings and try again.';

    /** Connection error */
    public ConnectionError: string = 'Connection error';

    //#endregion
    //#region ..ServiceListController..

    /** No services to reload. */
    public NoServicesToReload: string = 'No services to reload.';

    /**
     * Cannot reload service {0}.
     */
    public CannotReloadService: string = 'Cannot reload service {0}.';

    //#endregion
    //#region ..ServiceListController..

    /** Incorrect site name! */
    public IncorrectSiteName: string = 'Incorrect site name!';

    /**
     * Is removed the site <strong>{0}</strong>. Please wait...
     */
    public IsRemovedSiteWait: string = 'Is removed the site <strong>{0}</strong>. Please wait...';

    /** Loading list of sites... */
    public LoadingListOfSites: string = 'Loading list of sites...';

    //#endregion
    //#region ..SVN..

    /** Obtaining the group data from the server. Please wait... */
    public ObtainingTheGroupWait: string = 'Obtaining the group data from the server. Please wait...';

    /** Saving the group. Please wait... */
    public SavingTheGroupWait: string = 'Saving the group. Please wait...';

    /** Saved successfully!<br />Loading list of groups. Please wait... */
    public SavedSuccessfullyLoadingListOfGroups: string = 'Saved successfully!<br />Loading list of groups. Please wait...';

    /** Incorrect group name! */
    public IncorrectGroupName: string = 'Incorrect group name!';

    /**
     * Is removed the group <strong>{0}</strong>. Please wait...
     */
    public IsRemovedTheGroupWait: string = 'Is removed the group <strong>{0}</strong>. Please wait...';

    /** Loading list of groups... */
    public LoadingListOfGroups: string = 'Loading list of groups...';

    /** Obtaining the repository info from the server. Please wait... */
    public ObtainingTheRepositoryWait: string = 'Obtaining the repository info from the server. Please wait...';

    /** New Repository */
    public NewRepository: string = 'New Repository';

    /** Saving the respository. Please wait... */
    public SavingTheRepositoryWait: string = 'Saving the respository. Please wait...';

    /** Saved successfully!<br />Loading list of repositories. Please wait... */
    public SavedSuccessfullyLoadingListOfRepositories: string = 'Saved successfully!<br />Loading list of repositories. Please wait...';

    /** Incorrect repository name! */
    public IncorrectRepositoryName: string = 'Incorrect repository name!';

    /** Is removed the repository <strong>{0}</strong>. Please wait... */
    public IsRemovedTheRepositoryWait: string = 'Is removed the repository <strong>{0}</strong>. Please wait...';

    /** Loading list of repositories... */
    public LoadingListOfRepositories: string = 'Loading list of repositories...';

    //#endregion
    //#region ..Users..

    /** Obtaining the user data from the server. Please wait... */
    public ObtainingTheUserWait: string = 'Obtaining the user data from the server. Please wait...';

    /** Saving the user. Please wait... */
    public SavingTheUserWait: string = 'Saving the user. Please wait...';

    /** Saved successfully!<br />Loading list of users. Please wait... */
    public SavedSuccessfullyLoadingListOfUsers: string = 'Saved successfully!<br />Loading list of users. Please wait...';

    /** Is removed the user <strong>{0}</strong>. Please wait... */
    public IsRemovedUserWait: string = 'Is removed the user <strong>{0}</strong>. Please wait...';

    /** Loading list of users... */
    public LoadingListOfUsers: string = 'Loading list of users...';

    /** The account has been successfully updated! */
    public TheAccountHasBeenUpdated: string = 'The account has been successfully updated!';

    /** The user data has been successfully updated! */
    public TheUserHasBeenUpdated: string = 'The user data has been successfully updated!';

    /** The user groups list has been successfully updated! */
    public TheUserGroupsHasBeenUpdated: string = 'The user groups list has been successfully updated!';

    /** The user has been successfully created! */
    public TheUserHasBeenCreated: string = 'The user has been successfully created!';

    /** Incorrect user name! */
    public IncorrectUserName: string = 'Incorrect user name!';

    //#endregion

  }

}