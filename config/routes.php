<?php

require_once 'app/controllers/species_controller.php';
require_once 'app/controllers/abilities_controller.php';
require_once 'app/controllers/moves_controller.php';

$routes->get('/', function() {
    SpeciesController::index();
});

$routes->get('/species', function() {
    SpeciesController::index();
});

$routes->post('/species/add', function() {
    SpeciesController::create();
});

$routes->get('/species/new', function() {
    SpeciesController::newForm();
});
$routes->get('/species/search', function() {
    SpeciesController::search();
});

$routes->get('/species/:number/edit', function($number) {
    SpeciesController::edit($number);
});

$routes->post('/species/:number/save', function($number) {
    SpeciesController::update($number);
});

$routes->post('/species/:number/delete', function($number) {
    SpeciesController::delete($number);
});

$routes->get('/species/:number', function($number) {
    SpeciesController::show($number);
});

$routes->get('/ability/new', function() {
    AbilityController::newForm();
});

$routes->get('/ability/search', function() {
    AbilityController::search();
});
$routes->post('/ability/add', function() {
    AbilityController::create();
});

$routes->get('/ability', function() {
    AbilityController::index();
});

$routes->get('/ability/:name/edit', function($name) {
    AbilityController::edit($name);
});

$routes->post('/ability/:name/save', function($name) {
    AbilityController::update($name);
});

$routes->post('/ability/:name/delete', function($name) {
    AbilityController::delete($name);
});

$routes->get('/ability/:name', function($name) {
    AbilityController::show($name);
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
