-- Localhost DB Setup
DROP DATABASE IF EXISTS CinemaDB;
CREATE DATABASE CinemaDB;

USE CinemaDB;

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
--  DROP TABLE IF EXISTS Showtimes;
-- DROP TABLE IF EXISTS Tickets;
-- DROP TABLE IF EXISTS Will_have;

-- Default the engine was MyISAM which does not show relations in phpmyadmin.
-- This line sets the default to InnoDB as that allows for visible relations
SET default_storage_engine=innoDB;

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




-- SQL VIEWS --
CREATE VIEW view_showtimes_with_movie_info AS
SELECT
    s.Showtime_id,
    s.show_date,
    s.show_time,
    h.hall_name,
    m.title AS movie_title,
    m.movie_length,
    m.rating,
    m.genre,
    m.poster
FROM Showtimes s
         JOIN Movies m ON s.movie_id = m.movie_id
         JOIN Halls h ON s.hall_id = h.hall_id;

CREATE VIEW view_booked_seats AS
SELECT
    t.ticket_id,
    t.Showtime_id,
    w.seat_id,
    s.seat_name,
    u.user_id,
    u.first_name,
    u.last_name
FROM Tickets t
         JOIN Will_have w ON t.ticket_id = w.ticket_id
         JOIN Seats s ON w.seat_id = s.seat_id
         JOIN Users u ON t.user_id = u.user_id;





-- TRIGGERS --
-- prevent_duplicate_seat:
-- This trigger should help prevent double booking in case PHP fails to catch it
-- This trigger is very useful in case multiple users try to place similar bookings at the same time
DELIMITER $$
CREATE TRIGGER prevent_duplicate_seat
    BEFORE INSERT ON Will_have
    FOR EACH ROW
BEGIN
    DECLARE existingCount INT;

    SELECT COUNT(*)
    INTO existingCount
    FROM Will_have wh
             JOIN Tickets t ON wh.ticket_id = t.ticket_id
    WHERE wh.seat_id = NEW.seat_id
      AND t.Showtime_id = (
        SELECT Showtime_id
        FROM Tickets
        WHERE ticket_id = NEW.ticket_id
    );

    IF existingCount > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'This seat is already booked for this showtime.';
END IF;
END$$
DELIMITER ;

-- auto_null_rating
-- This trigger makes sure that if the movie hasn't yet been released it will have no rating
-- This is very important as the admin could accidentally insert a false rating
DELIMITER $$
CREATE TRIGGER auto_null_rating
BEFORE INSERT ON Movies
FOR EACH ROW
BEGIN
IF NEW.debut_date > CURDATE() THEN
    SET NEW.rating = NULL;
    END IF;
END$$
DELIMITER ;





-- ADMIN DATA --
-- There need to be an existing Admin to create an admin.
-- An Admin have been created so that:
--      1. Teachers can access the panel.
--      2. I do not have to change code to access admin on Simply.com
INSERT INTO Admins (`admin_id`, `first_name`, `last_name`, `username`, `admin_password`) VALUES
(1, 'Ricki', 'Guldborg', 'TheYori', '$2y$11$TRXq4iO32vh2QRVE2Imty.7ZWX2vIOnCNwThpozG9EL/Vs1I4eTry');

-- COMPANY DATA --
-- All of this data was inserted through the admin panel.
-- The SQL "INSERT" comes for phpmyadmin and has ONLY been added to this file to show off the 60-80% product with ease
INSERT INTO Company (`key_id`, `data_key`, `key_value`) VALUES
(1, 'Company Phone number:', '+45 29 88 10 07'),
(2, 'Company Name:', 'Midnight Scream'),
(3, 'Company Email:', 'rickiguldbog40@gmail.com'),
(4, 'Opening Hours:', '15:00 - 02:00'),
(5, 'Midnight Scream – Where Horror Never Sleeps', 'Welcome to Midnight Scream, a small but fiercely passionate horror cinema devoted to everything that goes bump in the night. With just two intimate screening halls and a single location, Midnight Scream is more than a movie theater — it’s a sanctuary for horror lovers, filmmakers, and the creatively twisted minds who keep the genre alive.\r\n\r\nMidnight Scream is dedicated entirely to horror in all its forms — from cult classics and B-movie gems to independent terrors and experimental short films. Whether it’s a blood-soaked slasher, a chilling psychological thriller, or a delightfully cheesy monster flick, if it makes your pulse race, it belongs on our screens.\r\n\r\nOur vision is to create a community-driven hub for horror enthusiasts — a place where fans can not only watch their favorite films but also share their own. We proudly open our doors to amateur filmmakers, allowing them to screen their creations, even those still in progress, before an audience of true horror fans. It’s a place for feedback, collaboration, and inspiration — because great horror deserves to grow in the dark.\r\n\r\nBut Midnight Scream isn’t just about watching movies — it’s about celebrating horror culture. We host quiz nights, writing groups, themed events, and community gatherings that bring horror fans together in laughter, creativity, and maybe a few nervous screams. Our goal is to build a space that feels like home to the misfits, the dreamers, and the storytellers who find beauty in fear.\r\n\r\nOur Values\r\n- Community Through Fear: Horror is best shared. We unite fans and creators through shared experiences, discussions, and chills.\r\n- A Stage for New Voices: We champion independent and amateur filmmakers by offering a platform to showcase both completed and unfinished works.\r\n- Diversity of Terror: From low-budget cult films to polished thrillers — every corner of horror has a place at Midnight Scream.\r\n- Creative Freedom: We embrace experimentation and originality, encouraging creators to push boundaries and reimagine the horror genre.\r\n- Atmosphere is Everything: Every detail, from the lighting to the décor, is crafted to immerse visitors in a world of eerie elegance and cinematic suspense.\r\n\r\nAt Midnight Scream, horror isn’t just entertainment — it’s a shared passion, an art form, and a lifestyle. Whether you come to be terrified, to show your work, or simply to meet others who understand your love for the strange and the scary, you’ll always have a place in the dark with us.'),
(6, 'Company Address:', 'Nørregade 22\r\n6650 Brørup'),
(10, 'Welcome to Midnight Scream', 'Step into the dark and take a seat — you’ve found your new home for horror.\r\n\r\nMidnight Scream is a small, independent cinema devoted entirely to the art of fear. From cult classics and B-movie gems to experimental indie horrors, we celebrate every scream, shiver, and shadow that makes the genre unforgettable. But we’re more than a cinema — we’re a community. A place for horror fans, filmmakers, and curious souls to gather, share ideas, and experience the thrill together. We host movie nights, creative events, and eerie celebrations that explore the beauty of the macabre in all its forms.\r\n\r\nSo grab some popcorn, silence your phone, and embrace the darkness. At Midnight Scream, horror never sleeps — and neither will your imagination.');


-- HALL DATA --
-- As the halls will not change in this project they have been added in here
-- CRUD via admin back-end panel does not take priority
INSERT INTO Halls (hall_name) VALUES ('Grimm Hall');
INSERT INTO Halls (hall_name) VALUES ('Wendigo Den');


INSERT INTO Seats (seat_name, hall_id) VALUES
-- Hall 1
('Seat A1-1', 1), ('Seat A1-2', 1), ('Seat A1-3', 1), ('Seat A1-4', 1),
('Seat A1-5', 1), ('Seat A1-6', 1), ('Seat A1-7', 1), ('Seat A1-8', 1),
('Seat A2-1', 1), ('Seat A2-2', 1), ('Seat A2-3', 1), ('Seat A2-4', 1),
('Seat A2-5', 1), ('Seat A2-6', 1), ('Seat A2-7', 1), ('Seat A2-8', 1),
('Seat A3-1', 1), ('Seat A3-2', 1), ('Seat A3-3', 1), ('Seat A3-4', 1),
('Seat A3-5', 1), ('Seat A3-6', 1), ('Seat A3-7', 1), ('Seat A3-8', 1),
('Seat A4-1', 1), ('Seat A4-2', 1), ('Seat A4-3', 1), ('Seat A4-4', 1),
('Seat A4-5', 1), ('Seat A4-6', 1), ('Seat A4-7', 1), ('Seat A4-8', 1),

-- Hall 2
('Seat B1-1', 2), ('Seat B1-2', 2), ('Seat B1-3', 2),
('Seat B1-4', 2), ('Seat B1-5', 2), ('Seat B1-6', 2),
('Seat B2-1', 2), ('Seat B2-2', 2), ('Seat B2-3', 2),
('Seat B2-4', 2), ('Seat B2-5', 2), ('Seat B2-6', 2),
('Seat B3-1', 2), ('Seat B3-2', 2), ('Seat B3-3', 2),
('Seat B3-4', 2), ('Seat B3-5', 2), ('Seat B3-6', 2),
('Seat B4-1', 2), ('Seat B4-2', 2), ('Seat B4-3', 2),
('Seat B4-4', 2), ('Seat B4-5', 2), ('Seat B4-6', 2);

