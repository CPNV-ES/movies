-- DATA

INSERT INTO `movies` (Title, Year, Length, Description, Poster) VALUES ("Lord of War", 2005, 100, "Film d'action sur le trafic d'arme", null), ("Alien vs Predator", 1990, 120, "Alien bouh ouh", null);

-- Paths & Files
INSERT INTO `types` (Name) VALUES ("avi"), ("mp4"), ("mov");

-- Files
INSERT INTO `sources` (Name) VALUES ("Z:/Personnel/Vidéo/");
INSERT INTO `paths` (Name, fkSources) VALUES ("Exemple/Test/A/", 1), ("Exemple/Test/B/", 1);
INSERT INTO `files` (Name, fkMovies, fkTypes, fkPaths, FileTitle) VALUES ("Lord.Of.War.2005.FRENCH.VFR.avi", 1, 1, 1, "Lord Of War"), ("Alien.vs.Predator.(1990).DIVIX.mov", 2, 3, 2,"Aliens vs Predators");

-- Countries
INSERT INTO `countries` (name) VALUES ("USA !"), ("Swiss") , ("Royaume Uni"), ("France");
INSERT INTO `countries_movies` (fkCountries, fkMovies) VALUES (1, 1), (3, 1), (1, 2), (3, 2);

-- Persons
INSERT INTO `people` (FirstName, LastName) VALUES ("Nicolas", "Cage"), ("Jared", "Leto"), ("Colin", "Salmon"), ("Sanaa", "Lathan"), ("Paul W. S.", "Anderson"), ("Andrew", "Niccol"); 
INSERT INTO `types_role` (Type) VALUES ("Writer"), ("Actor"), ("Director"), ("Producer");
INSERT INTO `people_movies` (fkPeople, fkTypes_Role, fkMovies) VALUES (6, 1, 1), (5, 1, 2), (1, 2, 1), (2, 2, 1), (3, 2, 2), (4, 2, 2);

-- Genres
INSERT INTO `genres` (Name) VALUES ("Action"), ("Triller"), ("Drama"), ("Horror"), ("Annimation");
INSERT INTO `genres_movies` (fkGenres, fkMovies) VALUES (1, 1), (3, 1), (1, 2), (4, 2);

-- Studios
INSERT INTO `studios`(Name) VALUES ("Brandywine Productions"), ("Twentieth Century Fox"), ("Warner Bros");
INSERT INTO `studios_movies` (fkStudios, fkMovies) VALUES (1, 2), (2, 2), (3, 1);