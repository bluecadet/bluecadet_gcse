<?php

namespace Drupal\Tests\bluecadet_gcse\Kernel;

use Drupal\bluecadet_gcse\Controller\GCSESearch;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the search controller as the container builds it.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Controller\GCSESearch
 * @group bluecadet_gcse
 */
#[CoversClass(GCSESearch::class)]
#[Group('bluecadet_gcse')]
#[RunTestsInSeparateProcesses]
class GCSESearchControllerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'bluecadet_gcse'];

  /**
   * The controller resolves from the container and reads the saved settings.
   */
  public function testControllerUsesSavedSettings(): void {
    $this->container->get('state')->set('bluecadet_gcse.settings', ['gcse_id' => 'xyz789', 'gcse_path' => 'gsearch']);

    $controller = $this->container->get('class_resolver')->getInstanceFromDefinition(GCSESearch::class);
    $this->assertInstanceOf(GCSESearch::class, $controller);

    $build = $controller->searchDisplay(new Request());
    $this->assertSame('xyz789', $build['#attached']['drupalSettings']['gsearch']['gcse_id']);
  }

}
