<?php

namespace Drupal\mapbox\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
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
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
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
    $build = [
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
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return $build;
  }

}
