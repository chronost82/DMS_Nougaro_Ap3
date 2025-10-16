<?php

use CodeIgniter\Router\RouteCollection;
use PHPUnit\Framework\TestStatus\Success;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Routes pour la gestion des clients
$routes->get('liste-clients', 'ClientController::affiche', ['as' => 'admin-liste-clients']);

$routes->post('suppr-client', 'ClientController::delete', ['as' => 'admin-suppr-client']);

$routes->get('ajout-client', 'ClientController::ajout', ['as' => 'admin-ajout-client']);
$routes->post('ajout-client', 'ClientController::create', ['as' => 'admin-ajout-client']);

$routes->get('ajout-client', 'ClientController::ajout', ['as' => 'admin-ajout-client']);
$routes->post('ajout-client', 'ClientController::create', ['as' => 'admin-ajout-client']);

//Routes pour la gestion des élèves
$routes->get('liste-eleves', 'EleveController::affiche', ['as' => 'admin-liste-eleves']);

$routes->post('suppr-eleve', 'EleveController::delete', ['as' => 'admin-suppr-eleve']);

$routes->get('modif-eleve', 'EleveController::modif', ['as' => 'admin-eleve-modif']);
$routes->post('modif-eleve', 'EleveController::update', ['as' => 'admin-eleve-modif']);

$routes->get('ajout-eleve', 'EleveController::ajout', ['as' => 'admin-ajout-eleve']);
$routes->post('ajout-eleve', 'EleveController::create', ['as' => 'admin-ajout-eleve']);

//Routes pour la gestion des demandes
//En attente
$routes->get('dashboard/list-demande-en-attente', 'DemandeController::affiche', ['as' => 'admin-liste-demandes-en-attentes']);

$routes->post('dashboard/suppr-demande-en-attente', 'DemandeController::delete', ['as' => 'admin-suppr-demande-en-attente']);

$routes->get('dashboard/modif-demande-en-attente', 'DemandeController::modif', ['as' => 'admin-demande-en-attente-modif']);
$routes->post('dashboard/modif-demande-en-attente', 'DemandeController::update', ['as' => 'admin-demande-en-attente-modif']);

$routes->get('ajout-demande', 'DemandeController::ajout', ['as' => 'admin-ajout-demande']);
$routes->post('ajout-demande', 'DemandeController::create', ['as' => 'admin-ajout-demande']);

//Validé
$routes->get('dashboard/liste-demandes-valides', 'DemandeValidesController::affiche', ['as' => 'admin-liste-demandes-valides']);

//Terminé
$routes->get('dashboard/liste-demandes-terminees', 'DemandeTermineController::affiche', ['as' => 'admin-liste-demandes-terminees']);

//Routes pour la gestion de la liste des test du contrôle du technique
$routes->get('dashboard/liste-test', 'TestController::affiche', ['as' => 'liste-test']);

$routes->post('dashboard/suppr-test', 'TestController::delete', ['as' => 'suppr-test']);

$routes->get('dashboard/modif-test', 'TestController::modif', ['as' => 'test-modif']);
$routes->post('dashboard/modif-test', 'TestController::update', ['as' => 'test-modif']);

$routes->get('dashboard/ajout-test', 'TestController::ajout', ['as' => 'ajout-test']);
$routes->post('dashboard/ajout-test', 'TestController::create', ['as' => 'ajout-test']);

//Contrôle technoque terminé
$routes->get('resultats-contrôle-technique', 'ResultatTestConlleur::affiche', ['as' => 'resultats-tests']);


$routes->get('dashboard', 'Dashboard::index');

//login et logout
service('auth')->routes($routes);
$routes->get('/logout', 'LogoutController::logout', ['as' => 'logout']);
