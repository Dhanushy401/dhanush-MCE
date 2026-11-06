-- redBus Educational Replica Database
-- Database: redbus_db
-- Compatible with: redbus_intermediate_no_student_id_email_fix project
-- IMPORTANT: This script drops and recreates the five project tables.
-- Back up existing project data before importing.

CREATE DATABASE IF NOT EXISTS redbus_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE redbus_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS booking_seats;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS buses;
DROP TABLE IF EXISTS routes;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    source VARCHAR(80) NOT NULL,
    destination VARCHAR(80) NOT NULL,
    UNIQUE KEY unique_route (source, destination)
) ENGINE=InnoDB;

CREATE TABLE buses (
    bus_id INT AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100) NOT NULL,
    bus_type VARCHAR(80) NOT NULL,
    departure TIME NOT NULL,
    arrival TIME NOT NULL,
    fare INT NOT NULL,
    total_seats INT NOT NULL DEFAULT 40,
    route_id INT NOT NULL,
    CONSTRAINT fk_buses_route
      FOREIGN KEY (route_id) REFERENCES routes(route_id)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bus_id INT NOT NULL,
    journey_date DATE NOT NULL,
    passenger_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    total_amount INT NOT NULL,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_user
      FOREIGN KEY (user_id) REFERENCES users(user_id)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_bookings_bus
      FOREIGN KEY (bus_id) REFERENCES buses(bus_id)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE booking_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    bus_id INT NOT NULL,
    journey_date DATE NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    CONSTRAINT fk_seats_booking
      FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
      ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_seats_bus
      FOREIGN KEY (bus_id) REFERENCES buses(bus_id)
      ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY one_booking_per_seat (bus_id, journey_date, seat_number)
) ENGINE=InnoDB;

-- Sample route dataset
INSERT INTO routes (source, destination) VALUES
('Bangalore','Mysore'),
('Bangalore','Chennai'),
('Bangalore','Hyderabad'),
('Bangalore','Mangalore'),
('Bangalore','Mumbai'),
('Mysore','Bangalore'),
('Chennai','Bangalore'),
('Hyderabad','Bangalore'),
('Pune','Bangalore'),
('Mangalore','Bangalore');

-- Sample bus dataset (10 buses mapped to the 10 routes above)
INSERT INTO buses
(bus_name, bus_type, departure, arrival, fare, total_seats, route_id) VALUES
('VRL Travels','AC Sleeper','21:00:00','05:00:00',850,40,1),
('SRS Travels','Non AC Sleeper','22:00:00','06:00:00',650,40,1),
('Orange Travels','AC Seater','20:30:00','05:30:00',950,40,2),
('KPN Travels','AC Sleeper','21:30:00','07:00:00',1100,40,2),
('GreenLine','AC Sleeper','20:00:00','07:00:00',1200,40,3),
('KSRTC Airavat','Airavat AC','22:00:00','06:00:00',900,40,4),
('VRL Travels','AC Sleeper','19:00:00','09:00:00',1500,40,5),
('SRS Travels','AC Sleeper','21:00:00','05:00:00',800,40,6),
('Orange Travels','AC Seater','22:00:00','06:00:00',1000,40,7),
('KPN Travels','AC Sleeper','20:00:00','08:00:00',1300,40,8);

-- Users register through the website; no demo account is inserted because
-- passwords must be created using PHP password_hash().
-- Bookings and seat allocations are created through the booking workflow.
