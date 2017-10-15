<?php

namespace Drupal\basic_feeds\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class BasicFeedsRouteSubscriber.
 *
 * @package Drupal\basic_feeds\Routing
 * Listens to the dynamic route events.
 */
class BasicFeedsRouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change form for the system.site_information_settings route
    // to Drupal\basic_feeds\Form\BasicFeedsSiteSiteInformationForm
    // First, we need to act only on the system.site_information_settings route.
    if($route = $collection->get('system.site_information_settings'))
    {
      // Next, we need to set the value for _form to the form we have created.
      $route->setDefault('_form', 'Drupal\basic_feeds\Form\BasicFeedsSiteSiteInformationForm');
    }
  }
}
