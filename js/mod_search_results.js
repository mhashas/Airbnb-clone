var map;
var markers = [];
var infoWindow;
var mode;
var map_updating_timer;

function initialize_map_search(lat, long, zoom_level, search_for_accommodation) {
	var noPoi = [
		{
			featureType: "poi",
			stylers: [
				{visibility: "off"}
			]
		}
	];
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(lat, long),
		zoom: parseInt(zoom_level),
		mapTypeId: 'roadmap',
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
	});

	map.setOptions({styles: noPoi});

	infoWindow = new google.maps.InfoWindow({
		disableAutoPan: false
	});

	google.maps.event.addListener(map, 'idle', function (event) {
		var sw = map.getBounds().getSouthWest();
		var ne = map.getBounds().getNorthEast();
		if (search_for_accommodation){
			scheduleDelayedCallback(sw,ne);
		}
	});
}

function scheduleDelayedCallback(sw_coordinate, ne_coordinate) {
	clearTimeout(map_updating_timer);
	map_updating_timer = setTimeout(function () {
		refreshData('search_parameters',sw_coordinate,ne_coordinate);
	}, 500);
}

function clearLocations() {
	infoWindow.close();
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers.length = 0;
	$('#search_results').html('');
}


function refreshData(form_id, sw_coordinate, ne_coordinate) {
	clearLocations();
	var formData = new FormData($('form[id="' + form_id + '"]')[0]);

	if (sw_coordinate != undefined && ne_coordinate != undefined) {
		formData.append('sw_lat', sw_coordinate.lat());
		formData.append('sw_lng', sw_coordinate.lng());
		formData.append('ne_lat', ne_coordinate.lat());
		formData.append('ne_lng', ne_coordinate.lng());
	}

	$.ajax({
		url: 'ajax.php',
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,

	}).done(function (data) {
		$('#search_results').html(data.content.search_results_html);
	})
}

