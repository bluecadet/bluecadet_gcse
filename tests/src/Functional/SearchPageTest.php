<?php

namespace Drupal\Tests\bluecadet_gcse\Functional;

use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the search page, the settings page and the search form block.
 *
 * @group bluecadet_gcse
 */
#[Group('bluecadet_gcse')]
#[RunTestsInSeparateProcesses]
class SearchPageTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['bluecadet_gcse', 'block'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // A bare test install gives anonymous users no content access.
    user_role_grant_permissions('anonymous', ['access content']);
  }

  /**
   * The search page loads with a generic title and the GCSE markup.
   */
  public function testSearchPage(): void {
    $this->drupalGet('/gsearch');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Search Results');
    $this->assertSession()->elementExists('css', 'gcse\:search');
  }

  /**
   * Search keys show up in the page title.
   */
  public function testSearchPageTitleWithKeys(): void {
    $this->drupalGet('/gsearch', ['query' => ['keys' => 'test']]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementTextContains('css', 'title', 'Search Results for test');
  }

  /**
   * The stored GCSE ID is exposed to the front-end script.
   */
  public function testGcseIdInDrupalSettings(): void {
    $this->container->get('state')->set('bluecadet_gcse.settings', ['gcse_id' => 'abc123', 'gcse_path' => 'gsearch']);
    $this->drupalGet('/gsearch');
    $settings = $this->getDrupalSettings();
    $this->assertSame('abc123', $settings['gsearch']['gcse_id']);
  }

  /**
   * Admins can change the search path and the page moves.
   */
  public function testSettingsFormMovesSearchPage(): void {
    $this->drupalLogin($this->drupalCreateUser(['administer bc google site search settings', 'access content']));

    $this->drupalGet('/admin/config/search/g-search-settings');
    $this->assertSession()->statusCodeEquals(200);
    $this->submitForm([
      'settings[gcse_id]' => 'abc123',
      'settings[gcse_path]' => '/find-it',
    ], 'Save');
    $this->assertSession()->pageTextContains('You have saved your settings.');

    $this->drupalGet('/find-it');
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet('/gsearch');
    $this->assertSession()->statusCodeEquals(404);
  }

  /**
   * The settings page is not available to anonymous or unprivileged users.
   */
  public function testSettingsFormAccess(): void {
    $this->drupalGet('/admin/config/search/g-search-settings');
    $this->assertSession()->statusCodeEquals(403);

    $this->drupalLogin($this->drupalCreateUser(['access content']));
    $this->drupalGet('/admin/config/search/g-search-settings');
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * The search form block renders a GET form pointing at the search page.
   */
  public function testSearchFormBlock(): void {
    $this->drupalPlaceBlock('gcse_search_form', ['region' => 'content']);
    $this->drupalGet('<front>');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementExists('css', 'form#google-custom-search[method="get"][action="/gsearch"]');
    $this->assertSession()->elementNotExists('css', 'form#google-custom-search input[name="form_token"]');
  }

}
