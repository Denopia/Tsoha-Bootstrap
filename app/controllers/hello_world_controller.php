<?php

require 'app/models/species.php';

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('helloworld.html');
//   	  View::make('home.html');
//        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        $allSpecies = Species::allSpecies();
//        $one = Species::findSpeciesByNumber(1);
//        $allulbs = Pokemon::findAllGenericByName('ulbasau');
//        $glaceon = Pokemon::findAnyById(7);
        Kint::dump($allSpecies);
//        Kint::dump($one);
//        Kint::dump($allulbs);
//        Kint::dump($glaceon);
    }

    public static function search_all() {
        View::make('suunnitelmat/search.html');
    }

    public static function add_new() {
        View::make('suunnitelmat/add_new.html');
    }

    public static function view_and_edit_my_guys() {
        View::make('suunnitelmat/my_info.html');
    }

    public static function view_general_info() {
        View::make('suunnitelmat/general_info.html');
    }

}
