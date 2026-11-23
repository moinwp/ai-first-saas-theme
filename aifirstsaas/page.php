<?php
/**
 * Page template — same shell as index.php without post-to-post nav.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h1 class="aifirst-page-title"><?php the_title(); ?></h1>
		<div class="aifirst-entry">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
		<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
