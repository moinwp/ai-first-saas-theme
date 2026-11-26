<?php
/**
 * AI First SaaS theme — bootstrap.
 *
 * Classic PHP theme that provides the chrome around the AI First SaaS
 * Directory plugin. All content templates live in the plugin; this
 * theme owns the header, drawer, footer and color tokens.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'AIFIRSTSAAS_THEME_VERSION', '1.0.1' );

if ( ! function_exists( 'aifirstsaas_setup' ) ) {
	/**
	 * Theme supports and nav menu locations.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function aifirstsaas_setup() {
		load_theme_textdomain( 'aifirstsaas', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);
		add_theme_support( 'custom-logo', array(
			'height'      => 32,
			'width'       => 160,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		add_theme_support( 'responsive-embeds' );

		register_nav_menus( array(
			'primary'        => __( 'Primary (header)', 'aifirstsaas' ),
			'mobile'         => __( 'Mobile drawer', 'aifirstsaas' ),
			'footer-explore' => __( 'Footer — Explore', 'aifirstsaas' ),
			'footer-learn'   => __( 'Footer — Learn', 'aifirstsaas' ),
			'footer-about'   => __( 'Footer — About', 'aifirstsaas' ),
			'footer-legal'   => __( 'Footer — Legal', 'aifirstsaas' ),
		) );
	}
}
add_action( 'after_setup_theme', 'aifirstsaas_setup' );

/**
 * Enqueue theme styles and scripts.
 *
 * The plugin owns `aifirst-public` CSS, `aifirst-inter` font, Alpine
 * and `aifirst-public` JS on its own screens. On non-plugin screens
 * (home when the plugin isn't opted in, pages, blog, search, 404) the
 * theme still needs Inter and Alpine for its header/drawer, so it
 * enqueues them under the same handles — WP dedupes by handle.
 *
 * @since 1.0.0
 * @return void
 */
