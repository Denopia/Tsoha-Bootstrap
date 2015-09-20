<?php

require 'app/models/species.php';

class SpeciesSearchController extends BaseController {

    public static function index() {
        $allSpecies = Species::allSpecies();
        View::make('suunnitelmat/species_search.html', array('allSpecies' => $allSpecies));
    }

    public static function show($number) {
        $species = Species::findByNumber($number);
        View::make('suunnitelmat/view_species.html', array('speciess' => $species));
    }

    public static function newForm() {
        View::make('suunnitelmat/new_species.html');
    }

    public static function create() {
        $params = $_POST;
        if($params['primary']==999){
            Redirect::to('/species/new');
        }
        $species = new Species(array(
            'primary_typing' => $params['primary'],
            'secondary_typing' => $params['secondary'],
            'species_name' => $params['name'],
            'pokedex_number' => $params['number'],
            'base_hp' => $params['hp'],
            'base_attack' => $params['attack'],
            'base_defense' => $params['defense'],
            'base_special_attack' => $params['spattack'],
            'base_special_defense' => $params['spdefense'],
            'base_speed' => $params['speed'],
        ));
//        Kint::dump($params);
        $species->save();
        Redirect::to('/species/' . $species->pokedex_number, array('message' => 'Species added to database'));
    }

}
