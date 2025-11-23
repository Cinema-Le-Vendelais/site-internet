<?php

/**
 * Page de contact
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 21/10/2024
 * Dépendances :
 * - PHPMailer - Envoi de mails
 */


date_default_timezone_set('Europe/Paris');

ini_set("display_errors", 1);

function get_ip_address()
{
    $ip_address = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP']; // Get the shared IP Address
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //Check if the proxy is used for IP/IPs
        // Split if multiple IP addresses exist and get the last IP address
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $multiple_ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip_address = trim(current($multiple_ips));
        } else {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}

/* REQUIREMENTS */

use PHPMailer\PHPMailer\PHPMailer;

$suspectMessage = "Votre demande à été considérée comme suspecte par nos services. Elle à été transmise à un administrateur, le temps d'attente devrais donc être rallongé.";

/* CAPTCHA */

function isHCaptchaValid($code)
{
    $data = array(
        'secret' => $_ENV["HCAPTCHA_SECRET_KEY"],
        'response' => $code
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    // var_dump($response);
    $responseData = json_decode($response);
    return $responseData->success;
}

/* ERRORS */
ini_set("display_errors", 0);
/*$errLevel = error_reporting(E_ALL ^ E_NOTICE);
error_reporting($errLevel);*/

/* MAIL */
function sendMail($subject, $data, $to)
{
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = $_ENV["EMAIL_HOST"];
    $mail->Port = $_ENV["EMAIL_PORT"];
    $mail->CharSet = "UTF-8";
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["EMAIL_USER"];
    $mail->Password = $_ENV["EMAIL_PASSWORD"];
    $mail->setFrom($_ENV["EMAIL_USER"], "Webmaster");

    $mail->addAddress($to, $to);

    $mail->Subject = $subject;
    $mail->msgHTML($data);
    $mail->AltBody = 'HTML messaging not supported';

    if ($mail->send()) {
        echo '<script>alert("Mail envoyé !")</script>';
        $_SESSION["last-form-send"] = date("Y-m-d H:i:s");
    } else {
        echo 'alert("Une erreur s\'est produite :/");console.log("' . $mail->ErrorInfo . '")</script>';
    }
}

function suspectSpam($ip, $post, $reason)
{

    unset($post["h-captcha-response"]);

    $data = json_encode($post, JSON_PRETTY_PRINT);

    $body = "
        <h1>Nouvelle demande d'un formulaire de contact suspectée de spam :</h1>
        <pre>$data</pre>

        <p>Adresse IP : ($ip)</p>
        <p>Raison : $reason</p>
    ";

    sendMail("SUSPICION DE SPAM", $body, $_ENV["EMAIL_USER"]);
    $_SESSION["last-form-send"] = date("Y-m-d H:i:s");
}

/* SUSPECTION DE SPAM V1 - USER AGENT */
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    suspectSpam($ip, $_POST, "Aucune user-agent");
    echo $suspectMessage;
    die();
}

