<?php

namespace Drupal\bluecadet_gcse\Plugin\Block;

use Drupal\bluecadet_gcse\Form\GSearch;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Sub Navigation Block' Block.
 *
 * @Block(
 *   id = "gcse_search_form",
 *   admin_label = @Translation("Google Custom Search Form"),
 * )
 */
class GSearchFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $build['form'] = \Drupal::formBuilder()->getForm(GSearch::class);

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
