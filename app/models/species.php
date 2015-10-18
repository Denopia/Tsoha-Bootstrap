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
            $ability1_id,
            $ability2_id,
            $ability3_id,
            $ability4_id,
            $abilities;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_types', 'validate_base_stats', 'validate_abilities');
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
        if ($this->ability1_id == '1' && $this->ability2_id == '1' && $this->ability3_id == '1' && $this->ability4_id == '1') {
            $errors[] = 'Species must have at least one ability';
            return $errors;
        }
        if ($this->ability1_id == $this->ability2_id && $this->ability1_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability1_id == $this->ability3_id && $this->ability1_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability1_id == $this->ability4_id && $this->ability1_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability2_id == $this->ability3_id && $this->ability2_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability2_id == $this->ability4_id && $this->ability2_id != '1') {
            $errors[] = 'Species can not have duplicate abilities';
            return $errors;
        }
        if ($this->ability3_id == $this->ability4_id && $this->ability3_id != '1') {
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
        $query = DB::connection()->prepare(
                'UPDATE Species '
                . 'SET species_name = :name, '
                . 'pokedex_number = :number, '
                . 'base_hp = :hp, '
                . 'base_attack = :attack, '
                . 'base_defense = :defense, '
                . 'base_special_attack = :spattack, '
                . 'base_special_defense = :spdefense, '
                . 'base_speed = :speed, '
                . 'primary_typing = :primary, '
                . 'secondary_typing = :secondary '
                . 'WHERE species_id = :id');
        $query->execute(array(
            'name' => $this->species_name,
            'number' => $this->pokedex_number,
            'hp' => $this->base_hp,
            'attack' => $this->base_attack,
            'defense' => $this->base_defense,
            'spattack' => $this->base_special_attack,
            'spdefense' => $this->base_special_defense,
            'speed' => $this->base_speed,
            'primary' => $this->primary_typing,
            'secondary' => $this->secondary_typing,
            'id' => $this->species_id));
    }

    public function deleteAllAbilities() {
        $query = DB::connection()->prepare("DELETE FROM species_ability WHERE species_id = :id");
        $query->execute(array('id' => $this->species_id));
    }

    /**
     * Poistaa tietyn nimisen taidon kaikilta lajeilta
     * 
     * @param type $id taidon id
     * @param type $name taidon nimi
     */
    public static function removeAbilityFromAll($id) {
        $query = DB::connection()->prepare('DELETE FROM species_ability WHERE ability_id = :id');
        $query->execute(array('id' => $id));
    }

    /**
     * Lisää uuden lajin tietokantaan
     */
    public function save() {
        $query = DB::connection()->prepare(
                'INSERT INTO Species ('
                . 'species_name, '
                . 'pokedex_number, '
                . 'base_hp, '
                . 'base_attack, '
                . 'base_defense, '
                . 'base_special_attack, '
                . 'base_special_defense, '
                . 'base_speed, '
                . 'primary_typing, '
                . 'secondary_typing) '
                . 'VALUES ('
                . ':name, '
                . ':number, '
                . ':hp, '
                . ':attack, '
                . ':defense, '
                . ':spattack, '
                . ':spdefense, '
                . ':speed, '
                . ':primary, '
                . ':secondary) '
                . 'RETURNING species_id');
        $query->execute(array(
            'name' => $this->species_name,
            'number' => $this->pokedex_number,
            'hp' => $this->base_hp,
            'attack' => $this->base_attack,
            'defense' => $this->base_defense,
            'spattack' => $this->base_special_attack,
            'spdefense' => $this->base_special_defense,
            'speed' => $this->base_speed,
            'primary' => $this->primary_typing,
            'secondary' => $this->secondary_typing));
        $row = $query->fetch();
        $this->species_id = $row['species_id'];
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
        $query = DB::connection()->prepare("SELECT * FROM Species ORDER BY pokedex_number");
        $query->execute();
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $types = array();
            if ($row['primary_typing'] != 19) {
                $query2 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
                $query2->execute(array('id' => $row['primary_typing']));
                $type1 = $query2->fetch();
                $types[] = $type1['typing_name'];
            }
            if ($row['secondary_typing'] != 19) {
                $query3 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
                $query3->execute(array('id' => $row['secondary_typing']));
                $type2 = $query3->fetch();
                $types[] = $type2['typing_name'];
            }

            $abilities = array();
            $query4 = DB::connection()->prepare("SELECT * FROM species_ability WHERE species_id = :id");
            $query4->execute(array('id' => $row['species_id']));
            $abilitytable = $query4->fetchAll();
            foreach ($abilitytable as $ab) {
                $abilities[] = Ability::findById($ab['ability_id']);
            }

            $allSpecies[] = new Species(array(
                'species_id' => $row['species_id'],
                'species_name' => $row['species_name'],
                'pokedex_number' => $row['pokedex_number'],
                'base_hp' => $row['base_hp'],
                'base_attack' => $row['base_attack'],
                'base_defense' => $row['base_defense'],
                'base_special_attack' => $row['base_special_attack'],
                'base_special_defense' => $row['base_special_defense'],
                'base_speed' => $row['base_speed'],
                'types' => $types,
                'abilities' => $abilities,
                'primary_typing' => $row['primary_typing'],
                'secondary_typing' => $row['secondary_typing']
            ));
            unset($types);
            unset($abilities);
        }
        return $allSpecies;
    }

    /**
     * Hakee lajin tietokannasta id:n perusteella
     * @param type $id
     * @return \Species
     */
    public static function findById($id) {
        $query = DB::connection()->prepare("SELECT * FROM Species WHERE species_id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $types = array();
        if ($row['primary_typing'] != 19) {
            $query2 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
            $query2->execute(array('id' => $row['primary_typing']));
            $type1 = $query2->fetch();
            $types[] = $type1['typing_name'];
        }
        if ($row['secondary_typing'] != 19) {
            $query3 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
            $query3->execute(array('id' => $row['secondary_typing']));
            $type2 = $query3->fetch();
            $types[] = $type2['typing_name'];
        }

        $abilities = array();
        $query4 = DB::connection()->prepare("SELECT * FROM species_ability WHERE species_id = :id");
        $query4->execute(array('id' => $row['species_id']));
        $abilitytable = $query4->fetchAll();
        foreach ($abilitytable as $ab) {
            $abilities[] = Ability::findById($ab['ability_id']);
        }

        $species = new Species(array(
            'species_id' => $row['species_id'],
            'species_name' => $row['species_name'],
            'pokedex_number' => $row['pokedex_number'],
            'base_hp' => $row['base_hp'],
            'base_attack' => $row['base_attack'],
            'base_defense' => $row['base_defense'],
            'base_special_attack' => $row['base_special_attack'],
            'base_special_defense' => $row['base_special_defense'],
            'base_speed' => $row['base_speed'],
            'types' => $types,
            'abilities' => $abilities,
            'primary_typing' => $row['primary_typing'],
            'secondary_typing' => $row['secondary_typing']
        ));

        return $species;
    }

    /**
     * Hakee lajin tietokannasta järjestysnumeron perusteella
     * @param type $number järjestysnumero
     * @return \Species
     */
    public static function findByNumber($number) {
        $query = DB::connection()->prepare("SELECT * FROM Species WHERE pokedex_number = :number LIMIT 1");
        $query->execute(array('number' => $number));
        $row = $query->fetch();
        $types = array();
        if ($row['primary_typing'] != 19) {
            $query2 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
            $query2->execute(array('id' => $row['primary_typing']));
            $type1 = $query2->fetch();
            $types[] = $type1['typing_name'];
        }
        if ($row['secondary_typing'] != 19) {
            $query3 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
            $query3->execute(array('id' => $row['secondary_typing']));
            $type2 = $query3->fetch();
            $types[] = $type2['typing_name'];
        }

        $abilities = array();
        $query4 = DB::connection()->prepare("SELECT * FROM species_ability WHERE species_id = :id");
        $query4->execute(array('id' => $row['species_id']));
        $abilitytable = $query4->fetchAll();
        foreach ($abilitytable as $ab) {
            $abilities[] = Ability::findById($ab['ability_id']);
        }

        $species = new Species(array(
            'species_id' => $row['species_id'],
            'species_name' => $row['species_name'],
            'pokedex_number' => $row['pokedex_number'],
            'base_hp' => $row['base_hp'],
            'base_attack' => $row['base_attack'],
            'base_defense' => $row['base_defense'],
            'base_special_attack' => $row['base_special_attack'],
            'base_special_defense' => $row['base_special_defense'],
            'base_speed' => $row['base_speed'],
            'types' => $types,
            'abilities' => $abilities,
            'primary_typing' => $row['primary_typing'],
            'secondary_typing' => $row['secondary_typing']
        ));

        return $species;
    }

    /**
     * Hakee lajin tietokannasta nimen perusteella
     * @param type $name nimi
     * @return \Species
     */
    public static function findByNameContaining($name) {
        $query = DB::connection()->prepare("SELECT * FROM Species WHERE UPPER(species_name) LIKE UPPER(:name) ORDER BY pokedex_number");
        $query->execute(array('name' => '%' . $name . '%'));
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $types = array();
            if ($row['primary_typing'] != 19) {
                $query2 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
                $query2->execute(array('id' => $row['primary_typing']));
                $type1 = $query2->fetch();
                $types[] = $type1['typing_name'];
            }
            if ($row['secondary_typing'] != 19) {
                $query3 = DB::connection()->prepare("SELECT * FROM Typing WHERE typing_id = :id LIMIT 1");
                $query3->execute(array('id' => $row['secondary_typing']));
                $type2 = $query3->fetch();
                $types[] = $type2['typing_name'];
            }

            $abilities = array();
            $query4 = DB::connection()->prepare("SELECT * FROM species_ability WHERE species_id = :id");
            $query4->execute(array('id' => $row['species_id']));
            $abilitytable = $query4->fetchAll();
            foreach ($abilitytable as $ab) {
                $abilities[] = Ability::findById($ab['ability_id']);
            }

            $allSpecies[] = new Species(array(
                'species_id' => $row['species_id'],
                'species_name' => $row['species_name'],
                'pokedex_number' => $row['pokedex_number'],
                'base_hp' => $row['base_hp'],
                'base_attack' => $row['base_attack'],
                'base_defense' => $row['base_defense'],
                'base_special_attack' => $row['base_special_attack'],
                'base_special_defense' => $row['base_special_defense'],
                'base_speed' => $row['base_speed'],
                'types' => $types,
                'abilities' => $abilities,
                'primary_typing' => $row['primary_typing'],
                'secondary_typing' => $row['secondary_typing']
            ));
            unset($types);
            unset($abilities);
        }
        return $allSpecies;
    }

    /**
     * Hakee lajin tietokannasta taidon nimen perusteella
     * @param type $name taidon id
     * @return \Species
     */
    public static function findByAbility($id) {
        $species = array();
        $query4 = DB::connection()->prepare("SELECT * FROM species_ability WHERE ability_id = :id");
        $query4->execute(array('id' => $id));
        $species_and_abilities = $query4->fetchAll();
        foreach ($species_and_abilities as $pair) {
            $species[] = self::findById($pair['species_id']);
        }
        return $species;
    }

}
