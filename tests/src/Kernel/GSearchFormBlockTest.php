<?php

namespace Drupal\Tests\bluecadet_gcse\Kernel;

use Drupal\bluecadet_gcse\Plugin\Block\GSearchFormBlock;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the search form block and the form it renders.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Plugin\Block\GSearchFormBlock
 * @group bluecadet_gcse
 */
#[CoversClass(GSearchFormBlock::class)]
#[Group('bluecadet_gcse')]
#[RunTestsInSeparateProcesses]
class GSearchFormBlockTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'bluecadet_gcse'];

  /**
   * The block is discoverable and creates via the container.
   */
  public function testBlockPluginExists(): void {
    $block = $this->container->get('plugin.manager.block')->createInstance('gcse_search_form');
    $this->assertInstanceOf(GSearchFormBlock::class, $block);
  }

  /**
   * The block is never cached.
   */
  public function testBlockIsUncacheable(): void {
    $block = $this->container->get('plugin.manager.block')->createInstance('gcse_search_form');
    $this->assertSame(0, $block->getCacheMaxAge());
  }

  /**
   * The block builds a GET form pointing at the search page, without tokens.
   */
  public function testBlockBuildsCleanGetForm(): void {
    $this->container->get('router.builder')->rebuild();
    $block = $this->container->get('plugin.manager.block')->createInstance('gcse_search_form');
    $form = $block->build()['form'];

    $this->assertSame('get', $form['#method']);
    $this->assertSame('/gsearch', $form['#action']);
    $this->assertSame('search', $form['keys']['#type']);
    $this->assertArrayHasKey('submit', $form['actions']);
    $this->assertArrayNotHasKey('form_token', $form);
    $this->assertArrayNotHasKey('form_build_id', $form);
    $this->assertArrayNotHasKey('form_id', $form);
  }

}
