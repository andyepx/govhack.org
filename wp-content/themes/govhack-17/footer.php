<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Sequential
 */
?>

</div><!-- #content -->

<?php if (show_footer()): ?>

    <footer id="colophon" class="site-footer" role="contentinfo">

        <?php get_sidebar('footer'); ?>

        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation" role="navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container_class' => 'menu-footer',
                    'menu_class' => 'clear',
                    'depth' => 1,
                ));
                ?>
            </nav><!-- #site-navigation -->
        <?php endif; ?>
	<div class="site-info">
                <em>GovHack</em> and the <em>GovHack Logo</em> are registered trademarks of GovHack Australia Limited.<br>Unless indicated otherwise, all other content on this site is licensed under a <a
                        href="https://creativecommons.org/licenses/by-nc-nd/3.0/us/legalcode" target="_blank">CC
                    BY-NC-ND 3.0 US</a>.
                <!-- Yes we are proud, sorry can't show it though.. <div>
                    <a href="http://wordpress.org/">Proudly powered by WordPress</a>
                </div>-->
            </div>
    </footer><!-- #colophon -->

<?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
