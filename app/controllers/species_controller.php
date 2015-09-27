<?php

require_once 'app/models/species.php';
require_once 'app/models/ability.php';

class SpeciesController extends BaseController {

    public static function index() {
        $allSpecies = Species::allSpecies();
        View::make('suunnitelmat/search_species.html', array('allSpecies' => $allSpecies));
    }

    public static function show($number) {
        $species = Species::findByNumber($number);
        View::make('suunnitelmat/view_species.html', array('species' => $species));
    }

    public static function newForm() {
        $abilities = Ability::allAbilities();
        View::make('suunnitelmat/new_species.html', array('abilities' => $abilities));
    }

    public static function search() {
        $name = $_GET['name'];
        $wanted = array();
        $allSpecies = Species::findByName($name);
        if (isset($_GET['type'])) {
            $typess = $_GET['type'];
            $break = false;
            foreach ($allSpecies as $species) {
                foreach ($species->types as $type) {
                    foreach ($typess as $type2) {
                        if ($type == $type2) {
                            $wanted[] = $species;
                            $break = true;
                            break;
                        }
                    }if ($break) {
                        $break = false;
                        break;
                    }
                }
            }
        } else {
            $wanted = $allSpecies;
        }
        View::make('suunnitelmat/search_species.html', array('allSpecies' => $wanted));
    }

    public static function create() {
        $params = $_POST;
        $attributes = array('primary_typing' => $params['primary'],
            'secondary_typing' => $params['secondary'],
            'species_name' => $params['name'],
            'pokedex_number' => $params['number'],
            'base_hp' => $params['hp'],
            'base_attack' => $params['attack'],
            'base_defense' => $params['defense'],
            'base_special_attack' => $params['spattack'],
            'base_special_defense' => $params['spdefense'],
            'base_speed' => $params['speed'],
            'ability1_id' => $params['ability1'],
            'ability2_id' => $params['ability2'],
            'ability3_id' => $params['ability3'],
            'species_id' => '0'
        );
        $species = new Species($attributes);
//        Kint::dump($params);
        $errors = $species->errors();
        if (count($errors) == 0) {
            $species->save();
            Redirect::to('/species/' . $species->pokedex_number, array('message' => 'Species added to database'));
        } else {
            $abilities = Ability::allAbilities();
            View::make('suunnitelmat/new_species.html', array('abilities' => $abilities, 'errors' => $errors, 'attributes' => $attributes));
//          View::make('game/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($number) {
        $abilities = Ability::allAbilities();
        $species = Species::findByNumber($number);
        View::make('suunnitelmat/edit_species.html', array('attributes' => $species, 'abilities' => $abilities));
    }

    // Pelin muokkaaminen (lomakkeen käsittely)
    public static function update($number) {
        $params = $_POST;
        $attributes = array('primary_typing' => $params['primary'],
            'secondary_typing' => $params['secondary'],
            'species_name' => $params['name'],
            'pokedex_number' => $params['number'],
            'base_hp' => $params['hp'],
            'base_attack' => $params['attack'],
            'base_defense' => $params['defense'],
            'base_special_attack' => $params['spattack'],
            'base_special_defense' => $params['spdefense'],
            'base_speed' => $params['speed'],
            'ability1_id' => $params['ability1'],
            'ability2_id' => $params['ability2'],
            'ability3_id' => $params['ability3'],
            'species_id' => $params['id']
        );

        $species = new Species($attributes);
        $errors = $species->errors();

        if (count($errors) == 0) {
            $species->update();
            Redirect::to('/species/' . $species->pokedex_number, array('message' => 'Species updated'));
        } else {
            $abilities = Ability::allAbilities();
            View::make('suunnitelmat/edit_species.html', array('abilities' => $abilities, 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function delete($number) {
        $species = new Species(array('pokedex_number' => $number));
        $species->delete();
        Redirect::to('/species', array('message' => 'Species removed from database'));
    }

}
