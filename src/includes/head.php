<?php
ini_set("display_errors", 1);

function createHead($title, $styles, $scripts){
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SETTINGS["website_name"] ?> - <?= $title ?></title>

    <!-- SEO -->

    <meta name="title" content="<?= SETTINGS["website_name"] ?>">
    <meta name="description" content="Le Vendelais : Cinéma associatif de proximité et de convivialité. Dates, horaires et sorties des films.">
    <meta name="keywords" content="le vendelais, cinéma le vendelais, le vendelais cinéma, cinéma Châtillon, cinéma chatillon, cinéma bretagne, cinéma vitré, cinema pays de vitre, châtillon, chatillon en vendelais, châtillon en vendelais
programme cinéma châtillon, programme cinema vitré, programme cinéma, cinéma art et essai vitré, cinéma art et essai chatillon, art et essai, chatillon art, chatillon en vendelais art et essai">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="author" content="Liam Charpentier">
    <link rel="shortcut icon" href="https://cdn.levendelaiscinema.fr/proxy/<?= SETTINGS["website_icon"] ?>" type="image/x-icon">

    <meta property="og:url" content="https://levendelaiscinema.fr" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= SETTINGS["website_name"] ?>" />
    <meta property="og:description" content="Le Vendelais : Cinéma associatif de proximité et de convivialité. Dates, horaires et sorties des films." />

    <!-- STYLESHEETS -->

    <link rel="preload" href='<?= loadCdnUrl("v2/css/style.css") ?>?v=0.0.16' as="style" onload="this.rel='stylesheet'"'>

    <!-- GLOBAL REQUIREMENTS -->

    <link rel="preload" href="<?= loadCdnUrl("v2/3rdparty/fontawesome/css/all.min.css") ?>" as="style" onload="this.rel='stylesheet'"'/>
    <link rel="preload" href="<?= loadCdnUrl("v2/3rdparty/notyf/notyf.css") ?>" as="style" onload="this.rel='stylesheet'"'">

    <script src="<?= loadCdnUrl("v2/3rdparty/notyf/notyf.js") ?>"></script>
    <script src="<?= loadCdnUrl("v2/js/api.js?v=0.0.1") ?>"></script>
    <script src="<?= loadCdnUrl("v1/js/functions.js?v=0.0.1") ?>"></script>

    <script src='<?= loadCdnUrl("v1/js/ads.js?v=0.0.1") ?>'></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- PAGE STYLES -->
    <?php
    foreach($styles as $style){
        echo "<link rel='stylesheet' href='".$style."'>\n";
    }
    ?>

    <!-- PAGE SCRIPTS -->
    <?php
    foreach($scripts as $script){
        echo "<script src='".$script."'></script>\n";
    }
    ?>

    <?php
    if(substr($_SERVER["REQUEST_URI"], 0, 6) == "/film/" || substr($_SERVER["REQUEST_URI"], 0, 6) == "/event/")
    {
    ?>
    <meta name="robots" content="noindex, nofollow">
    <?php
    }
    else{
    ?>                                  
    <meta name="robots" content="index, follow">
    <?php } ?>

    <script>
        function setCssVariable(variableName, value) {
            document.documentElement.style.setProperty("--" + variableName, value);
        }

        // Objet contenant les variables CSS et leurs valeurs
        let variables = {
            "text-color": "<?= SETTINGS['style_text_color'] ?>",
            "font-family": "<?= SETTINGS['style_font_family'] ?>",
            "accent-color": "<?= SETTINGS['style_accent_color'] ?>",
            "background-color": "<?= SETTINGS['style_background_color'] ?>",
            "default-color": "<?= SETTINGS['style_default_color'] ?>",
            "corner-radius": "<?= SETTINGS['style_corner_radius'] ?>px",

            "section-color": "<?= SETTINGS['style_section_background'] ?>",
            "section-radius": "<?= SETTINGS['style_section_radius'] ?>px",
            "section-text-color": "<?= SETTINGS['style_section_text'] ?>",
            "section-text-hover": "<?= SETTINGS['style_section_text_hover'] ?>",
        };

        for (let key in variables) {
            if (variables.hasOwnProperty(key)) {
                setCssVariable(key, variables[key]);
            }
        }

    </script>
</head>
<?php

}