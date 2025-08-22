CREATE TABLE IF NOT EXISTS personel (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(155) NOT NULL,
    lastname VARCHAR(155) NOT NULL,
    email VARCHAR(155) NOT NULL UNIQUE,
    phonenumber VARCHAR(20) NOT NULL UNIQUE,
    birthday DATE,

    PRIMARY KEY (id)


);

CREATE TABLE IF NOT EXISTS streetaddress (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    streetaddress VARCHAR(255) NOT NULL,
    streetnumber SMALLINT UNSIGNED NOT NULL,
    apartment VARCHAR(10),
    city VARCHAR(255) NOT NULL,

    personel_id INT UNSIGNED,
    PRIMARY KEY (id),

    CONSTRAINT fk_personel_streetaddress
        FOREIGN KEY (personel_id) REFERENCES personel(id)
        ON DELETE CASCADE
);

INSERT INTO personel (firstname, lastname, email, phonenumber, birthday)
VALUES 
('Jani', 'Juuso', 'jani.juuso@example.com', '0401234567', '1999-03-06'),
('Laura', 'Korhonen', 'laura.korhonen@example.com', '0509876543', '1988-11-30'),
('Mikko', 'Virtanen', 'mikko.virtanen@example.com', '0412345678', '1992-02-25'),
('Sari', 'Niemi', 'sari.niemi@example.com', '0501122334', '1995-07-14'),
('Timo', 'Heikkinen', 'timo.heikkinen@example.com', '0411223344', '1985-09-09'),
('Anna', 'Laine', 'anna.laine@example.com', '0505566778', '1990-04-22'),
('Jukka', 'Mäkinen', 'jukka.makinen@example.com', '0409988776', '1987-12-15'),
('Katja', 'Salmi', 'katja.salmi@example.com', '0506677889', '1993-06-10'),
('Petri', 'Koskinen', 'petri.koskinen@example.com', '0413344556', '1982-08-03'),
('Marja', 'Hämäläinen', 'marja.hamalainen@example.com', '0502233445', '1996-01-28');