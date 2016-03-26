<?php

class Booking
{
    private $id;
    private $accomodationId;
    private $renter_user_id;
    private $start_date; //timestamp
    private $end_date; //timetamp

    private static $CLASS_TABLE = 'airbnb_bookings';

    function __construct($id, $accomodationId, $renter_user_id, $start_date, $end_date){

        $this->id = $id;
        $this->accomodationId = $accomodationId;
        $this->renter_user_id = $renter_user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;

    }

    function loadCustomObject($databaseRow) {
        return new self($databaseRow['id'], $databaseRow['accomodation_id'], $databaseRow['renter_user_id'], $databaseRow['start_date'], $databaseRow['end_date']);
    }

    public static function getBookingDates($accomodationId, $starting_date = null){
        $db = Database::get();

        $sql = 'SELECT * FROM ' . self::$CLASS_TABLE .
               ' WHERE accomodation_id = ' . (int) $accomodationId;

        if ($starting_date)
            $sql .= ' AND (start_date >= ' . strtotime($starting_date) . ' OR end_date >= ' . strtotime($starting_date) . ')';

        $result = $db->query($sql);

        $bookings_information = array();

        if ($result->num_rows > 0){

            while ($row = $result->fetch_assoc()){
                $current_booking_information = new stdClass();
                $current_booking_information->start_date = $row['start_date'];
                $current_booking_information->end_date = $row['end_date'];

                array_push($bookings_information, $current_booking_information);
            }
        }

        return $bookings_information;
    }

}