-- MOVIE DATA --
-- All of this data was inserted through the admin panel.
-- The SQL "INSERT" comes for phpmyadmin and has ONLY been added to this file to show off the 60-80% product with ease
INSERT INTO Movies (`movie_id`, `title`, `movie_length`, `debut_date`, `rating`, `director`, `genre`, `movie_desc`, `poster`, `hall_id`) VALUES
(1, 'Alien', 117, '1979-05-24', 8.5, 'Ridley Scott', 'Horror - Science Fiction', 'The commercial space tug Nostromo is returning to Earth with a seven-member crew in &quot;stasis&quot;: captain Dallas, executive officer Kane, warrant officer Ripley, navigator Lambert, science officer Ash, and engineers Parker and Brett, along with the ship cat, Jones. The ship&#039;s computer, &quot;Mother&quot;, detects a transmission from a nearby planetoid and wakes the crew. Following company policy to investigate transmissions indicating intelligent life, they land on the surface, but the ship is damaged. Dallas, Kane, and Lambert discover the transmission comes from a derelict alien vessel. Inside is a giant, fossilised alien corpse with a hole in its torso. Meanwhile, Mother partially deciphers the transmission, which Ripley determines is a warning beacon and not an SOS as first thought.', '69012bc7cbff5-alien_1979_french_grande_original_film_art_5000x.png', NULL),
(2, 'Aliens', 137, '1986-10-31', 8.4, 'Ridley Scott', 'Horror - Science Fiction', 'Ellen Ripley has been in stasis for 57 years aboard a shuttlecraft after destroying her spaceship, the Nostromo, to escape an alien creature that slaughtered her crew. Ripley is rescued and debriefed by her Weyland-Yutani Corporation employers who doubt her claim about alien eggs in a derelict ship on the exomoon LV-426, now the site of a terraforming colony. After contact is lost with the colony, Weyland-Yutani representative Carter Burke and Colonial Marine Lieutenant Gorman ask Ripley to accompany them to investigate. Still traumatized by her alien encounter, she agrees on the condition that they exterminate the creatures. Ripley meets the Colonial Marines aboard the spaceship Sulaco but distrusts their android, Bishop, because the Nostromo&#039;s android, Ash, had betrayed its crew to protect the alien on company orders.', '69012c1bd59b7-aliens_1986_french_original_film_art_5000x.png', NULL),
(3, 'Blair Witch', 89, '2016-06-22', 5, 'Adam Wingard', 'Horror - Supernatural', 'In 2014, James Donahue finds a video on YouTube containing an image of a woman he believes to be his sister Heather, who disappeared in 1994 near Burkittsville, Maryland, while investigating the legend of the Blair Witch. Wanting to find out the truth, he travels to the woods with friend Peter Jones, Peter&#039;s girlfriend Ashley Bennett and film student Lisa Arlington, who wants to film James&#039; search as a documentary, The Absence of Closure. Locals Talia and Lane, who had uploaded the video to YouTube, say they will show the group the location of the tape only if they can join.', '69012c5ce54a0-BlairWitch_2016_teaser_original_film_art_5000x.png', NULL),
(4, 'Critters', 86, '1986-04-11', 6.1, 'Stephen Herek', 'Horror - Comedy', 'On an asteroid prison, a group of dangerous aliens known as Krites are set to be transported to another station. The Krites engineer an escape and hijack a ship, prompting the warden to hire two shape-changing bounty hunters, Ug and Lee, to pursue them to Earth. Studying life on Earth via various satellite television transmissions, Ug assumes the form of rock star Johnny Steele, while Lee remains undecided, thus retaining his blank, featureless head. On a rural Kansas farm, the Brown family sits down to breakfast. Father Jay and mother Helen send teenage daughter April and younger son Brad off to school while waiting on mechanic Charlie McFadden. A former baseball pitcher, Charlie has become the town drunk and crackpot, with claims of alien abductions foretold by messages through his fillings.', '69012caf3932d-critters_1986_style_A_original_film_art_5000x.png', NULL),
(5, 'Cujo', 93, '1983-08-12', 6.1, 'Lewis Teague', 'Horror - Thriller', 'Cujo, a friendly and easygoing St. Bernard, chases a wild rabbit and inserts his head into a cave, where a rabid bat bites him on the nose. The Trenton family—advertising executive Vic, housewife Donna, and young son Tad—take their car to the rural home of abusive mechanic Joe Camber for repairs, where they meet Cujo, the Camber family&#039;s pet, and get along well with him. Vic and Donna&#039;s marriage is tested when Vic learns that Donna had been having an affair with her ex-boyfriend from high school, Steve Kemp. The early signs of Cujo&#039;s infection start to appear, though no one notices. Joe Camber&#039;s wife Charity and his son Brett decide to leave for a week to visit Charity&#039;s sister. The furious stage of Cujo&#039;s infection sets in. Cujo refrains from attacking Brett but goes completely mad and kills the Cambers&#039; alcoholic neighbor Gary. He then mauls Joe to death.', '69012d214ca4e-cujo_1983_original_film_art_5000x.png', NULL),
(6, 'Friday the 13th', 95, '1980-05-09', 6.4, 'Sean S. Cunnigham', 'Horror - Slasher', 'In 1958 at Camp Crystal Lake, camp counsellors Barry and Claudette sneak away from other counsellors for a late-night rendezvous in a supply shed. An unseen assailant attacks and murders them. On Friday, June 13, 1979, camp counsellor and cook Annie hitchhikes with truck driver Enos toward the reopened Camp Crystal Lake. Enos warns her about the camp&#039;s troubled past, beginning when a young boy drowned in Crystal Lake in 1957. After Enos drops Annie off at a crossroads, she hitches another ride from an unseen person driving a Jeep. After the driver passes the camp entrance, Annie becomes fearful and leaps from the vehicle, fleeing into the woods where the driver eventually slashes her throat.', '69012da2cfb5b-friday_the_13th_1980_intl_linen_original_film_art_5000x.png', NULL),
(7, 'Poltergeist', 114, '1982-06-04', 7.3, 'Tobe Hooper', 'Horror - Supernatural', 'Steven and Diane Freeling live in the planned community of Cuesta Verde, California. Steven is a successful real estate agent, and Diane looks after their three children: sixteen-year-old Dana, eight-year-old Robbie, and a five-year-old Carol Anne. One night, Carol Anne inexplicably converses with the television set while it displays post-broadcast static. The next night, she fixates on the television again, and a ghostly white hand emerges from the screen, followed by a violent earthquake. As the family is shaken awake by the quake, Carol Anne eerily intones, &quot;They&#039;re here.&quot; The following day is filled with bizarre events: a glass of milk spontaneously breaks, silverware bends, and furniture moves on its own. These phenomena initially seem benign, but soon grow sinister.', '69012de993c1e-Poltergeist_1982_french_original_film_art_5000x.png', NULL),
(8, 'Psycho', 109, '1961-05-01', 8.5, 'Alfred Hitchcock', 'Horror - Psychological', 'Marion Crane, a real estate secretary in Phoenix, steals $40,000 in cash from her employer after hearing her boyfriend, Sam Loomis, complain that his debts are delaying their marriage. She sets off to drive to Sam&#039;s home in the town of Fairvale, California, switching cars in Bakersfield after an encounter with a suspicious policeman. A heavy rainstorm forces Marion to stop at the secluded Bates Motel just a few miles from Fairvale. Norman Bates, the proprietor, whose Second Empire style house overlooks the motel, registers Marion (who uses an alias) and invites her to dinner with him in the motel&#039;s office. When Norman returns to his house to retrieve the food, Marion overhears him arguing with his mother about his desire to dine with Marion.', '69012e2d26b98-psycho_R65_linen_original_film_art_328af3eb-eb2b-4630-aeda-058a852ef35e_5000x.png', NULL),
(9, 'The Blob', 86, '1959-10-16', 6.7, 'Irvin S. Yeaworth Jr.', 'Horror - Science Fiction', 'In a small Pennsylvania town in July 1957, teenager Steve Andrews and his girlfriend Jane Martin kiss at a lovers&#039; lane when they see a meteorite crash beyond the next hill. Steve goes looking for it but Barney, an old man living nearby, finds it first. When he pokes the meteorite with a stick, it breaks open and a small jelly-like globule blob inside attaches itself to his hand. In pain and unable to scrape or shake it loose, Barney runs onto the road, where he is nearly struck by Steve&#039;s car. Steve and Jane take him to Doctor Hallen. Doctor Hallen anesthetizes the man and sends Steve and Jane back to locate the impact site and gather information. Hallen decides he must amputate the man&#039;s arm since it is being phagocytosed. Before he can, the Blob completely absorbs Barney, then Hallen&#039;s nurse Kate, and finally the doctor himself, growing redder and larger with each victim.', '690130718cd51-the_blob_1958_linen_original_film_art_5000x 1.png', NULL),
(10, 'The Living Dead', 76, '1934-01-03', 5.1, 'Thomas Bentley', 'Horror - Mystery', '(1934) Gerald du Maurier, George Curzon, Grete Natzler, Belle Chrystall, Leslie Perrins, Henry Victor.  Curzon is great as the suave yet crazed doctor who has devised a formula that puts people into a death-like trance so he can then claim their life insurance.  Scotland Yard commissioner Du Maurier investigates but fails to realize than his own medical adviser (also Curzon) is the criminal mastermind behind this nefarious plot.  Similar in some ways to Lugosi’s Dark Eyes of London.  Not to be confused with the Paul Wegener film from 1932.  From a beautiful 16mm print.', '690130d0550af-scotland_yard_mystery_1934_linen_original_film_art_5000x.png', NULL),
(11, 'The Thing', 109, '1982-06-25', 8.2, 'John Carpenter', 'Horror - Science Fiction', 'In Antarctica, a Norwegian helicopter pursues a sled dog to an American research station. The Americans witness the passenger accidentally blow up the helicopter in addition to himself. The pilot fires a rifle and shouts at the Americans, but they cannot understand him, and he is shot dead in self-defence by station commander Garry. The American helicopter pilot, R. J. MacReady, and Dr. Copper leave to investigate the Norwegian base. Among the charred ruins and frozen corpses, they find the burnt corpse of a malformed humanoid, which they transfer to the American station. Their biologist, Blair, autopsies the remains and finds a normal set of human organs. Clark kennels the sled dog, and it soon metamorphoses and absorbs several of the station dogs. This disturbance alerts the team, and Childs uses a flamethrower to incinerate the creature.', '6901311226f09-the_thing_1982_linen_original_film_art_5000x.png', NULL),
(12, 'Texas Chainsaw Massacre', 98, '2003-10-17', 6.2, 'Marcus Nispel', 'Horror - Slasher', 'On August 18, 1973, five young adults – Erin Hardesty, her boyfriend Kemper, and their friends Morgan, Andy, and Pepper – are traveling to a concert after visiting Mexico to purchase marijuana. While driving through Texas, they pick up a traumatized hitchhiker walking in the middle of the road. She tells them they&#039;re going the wrong way and forces Kemper to stop the van. Speaking incoherently about &quot;a bad man&quot;, she commits suicide with a revolver hidden in her dress. The group finds a gas station hoping to contact the police. The store&#039;s proprietor, Luda Mae, tells them to meet Sheriff Hoyt at the Old Crawford Mill. Instead, they find Jedidiah, a young boy who says Hoyt is at home getting drunk. Erin and Kemper go through the woods to find his house, leaving Morgan, Andy, and Pepper at the mill with Jedidiah.', '69013145a6fbe-TexasChainsawMassacre_2003_original_film_art_7da27501-32a0-4f64-8795-9f0440641868_5000x.png', NULL);

