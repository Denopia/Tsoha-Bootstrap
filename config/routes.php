<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/search', function() {
    HelloWorldController::search_all();
});

$routes->get('/view/471', function() {
    HelloWorldController::view_general_info();
});

$routes->get('/addnew', function() {
    HelloWorldController::add_new();
});

$routes->get('/mypokemon/1', function() {
    HelloWorldController::view_and_edit_my_guys();
});
