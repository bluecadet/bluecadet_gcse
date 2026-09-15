<?php

namespace Drupal\bluecadet_gcse\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * Provides the dynamic GCSE search route.
 */
class GSearchRoutes implements ContainerInjectionInterface {

  /**
   * Drupal State.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a GSearchRoutes object.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = [];

    $settings = $this->state->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

    $routes['gcse.search'] = new Route(
      // Path to attach this route to:
      $settings['gcse_path'],
      // Route defaults:
      [
        '_controller' => 'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplay',
        // '_title' => 'Search'
        '_title_callback' => 'Drupal\bluecadet_gcse\Controller\GCSESearch::searchDisplayTitle',
      ],
      // Route requirements:
      [
        '_permission'  => 'access content',
      ]
    );

    return $routes;
  }

}
