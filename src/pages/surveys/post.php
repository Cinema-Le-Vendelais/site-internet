<?php

ini_set("display_errors", 1);

$data = json_decode(file_get_contents("php://input"), true);

function isCaptchaValid($code)
{
    $data = array(
        'secret' => $_ENV["TURNSTILE_SECRET_KEY"],
        'response' => $code
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://challenges.cloudflare.com/turnstile/v0/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);
    return $responseData->success;
}


$url = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($url, '/'));
$surveyId = $segments[count($segments) - 2] ?? null;

$survey = $db->prepare("SELECT * FROM `surveys` WHERE `id` = ?");
$survey->execute(
    array(
        $surveyId
    )
);

$survey = $survey->fetch();


$replies = $db->prepare("SELECT * FROM `surveys-replies` WHERE `surveyId` = ?");
$replies->execute(
    array(
        $surveyId
    )
);

if (!empty($data) && array_key_exists("captcha-response", $data) && array_key_exists("data", $data)) {

    // Si le captcha n'est pas valide
    if (!isCaptchaValid($data["captcha-response"])) {
        die(json_encode(["success" => false, "message" => "Captcha invalide !"]));
        exit;
    }

    if ($replies->rowCount() >= $survey["max_replies"]) {
        die(json_encode(["success" => false, "message" => "Le nombre maximum de réponses pour ce sondage à été atteint."]));
        exit;
    }

    $exe = $db->prepare("SELECT * FROM `surveys-replies` WHERE `surveyId` = ? AND `ip` = ?");
    $exe->execute(
        array(
            $surveyId,
            hash("SHA256", $ip)
        )
    );

    // Si il y a plus de 4 réponses avec la même adresse ip
    if ($exe->rowCount() >= 4) {
        die(json_encode(["success" => false, "message" => "Vous avez déjà répondu au sondage."]));
        exit;
    }

    $nbCount = 0;

    foreach ($exe->fetchAll() as $replie) {
        $nbCount += $replie["nbCount"];
    }

    $nbCountToPush = 0;

    $numbercountQuestions = [];

    // Parcourir les pages et les éléments pour récupérer les questions numbercount
    $d = json_decode($survey["data"], true);
    if (isset($d['pages'])) {
        foreach ($d['pages'] as $page) {
            if (isset($page['elements'])) {
                foreach ($page['elements'] as $element) {
                    if ($element['type'] === 'numbercount') {
                        // Ajouter le nom de la question à la liste des questions numbercount
                        $numbercountQuestions[] = $element['name'];
                    }
                }
            }
        }
    }

    foreach ($numbercountQuestions as $question) {
        if (isset($data["data"][$question]) && is_numeric($data["data"][$question])) {
            $nbCount += $data["data"][$question];
            $nbCountToPush += $data["data"][$question];
        }
    }

    if ($nbCount > $survey["max_replies"]) {
        die(json_encode(["success" => false, "message" => "Nombre de réponses max atteint. Il y en a " . ($nbCount - $survey["max_replies"]) . " en trop."]));
        exit;
    }

    $exe = $db->prepare("INSERT INTO `surveys-replies` (`surveyId`, `data`, `ip`, `nbCount`) VALUES (?, ?, ?, ?)");

    /* Encrypter le résultat */

    $encryption_key = $_ENV["NEWSLETTER_KEY"];
    $cipher = $_ENV["NEWSLETTER_CIPHER"];

    $encryptedData = openssl_encrypt(json_encode($data["data"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $cipher, $encryption_key, 0);


    $exe->execute(
        array(
            $surveyId,
            $encryptedData,
            hash("SHA256", $ip),
            $nbCountToPush ? $nbCountToPush : 1
        )
    );

    echo json_encode(["success" => true, "message" => "Réponse enregistrée !"]);

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES) && !empty($_POST["surveyId"])) {
    // Chemin où les fichiers seront sauvegardés
    $uploadDir = __DIR__."/../../cloud/surveys/".$_POST["surveyId"]."/";

    // Vérifie si le dossier existe sinon le crée
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $response = [];

    $dangerousExtensions = [
        // Scripts exécutables
        'php', 'php3', 'php4', 'php5', 'phtml', 'phar',
        'asp', 'aspx', 'jsp', 'cfm', 'cgi', 'pl',
    
        // Fichiers de configuration ou exécutable serveur
        'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'bash', 'bat', 'cmd', 'com', 'exe', 'msi', 'bin', 'cgi',
    
        // Scripts JavaScript
        'js', 'mjs', 'vb', 'vbs', 'vbe', 'wsf', 'wsh', 'ps1', 'psm1', 'psd1',
    
        // Fichiers de shell ou d'automatisation
        'py', 'rb', 'jar', 'scala', 'go', 'lua', 'pl', 'tcl',
    
        // Fichiers de bases de données et binaires
        'sql', 'db', 'db3', 'sdb', 'sqlite', 'bak', 'backup', 'dll', 'dat',

    
        // Fichiers potentiellement dangereux
        'scr', 'sct', 'mhtml', 'xhtml', 'jse', 'lnk', 'inf', 'reg', 'drv', 'sys'
    ];
    
    // Boucle à travers chaque fichier reçu
    foreach ($_FILES as $file) {
        $fileTmpPath = $file['tmp_name'];
        $fileName = basename($file['name']);
        $destination = $uploadDir . $fileName;

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if(in_array($ext, $dangerousExtensions))
        {
            header('Content-Type: application/json');
            $response["success"] = false;
            $response["message"] = "Fichier dangereux envoyé !";
            $response[$fileName] = null;
            echo json_encode($response);
            exit();
        }

        // Déplace le fichier vers le répertoire de destination
        if (move_uploaded_file($fileTmpPath, $destination)) {
            $response[$fileName] = "https://cloud.levendelaiscinema.fr/surveys/".$_POST["surveyId"]."/".basename($destination); // Retourne le chemin relatif du fichier enregistré
            $response["success"] = true;
        } else {
            $response["success"] = false;
            $response["message"] = "Erreur lors du déplacement du fichier !";
            $response[$fileName] = null;
        }
    }

    // Envoie la réponse JSON au client
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}