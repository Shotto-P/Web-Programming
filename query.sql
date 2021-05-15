/*
1.先在localhost主页面的SQL栏输入第一个语句，执行，建DB
2.选择建好的UniBNBDB
3.在SQL栏输入剩下的语句，并执行
*/

CREATE DATABASE UniBNBDB;

CREATE TABLE Users (
    User_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Fname VARCHAR(255) NOT NULL,
    Lname VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Mobile INT NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Country VARCHAR(255) NOT NULL,
    ABN INT,
    Rate FLOAT UNSIGNED,
    Avatar LONGBLOB NOT NULL 
);

CREATE TABLE Accommodations (
    Accom_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    City VARCHAR(255) NOT NULL,
    Country VARCHAR(255) NOT NULL,
    Price INT NOT NULL,
    Host_id INT UNSIGNED,
    FOREIGN KEY (Host_id) REFERENCES Users(User_id),
    NumOfRoom INT NOT NULL,
    NumOfBath INT NOT NULL,
    NumOfCarPark INT NOT NULL,
    MaxGuestNum INT NOT NULL,
    SmokeAllowed BOOLEAN NOT NULL,
    PetAllowed BOOLEAN NOT NULL,
    InternetConnected BOOLEAN NOT NULL,
    Rate FLOAT,
    Image LONGBLOB NOT NULL
);

CREATE TABLE Comments (
    Comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Content TEXT,
    Author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (Author_id) REFERENCES Users(User_id),
    Rate INT UNSIGNED,
    Accom_id INT UNSIGNED,
    FOREIGN KEY (Accom_id) REFERENCES Accommodations(Accom_id),
    Status VARCHAR(10) DEFAULT 'Unread',
    Receiver_id INT UNSIGNED,
    FOREIGN KEY (Receiver_id) REFERENCES Users(User_id)
);

CREATE TABLE Calendar (
    Date_id INT UNSIGNED PRIMARY KEY,
    Date DATE NOT NULL
);

CREATE TABLE Bookings (
    Booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Booking_status VARCHAR(15) NOT NULL,
    Accom_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (Accom_id) REFERENCES Accommodations(Accom_id),
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL,
    Tenant_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (Tenant_id) REFERENCES Users(User_id),
    TotalPrice INT UNSIGNED NOT NULL,
    Reason TEXT
);

DROP PROCEDURE IF EXISTS FillCalendar;
DELIMITER $$
CREATE PROCEDURE FillCalendar(IN startdate DATE, IN enddate DATE)
BEGIN
    DECLARE currentdate DATE;
    SET currentdate = startdate;
    WHILE currentdate <= enddate DO
        INSERT INTO Calendar VALUES (
            YEAR(currentdate)*10000+MONTH(currentdate)*100 + DAY(currentdate),
            currentdate
        );
        SET currentdate = ADDDATE(currentdate, INTERVAL 1 DAY);
    END WHILE;
END $$
DELIMITER ;

CALL FillCalendar('2021-01-01', '2023-01-01');