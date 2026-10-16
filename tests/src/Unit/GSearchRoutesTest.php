<?php

namespace Drupal\Tests\bluecadet_gcse\Unit;

use Drupal\bluecadet_gcse\Routing\GSearchRoutes;
use Drupal\Core\State\StateInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the dynamic GCSE search route.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Routing\GSearchRoutes
 * @group bluecadet_gcse
 */
#[CoversClass(GSearchRoutes::class)]
#[Group('bluecadet_gcse')]
class GSearchRoutesTest extends UnitTestCase {

  /**
   * Builds the routes class with the given stored settings.
   */
  protected function buildRoutes(?array $settings): GSearchRoutes {
    $state = $this->createMock(StateInterface::class);
    $state->method('get')
      ->willReturnCallback(fn ($key, $default = NULL) => $settings ?? $default);
    return new GSearchRoutes($state);
  }

  /**
   * Without saved settings the route lives at the default path.
   */
  public function testDefaultPath(): void {
    $routes = $this->buildRoutes(NULL)->routes();

    $this->assertSame(['gcse.search'], array_keys($routes));
    $this->assertSame('/gsearch', $routes['gcse.search']->getPath());
  }

  /**
   * The configured path is used for the route.
   */
  public function testCustomPath(): void {
    $routes = $this->buildRoutes(['gcse_id' => 'x', 'gcse_path' => 'find-it'])->routes();

    $this->assertSame('/find-it', $routes['gcse.search']->getPath());
  }

  /**
   * The route points at the controller and requires the content permission.
   */
  public function testControllerAndAccess(): void {
    $route = $this->buildRoutes(NULL)->routes()['gcse.search'];

    $this->assertSame(
      'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplay',
      $route->getDefault('_controller'),
    );
    $this->assertSame(
      'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplayTitle',
      $route->getDefault('_title_callback'),
    );
    $this->assertSame('access content', $route->getRequirement('_permission'));
  }

}
