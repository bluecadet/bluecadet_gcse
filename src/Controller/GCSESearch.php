<?php

namespace Drupal\bluecadet_gcse\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class GCSESearch extends ControllerBase {

  public function searchDisplay($search_term = '', Request $request) {
    $build = [];

    $settings = \Drupal::state()->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

    $build['#attached']['drupalSettings']['gsearch']['gcse_id'] = $settings['gcse_id'];
    $build['#attached']['library'][] = 'bluecadet_gcse/gcse';

    $build['search'] = [
      '#theme' => 'gcse_page',
    ];

    return $build;
  }

  public function searchDisplayTitle() {

    // Alter page title to display search keys
    $keys = \Drupal::request()->query->get('keys');
    $title = $keys ? 'Search Results for ' . urldecode($keys) : 'Search Results';

    return $title;

  }
}