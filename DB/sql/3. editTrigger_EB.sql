--new table
CREATE TABLE updated LIKE backup;
TRUNCATE `updated`;
ALTER TABLE `updated` COMMENT = 'Update Backup';

--on pedidos
CREATE TRIGGER `updated_ub` BEFORE UPDATE ON `pedidos` FOR EACH ROW
INSERT INTO updated(
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