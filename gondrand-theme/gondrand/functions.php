<?php
/**
 * Gondrand — thème WordPress qui affiche le site HTML.
 *
 * Apparence → Personnaliser → Gondrand : adresse e-mail des devis.
 */

if (!defined('ABSPATH')) {
    exit;
}

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/chrome.php';
require get_template_directory() . '/inc/admin.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
});

add_action('after_switch_theme', function () {
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    flush_rewrite_rules();
});

add_action('template_redirect', 'gondrand_try_serve', 0);

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
        $html = file_get_contents($real_file);
        $html = preg_replace('#<div class="dl-banner">.*?</div>#s', '', $html);
        $html = gondrand_absolutize_assets($html);
        $html = gondrand_inject_chrome($html, $path);
        $html = gondrand_apply_content($html, $path);
        $html = str_replace('</head>', gondrand_head_inject() . "\n</head>", $html);
        $html = str_replace('</body>', gondrand_footer_inject() . "\n</body>", $html);
        echo $html;
        exit;
    }

    readfile($real_file);
    exit;
}

function gondrand_mod($key) {
    $v = get_theme_mod($key, '');
    return is_string($v) ? trim($v) : '';
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
    $logo = gondrand_mod('gondrand_logo');
    $boot  = '<script>window.GONDRAND_CMS=' . wp_json_encode($cms) . ';';
    $boot .= 'window.GONDRAND_LOGO=' . wp_json_encode($logo) . ';</script>';
    $html = str_replace('</head>', $boot . "\n</head>", $html);

    return $html;
}

function gondrand_is_customizer() {
    if (function_exists('is_customize_preview') && is_customize_preview()) {
        return true;
    }
    return isset($_GET['customize_changeset_uuid']) || isset($_POST['wp_customize']);
}

function gondrand_head_inject() {
    $out = '<style id="gondrand-mobile">' . gondrand_mobile_css() . '</style>';

    $custom = function_exists('wp_get_custom_css') ? wp_get_custom_css() : '';
    if (is_string($custom) && trim($custom) !== '') {
        $out .= '<style id="wp-custom-css">' . wp_strip_all_tags($custom) . '</style>';
    }

    if (gondrand_is_customizer() || is_user_logged_in()) {
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

    $body  = "Nouvelle demande de devis — Gondrand\n";
    $body .= 'Date : ' . current_time('mysql') . "\n";
    $body .= 'Site : ' . home_url('/') . "\n\n";
    foreach ($lines as $label => $value) {
        if ($value !== '') {
            $body .= $label . ' : ' . $value . "\n";
        }
    }

    $subject = 'Demande de devis Gondrand';
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
