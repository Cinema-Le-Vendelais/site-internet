<?php

/**
 * Configuration générale
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 21/10/2024
*/



ini_set("display_errors", 1);

require_once __DIR__."/../../../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__."/../../../"));
$dotenv->load();

// Paramètres généraux
define("BASE", $_ENV["SCHEME"]."://".$_SERVER['SERVER_NAME']);

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

function getRootDomain() {
    $parts = explode('.', $_SERVER['SERVER_NAME']);
    return count($parts) >= 2 ? implode('.', array_slice($parts, -2)) : $_SERVER['SERVER_NAME'];
}

/**
 * Génère une URL d'asset avec version automatique (timestamp)
 * 
 * @param string $path Chemin relatif de l'asset (ex: /css/style.css)
 * @return string URL avec version
 */
function asset($url, $compress=0) {
    $cdnPrefix = "cdn://";

    if (strpos($url, $cdnPrefix) === 0) {

        $clean = preg_replace('/\?.*$/', '', substr($url, strlen($cdnPrefix)));

        $fullPath = __DIR__."/../../../cdn/" . $clean;
    
        // Si le fichier existe, ajouter le timestamp de modification
        if (file_exists($fullPath)) {
            $version = filemtime($fullPath);
            return str_replace("cdn://", $_ENV["SCHEME"]."://cdn.".getRootDomain()."/", $url) . '?v=' . $version. ($compress ? '&compress=1' : '');
        }
    }

    return $url;
}