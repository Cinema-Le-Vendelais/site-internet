<?php

/**
 * Page Confidentialité
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 20/04/2025
 */
?>

<style>
    .container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    h2 {
        margin: 0;
        color: var(--accent-color)
    }

    .sep {
        width: 100%;
        background: lightgray;
        height: 1px;
    }

    a {
        color: var(--accent-color);
        text-decoration: none;
    }

    h1 {
        position: relative;
        font-size: 40px;
        margin-left: 20px;
    }

    h1::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: -20px;
        width: 5px;
        border-radius: 8px;
        background: var(--accent-color);
        content: "";
    }

    a:hover {
        text-decoration: underline;
    }

    table {
        margin: auto;
        width: 100%;
        border-collapse: collapse;
    }

    table tr:first-child {
        border-top: none;
        background: var(--accent-color);
        color: #fff;
    }

    table tr {
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        background-color: #f5f9fc;
    }

    table tr:nth-child(odd):not(:first-child) {
        background-color: #ebf3f9;
    }

    table th {
        display: none;
    }

    table td {
        display: block;
    }

    table td:first-child {
        margin-top: .5em;
    }

    table td:last-child {
        margin-bottom: .5em;
    }

    table td:before {
        content: attr(data-th) ": ";
        font-weight: bold;
        width: 120px;
        display: inline-block;
        color: #000;
    }

    table th,
    table td {
        text-align: left;
    }

    table {
        color: #333;
        border-radius: .4em;
        overflow: hidden;
    }

    table tr {
        border-color: #bfbfbf;
    }

    table th,
    table td {
        padding: .5em 1em;
    }

    @media screen and (max-width: 601px) {
        table tr:nth-child(2) {
            border-top: none;
        }
    }

    @media screen and (min-width: 600px) {
        table tr:hover:not(:first-child) {
            background-color: #d8e7f3;
        }

        table td:before {
            display: none;
        }

        table th,
        table td {
            display: table-cell;
            padding: .25em .5em;
        }

        table th:first-child,
        table td:first-child {
            padding-left: 0;
        }

        table th:last-child,
        table td:last-child {
            padding-right: 0;
        }

        table th,
        table td {
            padding: 1em !important;
        }
    }
</style>

<h1>Politique de confidentialité</h1>


<div class="container">
    <h2>Introduction</h2>
    <div>
       <p>Nous accordons une grande importance à la protection de vos données personnelles. Cette politique de protection des données explique comment nous collectons, utilisons et protégeons les informations que vous nous fournissez via les formulaires présents sur notre site.</p>
       <p>Cette politique s'inscrit dans le cadre du Règlement Général sur la Protection des Données (RGPD) et de la loi Informatique et Libertés.</p>
    </div>

    <div class="sep"></div>

    <h2>Responsable du traitement</h2>
    <div>
        <p>Pour toute question relative à la protection de vos données, vous pouvez contacter notre Délégué à la Protection des Données (DPO) à l'adresse suivante : webmaster@levendelaiscinema.fr</p>
    </div>

    <div class="sep"></div>

    <h2>Données collectées via nos formulaires</h2>
    <div>
        <p>Selon les formulaires que vous remplissez sur notre site, nous pouvons collecter différents types de données personnelles :</p>

        <h3>Formulaire de contact</h3>
        <ul>
            <li>Nom et prénom</li>
            <li>Adresse email</li>
            <li>Numéro de téléphone</li>
            <li>Contenu de votre message</li>
        </ul>

        <h3>Formulaire d'inscription à la newsletter</h3>
        <ul>
            <li>Adresse email</li>
        </ul>

        <h3>Formulaire de commande d'affiches</h3>
        <ul>
            <li>Nom et prénom</li>
            <li>Adresse email</li>
            <li>Numéro de téléphone</li>
            <li>Liste des affiches sélectionnées</li>
            <li>Commentaire (facultatif)</li>
        </ul>

        <i>Note : Nous ne collectons que les données strictement nécessaires à la finalité du traitement. Les champs obligatoires sont clairement indiqués dans nos formulaires.</i>
    </div>

    <div class="sep"></div>

    <h2>Finalités du traitement</h2>
    <div>
        <p>Les données que vous nous fournissez via nos formulaires sont utilisées pour :</p>

        <ul>
            <li>Répondre à vos demandes d'information ou de contact</li>
            <li>Traiter et suivre vos commandes</li>
            <li>Vous envoyer notre newsletter et des communications marketing (avec votre consentement)</li>
            <li>Améliorer nos produits, services et l'expérience utilisateur de notre site</li>
            <li>Respecter nos obligations légales et réglementaires</li>
            <li>Prévenir la fraude et assurer la sécurité de notre site</li>
        </ul>
    </div>

    <div class="sep"></div>

    <p>Cette section est encore en cours de développement...</p>
</div>