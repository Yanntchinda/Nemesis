<?php
if (!defined('ABSPATH')) {
    exit;
}

function gondrand_default_slides() {
    $a = gondrand_assets();
    return [
        ['image' => $a . 'images/hero-road.jpg', 'title' => 'Transport terrestre', 'text' => 'Nous traitons votre transport avec le professionnalisme et la minutie que vous pouvez attendre.', 'url' => gondrand_u('services/luftfracht-2/')],
        ['image' => $a . 'images/hero-air.jpg', 'title' => 'Fret aérien', 'text' => "Avec nos solutions d'expédition de fret aérien, nous fournissons la rapidité et la fiabilité dont vous avez besoin.", 'url' => gondrand_u('services/ueber-uns/')],
        ['image' => $a . 'images/hero-sea.jpg', 'title' => 'Fret maritime', 'text' => 'Nos normes de qualité mondiales garantissent un service sûr et une livraison à temps.', 'url' => gondrand_u('services/beratung-2/')],
        ['image' => $a . 'images/hero-special.jpg', 'title' => 'Trafics spéciaux', 'text' => "Nous gérons vos besoins en logistique d'un endroit à un autre, peu importe la taille.", 'url' => gondrand_u('services/seefracht-2/')],
        ['image' => $a . 'images/hero-customs.jpg', 'title' => 'Douane', 'text' => 'En nous conformant aux lois européennes, nous vous offrons une réelle valeur ajoutée.', 'url' => gondrand_u('services/zoll-2/')],
        ['image' => $a . 'images/hero-fiscal.jpg', 'title' => 'Représentation fiscale', 'text' => 'Notre service de représentation fiscale vous aide à vous conformer à la réglementation TVA.', 'url' => gondrand_u('representation-fiscale/index.html')],
    ];
}

function gondrand_get_slides() {
    $slides = get_option('gondrand_slides', false);
    if ($slides === false || !is_array($slides)) {
        return gondrand_default_slides();
    }
    $out = [];
    foreach ($slides as $s) {
        if (!is_array($s)) {
            continue;
        }
        $img = isset($s['image']) ? trim((string) $s['image']) : '';
        if ($img === '') {
            continue;
        }
        $out[] = [
            'image' => $img,
            'title' => isset($s['title']) ? (string) $s['title'] : '',
            'text'  => isset($s['text']) ? (string) $s['text'] : '',
            'url'   => isset($s['url']) ? (string) $s['url'] : '',
        ];
    }
    return $out;
}

function gondrand_build_slides_html($slides) {
    if (!$slides) {
        return '';
    }
    $html = '';
    foreach ($slides as $i => $s) {
        $active = $i === 0 ? ' active' : '';
        $img = esc_url($s['image']);
        $title = esc_html($s['title']);
        $text = esc_html($s['text']);
        $url = isset($s['url']) ? trim($s['url']) : '';
        $html .= '<article class="slide' . $active . '" data-slide="' . ($i + 1) . '">';
        $html .= '<img class="slide-img" src="' . $img . '" alt="' . $title . '">';
        $html .= '<div class="wrap slide-inner">';
        $html .= '<h2>' . $title . '</h2>';
        $html .= '<div class="tagline">More Performance – More Success</div>';
        $html .= '<p>' . $text . '</p>';
        if ($url !== '') {
            $html .= '<a class="more" href="' . esc_url($url) . '">Lire la suite : →</a>';
        }
        $html .= '</div></article>';
    }
    return $html;
}

