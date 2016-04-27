# Change Log

All notable changes to this module will be documented in this file.

## [1.0.4] - 2016-04-10
### Added
- `NormalizeErrorMessage` to *api.php*.

### Changed
- `IsError` in *api.php*: check for keywords (`invalid|error|failed`).

## [1.0.3] - 2016-03-23
### Changed
- *api.php*: sudo password enter supported.

### Added
- *api.php*: `IsError` and `MakeCommand` for sudo password;
- *ssa.config.php*: `ssh_required_password` for sudo password.

## [1.0.2] - 2016-03-05
### Added
- *api.php*: added new method `Execute2`.

## [1.0.1] - 2016-03-03
### Changed
- *api.php* (`Execute`): added parameter `$dontTrim`.

## [1.0.0] - 2016-02-07
The module was created by @alekseynemiro.