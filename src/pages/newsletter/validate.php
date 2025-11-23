<?php

ini_set("display_errors", 1);

/**
 * Page d'inscription à la newsletter
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 02/03/2025
*/
?>

<style>

    main{
        display: flex;
        align-items: center;
        justify-content: center;
    }

        .confirmation-card {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 90%;
            width: 600px;
        }

        .success-icon {
            color: #4CAF50;
        }

        .error-icon {
            color: red;
        }

        .confirmation-card h1 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .confirmation-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .button {
            display: inline-block;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .button.success{
            background-color: #4CAF50;
        }

        .button.success:hover {
            background-color: #45a049;
        }

        .button.error{
            background-color: red;
        }

        .button.error:hover {
            background-color:rgb(230, 49, 49);
        }

        @media (max-width: 480px) {
            .confirmation-card {
                padding: 2rem;
            }

            h1 {
                font-size: 1.25rem;
            }
        }
    </style>


<?php


if(!empty($_GET["code"])){

    // Check si l'email est pas déjà dans la db
    $fetch = $db->prepare("SELECT * FROM email_validation WHERE code = ?");
    $fetch->execute(array($_GET["code"]));

    $data = $fetch->fetch();
    
    // Si le code n'existe pas ou à expiré
    if($fetch->rowCount() == 0 || $data["expires"] <= time()){

        echo <<<EOT
        <div class="confirmation-card">
            <i class="fa-solid fa-circle-xmark error-icon fa-2xl"></i>
            <h1>Code invalide ou expiré</h1>
            <p>Le lien à sûrement expiré. Tentez de vous réinscrire. Si cette erreur persiste, envoyez un email à webmaster@levendelaiscinema.fr</p>
            <a href="/" class="button error">Retour à l'accueil</a>
        </div>
        EOT;

    }
    else{
        $db->prepare("INSERT INTO newsletter (email) VALUES (?)")->execute([$data["email"]]);
        $db->prepare("DELETE FROM email_validation WHERE code = ?")->execute([$data["code"]]);

        echo <<<EOT
        <div class="confirmation-card">
            <i class="fa-solid fa-circle-check success-icon fa-2xl"></i>
            <h1>Adresse email enregistrée</h1>
            <p>Vous recevrez maintenant notre newsletter tous les mois.</p>
            <a href="/" class="button success">Retour à l'accueil</a>
        </div>
        EOT;
    }
}