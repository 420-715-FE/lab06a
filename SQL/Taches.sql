DROP DATABASE IF EXISTS taches;
CREATE DATABASE taches;
USE taches;

CREATE TABLE priorite (
	id INTEGER PRIMARY KEY,
	description VARCHAR(255) NOT NULL
);

INSERT INTO priorite (id, description) VALUES
(1, 'Urgente'),
(2, 'Haute'),
(3, 'Normale'),
(4, 'Basse')
;

CREATE TABLE tache (
	id INTEGER PRIMARY KEY AUTO_INCREMENT,
	description VARCHAR(2000) NOT NULL,
	completee BOOL DEFAULT FALSE,
	id_priorite INTEGER NOT NULL,
	FOREIGN KEY (id_priorite) REFERENCES priorite(id)
);

INSERT INTO tache (description, completee, id_priorite) VALUES
('Comprendre comment utiliser PDO', TRUE, 2),
('Lire l''énoncé du laboratoire', FALSE, 2),
('Écrire le code de la page index', FALSE, 3),
('Écrire le code de la liste', FALSE, 3),
('Écrire le code de la page afficher', FALSE, 3),
('Écrire le code de la page de modification', FALSE, 3),
('Écrire le code de la page d''ajout', FALSE, 3),
('Écrire le code de suppression', FALSE, 3),
('Nourrir le chat', FALSE, 1);

