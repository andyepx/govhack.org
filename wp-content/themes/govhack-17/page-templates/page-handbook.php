<?php
/**
 * Template Name: Handbook page
 * Author: AE
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <?php get_template_part('handbook', 'chapters'); ?>
        <main id="main" class="site-main" role="main">

            <?php while (have_posts()) : the_post(); ?>

                <?php get_template_part('content', 'page'); ?>


            <?php endwhile; // end of the loop. ?>

            <script>
                jQuery('.handbook-chapters-list').click(function () {
                    jQuery('.handbook-chapters-list').toggleClass('expanded');
                })
            </script>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer(); ?>