if (!empty($_POST) && isHCaptchaValid($_POST["h-captcha-response"])) {

    /* SUSPECTION DE SPAM V3 - POT DE MIEL */

    if (!empty($_POST["reason"])) {
        suspectSpam($ip, $_POST, "Remplissage du champ pot de miel 'reason'");
        echo $suspectMessage;
        die();
    }

    $currentHour = date("G");

    /* SUSPECTION DE SPAM V4 - VERIFICATION HORAIRES */

    if ($currentHour >= 23 || $currentHour <= 6) {
        suspectSpam($ip, $_POST, "Formulaire envoyé entre 23h et 6h");
        echo $suspectMessage;
        die();
    }

    /* SUSPECTION DE SPAM V5 - WRONG EMAIL */

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        suspectSpam($ip, $_POST, "Adresse email suspectée comme invalide");
        echo $suspectMessage;
        die();
    }

    /* SUSPECTION DE SPAM V6 - WRONG EMAIL HOST NAME */

    $atPos = mb_strpos($_POST["email"], '@');
    $domain = mb_substr($_POST["email"], $atPos + 1);

    if (!checkdnsrr($domain . '.', 'MX')) {

        suspectSpam($ip, $_POST, "Adresse invalide : Le nom de domaine $domain n'existe pas !");
        echo $suspectMessage;
        die();
    }


    $date1 = new DateTime(isset($_SESSION["last-form-send"]) ? $_SESSION["last-form-send"] : "");
    $date2 = new DateTime();

    $diff = $date1->diff($date2);

    $ip = null;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $ipInfos = "
        <h3>Adresse IP :</h3>
        <ul>
            <li>Adresse : <b>$ip</b></li>
            <li>Aucune information disponible sur cette adresse ip.</li>
        </ul>
        ";

    $ipData = json_decode(file_get_contents("https://api.ipbase.com/v1/json/$ip"), true);

    if ($ipData && !empty($ipData) && isset($ipData["country_name"]) && isset($ipData["region_name"])) {
        $loc = $ipData["region_name"] . "/" . $ipData["country_name"];
        $ipInfos = "
            <h3>Adresse IP :</h3>
            <ul>
                <li>Adresse : <b>$ip</b></li>
                <li>Localisation : <b>$loc</b></li>
            </ul>
        ";

        /* SUSPECTION DE SPAM V7 - WRONG IP */

        if (!$ip || $ipData["country_code"] != "FR") {
            suspectSpam($ip, $_POST, "L'adresse Ip n'est pas présente ou ne se situe pas en France.");
            echo $suspectMessage;
            die();
        }
    }

    if (($diff->i > 5) || (!isset($_SESSION["last-form-send"]) || empty($_SESSION["last-form-send"]))) {
        switch ($_POST["action-type"]) {
            case "devenir-benevole":
                if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["demande"])) {
                    $name = htmlspecialchars($_POST["name"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $phone = htmlspecialchars($_POST["phone"]);
                    $demande = htmlspecialchars($_POST["demande"]);

                    $data = "
                        <h2>$name souhaite devenir bénévole !</h2>
                        <span>Message automatique en provenance du formulaire 'Devenir bénévole' du site internet levendelaiscinema.fr</span>
    
                        <h3>Informations :</h3>
    
                        <ul>
                            <li>Nom & Prénom : <b>$name</b></li>
                            <li>Email : <b>$email</b></li>
                            <li>Numéro de téléphone : <b>$phone</b></li>
                        </ul>
    
                        <h3>Commentaire(s) : </h3>
                        <p>$demande</p>

                        $ipInfos
                    ";

                    sendMail("Requête bénévole", $data, "association@levendelaiscinema.fr");
                }
                break;

            case "suggestion-film":
                if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["film"])) {
                    $name = htmlspecialchars($_POST["name"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $phone = htmlspecialchars($_POST["phone"]);
                    $demande = htmlspecialchars($_POST["film"]);

                    $data = "
                    <h2>$name propose le film $demande !</h2>
                    <span>Message automatique en provenance du formulaire 'Suggestion film' du site internet levendelaiscinema.fr</span>
            
                    <h3>Informations :</h3>
            
                    <ul>
                        <li>Nom & Prénom : <b>$name</b></li>
                        <li>Email : <b>$email</b></li>
                        <li>Numéro de téléphone : <b>$phone</b></li>
                        <li>Film proposé : <b>$demande</b></li>
                    </ul>

                    $ipInfos
                    ";

                    sendMail("Propostion film", $data, "programmation@levendelaiscinema.fr");
                }

                break;

            case "louer-salle":
                if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["event"]) && !empty($_POST["dates"])) {
                    $name = htmlspecialchars($_POST["name"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $phone = htmlspecialchars($_POST["phone"]);
                    $event = htmlspecialchars($_POST["event"]);
                    $dates = htmlspecialchars($_POST["dates"]);

                    $data = "
                    <h2>$name voudrait louer la salle !</h2>
                    <span>Message automatique en provenance du formulaire 'Location de salle' du site internet levendelaiscinema.fr</span>
            
                    <h3>Informations :</h3>
            
                    <ul>
                        <li>Nom & Prénom : <b>$name</b></li>
                        <li>Email : <b>$email</b></li>
                        <li>Numéro de téléphone : <b>$phone</b></li>
                    </ul>
            
                    <h3>Evènement</h3>
            
                    <ul>
                        <li><b>Description</b> : $event</li>
                        <li><b>Dates</b> : $dates</li>
                    </ul>

                    $ipInfos
                    ";

                    sendMail("Location salle", $data, "salle@levendelaiscinema.fr");
                }

                break;


            /*case "commander-affiche":
                if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["affiche"]) && !empty($_POST["format"])) {
                    $name = htmlspecialchars($_POST["name"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $phone = htmlspecialchars($_POST["phone"]);
                    $affiche = htmlspecialchars($_POST["affiche"]);
                    $format = str_replace("-", "x", $_POST["format"]);

                    $data = "
                    <h2>$name tente de vous contacter !</h2>
                    <span>Message automatique en provenance du formulaire 'Commander une affiche' du site internet levendelaiscinema.fr</span>
            
                    <h3>Informations :</h3>
            
                    <ul>
                        <li>Nom & Prénom : <b>$name</b></li>
                        <li>Email : <b>$email</b></li>
                        <li>Numéro de téléphone : <b>$phone</b></li>
                    </ul>
            
                    <h3>Commande</h3>
            
                    <ul>
                        <li><b>Affiche</b> : $affiche</li>
                        <li><b>Format</b> : $format</li>
                    </ul>

                    $ipInfos
                    ";

                    sendMail("Commande affiche", $data, "affiches@levendelaiscinema.fr");
                }
                break;*/

            case 'form-autre':
                if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["text"])) {
                    $name = htmlspecialchars($_POST["name"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $phone = htmlspecialchars($_POST["phone"]);
                    $text = htmlspecialchars($_POST["text"]);

                    $data = "
                    <h2>$name tente de vous contacter !</h2>
                    <span>Message automatique en provenance du formulaire 'Autre demande' du site internet levendelaiscinema.fr</span>
            
                    <h3>Informations :</h3>
            
                    <ul>
                        <li>Nom & Prénom : <b>$name</b></li>
                        <li>Email : <b>$email</b></li>
                        <li>Numéro de téléphone : <b>$phone</b></li>
                    </ul>
            
                    <h3>Texte</h3>
                    <p>$text</p>

                    $ipInfos
                    ";

                    sendMail("Nouvelle demande", $data, "association@levendelaiscinema.fr");
                }
                break;
        }
    } else {
        $dateFuture = clone $date1;
        $dateFuture->add(new DateInterval('PT5M'));

        $diff2 = $dateFuture->diff(new DateTime());

        $ecart = $diff2->format('%i minutes et %s secondes');
        echo "<script>alert('Vous devez attendre $ecart')</script>";
    }
}


