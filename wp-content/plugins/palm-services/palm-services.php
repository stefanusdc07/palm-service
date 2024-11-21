<?php
/*
Plugin Name: Palm Services plugin
Description: Manages services, testimonials, and contact submissions with custom taxonomies and meta boxes
Version: 1.0
Author: Stefanus Dewangga
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


//Create upload directory on activation
register_activation_hook(__FILE__, 'csm_create_upload_directory');
function csm_create_upload_directory() {
    $upload_dir = wp_upload_dir();
    $custom_dir = $upload_dir['basedir'] . '/form-submissions';
    
    if (!file_exists($custom_dir)) {
        wp_mkdir_p($custom_dir);
        
        // Create .htaccess to protect uploaded files
        $htaccess_content = "Options -Indexes\nDeny from all";
        file_put_contents($custom_dir . '/.htaccess', $htaccess_content);
    }
}


// Register Custom Post Types and Taxonomy on init
add_action('init', 'csm_register_post_types_and_taxonomies');
function csm_register_post_types_and_taxonomies() {
    // Register Service Post Type
    register_post_type('service', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'add_new' => 'Add New Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
            'view_item' => 'View Service'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-hammer',
        'has_archive' => true
    ));

    // Register Contact Submission Post Type
    register_post_type('submission', array(
        'labels' => array(
            'name' => 'Submissions',
            'singular_name' => 'Submission',
            'add_new' => 'Add New Submission',
            'add_new_item' => 'Add New Submission',
            'edit_item' => 'Edit Submission',
            'view_item' => 'View Submission'
        ),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-email',
        'supports' => array('title')
    ));

    // Register Service Category Taxonomy
    register_taxonomy('service_category', 'service', array(
        'labels' => array(
            'name' => 'Service Categories',
            'singular_name' => 'Service Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories'
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'service-category')
    ));
}

// Add Meta Boxes
add_action('add_meta_boxes', 'csm_add_meta_boxes');
function csm_add_meta_boxes() {
    // Service Meta Box
    add_meta_box(
        'service_details',
        'Service Details',
        'csm_service_meta_box_callback',
        'service',
        'normal',
        'high'
    );

    // Submission Meta Box
    add_meta_box(
        'submission_details',
        'Submission Details',
        'csm_submission_meta_box_callback',
        'submission',
        'normal',
        'high'
    );
}

// Service Meta Box Callback
function csm_service_meta_box_callback($post) {
    wp_nonce_field('csm_save_meta_box_data', 'csm_meta_box_nonce');

    $subtitle = get_post_meta($post->ID, '_service_subtitle', true);
    $icon = get_post_meta($post->ID, '_service_icon', true);
    ?>
    <p>
        <label for="service_subtitle">Service Subtitle:</label><br>
        <input type="text" id="service_subtitle" name="service_subtitle" value="<?php echo esc_attr($subtitle); ?>" size="50">
    </p>
    <p>
        <label for="service_icon">Service Icon (Font Awesome class):</label><br>
        <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($icon); ?>" size="50">
        <br><small>Example: fas fa-star</small>
    </p>
    <?php
}

//Load Fontawesome
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

// Submission Meta Box Callback
function csm_submission_meta_box_callback($post) {
    $name = get_post_meta($post->ID, '_submitter_name', true);
    $email = get_post_meta($post->ID, '_submitter_email', true);
    $message = get_post_meta($post->ID, '_submission_message', true);
    $file_path = get_post_meta($post->ID, '_submission_file', true);
    ?>
    <p>
        <label><strong>Name:</strong></label><br>
        <?php echo esc_html($name); ?>
    </p>
    <p>
        <label><strong>Email:</strong></label><br>
        <?php echo esc_html($email); ?>
    </p>
    <p>
        <label><strong>Message:</strong></label><br>
        <?php echo esc_html($message); ?>
    </p>
    <?php if ($file_path): ?>
    <p>
        <label><strong>Uploaded File:</strong></label><br>
        <?php
        $file_url = csm_get_secure_file_url($file_path);
        $filename = basename($file_path);
        ?>
        <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=download_submission_file&submission_id=' . $post->ID . '&nonce=' . wp_create_nonce('download_submission_file'))); ?>" 
           target="_blank">
            <?php echo esc_html($filename); ?>
        </a>
    </p>
    <?php endif; ?>
    <?php
}

function csm_get_secure_file_url($file_path) {
    return admin_url('admin-ajax.php?action=download_submission_file&file=' . urlencode($file_path));
}

// Handle secure file downloads
add_action('wp_ajax_download_submission_file', 'csm_handle_file_download');
add_action('wp_ajax_nopriv_download_submission_file', 'csm_handle_file_download');
function csm_handle_file_download() {
    if (!isset($_GET['submission_id']) || !isset($_GET['nonce'])) {
        wp_die('Invalid request');
    }

    if (!wp_verify_nonce($_GET['nonce'], 'download_submission_file')) {
        wp_die('Security check failed');
    }

    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_die('Permission denied');
    }

    $submission_id = intval($_GET['submission_id']);
    $file_path = get_post_meta($submission_id, '_submission_file', true);

    if (!$file_path || !file_exists($file_path)) {
        wp_die('File not found');
    }

    // Serve the file
    $filename = basename($file_path);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
}



// Save Meta Box Data
add_action('save_post', 'csm_save_meta_box_data');
function csm_save_meta_box_data($post_id) {
    if (!isset($_POST['csm_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['csm_meta_box_nonce'], 'csm_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save service meta
    if (isset($_POST['service_subtitle'])) {
        update_post_meta($post_id, '_service_subtitle', sanitize_text_field($_POST['service_subtitle']));
    }
    if (isset($_POST['service_icon'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
    }
}

// Add Admin Menu for Submissions
add_action('admin_menu', 'csm_add_admin_menu');
function csm_add_admin_menu() {
    add_menu_page(
        'Contact Submissions',
        'Submissions',
        'manage_options',
        'contact-submissions',
        'csm_submissions_page_callback',
        'dashicons-email',
        30
    );
}

// Admin Page Callback
function csm_submissions_page_callback() {
    ?>
    <div class="wrap">
        <h1>Contact Submissions</h1>
        
        <?php
        $submissions = new WP_Query(array(
            'post_type' => 'submission',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'orderby' => 'date'
        ));

        if ($submissions->have_posts()) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($submissions->have_posts()) : $submissions->the_post(); 
                        $file_path = get_post_meta(get_the_ID(), '_submission_file', true);
                    ?>
                        <tr>
                            <td><?php echo get_the_date(); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), '_submitter_name', true)); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), '_submitter_email', true)); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), '_submission_message', true)); ?></td>
                            <td>
                                <?php if ($file_path): 
                                    $filename = basename($file_path);
                                ?>
                                    <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=download_submission_file&submission_id=' . get_the_ID() . '&nonce=' . wp_create_nonce('download_submission_file'))); ?>"
                                       target="_blank">
                                        <?php echo esc_html($filename); ?>
                                    </a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No submissions found.</p>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
    <?php
}

// Handle Form Submissions via AJAX
add_action('wp_ajax_submit_contact_form', 'csm_handle_submission');
add_action('wp_ajax_nopriv_submit_contact_form', 'csm_handle_submission');

function csm_handle_submission() {
    check_ajax_referer('contact_form_nonce', 'nonce');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('Please fill in all fields.');
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error('Please enter a valid email address.');
        return;
    }

    $file_path = '';
    if (!empty($_FILES['attachment']['name'])) {
        $file = $_FILES['attachment'];
        
        // Verify file type
        $allowed_types = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed_types)) {
            wp_send_json_error('Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG');
            return;
        }

        // Verify file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            wp_send_json_error('File size must be less than 5MB.');
            return;
        }

		if (isset($_POST['phone'])) {
    update_post_meta($submission_id, '_submitter_phone', sanitize_text_field($_POST['phone']));
}
if (isset($_POST['services'])) {
    update_post_meta($submission_id, '_submission_service', sanitize_text_field($_POST['services']));
}

         // Generate secure filename
         $upload_dir = wp_upload_dir();
         $custom_dir = $upload_dir['basedir'] . '/form-submissions';
         $filename = uniqid() . '-' . sanitize_file_name($file['name']);
         $file_path = $custom_dir . '/' . $filename;
 
         // Move uploaded file
         if (!move_uploaded_file($file['tmp_name'], $file_path)) {
             wp_send_json_error('Failed to upload file.');
             return;
         }
     }

    // Create submission post
    $submission_data = array(
        'post_title' => wp_strip_all_tags($name),
        'post_type' => 'submission',
        'post_status' => 'publish'
    );

    $submission_id = wp_insert_post($submission_data);

    if ($submission_id) {
        update_post_meta($submission_id, '_submitter_name', $name);
        update_post_meta($submission_id, '_submitter_email', $email);
        update_post_meta($submission_id, '_submission_message', $message);
        if ($file_path) {
            update_post_meta($submission_id, '_submission_file', $file_path);
        }

        wp_send_json_success('Thank you for your submission!');
    } else {
        // Clean up file if post creation fails
        if ($file_path && file_exists($file_path)) {
            unlink($file_path);
        }
        wp_send_json_error('An error occurred. Please try again.');
    }
}

// Add shortcode for the contact form
add_shortcode('contact_form', 'csm_contact_form_shortcode');
function csm_contact_form_shortcode() {
    ob_start();
    ?>
    <form id="contact-form" class="contact-form" enctype="multipart/form-data">
        <?php wp_nonce_field('contact_form_nonce'); ?>
        <div class="row">
            <div class="col-md-6">
                <label for="name">Name<span>*</span></label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="email">E-Mail<span>*</span></label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="phone">Telefonnummer</label>
                <input type="text" id="phone" name="phone" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="services">Services<span>*</span></label>
                <select id="services" name="services" class="form-select" required>
                    <option value="">Select Service</option>
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <option value="service3">Service 3</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="message">Nachricht</label>
            <textarea id="message" name="message" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="attachment">Dateien hochladen</label>
            <input type="file" id="attachment" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            <small>Dateien hochladen (z.B. deinen Lebenslauf hochladen, Zeugnisse...)</small>
        </div>
        <div class="mb-3">
            <label>
                <input type="checkbox" name="terms" required>
                Mit dem Absenden bestätige ich die <a href="#">Datenschutzinformation</a> gelesen zu haben und bestätige diese.
            </label>
        </div>
        <button type="submit" class="btn btn-danger">JETZ ANFRAGEN</button>
        <div id="form-message" class="mt-3"></div>
    </form>

    <script>
    jQuery(document).ready(function($) {
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            formData.append('action', 'submit_contact_form');
            formData.append('nonce', '<?php echo wp_create_nonce('contact_form_nonce'); ?>');

            $('#form-message').html('<div class="alert alert-info">Submitting...</div>');
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#form-message').html('<div class="alert alert-success">' + response.data + '</div>');
                        $('#contact-form')[0].reset();
                    } else {
                        $('#form-message').html('<div class="alert alert-danger">' + response.data + '</div>');
                    }
                },
                error: function() {
                    $('#form-message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}