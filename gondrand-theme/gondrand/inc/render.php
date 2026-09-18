<?php
if (!defined('ABSPATH')) {
    exit;
}

function gondrand_text($key, $default) {
    $v = gondrand_mod($key);
    return $v !== '' ? $v : $default;
}

function gondrand_render_home() {
    $assets = gondrand_assets();
    $home   = gondrand_home();
    $slides = gondrand_build_slides_html(gondrand_get_slides());
    $h2  = gondrand_text('gondrand_home_h2', 'More Performance – More Success');
    $h3  = gondrand_text('gondrand_home_h3', 'Logistique depuis 1866');
    $p1  = gondrand_text('gondrand_home_p1', 'Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport. Nous donnons une touche personnelle à tout ce que nous faisons grâce à notre personnel, qui soutient ce service sur mesure. Ils s’adaptent à vos besoins et non l’inverse.');
    $p2  = gondrand_text('gondrand_home_p2', 'Nous voulons apprendre à vous connaître – et vous devriez également nous connaître personnellement. Nous avons l’intention de créer une relation de confiance à long terme avec vous, comme nous le faisons avec tous nos clients depuis des années, voire des décennies.');
    $vid = gondrand_mod('gondrand_home_video');
    if ($vid === '') {
        $vid = $assets . 'images/hero-road.jpg';
    }

    status_header(200);
    nocache_headers();
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Gondrand-Theme: 2.2.0');
    header('X-LiteSpeed-Cache-Control: no-cache');

    $head = gondrand_head_inject();
    $foot = gondrand_footer_inject();
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TRAVEX GLOBAL FORWARDING | LOGISTICS • MORE PERFORMANCE – MORE SUCCESS</title>
  <meta name="description" content="Travex Global Forwarding, logistique depuis 1866.">
  <link rel="icon" href="<?php echo esc_url($assets . 'images/logo-gondrand.png'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo esc_url($assets . 'css/style.css'); ?>">
  <!-- gondrand-theme 2.1.1 php-home -->
  <?php echo $head; ?>
</head>
<body>
  <?php echo gondrand_header_html('home'); ?>

  <section class="hero" id="accueil">
    <div class="slides"><?php echo $slides; ?></div>
    <div class="hero-nav">
      <button class="prev" type="button" aria-label="Précédent">‹</button>
      <button class="next" type="button" aria-label="Suivant">›</button>
    </div>
  </section>

  <section class="section">
    <div class="wrap split">
      <div class="prose">
        <div class="bar"></div>
        <h2><?php echo esc_html($h2); ?></h2>
        <h3 style="font-weight:600;color:#64748b;margin-top:0"><?php echo esc_html($h3); ?></h3>
        <p><?php echo esc_html($p1); ?></p>
        <p><?php echo esc_html($p2); ?></p>
      </div>
      <div>
        <div class="video-box">
          <img src="<?php echo esc_url($vid); ?>" alt="Travex Global Forwarding logistique">
          <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(201,168,76,.9);display:grid;place-items:center;font-size:22px;color:#062544">▶</div>
            <small style="letter-spacing:.16em;text-transform:uppercase">Travex Global Forwarding</small>
          </div>
        </div>
        <p style="margin-top:10px;font-size:13px;color:#64748b">GRANDE VALEUR · RAPIDE · IMPORTANT</p>
      </div>
    </div>
  </section>

  <section class="section alt" id="emplacements">
    <div class="wrap">
      <div class="bar"></div>
      <h2><?php echo esc_html(gondrand_text('gondrand_loc_title', 'TRAVEX GLOBAL FORWARDING – EMPLACEMENTS')); ?></h2>
      <p class="lead"><?php echo esc_html(gondrand_text('gondrand_loc_lead', 'Notre point de réception à Renchen, facilement accessible depuis l’Allemagne, la France et la Suisse.')); ?></p>
      <?php echo gondrand_locations_html(); ?>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="bar"></div>
      <h2><?php echo esc_html(gondrand_text('gondrand_specials_title', 'Services spéciaux')); ?></h2>
      <p class="lead"><?php echo esc_html(gondrand_text('gondrand_specials_lead', 'En tant que membre d’un réseau d’investisseurs internationaux, nous disposons des ressources financières et logistiques nécessaires à la définition et à la réalisation des objectifs de nos clients, tout en les accompagnant tout au long du processus.')); ?></p>
      <div class="cards">
        <?php echo gondrand_home_cards_html(); ?>
      </div>
    </div>
  </section>

  <section class="section alt">
    <div class="wrap split">
      <div id="special-slot"></div>
      <div id="quote-slot"></div>
    </div>
  </section>

  <section class="map">
    <img src="<?php echo esc_url(gondrand_mod('gondrand_map_image') ?: ($assets . 'images/hero-sea.jpg')); ?>" alt="Réseau mondial">
    <span class="pin" style="left:48%;top:38%"></span>
    <span class="pin" style="left:62%;top:44%"></span>
    <span class="pin" style="left:35%;top:52%"></span>
    <span class="pin" style="left:71%;top:36%"></span>
    <div class="map-card">
      <h3>TRAVEX GLOBAL FORWARDING</h3>
      <p><?php echo esc_html(preg_replace('/\s+/', ' ', wp_strip_all_tags(gondrand_text('gondrand_address', 'Im Brünnel 2, 77871 Renchen')))); ?></p>
      <?php if (gondrand_mod('gondrand_phone') !== '') : ?>
      <p>Tél. <?php echo esc_html(gondrand_mod('gondrand_phone')); ?></p>
      <?php endif; ?>
      <a class="btn" href="<?php echo esc_url(gondrand_u('contact/index.html')); ?>" style="margin-top:12px">Toutes les agences</a>
    </div>
  </section>

  <?php echo gondrand_footer_html(); ?>
  <script>window.BASE=<?php echo wp_json_encode($home); ?>;window.GONDRAND_ASSETS=<?php echo wp_json_encode($assets); ?>;window.GONDRAND_LOCS=<?php echo wp_json_encode(gondrand_locations_payload()); ?>;window.PAGE="home";</script>
  <script src="<?php echo esc_url($assets . 'js/main.js'); ?>"></script>
  <script>
    document.getElementById("special-slot").innerHTML = Travex.specialHTML();
    document.getElementById("quote-slot").innerHTML = Travex.quoteHTML(true);
  </script>
  <?php echo $foot; ?>
</body>
</html>
    <?php
    exit;
}

