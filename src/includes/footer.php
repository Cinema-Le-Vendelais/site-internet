</main>

<!-- PAGE SCRIPTS -->
<?php
if (array_key_exists("footerScripts", PAGE) && is_array(PAGE["footerScripts"])) {
    foreach (PAGE["footerScripts"] as $script) {
        echo "<script src='" . $script . "'></script>\n";
    }
}

if ($GLOBALS["theme"] == "halloween") {
    echo "<img src='https://cdn.levendelaiscinema.fr/proxy/https://cloud.levendelaiscinema.fr/uploads/medias/halloween-separator.png' class='halloween-separator'>";
}
?>


<footer>
    <?php
    if ($GLOBALS["theme"] == "noel") {
    ?>
        <ul class="lightrope">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    <?php
    }
    ?>
    <div class="foo">
        <div class="logo">
            <img class="logo lazyload" data-src="<?= loadCdnUrl("v2/logo.png") ?>?compress=1" alt="Logo">
            <a href="https://go.levendelaiscinema.fr/newsletter">
                <i class="fa-solid fa-envelope fa-xl"></i>
                S'abonner à la newsletter
            </a>
        </div>
        <div class="contact">
            <h4>Nous contacter</h4>
            <ul>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:<?= SETTINGS["contact_email"] ?>" target="_blank"><?= SETTINGS["contact_email"] ?></a>
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:<?= str_replace(" ", "", SETTINGS["contact_phone"]) ?>" target="_blank"><?= SETTINGS["contact_phone"] ?></a>
                </li>
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?= SETTINGS["contact_address"] ?></span>
                </li>
            </ul>
        </div>
        <div class="socials">
            <h4>Réseaux sociaux</h4>
            <ul>
                <li>
                    <i class="fa-brands fa-facebook"></i>
                    <a href="<?= SETTINGS["contact_facebook"] ?>" target="_blank">Facebook</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="copy">
        <ul>
            <?php
            foreach (MENU_LEGAL as $item) {
                echo "<li class='legal'><a href='" . $item["url"] . "'>" . $item["title"] . "</a></li>";
            }
            ?>
            <li>
                <a href="https://levendelaiscinema.fr/portail"><i class="fa-solid fa-lock"></i> Espace bénévoles</a>
            </li>
            <li>
                <span><?= SETTINGS["website_name"] ?> &copy; 2025. Réalisé par <a class="liamgenjs" href="https://liamcharpentier.fr">Liam <i class="fa-solid fa-arrow-up-right-from-square"></i></a></span>
            </li>
        </ul>
    </div>
</footer>


    <div class="mobile-menu">
        <?php
        foreach (MENU as $item) {
            if (!$item["mobile"]) continue;
            $url = $item["url"];
            $icon = $item["icon"];
            $name = $item["short_name"] ? $item["short_name"] : $item["title"];
            $isNew = $item["isNew"] ? ' class="is-new" ' : "";

            echo <<<EOT
            <a href="$url"$isNew>
                <i class="fa-solid fa-$icon"></i>
                <span>$name</span>
            </a>
            EOT;
        }
        ?>
    </div>


    <div class="lgjs-cookies">
        <div class="lgjs-cookies-content">
            <p>
            <h2><i class="fa-solid fa-cookie-bite fa-xl"></i> Cookies</h2>
            Ce site Web utilise des cookies pour vous aider à avoir une expérience de navigation supérieure et plus pertinente sur le site Web.
            <a href="/about-cookies">En savoir plus...</a>
            </p>

            <button class="lgjs-cookies-ok">J'accepte</button>
            <button class="lgjs-cookies-nop">Je refuse</button>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let cookiesWrapper = document.querySelector('.lgjs-cookies');
            let cookieButtonYes = document.querySelector(".lgjs-cookies-ok");
            let cookieButtonNop = document.querySelector(".lgjs-cookies-nop");

            if (cookiesWrapper) {
                if (!getCookie("cookie-consent") || getCookie("cookie-consent") == "") {
                    cookiesWrapper.style.display = "block";
                }

                cookieButtonYes.addEventListener("click", () => {
                    setCookie("cookie-consent", true, 365)
                    location.reload();
                })

                cookieButtonNop.addEventListener("click", () => {
                    setCookie("cookie-consent", false, 365);
                    location.reload();
                })
            }


        })
    </script>

    <script src="<?= loadCdnUrl("v2/3rdparty/lazysizes/script.js") ?>"></script>

    </body>

</html>