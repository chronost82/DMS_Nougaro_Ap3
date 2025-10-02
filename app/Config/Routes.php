<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Routes pour la gestion des clients
$routes->get('liste-clients', 'liste-clients::affiche', ['as'=> 'admin-liste-clients']);

$routes->post('suppr-client', 'client::delete', ['as'=> 'admin-suppr-client']);

$routes->get('modif-client', 'client::modif', ['as'=> 'admin-client-modif']);
$routes->post('modif-client', 'client::update', ['as'=> 'admin-client-modif']);

$routes->get('ajout-client', 'client::ajout', ['as'=> 'admin-ajout-client']);
$routes->post('ajout client', 'client::create', ['as'=> 'admin-ajout-client']);

//Routes pour la gestion des élèves
$routes->get('liste-eleves', 'liste-eleve::affiche', ['as'=> 'admin-liste-eleves']);

$routes->post('suppr-eleve', 'eleves::delete', ['as'=> 'admin-suppr-eleves']);

$routes->get('modif-eleve', 'eleve::modif', ['as'=> 'admin-eleve-modif']);
$routes->post('modif-eleve', 'eleve::update', ['as'=> 'admin-eleve-modif']);

$routes->get('ajout-eleve', 'eleve::ajout', ['as'=> 'admin-ajout-eleve']);
$routes->post('ajout eleve', 'eleve::create', ['as'=> 'admin-ajout-eleve']);

