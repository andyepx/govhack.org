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
    
	<footer id="colophon" class="site-footer" role="contentinfo">

        <?php get_sidebar( 'footer' ); ?>

        <?php if ( has_nav_menu( 'footer' ) ) : ?>
            <nav class="footer-navigation" role="navigation">
                <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'footer',
                        'container_class' => 'menu-footer',
                        'menu_class'      => 'clear',
                        'depth'           => 1,
                    ) );
                ?>
            </nav><!-- #site-navigation -->
        <?php endif; ?>

		<div class="site-info">
            GovHack is run under the auspices of <a href="<?php echo esc_url( __( 'https://linux.org.au/', 'sequential' ) ); ?>" target="_blank">Linux Australia</a>. 
            Content on this site is licensed unser a <a href="<?php echo esc_url( __( 'https://creativecommons.org/licenses/by-nc-nd/3.0/us/legalcode', 'sequential' ) ); ?>" target="_blank">CC BY-NC-ND 3.0 US</a>. 
            <!-- Yes we are proud, sorry can't show it though.. <div>
                <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'sequential' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'sequential' ), 'WordPress' ); ?></a>
            </div>-->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>