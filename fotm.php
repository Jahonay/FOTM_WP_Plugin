<?php

/**
 * Plugin Name: fotm-plugin
 * Plugin URI: https://www.jahonay.github.io/
 * Freedom on the move supplemental database files
 * Version: 0.1
 * Author: John-Mackey
 * Author URI: https://www.johnmackeydesigns.com/
 **/

function idp_register_post_type()
{
    $labels = array(
        'name'                  => 'FOTM Images',
        'singular_name'         => 'FOTM Image',
        'menu_name'             => 'FOTM Images',
        'name_admin_bar'        => 'Image',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Image',
        'new_item'              => 'New Image',
        'edit_item'             => 'Edit Image',
        'view_item'             => 'View Image',
        'all_items'             => 'All Images',
        'search_items'          => 'Search Images',
        'not_found'             => 'No images found.',
        'not_found_in_trash'    => 'No images found in Trash.',
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-image',
    );

    register_post_type('idp_image', $args);
}
add_action('init', 'idp_register_post_type');

// Add custom meta boxes
function idp_add_custom_meta_boxes()
{
    add_meta_box('idp_meta_box', 'Image Metadata', 'idp_meta_box_callback', 'idp_image', 'normal', 'high');
}
add_action('add_meta_boxes', 'idp_add_custom_meta_boxes');

function idp_meta_box_callback($post)
{
    $newspaper = get_post_meta($post->ID, '_idp_newspaper', true);
    $mover = get_post_meta($post->ID, '_idp_mover', true);
    $location = get_post_meta($post->ID, '_idp_location', true);
    $topic = get_post_meta($post->ID, '_idp_topic', true);
    $credit = get_post_meta($post->ID, '_idp_credit', true);

?>
    <p>
        <label for="idp_newspaper">Newspaper:</label>
        <input type="text" name="idp_newspaper" id="idp_newspaper" value="<?php echo esc_attr($newspaper); ?>" />
    </p>
    <p>
        <label for="idp_mover">Mover:</label>
        <input type="text" name="idp_mover" id="idp_mover" value="<?php echo esc_attr($mover); ?>" />
    </p>
    <p>
        <label for="idp_location">Location:</label>
        <input type="text" name="idp_location" id="idp_location" value="<?php echo esc_attr($location); ?>" />
    </p>
    <p>
        <label for="idp_mover">Topic:</label>
        <input type="text" name="idp_topic" id="idp_topic" value="<?php echo esc_attr($mover); ?>" />
    </p>
    <p>
        <label for="idp_location">Credit:</label>
        <input type="text" name="idp_credit" id="idp_credit" value="<?php echo esc_attr($location); ?>" />
    </p>
    <?php
}

function idp_save_post_meta($post_id)
{
    if (isset($_POST['idp_newspaper'])) {
        update_post_meta($post_id, '_idp_newspaper', sanitize_text_field($_POST['idp_newspaper']));
    }
    if (isset($_POST['idp_mover'])) {
        update_post_meta($post_id, '_idp_mover', sanitize_text_field($_POST['idp_mover']));
    }
    if (isset($_POST['idp_location'])) {
        update_post_meta($post_id, '_idp_location', sanitize_text_field($_POST['idp_location']));
    }
    if (isset($_POST['idp_topic'])) {
        update_post_meta($post_id, '_idp_topic', sanitize_text_field($_POST['idp_topic']));
    }
    if (isset($_POST['idp_credit'])) {
        update_post_meta($post_id, '_idp_credit', sanitize_text_field($_POST['idp_credit']));
    }
}
add_action('save_post', 'idp_save_post_meta');

