<?php

namespace Drupal\admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Configure DashboardSettingsForm settings for this site.
 */
class DashboardSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'admin.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('admin.settings');

    // Contact service to get a quote (Obenir un devis).
    $cqs_link = $config->get('contact_quotation_service_link');
    $contact_quotation_service_link = isset($cqs_link) ? Node::load($cqs_link) : NULL;
    // Contact us page.
    $cu_link = $config->get('contact_us_link');
    $contact_us_link = isset($cu_link) ? Node::load($cu_link) : NULL;
    // My home banner.
    $rf1 = $config->get('featured_resource_1');
    $rf2 = $config->get('featured_resource_2');
    $rf3 = $config->get('featured_resource_3');
    $featured_resource_1 = $rf1 ? Node::load($rf1) : NULL;
    $featured_resource_2 = $rf2 ? Node::load($rf2) : NULL;
    $featured_resource_3 = $rf3 ? Node::load($rf3) : NULL;

    // Home banner.
    $form['my_home_banner'] = [
      '#type' => 'details',
      '#title' => $this->t('Homepage banner for connected users'),
      '#description' => t('Choose 1 to 3 resources. Only thematic folders are available.'),
      '#open' => TRUE,
    ];
    $form['my_home_banner']['banner_title'] = [
      '#type' => 'textfield',
      '#title' => t('Banner title'),
      '#default_value' => $config->get('banner_title') ?: '',
      '#placeholder' => '',
    ];
    $form['my_home_banner']['featured_resource_1'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#title' => t('Resource') . ' #1',
      '#description' => t('Choose a resource. Start typing the title of the resource in this field.'),
      '#default_value' => $featured_resource_1,
      '#empty_value' => '',
      '#placeholder' => t(''),
      '#selection_settings' => [
        'target_bundles' => ['thematic_folder'],
      ],
    ];
    $form['my_home_banner']['featured_resource_2'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#title' => t('Resource') . ' #2',
      '#description' => t('Choose a resource. Start typing the title of the resource in this field.'),
      '#default_value' => $featured_resource_2,
      '#empty_value' => '',
      '#placeholder' => t(''),
      '#selection_settings' => [
        'target_bundles' => ['thematic_folder'],
      ],
    ];
    $form['my_home_banner']['featured_resource_3'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#title' => t('Resource') . ' #3',
      '#description' => t('Choose a resource. Start typing the title of the resource in this field.'),
      '#default_value' => $featured_resource_3,
      '#empty_value' => '',
      '#placeholder' => t(''),
      '#selection_settings' => [
        'target_bundles' => ['thematic_folder'],
      ],
    ];

    // Contact.
    $form['customer_service'] = [
      '#type' => 'details',
      '#title' => $this->t('Customer Service'),
      '#description' => t(''),
      '#open' => FALSE,
    ];
    $form['customer_service']['contact_quotation_service_link'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#title' => t('Customer quotation service page'),
      '#description' => t('Choose Quotation Customer Service Page.'),
      '#default_value' => $contact_quotation_service_link,
      '#empty_value' => '',
      '#placeholder' => t(''),
      '#selection_settings' => [
        'target_bundles' => ['page', 'page_2_columns'],
      ],
    ];
    $form['customer_service']['contact_us_link'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#title' => t('Contact Us page'),
      '#description' => t('Choose Contact Us Page.'),
      '#default_value' => $contact_us_link,
      '#empty_value' => '',
      '#placeholder' => t(''),
      '#selection_settings' => [
        'target_bundles' => ['page', 'page_2_columns'],
      ],
    ];
    $form['customer_service']['faq_link'] = [
      '#type' => 'textfield',
      '#title' => t('Extern FAQ link'),
      '#description' => t('Choose extern FAQ link.'),
      '#default_value' => $config->get('faq_link') ?: '',
      '#empty_value' => '',
      '#placeholder' => t('https://support.domain_name.io./'),
    ];
    $form['customer_service']['contact_service_phone'] = [
      '#type' => 'textfield',
      '#title' => t('Phone number'),
      '#default_value' => $config->get('contact_service_phone') ?: '',
      '#placeholder' => t('(438) 000-0000'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('admin.settings')
      ->set('contact_quotation_service_link', $form_state->getValue('contact_quotation_service_link'))
      ->set('contact_us_link', $form_state->getValue('contact_us_link'))
      ->set('faq_link', $form_state->getValue('faq_link'))
      ->set('contact_service_phone', $form_state->getValue('contact_service_phone'))
      ->set('featured_resource_1', $form_state->getValue('featured_resource_1'))
      ->set('featured_resource_2', $form_state->getValue('featured_resource_2'))
      ->set('featured_resource_3', $form_state->getValue('featured_resource_3'))
      ->set('banner_title', $form_state->getValue('banner_title'))
      ->save();
  }

}
