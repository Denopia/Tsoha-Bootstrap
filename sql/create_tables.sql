-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Trainer(
  trainer_id SERIAL PRIMARY KEY,
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL
);

CREATE TABLE Typing(
  typing_id SERIAL PRIMARY KEY,
  typing_name varchar(15) NOT NULL
);

CREATE TABLE Nature(
  nature_id SERIAL PRIMARY KEY,
  nature_name varchar(15) NOT NULL,
  strong_stat varchar(20) NOT NULL,
  weak_stat varchar(20) NOT NULL
);

CREATE TABLE Ability(
  ability_id SERIAL PRIMARY KEY,
  ability_name varchar(15) NOT NULL,
  description varchar(300) NOT NULL
);

CREATE TABLE Move(
  move_id SERIAL PRIMARY KEY,
  move_name varchar(15) NOT NULL,
  description varchar(300) NOT NULL,
  damage int,
  accuracy int,
  pp int,
  category varchar(15)
);

CREATE TABLE Pokemon(
  pokemon_id SERIAL PRIMARY KEY,
  pokedex_number int NOT NULL,
  species_name varchar(15) NOT NULL,
  base_hp int NOT NULL,
  base_attack int NOT NULL,
  base_defense int NOT NULL,
  base_special_attack int NOT NULL,
  base_special_defense int NOT NULL,
  base_speed int NOT NULL,
  nickname varchar(20),
  gender varchar(15),
  hp int,
  attack int,
  defense int,
  special_attack int,
  special_defense int,
  speed int,
  happiness int,
  iv_hp int,
  iv_attack int,
  iv_defense int,
  iv_special_attack int,
  iv_special_defense int,
  iv_speed int,
  ev_hp int,
  ev_attack int,
  ev_defense int,
  ev_special_attack int,
  ev_special_defense int,
  ev_speed int,
  shiny boolean,
  lvl int
);

CREATE TABLE trainer_pokemon (
  trainer_id int REFERENCES Trainer (trainer_id),
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  PRIMARY KEY (trainer_id, pokemon_id)
);

CREATE TABLE pokemon_typing (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  typing_id int REFERENCES Typing (typing_id),
  PRIMARY KEY (pokemon_id, typing_id)
);

CREATE TABLE move_typing (
  move_id int REFERENCES Move (move_id),
  typing_id int REFERENCES Typing (typing_id),
  PRIMARY KEY (move_id, typing_id)
);

CREATE TABLE pokemon_all_moves (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  move_id int REFERENCES Move (move_id),
  PRIMARY KEY (pokemon_id, move_id)
);

CREATE TABLE pokemon_current_moves (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  move_id int REFERENCES Move (move_id),
  PRIMARY KEY (pokemon_id, move_id)
);

CREATE TABLE pokemon_nature (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  nature_id int REFERENCES Nature (nature_id),
  PRIMARY KEY (pokemon_id, nature_id)
);

CREATE TABLE pokemon_all_abilities (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  ability_id int REFERENCES Ability (ability_id),
  PRIMARY KEY (pokemon_id, ability_id)
);

CREATE TABLE pokemon_current_ability (
  pokemon_id int REFERENCES Pokemon (pokemon_id),
  ability_id int REFERENCES Ability (ability_id),
  PRIMARY KEY (pokemon_id, ability_id)
);

CREATE TABLE super_effective (
  attack_typing_id int REFERENCES Typing (typing_id),
  defense_typing_id int REFERENCES Typing (typing_id),
  PRIMARY KEY (attack_typing_id, defense_typing_id)
);

CREATE TABLE not_effective (
  attack_typing_id int REFERENCES Typing (typing_id),
  defense_typing_id int REFERENCES Typing (typing_id),
  PRIMARY KEY (attack_typing_id, defense_typing_id)
);

CREATE TABLE immune (
  attack_typing_id int REFERENCES Typing (typing_id),
  defense_typing_id int REFERENCES Typing (typing_id),
  PRIMARY KEY (attack_typing_id, defense_typing_id)
);