-- NEWS DATA --
-- All of this data was inserted through the admin panel.
-- The SQL "INSERT" comes for phpmyadmin and has ONLY been added to this file to show off the 60-80% product with ease
INSERT INTO News (`news_id`, `release_date`, `title`, `content`, `banner_img`) VALUES
(7, '2025-10-31', 'The Hauntings begin at Midnight Scream - A safehouse for horror fans and creators', 'This week marks the long-awaited opening of Midnight Scream, a brand-new horror-themed cinema that promises to bring fear, fun, and community to the heart of the city. Small, humble, and filled with ambition, Midnight Scream is set to become the go-to gathering place for horror enthusiasts of every kind — from die-hard fans of cult classics to aspiring filmmakers ready to unleash their nightmares on the big screen.\r\n\r\nWith just two intimate screening halls, Midnight Scream embraces the eerie coziness of independent cinema while offering something few theaters dare to: a space entirely dedicated to horror. For now, the cinema will focus primarily on B-movies and cult favorites, celebrating the weird, the wild, and the wonderfully low-budget heart of the genre. Titles that might once have been forgotten are given new life here, projected in a space filled with the laughter, gasps, and applause of fellow horror lovers.\r\n\r\nBut for the founders of Midnight Scream, this is only the beginning. “We’re starting small,” says the management, “but our ambitions are anything but.” In time, the cinema plans to open its doors to amateur and first-time filmmakers, giving them a rare opportunity to showcase their work to a live audience. What’s more, these partnerships will allow filmmakers to earn money from their screenings, making Midnight Scream not only a platform for creativity but also a stepping stone into the horror film industry.\r\n\r\n“We want to create a space where new ideas and new voices in horror can be seen, heard, and celebrated,” the team explains. “There are so many passionate horror fans out there with incredible stories to tell — we want them to know they have a home here.”\r\n\r\nA Growing Hub for Horror and Creativity\r\n\r\nThough its focus is currently on film screenings, Midnight Scream has plans to expand into a true community hub for horror lovers. The team hopes to host events of all kinds — from writing workshops and art exhibitions to themed quiz nights and fan meetups. While the cinema is starting small, many of these early events will be free to attend, ensuring accessibility for everyone who shares a love for the genre.\r\n\r\nSome special events will include prizes or exclusive screenings, and the cinema promises that even as it grows, affordability and inclusion will remain top priorities. Midnight Scream’s mission is clear: to make horror not only entertaining, but welcoming — a genre that everyone can enjoy, regardless of who they are.\r\n\r\nSnacks, Not Scares, at the Counter\r\n\r\nNo trip to the movies is complete without snacks, and Midnight Scream’s snack bar is already earning praise for its fair and fan-friendly prices. Offering popcorn, candy, and classic drinks at prices far below the usual cinema mark-ups, the snack bar will be open at every screening and event.\r\n\r\nUnlike many cinemas, however, no alcohol is sold or allowed on the premises. This decision, the owners explain, is intentional: “We want Midnight Scream to be a place where everyone feels safe — whether you’re a young adult, neurodivergent, or someone in recovery. Horror can be intense, emotional, and communal — but it should never be made uncomfortable by someone else’s lowered inhibitions.”\r\n\r\nA Safe Space for All Horror Fans\r\n\r\nMore than anything, Midnight Scream is committed to being a safe and inclusive space. The cinema proudly identifies as an LGBTQIA+–friendly environment, where everyone is welcome to enjoy horror without judgment or discrimination. Any form of harassment or hate speech — whether based on gender, sexuality, race, religion, or any other identity — will result in an immediate and permanent ban from the premises.\r\n\r\n“Horror has always been a genre about facing fear,” the owners say. “But that fear should stay on the screen — not in our community. We want to make the horror experience safe, creative, and empowering for everyone.”\r\n\r\nThe Beginning of Something Beautifully Frightening\r\n\r\nWith its dark décor, cozy screening rooms, and passionate vision, Midnight Scream may be small — but it’s filled with big dreams. From the revival of forgotten classics to the debut of brand-new nightmares, this little cinema is poised to become a cornerstone of the horror fan community.\r\n\r\nSo, grab your popcorn, silence your phone, and prepare to scream — because Midnight Scream has arrived, and the night belongs to horror once more.\r\n\r\nMidnight Scream – Where Horror Never Sleeps.', '69051cfa3af2f-pexels-freestocks-211358.jpg'),
(8, '2025-11-01', 'Are Zombie Movies Really Horror? We Think So!', 'It’s a debate that’s haunted horror fans for decades:\r\nAre zombie movies really horror — or are they something else?\r\n\r\nSome say zombie films belong more to the action or apocalypse genre, filled with explosions, gunfire, and survival drama rather than raw terror. Others argue that the undead are pure horror — rotting reflections of our deepest fears about death, disease, and humanity itself. At Midnight Scream, we’ve heard all sides of the argument. There are those who claim modern zombie flicks like World War Z or Army of the Dead are too flashy, too fast, too... not-scary. They miss the eerie slowness and creeping dread of classics like Night of the Living Dead or Dawn of the Dead. And then there’s the philosophical camp — the ones who say zombie movies aren’t about monsters at all, but about us. About how people behave when civilization collapses. About survival, morality, and how quickly humans can turn into something just as monstrous as the undead.\r\n\r\nBut here’s the thing: at the end of the day, we at Midnight Scream don’t really care what anyone else calls them — because when the dead rise and the lights go out, we definitely find them scary! Whether it’s the slow shuffle of a corpse in a graveyard or a screaming horde breaking down a barricade, zombie movies have earned their place in our dark little hearts. Horror, action, drama — whatever you want to call it, it still makes our popcorn fly and our pulses race.\r\n\r\nBrains, Blood, and Bragging Rights: Our Upcoming Zombie Marathon!\r\n\r\nAnd because we love them so much, we’re planning something truly undeadly — a Zombie Movie Marathon at Midnight Scream!\r\n\r\nWe’ll screen some of the greatest (and goriest) zombie films ever made, from cult classics to modern gut-munching chaos. Between screenings, we’ll host zombie-themed quizzes where horror knowledge can win you real prizes. There’ll be rewards for the top 3 players in each hall — which means 6 winners total across both of our screening rooms! Think of it as your chance to prove your horror expertise and maybe take home some exclusive Midnight Scream horror swag. You’ll laugh, you’ll scream, and maybe — just maybe — you’ll start to wonder if that sound behind you in the dark was really just someone’s popcorn bucket.\r\n\r\nSo stay tuned for dates, grab your survival kits, and get ready to join the horde. Because at Midnight Scream, we don’t just watch zombie movies — we celebrate them.\r\n\r\nMidnight Scream – Where Horror Never Sleeps.', '69051f02e6c6e-pexels-cottonbro-5435455.jpg'),
(9, '2025-11-02', 'Should Hollywood Keep Adapting Horror Games? Let’s Talk About It!', 'In recent years, Hollywood has been digging deeper than ever into the gaming world — turning our favorite horror video games into big-screen experiences. From Resident Evil to Silent Hill, and more recently Five Nights at Freddy’s, the movie industry seems determined to prove that what scares us behind a controller can also terrify us in a cinema seat.\r\n\r\nBut here’s the question we’ve been asking ourselves here at Midnight Scream: Is adapting horror games into movies really something the film industry should keep investing in? On one hand, the appeal is obvious. Horror games already have strong fanbases, rich worlds, and built-in tension. The atmosphere is there. The monsters are terrifying. The stories are often cinematic in nature. It feels like an easy win. Yet, time and time again, something goes wrong. The adaptation falls flat. The scares don’t land. The characters feel hollow compared to their pixelated counterparts. Why does this keep happening?\r\n\r\nOne possible reason is that the film industry doesn’t always understand the gaming industry. Filmmakers trained in traditional storytelling sometimes struggle to grasp what makes a game so immersive — the interactivity, the pacing, the sense of player control. What terrifies you when you’re the one playing doesn’t always translate when you’re simply watching someone else survive. And then there’s the issue of creative control. Too often, game creators — the very people who built these chilling worlds — are sidelined once Hollywood steps in. Without their input, adaptations risk losing the soul of the source material. Perhaps the solution lies in collaboration. Imagine horror films where game writers, designers, and directors have a real say in how the story is told on screen. Where filmmakers don’t just adapt a game, but translate its atmosphere, pacing, and emotion. Maybe then we’d get adaptations that truly capture what made the original game so unforgettable. Because let’s be honest: the horror gaming world has already proven it knows how to scare us. Maybe it’s time the movie industry started taking notes.\r\n\r\nMidnight Scream’s Horror Game Week!\r\n\r\nTo put this debate to the test, we at Midnight Scream are dedicating an entire week to movie adaptations of horror games!\r\n\r\nFrom the haunted hospitals of Silent Hill to the bio-engineered nightmares of Resident Evil, and the jumpscare madness of Five Nights at Freddy’s, we’re screening the biggest — and boldest — attempts to bring gaming horror to life. During this week, you’ll get the chance to cast your own vote in our in-person fan poll. Tell us which adaptations nailed it and which ones should have stayed on the console. We’ll tally the results and share what our horror community really thinks!\r\n\r\nSpecial Offer: Tickets will be half off when booking seats using the code: HORRORGAMESAREAWESOME.\r\n\r\nSo come join the debate, grab some popcorn, and see for yourself whether the jump from joystick to film reel really works. Because at Midnight Scream, we don’t just watch horror — we question it, celebrate it, and sometimes, scream about it.\r\n\r\nMidnight Scream – Where Horror Never Sleeps.', '6905216136f83-pexels-unpoquitodefoto-28833650.jpg'),
(10, '2025-11-22', 'Do Amateur Horror Filmmakers Get a Fair Chance?', 'Let’s be honest — we all love a good Stephen King adaptation. From The Shining to It, the master of horror has given us decades of sleepless nights and unforgettable scenes. But lately, here at Midnight Scream, we’ve been asking ourselves a question that might make some studio executives squirm:\r\n\r\nDo amateur horror filmmakers ever get a fair chance?\r\n\r\nWhen you look at the big screen, so many horror movies are based on novels by already famous authors — the kind whose names alone can sell tickets. That’s not necessarily bad; after all, these stories are classics for a reason. But what about all the other writers and storytellers out there? The ones who write bone-chilling tales that never make it to the bestseller lists, yet have the power to haunt your dreams for weeks? And beyond the books — what about the filmmakers? The ones who shoot their first short film on borrowed cameras, who build monsters out of cardboard and corn syrup, who pour their hearts (and probably a few fake blood buckets) into their craft just for the love of it? These creators rarely get the spotlight they deserve. Too often, the film industry overlooks raw, passionate storytelling in favor of familiar names and big studio budgets. And that’s something we at Midnight Scream want to change.\r\n\r\n🎬 We Want to Give Every Horror Creator a Stage\r\n\r\nAt Midnight Scream, we believe that horror belongs to everyone. Whether you’re an industry veteran or someone who just finished editing your very first short film, we want to see your work on the big screen. It doesn’t matter if it’s your first, third, or even twentieth movie. It doesn’t matter if your film runs for five minutes or five hours (though, we may need a lot of popcorn for the latter). What matters is your passion, your vision, and your desire to share something that will make audiences shiver — in the best way possible. And if your movie is based on an obscure book, that’s fantastic! We love seeing hidden gems brought to life. Just make sure you’ve gotten all the proper permissions — because while we adore a good scare, we prefer that the only thing frightening about your film is what’s on-screen, not the legal consequences afterwards. (hihi - but with a deep voice)\r\n\r\n💀 Midnight Scream’s Promise\r\n\r\nOur mission is simple: to give amateur and independent horror filmmakers a real platform. We want to help you reach audiences who appreciate creativity, passion, and the thrill of the unknown. Because horror doesn’t have to come from Hollywood to be great — sometimes the scariest, smartest, and most original stories come from people working out of their basements, garages, or tiny apartments with big imaginations. So, if you’ve got a film — finished, unfinished, or somewhere in between — we want to see it. Submit it. Share it. Let’s bring your nightmare to life.\r\n\r\nAt Midnight Scream, we don’t just play movies. We champion them.\r\n\r\nMidnight Scream – Where Horror Never Sleeps', '69052462cee5d-pexels-cottonbro-5427546.jpg'),
(11, '2025-12-19', 'The Beautiful Horror of Masquerade Balls', 'There’s something both enchanting and deeply unsettling about a masquerade ball. The flicker of candlelight on satin masks, the whisper of gowns gliding across marble floors, and the haunting music that fills the air — all of it feels like a dream teetering on the edge of a nightmare. But beneath the elegance and allure, masquerades have always carried a sense of danger — and sometimes, true horror.\r\n\r\nHistorically, masquerade balls emerged in the late Middle Ages and flourished during the Renaissance as lavish celebrations of mystery and excess. Nobles and commoners alike could don masks and become someone — or something — else for a night. The anonymity of the mask offered freedom, but also temptation. People spoke forbidden words, acted on hidden desires, and sometimes settled deadly grudges under the cover of disguise. The most infamous example, of course, comes from “The Masque of the Red Death,” Edgar Allan Poe’s haunting tale of vanity and death. A prince throws a masquerade to escape the plague ravaging his kingdom, only for death itself to join the party. It’s a chilling reminder that masks may hide our faces, but not our fates.\r\n\r\nBeauty, Fear, and the Psychology of the Mask\r\n\r\nPart of what makes masquerades so unnerving is the psychological tension they create. A mask blurs the line between identity and illusion. It hides emotions, conceals truth, and allows darkness to surface. The person smiling behind a painted mask might be a friend, a stranger — or something far worse. This combination of beauty and deceit, elegance and unease, is what makes masquerades such a powerful symbol in horror. They remind us that fear doesn’t always wear a monster’s face — sometimes it’s hidden behind a smile.\r\n\r\nAt Midnight Scream, we find that fascinating — and we can’t resist bringing that haunting duality to life.\r\n\r\nMidnight Scream’s Summer Solstice Masquerade\r\n\r\nThis summer, around the Summer Solstice, Midnight Scream will be hosting a masquerade ball of our own — a night of dark elegance, twisted romance, and theatrical horror. The underlying theme is still being perfected, but we’re currently inspired by the idea of poisoned romance. Expect décor that plays with perception — objects that from afar seem lovely, but up close reveal something disturbingly wrong. Roses that bleed, chandeliers that whisper, beauty that hides decay.\r\n\r\nYes, it will be scary. And yes — it will be so much fun.\r\n\r\nThis will also be one of our rare events where alcohol will be available, though strictly limited. We want everyone to feel comfortable and safe while enjoying themselves. To ensure that, we’ll have trained security present throughout the event. Anyone who becomes aggressive, violent, or disrespectful will be removed immediately — no exceptions. Drugs and illegal activity will not be tolerated, and if necessary, security will involve the police. We want the only screams that night to be ones of delightful terror, not distress. Because safety and respect are the heart of what we do.\r\n\r\nTo maintain that, this event will also have an age limit, which we’ll announce closer to the date — along with ticket information, dress code details, and sneak peeks at our haunting decorations. So polish your mask, practice your waltz, and prepare your nerves. The Midnight Scream Summer Solstice Masquerade will be a night of beauty, horror, and mystery you won’t forget — no matter how well you try to hide behind your mask.\r\n\r\n🩸 Midnight Scream – Where Horror Never Sleeps (and the masks never tell the truth).', '690528164d4db-pexels-freestockpro-3038246.jpg'),
(12, '2026-01-09', 'Real-Life Horror: When the Ordinary Turns Unnerving', 'Horror doesn’t always come from movies. Sometimes, it sneaks up on us in the middle of our everyday lives — in those fleeting, unsettling moments when reality feels just a little bit off. We’ve all been there. You’re walking home late, and you hear a strange sound from the forest — a rustle that’s too big to be the wind. Your heartbeat quickens, you pick up the pace, and you tell yourself it’s just an animal. Probably. Or maybe it’s that rundown abandoned house you pass every day. The one with the windows like hollow eyes and the front door that seems to breathe when the wind hits it just right. You don’t believe in ghosts… until you catch a flicker of movement inside when no one should be there.\r\n\r\nThen there’s the classic: you wake up in the middle of the night, glance across the room, and see a human-shaped figure sitting in the corner. You freeze. Your mind races. You can feel it watching you. And then you realize  - oh, it’s just your pile of clothes on the chair. (Still terrifying.) These moments may not be real horror, but they feel like it. And that’s what makes them so powerful. Our imaginations take the smallest detail — a shadow, a sound, a half-seen shape — and spin it into something dark and thrilling. While it’s not exactly fun in the moment (no one enjoys their heart leaping into their throat), these experiences are the sparks that light up the creative darkness inside us. Each “harmless scare” could be the seed of an incredible horror story.\r\n\r\n🩸 From Fright to Fiction: Turning Fear Into Stories\r\n\r\nHere at Midnight Scream, we think these little real-life frights deserve more than just a nervous laugh and a “well, that was weird.” We believe they can become amazing short stories — the kind that give others that same delicious shiver down their spine. That’s why we’re planning a special community event in the near future where horror lovers can meet up, share experiences, and brainstorm story ideas together.\r\n\r\nWe’ll form small groups, swap our real-life spooky moments, and twist them into chilling tales. Maybe that abandoned house you were afraid to enter hides a cellar once used by a serial killer — one that’s long been forgotten… or maybe not so forgotten after all. Maybe someone else has moved in down there, and your curiosity has just made you their next problem to solve. The goal isn’t just to scare each other (though that will happen). It’s to explore how fear and imagination work together — to take something ordinary and make it extraordinary.\r\n\r\n👻 Join the Fear Factory\r\n\r\nThe date and details will be announced soon, but trust us — this will be a night for horror fans, storytellers, and anyone who’s ever had to double-check their own reflection in the dark. You don’t have to be a writer. You don’t have to be a filmmaker. You just have to have a story — or at least a memory that once made your blood run cold. Hell, not even that!\r\n\r\nTogether, we’ll laugh, we’ll shiver, and we’ll prove that real horror isn’t only on the screen — sometimes, it’s hiding in the everyday.\r\n\r\n🩸 Midnight Scream – Where Horror Never Sleeps (and your imagination never rests).', '69052b1f569b2-pexels-pixabay-373511.jpg');


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

