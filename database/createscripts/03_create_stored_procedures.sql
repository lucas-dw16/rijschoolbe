USE `mvc_test_crud`;

DROP PROCEDURE IF EXISTS sp_GetAllInstructeurs;
DROP PROCEDURE IF EXISTS sp_GetInstructeurById;
DROP PROCEDURE IF EXISTS sp_GetAllTypeVoertuigen;
DROP PROCEDURE IF EXISTS sp_GetVoertuigById;
DROP PROCEDURE IF EXISTS sp_GetVoertuigenVanInstructeur;
DROP PROCEDURE IF EXISTS sp_GetBeschikbareVoertuigen;
DROP PROCEDURE IF EXISTS sp_AssignVoertuigToInstructeur;
DROP PROCEDURE IF EXISTS sp_UpdateVoertuig;
DROP PROCEDURE IF EXISTS sp_SetInstructeurActief;
DROP PROCEDURE IF EXISTS sp_ToggleInstructeurActief;

DELIMITER $$

CREATE PROCEDURE sp_GetAllInstructeurs()
BEGIN
    SELECT
        I.Id,
        I.Voornaam,
        I.Tussenvoegsel,
        I.Achternaam,
        I.Mobiel,
        I.DatumInDienst,
        I.AantalSterren,
        I.IsActief,
        I.Opmerking,
        I.DatumAangemaakt,
        I.DatumGewijzigd
    FROM Instructeur AS I
    WHERE I.IsActief = 1
    ORDER BY I.AantalSterren DESC, I.Voornaam ASC;
END$$

CREATE PROCEDURE sp_GetInstructeurById(IN p_id INT)
BEGIN
    SELECT
        I.Id,
        I.Voornaam,
        I.Tussenvoegsel,
        I.Achternaam,
        I.Mobiel,
        I.DatumInDienst,
        I.AantalSterren,
        I.IsActief,
        I.Opmerking,
        I.DatumAangemaakt,
        I.DatumGewijzigd
    FROM Instructeur AS I
    WHERE I.Id = p_id AND I.IsActief = 1;
END$$

CREATE PROCEDURE sp_GetAllTypeVoertuigen()
BEGIN
    SELECT
        TV.Id,
        TV.TypeVoertuig,
        TV.Rijbewijscategorie,
        TV.IsActief,
        TV.Opmerking,
        TV.DatumAangemaakt,
        TV.DatumGewijzigd
    FROM TypeVoertuig AS TV
    WHERE TV.IsActief = 1
    ORDER BY TV.Rijbewijscategorie ASC, TV.TypeVoertuig ASC;
END$$

CREATE PROCEDURE sp_GetVoertuigById(IN p_id INT)
BEGIN
    SELECT
        V.Id,
        V.Kenteken,
        V.Type,
        V.Bouwjaar,
        V.Brandstof,
        V.TypeVoertuigId,
        V.IsActief,
        V.Opmerking,
        V.DatumAangemaakt,
        V.DatumGewijzigd,
        TV.TypeVoertuig,
        TV.Rijbewijscategorie,
        VI.InstructeurId
    FROM Voertuig AS V
    INNER JOIN TypeVoertuig AS TV ON TV.Id = V.TypeVoertuigId
    LEFT JOIN VoertuigInstructeur AS VI ON VI.VoertuigId = V.Id AND VI.IsActief = 1
    WHERE V.Id = p_id AND V.IsActief = 1;
END$$

CREATE PROCEDURE sp_GetVoertuigenVanInstructeur(IN p_instructeur_id INT)
BEGIN
    SELECT
        V.Id,
        V.Kenteken,
        V.Type,
        V.Bouwjaar,
        V.Brandstof,
        V.TypeVoertuigId,
        V.IsActief,
        V.Opmerking,
        V.DatumAangemaakt,
        V.DatumGewijzigd,
        TV.TypeVoertuig,
        TV.Rijbewijscategorie,
        VI.InstructeurId
    FROM Voertuig AS V
    INNER JOIN VoertuigInstructeur AS VI ON VI.VoertuigId = V.Id AND VI.IsActief = 1 AND VI.InstructeurId = p_instructeur_id
    INNER JOIN TypeVoertuig AS TV ON TV.Id = V.TypeVoertuigId
    WHERE V.IsActief = 1
    ORDER BY TV.Rijbewijscategorie ASC, V.Type ASC;
END$$

CREATE PROCEDURE sp_GetBeschikbareVoertuigen()
BEGIN
    SELECT
        V.Id,
        V.Kenteken,
        V.Type,
        V.Bouwjaar,
        V.Brandstof,
        V.TypeVoertuigId,
        V.IsActief,
        V.Opmerking,
        V.DatumAangemaakt,
        V.DatumGewijzigd,
        TV.TypeVoertuig,
        TV.Rijbewijscategorie
    FROM Voertuig AS V
    INNER JOIN TypeVoertuig AS TV ON TV.Id = V.TypeVoertuigId
    LEFT JOIN VoertuigInstructeur AS VI ON VI.VoertuigId = V.Id AND VI.IsActief = 1
    WHERE V.IsActief = 1 AND VI.Id IS NULL
    ORDER BY TV.Rijbewijscategorie ASC, V.Type ASC;
