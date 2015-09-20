<?php

require 'app/models/type.php';

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
            $secondary_typing;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function save() {
        if ($this->secondary_typing == 999) {
            $query = DB::connection()->prepare('INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES (:name, :number, :hp, :attack, :defense, :spattack, :spdefense, :speed, :primary) RETURNING pokedex_number');
            $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing));
        } else {
            $query = DB::connection()->prepare('INSERT INTO Species (species_name, pokedex_number, base_hp, base_attack, base_defense, base_special_attack, base_special_defense, base_speed, primary_typing, secondary_typing) VALUES (:name, :number, :hp, :attack, :defense, :spattack, :spdefense, :speed, :primary, :secondary) RETURNING pokedex_number');
            $query->execute(array('name' => $this->species_name, 'number' => $this->pokedex_number, 'hp' => $this->base_hp, 'attack' => $this->base_attack, 'defense' => $this->base_defense, 'spattack' => $this->base_special_attack, 'spdefense' => $this->base_special_defense, 'speed' => $this->base_speed, 'primary' => $this->primary_typing, 'secondary' => $this->secondary_typing));
        }
        $row = $query->fetch();
        $this->pokedex_number = $row['pokedex_number'];
    }

    public static function allSpecies() {
        $query = DB::connection()->prepare("SELECT * FROM Species ORDER BY pokedex_number");
        $query->execute();
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $types = array();
            $type1id = $row['primary_typing'];
            if ($type1id != null) {
                $query2 = DB::connection()->prepare("SELECT * FROM typing WHERE typing_id = $type1id LIMIT 1");
                $query2->execute();
                $type1 = $query2->fetch();
                $type1name = $type1['typing_name'];
                $types[] = $type1name;
            }
            $type2id = $row['secondary_typing'];
            if ($type2id != null) {
                $query3 = DB::connection()->prepare("SELECT * FROM typing WHERE typing_id = $type2id LIMIT 1");
                $query3->execute();
                $type2 = $query3->fetch();
                $type2name = $type2['typing_name'];
                $types[] = $type2name;
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
                'types' => $types
            ));
            unset($types);
        }
        return $allSpecies;
    }

    public static function findByNumber($number) {
        $query = DB::connection()->prepare("SELECT * FROM Species WHERE pokedex_number = :number LIMIT 1");
        $query->execute(array('number' => $number));
        $rows = $query->fetchAll();
        $allSpecies = array();
        foreach ($rows as $row) {
            $types = array();
            $type1id = $row['primary_typing'];
            if ($type1id != null) {
                $query2 = DB::connection()->prepare("SELECT * FROM typing WHERE typing_id = $type1id LIMIT 1");
                $query2->execute();
                $type1 = $query2->fetch();
                $type1name = $type1['typing_name'];
                $types[] = $type1name;
            }
            $type2id = $row['secondary_typing'];
            if ($type2id != null) {
                $query3 = DB::connection()->prepare("SELECT * FROM typing WHERE typing_id = $type2id LIMIT 1");
                $query3->execute();
                $type2 = $query3->fetch();
                $type2name = $type2['typing_name'];
                $types[] = $type2name;
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
                'types' => $types
            ));
            unset($types);
        }
        return $allSpecies;
    }

}
