$(function () {
  ymaps.ready(init);
});


function init() {
	var myMap, placemarks, mapId = "main_map", mapIdHash = '#' + mapId;
	
	if($(mapIdHash).length === 0)
		return false;

    myMap = new ymaps.Map(mapId, {
      center: [44.94408, 34.097410000000004],
      zoom: 11
    });
    
    
    var clusterLayout = ymaps.templateLayoutFactory.createClass('<div class="ymap-cluster">{{ properties.geoObjects.length }}</div>');
    var objectLayout = ymaps.templateLayoutFactory.createClass('\
    	<div class="ymap-object"><div class="icon"></div></div>');
    var objectLayoutHovered = ymaps.templateLayoutFactory.createClass('\
    	<div class="ymap-object"><div class="icon"></div><div class="ymap-object-hint"><div class="inner">{{ properties.clusterCaption }}</div></div></div>');

    $(mapIdHash).data('yamap', myMap);

    var iconObject = {
      iconImageHref: '/img/balloon.png',
      iconImageSize: [43, 34],
      iconImageOffset: [-11, -34],
      iconLayout: 'default#image'
    };
    
    var lat = $(mapIdHash).data('lat'), lng = $(mapIdHash).data('lng');
    if(!lat || $(mapIdHash).data('loadall')){
		$.getJSON('/site/mapjson', function (data){
			if(data.placemarks){
				placemarks = data.placemarks;
				var clusterer = new ymaps.Clusterer({
					clusterDisableClickZoom: false,
					clusterIconLayout: clusterLayout,
					clusterIconShape: {
						type: 'Rectangle',
						coordinates: [
							[0,0], [30, 30]
						]
					},
					geoObjectIconLayout: objectLayout,
					geoObjectIconShape: {
						type: 'Rectangle',
						coordinates: [
							[-10,-34], [10, 0]
						]
					},
					
				});
				
				var clustererObjects = [];

			    for (var i in placemarks) {
			    	var placemark = placemarks[i];
			    	clustererObjects.push(new ymaps.GeoObject({
						    geometry: { type: "Point", coordinates: [placemark.lat, placemark.lon] },
						    properties: {
						        clusterCaption: placemark.hint.replace(/&quot;/g, '"'),
						        balloonContentBody: placemark.content
						    }
						})
					);
			    }
			    clusterer.add(clustererObjects);
			    
			        clusterer.events
				        .add(['mouseenter', 'mouseleave', 'click'], function (e) {
				            var target = e.get('target'),
				                type = e.get('type');
				            if (typeof target.getGeoObjects != 'undefined') {
				            } else {
				                // Событие произошло на геообъекте.
				                if (type == 'mouseenter') {
				                    target.options.set('iconLayout', objectLayoutHovered);
				                } else {
				                    target.options.set('iconLayout', objectLayout);
				                }
				            }
				        });
			    
			    myMap.geoObjects.add(clusterer);
			    if(lat && lng){
				    myMap.setCenter([lat, lng]);
				    myMap.setZoom(16);
			    } else {
				    myMap.setBounds(myMap.geoObjects.getBounds());
			    }
			}
		});
    } else {
		myMap.geoObjects.removeAll();
				myMap.geoObjects.add(new ymaps.Placemark([lat, lng], {
					}, iconObject
				));
			    myMap.setCenter([lat, lng]);
			    myMap.setZoom(16);
    }

	var deferedSearch;	
	$('[data-map-target]').keyup(function(){
		var target = $(this).data('map-target');
		target = $(target);
		var myMap = target.data('yamap');
		if(!myMap)
			return;
			
		var address = $(this).val();
		
		clearTimeout(deferedSearch);
		deferedSearch = setTimeout(function(){
			var objects = ymaps.geocode(address, {kind: 'house'});
			
			objects.then(function (result) {
					
				var coords = result.geoObjects.get(0).geometry.getCoordinates();
				
				$('input.latinto').val(coords[0]);
				$('input.lnginto').val(coords[1]);
				
				myMap.geoObjects.removeAll();
				myMap.geoObjects.add(new ymaps.Placemark(coords, {
					}, iconObject
				));
			    myMap.setCenter(coords);
			    myMap.setZoom(16);
			
			});
		
		}, 500);
		
				
	});

  }


