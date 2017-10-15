<?php

namespace Drupal\basic_feeds\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
/**
 * Class BfJsonPageController.
 *
 * @package Drupal\basic_feeds\Controller
 */
class BfJsonPageController extends ControllerBase {
  /**
   * Getjsonentitypage.
   *
   * @return renderable array
   *   Return Hello string.
   * @param $siteapikey
   * @param $entity_type to load entity types dynamically.
   */
  public function getJsonEntityPage($siteapikey, $entity_type, $entity_id) {
    $entity_manager = \Drupal::entityTypeManager();
    //  First, it will normalize the object to an array.
    // Then it will encode that array into the requested format.
    $serializer = \Drupal::service('serializer');
    // Load entity.
    $entity = $entity_manager->getStorage($entity_type)->load($entity_id);
    // convert entity object to json array.
    $data = $serializer->serialize($entity, 'json', [
      'plugin_id' => 'entity'
    ]);
    return $data;
  }

  /**
   * Access callback for getJsonEntityPage.
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return \Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   */
  public function access(AccountInterface $account) {
    $config = \Drupal::config('system.site');
    $siteapikey = $config->get('siteapikey');
    $route_match = \Drupal::service('current_route_match');
    $siteapikey_requested = $route_match->getParameter('siteapikey');

    // Compare entity types exist in site.information.entity_types variables.
    $entity_type_requested = $route_match->getParameter('entity_type');
    $entity_types = $config->get('entity_type');

    // Compare entity bundle types exist in site.information.entity_bundle_types
    // variables.

    $entity_bundle_types = $config->get('entity_bundle_types');
    $entity_id = $route_match->getParameter('entity_id');

/*    if (($siteapikey_requested !== $siteapikey) ||
      (!empty($entity_types) && !in_array($entity_type_requested, $entity_types))
      ) {
      // Return 403 Access Denied page.
      return AccessResult::forbidden();
    }*/

    return AccessResult::allowed();
  }
}
