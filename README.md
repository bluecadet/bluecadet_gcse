# Bluecadet's Google Custom Search Engine

Adds a Google Custom Search Engine (GCSE) integration to Drupal — a settings form to configure the GCSE ID and search page path, a search block/form, and a search results page that renders Google's results widget.

## Requirements

- Drupal 9, 10, or 11
- PHP 8.0, 8.1, or 8.3

## Versions

### 2.1 Branch

- **2.1.x**: Drupal 9/10/11 support (PHP 8.0/8.1/8.3)

### 2.0 Branch

- **2.0.x**: Drupal 9/10 support (PHP 7.4/8.0)

<!-- Older/unsupported branches can get a one-line note instead, e.g.
     "### 1.x Branch — completely outdated, do not use." -->

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

## Changelog

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
