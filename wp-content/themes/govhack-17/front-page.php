<?php
/**
 * The Front Page. It just is.
 *
 * @package Sequential
 */

// All for the sake of being able to show the number of active locations in the tooltip
try {
    $loc_parser = gh_get_location_parser();
    $loc_parser->parse_locations();
    $au_count = $loc_parser->get_region_counts(['nsw', 'act', 'qld', 'vic', 'tas', 'wa', 'sa', 'nt']);
    $nz_count = $loc_parser->get_region_counts(['nz']);
    $au_count = array_sum($au_count);
    $nz_count = array_sum($nz_count);
    // if ( is_wp_error($au_count) || is_wp_error($nz_count) ){
    // throw new Exception();
    // }
} catch (Exception $ex) {
    // Anything could happen...
    $au_count = $nz_count = 0;
} finally {
    $au_tooltip = !(empty($au_count) || is_wp_error($au_count)) ? "Across $au_count locations and counting" : 'In major cities and regional centres around Australia';
    $nz_tooltip = !(empty($nz_count) || is_wp_error($nz_count)) ? "Across $nz_count locations and counting" : 'In major cities and regional centres around New Zealand';
}


get_header(); ?>

    <section id="primary">
        <?php if ($chosen_banner = get_theme_mod('gh_chosen_banner_template', 0)): ?>
            <?php get_template_part($chosen_banner) ?>
        <?php else: ?>
            <div class="hero home-hero" role="banner">
                <div class="wrapper">
                    <?php if ($banner_date_heading = get_theme_mod('gh_date_heading_content', false)): ?>
                        <h2 class="home-hero-date-counter"><?php echo $banner_date_heading ?></h2>
                    <?php endif; ?>
                    <div id="home-hero-slider" class="home-hero-slider">
                        <div>
                            <?php if ($banner_content = get_theme_mod('gh_banner_content', false)): ?>
                                <h1><?php echo $banner_content ?></h1>
                            <?php else: ?>
                                <h1 id="gh-defined">
                                    <dfn class="govhack tooltip-now"
                                         title="An event with a history that can be traced back to 2009.">GovHack</dfn>
                                    is an
                                    <dfn class="open-data tooltip-now"
                                         title="Data sets that governments (federal, state) have made available to the public.">open
                                        data</dfn>
                                    <dfn class="hackathon tooltip-now"
                                         title="A spontaneous, creative event where members form teams to create solutions to problems.">hackathon</dfn>
                                    held across
                                    <dfn class="australia tooltip-defer"
                                         title="<?php echo $au_tooltip ?>">Australia</dfn>
                                    and
                                    <dfn class="new-zealand tooltip-defer" title="<?php echo $nz_tooltip ?>">New
                                        Zealand</dfn>
                                </h1>
                            <?php endif; ?>
                        </div>
                        <?php if (($sleeps_left = gh_sleeps_left()) !== null): ?>
                            <div>
                                <?php if ($sleeps_left > 0): ?>
                                    <!-- This value appears incorrectly because of caching -->
                                    <!-- <h1>Only <?php echo sprintf(_n('%s sleep', '%s sleeps', $sleeps_left), $sleeps_left) ?> to go until GovHack 2016</h1> -->
                                <?php elseif ($sleeps_left > -3): ?>
                                    <!-- <h1>GovHack is currently live <span class="fa fa-rss"></span> across Australia and New Zealand</h1> -->
                                <?php endif; ?>

                                <h1 id="time-left">GovHack begins shortly</h1>
                                <?php echo gh_sleeps_left_js('#time-left') ?>

                            </div>
                        <?php endif; ?>
                    </div><!-- .home-hero-slider -->
                    <div>
                        <?php
                        for ($i = 1; $i <= 2; $i++):
                            $naked = get_theme_mod('gh_banner_button_' . $i . '_naked', false);
                            $label = get_theme_mod('gh_banner_button_' . $i . '_label');
                            $link = get_theme_mod('gh_banner_button_' . $i . '_link') ?: get_permalink(get_theme_mod('gh_banner_button_' . $i . '_page', 0));
                            if ($label && $link): ?>
                                <?php if ($naked): ?>
                                    <?php echo do_shortcode($label) ?>
                                <?php else: ?>
                                    <a class="button-minimal"
                                       href="<?php echo esc_url($link) ?>"><?php echo do_shortcode($label) ?></a>
                                <?php endif; ?>
                            <?php endif; endfor; ?>
                    </div>
                </div><!-- .wrapper -->
            </div><!-- .hero -->
        <?php endif; ?>

        <?php if ($banner_date_heading = get_theme_mod('gh_tile_show_announcements', false)): ?>
            <div class="content-area gh-announcements full-width">
                <div class="wrapper flex-wrapper flex-center">
                    <?php get_template_part('home', 'announcements'); ?>
                </div>
            </div>
        <?php endif; // gh_tile_show_announcements ?>


        <?php
        // Check if there is a nominated Sponsor logos page. 
        if ($sponsor_logos_page_id = get_option('gh_sponsor_logos_page_id')) {
            $sponsor_logos_page_link = get_permalink($sponsor_logos_page_id);
        }
        ?>

        <?php get_template_part('region', 'buttons'); ?>

        <div class="content-area full-width">
            <div class="gh-tiles wrapper">
                <div class="gh-home-tile-header">
                    <div class="gh-home-social-media">
                        <?php if ($gh_twitter_url = get_theme_mod('gh_social_twitter', false)): ?>
                            <a href="<?php echo esc_url($gh_twitter_url) ?>"><span
                                        class="fa fa-twitter-square fa-2x"></span></a>
                        <?php endif; // gh_social_twitter ?>
                        <?php if ($gh_facebook_url = get_theme_mod('gh_social_facebook', false)): ?>
                            <a href="<?php echo esc_url($gh_facebook_url) ?>"><span
                                        class="fa fa-facebook-square fa-2x"></span></a>
                        <?php endif; // gh_social_facebook ?>
                        <?php if ($gh_slack_url = get_theme_mod('gh_social_slack', false)): ?>
                            <a href="<?php echo esc_url($gh_slack_url) ?>"><span class="fa fa-slack fa-2x"></span></a>
                        <?php endif; // gh_social_slack ?>
                    </div>
                </div>
                <div id="gh-homepage-slider" class="gh-slider" style="display: none;">
                    <div><?php get_template_part('home', 'tiles-grid'); ?></div>
                </div>
            </div>
            <div class="wrapper">
                <?php if (get_theme_mod('gh_tile_show_lastyear_notice', 0) || (defined('GH_SHOW_LASTYEAR_NOTICE') && GH_SHOW_LASTYEAR_NOTICE)): ?>
                    <div class="gh-lastyear-notice">
                        Were you looking for something?
                        You might find it in <a href="http://archive.govhack.org" target="_blank">last year's
                            website</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-area full-width">
            <div class="gh-sponsors wrapper">
                <h2 class="gh-sponsors-header">
                    GovHack 2017 National Sponsors
                    <?php if (isset($sponsor_logos_page_link)): ?>
                        <small class="inline-muted-link"> <a href="<?php echo $sponsor_logos_page_link ?>">See all
                                national and state sponsors &raquo;</a></small>
                    <?php endif; ?>
                </h2>
                <?php get_template_part('home', 'sponsors'); ?>
            </div>
        </div>


    </section>

<?php get_footer();


// The graveyard
/*
                    <div class="col-1-1">
                        <a class="tile tile-flat gh-lightgrey" href="#">
                            <span class="tile-caption">
                            Were you looking for something? You might find it in last year's website
                            </span>
                        </a>                        
                    </div>
*/