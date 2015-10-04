<?php

require_once 'app/models/type.php';
require_once 'app/models/ability.php';

/**
 * Model joka käsittelee tietokannan
 * Species-taulun kyselyitä
 */
class Species extends BaseModel {

    public $species_id,
            $species_name,
            $species_original_name,
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
            $ability3_id,
            $ability1_name,
            $ability2_name,
            $ability3_name;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_number', 'validate_types', 'validate_abilities', 'validate_base_stats');
    }

    /**
     * Validoi lajin nimen siten että sen pituus on 3-15 merkkiä ja 
     * ettei samannimistä lajia ole jo olemassa
     * 
     * @return string
     */
    public function validate_name() {
        $errors = parent::validate_string_length($this->species_name, '3', '15', 'Species name');
        if (count($errors) != 0) {
            return $errors;
        }
        $query = DB::connection()->prepare('SELECT * FROM Species WHERE species_name = :n');
        $query->execute(array('n' => $this->species_name));
        $rows = $query->fetchAll();
        foreach ($rows as $row) {
            if ($this->species_id != $row['species_id']) {
                $errors[] = 'A species with this name already exists';
            }
        }
        return $errors;
    }

    /**
     * Validoi järjestysnumeron siten ettei se ole jo 
     * käytössä ja että se on välillä 1-999
     * 
     * @return string
     */
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
                $errors[] = 'A species with this Pokédex number already exists';
            }
        }
        return $errors;
    }

    /**
     * Validoi tyypit niin ettei ensi- ja toissijaiset tyypit ole identtiset
     * ja että ensisijaisella tyypillä on jokin arvo
     * 
     * @return string
     */
    public function validate_types() {
        $errors = array();
        if ($this->primary_typing == '19') {
            $errors[] = 'Primary type can not be empty';
        } else if ($this->primary_typing == $this->secondary_typing) {
            $errors[] = 'Secondary type can not be the same as primary type';
        }
        return $errors;
    }

    /**
     * 
     * @return stringValidoi ettei ole duplikaattitaitoja ja että on ainakin yksi taito
     */
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

    /**
     * Validoi base statsit välille 1-999
     * @return type
     */
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

    /**
     * Päivittää lajin tietokantaan
     */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Species SET species_name = :name, pokedex_number = :number, base_hp = :hp, base_attack = :attack, base_defense = :defense, base_special_attack = :spattack, base_special_defense = :spdefense, base_speed = :speed, primary_typing = :primary, secondary_typing = :secondary, ability1 = :a1, ability2 = :a2, ability3 = :a3 WHERE species_id = :id;');
        $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing, 'secondary' => $this->secondary_typing, 'a1' => $this->ability1_id, 'a2' => $this->ability2_id, 'a3' => $this->ability3_id, 'id' => $this->species_id));
        $row = $query->fetch();
    }

    /**
     * Poistaa tietyn nimisen taidon kaikilta lajeilta
     * 
     * @param type $id taidon id
     * @param type $name taidon nimi
     */
    public static function removeAbilityFromAll($id, $name) {
        $rows = self::findByAbility($name);
        foreach ($rows as $row) {
            if ($row->ability1_name == $name) {
                $query = DB::connection()->prepare('UPDATE Species SET ability1 = :a WHERE species_id = :id');
                $query->execute(array('a' => '1', 'id' => $row->species_id));
            } else if ($row->ability2_name == $name) {
                $query = DB::connection()->prepare('UPDATE Species SET ability2 = :a WHERE species_id = :id');
                $query->execute(array('a' => '1', 'id' => $row->species_id));
            } else if ($row->ability3_name == $name) {
                $query = DB::connection()->prepare('UPDATE Species SET ability3 = :a WHERE species_id = :id');
                $query->execute(array('a' => '1', 'id' => $row->species_id));
            }
        }
    }

    /**
     * Lisää uuden lajin tietokantaan
     */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing, ability1, ability2, ability3) VALUES (:name, :number, :hp, :attack, :defense, :spattack, :spdefense, :speed, :primary, :secondary, :a1, :a2, :a3) RETURNING species_id');
        $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing, 'secondary' => $this->secondary_typing, 'a1' => $this->ability1_id, 'a2' => $this->ability2_id, 'a3' => $this->ability3_id));
        $row = $query->fetch();
        $pokemonid = $row['species_id'];
    }

    /**
     * Poistaa lajin tietokannasta
     */
    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Species WHERE pokedex_number = :number');
        $query->execute(array('number' => $this->pokedex_number));
    }

    /**
     * Hakee kaikki lajit tietokannasta
     * 
     * @return \Species
     */
    public static function allSpecies() {
        $query = DB::connection()->prepare(
                "SELECT * FROM 
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
                    c.ability_id as ability1_id,  
                    d.ability_id as ability2_id, 
                    e.ability_id as ability3_id, 
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
                'abilities' => $abilities,
                'ability1_id' => $row['ability1_id'],
                'ability2_id' => $row['ability2_id'],
                'ability3_id' => $row['ability3_id'],
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3']
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

    /**
     * Hakee lajin tietokannasta id:n perusteella
     * @param type $id
     * @return \Species
     */
    public static function findById($id) {
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
                    c.ability_id as ability1_id,  
                    d.ability_id as ability2_id, 
                    e.ability_id as ability3_id, 
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
                    AND id = :id
                    ORDER BY id;");
        $query->execute(array('id' => $id));
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
                'species_original_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities,
                'ability1_id' => $row['ability1_id'],
                'ability2_id' => $row['ability2_id'],
                'ability3_id' => $row['ability3_id'],
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3']
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies[0];
    }

    /**
     * Hakee lajin tietokannasta järjestysnumeron perusteella
     * @param type $number järjestysnumero
     * @return \Species
     */
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
c.ability_id as ability1_id,  
d.ability_id as ability2_id, 
e.ability_id as ability3_id, 
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
                'species_original_name' => $row['name'],
                'pokedex_number' => $row['number'],
                'base_hp' => $row['hp'],
                'base_attack' => $row['attack'],
                'base_defense' => $row['defense'],
                'base_special_attack' => $row['special_attack'],
                'base_special_defense' => $row['special_defense'],
                'base_speed' => $row['speed'],
                'types' => $types,
                'abilities' => $abilities,
                'ability1_id' => $row['ability1_id'],
                'ability2_id' => $row['ability2_id'],
                'ability3_id' => $row['ability3_id'],
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3']
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies[0];
    }

    /**
     * Hakee lajin tietokannasta nimen perusteella
     * @param type $name nimi
     * @return \Species
     */
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
c.ability_id as ability1_id,  
d.ability_id as ability2_id, 
e.ability_id as ability3_id, 
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
                'abilities' => $abilities,
                'ability1_id' => $row['ability1_id'],
                'ability2_id' => $row['ability2_id'],
                'ability3_id' => $row['ability3_id'],
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3']
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

    /**
     * Hakee lajin tietokannasta taidon nimen perusteella
     * @param type $name taidon nimi
     * @return \Species
     */
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
c.ability_id as ability1_id,  
d.ability_id as ability2_id, 
e.ability_id as ability3_id, 
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
                'abilities' => $abilities,
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3'],
                'ability1_id' => $row['ability1_id'],
                'ability2_id' => $row['ability2_id'],
                'ability3_id' => $row['ability3_id'],
                'ability1_name' => $row['ability1'],
                'ability2_name' => $row['ability2'],
                'ability3_name' => $row['ability3']
            ));
            unset($abilities);
            unset($types);
        }
        return $allSpecies;
    }

}
