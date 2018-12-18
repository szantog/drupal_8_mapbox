<?php

namespace Drupal\mapbox\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\views\ViewExecutable;

/**
 * The default style plugin for Mapbox.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "mapbox",
 *   title = @Translation("Mapbox"),
 *   help = @Translation("Displays the default summary as a list."),
 *   display_types = {"normal"}
 * )
 */
class Mapbox extends StylePluginBase {
  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;
  /**
   * {@inheritdoc}
   */
  protected $usesFields = TRUE;
  /**
   * {@inheritdoc}
   */
  protected $usesGrouping = FALSE;
  /** @var \Drupal\mapbox\MapboxBuilder $mapboxBuilder */
  protected $mapboxBuilder;

  /** @var mixed|null */
  protected $mapboxId;

  /** @var \Drupal\mapbox\Entity\Mapbox */
  protected $mapbox;

  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    if (!empty($options['mapbox_id'])) {
      $this->mapboxConfig = \Drupal::service('config.factory')
        ->get($options['mapbox_id']);
      $this->mapboxId = $options['mapbox_id'];
      $this->mapbox = \Drupal::service('entity_type.manager')
        ->getStorage('mapbox')
        ->load($this->mapboxId);
    }

    $this->mapboxBuilder = \Drupal::service('mapbox.builder');
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options['mapbox_id'] = ['default' => []];
    $options['location_field'] = ['default' => []];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $fields = $this->displayHandler->getFieldLabels(TRUE);

    $form['location_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Location field'),
      '#options' => $fields,
      '#required' => TRUE,
      '#default_value' => $this->options['location_field'],
      '#description' => $this->t('Select the field that will be used as the location.'),
    ];
    $form['mapbox_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Mapbox configuration'),
      '#options' => $this->mapboxBuilder->getMapConfigs(),
      '#default_value' => $this->mapboxId,
      '#required' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    // Group the rows according to the grouping field, if specified.
    $sets = $this->renderGrouping($this->view->result, $this->options['grouping']);
    $views_result = $this->view->result;
    // @todo We don't display grouping info for now. Could be useful for select
    // widget, though.
    $data = [];
    foreach ($this->rendered_fields as $key => $field) {
      $data[$key][] = $views_result[$key]->_entity->{$this->options['location_field']}->lon;
      $data[$key][] = $views_result[$key]->_entity->{$this->options['location_field']}->lat;
    }

    return $this->mapboxBuilder->renderMap($this->mapbox, '100%', '600px', 'test', $data);
  }
}
