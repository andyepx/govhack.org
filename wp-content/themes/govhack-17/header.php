<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sequential
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('–', true, 'right'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'sequential'); ?></a>

    <?php if (show_header()): ?>

    <?php if (get_header_image()) : ?>
    <header id="masthead" class="site-header header-with-background"
            style="background-image: url('<?php header_image(); ?>');">
        <?php else: ?>
        <header id="masthead" class="site-header" role="banner">
            <?php endif; ?>

            <?php if (header_search()): ?>
                <div class="wrapper searchbar-wrapper">
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>

            <div class="wrapper gh-headerdevice-container">
                <a class="gh-headerdevice gh-headerdevice-1 blue pink dual-color"
                   href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <div class="gh-site-branding">
                        <?php sequential_the_site_logo(); ?>
                        <?php if (get_header_image()) : ?>
                            <div class="site-title">
                                <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>">
                            </div>
                        <?php elseif (file_exists(get_stylesheet_directory() . '/img/logo.png')): ?>
                            <div class="site-title">
                                <img src="<?= get_stylesheet_directory_uri() . '/img/logo.png' ?>"
                                     alt="<?php bloginfo('name'); ?>">
                            </div>
                        <?php else: ?>
                            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                      rel="home"><?php bloginfo('name'); ?></a></h1>
                            <h2 class="site-description"><?php bloginfo('description'); ?></h2>
                        <?php endif; ?>
                    </div>

                </a>

                <?php if (header_tiles()): ?>
                    <?php get_template_part('header', 'tiles') ?>
                <?php endif; ?>

            </div><!-- .wrapper.gh-headerdevice-container -->

            <div class="wrapper">
                <nav id="site-navigation" class="main-navigation" role="navigation">
                    <?php if (has_nav_menu('primary')) : ?>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container_class' => 'menu-primary',
                            'menu_class' => 'clear nav-menu',
                        ));
                        ?>
                    <?php endif; ?>
                </nav><!-- #site-navigation -->

            </div><!-- .wrapper -->

        </header><!-- #masthead -->

        <?php endif; ?>

        <div id="content" class="site-content <?php echo content_additional_classes() ?>">