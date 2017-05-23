<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Sequential
 */

$gh_archive = defined('GH_ARCHIVE_URL') ? GH_ARCHIVE_URL : 'http://archive.govhack.org';
 
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="wrapper">

				<section class="error-404 not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'sequential' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="page-content">
                        <div class="grid">
                            <div class="col-1-2">
                                <p><?php esc_html_e( 'You might have tried to access one of the pages from our past websites (2015, 2014, etc).', 'sequential' ); ?></p>
                                <p>After you navigated here but before this page was loaded, we already checked for the URL on the archive site, and couldn't find it either. Perhaps try browsing <a href="<?= esc_url($gh_archive) ?>">the archive site</a> yourself.</p>
                            </div>
                            <div class="col-1-2">
                                <p><?php esc_html_e( 'Or search for the topic that you seek:', 'sequential' ); ?></p>
                                <?php get_search_form(); ?>
                            </div>
                        </div>


					</div><!-- .page-content -->
				</section><!-- .error-404 -->

			</div><!-- .wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>