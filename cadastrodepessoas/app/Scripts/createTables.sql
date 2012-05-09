DROP SCHEMA IF EXISTS `cadastrodepessoas`;
CREATE SCHEMA `cadastrodepessoas`;
USE cadastrodepessoas;

CREATE TABLE departments (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL
);

CREATE TABLE courses (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL
);

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nusp VARCHAR(10) NOT NULL UNIQUE,
	name VARCHAR(70) NOT NULL,
	email VARCHAR(50) NOT NULL UNIQUE,
	password VARCHAR(50) NOT NULL,
	photo VARCHAR(100) DEFAULT NULL,
	webpage VARCHAR(100) DEFAULT NULL,
	lattes VARCHAR(100) DEFAULT NULL,
    activation_status ENUM('active', 'waiting_activation', 'waiting_validation') DEFAULT 'waiting_validation' NOT NULL,
    hash VARCHAR(40) NOT NULL,
    user_type ENUM('user', 'admin') DEFAULT 'user',
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE students (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	course_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE professor_categories (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(70) NOT NULL
);

CREATE TABLE professors (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	telephone varchar(9) DEFAULT NULL,
	room varchar(10) DEFAULT NULL,
	user_id INT NOT NULL,
	department_id INT NOT NULL,
	professor_category_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (department_id) REFERENCES departments(id),
	FOREIGN KEY (professor_category_id) REFERENCES professor_categories(id)
);

CREATE TABLE employees (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	occupation VARCHAR(70) NOT NULL,
	telephone varchar(9) DEFAULT NULL,
	room varchar(10) DEFAULT NULL,
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(id)
);