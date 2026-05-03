-- REQUETE 1 : Liste des matchs de la première journée (stage = 1)
SELECT date_match, home, away, stage 
FROM match_foot 
WHERE stage = 1;

-- REQUETE 2 : Liste des équipes
SELECT * 
FROM team;

-- REQUETE 3 : Matchs avec le nom de l'équipe qui reçoit
SELECT m.date_match, t.name_team 
FROM match_foot m
JOIN team t ON m.home = t.id_team
WHERE m.stage = 1;

-- REQUETE 4 : Matchs avec noms des équipes qui jouent
SELECT m.date_match, t1.name_team AS home_team, t2.name_team AS away_team 
FROM match_foot m
JOIN team t1 ON m.home = t1.id_team
JOIN team t2 ON m.away = t2.id_team
WHERE m.stage = 1;

-- REQUETE 5 : Création de la vue LesRencontres
CREATE VIEW LesRencontres AS 
SELECT m.date_match, t1.name_team AS home_team, t2.name_team AS away_team, m.id_match 
FROM match_foot m
JOIN team t1 ON m.home = t1.id_team
JOIN team t2 ON m.away = t2.id_team;

-- REQUETE 6 : Liste des types d'événements
SELECT * 
FROM type_de_event;

-- REQUETE 7 : Nom des équipes créées en 1881
SELECT name_team 
FROM team 
WHERE birthday = '1881';

-- REQUETE 8 : Liste des équipes triées par date de création
SELECT name_team, birthday 
FROM team 
ORDER BY birthday ASC;

-- REQUETE 9 : Équipe du joueur Zlatan
SELECT DISTINCT t.name_team 
FROM player p
JOIN event e ON p.id_player = e.player
JOIN team t ON e.team = t.id_team
WHERE p.name_player LIKE '%Zlatan%';

-- REQUETE 10 : Vérification du nombre d'équipes
SELECT COUNT(*) 
FROM team;

-- REQUETE 11 : Vérification du nombre de matchs
SELECT COUNT(*) 
FROM match_foot;

-- REQUETE 12 : Nombre de buts sur penalty
SELECT COUNT(e.type) 
FROM event e
JOIN type_de_event t ON e.type = t.type
WHERE t.type LIKE '%penalty%';

-- REQUETE 13 : Nombre de cartons rouges (sous-requête)
SELECT (SELECT COUNT(*) 
		FROM event 
		WHERE type LIKE '%red%') AS total_red_cards;

-- REQUETE 14 : Nombre de penaltys sifflés (jointure)
SELECT COUNT(*) FROM event e
JOIN type_de_event t ON e.type = t.type
WHERE t.type IN (SELECT type FROM type_de_event WHERE type LIKE '%penalty%' OR type LIKE '%miss%');

-- REQUETE 14 (avec sous-requête)
SELECT (SELECT COUNT(*) 
FROM event 
WHERE type IN (SELECT type
				FROM type_de_event
				WHERE type LIKE '%miss%' OR type LIKE '%penalty%')) AS total_penalties;

