<?php

namespace Drupal\bluecadet_gcse\Routing;

use Symfony\Component\Routing\Route;

class GSearchRoutes {
  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = array();

    $settings = \Drupal::state()->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

    $routes['gcse.search'] = new Route(
      // Path to attach this route to:
      $settings['gcse_path'],
      // Route defaults:
      array(
        '_controller' => 'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplay',
        // '_title' => 'Search'
        '_title_callback' => 'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplayTitle',
      ),
      // Route requirements:
      array(
        '_permission'  => 'access content',
      )
    );

    return $routes;
  }
}