-- SHOWTIME DATA --
-- A bunch of showtimes has been created
-- Made to make sure that there will be showtimes until the end of the exam.
INSERT INTO Showtimes (show_date, show_time, hall_id, movie_id) VALUES
-- (1,11) → Nov 1–3
('2025-11-01', '15:00', 1, 1), ('2025-11-01', '18:00', 1, 1), ('2025-11-01', '21:00', 2, 1), ('2025-11-01', '23:30', 2, 1),
('2025-11-01', '15:00', 2, 11), ('2025-11-01', '18:00', 2, 11), ('2025-11-01', '21:00', 1, 11), ('2025-11-01', '23:30', 1, 11),
('2025-11-02', '15:00', 1, 1), ('2025-11-02', '18:00', 1, 1), ('2025-11-02', '21:00', 2, 1), ('2025-11-02', '23:30', 2, 1),
('2025-11-02', '15:00', 2, 11), ('2025-11-02', '18:00', 2, 11), ('2025-11-02', '21:00', 1, 11), ('2025-11-02', '23:30', 1, 11),
('2025-11-03', '15:00', 1, 1), ('2025-11-03', '18:00', 1, 1), ('2025-11-03', '21:00', 2, 1), ('2025-11-03', '23:30', 2, 1),
('2025-11-03', '15:00', 2, 11), ('2025-11-03', '18:00', 2, 11), ('2025-11-03', '21:00', 1, 11), ('2025-11-03', '23:30', 1, 11),

