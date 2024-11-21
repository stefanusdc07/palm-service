<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package palm_services
 */
?>
 <html <?php language_attributes(); ?>>
 <head>
     <meta charset="<?php bloginfo('charset'); ?>">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title><?php wp_title('|', true, 'right'); ?></title>
     <?php wp_head(); ?>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 </head>
 <header id="header" class="navbar navbar-expand-lg fixed-top">
 <div class="container">
     <!-- Logo -->
     <a class="navbar-brand" href="<?php echo home_url(); ?>">
         <?php 
         if (has_custom_logo()) {
             the_custom_logo();
         } else {
             echo '<img src="' . get_template_directory_uri() . '/assets/logo.png" alt="Logo" style="max-height: 40px;">'; // Add a fallback logo
         }
         ?>
     </a>

     <!-- Hamburger Menu for Mobile -->
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>

     <!-- Navigation Links -->
     <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
         <?php
         wp_nav_menu(array(
             'theme_location' => 'primary',
             'container' => false,
             'menu_class' => 'navbar-nav me-auto mb-2 mb-lg-0',
             'fallback_cb' => '__return_false',
             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
             'depth' => 2,
             'link_before' => '<span class="nav-link">',
             'link_after' => '</span>',
         ));
         ?>
         <!-- Call to Action Button -->
         <a href="#" class="btn btn-primary ms-3">JETZT ANFRAGEN</a>
     </div>
 </div>
</header>

<script>
 document.addEventListener("scroll", function () {
     const header = document.getElementById("header");
     header.classList.toggle("scrolled", window.scrollY > 50);
 });
</script>

<?php wp_footer(); ?>
</body>
</html>
