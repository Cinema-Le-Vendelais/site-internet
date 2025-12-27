<?php

/**
 * Page d'affiches
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 01/03/2025
 */

/* use PHPMailer\PHPMailer\PHPMailer;


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
    $mail->AltBody = 'Impossible de charger le message...';

    if ($mail->send()) {
        echo <<<EOT
        <script>Swal.fire({
            title: "Commande validée !",
            text: "Votre commande à été envoyée. Souhaitez vous vider votre liste de films ?",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Non merci",
            confirmButtonText: "Oui, je veux vider la liste!"
          }).then((result) => {
            if (result.isConfirmed) {
              localStorage.removeItem("commande-affiches");
              Swal.fire({
                title: "Liste vidée !",
                text: "La liste des films à bien été vidée.",
                icon: "success"
              }).then(() => {window.location.reload()})
            }
          });</script>
        EOT;
        $_SESSION["last-form-send"] = date("Y-m-d H:i:s");
    } else {
        echo 'alert("Une erreur s\'est produite :/");console.log("' . $mail->ErrorInfo . '")</script>';
    }
}

function sendMailNoRep($subject, $data, $to)
{
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

    $mail->addAddress($to, $to);

    $mail->Subject = $subject;
    $mail->msgHTML($data);
    $mail->AltBody = 'Impossible de charger le message...';

    $mail->send();
}

if (!empty($_POST) && isHCaptchaValid($_POST["h-captcha-response"]) && !empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["data"])) {

    
    $subject = "Nouvelle commande d'affiches";
    $to = "affiches@levendelaiscinema.fr";

    $tarif_normal_pf = 5;
    $tarif_normal_gf = 10;
    $tarif_benevole_pf = 3;
    $tarif_benevole_gf = 6;

    $data = json_decode($_POST["data"], true);

    function makeHTML($film)
    {
        $gf = array_key_exists("GF", $film) ? "Grand format : <b>{$film["GF"]}</b>" : "";
        $pf = array_key_exists("PF", $film) ? "Petit format : <b>{$film["PF"]}</b>" : "";
        $html = <<<EOT
        <div>
            <h4>{$film["nom"]} ({$film["date"]})</h4>
            <p>{$gf}</p>
            <p>{$pf}</p>
        </div>
        EOT;
        return $html;
    }

    $commande = count($data) == 0 ? $_POST["commentaire"] : implode("", array_map("makeHTML", $data));
    $comment = count($data) == 0 ? "" : "<p>Commentaire : <b>{$_POST["commentaire"]}</b></p>";

    $gf_count = array_sum(array_map(fn($value) => array_key_exists("GF", $value) ? $value["GF"] : 0, $data));
    $pf_count = array_sum(array_map(fn($value) => array_key_exists("PF", $value) ? $value["PF"] : 0, $data));

    $normalPrice = $pf_count*$tarif_normal_pf+$gf_count*$tarif_normal_gf;
    $benevolePrice = $pf_count*$tarif_benevole_pf+$gf_count*$tarif_benevole_gf;

    $html = <<<EOT
        <h2>Une nouvelle commande d'affiches est disponible.</h2>
        <p>Nom & Prénom : {$_POST["name"]}</p>
        <p>Email : {$_POST["email"]}</p>
        <p>Téléphone : {$_POST["phone"]}</p>
        $comment

        <hr>

        <h3>Details de la commande</h3>

        $commande

        <hr>

        <h3>Récapitulatif</h3>

        <p>Nombre de petites affiches : <b>$pf_count</b></p>
        <p>Nombre de grandes affiches : <b>$gf_count</b></p>

        <p>Tarif normal : <b>{$normalPrice}€</b></p>
        <p>Tarif bénévole : <b>{$benevolePrice}€</b></p>

        <i>Ceci est un message automatique envoyé depuis la page "Commander des affiches" du site web levendelaiscinema.fr. Merci de vérifier l'exactitude des tarifs avant toute réponse.</i>
    EOT;

    sendMail($subject, $html, $to);

    // Envoyer le mail à la personne

    $html = <<<EOT
        <h2>Commande d'affiches validée !</h2>

        <p>Votre commande d'affiches à bien été prise en compte. Une réponse va vous être envoyée dès que possible.</p>

        <h3>Details de la commande</h3>

        $comment

        $commande

        <hr>

        <h3>Récapitulatif</h3>

        <p>Nombre de petites affiches : <b>$pf_count</b></p>
        <p>Nombre de grandes affiches : <b>$gf_count</b></p>

        <i>Ceci est un message automatique envoyé depuis la page "Commander des affiches" du site web levendelaiscinema.fr. Merci de ne pas y répondre.</i>
    EOT;

    sendMailNoRep("Commande d'affiches validée", $html, $_POST["email"]);
}
*/
?>



<!--<script src='https://js.hcaptcha.com/1/api.js' async defer></script>-->
<h2>Vente d'affiches</h2>

<div class="beta">
    Les réservations sont actuellement indisponibles. Pour commander des affiches, contactez affiches@levendelaiscinema.fr !
</div>

<!--<details>
  <summary>Cliquez ici pour visualiser votre commande</summary>
  <div class="command-holder">
    <h3>Votre commande :</h3>
    <div class="command">
    </div>

    <form id="form-commande" action="" method="POST">
        <h5>Coordonnées</h5>

        <input type="text" name="name" placeholder="Nom et Prénom" required>
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="phone" name="phone" placeholder="Numéro de téléphone" required>

        <textarea name="commentaire" placeholder="Commentaire (par exemple si une affiche n'est pas dans la liste)"></textarea>

        <input id="data" name="data" type="hidden" required>

        <div class="h-captcha" data-sitekey="<?= $_ENV["HCAPTCHA_SITE_KEY"] ?>"></div>

        <button type="button" id="commander-affiches">Commander ces affiches</button>
    </form>
</div>
</details>-->




<input type="text" id="searchInput" placeholder="Rechercher">

<table>
    <thead>
        <th>
            Petite Affiche
        </th>
        <th>
            Grande Affiche
        </th>
    </thead>
    <tbody>
        <tr>
            <td>
                (60x40) 5€
            </td>
            <td>
                (160x120) 10€
            </td>
        </tr>
    </tbody>
</table>

<div class="ctn">
    <span class="legend">Affiche en rouleau</span>
    <button id="show-command">Afficher votre panier<span>0</span></button>
</div>


<section class="container"></section>

<style>
    .container{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 50px;
    }

    .container article{
        display: flex;
        flex-direction: column;
        gap: 10px;
        justify-content: center;
        align-items: center;
    }

    .container article h2{
        font-size: 1em;
    }

    .container article img{
        width: 250px;
        height: 350px;
    }

    #show-command{
        position: relative;
        padding: 10px;
        border-radius: 8px;
        background: darkblue;
        border: none;
        font-family: var(--font-family);
        font-weight: var(--font-weight);
        cursor: pointer;
        transition: all .5s;
        color: white;
    }

    #show-command:hover{
        background: blue;
    }

    #show-command span{
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        top: -10px;
        right: -10px;
        width: 20px;
        height: 20px;
        color: white;
        background: red;
        padding: .1rem;
        border-radius: 50%;
    }
</style>