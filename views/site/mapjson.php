<?
$placemarks = [];
foreach($data as $store) {
	if(!$store->lat || !$store->lng)
		continue;
		
	$placemarks[] = [
			'lat'=>$store->lat,
			'lon'=>$store->lng,
			'hint'=>$store->title,
			'content'=>$this->render('map_balloon', ['store'=>$store])
		];
}

echo JSON_encode(['placemarks'=>$placemarks]);