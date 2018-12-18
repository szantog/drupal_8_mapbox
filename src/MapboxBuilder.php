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

  public function preprocessMap(&$variables, Mapbox $mapbox) {
    $variables['html_id'] = $mapbox->getHtmlId();
    $variables['mapbox_id'] = $mapbox->id();
    $variables['label'] = $mapbox->label();
    $variables['access_token'] = $mapbox->access_token;
    $variables['style'] = $mapbox->style;
    $variables['zoom'] = $mapbox->zoom;
    $variables['width'] = $variables["children"]["#width"];
    $variables['height'] = $variables["children"]["#height"];
    $center = $mapbox->get('center');
    if (!empty($center)) {
      $variables['center'] = "[{$center['x']}, {$center['y']}]";
    }
  }

  public function preprocessMaps () {
    $mapboxes = [];
    foreach ($this->maps as $config) {
      $mapboxes[] = $this->preprocessMap($config);
    }

    return $mapboxes;
  }

  public function renderMap(Mapbox $mapbox, $width='100%', $height = 'auto', $data = NULL) {
    $build['html'] = [
      '#theme' => 'mapbox',
      '#mapbox' => $mapbox,
      '#data' => $data,
      '#width' => $width,
      '#height' => $height,
      '#attached' => [
        'library' => [
          'mapbox/mapboxgl',
          //'mapbox/mapbox',
        ],
      ],
    ];

    return $build;
  }
}
