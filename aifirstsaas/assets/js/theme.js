/**
 * AI First SaaS theme — client runtime.
 *
 * Provides two things:
 *   1. The Alpine `AIFirstDrawer` factory used by the topbar/burger.
 *   2. A vanilla newsletter-form handler that POSTs to the plugin's
 *      admin-ajax action `aifirst_subscribe`.
 *
 * @since 1.0.0
 */
( function () {
	'use strict';

	window.AIFirstDrawer = function () {
		return {
			open: false,
			toggle: function () { this.open = ! this.open; this.syncBody(); },
			close:  function () { if ( this.open ) { this.open = false; this.syncBody(); } },
			syncBody: function () {
				// Lock the page behind the drawer on mobile only.
				if ( this.open && window.matchMedia( '(max-width: 1024px)' ).matches ) {
					document.body.style.overflow = 'hidden';
				} else {
					document.body.style.overflow = '';
				}
			}
		};
	};

	// Newsletter subscribe — delegated to document so the form works in
	// footer.php or anywhere else a theme page drops `[data-aifirst-newsletter]`.
	document.addEventListener( 'submit', function ( ev ) {
		var form = ev.target;
		if ( ! form || ! form.hasAttribute || ! form.hasAttribute( 'data-aifirst-newsletter' ) ) {
			return;
		}
		ev.preventDefault();

		var cfg    = window.AIFirstSaaSTheme || {};
		var status = form.querySelector( '[data-aifirst-newsletter-status]' );
		var btn    = form.querySelector( 'button[type="submit"]' );
		var email  = ( form.email && form.email.value || '' ).trim();
		var hp     = form.hp ? form.hp.value : '';
		var source = form.source ? form.source.value : 'footer';

		if ( ! email ) {
			setStatus( status, cfg.i18n && cfg.i18n.err || 'A valid email is required.', 'err' );
			return;
		}

		if ( btn ) {
			btn.disabled = true;
			btn.dataset.origLabel = btn.textContent;
			if ( cfg.i18n && cfg.i18n.subscribing ) { btn.textContent = cfg.i18n.subscribing; }
		}
		setStatus( status, '', '' );

		var body = new URLSearchParams();
		body.set( 'action', cfg.subAction || 'aifirst_subscribe' );
		body.set( 'aifirst_subscribe_nonce', cfg.subNonce || '' );
		body.set( 'email', email );
		body.set( 'scope', 'all' );
		body.set( 'source', source );
		if ( hp ) { body.set( 'hp', hp ); }

		fetch( cfg.ajaxUrl, {
			method:      'POST',
			credentials: 'same-origin',
			headers:     { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body:        body.toString()
		} )
		.then( function ( res ) { return res.json().catch( function () { return { success: false }; } ); } )
		.then( function ( data ) {
			if ( data && data.success ) {
				setStatus( status, ( data.data && data.data.message ) || ( cfg.i18n && cfg.i18n.ok ) || 'Subscribed.', 'ok' );
				form.reset();
			} else {
				setStatus( status, ( data && data.data && data.data.message ) || ( cfg.i18n && cfg.i18n.err ) || 'Try again.', 'err' );
			}
		} )
		.catch( function () {
			setStatus( status, cfg.i18n && cfg.i18n.err || 'Try again.', 'err' );
		} )
		.finally( function () {
			if ( btn ) {
				btn.disabled = false;
				btn.textContent = btn.dataset.origLabel || ( cfg.i18n && cfg.i18n.subscribe ) || 'Subscribe';
			}
		} );
	} );

	function setStatus( el, msg, tone ) {
		if ( ! el ) { return; }
		el.textContent = msg || '';
		el.classList.remove( 'aifirst-newsletter__status--ok', 'aifirst-newsletter__status--err' );
		if ( 'ok'  === tone ) { el.classList.add( 'aifirst-newsletter__status--ok' ); }
		if ( 'err' === tone ) { el.classList.add( 'aifirst-newsletter__status--err' ); }
	}
} )();
