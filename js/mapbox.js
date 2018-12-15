(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.mapboxMapBox = {
    attach: function (context, settings) {

      mapboxgl.accessToken = '';
      const map = new mapboxgl.Map({
        container: '',
        style: '',
        center: [0, 0],
        zoom: 1.1
      });
    }
  };

})(jQuery, Drupal);
