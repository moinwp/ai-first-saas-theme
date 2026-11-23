# AI First SaaS — theme notes

Version 1.0.0. Pairs with `aifirstsaas-directory` plugin v0.8.3.

## What this theme ships

Classic PHP theme (not a block theme), text-domain `aifirstsaas`, folder
`aifirstsaas/`. Provides the chrome around the plugin's content
templates:

- `style.css` — theme header, base reset, `--aif-*` and bare `--bg /
  --bg2 / --card / --text / --sub / --border / --accent` tokens, light
  and dark palettes.
- `functions.php` — theme supports (title-tag, HTML5, post-thumbnails,
  responsive-embeds, custom-logo, automatic-feed-links), six nav menu
  locations (`primary`, `mobile`, `footer-explore`, `footer-learn`,
  `footer-about`, `footer-legal`), asset enqueue (Inter, theme CSS,
  Alpine when the plugin isn't loading it, theme JS with
  `AIFirstSaaSTheme` localisation), `has-theme-header-toggle` body
  class, footer-menu fallbacks.
- `header.php` — `<html>`/`<head>`/`<body>`, sticky topbar (logo /
  wordmark, desktop nav, desktop search, dark-mode toggle, burger),
  mobile drawer (search + nav, hidden by default), opens
  `<main id="aifirst-main" class="aifirst-main">`.
- `footer.php` — newsletter block, four-column grid, bottom row
  (copyright + socials), closes `<main>`.
- `index.php`, `page.php`, `search.php`, `404.php` — fallbacks for
  anything the plugin doesn't route.
- `assets/js/theme.js` — Alpine `AIFirstDrawer` factory and a
  delegated `[data-aifirst-newsletter]` form handler.
- `screenshot.png` — 1200×900 placeholder.

## Integration points verified against the plugin

| Assumption in spec | Reality in plugin (v0.8.3) | What the theme does |
|---|---|---|
| Plugin reads `--bg / --bg2 / --card / --text / --sub / --border / --accent` too | **Not quite.** `assets/css/public.css` only reads `--aif-*`. The bare names are used only by the canonical design file. | Theme defines **both** families on `:root` (and under `[data-theme="dark"]` + the dark media query) so plugin templates and any inline design-file-style markup share one palette. |
| Newsletter POSTs to `/wp-json/aifirst/v1/subscribe` | **No such REST route.** `AIFirst_REST` exposes `/aifirst/v1/tool` and `/aifirst/v1/home-section/{section}`. Newsletter is `admin-ajax.php?action=aifirst_subscribe` with nonce name `aifirst_subscribe` (field `aifirst_subscribe_nonce`), honeypot `hp`, `email`, `scope`, `source` — see `includes/class-aifirst-subscribers.php`. | `theme.js` POSTs to `admin-ajax.php` with the correct payload, using a nonce minted in `functions.php`. |
| `AIFirst_Components::stack_badge()` exists | **Method is `stack_header_badge()`**. Also — the plugin already injects it from `wp_footer` on plugin-owned screens (see `AIFirst_Public::render_stack_badge`). | Theme does **not** render the badge. We rely on the plugin's own footer injection, which keeps the floating position the design calls for. |
| Hide plugin's floating toggle via `.aifirst-theme-toggle--float` | The plugin uses the **base** class `aifirst-theme-toggle` for its floating pill (no `--float` modifier). | Theme adds `body.has-theme-header-toggle` always, and a CSS rule hides the floating one: `body.has-theme-header-toggle > .aifirst-theme-toggle { display: none; }`. The theme's own toggle lives inside `<header>` so the child combinator (`>`) only matches the plugin's body-level pill. The header one uses the same class and binds the same `AIFirstThemeToggle` Alpine factory, so the plugin's FOUC-prevention script and three-state cycle work unchanged. |
| Plugin ships Alpine | Only on plugin-owned screens. | Theme enqueues Alpine under the same `alpinejs` handle only when it isn't already registered; needed so the burger/drawer work on theme-only screens (home when `aifirst_own_home` is off, pages, blog, search, 404). |

## Deliberate deviations

1. **Header dark-mode button is visible on ALL pages (plugin and
   theme).** The plugin's floating pill is suppressed via
   `has-theme-header-toggle`; the header one uses the plugin's
   `AIFirstThemeToggle` factory, so behaviour (System → Light → Dark,
   `localStorage['aifirst_theme']`, `data-theme` on `<html>`) is
   identical.
2. **Theme-owned `AIFirstDrawer` Alpine factory lives in
   `assets/js/theme.js`**, not in the plugin — the plugin has no
   concept of a drawer.
3. **No front-page template is shipped.** `templates/public/front-page.php`
   is routed by the plugin's `template_include` when
   `get_option( 'aifirst_own_home' )` is on; if the owner hasn't opted
   in, WP falls through to `index.php`, which is the correct fallback.
4. **Default primary-nav fallback** renders a flat list of seven items
   (Tools, Categories, Features, Compare, Radar, Blog, Submit) when
   no `primary` menu is assigned — matches the design's nav labels.
   The plugin injects its own `Submit` item via `wp_nav_menu_items`
   on menus registered under locations named like `primary`,
   `primary-menu`, `main`, `main-menu`, `menu-1`, `header` or `top`;
   this theme uses `primary` so the injection fires once the admin
   builds a menu.

## Acceptance walk — expected outcomes

| URL | Desktop ≥1024px | Phone ≤1024px |
|---|---|---|
| `/` | Topbar: wordmark, 7-item default nav or admin menu, pill search, theme toggle. Front-page template from the plugin when `aifirst_own_home` is on; else WP falls back to `index.php` and lists posts. | Topbar: wordmark, theme toggle, burger. Drawer opens with search input + nav. |
| `/tools/` | Plugin's `archive-aifirst_tool.php` renders inside the theme chrome. | Same; plugin's own filter-sidebar toggle (new in v0.8.3) handles the filter rail. |
| `/tools/<slug>/` | Plugin's `single-aifirst_tool.php` renders; theme header and footer frame it. | Content stacks per the plugin's rules; drawer still works. |
| `/?s=jasper` | Theme's `search.php` lists results. | Same on mobile. |
| Any missing URL | Theme's `404.php` with a search input. | Same on mobile. |

## Not shipped (plugin territory)

`single-aifirst_tool.php`, `archive-aifirst_tool.php`,
`taxonomy-aifirst_category.php`, `taxonomy-aifirst_feature.php`,
`compare.php`, `stack.php`, `submit.php`, `changelog.php`,
`features-index.php`, `front-page.php`. All routed by
`AIFirst_Public::template_include`.

## Deferred

- Translation `.pot` is not included (text-domain is loaded; add a
  `languages/` POT later with WP-CLI).
- Customizer controls for social links and the footer tagline — hard-coded
  for v1.0.0; move to Customizer when they need per-site editing.
- `comments.php` — only reached by `index.php`/`page.php` when comments
  are open on a post; WP's default renders. Bespoke theming can be
  added when the blog gains traction.
