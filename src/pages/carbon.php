<?php
/**
 * Page d'informations sur le rejet de CO2
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 02/03/2025
*/
?>

<style>
    :root {
      --page-primary-color: #2e8b57; /* Vert pour l'environnement */
      --secondary-color: #f5f5f5; /* Couleur de fond clair */
    }

    section {
      margin-bottom: 2rem;
    }

    section h2 {
      font-size: 1.5rem;
      color: var(--page-primary-color);
      margin-bottom: 1rem;
    }

    .carbon-stats {
      background-color: var(--page-primary-color);
      color: white;
      padding: 1rem;
      border-radius: 8px;
      text-align: center;
      margin: 1.5rem 0;
    }

    .carbon-stats p {
      margin: 0;
      font-size: 1.2rem;
    }

    .carbon-stats strong {
      font-size: 1.4rem;
    }

    .carbon-equivalents {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }

    .carbon-equivalents .item {
      background-color: var(--page-primary-color);
      color: white;
      padding: 1rem;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .carbon-equivalents i {
      width: 60px;
      height: auto;
      margin-bottom: 0.5rem;
    }

    @media (max-width: 600px) {

        .carbon-equivalents .item {
        padding: 0.5rem;
      }


      .carbon-equivalents {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>

<h1>Empreinte carbone</h1>

<section>
    <h2>Qu'est-ce que l'empreinte carbone d'un site web ?</h2>
    <p>
      L'empreinte carbone d'un site web est la quantité de dioxyde de carbone (CO₂) émise dans l'atmosphère pour chaque action liée à son fonctionnement : 
      l'hébergement des serveurs, les connexions réseau, et l'énergie consommée par les appareils des utilisateurs.
    </p>
    <p>
      Même les activités numériques génèrent une empreinte écologique. Réduire l'empreinte carbone d'un site contribue à la lutte contre le réchauffement climatique.
    </p>
  </section>

  <section>
    <h2>Notre engagement</h2>
    <p>
      Nous sommes conscients de l'impact environnemental de notre présence en ligne. C'est pourquoi nous mettons en place des solutions pour minimiser notre empreinte carbone.
      Notre site est hébergé par <strong>OVH</strong>, un hébergeur qui favorise l'utilisation de centres de données plus respectueux de l'environnement.
    </p>
  </section>

  <section class="carbon-stats">
    <p>Empreinte carbone par visite : <strong>~0.10g de CO₂</strong></p>
    <p>Nombre de visites mensuelles : <strong>~1000 visites</strong></p>
    <p>Hébergeur : <strong>OVH</strong></p>
  </section>

  <section>
    <h2>Ce que cela signifie</h2>
    <p>
      Actuellement, chaque visite de notre site émet environ <strong>0,10 gramme de CO₂</strong> dans l'atmosphère. Avec environ <strong>1000 visites par mois</strong>,
      notre site émet environ <strong>100 grammes de CO₂ par mois</strong>
    </p>

    <p>
      Pour mieux comprendre l'empreinte carbone de notre site, voici ce que 0.1 grammes de CO₂ équivalent :
    </p>

    <div class="carbon-equivalents">
      <div class="item">
      <i class="fa-solid fa-car"></i>
        <p><strong>0,0015 km</strong> en voiture</p>
      </div>
      <div class="item">
      <i class="fa-solid fa-phone"></i>
        <p><strong>1/2 charge</strong> de smartphone</p>
      </div>
      <div class="item">
      <i class="fa-solid fa-lightbulb"></i>
        <p><strong>1 heure</strong> d'utilisation d'ampoule LED</p>
      </div>
    </div>

    <p>
      Et ce que 100 grammes de CO₂ équivalent :
    </p>
    <div class="carbon-equivalents">
      <div class="item">
      <i class="fa-solid fa-car"></i>
        <p><strong>0.83 km</strong> en voiture</p>
      </div>
      <div class="item">
        <i class="fa-solid fa-phone"></i>
        <p><strong>90 charges</strong> de smartphone</p>
      </div>
      <div class="item">
      <i class="fa-solid fa-lightbulb"></i>
        <p><strong>450 heures</strong> d'utilisation d'ampoule LED</p>
      </div>
    </div>

    <p>
      Grâce à l'optimisation du site et l'utilisation de services d'hébergement écologiques comme ceux proposés par OVH, nous cherchons à réduire encore plus cette empreinte.
    </p>
  </section>

  <img src="https://green-web-badge.vnphanquang.workers.dev/levendelaiscinema.fr?nocache=true" height="200px">

  <p>
    <a target="_blank" href="https://www.websitecarbon.com/website/levendelaiscinema-fr/">En savoir plus sur notre émission de CO²</a>    
    <i class="fa-solid fa-external-link"></i>
  </p>


  <h1>Résultats fournis par www.websitecarbon.com</h1>