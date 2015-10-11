<?php

require_once 'app/models/type.php';
require_once 'app/models/ability.php';
require_once 'app/models/species.php';

/**
 * Model joka käsittelee tietokannan
 * Pokemon-taulun kyselyitä
 */
class Pokemon extends BaseModel {

    public $pokemon_id,
            $nickname,
            $gender,
            $hp,
            $attack,
            $defense,
            $special_attack,
            $special_defense,
            $speed,
            $happiness,
            $iv_hp,
            $iv_attack,
            $iv_defense,
            $iv_special_attack,
            $iv_special_defense,
            $iv_speed,
            $ev_hp,
            $ev_attack,
            $ev_defense,
            $ev_special_attack,
            $ev_special_defense,
            $ev_speed,
            $shiny,
            $lvl,
            $nature,
            $nature_name,
            $current_ability,
            $current_ability_name,
            $species,
            $trainer,
            $speciesmodel;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_nickname', 'validate_lvl', 'validate_stats', 'validate_happiness');
    }

    /**
     * Validoi onnellisuuden arvon
     * @return type
     */
    public function validate_happiness() {
        $errors = parent::validate_integer($this->happiness, '0', '255', 'Happiness');
        return $errors;
    }

    /**
     * Validoi tason arvon
     * @return type
     */
    public function validate_lvl() {
        $errors = parent::validate_integer($this->lvl, '0', '100', 'Level');
        return $errors;
    }

    /**
     * Validoi lempinimen pituuden
     * @return type
     */
    public function validate_nickname() {
        $errors = parent::validate_string_length($this->nickname, '0', '15', 'Nickname');
        return $errors;
    }

    /**
     * Vaidoi statsit
     * @return type
     */
    public function validate_stats() {
        $errors = array();
        $errors = array_merge($errors, parent::validate_integer($this->hp, '0', '999', 'Hp'));
        $errors = array_merge($errors, parent::validate_integer($this->attack, '0', '999', 'Attack'));
        $errors = array_merge($errors, parent::validate_integer($this->defense, '0', '999', 'Defense'));
        $errors = array_merge($errors, parent::validate_integer($this->special_attack, '0', '999', 'Special Attack'));
        $errors = array_merge($errors, parent::validate_integer($this->special_defense, '0', '999', 'Special Defense'));
        $errors = array_merge($errors, parent::validate_integer($this->speed, '0', '999', 'Speed'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_hp, '0', '255', 'Hp EV'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_attack, '0', '255', 'Attack EV'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_defense, '0', '255', 'Defense EV'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_special_attack, '0', '255', 'Special Attack EV'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_special_defense, '0', '255', 'Special Defense EV'));
        $errors = array_merge($errors, parent::validate_integer($this->ev_speed, '0', '255', 'Speed EV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_hp, '0', '31', 'Hp IV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_attack, '0', '31', 'Attack IV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_defense, '0', '31', 'Defense IV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_special_attack, '0', '31', 'Special Attack IV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_special_defense, '0', '31', 'Special Defense IV'));
        $errors = array_merge($errors, parent::validate_integer($this->iv_speed, '0', '31', 'Speed IV'));
        return $errors;
    }

    /**
     * Päivittää Pokemonin tietokantaan
     */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Pokemon '
                . 'SET nickname = :nickname, '
                . 'gender = :gender, '
                . 'hp = :hp, '
                . 'attack = :attack, '
                . 'defense = :defense, '
                . 'special_attack = :special_attack, '
                . 'special_defense = :special_defense, '
                . 'speed = :speed, '
                . 'happiness = :happiness, '
                . 'iv_hp = :iv_hp, '
                . 'iv_attack = :iv_attack, '
                . 'iv_defense = :iv_defense, '
                . 'iv_special_attack = :iv_special_attack, '
                . 'iv_special_defense = :iv_special_defense, '
                . 'iv_speed = :iv_speed, '
                . 'ev_hp = :ev_hp, '
                . 'ev_attack = :ev_attack, '
                . 'ev_defense = :ev_defense, '
                . 'ev_special_attack = :ev_special_attack, '
                . 'ev_special_defense = :ev_special_defense, '
                . 'ev_speed = :ev_speed, '
                . 'shiny = :shiny, '
                . 'lvl = :lvl, '
                . 'nature = :nature, '
                . 'current_ability = :current_ability, '
                . 'species = :species '
                . 'WHERE pokemon_id = :pokemon_id;');
        $query->execute(array(
            'pokemon_id' => $this->pokemon_id,
            'nickname' => $this->nickname,
            'gender' => $this->gender,
            'hp' => $this->hp,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'special_attack' => $this->special_attack,
            'special_defense' => $this->special_defense,
            'speed' => $this->speed,
            'happiness' => $this->happiness,
            'iv_hp' => $this->iv_hp,
            'iv_attack' => $this->iv_attack,
            'iv_defense' => $this->iv_defense,
            'iv_special_attack' => $this->iv_special_attack,
            'iv_special_defense' => $this->iv_special_defense,
            'iv_speed' => $this->iv_speed,
            'ev_hp' => $this->ev_hp,
            'ev_attack' => $this->ev_attack,
            'ev_defense' => $this->ev_defense,
            'ev_special_attack' => $this->ev_special_attack,
            'ev_special_defense' => $this->ev_special_defense,
            'ev_speed' => $this->ev_speed,
            'shiny' => $this->shiny,
            'lvl' => $this->lvl,
            'nature' => $this->nature,
            'current_ability' => $this->current_ability,
            'species' => $this->species
        ));
    }

    /**
     * Tallentaa uuden pokemonin tietokantaan
     */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Pokemon '
                . '(nickname, '
                . 'gender, '
                . 'hp, '
                . 'attack, '
                . 'defense, '
                . 'special_attack, '
                . 'special_defense, '
                . 'speed, '
                . 'happiness, '
                . 'iv_hp, '
                . 'iv_attack, '
                . 'iv_defense, '
                . 'iv_special_attack, '
                . 'iv_special_defense, '
                . 'iv_speed, '
                . 'ev_hp, '
                . 'ev_attack, '
                . 'ev_defense, '
                . 'ev_special_attack, '
                . 'ev_special_defense, '
                . 'ev_speed, '
                . 'shiny, '
                . 'lvl, '
                . 'nature, '
                . 'current_ability, '
                . 'species, '
                . 'trainer) '
                . 'VALUES '
                . '(:nickname, '
                . ':gender, '
                . ':hp, '
                . ':attack, '
                . ':defense, '
                . ':special_attack, '
                . ':special_defense, '
                . ':speed, '
                . ':happiness, '
                . ':iv_hp, '
                . ':iv_attack, '
                . ':iv_defense, '
                . ':iv_special_attack, '
                . ':iv_special_defense, '
                . ':iv_speed, '
                . ':ev_hp, '
                . ':ev_attack, '
                . ':ev_defense, '
                . ':ev_special_attack, '
                . ':ev_special_defense, '
                . ':ev_speed, '
                . ':shiny, '
                . ':lvl, '
                . ':nature, '
                . ':current_ability, '
                . ':species, '
                . ':trainer) '
                . 'RETURNING pokemon_id');
        $query->execute(array(
            'nickname' => $this->nickname,
            'gender' => $this->gender,
            'hp' => $this->hp,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'special_attack' => $this->special_attack,
            'special_defense' => $this->special_defense,
            'speed' => $this->speed,
            'happiness' => $this->happiness,
            'iv_hp' => $this->iv_hp,
            'iv_attack' => $this->iv_attack,
            'iv_defense' => $this->iv_defense,
            'iv_special_attack' => $this->iv_special_attack,
            'iv_special_defense' => $this->iv_special_defense,
            'iv_speed' => $this->iv_speed,
            'ev_hp' => $this->ev_hp,
            'ev_attack' => $this->ev_attack,
            'ev_defense' => $this->ev_defense,
            'ev_special_attack' => $this->ev_special_attack,
            'ev_special_defense' => $this->ev_special_defense,
            'ev_speed' => $this->ev_speed,
            'shiny' => $this->shiny,
            'lvl' => $this->lvl,
            'nature' => $this->nature,
            'current_ability' => $this->current_ability,
            'species' => $this->species,
            'trainer' => $this->trainer
        ));
