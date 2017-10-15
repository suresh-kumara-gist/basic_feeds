<?php
namespace Drupal\basic_feeds\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;

// This is the form we are extending
use Drupal\system\Form\SiteInformationForm;
use Drupal\basic_feeds\BasicFeedsHelper;
use Symfony\Component\Yaml\Parser;
//use Drupal\Core\Ajax\AjaxResponse;
//use Drupal\Core\Ajax\InsertCommand;
/**
 * Configure site information settings for this site.
 */
class BasicFeedsSiteSiteInformationForm extends SiteInformationForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration
    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending
    $form = parent::buildForm($form, $form_state);
    $form['#attached']['library'][] = 'basic_feeds/basic_feeds_js';
    $form['basic_feeds_information'] = [
      '#type' => 'details',
      '#title' => t('Basic feeds information'),
      '#open' => TRUE,
    ];
    $form['basic_feeds_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?
        $site_config->get('siteapikey') : t('No API Key added yet'),
      '#description' => $this->t('Site API Key is a random string without spaces.'),
      '#required' => TRUE,
    ];
    $form['basic_feeds_information']['generate_api_key'] = [
      '#type' => 'inline_template',
      '#template' => '<button type="button" class="search-box__button" 
       onclick="insertApikey()">' . t('Generate site api key') . '</button>',
    ];
    $entity_types = $site_config->get('entity_types');
    $form['basic_feeds_information']['entity_types'] = [
      '#type' => 'select',
      '#title' => t('Select Entity types'),
      '#default_value' => $entity_types,
      '#description' => $this->t('Select Entity types'),
      '#options' => BasicFeedsHelper::getAllEntityTypes(),
      '#multiple' => TRUE,
      '#ajax' => [
        'callback' => '::getentityBundletypesField',
        'wrapper' => 'entity-bundle-types-dropdown-replace',
      ],
    ];
    $entity_bundle_types = $site_config->get('entity_bundle_types');
    $form['basic_feeds_information']['entity_bundle_types'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Entity bundle types'),
      '#description' => $this->t('Entity bundle types'),
      '#prefix' => '<div id="entity-bundle-types-dropdown-replace">',
      '#suffix' => '</div>',
      '#validated' => TRUE,
    ];
    if (!empty($entity_bundle_types)) {
      $form['basic_feeds_information']['entity_bundle_types']['#options'] =
        BasicFeedsHelper::getAllEntiyBundleTypes($entity_types);
      $form['basic_feeds_information']['entity_bundle_types']['#default_value']
        = $entity_bundle_types;
    }
    // rename configuration form button.
    $form['actions']['submit']['#value'] = $this->t('Update configuration');
    return $form;
  }

  /**
   * @param array $form
   * @param array $form_state
   * @return array
   * ajax callback to load bundle type options to entity_bundle_types field based on selection
   * in the entity type field.
   */
  public function getentityBundletypesField(array &$form, FormStateInterface $form_state) {
    $entity_types = $form_state->getValue('entity_types');
//    $form['basic_feeds_information']['entity_bundle_types'] = [
//      '#type' => 'select',
//      '#multiple' => TRUE,
//      '#title' => t('Entity bundle types'),
//      '#description' => $this->t('Entity bundle types'),
//      '#options' => BasicFeedsHelper::getAllEntiyBundleTypes($entity_types),
//      '#prefix' => '<div class="dropdown-entity-bundle-types-replace">',
//      '#suffix' => '</div>',
//    ];
//    $result = new AjaxResponse();
//    $renderer = \Drupal::service('renderer');
//    $html = $renderer->render($form['basic_feeds_information']['entity_bundle_types']);
//
//    $result->addCommand(new InsertCommand(
//        "div.dropdown-entity-bundle-types-replace",
//        $html)
//    );
    $form['basic_feeds_information']['entity_bundle_types']['#options'] = [];
    $form['basic_feeds_information']['entity_bundle_types']['#options'] =
      BasicFeedsHelper::getAllEntiyBundleTypes($entity_types);

    return $form['basic_feeds_information']['entity_bundle_types'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $siteapikey = $form_state->getValue('siteapikey');
    if (Parser::preg_match('{\s}', $siteapikey)) {
      $siteapikey = "";
    }
    $entity_types = $form_state->getValue('entity_types');
    $entity_bundle_types = $form_state->getValue('entity_bundle_types');
    $this->config('system.site')
      ->set('siteapikey', $siteapikey)
      ->set('entity_types', $entity_types)
      ->set('entity_bundle_types', $entity_bundle_types)
      // Make sure to save the configuration
      ->save();

    // Pass the remaining values off to the original form that we have extended,
    // so that they are also saved
    parent::submitForm($form, $form_state);
    $message = t('Site API Key has been saved with @siteapikey.', [
      '@siteapikey' => $siteapikey,
    ]);
    drupal_set_message($message);
  }
}