function gondrand_default_locations() {
    return [
        ['title' => 'TRAVEX GLOBAL FORWARDING – RENCHEN', 'address' => "Im Brünnel 2\n77871 Renchen", 'text' => ''],
        ['title' => 'Renchen', 'address' => "Allemagne\n0 km", 'text' => ''],
        ['title' => 'Appenweier', 'address' => "Allemagne\nenv. 5 km", 'text' => ''],
        ['title' => 'Achern', 'address' => "Allemagne\nenv. 8 km", 'text' => ''],
        ['title' => 'Oberkirch', 'address' => "Allemagne\nenv. 9 km", 'text' => ''],
        ['title' => 'Offenburg', 'address' => "Allemagne\nenv. 14 km", 'text' => ''],
        ['title' => 'Kehl', 'address' => "Allemagne\nenv. 20 km", 'text' => ''],
        ['title' => 'Baden-Baden', 'address' => "Allemagne\nenv. 25 km", 'text' => ''],
        ['title' => 'Karlsruhe', 'address' => "Allemagne\nenv. 60 km", 'text' => ''],
        ['title' => 'Freiburg', 'address' => "Allemagne\nenv. 70 km", 'text' => ''],
        ['title' => 'Pforzheim', 'address' => "Allemagne\nenv. 80 km", 'text' => ''],
        ['title' => 'Stuttgart', 'address' => "Allemagne\nenv. 130 km", 'text' => ''],
        ['title' => 'Strasbourg', 'address' => "France\nenv. 35 km", 'text' => ''],
        ['title' => 'Alsace et environs', 'address' => "France\nFacilement accessibles", 'text' => ''],
        ['title' => 'Bâle', 'address' => "Suisse\nenv. 140 km", 'text' => ''],
        ['title' => 'Zurich', 'address' => "Suisse\nenv. 200 km", 'text' => ''],
        ['title' => 'Berne', 'address' => "Suisse\nenv. 220 km", 'text' => ''],
    ];
}

function gondrand_expand_location_cards($locs) {
    $cards = [];
    foreach ($locs as $l) {
        $title = isset($l['title']) ? trim((string) $l['title']) : '';
        $address = isset($l['address']) ? trim((string) $l['address']) : '';
        $text = isset($l['text']) ? (string) $l['text'] : '';
        if ($title !== '' || $address !== '') {
            $cards[] = ['title' => $title, 'address' => $address, 'text' => ''];
        }
        if (trim($text) === '') {
            continue;
        }
        $group = '';
        foreach (preg_split('/\R/u', $text) as $line) {
            $line = trim($line);
            $line = preg_replace('/^[•📍📌*\-]+\s*/u', '', $line);
            if ($line === '' || stripos($line, 'Notre point') === 0) {
                continue;
            }
            if (preg_match('/Allemagne|France|Suisse/u', $line) && !preg_match('/km/i', $line)) {
                $group = trim(preg_replace('/[\x{1F1E6}-\x{1F1FF}]/u', '', $line));
                continue;
            }
            if (preg_match('/^(.+?)\s+[–—-]\s+(.+)$/u', $line, $m)) {
                $cards[] = [
                    'title'   => trim($m[1]),
                    'address' => trim(($group !== '' ? $group . "\n" : '') . $m[2]),
                    'text'    => '',
                ];
            } else {
                $cards[] = ['title' => $line, 'address' => $group, 'text' => ''];
            }
        }
    }
    return $cards ?: $locs;
}

function gondrand_get_locations() {
    $locs = get_option('gondrand_locations', false);
    if ($locs === false || !is_array($locs)) {
        return gondrand_default_locations();
    }
    $out = [];
    foreach ($locs as $l) {
        if (!is_array($l)) {
            continue;
        }
        $title = isset($l['title']) ? trim((string) $l['title']) : '';
        $address = isset($l['address']) ? trim((string) $l['address']) : '';
        $text = isset($l['text']) ? (string) $l['text'] : '';
        if ($title === '' && $address === '' && trim($text) === '') {
            continue;
        }
        $out[] = ['title' => $title, 'address' => $address, 'text' => $text];
    }
    $expanded = gondrand_expand_location_cards($out);
    if (count($out) === 1 && count($expanded) > 1) {
        update_option('gondrand_locations', $expanded, false);
        return $expanded;
    }
    return $expanded;
}

function gondrand_locations_payload() {
    $out = [];
    foreach (gondrand_get_locations() as $l) {
        $out[] = [
            'n'       => $l['title'],
            'a'       => $l['address'],
            'nearby'  => $l['text'],
            'title'   => $l['title'],
            'address' => $l['address'],
            'text'    => $l['text'],
        ];
    }
    return $out;
}

