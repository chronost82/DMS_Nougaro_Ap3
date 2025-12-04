<?php

use CodeIgniter\Router\RouteCollection;
use PHPUnit\Framework\TestStatus\Success;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Routes pour la gestion des clients
$routes->get('liste-clients', 'ClientController::affiche', ['as' => 'liste-clients']);

$routes->get('suppr-client/(:num)', 'ClientController::delete/$1', ['as' => 'client-suppr']);

$routes->get('ajout-client', 'ClientController::ajout', ['as' => 'ajout-client']);
$routes->post('ajout-client', 'ClientController::create', ['as' => 'client-ajout']);

$routes->get('modif-client/(:num)', 'ClientController::modif/$1', ['as' => 'modif-client']);
$routes->post('modif-client', 'ClientController::update', ['as' => 'client-modif']);

//Routes pour la gestion des élèves
$routes->get('liste-eleve', 'EleveController::affiche', ['as' => 'liste-eleve']);

$routes->get('suppr-eleve/(:num)', 'EleveController::delete/$1', ['as' => 'suppr-eleve']);

$routes->get('modif-eleve/(:num)', 'EleveController::modif/$1', ['as' => 'eleve-modif']);
$routes->post('modif-eleve', 'EleveController::update', ['as' => 'modif-eleve']);

$routes->get('ajout-eleve', 'EleveController::ajout', ['as' => 'ajout-eleve']);
$routes->post('ajout-eleve', 'EleveController::create', ['as' => 'eleve-ajout']);

//Routes pour la gestion des demandes
//En attente
$routes->get('dashboard/list-demande-en-attente', 'DemandeController::affiche', ['as' => 'admin-liste-demandes-en-attentes']);

$routes->get('dashboard/suppr-demande-en-attente/(:num)', 'DemandeController::delete/$1', ['as' => 'admin-suppr-demande-en-attente']);
$routes->get('dashboard/suppr-demande-valide/(:num)', 'DemandeController::deleteDemandeValide/$1', ['as' => 'admin-suppr-demande-valide']);

$routes->post('dashboard/modif-demande-en-attente', 'DemandeController::update', ['as' => 'admin-demande-en-attente-modif']);
$routes->get('dashboard/valide-demande-en-attente/(:num)', 'DemandeController::updateToTerminee/$1', ['as' => 'admin-valide-demande-en-attente']);

$routes->get('ajout-demande', 'DemandeController::ajout', ['as' => 'admin-ajout-demande']);
$routes->post('ajout-demande', 'DemandeController::create', ['as' => 'admin-ajout-demande', 'filter' => 'csrf']);

//Validé
$routes->get('dashboard/liste-demandes-valides', 'DemandeValidesController::affiche', ['as' => 'admin-liste-demandes-valides']);

//Terminé
$routes->get('dashboard/liste-demandes-terminees', 'DemandeTermineController::affiche', ['as' => 'admin-liste-demandes-terminees']);

//Routes pour la gestion de la liste des test du contrôle du technique
$routes->get('liste-test', 'TestController::affiche', ['as' => 'test-liste']);

$routes->get('suppr-test/(:num)', 'TestController::delete/$1', ['as' => 'test-suppr']);

$routes->get('modif-test/(:num)', 'TestController::modif/$1', ['as' => 'modif-test']);
$routes->post('modif-test', 'TestController::update', ['as' => 'test-modif']);

$routes->get('ajout-test', 'TestController::ajout', ['as' => 'ajout-test']);
$routes->post('create-test', 'TestController::create', ['as' => 'test-ajout']);

//Contrôle technoque terminé
$routes->get('resultats-contrôle-technique', 'ResultatTestConlleur::affiche', ['as' => 'resultats-tests']);


$routes->get('dashboard', 'Dashboard::index');

//login et logout
service('auth')->routes($routes);
$routes->get('/logout', 'LogoutController::logout', ['as' => 'logout']);

// API disponibilité CT (comptage par date/heure)
$routes->get('api/ct/availability', 'DemandeController::ctAvailability', ['as' => 'ct-availability']);

$routes->post('liste-clients', 'ClientController::mail');
$routes->post('dashboard/list-demande-en-attente', 'DemandeController::mail');

$routes->get('modif-annee(:num)','EleveController::modifAnnee/$1',['as' => 'annee-modif']);

$routes->get('controletechnique-(:num)','ControleTechniqueController::affiche/$1',['as' => 'controle-technique']);
$routes->post('controletechnique/save-etat', 'ControleTechniqueController::saveEtat');
$routes->post('controletechnique/save-controleur', 'ControleTechniqueController::saveControleur');
$routes->post('controletechnique/save-commentaire', 'ControleTechniqueController::saveCommentaire');
$routes->post('controletechnique/terminer', 'ControleTechniqueController::terminer');
$routes->get('confirm-suppr', 'ClientController::confirmDelete', ['as' => 'suppr-confirm']);
