<?php

require_once 'app/models/type.php';
require_once 'app/models/species.php';
require_once 'app/controllers/species_controller.php';

/**
 * Model joka käsittelee tietokannan
 * Ability-taulun kyselyitä
 */
class Ability extends BaseModel {

    public $ability_name,
            $ability_id,
            $description,
            $ability_original_name;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_description');
    }

    /**
     * Validoi nimen siten että sen pituus on välillä 3-15 ja ettei samannimistä
     * taitoa jo ole olemassa
     * 
     * @return string virheet
     */
    public function validate_name() {
        $errors = parent::validate_string_length($this->ability_name, '3', '15', 'Ability name');
        if (count($errors) != 0) {
            return $errors;
        }
        $query = DB::connection()->prepare('SELECT * FROM Ability WHERE ability_name = :n');
        $query->execute(array('n' => $this->ability_name));
        $rows = $query->fetchAll();
        foreach ($rows as $row) {
            if ($this->ability_id != $row['ability_id']) {
                $errors[] = 'An ability with this name already exists';
            }
        }
        return $errors;
    }

    /**
     * Validoi kuvauksen pituuden
     * 
     * @return type virheet
     */
    public function validate_description() {
        return parent::validate_string_length($this->description, '3', '300', 'Description');
    }

    /**
     * Poistaa taidon tietokannasta
     */
    public function delete() {
        SpeciesController::removeAbilityFromAll($this->ability_id, $this->ability_name);
        PokemonController::removeAbilityFromAll($this->ability_id, $this->ability_name);
        $query = DB::connection()->prepare('DELETE FROM Ability WHERE ability_name = :name');
        $query->execute(array('name' => $this->ability_name));
    }

    /**
     * Tallentaa tietokantaan uuden taidon
     */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Ability (ability_name, description) VALUES (:name, :desc) RETURNING ability_id');
        $query->execute(array('name' => $this->ability_name, 'desc' => $this->description));
    }

    /**
     * Päivittää taidon tietokantaan
     */
    public function update() {
        $query = DB::connection()->prepare('UPDATE Ability SET ability_name = :name, description = :description WHERE ability_id = :id');
        $query->execute(array('name' => $this->ability_name, 'description' => $this->description, 'id' => $this->ability_id));
        $row = $query->fetch();
    }

    /**
     * Palauttaa tietokannan kaikki taidot
     * @return \Ability
     */
    public static function allAbilities() {
        $query = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_id > 1 ORDER BY ability_name");
        $query->execute();
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description']
            ));
        }
        return $allAbilities;
    }

    /**
     * Palauttaa tietokannan kaikki taidot ja lisäksi niin sanotun "ei taidon" mikä
     * on taito joka ei tee mitään
     * @return \Ability
     */
    public static function allAbilitiesWithNo() {
        $query = DB::connection()->prepare("SELECT * FROM Ability ORDER BY ability_name");
        $query->execute();
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description']
            ));
        }
        return $allAbilities;
    }

    /**
     * Etsii tietokannasta taidot nimen ja kuvauksen mukaan
     * @param type $name nimi
     * @param type $description kuvaus
     * @return \Ability
     */
    public static function search($name, $description) {
        $query = DB::connection()->prepare("SELECT * FROM Ability WHERE UPPER(ability_name) LIKE UPPER(:n) AND UPPER(description) LIKE UPPER(:d) AND ability_id > 1 ORDER BY ability_name");
        $query->execute(array('n' => "%$name%", 'd' => "%$description%"));
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description']
            ));
        }
        return $allAbilities;
    }

    /**
     * Etsii tietokannasta taidon nimen mukaan
     * @param type $name nimi
     * @return \Ability
     */
    public static function findByName($name) {
        $query = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_name = :name LIMIT 1");
        $query->execute(array('name' => $name));
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description'],
                'ability_original_name' => $row['ability_name']
            ));
        }
        return $allAbilities[0];
    }

    /**
     * Etsii tietokannasta taison id:n mukaan
     * @param type $id id
     * @return \Ability
     */
    public static function findById($id) {
        $query = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_id = :id LIMIT 1");
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description'],
                'ability_original_name' => $row['ability_name']
            ));
        }
        return $allAbilities[0];
    }

}
