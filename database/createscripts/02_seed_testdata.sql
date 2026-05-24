USE `mvc_test_crud`;

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

INSERT INTO TypeVoertuig (Id, TypeVoertuig, Rijbewijscategorie, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Personenauto', 'B', 1, NULL, SYSDATE(6), SYSDATE(6)),
(2, 'Vrachtwagen', 'C', 1, NULL, SYSDATE(6), SYSDATE(6)),
(3, 'Bus', 'D', 1, NULL, SYSDATE(6), SYSDATE(6)),
(4, 'Bromfiets', 'AM', 1, NULL, SYSDATE(6), SYSDATE(6));

INSERT INTO Instructeur (Id, Voornaam, Tussenvoegsel, Achternaam, Mobiel, DatumInDienst, AantalSterren, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'Li', '', 'Zhan', '06-28493827', '2015-04-17', 3, 1, NULL, SYSDATE(6), SYSDATE(6)),
(2, 'Leroy', '', 'Boerhaven', '06-39398734', '2018-06-25', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(3, 'Yoeri', 'Van', 'Veen', '06-24383291', '2010-05-12', 3, 1, NULL, SYSDATE(6), SYSDATE(6)),
(4, 'Bert', 'Van', 'Sali', '06-48293823', '2023-01-10', 4, 1, NULL, SYSDATE(6), SYSDATE(6)),
(5, 'Mohammed', 'El', 'Yassidi', '06-34291234', '2010-06-14', 5, 1, NULL, SYSDATE(6), SYSDATE(6));

INSERT INTO Voertuig (Id, Kenteken, Type, Bouwjaar, Brandstof, TypeVoertuigId, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 'AU-67-IO', 'Golf', '2017-06-12', 'Diesel', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(2, 'TR-24-OP', 'DAF', '2019-05-23', 'Diesel', 2, 1, NULL, SYSDATE(6), SYSDATE(6)),
(3, 'TH-78-KL', 'Mercedes', '2023-01-01', 'Benzine', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(4, '90-KL-TR', 'Fiat 500', '2021-09-12', 'Benzine', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(5, '34-TK-LP', 'Scania', '2015-03-13', 'Diesel', 2, 1, NULL, SYSDATE(6), SYSDATE(6)),
(6, 'YY-OP-78', 'BMW M5', '2022-05-13', 'Diesel', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(7, 'UU-HH-JK', 'M.A.N', '2017-12-03', 'Diesel', 2, 1, NULL, SYSDATE(6), SYSDATE(6)),
(8, 'ST-FZ-28', 'Citroën', '2018-01-20', 'Elektrisch', 1, 1, NULL, SYSDATE(6), SYSDATE(6)),
(9, '123-FR-T', 'Piaggio ZIP', '2021-02-01', 'Benzine', 4, 1, NULL, SYSDATE(6), SYSDATE(6)),
(10, 'DRS-52-P', 'Vespa', '2022-03-21', 'Benzine', 4, 1, NULL, SYSDATE(6), SYSDATE(6)),
(11, 'STP-12-U', 'Kymco', '2022-07-02', 'Benzine', 4, 1, NULL, SYSDATE(6), SYSDATE(6)),
(12, '45-SD-23', 'Renault', '2023-01-01', 'Diesel', 3, 1, NULL, SYSDATE(6), SYSDATE(6));

INSERT INTO VoertuigInstructeur (Id, VoertuigId, InstructeurId, DatumToekenning, IsActief, Opmerking, DatumAangemaakt, DatumGewijzigd) VALUES
(1, 1, 5, '2017-06-18', 1, NULL, SYSDATE(6), SYSDATE(6)),
(2, 3, 1, '2021-09-26', 1, NULL, SYSDATE(6), SYSDATE(6)),
(3, 9, 1, '2021-09-27', 1, NULL, SYSDATE(6), SYSDATE(6)),
(4, 4, 4, '2022-08-01', 1, NULL, SYSDATE(6), SYSDATE(6)),
(5, 5, 1, '2019-08-30', 1, NULL, SYSDATE(6), SYSDATE(6)),
(6, 10, 5, '2020-02-02', 1, NULL, SYSDATE(6), SYSDATE(6)),
(7, 2, 5, '2024-01-15', 1, NULL, SYSDATE(6), SYSDATE(6));