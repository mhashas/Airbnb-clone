<?php

include_once('Booking.php');
include_once('Database.php');

class Accomodation {
	private $id;
	private $name;
	private $location;
	private $hostId;
	private $nrOfGuests;
	private $type; // 0 = entire room/apartament, 1 = private room,  2 = shared room
	private $price_per_night;
	private $gmap_x;
	private $gmap_y;
	private $image;

	private static $CLASS_TABLE = 'airbnb_accommodations';
	static $TYPE_NAMES = array('Entire Home', 'Private Room', 'Shared room');

	function __construct($id, $name, $location, $hostId, $nrOfGuests, $type, $price, $gmap_x, $gmap_y, $image_location) {
		$this->id = $id;
		$this->name = $name;
		$this->location = $location;
		$this->hostId = $hostId;
		$this->nrOfGuests = $nrOfGuests;
		$this->type = $type;
		$this->price_per_night = $price;
		$this->gmap_x = $gmap_x;
		$this->gmap_y = $gmap_y;
		$this->image = $image_location;
	}

	function loadCustomObject($row) {
		return new self($row['id'], $row['name'], $row['location'], $row['host_id'], $row['number_of_guests'], $row['type'], $row['price'], $row['gmap_x'], $row['gmap_y'], $row['image']);
	}

	function getId() {
		return $this->id;
	}

	function getLocation() {
		return $this->location;
	}

	function getHostId() {
		return $this->hostId;
	}

	function setHostId($hostId) {
		$this->hostId = $hostId;
	}

	function getPricePerNight() {
		return $this->price_per_night;
	}

	function setPricePerNight($price_per_night) {
		$this->price_per_nigh = $price_per_night;
	}

	function getImage() {
		return $this->image;
	}

	function setImage($image) {
		$this->image = $image;
	}

	function getType(){
		return $this->type;
	}

	function getGmapX(){
		return $this->gmap_x;
	}

	function getGmapY(){
		return $this->gmap_y;
	}

	function setType($type){
		$this->type = $type;
	}

	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}

	/** Given a start date and an end date, checks whether the current accommodation is booked in that time interval
	 * @param $start_date
	 * @param $end_date
	 *
	 * @return bool
	 */
	public function isBooked($start_date, $end_date) {
		$bookings = Booking::getBookingDates($this->id, date('Y-m-d'));

		foreach ($bookings as $booking) {
			// if they overlap return false
			if (strtotime($start_date) <= strtotime($booking->end_date) && strtotime($booking->start_date) <= strtotime($end_date)) {
				return true;
			}
		}

		return false;
	}

	/** Searches for the available accmodation based on the parameter
	 *
	 * @param     $location
	 * @param     $nrOfGuests
	 * @param int $start_date
	 * @param int $end_date
	 * @param int $max_price
	 *
	 * @return array
	 */
	public static function search($location, $nrOfGuests, $start_date = 0, $end_date = 0, $min_price = 0, $max_price = 0, $type_array = null, $type_array_sum = 0) {
		$db = Database::get();

		$sql = 'SELECT * FROM ' . self::$CLASS_TABLE .
			' WHERE location LIKE \'' . (string)$location . '\' AND number_of_guests >= ' . (int)$nrOfGuests;


		if ($type_array_sum) {
			$sql .= ' AND (';
			foreach ($type_array as $type) {
				if ($type->value) {
					$sql .= ' type = ' . (int)$type->value . ' OR ';
				}
			}
			$sql = substr($sql, 0, strlen($sql) - 3);
			$sql .= ' )';
		}

		$result = $db->query($sql);
		$found_accommodations = array();

		if ($result->num_rows) {
			while ($row = $result->fetch_assoc()) {
				$current_accommodation = self::loadCustomObject($row);

				if ($start_date && $end_date && $current_accommodation->isBooked($start_date, $end_date)) {
					continue;
				}

				$days_between = ($start_date && $end_date) ? ceil(abs(strtotime($end_date) - strtotime($start_date)) / 86400) : 0;
				$current_accommodation->total_price = ($days_between ? $days_between : 1) * $current_accommodation->getPricePerNight();
				$current_accommodation->type_name = self::$TYPE_NAMES[$current_accommodation->getType() - 1];

				if ($min_price && $max_price && ($current_accommodation->total_price > $max_price || $current_accommodation->total_price < $min_price)) {
					continue;

				}
				array_push($found_accommodations, $current_accommodation);
			}
		}

		return $found_accommodations;
	}
}