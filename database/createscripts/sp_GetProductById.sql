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
