<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="container site-main">
        <?php
        while (have_posts()) :
            the_post();
        ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <?php
                    the_post_thumbnail('large');
                    the_content();

                    $newspaper = get_post_meta(get_the_ID(), '_idp_newspaper', true);
                    $mover = get_post_meta(get_the_ID(), '_idp_mover', true);
                    $location = get_post_meta(get_the_ID(), '_idp_location', true);
                    $topic = get_post_meta(get_the_ID(), '_idp_topic', true);
                    $credit = get_post_meta(get_the_ID(), '_idp_credit', true);

                    if ($newspaper || $mover || $location) {
                        echo '<div class="image-meta">';
                        if ($newspaper) {
                            echo '<p><strong>Newspaper:</strong> ' . esc_html($newspaper) . '</p>';
                        }
                        if ($mover) {
                            echo '<p><strong>Mover:</strong> ' . esc_html($mover) . '</p>';
                        }
                        if ($location) {
                            echo '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
                        }
                        if ($topic) {
                            echo '<p><strong>Topic:</strong> ' . esc_html($topic) . '</p>';
                        }
                        if ($credit) {
                            echo '<p><strong>Credit:</strong> ' . esc_html($credit) . '</p>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div><!-- .entry-content -->
            </article><!-- #post-<?php the_ID(); ?> -->

        <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        endwhile;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
