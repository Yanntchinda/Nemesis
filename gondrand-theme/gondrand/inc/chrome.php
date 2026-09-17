<?php
if (!defined('ABSPATH')) {
    exit;
}

function gondrand_home() {
    return trailingslashit(home_url('/'));
}

function gondrand_assets() {
    return trailingslashit(get_template_directory_uri() . '/site');
}

function gondrand_t($key, $fallback) {
    $v = gondrand_mod('gondrand_i18n_' . $key);
    return $v !== '' ? $v : $fallback;
}

function gondrand_u($path) {
    $home = gondrand_home();
    if (!$path || $path === 'index.html') {
        return $home;
    }
    return $home . ltrim($path, '/');
}

function gondrand_header_html($page = '') {
    $home = gondrand_home();
    $assets = gondrand_assets();
    $logo = gondrand_mod('gondrand_logo');
    if ($logo === '') {
        $logo = $assets . 'images/logo-gondrand.png';
    }
    $nav = [
        ['home', gondrand_t('nav_home', 'Accueil'), 'index.html'],
        ['entreprise', gondrand_t('nav_company', 'Entreprise'), 'entreprise/index.html'],
        ['devis', gondrand_t('nav_quote', 'Devis'), 'demande-de-cotation/index.html'],
        ['contact', gondrand_t('nav_contact', 'Contact'), 'contact/index.html'],
    ];
    $services = [
        [gondrand_t('svc_road', 'Transport terrestre'), 'services/luftfracht-2/'],
        [gondrand_t('svc_air', 'Fret aérien'), 'services/ueber-uns/'],
        [gondrand_t('svc_sea', 'Fret maritime'), 'services/beratung-2/'],
        [gondrand_t('svc_special', 'Trafics spéciaux'), 'services/seefracht-2/'],
        [gondrand_t('svc_customs', 'Douane'), 'services/zoll-2/'],
        [gondrand_t('svc_vat', 'TVA / Représentation fiscale'), 'representation-fiscale/index.html'],
    ];
    $links = '';
    foreach ($nav as $item) {
        if ($item[0] === 'devis') {
            $links .= '<div class="drop"><span>' . esc_html(gondrand_t('nav_services', 'Services')) . ' ▾</span><div class="drop-menu">';
            foreach ($services as $s) {
                $links .= '<a href="' . esc_url(gondrand_u($s[1])) . '">' . esc_html($s[0]) . '</a>';
            }
            $links .= '</div></div>';
        }
        $active = $item[0] === $page ? ' active' : '';
        $links .= '<a class="' . trim($active) . '" href="' . esc_url(gondrand_u($item[2])) . '">' . esc_html($item[1]) . '</a>';
    }
    $addr = gondrand_mod('gondrand_email');
    ob_start();
    ?>
    <div class="topbar">
      <div class="wrap">
        <div class="lang">
          <button type="button" class="lang-btn on" data-lang="fr">FR</button>
          <button type="button" class="lang-btn" data-lang="en">EN</button>
        </div>
        <div class="top-links">
          <a href="<?php echo esc_url(gondrand_u('demande-de-cotation/index.html')); ?>"><?php echo esc_html(gondrand_t('nav_rfq', 'Demande de cotation')); ?></a>
        </div>
      </div>
    </div>
    <header class="header">
      <div class="wrap">
        <a class="logo" href="<?php echo esc_url($home); ?>" aria-label="Gondrand accueil">
          <img src="<?php echo esc_url($logo); ?>" alt="GONDRAND">
        </a>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
        <nav class="nav" id="nav"><?php echo $links; ?></nav>
      </div>
    </header>
    <?php
    return ob_get_clean();
}

