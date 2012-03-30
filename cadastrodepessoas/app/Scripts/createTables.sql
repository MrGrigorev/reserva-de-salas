DROP SCHEMA IF EXISTS `cadastrodepessoas`;
CREATE SCHEMA `cadastrodepessoas`;
USE cadastrodepessoas;

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nusp VARCHAR(10) NOT NULL UNIQUE,
	name VARCHAR(70) NOT NULL,
	email VARCHAR(50) NOT NULL UNIQUE,
	password VARCHAR(50) NOT NULL,
	photo VARCHAR(100) DEFAULT NULL,
    activation_status ENUM('waiting_validation', 'waiting_activation', 'active') DEFAULT 'waiting_validation' NOT NULL,
    hash VARCHAR(40) NOT NULL,
    user_type ENUM('user', 'admin') DEFAULT 'user',
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE students (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	course VARCHAR(100) NOT NULL,
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE professors (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	department VARCHAR(50) NOT NULL,
	webpage VARCHAR(100) DEFAULT NULL,
	lattes VARCHAR(100) DEFAULT NULL,
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE employees (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	occupation VARCHAR(70) NOT NULL,
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id)
);
