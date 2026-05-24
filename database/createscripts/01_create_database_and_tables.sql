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

DROP TABLE IF EXISTS VoertuigInstructeur;
DROP TABLE IF EXISTS Voertuig;
DROP TABLE IF EXISTS TypeVoertuig;
DROP TABLE IF EXISTS Instructeur;

CREATE TABLE IF NOT EXISTS TypeVoertuig (
    Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    TypeVoertuig VARCHAR(100) NOT NULL,
    Rijbewijscategorie VARCHAR(10) NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,
    PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Instructeur (
    Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Voornaam VARCHAR(100) NOT NULL,
    Tussenvoegsel VARCHAR(100) NULL,
    Achternaam VARCHAR(100) NOT NULL,
    Mobiel VARCHAR(20) NOT NULL,
    DatumInDienst DATE NOT NULL,
    AantalSterren TINYINT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,
    PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Voertuig (
    Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Kenteken VARCHAR(20) NOT NULL,
    Type VARCHAR(100) NOT NULL,
    Bouwjaar DATE NOT NULL,
    Brandstof VARCHAR(20) NOT NULL,
    TypeVoertuigId INT UNSIGNED NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,
    PRIMARY KEY (Id),
    CONSTRAINT FK_Voertuig_TypeVoertuig
        FOREIGN KEY (TypeVoertuigId)
        REFERENCES TypeVoertuig (Id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS VoertuigInstructeur (
    Id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    VoertuigId INT UNSIGNED NOT NULL,
    InstructeurId INT UNSIGNED NOT NULL,
    DatumToekenning DATE NOT NULL,
    IsActief BIT NOT NULL DEFAULT 1,
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL,
    DatumGewijzigd DATETIME(6) NOT NULL,
    PRIMARY KEY (Id),
    CONSTRAINT FK_VoertuigInstructeur_Voertuig
        FOREIGN KEY (VoertuigId)
        REFERENCES Voertuig (Id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT FK_VoertuigInstructeur_Instructeur
        FOREIGN KEY (InstructeurId)
        REFERENCES Instructeur (Id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;