function gondrand_footer_html() {
    $phone = gondrand_mod('gondrand_phone');
    if ($phone === '') {
        $phone = '+33 1 44 13 14 00';
    }
    $email = gondrand_mod('gondrand_email');
    if ($email === '') {
        $email = 'accueil.dg@gondrand.fr';
    }
    $addr = gondrand_mod('gondrand_address');
    if ($addr === '') {
        $addr = '11 rue de Lübeck<br>75116 Paris';
    } else {
        $addr = nl2br(esc_html($addr));
    }
    ob_start();
    ?>
    <section class="group">
      <div class="wrap">
        <h2><?php echo esc_html(gondrand_t('group', "NOTRE GROUPE D'ENTREPRISES")); ?></h2>
        <div class="brands">
          <a class="brand" href="<?php echo esc_url(gondrand_home()); ?>">GONDRAND<small>FRANCE</small></a>
          <a class="brand ngl" href="https://ngl-germany.eu/" target="_blank" rel="noopener">NGL<small>GERMANY</small></a>
          <a class="brand mon" href="https://www.monnard.com/" target="_blank" rel="noopener">MONNARD<small>SPEDITION</small></a>
          <a class="brand mf" href="https://www.monfreight.com/" target="_blank" rel="noopener">MONFREIGHT<small>INC.</small></a>
          <a class="brand cf" href="https://www.cargoflores.com/en/" target="_blank" rel="noopener">CARGO FLORES<small>NGL GROUP</small></a>
        </div>
      </div>
    </section>
    <footer class="footer">
      <div class="wrap">
        <div class="fgrid">
          <div>
            <h4><?php echo esc_html(gondrand_t('downloads', 'Téléchargements')); ?></h4>
            <ul>
              <li><a href="#"><?php echo esc_html(gondrand_t('terms', 'Conditions générales')); ?></a></li>
              <li><a href="#"><?php echo esc_html(gondrand_t('containers', 'Dimensions conteneurs')); ?></a></li>
              <li><a href="#"><?php echo esc_html(gondrand_t('ges', 'Bilan émissions GES')); ?></a></li>
              <li><a href="#"><?php echo esc_html(gondrand_t('airlines', 'Codes compagnies aériennes')); ?></a></li>
              <li><a href="#"><?php echo esc_html(gondrand_t('vat_ecom', 'TVA & E-Commerce')); ?></a></li>
              <li><a href="#"><?php echo esc_html(gondrand_t('customs_form', 'Douanes & formalités')); ?></a></li>
            </ul>
          </div>
          <div>
            <h4><?php echo esc_html(gondrand_t('network', 'Réseau Gondrand')); ?></h4>
            <div class="countries">
              <span>Belgique</span><span>Chine</span><span>Tchéquie</span>
              <span>France</span><span>Allemagne (NGL)</span><span>Allemagne (Monnard)</span>
              <span>Luxembourg</span><span>Mexique</span><span>Maroc</span>
              <span>Pays-Bas</span><span>Nouvelle-Calédonie</span><span>Sénégal</span>
              <span>Espagne</span><span>Suisse</span><span>Tahiti</span>
              <span>Turquie</span><span>Royaume-Uni</span><span>USA</span>
            </div>
          </div>
          <div>
            <h4><?php echo esc_html(gondrand_t('hq', 'Siège')); ?></h4>
            <p><?php echo $addr; ?><br><?php echo esc_html(gondrand_t('tel', 'Tél.')); ?> <?php echo esc_html($phone); ?><br>
            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
            <p style="margin-top:12px"><a href="<?php echo esc_url(gondrand_u('mentions-legales/index.html')); ?>"><?php echo esc_html(gondrand_t('legal', 'Mentions légales')); ?></a><br>
            <a href="<?php echo esc_url(gondrand_u('mentions-legales/index.html')); ?>#privacy"><?php echo esc_html(gondrand_t('privacy', 'Politique de confidentialité')); ?></a></p>
          </div>
        </div>
        <div class="copy">
          <div class="certs"><span>IATA</span><span>OEA</span><span>ISO 9001</span></div>
          <div>© 2026 NGL Gondrand Group SA • Tous droits réservés.</div>
          <div>Logistique depuis 1866</div>
        </div>
      </div>
    </footer>
    <?php
    return ob_get_clean();
}

function gondrand_absolutize_assets($html) {
    $assets = gondrand_assets();
    $home = gondrand_home();
    $map = [
        '../../css/' => $assets . 'css/',
        '../../js/' => $assets . 'js/',
        '../../images/' => $assets . 'images/',
        '../css/' => $assets . 'css/',
        '../js/' => $assets . 'js/',
        '../images/' => $assets . 'images/',
        'href="css/' => 'href="' . $assets . 'css/',
        'href="images/' => 'href="' . $assets . 'images/',
        'src="js/' => 'src="' . $assets . 'js/',
        'src="images/' => 'src="' . $assets . 'images/',
        "url('images/" => "url('" . $assets . 'images/',
        'url("images/' => 'url("' . $assets . 'images/',
        "url('images/" => "url('" . $assets . 'images/',
    ];
    $html = strtr($html, $map);
    $html = preg_replace(
        '/<script>\s*window\.BASE\s*=\s*["\'][^"\']*["\']\s*;/',
        '<script>window.BASE=' . wp_json_encode($home) . ';window.GONDRAND_ASSETS=' . wp_json_encode($assets) . ';',
        $html
    );
    return $html;
}

function gondrand_inject_chrome($html, $path) {
    $page = '';
    if (strpos($path, 'entreprise') !== false) {
        $page = 'entreprise';
    } elseif (strpos($path, 'contact') !== false) {
        $page = 'contact';
    } elseif (strpos($path, 'demande-de-cotation') !== false || strpos($path, 'devis') !== false) {
        $page = 'devis';
    } elseif ($path === '/' || $path === '/index.html') {
        $page = 'home';
    }
    $html = preg_replace('#<div id="site-header"></div>#', gondrand_header_html($page), $html, 1);
    $html = preg_replace('#<div id="site-footer"></div>#', gondrand_footer_html(), $html, 1);
    return $html;
}
