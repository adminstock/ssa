# Contributing to SmallServerAdmin

Thanks for taking the time to contribute!

The following is a set of guidelines for contributing to **SmallServerAdmin**.

## Code of Conduct

This project adheres to the [Contributor Covenant Code of Conduct](CODEOFCONDUCT.md).
By participating, you are expected to uphold this code.

## Found an Issue?

If you find a bug in the source code, you can help us by [submitting an issue](https://github.com/adminstock/ssa/issues/new) 
to our [GitHub Repository](https://github.com/adminstock/ssa). 
Even better you can submit a **Pull Request** with a fix.

## Want a new Module or Feature?

You can request a new module or feature by [submitting an issue](https://github.com/adminstock/ssa/issues/new) 
to our [GitHub Repository](https://github.com/adminstock/ssa).

Please note:

- Major Changes that you wish to contribute to the project should be discussed.
- Small Changes can be crafted and submitted to the 
  [GitHub Repository](https://github.com/adminstock/ssa)
  as a **Pull Request**.

## Code Contributions

The project welcomes new contributors.

To get started, fork the **SmallServerAdmin** on **GitHub** and check out your copy locally.

Make your changes, in accordance with our [rules and recommendations](#Coding-Rules).

Make sure that your changes are working properly.

**Commit** and **Push** your changes.

Send **Pull Request**.

**Please, before writing code, pay attention to [requests queue](https://github.com/adminstock/ssa/pulls)
and see SmallServerAdmin version in the [dev](https://github.com/adminstock/ssa/tree/dev) branch.**

### Coding Rules

To ensure consistency throughout the source code and to work properly it is recommended to follow the rules:

- All public classes, methods and properties must be documented;
- Use two spaces for indentation;
- Server-side code must be placed in the `SmallServerAdmin`;
  - For server-side code use namespaces:
    - for API: `Api` (examples: [#1](SmallServerAdmin/users/api.php), [#2](SmallServerAdmin/ssh/api.php));
    - for Models: `Models` (examples: [#1](SmallServerAdmin/sites/models/Site.php), [#2](SmallServerAdmin/files/models/FileSystemItemInfo.php));
    - for pages: `ModuleName` (examples: [#1](SmallServerAdmin/users/index.php), [#2](SmallServerAdmin/monitoring/index.php));
  - Use `SSH` class ot the `/ssh/api.php` to work with **SSH**;
  - For new modules, do not forget to create a **README** and **CHANGELOG** files;
  - Each module should have a version number in the following format: _Major.Minor.Changes_. For example: `1.0.0`;
  - Recommended to work with the **API** using client-side code;
  - Watch out for encoding files. Use **UTF-8** without **BOM**. This can be critical for **PHP**;
- Client-side code must be placed in the `FrontEndScripts`;
  - Use **TypeScript**;
  - Recommended to use **AngularJS**;
  - Compiled code must be placed in the folder `../SmallServerAdmin/Content` (files: `compiled.js` and `compiled.min.js`);
  - For client-side code use namespaces:
    - `SmallServerAdmin` - root namespace;
    - `SmallServerAdmin.Controllers` - controllers;
    - `SmallServerAdmin.Filters` - custom filters;
    - `SmallServerAdmin.Models` - models;
  - Recommended to use the camel style. The first word with capital letters.
    For example: `TheNewBestName`;
  - Use prefix:
    - `I*` - for interfaces. For example: `ISelectPath`;
  - Use suffix:
    - `*Controller` - for controllers. For example: `UserListController`;
  - Use `ApiRequest` class to work with **WebAPI**;

### Structure of the project

#### Introduction

The solution consists of two projects: **SmallServerAdmin** and **FrontEndScripts**.

**SmallServerAdmin** - the main project. 
It contains client pages and classes to interact with a server.

**FrontEndScripts** - contains client-side code.

#### SmallServerAdmin

The project has a strict structure.

At the root folder located modules.

In the same folder can be only one module.

The module name is the name of the folder.

The following shows the structure of the project files:

```
SmallServerAdmin
-- Content - styles, fonts, images and client-side scripts
-- Controls - user controls of the SSA
-- Layouts - master pages (templates)
-- Libs - additional server-side libraries
-- servers - contains configuration files connected servers
-- api.php - the main file for API
-- config.php - config of the WebForms.PHP
-- global.json and global.*.json - localization resources
-- global.php - global handler (WebForms.PHP)
-- index.php and index.html.php - main page
-- ssa.config.php - config of SmallServerAdmin
-- any module
---- api.php - methods of API
---- index.php (+index.html.php) - main page of module
---- menu.php - view of the module in the navigation menu (optional)
---- widget.php - widget to display on the panel main page (optional)
---- any files and folders (optional)
```

Each module should have its own **api.php** file.
This file must contain the public **API** methods.

Call **API** methods through **api.php** file located in the root directory.

As a rule, work with the **API** is through the client-side code.

#### FrontEndScripts

Client-side code written in **TypeScript** for **AngularJS**.

```
FrontEndScripts
-- Controllers - controllers
-- Filters - custom filters for AngularJS
-- Interfaces - additional interfaces
-- libs - additional libraries
-- Models - models (yet in a heap)
-- typings - type definitions for TypeScript and Visual Studio
-- _app.ts - main application class
-- ApiRequest.ts - API request helper
-- bundleconfig.json - building rules (for VS2015)
-- compiled.js.bundle - building rules (for VS2013)
```

### Software

You can use any handy software. 
It will be nice if you include your changes to 
**[SmallServerAdmin.phpproj](SmallServerAdmin/SmallServerAdmin.phpproj)**,
**[FrontEndScripts.csproj](FrontEndScripts/FrontEndScripts.csproj)**,
**[compiled.js.bundle](FrontEndScripts/compiled.js.bundle)**,
**[bundleconfig.json](FrontEndScripts/bundleconfig.json)** and 
**[compilerconfig.json](SmallServerAdmin/Content/scss/compilerconfig.json)**.

The project is written in **Visual Studio 2015**.

#### Visual Studio 2013 (Professional or Ultimate)

* **[PHP Tools for Visual Studio](https://visualstudiogallery.msdn.microsoft.com/6eb51f05-ef01-4513-ac83-4c5f50c95fb5)**
* **[Web Essentials 2013](https://visualstudiogallery.msdn.microsoft.com/56633663-6799-41d7-9df7-0f2a504ca361)**

#### Visual Studio 2015

* **[PHP Tools for Visual Studio](https://visualstudiogallery.msdn.microsoft.com/6eb51f05-ef01-4513-ac83-4c5f50c95fb5)**
* **[Web Essentials 2015](https://visualstudiogallery.msdn.microsoft.com/ee6e6d8c-c837-41fb-886a-6b50ae2d06a2)**
* **[BundlerMinifier](https://visualstudiogallery.msdn.microsoft.com/9ec27da7-e24b-4d56-8064-fd7e88ac1c40)**
* **[Web Compiler](https://visualstudiogallery.msdn.microsoft.com/3b329021-cd7a-4a01-86fc-714c2d05bb6c)**

---

And also required **[TypeScript](http://www.typescriptlang.org/#download-links)** (v1.5-1.8) and 
**[SCSS](http://sass-lang.com/)**.

To manage nesting files recommended the extension **[File Nesting](https://visualstudiogallery.msdn.microsoft.com/3ebde8fb-26d8-4374-a0eb-1e4e2665070c)**.

---

#### PHP

For **PHP** required **php_ssh2.dll**.

Download [Win32 SSH2 PECL extension](http://windows.php.net/downloads/pecl/releases/ssh2/0.12/)
and extract **php_ssh2.dll** to `C:\Program Files (x86)\IIS Express\PHP\v5.5\ext`,
and **libssh2.dll** to `C:\Program Files (x86)\IIS Express\PHP\v5.5`.

Open `C:\Program Files (x86)\IIS Express\PHP\v5.5\php.ini` and add: 
`extension=php_ssh2.dll` to `[ExtensionList]`:

```
[ExtensionList]
; ... other extinsions
extension=php_ssh2.dll
```

_**NOTE:** Restart **IIS**, if is started._

_**ATTENTION:** At the time of this writing, **SSH** no problem to work with **PHP 5.5**.
In **PHP 5.6** and **PHP 7** had problems._

For more information please see [PHP Manual](http://php.net/manual/en/ssh2.installation.php).

---

To test it is recommended to use a virtual machines of 
**[VirtualBox](https://www.virtualbox.org/)** or 
**[VMWare](http://www.vmware.com)**.