-- REQUETE 15 : Nombre total de buts
SELECT COUNT(type) 
FROM event 
WHERE type IN (SELECT type
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%');

-- REQUETE 16 : Nombre de buts par équipe
SELECT t.name_team, COUNT(type) AS count 
FROM event e
JOIN team t ON t.id_team = team
WHERE type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%')
GROUP BY t.name_team;

-- Création de la vue nombreDeButParEquipe
CREATE VIEW nombreDeButParEquipe 
AS
SELECT t.name_team, COUNT(type) AS count 
FROM event e
JOIN team t ON t.id_team = team
WHERE type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%')
GROUP BY t.name_team;

-- REQUETE 17 : 10 cartons rouges les plus rapides
SELECT * 
FROM event 
WHERE type LIKE '%red%' 
ORDER BY time_event ASC 
LIMIT 10;

-- REQUETE 18 : Date de création la plus ancienne
SELECT MIN(birthday) 
FROM team;

-- REQUETE 19 : Équipe la plus ancienne
SELECT name_team 
FROM team
WHERE birthday = (SELECT MIN(birthday) FROM team);

-- REQUETE 20 : Clé primaire de Lorient
SELECT id_team 
FROM team 
WHERE name_team LIKE '%Lorient%';

-- REQUETE 21 : Vérifier que Lorient a marqué 47 buts
SELECT * 
FROM nombreDeButParEquipe 
WHERE name_team = (SELECT name_team 
					FROM team 
					WHERE name_team 
					LIKE '%Lorient%');

-- REQUETE 22 : Buts à l'extérieur pour Lorient
SELECT COUNT(type) 
FROM event 
WHERE team = (SELECT id_team 
				FROM team 
				WHERE name_team LIKE '%Lorient%') 
AND match_event IN (SELECT id_match 
					FROM match_foot 
					WHERE away = (SELECT id_team 
									FROM team 
									WHERE name_team LIKE '%Lorient%'))
AND type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%');

-- REQUETE 23 : Buts encaissés par Lorient
SELECT COUNT(type) 
FROM event 
WHERE match_event IN (SELECT id_match 
						FROM match_foot 
						WHERE away = (SELECT id_team 
										FROM team 
										WHERE name_team LIKE '%Lorient%'))
AND type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%');

-- REQUETE 24 : Buts encaissés à domicile par Lorient
SELECT COUNT(type) FROM event 
WHERE team != (SELECT id_team 
				FROM team 
				WHERE name_team LIKE '%Lorient%')
AND match_event IN (SELECT id_match 
					FROM match_foot 
					WHERE home = (SELECT id_team 
									FROM team 
									WHERE name_team LIKE '%Lorient%'))
AND type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%');

-- REQUETE 25 : Buts marqués en première moitié de saison
SELECT COUNT(type) 
FROM event
WHERE match_event IN (SELECT id_match FROM match_foot WHERE stage <= 19)
AND team = (SELECT id_team 
				FROM team
				WHERE name_team LIKE '%Lorient%')
AND type IN (SELECT type 
				FROM type_de_event
				WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%');

-- REQUETE 26 : But le plus rapide
SELECT * 
FROM event 
WHERE time_event = (SELECT MIN(time_event) 
					FROM event
					WHERE type IN (SELECT type 
									FROM type_de_event
									WHERE type LIKE '%goal%' OR type LIKE '%penalty%' OR type LIKE '%own%'));

-- REQUETE 27 : Buts marqués à la troisième minute avec le joueur
SELECT p.name_player, e.* 
FROM event e 
JOIN player p ON e.player = p.id_player
WHERE time_event = 3;

-- REQUETE 28 : Ajout de la date du match
SELECT m.date_match, p.name_player, e.* 
FROM event e 
JOIN player p ON e.player = p.id_player 
JOIN match_foot m ON e.match_event = m.id_match 
WHERE e.time_event = 3;

-- REQUETE 29 : Ajout du nom des équipes impliquées
SELECT m.date_match, t1.name_team AS home_team, t2.name_team AS away_team, p.name_player, e.time_event 
FROM event e 
JOIN player p ON e.player = p.id_player 
JOIN match_foot m ON e.match_event = m.id_match 
JOIN team t1 ON m.home = t1.id_team 
JOIN team t2 ON m.away = t2.id_team 
WHERE e.time_event = 3;

-- REQUETE 30 : Limitation de l'affichage
SELECT m.date_match, t1.name_team AS home_team, t2.name_team AS away_team, p.name_player, e.time_event 
FROM event e 
JOIN player p ON e.player = p.id_player 
JOIN match_foot m ON e.match_event = m.id_match 
JOIN team t1 ON m.home = t1.id_team 
JOIN team t2 ON m.away = t2.id_team 
WHERE e.time_event = 3;

-- View LesJoueurs
CREATE VIEW LesJoueurs
AS
SELECT DISTINCT ON (p.id_player) 
    p.id_player, 
    p.name_player, 
    t.id_team, 
    t.short,
	t.photo_team
FROM player p
JOIN EVENT e ON e.player = p.id_player
JOIN team t ON e.team = t.id_team
ORDER BY p.id_player, e.id_event DESC;


