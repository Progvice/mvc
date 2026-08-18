CREATE TABLE IF NOT EXISTS jobs (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name TEXT  NOT NULL,
    action VARCHAR(254) NOT NULL,
    status VARCHAR(255),
    jobtype VARCHAR(255),

    PRIMARY KEY (id)

);

CREATE TABLE IF NOT EXISTS personel (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(155) NOT NULL,
    lastname VARCHAR(155) NOT NULL,
    email VARCHAR(155) NOT NULL UNIQUE,
    phonenumber VARCHAR(22) NOT NULL UNIQUE,
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
        ON UPDATE CASCADE


);

CREATE TABLE IF NOT EXISTS rides (

    uuid VARCHAR(36) NOT NULL UNIQUE,
    userid VARCHAR(36) NOT NULL,
    pickuptime VARCHAR(255) NOT NULL,
    homeaddr VARCHAR(254) NOT NULL,
    zipcode VARCHAR(5) NOT NULL,
    city VARCHAR(255) NOT NULL,
    apartment VARCHAR(10),
    personel_id INT UNSIGNED,

    CONSTRAINT fk_personel_rides
        FOREIGN KEY (personel_id) REFERENCES personel(id)
        
);

CREATE TABLE IF NOT EXISTS page (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    uri VARCHAR(255) NOT NULL UNIQUE,

    PRIMARY KEY (id)

);

CREATE TABLE IF NOT EXISTS page_content (

    template VARCHAR(255) NOT NULL,
    data JSON,
    page_id INT UNSIGNED,

    CONSTRAINT fk_page_page_content
        FOREIGN KEY (page_id) REFERENCES page(id)
        
);

