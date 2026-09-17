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
    if ($slides === false) {
        return gondrand_default_slides();
    }
    if (!is_array($slides)) {
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

add_action('admin_menu', function () {
    add_menu_page(
        'Gondrand',
        'Gondrand',
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

add_action('admin_post_gondrand_save_content', function () {
    if (!current_user_can('edit_theme_options')) {
        wp_die('Accès refusé');
    }
    check_admin_referer('gondrand_save_content');

    if (!empty($_POST['gondrand_reset'])) {
        delete_option('gondrand_slides');
        wp_safe_redirect(admin_url('admin.php?page=gondrand-content&reset=1'));
        exit;
    }

    $images = isset($_POST['slide_image']) ? (array) $_POST['slide_image'] : [];
    $titles = isset($_POST['slide_title']) ? (array) $_POST['slide_title'] : [];
    $texts  = isset($_POST['slide_text']) ? (array) $_POST['slide_text'] : [];
    $urls   = isset($_POST['slide_url']) ? (array) $_POST['slide_url'] : [];

    $slides = [];
    foreach ($images as $i => $img) {
        $img = esc_url_raw(trim((string) $img));
        if ($img === '') {
            continue;
        }
        $slides[] = [
            'image' => $img,
            'title' => sanitize_text_field($titles[$i] ?? ''),
            'text'  => sanitize_textarea_field($texts[$i] ?? ''),
            'url'   => esc_url_raw($urls[$i] ?? ''),
        ];
    }
    update_option('gondrand_slides', $slides, false);

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
    ];
    foreach ($map as $key => $cb) {
        if (!isset($_POST[$key])) {
            continue;
        }
        $val = $cb(wp_unslash($_POST[$key]));
        set_theme_mod($key, $val);
    }

    wp_safe_redirect(admin_url('admin.php?page=gondrand-content&saved=1'));
    exit;
});

function gondrand_admin_page() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    $slides = gondrand_get_slides();
    ?>
    <div class="wrap">
      <h1>Gondrand — contenu du site</h1>
      <?php if (!empty($_GET['saved'])) : ?>
        <div class="notice notice-success is-dismissible"><p>Enregistré. Videz le cache (LiteSpeed) puis ouvrez le site.</p></div>
      <?php endif; ?>
      <?php if (!empty($_GET['reset'])) : ?>
        <div class="notice notice-success is-dismissible"><p>Slider réinitialisé.</p></div>
      <?php endif; ?>
      <p>Ajoutez, remplacez ou supprimez les images du slider. Un champ vide n’affiche rien. Cliquez sur <strong>Enregistrer</strong>.</p>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('gondrand_save_content'); ?>
        <input type="hidden" name="action" value="gondrand_save_content">

        <h2>Logo</h2>
        <p>
          <input type="url" class="large-text gondrand-image" name="gondrand_logo" id="gondrand_logo" value="<?php echo esc_attr(gondrand_mod('gondrand_logo')); ?>">
          <button type="button" class="button gondrand-pick" data-target="gondrand_logo">Choisir une image</button>
        </p>

        <h2>Slider d’accueil</h2>
        <p class="description">Autant de slides que vous voulez. L’ordre est celui de la liste.</p>
        <div id="gondrand-slides">
          <?php foreach ($slides as $i => $s) : ?>
            <?php gondrand_slide_row($i, $s); ?>
          <?php endforeach; ?>
        </div>
        <p>
          <button type="button" class="button button-secondary" id="gondrand-add-slide">+ Ajouter une image</button>
        </p>

        <h2>Textes d’accueil</h2>
        <table class="form-table">
          <tr><th>Titre</th><td><input class="large-text" name="gondrand_home_h2" value="<?php echo esc_attr(gondrand_mod('gondrand_home_h2')); ?>"></td></tr>
          <tr><th>Sous-titre</th><td><input class="large-text" name="gondrand_home_h3" value="<?php echo esc_attr(gondrand_mod('gondrand_home_h3')); ?>"></td></tr>
          <tr><th>Paragraphe 1</th><td><textarea class="large-text" rows="4" name="gondrand_home_p1"><?php echo esc_textarea(gondrand_mod('gondrand_home_p1')); ?></textarea></td></tr>
          <tr><th>Paragraphe 2</th><td><textarea class="large-text" rows="4" name="gondrand_home_p2"><?php echo esc_textarea(gondrand_mod('gondrand_home_p2')); ?></textarea></td></tr>
          <tr>
            <th>Image vidéo</th>
            <td>
              <input type="url" class="large-text gondrand-image" name="gondrand_home_video" id="gondrand_home_video" value="<?php echo esc_attr(gondrand_mod('gondrand_home_video')); ?>">
              <button type="button" class="button gondrand-pick" data-target="gondrand_home_video">Choisir une image</button>
            </td>
          </tr>
        </table>

        <h2>Coordonnées</h2>
        <table class="form-table">
          <tr><th>Adresse</th><td><textarea class="large-text" rows="3" name="gondrand_address"><?php echo esc_textarea(gondrand_mod('gondrand_address')); ?></textarea></td></tr>
          <tr><th>Téléphone</th><td><input class="regular-text" name="gondrand_phone" value="<?php echo esc_attr(gondrand_mod('gondrand_phone')); ?>"></td></tr>
          <tr><th>E-mail affiché</th><td><input class="regular-text" type="email" name="gondrand_email" value="<?php echo esc_attr(gondrand_mod('gondrand_email')); ?>"></td></tr>
          <tr><th>E-mail des devis</th><td><input class="regular-text" type="email" name="gondrand_quote_email" value="<?php echo esc_attr(get_theme_mod('gondrand_quote_email', get_option('admin_email'))); ?>"></td></tr>
        </table>

        <?php submit_button('Enregistrer et publier sur le site'); ?>
        <p>
          <button type="submit" name="gondrand_reset" value="1" class="button" onclick="return confirm('Remettre le slider d’origine ?');">Réinitialiser le slider</button>
        </p>
      </form>
    </div>
    <script>
    (function($){
      function bindPick(btn){
        btn.addEventListener('click', function(e){
          e.preventDefault();
          var id = this.getAttribute('data-target');
          var input = id ? document.getElementById(id) : this.parentNode.querySelector('.gondrand-image');
          var frame = wp.media({ title: 'Choisir une image', multiple: false, library: { type: 'image' } });
          frame.on('select', function(){
            var att = frame.state().get('selection').first().toJSON();
            if (input) input.value = att.url;
            var prev = input && input.parentNode.querySelector('.gondrand-prev');
            if (prev) prev.src = att.url;
          });
          frame.open();
        });
      }
      document.querySelectorAll('.gondrand-pick').forEach(bindPick);
      document.getElementById('gondrand-add-slide').addEventListener('click', function(){
        var wrap = document.getElementById('gondrand-slides');
        var tmp = document.createElement('div');
        tmp.innerHTML = <?php echo wp_json_encode(gondrand_slide_row_html(99, ['image'=>'','title'=>'','text'=>'','url'=>''])); ?>;
        var row = tmp.firstElementChild;
        wrap.appendChild(row);
        row.querySelectorAll('.gondrand-pick').forEach(bindPick);
      });
      document.getElementById('gondrand-slides').addEventListener('click', function(e){
        if (e.target.classList.contains('gondrand-del')) {
          e.preventDefault();
          var row = e.target.closest('.gondrand-slide');
          if (row) row.remove();
        }
      });
    })(jQuery);
    </script>
    <style>
      .gondrand-slide { background:#fff; border:1px solid #c3c4c7; padding:16px; margin:12px 0; }
      .gondrand-slide img.gondrand-prev { max-height:90px; display:block; margin:8px 0; }
      .gondrand-slide textarea { width:100%; }
    </style>
    <?php
}

function gondrand_slide_row($i, $s) {
    echo gondrand_slide_row_html($i, $s);
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
      <?php if ($src) : ?><img class="gondrand-prev" src="<?php echo $src; ?>" alt=""><?php else : ?><img class="gondrand-prev" src="" alt="" style="display:none"><?php endif; ?>
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
        $url = $s['url'] !== '' ? esc_url($s['url']) : '#';
        $html .= '<article class="slide' . $active . '" style="background-image:url(\'' . $img . '\')" data-slide="' . ($i + 1) . '">';
        $html .= '<div class="wrap slide-inner">';
        $html .= '<h2>' . $title . '</h2>';
        $html .= '<div class="tagline">More Performance – More Success</div>';
        $html .= '<p>' . $text . '</p>';
        if ($s['url'] !== '') {
            $html .= '<a class="more" href="' . $url . '">Lire la suite : →</a>';
        }
        $html .= '</div></article>';
    }
    return $html;
}
