(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.mapboxMapBox = {
    attach: function (context, settings) {
      Object.keys(settings.mapbox).forEach(function (key) {

        var setting = settings.mapbox[key];
        mapboxgl.accessToken = setting.mapbox.access_token;
        const map = new mapboxgl.Map({
          container: key,
          style: setting.mapbox.style,
          center: [setting.mapbox.center.x, setting.mapbox.center.y],
          zoom: setting.mapbox.zoom,
        });


        var features = [];
        for (var i = 0; i < setting.data.length; i++) {
          features[i] = {
            "type": "Feature",
            "geometry": {
              "type": "Point",
              "coordinates": [setting.data[i][0], setting.data[i][1]],
            }
          };
        }

        console.log(setting.data);
        map.on("load", function () {
          map.addSource("field", {
            "type": "geojson",
            "data": {
              "type": "FeatureCollection",
              "features": features,
            }
          });

          map.addLayer({
            "id": "park-volcanoes",
            "type": "circle",
            "source": "field",
            "paint": {
              "circle-radius": 6,
              "circle-color": "#B42222"
            },
            "filter": ["==", "$type", "Point"],
          });
        });
      });

    }
  };

})(jQuery, Drupal);
