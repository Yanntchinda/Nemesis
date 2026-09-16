<?php
/**
 * Gondrand — thème WordPress qui affiche le site HTML.
 *
 * Apparence → Personnaliser → Gondrand : adresse e-mail des devis.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    show_admin_bar(false);
});

add_action('after_switch_theme', function () {
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
    }
    flush_rewrite_rules();
});

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('gondrand', [
        'title'    => 'Gondrand',
        'priority' => 30,
    ]);
    $wp_customize->add_setting('gondrand_quote_email', [
        'default'           => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('gondrand_quote_email', [
        'label'       => 'E-mail des demandes de devis',
        'description' => 'Les formulaires Devis / Cotation sont envoyés à cette adresse.',
        'section'     => 'gondrand',
        'type'        => 'email',
    ]);
});

add_action('init', 'gondrand_try_serve', 0);
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
        echo $html;
        exit;
    }

    readfile($real_file);
    exit;
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
