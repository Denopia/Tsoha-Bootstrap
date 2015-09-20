<?php

require 'app/controllers/species_controller.php';

$routes->get('/', function() {
    SpeciesSearchController::index();
});

$routes->get('/search', function() {
    SpeciesSearchController::index();
});

$routes->post('/species', function() {
//    SpeciesSearchController::index();
    SpeciesSearchController::create();
});

//$routes->get('/species', function() {
//    SpeciesSearchController::index();
////    SpeciesSearchController::create();
//});

$routes->get('/species/new', function() {
    SpeciesSearchController::newForm();
});

$routes->get('/species/:number', function($number) {
    SpeciesSearchController::show($number);
});



$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

//$routes->get('/view/471', function() {
//    HelloWorldController::view_general_info();
//});
//
//$routes->get('/addnew', function() {
//    HelloWorldController::add_new();
//});
//
//$routes->get('/mypokemon/1', function() {
//    HelloWorldController::view_and_edit_my_guys();
//});
