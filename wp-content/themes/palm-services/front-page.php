<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package palm-services
 */

get_header();
?>
<?php get_header(); ?>

<main class="frontpage">
    <!-- Hero Section -->
    <section class="hero text-center text-white" style="background: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-bg.png') no-repeat center center; background-size: cover;">
        <div class="container py-5">
            <h1 class="display-4 fw-bold">SICHERHEIT FÜR IHR ZUHAUSE UND UNTERNEHMEN</h1>
            <p class="lead">Wir bieten maßgeschneiderte Sicherheitslösungen, um Ihre wertvollsten Güter zu schützen. Vertrauen Sie auf unsere langjährige Erfahrung und modernste Technologie.</p>
            <a href="#" class="btn btn-primary">JETZT ANFRAGEN</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services py-5">
        <div class="container">
            <h2 class="section-title text-center">OUR SERVICES</h2>
            <hr class="solid">
            <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <div class="row text-center">
    <?php
  
    $args = array(
        'post_type' => 'service',
        'posts_per_page' => 6, 
        'orderby' => 'date',
        'order' => 'ASC'
    );
    $service_query = new WP_Query($args);

    if ($service_query->have_posts()) :
        $counter = 0; 
        while ($service_query->have_posts()) :
            $service_query->the_post();
            
            $subtitle = get_post_meta(get_the_ID(), '_service_subtitle', true);
            $icon = get_post_meta(get_the_ID(), '_service_icon', true);

           
            if ($counter % 3 === 0 && $counter !== 0) {
                echo '</div><div class="row text-center">';
            }
            ?>
            <div class="col-md-4 py-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="<?php echo esc_attr($icon); ?> display-5"></i> 
                        <h5 class="card-title mt-3"><?php the_title(); ?></h5> 
                        <p class="card-text"><?php echo esc_html($subtitle); ?></p> 
                    </div>
                </div>
            </div>
            <?php
            $counter++; 
        endwhile;
        wp_reset_postdata(); 
    else :
        ?>
        <p>No services found.</p>
    <?php endif; ?>
</div>

        </div>
    </section>

    <!-- About Section -->
    <section class="about bg-light py-5">
        <div class="container d-flex flex-column flex-md-row align-items-center">
            <div class="text-md-start mb-4 mb-md-0">
                <h2>UNSER UNTERNEHMEN</h2>
                <hr class="about align-items-left">
                <p>Die Firma Alarmanlagenbau-Korsing GmbH & Co. KG
                gibt es seit dem 1.10.1990. Unseren Kunden bieten wir einen 24-Stunden-Service an. Alle Zulassungen, die man für eine erfolgreiche Arbeit auf dem Gebiet der elektronischen Sicherheitstechnik benötigt, sind vorhanden</p>
            </div>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/company.png" alt="Company Building" class="img-fluid rounded">
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials py-5">
        <div class="container text-center">
            <h2>WAS UNSERE KUNDEN SAGEN</h2>
            <hr class="testimonials">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <div id="testimonial-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="container">
    <div class="row justify-content-center">
        <?php for ($i = 1; $i <= 3; $i++) : ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0">
                    <div class="card-body text-center">
                        <!-- Star Rating -->
                        <div class="star-rating mb-3">
                            <span class="text-warning">★</span>
                            <span class="text-warning">★</span>
                            <span class="text-warning">★</span>
                            <span class="text-warning">★</span>
                            <span class="text-warning">★</span>
                        </div>
                        <!-- Testimonial Text -->
                        <p class="card-text small mb-4">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                            sed do eiusmod tempor incididunt ut labore et dolore 
                            magna aliqua. Lorem ipsum dolor sit amet, consectetur 
                            adipiscing elit, sed do eiusmod tempor incididunt ut 
                            labore et dolore magna aliqua.
                        </p>
                        <!-- Author -->
                        <p class="text-muted fst-italic">Lorem Ipsum</p>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact bg-light py-5">
    <div class="container">
        <h2 class="contact text-center mb-4">KONTAKTFORMULAR</h2>
        <?php echo do_shortcode('[contact_form]'); ?>
    </div>
</section>

</main>

<?php get_footer(); ?>
