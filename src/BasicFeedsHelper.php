<?php
/**
 * @file
 */
namespace Drupal\basic_feeds;


class BasicFeedsHelper {

  /**
   * @return array
   * getAllEntityTypes method returns array of entity type ids available in the system.
   */
  public static function getAllEntityTypes() {
    $entities_info = [];
    foreach(\Drupal::EntityManager()->getDefinitions() as $id => $definition) {
      if (is_a($definition ,'Drupal\Core\Entity\ContentEntityType')) {
        $entities_info[$id] = $id;
      }
    }
    return $entities_info;
  }

  /**
   * @param array $entities
   *
   * @return array
   * getAllEntiyBundleTypes methods returns bundle types of entities.
   */
  public static function getAllEntiyBundleTypes(array $entities) {
    $bundleinfo = [];
    if (!empty($entities)) {
      foreach ($entities as $key  => $entity_id) {
        $bundleinfo += self::getBundleListFromEntityType($key);
      }
    }
    return $bundleinfo;
  }

  /**
   * @param int $length
   *
   * @return string
   * 
   */
   /**
   public static function generateRandomString($length = 30) {
    $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321'.time();
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = $length; $i > 0; $i--)
    {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
   }
  */

  /**
   * @param $entity_types
   * @param $entity_bundle_types
   * @param $entity_id
   * @return bool
   * entityAllowed function loads entity from entity id requested in url. If entity not found return
   * false else need to check entity bundle types selected or not in basic feeds entity bundle types configuration
   * if not then return true else we have to get bundle name from entity and compare in basic feeds entity
   * bundle types configuration, if not present then return FALSE.
   */
  public static function entityAllowed($requested_entity_type, $entity_bundle_types,
    $entity_id) {
    $entity = \Drupal::entityTypeManager()->getStorage($requested_entity_type)
      ->load($entity_id);
    if (!$entity) {
      return FALSE;
    }
    $bundle = self::getBundleListFromEntityType($requested_entity_type);
    if (!empty(array_diff_key($bundle, $entity_bundle_types)) &&
      !in_array($entity->bundle(), $entity_bundle_types)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * @param $entity_type
   *
   * @return array
   * getBundleListFromEntityType returns bundle list for entity type.
   */
  public static function getBundleListFromEntityType($entity_type) {
    $entitybundleinfo = \Drupal::service("entity_type.bundle.info")
      ->getBundleInfo($entity_type);
    if (!empty($entitybundleinfo)) {
      $bundles = [];
      foreach ($entitybundleinfo as $key => $value) {
        $bundles += [
          $key => $value['label']
        ];
      }
    }
    return $bundles;
  }
}