-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Trainer (username, password) VALUES ('Steven','Stone');
INSERT INTO Trainer (username, password) VALUES ('Archie','Aqua');

INSERT INTO Typing (typing_name) VALUES ('Normal');
INSERT INTO Typing (typing_name) VALUES ('Fighting');
INSERT INTO Typing (typing_name) VALUES ('Flying');
INSERT INTO Typing (typing_name) VALUES ('Poison');
INSERT INTO Typing (typing_name) VALUES ('Ground');
INSERT INTO Typing (typing_name) VALUES ('Rock');
INSERT INTO Typing (typing_name) VALUES ('Bug');
INSERT INTO Typing (typing_name) VALUES ('Ghost');
INSERT INTO Typing (typing_name) VALUES ('Steel');
INSERT INTO Typing (typing_name) VALUES ('Fire');
INSERT INTO Typing (typing_name) VALUES ('Water');
INSERT INTO Typing (typing_name) VALUES ('Grass');
INSERT INTO Typing (typing_name) VALUES ('Electric');
INSERT INTO Typing (typing_name) VALUES ('Psychic');
INSERT INTO Typing (typing_name) VALUES ('Ice');
INSERT INTO Typing (typing_name) VALUES ('Dragon');
INSERT INTO Typing (typing_name) VALUES ('Dark');
INSERT INTO Typing (typing_name) VALUES ('Fairy');
INSERT INTO Typing (typing_name) VALUES ('N/A');

INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Hardy','-','-');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Lonely','Attack','Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Brave','Attack','Speed');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Adamant','Attack','Special Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Naughty','Attack','Special Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Bold','Defense','Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Docile','-','-');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Relaxed','Defense','Speed');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Impish','Defense','Special Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Lax','Defense','Special Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Timid','Speed','Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Hasty','Speed','Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Serious','-','-');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Jolly', 'Speed', 'Special Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Naive', 'Speed', 'Special Defense');

INSERT INTO Ability (ability_name, description) VALUES ('No Ability', 'Doesn''t have special ability.');
INSERT INTO Ability (ability_name, description) VALUES ('Overgrow', 'Powers up Grass-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Chlorophyll', 'Boosts the Pokémon''s Speed stat in sunshine.');
INSERT INTO Ability (ability_name, description) VALUES ('Blaze', 'Powers up Fire-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Solar Power', 'Boosts the Sp. Atk stat in sunny weather, but HP decreases.');
INSERT INTO Ability (ability_name, description) VALUES ('Torrent', 'Powers up Water-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Rain Dish', 'The Pokémon gradually regains HP in rain.');
INSERT INTO Ability (ability_name, description) VALUES ('Snow Cloak', 'Boosts evasion in a hailstorm.');
INSERT INTO Ability (ability_name, description) VALUES ('Ice Body', 'The Pokémon gradually regains HP in a hailstorm.');

INSERT INTO Move (move_name, description, power, accuracy, pp, category, typing) VALUES ('Giga Drain', 'A nutrient-draining attack. The user''s HP is restored by half the damage taken by the target.', 75, 100, 10, 'Special', 12);
INSERT INTO Move (move_name, description, power, accuracy, pp, category, typing) VALUES ('Flare Blitz', 'The user cloaks itself in fire and charges the target. This also damages the user quite a lot. This may leave the target with a burn.', 120, 100, 15, 'Physical', 10);

INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES ('Bulbasaur', 1, 45, 49, 49, 65, 65, 45, 12, 4, 2, 3, 1);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES ('Charmander', 4, 39, 52, 43, 60, 50, 65, 10, 19, 4, 5, 1);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES ('Squirtle', 7, 44, 48, 65, 50, 64, 43, 11, 19, 6, 7, 1);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES ('Glaceon', 471, 65, 60, 110, 130, 95, 65, 15, 19, 8, 9, 1);

INSERT INTO Pokemon(nickname, gender, lvl, nature, current_ability, species) VALUES ('Kukkaruukku', 'Male', 5, 6, 1, 1);