-- (2,10) → Nov 4–6
('2025-11-04', '15:00', 1, 2), ('2025-11-04', '18:00', 1, 2), ('2025-11-04', '21:00', 2, 2), ('2025-11-04', '23:30', 2, 2),
('2025-11-04', '15:00', 2, 10), ('2025-11-04', '18:00', 2, 10), ('2025-11-04', '21:00', 1, 10), ('2025-11-04', '23:30', 1, 10),
('2025-11-05', '15:00', 1, 2), ('2025-11-05', '18:00', 1, 2), ('2025-11-05', '21:00', 2, 2), ('2025-11-05', '23:30', 2, 2),
('2025-11-05', '15:00', 2, 10), ('2025-11-05', '18:00', 2, 10), ('2025-11-05', '21:00', 1, 10), ('2025-11-05', '23:30', 1, 10),
('2025-11-06', '15:00', 1, 2), ('2025-11-06', '18:00', 1, 2), ('2025-11-06', '21:00', 2, 2), ('2025-11-06', '23:30', 2, 2),
('2025-11-06', '15:00', 2, 10), ('2025-11-06', '18:00', 2, 10), ('2025-11-06', '21:00', 1, 10), ('2025-11-06', '23:30', 1, 10),

-- (3,9) → Nov 7–9
('2025-11-07', '15:00', 1, 3), ('2025-11-07', '18:00', 1, 3), ('2025-11-07', '21:00', 2, 3), ('2025-11-07', '23:30', 2, 3),
('2025-11-07', '15:00', 2, 9), ('2025-11-07', '18:00', 2, 9), ('2025-11-07', '21:00', 1, 9), ('2025-11-07', '23:30', 1, 9),
('2025-11-08', '15:00', 1, 3), ('2025-11-08', '18:00', 1, 3), ('2025-11-08', '21:00', 2, 3), ('2025-11-08', '23:30', 2, 3),
('2025-11-08', '15:00', 2, 9), ('2025-11-08', '18:00', 2, 9), ('2025-11-08', '21:00', 1, 9), ('2025-11-08', '23:30', 1, 9),
('2025-11-09', '15:00', 1, 3), ('2025-11-09', '18:00', 1, 3), ('2025-11-09', '21:00', 2, 3), ('2025-11-09', '23:30', 2, 3),
('2025-11-09', '15:00', 2, 9), ('2025-11-09', '18:00', 2, 9), ('2025-11-09', '21:00', 1, 9), ('2025-11-09', '23:30', 1, 9),

-- (4,8) → Nov 10–12
('2025-11-10', '15:00', 1, 4), ('2025-11-10', '18:00', 1, 4), ('2025-11-10', '21:00', 2, 4), ('2025-11-10', '23:30', 2, 4),
('2025-11-10', '15:00', 2, 8), ('2025-11-10', '18:00', 2, 8), ('2025-11-10', '21:00', 1, 8), ('2025-11-10', '23:30', 1, 8),
('2025-11-11', '15:00', 1, 4), ('2025-11-11', '18:00', 1, 4), ('2025-11-11', '21:00', 2, 4), ('2025-11-11', '23:30', 2, 4),
('2025-11-11', '15:00', 2, 8), ('2025-11-11', '18:00', 2, 8), ('2025-11-11', '21:00', 1, 8), ('2025-11-11', '23:30', 1, 8),
('2025-11-12', '15:00', 1, 4), ('2025-11-12', '18:00', 1, 4), ('2025-11-12', '21:00', 2, 4), ('2025-11-12', '23:30', 2, 4),
('2025-11-12', '15:00', 2, 8), ('2025-11-12', '18:00', 2, 8), ('2025-11-12', '21:00', 1, 8), ('2025-11-12', '23:30', 1, 8),

-- (5,7) → Nov 13–15
('2025-11-13', '15:00', 1, 5), ('2025-11-13', '18:00', 1, 5), ('2025-11-13', '21:00', 2, 5), ('2025-11-13', '23:30', 2, 5),
('2025-11-13', '15:00', 2, 7), ('2025-11-13', '18:00', 2, 7), ('2025-11-13', '21:00', 1, 7), ('2025-11-13', '23:30', 1, 7),
('2025-11-14', '15:00', 1, 5), ('2025-11-14', '18:00', 1, 5), ('2025-11-14', '21:00', 2, 5), ('2025-11-14', '23:30', 2, 5),
('2025-11-14', '15:00', 2, 7), ('2025-11-14', '18:00', 2, 7), ('2025-11-14', '21:00', 1, 7), ('2025-11-14', '23:30', 1, 7),
('2025-11-15', '15:00', 1, 5), ('2025-11-15', '18:00', 1, 5), ('2025-11-15', '21:00', 2, 5), ('2025-11-15', '23:30', 2, 5),
('2025-11-15', '15:00', 2, 7), ('2025-11-15', '18:00', 2, 7), ('2025-11-15', '21:00', 1, 7), ('2025-11-15', '23:30', 1, 7),

-- (6,12) → Nov 16–18
('2025-11-16', '15:00', 1, 6), ('2025-11-16', '18:00', 1, 6), ('2025-11-16', '21:00', 2, 6), ('2025-11-16', '23:30', 2, 6),
('2025-11-16', '15:00', 2, 12), ('2025-11-16', '18:00', 2, 12), ('2025-11-16', '21:00', 1, 12), ('2025-11-16', '23:30', 1, 12),
('2025-11-17', '15:00', 1, 6), ('2025-11-17', '18:00', 1, 6), ('2025-11-17', '21:00', 2, 6), ('2025-11-17', '23:30', 2, 6),
('2025-11-17', '15:00', 2, 12), ('2025-11-17', '18:00', 2, 12), ('2025-11-17', '21:00', 1, 12), ('2025-11-17', '23:30', 1, 12),
('2025-11-18', '15:00', 1, 6), ('2025-11-18', '18:00', 1, 6), ('2025-11-18', '21:00', 2, 6), ('2025-11-18', '23:30', 2, 6),
('2025-11-18', '15:00', 2, 12), ('2025-11-18', '18:00', 2, 12), ('2025-11-18', '21:00', 1, 12), ('2025-11-18', '23:30', 1, 12),

-- Repeat the same 6-pair cycle again for Nov 19–30
-- (1,11) → Nov 19–21
('2025-11-19', '15:00', 1, 1), ('2025-11-19', '18:00', 1, 1), ('2025-11-19', '21:00', 2, 1), ('2025-11-19', '23:30', 2, 1),
('2025-11-19', '15:00', 2, 11), ('2025-11-19', '18:00', 2, 11), ('2025-11-19', '21:00', 1, 11), ('2025-11-19', '23:30', 1, 11),
('2025-11-20', '15:00', 1, 1), ('2025-11-20', '18:00', 1, 1), ('2025-11-20', '21:00', 2, 1), ('2025-11-20', '23:30', 2, 1),
('2025-11-20', '15:00', 2, 11), ('2025-11-20', '18:00', 2, 11), ('2025-11-20', '21:00', 1, 11), ('2025-11-20', '23:30', 1, 11),
('2025-11-21', '15:00', 1, 1), ('2025-11-21', '18:00', 1, 1), ('2025-11-21', '21:00', 2, 1), ('2025-11-21', '23:30', 2, 1),
('2025-11-21', '15:00', 2, 11), ('2025-11-21', '18:00', 2, 11), ('2025-11-21', '21:00', 1, 11), ('2025-11-21', '23:30', 1, 11),