?>

<script src='https://js.hcaptcha.com/1/api.js' async defer></script>

<h1>Nos formulaires de contact</h1>

<b style="color: red;"><i class="fa-solid fa-triangle-exclamation"></i> Merci de ne pas abuser de ces formulaires de contact et d'utiliser le formulaire approprié ! Veuillez noter que les formulaires envoyés entre 23h et 6h seront automatiquement comptés comme spam !</b>

<div class="accordion forms">
    <div class="accordion-item">
        <button id="accordion-button-1" aria-expanded="false">
            <span class="accordion-title">Devenir bénévole</span>
            <span class="icon" aria-hidden="true"></span>
        </button>
        <div class="accordion-content">
            <form class="form" method="post" id="devenir">
                <div class="elem">
                    <div class="warning">Merci ne n'utiliser ce formulaire que si vous souhaitez devenir bénévole. Pour toutes autre demandes consultez nos autres formulaires ou rendez vous directement sur "Autres demandes"</div>

                    <p><span class="red">*</span> : Champs obligatoires</p>

                    <div class="row">
                        <label for="name">Nom & Prénom (<span class="red">*</span>)</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="row">
                        <label for="email">Email (<span class="red">*</span>)</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="row">
                        <label for="phone">Numéro de téléphone (<span class="red">*</span>)</label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" required>
                    </div>

                    <div class="row">
                        <label for="demande">Commentaire (<span class="red">*</span>)</label>
                        <textarea name="demande" required></textarea>
                    </div>

                    <input type="text" class="reason" name="reason">
                </div>

                <div>
                    <input type="hidden" name="action-type" value="devenir-benevole">
                    <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>
                    <div>
                        <input type="checkbox" id="consentement1" name="consentement1" required>
                        <label for="consentement1">Je consens à la collecte et au traitement de mes données.</label>
                    </div>
                    <button>
                        Envoyer
                    </button>
                </div>

            </form>
        </div>
    </div>
    <div class="accordion-item">
        <button id="accordion-button-2" aria-expanded="false">
            <span class="accordion-title">Suggestion de film</span>
            <span class="icon" aria-hidden="true"></span>
        </button>
        <div class="accordion-content">
            <form method="POST" class="form">
                <div class="elem">
                    <div class="warning">Merci ne n'utiliser ce formulaire que si vous souhaitez proposer un film. Pour toutes autre demandes consultez nos autres formulaires ou rendez vous directement sur "Autres demandes"</div>

                    <p><span class="red">*</span> : Champs obligatoires</p>

                    <div class="row">
                        <label for="password">Nom & Prénom (<span class="red">*</span>)</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="row">
                        <label for="password">Email (<span class="red">*</span>)</label>
                        <input type="email" name="email" required>
                    </div class="row">

                    <div class="row">
                        <label for="film">Numéro de téléphone (<span class="red">*</span>)</label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" required>
                    </div>

                    <div class="row">
                        <label for="film">Quel film voulez vous proposer ? (<span class="red">*</span>)</label>
                        <input type="text" name="film" required>
                    </div>

                    <input type="text" class="reason" name="reason">
                </div>

                <div>
                    <input type="hidden" name="action-type" value="suggestion-film">
                    <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>
                    <div>
                        <input type="checkbox" id="consentement2" name="consentement2" required>
                        <label for="consentement2">Je consens à la collecte et au traitement de mes données.</label>
                    </div>
                    <button>
                        Envoyer
                    </button>
                </div>

            </form>
        </div>
    </div>
    <div class="accordion-item">
        <button id="accordion-button-3" aria-expanded="false">
            <span class="accordion-title">Mise à disposition de la salle</span>
            <span class="icon" aria-hidden="true"></span>
        </button>
        <div class="accordion-content">
            <form method="POST" class="form">
                <div class="elem">
                    <div class="warning">Merci ne n'utiliser ce formulaire que si vous souhaitez reserver notre salle. Pour toutes autre demandes consultez nos autres formulaires ou rendez vous directement sur "Autres demandes"</div>

                    <p><span class="red">*</span> : Champs obligatoires</p>

                    <div class="row">
                        <label for="password">Nom & Prénom (<span class="red">*</span>)</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="row">
                        <label for="password">Email (<span class="red">*</span>)</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="row">
                        <label for="phone">Numéro de téléphone (<span class="red">*</span>)</label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" required>
                    </div>

                    <div class="row">
                        <label for="event">Description de l'évènement (<span class="red">*</span>)</label>
                        <textarea name="event" required></textarea>
                    </div>

                    <div class="row">
                        <label for="dates">Date(s) souhaitée(s) (<span class="red">*</span>)</label>
                        <input type="text" name="dates" required>
                    </div>

                    <input type="text" class="reason" name="reason">
                </div>

                <div>
                    <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>
                    <div>
                        <input type="checkbox" id="consentement3" name="consentement3" required>
                        <label for="consentement3">Je consens à la collecte et au traitement de mes données.</label>
                    </div>
                    <input type="hidden" name="action-type" value="louer-salle">
                    <button>
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="accordion-item">
        <button id="accordion-button-4" aria-expanded="false">
            <span class="accordion-title">Commander une affiche</span>
            <span class="icon" aria-hidden="true"></span>
        </button>
        <div class="accordion-content">

        <div class="form">
            <div class="warning">
                Un nouveau système de commande d'affiches à été mis en place.
                Rendez vous dans l'onglet Affiches du menu ou <a href="/affiches">cliquez ici</a>
            </div>
        </div>

        
        <!--<form method="POST" class="form" id="affiches">
                <div class="elem">

                    <div class="warning">Merci ne n'utiliser ce formulaire que si vous souhaitez commander une/des affiches. Pour toutes autre demandes consultez nos autres formulaires ou rendez vous directement sur "Autres demandes"</div>

                    <p><span class="red">*</span> : Champs obligatoires</p>

                    <div class="row">
                        <label for="name">Nom & Prénom (<span class="red">*</span>)</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="row">
                        <label for="email">Email (<span class="red">*</span>)</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="row">
                        <label for="tel">Numéro de téléphone (<span class="red">*</span>)</label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" required>
                    </div>

                    <div class="row">
                        <label for="affiche">Film (<span class="red">*</span>)</label>
                        <input name="affiche" type="text" required>
                    </div>

                    <div class="row">
                        <label for="format">Format (<span class="red">*</span>)</label>
                        <select name="format">
                            <option value="160-120">160x120 - 10€</option>
                            <option value="60-40">60x40 - 5€</option>
                        </select>
                    </div>

                    <input type="text" class="reason" name="reason">
                </div>

                <div>
                    <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>
                    <input type="hidden" name="action-type" value="commander-affiche">
                    <div>
                        <input type="checkbox" id="consentement4" name="consentement4" required>
                        <label for="consentement4">Je consens à la collecte et au traitement de mes données.</label>
                    </div>
                    <button>
                        Envoyer
                    </button>
                </div>
            </form>-->
        </div>
    </div>
    <div class="accordion-item">
        <button id="accordion-button-5" aria-expanded="false">
            <span class="accordion-title">Autres demandes</span>
            <span class="icon" aria-hidden="true"></span>
        </button>
        <div class="accordion-content">
        <form method="POST" class="form">
                <div class="elem">
                    <p><span class="red">*</span> : Champs obligatoires</p>

                    <div class="row">
                        <label for="password">Nom & Prénom (<span class="red">*</span>)</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="row">
                        <label for="password">Email (<span class="red">*</span>)</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="row">
                        <label for="film">Numéro de téléphone (<span class="red">*</span>)</label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" required>
                    </div>

                    <div class="row">
                        <label for="film">Une question, besoin d'aide ?</label>
                        <textarea type="text" name="text" required></textarea>
                    </div>

                    <input type="text" class="reason" name="reason">
                </div>

                <div>
                    <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>
                    <input type="hidden" name="action-type" value="form-autre">
                    <div>
                        <input type="checkbox" id="consentement5" name="consentement5" required>
                        <label for="consentement5">Je consens à la collecte et au traitement de mes données.</label>
                    </div>
                    <button>
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<p>En remplissant l'un des formulaires de contact ci-dessus, vous acceptez que seules les données renseignées soient communiquées à nos services afin de prendre en compte votre demande. En acceptant, vous consentez à ce que toutes les données que vous fournissez (email, téléphone, etc.) soient visibles par tous les membres de l'association. Les données ne seront ni stockées par un service interne ni cryptées.</p>

<script>
    const items = document.querySelectorAll('.accordion button');

    function toggleAccordion() {
        const itemToggle = this.getAttribute('aria-expanded');

        for (i = 0; i < items.length; i++) {
            items[i].setAttribute('aria-expanded', 'false');
        }

        if (itemToggle == 'false') {
            this.setAttribute('aria-expanded', 'true');
        }
    }

    items.forEach((item) => item.addEventListener('click', toggleAccordion));
</script>
<!--<h1>Les formulaires de contact sont actuellement en maintenance. Veuillez nous contacter via l'adresse <a href="mailto:association@levendelaiscinema.fr">association@levendelaiscinema.fr</a> ou par téléphone au <a href="tel:0299761663">02 99 76 16 63</a></h1>-->
</div>