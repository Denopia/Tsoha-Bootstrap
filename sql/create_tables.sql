-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Trainer(
  trainer_id SERIAL PRIMARY KEY,
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  admini boolean NOT NULL
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

CREATE TABLE Species(
  species_id SERIAL PRIMARY KEY,
  species_name varchar(15) NOT NULL,
  pokedex_number int NOT NULL,
  base_hp int NOT NULL,
  base_attack int NOT NULL,
  base_defense int NOT NULL,
  base_special_attack int NOT NULL,
  base_special_defense int NOT NULL,
  base_speed int NOT NULL,
  primary_typing INTEGER REFERENCES Typing(typing_id),
  secondary_typing INTEGER REFERENCES Typing(typing_id)
);

CREATE TABLE Pokemon(
  pokemon_id SERIAL PRIMARY KEY,
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
  lvl int,
  nature INTEGER REFERENCES Nature(nature_id),
  current_ability INTEGER REFERENCES Ability(ability_id),
  species INTEGER REFERENCES Species(species_id),
  trainer INTEGER REFERENCES Trainer (trainer_id)
);

CREATE TABLE species_ability (
  species_id int REFERENCES Species (species_id),
  ability_id int REFERENCES Ability (ability_id),
  id SERIAL PRIMARY KEY
);








