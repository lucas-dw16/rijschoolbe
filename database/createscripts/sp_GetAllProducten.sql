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
