--Being secure we start from a white document
DROP TABLE IF EXISTS pedidos;


--Creating DB (change pau for your db name)
CREATE DATABASE IF NOT EXISTS pau;
USE pau;


--Creating the first table
CREATE TABLE IF NOT EXISTS pedidos(
    ID int NOT NULL,
    NUMBER varchar(100),
    PRICE int(10),
    COUNTRY varchar(30),
    CP varchar(10),
    DATEHOUR varchar(20),
    DATE varchar(30),
    HOUR varchar(30),
    HOURAPROX varchar(4),
    MONTH varchar(20),
    WEEKDAY varchar(20),
    ASIN varchar(50),
    USER VARCHAR(30),
    ADDED varchar(50)
);
--Converting ID into an primary key and AI
ALTER TABLE `pedidos` CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`ID`);

--Creating another table to make the backup
CREATE TABLE IF NOT EXISTS backup LIKE pedidos;
TRUNCATE backup;

--Creating trigger
CREATE TRIGGER `backup_BD` BEFORE DELETE ON `pedidos` FOR EACH ROW
INSERT INTO backup(
    ID,
    NUMBER,
    PRICE,
    COUNTRY,
    CP,
    DATEHOUR,
    DATE,
    HOUR,
    HOURAPROX,
    MONTH,
    WEEKDAY,
    ASIN,
    USER,
    ADDED
  )
VALUES(
    OLD.ID,
    OLD.NUMBER,
    OLD.PRICE,
    OLD.COUNTRY,
    OLD.CP,
    OLD.DATEHOUR,
    OLD.DATE,
    OLD.HOUR,
    OLD.HOURAPROX,
    OLD.MONTH,
    OLD.WEEKDAY,
    OLD.ASIN,
    CURRENT_USER(),
    NOW()
  );