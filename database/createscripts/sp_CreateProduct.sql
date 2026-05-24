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
