-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Trainer (username, password) VALUES ('Steven','Stone');
INSERT INTO Trainer (username, password) VALUES ('Archie','Aqua');

INSERT INTO Typing (typing_name) VALUES ('Fire');
INSERT INTO Typing (typing_name) VALUES ('Water');
INSERT INTO Typing (typing_name) VALUES ('Grass');
INSERT INTO Typing (typing_name) VALUES ('Poison');
INSERT INTO Typing (typing_name) VALUES ('Flying');

INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Adamant','Attack','Special Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Timid', 'Speed', 'Attack');

INSERT INTO Ability (ability_name, description) VALUES ('Overgrow', 'Powers up Grass-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Chlorophyll', 'Boosts the Pokémon''s Speed stat in sunshine.');
INSERT INTO Ability (ability_name, description) VALUES ('Blaze', 'Powers up Fire-type moves when the Pokémon is in trouble.');

--INSERT INTO Move (move_name, description) VALUES ('Flare Blitz', 'The user cloaks itself in fire and charges the target. This also damages the user quite a lot. This may leave the target with a burn.');


INSERT INTO Move (move_name, description, damage, accuracy, pp, category) VALUES ('Giga Drain', 'A nutrient-draining attack. The user''s HP is restored by half the damage taken by the target.', 75, 100, 10, 'Special');
INSERT INTO Move (move_name, description, damage, accuracy, pp, category) VALUES ('Flare Blitz', 'The user cloaks itself in fire and charges the target. This also damages the user quite a lot. This may leave the target with a burn.', 120, 100, 15, 'Physical');