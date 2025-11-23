<?php

/**
 * Page Cookies
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 14/04/2025
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

<h1>Politique de cookies</h1>


<div class="container">
    <h2>Qu'est-ce qu'un cookie ?</h2>
    <div>
        <p>Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, tablette, smartphone) lors de votre visite sur notre site. Les cookies nous permettent de reconnaître votre navigateur et de collecter certaines informations sur votre navigation.</p>
        <p>Les cookies sont utilisés pour assurer le bon fonctionnement du site, améliorer votre expérience de navigation, vous proposer des services et contenus personnalisés, et nous aider à comprendre comment notre site est utilisé.</p>
    </div>

    <div class="sep"></div>

    <h2>Types de cookies utilisés</h2>
    <div>
        <h3>Cookies strictement nécessaires</h3>
        <p>Ces cookies sont indispensables au fonctionnement de notre site. Ils vous permettent d'utiliser les principales fonctionnalités du site (par exemple, l'accès à votre compte bénévole). Sans ces cookies, vous ne pourrez pas utiliser notre site normalement.</p>

        <h3>Cookies de sécurité</h3>
        <p>Ces cookies sont indispensables à la sécurité du site. Il permettent d'éviter le spam et les attaques envers nos services.</p>

        <h3>Cookies analytiques</h3>
        <p>Ces cookies nous permettent de recueillir des informations sur la façon dont vous utilisez notre site (par exemple, les pages que vous consultez le plus souvent). Ils nous aident à améliorer notre site en nous montrant comment les visiteurs naviguent.</p>
    </div>

    <div class="sep"></div>

    <h2>Liste détaillée des cookies</h2>
    <div>
        <table>
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Fournisseur</th>
                    <th>Description</th>
                    <th>Durée</th>
                </tr>
                <tr>
                    <td>_ga</td>
                    <td>Google Analytics</td>
                    <td>Distingue les utilisateurs et génère des statistiques</td>
                    <td>2 ans</td>
                </tr>
                <tr>
                    <td>cookie-consent</td>
                    <td>Notre site</td>
                    <td>Enregistre vos préférences concernant les cookies</td>
                    <td>1 an</td>
                </tr>
                <tr>
                    <td>oauth_access_token</td>
                    <td>Notre site</td>
                    <td>Permet de vous garder connecté sur tous nos espaces bénévoles</td>
                    <td>1h</td>
                </tr>
                <tr>
                    <td>oauth_refresh_token</td>
                    <td>Notre site</td>
                    <td>Permet de vous garder connecté sur tous nos espaces bénévoles</td>
                    <td></td>
                </tr>
                <tr>
                    <td>oauth_token_expiry</td>
                    <td>Notre site</td>
                    <td>Permet de vous garder connecté sur tous nos espaces bénévoles</td>
                    <td></td>
                </tr>
                <tr>
                    <td>PHPSESSID</td>
                    <td>Notre site</td>
                    <td>Permet de vous garder connecté sur tous nos espaces bénévoles</td>
                    <td>Session</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="sep"></div>

    <h2>Consentement</h2>
    <div>
        <p>Lors de votre première visite sur notre site, un bandeau vous informe de la présence de cookies et vous invite à indiquer votre choix. Ces cookies ne sont déposés que si vous les acceptez ou que vous poursuivez votre navigation sur le site en visitant une seconde page.</p>
        <p><!--Vous pouvez à tout moment revenir sur votre choix en cliquant sur "Gérer mes cookies" en bas de page.-->Vous ne pouvez pas revenir sur votre choix pour le moment mais nous y travaillons. En attendant, si vous souhaitez changer votre choix, supprimez le cookie <code>cookie-consent</code>.</p>
    </div>

    <div class="sep"></div>

    <h2>Mise à jour de la politique de cookies</h2>
    <div>
        <p>Nous nous réservons le droit de modifier cette politique de cookies à tout moment. La date de dernière mise à jour indiquée ci-dessous sera modifiée en conséquence. Nous vous encourageons à consulter régulièrement cette page pour rester informé des changements.</p>
        <p>Dernière mise à jour : 20 avril 2025</p>
    </div>
</div>