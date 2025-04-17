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
	id_priorite INTEGER NOT NULL,
	FOREIGN KEY (id_priorite) REFERENCES priorite(id)
);

INSERT INTO tache (description, id_priorite) VALUES
('Comprendre comment utiliser PDO', 2),
('Lire l''énoncé du laboratoire', 2),
('Écrire le code de la page index', 3),
('Écrire le code de la liste', 3),
('Écrire le code de la page afficher', 3),
('Écrire le code de la page de modification', 3),
('Écrire le code de la page d''ajout', 3),
('Écrire le code de suppression', 3),
('Nourrir le chat', 1);

