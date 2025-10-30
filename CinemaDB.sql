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
    key_value TEXT NOT NULL
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
    movie_desc TEXT NOT NULL,
    poster VARCHAR(255),
    hall_id INT,
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




-- HALL DATA --
-- As the halls will not change in this project they have been added in here
-- CRUD via admin back-end panel does not take priority
INSERT INTO Halls (hall_name) VALUES ('Grimm Hall');
INSERT INTO Halls (hall_name) VALUES ('Wendigo Den');

-- MOVIE DATA --
-- As it is easier to drop the entire Database when going live on Simply.com I have chosen to hardcode.
-- CRUD functions work perfectly, so this is just easier for me to maintain in the long run.


-- POSTAL CODE DATA --
-- All this data is permanent and will not change
-- This data needs to be implemented after database creation
INSERT INTO PostalCodes (postal_code, city) VALUES (1301, 'København K');
INSERT INTO PostalCodes (postal_code, city) VALUES (2000, 'Frederiksberg');
INSERT INTO PostalCodes (postal_code, city) VALUES (2100, 'København Ø');
INSERT INTO PostalCodes (postal_code, city) VALUES (2200, 'København N');
INSERT INTO PostalCodes (postal_code, city) VALUES (2300, 'København S');
INSERT INTO PostalCodes (postal_code, city) VALUES (2400, 'København NV');
INSERT INTO PostalCodes (postal_code, city) VALUES (2450, 'København SV');
INSERT INTO PostalCodes (postal_code, city) VALUES (2500, 'Valby');
INSERT INTO PostalCodes (postal_code, city) VALUES (2600, 'Glostrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (2605, 'Brøndby');
INSERT INTO PostalCodes (postal_code, city) VALUES (2610, 'Rødovre');
INSERT INTO PostalCodes (postal_code, city) VALUES (2625, 'Vallensbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (2630, 'Taastrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (2635, 'Ishøj');
INSERT INTO PostalCodes (postal_code, city) VALUES (2640, 'Hedehusene');
INSERT INTO PostalCodes (postal_code, city) VALUES (2650, 'Hvidovre');
INSERT INTO PostalCodes (postal_code, city) VALUES (2660, 'Brøndby Strand');
INSERT INTO PostalCodes (postal_code, city) VALUES (2665, 'Vallensbæk Strand');
INSERT INTO PostalCodes (postal_code, city) VALUES (2670, 'Greve');
INSERT INTO PostalCodes (postal_code, city) VALUES (2680, 'Solrød Strand');
INSERT INTO PostalCodes (postal_code, city) VALUES (2690, 'Karlslunde');
INSERT INTO PostalCodes (postal_code, city) VALUES (2700, 'Brønshøj');
INSERT INTO PostalCodes (postal_code, city) VALUES (2720, 'Vanløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (2730, 'Herlev');
INSERT INTO PostalCodes (postal_code, city) VALUES (2740, 'Skovlunde');
INSERT INTO PostalCodes (postal_code, city) VALUES (2750, 'Ballerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (2760, 'Måløv');
INSERT INTO PostalCodes (postal_code, city) VALUES (2765, 'Smørum');
INSERT INTO PostalCodes (postal_code, city) VALUES (2770, 'Kastrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (2791, 'Dragør');
INSERT INTO PostalCodes (postal_code, city) VALUES (2800, 'Kongens Lyngby');
INSERT INTO PostalCodes (postal_code, city) VALUES (2820, 'Gentofte');
INSERT INTO PostalCodes (postal_code, city) VALUES (2830, 'Virum');
INSERT INTO PostalCodes (postal_code, city) VALUES (2840, 'Holte');
INSERT INTO PostalCodes (postal_code, city) VALUES (2850, 'Nærum');
INSERT INTO PostalCodes (postal_code, city) VALUES (2860, 'Søborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (2880, 'Bagsværd');
INSERT INTO PostalCodes (postal_code, city) VALUES (2900, 'Hellerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (2920, 'Charlottenlund');
INSERT INTO PostalCodes (postal_code, city) VALUES (2930, 'Klampenborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (2942, 'Skodsborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (2950, 'Vedbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (2960, 'Rungsted Kyst');
INSERT INTO PostalCodes (postal_code, city) VALUES (2970, 'Hørsholm');
INSERT INTO PostalCodes (postal_code, city) VALUES (2980, 'Kokkedal');
INSERT INTO PostalCodes (postal_code, city) VALUES (2990, 'Nivå');
INSERT INTO PostalCodes (postal_code, city) VALUES (3000, 'Helsingør');
INSERT INTO PostalCodes (postal_code, city) VALUES (3050, 'Humlebæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (3060, 'Espergærde');
INSERT INTO PostalCodes (postal_code, city) VALUES (3070, 'Snekkersten');
INSERT INTO PostalCodes (postal_code, city) VALUES (3080, 'Tikøb');
INSERT INTO PostalCodes (postal_code, city) VALUES (3100, 'Hornbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (3120, 'Dronningmølle');
INSERT INTO PostalCodes (postal_code, city) VALUES (3140, 'Ålsgårde');
INSERT INTO PostalCodes (postal_code, city) VALUES (3150, 'Hellebæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (3200, 'Helsinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (3210, 'Vejby');
INSERT INTO PostalCodes (postal_code, city) VALUES (3220, 'Tisvildeleje');
INSERT INTO PostalCodes (postal_code, city) VALUES (3230, 'Græsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (3250, 'Gilleleje');
INSERT INTO PostalCodes (postal_code, city) VALUES (3300, 'Frederiksværk');
INSERT INTO PostalCodes (postal_code, city) VALUES (3310, 'Ølsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (3320, 'Skævinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (3330, 'Gørløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (3360, 'Liseleje');
INSERT INTO PostalCodes (postal_code, city) VALUES (3370, 'Melby');
INSERT INTO PostalCodes (postal_code, city) VALUES (3390, 'Hundested');
INSERT INTO PostalCodes (postal_code, city) VALUES (3400, 'Hillerød');
INSERT INTO PostalCodes (postal_code, city) VALUES (3460, 'Birkerød');
INSERT INTO PostalCodes (postal_code, city) VALUES (3480, 'Fredensborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (3490, 'Kvistgård');
INSERT INTO PostalCodes (postal_code, city) VALUES (3500, 'Værløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (3520, 'Farum');
INSERT INTO PostalCodes (postal_code, city) VALUES (3540, 'Lynge');
INSERT INTO PostalCodes (postal_code, city) VALUES (3550, 'Slangerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (3600, 'Frederikssund');
INSERT INTO PostalCodes (postal_code, city) VALUES (3630, 'Jægerspris');
INSERT INTO PostalCodes (postal_code, city) VALUES (3650, 'Ølstykke');
INSERT INTO PostalCodes (postal_code, city) VALUES (3660, 'Stenløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (3670, 'Veksø Sjælland');
INSERT INTO PostalCodes (postal_code, city) VALUES (3700, 'Rønne');
INSERT INTO PostalCodes (postal_code, city) VALUES (3720, 'Aakirkeby');
INSERT INTO PostalCodes (postal_code, city) VALUES (3730, 'Nexø');
INSERT INTO PostalCodes (postal_code, city) VALUES (3740, 'Svaneke');
INSERT INTO PostalCodes (postal_code, city) VALUES (3751, 'Østermarie');
INSERT INTO PostalCodes (postal_code, city) VALUES (3760, 'Gudhjem');
INSERT INTO PostalCodes (postal_code, city) VALUES (3770, 'Allinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (3782, 'Klemensker');
INSERT INTO PostalCodes (postal_code, city) VALUES (3790, 'Hasle');
INSERT INTO PostalCodes (postal_code, city) VALUES (4000, 'Roskilde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4040, 'Jyllinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4050, 'Skibby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4060, 'Kirke Såby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4070, 'Kirke Hyllinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4100, 'Ringsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (4130, 'Viby Sjælland');
INSERT INTO PostalCodes (postal_code, city) VALUES (4140, 'Borup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4160, 'Herlufmagle');
INSERT INTO PostalCodes (postal_code, city) VALUES (4171, 'Glumsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4173, 'Fjenneslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4174, 'Jystrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4180, 'Sorø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4190, 'Munke Bjergby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4200, 'Slagelse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4220, 'Korsør');
INSERT INTO PostalCodes (postal_code, city) VALUES (4230, 'Skælskør');
INSERT INTO PostalCodes (postal_code, city) VALUES (4241, 'Vemmelev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4242, 'Boeslunde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4243, 'Rude');
INSERT INTO PostalCodes (postal_code, city) VALUES (4250, 'Fuglebjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4261, 'Dalmose');
INSERT INTO PostalCodes (postal_code, city) VALUES (4262, 'Sandved');
INSERT INTO PostalCodes (postal_code, city) VALUES (4270, 'Høng');
INSERT INTO PostalCodes (postal_code, city) VALUES (4281, 'Gørlev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4291, 'Ruds Vedby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4293, 'Dianalund');
INSERT INTO PostalCodes (postal_code, city) VALUES (4295, 'Stenlille');
INSERT INTO PostalCodes (postal_code, city) VALUES (4296, 'Nyrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4300, 'Holbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (4320, 'Lejre');
INSERT INTO PostalCodes (postal_code, city) VALUES (4330, 'Hvalsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4340, 'Tølløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4350, 'Ugerløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4360, 'Kirke Eskilstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4370, 'Store Merløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4390, 'Vipperød');
INSERT INTO PostalCodes (postal_code, city) VALUES (4400, 'Kalundborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4420, 'Regstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4440, 'Mørkøv');
INSERT INTO PostalCodes (postal_code, city) VALUES (4450, 'Jyderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4460, 'Snertinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4470, 'Svebølle');
INSERT INTO PostalCodes (postal_code, city) VALUES (4480, 'Store Fuglede');
INSERT INTO PostalCodes (postal_code, city) VALUES (4490, 'Jerslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4500, 'Nykøbing Sj');
INSERT INTO PostalCodes (postal_code, city) VALUES (4520, 'Svinninge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4532, 'Gislinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4534, 'Hørve');
INSERT INTO PostalCodes (postal_code, city) VALUES (4540, 'Fårevejle');
INSERT INTO PostalCodes (postal_code, city) VALUES (4550, 'Asnæs');
INSERT INTO PostalCodes (postal_code, city) VALUES (4560, 'Vig');
INSERT INTO PostalCodes (postal_code, city) VALUES (4571, 'Grevinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4572, 'Nørre Asmindrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4573, 'Højby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4581, 'Rørvig');
INSERT INTO PostalCodes (postal_code, city) VALUES (4583, 'Sjællands Odde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4591, 'Føllenslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4592, 'Sejerø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4593, 'Eskebjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4600, 'Køge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4621, 'Gadstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4622, 'Havdrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4623, 'Lille Skensved');
INSERT INTO PostalCodes (postal_code, city) VALUES (4632, 'Bjæverskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (4640, 'Fakse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4652, 'Hårlev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4653, 'Karise');
INSERT INTO PostalCodes (postal_code, city) VALUES (4654, 'Fakse Ladeplads');
INSERT INTO PostalCodes (postal_code, city) VALUES (4660, 'Store Heddinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4671, 'Strøby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4672, 'Klippinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4673, 'Rødvig Stevns');
INSERT INTO PostalCodes (postal_code, city) VALUES (4681, 'Herfølge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4682, 'Tureby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4683, 'Rønnede');
INSERT INTO PostalCodes (postal_code, city) VALUES (4684, 'Holme-Olstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4690, 'Haslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4700, 'Næstved');
INSERT INTO PostalCodes (postal_code, city) VALUES (4720, 'Præstø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4733, 'Tappernøje');
INSERT INTO PostalCodes (postal_code, city) VALUES (4735, 'Mern');
INSERT INTO PostalCodes (postal_code, city) VALUES (4736, 'Karrebæksminde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4750, 'Lundby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4760, 'Vordingborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4771, 'Kalvehave');
INSERT INTO PostalCodes (postal_code, city) VALUES (4772, 'Langebæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (4773, 'Stensved');
INSERT INTO PostalCodes (postal_code, city) VALUES (4780, 'Stege');
INSERT INTO PostalCodes (postal_code, city) VALUES (4791, 'Borre');
INSERT INTO PostalCodes (postal_code, city) VALUES (4792, 'Askeby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4793, 'Bogø By');
INSERT INTO PostalCodes (postal_code, city) VALUES (4800, 'Nykøbing F');
INSERT INTO PostalCodes (postal_code, city) VALUES (4840, 'Nørre Alslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4850, 'Stubbekøbing');
INSERT INTO PostalCodes (postal_code, city) VALUES (4862, 'Guldborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4863, 'Eskilstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4871, 'Horbelev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4872, 'Idestrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (4873, 'Væggerløse');
INSERT INTO PostalCodes (postal_code, city) VALUES (4874, 'Gedser');
INSERT INTO PostalCodes (postal_code, city) VALUES (4880, 'Nysted');
INSERT INTO PostalCodes (postal_code, city) VALUES (4891, 'Toreby L');
INSERT INTO PostalCodes (postal_code, city) VALUES (4892, 'Kettinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (4894, 'Øster Ulslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4895, 'Errindlev');
INSERT INTO PostalCodes (postal_code, city) VALUES (4900, 'Nakskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (4912, 'Harpelunde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4913, 'Horslunde');
INSERT INTO PostalCodes (postal_code, city) VALUES (4920, 'Søllested');
INSERT INTO PostalCodes (postal_code, city) VALUES (4930, 'Maribo');
INSERT INTO PostalCodes (postal_code, city) VALUES (4941, 'Bandholm');
INSERT INTO PostalCodes (postal_code, city) VALUES (4943, 'Torrig L');
INSERT INTO PostalCodes (postal_code, city) VALUES (4944, 'Fejø');
INSERT INTO PostalCodes (postal_code, city) VALUES (4951, 'Nørreballe');
INSERT INTO PostalCodes (postal_code, city) VALUES (4952, 'Stokkemarke');
INSERT INTO PostalCodes (postal_code, city) VALUES (4953, 'Vesterborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (4960, 'Holeby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4970, 'Rødby');
INSERT INTO PostalCodes (postal_code, city) VALUES (4983, 'Dannemare');
INSERT INTO PostalCodes (postal_code, city) VALUES (4990, 'Sakskøbing');
INSERT INTO PostalCodes (postal_code, city) VALUES (5000, 'Odense C');
INSERT INTO PostalCodes (postal_code, city) VALUES (5200, 'Odense V');
INSERT INTO PostalCodes (postal_code, city) VALUES (5210, 'Odense NV');
INSERT INTO PostalCodes (postal_code, city) VALUES (5220, 'Odense SØ');
INSERT INTO PostalCodes (postal_code, city) VALUES (5230, 'Odense M');
INSERT INTO PostalCodes (postal_code, city) VALUES (5240, 'Odense NØ');
INSERT INTO PostalCodes (postal_code, city) VALUES (5250, 'Odense SV');
INSERT INTO PostalCodes (postal_code, city) VALUES (5260, 'Odense S');
INSERT INTO PostalCodes (postal_code, city) VALUES (5270, 'Odense N');
INSERT INTO PostalCodes (postal_code, city) VALUES (5290, 'Marslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (5300, 'Kerteminde');
INSERT INTO PostalCodes (postal_code, city) VALUES (5330, 'Munkebo');
INSERT INTO PostalCodes (postal_code, city) VALUES (5350, 'Rynkeby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5370, 'Mesinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (5380, 'Dalby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5390, 'Martofte');
INSERT INTO PostalCodes (postal_code, city) VALUES (5400, 'Bogense');
INSERT INTO PostalCodes (postal_code, city) VALUES (5450, 'Otterup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5462, 'Morud');
INSERT INTO PostalCodes (postal_code, city) VALUES (5463, 'Harndrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5464, 'Brenderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5466, 'Asperup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5471, 'Søndersø');
INSERT INTO PostalCodes (postal_code, city) VALUES (5474, 'Veflinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (5485, 'Skamby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5491, 'Blommenslyst');
INSERT INTO PostalCodes (postal_code, city) VALUES (5492, 'Vissenbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5500, 'Middelfart');
INSERT INTO PostalCodes (postal_code, city) VALUES (5540, 'Ullerslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (5550, 'Langeskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (5560, 'Aarup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5580, 'Nørre Aaby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5591, 'Gelsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (5592, 'Ejby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5600, 'Faaborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5610, 'Assens');
INSERT INTO PostalCodes (postal_code, city) VALUES (5620, 'Glamsbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5631, 'Ebberup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5642, 'Millinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (5672, 'Broby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5683, 'Haarby');
INSERT INTO PostalCodes (postal_code, city) VALUES (5690, 'Tommerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5700, 'Svendborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5750, 'Ringe');
INSERT INTO PostalCodes (postal_code, city) VALUES (5762, 'Vester Skerninge');
INSERT INTO PostalCodes (postal_code, city) VALUES (5771, 'Stenstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5772, 'Kværndrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5792, 'Årslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (5800, 'Nyborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5853, 'Ørbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (5854, 'Gislev');
INSERT INTO PostalCodes (postal_code, city) VALUES (5856, 'Ryslinge');
INSERT INTO PostalCodes (postal_code, city) VALUES (5863, 'Ferritslev Fyn');
INSERT INTO PostalCodes (postal_code, city) VALUES (5871, 'Frørup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5874, 'Hesselager');
INSERT INTO PostalCodes (postal_code, city) VALUES (5881, 'Skårup Fyn');
INSERT INTO PostalCodes (postal_code, city) VALUES (5882, 'Vejstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (5883, 'Oure');
INSERT INTO PostalCodes (postal_code, city) VALUES (5884, 'Gudme');
INSERT INTO PostalCodes (postal_code, city) VALUES (5892, 'Gudbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (5900, 'Rudkøbing');
INSERT INTO PostalCodes (postal_code, city) VALUES (5932, 'Humble');
INSERT INTO PostalCodes (postal_code, city) VALUES (5935, 'Bagenkop');
INSERT INTO PostalCodes (postal_code, city) VALUES (5953, 'Tranekær');
INSERT INTO PostalCodes (postal_code, city) VALUES (5960, 'Marstal');
INSERT INTO PostalCodes (postal_code, city) VALUES (5970, 'Ærøskøbing');
INSERT INTO PostalCodes (postal_code, city) VALUES (5985, 'Søby Ærø');
INSERT INTO PostalCodes (postal_code, city) VALUES (6000, 'Kolding');
INSERT INTO PostalCodes (postal_code, city) VALUES (6040, 'Egtved');
INSERT INTO PostalCodes (postal_code, city) VALUES (6051, 'Almind');
INSERT INTO PostalCodes (postal_code, city) VALUES (6052, 'Viuf');
INSERT INTO PostalCodes (postal_code, city) VALUES (6064, 'Jordrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6070, 'Christiansfeld');
INSERT INTO PostalCodes (postal_code, city) VALUES (6091, 'Bjert');
INSERT INTO PostalCodes (postal_code, city) VALUES (6092, 'Sønder Stenderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6093, 'Sjølund');
INSERT INTO PostalCodes (postal_code, city) VALUES (6094, 'Hejls');
INSERT INTO PostalCodes (postal_code, city) VALUES (6100, 'Haderslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (6200, 'Aabenraa');
INSERT INTO PostalCodes (postal_code, city) VALUES (6230, 'Rødekro');
INSERT INTO PostalCodes (postal_code, city) VALUES (6240, 'Løgumkloster');
INSERT INTO PostalCodes (postal_code, city) VALUES (6261, 'Bredebro');
INSERT INTO PostalCodes (postal_code, city) VALUES (6270, 'Tønder');
INSERT INTO PostalCodes (postal_code, city) VALUES (6280, 'Højer');
INSERT INTO PostalCodes (postal_code, city) VALUES (6300, 'Gråsten');
INSERT INTO PostalCodes (postal_code, city) VALUES (6310, 'Broager');
INSERT INTO PostalCodes (postal_code, city) VALUES (6320, 'Egernsund');
INSERT INTO PostalCodes (postal_code, city) VALUES (6330, 'Padborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6340, 'Kruså');
INSERT INTO PostalCodes (postal_code, city) VALUES (6360, 'Tinglev');
INSERT INTO PostalCodes (postal_code, city) VALUES (6372, 'Bylderup-Bov');
INSERT INTO PostalCodes (postal_code, city) VALUES (6392, 'Bolderslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (6400, 'Sønderborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6430, 'Nordborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6440, 'Augustenborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6470, 'Sydals');
INSERT INTO PostalCodes (postal_code, city) VALUES (6500, 'Vojens');
INSERT INTO PostalCodes (postal_code, city) VALUES (6510, 'Gram');
INSERT INTO PostalCodes (postal_code, city) VALUES (6520, 'Toftlund');
INSERT INTO PostalCodes (postal_code, city) VALUES (6535, 'Branderup J');
INSERT INTO PostalCodes (postal_code, city) VALUES (6541, 'Bevtoft');
INSERT INTO PostalCodes (postal_code, city) VALUES (6560, 'Sommersted');
INSERT INTO PostalCodes (postal_code, city) VALUES (6580, 'Vamdrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6600, 'Vejen');
INSERT INTO PostalCodes (postal_code, city) VALUES (6621, 'Gesten');
INSERT INTO PostalCodes (postal_code, city) VALUES (6622, 'Bække');
INSERT INTO PostalCodes (postal_code, city) VALUES (6623, 'Vorbasse');
INSERT INTO PostalCodes (postal_code, city) VALUES (6630, 'Rødding');
INSERT INTO PostalCodes (postal_code, city) VALUES (6640, 'Lunderskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (6650, 'Brørup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6660, 'Lintrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6670, 'Holsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (6682, 'Hovborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6683, 'Føvling');
INSERT INTO PostalCodes (postal_code, city) VALUES (6690, 'Gørding');
INSERT INTO PostalCodes (postal_code, city) VALUES (6700, 'Esbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6701, 'Esbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6705, 'Esbjerg Ø');
INSERT INTO PostalCodes (postal_code, city) VALUES (6710, 'Esbjerg V');
INSERT INTO PostalCodes (postal_code, city) VALUES (6715, 'Esbjerg N');
INSERT INTO PostalCodes (postal_code, city) VALUES (6720, 'Fanø');
INSERT INTO PostalCodes (postal_code, city) VALUES (6731, 'Tjæreborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6740, 'Bramming');
INSERT INTO PostalCodes (postal_code, city) VALUES (6752, 'Glejbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (6760, 'Ribe');
INSERT INTO PostalCodes (postal_code, city) VALUES (6771, 'Gredstedbro');
INSERT INTO PostalCodes (postal_code, city) VALUES (6780, 'Skærbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (6792, 'Rømø');
INSERT INTO PostalCodes (postal_code, city) VALUES (6800, 'Varde');
INSERT INTO PostalCodes (postal_code, city) VALUES (6818, 'Årre');
INSERT INTO PostalCodes (postal_code, city) VALUES (6823, 'Ansager');
INSERT INTO PostalCodes (postal_code, city) VALUES (6830, 'Nørre Nebel');
INSERT INTO PostalCodes (postal_code, city) VALUES (6840, 'Oksbøl');
INSERT INTO PostalCodes (postal_code, city) VALUES (6851, 'Janderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6852, 'Billum');
INSERT INTO PostalCodes (postal_code, city) VALUES (6853, 'Vejers Strand');
INSERT INTO PostalCodes (postal_code, city) VALUES (6854, 'Henne');
INSERT INTO PostalCodes (postal_code, city) VALUES (6855, 'Outrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6857, 'Blåvand');
INSERT INTO PostalCodes (postal_code, city) VALUES (6862, 'Tistrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (6870, 'Ølgod');
INSERT INTO PostalCodes (postal_code, city) VALUES (6880, 'Tarm');
INSERT INTO PostalCodes (postal_code, city) VALUES (6893, 'Hemmet');
INSERT INTO PostalCodes (postal_code, city) VALUES (6900, 'Skjern');
INSERT INTO PostalCodes (postal_code, city) VALUES (6920, 'Videbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (6933, 'Kibæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (6940, 'Lem St');
INSERT INTO PostalCodes (postal_code, city) VALUES (6950, 'Ringkøbing');
INSERT INTO PostalCodes (postal_code, city) VALUES (6960, 'Hvide Sande');
INSERT INTO PostalCodes (postal_code, city) VALUES (6971, 'Spjald');
INSERT INTO PostalCodes (postal_code, city) VALUES (6973, 'Ørnhøj');
INSERT INTO PostalCodes (postal_code, city) VALUES (6980, 'Tim');
INSERT INTO PostalCodes (postal_code, city) VALUES (6990, 'Ulfborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (7000, 'Fredericia');
INSERT INTO PostalCodes (postal_code, city) VALUES (7080, 'Børkop');
INSERT INTO PostalCodes (postal_code, city) VALUES (7100, 'Vejle');
INSERT INTO PostalCodes (postal_code, city) VALUES (7120, 'Vejle Øst');
INSERT INTO PostalCodes (postal_code, city) VALUES (7130, 'Juelsminde');
INSERT INTO PostalCodes (postal_code, city) VALUES (7140, 'Stouby');
INSERT INTO PostalCodes (postal_code, city) VALUES (7150, 'Barrit');
INSERT INTO PostalCodes (postal_code, city) VALUES (7160, 'Tørring');
INSERT INTO PostalCodes (postal_code, city) VALUES (7171, 'Uldum');
INSERT INTO PostalCodes (postal_code, city) VALUES (7173, 'Vonge');
INSERT INTO PostalCodes (postal_code, city) VALUES (7182, 'Bredsten');
INSERT INTO PostalCodes (postal_code, city) VALUES (7183, 'Randbøl');
INSERT INTO PostalCodes (postal_code, city) VALUES (7184, 'Vandel');
INSERT INTO PostalCodes (postal_code, city) VALUES (7190, 'Billund');
INSERT INTO PostalCodes (postal_code, city) VALUES (7200, 'Grindsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (7250, 'Hejnsvig');
INSERT INTO PostalCodes (postal_code, city) VALUES (7260, 'Sønder Omme');
INSERT INTO PostalCodes (postal_code, city) VALUES (7270, 'Stakroge');
INSERT INTO PostalCodes (postal_code, city) VALUES (7280, 'Sønder Felding');
INSERT INTO PostalCodes (postal_code, city) VALUES (7300, 'Jelling');
INSERT INTO PostalCodes (postal_code, city) VALUES (7321, 'Gadbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (7323, 'Give');
INSERT INTO PostalCodes (postal_code, city) VALUES (7330, 'Brande');
INSERT INTO PostalCodes (postal_code, city) VALUES (7361, 'Ejstrupholm');
INSERT INTO PostalCodes (postal_code, city) VALUES (7362, 'Hampen');
INSERT INTO PostalCodes (postal_code, city) VALUES (7400, 'Herning');
INSERT INTO PostalCodes (postal_code, city) VALUES (7430, 'Ikast');
INSERT INTO PostalCodes (postal_code, city) VALUES (7441, 'Bording');
INSERT INTO PostalCodes (postal_code, city) VALUES (7442, 'Engesvang');
INSERT INTO PostalCodes (postal_code, city) VALUES (7451, 'Sunds');
INSERT INTO PostalCodes (postal_code, city) VALUES (7470, 'Karup J');
INSERT INTO PostalCodes (postal_code, city) VALUES (7480, 'Vildbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (7490, 'Aulum');
INSERT INTO PostalCodes (postal_code, city) VALUES (7500, 'Holstebro');
INSERT INTO PostalCodes (postal_code, city) VALUES (7540, 'Haderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (7550, 'Sørvad');
INSERT INTO PostalCodes (postal_code, city) VALUES (7560, 'Hjerm');
INSERT INTO PostalCodes (postal_code, city) VALUES (7570, 'Vemb');
INSERT INTO PostalCodes (postal_code, city) VALUES (7600, 'Struer');
INSERT INTO PostalCodes (postal_code, city) VALUES (7620, 'Lemvig');
INSERT INTO PostalCodes (postal_code, city) VALUES (7650, 'Bøvlingbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (7660, 'Bækmarksbro');
INSERT INTO PostalCodes (postal_code, city) VALUES (7673, 'Harboøre');
INSERT INTO PostalCodes (postal_code, city) VALUES (7680, 'Thyborøn');
INSERT INTO PostalCodes (postal_code, city) VALUES (7700, 'Thisted');
INSERT INTO PostalCodes (postal_code, city) VALUES (7730, 'Hanstholm');
INSERT INTO PostalCodes (postal_code, city) VALUES (7741, 'Frøstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (7742, 'Vesløs');
INSERT INTO PostalCodes (postal_code, city) VALUES (7752, 'Snedsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (7755, 'Bedsted Thy');
INSERT INTO PostalCodes (postal_code, city) VALUES (7760, 'Hurup Thy');
INSERT INTO PostalCodes (postal_code, city) VALUES (7770, 'Vestervig');
INSERT INTO PostalCodes (postal_code, city) VALUES (7790, 'Thyholm');
INSERT INTO PostalCodes (postal_code, city) VALUES (7800, 'Skive');
INSERT INTO PostalCodes (postal_code, city) VALUES (7830, 'Vinderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (7840, 'Højslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (7850, 'Stoholm, Jylland');
INSERT INTO PostalCodes (postal_code, city) VALUES (7860, 'Spøttrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (7870, 'Roslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (7884, 'Fur');
INSERT INTO PostalCodes (postal_code, city) VALUES (7900, 'Nykøbing M');
INSERT INTO PostalCodes (postal_code, city) VALUES (7950, 'Erslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (7960, 'Karby');
INSERT INTO PostalCodes (postal_code, city) VALUES (7970, 'Redsted M');
INSERT INTO PostalCodes (postal_code, city) VALUES (7980, 'Vils');
INSERT INTO PostalCodes (postal_code, city) VALUES (7990, 'Øster Assels');
INSERT INTO PostalCodes (postal_code, city) VALUES (8000, 'Århus C');
INSERT INTO PostalCodes (postal_code, city) VALUES (8200, 'Århus N');
INSERT INTO PostalCodes (postal_code, city) VALUES (8210, 'Århus V');
INSERT INTO PostalCodes (postal_code, city) VALUES (8220, 'Brabrand');
INSERT INTO PostalCodes (postal_code, city) VALUES (8230, 'Åbyhøj');
INSERT INTO PostalCodes (postal_code, city) VALUES (8240, 'Risskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (8250, 'Egå');
INSERT INTO PostalCodes (postal_code, city) VALUES (8260, 'Viby J');
INSERT INTO PostalCodes (postal_code, city) VALUES (8270, 'Højbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8300, 'Odder');
INSERT INTO PostalCodes (postal_code, city) VALUES (8305, 'Samsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (8310, 'Tranbjerg J');
INSERT INTO PostalCodes (postal_code, city) VALUES (8320, 'Mårslet');
INSERT INTO PostalCodes (postal_code, city) VALUES (8330, 'Beder');
INSERT INTO PostalCodes (postal_code, city) VALUES (8340, 'Malling');
INSERT INTO PostalCodes (postal_code, city) VALUES (8350, 'Hundslund');
INSERT INTO PostalCodes (postal_code, city) VALUES (8355, 'Solbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8361, 'Hasselager');
INSERT INTO PostalCodes (postal_code, city) VALUES (8362, 'Hørning');
INSERT INTO PostalCodes (postal_code, city) VALUES (8370, 'Hadsten');
INSERT INTO PostalCodes (postal_code, city) VALUES (8380, 'Trige');
INSERT INTO PostalCodes (postal_code, city) VALUES (8381, 'Tilst');
INSERT INTO PostalCodes (postal_code, city) VALUES (8382, 'Hinnerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8400, 'Ebeltoft');
INSERT INTO PostalCodes (postal_code, city) VALUES (8410, 'Rønde');
INSERT INTO PostalCodes (postal_code, city) VALUES (8420, 'Knebel');
INSERT INTO PostalCodes (postal_code, city) VALUES (8444, 'Balle');
INSERT INTO PostalCodes (postal_code, city) VALUES (8450, 'Hammel');
INSERT INTO PostalCodes (postal_code, city) VALUES (8462, 'Harlev J');
INSERT INTO PostalCodes (postal_code, city) VALUES (8464, 'Galten');
INSERT INTO PostalCodes (postal_code, city) VALUES (8471, 'Sabro');
INSERT INTO PostalCodes (postal_code, city) VALUES (8472, 'Sporup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8500, 'Grenaa');
INSERT INTO PostalCodes (postal_code, city) VALUES (8520, 'Lystrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8530, 'Hjortshøj');
INSERT INTO PostalCodes (postal_code, city) VALUES (8541, 'Skødstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8543, 'Hornslet');
INSERT INTO PostalCodes (postal_code, city) VALUES (8544, 'Mørke');
INSERT INTO PostalCodes (postal_code, city) VALUES (8550, 'Ryomgård');
INSERT INTO PostalCodes (postal_code, city) VALUES (8560, 'Kolind');
INSERT INTO PostalCodes (postal_code, city) VALUES (8570, 'Trustrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8581, 'Nimtofte');
INSERT INTO PostalCodes (postal_code, city) VALUES (8585, 'Glesborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8586, 'Ørum Djurs');
INSERT INTO PostalCodes (postal_code, city) VALUES (8592, 'Anholt');
INSERT INTO PostalCodes (postal_code, city) VALUES (8600, 'Silkeborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8620, 'Kjellerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8632, 'Lemming');
INSERT INTO PostalCodes (postal_code, city) VALUES (8641, 'Sorring');
INSERT INTO PostalCodes (postal_code, city) VALUES (8643, 'Ans By');
INSERT INTO PostalCodes (postal_code, city) VALUES (8653, 'Them');
INSERT INTO PostalCodes (postal_code, city) VALUES (8654, 'Bryrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8660, 'Skanderborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8670, 'Låsby');
INSERT INTO PostalCodes (postal_code, city) VALUES (8680, 'Ry');
INSERT INTO PostalCodes (postal_code, city) VALUES (8700, 'Horsens');
INSERT INTO PostalCodes (postal_code, city) VALUES (8721, 'Daugård');
INSERT INTO PostalCodes (postal_code, city) VALUES (8722, 'Hedensted');
INSERT INTO PostalCodes (postal_code, city) VALUES (8723, 'Løsning');
INSERT INTO PostalCodes (postal_code, city) VALUES (8732, 'Hovedgård');
INSERT INTO PostalCodes (postal_code, city) VALUES (8740, 'Brædstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8751, 'Gedved');
INSERT INTO PostalCodes (postal_code, city) VALUES (8752, 'Østbirk');
INSERT INTO PostalCodes (postal_code, city) VALUES (8762, 'Flemming');
INSERT INTO PostalCodes (postal_code, city) VALUES (8763, 'Rask Mølle');
INSERT INTO PostalCodes (postal_code, city) VALUES (8765, 'Klovborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8766, 'Nørre Snede');
INSERT INTO PostalCodes (postal_code, city) VALUES (8781, 'Stenderup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8783, 'Hornsyld');
INSERT INTO PostalCodes (postal_code, city) VALUES (8800, 'Viborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (8830, 'Tjele');
INSERT INTO PostalCodes (postal_code, city) VALUES (8831, 'Løgstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8832, 'Skals');
INSERT INTO PostalCodes (postal_code, city) VALUES (8840, 'Rødkærsbro');
INSERT INTO PostalCodes (postal_code, city) VALUES (8850, 'Bjerringbro');
INSERT INTO PostalCodes (postal_code, city) VALUES (8860, 'Ulstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8870, 'Langå');
INSERT INTO PostalCodes (postal_code, city) VALUES (8881, 'Thorsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (8882, 'Fårvang');
INSERT INTO PostalCodes (postal_code, city) VALUES (8883, 'Gjern');
INSERT INTO PostalCodes (postal_code, city) VALUES (8900, 'Randers');
INSERT INTO PostalCodes (postal_code, city) VALUES (8950, 'Ørsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (8961, 'Allingåbro');
INSERT INTO PostalCodes (postal_code, city) VALUES (8963, 'Auning');
INSERT INTO PostalCodes (postal_code, city) VALUES (8970, 'Havndal');
INSERT INTO PostalCodes (postal_code, city) VALUES (8981, 'Spenstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (8983, 'Gjerlev J');
INSERT INTO PostalCodes (postal_code, city) VALUES (8990, 'Fårup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9000, 'Aalborg');
INSERT INTO PostalCodes (postal_code, city) VALUES (9200, 'Aalborg SV');
INSERT INTO PostalCodes (postal_code, city) VALUES (9210, 'Aalborg SØ');
INSERT INTO PostalCodes (postal_code, city) VALUES (9220, 'Aalborg Øst');
INSERT INTO PostalCodes (postal_code, city) VALUES (9230, 'Svenstrup J');
INSERT INTO PostalCodes (postal_code, city) VALUES (9240, 'Nibe');
INSERT INTO PostalCodes (postal_code, city) VALUES (9260, 'Gistrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9270, 'Klarup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9280, 'Storvorde');
INSERT INTO PostalCodes (postal_code, city) VALUES (9293, 'Kongerslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (9300, 'Sæby');
INSERT INTO PostalCodes (postal_code, city) VALUES (9310, 'Vodskov');
INSERT INTO PostalCodes (postal_code, city) VALUES (9320, 'Hjallerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9330, 'Dronninglund');
INSERT INTO PostalCodes (postal_code, city) VALUES (9340, 'Asaa');
INSERT INTO PostalCodes (postal_code, city) VALUES (9352, 'Dybvad');
INSERT INTO PostalCodes (postal_code, city) VALUES (9362, 'Gandrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9370, 'Hals');
INSERT INTO PostalCodes (postal_code, city) VALUES (9380, 'Vestbjerg');
INSERT INTO PostalCodes (postal_code, city) VALUES (9381, 'Sulsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (9382, 'Tylstrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9400, 'Nørresundby');
INSERT INTO PostalCodes (postal_code, city) VALUES (9430, 'Vadum');
INSERT INTO PostalCodes (postal_code, city) VALUES (9440, 'Aabybro');
INSERT INTO PostalCodes (postal_code, city) VALUES (9460, 'Brovst');
INSERT INTO PostalCodes (postal_code, city) VALUES (9480, 'Løkken');
INSERT INTO PostalCodes (postal_code, city) VALUES (9490, 'Pandrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9492, 'Blokhus');
INSERT INTO PostalCodes (postal_code, city) VALUES (9493, 'Saltum');
INSERT INTO PostalCodes (postal_code, city) VALUES (9500, 'Hobro');
INSERT INTO PostalCodes (postal_code, city) VALUES (9510, 'Arden');
INSERT INTO PostalCodes (postal_code, city) VALUES (9520, 'Skørping');
INSERT INTO PostalCodes (postal_code, city) VALUES (9530, 'Støvring');
INSERT INTO PostalCodes (postal_code, city) VALUES (9541, 'Suldrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9550, 'Mariager');
INSERT INTO PostalCodes (postal_code, city) VALUES (9560, 'Hadsund');
INSERT INTO PostalCodes (postal_code, city) VALUES (9574, 'Bælum');
INSERT INTO PostalCodes (postal_code, city) VALUES (9575, 'Terndrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9600, 'Aars');
INSERT INTO PostalCodes (postal_code, city) VALUES (9610, 'Nørager');
INSERT INTO PostalCodes (postal_code, city) VALUES (9620, 'Aalestrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9631, 'Gedsted');
INSERT INTO PostalCodes (postal_code, city) VALUES (9632, 'Møldrup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9640, 'Farsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (9670, 'Løgstør');
INSERT INTO PostalCodes (postal_code, city) VALUES (9681, 'Ranum');
INSERT INTO PostalCodes (postal_code, city) VALUES (9690, 'Fjerritslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (9700, 'Brønderslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (9740, 'Jerslev J');
INSERT INTO PostalCodes (postal_code, city) VALUES (9750, 'Øster Vrå');
INSERT INTO PostalCodes (postal_code, city) VALUES (9760, 'Vrå');
INSERT INTO PostalCodes (postal_code, city) VALUES (9800, 'Hjørring');
INSERT INTO PostalCodes (postal_code, city) VALUES (9830, 'Tårs');
INSERT INTO PostalCodes (postal_code, city) VALUES (9850, 'Hirtshals');
INSERT INTO PostalCodes (postal_code, city) VALUES (9870, 'Sindal');
INSERT INTO PostalCodes (postal_code, city) VALUES (9881, 'Bindslev');
INSERT INTO PostalCodes (postal_code, city) VALUES (9900, 'Frederikshavn');
INSERT INTO PostalCodes (postal_code, city) VALUES (9940, 'Læsø');
INSERT INTO PostalCodes (postal_code, city) VALUES (9970, 'Strandby');
INSERT INTO PostalCodes (postal_code, city) VALUES (9981, 'Jerup');
INSERT INTO PostalCodes (postal_code, city) VALUES (9982, 'Ålbæk');
INSERT INTO PostalCodes (postal_code, city) VALUES (9990, 'Skagen');