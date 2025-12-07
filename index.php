<div style="background-color:#FFFF00;padding:.5em;border-radius:16px;margin:1em 5em;">
    <h3>Maintenance en cours</h3>
    <p>Notre site sera en maintenance du <strong>7 au 14 décembre</strong>. Le site risque d'être dysfonctionnel ou complètement inaccessible par moments. </p>
    <p>Nous nous excusons pour la gêne occasionnée. <a target="_blank" href="https://www.facebook.com/cinemalevendelais">Retrouvez toute notre programmation sur Facebook</a> </p>
</div>

<?php

ini_set("display_errors", 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
    Exigences
*/

require_once(__DIR__."/../vendor/autoload.php");


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$db = new PDO('mysql:host='.$_ENV["DB_HOST"].';dbname='.$_ENV["DB_NAME"].';charset=utf8', $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

function loadSettingsFromDb(){
    
    $db = new PDO('mysql:host='.$_ENV["DB_HOST"].';dbname='.$_ENV["DB_NAME"].';charset=utf8', $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

    $settings = $db->prepare("SELECT * FROM settings WHERE id = 1");
    $settings->execute(array());

    $settings = $settings->fetch();

    return $settings;
}


require_once(__DIR__."/src/tools/default.php");

// Si il y a une maintenance & que l'adresse IP n'est pas whitelisté, on affiche la page de maintenance
if(filter_var($_ENV["MAINTENANCE"], FILTER_VALIDATE_BOOLEAN) && !in_array($ip, explode(",", $_ENV["WHITELISTED_IPS"]))){
    require_once(__DIR__."/src/errors/maintenance.php");
    die;
}

/*
    Fonctions
*/

function getCurrentPath(){
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $result = implode('/', $segments);
    
    return $result;
}

function mapScriptsAndStyles($elements){
    if($elements){
        foreach($elements as $k=>$element){
            $elements[$k] = asset($element);
        }
    }

    return $elements;
}

function get($key, $return=false){
    return array_key_exists($key, PAGE) ? PAGE[$key] : $return;
}

function generateMenu($data){
    $menuItems = array_filter($data, function ($item) {
        return isset($item['menu']) && $item['menu'] === true;
    });
    
    // Trier par menuIndex
    usort($menuItems, function ($a, $b) {
        return $a['menuIndex'] <=> $b['menuIndex'];
    });
    
    // Extraire seulement url et title
    $result = array_map(function ($item) {
        return [
            "url" => BASE."/".$item["url"],
            "title" => $item["title"],
            "icon" => array_key_exists("icon", $item) ? $item["icon"] : false,
            "short_name" => array_key_exists("short_name", $item) ? $item["short_name"] : false,
            "mobile" => array_key_exists("mobile", $item) ? $item["mobile"] : false,
            "accent" => array_key_exists("accent", $item) ? $item["accent"] : false,
            "isNew" => array_key_exists("isNew", $item) ? $item["isNew"] : false
        ];
    }, $menuItems);

    return $result;
}

function generateLegalMenu($data){
    $menuItems = array_filter($data, function ($item) {
        return isset($item['legal']) && $item['legal'] === true;
    });
    
    // Extraire seulement url et title
    $result = array_map(function ($item) {
        return [
            "url" => BASE."/".$item["url"],
            "title" => $item["title"],
        ];
    }, $menuItems);

    return $result;
}

function replace_placeholders($description, $values) {
    foreach ($values as $key => $value) {
        $description = str_replace("%$key", $value, $description);
    }
    return $description;
}

function logD($value){
    $time = date("H:i:s");
    echo <<<EOT
    <script>console.log('%c[$time] - $value', 'color: skyblue');</script>
    EOT;
}

function sendNtfy($message) {
    $url = "https://ntfy.sh/levendelais";

    $headers = [
        "X-Title: 🚨 Alerte erreur sur le site !",
        "X-Priority: 4",
        "Tags: error" 
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);

    // Vérifier s'il y a une erreur
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
    }

    // Fermer la session cURL
    curl_close($ch);
}


function sendError($code, $data){
    $jsonData = file_get_contents(__DIR__.'/src/errors/errors.json');
    $errors = json_decode($jsonData, true);

    $error = $errors[$code] ?? $errors[404];

    define("ERROR", array(
        "code" => $code,
        "name" => $error["code"]
    ));

    sendNtfy(replace_placeholders($error["description"], $data));

    require_once(__DIR__.'/src/errors/error.php');
    die;
}

/*
    Récupérer la liste des pages
*/

$PAGE_PATH = getCurrentPath();

$pages = null;
$pageData = null;

/*
    Charger le cache
*/

$redis = new Redis();

function loadWithoutRedis(){
    global $PAGE_PATH, $pageData;
    // Charger ls pages
    $jsonData = file_get_contents(__DIR__.'/pages.json');
    $pages = json_decode($jsonData, true);
    // Charger les paramètres manuellement
    define("SETTINGS", loadSettingsFromDb());
    // Charger le menu manuellement
    define("MENU", generateMenu($pages));
    // Charger le menu legal manuellement
    define("MENU_LEGAL", generateLegalMenu($pages));
    // Charger la page manuellement
    foreach ($pages as $page) {
        if ($page['url'] === $PAGE_PATH || fnmatch($page['url'], $PAGE_PATH)) {
            $pageData = $page;
            break;
        }
    }
}

//loadWithoutRedis();

try{
    $redis->connect($_ENV["REDIS_HOST"], $_ENV["REDIS_PORT"], 1);
    $redis->auth($_ENV["REDIS_PASSWORD"]);

    if ($redis->ping() == '+PONG') {
        // Charger les pages
        if ($redis->exists("levendel-pages")) {
            $pages = json_decode($redis->get('levendel-pages'), true);
        } else {
            $jsonData = file_get_contents(__DIR__.'/pages.json');
            $pages = json_decode($jsonData, true);
            $redis->set('levendel-pages', json_encode($pages));
            $redis->expire("levendel-pages", 120);
        }
    
        // Charger la page
        if ($redis->exists("levendel-page-[$PAGE_PATH]")) {
            $pageData = json_decode($redis->get("levendel-page-[$PAGE_PATH]"), true);
        } else {
            foreach ($pages as $page) {
                if ($page['url'] === $PAGE_PATH || fnmatch($page['url'], $PAGE_PATH)) {
                    $pageData = $page;
                    break;
                }
            } 
    
            if($pageData){
                $redis->set("levendel-page-[$PAGE_PATH]", json_encode($pageData));
                $redis->expire("levendel-page-[$PAGE_PATH]", 120);
            }  
        }
    
        // Charger les paramètres
        if ($redis->exists("levendel-settings")) {
            DEFINE("SETTINGS", json_decode($redis->get('levendel-settings'), true));
        } else {
            define("SETTINGS", loadSettingsFromDb());
            $redis->set('levendel-settings', json_encode(SETTINGS));
            $redis->expire("levendel-settings", 3600);
        }
    
        // Charger le menu
        if ($redis->exists("levendel-menu")) {
            DEFINE("MENU", json_decode($redis->get('levendel-menu'), true));
        } else {
            define("MENU", generateMenu($pages));
            $redis->set('levendel-menu', json_encode(MENU));
            $redis->expire("levendel-menu", 3600);
        }
    
        // Charger le menu légal
        if ($redis->exists("levendel-menu-legal")) {
            DEFINE("MENU_LEGAL", json_decode($redis->get('levendel-menu-legal'), true));
        } else {
            define("MENU_LEGAL", generateLegalMenu($pages));
            $redis->set('levendel-menu-legal', json_encode(MENU_LEGAL));
            $redis->expire("levendel-menu-legal", 3600);
        }
    
        $redis->close();
    
    } else {
        loadWithoutRedis();
    }
}
catch (Exception $e) {
    loadWithoutRedis();
}

define('PAGE', $pageData);



if ($pageData) {
    
    if(get("type") == "model" && !array_key_exists(get("slug"), SETTINGS)) return sendError(1007, array("model" => get("slug"), "page" => PAGE["title"]));


    if(get("includes", true))
    {
        require_once(__DIR__."/src/includes/head.php");

        createHead(
            PAGE["title"],
            mapScriptsAndStyles(get("styles", [])),
            mapScriptsAndStyles(get("scripts", [])),
        );

        require_once(__DIR__."/src/includes/header.php");
    }

    if(get("type") == "model" && get("slug")){
        echo SETTINGS[get("slug")];
    }
    else if(!get("isEmpty")){

        $file_path = __DIR__."/src/pages/".PAGE["path"];

        if(!file_exists($file_path))
        {
            sendError(1006, array("file" => $file_path, "page" => PAGE["title"]));
            die;
        }

        require_once($file_path);
    }

    if(get("includes", true))
    {
        require_once(__DIR__."/src/includes/footer.php");
    }
} else {
    header("HTTP/1.0 404 Not Found");
    require_once(__DIR__."/src/errors/404.php");
    die;
}