function gondrand_locations_html() {
    $html = '<div class="loc-grid" id="loc-grid">';
    foreach (gondrand_get_locations() as $l) {
        $html .= '<article class="loc">';
        if ($l['title'] !== '') {
            $html .= '<h3>' . esc_html($l['title']) . '</h3>';
        }
        $body = trim($l['address'] . "\n" . ($l['text'] ?? ''));
        $body = preg_replace('/^[•📍📌*\-]+\s*/mu', '', $body);
        if ($body !== '') {
            $html .= '<p>' . nl2br(esc_html($body)) . '</p>';
        }
        $html .= '</article>';
    }
    $html .= '</div>';
    return $html;
}

function gondrand_loc_heading() {
    $v = gondrand_mod('gondrand_loc_title');
    if ($v === '' || stripos($v, 'gondrand') !== false) {
        return 'TRAVEX GLOBAL FORWARDING EMPLACEMENTS';
    }
    return $v;
}

function gondrand_loc_row_html($l) {
    ob_start();
    ?>
    <div class="gondrand-slide gondrand-loc">
      <p>Nom<br><input class="large-text" name="loc_title[]" value="<?php echo esc_attr($l['title'] ?? ''); ?>"></p>
      <p>Adresse<br><textarea name="loc_address[]" rows="2" class="large-text"><?php echo esc_textarea($l['address'] ?? ''); ?></textarea></p>
      <p>Complément<br><textarea name="loc_text[]" rows="2" class="large-text"><?php echo esc_textarea($l['text'] ?? ''); ?></textarea></p>
      <p><button type="button" class="button gondrand-del-loc">Supprimer cet emplacement</button></p>
    </div>
    <?php
    return ob_get_clean();
}

add_action('admin_menu', function () {
    add_menu_page(
        'Travex',
        'Travex',
        'edit_theme_options',
        'gondrand-content',
        'gondrand_admin_page',
        'dashicons-slides',
        3
    );
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_gondrand-content') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('jquery');
});

add_action('admin_notices', function () {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    $moved = get_option('gondrand_moved_index_html');
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $on = $screen && isset($screen->id) && strpos($screen->id, 'gondrand') !== false;
    if ($moved && $on) {
        echo '<div class="notice notice-warning"><p>Un fichier <code>index.html</code> bloquait WordPress à la racine du site. Il a été renommé. Purgez LiteSpeed.</p></div>';
    }
    if (!$on) {
        echo '<div class="notice notice-info"><p><strong>Travex :</strong> pour modifier le site, menu <a href="' . esc_url(admin_url('admin.php?page=gondrand-content')) . '">Travex Global Forwarding</a> (accueil) ou <a href="' . esc_url(admin_url('admin.php?page=gondrand-pages')) . '">Toutes les pages</a>. Pas le menu « Pages » de WordPress.</p></div>';
    }
});

