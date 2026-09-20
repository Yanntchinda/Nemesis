<?php
/**
 * Plugin Name: Travex Actualiser
 * Description: Fait en sorte que l’accueil WordPress s’affiche (renomme index.html), vide LiteSpeed, PC et Android voient les mêmes images.
 * Version: 1.0
 *
 * Installation : copier ce fichier dans wp-content/mu-plugins/travex-actualiser.php
 * (créez le dossier mu-plugins s’il n’existe pas).
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    foreach (['index.html', 'index.htm', 'home.html'] as $name) {
        $file = ABSPATH . $name;
        if (!is_file($file)) {
            continue;
        }
        $dest = ABSPATH . $name . '.off';
        if (is_file($dest)) {
            $dest = ABSPATH . $name . '.off-' . time();
        }
        @rename($file, $dest);
    }
    if (has_action('litespeed_control_set_nocache')) {
        do_action('litespeed_control_set_nocache', 'travex-actualiser');
    }
}, 0);

add_action('admin_bar_menu', function ($bar) {
    if (!current_user_can('edit_theme_options') || !is_object($bar)) {
        return;
    }
    $bar->add_node([
        'id'    => 'travex-purge',
        'title' => 'Actualiser le site (PC + Android)',
        'href'  => wp_nonce_url(admin_url('admin.php?page=gondrand-content&gondrand_purge=1'), 'gondrand_purge'),
    ]);
}, 80);
