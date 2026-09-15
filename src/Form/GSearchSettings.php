<?php

namespace Drupal\bluecadet_gcse\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Routing\RouteBuilderInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Paragraph examples to upload images per para bundle.
 */
class GSearchSettings extends FormBase {

  /**
   * Drupal State.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Route builder.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected $routeBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(StateInterface $state, RouteBuilderInterface $route_builder) {
    $this->state = $state;
    $this->routeBuilder = $route_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
      $container->get('router.builder'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_custom_search_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $settings = $this->state->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

    $form['settings']['#tree'] = TRUE;

    $form['settings']['gcse_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('GCSE ID'),
      '#default_value' => $settings['gcse_id'],
      '#description' => $this->t('Enter the ID from your Google Custom Search Engine instance.'),
    ];

    $form['settings']['gcse_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search page path'),
      '#default_value' => $settings['gcse_path'],
      '#description' => $this->t('Enter the path for the search page. Please include preceeding slash but not the trailing slash.'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $settings = $values['settings'];
    $this->state->set('bluecadet_gcse.settings', $settings);

    // Rebuild routes.
    $this->routeBuilder->rebuild();

    $this->messenger()->addMessage('You have saved your settings.');
  }

}
