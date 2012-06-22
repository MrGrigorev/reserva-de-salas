DROP SCHEMA IF EXISTS `reservadesalas`;
CREATE SCHEMA `reservadesalas`;
USE reservadesalas;

CREATE TABLE admins (
	nusp VARCHAR(10) NOT NULL PRIMARY KEY
);

CREATE TABLE buildings (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL,
	floor_number INT UNSIGNED NOT NULL 
);

CREATE TABLE rooms (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	building_id INT NOT NULL,
	name VARCHAR(70) DEFAULT NULL,
	number INT DEFAULT NULL,
	floor INT UNSIGNED NOT NULL,
	room_type ENUM('auditorium', 'normal', 'noble') DEFAULT 'normal' NOT NULL,
	description TEXT DEFAULT NULL,
	capacity INT UNSIGNED NOT NULL,
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL,
	FOREIGN KEY (building_id) REFERENCES buildings(id)
);

CREATE TABLE resources (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	room_id INT DEFAULT NULL,
	serial_number VARCHAR(50) UNIQUE NOT NULL,
	name VARCHAR(50) NOT NULL,
	description TEXT DEFAULT NULL,
	FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE reservations (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	start_time DATETIME NOT NULL,
	end_time DATETIME NOT NULL,
	room_id INT NOT NULL,
	nusp VARCHAR(10) NOT NULL,
	description TEXT DEFAULT NULL,
	is_activated BIT NOT NULL DEFAULT 0,
	created DATETIME DEFAULT NULL,
	FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE reservations_resources (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	reservation_id INT NOT NULL,
	resource_id INT NOT NULL,
	FOREIGN KEY (reservation_id) REFERENCES reservations(id),
	FOREIGN KEY (resource_id) REFERENCES resources(id)
);

CREATE TABLE reservation_types (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL
);