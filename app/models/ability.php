<?php

require_once 'app/models/type.php';

class Ability extends BaseModel {

    public $ability_name,
            $ability_id,
            $description;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_description');
    }

    public function validate_name() {
        return parent::validate_string_length($this->ability_name, '3', '15', 'Ability name');
    }

    public function validate_description() {
        return parent::validate_string_length($this->description, '3', '300', 'Description');
    }
    
    public function delete(){
        $query = DB::connection()->prepare('DELETE FROM Ability WHERE ability_name = :name');
        $query->execute(array('name' => $this->ability_name));
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Ability (ability_name, description) VALUES (:name, :desc) RETURNING ability_id');
        $query->execute(array('name' => $this->ability_name, 'desc' => $this->description));
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Ability SET ability_name = :name, description = :description WHERE ability_id = :id');
        $query->execute(array('name' => $this->ability_name, 'description' => $this->description, 'id' => $this->ability_id));
        $row = $query->fetch();
    }

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

    public static function findByName($name) {
        $query = DB::connection()->prepare("SELECT * FROM Ability WHERE ability_name = :name LIMIT 1");
        $query->execute(array('name' => $name));
        $rows = $query->fetchAll();
        $allAbilities = array();
        foreach ($rows as $row) {
            $allAbilities[] = new Ability(array(
                'ability_name' => $row['ability_name'],
                'ability_id' => $row['ability_id'],
                'description' => $row['description']
            ));
        }
        return $allAbilities[0];
    }

}
