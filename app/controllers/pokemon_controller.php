<?php

require_once 'app/models/species.php';
require_once 'app/models/ability.php';
require_once 'app/models/pokemon.php';
require_once 'app/models/nature.php';

/**
 * Kontrolloi pokemoneihin liittyviä toimintoja
 */
class PokemonController extends BaseController {

    /**
     * Luo pokemonien hakusivun kirjautuneen käyttäjän mukaan
     */
    public static function index() {
        self::check_logged_in();
        $allPokemon = Pokemon::findByUser(parent::get_user_logged_in()->user_id);
        View::make('suunnitelmat/search_pokemon.html', array('allPokemon' => $allPokemon));
    }

    /**
     * Näyttää tietyn pokemonin esittelysivun
     * 
     * @param type $id pokemonin id
     */
    public static function show($id) {
        $pokemon = Pokemon::findById($id);
        self::check_user_id($pokemon->trainer);
        View::make('suunnitelmat/view_pokemon.html', array('species' => $pokemon->speciesmodel, 'pokemon' => $pokemon));
    }

    /**
     * Luo lomakkeen uuden pokemonin luontiin
     * joka kuuluu lajiin jolla on parametrina saatu id
     * 
     * @param type $number pokemon lajin id
     */
    public static function newForm($number) {
        $species = Species::findByNumber($number);
        $abilities = array();
        if ($species->ability1_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability1_id,
                'ability_name' => $species->ability1_name
            ));
            $abilities[] = $ability;
        }
        if ($species->ability2_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability2_id,
                'ability_name' => $species->ability2_name
            ));
            $abilities[] = $ability;
        }
        if ($species->ability3_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability3_id,
                'ability_name' => $species->ability3_name
            ));
            $abilities[] = $ability;
        }
        $allNatures = Nature::allNatures();
        $attributes = (array('current_ability' => $species->ability1_id, 'species' => $species->species_id));
        View::make('suunnitelmat/new_pokemon.html', array('attributes' => $attributes, 'abilities' => $abilities, 'species' => $species, 'natures' => $allNatures));
    }

    /**
     * Näyttää tietyn pokemonin muokkaussivun
     * 
     * @param type $id pokemonin id
     */
    public static function editForm($id) {
        $pokemon = Pokemon::findById($id);
        $species = $pokemon->speciesmodel;
        $abilities = array();
        if ($species->ability1_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability1_id,
                'ability_name' => $species->ability1_name
            ));
            $abilities[] = $ability;
        }
        if ($species->ability2_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability2_id,
                'ability_name' => $species->ability2_name
            ));
            $abilities[] = $ability;
        }
        if ($species->ability3_id > 1) {
            $ability = new Ability(array(
                'ability_id' => $species->ability3_id,
                'ability_name' => $species->ability3_name
            ));
            $abilities[] = $ability;
        }
        $allNatures = Nature::allNatures();
        View::make('suunnitelmat/edit_pokemon.html', array('species' => $pokemon->speciesmodel, 'pokemon' => $pokemon, 'abilities' => $abilities, 'natures' => $allNatures));
    }

    /**
     * Hakee käyttäjän pokemoneja get pyynnön parametrien mukaan.
     * Toimii tällä hetkellä vain lempinimen perusteella.
     */
    public static function search() {
        if ($_GET['nickname'] != null) {
            $nickname = $_GET['nickname'];
            $allPokemon = Pokemon::findByUserAndNickname(parent::get_user_logged_in()->user_id, $nickname);
            View::make('suunnitelmat/search_pokemon.html', array('allPokemon' => $allPokemon));
        } else {
            Redirect::to('/pokemon');
        }
    }

    /**
     * Jos post-pyynnön parametreissa ei virheitä, luo uuden pokemonnin ja ohjaa
     * käyttäjän pokemoninen sivulle, jos oli virheitä ohjaa takaisin lomakkeeseen
     */
    public static function create() {
        $params = $_POST;
        $attributes = (array(
            'nickname' => $params['nickname'],
            'gender' => $params['gender'],
            'hp' => $params['hp'],
            'attack' => $params['attack'],
            'defense' => $params['defense'],
            'special_attack' => $params['special_attack'],
            'special_defense' => $params['special_defense'],
            'speed' => $params['speed'],
            'happiness' => $params['happiness'],
            'iv_hp' => $params['iv_hp'],
            'iv_attack' => $params['iv_attack'],
            'iv_defense' => $params['iv_defense'],
            'iv_special_attack' => $params['iv_special_attack'],
            'iv_special_defense' => $params['iv_special_defense'],
            'iv_speed' => $params['iv_speed'],
            'ev_hp' => $params['ev_hp'],
            'ev_attack' => $params['ev_attack'],
            'ev_defense' => $params['ev_defense'],
            'ev_special_attack' => $params['ev_special_attack'],
            'ev_special_defense' => $params['ev_special_defense'],
            'ev_speed' => $params['ev_speed'],
            'shiny' => $params['shiny'],
            'lvl' => $params['lvl'],
            'nature' => $params['nature'],
            'current_ability' => $params['current_ability'],
            'species' => $params['species_id'],
            'trainer' => parent::get_user_logged_in()->user_id
        ));
        $pokemon = new Pokemon($attributes);
        $errors = $pokemon->errors();
        if (count($errors) == 0) {
            $pokemon->save();
            Redirect::to('/pokemon', array('message' => 'New Pokémon added!'));
        } else {
            $species2 = Species::findById($params['species_id']);
            $abilities2 = array();
            if ($species2->ability1_id > 1) {
                $ability2 = new Ability(array(
                    'ability_id' => $species2->ability1_id,
                    'ability_name' => $species2->ability1_name
                ));
                $abilities2[] = $ability2;
            }
            if ($species2->ability2_id > 1) {
                $ability2 = new Ability(array(
                    'ability_id' => $species2->ability2_id,
                    'ability_name' => $species2->ability2_name
                ));
                $abilities2[] = $ability2;
            }
            if ($species2->ability3_id > 1) {
                $ability2 = new Ability(array(
                    'ability_id' => $species2->ability3_id,
                    'ability_name' => $species2->ability3_name
                ));
                $abilities2[] = $ability2;
            }
            $allNatures = Nature::allNatures();
            View::make('suunnitelmat/new_pokemon.html', array('species' => $species2, 'abilities' => $abilities2, 'errors' => $errors, 'attributes' => $attributes, 'natures' => $allNatures));
        }
    }

    /**
     * Päivittää pokemonin post-pyynnön parametreilla jos ei
     * löydy virheitä, muuten ohjaa takaisin lomakkeeseen
     * 
     * @param type $number pokemonin id
     */
    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'pokemon_id' => $id,
            'nickname' => $params['nickname'],
            'gender' => $params['gender'],
            'hp' => $params['hp'],
            'attack' => $params['attack'],
            'defense' => $params['defense'],
            'special_attack' => $params['special_attack'],
            'special_defense' => $params['special_defense'],
            'speed' => $params['speed'],
            'happiness' => $params['happiness'],
            'iv_hp' => $params['iv_hp'],
            'iv_attack' => $params['iv_attack'],
            'iv_defense' => $params['iv_defense'],
            'iv_special_attack' => $params['iv_special_attack'],
            'iv_special_defense' => $params['iv_special_defense'],
            'iv_speed' => $params['iv_speed'],
            'ev_hp' => $params['ev_hp'],
            'ev_attack' => $params['ev_attack'],
            'ev_defense' => $params['ev_defense'],
            'ev_special_attack' => $params['ev_special_attack'],
            'ev_special_defense' => $params['ev_special_defense'],
            'ev_speed' => $params['ev_speed'],
            'shiny' => $params['shiny'],
            'lvl' => $params['lvl'],
            'nature' => $params['nature'],
            'current_ability' => $params['current_ability'],
            'species' => $params['species_id'],
            'trainer' => parent::get_user_logged_in()->user_id
        );

        $pokemon = new Pokemon($attributes);
        $errors = $pokemon->errors();

        if (count($errors) == 0) {
            $pokemon->update();
            Redirect::to('/pokemon/' . $pokemon->pokemon_id, array('message' => 'Pokemon updated'));
        } else {
            $species = Species::findById($pokemon->species);
            $abilities = array();
            if ($species->ability1_id > 1) {
                $ability = new Ability(array(
                    'ability_id' => $species->ability1_id,
                    'ability_name' => $species->ability1_name
                ));
                $abilities[] = $ability;
            }
            if ($species->ability2_id > 1) {
                $ability = new Ability(array(
                    'ability_id' => $species->ability2_id,
                    'ability_name' => $species->ability2_name
                ));
                $abilities[] = $ability;
            }
            if ($species->ability3_id > 1) {
                $ability = new Ability(array(
                    'ability_id' => $species->ability3_id,
                    'ability_name' => $species->ability3_name
                ));
                $abilities[] = $ability;
            }
            $allNatures = Nature::allNatures();
            View::make('suunnitelmat/edit_pokemon.html', array('errors' => $errors, 'species' => $pokemon->speciesmodel, 'pokemon' => $pokemon, 'abilities' => $abilities, 'natures' => $allNatures));
        }
    }

    /**
     * Poistaa pokemonin sen id:n mukaan
     * 
     * @param type $id pokemonin id
     */
    public static function delete($id) {
        $pokemon = Pokemon::findById($id);
        self::check_user_id($pokemon->trainer);
        $pokemon->delete();
        Redirect::to('/pokemon', array('message' => 'Pokémon removed'));
    }

    public static function removeAbilityFromAll($id, $name) {
        self::check_admin();
        Pokemon::removeAbilityFromAll($id, $name);
    }

}
