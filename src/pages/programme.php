<?php
/**
 * Page du programme
 *
 *
 * Auteur      : Liam
 * Version     : 1.0.0
 * Mise à jour : 01/03/2025
*/

// Mapper les catégories
function cat($n)
{
    return "<span class='categorie'>$n</span>";
}

?>

<style>
    h1{
        display: flex;
        gap: 10px;
    }
    h1 select:where(option, select){
        font-size: 1em;
        font-weight: bold; 
    }
</style>

<?php
if(SETTINGS["website_vacation"] == "1")
        {
            ?>
           <div class="vacations">
                <h1>C'est les vacances au Vendelais ☀️</h1>
                <h2>Toute l'équipe du Vendelais vous souhaite un bel été. A bientôt !</h2>
            </div>
            <?php
        }
        else
        {?>

<h1>Programme <select name="programme-type"><option value="month" selected>du mois</option><option value="week">de la semaine</option></select></h1>

        <!-- SURVEY -->
        <!--<div class="survey">
            <h2>Hey, vous auriez 30 secondes pour répondre à un sondage ?</h2>
            <p>Ce sondage prends seulement 30s et nous permet d'<b>améliorer le site ainsi que le cinéma</b>. Merci !</p>

            <p><a href="/sondage">Cliquez ici pour répondre</a></p>
        </div>
        
        <style>.survey{display:none;padding:15px;border-radius:var(--corner-radius);background:red;color:#fff}.survey a{padding:10px;border-radius:8px;background:#fff;color:red;text-decoration:none}</style><script>localStorage.getItem("survey-reply")||(document.querySelector(".survey").style.display="block")</script>
        -->
        
<p><b>Astuce :</b> Cliquez sur les affiches pour voir toutes les informations sur le film (<b>date(s)</b>, bande annonce, ...)</p>

<div class="film-list"></div>

<?php
}