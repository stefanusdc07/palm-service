<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package palm_services
 */

?>

<footer class="footer bg-white py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <img src="<?php echo get_theme_file_uri('assets/images/logo.png'); ?>" alt="Alarmanlagenbau-Korsing" class="mb-2" style="max-width: 60px;">
                <div class="company-info small">
                    <p class="mb-1">Alarmanlagenbau-Korsing</p>
                    <p class="mb-1">GmbH & Co. KG</p>
                    <p class="mb-1">Walter-Korsing-Straße 21</p>
                    <p class="mb-1">15230 Frankfurt (Oder)</p>
                </div>
            </div>

            <!-- Unternehmen -->
            <div>
                <h6 class="fw-bold mb-2">Unternehmen</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1">Unser Unternehmen</li>
                    <li class="mb-1">Produkte & Hersteller</li>
                    <li class="mb-1">Referenzen</li>
                    <li class="mb-1">Kontakt</li>
                </ul>
            </div>

            <!-- Leistungen -->
            <div>
                <h6 class="fw-bold mb-2">Leistungen</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1">Einbruchmeldeanlagen</li>
                    <li class="mb-1">Videoüberwachung</li>
                    <li class="mb-1">Brandmeldeanlagen</li>
                    <li class="mb-1">Rauchabzugsanlagen</li>
                    <li class="mb-1">lorem ipsum</li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h6 class="fw-bold mb-2">Den Kontakt Halten</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1">+49 335 545620</li>
                    <li class="mb-1">info@alarmanlagenbau-korsing.de</li>
                </ul>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-4 small">
            <span>Impressum</span>
            <span class="mx-2">•</span>
            <span>Datenschutz</span>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>