// Shortcode to display images
function idp_display_images($atts)
{
    $paged_number = (get_query_var('paged') ?  get_query_var('paged') : (isset($atts['paged']) ? $atts['paged'] : ''));

    $atts = shortcode_atts(array(
        'newspaper' => '',
        'mover' => '',
        'location' => '',
        'topic' => '',
        'credit' => '',
        'paged' => 1,
    ), $atts, 'idp_images');

    $args = array(
        'post_type' => 'idp_image',
        'posts_per_page' => 9,
        'paged' => $paged_number,
        'meta_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($atts['newspaper']) {
        $args['meta_query'][] = array(
            'key' => '_idp_newspaper',
            'value' => $atts['newspaper'],
            'compare' => 'LIKE',
        );
    }
    if ($atts['mover']) {
        $args['meta_query'][] = array(
            'key' => '_idp_mover',
            'value' => $atts['mover'],
            'compare' => 'LIKE',
        );
    }
    if ($atts['location']) {
        $args['meta_query'][] = array(
            'key' => '_idp_location',
            'value' => $atts['location'],
            'compare' => 'LIKE',
        );
    }
    if ($atts['topic']) {
        $args['meta_query'][] = array(
            'key' => '_idp_topic',
            'value' => $atts['topic'],
            'compare' => 'LIKE',
        );
    }
    if ($atts['credit']) {
        $args['meta_query'][] = array(
            'key' => '_idp_credit',
            'value' => $atts['credit'],
            'compare' => 'LIKE',
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        echo '<div class="idp-images row">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $img_src = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $newspaper = get_post_meta(get_the_ID(), '_idp_newspaper', true);
            $mover = get_post_meta(get_the_ID(), '_idp_mover', true);
            $location = get_post_meta(get_the_ID(), '_idp_location', true);
            $topic = get_post_meta(get_the_ID(), '_idp_topic', true);
            $credit = get_post_meta(get_the_ID(), '_idp_credit', true);

    ?>
            <div class="col-md-4 card-wrapper">
                <a class="idp-link text-dark " href="<?= get_permalink($post_id); ?>">
                    <div class="idp-image-card p-2">
                        <h4 class="font-weight-bold text-uppercase"><?php the_title(); ?></h4>
                        <img style="object-fit: cover;width: 100%;height: 250px;" src="<?php echo esc_url($img_src); ?>" alt="<?php the_title(); ?>">
                        <div class="idp-meta">
                            <p><?php the_content() ?></p>
                            <p>
                                <?php if ($newspaper) echo 'Newspaper:' . esc_html($newspaper) . '<br>'; ?>
                                <?php if ($mover) echo 'Mover:' . esc_html($mover) . '<br>'; ?>
                                <?php if ($location) echo 'Location:' .  esc_html($location) . '<br>'; ?>
                                <?php if ($topic) echo 'Topic:' . esc_html($topic) . '<br>'; ?>
                                <?php if ($credit) echo 'Credit:' . esc_html($credit) . '<br>'; ?></p>
                        </div>
                    </div>
                </a>
            </div>
<?php
        }
        echo '</div>';

        // Pagination
        $big = 999999999; // need an unlikely integer


        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $query->max_num_pages
        ));


        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return '<p>No images found.</p>';
    }
}
add_shortcode('idp_images', 'idp_display_images');

// Enqueue styles
function idp_enqueue_styles()
{
    wp_enqueue_style('idp-styles', plugins_url('styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'idp_enqueue_styles');


add_filter('single_template', 'my_custom_template');


// Add template files
function my_custom_template($single)
{

    global $post;

    /* Checks for single template by post type */
    if ($post->post_type == 'idp_image') {
        if (file_exists(plugin_dir_path(__FILE__) . 'templates/single-idp_image.php')) {
            return plugin_dir_path(__FILE__) . 'templates/single-idp_image.php';
        }
    }

    return $single;
}

add_filter('archive_template', 'my_custom_template_archive');


// Add template files
function my_custom_template_archive($single)
{

    global $post;

    /* Checks for archive template by post type */
    if ($post->post_type == 'idp_image') {
        if (file_exists(plugin_dir_path(__FILE__) . 'templates/archive-idp_image.php')) {
            return plugin_dir_path(__FILE__) . 'templates/archive-idp_image.php';
        }
    }

    return $single;
}