function aifirstsaas_enqueue_assets() {
	// Inter + Space Grotesk (wordmark font from the Stack Logo System).
	wp_enqueue_style(
		'aifirst-inter',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap',
		array(),
		null
	);

	// Theme stylesheet.
	wp_enqueue_style(
		'aifirstsaas-theme',
		get_stylesheet_uri(),
		array( 'aifirst-inter' ),
		AIFIRSTSAAS_THEME_VERSION
	);

	// Alpine — only register if the plugin hasn't already. Same handle.
	if ( ! wp_script_is( 'alpinejs', 'registered' ) && ! wp_script_is( 'alpinejs', 'enqueued' ) ) {
		wp_enqueue_script(
			'alpinejs',
			'https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js',
			array(),
			'3.14.1',
			true
		);
		add_filter( 'script_loader_tag', 'aifirstsaas_defer_alpine', 10, 2 );
	}

	// Theme JS — drawer Alpine factory + newsletter form handler.
	wp_enqueue_script(
		'aifirstsaas-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		AIFIRSTSAAS_THEME_VERSION,
		true
	);
	wp_localize_script(
		'aifirstsaas-theme',
		'AIFirstSaaSTheme',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'subAction' => 'aifirst_subscribe',
			'subNonce'  => wp_create_nonce( 'aifirst_subscribe' ),
			'i18n'      => array(
				'subscribing' => __( 'Subscribing…', 'aifirstsaas' ),
				'subscribe'   => __( 'Subscribe', 'aifirstsaas' ),
				'ok'          => __( '✓ Subscribed — check your inbox.', 'aifirstsaas' ),
				'err'         => __( 'Sorry — that did not work. Try again?', 'aifirstsaas' ),
				'closeMenu'   => __( 'Close menu', 'aifirstsaas' ),
				'openMenu'    => __( 'Open menu', 'aifirstsaas' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aifirstsaas_enqueue_assets' );

/**
 * Add `defer` to Alpine when the theme is the one registering it.
 *
 * @since 1.0.0
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function aifirstsaas_defer_alpine( $tag, $handle ) {
	if ( 'alpinejs' === $handle && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}

/**
 * Mark the body so the plugin's floating theme-toggle pill is hidden.
 *
 * Also exposes a `has-plugin-templates` class on plugin-owned screens.
 *
 * @since 1.0.0
 * @param string[] $classes Body classes.
 * @return string[]
 */
function aifirstsaas_body_classes( $classes ) {
	$classes[] = 'has-theme-header-toggle';

	if ( is_singular( array( 'aifirst_tool', 'aifirst_comparison' ) )
		|| is_post_type_archive( 'aifirst_tool' )
		|| is_tax( array( 'aifirst_category', 'aifirst_feature' ) ) ) {
		$classes[] = 'has-plugin-templates';
	}

	return $classes;
}
add_filter( 'body_class', 'aifirstsaas_body_classes' );

/**
 * Default primary-nav items so the theme is usable before the admin
 * builds a menu in Appearance → Menus. Renders a flat <ul> matching
 * the markup wp_nav_menu would emit, so styling stays consistent.
 *
 * @since 1.0.0
 * @return void
 */
function aifirstsaas_default_primary_nav() {
	$items = array(
		'tools'      => array( 'label' => __( 'Tools', 'aifirstsaas' ),      'url' => home_url( '/tools/' ) ),
		'categories' => array( 'label' => __( 'Categories', 'aifirstsaas' ), 'url' => home_url( '/categories/' ) ),
		'features'   => array( 'label' => __( 'Features', 'aifirstsaas' ),   'url' => home_url( '/features/' ) ),
		'compare'    => array( 'label' => __( 'Compare', 'aifirstsaas' ),    'url' => home_url( '/compare/' ) ),
		'radar'      => array( 'label' => __( 'Radar', 'aifirstsaas' ),      'url' => home_url( '/changelog/' ) ),
		'blog'       => array( 'label' => __( 'Blog', 'aifirstsaas' ),       'url' => home_url( '/blog/' ) ),
		'submit'     => array( 'label' => __( 'Submit', 'aifirstsaas' ),     'url' => home_url( '/submit/' ) ),
	);
	echo '<ul class="aifirst-nav-list">';
	foreach ( $items as $key => $it ) {
		printf(
			'<li class="menu-item menu-item-%1$s"><a href="%2$s">%3$s</a></li>',
			esc_attr( $key ),
			esc_url( $it['url'] ),
			esc_html( $it['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Social links for the footer bottom row. SVG inline so the icons
 * inherit currentColor. Add/remove entries in Customizer later.
 *
 * @since 1.0.0
 * @return array<int,array{label:string,url:string,svg:string}>
 */
function aifirstsaas_social_links() {
	return array(
		array(
			'label' => 'X',
			'url'   => 'https://x.com/aifirstsaas',
			'svg'   => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231L18.244 2.25Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>',
		),
		array(
			'label' => 'GitHub',
			'url'   => 'https://github.com/aifirstsaas',
			'svg'   => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M12 .5C5.73.5.98 5.24.98 11.52c0 4.86 3.15 8.98 7.52 10.44.55.1.75-.24.75-.53v-1.85c-3.06.67-3.71-1.47-3.71-1.47-.5-1.28-1.23-1.62-1.23-1.62-1.01-.69.08-.68.08-.68 1.12.08 1.71 1.15 1.71 1.15.99 1.7 2.6 1.21 3.24.93.1-.72.39-1.21.71-1.49-2.44-.28-5.01-1.22-5.01-5.45 0-1.21.43-2.19 1.14-2.96-.11-.28-.49-1.41.11-2.94 0 0 .94-.3 3.07 1.13.89-.25 1.85-.37 2.8-.37s1.91.13 2.8.37c2.13-1.43 3.07-1.13 3.07-1.13.6 1.53.22 2.66.11 2.94.71.77 1.14 1.75 1.14 2.96 0 4.24-2.58 5.17-5.03 5.44.4.34.76 1.02.76 2.06v3.05c0 .3.2.65.76.53 4.37-1.46 7.52-5.58 7.52-10.44C23.02 5.24 18.27.5 12 .5Z"/></svg>',
		),
		array(
			'label' => 'RSS',
			'url'   => home_url( '/feed/' ),
			'svg'   => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M6.18 15.64a2.18 2.18 0 1 1 0 4.36 2.18 2.18 0 0 1 0-4.36ZM4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44Zm0 5.66A9.9 9.9 0 0 1 13.9 20h-2.83A7.07 7.07 0 0 0 4 12.93V10.1Z"/></svg>',
		),
	);
}

/**
 * Stacked-cards logo mark (SVG). Uses CSS custom properties for fills
 * so dark-mode inverts automatically via the --logo-* tokens.
 *
 * @since 1.0.1
 * @param int $size Width/height in px (default 28 for the header).
 * @return string Inline SVG markup.
 */
function aifirstsaas_logo_mark( $size = 28 ) {
	return '<svg class="aifirst-logo-mark" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
		. '<rect x="8" y="46" width="52" height="26" rx="7" fill="var(--logo-card-bottom)"/>'
		. '<rect x="14" y="32" width="52" height="26" rx="7" fill="var(--logo-card-middle)"/>'
		. '<rect x="20" y="18" width="52" height="26" rx="7" fill="var(--logo-card-top)"/>'
		. '<path d="M60 6 C61.4 18,65 21.6,77 23 C65 24.4,61.4 28,60 40 C58.6 28,55 24.4,43 23 C55 21.6,58.6 18,60 6Z" fill="var(--logo-spark)"/>'
		. '</svg>';
}

/**
 * Styled wordmark "AIFirstSaaS" with the accent span on "First".
 *
 * @since 1.0.1
 * @return string HTML markup.
 */
function aifirstsaas_wordmark() {
	return '<span class="aifirst-wordmark">AI<span class="aifirst-wm-accent">First</span>SaaS</span>';
}

/**
 * Render the nav list for a theme location, with a sensible fallback.
 *
 * @since 1.0.0
 * @param string $location Theme location.
 * @param array  $fallback  Fallback items [label,url].
 * @return void
 */
function aifirstsaas_footer_menu( $location, $fallback ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array(
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
			'items_wrap'     => '<ul>%3$s</ul>',
		) );
		return;
	}
	echo '<ul>';
	foreach ( $fallback as $it ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $it['url'] ),
			esc_html( $it['label'] )
		);
	}
	echo '</ul>';
}
