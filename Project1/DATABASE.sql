    DROP TABLE IF EXISTS car_owners;
    CREATE TABLE car_owners (
        userID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(40) NOT NULL,
        email VARCHAR(80) NOT NULL UNIQUE,
        password VARCHAR(256) NOT NULL,
        user_level TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,/*Regular user = 0, Administrator is 1*/
        active CHAR(32), /*Stores an activation code if you will require users to activate their accounts via email*/
        registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (userID)
        );

    DROP TABLE IF EXISTS cars;
    CREATE TABLE cars(
       carID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
       make VARCHAR(32) NOT NULL,
       model VARCHAR(32) NOT NULL,
       year INT(4) NOT NULL,
       base_price INT(9) NOT NULL,
        PRIMARY KEY (carID)
    );
        
    INSERT INTO cars VALUES ('1', 'Alfa Romeo', 'Tonale ', '2024', '42995');
    INSERT INTO cars VALUES ('2', 'Cadillac', 'LYRIQ', '2024', '57195');
    INSERT INTO cars VALUES ('3', 'Polestar', '5', '2024', '100000');
    INSERT INTO cars VALUES ('4', 'Mercedes-Benz', 'CLA', '2024', '42000');
    INSERT INTO cars VALUES ('5', 'GMC', 'Sierra EV', '2024', '50000');

    INSERT INTO cars VALUES ('6', 'Chrysler', 'Pacifica', '2023', '37020');
    INSERT INTO cars VALUES ('7', 'Fisker', 'Ocean', '2023', '37499');
    INSERT INTO cars VALUES ('8', 'Dodge', 'Durango', '2023', '38495');
    INSERT INTO cars VALUES ('9', 'Nissan', 'LEAF', '2023', '27800');
    INSERT INTO cars VALUES ('10', 'Ford', 'Mustang', '2023', '27770');

    INSERT INTO cars VALUES ('11', 'Toyota', 'RAV4', '2022', '26525');
    INSERT INTO cars VALUES ('12', 'Ford', 'Bronco', '2022', '33450');
    INSERT INTO cars VALUES ('13', 'GMC', 'Sierra 1500 Limited Regular Cab', '2022', '30700');
    INSERT INTO cars VALUES ('14', 'Nissan', 'Rouge Sport', '2022', '24260');
    INSERT INTO cars VALUES ('15', 'Maserati', 'Levante', '2022', '153100');

    INSERT INTO cars VALUES ('16', 'Ram', '1500 Classic Crew Cab', '2021', '30000');
    INSERT INTO cars VALUES ('17', 'Lincoln', 'Corsair', '2021', '35945');
    INSERT INTO cars VALUES ('18', 'Rolls-Royce', 'Dawn', '2021', '32174');
    INSERT INTO cars VALUES ('19', 'BMW', 'X1', '2021', '35400');
    INSERT INTO cars VALUES ('20', 'Lotus', 'Evora GT', '2021', '96950');

    DROP TABLE IF EXISTS users_cars;
    CREATE TABLE users_cars(
        UID INTEGER UNSIGNED NOT NULL,
        CID INTEGER UNSIGNED NOT NULL,
        owner varchar(20) NOT NULL,
        cond varchar(20) NOT NULL,
        miles_driven INTEGER(10) NOT NULL,
        Tinted_Windows varchar(20),
        Heated_Seat varchar(20),
        WiFi varchar(20),
        AutoEmergencyBreaking varchar(30),
        NavSystem varchar(20),
        final_price INTEGER(10) NOT NULL,
        PRIMARY KEY (UID,CID)
    );

    ALTER TABLE users_cars ADD FOREIGN KEY (UID) REFERENCES car_owners(userID);
    ALTER TABLE users_cars ADD FOREIGN KEY (CID) REFERENCES cars(carID);

