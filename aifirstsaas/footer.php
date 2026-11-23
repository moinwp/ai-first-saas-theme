<?php
/**
 * Footer template.
 *
 * Newsletter block, four-column links grid, bottom row (copyright +
 * socials). Closes <main>, <body>, <html>.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
</main><!-- /#aifirst-main -->

<footer class="aifirst-site-footer" role="contentinfo">
	<div class="aifirst-footer-inner">

		<section class="aifirst-newsletter" aria-labelledby="aifirst-newsletter-title">
			<div class="aifirst-newsletter__copy">
				<div id="aifirst-newsletter-title" class="aifirst-newsletter__title"><?php esc_html_e( 'Get weekly AI feature updates', 'aifirstsaas' ); ?></div>
				<div class="aifirst-newsletter__sub"><?php esc_html_e( 'New features, plan changes, and maturity shifts — every Friday.', 'aifirstsaas' ); ?></div>
			</div>
			<form class="aifirst-newsletter__form" data-aifirst-newsletter>
				<label class="aifirst-sr" for="aifirst-newsletter-email"><?php esc_html_e( 'Email address', 'aifirstsaas' ); ?></label>
				<input id="aifirst-newsletter-email" type="email" name="email" required placeholder="you@company.com">
				<input type="text" name="hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;">
				<input type="hidden" name="source" value="footer">
				<button type="submit"><?php esc_html_e( 'Subscribe', 'aifirstsaas' ); ?></button>
				<span class="aifirst-newsletter__status" data-aifirst-newsletter-status role="status" aria-live="polite"></span>
			</form>
		</section>

		<div class="aifirst-footer-grid">
			<div class="aifirst-footer-col aifirst-footer-brand">
				<a class="aifirst-footer-wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>">aifirstsaas</a>
				<div class="aifirst-footer-tagline"><?php esc_html_e( 'Verified AI feature data for every SaaS tool your team uses.', 'aifirstsaas' ); ?></div>
			</div>

			<div class="aifirst-footer-col">
				<h2 class="aifirst-footer-heading"><?php esc_html_e( 'Explore', 'aifirstsaas' ); ?></h2>
				<?php aifirstsaas_footer_menu( 'footer-explore', array(
					array( 'label' => __( 'Tools', 'aifirstsaas' ),      'url' => home_url( '/tools/' ) ),
					array( 'label' => __( 'Categories', 'aifirstsaas' ), 'url' => home_url( '/tools/' ) ),
					array( 'label' => __( 'Features', 'aifirstsaas' ),   'url' => home_url( '/features/' ) ),
					array( 'label' => __( 'Compare', 'aifirstsaas' ),    'url' => home_url( '/compare/' ) ),
				) ); ?>
			</div>

			<div class="aifirst-footer-col">
				<h2 class="aifirst-footer-heading"><?php esc_html_e( 'Learn', 'aifirstsaas' ); ?></h2>
				<?php aifirstsaas_footer_menu( 'footer-learn', array(
					array( 'label' => __( 'Radar', 'aifirstsaas' ), 'url' => home_url( '/changelog/' ) ),
					array( 'label' => __( 'Blog', 'aifirstsaas' ),  'url' => home_url( '/blog/' ) ),
					array( 'label' => __( 'FAQ', 'aifirstsaas' ),   'url' => home_url( '/faq/' ) ),
				) ); ?>
			</div>

			<div class="aifirst-footer-col">
				<h2 class="aifirst-footer-heading"><?php esc_html_e( 'About', 'aifirstsaas' ); ?></h2>
				<?php aifirstsaas_footer_menu( 'footer-about', array(
					array( 'label' => __( 'About', 'aifirstsaas' ),        'url' => home_url( '/about/' ) ),
					array( 'label' => __( 'Submit a tool', 'aifirstsaas' ),'url' => home_url( '/submit/' ) ),
					array( 'label' => __( 'Contact', 'aifirstsaas' ),      'url' => home_url( '/contact/' ) ),
				) ); ?>
			</div>

			<div class="aifirst-footer-col">
				<h2 class="aifirst-footer-heading"><?php esc_html_e( 'Legal', 'aifirstsaas' ); ?></h2>
				<?php aifirstsaas_footer_menu( 'footer-legal', array(
					array( 'label' => __( 'Privacy', 'aifirstsaas' ), 'url' => home_url( '/privacy/' ) ),
					array( 'label' => __( 'Terms', 'aifirstsaas' ),   'url' => home_url( '/terms/' ) ),
				) ); ?>
			</div>
		</div>

		<div class="aifirst-footer-bottom">
			<div class="aifirst-copyright">
				<?php
				printf(
					/* translators: %s: current year */
					esc_html__( '© %s aifirstsaas.com', 'aifirstsaas' ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</div>
			<div class="aifirst-social">
				<?php foreach ( aifirstsaas_social_links() as $s ) : ?>
					<a href="<?php echo esc_url( $s['url'] ); ?>" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
						<?php echo $s['svg']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
