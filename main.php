<?php
require_once('./Twig/lib/Twig/Autoloader.php');
require_once('./Classes/Accommodation.php');
require_once('./Classes/Database.php');
require_once('./Classes/Twig.php');

$twig = Twig::get();
$form_submitted = isset($_POST["submitted"]) ? $_POST["submitted"] : false;


if (!$form_submitted) {
	echo $twig->render('airbnb_main.twig');
	exit();
}

//if form was submitted, gather its data
$city = $_POST["city"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$number_of_guests = $_POST["number_of_guests"];
$min_price = isset($_POST["min_price"]) ? $_POST["min_price"] : 0;
$max_price = isset($_POST["max_price"]) ? $_POST["max_price"] : 0;

$type_array = array();

for ($i = 0; $i < sizeof(Accomodation::$TYPE_NAMES); $i++) {
	$type = new stdClass;
	$type->name = Accomodation::$TYPE_NAMES[$i];
	$type->index = $i;
	$type->value = isset($_POST['type' . $i]) ? (int)($_POST['type' . $i] === true) * ($i + 1) : 0;
	array_push($type_array, $type);
}

if (!$city) {
	echo $twig->render('airbnb_main.twig');
	exit();
}

$accommodations = Accomodation::search($city, $number_of_guests, $start_date, $end_date, $min_price, $max_price, $type_array);

$gmap_x = 0;
$gmap_y = 0;

foreach ($accommodations as $accommodation) {
	$gmap_x += $accommodation->getGmapX();
	$gmap_y += $accommodation->getGmapY();
}

$gmap_x = $gmap_x / sizeof($accommodations);
$gmap_y = $gmap_y / sizeof($accommodations);

echo $twig->render('airbnb_search_results.twig', array('accommodations' => $accommodations,
                                                       'type_array' => $type_array,
                                                       'CURRENT_CITY' => $city,
                                                       'CURRENT_START_DATE' => $start_date,
                                                       'CURRENT_END_DATE' => $end_date,
                                                       'CURRENT_NR_OF_GUESTS' => $number_of_guests,
                                                       'GMAP_LAT' => $gmap_x,
                                                       'GMAP_LONG' => $gmap_y,
));

