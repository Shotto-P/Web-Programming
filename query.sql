CREATE DATABASE UniBNBDB;

CREATE TABLE Hosts (
    Host_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Fname VARCHAR(255) NOT NULL,
    Lname VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Mobile INT NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Country VARCHAR(255) NOT NULL,
    ABN INT NOT NULL,
    Rate FLOAT UNSIGNED 
);

CREATE TABLE Accommodations (
    Accom_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Address VARCHAR(255) NOT NULL,
    City VARCHAR(255) NOT NULL,
    Country VARCHAR(255) NOT NULL,
    Status VARCHAR(15) NOT NULL,
    Booking_date DATE,
    Tenant_id INT UNSIGNED,
    FOREIGN KEY (Tenant_id) REFERENCES Tenants(Tenant_id),
    Price INT NOT NULL,
    Host_id INT UNSIGNED,
    FOREIGN KEY (Host_id) REFERENCES Hosts(Host_id),
    NumOfRoom INT NOT NULL,
    NumOfBath INT NOT NULL,
    NumOfCarPark INT NOT NULL,
    Rate FLOAT
);

CREATE TABLE Tenants (
    Tenant_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    Fname VARCHAR(255) NOT NULL,
    Lname VARCHAR(255) NOT NULL,
    Mobile INT NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Country VARCHAR(255) NOT NULL
);

CREATE TABLE CommentsAccom (
    CommA_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Content TEXT,
    Tenant_id INT UNSIGNED,
    FOREIGN KEY (Tenant_id) REFERENCES Tenants(Tenant_id),
    Rate TINYINT UNSIGNED
);

CREATE TABLE CommentsHost (
    CommH_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Content TEXT,
    Tenant_id INT UNSIGNED,
    FOREIGN KEY (Tenant_id) REFERENCES Tenants(Tenant_id),
    Rate TINYINT UNSIGNED
);

CREATE TABLE Admin (
    Admin_id INT UNSIGNED IDENTITY(1,1) PRIMARY KEY,
    Password VARCHAR(255) NOT NULL,
    Usernmae VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS Calendar;
CREATE TABLE Calendar (
    date_id INT UNSIGNED PRIMARY KEY,
    Date DATE NOT NULL,
    Accom_id INT UNSIGNED,
    FOREIGN KEY (Accom_id) REFERENCES Accommodations(Accom_id),
    Availability VARCHAR(15) DEFAULT 'Available'
);

DROP PROCEDURE IF EXISTS FillCalendar;
DELIMITER $$
CREATE PROCEDURE FillCalendar(start_date DATE, end_date DATE)
BEGIN
    DECLARE current_date DATE;
    SET current_date = start_date;
    WHILE current_date <= end_date DO
        INSERT INTO Calendar VALUES (current_date);
        SET current_date = ADDDATE(current_date, INTERVAL 1 DAY);
    END WHILE;
END $$
DELIMITER ;

CALL FillCalendar('2021-01-01', '2023-01-01');