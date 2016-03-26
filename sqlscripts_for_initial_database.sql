-- create accommodations table --
CREATE TABLE IF NOT EXISTS `test`.`airbnb_accommodations`
( `id` TINYINT NOT NULL AUTO_INCREMENT ,
`location` VARCHAR(32) NOT NULL ,
`host_id` TINYINT NOT NULL ,
`number_of_guests` TINYINT NOT NULL ,
`price` INT NOT NULL COMMENT 'The price for a night' ,
`gmap_x` INT NOT NULL COMMENT 'x coordinate of google maps location' ,
`gmap_y` INT NOT NULL COMMENT 'y coordinate of google maps location' ,
`type` INT NOT NULL COMMENT '0 = Entire apartament, 1 = private room, 2 = shared room ' ,
`image` VARCHAR(32) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `airbnb_accommodations` ADD `name` VARCHAR(128) NOT NULL AFTER `id`;

-- populate accommodations table with some values --
INSERT INTO `airbnb_accommodations` (`id`, `name`, `location`, `host_id`, `number_of_guests`, `price`, `gmap_x`, `gmap_y`, `type`, `image`) VALUES ('1', 'Penthouse apartament with terrace', 'Prague', '122', '5', '65', '56', '56', '3', '2.jpg');
INSERT INTO `airbnb_accommodations` (`id`, `name`, `location`, `host_id`, `number_of_guests`, `price`, `gmap_x`, `gmap_y`, `type`, `image`) VALUES ('3', 'Cozy room', 'Prague', '123', '1', '23', '50', '16', '1', '1.jpg');
INSERT INTO `airbnb_accommodations` (`id`, `name`, `location`, `host_id`, `number_of_guests`, `price`, `gmap_x`, `gmap_y`, `type`, `image`) VALUES ('3', 'Very nice shared room', 'Prague', '4', '1', '23', '45', '23', '3', '3.jpg');

-- create bookings table --

CREATE TABLE `test`.`airbnb_bookings`
( `id` INT NOT NULL AUTO_INCREMENT ,
`accomodation_id` TINYINT NOT NULL ,
`renter_user_id` INT NOT NULL ,
`start_date` DATE NOT NULL ,
`end_date` DATE NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;


-- populate bookings table with some values --
INSERT INTO `airbnb_bookings` (`id`, `accomodation_id`, `renter_user_id`, `start_date`, `end_date`) VALUES ('1', '1', '23', '2016-03-26', '2016-03-30');