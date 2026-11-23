<?php
/**
 * 404 template.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="aifirst-no-results">
	<h1 class="aifirst-page-title"><?php esc_html_e( 'That page isn\'t here.', 'aifirstsaas' ); ?></h1>
	<p><?php esc_html_e( 'The link might be out of date, or the page may have been moved. Try a search.', 'aifirstsaas' ); ?></p>
	<form class="aifirst-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="aifirst-sr" for="aifirst-search-404"><?php esc_html_e( 'Search', 'aifirstsaas' ); ?></label>
		<input id="aifirst-search-404" type="search" name="s" placeholder="<?php esc_attr_e( 'Search a tool, feature, or category…', 'aifirstsaas' ); ?>">
		<button type="submit"><?php esc_html_e( 'Search', 'aifirstsaas' ); ?></button>
	</form>
	<p style="margin-top:24px;">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Back to the home page', 'aifirstsaas' ); ?></a>
	</p>
</section>

<?php get_footer(); ?>
