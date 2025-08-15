<?php

namespace Drupal\bluecadet_gcse\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

/**
 * Configure Paragraph examples to upload images per para bundle.
 */
class GSearch extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_custom_search';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Disable caching on this form.
    $form_state->setCached(FALSE);

    $form['#action'] = Url::fromRoute('gcse.search')->toString();
    $form['#method'] = 'get';

    $form['keys'] = [
      '#type' => 'search',
      '#title' => $this->t('Search'),
      '#title_display' => 'invisible',
      '#size' => 15,
      '#default_value' => '',
      '#attributes' => ['title' => $this->t('Enter the terms you wish to search for.')],
    ];

    $form['#after_build'][] = [$this, 'afterBuild'];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#id' => 'gcse-search-submit',
      // Prevent op from showing up in the query string.
      '#name' => '',
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
    if (isset($_GET['destination'])) {
      unset($_GET['destination']);
    }
  }

  /**
   * After build handler to remove form tokens.
   *
   * @param array $element
   *   The form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The modified form element.
   */
  public function afterBuild(array $element, FormStateInterface $form_state): array {
    unset($element['form_token']);
    unset($element['form_build_id']);
    unset($element['form_id']);
    return $element;
  }

}
