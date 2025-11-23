<?php

/**
 * Page Mentions Légales
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 14/04/2025
 */
?>

<style>
    .container{
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    h2{
        margin: 0;
        color: var(--accent-color)
    }

    .sep{
        width: 100%;
        background: lightgray;
        height: 1px;
    }

    a{
        color: var(--accent-color);
        text-decoration: none;
    }

    h1{
        position: relative;
        font-size: 40px;
        margin-left: 20px;
    }

    h1::before{
        position: absolute;
        top:0;
        bottom:0;
        left:-20px;
        width: 5px;
        border-radius:8px;
        background: var(--accent-color);
        content: "";
    }

    a:hover{
        text-decoration: underline;
    }
</style>

<h1>Mentions légales</h1>

<div class="container">
    <h2>Éditeur du site</h2>
    <div>
        <p><b>Raison sociale : </b>Association du Cinéma Le Vendelais</p>
        <p><b>Adresse : </b>1 place de l'église, Châtillon-en-Vendelais 35210</p>
        <p><b>Téléphone : </b>02 99 76 16 63</p>
        <p><b>Email : </b>association@levendelaiscinema.fr</p>
        <p><b>Directeur de la publication : </b>Claude CHARPENTIER, Président</p>
    </div>

    <div class="sep"></div>

    <h2>Hébergeur</h2>
    <div>
        <p><b>Raison sociale : </b>OVH SAS</p>
        <p><b>Siège social : </b>2 rue Kellermann - 59100 Roubaix</p>
        <p><b>N° TVA : </b>FR 22 424 761 419</p>
    </div>

    <div class="sep"></div>

    <h2>Crédits</h2>
    <div>
        <p><b>Développement, design & maintenance : </b><a href="https://liamcharpentier.fr" target="_blank" rel="noopener">Liam CHARPENTIER</a></p>
        <p><b>Récupération des bandes annonces : </b> <a href="https://www.themoviedb.org/" target="_blank" rel="noopener">THE MOVIE DATABASE (TMDB)</a></p>
        <p><b>Icônes : </b><a href="https://fontawesome.com/" target="_blank" rel="noopener">FontAwesome</a></p>
    </div>

    <div class="sep"></div>

    <h2>Propriété intellectuelle</h2>
    <div>
        <p>L'ensemble des éléments constituant ce site (textes, graphismes, logiciels, photographies, images, vidéos, sons, plans, logos, marques, etc.) est la propriété exclusive du Cinéma Le Vendelais ou fait l'objet d'une autorisation d'utilisation.</p>
        <p>Ces éléments sont soumis aux lois régissant la propriété intellectuelle. Toute reproduction ou représentation, intégrale ou partielle, faite sans le consentement du Cinéma Le Vendelais est illicite.</p>
    </div>

    <div class="sep"></div>

    <h2>Cookies</h2>
    <div>
        <p>Notre site utilise des cookies pour améliorer votre expérience de navigation. Vous pouvez à tout moment désactiver les cookies en paramétrant votre navigateur.</p>
        <p>Pour en savoir plus sur l'utilisation des cookies, veuillez consulter notre <a href="/about-cookies">Politique de Cookies</a>.</p>
    </div>

    <div class="sep"></div>

    <h2>Protection des données personnelles</h2>
    <div>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation, de portabilité et d'opposition aux données personnelles vous concernant.</p>
        <p>Pour exercer ces droits ou pour toute question sur le traitement de vos données, vous pouvez contacter notre Délégué à la Protection des Données à l'adresse suivante : webmaster@levendelaiscinema.fr</p>
        <p>Pour plus d'informations sur la façon dont nous traitons vos données, veuillez consulter notre <a href="/about-data">Politique de Confidentialité</a>.</p>
    </div>
</div>