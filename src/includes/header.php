<body>

    <?php
    if(isset($_COOKIE["cookie-consent"]) && $_COOKIE["cookie-consent"] == "true")
    {
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P5DQ1FKHBS"></script>
    <script>

        Sentry.onLoad(() => {
            console.log("Sentry loaded");
            Sentry.init({
                dsn: "https://1ef3990d2d1cb99b997e9b60232d37d3@o4510564015013888.ingest.de.sentry.io/4510564064165968",
                sendDefaultPii: true
            });
        })


        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-P5DQ1FKHBS');
    </script>
    <?php } ?>

    <script>
        let notyf = new Notyf();
    </script>

    <header>
        <div>
            <img data-src="<?= asset("cdn://images/logo.png", 1) ?>" class="logo lazyload" style="width: 300px;" alt="Logo">
            <span class="address">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <p><?= explode(" - ", SETTINGS["contact_address"])[0] ?></p>
                    <span><?= explode(" - ", SETTINGS["contact_address"])[1] ?></span>
                </div>
            </span>
             <div class="mobile-only" style="margin-top:20px;padding:20px;">
                <a href="https://levendelaiscinema.fr/portail"><i class="fa-solid fa-lock"></i> Espace bénévoles</a>
            </div>
        </div>

        <div class="menu">
            <a href="https://levendelaiscinema.fr/portail"><i class="fa-solid fa-lock"></i> Espace bénévoles</a>
            <?php
            foreach(MENU as $item){

                $class = 'class="'.join(" ", array(
                    (array_key_exists("accent", $item) && $item["accent"] ? "contact" : null),
                    (array_key_exists("isNew", $item) && $item["isNew"] ? "is-new" : null)
                )).'"';



                echo "<a ".$class." href='".$item["url"]."'>".$item["title"]."</a>";
            }
            ?>
        </div>
    </header>

    <main>

    <?php

    if(SETTINGS["website_maintenance"] == "1" )
    {
        if(isset($_COOKIE['oauth_access_token']) || !empty($_COOKIE['oauth_access_token']))
        {
            echo "

            <div style='position: fixed;bottom: 10px;right: 10px;z-index:999999999999;background: blue;padding: 10px;color: white;border-radius: 6px;'>
            Mode maintenance actif !
            </div>
            
            ";
        }
        else{
    ?>
    <style>
        .maintenance p, .maintenance h1{ color: #FFFF00}
        a { color: #fff; font-weight: bold;}
        a:hover { text-decoration: none; }
        svg { width: 75px; margin-top: 1em; }
    </style>

    <div class="vacations maintenance" style="background: orangered;color: white;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M331.8 224.1c28.29 0 54.88 10.99 74.86 30.97l19.59 19.59c40.01-17.74 71.25-53.3 81.62-96.65c5.725-23.92 5.34-47.08 .2148-68.4c-2.613-10.88-16.43-14.51-24.34-6.604l-68.9 68.9h-75.6V97.2l68.9-68.9c7.912-7.912 4.275-21.73-6.604-24.34c-21.32-5.125-44.48-5.51-68.4 .2148c-55.3 13.23-98.39 60.22-107.2 116.4C224.5 128.9 224.2 137 224.3 145l82.78 82.86C315.2 225.1 323.5 224.1 331.8 224.1zM384 278.6c-23.16-23.16-57.57-27.57-85.39-13.9L191.1 158L191.1 95.99l-127.1-95.99L0 63.1l96 127.1l62.04 .0077l106.7 106.6c-13.67 27.82-9.251 62.23 13.91 85.39l117 117.1c14.62 14.5 38.21 14.5 52.71-.0016l52.75-52.75c14.5-14.5 14.5-38.08-.0016-52.71L384 278.6zM227.9 307L168.7 247.9l-148.9 148.9c-26.37 26.37-26.37 69.08 0 95.45C32.96 505.4 50.21 512 67.5 512s34.54-6.592 47.72-19.78l119.1-119.1C225.5 352.3 222.6 329.4 227.9 307zM64 472c-13.25 0-24-10.75-24-24c0-13.26 10.75-24 24-24S88 434.7 88 448C88 461.3 77.25 472 64 472z"/></svg>
        <div>
            <h1>Nous seront bientôt de retour !</h1>
            <div>
                <p>Nous sommes actuellement en train d'effectuer une maintenance du site. Vous pouvez toujours nous contacter à l'adresse <a href="mailto:<?= SETTINGS["contact_email"] ?>"><?= SETTINGS["contact_email"] ?></a></p>
                <p><b>Vous êtes bénévole ?</b> <a href="https://nextcloud.levendelaiscinema.fr">Accéder à l'espace bénévoles <i class="fa fa-external-link"></i></a></p>
                <p>&mdash; L'équipe du Vendelais</p>
            </div>
        </div>
    </div>
    <?php
        include("footer.php");
        die;
        }
    }


    $currentDate = new DateTime();

    // Date du nouvel an
    $dateNewYear = new DateTime($currentDate->format('Y') . '-01-01');

    // Définir la date d'octobre rose'
    $startDateRose = new DateTime($currentDate->format('Y') . '-10-01');
    $endDateRose = new DateTime($currentDate->format('Y') . '-10-20');

    // Définir la date d'halloween
    $startDateHalloween = new DateTime($currentDate->format('Y') . '-10-20');
    $endDateHalloween = new DateTime($currentDate->format('Y') . '-11-02');

    // Définir les dates de noël
    $startDateNoel = new DateTime($currentDate->format('Y') . '-12-01');
    $endDateNoel = new DateTime($currentDate->format('Y')+1 . '-01-05');

    global $theme;

    $theme = "";

    if (((new DateTime())->setTime(0,0) == $dateNewYear || !empty($_GET["newyear"])) && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == "/")
    {
        $theme = "new_year";
        ?>
        
        <script src='https://cdn.jsdelivr.net/npm/fireworks-js@2.x/dist/index.umd.js'></script>
        <div class="fireworks" style="position: fixed;top: 0;left: 0;width:100%;height:100%;z-index:99999;user-select: none;pointer-events: none;"></div>
        <script>
            const container = document.querySelector('.fireworks')
            const fireworks = new Fireworks.default(container)
            fireworks.start()
        </script>

        
        <?php
    }
    else if (($currentDate >= $startDateRose && $currentDate <= $endDateRose) || !empty($_GET["rose"]) )
    {
        $theme = "rose";
        ?>
        <link href=<?= asset("cdn://styles/themes/rose.css") ?> rel=stylesheet>
        <img src="https://cdn.levendelaiscinema.fr/proxy/https://onconormandie.fr/wp-content/uploads/2021/10/ruban-rose.png" class="octobre-rose">
        
        <?php
    }
    else if (($currentDate >= $startDateHalloween && $currentDate <= $endDateHalloween) || !empty($_GET["halloween"]) )
    {
        $theme = "halloween";
        ?>
        <link href=<?= asset("cdn://styles/themes/halloween.css")?> rel=stylesheet><link href="https://fonts.googleapis.com/css?family=Eater"rel=stylesheet>
        <div class=spider_0><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div><div class=spider_1><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div><div class=spider_2><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div><div class=spider_3><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div><div class=spider_4><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div><div class=spider_5><div class="left eye"></div><div class="right eye"></div><span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg left"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span> <span class="leg right"></span></div>
        <?php
    }
    else if(($currentDate >= $startDateNoel && $currentDate <= $endDateNoel) || !empty($_GET["noel"]) )
    {
        $theme = "noel";
        ?>
        <link href=<?= asset("cdn://styles/themes/noel.css")?> rel=stylesheet>

        <div class="snowflakes" aria-hidden="true">
            <div class="snowflake snowflake1"></div>
            <div class="snowflake snowflake1"></div>
            <div class="snowflake snowflake3"></div>
            <div class="snowflake snowflake2"></div>
            <div class="snowflake snowflake1"></div>
            <div class="snowflake snowflake4"></div>
            <div class="snowflake snowflake2"></div>
            <div class="snowflake snowflake1"></div>
            <div class="snowflake snowflake4"></div>
            <div class="snowflake snowflake2"></div>
        </div>
        <?php
    }
    ?>