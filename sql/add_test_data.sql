-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Trainer (username, password, admini) VALUES ('Steven','Stone', true);
INSERT INTO Trainer (username, password, admini) VALUES ('Archie','Aqua', false);

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
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Modest','Special Attack','Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Mild','Special Attack','Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Quiet','Special Attack','Speed');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Bashful','-','-');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Rash','Special Attack','Special Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Calm','Special Defense','Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Gentle','Special Defense','Defense');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Sassy','Special Defense','Speed');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Careful','Special Defense','Special Attack');
INSERT INTO Nature (nature_name, strong_stat, weak_stat) VALUES ('Quirky','-','-');

INSERT INTO Ability (ability_name, description) VALUES ('No Ability', 'Doesn''t have special ability.');
INSERT INTO Ability (ability_name, description) VALUES ('Overgrow', 'Powers up Grass-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Chlorophyll', 'Boosts the Pokémon''s Speed stat in sunshine.');
INSERT INTO Ability (ability_name, description) VALUES ('Blaze', 'Powers up Fire-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Solar Power', 'Boosts the Sp. Atk stat in sunny weather, but HP decreases.');
INSERT INTO Ability (ability_name, description) VALUES ('Torrent', 'Powers up Water-type moves when the Pokémon is in trouble.');
INSERT INTO Ability (ability_name, description) VALUES ('Rain Dish', 'The Pokémon gradually regains HP in rain.');
INSERT INTO Ability (ability_name, description) VALUES ('Snow Cloak', 'Boosts evasion in a hailstorm.');
INSERT INTO Ability (ability_name, description) VALUES ('Ice Body', 'The Pokémon gradually regains HP in a hailstorm.');

INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES ('Bulbasaur', 1, 45, 49, 49, 65, 65, 45, 12, 4);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES ('Charmander', 4, 39, 52, 43, 60, 50, 65, 10, 19);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES ('Squirtle', 7, 44, 48, 65, 50, 64, 43, 11, 19);
INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES ('Glaceon', 471, 65, 60, 110, 130, 95, 65, 15, 19);

INSERT INTO species_ability (species_id, ability_id) VALUES (1,2);
INSERT INTO species_ability (species_id, ability_id) VALUES (1,3);

INSERT INTO species_ability (species_id, ability_id) VALUES (2,4);
INSERT INTO species_ability (species_id, ability_id) VALUES (2,5);

INSERT INTO species_ability (species_id, ability_id) VALUES (3,6);
INSERT INTO species_ability (species_id, ability_id) VALUES (3,7);

INSERT INTO species_ability (species_id, ability_id) VALUES (4,8);
INSERT INTO species_ability (species_id, ability_id) VALUES (4,9);