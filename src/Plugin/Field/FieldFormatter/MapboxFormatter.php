<?php

namespace Drupal\mapbox\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'mapbox_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "mapbox_formatter",
 *   label = @Translation("Mapbox formatter"),
 *   field_types = {
 *     "geofield"
 *   }
 * )
 */
class MapboxFormatter extends FormatterBase {
  /**
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $config_factory;

  /** @var \Drupal\mapbox\MapboxBuilder $mapboxBuilder */
  protected $mapboxBuilder;

  /** @var mixed|null  */
  protected $mapboxId;

  /** @var \Drupal\mapbox\Entity\Mapbox  */
  protected $mapbox;

  public function __construct($plugin_id, $plugin_definition, \Drupal\Core\Field\FieldDefinitionInterface $field_definition, array $settings, string $label, string $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->config_factory = \Drupal::service('config.factory');
    $this->mapboxBuilder = \Drupal::service('mapbox.builder');

    if (!empty($this->getSetting('mapbox_id'))) {
      $this->mapboxId = $this->getSetting('mapbox_id');
      $map_config = $this->config_factory->loadMultiple(["mapbox.mapbox.$this->mapbox_id"]);
      $this->mapbox = \Drupal::service('entity_type.manager')
        ->getStorage('mapbox')
        ->load($this->mapbox_id);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'mapbox_id' => NULL,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      'mapbox_id' => [
        '#type' => 'select',
        '#title' => $this->t('Select Mapbox configuration'),
        '#options' => $this->getMapConfigs(),
        '#default_value' => $this->mapbox_id,
        '#required' => TRUE,
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $configs = $this->getMapConfigs();
    if (isset($configs[$this->mapbox_id])) {
      $summary = [$this->t('Mapbox configuration: @mapbox_label', ['@mapbox_label' => $configs[$this->mapbox_id]])] ;
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    if (!$this->map_config) {
      return [
        '#markup' => $this->t('No Mapbox configuration set.')
      ];
    }

    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return array
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    $build['html'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => '<div id="map" style="width: 400px; height: 300px"></div>',
      '#attached' => [
        'library' => [
          'mapbox/mapboxgl',
          'mapbox/mapbox',
        ],
      ],
    ];




    return $build;
  }

  /**
   * Build a key => label array from all Mapbox configuration.
   */
  private function getMapConfigs() {
    $configs = $this->config_factory->loadMultiple($this->config_factory->listAll('mapbox'));
    $return = [];
    /** @var \Drupal\Core\Config\ImmutableConfig $config */
    foreach ($configs as $config) {
      $return[$config->get('id')] = $config->get('label');
    }

    return $return;
  }
}