-- (2,10) → Nov 22–24
('2025-11-22', '15:00', 1, 2), ('2025-11-22', '18:00', 1, 2), ('2025-11-22', '21:00', 2, 2), ('2025-11-22', '23:30', 2, 2),
('2025-11-22', '15:00', 2, 10), ('2025-11-22', '18:00', 2, 10), ('2025-11-22', '21:00', 1, 10), ('2025-11-22', '23:30', 1, 10),
('2025-11-23', '15:00', 1, 2), ('2025-11-23', '18:00', 1, 2), ('2025-11-23', '21:00', 2, 2), ('2025-11-23', '23:30', 2, 2),
('2025-11-23', '15:00', 2, 10), ('2025-11-23', '18:00', 2, 10), ('2025-11-23', '21:00', 1, 10), ('2025-11-23', '23:30', 1, 10),
('2025-11-24', '15:00', 1, 2), ('2025-11-24', '18:00', 1, 2), ('2025-11-24', '21:00', 2, 2), ('2025-11-24', '23:30', 2, 2),
('2025-11-24', '15:00', 2, 10), ('2025-11-24', '18:00', 2, 10), ('2025-11-24', '21:00', 1, 10), ('2025-11-24', '23:30', 1, 10),

-- (3,9) → Nov 25–27
('2025-11-25', '15:00', 1, 3), ('2025-11-25', '18:00', 1, 3), ('2025-11-25', '21:00', 2, 3), ('2025-11-25', '23:30', 2, 3),
('2025-11-25', '15:00', 2, 9), ('2025-11-25', '18:00', 2, 9), ('2025-11-25', '21:00', 1, 9), ('2025-11-25', '23:30', 1, 9),
('2025-11-26', '15:00', 1, 3), ('2025-11-26', '18:00', 1, 3), ('2025-11-26', '21:00', 2, 3), ('2025-11-26', '23:30', 2, 3),
('2025-11-26', '15:00', 2, 9), ('2025-11-26', '18:00', 2, 9), ('2025-11-26', '21:00', 1, 9), ('2025-11-26', '23:30', 1, 9),
('2025-11-27', '15:00', 1, 3), ('2025-11-27', '18:00', 1, 3), ('2025-11-27', '21:00', 2, 3), ('2025-11-27', '23:30', 2, 3),
('2025-11-27', '15:00', 2, 9), ('2025-11-27', '18:00', 2, 9), ('2025-11-27', '21:00', 1, 9), ('2025-11-27', '23:30', 1, 9),

-- (4,8) → Nov 28–30
('2025-11-28', '15:00', 1, 4), ('2025-11-28', '18:00', 1, 4), ('2025-11-28', '21:00', 2, 4), ('2025-11-28', '23:30', 2, 4),
('2025-11-28', '15:00', 2, 8), ('2025-11-28', '18:00', 2, 8), ('2025-11-28', '21:00', 1, 8), ('2025-11-28', '23:30', 1, 8),
('2025-11-29', '15:00', 1, 4), ('2025-11-29', '18:00', 1, 4), ('2025-11-29', '21:00', 2, 4), ('2025-11-29', '23:30', 2, 4),
('2025-11-29', '15:00', 2, 8), ('2025-11-29', '18:00', 2, 8), ('2025-11-29', '21:00', 1, 8), ('2025-11-29', '23:30', 1, 8),
('2025-11-30', '15:00', 1, 4), ('2025-11-30', '18:00', 1, 4), ('2025-11-30', '21:00', 2, 4), ('2025-11-30', '23:30', 2, 4),
('2025-11-30', '15:00', 2, 8), ('2025-11-30', '18:00', 2, 8), ('2025-11-30', '21:00', 1, 8), ('2025-11-30', '23:30', 1, 8),

-- (1,11) → Dec 1–3
('2025-12-01', '15:00', 1, 1), ('2025-12-01', '18:00', 1, 1), ('2025-12-01', '21:00', 2, 1), ('2025-12-01', '23:30', 2, 1),
('2025-12-01', '15:00', 2, 11), ('2025-12-01', '18:00', 2, 11), ('2025-12-01', '21:00', 1, 11), ('2025-12-01', '23:30', 1, 11),
('2025-12-02', '15:00', 1, 1), ('2025-12-02', '18:00', 1, 1), ('2025-12-02', '21:00', 2, 1), ('2025-12-02', '23:30', 2, 1),
('2025-12-02', '15:00', 2, 11), ('2025-12-02', '18:00', 2, 11), ('2025-12-02', '21:00', 1, 11), ('2025-12-02', '23:30', 1, 11),
('2025-12-03', '15:00', 1, 1), ('2025-12-03', '18:00', 1, 1), ('2025-12-03', '21:00', 2, 1), ('2025-12-03', '23:30', 2, 1),
('2025-12-03', '15:00', 2, 11), ('2025-12-03', '18:00', 2, 11), ('2025-12-03', '21:00', 1, 11), ('2025-12-03', '23:30', 1, 11),

-- (2,10) → Dec 4–6
('2025-12-04', '15:00', 1, 2), ('2025-12-04', '18:00', 1, 2), ('2025-12-04', '21:00', 2, 2), ('2025-12-04', '23:30', 2, 2),
('2025-12-04', '15:00', 2, 10), ('2025-12-04', '18:00', 2, 10), ('2025-12-04', '21:00', 1, 10), ('2025-12-04', '23:30', 1, 10),
('2025-12-05', '15:00', 1, 2), ('2025-12-05', '18:00', 1, 2), ('2025-12-05', '21:00', 2, 2), ('2025-12-05', '23:30', 2, 2),
('2025-12-05', '15:00', 2, 10), ('2025-12-05', '18:00', 2, 10), ('2025-12-05', '21:00', 1, 10), ('2025-12-05', '23:30', 1, 10),
('2025-12-06', '15:00', 1, 2), ('2025-12-06', '18:00', 1, 2), ('2025-12-06', '21:00', 2, 2), ('2025-12-06', '23:30', 2, 2),
('2025-12-06', '15:00', 2, 10), ('2025-12-06', '18:00', 2, 10), ('2025-12-06', '21:00', 1, 10), ('2025-12-06', '23:30', 1, 10),

-- (3,9) → Dec 7–9
('2025-12-07', '15:00', 1, 3), ('2025-12-07', '18:00', 1, 3), ('2025-12-07', '21:00', 2, 3), ('2025-12-07', '23:30', 2, 3),
('2025-12-07', '15:00', 2, 9), ('2025-12-07', '18:00', 2, 9), ('2025-12-07', '21:00', 1, 9), ('2025-12-07', '23:30', 1, 9),
('2025-12-08', '15:00', 1, 3), ('2025-12-08', '18:00', 1, 3), ('2025-12-08', '21:00', 2, 3), ('2025-12-08', '23:30', 2, 3),
('2025-12-08', '15:00', 2, 9), ('2025-12-08', '18:00', 2, 9), ('2025-12-08', '21:00', 1, 9), ('2025-12-08', '23:30', 1, 9),
('2025-12-09', '15:00', 1, 3), ('2025-12-09', '18:00', 1, 3), ('2025-12-09', '21:00', 2, 3), ('2025-12-09', '23:30', 2, 3),
('2025-12-09', '15:00', 2, 9), ('2025-12-09', '18:00', 2, 9), ('2025-12-09', '21:00', 1, 9), ('2025-12-09', '23:30', 1, 9),

-- (4,8) → Dec 10–12
('2025-12-10', '15:00', 1, 4), ('2025-12-10', '18:00', 1, 4), ('2025-12-10', '21:00', 2, 4), ('2025-12-10', '23:30', 2, 4),
('2025-12-10', '15:00', 2, 8), ('2025-12-10', '18:00', 2, 8), ('2025-12-10', '21:00', 1, 8), ('2025-12-10', '23:30', 1, 8),
('2025-12-11', '15:00', 1, 4), ('2025-12-11', '18:00', 1, 4), ('2025-12-11', '21:00', 2, 4), ('2025-12-11', '23:30', 2, 4),
('2025-12-11', '15:00', 2, 8), ('2025-12-11', '18:00', 2, 8), ('2025-12-11', '21:00', 1, 8), ('2025-12-11', '23:30', 1, 8),
('2025-12-12', '15:00', 1, 4), ('2025-12-12', '18:00', 1, 4), ('2025-12-12', '21:00', 2, 4), ('2025-12-12', '23:30', 2, 4),
('2025-12-12', '15:00', 2, 8), ('2025-12-12', '18:00', 2, 8), ('2025-12-12', '21:00', 1, 8), ('2025-12-12', '23:30', 1, 8),

-- (5,7) → Dec 13–15
('2025-12-13', '15:00', 1, 5), ('2025-12-13', '18:00', 1, 5), ('2025-12-13', '21:00', 2, 5), ('2025-12-13', '23:30', 2, 5),
('2025-12-13', '15:00', 2, 7), ('2025-12-13', '18:00', 2, 7), ('2025-12-13', '21:00', 1, 7), ('2025-12-13', '23:30', 1, 7),
('2025-12-14', '15:00', 1, 5), ('2025-12-14', '18:00', 1, 5), ('2025-12-14', '21:00', 2, 5), ('2025-12-14', '23:30', 2, 5),
('2025-12-14', '15:00', 2, 7), ('2025-12-14', '18:00', 2, 7), ('2025-12-14', '21:00', 1, 7), ('2025-12-14', '23:30', 1, 7),
('2025-12-15', '15:00', 1, 5), ('2025-12-15', '18:00', 1, 5), ('2025-12-15', '21:00', 2, 5), ('2025-12-15', '23:30', 2, 5),
('2025-12-15', '15:00', 2, 7), ('2025-12-15', '18:00', 2, 7), ('2025-12-15', '21:00', 1, 7), ('2025-12-15', '23:30', 1, 7),