END$$

CREATE PROCEDURE sp_AssignVoertuigToInstructeur(IN p_voertuig_id INT, IN p_instructeur_id INT)
BEGIN
    DECLARE v_assignment_id INT DEFAULT NULL;

    SELECT Id INTO v_assignment_id
    FROM VoertuigInstructeur
    WHERE VoertuigId = p_voertuig_id AND IsActief = 1
    LIMIT 1;

    IF v_assignment_id IS NULL THEN
        INSERT INTO VoertuigInstructeur (
            VoertuigId,
            InstructeurId,
            DatumToekenning,
            IsActief,
            Opmerking,
            DatumAangemaakt,
            DatumGewijzigd
        ) VALUES (
            p_voertuig_id,
            p_instructeur_id,
            CURDATE(),
            1,
            NULL,
            SYSDATE(6),
            SYSDATE(6)
        );
    ELSE
        UPDATE VoertuigInstructeur
        SET InstructeurId = p_instructeur_id,
            DatumGewijzigd = SYSDATE(6)
        WHERE Id = v_assignment_id;
    END IF;

    SELECT * FROM VoertuigInstructeur WHERE VoertuigId = p_voertuig_id AND IsActief = 1 LIMIT 1;
END$$

CREATE PROCEDURE sp_UpdateVoertuig(
    IN p_id INT,
    IN p_type_voertuig_id INT,
    IN p_type VARCHAR(100),
    IN p_kenteken VARCHAR(20),
    IN p_brandstof VARCHAR(20),
    IN p_instructeur_id INT
)
BEGIN
    UPDATE Voertuig
    SET TypeVoertuigId = p_type_voertuig_id,
        Type = p_type,
        Kenteken = p_kenteken,
        Brandstof = p_brandstof,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    IF p_instructeur_id IS NOT NULL THEN
        IF EXISTS (
            SELECT 1
            FROM VoertuigInstructeur
            WHERE VoertuigId = p_id AND IsActief = 1
        ) THEN
            UPDATE VoertuigInstructeur
            SET InstructeurId = p_instructeur_id,
                DatumGewijzigd = SYSDATE(6)
            WHERE VoertuigId = p_id AND IsActief = 1;
        ELSE
            INSERT INTO VoertuigInstructeur (
                VoertuigId,
                InstructeurId,
                DatumToekenning,
                IsActief,
                Opmerking,
                DatumAangemaakt,
                DatumGewijzigd
            ) VALUES (
                p_id,
                p_instructeur_id,
                CURDATE(),
                1,
                NULL,
                SYSDATE(6),
                SYSDATE(6)
            );
        END IF;
    END IF;

    SELECT * FROM Voertuig WHERE Id = p_id;
END$$

CREATE PROCEDURE sp_SetInstructeurActief(
    IN p_instructeur_id INT,
    IN p_is_actief BIT
)
BEGIN
    UPDATE Instructeur
    SET IsActief = p_is_actief,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_instructeur_id;

    IF p_is_actief = 0 THEN
        UPDATE VoertuigInstructeur
        SET IsActief = 0,
            DatumGewijzigd = SYSDATE(6)
        WHERE InstructeurId = p_instructeur_id AND IsActief = 1;
    END IF;

    SELECT * FROM Instructeur WHERE Id = p_instructeur_id;
END$$

CREATE PROCEDURE sp_ToggleInstructeurActief(IN p_instructeur_id INT)
BEGIN
    IF EXISTS (SELECT 1 FROM Instructeur WHERE Id = p_instructeur_id AND IsActief = 1) THEN
        CALL sp_SetInstructeurActief(p_instructeur_id, 0);
    ELSE
        CALL sp_SetInstructeurActief(p_instructeur_id, 1);
    END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_RemoveVoertuigAssignment(IN p_voertuig_id INT)
BEGIN
    UPDATE VoertuigInstructeur
    SET IsActief = 0,
        DatumGewijzigd = SYSDATE(6)
    WHERE VoertuigId = p_voertuig_id AND IsActief = 1;
END$$

CREATE PROCEDURE sp_DeactivateVoertuig(IN p_voertuig_id INT)
BEGIN
    UPDATE Voertuig
    SET IsActief = 0,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_voertuig_id AND IsActief = 1;

    UPDATE VoertuigInstructeur
    SET IsActief = 0,
        DatumGewijzigd = SYSDATE(6)
    WHERE VoertuigId = p_voertuig_id AND IsActief = 1;
END$$

DELIMITER ;