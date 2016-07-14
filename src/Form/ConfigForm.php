<?php

namespace Drupal\idevaffiliate\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 *
 * @package Drupal\idevaffiliate\Form
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'idevaffiliate.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'idevaffiliate_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('idevaffiliate.config');
    $form['endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('iDevAffiliate Base URL'),
      '#default_value' => $config->get('endpoint'),
      '#placeholder' => 'https://idevaffiliate.yournetwork',
      '#required' => true,
    ];
    $form['secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('sale.php secret key'),
      '#default_value' => $config->get('secret'),
      '#description' => $this->t('Secret key to protect <code>sale.php</code>. Obtain at <code>General Settings &gt; Advanced Fraud Protection</code>.'),
      '#required' => true,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!UrlHelper::isValid($form_state->getValue('endpoint'), true)) {
      $form_state->setErrorByName('endpoint', $this->t('Endpoint must be a valid absolute URL.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('idevaffiliate.config')
      ->set('endpoint', trim($form_state->getValue('endpoint'), '/'))
      ->set('secret', $form_state->getValue('secret'))
      ->save();
  }

}
