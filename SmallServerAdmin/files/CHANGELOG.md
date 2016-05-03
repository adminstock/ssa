# Change Log

All notable changes to this module will be documented in this file.

## [1.0.6] - 2016-05-03
### Added
- Move, copy and mass deletion files and folders.

### Changed
- Disabled `files_auto_reload`.

## [1.0.5] - 2016-04-21
### Added
- Open folders and files by double click (`FileListController.ts`, `treeViewTemplate.html`);

### Fixed
- Remove escaping `EOF` string (*api.php*, method `Save`).

## [1.0.4] - 2016-04-18
### Fixed
- Slash escaping (*api.php*, method `Save`).

### Added
- *FileListControoler.ts* (`Open`): detect PHP, Perl, Python, 
  C, C++, C#, Java, JavaScript, CSS, SCSS, LESS, HTML, 
  SQL, Markdown, INI by file extension 
  and Nginx, Apache configs by content;
- Groups for new files.

## [1.0.3] - 2016-04-10
### Added
- New parameter `files_auto_reload` to *ssa.config* 
  and usage it in `Save` of the *api.php*;

### Fixed
- `$` escaping problem (*api.php*, method `Save`).

## [1.0.2] - 2016-03-20
### Added
- *FileListControoler.ts* (`Open`): detect shell files by `!#` and xml by `?xml`.

### Changed
- *api.php* (`Save`): `daemon-reload` for files of `/etc/init.d`;

## [1.0.1] - 2016-03-12
### Added
- Re-open file button;
- Clear content button.

## [1.0.0] - 2016-03-08
The module was created by @alekseynemiro.