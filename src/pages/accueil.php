<?php
/**
 * Page d'accueil
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 01/03/2025
*/
?>


<!-- TEMPORAIRE UPDATE -->
<!--<div style="padding: 15px;border-radius: var(--corner-radius);background: blue;color: white;">
    <i class="fa-solid fa-triangle-exclamation"></i>
    La maintenance est désormais terminée. Il est possible que certains bugs subsistent. Nous vous remercions de bien vouloir nous les signaler en utilisant nos formulaires de contact ou en envoyant un e-mail à webmaster@levendelaiscinema.fr
</div>-->



<?php
if (SETTINGS["website_vacation"] == "1" ||array_key_exists("vac", $_GET)) {
?>

    <div class="main-header">
        <section class="splide" id="mise-en-avant">
            <div class="splide__track">
                <ul class="splide__list"></ul>
            </div>
        </section>

        <div class="surveys"></div>
    </div>

    <div class="main-content" style="height:100%;">
        <div class="vacations" style="margin:50px;">
            <h1>C'est les vacances au Vendelais ☀️</h1>
            <h2>Toute l'équipe du Vendelais vous souhaite un bel été. A bientôt !</h2>
        </div>
    </div>

<?php
} else {
  
?>

<div class="main-header">
    <section class="splide" id="mise-en-avant">
        <div class="splide__track">
            <ul class="splide__list"></ul>
        </div>
    </section>

    <div class="surveys"></div>
</div>

<div class="main-content">
    <!-- SEARCH BAR -->
    <!--<div class="search-bar">
        <input type="text" class="search" placeholder="Rechercher un film...">
        <button>
            <i class="fa-solid fa-magnifying-glass fa-2xl"></i>
        </button>
    </div>-->

    <!-- SEARCH RESULTS -->
    <!--<div class="search-results"></div>-->

    <?php
    if(array_key_exists("DEV", $_GET)){

    ?>

    <!-- CATEGORIES -->

    <div class="categories"></div>

    <script>
        postApi("categories", "v3").then(res => {
            if(res.status == "OK"){
                
            }
        })
    </script>

    <?php } ?>

    <div class="swiper dates-placeholder">
        <div class="swiper-wrapper"></div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    


    <!-- FILMS -->

    <h2 id="dayname">A l'affiche aujourd'hui</h2>
    <div class="films-container">
        <div class="movies"></div>
    </div>


</div>

<?php
}
?>


<div class="main-footer">
    <!-- SLIDER -->
    <section class="splide temp-slider">
        <div class="splide__track">
            <ul class="splide__list">
                <li class="splide__slide">
                    <a href="https://levendelaiscinema.fr/affiches">
                        <img class="lazyload" data-src="https://cloud.levendelaiscinema.fr/uploads/slider/commander-affiches-version-2.gif" alt="Commander des affiches">
                    </a>
                </li>
                <li class="splide__slide">
                    <a href="https://levendelaiscinema.fr/contacter#devenir">
                        <img class="lazyload" data-src="https://cloud.levendelaiscinema.fr/uploads/slider/devenez-benevole-version-2.gif" alt="Devenir bénévole">
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <!-- ANNONCEURS -->

    <div class="ads">
        <p>Annonceurs</p>
        <ad></ad>
    </div>

    <div class="ads">
        <p>Nos partenaires</p>
        <div class="partners"></div>
    </div>

    <div class="ads">
        <p>Avec le soutien</p>
        <div class="soutiens"></div>
    </div>

    <style>
        .main-content{
            background: url("<?= asset("cdn://images/header.png") ?>");
        }
    </style>

</div>