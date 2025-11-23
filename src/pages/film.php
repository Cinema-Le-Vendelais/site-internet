<?php
/**
 * Page d'un film
 *
 *
 * Auteur      : Liam
 * Version     : 1.1.0
 * Mise à jour : 31/05s/2025
*/

?>

<div class="film" itemscope itemtype="http://schema.org/Movie">
    <div class="container">
        <div class="data">
            <img itemprop="image" id="poster" alt="">
            <div class="infos"></div>
        </div>
            <div>
                <h3>Séances</h3>
                
                <a href="<?= BASE ?>/infos-pratiques#tarifs" style="text-decoration: none">
                    <div class="dates" id="dates"></div>
                </a>

                <div>
            </div>
        </div>
    </div>
</div>

<!-- ANNONCEURS -->
<div class="ads">
    <p>Annonceurs</p>
    <ad></ad>
</div>