<?php

namespace Drupal\Tests\bluecadet_gcse\Kernel;

use Drupal\bluecadet_gcse\Form\GSearch;
use Drupal\Core\Form\FormState;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Tests building and submitting the search form.
 *
 * @coversDefaultClass \Drupal\bluecadet_gcse\Form\GSearch
 * @group bluecadet_gcse
 */
#[CoversClass(GSearch::class)]
#[Group('bluecadet_gcse')]
#[RunTestsInSeparateProcesses]
class GSearchFormTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'bluecadet_gcse'];

  /**
   * The form is a GET form that submits to the search page.
   */
  public function testBuildForm(): void {
    $this->container->get('router.builder')->rebuild();
    $form = $this->container->get('form_builder')->getForm(GSearch::class);

    $this->assertSame('get', $form['#method']);
    $this->assertSame('/gsearch', $form['#action']);
    $this->assertSame('search', $form['keys']['#type']);
    $this->assertSame('Search', (string) $form['actions']['submit']['#value']);
    $this->assertSame('', $form['actions']['submit']['#name']);
    $this->assertArrayNotHasKey('form_token', $form);
    $this->assertArrayNotHasKey('form_build_id', $form);
    $this->assertArrayNotHasKey('form_id', $form);
  }

  /**
   * Submitting drops any destination parameter from the current request.
   */
  public function testSubmitRemovesDestination(): void {
    $request = Request::create('/', 'GET', ['destination' => '/somewhere']);
    $request->setSession(new Session(new MockArraySessionStorage()));
    $this->container->get('request_stack')->push($request);

    $form_state = new FormState();
    $this->container->get('form_builder')->submitForm(GSearch::class, $form_state);

    $this->assertFalse($request->query->has('destination'));
  }

}
