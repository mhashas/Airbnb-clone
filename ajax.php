<?php

include_once('./Classes/AjaxObject.php');
include_once('./classes/Accommodation.php');
require_once('./Classes/Twig.php');
$action = $_POST['action'];


switch ($action) {
	case 'refresh-data':
		$min_price = $_POST['min_amount'];
		$max_price = $_POST['max_amount'];
		$city = $_POST['city'];
		$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
		$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
		$number_of_guests = $_POST['number_of_guests'];


		$type_array = array();
		$type_array_sum = 0;

		for ($i = 0; $i < sizeof(Accomodation::$TYPE_NAMES); $i++) {
			$type = new stdClass;
			$type->name = Accomodation::$TYPE_NAMES[$i];
			$type->index = $i;
			$type->value = isset($_POST['type' . $i]) ? (int)($_POST['type' . $i] == 'on') * ($i + 1) : 0;
			$type_array_sum += $type->value;
			array_push($type_array, $type);
		}


		$accommodations = Accomodation::search($city, $number_of_guests, $start_date, $end_date, $min_price, $max_price, $type_array, $type_array_sum);

		$twig = Twig::get();

		$html = $twig->render('accommodations.twig', array('accommodations' => $accommodations,
		                                                       'type_array' => $type_array,
		                                                       'CURRENT_CITY' => $city,
		                                                       'CURRENT_START_DATE' => $start_date,
		                                                       'CURRENT_END_DATE' => $end_date,
		                                                       'CURRENT_NR_OF_GUESTS' => $number_of_guests,
		));

		(new AjaxObject(array('search_results_html' => $html, 'accommodations' => $accommodations)))->encode();
		exit();
}