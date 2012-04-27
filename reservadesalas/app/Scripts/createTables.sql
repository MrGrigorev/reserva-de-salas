DROP SCHEMA IF EXISTS `reservadesalas`;
CREATE SCHEMA `reservadesalas`;
USE reservadesalas;

CREATE TABLE rooms (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	building_id INT UNSIGNED NOT NULL,
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
	room_id INT UNSIGNED NOT NULL,
	serial_number VARCHAR(50) NOT NULL,
	name VARCHAR(50) NOT NULL,
	description TEXT DEFAULT NULL,
	FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE buildings (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL,
	floor_number INT UNSIGNED NOT NULL 
);