//        $row = $query->fetch();
//        $pokemonid = $row['pokemon_id'];
    }

    /**
     * Poistaa pokemonin tietokannasta
     */
    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Pokemon WHERE pokemon_id = :pokemon_id');
        $query->execute(array('pokemon_id' => $this->pokemon_id));
    }

    public function removeAllWithThisSpecies($number) {
        $species = Species::findByNumber($number);
        $query = DB::connection()->prepare('DELETE FROM Pokemon WHERE species = :n');
        $query->execute(array('n' => $species->species_id));
    }

    public static function removeAbilityFromAll($id, $name) {
        $rows = self::findAll();
        foreach ($rows as $row) {
            if ($row->current_ability_name == $name) {
                $query = DB::connection()->prepare('UPDATE Pokemon SET current_ability = :a WHERE pokemon_id = :id');
                $query->execute(array('a' => '1', 'id' => $row->pokemon_id));
            }
        }
    }

    public function findAll() {
        $query = DB::connection()->prepare("SELECT * FROM Pokemon ORDER BY nickname");
        $query->execute();
        $rows = $query->fetchAll();
        $allPokemon = array();
        foreach ($rows as $row) {
            $speciesmodel = Species::findById($row['species']);
            $query2 = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_id = :aid");
            $query2->execute(array('aid' => $row['current_ability']));
            $rows2 = $query2->fetchAll();
            foreach ($rows2 as $row2) {
                $current_ability = $row2['ability_name'];
            }
            $query3 = DB::connection()->prepare("SELECT * FROM Nature WHERE nature_id = :nid");
            $query3->execute(array('nid' => $row['nature']));
            $rows3 = $query3->fetchAll();
            foreach ($rows3 as $row3) {
                $current_nature = $row3['nature_name'];
            }
            $allPokemon[] = new Pokemon(array(
                'pokemon_id' => $row['pokemon_id'],
                'nickname' => $row['nickname'],
                'gender' => $row['gender'],
                'hp' => $row['hp'],
                'attack' => $row['attack'],
                'defense' => $row['defense'],
                'special_attack' => $row['special_attack'],
                'special_defense' => $row['special_defense'],
                'speed' => $row['speed'],
                'happiness' => $row['happiness'],
                'iv_hp' => $row['iv_hp'],
                'iv_attack' => $row['iv_attack'],
                'iv_defense' => $row['iv_defense'],
                'iv_special_attack' => $row['iv_special_attack'],
                'iv_special_defense' => $row['iv_special_defense'],
                'iv_speed' => $row['iv_speed'],
                'ev_hp' => $row['ev_hp'],
                'ev_attack' => $row['ev_attack'],
                'ev_defense' => $row['ev_defense'],
                'ev_special_attack' => $row['ev_special_attack'],
                'ev_special_defense' => $row['ev_special_defense'],
                'ev_speed' => $row['ev_speed'],
                'shiny' => $row['shiny'],
                'lvl' => $row['lvl'],
                'nature' => $row['nature'],
                'current_ability' => $row['current_ability'],
                'species' => $row['species'],
                'trainer' => $row['trainer'],
                'speciesmodel' => $speciesmodel,
                'current_ability_name' => $current_ability,
                'nature_name' => $current_nature
            ));
        }
        return $allPokemon;
    }

    /**
     * Etsii tietokannasta pokemoneja kouluttajan id:n perusteella
     * @param type $trainer
     * @return \Pokemon
     */
    public function findByUser($trainer) {
        $query = DB::connection()->prepare("SELECT * FROM Pokemon WHERE trainer =:trainer ORDER BY nickname");
        $query->execute(array('trainer' => $trainer));
        $rows = $query->fetchAll();
        $allPokemon = array();
        foreach ($rows as $row) {
            $speciesmodel = Species::findById($row['species']);
            $query2 = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_id = :aid");
            $query2->execute(array('aid' => $row['current_ability']));
            $rows2 = $query2->fetchAll();
            foreach ($rows2 as $row2) {
                $current_ability = $row2['ability_name'];
            }
            $query3 = DB::connection()->prepare("SELECT * FROM Nature WHERE nature_id = :nid");
            $query3->execute(array('nid' => $row['nature']));
            $rows3 = $query3->fetchAll();
            foreach ($rows3 as $row3) {
                $current_nature = $row3['nature_name'];
            }
            $allPokemon[] = new Pokemon(array(
                'pokemon_id' => $row['pokemon_id'],
                'nickname' => $row['nickname'],
                'gender' => $row['gender'],
                'hp' => $row['hp'],
                'attack' => $row['attack'],
                'defense' => $row['defense'],
                'special_attack' => $row['special_attack'],
                'special_defense' => $row['special_defense'],
                'speed' => $row['speed'],
                'happiness' => $row['happiness'],
                'iv_hp' => $row['iv_hp'],
                'iv_attack' => $row['iv_attack'],
                'iv_defense' => $row['iv_defense'],
                'iv_special_attack' => $row['iv_special_attack'],
                'iv_special_defense' => $row['iv_special_defense'],
                'iv_speed' => $row['iv_speed'],
                'ev_hp' => $row['ev_hp'],
                'ev_attack' => $row['ev_attack'],
                'ev_defense' => $row['ev_defense'],
                'ev_special_attack' => $row['ev_special_attack'],
                'ev_special_defense' => $row['ev_special_defense'],
                'ev_speed' => $row['ev_speed'],
                'shiny' => $row['shiny'],
                'lvl' => $row['lvl'],
                'nature' => $row['nature'],
                'current_ability' => $row['current_ability'],
                'species' => $row['species'],
                'trainer' => $row['trainer'],
                'speciesmodel' => $speciesmodel,
                'current_ability_name' => $current_ability,
                'nature_name' => $current_nature
            ));
        }
        return $allPokemon;
    }

    /**
     * Etsii Pokemoneja tietokannasta kouluttajan ja lempinimen perusteella
     * @param type $user kouluttaja
     * @param type $name lempinimi
     * @return type
     */
    public function findByUserAndNickname($user, $name) {
        $allpokemon = self::findByUser($user);
        $wantedpokemon = array();
        foreach ($allpokemon as $pokemon) {
            if (strpos(strtoupper($pokemon->nickname), strtoupper($name)) !== false) {
                $wantedpokemon[] = $pokemon;
            }
        }
        return $wantedpokemon;
    }

    /**
     * Etsii Pokemoneja tietokannasta kouluttajan ja tyypin perusteella
     * @param type $types tyypit
     * @return type
     */
    public function findByUserAndType($types) {
        $allpokemon = self::findByUser();
        $wantedpokemon = array();
        foreach ($allpokemon as $pokemon) {
            foreach ($types as $type) {
                if ($type == $pokemon->speciesmodel->primary_typing || $type == $pokemon->speciesmodel->secondary_typing) {
                    $wantedpokemon[] = $pokemon;
                    break;
                }
            }
        }
        return $wantedpokemon;
    }

    /**
     * Etsii tietokannasta pokemonit kouluttajan, tyypin ja lempinimen mukaan
     * @param type $name lempinimi
     * @param type $types tyypit
     * @return type
     */
    public function findByUserTypeAndName($name, $types) {
        $allpokemon = self::findByUser();
        $almostwantedpokemon = array();
        foreach ($allpokemon as $pokemon) {
            if (strpos(strtoupper($pokemon->nickname), strtoupper($name)) !== false) {
                $almostwantedpokemon[] = $pokemon;
            }
        }
        $wantedpokemon = array();
        foreach ($almostwantedpokemon as $pokemon) {
            foreach ($types as $type) {
                if ($type == $pokemon->speciesmodel->primary_typing || $type == $pokemon->speciesmodel->secondary_typing) {
                    $wantedpokemon[] = $pokemon;
                    break;
                }
            }
        }
        return $wantedpokemon;
    }

    /**
     * Etsii pokemonin tietokannasta id:n mukaan
     * @param type $id id
     * @return \Pokemon
     */
    public function findById($id) {
        $query = DB::connection()->prepare("SELECT * FROM Pokemon WHERE pokemon_id =:id LIMIT 1");
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $allPokemon = array();

        foreach ($rows as $row) {
            $query2 = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_id = :aid");
            $query2->execute(array('aid' => $row['current_ability']));
            $rows2 = $query2->fetchAll();
            foreach ($rows2 as $row2) {
                $current_ability = $row2['ability_name'];
            }
            $query3 = DB::connection()->prepare("SELECT * FROM Nature WHERE nature_id = :nid");
            $query3->execute(array('nid' => $row['nature']));
            $rows3 = $query3->fetchAll();
            foreach ($rows3 as $row3) {
                $current_nature = $row3['nature_name'];
            }
            $speciesmodel = Species::findById($row['species']);
            $allPokemon[] = new Pokemon(array(
                'pokemon_id' => $row['pokemon_id'],
                'nickname' => $row['nickname'],
                'gender' => $row['gender'],
                'hp' => $row['hp'],
                'attack' => $row['attack'],
                'defense' => $row['defense'],
                'special_attack' => $row['special_attack'],
                'special_defense' => $row['special_defense'],
                'speed' => $row['speed'],
                'happiness' => $row['happiness'],
                'iv_hp' => $row['iv_hp'],
                'iv_attack' => $row['iv_attack'],
                'iv_defense' => $row['iv_defense'],
                'iv_special_attack' => $row['iv_special_attack'],
                'iv_special_defense' => $row['iv_special_defense'],
                'iv_speed' => $row['iv_speed'],
                'ev_hp' => $row['ev_hp'],
                'ev_attack' => $row['ev_attack'],
                'ev_defense' => $row['ev_defense'],
                'ev_special_attack' => $row['ev_special_attack'],
                'ev_special_defense' => $row['ev_special_defense'],
                'ev_speed' => $row['ev_speed'],
                'shiny' => $row['shiny'],
                'lvl' => $row['lvl'],
                'nature' => $row['nature'],
                'current_ability' => $row['current_ability'],
                'species' => $row['species'],
                'trainer' => $row['trainer'],
                'speciesmodel' => $speciesmodel,
                'nature_name' => $current_nature,
                'current_ability_name' => $current_ability
            ));
        }
        return $allPokemon[0];
    }

}
