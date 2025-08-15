<?php

namespace Drupal\bluecadet_gcse\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Configure Paragraph examples to upload images per para bundle.
 */
class GSearchSettings extends FormBase {

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

    $settings = \Drupal::state()->get('bluecadet_gcse.settings', ['gcse_id' => '', 'gcse_path' => 'gsearch']);

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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $settings = $values['settings'];
    \Drupal::state()->set('bluecadet_gcse.settings', $settings);

    // Rebuild routes.
    \Drupal::service("router.builder")->rebuild();

    $this->messenger()->addMessage('You have saved your settings.');
  }

}
