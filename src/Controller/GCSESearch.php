<?php

namespace Drupal\bluecadet_gcse\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides search functionality for GCSE content.
 */
class GCSESearch extends ControllerBase {

  /**
   * Drupal State.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
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

    $settings = $this->state->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

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
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return string
   *   The search results title.
   */
  public function searchDisplayTitle(Request $request): string {

    // Alter page title to display search keys.
    $keys = $request->query->get('keys');
    $title = $keys ? 'Search Results for ' . urldecode($keys) : 'Search Results';

    return $title;

  }

}
