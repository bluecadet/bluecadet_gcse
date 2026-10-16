<?php

namespace Drupal\Tests\bluecadet_gcse\Unit;

use Drupal\bluecadet_gcse\Form\GSearch;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the unit-testable parts of the search form.
 */
#[CoversClass(GSearch::class)]
#[Group('bluecadet_gcse')]
class GSearchTest extends UnitTestCase {

  /**
   * The form id is stable.
   */
  public function testFormId(): void {
    $this->assertSame('google_custom_search', (new GSearch())->getFormId());
  }

  /**
   * The after-build handler strips the tokens so the GET URL stays clean.
   */
  public function testAfterBuildRemovesTokens(): void {
    $element = [
      'keys' => ['#type' => 'search'],
      'form_token' => ['#type' => 'token'],
      'form_build_id' => ['#type' => 'hidden'],
      'form_id' => ['#type' => 'hidden'],
    ];

    $result = (new GSearch())->afterBuild($element, $this->createMock(FormStateInterface::class));

    $this->assertSame(['keys' => ['#type' => 'search']], $result);
  }

}
