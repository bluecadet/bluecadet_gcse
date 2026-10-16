<?php

namespace Drupal\Tests\bluecadet_gcse\Unit;

use Drupal\bluecadet_gcse\Controller\GCSESearch;
use Drupal\Core\State\StateInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the GCSESearch controller.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Controller\GCSESearch
 * @group bluecadet_gcse
 */
#[CoversClass(GCSESearch::class)]
#[Group('bluecadet_gcse')]
class GCSESearchTest extends UnitTestCase {

  /**
   * Builds a controller with a state service returning the given settings.
   *
   * @param array|null $settings
   *   The stored settings, or NULL to fall back to the caller's default.
   */
  protected function buildController(?array $settings): GCSESearch {
    $state = $this->createMock(StateInterface::class);
    $state->method('get')
      ->with('bluecadet_gcse.settings', $this->anything())
      ->willReturnCallback(fn ($key, $default = NULL) => $settings ?? $default);
    return new GCSESearch($state);
  }

  /**
   * The title falls back to a generic label when there are no keys.
   */
  public function testTitleWithoutKeys(): void {
    $controller = $this->buildController(NULL);
    $this->assertSame('Search Results', $controller->searchDisplayTitle(new Request()));
    $this->assertSame('Search Results', $controller->searchDisplayTitle(new Request(['keys' => ''])));
  }

  /**
   * The title includes the search keys.
   */
  public function testTitleWithKeys(): void {
    $controller = $this->buildController(NULL);
    $this->assertSame('Search Results for test', $controller->searchDisplayTitle(new Request(['keys' => 'test'])));
  }

  /**
   * Encoded keys are decoded before being used in the title.
   */
  public function testTitleDecodesKeys(): void {
    $controller = $this->buildController(NULL);
    $this->assertSame(
      'Search Results for hello world',
      $controller->searchDisplayTitle(new Request(['keys' => 'hello%20world'])),
    );
  }

  /**
   * The results page attaches the library and the stored GCSE ID.
   */
  public function testSearchDisplayUsesStoredSettings(): void {
    $controller = $this->buildController(['gcse_id' => 'abc123', 'gcse_path' => 'find']);
    $build = $controller->searchDisplay(new Request());

    $this->assertSame('abc123', $build['#attached']['drupalSettings']['gsearch']['gcse_id']);
    $this->assertContains('bluecadet_gcse/gcse', $build['#attached']['library']);
    $this->assertSame('gcse_page', $build['search']['#theme']);
  }

  /**
   * With nothing saved, the results page uses an empty GCSE ID.
   */
  public function testSearchDisplayDefaults(): void {
    $controller = $this->buildController(NULL);
    $build = $controller->searchDisplay(new Request());

    $this->assertSame('', $build['#attached']['drupalSettings']['gsearch']['gcse_id']);
  }

}
