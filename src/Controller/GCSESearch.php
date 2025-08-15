<?php

namespace Drupal\bluecadet_gcse\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides search functionality for GCSE content.
 */
class GCSESearch extends ControllerBase {

  /**
   * Displays the search results.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return array
   *   A render array for the search results.
   */
  public function searchDisplay(Request $request): array {
    $build = [];

    $settings = \Drupal::state()->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

    $build['#attached']['drupalSettings']['gsearch']['gcse_id'] = $settings['gcse_id'];
    $build['#attached']['library'][] = 'bluecadet_gcse/gcse';

    $build['search'] = [
      '#theme' => 'gcse_page',
    ];

    return $build;
  }

  /**
   * Displays the search results title.
   *
   * @return string
   *   The search results title.
   */
  public function searchDisplayTitle(): string {

    // Alter page title to display search keys
    $keys = \Drupal::request()->query->get('keys');
    $title = $keys ? 'Search Results for ' . urldecode($keys) : 'Search Results';

    return $title;

  }

}
