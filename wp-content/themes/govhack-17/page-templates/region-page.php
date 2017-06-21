<?php
/**
 * Template Name: GH Region Page
 *
 * @package Sequential
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <?php if (has_post_thumbnail()): ?>
        <div class="hero region-hero has-background-image" style="background-image: url('<?php the_post_thumbnail_url() ?>')">
    <?php else: ?>
        <div class="hero region-hero">
    <?php endif; ?>
    <div class="wrapper">
        <?php // sequential_post_thumbnail(); ?>

        <div class="entry-written-content">

            <header class="hero-header">
                <?php if ($heading = get_post_meta(get_the_ID(), 'hero_title', true)): ?>
                    <!-- <span class="inline-logo logo-govhack"></span> -->
                    <h1 class="hero-heading"><?= htmlentities($heading) ?></h1>
                <?php else: ?>
                    <h1 class="hero-heading"><?php the_title() ?></h1>
                <?php endif; ?>
                <?php if ($subheading = get_post_meta(get_the_ID(), 'hero_subtitle', true)): ?>
                    <h2 class="hero-subheading"><?= htmlentities($subheading) ?></h2>
                <?php endif; ?>
            </header>

        </div><!-- .entry-written-content -->
    </div><!-- .wrapper -->
    </div><!-- .hero -->

    <main id="main" class="site-main two-up" role="main">

        <?php get_template_part('region', 'location_side'); ?>

        <div class="wrapper">
            <div id="primary" class="content-area">

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content">
                        <?php the_content() ?>
                    </div>
                </article><!-- .hentry -->

                <?php edit_post_link(esc_html__('Edit', 'sequential'), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>'); ?>

            </div><!-- #primary -->

            <!--            --><?php //get_sidebar('region'); ?>

        </div><!-- .wrapper -->
    </main><!-- #main -->

    <?php if (gh_has_regional_sponsors()): ?>
        <div class="hero region-sponsors-hero">
            <div class="wrapper">
                <section class="region-sponsors gh-sponsors">
                    <h2 class="gh-sponsors-header">2017 State Sponsors</h2>
                    <?php get_template_part('region', 'sponsors'); ?>
                </section>
            </div><!-- .wrapper -->
        </div><!-- .hero -->
    <?php endif; // gh_has_regional_sponsors ?>

<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>