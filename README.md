# Bluecadet's Google Custom Search Engine

Adds a Google Custom Search Engine (GCSE) integration to Drupal — a settings form to configure the GCSE ID and search page path, a search block/form, and a search results page that renders Google's results widget.

## Requirements

- Drupal 10.5+ or Drupal 11.2+
- PHP 8.2 or higher

## Versions

### 2.1 Branch

- **2.1.x**: Drupal 10.5+/11.2+ support (PHP 8.2+)

### 2.0 Branch

- **2.0.x**: Drupal 9/10 support -- outdated, do not use.

## Includes

- Admin settings form (`/admin/config/search/g-search-settings`) to configure the GCSE ID and the search results page path
- A dynamically-registered route for the search results page, built from the configured path
- A search results page that attaches the GCSE JS library and renders the `gcse_page` theme
- A search form (`GSearch`) and a corresponding block plugin (`Google Custom Search Form`) that can be placed anywhere to submit queries to the results page
- Update status integration via `bc_drupal_package_manager`, so this module's version is checked against Bluecadet's package feed on the module updates report

## Not using Composer

If you are not using composer, you can delete all unneeded files.

- composer.json

## Using Composer

If you are using composer to manage Drupal modules, make sure you add custom
location for this module to be downloaded to. You must add the installer types
line as well as the location for the module.

```json
  ...
  "installer-types": ["custom-drupal-module"],
  "installer-paths": {
    "web/core": ["type:drupal-core"],
    "web/modules/contrib/{$name}": ["type:drupal-module"],
    "web/modules/custom/{$name}": ["type:custom-drupal-module"],
    "web/profiles/contrib/{$name}": ["type:drupal-profile"],
    "web/themes/contrib/{$name}": ["type:drupal-theme"],
    "drush/contrib/{$name}": ["type:drupal-drush"]
  },
  ...
```

## Testing

This module includes automated tests that run via GitHub Actions against Drupal 10.5.x-11.3.x (see `.github/drupal-ci.yml` for the exact PHP/MariaDB matrix).

### Test Plan

#### Automated Tests (GitHub Actions)

The CI pipeline runs the following for each Drupal version:

1. **PHPCS** - Drupal coding standards validation (`Drupal` and `DrupalPractice` standards)
2. **PHPStan** - static analysis for deprecated API usage
3. **PHPUnit** - automated tests

#### Current coverage

No automated test coverage yet. Verified manually against a fresh Drupal 11.3 install for this release: module enable, the search results route (with and without a `keys` query param), and the admin settings form all load correctly after converting every service lookup to real dependency injection.

## Changelog

### 2.1.x

- Narrowed Drupal core support to `^10.5 || ^11.2` (dropping EOL Drupal 9); `composer.json` previously had no `drupal/core` constraint at all
- Adopted the reusable GitHub Actions workflow architecture; moved CI to a shared, config-driven orchestrator in `bluecadet/web-gh-actions`
- Converted every `\Drupal::` static service call to real constructor-based dependency injection across the controller, both forms, the block plugin, and the dynamic route-callback class
- Fixed direct `$_GET` superglobal access in the search form's submit handler
- Fixed a stale `bluecadet_gse` (missing "c") repo URL in `package.json`
- Fixed several postcss plugins that were silently relying on an old transitive dependency rather than being declared directly; updated `@bluecadet/drops` to `^1.2.1` and `@bluecadet/bldr` from a pinned 1.x version to `2.0.0-alpha.12`
- Added `extra.bluecadet-package-manager` composer.json metadata

### 2.1.0

- Updates for Drupal 11 compatibility

### 2.0.0

- Support Drupal 10
- Support PHP 8
- Adds in update status to check module version updates

### 8.x-1.0.5

- Updated dependencies so we can use Composer v2

<br>
<br>
<br>

## Proudly developed @ Bluecadet

<p style="background-color: white; padding: 20px">
  <a href="https://www.bluecadet.com/"><img style="max-width: 50%; min-width: 300px; background: white; padding: 20px;" src="https://www.bluecadet.com/wp-content/themes/bluecadet-2018/images/logo/logo-bluecadet-black.svg" alt="Bluecadet"></a>
</p>
