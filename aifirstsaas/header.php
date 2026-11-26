<?php
/**
 * Header template.
 *
 * Renders <html>, <head>, the topbar (logo, desktop nav, search,
 * dark-mode toggle, burger) and the mobile drawer. Opens <main> which
 * footer.php closes.
 *
 * @package AIFirstSaaS
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="aifirst-skip-link" href="#aifirst-main"><?php esc_html_e( 'Skip to content', 'aifirstsaas' ); ?></a>

<header class="aifirst-topbar" x-data="AIFirstDrawer()" @keydown.escape.window="close()">
	<div class="aifirst-topbar-inner">
		<div class="aifirst-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo aifirstsaas_logo_mark( 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php echo aifirstsaas_wordmark(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="aifirst-topbar-right">
			<nav class="aifirst-nav aifirst-desktop-nav" aria-label="<?php esc_attr_e( 'Primary', 'aifirstsaas' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'depth'          => 1,
						'menu_class'     => 'aifirst-nav-list',
						'fallback_cb'    => 'aifirstsaas_default_primary_nav',
					) );
				} else {
					aifirstsaas_default_primary_nav();
				}
				?>
			</nav>

			<form class="aifirst-topbar-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="aifirst-sr" for="aifirst-search-desktop"><?php esc_html_e( 'Search', 'aifirstsaas' ); ?></label>
				<input id="aifirst-search-desktop" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search a tool or feature…', 'aifirstsaas' ); ?>" autocomplete="off">
			</form>

			<?php
			// The plugin ships the Alpine factory `AIFirstThemeToggle`.
			// We just render a <button> that binds it. The factory
			// toggles `data-theme` on <html> and persists to
			// localStorage[aifirst_theme]; the plugin also prints a
			// FOUC-prevention <script> in <head> on its own screens.
			$toggle_cfg = array(
				'systemLabel' => __( '☾ System', 'aifirstsaas' ),
				'lightLabel'  => __( '☀ Light', 'aifirstsaas' ),
				'darkLabel'   => __( '☽ Dark', 'aifirstsaas' ),
			);
			?>
			<button type="button" class="aifirst-theme-toggle"
				x-data="AIFirstThemeToggle(<?php echo esc_attr( wp_json_encode( $toggle_cfg ) ); ?>)"
				x-init="init()"
				@click="cycle()"
				x-text="label"
				:aria-label="label"
				:title="label"><?php echo esc_html( $toggle_cfg['systemLabel'] ); ?></button>

			<button type="button" class="aifirst-burger"
				@click="toggle()"
				:aria-expanded="open.toString()"
				aria-controls="aifirst-drawer"
				:aria-label="open ? '<?php echo esc_js( __( 'Close menu', 'aifirstsaas' ) ); ?>' : '<?php echo esc_js( __( 'Open menu', 'aifirstsaas' ) ); ?>'">
				<span x-text="open ? '✕' : '☰'" aria-hidden="true">☰</span>
				<span class="aifirst-sr"><?php esc_html_e( 'Menu', 'aifirstsaas' ); ?></span>
			</button>
		</div>
	</div>

	<div id="aifirst-drawer" class="aifirst-drawer" x-show="open" x-cloak x-transition.opacity>
		<div class="aifirst-drawer-inner">
			<form class="aifirst-drawer-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="aifirst-sr" for="aifirst-search-mobile"><?php esc_html_e( 'Search', 'aifirstsaas' ); ?></label>
				<input id="aifirst-search-mobile" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search a tool or feature…', 'aifirstsaas' ); ?>" autocomplete="off">
			</form>

			<nav class="aifirst-drawer-nav" aria-label="<?php esc_attr_e( 'Mobile', 'aifirstsaas' ); ?>" @click="close()">
				<?php
				if ( has_nav_menu( 'mobile' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'mobile',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
						'items_wrap'     => '<ul>%3$s</ul>',
					) );
				} elseif ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => 'aifirstsaas_default_primary_nav',
						'items_wrap'     => '<ul>%3$s</ul>',
					) );
				} else {
					aifirstsaas_default_primary_nav();
				}
				?>
			</nav>
		</div>
	</div>
</header>

<main id="aifirst-main" class="aifirst-main" role="main">
