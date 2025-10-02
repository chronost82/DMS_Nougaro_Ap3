<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Routes pour la gestion des clients
$routes->get('liste-clients', 'client::affiche', ['as'=> 'admin-liste-clients']);

$routes->post('suppr-client', 'client::delete', ['as'=> 'admin-suppr-client']);

$routes->get('modif-client', 'client::modif', ['as'=> 'admin-client-modif']);
$routes->post('modif-client', 'client::update', ['as'=> 'admin-client-modif']);

$routes->get('ajout-client', 'client::ajout', ['as'=> 'admin-ajout-client']);
$routes->post('ajout-client', 'client::create', ['as'=> 'admin-ajout-client']);

//Routes pour la gestion des élèves
$routes->get('liste-eleves', 'eleve::affiche', ['as'=> 'admin-liste-eleves']);

$routes->post('suppr-eleve', 'eleve::delete', ['as'=> 'admin-suppr-eleve']);

$routes->get('modif-eleve', 'eleve::modif', ['as'=> 'admin-eleve-modif']);
$routes->post('modif-eleve', 'eleve::update', ['as'=> 'admin-eleve-modif']);

$routes->get('ajout-eleve', 'eleve::ajout', ['as'=> 'admin-ajout-eleve']);
$routes->post('ajout-eleve', 'eleve::create', ['as'=> 'admin-ajout-eleve']);

//Routes pour la gestion des demandes
//En attente
$routes->get('liste-demandes-en-attentes', 'demande::affiche', ['as'=> 'admin-liste-demandes-en-attentes']);

$routes->post('suppr-demande-en-attente', 'demande::delete', ['as'=> 'admin-suppr-demande-en-attente']);

$routes->get('modif-demande-en-attente', 'demande::modif', ['as'=> 'admin-demande-en-attente-modif']);
$routes->post('modif-demande-en-attente', 'demande::update', ['as'=> 'admin-demande-en-attente-modif']);

$routes->get('ajout-demande', 'demande::ajout', ['as'=> 'admin-ajout-demande']);
$routes->post('ajout-demande', 'demande::create', ['as'=> 'admin-ajout-demande']);

//Validé
$routes->get('liste-demandes-valides', 'demande-valides::affiche', ['as'=> 'admin-liste-demandes-valides']);

//Terminé
$routes->get('liste-demandes-terminees', 'demande-termine::affiche', ['as'=> 'admin-liste-demandes-terminees']);

//Routes pour la gestion de la liste des test du contrôle du technique
$routes->get('liste-test', 'test::affiche', ['as'=> 'liste-test']);

$routes->post('suppr-test', 'test::delete', ['as'=> 'suppr-test']);

$routes->get('modif-test', 'test::modif', ['as'=> 'test-modif']);
$routes->post('modif-test', 'test::update', ['as'=> 'test-modif']);

$routes->get('ajout-test', 'test::ajout', ['as'=> 'ajout-test']);
$routes->post('ajout-test', 'test::create', ['as'=> 'ajout-test']);

//Contrôle technoque terminé
$routes->get('resultats-contrôle-technique', 'resultat-test::affiche', ['as'=> 'resultats-tests']);


$routes->get('/dashboard', 'Dashboard::index');
