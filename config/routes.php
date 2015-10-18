<?php

require_once 'app/controllers/species_controller.php';
require_once 'app/controllers/abilities_controller.php';
require_once 'app/controllers/moves_controller.php';
require_once 'app/controllers/user_controller.php';
require_once 'app/controllers/pokemon_controller.php';

$routes->get('/', function() {
    SpeciesController::index();
});

/**
 * USERS
 */

$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->get('/logout', function() {
    UserController::logout();
});

$routes->get('/register', function() {
    UserController::register();
});

$routes->post('/register', function() {
    UserController::handle_register();
});

/**
 * SPECIES
 */

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

/**
 * ABILITIES
 */

$routes->get('/ability', function() {
    AbilityController::index();
});

$routes->get('/ability/new', function() {
    AbilityController::newForm();
});

$routes->post('/ability/add', function() {
    AbilityController::create();
});

$routes->get('/ability/search', function() {
    AbilityController::search();
});

$routes->get('/ability/:id/edit', function($id) {
    AbilityController::edit($id);
});

$routes->post('/ability/:id/save', function($id) {
    AbilityController::update($id);
});

$routes->post('/ability/:id/delete', function($id) {
    AbilityController::delete($id);
});

$routes->get('/ability/:id', function($id) {
    AbilityController::show($id);
});

/**
 * POKEMON
 */

$routes->get('/pokemon', function() {
    PokemonController::index();
});

$routes->get('/pokemon/search', function() {
    PokemonController::search();
});

$routes->post('/pokemon/new/add', function() {
    PokemonController::create();
});

$routes->get('/pokemon/new/:number', function($number) {
    PokemonController::newForm($number);
});

$routes->post('/pokemon/new/:number', function($number) {
    PokemonController::quickAdd($number);
});

$routes->get('/pokemon/new/:number', function($number) {
    PokemonController::newForm($number);
});

$routes->get('/pokemon/:id/edit', function($id) {
    PokemonController::editForm($id);
});

$routes->post('/pokemon/:id/save', function($id) {
    PokemonController::update($id);
});

$routes->post('/pokemon/:id/delete', function($id) {
    PokemonController::delete($id);
});

$routes->get('/pokemon/:id', function($id) {
    PokemonController::show($id);
});





$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

