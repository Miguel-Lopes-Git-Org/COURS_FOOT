DROP TABLE IF EXISTS team CASCADE;
DROP TABLE IF EXISTS player CASCADE;
DROP TABLE IF EXISTS match_foot CASCADE;
DROP TABLE IF EXISTS type_de_event CASCADE;
DROP TABLE IF EXISTS event CASCADE;

CREATE TABLE users(
	identifiant VARCHAR(50),
	password VARCHAR(50),
	PRIMARY KEY(identifiant)
);

INSERT INTO users VALUES ('test', 'test');

CREATE TABLE team(
   id_team INT,
   name_team VARCHAR(50),
   surname VARCHAR(50),
   short VARCHAR(50),
   birthday VARCHAR(4),
   city VARCHAR(50),
   photo_team VARCHAR(300),
   PRIMARY KEY(id_team)
);

CREATE TABLE player(
   id_player INT,
   name_player VARCHAR(50),
   birthday DATE,
   photo_player VARCHAR(300),
   PRIMARY KEY(id_player)
);

CREATE TABLE type_de_event(
   type VARCHAR(50),
   lib VARCHAR(100),
   PRIMARY KEY(type)
);

CREATE TABLE match_foot(
   id_match INT,
   date_match DATE,
   home INT,
   away INT,
   stage INT,
   PRIMARY KEY(id_match),
   FOREIGN KEY(home) REFERENCES team(id_team),
   FOREIGN KEY(away) REFERENCES team(id_team)
);

CREATE TABLE EVENT(
   id_event INT,
   match_event INT,
   time_event INT,
   player INT,
   team INT,
   type VARCHAR(50),
   photo_event VARCHAR(300),
   video_event VARCHAR(300),
   PRIMARY KEY(id_event),
   FOREIGN KEY(type) REFERENCES type_de_event(type),
   FOREIGN KEY(team) REFERENCES team(id_team),
   FOREIGN KEY(match_event) REFERENCES match_foot(id_match),
   FOREIGN KEY(player) REFERENCES player(id_player)
);

INSERT INTO type_de_event VALUES 
('goal', 'but'), 
('penalty', 'but sur penalty'), 
('own', 'but contre son camp'), 
('miss', 'penalty raté'), 
('yellow', 'carton jaune'), 
('yellow2', 'deuxième carton jaune'), 
('red', 'carton rouge');
