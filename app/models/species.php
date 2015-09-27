<?php

require_once 'app/models/type.php';
require_once 'app/models/ability.php';

class Species extends BaseModel {

    public $species_id,
            $species_name,
            $pokedex_number,
            $base_hp,
            $base_attack,
            $base_defense,
            $base_special_attack,
            $base_special_defense,
            $base_speed,
            $types,
            $primary_typing,
            $secondary_typing,
            $abilities,
            $ability1_id,
            $ability2_id,
            $ability3_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_number', 'validate_types', 'validate_abilities', 'validate_base_stats');
    }

    public function validate_name() {
        return parent::validate_string_length($this->species_name, '3', '15', 'Species name');
    }

    public function validate_number() {
        $errors = parent::validate_integer($this->pokedex_number, '1', '999', 'Pokedex number');
        if (count($errors) != 0) {
            return $errors;
        }
        $query = DB::connection()->prepare('SELECT * FROM Species WHERE pokedex_number = :n');
        $query->execute(array('n' => $this->pokedex_number));
        $rows = $query->fetchAll();
        foreach ($rows as $row) {
            if ($this->species_id != $row['species_id']) {
                $errors[] = 'A Pokémon with this Pokédex number already exists';
            }
        }
        return $errors;
    }

    public function validate_types() {
        $errors = array();
        if ($this->primary_typing == '19') {
            $errors[] = 'Primary type can not be empty';
        } else if ($this->primary_typing == $this->secondary_typing) {
            $errors[] = 'Secondary type can not be same as primary type';
        }
        return $errors;
    }

    public function validate_abilities() {
        $errors = array();
        if ($this->ability1_id == '1' && $this->ability2_id == '1' && $this->ability3_id == '1') {
            $errors[] = 'Species must have at least one ability';
            return $errors;
        }
        if ($this->ability1_id == $this->ability2_id && $this->ability1_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability2_id == $this->ability3_id && $this->ability2_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability3_id == $this->ability1_id && $this->ability3_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        return $errors;
    }

    public function validate_base_stats() {
        $errors = array();
        $errors = array_merge($errors, parent::validate_integer($this->base_hp, '1', '999', 'Base hp'));
        $errors = array_merge($errors, parent::validate_integer($this->base_attack, '1', '999', 'Base attack'));
        $errors = array_merge($errors, parent::validate_integer($this->base_defense, '1', '999', 'Base defense'));
        $errors = array_merge($errors, parent::validate_integer($this->base_special_attack, '1', '999', 'Base special attack'));
        $errors = array_merge($errors, parent::validate_integer($this->base_special_defense, '1', '999', 'Base special defense'));
        $errors = array_merge($errors, parent::validate_integer($this->base_speed, '1', '999', 'Base speed'));
        return $errors;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Species SET species_name = :name, pokedex_number = :number, base_hp = :hp, base_attack = :attack, base_defense = :defense, base_special_attack = :spattack, base_special_defense = :spdefense, base_speed = :speed, primary_typing = :primary, secondary_typing = :secondary, ability1 = :a1, ability2 = :a2, ability3 = :a3 WHERE species_id = :id;');
        $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing, 'secondary' => $this->secondary_typing, 'a1' => $this->ability1_id, 'a2' => $this->ability2_id, 'a3' => $this->ability3_id, 'id' => $this->species_id));
        $row = $query->fetch();
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES (:name, :number, :hp, :attack, :defense, :spattack, :spdefense, :speed, :primary, :secondary, :a1, :a2, :a3) RETURNING species_id');
        $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing, 'secondary' => $this->secondary_typing, 'a1' => $this->ability1_id, 'a2' => $this->ability2_id, 'a3' => $this->ability3_id));
        $row = $query->fetch();
        $pokemonid = $row['species_id'];
    }
    
    public function delete(){
        $query = DB::connection()->prepare('DELETE FROM Species WHERE pokedex_number = :number');
        $query->execute(array('number' => $this->pokedex_number));
    }

    public static function allSpecies() {
        $query = DB::connection()->prepare(
                "SELECT * 
FROM 
(
SELECT ROW_NUMBER() OVER(PARTITION BY species.pokedex_number ORDER BY species.pokedex_number) as rownum, 
species.species_id as id, 
species.pokedex_number as number, 
species.species_name as name,
a.typing_name as primary, 
b.typing_name as secondary, 
c.ability_name as ability1,  
d.ability_name as ability2, 
e.ability_name as ability3, 
species.base_hp as hp, 
species.base_attack as attack, 
species.base_defense as defense, 
species.base_special_attack as special_attack, 
species.base_special_defense as special_defense, 
species.base_speed as speed
FROM species, 
typing a, 
typing b, 
ability c,
ability d, 
ability e
WHERE species.primary_typing = a.typing_id
and species.secondary_typing = b.typing_id
and c.ability_id = species.ability1
and d.ability_id = species.ability2
and e.ability_id = species.ability3
)a
WHERE a.rownum = 1
ORDER BY number;");
        $query->execute();
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $abilities = array();
            if ($row['ability1'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability1']);
            }
            if ($row['ability2'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability2']);
            }
            if ($row['ability3'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability3']);
            }
            $types = array();
            $types[] = $row['primary'];
            if ($row['secondary'] != 'N/A') {
                $types[] = $row['secondary'];
            }
            $allSpecies[] = new Species(array(
                'species_id' => $row['id'],
                'species_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

    public static function findByNumber($number) {
        $query = DB::connection()->prepare("SELECT * 
FROM 
(
SELECT ROW_NUMBER() OVER(PARTITION BY species.pokedex_number ORDER BY species.pokedex_number) as rownum, 
species.species_id as id, 
species.pokedex_number as number, 
species.species_name as name,
a.typing_name as primary, 
b.typing_name as secondary, 
c.ability_name as ability1,  
d.ability_name as ability2, 
e.ability_name as ability3, 
species.base_hp as hp, 
species.base_attack as attack, 
species.base_defense as defense, 
species.base_special_attack as special_attack, 
species.base_special_defense as special_defense, 
species.base_speed as speed
FROM species, 
typing a, 
typing b, 
ability c,
ability d, 
ability e
WHERE species.primary_typing = a.typing_id
and species.secondary_typing = b.typing_id
and c.ability_id = species.ability1
and d.ability_id = species.ability2
and e.ability_id = species.ability3)a
WHERE a.rownum = 1
AND number = :number
ORDER BY number;");
        $query->execute(array('number' => $number));
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $abilities = array();
            if ($row['ability1'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability1']);
            }
            if ($row['ability2'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability2']);
            }
            if ($row['ability3'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability3']);
            }
            $types = array();
            $types[] = $row['primary'];
            if ($row['secondary'] != 'N/A') {
                $types[] = $row['secondary'];
            }
            $allSpecies[] = new Species(array(
                'species_id' => $row['id'],
                'species_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies[0];
    }

    public static function findByName($name) {
        $query = DB::connection()->prepare("SELECT * 
FROM 
(
SELECT ROW_NUMBER() OVER(PARTITION BY species.pokedex_number ORDER BY species.pokedex_number) as rownum, 
species.species_id as id, 
species.pokedex_number as number, 
species.species_name as name,
a.typing_name as primary, 
b.typing_name as secondary, 
c.ability_name as ability1,  
d.ability_name as ability2, 
e.ability_name as ability3, 
species.base_hp as hp, 
species.base_attack as attack, 
species.base_defense as defense, 
species.base_special_attack as special_attack, 
species.base_special_defense as special_defense, 
species.base_speed as speed
FROM species, 
typing a, 
typing b, 
ability c,
ability d, 
ability e
WHERE species.primary_typing = a.typing_id
and species.secondary_typing = b.typing_id
and c.ability_id = species.ability1
and d.ability_id = species.ability2
and e.ability_id = species.ability3)a
WHERE a.rownum = 1
AND name LIKE :name
ORDER BY number;");
        $query->execute(array('name' => "%$name%"));
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $abilities = array();
            if ($row['ability1'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability1']);
            }
            if ($row['ability2'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability2']);
            }
            if ($row['ability3'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability3']);
            }
            $types = array();
            $types[] = $row['primary'];
            if ($row['secondary'] != 'N/A') {
                $types[] = $row['secondary'];
            }
            $allSpecies[] = new Species(array(
                'species_id' => $row['id'],
                'species_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

    public static function findByAbility($name) {
        $query = DB::connection()->prepare("SELECT * 
FROM 
(
SELECT ROW_NUMBER() OVER(PARTITION BY species.pokedex_number ORDER BY species.pokedex_number) as rownum, 
species.species_id as id, 
species.pokedex_number as number, 
species.species_name as name,
a.typing_name as primary, 
b.typing_name as secondary, 
c.ability_name as ability1,  
d.ability_name as ability2, 
e.ability_name as ability3, 
species.base_hp as hp, 
species.base_attack as attack, 
species.base_defense as defense, 
species.base_special_attack as special_attack, 
species.base_special_defense as special_defense, 
species.base_speed as speed
FROM species, 
typing a, 
typing b, 
ability c,
ability d, 
ability e
WHERE species.primary_typing = a.typing_id
and species.secondary_typing = b.typing_id
and c.ability_id = species.ability1
and d.ability_id = species.ability2
and e.ability_id = species.ability3)a
WHERE a.rownum = 1
AND(
a.ability1 = :name
OR a.ability2 = :name
OR a.ability2 = :name
)
ORDER BY number;");
        $query->execute(array('name' => $name));
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            if ($row['ability1'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability1']);
            }
            if ($row['ability2'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability2']);
            }
            if ($row['ability3'] != 'No Ability') {
                $abilities[] = Ability::findByName($row['ability3']);
            }
            $types = array();
            $types[] = $row['primary'];
            if ($row['secondary'] != 'N/A') {
                $types[] = $row['secondary'];
            }
            $allSpecies[] = new Species(array(
                'species_id' => $row['id'],
                'species_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

}
