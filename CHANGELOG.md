# Change Log

All notable changes to SmallServerAdmin will be documented in this file.

## [1.0.54-alpha] - unreleased
### Added
- Support for multiple update sources;
- Markdown support;
- Editing server configurations.

### Changed
- */ssa.config.php*:
  - added `$config['settings_default_branch']`;
  - added `$config['settings_update_sources']`;
  - added `DefaultBranch` to `$config['client']`.

### Removed
- Default server and ssh settings from *ssa.config.php* 
  (connection settings are expected in a config files in the folder */servers*).

### Fixed
- Subversion API (*/svn/api.php*).

## [1.0.49-alpha] - 2016-06-12
### Added
- **ssh2** checking;
- Connection test;
- Client-side localization and resources for Russian;
- Resources for German (only server-side);
- `HtanEnabled` to `$config['client']`.

### Changed
- Widget of news: Twitter replaced to Facebook and VKontakte.

### Fixed
- Search sites (*/sites/api.php*).

## [1.0.39-alpha] - 2016-05-03
### Added
- @adminstock news widget;
- New module - **settings** with update functions;
- Logout button;
- Support for multiple servers;
- Move, copy and mass deletion files and folders.

### Changed
- References to script and style file moved to `StaticIncludes` control;
- The language selection interface changed.

### Fixed
- Fixed problem with setting the password to svn users, 
  if the password file does not exist;
- Error messages on Russian;
- Fixed detection of `Content-Type` in **API**.

## [1.0.32-alpha] - 2016-04-27
First alpha.