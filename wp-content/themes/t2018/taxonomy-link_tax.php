<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage wseslaw
 */

get_header();
//cat_list();
input_link();

?>

	<article id="primary" class="content-area">
<!--    taxonomy-link_tax-->
    <header class="page-header" style="float: none">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
		?>
    </header><!-- .page-header -->
	  <?php
	  ?>
		<main id="main" class="site-main">
		<?php
		if ( have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				/* Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that
				 * will be used instead.
				 */
				get_template_part( 'template-parts/content', 'linkslist' );

			endwhile;
		echo '<hr>';
		the_posts_navigation( array(
			'prev_text'          => 'Предыдущие записи',
			'next_text'          => 'Следующие записи',
			'screen_reader_text' => 'Навигация',
		) );
		
//		wp_link_pages( array(
//			'before'=>'<div class="page-links">'.esc_html__( 'Pages:', 'wseslaw' ),
//			'after' =>'</div>',
//		) );

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</article><!-- #primary -->

<?php
get_sidebar();
get_footer();
