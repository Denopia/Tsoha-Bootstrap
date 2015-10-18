<?php

require_once 'app/models/species.php';
require_once 'app/models/ability.php';

/**
 * Kontrolloi lajiin liittyviä toimintoja
 */
class SpeciesController extends BaseController {

    /**
     * Luo lajien hakusivun
     */
    public static function index() {
        $allSpecies = Species::allSpecies();
        View::make('suunnitelmat/search_species.html', array('allSpecies' => $allSpecies));
    }

    /**
     * Luo lajin esittelysivun
     * 
     * @param type $number halutun lajin järjestysnumero
     */
    public static function show($number) {
        $species = Species::findByNumber($number);
        View::make('suunnitelmat/view_species.html', array('species' => $species));
    }

    /**
     * Luo lomakkeen lajin luontia varten
     */
    public static function newForm() {
        self::check_admin();
        $abilities = Ability::allAbilities();
        View::make('suunnitelmat/new_species.html', array('abilities' => $abilities));
    }

    /**
     * Hakee lajeja get pyynnön parametrien mukaan
     */
    public static function search() {
        $name = $_GET['name'];
        $wanted = array();
        $allSpecies = Species::findByNameContaining($name);
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

    /**
     * Luo uuden lajin post pyynnön parametrien mukaan jos virheitä ei löydy,
     * muuten ohjaa takaisin lomakkeeseen
     */
    public static function create() {
        self::check_admin();
        $params = $_POST;
        $abilities = array();
        if ($params['ability1'] > 1) {
            $abilities[] = Ability::findById($params['ability1']);
        }
        if ($params['ability2'] > 1) {
            $abilities[] = Ability::findById($params['ability2']);
        }
        if ($params['ability3'] > 1) {
            $abilities[] = Ability::findById($params['ability3']);
        }
        if ($params['ability4'] > 1) {
            $abilities[] = Ability::findById($params['ability4']);
        }
        $attributes = array(
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
            'abilities' => $abilities,
            'ability1_id' => $params['ability1'],
            'ability2_id' => $params['ability2'],
            'ability3_id' => $params['ability3'],
            'ability4_id' => $params['ability4']
        );

        $species = new Species($attributes);
        $errors = $species->errors();
        if (count($errors) == 0) {
            $species->save();
            foreach ($abilities as $ability) {
                Ability::addAbilityToSpecies($species->species_id, $ability->ability_id);
            }
            Redirect::to('/species/' . $species->pokedex_number, array('message' => 'Species added to database'));
        } else {
            $abilities = Ability::allAbilities();
            View::make('suunnitelmat/new_species.html', array('abilities' => $abilities, 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    /**
     * Luo lomakkeen tietyn lajin muokkausta varten
     * 
     * @param type $number halutun lajin järjestysumero
     */
    public static function edit($number) {
        self::check_admin();
        $abilities = Ability::allAbilities();
        $species = Species::findByNumber($number);
        View::make('suunnitelmat/edit_species.html', array('attributes' => $species, 'abilities' => $abilities));
    }

    /**
     * Päivittää lajin tietokantaan post-pyynnön parametrien mukaan jos
     * ei löydy virheitä
     * 
     * @param type $number muutettavan lajin id
     */
    public static function update($number) {
        self::check_admin();
        $params = $_POST;
        $abilities = array();
        if ($params['ability1'] > 1) {
            $abilities[] = Ability::findById($params['ability1']);
        }
        if ($params['ability2'] > 1) {
            $abilities[] = Ability::findById($params['ability2']);
        }
        if ($params['ability3'] > 1) {
            $abilities[] = Ability::findById($params['ability3']);
        }
        if ($params['ability4'] > 1) {
            $abilities[] = Ability::findById($params['ability4']);
        }
        $attributes = array('primary_typing' => $params['primary'],
            'secondary_typing' => $params['secondary'],
            'species_name' => $params['name'],
            'species_original_name' => $params['oname'],
            'pokedex_number' => $params['number'],
            'base_hp' => $params['hp'],
            'base_attack' => $params['attack'],
            'base_defense' => $params['defense'],
            'base_special_attack' => $params['spattack'],
            'base_special_defense' => $params['spdefense'],
            'base_speed' => $params['speed'],
            'species_id' => $params['id'],
            'abilities' => $abilities,
            'ability1_id' => $params['ability1'],
            'ability2_id' => $params['ability2'],
            'ability3_id' => $params['ability3'],
            'ability4_id' => $params['ability4']
        );

        $species = new Species($attributes);
        $errors = $species->errors();

        if (count($errors) == 0) {
            $species->update();
            $species->deleteAllAbilities();
            foreach ($abilities as $ability) {
                Ability::addAbilityToSpecies($species->species_id, $ability->ability_id);
            }
            Redirect::to('/species/' . $species->pokedex_number, array('message' => 'Species updated'));
        } else {
            $abilities = Ability::allAbilities();
            View::make('suunnitelmat/edit_species.html', array('abilities' => $abilities, 'errors' => $errors, 'attributes' => $attributes));
        }
    }

    /**
     * Poistaaa tietyn lajin tietokannasta
     * 
     * @param type $number poistettavan lajin järjestysnumero
     */
    public static function delete($number) {
        self::check_admin();
        Pokemon::removeAllWithThisSpecies($number);
        $species = Species::findByNumber($number);
        $species->deleteAllAbilities();
        $species->delete();
        Redirect::to('/species', array('message' => 'Species removed from database'));
    }

    /**
     * Poistaa mahdolliset riippuvuudet tietokannan 
     * lajien ja halutun taidon väliltä
     * 
     * @param type $id taidon nimi
     * @param type $name taidon id
     */
    public static function removeAbilityFromAll($id) {
        self::check_admin();
        Species::removeAbilityFromAll($id);
    }

}
