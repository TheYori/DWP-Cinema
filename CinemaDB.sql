-- Localhost DB Setup
DROP DATABASE IF EXISTS CinemaDB;
CREATE DATABASE CinemaDB;

USE CinemaDB;

-- As default the engine was MyISAM which does not show relations in phpmyadmin. This line sets the default to InnoDB as that allows for visible relations
SET default_storage_engine=innoDB;

-- Remote hosting DBs (one.com etc.)
-- We need to do this, because remote hosts does not allow us root access etc.
-- DROP TABLE IF EXISTS Admins;
-- DROP TABLE IF EXISTS Company;
-- DROP TABLE IF EXISTS News;
-- DROP TABLE IF EXISTS PostalCodes;
-- DROP TABLE IF EXISTS Users;
-- DROP TABLE IF EXISTS Halls;
-- DROP TABLE IF EXISTS Seats;
-- DROP TABLE IF EXISTS Movies;
-- DROP TABLE IF EXISTS Showtimes;
-- DROP TABLE IF EXISTS Tickets;
-- DROP TABLE IF EXISTS Will_have;

CREATE TABLE Admins(
    admin_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    admin_password VARCHAR(60) NOT NULL
);

CREATE TABLE Company(
    key_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_key VARCHAR(50) NOT NULL,
    key_value VARCHAR(255) NOT NULL
);

CREATE TABLE News(
    news_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    release_date DATE NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    banner_img VARCHAR(255) NOT NULL
);

CREATE TABLE PostalCodes(
    postal_code INT NOT NULL PRIMARY KEY,
    city VARCHAR(20) NOT NULL
);

CREATE TABLE Users(
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    birth_date DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    street VARCHAR(100) NOT NULL,
    postal_code INT(4) NOT NULL,
    user_password VARCHAR(60) NOT NULL,
    FOREIGN KEY (postal_code) REFERENCES PostalCodes (postal_code)
);

CREATE TABLE Halls(
    hall_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    hall_name VARCHAR(50) NOT NULL
);

CREATE TABLE Seats(
    seat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    seat_name VARCHAR(20) NOT NULL,
    hall_id int NOT NULL,
    FOREIGN KEY (hall_id) REFERENCES Halls (hall_id)
);

CREATE TABLE Movies(
    movie_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    movie_length INT(3) NOT NULL,
    debut_date DATE, -- As the movie can be pre-release it might not be out
    rating FLOAT, -- As the movie can be pre-release it might not be rated
    director VARCHAR(120) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    movie_desc VARCHAR(500) NOT NULL,
    poster VARCHAR(255),
    hall_id INT NOT NULL, 
    FOREIGN KEY (hall_id) REFERENCES Halls (hall_id)
);

CREATE TABLE Showtimes(
    Showtime_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    hall_id INT NOT NULL,
    movie_id INT NOT NULL,
    FOREIGN KEY (hall_id) REFERENCES Halls (hall_id),
    FOREIGN KEY (movie_id) REFERENCES Movies (movie_id)
);

CREATE TABLE Tickets(
    ticket_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ticket_date DATE NOT NULL,
    ticket_time TIME NOT NULL,
    user_id INT NOT NULL,
    Showtime_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (user_id),
    FOREIGN KEY (Showtime_id) REFERENCES Showtimes (Showtime_id)
);

CREATE TABLE Will_have(
    ticket_id INT NOT NULL,
    seat_id INT NOT NULL,
    CONSTRAINT PK_Will_have PRIMARY KEY (ticket_id, seat_id),
    FOREIGN KEY (ticket_id) REFERENCES Tickets (ticket_id),
    FOREIGN KEY (seat_id) REFERENCES Seats (seat_id)
);