function gondrand_home_cards_html() {
    $assets = gondrand_assets();
    $cards = [
        [1, 'hero-customs.jpg', 'About us', 'Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport.', gondrand_u('entreprise/index.html')],
        [2, 'hero-road.jpg', 'Transport terrestre', 'Une organisation qui vous propose des services de porte à porte sur tout le territoire de l’Ancien Monde.', gondrand_u('services/luftfracht-2/')],
        [3, 'hero-air.jpg', 'Fret aérien', 'Notre équipe de fret aérien vous rassure en sachant que vos marchandises sont entre de bonnes mains.', gondrand_u('services/ueber-uns/')],
        [4, 'hero-sea.jpg', 'Fret maritime', 'Nos professionnels du fret maritime tirent parti de leur vaste expérience pour gérer les flux de marchandises.', gondrand_u('services/beratung-2/')],
        [5, 'hero-special.jpg', 'Trafics spéciaux', 'L’activité inhérente au transport exceptionnel est adossée à un cabinet spécifique pour l’étude et la mise.', gondrand_u('services/seefracht-2/')],
        [6, 'hero-fiscal.jpg', 'Douane', 'Ainsi, vos marchandises sont placées sous un régime suspensif de taxes, jusqu’à la destination finale que vous aurez choisie.', gondrand_u('douane/index.html')],
    ];
    $html = '';
    foreach ($cards as $c) {
        $img = gondrand_mod("gondrand_card_{$c[0]}_image");
        if ($img === '') {
            $img = $assets . 'images/' . $c[1];
        }
        $title = gondrand_text("gondrand_card_{$c[0]}_title", $c[2]);
        $text  = gondrand_text("gondrand_card_{$c[0]}_text", $c[3]);
        $html .= '<article class="card" data-card="' . $c[0] . '">';
        $html .= '<img src="' . esc_url($img) . '" alt="' . esc_attr($title) . '">';
        $html .= '<div class="body"><h3>' . esc_html($title) . '</h3>';
        $html .= '<p>' . esc_html($text) . '</p>';
        $html .= '<a class="more" href="' . esc_url($c[4]) . '">Lire la suite →</a></div></article>';
    }
    return $html;
}

function gondrand_disable_root_html() {
    $moved = [];
    foreach (['index.html', 'index.htm', 'home.html'] as $name) {
        $file = ABSPATH . $name;
        if (is_file($file) && is_writable($file)) {
            $dest = ABSPATH . $name . '.gondrand-bak';
            if (@rename($file, $dest)) {
                $moved[] = $name;
            }
        }
    }
    if ($moved) {
        update_option('gondrand_moved_index_html', implode(', ', $moved), false);
    }
    return $moved;
}
