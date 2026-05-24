DROP DATABASE IF EXISTS `mvc_test_crud`;
CREATE DATABASE IF NOT EXISTS `mvc_test_crud`;
USE `mvc_test_crud`;

DROP TABLE IF EXISTS Product;

CREATE TABLE IF NOT EXISTS Product (
	Id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	Naam VARCHAR(100) NOT NULL,
	Beschrijving VARCHAR(255) NOT NULL,
	Prijs DECIMAL(10,2) NOT NULL,
	IsActief BIT NOT NULL DEFAULT 1,
	DatumAangemaakt DATETIME(6) NOT NULL,
	DatumGewijzigd DATETIME(6) NOT NULL,
	CONSTRAINT PK_Product_Id PRIMARY KEY (Id)
) ENGINE=InnoDB;

INSERT INTO Product
(
	Naam,
	Beschrijving,
	Prijs,
	IsActief,
	DatumAangemaakt,
	DatumGewijzigd
)
VALUES
	('Test Product A', 'Eerste testproduct', 9.95, 1, SYSDATE(6), SYSDATE(6)),
	('Test Product B', 'Tweede testproduct', 19.50, 1, SYSDATE(6), SYSDATE(6)),
	('Test Product C', 'Derde testproduct', 5.00, 1, SYSDATE(6), SYSDATE(6));

DROP PROCEDURE IF EXISTS sp_GetAllProducten;

DELIMITER $$

CREATE PROCEDURE sp_GetAllProducten()
BEGIN
	SELECT
		P.Id,
		P.Naam,
		P.Beschrijving,
		P.Prijs,
		P.IsActief
	FROM Product AS P
	ORDER BY P.Id ASC;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_CreateProduct;

DELIMITER $$

CREATE PROCEDURE sp_CreateProduct(
	IN p_naam VARCHAR(100),
	IN p_beschrijving VARCHAR(255),
	IN p_prijs DECIMAL(10,2)
)
BEGIN
	INSERT INTO Product (
		Naam,
		Beschrijving,
		Prijs,
		IsActief,
		DatumAangemaakt,
		DatumGewijzigd
	)
	VALUES (
		p_naam,
		p_beschrijving,
		p_prijs,
		1,
		SYSDATE(6),
		SYSDATE(6)
	);

	SELECT LAST_INSERT_ID() AS new_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_DeleteProduct;

DELIMITER $$

CREATE PROCEDURE sp_DeleteProduct(
	IN p_id INT
)
BEGIN
	DELETE FROM Product
	WHERE Id = p_id;

	SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_GetProductById;

DELIMITER $$

CREATE PROCEDURE sp_GetProductById(
	IN p_id INT
)
BEGIN
	SELECT
		P.Id,
		P.Naam,
		P.Beschrijving,
		P.Prijs,
		P.IsActief
	FROM Product AS P
	WHERE P.Id = p_id;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_UpdateProduct;

DELIMITER $$

CREATE PROCEDURE sp_UpdateProduct(
	IN p_id INT,
	IN p_naam VARCHAR(100),
	IN p_beschrijving VARCHAR(255),
	IN p_prijs DECIMAL(10,2)
)
BEGIN
	UPDATE Product
	SET
		Naam = p_naam,
		Beschrijving = p_beschrijving,
		Prijs = p_prijs,
		DatumGewijzigd = SYSDATE(6)
	WHERE Id = p_id;

	SELECT ROW_COUNT() AS affected;
END$$

DELIMITER ;
