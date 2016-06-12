# Change Log

All notable changes to this module will be documented in this file.

## [1.0.7] - 2016-06-12
### Added
- Localized interface to Russian.

### Fixed
- Case with disabled Apache and HTAN;
- Search sites (*api.php*).

## [1.0.6] - 2016-04-27
### Added
- Public API method `ReloadServices` for reload services (*api.php*);
- Interface and new logic to services reload (*edit.html.php*, *SiteEditorController.ts*);
- `ReloadingInfo` class to *FrontEndScripts*;
- `ApiRequest.Echo` (*ApiRequest.ts*) and handler to root *api.php*.

### Changed
- Help to bottom (*edit.html.php*), fixed errors.

### Removed
- `ReloadWebServer` private method from *api.php*;
- `NoReload` parameter from `SetSiteStatus` and `DeleteSite` (*api.php*).

### Fixed
- ASP.NET config master.

## [1.0.5] - 2016-04-21
### Added
- Reload php-fpm;
- Checking Nginx, Apache and HTAN.Runner paths;
- `NoReload` in `SetSiteStatus` (*api.php*).

### Fixed
- Typos in config patterns;
- Slash escaping (*api.php*, method `SaveSite`);
- Error with empty sites in list;
- Optimization for small screens;
- Reload servers problem fixed.

## [1.0.4] - 2016-04-10
### Added
- Help;
- Test configs to `ReloadWebServer` in *api.php*.

### Changed
- Site list v1.0.3 for widget.

### Fixed
- Patterns for Nginx (*SiteEditorController.ts*);
- Deletion unused configs (*api.php*, method `SaveSite`);
- `$` escaping problem (*api.php*, method `SaveSite`).

## [1.0.3] - 2016-03-27
### Added
- HTAN.Runner support;
- Disable/Enable individual configuration files;
- Wizard to config creation.

### Changed
- Interface of site list:
  - icons replaced to labels;
  - new change status button.

### Fixed
- Incorrect mapping configuration files.

## [1.0.2] - 2016-03-12
### Fixed
- Reload Nginx and Apache in the `SetSiteStatus` (*api.php*).

## [1.0.1] - 2016-03-09
### Added
- Widget (*widget.php*).

## [1.0.0] - 2016-02-28
The module was created by @alekseynemiro.