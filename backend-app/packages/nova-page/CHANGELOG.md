# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.1.2] - 2021-03-04

### Changed

- Changed `value` column from `varchar` to `text` to support SQL Server (thanks to [@davidjosephhayes](https://github.com/davidjosephhayes))
- Display Nova error toast on validation failure (thanks to [@medvinator](https://github.com/medvinator))
- Updated packages

PS: Running `php artisan migrate` is recommended after installing this version.

## [3.1.1] - 2021-01-18

### Added

- Added Persian (Farsi) translations (thanks to [@FaridAghili](https://github.com/FaridAghili))
- Added `nova_set_setting_value` helper

### Changed

- Updated packages

## [3.1.0] - 2020-12-04

### Added

- Added page HTML `<title>` to Settings (thanks to [@medvinator](https://github.com/medvinator))
- Added Russian translations (thanks to [@medvinator](https://github.com/medvinator))
- Added fields authorization support
- Added section about authorization to `README.md`

## [3.0.0] - 2020-12-02

### Changed

- Fixed duplicate panels (thanks to [@mxm1070](https://github.com/mxm1070))
- Dropped PHP 7.1, Laravel 6 and Nova 2 support
- Updated packages

## [2.6.2] - 2020-11-18

### Changed

- Improved navigation logic
- Fixed an issue with querying fields when there's only the general path

## [2.6.1] - 2020-11-18

### Changed

- Avoid using mass assignment to allow using observers

## [2.6.0] - 2020-11-18

### Added

- Added multiple paths/domains support to split fields between multiple pages (thanks to [@MarikaMustV](https://github.com/MarikaMustV) and [@lstoyanoff](https://github.com/lstoyanoff))
- Added [eminiarts/nova-tabs](https://github.com/eminiarts/nova-tabs) support (thanks to [@MarikaMustV](https://github.com/MarikaMustV))

### Changed

- Updated packages

## [2.5.8] - 2020-11-18

### Changed

- Fixed some translations not loading in some cases

## [2.5.7] - 2020-11-17

### Changed

- Fixed some translations not loading in some cases

## [2.5.6] - 2020-11-03

### Changed

- Upgrade translations loader
- Fixed translations publishing

## [2.5.5] - 2020-10-22

### Added

- Added Estonian (et) translations

### Changed

- Replaced translations logic with `nova-translations-loader`
- Updated packages

## [2.5.4] - 2020-07-29

### Changed

- Updated packages
- Fixed double call to update method in Mozilla Firefox (thanks to [@vkazakevich](https://github.com/vkazakevich))

## [2.5.3] - 2020-07-08

### Changed

- Fixed type error (thanks to [@indykoning](https://github.com/indykoning))

## [2.5.2] - 2020-07-08

### Changed

- Fixed the requirement to publish config (thanks to [@indykoning](https://github.com/indykoning))

## [2.5.1] - 2020-07-08

### Added

- Added option to override Settings model (thanks to [@indykoning](https://github.com/indykoning))

### Changed

- Updated packages

## [2.5.0] - 2020-04-16

### Added

- Added default optional value to `nova_get_setting($key, $default = null)` helper (thanks to [@Landish](https://github.com/Landish))
- Added `type="submit"` to Settings submit button to allow enter press to save the form (thanks to [@Landish](https://github.com/Landish))

### Changed

- Fixed `Date` and `DateTime` fields
- Updated packages

## [2.4.3] - 2020-03-19

### Changed

- Fixed `->rules('required')` not displaying asterisk

## [2.4.2] - 2020-03-05

### Added

- Support Nova 3.0 in `composer.json` requirements

## [2.4.1] - 2020-03-02

### Changed

- Fixed typo

## [2.4.0] - 2020-03-02

### Added

- Added config file with `reload_page_on_save` option

### Changed

- Read-only fields are no longer being saved
- Migration(s) are now loaded automatically. The following migration file can be deleted from your project:
  - `2019_08_13_000000_create_nova_settings_table.php`
- Updated packages

## [2.3.0] - 2020-02-25

### Added

- Added translations support

## [2.2.0] - 2020-02-21

### Added

- Added [epartment/nova-dependency-container](https://github.com/epartment/nova-dependency-container) support

## [2.1.1] - 2020-02-11

### Changed

- `nova_get_setting()` and `nova_get_settings()` now cache values to avoid duplicate queries

## [2.1.0] - 2020-01-27

### Added

- Allow passing `callable` that returns an array of fields as first argument of `NovaPage::addSettingsFields()`

## [2.0.2] - 2020-01-27

### Changed

- Improve `nova-translatable` support
- Fixed `Image` field `DELETE` route not working when routes are cached
- Updated packages

## [2.0.1] - 2020-01-17

### Changed

- Support `nova-translatable` rule validation

## [2.0.0] - 2020-01-17

### Changed

- Replaced `customFormatter` logic with `casts` that works identical to Laravel model's cast
- `nova_get_setting_value($key)` is now `nova_get_setting($key)`
- `nova_get_settings($keys = null)` now accepts an array of keys that define which settings to return
- `setSettingsFields($fields, $customFormatter)` is now `addSettingsFields($fields, $casts = [])`
- Added `addCasts($casts = [])`

## [1.4.0] - 2019-12-10

### Added

- Added validation support (thanks to [@tom075](https://github.com/tom075))

## [1.3.0] - 2019-12-03

### Added

- Added support for Laravel Translatable (thanks to [@royduin](https://github.com/royduin))

## [1.2.1] - 2019-11-14

### Changed

- Fixed compatibility with Nova 2.7.0

## [1.2.0] - 2019-11-11

### Added

- Added `Image` support

## [1.1.0] - 2019-09-15

### Added

- Added `Panel` support

## [1.0.0] - 2019-08-13

Initial release.

### Added

- Manage settings fields in code
- UI for editing settings
- Helpers for accessing settings
