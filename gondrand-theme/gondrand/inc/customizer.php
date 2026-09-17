<?php
if (!defined('ABSPATH')) {
    exit;
}

function gondrand_text_setting($wp_customize, $id, $label, $section, $type = 'text') {
    $wp_customize->add_setting($id, [
        'default'           => '',
        'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control($id, [
        'label'   => $label,
        'section' => $section,
        'type'    => $type,
    ]);
}

function gondrand_image_setting($wp_customize, $id, $label, $section) {
    $wp_customize->add_setting($id, [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $id, [
        'label'    => $label,
        'section'  => $section,
        'settings' => $id,
    ]));
}

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_panel('gondrand_panel', [
        'title'       => 'Gondrand — contenu du site',
        'description' => 'Modifiez les images du slider, les textes et le logo. Laissez un champ vide pour garder le texte d’origine.',
        'priority'    => 10,
    ]);

    $wp_customize->add_section('gondrand', [
        'title' => 'Devis / e-mail',
        'panel' => 'gondrand_panel',
    ]);
    $wp_customize->add_setting('gondrand_quote_email', [
        'default'           => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('gondrand_quote_email', [
        'label'   => 'E-mail des demandes de devis',
        'section' => 'gondrand',
        'type'    => 'email',
    ]);

    $wp_customize->add_section('gondrand_brand', [
        'title' => 'Logo',
        'panel' => 'gondrand_panel',
    ]);
    gondrand_image_setting($wp_customize, 'gondrand_logo', 'Logo du site', 'gondrand_brand');

    $slides = [
        1 => 'Transport terrestre',
        2 => 'Fret aérien',
        3 => 'Fret maritime',
        4 => 'Trafics spéciaux',
        5 => 'Douane',
        6 => 'Représentation fiscale',
    ];
    foreach ($slides as $i => $name) {
        $sec = 'gondrand_slide_' . $i;
        $wp_customize->add_section($sec, [
            'title' => "Slider $i — $name",
            'panel' => 'gondrand_panel',
        ]);
        gondrand_image_setting($wp_customize, "gondrand_slide_{$i}_image", 'Image de fond', $sec);
        gondrand_text_setting($wp_customize, "gondrand_slide_{$i}_title", 'Titre', $sec);
        gondrand_text_setting($wp_customize, "gondrand_slide_{$i}_text", 'Texte', $sec, 'textarea');
        gondrand_text_setting($wp_customize, "gondrand_slide_{$i}_url", 'Lien « Lire la suite »', $sec);
    }

    $wp_customize->add_section('gondrand_home', [
        'title' => 'Accueil — intro & vidéo',
        'panel' => 'gondrand_panel',
    ]);
    gondrand_text_setting($wp_customize, 'gondrand_home_h2', 'Titre intro', 'gondrand_home');
    gondrand_text_setting($wp_customize, 'gondrand_home_h3', 'Sous-titre', 'gondrand_home');
    gondrand_text_setting($wp_customize, 'gondrand_home_p1', 'Paragraphe 1', 'gondrand_home', 'textarea');
    gondrand_text_setting($wp_customize, 'gondrand_home_p2', 'Paragraphe 2', 'gondrand_home', 'textarea');
    gondrand_image_setting($wp_customize, 'gondrand_home_video', 'Image de la vidéo', 'gondrand_home');

    $wp_customize->add_section('gondrand_cards', [
        'title' => 'Accueil — cartes services',
        'panel' => 'gondrand_panel',
    ]);
    $cards = [1 => 'About us', 2 => 'Transport terrestre', 3 => 'Fret aérien', 4 => 'Fret maritime', 5 => 'Trafics spéciaux', 6 => 'Douane'];
    foreach ($cards as $i => $name) {
        gondrand_image_setting($wp_customize, "gondrand_card_{$i}_image", "Carte $i — $name : image", 'gondrand_cards');
        gondrand_text_setting($wp_customize, "gondrand_card_{$i}_title", "Carte $i — titre", 'gondrand_cards');
        gondrand_text_setting($wp_customize, "gondrand_card_{$i}_text", "Carte $i — texte", 'gondrand_cards', 'textarea');
    }

    $wp_customize->add_section('gondrand_contact', [
        'title' => 'Coordonnées',
        'panel' => 'gondrand_panel',
    ]);
    gondrand_text_setting($wp_customize, 'gondrand_address', 'Adresse', 'gondrand_contact', 'textarea');
    gondrand_text_setting($wp_customize, 'gondrand_phone', 'Téléphone', 'gondrand_contact');
    gondrand_text_setting($wp_customize, 'gondrand_email', 'E-mail affiché', 'gondrand_contact');

    $wp_customize->add_section('gondrand_nav', [
        'title' => 'Menu / navigation',
        'panel' => 'gondrand_panel',
    ]);
    foreach ([
        'nav_home' => 'Accueil',
        'nav_company' => 'Entreprise',
        'nav_services' => 'Services',
        'nav_quote' => 'Devis',
        'nav_contact' => 'Contact',
        'nav_rfq' => 'Demande de cotation',
        'nav_jobs' => 'Espace emploi',
        'nav_locations' => 'Emplacements',
    ] as $key => $label) {
        gondrand_text_setting($wp_customize, 'gondrand_i18n_' . $key, $label, 'gondrand_nav');
    }

    $wp_customize->add_section('gondrand_copy', [
        'title' => 'Textes généraux',
        'panel' => 'gondrand_panel',
    ]);
    foreach ([
        'svc_road' => 'Transport terrestre',
        'svc_air' => 'Fret aérien',
        'svc_sea' => 'Fret maritime',
        'svc_special' => 'Trafics spéciaux',
        'svc_customs' => 'Douane',
        'svc_vat' => 'Représentation fiscale',
        'about_sub' => 'Logistique depuis 1866',
        'about_p1' => 'Intro paragraphe 1',
        'about_p2' => 'Intro paragraphe 2',
        'quote_title' => 'Titre formulaire devis',
        'special_title' => 'Titre « ce qui nous rend spécial »',
        'loc_title' => 'Titre emplacements',
        'read_more' => 'Lire la suite',
    ] as $key => $label) {
        $type = in_array($key, ['about_p1', 'about_p2'], true) ? 'textarea' : 'text';
        gondrand_text_setting($wp_customize, 'gondrand_i18n_' . $key, $label, 'gondrand_copy', $type);
    }

    $pages = [
        'entreprise' => 'Page Entreprise (contenu)',
        'contact' => 'Page Contact (contenu)',
        'demande-de-cotation' => 'Page Demande de cotation (intro)',
        'mentions-legales' => 'Mentions légales',
        'representation-fiscale' => 'Représentation fiscale',
        'douane' => 'Douane',
        'luftfracht-2' => 'Transport terrestre (service)',
        'ueber-uns' => 'Fret aérien (service)',
        'beratung-2' => 'Fret maritime (service)',
        'seefracht-2' => 'Trafics spéciaux (service)',
        'zoll-2' => 'Douane (service)',
        'logistik-2' => 'Entreprise (service)',
    ];
    $wp_customize->add_section('gondrand_pages', [
        'title'       => 'Pages — contenu HTML',
        'description' => 'Collez le nouveau texte / HTML de la zone principale. Vide = contenu actuel.',
        'panel'       => 'gondrand_panel',
    ]);
    foreach ($pages as $slug => $label) {
        $wp_customize->add_setting('gondrand_page_' . $slug, [
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('gondrand_page_' . $slug, [
            'label'   => $label,
            'section' => 'gondrand_pages',
            'type'    => 'textarea',
        ]);
    }
});
