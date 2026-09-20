<?php
/**
 * Travex — thème WordPress qui affiche le site HTML.
 *
 * Apparence → Personnaliser → Travex : adresse e-mail des devis.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('DONOTCACHEPAGE')) {
    define('DONOTCACHEPAGE', true);
}
if (!defined('LSCACHE_NO_CACHE')) {
    define('LSCACHE_NO_CACHE', true);
}

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/chrome.php';
require get_template_directory() . '/inc/admin.php';
require get_template_directory() . '/inc/pages.php';
require get_template_directory() . '/inc/render.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
});

add_action('after_switch_theme', function () {
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    update_option('show_on_front', 'posts');
    gondrand_disable_root_html();
    if (function_exists('gondrand_touch_bust')) {
        gondrand_touch_bust();
    }
    $title = get_theme_mod('gondrand_loc_title', '');
    if (!is_string($title) || $title === '' || stripos($title, 'gondrand') !== false) {
        set_theme_mod('gondrand_loc_title', 'TRAVEX GLOBAL FORWARDING EMPLACEMENTS');
    }
    $logo = get_theme_mod('gondrand_logo', '');
    if (is_string($logo) && ($logo === '' || stripos($logo, 'gondrand') !== false)) {
        remove_theme_mod('gondrand_logo');
    }
    flush_rewrite_rules();
});

add_action('admin_init', function () {
    gondrand_disable_root_html();
    $title = get_theme_mod('gondrand_loc_title', '');
    if (is_string($title) && stripos($title, 'gondrand') !== false) {
        set_theme_mod('gondrand_loc_title', 'TRAVEX GLOBAL FORWARDING EMPLACEMENTS');
    }
    $logo = get_theme_mod('gondrand_logo', '');
    if (is_string($logo) && stripos($logo, 'gondrand') !== false) {
        remove_theme_mod('gondrand_logo');
    }
    if (isset($_GET['gondrand_purge']) && current_user_can('edit_theme_options')) {
        check_admin_referer('gondrand_purge');
        gondrand_touch_bust();
        $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : 'gondrand-content';
        if ($page !== 'gondrand-pages') {
            $page = 'gondrand-content';
        }
        wp_safe_redirect(admin_url('admin.php?page=' . $page . '&purged=1'));
        exit;
    }
});

function gondrand_purge_button() {
    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : 'gondrand-content';
    if ($page !== 'gondrand-pages') {
        $page = 'gondrand-content';
    }
    $url = wp_nonce_url(admin_url('admin.php?page=' . $page . '&gondrand_purge=1'), 'gondrand_purge');
    echo '<p><a class="button button-primary" href="' . esc_url($url) . '">Purger tout le cache (PC + Android)</a> ';
    echo '<span class="description">À cliquer après une modification si le téléphone n’affiche pas le changement.</span></p>';
}

add_action('template_redirect', function () {
    if (function_exists('do_action')) {
        do_action('litespeed_control_set_nocache', 'travex-html');
    }
}, 1);
add_action('template_redirect', 'gondrand_try_serve', 20);

function gondrand_bust() {
    $v = get_option('gondrand_bust', '');
    return $v !== '' ? (string) $v : '238';
}

function gondrand_purge_caches() {
    if (has_action('litespeed_purge_all')) {
        do_action('litespeed_purge_all');
    }
    if (has_action('litespeed_purge_cssjs')) {
        do_action('litespeed_purge_cssjs');
    }
    if (has_action('litespeed_purge_url')) {
        do_action('litespeed_purge_url', home_url('/'));
    }
    if (class_exists('LiteSpeed\\Purge') && method_exists('LiteSpeed\\Purge', 'purge_all')) {
        \LiteSpeed\Purge::purge_all();
    }
}

function gondrand_touch_bust() {
    update_option('gondrand_bust', (string) time(), false);
    gondrand_purge_caches();
}

function gondrand_asset($rel) {
    $rel = ltrim((string) $rel, '/');
    return gondrand_assets() . $rel . '?ver=' . rawurlencode(gondrand_bust());
}

function gondrand_bust_html($html) {
    $ver = rawurlencode(gondrand_bust());
    $html = preg_replace('#(css/style\.css)(\?ver=[^"\']*)?#', '$1?ver=' . $ver, $html);
    $html = preg_replace('#(js/main\.js)(\?ver=[^"\']*)?#', '$1?ver=' . $ver, $html);
    return is_string($html) ? $html : $html;
}

function gondrand_request_path() {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (!is_string($uri) || $uri === '') {
        $uri = '/';
    }
    $home = parse_url(home_url('/'), PHP_URL_PATH);
    if (is_string($home) && $home !== '/' && strpos($uri, rtrim($home, '/')) === 0) {
        $uri = substr($uri, strlen(rtrim($home, '/')));
        if ($uri === '' || $uri[0] !== '/') {
            $uri = '/' . ltrim($uri, '/');
        }
    }
    if (preg_match('#^/index\.php(/.*)$#', $uri, $m)) {
        $uri = $m[1];
    }
    return rawurldecode($uri);
}

function gondrand_is_wp_path($path) {
    return (bool) preg_match(
        '#^/(wp-admin|wp-includes|wp-content|wp-json|wp-login|wp-cron|xmlrpc)(/|$)#',
        $path
    );
}

function gondrand_try_serve() {
    static $done = false;
    if ($done || is_admin() || wp_doing_cron() || wp_doing_ajax()) {
        return;
    }
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return;
    }

    $path = gondrand_request_path();
    if (gondrand_is_wp_path($path)) {
        return;
    }

    if ($path === '/send.php') {
        $done = true;
        gondrand_handle_mail();
        exit;
    }

    if ($path === '/travex-bust.json' || $path === '/travex-bust.json/') {
        $done = true;
        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('X-LiteSpeed-Cache-Control: no-cache');
        echo wp_json_encode(['bust' => gondrand_bust()]);
        exit;
    }

    if ($path === '/' || $path === '/index.html' || $path === '/index.php' || $path === '') {
        $done = true;
        gondrand_render_home();
        return;
    }

    $site = get_template_directory() . '/site';
    $rel  = $path === '/' ? '/index.html' : $path;
    $candidate = $site . $rel;

    if (is_dir($candidate)) {
        $candidate = rtrim($candidate, '/') . '/index.html';
    }

    $real_site = realpath($site);
    $real_file = realpath($candidate);
    if (!$real_site || !$real_file || strpos($real_file, $real_site) !== 0 || !is_file($real_file)) {
        return;
    }

    $ext = strtolower(pathinfo($real_file, PATHINFO_EXTENSION));
    if ($ext === 'php') {
        return;
    }

    if ($ext === 'html' && function_exists('gondrand_path_to_slug') && function_exists('gondrand_page_is_hidden')) {
        $slug = gondrand_path_to_slug($path);
        if (gondrand_page_is_hidden($slug)) {
            $done = true;
            wp_safe_redirect(home_url('/'));
            exit;
        }
    }

    $done = true;

    $mimes = [
        'html' => 'text/html; charset=UTF-8',
        'css'  => 'text/css; charset=UTF-8',
        'js'   => 'application/javascript; charset=UTF-8',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'json' => 'application/json',
        'txt'  => 'text/plain; charset=UTF-8',
    ];

    status_header(200);
    nocache_headers();
    header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));

    if ($ext === 'html') {
        header('X-Gondrand-Theme: 2.2.19');
        header('X-LiteSpeed-Cache-Control: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
        $html = file_get_contents($real_file);
        $html = preg_replace('#<div class="dl-banner">.*?</div>#s', '', $html);
        if (function_exists('gondrand_apply_saved_body')) {
            $html = gondrand_apply_saved_body($html, $path);
        }
        $html = gondrand_absolutize_assets($html);
        $html = gondrand_inject_chrome($html, $path);
        $html = gondrand_apply_content($html, $path);
        $html = gondrand_bust_html($html);
        $html = str_replace('</head>', gondrand_head_inject() . "\n</head>", $html);
        $html = str_replace('</body>', gondrand_footer_inject() . "\n</body>", $html);
        echo $html;
        exit;
    }

    if (in_array($ext, ['css', 'js'], true)) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('X-LiteSpeed-Cache-Control: no-cache');
    }
    readfile($real_file);
    exit;
}

function gondrand_mod($key) {
    $v = get_theme_mod($key, '');
    return is_string($v) ? trim($v) : '';
}

function gondrand_quote_defaults() {
    return [
        'quote_title' => 'Demander un devis gratuit',
        'quote_intro' => 'Travex Global Forwarding est à votre service pour trouver la meilleure solution concernant la logistique et le transport de vos marchandises.',
        'transport_type' => 'Type de transport',
        'transport_type_ph' => 'Type de Transport',
        'warehousing' => 'Entreposage',
        'air_t' => 'Transport aérien',
        'sea_t' => 'Transport maritime',
        'multi_t' => 'Transport multimodal',
        'road_t' => 'Transport terrestre',
        'incoterms' => 'Conditions de vente',
        'from_city' => 'Ville de départ',
        'to_city' => "Ville d'arrivée",
        'weight' => 'Poids (kg)',
        'email' => 'E-mail',
        'quote_company' => 'Société',
        'quote_phone' => 'Téléphone',
        'quote_contact' => 'Contact',
        'quote_from' => 'Départ',
        'quote_to' => 'Arrivée',
        'quote_from_country' => 'Pays (départ)',
        'quote_to_country' => 'Pays (arrivée)',
        'quote_client' => 'Données du client',
        'quote_pack' => 'Colis',
        'quote_parcel' => 'Type de colis',
        'quote_dimensions' => 'Dimensions (L × l × H)',
        'quote_dangerous' => 'Marchandise dangereuse',
        'quote_comments' => 'Commentaires',
        'privacy_ok' => 'Politique de confidentialité acceptée.',
        'quote_ext' => 'Pour une version étendue du formulaire, cliquez ici.',
        'click_here' => 'cliquez ici',
        'send' => 'Envoyer →',
        'quote_ok' => 'Votre demande a bien été transmise. Un commercial Travex Global Forwarding vous répondra dans les plus brefs délais.',
        'nav_quote' => 'Devis',
    ];
}

function gondrand_quote_list($key, $fallback) {
    $v = gondrand_mod($key);
    if ($v === '') {
        $v = $fallback;
    }
    $lines = preg_split('/\R/u', $v);
    $out = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

function gondrand_cms_payload() {
    $cms = [];
    $mods = get_theme_mods();
    if (!is_array($mods)) {
        $mods = [];
    }
    foreach ($mods as $key => $value) {
        if (!is_string($key) || strpos($key, 'gondrand_i18n_') !== 0) {
            continue;
        }
        if (!is_string($value) || trim($value) === '') {
            continue;
        }
        $cms[substr($key, strlen('gondrand_i18n_'))] = $value;
    }
    foreach (gondrand_quote_defaults() as $key => $fallback) {
        $v = gondrand_mod('gondrand_i18n_' . $key);
        if ($v !== '') {
            $cms[$key] = $v;
        } elseif ($key !== 'quote_intro' && (!isset($cms[$key]) || $cms[$key] === '')) {
            $cms[$key] = $fallback;
        }
    }
    if (function_exists('gondrand_catalog')) {
        foreach (gondrand_catalog() as $slug => $page) {
            if (empty($page['i18n'])) {
                continue;
            }
            $cms[$page['i18n']] = gondrand_page_name($slug);
        }
        $cms['nav_quote'] = gondrand_t('nav_quote', 'Devis');
        $cms['nav_rfq'] = gondrand_page_name('demande-de-cotation');
    }
    $cms['quote_types'] = gondrand_quote_list(
        'gondrand_quote_types',
        "Entreposage\nTransport aérien\nTransport maritime\nTransport multimodal\nTransport terrestre"
    );
    $cms['quote_parcels'] = gondrand_quote_list(
        'gondrand_quote_parcels',
        "Palette\nCarton\nConteneur\nCaisse bois\nSur-mesure\nDivers"
    );
    $cms['quote_incoterms'] = gondrand_quote_list(
        'gondrand_quote_incoterms',
        "EXW\nFCA\nFAS\nFOB\nCFR\nCIF\nCPT\nCIP\nDAP\nDPU\nDDP"
    );
    return $cms;
}

function gondrand_apply_page_title($html, $path) {
    if (!function_exists('gondrand_path_to_slug') || !function_exists('gondrand_page_name')) {
        return $html;
    }
    $slug = gondrand_path_to_slug($path);
    if ($slug === '' || $slug === 'home') {
        return $html;
    }
    $name = gondrand_page_name($slug);
    if ($name === '') {
        return $html;
    }
    $out = preg_replace(
        '#<title>.*?</title>#s',
        '<title>' . esc_html($name) . ' | TRAVEX GLOBAL FORWARDING</title>',
        $html,
        1
    );
    return is_string($out) ? $out : $html;
}

function gondrand_replace_once($html, $pattern, $replacement) {
    $out = preg_replace($pattern, $replacement, $html, 1);
    return is_string($out) ? $out : $html;
}

function gondrand_apply_content($html, $path) {
    if (function_exists('gondrand_build_slides_html')) {
        $built = gondrand_build_slides_html(gondrand_get_slides());
        $html = preg_replace('#<div class="slides">.*?</div>#s', '<div class="slides">' . $built . '</div>', $html, 1);
    }

    $h2 = gondrand_mod('gondrand_home_h2');
    $h3 = gondrand_mod('gondrand_home_h3');
    $p1 = gondrand_mod('gondrand_home_p1');
    $p2 = gondrand_mod('gondrand_home_p2');
    $vid = gondrand_mod('gondrand_home_video');
    if ($h2 !== '') {
        $html = gondrand_replace_once($html, '#(<div class="prose">\s*<div class="bar"></div>\s*<h2>)(.*?)(</h2>)#s', '$1' . esc_html($h2) . '$3');
    }
    if ($h3 !== '') {
        $html = gondrand_replace_once($html, '#(data-i18n="about_sub">)(.*?)(</h3>)#s', '$1' . esc_html($h3) . '$3');
    }
    if ($p1 !== '') {
        $html = gondrand_replace_once($html, '#(data-i18n="about_p1">)(.*?)(</p>)#s', '$1' . esc_html($p1) . '$3');
    }
    if ($p2 !== '') {
        $html = gondrand_replace_once($html, '#(data-i18n="about_p2">)(.*?)(</p>)#s', '$1' . esc_html($p2) . '$3');
    }
    if ($vid !== '') {
        $html = gondrand_replace_once($html, '#(<div class="video-box">\s*<img src=")([^"]*)(")#s', '$1' . esc_url($vid) . '$3');
    }

    for ($i = 1; $i <= 6; $i++) {
        $cimg   = gondrand_mod("gondrand_card_{$i}_image");
        $ctitle = gondrand_mod("gondrand_card_{$i}_title");
        $ctext  = gondrand_mod("gondrand_card_{$i}_text");
        if ($cimg !== '') {
            $html = gondrand_replace_once(
                $html,
                '#(<article class="card"[^>]*data-card="' . $i . '"[^>]*>\s*<img src=")([^"]*)(")#s',
                '$1' . esc_url($cimg) . '$3'
            );
        }
        if ($ctitle !== '') {
            $html = gondrand_replace_once(
                $html,
                '#(<article class="card"[^>]*data-card="' . $i . '"[^>]*>.*?<h3[^>]*>)(.*?)(</h3>)#s',
                '$1' . esc_html($ctitle) . '$3'
            );
        }
        if ($ctext !== '') {
            $html = gondrand_replace_once(
                $html,
                '#(<article class="card"[^>]*data-card="' . $i . '"[^>]*>.*?<p[^>]*>)(.*?)(</p>)#s',
                '$1' . esc_html($ctext) . '$3'
            );
        }
    }

    $addr  = gondrand_mod('gondrand_address');
    $phone = gondrand_mod('gondrand_phone');
    $email = gondrand_mod('gondrand_email');
    if ($addr !== '') {
        $html = str_replace('11 rue de Lübeck', esc_html(preg_replace('/\s+/', ' ', $addr)), $html);
    }
    if ($phone !== '') {
        $html = str_replace('+33 1 44 13 14 00', esc_html($phone), $html);
    }
    if ($email !== '') {
        $html = str_replace('accueil.dg@gondrand.fr', esc_html($email), $html);
    }

    $slug = trim((string) $path, '/');
    $slug = preg_replace('#/index\.html$#', '', $slug);
    $slug = basename($slug === '' ? 'home' : $slug);
    $page = gondrand_mod('gondrand_page_' . $slug);
    if ($page !== '') {
        $html = gondrand_replace_once(
            $html,
            '#(<div class="prose"[^>]*>)(.*?)(</div>)#s',
            '$1' . $page . '$3'
        );
    }

    $logo = function_exists('gondrand_logo_url') ? gondrand_logo_url() : gondrand_mod('gondrand_logo');
    $boot  = '<script>window.GONDRAND_CMS=' . wp_json_encode(gondrand_cms_payload()) . ';';
    $boot .= 'window.GONDRAND_LOGO=' . wp_json_encode($logo) . ';';
    $boot .= 'window.TRAVEX_BUST=' . wp_json_encode(gondrand_bust()) . ';';
    $boot .= 'window.TRAVEX_BUST_URL=' . wp_json_encode(home_url('/travex-bust.json')) . ';';
    if (function_exists('gondrand_locations_payload')) {
        $boot .= 'window.GONDRAND_LOCS=' . wp_json_encode(gondrand_locations_payload()) . ';';
    }
    if (function_exists('gondrand_get_brands')) {
        $boot .= 'window.GONDRAND_BRANDS=' . wp_json_encode(gondrand_get_brands()) . ';';
    }
    $boot .= '</script>';
    $html = str_replace('</head>', $boot . "\n</head>", $html);
    $html = gondrand_apply_page_title($html, $path);
    if (function_exists('gondrand_apply_timeline') && strpos((string) $path, 'entreprise') !== false) {
        $html = gondrand_apply_timeline($html);
    }

    return $html;
}

function gondrand_is_customizer() {
    if (function_exists('is_customize_preview') && is_customize_preview()) {
        return true;
    }
    return isset($_GET['customize_changeset_uuid']) || isset($_POST['wp_customize']);
}

function gondrand_is_android() {
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
    return stripos($ua, 'Android') !== false;
}

function gondrand_android_slider_css() {
    return 'html body .hero,html body .hero .slides,html body .hero .slide.active{height:auto!important;max-height:none!important;min-height:0!important;overflow:visible!important;}'
        . 'html body .hero .slides{position:relative!important;inset:auto!important;}'
        . 'html body .hero .slide{height:auto!important;overflow:visible!important;transform:none!important;}'
        . 'html body .hero .slide.active{position:relative!important;}'
        . 'html body .hero .slide .slide-img,html body .hero img.slide-img{position:static!important;display:block!important;width:100%!important;height:auto!important;max-width:100%!important;max-height:none!important;margin:0 auto!important;object-fit:contain!important;object-position:center center!important;transform:none!important;-webkit-transform:none!important;inset:auto!important;top:auto!important;left:auto!important;right:auto!important;bottom:auto!important;}';
}

function gondrand_head_inject() {
    $out = '<!-- travex-bust ' . esc_html(gondrand_bust()) . ' -->';
    $out .= '<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">';
    $out .= '<meta http-equiv="Pragma" content="no-cache">';
    $out .= '<script>if(/Android/i.test(navigator.userAgent||""))document.documentElement.classList.add("travex-android");</script>';
    $out .= '<style id="travex-android-slider">html.travex-android .hero,html.travex-android .hero .slides,html.travex-android .hero .slide.active{height:auto!important;max-height:none!important;min-height:0!important;overflow:visible!important}html.travex-android .hero .slides{position:relative!important;inset:auto!important}html.travex-android .hero .slide{display:block!important;position:absolute!important;left:0!important;right:0!important;top:0!important;bottom:auto!important;width:100%!important;height:auto!important;overflow:visible!important;transform:none!important;-webkit-transform:none!important}html.travex-android .hero .slide.active{position:relative!important;opacity:1!important;z-index:1!important}html.travex-android .hero .slide-img,html.travex-android .hero img.slide-img{position:static!important;inset:auto!important;width:100%!important;max-width:100%!important;height:auto!important;max-height:none!important;object-fit:contain!important;-webkit-object-fit:contain!important;object-position:center center!important;transform:none!important;-webkit-transform:none!important;margin:0 auto!important;display:block!important}</style>';
    $out .= '<style id="gondrand-layout">' . gondrand_layout_css() . '</style>';
    $out .= '<style id="gondrand-slider">' . gondrand_slider_css() . '</style>';
    $out .= '<style id="gondrand-mobile">' . gondrand_mobile_css() . '</style>';
    if (gondrand_is_android()) {
        $out .= '<style id="travex-android-slider">' . gondrand_android_slider_css() . '</style>';
    }

    $custom = function_exists('wp_get_custom_css') ? wp_get_custom_css() : '';
    if (is_string($custom) && trim($custom) !== '') {
        $out .= '<style id="wp-custom-css">' . wp_strip_all_tags($custom) . '</style>';
    }

    if (gondrand_is_customizer() || is_user_logged_in()) {
        if (gondrand_is_customizer()) {
            wp_enqueue_script('customize-preview');
        }
        ob_start();
        wp_head();
        $out .= ob_get_clean();
    }

    return $out;
}

function gondrand_footer_inject() {
    if (!gondrand_is_customizer() && !is_user_logged_in()) {
        return '';
    }
    ob_start();
    wp_footer();
    return ob_get_clean();
}

function gondrand_slide_fit($key) {
    $v = strtolower(gondrand_mod($key));
    return $v === 'cover' ? 'cover' : 'contain';
}

function gondrand_slide_pos($key) {
    $v = strtolower(gondrand_mod($key));
    if ($v === 'top') {
        return 'center top';
    }
    if ($v === 'bottom') {
        return 'center bottom';
    }
    return 'center center';
}

function gondrand_slide_height_px($key, $min, $max) {
    $v = gondrand_mod($key);
    if ($v === '' || !is_numeric($v)) {
        return 0;
    }
    return max($min, min($max, (int) $v));
}

function gondrand_slide_mobile_fit() {
    return gondrand_slide_fit('gondrand_slide_mobile_fit');
}

function gondrand_slide_mobile_pos() {
    return gondrand_slide_pos('gondrand_slide_mobile_pos');
}

function gondrand_slide_mobile_height() {
    return gondrand_slide_height_px('gondrand_slide_mobile_height', 160, 900);
}

function gondrand_slide_overlay() {
    $v = gondrand_mod('gondrand_slide_overlay');
    if ($v === '' || !is_numeric($v)) {
        return 0;
    }
    return max(0, min(100, (int) $v)) / 100;
}

function gondrand_slide_img_clip($pos) {
    if ($pos === 'center top') {
        return 'top:0 !important;bottom:auto !important;transform:none !important;';
    }
    if ($pos === 'center bottom') {
        return 'top:auto !important;bottom:0 !important;transform:none !important;';
    }
    return 'top:50% !important;bottom:auto !important;transform:translateY(-50%) !important;';
}

function gondrand_slide_rules($fit, $pos, $h, $ov, $cover_fallback, $clip_height = true) {
    $css = '';
    $css .= '.slide{display:block !important;}';
    if ($fit === 'cover') {
        $height = $h ? $h . 'px' : $cover_fallback;
        $css .= '.hero{height:' . $height . ' !important;overflow:hidden !important;position:relative !important;}';
        $css .= '.slides{position:absolute !important;inset:0 !important;height:auto !important;}';
        $css .= '.slide{position:absolute !important;inset:0 !important;opacity:0 !important;z-index:0 !important;background-size:cover !important;background-position:' . $pos . ' !important;}';
        $css .= '.slide.active{opacity:1 !important;z-index:1 !important;}';
        $css .= '.slide-img{position:absolute !important;inset:0 !important;width:100% !important;height:100% !important;object-fit:cover !important;object-position:' . $pos . ' !important;}';
        return $css;
    }
    if ($h && $clip_height) {
        $css .= '.hero{height:' . $h . 'px !important;min-height:0 !important;overflow:hidden !important;background:#111 !important;position:relative !important;}';
        $css .= '.slides{position:absolute !important;inset:0 !important;height:100% !important;}';
        $css .= '.slide{position:absolute !important;inset:0 !important;opacity:0 !important;z-index:0 !important;overflow:hidden !important;background:#111 !important;}';
        $css .= '.slide.active{opacity:1 !important;z-index:1 !important;}';
        $css .= '.slide-img{position:absolute !important;left:0 !important;right:0 !important;width:100% !important;height:auto !important;max-width:none !important;max-height:none !important;object-fit:cover !important;object-position:' . $pos . ' !important;' . gondrand_slide_img_clip($pos) . '}';
    } elseif ($h && !$clip_height) {
        $css .= '.hero{height:' . $h . 'px !important;min-height:0 !important;overflow:hidden !important;background:#111 !important;position:relative !important;}';
        $css .= '.slides{position:absolute !important;inset:0 !important;height:100% !important;}';
        $css .= '.slide{position:absolute !important;inset:0 !important;opacity:0 !important;z-index:0 !important;overflow:hidden !important;background:#111 !important;}';
        $css .= '.slide.active{opacity:1 !important;z-index:1 !important;}';
        $css .= '.slide-img{position:absolute !important;inset:0 !important;width:100% !important;height:100% !important;max-width:100% !important;object-fit:contain !important;object-position:' . $pos . ' !important;transform:none !important;}';
        $css .= '.hero-nav button{top:38% !important;}';
    } else {
        $css .= '.hero{height:auto !important;min-height:0 !important;overflow:hidden !important;background:#111 !important;position:relative !important;}';
        $css .= '.slides{position:relative !important;inset:auto !important;height:auto !important;}';
        $css .= '.slide{position:absolute !important;left:0 !important;right:0 !important;top:0 !important;opacity:0 !important;z-index:0 !important;height:auto !important;pointer-events:none !important;}';
        $css .= '.slide.active{position:relative !important;opacity:1 !important;z-index:1 !important;pointer-events:auto !important;}';
        $css .= '.slide-img{position:relative !important;inset:auto !important;width:100% !important;height:auto !important;max-width:100% !important;max-height:none !important;transform:none !important;object-fit:contain !important;object-position:' . $pos . ' !important;display:block !important;}';
        $css .= '.hero-nav button{top:38% !important;}';
    }
    $css .= '.slide-inner,.slide h2,.slide p,.slide .tagline,.slide .more{display:none !important;}';
    $css .= '.slide::after{background:rgba(6,20,40,' . $ov . ') !important;}';
    return $css;
}

function gondrand_slider_css() {
    $ov = gondrand_slide_overlay();
    $css = '.slide-inner,.slide h2,.slide p,.slide .tagline,.slide .more{display:none !important;}';
    $css .= '.slide::after{content:"";position:absolute;inset:0;pointer-events:none;background:rgba(6,20,40,' . $ov . ') !important;z-index:1;}';
    $css .= '@media screen and (min-width:981px){';
    $css .= gondrand_slide_rules(
        gondrand_slide_fit('gondrand_slide_desktop_fit'),
        gondrand_slide_pos('gondrand_slide_desktop_pos'),
        gondrand_slide_height_px('gondrand_slide_desktop_height', 200, 1400),
        $ov,
        '620px',
        true
    );
    $css .= '}';
    $css .= '@media screen and (max-width:980px){';
    $css .= gondrand_slide_rules(
        'contain',
        gondrand_slide_mobile_pos(),
        0,
        $ov,
        '420px',
        false
    );
    $css .= '.hero,.hero .slides,.hero .slide.active{height:auto !important;max-height:none !important;min-height:0 !important;overflow:visible !important;}';
    $css .= '.hero .slides{position:relative !important;inset:auto !important;}';
    $css .= '.hero .slide{position:absolute !important;left:0 !important;right:0 !important;top:0 !important;bottom:auto !important;inset:auto !important;height:auto !important;overflow:visible !important;}';
    $css .= '.hero .slide.active{position:relative !important;}';
    $css .= '.hero .slide-img{position:relative !important;inset:auto !important;left:auto !important;right:auto !important;top:auto !important;bottom:auto !important;width:100% !important;height:auto !important;max-width:100% !important;max-height:none !important;object-fit:contain !important;object-position:center center !important;transform:none !important;display:block !important;}';
    $css .= '}';
    return $css;
}

function gondrand_layout_css() {
    return <<<'CSS'
.logo {
  display: flex !important; align-items: center !important;
  overflow: visible !important; width: auto !important;
  min-width: 320px; max-width: 480px; height: 76px !important;
  flex: 0 0 auto !important;
}
.logo img {
  height: 72px !important; width: auto !important; max-width: 460px !important;
  max-height: 76px !important; object-fit: contain !important;
  position: static !important; transform: none !important; left: auto !important; top: auto !important;
}
.loc-grid {
  display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 16px !important;
  max-width: none !important;
}
.loc {
  border: 1px solid #e4e8ee !important; padding: 16px 16px 14px !important;
  background: #fff !important; min-height: 170px !important; list-style: none !important;
}
.loc ul, .loc li, .loc p { list-style: none !important; }
.loc-nearby { display: none !important; }
.brands { display: grid !important; grid-template-columns: repeat(5, 1fr) !important; gap: 12px !important; max-width: none !important; }
.brand { min-height: 92px !important; width: auto !important; max-width: none !important; }
.brand img { max-height: 36px !important; max-width: 120px !important; width: auto !important; height: auto !important; object-fit: contain !important; margin: 0 auto 6px !important; }
CSS;
}

function gondrand_mobile_css() {
    return <<<'CSS'
@media screen and (max-width: 900px) {
  html, body { max-width: 100% !important; overflow-x: hidden !important; }
  .wrap { width: calc(100% - 20px) !important; max-width: 100% !important; margin-left: auto !important; margin-right: auto !important; }
  .split { display: block !important; }
  .split > * { display: block !important; width: 100% !important; max-width: 100% !important; float: none !important; margin-left: 0 !important; margin-right: 0 !important; margin-bottom: 22px !important; }
  .video-box { width: 100% !important; max-width: 100% !important; }
  .quote, #quote-slot, #special-slot, #q { display: block !important; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; }
  .quote { padding: 16px !important; overflow: hidden !important; }
  .grid-2 { display: block !important; }
  .grid-2 > * { width: 100% !important; max-width: 100% !important; }
  .cards, .loc-grid, .specials, .mods, .brands, .fgrid, .gallery, .stats { display: block !important; }
  .cards > *, .loc-grid > *, .specials > *, .gallery > * { width: 100% !important; max-width: 100% !important; margin-bottom: 14px !important; }
  input, select, textarea, img, svg, iframe { max-width: 100% !important; box-sizing: border-box !important; }
  .map-card { min-width: 0 !important; width: calc(100% - 24px) !important; left: 12px !important; right: 12px !important; transform: translate(0, -50%) !important; }
  .logo { min-width: 0 !important; max-width: 70% !important; height: auto !important; }
  .logo img { height: 48px !important; max-width: 220px !important; }
}
CSS;
}

function gondrand_field($key) {
    $v = $_POST[$key] ?? '';
    if (is_array($v)) {
        $v = implode(', ', $v);
    }
    return trim(wp_strip_all_tags((string) $v));
}

function gondrand_handle_mail() {
    header('Content-Type: application/json; charset=utf-8');

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        status_header(405);
        echo wp_json_encode(['ok' => false, 'error' => 'method']);
        return;
    }

    $email = gondrand_field('email');
    if ($email === '' || !is_email($email)) {
        status_header(400);
        echo wp_json_encode(['ok' => false, 'error' => 'email']);
        return;
    }

    if (gondrand_field('website') !== '') {
        echo wp_json_encode(['ok' => true]);
        return;
    }

    $to = get_theme_mod('gondrand_quote_email', get_option('admin_email'));
    if (!is_email($to)) {
        $to = get_option('admin_email');
    }

    $lines = [
        'Type de transport'      => gondrand_field('type'),
        'Incoterms'              => gondrand_field('incoterms'),
        'Société'                => gondrand_field('company'),
        'Contact'                => gondrand_field('contact'),
        'Téléphone'              => gondrand_field('phone'),
        'E-mail'                 => $email,
        'Départ (ville)'         => gondrand_field('from') ?: gondrand_field('from_city'),
        'Départ (pays)'          => gondrand_field('from_country'),
        'Arrivée (ville)'        => gondrand_field('to') ?: gondrand_field('to_city'),
        'Arrivée (pays)'         => gondrand_field('to_country'),
        'Type de colis'          => gondrand_field('parcel'),
        'Poids (kg)'             => gondrand_field('weight'),
        'Dimensions'             => gondrand_field('dimensions'),
        'Marchandise dangereuse' => gondrand_field('dangerous'),
        'Commentaires'           => gondrand_field('comments'),
    ];

    $body  = "Nouvelle demande de devis — Travex Global Forwarding\n";
    $body .= 'Date : ' . current_time('mysql') . "\n";
    $body .= 'Site : ' . home_url('/') . "\n\n";
    foreach ($lines as $label => $value) {
        if ($value !== '') {
            $body .= $label . ' : ' . $value . "\n";
        }
    }

    $subject = 'Demande de devis Travex Global Forwarding';
    $type    = gondrand_field('type');
    if ($type !== '') {
        $subject .= ' — ' . $type;
    }

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $email,
    ];

    $ok = wp_mail($to, $subject, $body, $headers);
    if (!$ok) {
        status_header(500);
        echo wp_json_encode(['ok' => false, 'error' => 'mail']);
        return;
    }

    echo wp_json_encode(['ok' => true]);
}