-- (6,12) → Dec 16–18
('2025-12-16', '15:00', 1, 6), ('2025-12-16', '18:00', 1, 6), ('2025-12-16', '21:00', 2, 6), ('2025-12-16', '23:30', 2, 6),
('2025-12-16', '15:00', 2, 12), ('2025-12-16', '18:00', 2, 12), ('2025-12-16', '21:00', 1, 12), ('2025-12-16', '23:30', 1, 12),
('2025-12-17', '15:00', 1, 6), ('2025-12-17', '18:00', 1, 6), ('2025-12-17', '21:00', 2, 6), ('2025-12-17', '23:30', 2, 6),
('2025-12-17', '15:00', 2, 12), ('2025-12-17', '18:00', 2, 12), ('2025-12-17', '21:00', 1, 12), ('2025-12-17', '23:30', 1, 12),
('2025-12-18', '15:00', 1, 6), ('2025-12-18', '18:00', 1, 6), ('2025-12-18', '21:00', 2, 6), ('2025-12-18', '23:30', 2, 6),
('2025-12-18', '15:00', 2, 12), ('2025-12-18', '18:00', 2, 12), ('2025-12-18', '21:00', 1, 12), ('2025-12-18', '23:30', 1, 12),

-- Repeat cycle again for Dec 19–30
-- (1,11) → Dec 19–21
('2025-12-19', '15:00', 1, 1), ('2025-12-19', '18:00', 1, 1), ('2025-12-19', '21:00', 2, 1), ('2025-12-19', '23:30', 2, 1),
('2025-12-19', '15:00', 2, 11), ('2025-12-19', '18:00', 2, 11), ('2025-12-19', '21:00', 1, 11), ('2025-12-19', '23:30', 1, 11),
('2025-12-20', '15:00', 1, 1), ('2025-12-20', '18:00', 1, 1), ('2025-12-20', '21:00', 2, 1), ('2025-12-20', '23:30', 2, 1),
('2025-12-20', '15:00', 2, 11), ('2025-12-20', '18:00', 2, 11), ('2025-12-20', '21:00', 1, 11), ('2025-12-20', '23:30', 1, 11),
('2025-12-21', '15:00', 1, 1), ('2025-12-21', '18:00', 1, 1), ('2025-12-21', '21:00', 2, 1), ('2025-12-21', '23:30', 2, 1),
('2025-12-21', '15:00', 2, 11), ('2025-12-21', '18:00', 2, 11), ('2025-12-21', '21:00', 1, 11), ('2025-12-21', '23:30', 1, 11),

-- (2,10) → Dec 22–24
('2025-12-22', '15:00', 1, 2), ('2025-12-22', '18:00', 1, 2), ('2025-12-22', '21:00', 2, 2), ('2025-12-22', '23:30', 2, 2),
('2025-12-22', '15:00', 2, 10), ('2025-12-22', '18:00', 2, 10), ('2025-12-22', '21:00', 1, 10), ('2025-12-22', '23:30', 1, 10),
('2025-12-23', '15:00', 1, 2), ('2025-12-23', '18:00', 1, 2), ('2025-12-23', '21:00', 2, 2), ('2025-12-23', '23:30', 2, 2),
('2025-12-23', '15:00', 2, 10), ('2025-12-23', '18:00', 2, 10), ('2025-12-23', '21:00', 1, 10), ('2025-12-23', '23:30', 1, 10),
('2025-12-24', '15:00', 1, 2), ('2025-12-24', '18:00', 1, 2), ('2025-12-24', '21:00', 2, 2), ('2025-12-24', '23:30', 2, 2),
('2025-12-24', '15:00', 2, 10), ('2025-12-24', '18:00', 2, 10), ('2025-12-24', '21:00', 1, 10), ('2025-12-24', '23:30', 1, 10),

-- (3,9) → Dec 25–27
('2025-12-25', '15:00', 1, 3), ('2025-12-25', '18:00', 1, 3), ('2025-12-25', '21:00', 2, 3), ('2025-12-25', '23:30', 2, 3),
('2025-12-25', '15:00', 2, 9), ('2025-12-25', '18:00', 2, 9), ('2025-12-25', '21:00', 1, 9), ('2025-12-25', '23:30', 1, 9),
('2025-12-26', '15:00', 1, 3), ('2025-12-26', '18:00', 1, 3), ('2025-12-26', '21:00', 2, 3), ('2025-12-26', '23:30', 2, 3),
('2025-12-26', '15:00', 2, 9), ('2025-12-26', '18:00', 2, 9), ('2025-12-26', '21:00', 1, 9), ('2025-12-26', '23:30', 1, 9),
('2025-12-27', '15:00', 1, 3), ('2025-12-27', '18:00', 1, 3), ('2025-12-27', '21:00', 2, 3), ('2025-12-27', '23:30', 2, 3),
('2025-12-27', '15:00', 2, 9), ('2025-12-27', '18:00', 2, 9), ('2025-12-27', '21:00', 1, 9), ('2025-12-27', '23:30', 1, 9),

-- (4,8) → Dec 28–30
('2025-12-28', '15:00', 1, 4), ('2025-12-28', '18:00', 1, 4), ('2025-12-28', '21:00', 2, 4), ('2025-12-28', '23:30', 2, 4),
('2025-12-28', '15:00', 2, 8), ('2025-12-28', '18:00', 2, 8), ('2025-12-28', '21:00', 1, 8), ('2025-12-28', '23:30', 1, 8),
('2025-12-29', '15:00', 1, 4), ('2025-12-29', '18:00', 1, 4), ('2025-12-29', '21:00', 2, 4), ('2025-12-29', '23:30', 2, 4),
('2025-12-29', '15:00', 2, 8), ('2025-12-29', '18:00', 2, 8), ('2025-12-29', '21:00', 1, 8), ('2025-12-29', '23:30', 1, 8),
('2025-12-30', '15:00', 1, 4), ('2025-12-30', '18:00', 1, 4), ('2025-12-30', '21:00', 2, 4), ('2025-12-30', '23:30', 2, 4),
('2025-12-30', '15:00', 2, 8), ('2025-12-30', '18:00', 2, 8), ('2025-12-30', '21:00', 1, 8), ('2025-12-30', '23:30', 1, 8),

-- (1,11) → Jan 1–3
('2026-01-01', '15:00', 1, 1), ('2026-01-01', '18:00', 1, 1), ('2026-01-01', '21:00', 2, 1), ('2026-01-01', '23:30', 2, 1),
('2026-01-01', '15:00', 2, 11), ('2026-01-01', '18:00', 2, 11), ('2026-01-01', '21:00', 1, 11), ('2026-01-01', '23:30', 1, 11),
('2026-01-02', '15:00', 1, 1), ('2026-01-02', '18:00', 1, 1), ('2026-01-02', '21:00', 2, 1), ('2026-01-02', '23:30', 2, 1),
('2026-01-02', '15:00', 2, 11), ('2026-01-02', '18:00', 2, 11), ('2026-01-02', '21:00', 1, 11), ('2026-01-02', '23:30', 1, 11),
('2026-01-03', '15:00', 1, 1), ('2026-01-03', '18:00', 1, 1), ('2026-01-03', '21:00', 2, 1), ('2026-01-03', '23:30', 2, 1),
('2026-01-03', '15:00', 2, 11), ('2026-01-03', '18:00', 2, 11), ('2026-01-03', '21:00', 1, 11), ('2026-01-03', '23:30', 1, 11),

-- (2,10) → Jan 4–6
('2026-01-04', '15:00', 1, 2), ('2026-01-04', '18:00', 1, 2), ('2026-01-04', '21:00', 2, 2), ('2026-01-04', '23:30', 2, 2),
('2026-01-04', '15:00', 2, 10), ('2026-01-04', '18:00', 2, 10), ('2026-01-04', '21:00', 1, 10), ('2026-01-04', '23:30', 1, 10),
('2026-01-05', '15:00', 1, 2), ('2026-01-05', '18:00', 1, 2), ('2026-01-05', '21:00', 2, 2), ('2026-01-05', '23:30', 2, 2),
('2026-01-05', '15:00', 2, 10), ('2026-01-05', '18:00', 2, 10), ('2026-01-05', '21:00', 1, 10), ('2026-01-05', '23:30', 1, 10),
('2026-01-06', '15:00', 1, 2), ('2026-01-06', '18:00', 1, 2), ('2026-01-06', '21:00', 2, 2), ('2026-01-06', '23:30', 2, 2),
('2026-01-06', '15:00', 2, 10), ('2026-01-06', '18:00', 2, 10), ('2026-01-06', '21:00', 1, 10), ('2026-01-06', '23:30', 1, 10),

