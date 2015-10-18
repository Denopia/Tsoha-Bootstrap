<?php

require_once 'app/models/species.php';
require_once 'app/models/ability.php';

/**
 * Kontrolloi taitoihin liittyviä toimintoja
 */
class AbilityController extends BaseController {

    /**
     * Luo taitojen hakusivun
     */
    public static function index() {
        $allAbilities = Ability::allAbilities();
        View::make('suunnitelmat/search_ability.html', array('allAbilities' => $allAbilities));
    }

    /**
     * Luo taitojen hakusivun kun halutaan hakea taitoja tietyin kriteerein
     */
    public static function search() {
        $params = $_GET;
        $allAbilities = Ability::search($params['name'], $params['description']);
        View::make('suunnitelmat/search_ability.html', array('allAbilities' => $allAbilities));
    }

    /**
     * Luo uuden taidon luontiin tarvittavan lomakkeen
     */
    public static function newForm() {
        self::check_admin();
        View::make('suunnitelmat/new_ability.html');
    }

    /**
     * Luo taidon jos ei tule virheitä ja ohjaa kyseisen taidon
     * esittelysivulle, muuten ohjaa takaisin lomakkeeseen
     */
    public static function create() {
        self::check_admin();
        $params = $_POST;
        $attributes = array(
            'ability_name' => $params['name'],
            'description' => $params['description'],
            'ability_original_name' => $params['name']
        );
        $ability = new Ability($attributes);
        $errors = $ability->errors();
        if (count($errors) == 0) {
            $ability->save();
            Redirect::to('/ability/' . $ability->ability_id, array('message' => 'Ability added to database'));
        } else {
            View::make('suunnitelmat/new_ability.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    /**
     * Näyttää tietyn taidon eli on find-toiminnallisuus.
     * Kaiketi huono käytäntö käyttää nimeä hakemiseen, voisi muuttaa 
     * hakuperusteeksi id:n
     * 
     * @param type $name taidon nimi
     */
    public static function show($id) {
        $ability = Ability::findById($id);
        $allSpecies = Species::findByAbility($id);
        View::make('suunnitelmat/view_ability.html', array('ability' => $ability, 'allSpecies' => $allSpecies));
    }

    /**
     * Poistaa taidon nimen perusteella 
     * 
     * @param type $name taidon nimi
     */
    public static function delete($id) {
        self::check_admin();
        $ability = new Ability(array('ability_id' => $id));
        $ability->delete();
        Redirect::to('/ability', array('message' => 'Ability removed from database'));
    }

    /**
     * Luo taidon muokkauslomakkeen
     * 
     * @param type $name taidon nimi
     */
    public static function edit($id) {
        self::check_admin();
        $ability = Ability::findById($id);
        View::make('suunnitelmat/edit_ability.html', array('attributes' => $ability));
    }

    /**
     * Päivittää taidon post-pyynnön parametreilla jos ei
     * löydy virheitä, muuten ohjaa takaisin lomakkeeseen
     * 
     * @param type $number taidon id
     */
    public static function update($id) {
        self::check_admin();
        $params = $_POST;
        $attributes = array('ability_name' => $params['name'],
            'description' => $params['description'],
            'ability_id' => $params['id'],
            'ability_original_name' => $params['oname']
        );
        $ability = new Ability($attributes);
        $errors = $ability->errors();
        if (count($errors) == 0) {
            $ability->update();
            Redirect::to('/ability/' . $id, array('message' => 'Ability updated'));
        } else {
            View::make('suunnitelmat/edit_ability.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

}
