<?php

/**
 * Configuration générale
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 21/10/2024
 * TODO :  Paramètres dans un meilleur format et un fichier différent
*/

ini_set("display_errors", 0);

// Paramètres généraux
define("AUTHOR", "Liam CHARPENTIER");
define("VERSION", "1.0.2");
define("BASE", "https://".$_SERVER['SERVER_NAME']);

define("MAINTENANCE", false);

define("WHITELISTED_IPS", array("90.27.221.184", "2a01:cb08:90d4:8c00:8947:fb95:df0f:b2cc"));

// Récupération de l'adresse IP
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// Récupérer l'url actuel
$currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$currentQuery = parse_str(parse_url($currentURL, PHP_URL_QUERY) ? parse_url($currentURL, PHP_URL_QUERY) : "", $queryes);


// Si le navigateur a demandé un cache non expiré, on l'envoie avec des headers spéciaux
if ( (isset($queryes['no-cache']) && $queryes['no-cache'] == '1') || (isset($_COOKIE['no-cache']) && $_COOKIE['no-cache'] == '1') ){
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

/**
 * Fonction qui permet de rediriger vers une page
 *
 * @param to $to URL vers lequel on redirige
 * @return null
*/
function redirect($to, $timeout=0){
    if (headers_sent()) {
        die("<script>setTimeout(() => {window.location.href = '$to'}, $timeout)</script>");
    }
    else{
        usleep($timeout);
        exit(header("Location: ".$to));
    }
}

/**
 * Fonction qui permet d'afficher une notification
 *
 * @param to $to URL vers lequel on redirige
 * @return null
*/
function showNotification($message, $type="success"){
    echo "<script>new Notyf().$type('$message')</script>";
}


/**
 * Fonction qui permet de récupérer le lien complet vers un chemin CDN
 *
 * @param path $path Chemin CDN
 * @return string Retourne le chemin complet.
*/
function loadCdnUrl($path) {
    $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    parse_str(parse_url($currentURL, PHP_URL_QUERY) ? parse_url($currentURL, PHP_URL_QUERY) : "", $queryes);


    $cdnBase = "https://cdn.levendelaiscinema.fr/";
    $url = $cdnBase.$path;

    $no_cache = (isset($queryes['no-cache']) && $queryes['no-cache'] == '1') || (isset($_COOKIE['no-cache']) && $_COOKIE['no-cache'] == '1');
    if ($no_cache) {
        return $url . '?no_cache=1';
    } else {
        return $url;
    }
    
}