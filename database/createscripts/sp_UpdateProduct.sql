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
