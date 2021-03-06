<?php

namespace Drupal\basic_feeds\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\basic_feeds\BasicFeedsHelper;
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
   * @param $siteapikey
   * @param $entity_type to load entity types dynamically
   * @param $entity_id.
   */
  public function getJsonEntityPage($siteapikey, $entity_type, $entity_id) {
    $response = new Response();
    $entity_manager = \Drupal::entityTypeManager();
    //  First, serializer will normalize the object to an array.
    // Then it will encode array into the requested format.
    $serializer = \Drupal::service('serializer');
    // Load entity.
    $entity = $entity_manager->getStorage($entity_type)->load($entity_id);
    // convert entity object to json array.
    $data = $serializer->serialize($entity, 'json', [
      'plugin_id' => 'entity'
    ]);
    $response->setContent($data);
    return $response;
  }

  /**
   * Access callback for getJsonEntityPage.
   * @param \Drupal\Core\Session\AccountInterface $account
   *
   * @return \Drupal\Core\Access\AccessResult|
   * \Drupal\Core\Access\AccessResultAllowed|
   * \Drupal\Core\Access\AccessResultForbidden
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

    if (($siteapikey_requested !== $siteapikey) ||
      (!empty($entity_types) && !in_array($entity_type_requested, $entity_types))
      || (!empty($entity_bundle_types) &&
        !BasicFeedsHelper::entityAllowed($entity_type_requested, $entity_bundle_types,
          $entity_id))
      ) {
      // Return 403 Access Denied page.
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }
}
