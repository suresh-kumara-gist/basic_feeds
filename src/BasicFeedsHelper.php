<?php
/**
 * @file
 */
namespace Drupal\basic_feeds;


class BasicFeedsHelper {

  /**
   * @return array
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
   */
  public static function getAllEntiyBundleTypes(array $entities) {
    $bundleinfo = [];
    if (!empty($entities)) {
      foreach ($entities as $key  => $entity_id) {
        $entitybundleinfo = \Drupal::service("entity_type.bundle.info")
          ->getBundleInfo($key);
        if (!empty($entitybundleinfo)) {
          $bundles = [];
          foreach ($entitybundleinfo as $key => $value) {
            $bundles += [
              $key => $value['label']
            ];
          }
          $bundleinfo += $bundles;
        }
      }
    }
    return $bundleinfo;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  function generateRandomString($length = 30) {
    $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321'.time();
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = $length; $i > 0; $i--)
    {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}