<?php
/**
 * Fallback template.
 *
 * Covers blog archives, categories, tags and any single post the
 * plugin doesn't own. Plugin-owned screens (aifirst_tool, aifirst
 * taxonomies, /compare, /stack, /features, /submit, /changelog, and
 * the front page when `aifirst_own_home` is set) are routed through
 * `template_include` before this file is ever reached.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php if ( have_posts() ) : ?>

	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="aifirst-archive-header">
			<h1 class="aifirst-page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php elseif ( is_archive() ) : ?>
		<header class="aifirst-archive-header">
			<h1 class="aifirst-page-title"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="aifirst-entry">', '</div>' ); ?>
		</header>
	<?php endif; ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( is_singular() ) : ?>
				<h1 class="aifirst-page-title"><?php the_title(); ?></h1>
				<div class="aifirst-entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
				</div>
				<?php if ( get_previous_post_link() || get_next_post_link() ) : ?>
					<nav class="aifirst-post-nav">
						<div><?php previous_post_link(); ?></div>
						<div><?php next_post_link(); ?></div>
					</nav>
				<?php endif; ?>
				<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
			<?php else : ?>
				<h2 class="aifirst-page-title" style="font-size:22px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="aifirst-result-meta"><?php echo esc_html( get_the_date() ); ?></div>
				<div class="aifirst-entry"><?php the_excerpt(); ?></div>
			<?php endif; ?>
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
		<h1 class="aifirst-page-title"><?php esc_html_e( 'Nothing to show here yet', 'aifirstsaas' ); ?></h1>
		<p><?php esc_html_e( 'Try a search instead.', 'aifirstsaas' ); ?></p>
		<?php get_search_form(); ?>
	</section>

<?php endif; ?>

<?php get_footer(); ?>
