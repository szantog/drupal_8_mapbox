<?php

namespace Drupal\mapbox\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MapboxForm.
 */
class MapboxForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $mapbox = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $mapbox->label(),
      '#description' => $this->t("Label for the Mapbox."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $mapbox->id(),
      '#machine_name' => [
        'exists' => '\Drupal\mapbox\Entity\Mapbox::load',
      ],
      '#disabled' => !$mapbox->isNew(),
    ];

    $form['access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access token'),
      '#maxlength' => 60,
      '#default_value' => $mapbox->label(),
      '#description' => $this->t("The map's access token. To get access token register on the <a href='https://www.mapbox.com'>Mapbox</a> site."),
      '#required' => TRUE,
    ];

    $form['style'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Style'),
      '#maxlength' => 60,
      '#default_value' => $mapbox->label(),
      '#description' => $this->t("To load a style from the Mapbox API, you can use a URL of the form mapbox://styles/:owner/:style, where :owner is your Mapbox account name and :style is the style ID. Or you can use one of the <a href='https://www.mapbox.com/maps/'>predefined Mapbox styles</a>.<br>Tilesets hosted with Mapbox can be style-optimized if you append ?optimize=true to the end of your style URL, like mapbox://styles/mapbox/streets-v9?optimize=true. Learn more about style-optimized vector tiles in our <a href='https://www.mapbox.com/api-documentation/#retrieve-tiles'>API documentation</a>."),
      '#required' => TRUE,
    ];

    $form['center'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Center'),
      '#description' => $this->t('The inital geographical centerpoint of the map. If  center is not specified in the constructor options, Mapbox GL JS will look for it in the map\'s style object. If it is not specified in the style, either, it will default to  [0, 0] Note: Mapbox GL uses longitude, latitude coordinate order (as opposed to latitude, longitude) to match GeoJSON.'),
    ];

    $form['center']['x'] = [
      '#type' => 'textfield',
      '#field_prefix' => $this->t('X: '),
      '#maxlength' => 8,
      '#default_value' => $mapbox->get('center.x'),
      '#tree' => TRUE,
    ];

    $form['center']['y'] = [
      '#type' => 'textfield',
      '#field_prefix' => $this->t('Y: '),
      '#maxlength' => 8,
      '#default_value' => $mapbox->get('center.x'),
      '#tree' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $mapbox = $this->entity;
    $status = $mapbox->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Mapbox.', [
          '%label' => $mapbox->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Mapbox.', [
          '%label' => $mapbox->label(),
        ]));
    }
    $form_state->setRedirectUrl($mapbox->toUrl('collection'));
  }

}
