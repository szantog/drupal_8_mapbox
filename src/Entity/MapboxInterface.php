<?php

namespace Drupal\mapbox\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Mapbox entities.
 */
interface MapboxInterface extends ConfigEntityInterface {

  /**
   * Get a string for use as a valid HTML ID of a map and guarantees uniqueness.
   */
  public function getHtmlId();
}
