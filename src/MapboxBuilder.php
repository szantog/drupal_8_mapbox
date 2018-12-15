<?php

namespace Drupal\mapbox;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\CachedStorage;
use Drupal\mapbox\Entity\Mapbox;

/**
 * Class MapboxBuilder.
 */
class MapboxBuilder implements MapboxBuilderInterface {

  /**
   * Drupal\Core\Config\ConfigManagerInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;
  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;
  /**
   * Drupal\Core\Config\CachedStorage definition.
   *
   * @var \Drupal\Core\Config\CachedStorage
   */
  protected $configStorage;
  /** @var array */
  protected $maps;

  /**
   * Constructs a new MapboxBuilder object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, CachedStorage $config_storage) {
    $this->configFactory = $config_factory;
    $this->configStorage = $config_storage;
    $this->maps = [];
  }

  public function addMap(Mapbox $mapbox) {
    $this->maps[] = $mapbox;
  }

  public function getMaps() {
    return $this->maps;
  }

  private function preprocessMap(Mapbox $config) {
    $html_id = $config->getHtmlId();
    $build = $config;

    if (!empty($build['center'])) {
      $x = !empty($build['center']['x']) ? $build['center']['x'] : 0;
      $y = !empty($build['center']['y']) ? $build['center']['y'] : 0;
      $build['center'] = "[$x, $y]";
    }
  }

  public function preprocessMaps () {
    $mapboxes = [];
    foreach ($this->mapConfigs as $config) {
      $mapboxes[] = $this->preprocessMap($config);
    }

    return $mapboxes;
  }
}
