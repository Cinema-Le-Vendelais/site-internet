<?php

/**
 * Page d'inscription à la newsletter
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 02/03/2025
 * Dépendances :
 * - PHPMainer - Envoi de mails
*/

use PHPMailer\PHPMailer\PHPMailer;

?>

<form method="POST" class="form">
    <div class="elem">
        <h2>Inscription à la newsletter</h2>

        <div class="row">
            <label for="email">Email (<span class="red">*</span>)</label>
            <input type="email" name="email" required>
            <div class="cf-turnstile" data-theme="light" data-sitekey="0x4AAAAAAAzqwHdWjZiRD2bI"></div>
        </div class="row">
    </div>

    <div>
        <button>
            S'inscrire
        </button>
    </div>
</form>

<div>
    <p>La newsletter du cinéma est composée de tous <b>les prochains films du mois</b>. Elle est envoyée par les adresses emails suivants :</p>
    <ul class="email-list">
        <li>newsletter@levendelaiscinema.fr</li>
        <li>newsletter.levendelais@gmail.com (email de secours)</li>
        <li>ne-pas-repondre@levendelaiscinema.fr (email d'envoi automatique)</li>
    </ul>
    <p class="alert">⚠️ Si vous recevez un e-mail provenant d'une autre adresse, ne l'ouvrez pas et signalez-le.</p>
    <p class="notice">
        En ajoutant votre adresse e-mail, vous acceptez que nous utilisions des techniques marketing
        (comme le suivi des ouvertures) pour améliorer nos services et éviter le spam. Ce suivi est anonyme.
    </p>
</div>



<style>

.email-list {
    list-style: none;
    padding: 0;
}
.email-list li {
    background: #0073e6;
    color: white;
    margin: 5px 0;
    padding: 5px;
    border-radius: 5px;
    font-weight: bold;
}
.alert {
    color: red;
    font-weight: bold;
    margin-top: 15px;
}
.notice {
    font-size: 14px;
    color: #555;
    margin-top: 15px;
}
</style>
<?php

/* CAPTCHA */
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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

if(!empty($_POST["email"])){

    //TODO : Vérifier que l'email n'est pas un spam sinon l'ajouter dans une liste de vérification.

    if(!array_key_exists("cf-turnstile-response", $_POST) || !isCaptchaValid($_POST["cf-turnstile-response"])){
        //showNotification("Captcha invalide.", "error");
        exit;
        die();
    }

    // Encryption de l'email fourni.
    $encryption_key = $_ENV["NEWSLETTER_KEY"];
    $cipher = $_ENV["NEWSLETTER_CIPHER"];

    $encrypted_email = openssl_encrypt($_POST["email"], $cipher, $encryption_key, 0);

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, "https://gestion.levendelaiscinema.fr/webmaster/newsletter/data?email=".urlencode($encrypted_email));
    curl_setopt($ch,CURLOPT_POST, false);

    curl_setopt($ch,CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

    $result = json_decode(curl_exec($ch), true);

    if($result["score"] > 0)
    {
        $db->prepare("INSERT INTO confirmation_list (type, data) VALUES (?, ?)")->execute(["email", json_encode(array("email" => $encrypted_email, "reason" => $result["reason"], "score" => $result["score"]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        echo <<<EOT
            <script>
            Swal.fire({
                title: "Email ajouté à la liste de validation",
                text: "Votre email à été détecté comme suspect et à été ajouté à une liste de validation.",
                icon: "info",
                footer: "Vous recevrez un email si votre adresse est retenue."
            });
            </script>
        EOT;

        exit;
    }

    // Check si l'email est pas déjà dans la db
    $fetch = $db->prepare("SELECT email FROM newsletter WHERE email = ?");
    $fetch->execute(array($encrypted_email));
    
    if($fetch->rowCount() == 0){

        $verifCode = generateRandomString(10);

        $expires = strtotime("+30 minutes");

        $db->prepare("INSERT INTO email_validation (code, email, expires) VALUES (?, ?, ?)")->execute([$verifCode, $encrypted_email, $expires]);

        $url = "https://levendelaiscinema.fr/newsletter/validate?err&code=".$verifCode;

        try{
            $fields = [
                'passwd'   => $_ENV["LINK_PASSWORD"],
                'url'      => "https://levendelaiscinema.fr/newsletter/validate?code=".$verifCode,
                'expires'  => $expires,
                "keyword"  => "newsletter-activate"
            ];

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, "https://api.levendelaiscinema.fr/v2/links");
            curl_setopt($ch,CURLOPT_POST, true);

            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch,CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

            $result = json_decode(curl_exec($ch), true);

            if($result["status"] == "OK")
            {
                $url = "https://go.levendelaiscinema.fr/".$result["data"]["short"];
            }
            else{
                $url = "https://levendelaiscinema.fr/newsletter/validate?err&code=".$verifCode;
            }
        }
        catch (Exception $e){
            $url = "https://levendelaiscinema.fr/newsletter/validate?err&code=".$verifCode;
        }

        /* ENVOI DU MAIL */

        $message = <<<EOT
        <h3>
        Bonjour,
        <br>
        Merci pour votre inscription à la newsletter mensuelle du Vendelais ! Avant de commencer, il vous reste une dernière étape :<br>
        Cliquez sur le bouton ci-dessous pour valider ton adresse email :</h3><br>

        <a href="$url">👉 Valider mon email</a><br><br>

        Impossible d'ouvrir le lien ? Veuillez coller ce lien dans votre navigateur : $url<br>
        
        <b>Ce lien expire dans 2h</b><br><br>

        Si vous n'êtes pas à l’origine de cette inscription, ignorez simplement cet email.<br><br>

        À bientôt !<br>
        L’équipe du Vendelais
        EOT;

        //TODO : Ajouter le mail à la queue des mails à envoyer

        $mail = new PHPMailer;
        $mail->isSMTP(); 
        $mail->SMTPDebug = 0;
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->CharSet = "UTF-8";
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["NO_REPLY_EMAIL_USER"];
        $mail->Password = $_ENV["NO_REPLY_EMAIL_PASSWORD"];
        $mail->setFrom($_ENV["NO_REPLY_EMAIL_USER"], "Le Vendelais");
        
        $mail->addAddress($_POST["email"], $_POST["email"]);

        $mail->Subject = "Confirmation de l'adresse mail";
        $mail->msgHTML($message);
        $mail->AltBody = 'Confirme ton adresse mail : https://levendelaiscinema.fr/newsletter/validate?code='.$verifCode;

        if($mail->send()){
            echo <<<EOT
            <script>
            Swal.fire({
                title: "Email de validation envoyé",
                text: "Ouvre ta boîte mail et clique sur le lien qu'on vient de t'envoyer pour valider ton inscription (sécurisé)",
                icon: "success",
                footer: "Si tu ne reçois rien, vérifie ton dossier spam !"
            });
            </script>
            EOT;
        }else{
            echo <<<EOT
            <script>
            Swal.fire({
                title: "Impossible d'envoyer le mail de confirmation",
                text: "$mail->ErrorInfo",
                icon: "error"
            });
            </script>
            EOT;
        }

       

    }
    else{
        echo <<<EOT
            <script>
            Swal.fire({
                title: "Email déjà enregistré !",
                icon: "error"
            });
            </script>
            EOT;
    }
}