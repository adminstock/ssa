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

	/** Russian resources. */
  export class RU implements ILocalization {

    //#region ..Common..

    /**
     * Loading...
     */
    public Loading: string = 'Загрузка...';

    /** Saving... */
    public Saving: string = 'Сохранение...';

    /** Deleting... */
    public Deleting: string = 'Удаление...';

    /** Preparing... */
    public Preparing: string = 'Подготовка...';

    /** Preparing form. Please wait... */
    public PreparingFormWait: string = 'Подготовка формы. Пожалуйста, подождите...';

    /** Success */
    public Success: string = 'Успех';

		/**
		 * Error
		 */
    public Error: string = 'Ошибка';

    /** Ok */
    public Ok: string = 'Ok';

    /** Total */
    public Total: string = 'Всего';

    /** Kb */
    public Kb: string = 'Кб';

    /** Mb */
    public Mb: string = 'Мб';

    /** Gb */
    public Gb: string = 'Гб';

    /** In Use */
    public InUse: string = 'Использовано';

    /** New Group */
    public NewGroup: string = 'Новая группа';

    /** New User */
    public NewUser: string = 'Новый пользователь';


    //#endregion
    //#region ..MasterController..

    /**
     * Config not found.<br />Please check <code>$config[\'client\']</code> of the <strong>/ssa.config.php</strong>.
     */
    public ConfigNotFound: string = 'Конфигурация не найдена.<br />Пожалуйста, проверьте параметр <code>$config[\'client\']</code> в файле <strong>/ssa.config.php</strong>.';

    /**
     * Servers controller not found.
     */
    public ServersControllerNotFound: string = 'Контроллер управления серверами не найден.';

    //#endregion
    //#region ..FileListController..

    /**
     * Loading the file contents...
     */
    public LoadingFileContents: string = 'Получение содержимого файла...';

		/**
		 * Getting the file info...
		 */
    public GettingFileInfo: string = 'Запрос информации о файле...';

    //#endregion
    //#region ..PanelServersController..

    /** Unable to connect to the server.<br />Check the connection settings and try again. */
    public UnableToConnectTheServer: string = 'Невозможно подключиться к серверу.<br />Проверьте параметры соединения и повторите попытку.';

    /** Connection error */
    public ConnectionError: string = 'Ошибка соединения';

    //#endregion
    //#region ..ServiceListController..

    /** No services to reload. */
    public NoServicesToReload: string = 'Нет служб для перезапуска.';

    /**
     * Cannot reload service {0}.
     */
    public CannotReloadService: string = 'Не удалось перезапустить службу {0}.';

    //#endregion
    //#region ..ServiceListController..

    /** Incorrect site name! */
    public IncorrectSiteName: string = 'Указано неправильное имя сайта!';

    /**
     * Is removed the site <strong>{0}</strong>. Please wait...
     */
    public IsRemovedSiteWait: string = 'Идет удаление сайта <strong>{0}</strong>. Пожалуйста, подождите...';

    /** Loading list of sites... */
    public LoadingListOfSites: string = 'Загрузка списка сайтов...';

    //#endregion
    //#region ..SVN..

    /** Obtaining the group data from the server. Please wait... */
    public ObtainingTheGroupWait: string = 'Получение информации о группе. Пожалуйста, подождите...';

    /** Saving the group. Please wait... */
    public SavingTheGroupWait: string = 'Сохранение группы. Пожалуйста, подождите...';

    /** Saved successfully!<br />Loading list of groups. Please wait... */
    public SavedSuccessfullyLoadingListOfGroups: string = 'Группа успешно сохранена!<br />Обновление списка групп. Пожалуйста, подождите...';

    /** Incorrect group name! */
    public IncorrectGroupName: string = 'Указано неправильное имя группы!';

    /**
     * Is removed the group <strong>{0}</strong>. Please wait...
     */
    public IsRemovedTheGroupWait: string = 'Идет удаление группы <strong>{0}</strong>. Пожалуйста, подождите...';

    /** Loading list of groups... */
    public LoadingListOfGroups: string = 'Загрузка списка групп...';

    /** Obtaining the repository info from the server. Please wait... */
    public ObtainingTheRepositoryWait: string = 'Получение информации о хранилище. Пожалуйста, подождите...';

    /** New Repository */
    public NewRepository: string = 'Новое хранилище';

    /** Saving the respository. Please wait... */
    public SavingTheRepositoryWait: string = 'Сохранение хранилища. Пожалуйста, подождите...';

    /** Saved successfully!<br />Loading list of repositories. Please wait... */
    public SavedSuccessfullyLoadingListOfRepositories: string = 'Хранилище успешно сохранено!<br />Обновление списка. Пожалуйста, подождите...';

    /** Incorrect repository name! */
    public IncorrectRepositoryName: string = 'Указано неправильное имя хранилища!';

    /** Is removed the repository <strong>{0}</strong>. Please wait... */
    public IsRemovedTheRepositoryWait: string = 'Идет удаление хранилища <strong>{0}</strong>. Пожалуйста, подождите...';

    /** Loading list of repositories... */
    public LoadingListOfRepositories: string = 'Загрузка списка хранилищ...';

    //#endregion
    //#region ..Users..

    /** Obtaining the user data from the server. Please wait... */
    public ObtainingTheUserWait: string = 'Получение данных пользователя с сервера. Пожалуйста, подождите...';

    /** Saving the user. Please wait... */
    public SavingTheUserWait: string = 'Сохранение пользователя. Пожалуйста, подождите...';

    /** Saved successfully!<br />Loading list of users. Please wait... */
    public SavedSuccessfullyLoadingListOfUsers: string = 'Пользователь успешно сохранен!<br />Обновление списка пользователей. Пожалуйста, подождите...';

    /** Is removed the user <strong>{0}</strong>. Please wait... */
    public IsRemovedUserWait: string = 'Идет удаление пользователя <strong>{0}</strong>. Пожалуйста, подождите...';

    /** Loading list of users... */
    public LoadingListOfUsers: string = 'Загрузка списка пользователей...';

    /** The account has been successfully updated! */
    public TheAccountHasBeenUpdated: string = 'Учетная запись успешно обновлена!';

    /** The user data has been successfully updated! */
    public TheUserHasBeenUpdated: string = 'Данные пользователя успешно обновлены!';

    /** The user groups list has been successfully updated! */
    public TheUserGroupsHasBeenUpdated: string = 'Список групп пользователя успешно обновлен!';

    /** The user has been successfully created! */
    public TheUserHasBeenCreated: string = 'Пользователь успешно создан!';

    /** Incorrect user name! */
    public IncorrectUserName: string = 'Указано неправильное имя пользователя!';

    //#endregion

	}

}