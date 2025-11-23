<?php
/**
 * Page de désinscription à la newsletter
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 02/03/2025
*/

if(!empty($_GET["email"])){
    // Si l'email n'est pas encrypté on l'encrypte
    if(array_key_exists("not_encrypted", $_GET))
    {
        $encryption_key = $_ENV["NEWSLETTER_KEY"];
        $cipher = $_ENV["NEWSLETTER_CIPHER"];
    
        $encrypted_email = openssl_encrypt($_GET["email"], $cipher, $encryption_key, 0);
    }
    else
    {
        $encrypted_email = $_GET["email"];
    }

    // Suppression de l'email dans la db
    $fetch = $db->prepare("DELETE FROM `newsletter` WHERE email = ?");
    $fetch->execute(array($encrypted_email));

    showNotification("Email supprimé, redirection dans 3s.");
    redirect(BASE, 3000);
}
else{
    redirect(BASE);
    echo "Token d'email invalide !";
    exit;
}

?>

<h1>Désinscription à la newsletter effectuée, redirection en cours...</h1>