<?php

namespace Drupal\mapbox\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Mapbox entity.
 *
 * @ConfigEntityType(
 *   id = "mapbox",
 *   label = @Translation("Mapbox"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\mapbox\MapboxListBuilder",
 *     "form" = {
 *       "add" = "Drupal\mapbox\Form\MapboxForm",
 *       "edit" = "Drupal\mapbox\Form\MapboxForm",
 *       "delete" = "Drupal\mapbox\Form\MapboxDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\mapbox\MapboxHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "mapbox",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/mapbox/{mapbox}",
 *     "add-form" = "/admin/structure/mapbox/add",
 *     "edit-form" = "/admin/structure/mapbox/{mapbox}/edit",
 *     "delete-form" = "/admin/structure/mapbox/{mapbox}/delete",
 *     "collection" = "/admin/structure/mapbox"
 *   }
 * )
 */
class Mapbox extends ConfigEntityBase implements MapboxInterface {

  /**
   * The Mapbox ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Mapbox label.
   *
   * @var string
   */
  protected $label;

}
