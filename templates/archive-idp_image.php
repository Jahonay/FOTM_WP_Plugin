<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container">
        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title">Images</h1>
            </header><!-- .page-header -->
            <div class="idp-images post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php
                // Start the Loop.
                while (have_posts()) :
                    the_post();
                    $post_id = get_the_ID();
                    $img_src = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    $newspaper = get_post_meta(get_the_ID(), '_idp_newspaper', true);
                    $mover = get_post_meta(get_the_ID(), '_idp_mover', true);
                    $location = get_post_meta(get_the_ID(), '_idp_location', true);
                    $topic = get_post_meta(get_the_ID(), '_idp_topic', true);
                    $credit = get_post_meta(get_the_ID(), '_idp_credit', true);
                ?>


                    <div class="col-md-4 card-wrapper">
                        <a class="idp-link text-dark" href="<?= get_permalink($post_id); ?>">
                            <div class="idp-image-card p-2">
                                <h4 class="font-weight-bold text-uppercase"><?php the_title(); ?></h4>
                                <img style="object-fit: cover;width: 100%;height: 250px;" src="<?php echo esc_url($img_src); ?>" alt="<?php the_title(); ?>">
                                <div class="idp-meta">
                                    <p><?php
                                        the_content() ?></p>
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
                endwhile;

                ?>
            </div>
        <?php
            // Pagination.
            the_posts_pagination();

        else :
        ?>

            <p>No images found.</p>

        <?php endif; ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