function gondrand_save_from_post() {
    if (!empty($_POST['gondrand_reset'])) {
        delete_option('gondrand_slides');
        return 'reset';
    }

    $images = isset($_POST['slide_image']) ? (array) $_POST['slide_image'] : [];
    $titles = isset($_POST['slide_title']) ? (array) $_POST['slide_title'] : [];
    $texts  = isset($_POST['slide_text']) ? (array) $_POST['slide_text'] : [];
    $urls   = isset($_POST['slide_url']) ? (array) $_POST['slide_url'] : [];

    $slides = [];
    foreach ($images as $i => $img) {
        $img = esc_url_raw(trim((string) wp_unslash($img)));
        if ($img === '') {
            continue;
        }
        $slides[] = [
            'image' => $img,
            'title' => sanitize_text_field(wp_unslash($titles[$i] ?? '')),
            'text'  => sanitize_textarea_field(wp_unslash($texts[$i] ?? '')),
            'url'   => esc_url_raw(wp_unslash($urls[$i] ?? '')),
        ];
    }
    update_option('gondrand_slides', $slides, false);

    $lt = isset($_POST['loc_title']) ? (array) wp_unslash($_POST['loc_title']) : [];
    $la = isset($_POST['loc_address']) ? (array) wp_unslash($_POST['loc_address']) : [];
    $lx = isset($_POST['loc_text']) ? (array) wp_unslash($_POST['loc_text']) : [];
    $locations = [];
    foreach ($lt as $i => $title) {
        $title = sanitize_text_field($title);
        $address = sanitize_textarea_field($la[$i] ?? '');
        $text = sanitize_textarea_field($lx[$i] ?? '');
        if ($title === '' && $address === '' && trim($text) === '') {
            continue;
        }
        $locations[] = ['title' => $title, 'address' => $address, 'text' => $text];
    }
    update_option('gondrand_locations', $locations, false);

    $map = [
        'gondrand_logo' => 'esc_url_raw',
        'gondrand_home_h2' => 'sanitize_text_field',
        'gondrand_home_h3' => 'sanitize_text_field',
        'gondrand_home_p1' => 'sanitize_textarea_field',
        'gondrand_home_p2' => 'sanitize_textarea_field',
        'gondrand_home_video' => 'esc_url_raw',
        'gondrand_address' => 'sanitize_textarea_field',
        'gondrand_phone' => 'sanitize_text_field',
        'gondrand_email' => 'sanitize_email',
        'gondrand_quote_email' => 'sanitize_email',
        'gondrand_loc_title' => 'sanitize_text_field',
        'gondrand_loc_lead' => 'sanitize_textarea_field',
        'gondrand_specials_title' => 'sanitize_text_field',
        'gondrand_specials_lead' => 'sanitize_textarea_field',
        'gondrand_map_image' => 'esc_url_raw',
        'gondrand_slide_mobile_fit' => 'sanitize_text_field',
        'gondrand_slide_mobile_pos' => 'sanitize_text_field',
        'gondrand_slide_mobile_height' => 'sanitize_text_field',
    ];
    foreach ($map as $key => $cb) {
        if (!isset($_POST[$key])) {
            continue;
        }
        $val = $cb(wp_unslash($_POST[$key]));
        if ($key === 'gondrand_loc_title' && ( $val === '' || stripos($val, 'gondrand') !== false )) {
            $val = 'TRAVEX GLOBAL FORWARDING EMPLACEMENTS';
        }
        if ($key === 'gondrand_slide_mobile_fit') {
            $val = $val === 'cover' ? 'cover' : 'contain';
        }
        if ($key === 'gondrand_slide_mobile_pos') {
            $val = in_array($val, ['top', 'bottom', 'center'], true) ? $val : 'center';
        }
        if ($key === 'gondrand_slide_mobile_height') {
            $val = $val === '' ? '' : (string) max(160, min(900, (int) $val));
        }
        set_theme_mod($key, $val);
    }

    for ($i = 1; $i <= 6; $i++) {
        foreach (['image', 'title', 'text'] as $f) {
            $key = "gondrand_card_{$i}_{$f}";
            if (!isset($_POST[$key])) {
                continue;
            }
            $raw = wp_unslash($_POST[$key]);
            $val = $f === 'image' ? esc_url_raw($raw) : ($f === 'title' ? sanitize_text_field($raw) : sanitize_textarea_field($raw));
            set_theme_mod($key, $val);
        }
    }

    if (function_exists('gondrand_disable_root_html')) {
        gondrand_disable_root_html();
    }

    return 'saved';
}

add_action('admin_init', function () {
    if (!isset($_POST['gondrand_do_save'])) {
        return;
    }
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    check_admin_referer('gondrand_save_content');
    $status = gondrand_save_from_post();
    wp_safe_redirect(admin_url('admin.php?page=gondrand-content&' . $status . '=1'));
    exit;
});

