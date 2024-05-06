<?php
namespace dacode;

use dacode\controller\CtrlDacode;

require_once '../../vendor/autoload.php';

if (session_status() != PHP_SESSION_ACTIVE) session_start();

$ctrlDacode = new CtrlDacode();


// ###### Dans le if = instructions exécutées que pour requête ajax ########
// ###### Dans le else = instructions exécutées que pour requête http standard ########
// ###### au delà = instructions exécutées pour les 2 types de requête ########
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isRequestAjax = true;
}
else {
    $isRequestAjax = false;
}


if (file_exists("./param.ini")) {
    $param = parse_ini_file("./param.ini", true);
    extract($param['APPWEB']);
}

define('APP_ROOT', $app_root);
define('PUBLIC_ROOT', $public_root);

$uri = $_SERVER['REQUEST_URI']; // = partie de l'url après le nom de domaine

$route = explode('?',$uri)[0]; // on garde la partie avant le ? ex /rubrique?id=1

$method = strtolower($_SERVER['REQUEST_METHOD']);
// echo $route . ' - ' . $method;


// Évaluation de la requête pour traitement
if ($method == 'get' && !$isRequestAjax){ // Non ajax et methode get (lecture seule)
    match($route){
        APP_ROOT                            => $ctrlDacode->getIndex(),
        APP_ROOT .'/'                       => $ctrlDacode->getIndex(),
        APP_ROOT .'/accueil'                => $ctrlDacode->getIndex(),
        APP_ROOT .'/login'                  => $ctrlDacode->getLogIn(),
        APP_ROOT .'/create-account'         => $ctrlDacode->getSignIn(),
        APP_ROOT .'/logout'                 => $ctrlDacode->getLogout(),
        APP_ROOT .'/my-account'             => $ctrlDacode->getMyAccount(),
        APP_ROOT .'/playground'             => $ctrlDacode->getPlayground(),
        APP_ROOT .'/about'                  => $ctrlDacode->getAbout(),
        default                             => $ctrlDacode->getNotFound(),
    };
}
elseif ($method == 'post' && !$isRequestAjax) { // Non ajax et methode post (modification de données)
    match($route){
        APP_ROOT .'/login'                 => $ctrlDacode->getLogIn(),
        APP_ROOT .'/create-account'        => $ctrlDacode->getSignIn(),
        default                            => $ctrlDacode->getNotFound(),
    };
}
elseif ($method == 'get' && $isRequestAjax) { // Ajax et methode get (lecture seule)
    match($route){
        APP_ROOT .'/playground/loadWorkspace'   => $ctrlDacode->loadUserDataFromSlot(),
        default                                 => ajaxError(),
    };
}
elseif ($method == 'post' && $isRequestAjax) { // Ajax et methode post (modification de données)
    match($route){
        APP_ROOT .'/playground/deleteWorkspace' => $ctrlDacode->deleteUserDataFromSlot(),
        APP_ROOT .'/playground/saveWorkspace'   => $ctrlDacode->saveUserDataFromSlot(),
        default                                 => ajaxError(),
    };
}
else {
    $cntrlFavoris->getIndex();
}
// Pas de code à partir d'ici (sauf functions)


function ajaxError() {
    http_response_code(404);
    exit();
}