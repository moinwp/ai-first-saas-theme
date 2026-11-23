<?php
/**
 * Search results.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="aifirst-archive-header">
	<h1 class="aifirst-page-title">
		<?php
		printf(
			/* translators: %s: search query */
			esc_html__( 'Search results for: %s', 'aifirstsaas' ),
			'<em>' . esc_html( get_search_query() ) . '</em>'
		);
		?>
	</h1>
</header>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'aifirst-result-item' ); ?>>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="aifirst-result-meta">
				<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
				· <?php echo esc_html( get_the_date() ); ?>
			</div>
			<div class="aifirst-entry"><?php the_excerpt(); ?></div>
		</article>
	<?php endwhile; ?>

	<?php
	the_posts_pagination( array(
		'mid_size'  => 2,
		'prev_text' => __( '← Previous', 'aifirstsaas' ),
		'next_text' => __( 'Next →', 'aifirstsaas' ),
	) );
	?>

<?php else : ?>

	<section class="aifirst-no-results">
		<p><?php esc_html_e( 'No results. Try a different query.', 'aifirstsaas' ); ?></p>
		<?php get_search_form(); ?>
	</section>

<?php endif; ?>

<?php get_footer(); ?>