-- (3,9) → Jan 7–9
('2026-01-07', '15:00', 1, 3), ('2026-01-07', '18:00', 1, 3), ('2026-01-07', '21:00', 2, 3), ('2026-01-07', '23:30', 2, 3),
('2026-01-07', '15:00', 2, 9), ('2026-01-07', '18:00', 2, 9), ('2026-01-07', '21:00', 1, 9), ('2026-01-07', '23:30', 1, 9),
('2026-01-08', '15:00', 1, 3), ('2026-01-08', '18:00', 1, 3), ('2026-01-08', '21:00', 2, 3), ('2026-01-08', '23:30', 2, 3),
('2026-01-08', '15:00', 2, 9), ('2026-01-08', '18:00', 2, 9), ('2026-01-08', '21:00', 1, 9), ('2026-01-08', '23:30', 1, 9),
('2026-01-09', '15:00', 1, 3), ('2026-01-09', '18:00', 1, 3), ('2026-01-09', '21:00', 2, 3), ('2026-01-09', '23:30', 2, 3),
('2026-01-09', '15:00', 2, 9), ('2026-01-09', '18:00', 2, 9), ('2026-01-09', '21:00', 1, 9), ('2026-01-09', '23:30', 1, 9),

-- (4,8) → Jan 10–12
('2026-01-10', '15:00', 1, 4), ('2026-01-10', '18:00', 1, 4), ('2026-01-10', '21:00', 2, 4), ('2026-01-10', '23:30', 2, 4),
('2026-01-10', '15:00', 2, 8), ('2026-01-10', '18:00', 2, 8), ('2026-01-10', '21:00', 1, 8), ('2026-01-10', '23:30', 1, 8),
('2026-01-11', '15:00', 1, 4), ('2026-01-11', '18:00', 1, 4), ('2026-01-11', '21:00', 2, 4), ('2026-01-11', '23:30', 2, 4),
('2026-01-11', '15:00', 2, 8), ('2026-01-11', '18:00', 2, 8), ('2026-01-11', '21:00', 1, 8), ('2026-01-11', '23:30', 1, 8),
('2026-01-12', '15:00', 1, 4), ('2026-01-12', '18:00', 1, 4), ('2026-01-12', '21:00', 2, 4), ('2026-01-12', '23:30', 2, 4),
('2026-01-12', '15:00', 2, 8), ('2026-01-12', '18:00', 2, 8), ('2026-01-12', '21:00', 1, 8), ('2026-01-12', '23:30', 1, 8),

-- (5,7) → Jan 13–15
('2026-01-13', '15:00', 1, 5), ('2026-01-13', '18:00', 1, 5), ('2026-01-13', '21:00', 2, 5), ('2026-01-13', '23:30', 2, 5),
('2026-01-13', '15:00', 2, 7), ('2026-01-13', '18:00', 2, 7), ('2026-01-13', '21:00', 1, 7), ('2026-01-13', '23:30', 1, 7),
('2026-01-14', '15:00', 1, 5), ('2026-01-14', '18:00', 1, 5), ('2026-01-14', '21:00', 2, 5), ('2026-01-14', '23:30', 2, 5),
('2026-01-14', '15:00', 2, 7), ('2026-01-14', '18:00', 2, 7), ('2026-01-14', '21:00', 1, 7), ('2026-01-14', '23:30', 1, 7),
('2026-01-15', '15:00', 1, 5), ('2026-01-15', '18:00', 1, 5), ('2026-01-15', '21:00', 2, 5), ('2026-01-15', '23:30', 2, 5),
('2026-01-15', '15:00', 2, 7), ('2026-01-15', '18:00', 2, 7), ('2026-01-15', '21:00', 1, 7), ('2026-01-15', '23:30', 1, 7),

-- (6,12) → Jan 16–18
('2026-01-16', '15:00', 1, 6), ('2026-01-16', '18:00', 1, 6), ('2026-01-16', '21:00', 2, 6), ('2026-01-16', '23:30', 2, 6),
('2026-01-16', '15:00', 2, 12), ('2026-01-16', '18:00', 2, 12), ('2026-01-16', '21:00', 1, 12), ('2026-01-16', '23:30', 1, 12),
('2026-01-17', '15:00', 1, 6), ('2026-01-17', '18:00', 1, 6), ('2026-01-17', '21:00', 2, 6), ('2026-01-17', '23:30', 2, 6),
('2026-01-17', '15:00', 2, 12), ('2026-01-17', '18:00', 2, 12), ('2026-01-17', '21:00', 1, 12), ('2026-01-17', '23:30', 1, 12),
('2026-01-18', '15:00', 1, 6), ('2026-01-18', '18:00', 1, 6), ('2026-01-18', '21:00', 2, 6), ('2026-01-18', '23:30', 2, 6),
('2026-01-18', '15:00', 2, 12), ('2026-01-18', '18:00', 2, 12), ('2026-01-18', '21:00', 1, 12), ('2026-01-18', '23:30', 1, 12),

-- Repeat cycle again for Jan 19–30
-- (1,11) → Jan 19–21
('2026-01-19', '15:00', 1, 1), ('2026-01-19', '18:00', 1, 1), ('2026-01-19', '21:00', 2, 1), ('2026-01-19', '23:30', 2, 1),
('2026-01-19', '15:00', 2, 11), ('2026-01-19', '18:00', 2, 11), ('2026-01-19', '21:00', 1, 11), ('2026-01-19', '23:30', 1, 11),
('2026-01-20', '15:00', 1, 1), ('2026-01-20', '18:00', 1, 1), ('2026-01-20', '21:00', 2, 1), ('2026-01-20', '23:30', 2, 1),
('2026-01-20', '15:00', 2, 11), ('2026-01-20', '18:00', 2, 11), ('2026-01-20', '21:00', 1, 11), ('2026-01-20', '23:30', 1, 11),
('2026-01-21', '15:00', 1, 1), ('2026-01-21', '18:00', 1, 1), ('2026-01-21', '21:00', 2, 1), ('2026-01-21', '23:30', 2, 1),
('2026-01-21', '15:00', 2, 11), ('2026-01-21', '18:00', 2, 11), ('2026-01-21', '21:00', 1, 11), ('2026-01-21', '23:30', 1, 11),

-- (2,10) → Jan 22–24
('2026-01-22', '15:00', 1, 2), ('2026-01-22', '18:00', 1, 2), ('2026-01-22', '21:00', 2, 2), ('2026-01-22', '23:30', 2, 2),
('2026-01-22', '15:00', 2, 10), ('2026-01-22', '18:00', 2, 10), ('2026-01-22', '21:00', 1, 10), ('2026-01-22', '23:30', 1, 10),
('2026-01-23', '15:00', 1, 2), ('2026-01-23', '18:00', 1, 2), ('2026-01-23', '21:00', 2, 2), ('2026-01-23', '23:30', 2, 2),
('2026-01-23', '15:00', 2, 10), ('2026-01-23', '18:00', 2, 10), ('2026-01-23', '21:00', 1, 10), ('2026-01-23', '23:30', 1, 10),
('2026-01-24', '15:00', 1, 2), ('2026-01-24', '18:00', 1, 2), ('2026-01-24', '21:00', 2, 2), ('2026-01-24', '23:30', 2, 2),
('2026-01-24', '15:00', 2, 10), ('2026-01-24', '18:00', 2, 10), ('2026-01-24', '21:00', 1, 10), ('2026-01-24', '23:30', 1, 10),

-- (3,9) → Jan 25–27
('2026-01-25', '15:00', 1, 3), ('2026-01-25', '18:00', 1, 3), ('2026-01-25', '21:00', 2, 3), ('2026-01-25', '23:30', 2, 3),
('2026-01-25', '15:00', 2, 9), ('2026-01-25', '18:00', 2, 9), ('2026-01-25', '21:00', 1, 9), ('2026-01-25', '23:30', 1, 9),
('2026-01-26', '15:00', 1, 3), ('2026-01-26', '18:00', 1, 3), ('2026-01-26', '21:00', 2, 3), ('2026-01-26', '23:30', 2, 3),
('2026-01-26', '15:00', 2, 9), ('2026-01-26', '18:00', 2, 9), ('2026-01-26', '21:00', 1, 9), ('2026-01-26', '23:30', 1, 9),
('2026-01-27', '15:00', 1, 3), ('2026-01-27', '18:00', 1, 3), ('2026-01-27', '21:00', 2, 3), ('2026-01-27', '23:30', 2, 3),
('2026-01-27', '15:00', 2, 9), ('2026-01-27', '18:00', 2, 9), ('2026-01-27', '21:00', 1, 9), ('2026-01-27', '23:30', 1, 9),

-- (4,8) → Jan 28–30
('2026-01-28', '15:00', 1, 4), ('2026-01-28', '18:00', 1, 4), ('2026-01-28', '21:00', 2, 4), ('2026-01-28', '23:30', 2, 4),
('2026-01-28', '15:00', 2, 8), ('2026-01-28', '18:00', 2, 8), ('2026-01-28', '21:00', 1, 8), ('2026-01-28', '23:30', 1, 8),
('2026-01-29', '15:00', 1, 4), ('2026-01-29', '18:00', 1, 4), ('2026-01-29', '21:00', 2, 4), ('2026-01-29', '23:30', 2, 4),
('2026-01-29', '15:00', 2, 8), ('2026-01-29', '18:00', 2, 8), ('2026-01-29', '21:00', 1, 8), ('2026-01-29', '23:30', 1, 8),
('2026-01-30', '15:00', 1, 4), ('2026-01-30', '18:00', 1, 4), ('2026-01-30', '21:00', 2, 4), ('2026-01-30', '23:30', 2, 4),
('2026-01-30', '15:00', 2, 8), ('2026-01-30', '18:00', 2, 8), ('2026-01-30', '21:00', 1, 8), ('2026-01-30', '23:30', 1, 8);