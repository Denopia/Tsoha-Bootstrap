<?php

require_once 'app/models/species.php';
require_once 'app/models/ability.php';

class AbilityController extends BaseController {

    public static function index() {
        $allAbilities = Ability::allAbilities();
        View::make('suunnitelmat/search_ability.html', array('allAbilities' => $allAbilities));
    }

    public static function search() {
        $params = $_GET;
        $allAbilities = Ability::search($params['name'], $params['description']);
        View::make('suunnitelmat/search_ability.html', array('allAbilities' => $allAbilities));
    }

    public static function newForm() {
        View::make('suunnitelmat/new_ability.html');
    }

    public static function create() {
        $params = $_POST;
        $attributes = array(
            'ability_name' => $params['name'],
            'description' => $params['description']
        );
        $ability = new Ability($attributes);
        $errors = $ability->errors();
        if (count($errors) == 0) {
            $ability->save();
            Redirect::to('/ability/' . $ability->ability_name, array('message' => 'Ability added to database'));
        } else {
            View::make('suunnitelmat/new_ability.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function show($name) {
        $allAbilities = Ability::findByName($name);
        $allSpecies = Species::findByAbility($name);
        View::make('suunnitelmat/view_ability.html', array('ability' => $allAbilities, 'allSpecies' => $allSpecies));
    }

    public static function delete($name) {
        $ability = new Ability(array('ability_name' => $name));
        $ability->delete();
        Redirect::to('/ability', array('message' => 'Ability removed from database'));
    }

    public static function edit($name) {
        $ability = Ability::findByName($name);
        View::make('suunnitelmat/edit_ability.html', array('attributes' => $ability));
    }

    public static function update($number) {
        $params = $_POST;
        $attributes = array('ability_name' => $params['name'],
            'description' => $params['description'],
            'ability_id' => $params['id']
            );
    }

}