function gondrand_admin_page() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    $slides = gondrand_get_slides();
    $view = home_url('/?nocache=' . time());
    $root_html = is_file(ABSPATH . 'index.html');
    ?>
    <div class="wrap">
      <h1>Travex — modifier le site</h1>
      <?php if (!empty($_GET['saved'])) : ?>
        <div class="notice notice-success is-dismissible">
          <p><strong>Enregistré.</strong> Ouvrez le site (sans cache) :
            <a href="<?php echo esc_url($view); ?>" target="_blank" rel="noopener">voir le site</a>
            — puis LiteSpeed → Purger tout.</p>
        </div>
      <?php endif; ?>
      <?php if (!empty($_GET['reset'])) : ?>
        <div class="notice notice-success is-dismissible"><p>Slider réinitialisé.</p></div>
      <?php endif; ?>
      <?php if ($root_html) : ?>
        <div class="notice notice-error"><p>Un fichier <code><?php echo esc_html(ABSPATH); ?>index.html</code> empêche WordPress d’afficher vos modifications. Supprimez-le dans le gestionnaire de fichiers LWS (htdocs), ou cliquez Enregistrer pour tenter de le renommer.</p></div>
      <?php endif; ?>
      <p><strong>Ici :</strong> accueil (slider, intro, cartes). Pour les autres pages : <a href="<?php echo esc_url(admin_url('admin.php?page=gondrand-pages')); ?>">Toutes les pages</a>. Cliquez ensuite sur <strong>Enregistrer et publier</strong>.</p>
      <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=gondrand-content')); ?>">
        <?php wp_nonce_field('gondrand_save_content'); ?>
        <input type="hidden" name="gondrand_do_save" value="1">

        <h2>Logo</h2>
        <p>
          <input type="url" class="large-text gondrand-image" name="gondrand_logo" id="gondrand_logo" value="<?php echo esc_attr(gondrand_mod('gondrand_logo') ?: (gondrand_assets() . 'images/logo-gondrand.png')); ?>">
          <button type="button" class="button gondrand-pick" data-target="gondrand_logo">Choisir une image</button>
        </p>

        <h2>Slider d’accueil</h2>
        <p class="description">Autant d’images que vous voulez. <strong>Supprimer ce slide</strong> l’enlève du site. <strong>Ajouter des images</strong> en prend plusieurs d’un coup.</p>
        <div id="gondrand-slides">
          <?php foreach ($slides as $i => $s) : ?>
            <?php echo gondrand_slide_row_html($i, $s); ?>
          <?php endforeach; ?>
        </div>
        <p>
          <button type="button" class="button button-secondary" id="gondrand-add-slide">+ Ajouter un slide vide</button>
          <button type="button" class="button button-primary" id="gondrand-add-many">+ Ajouter des images (médiathèque)</button>
        </p>

        <h3>Slider sur téléphone / Android</h3>
        <p class="description">Sur ordinateur l’image reste comme aujourd’hui. Sur téléphone, par défaut l’<strong>image entière</strong> s’affiche (sans zoom). Vous pouvez changer ce réglage ci-dessous.</p>
        <table class="form-table" role="presentation">
          <tr>
            <th>Affichage de l’image</th>
            <td>
              <?php $fit = gondrand_mod('gondrand_slide_mobile_fit') === 'cover' ? 'cover' : 'contain'; ?>
              <select name="gondrand_slide_mobile_fit">
                <option value="contain" <?php selected($fit, 'contain'); ?>>Image entière (recommandé, sans zoom)</option>
                <option value="cover" <?php selected($fit, 'cover'); ?>>Remplir le cadre (zoom / recadrage)</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>Position</th>
            <td>
              <?php $pos = gondrand_mod('gondrand_slide_mobile_pos'); if (!in_array($pos, ['top', 'bottom', 'center'], true)) { $pos = 'center'; } ?>
              <select name="gondrand_slide_mobile_pos">
                <option value="center" <?php selected($pos, 'center'); ?>>Centre</option>
                <option value="top" <?php selected($pos, 'top'); ?>>Haut</option>
                <option value="bottom" <?php selected($pos, 'bottom'); ?>>Bas</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>Hauteur max (px)</th>
            <td>
              <input type="number" class="small-text" name="gondrand_slide_mobile_height" min="160" max="900" placeholder="auto" value="<?php echo esc_attr(gondrand_mod('gondrand_slide_mobile_height')); ?>">
              <p class="description">Vide = hauteur automatique (toute l’image). Exemple : 280 pour limiter la hauteur.</p>
            </td>
          </tr>
        </table>

        <h2>Textes d’accueil</h2>
        <table class="form-table" role="presentation">
          <tr><th>Titre</th><td><input class="large-text" name="gondrand_home_h2" value="<?php echo esc_attr(gondrand_text('gondrand_home_h2', 'More Performance – More Success')); ?>"></td></tr>
          <tr><th>Sous-titre</th><td><input class="large-text" name="gondrand_home_h3" value="<?php echo esc_attr(gondrand_text('gondrand_home_h3', 'Logistique depuis 1866')); ?>"></td></tr>
          <tr><th>Paragraphe 1</th><td><textarea class="large-text" rows="4" name="gondrand_home_p1"><?php echo esc_textarea(gondrand_text('gondrand_home_p1', 'Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport. Nous donnons une touche personnelle à tout ce que nous faisons grâce à notre personnel, qui soutient ce service sur mesure. Ils s’adaptent à vos besoins et non l’inverse.')); ?></textarea></td></tr>
          <tr><th>Paragraphe 2</th><td><textarea class="large-text" rows="4" name="gondrand_home_p2"><?php echo esc_textarea(gondrand_text('gondrand_home_p2', 'Nous voulons apprendre à vous connaître – et vous devriez également nous connaître personnellement. Nous avons l’intention de créer une relation de confiance à long terme avec vous, comme nous le faisons avec tous nos clients depuis des années, voire des décennies.')); ?></textarea></td></tr>
          <tr>
            <th>Image (bloc vidéo)</th>
            <td>
              <input type="url" class="large-text gondrand-image" name="gondrand_home_video" id="gondrand_home_video" value="<?php echo esc_attr(gondrand_mod('gondrand_home_video') ?: (gondrand_assets() . 'images/hero-road.jpg')); ?>">
              <button type="button" class="button gondrand-pick" data-target="gondrand_home_video">Choisir une image</button>
            </td>
          </tr>
        </table>

        <h2>Cartes « Services spéciaux »</h2>
        <table class="form-table" role="presentation">
          <?php
          $card_defaults = [
              1 => ['About us', 'Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport.'],
              2 => ['Transport terrestre', 'Une organisation qui vous propose des services de porte à porte sur tout le territoire de l’Ancien Monde.'],
              3 => ['Fret aérien', 'Notre équipe de fret aérien vous rassure en sachant que vos marchandises sont entre de bonnes mains.'],
              4 => ['Fret maritime', 'Nos professionnels du fret maritime tirent parti de leur vaste expérience pour gérer les flux de marchandises.'],
              5 => ['Trafics spéciaux', 'L’activité inhérente au transport exceptionnel est adossée à un cabinet spécifique pour l’étude et la mise.'],
              6 => ['Douane', 'Ainsi, vos marchandises sont placées sous un régime suspensif de taxes, jusqu’à la destination finale que vous aurez choisie.'],
          ];
          foreach ($card_defaults as $i => $d) :
          ?>
            <tr>
              <th>Carte <?php echo (int) $i; ?></th>
              <td>
                <input class="large-text" name="gondrand_card_<?php echo (int) $i; ?>_title" value="<?php echo esc_attr(gondrand_text("gondrand_card_{$i}_title", $d[0])); ?>" placeholder="Titre">
                <textarea class="large-text" rows="2" name="gondrand_card_<?php echo (int) $i; ?>_text"><?php echo esc_textarea(gondrand_text("gondrand_card_{$i}_text", $d[1])); ?></textarea>
                <input type="url" class="large-text gondrand-image" name="gondrand_card_<?php echo (int) $i; ?>_image" id="gondrand_card_<?php echo (int) $i; ?>_image" value="<?php echo esc_attr(gondrand_mod("gondrand_card_{$i}_image")); ?>" placeholder="URL de l’image">
                <button type="button" class="button gondrand-pick" data-target="gondrand_card_<?php echo (int) $i; ?>_image">Choisir / remplacer l’image</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>

        <h2>Emplacements</h2>
        <p class="description">Ajoutez, modifiez ou supprimez les agences affichées sur l’accueil et la page Contact.</p>
        <div id="gondrand-locs">
          <?php foreach (gondrand_get_locations() as $loc) : ?>
            <?php echo gondrand_loc_row_html($loc); ?>
          <?php endforeach; ?>
        </div>
        <p><button type="button" class="button" id="gondrand-add-loc">+ Ajouter un emplacement</button></p>

        <h2>Emplacements & carte</h2>
        <table class="form-table" role="presentation">
          <tr><th>Titre emplacements</th><td><input class="large-text" name="gondrand_loc_title" value="<?php echo esc_attr(gondrand_loc_heading()); ?>"></td></tr>
          <tr><th>Texte emplacements</th><td><textarea class="large-text" rows="2" name="gondrand_loc_lead"><?php echo esc_textarea(gondrand_text('gondrand_loc_lead', 'Notre point de réception à Renchen, facilement accessible depuis l’Allemagne, la France et la Suisse.')); ?></textarea></td></tr>
          <tr><th>Titre services spéciaux</th><td><input class="large-text" name="gondrand_specials_title" value="<?php echo esc_attr(gondrand_text('gondrand_specials_title', 'Services spéciaux')); ?>"></td></tr>
          <tr><th>Texte services spéciaux</th><td><textarea class="large-text" rows="3" name="gondrand_specials_lead"><?php echo esc_textarea(gondrand_text('gondrand_specials_lead', 'En tant que membre d’un réseau d’investisseurs internationaux, nous disposons des ressources financières et logistiques nécessaires à la définition et à la réalisation des objectifs de nos clients, tout en les accompagnant tout au long du processus.')); ?></textarea></td></tr>
          <tr>
            <th>Image de la carte</th>
            <td>
              <input type="url" class="large-text gondrand-image" name="gondrand_map_image" id="gondrand_map_image" value="<?php echo esc_attr(gondrand_mod('gondrand_map_image') ?: (gondrand_assets() . 'images/hero-sea.jpg')); ?>">
              <button type="button" class="button gondrand-pick" data-target="gondrand_map_image">Choisir une image</button>
            </td>
          </tr>
        </table>

        <h2>Coordonnées</h2>
        <table class="form-table" role="presentation">
          <tr><th>Adresse</th><td><textarea class="large-text" rows="3" name="gondrand_address"><?php echo esc_textarea(gondrand_text('gondrand_address', "Im Brünnel 2\n77871 Renchen")); ?></textarea></td></tr>
          <tr><th>Téléphone</th><td><input class="regular-text" name="gondrand_phone" value="<?php echo esc_attr(gondrand_text('gondrand_phone', '+33 1 44 13 14 00')); ?>"></td></tr>
          <tr><th>E-mail affiché</th><td><input class="regular-text" type="email" name="gondrand_email" value="<?php echo esc_attr(gondrand_text('gondrand_email', 'accueil.dg@gondrand.fr')); ?>"></td></tr>
          <tr><th>E-mail des devis</th><td><input class="regular-text" type="email" name="gondrand_quote_email" value="<?php echo esc_attr(get_theme_mod('gondrand_quote_email', get_option('admin_email'))); ?>"></td></tr>
        </table>

        <?php submit_button('Enregistrer et publier sur le site'); ?>
        <p>
          <button type="submit" name="gondrand_reset" value="1" class="button" onclick="return confirm('Remettre le slider d’origine ?');">Réinitialiser le slider</button>
          <a class="button" href="<?php echo esc_url($view); ?>" target="_blank" rel="noopener">Voir le site</a>
        </p>
      </form>
    </div>
    <script>
    (function(){
      function bindPick(btn){
        btn.addEventListener('click', function(e){
          e.preventDefault();
          var id = this.getAttribute('data-target');
          var input = id ? document.getElementById(id) : this.parentNode.querySelector('.gondrand-image');
          var frame = wp.media({ title: 'Choisir une image', multiple: false, library: { type: 'image' } });
          frame.on('select', function(){
            var att = frame.state().get('selection').first().toJSON();
            if (input) input.value = att.url;
            var box = input && input.closest('.gondrand-slide');
            var prev = box ? box.querySelector('.gondrand-prev') : null;
            if (prev) { prev.src = att.url; prev.style.display = 'block'; }
          });
          frame.open();
        });
      }
      document.querySelectorAll('.gondrand-pick').forEach(bindPick);
      function addRow(data){
        var wrap = document.getElementById('gondrand-slides');
        var tmp = document.createElement('div');
        tmp.innerHTML = <?php echo wp_json_encode(gondrand_slide_row_html(99, ['image'=>'','title'=>'','text'=>'','url'=>''])); ?>;
        var row = tmp.firstElementChild;
        if (data && data.url) {
          row.querySelector('.gondrand-image').value = data.url;
          var prev = row.querySelector('.gondrand-prev');
          prev.src = data.url;
          prev.style.display = 'block';
          if (data.title) row.querySelector('[name="slide_title[]"]').value = data.title;
        }
        wrap.appendChild(row);
        row.querySelectorAll('.gondrand-pick').forEach(bindPick);
      }
      document.getElementById('gondrand-add-slide').addEventListener('click', function(){ addRow(null); });
      document.getElementById('gondrand-add-many').addEventListener('click', function(e){
        e.preventDefault();
        var frame = wp.media({ title: 'Ajouter des images au slider', multiple: true, library: { type: 'image' } });
        frame.on('select', function(){
          frame.state().get('selection').each(function(att){
            att = att.toJSON();
            addRow({ url: att.url, title: att.title || '' });
          });
        });
        frame.open();
      });
      document.getElementById('gondrand-slides').addEventListener('click', function(e){
        if (e.target.classList.contains('gondrand-del')) {
          e.preventDefault();
          var row = e.target.closest('.gondrand-slide');
          if (row) row.remove();
        }
      });
      document.getElementById('gondrand-add-loc').addEventListener('click', function(){
        var wrap = document.getElementById('gondrand-locs');
        var tmp = document.createElement('div');
        tmp.innerHTML = <?php echo wp_json_encode(gondrand_loc_row_html(['title'=>'','address'=>'','text'=>''])); ?>;
        wrap.appendChild(tmp.firstElementChild);
      });
      document.getElementById('gondrand-locs').addEventListener('click', function(e){
        if (e.target.classList.contains('gondrand-del-loc')) {
          e.preventDefault();
          var row = e.target.closest('.gondrand-loc');
          if (row) row.remove();
        }
      });
    })();
    </script>
    <style>
      .gondrand-slide { background:#fff; border:1px solid #c3c4c7; padding:16px; margin:12px 0; }
      .gondrand-slide img.gondrand-prev { max-height:90px; display:block; margin:8px 0; background:#f0f0f1; }
      .gondrand-slide textarea { width:100%; }
    </style>
    <?php
}

function gondrand_slide_row_html($i, $s) {
    $img = esc_attr($s['image'] ?? '');
    $title = esc_attr($s['title'] ?? '');
    $text = esc_textarea($s['text'] ?? '');
    $url = esc_attr($s['url'] ?? '');
    $src = esc_url($s['image'] ?? '');
    ob_start();
    ?>
    <div class="gondrand-slide">
      <p><strong>Image du slider</strong></p>
      <img class="gondrand-prev" src="<?php echo $src; ?>" alt="" <?php echo $src ? '' : 'style="display:none"'; ?>>
      <p>
        <input type="url" class="large-text gondrand-image" name="slide_image[]" value="<?php echo $img; ?>">
        <button type="button" class="button gondrand-pick">Choisir / remplacer l’image</button>
        <button type="button" class="button gondrand-del">Supprimer ce slide</button>
      </p>
      <p>Titre<br><input class="large-text" name="slide_title[]" value="<?php echo $title; ?>"></p>
      <p>Texte<br><textarea name="slide_text[]" rows="3"><?php echo $text; ?></textarea></p>
      <p>Lien (facultatif)<br><input class="large-text" name="slide_url[]" value="<?php echo $url; ?>"></p>
    </div>
    <?php
    return ob_get_clean();
}
