CREATE DATABASE IF NOT EXISTS electricity_bill_management;

USE electricity_bill_management;

CREATE TABLE IF NOT EXISTS files(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(20),
    file_name VARCHAR(255) NOT NULL,
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bills(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    meter_number VARCHAR(50) NOT NULL,
    billing_period VARCHAR(50) NOT NULL,
    billing_date DATE NOT NULL,
    previous_reading INT NOT NULL,
    current_reading INT NOT NULL,
    units_consumed INT NOT NULL,
    rate_per_unit DECIMAL(10,2) NOT NULL,
    bill_amount DECIMAL(10,2) NOT NULL,
    upload_id INT,
    FOREIGN KEY (upload_id) REFERENCES files(id)
);

CREATE VIEW bills_by_user_id AS
SELECT 
    b.id AS bill_id, 
    b.user_id, 
    b.customer_name, 
    b.address, 
    b.meter_number, 
    b.billing_period, 
    b.billing_date, 
    b.previous_reading, 
    b.current_reading, 
    b.units_consumed, 
    b.rate_per_unit, 
    b.bill_amount, 
    f.file_name AS uploaded_file
FROM 
    bills b
LEFT JOIN 
    files f ON b.upload_id = f.id;

CREATE VIEW bills_by_customer_name AS
SELECT 
    b.id AS bill_id, 
    b.user_id, 
    b.customer_name, 
    b.address, 
    b.meter_number, 
    b.billing_period, 
    b.billing_date, 
    b.previous_reading, 
    b.current_reading, 
    b.units_consumed, 
    b.rate_per_unit, 
    b.bill_amount, 
    f.file_name AS uploaded_file
FROM 
    bills b
LEFT JOIN 
    files f ON b.upload_id = f.id
ORDER BY 
    b.customer_name;


CREATE VIEW bills_by_meter_number AS
SELECT 
    b.id AS bill_id, 
    b.user_id, 
    b.customer_name, 
    b.address, 
    b.meter_number, 
    b.billing_period, 
    b.billing_date, 
    b.previous_reading, 
    b.current_reading, 
    b.units_consumed, 
    b.rate_per_unit, 
    b.bill_amount, 
    f.file_name AS uploaded_file
FROM 
    bills b
LEFT JOIN 
    files f ON b.upload_id = f.id
ORDER BY 
    b.meter_number;


DELIMITER //

CREATE PROCEDURE calculate_total_bill()
BEGIN
    SELECT 
        user_id, 
        SUM(bill_amount) AS total_bill
    FROM 
        bills
    GROUP BY 
        user_id;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE get_bills_by_user_id(IN input_user_id INT)
BEGIN
    SELECT 
        b.id AS bill_id, 
        b.user_id, 
        b.customer_name, 
        b.address, 
        b.meter_number, 
        b.billing_period, 
        b.billing_date, 
        b.previous_reading, 
        b.current_reading, 
        b.units_consumed, 
        b.rate_per_unit, 
        b.bill_amount, 
        f.file_name AS uploaded_file
    FROM 
        bills b
    LEFT JOIN 
        files f ON b.upload_id = f.id
    WHERE 
        b.user_id = input_user_id;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE get_bills_by_customer_name(IN input_customer_name VARCHAR(255))
BEGIN
    SELECT 
        b.id AS bill_id, 
        b.user_id, 
        b.customer_name, 
        b.address, 
        b.meter_number, 
        b.billing_period, 
        b.billing_date, 
        b.previous_reading, 
        b.current_reading, 
        b.units_consumed, 
        b.rate_per_unit, 
        b.bill_amount, 
        f.file_name AS uploaded_file
    FROM 
        bills b
    LEFT JOIN 
        files f ON b.upload_id = f.id
    WHERE 
        b.customer_name = input_customer_name;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE get_bills_by_meter_number(IN input_meter_number VARCHAR(50))
BEGIN
    SELECT 
        b.id AS bill_id, 
        b.user_id, 
        b.customer_name, 
        b.address, 
        b.meter_number, 
        b.billing_period, 
        b.billing_date, 
        b.previous_reading, 
        b.current_reading, 
        b.units_consumed, 
        b.rate_per_unit, 
        b.bill_amount, 
        f.file_name AS uploaded_file
    FROM 
        bills b
    LEFT JOIN 
        files f ON b.upload_id = f.id
    WHERE 
        b.meter_number = input_meter_number;
END //

DELIMITER ;
