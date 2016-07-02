# Участие в проекте SmallServerAdmin

Благодарим вас за проявление интереса к проекту!

Принять участие в проекте **SmallServerAdmin** может любой желающий.

В этом документе описан ряд принципов и правил для содействия **SmallServerAdmin**.

## Нормы поведения

Этот проект придерживается **[Contributor Covenant Code of Conduct](CODEOFCONDUCT.md)**.
Участвуя в проекте, вы должны соблюдать указанные в кодексе нормы поведения.

## Нашли проблему или хотите новый модуль или функционал?

Если вы обнаружили какую-либо ошибку или проблему, [отправьте уведомление](https://github.com/adminstock/ssa/issues/new) 
в [наш репозиторий на сайте **GitHub**](https://github.com/adminstock/ssa). 

Вы также можете самостоятельно исправить проблему и отправить ваши исправления (**Pull Request**).

Вы можете предложить разработку нового модуля или функции, [оставив сообщение](https://github.com/adminstock/ssa/issues/new)
в [нашем репозиторие](https://github.com/adminstock/ssa).

Либо вы можете самостоятельно сделать новый модуль или функционал и отправить ваш код нам (**Pull Request**).

Обратите внимание:

- Крупные изменения требуют предварительного согласования и обсуждения.
- Небольшие исправления или изменения, не затрагивающие архитектуру проекта, могут быть 
  отправлены в [наш репозиторий](https://github.com/adminstock/ssa) (**Pull Request**).

**Пожалуйста, используйте английский язык.**

Прежде чем добавлять сообщение, убедитесь, что подобная тема [еще не подымалась](https://github.com/adminstock/ssa/issues), 
в том числе отсутствует среди [закрытых обсуждений](https://github.com/adminstock/ssa/issues?q=is%3Aissue+is%3Aclosed).

## На передовую!

Проект приветствует новых участников, готовых пахать круглые сутки, как негры, в поте лица на голом энтузиазме :)

Чтобы начать работать с проектом, сделайте себе копию (**fork**) проекта **SmallServerAdmin** на **GitHub** 
и извлеките эту копию на локальный компьютер, на котором вы планируете работать с кодом проекта.

Внесите необходимые изменения в код, стараясь придерживаться [правил и рекомендаций](#Правила-написания-кода).

Убедитесь, что ваши изменения работают должным образом.

Если все хорошо, выполните отправку изменений в вашу копию репозитория на **GitHub** (**Commit** и **Push**).

Затем сделайте **Pull Request**, чтобы предложить ваши изменения нам.

Не забудьте написать (при отправке **Pull Request**), что вы сделали, 
чтобы было проще принять ваши изменения. Пожалуйста, используйте английский язык.

**Пожалуйста, перед написанием кода, обращайте внимание на [нерассмотренные запросы](https://github.com/adminstock/ssa/pulls), 
а также версию **SmallServerAdmin** в ветке [dev](https://github.com/adminstock/ssa/tree/dev).**

### Правила написания кода

Для обеспечения согласованности по всему исходному коду и для правильной работы рекомендуется придерживаться следующих правил:

- Все публичные классы, методы и свойства должны быть документированы;
- Используйте два пробела для создания отступов;
- Серверный код должен находиться в проекте `SmallServerAdmin`;
  - В сервером коде используются следующие пространства имен:
    - для API: `Api` (например: [#1](SmallServerAdmin/users/api.php), [#2](SmallServerAdmin/ssh/api.php));
    - для классов моделей: `Models` (например: [#1](SmallServerAdmin/sites/models/Site.php), [#2](SmallServerAdmin/files/models/FileSystemItemInfo.php));
    - для страниц: `ИмяМодуля` (например: [#1](SmallServerAdmin/users/index.php), [#2](SmallServerAdmin/monitoring/index.php));
  - Используйте класс `SSH` из файла `/ssh/api.php` для работы с **SSH**;
  - Для новых модулей не забывайте создавать файлы **README** и **CHANGELOG**;
  - Каждый модуль дожен иметь собственный номер версии в формате: _СтаршийНомер.Младший.Изменения_. Например: `1.0.0`;
  - Работать с **API** рекомендуется исключительно клиентским кодом (хотя классические postback запросы не запрещены);
  - Следите за кодировками файлов. Используйте **UTF-8** без **BOM**. Неправильная кодировка может быть критична для **PHP**;
- Клиентский код должен располагаться в проекте `FrontEndScripts`;
  - Используйте **TypeScript**;
  - Рекомендуется использовать **AngularJS**;
  - Компилированный клиентский под должен помещаться в папку `../SmallServerAdmin/Content` (файлы: `compiled.js` и `compiled.min.js`);
  - В клиентском коде используются следующие пространства имен:
    - `SmallServerAdmin` - корневое пространство имен;
    - `SmallServerAdmin.Controllers` - контроллеры;
    - `SmallServerAdmin.Filters` - пользовательские фильтры;
    - `SmallServerAdmin.Models` - классы моделей;
  - При назначении имен, рекомендуется использовать верблюжий стиль. 
	  Каждое слово с заглавной буквы.
    Например: `TheNewBestName`;
  - Используйте префиксы:
    - `I*` - для интерфейсов. Например: `ISelectPath`;
  - Используйте суффиксы:
    - `*Controller` - для контроллеров. Например: `UserListController`;
  - Используйте класс `ApiRequest` для работы с **WebAPI**;

### Структура проекта

#### Введение

На данный момент решение состоит из двух проектов: **SmallServerAdmin** и **FrontEndScripts**.

**SmallServerAdmin** - основной проект. 
В нем содержатся все страницы, серверный **API** и компилированный клиентский код.

**FrontEndScripts** - клиентский код в исходном виде (**TypeScript**).

#### SmallServerAdmin

Проект имеет строгую структуру.

Модули располагаются в корневом каталоге.

В одном каталоге может быть только один модуль.

Имя папки является именем модуля.

Ниже показана структура файлов проекта:

```
SmallServerAdmin
-- Content - стили, шрифты, изображения и компилированный клиентский код
-- Controls - пользовательские (серверные) элементы управления SSA
-- Layouts - шаблоны (мастер-страницы)
-- Libs - дополнительные серверные библиотеки
-- servers - папка с файлами конфигураций подключенных к панели серверов
-- api.php - основной класс API (все запросы к API модулей идут через него)
-- config.php - файл конфигурации WebForms.PHP (движок на котором работает SSA)
-- global.json и global.*.json - глобальные ресурсы локализации
-- global.php - глоабльный обработчик (WebForms.PHP)
-- index.php и index.html.php - главная страница
-- ssa.config.php - файл конфигурации SmallServerAdmin
-- любой модуль
---- api.php - реализует методы API модуля
---- index.php (+index.html.php) - главная страница модуля
---- menu.php - представляет модуль в левом меню (опционально)
---- widget.php - виджет для использования на главной странице панели (опционально)
---- любые другие файлы и папки модуля (опционально)
```

Каждый модуль должен иметь собственный файл **api.php**.
Этот файл должен содержать публичные методы **API**.

Вызов публичных методов **API** конкретного модуля осуществляется через корневой файл **api.php** 
(который расположен в корневом каталоге панели).

Как правило, работа с серверным **API** осуществляется посредствам клиентского кода.

#### FrontEndScripts

Клиентский кода написан на **TypeScript** для **AngularJS**.

```
FrontEndScripts
-- Controllers - классы контроллеров
-- Filters - пользовательские фильтры для AngularJS
-- Interfaces - пользовательские интерфейсы
-- libs - дополнительные клиентские библиотеки (в основном JavaScript)
-- Models - классы моделей (пока все в куче)
-- typings - определения типов для TypeScript
-- _app.ts - основной класс приложения
-- ApiRequest.ts - вспомогательный класс для работы с серверным API
-- bundleconfig.json - правила сборки для Visual Studio 2015
-- compiled.js.bundle - правила сборки для Visual Studio 2013
```

### Программное обеспечение

Вы можете использовать любое удобное программное обеспечение.
 Будет хорошо, если вы не забудете вносить ваши изменения в файлы
**[SmallServerAdmin.phpproj](SmallServerAdmin/SmallServerAdmin.phpproj)**,
**[FrontEndScripts.csproj](FrontEndScripts/FrontEndScripts.csproj)**,
**[compiled.js.bundle](FrontEndScripts/compiled.js.bundle)**,
**[bundleconfig.json](FrontEndScripts/bundleconfig.json)** и 
**[compilerconfig.json](SmallServerAdmin/Content/scss/compilerconfig.json)**.

Решение написано в **Visual Studio 2015**.

#### Visual Studio 2013 (редакции Professional или Ultimate)

* **[PHP Tools for Visual Studio](https://visualstudiogallery.msdn.microsoft.com/6eb51f05-ef01-4513-ac83-4c5f50c95fb5)**
* **[Web Essentials 2013](https://visualstudiogallery.msdn.microsoft.com/56633663-6799-41d7-9df7-0f2a504ca361)**

#### Visual Studio 2015

* **[PHP Tools for Visual Studio](https://visualstudiogallery.msdn.microsoft.com/6eb51f05-ef01-4513-ac83-4c5f50c95fb5)**
* **[Web Essentials 2015](https://visualstudiogallery.msdn.microsoft.com/ee6e6d8c-c837-41fb-886a-6b50ae2d06a2)**
* **[BundlerMinifier](https://visualstudiogallery.msdn.microsoft.com/9ec27da7-e24b-4d56-8064-fd7e88ac1c40)**
* **[Web Compiler](https://visualstudiogallery.msdn.microsoft.com/3b329021-cd7a-4a01-86fc-714c2d05bb6c)**

---

Для клиентского кода требуется **[TypeScript](http://www.typescriptlang.org/#download-links)** (v1.5-1.8), для стилей 
**[SCSS](http://sass-lang.com/)**.

Также рекомендуется расширение **[File Nesting](https://visualstudiogallery.msdn.microsoft.com/3ebde8fb-26d8-4374-a0eb-1e4e2665070c)** для **Visual Studio**,
которое поможет группировать файлы (такие как: *index.html.php*, *index.php*, *index.json*, *index.ru.json* и т.п.).

---

#### PHP

Для **PHP** требуется расширение **php_ssh2.dll**.

[Скачайте расширение Win32 SSH2 PECL](http://windows.php.net/downloads/pecl/releases/ssh2/0.12/)
и извлеките файл **php_ssh2.dll** в папку `C:\Program Files (x86)\IIS Express\PHP\v5.5\ext`,
а файл **libssh2.dll** поместите в папку `C:\Program Files (x86)\IIS Express\PHP\v5.5`.

Откройте файл `C:\Program Files (x86)\IIS Express\PHP\v5.5\php.ini` и довьте расширение: 
`extension=php_ssh2.dll` в секцию `[ExtensionList]`:

```
[ExtensionList]
; ... other extinsions
extension=php_ssh2.dll
```

_**Примечание:** Не забудьте перезапустить **IIS**, если он был запущен._

_**Предупреждение:** На данный момент, **SSH** без танцев с бубном работает нормально только с **PHP 5.5**.
Со старшими версиями (**PHP 5.6** и **PHP 7**) могут быть проблемы._

_**Примечание:** Бубен вам в любом случае понадобится, будьте к этому готовы :)_

Для получения дополнительной информации см. **[PHP Manual](http://php.net/manual/ru/ssh2.installation.php)**.

---

Для тестирования рекомндуется использовать локальные виртуальные машины
**[VirtualBox](https://www.virtualbox.org/)** или
**[VMWare](http://www.vmware.com)**.