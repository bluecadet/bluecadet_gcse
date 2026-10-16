<?php

namespace Drupal\Tests\bluecadet_gcse\Kernel;

use Drupal\bluecadet_gcse\Form\GSearchSettings;
use Drupal\Core\Form\FormState;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the settings form and the route it controls.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Form\GSearchSettings
 * @group bluecadet_gcse
 */
#[CoversClass(GSearchSettings::class)]
#[Group('bluecadet_gcse')]
#[RunTestsInSeparateProcesses]
class GSearchSettingsFormTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'bluecadet_gcse'];

  /**
   * The form renders the stored defaults when nothing has been saved.
   */
  public function testFormShowsDefaults(): void {
    $form = $this->container->get('form_builder')->getForm(GSearchSettings::class);

    $this->assertSame('', $form['settings']['gcse_id']['#default_value']);
    $this->assertSame('gsearch', $form['settings']['gcse_path']['#default_value']);
  }

  /**
   * Submitting stores the settings and the form reflects them afterwards.
   */
  public function testSubmitSavesSettings(): void {
    $form_state = (new FormState())->setValues([
      'settings' => ['gcse_id' => 'abc123', 'gcse_path' => '/find'],
    ]);
    $this->container->get('form_builder')->submitForm(GSearchSettings::class, $form_state);

    $this->assertSame(
      ['gcse_id' => 'abc123', 'gcse_path' => '/find'],
      $this->container->get('state')->get('bluecadet_gcse.settings'),
    );

    $messages = $this->container->get('messenger')->messagesByType('status');
    $this->assertCount(1, $messages);

    $form = $this->container->get('form_builder')->getForm(GSearchSettings::class);
    $this->assertSame('abc123', $form['settings']['gcse_id']['#default_value']);
  }

  /**
   * Saving a new path rebuilds the routes so the search page moves.
   */
  public function testSubmitRebuildsRoute(): void {
    $provider = $this->container->get('router.route_provider');
    $this->container->get('router.builder')->rebuild();
    $this->assertSame('/gsearch', $provider->getRouteByName('gcse.search')->getPath());

    $form_state = (new FormState())->setValues([
      'settings' => ['gcse_id' => 'abc123', 'gcse_path' => '/find'],
    ]);
    $this->container->get('form_builder')->submitForm(GSearchSettings::class, $form_state);

    $this->assertSame('/find', $provider->getRouteByName('gcse.search')->getPath());
  }

}
