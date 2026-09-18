<?php
if (!defined('ABSPATH')) {
    exit;
}

function gondrand_catalog() {
    return [
        'home' => ['label' => 'Accueil', 'file' => 'index.html', 'path' => '/', 'home' => true, 'i18n' => 'nav_home'],
        'entreprise' => ['label' => 'Entreprise', 'file' => 'entreprise/index.html', 'path' => 'entreprise/', 'i18n' => 'nav_company'],
        'contact' => ['label' => 'Contact', 'file' => 'contact/index.html', 'path' => 'contact/', 'i18n' => 'nav_contact'],
        'demande-de-cotation' => ['label' => 'Demande de cotation', 'file' => 'demande-de-cotation/index.html', 'path' => 'demande-de-cotation/', 'i18n' => 'nav_rfq'],
        'services' => ['label' => 'Services', 'file' => 'services/index.html', 'path' => 'services/', 'i18n' => 'nav_services'],
        'luftfracht-2' => ['label' => 'Transport terrestre', 'file' => 'services/luftfracht-2/index.html', 'path' => 'services/luftfracht-2/', 'i18n' => 'svc_road'],
        'ueber-uns' => ['label' => 'Fret aérien', 'file' => 'services/ueber-uns/index.html', 'path' => 'services/ueber-uns/', 'i18n' => 'svc_air'],
        'beratung-2' => ['label' => 'Fret maritime', 'file' => 'services/beratung-2/index.html', 'path' => 'services/beratung-2/', 'i18n' => 'svc_sea'],
        'seefracht-2' => ['label' => 'Trafics spéciaux', 'file' => 'services/seefracht-2/index.html', 'path' => 'services/seefracht-2/', 'i18n' => 'svc_special'],
        'zoll-2' => ['label' => 'Douane (service)', 'file' => 'services/zoll-2/index.html', 'path' => 'services/zoll-2/', 'i18n' => 'svc_customs'],
        'logistik-2' => ['label' => 'About us', 'file' => 'services/logistik-2/index.html', 'path' => 'services/logistik-2/', 'i18n' => 'about_us'],
        'representation-fiscale' => ['label' => 'Représentation fiscale', 'file' => 'representation-fiscale/index.html', 'path' => 'representation-fiscale/', 'i18n' => 'svc_vat'],
        'mentions-legales' => ['label' => 'Mentions légales', 'file' => 'mentions-legales/index.html', 'path' => 'mentions-legales/', 'i18n' => 'legal'],
    ];
}

function gondrand_page_name($slug) {
    $saved = get_option('gondrand_page_names', []);
    if (is_array($saved) && isset($saved[$slug])) {
        $name = trim((string) $saved[$slug]);
        if ($name !== '') {
            return $name;
        }
    }
    $cat = gondrand_catalog();
    if (isset($cat[$slug]['i18n']) && function_exists('gondrand_t')) {
        return gondrand_t($cat[$slug]['i18n'], $cat[$slug]['label']);
    }
    return $cat[$slug]['label'] ?? $slug;
}

function gondrand_save_page_name($slug, $name) {
    $name = sanitize_text_field($name);
    $saved = get_option('gondrand_page_names', []);
    if (!is_array($saved)) {
        $saved = [];
    }
    if ($name === '') {
        unset($saved[$slug]);
    } else {
        $saved[$slug] = $name;
    }
    update_option('gondrand_page_names', $saved, false);
    $cat = gondrand_catalog();
    if ($name !== '' && isset($cat[$slug]['i18n'])) {
        set_theme_mod('gondrand_i18n_' . $cat[$slug]['i18n'], $name);
    }
    if ($slug === 'demande-de-cotation' && $name !== '') {
        set_theme_mod('gondrand_i18n_nav_rfq', $name);
    }
}

function gondrand_path_to_slug($path) {
    $path = trim((string) $path, '/');
    $path = preg_replace('#/index\\.html$#', '', $path);
    if ($path === '' || $path === 'index.html' || $path === 'index.php') {
        return 'home';
    }
    foreach (gondrand_catalog() as $slug => $page) {
        $p = trim($page['path'], '/');
        if ($p === $path || $page['file'] === $path || $page['file'] === $path . '/index.html') {
            return $slug;
        }
        if (basename($p) === basename($path) && $p !== '') {
            return $slug;
        }
    }
    return basename($path);
}

function gondrand_page_option_key($slug) {
    return 'gondrand_pagebody_' . preg_replace('/[^a-z0-9_-]/i', '', $slug);
}

function gondrand_extract_body($html) {
    if (preg_match('#<div id="site-header"></div>(.*?)<div id="site-footer"></div>#s', $html, $m)) {
        return $m[1];
    }
    return $html;
}

function gondrand_file_body($slug) {
    $cat = gondrand_catalog();
    if (!isset($cat[$slug])) {
        return '';
    }
    $file = get_template_directory() . '/site/' . $cat[$slug]['file'];
    if (!is_readable($file)) {
        return '';
    }
    return gondrand_extract_body((string) file_get_contents($file));
}

function gondrand_get_page_body($slug) {
    $saved = get_option(gondrand_page_option_key($slug), '');
    if (is_string($saved) && trim($saved) !== '') {
        $body = $saved;
    } else {
        $body = gondrand_file_body($slug);
    }
    if ($slug === 'entreprise' && function_exists('gondrand_apply_timeline')) {
        $body = gondrand_apply_timeline($body);
    }
    return $body;
}

function gondrand_kses_page($html) {
    $allowed = wp_kses_allowed_html('post');
    foreach (['section', 'article', 'nav', 'header', 'footer', 'main'] as $tag) {
        $allowed[$tag] = [
            'class' => true,
            'id'    => true,
            'style' => true,
        ];
    }
    foreach (['div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'a', 'img', 'ul', 'ol', 'li', 'form', 'input', 'select', 'option', 'textarea', 'label', 'button', 'small', 'b', 'strong', 'em', 'br'] as $tag) {
        if (!isset($allowed[$tag])) {
            $allowed[$tag] = [];
        }
        $allowed[$tag]['class'] = true;
        $allowed[$tag]['id'] = true;
        $allowed[$tag]['style'] = true;
    }
    $allowed['img']['src'] = true;
    $allowed['img']['alt'] = true;
    $allowed['img']['width'] = true;
    $allowed['img']['height'] = true;
    $allowed['a']['href'] = true;
    $allowed['a']['target'] = true;
    $allowed['a']['rel'] = true;
    $allowed['input']['type'] = true;
    $allowed['input']['name'] = true;
    $allowed['input']['value'] = true;
    $allowed['input']['required'] = true;
    $allowed['input']['placeholder'] = true;
    $allowed['select']['name'] = true;
    $allowed['select']['required'] = true;
    $allowed['option']['value'] = true;
    $allowed['textarea']['name'] = true;
    $allowed['textarea']['rows'] = true;
    $allowed['form']['action'] = true;
    $allowed['form']['method'] = true;
    $allowed['form']['id'] = true;
    $allowed['button']['type'] = true;
    $allowed['button']['class'] = true;
    $allowed['label']['class'] = true;
    return wp_kses($html, $allowed);
}

function gondrand_load_fragment($html) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML(
        '<?xml encoding="UTF-8"><html><body><div id="gwrap">' . $html . '</div></body></html>'
    );
    libxml_clear_errors();
    return $dom;
}

function gondrand_fragment_html($dom) {
    $wrap = $dom->getElementById('gwrap');
    if (!$wrap) {
        return '';
    }
    $out = '';
    foreach ($wrap->childNodes as $child) {
        $out .= $dom->saveHTML($child);
    }
    return $out;
}

function gondrand_inner_html($el) {
    $html = '';
    foreach ($el->childNodes as $child) {
        $html .= $el->ownerDocument->saveHTML($child);
    }
    return $html;
}

function gondrand_set_inner($el, $html) {
    while ($el->firstChild) {
        $el->removeChild($el->firstChild);
    }
    $html = (string) $html;
    if ($html === '') {
        return;
    }
    $tmp = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $tmp->loadHTML('<?xml encoding="UTF-8"><div id="tmp">' . $html . '</div>');
    libxml_clear_errors();
    $src = $tmp->getElementById('tmp');
    if (!$src) {
        $el->appendChild($el->ownerDocument->createTextNode(wp_strip_all_tags($html)));
        return;
    }
    foreach ($src->childNodes as $child) {
        $el->appendChild($el->ownerDocument->importNode($child, true));
    }
}

function gondrand_page_text_nodes($dom) {
    $xpath = new DOMXPath($dom);
    $query = "//h1|//h2|//h3|//h4|//p|//li"
        . "|//div[contains(concat(' ', normalize-space(@class), ' '), ' kicker ')]"
        . "|//*[contains(concat(' ', normalize-space(@class), ' '), ' stat ')]/strong"
        . "|//*[contains(concat(' ', normalize-space(@class), ' '), ' stat ')]/span"
        . "|//*[contains(concat(' ', normalize-space(@class), ' '), ' mod ')]/b";
    $out = [];
    foreach ($xpath->query($query) as $el) {
        if (!($el instanceof DOMElement)) {
            continue;
        }
        if (gondrand_has_ancestor_tag($el, ['script', 'form', 'nav', 'button'])) {
            continue;
        }
        if (gondrand_has_class_ancestor($el, 'timeline') || gondrand_has_class_ancestor($el, 'tl')) {
            continue;
        }
        $class = $el->getAttribute('class');
        if (strpos($class, 'crumbs') !== false) {
            continue;
        }
        $plain = trim(preg_replace('/\s+/', ' ', $el->textContent));
        if ($plain === '') {
            continue;
        }
        $out[] = $el;
    }
    return $out;
}

function gondrand_page_image_nodes($dom) {
    $xpath = new DOMXPath($dom);
    $out = [];
    foreach ($xpath->query('//*') as $el) {
        if (!($el instanceof DOMElement)) {
            continue;
        }
        if ($el->tagName === 'img' && $el->hasAttribute('src')) {
            $out[] = $el;
            continue;
        }
        $style = $el->getAttribute('style');
        if ($style !== '' && stripos($style, 'background-image') !== false) {
            $out[] = $el;
        }
    }
    return $out;
}

function gondrand_has_ancestor_tag($el, $tags) {
    $p = $el->parentNode;
    while ($p instanceof DOMElement) {
        if (in_array(strtolower($p->tagName), $tags, true)) {
            return true;
        }
        $p = $p->parentNode;
    }
    return false;
}

function gondrand_has_class_ancestor($el, $class) {
    $p = $el;
    while ($p instanceof DOMElement) {
        $c = ' ' . $p->getAttribute('class') . ' ';
        if (strpos($c, ' ' . $class . ' ') !== false) {
            return true;
        }
        $p = $p->parentNode;
    }
    return false;
}

function gondrand_default_timeline() {
    return [
        ['year' => '1866', 'text' => 'Création de la société de transport TRAVEX GLOBAL FORWARDING par les frères TRAVEX GLOBAL FORWARDING.'],
    ];
}

function gondrand_timeline_drop_years() {
    return ['1881', '1890', '1919', 'Entre-deux-guerres', '1950', '1966', '2016'];
}

function gondrand_get_timeline() {
    $rows = get_option('gondrand_timeline', false);
    if ($rows === false || !is_array($rows)) {
        return gondrand_default_timeline();
    }
    $drop = array_map('strtolower', gondrand_timeline_drop_years());
    $out = [];
    $changed = false;
    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $year = isset($r['year']) ? trim((string) $r['year']) : '';
        $text = isset($r['text']) ? trim((string) $r['text']) : '';
        if ($year === '' && $text === '') {
            continue;
        }
        if ($year !== '' && in_array(strtolower($year), $drop, true)) {
            $changed = true;
            continue;
        }
        $out[] = ['year' => $year, 'text' => $text];
    }
    if ($changed) {
        update_option('gondrand_timeline', $out, false);
    }
    return $out;
}

function gondrand_save_timeline_from_post() {
    if (!isset($_POST['gondrand_timeline_present'])) {
        return;
    }
    $years = isset($_POST['tl_year']) ? (array) wp_unslash($_POST['tl_year']) : [];
    $texts = isset($_POST['tl_text']) ? (array) wp_unslash($_POST['tl_text']) : [];
    $rows = [];
    foreach ($years as $i => $year) {
        $year = sanitize_text_field($year);
        $text = sanitize_textarea_field($texts[$i] ?? '');
        if ($year === '' && $text === '') {
            continue;
        }
        $rows[] = ['year' => $year, 'text' => $text];
    }
    update_option('gondrand_timeline', $rows, false);
}

function gondrand_timeline_html() {
    $html = '';
    foreach (gondrand_get_timeline() as $r) {
        $html .= '<div class="tl">';
        if ($r['year'] !== '') {
            $html .= '<b>' . esc_html($r['year']) . '</b>';
        }
        if ($r['text'] !== '') {
            $html .= '<p>' . esc_html($r['text']) . '</p>';
        }
        $html .= '</div>';
    }
    return $html;
}

function gondrand_replace_div_class($html, $class, $replacement) {
    $needle = '<div class="' . $class . '">';
    $pos = strpos($html, $needle);
    if ($pos === false) {
        return $html;
    }
    $i = $pos + strlen($needle);
    $depth = 1;
    $len = strlen($html);
    while ($i < $len && $depth > 0) {
        $next_open = stripos($html, '<div', $i);
        $next_close = stripos($html, '</div>', $i);
        if ($next_close === false) {
            break;
        }
        if ($next_open !== false && $next_open < $next_close) {
            $depth++;
            $i = $next_open + 4;
        } else {
            $depth--;
            if ($depth === 0) {
                $i = $next_close + 6;
                break;
            }
            $i = $next_close + 6;
        }
    }
    return substr($html, 0, $pos) . $replacement . substr($html, $i);
}

function gondrand_entreprise_logistics_html() {
    $quote = function_exists('gondrand_u') ? gondrand_u('demande-de-cotation/index.html') : '../demande-de-cotation/index.html';
    return '<div id="entreprise-logistics">'
        . '<p>TRAVEX GLOBAL FORWARDING provides storage and logistics solutions in Renchen, Germany, to support your import and export operations.</p>'
        . '<p>Our logistics facility allows goods to be received, stored and prepared before their next stage of transportation.</p>'
        . '<h3>Cargo Reception</h3>'
        . '<p>Your goods can be received at our logistics facility according to the agreed arrangements.</p>'
        . '<p>We can coordinate the consolidation of your cargo before its next stage of transportation.</p>'
        . '<h3>Storage</h3>'
        . '<p>Do you need to store your goods before shipment?</p>'
        . '<p>We offer storage solutions for different types of cargo and volumes, subject to available capacity.</p>'
        . '<h3>Shipment Preparation</h3>'
        . '<p>We can coordinate various logistics operations before shipment, including:</p>'
        . '<ul>'
        . '<li>Cargo reception</li>'
        . '<li>Storage</li>'
        . '<li>Consolidation</li>'
        . '<li>Loading preparation</li>'
        . '<li>Cargo grouping</li>'
        . '<li>Shipment preparation</li>'
        . '</ul>'
        . '<h3>Container Preparation</h3>'
        . '<p>Our logistics solution can also be used to prepare cargo for container shipments.</p>'
        . '<p>Depending on the arrangement, goods can be received, stored and consolidated before being loaded into a container.</p>'
        . '<h3>A Solution for Businesses</h3>'
        . '<p>Our logistics facilities can be particularly useful for companies, traders, importers, exporters and operators requiring logistics space in Germany to organize their goods before shipment.</p>'
        . '<h3>Looking for storage or a logistics solution?</h3>'
        . '<p>Tell us the type of goods, volume, required storage period and your logistics requirements.</p>'
        . '<p>We will assess your needs and propose a suitable solution.</p>'
        . '<p><a class="btn" href="' . esc_url($quote) . '">Request a Quote</a></p>'
        . '</div>';
}

function gondrand_apply_timeline($html) {
    $html = preg_replace('#<h3>Les grandes étapes du développement depuis sa création</h3>#', '', $html, 1);
    $inner = '<div class="timeline">' . gondrand_timeline_html() . '</div>';
    $has_log = (strpos($html, 'id="entreprise-logistics"') !== false || strpos($html, "id='entreprise-logistics'") !== false);
    if (!$has_log) {
        $inner .= gondrand_entreprise_logistics_html();
    }
    if (strpos($html, '<div class="timeline">') !== false) {
        return gondrand_replace_div_class($html, 'timeline', $inner);
    }
    if (!$has_log) {
        $out = preg_replace('#(<div class="prose">)#', '$1' . gondrand_entreprise_logistics_html(), $html, 1);
        return is_string($out) ? $out : $html;
    }
    return $html;
}

function gondrand_timeline_row_html($r) {
    ob_start();
    ?>
    <div class="gondrand-block gondrand-tl">
      <p>Date / année<br><input class="regular-text" name="tl_year[]" value="<?php echo esc_attr($r['year'] ?? ''); ?>" placeholder="ex. 1866"></p>
      <p>Texte<br><textarea name="tl_text[]" rows="3" class="large-text"><?php echo esc_textarea($r['text'] ?? ''); ?></textarea></p>
      <p><button type="button" class="button gondrand-del-tl">Supprimer cette date</button></p>
    </div>
    <?php
    return ob_get_clean();
}

function gondrand_bg_url($style) {
    if (preg_match('/background-image\s*:\s*url\((["\']?)([^"\')]+)\1\)/i', $style, $m)) {
        return $m[2];
    }
    return '';
}

function gondrand_set_bg_url($el, $url) {
    $style = $el->getAttribute('style');
    if (preg_match('/background-image\s*:\s*url\((["\']?)([^"\')]+)\1\)/i', $style)) {
        $style = preg_replace(
            '/background-image\s*:\s*url\((["\']?)([^"\')]+)\1\)/i',
            "background-image:url('" . esc_url($url) . "')",
            $style,
            1
        );
    } else {
        $style = rtrim($style, ';') . ";background-image:url('" . esc_url($url) . "')";
    }
    $el->setAttribute('style', $style);
}

function gondrand_parse_page($slug) {
    $body = gondrand_get_page_body($slug);
    $dom = gondrand_load_fragment($body);
    $texts = [];
    foreach (gondrand_page_text_nodes($dom) as $i => $el) {
        $texts[] = [
            'i'     => $i,
            'tag'   => $el->tagName,
            'html'  => gondrand_inner_html($el),
            'plain' => trim(preg_replace('/\s+/', ' ', $el->textContent)),
        ];
    }
    $images = [];
    foreach (gondrand_page_image_nodes($dom) as $i => $el) {
        if ($el->tagName === 'img') {
            $src = $el->getAttribute('src');
            $kind = 'img';
        } else {
            $src = gondrand_bg_url($el->getAttribute('style'));
            $kind = 'bg';
        }
        $images[] = [
            'i'    => $i,
            'kind' => $kind,
            'src'  => $src,
            'alt'  => $el->getAttribute('alt'),
        ];
    }
    return ['texts' => $texts, 'images' => $images, 'body' => $body];
}

function gondrand_apply_saved_body($html, $path) {
    $slug = gondrand_path_to_slug($path);
    if ($slug === 'home') {
        return $html;
    }
    $saved = get_option(gondrand_page_option_key($slug), '');
    if (!is_string($saved) || trim($saved) === '') {
        return $html;
    }
    $out = preg_replace_callback(
        '#(<div id="site-header"></div>)(.*?)(<div id="site-footer"></div>)#s',
        function ($m) use ($saved) {
            return $m[1] . $saved . $m[3];
        },
        $html,
        1
    );
    $html = is_string($out) ? $out : $html;
    if ($slug === 'entreprise' && function_exists('gondrand_apply_timeline')) {
        $html = gondrand_apply_timeline($html);
    }
    return $html;
}

add_action('admin_menu', function () {
    add_submenu_page(
        'gondrand-content',
        'Toutes les pages',
        'Toutes les pages',
        'edit_theme_options',
        'gondrand-pages',
        'gondrand_pages_admin'
    );
}, 20);

add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos((string) $hook, 'gondrand') === false) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('jquery');
});

add_action('admin_init', function () {
    if (isset($_POST['gondrand_save_names']) && current_user_can('edit_theme_options')) {
        check_admin_referer('gondrand_save_names');
        $posted = isset($_POST['page_name']) ? (array) wp_unslash($_POST['page_name']) : [];
        foreach (gondrand_catalog() as $slug => $page) {
            if (!isset($posted[$slug])) {
                continue;
            }
            gondrand_save_page_name($slug, $posted[$slug]);
        }
        if (isset($_POST['nav_quote_name'])) {
            $nq = sanitize_text_field(wp_unslash($_POST['nav_quote_name']));
            if ($nq !== '') {
                set_theme_mod('gondrand_i18n_nav_quote', $nq);
            }
        }
        if (function_exists('gondrand_touch_bust')) {
            gondrand_touch_bust();
        }
        wp_safe_redirect(admin_url('admin.php?page=gondrand-pages&names=1'));
        exit;
    }
    if (!isset($_POST['gondrand_save_page'])) {
        return;
    }
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    check_admin_referer('gondrand_save_page');
    $slug = sanitize_key(wp_unslash($_POST['gondrand_page_slug'] ?? ''));
    $cat = gondrand_catalog();
    if (!isset($cat[$slug]) || !empty($cat[$slug]['home'])) {
        wp_safe_redirect(admin_url('admin.php?page=gondrand-pages'));
        exit;
    }

    if (isset($_POST['g_page_name'])) {
        gondrand_save_page_name($slug, wp_unslash($_POST['g_page_name']));
    }

    if ($slug === 'entreprise') {
        gondrand_save_timeline_from_post();
        if (function_exists('gondrand_save_brands_from_post')) {
            gondrand_save_brands_from_post();
        }
        if (isset($_POST['gondrand_i18n_group'])) {
            set_theme_mod('gondrand_i18n_group', sanitize_text_field(wp_unslash($_POST['gondrand_i18n_group'])));
        }
    }

    if (!empty($_POST['gondrand_reset_page'])) {
        delete_option(gondrand_page_option_key($slug));
        if ($slug === 'entreprise') {
            delete_option('gondrand_timeline');
        }
        wp_safe_redirect(admin_url('admin.php?page=gondrand-pages&edit=' . rawurlencode($slug) . '&reset=1'));
        exit;
    }

    $body = gondrand_get_page_body($slug);
    $dom = gondrand_load_fragment($body);

    $texts_post = isset($_POST['g_text']) ? (array) wp_unslash($_POST['g_text']) : [];
    $text_del = isset($_POST['g_text_del']) ? (array) $_POST['g_text_del'] : [];
    $nodes = gondrand_page_text_nodes($dom);
    for ($i = count($nodes) - 1; $i >= 0; $i--) {
        $el = $nodes[$i];
        if (!empty($text_del[$i])) {
            if ($el->parentNode) {
                $el->parentNode->removeChild($el);
            }
            continue;
        }
        if (isset($texts_post[$i])) {
            gondrand_set_inner($el, gondrand_kses_page($texts_post[$i]));
        }
    }

    $imgs_post = isset($_POST['g_img']) ? (array) wp_unslash($_POST['g_img']) : [];
    $img_del = isset($_POST['g_img_del']) ? (array) $_POST['g_img_del'] : [];
    $inodes = gondrand_page_image_nodes($dom);
    for ($i = count($inodes) - 1; $i >= 0; $i--) {
        $el = $inodes[$i];
        if (!empty($img_del[$i])) {
            if ($el->tagName === 'img' && $el->parentNode) {
                $el->parentNode->removeChild($el);
            } else {
                $style = preg_replace('/background-image\s*:\s*url\((["\']?)([^"\')]+)\1\)\s*;?/i', '', $el->getAttribute('style'));
                $el->setAttribute('style', $style);
            }
            continue;
        }
        $url = isset($imgs_post[$i]) ? esc_url_raw(trim((string) $imgs_post[$i])) : '';
        if ($url === '') {
            continue;
        }
        if ($el->tagName === 'img') {
            $el->setAttribute('src', $url);
        } else {
            gondrand_set_bg_url($el, $url);
        }
    }

    $add_title = sanitize_text_field(wp_unslash($_POST['g_add_title'] ?? ''));
    $add_text  = sanitize_textarea_field(wp_unslash($_POST['g_add_text'] ?? ''));
    $add_img   = esc_url_raw(wp_unslash($_POST['g_add_image'] ?? ''));
    if ($add_title !== '' || $add_text !== '' || $add_img !== '') {
        $wrap = $dom->getElementById('gwrap');
        if ($wrap) {
            $section = $dom->createElement('section');
            $section->setAttribute('class', 'section');
            $inner = $dom->createElement('div');
            $inner->setAttribute('class', 'wrap prose');
            if ($add_title !== '') {
                $h = $dom->createElement('h2');
                $h->appendChild($dom->createTextNode($add_title));
                $inner->appendChild($h);
            }
            if ($add_img !== '') {
                $im = $dom->createElement('img');
                $im->setAttribute('src', $add_img);
                $im->setAttribute('alt', $add_title);
                $inner->appendChild($im);
            }
            if ($add_text !== '') {
                $p = $dom->createElement('p');
                $p->appendChild($dom->createTextNode($add_text));
                $inner->appendChild($p);
            }
            $section->appendChild($inner);
            $wrap->appendChild($section);
        }
    }

    $html = gondrand_fragment_html($dom);
    update_option(gondrand_page_option_key($slug), $html, false);

    if (function_exists('gondrand_disable_root_html')) {
        gondrand_disable_root_html();
    }
    if (function_exists('gondrand_touch_bust')) {
        gondrand_touch_bust();
    }

    wp_safe_redirect(admin_url('admin.php?page=gondrand-pages&edit=' . rawurlencode($slug) . '&saved=1'));
    exit;
});

function gondrand_pages_admin() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    $edit = isset($_GET['edit']) ? sanitize_key(wp_unslash($_GET['edit'])) : '';
    $cat = gondrand_catalog();
    if ($edit && isset($cat[$edit]) && empty($cat[$edit]['home'])) {
        gondrand_page_editor($edit);
        return;
    }
    if ($edit === 'home') {
        wp_safe_redirect(admin_url('admin.php?page=gondrand-content'));
        exit;
    }
    echo '<div class="wrap"><h1>Toutes les pages du site</h1>';
    if (!empty($_GET['names'])) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Noms enregistrés.</strong> Purgez LiteSpeed. Ils apparaissent dans le menu et le titre du navigateur.</p></div>';
    }
    echo '<p>Changez le <strong>nom</strong> de chaque page, puis cliquez pour modifier ses textes et images.</p>';
    echo '<form method="post" action="' . esc_url(admin_url('admin.php?page=gondrand-pages')) . '">';
    wp_nonce_field('gondrand_save_names');
    echo '<input type="hidden" name="gondrand_save_names" value="1">';
    echo '<table class="widefat striped"><thead><tr><th>Nom de la page</th><th></th><th></th></tr></thead><tbody>';
    foreach ($cat as $slug => $page) {
        if (!empty($page['home'])) {
            $url = admin_url('admin.php?page=gondrand-content');
            $view = home_url('/?nocache=' . time());
        } else {
            $url = admin_url('admin.php?page=gondrand-pages&edit=' . rawurlencode($slug));
            $view = home_url('/' . ltrim($page['path'], '/') . '?nocache=' . time());
        }
        $custom = !empty($page['home']) || (get_option(gondrand_page_option_key($slug), '') !== '');
        echo '<tr>';
        echo '<td><input class="large-text" name="page_name[' . esc_attr($slug) . ']" value="' . esc_attr(gondrand_page_name($slug)) . '"></td>';
        echo '<td><a class="button button-primary" href="' . esc_url($url) . '">Modifier cette page</a></td>';
        echo '<td><a href="' . esc_url($view) . '" target="_blank" rel="noopener">Voir</a>';
        if ($custom && empty($page['home'])) {
            echo ' · <span style="color:#00a32a">modifiée</span>';
        }
        echo '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p style="margin-top:12px"><label>Nom dans le menu (lien Devis)&nbsp; <input class="regular-text" name="nav_quote_name" value="' . esc_attr(gondrand_t('nav_quote', 'Devis')) . '"></label></p>';
    submit_button('Enregistrer les noms des pages');
    echo '</form></div>';
}

function gondrand_page_editor($slug) {
    $cat = gondrand_catalog();
    $page = $cat[$slug];
    $parsed = gondrand_parse_page($slug);
    $view = home_url('/' . ltrim($page['path'], '/') . '?nocache=' . time());
    $tags = [
        'h1' => 'Titre principal',
        'h2' => 'Titre',
        'h3' => 'Sous-titre',
        'h4' => 'Sous-titre',
        'p'  => 'Paragraphe',
        'li' => 'Puce',
        'div'=> 'Bandeau',
        'b'  => 'Libellé',
        'strong' => 'Chiffre',
        'span' => 'Libellé',
    ];
    ?>
    <div class="wrap">
      <h1>Modifier : <?php echo esc_html(gondrand_page_name($slug)); ?></h1>
      <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=gondrand-pages')); ?>">&larr; Toutes les pages</a>
        · <a href="<?php echo esc_url($view); ?>" target="_blank" rel="noopener">Voir cette page</a>
      </p>
      <?php if (!empty($_GET['saved'])) : ?>
        <div class="notice notice-success is-dismissible"><p><strong>Enregistré et publié.</strong> Purgez LiteSpeed si besoin. <a href="<?php echo esc_url($view); ?>" target="_blank">Voir la page</a></p></div>
      <?php endif; ?>
      <?php if (!empty($_GET['reset'])) : ?>
        <div class="notice notice-success is-dismissible"><p>Page rétablie d’origine.</p></div>
      <?php endif; ?>

      <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=gondrand-pages&edit=' . rawurlencode($slug))); ?>">
        <?php wp_nonce_field('gondrand_save_page'); ?>
        <input type="hidden" name="gondrand_save_page" value="1">
        <input type="hidden" name="gondrand_page_slug" value="<?php echo esc_attr($slug); ?>">

        <?php if ($slug === 'entreprise') : ?>
        <h2>Dates / historique</h2>
        <p class="description">Supprimez une date, modifiez-la, ou ajoutez-en. Cliquez ensuite sur <strong>Enregistrer et publier</strong>.</p>
        <input type="hidden" name="gondrand_timeline_present" value="1">
        <div id="gondrand-tls">
          <?php foreach (gondrand_get_timeline() as $row) : ?>
            <?php echo gondrand_timeline_row_html($row); ?>
          <?php endforeach; ?>
        </div>
        <p><button type="button" class="button" id="gondrand-add-tl">+ Ajouter une date</button></p>

        <h2>Notre groupe d’entreprises — logos</h2>
        <p class="description">Ces logos apparaissent en bas de toutes les pages (bandeau « Notre groupe d’entreprises »).</p>
        <p><label>Titre du bandeau<br>
          <input class="large-text" name="gondrand_i18n_group" value="<?php echo esc_attr(gondrand_t('group', "NOTRE GROUPE D'ENTREPRISES")); ?>">
        </label></p>
        <input type="hidden" name="gondrand_brands_present" value="1">
        <div id="gondrand-brands">
          <?php
          $brands = function_exists('gondrand_get_brands') ? gondrand_get_brands() : [];
          foreach ($brands as $brand) {
              echo function_exists('gondrand_brand_row_html') ? gondrand_brand_row_html($brand) : '';
          }
          ?>
        </div>
        <p><button type="button" class="button" id="gondrand-add-brand">+ Ajouter un logo</button></p>
        <?php endif; ?>

        <h2>Images de la page</h2>
        <p class="description">Remplacez, ou cochez <strong>Supprimer</strong> pour enlever l’image du site.</p>
        <?php if (!$parsed['images']) : ?>
          <p>Aucune image sur cette page. Ajoutez-en en bas.</p>
        <?php endif; ?>
        <?php foreach ($parsed['images'] as $img) : ?>
          <div class="gondrand-block">
            <?php if ($img['src']) : ?>
              <img src="<?php echo esc_url($img['src']); ?>" alt="" class="gondrand-prev">
            <?php endif; ?>
            <p>
              <input type="url" class="large-text gondrand-image" name="g_img[<?php echo (int) $img['i']; ?>]" id="g_img_<?php echo (int) $img['i']; ?>" value="<?php echo esc_attr($img['src']); ?>">
              <button type="button" class="button gondrand-pick" data-target="g_img_<?php echo (int) $img['i']; ?>">Choisir / remplacer</button>
              <label><input type="checkbox" name="g_img_del[<?php echo (int) $img['i']; ?>]" value="1"> Supprimer cette image</label>
            </p>
          </div>
        <?php endforeach; ?>

        <h2>Textes de la page</h2>
        <p class="description">Modifiez n’importe quel texte. Cochez <strong>Supprimer</strong> pour l’enlever du site.</p>
        <?php foreach ($parsed['texts'] as $t) :
            $label = $tags[$t['tag']] ?? $t['tag'];
            $rows = (strlen($t['plain']) > 80 || $t['tag'] === 'p') ? 4 : 2;
        ?>
          <div class="gondrand-block">
            <p><strong><?php echo esc_html($label); ?></strong>
              <label style="float:right"><input type="checkbox" name="g_text_del[<?php echo (int) $t['i']; ?>]" value="1"> Supprimer cet élément</label>
            </p>
            <textarea class="large-text" rows="<?php echo (int) $rows; ?>" name="g_text[<?php echo (int) $t['i']; ?>]"><?php echo esc_textarea($t['html']); ?></textarea>
          </div>
        <?php endforeach; ?>

        <h2>Ajouter un élément</h2>
        <div class="gondrand-block">
          <p>Titre<br><input class="large-text" name="g_add_title" placeholder="Nouveau titre (facultatif)"></p>
          <p>Texte<br><textarea class="large-text" rows="4" name="g_add_text" placeholder="Nouveau paragraphe"></textarea></p>
          <p>Image<br>
            <input type="url" class="large-text gondrand-image" name="g_add_image" id="g_add_image">
            <button type="button" class="button gondrand-pick" data-target="g_add_image">Choisir une image</button>
          </p>
        </div>

        <?php submit_button('Enregistrer et publier sur le site'); ?>
        <p>
          <button type="submit" name="gondrand_reset_page" value="1" class="button" onclick="return confirm('Remettre cette page d’origine ?');">Réinitialiser cette page</button>
        </p>
      </form>
    </div>
    <script>
    (function(){
      function bindPick(btn){
        if (btn.getAttribute('data-bound')) return;
        btn.setAttribute('data-bound', '1');
        btn.addEventListener('click', function(e){
          e.preventDefault();
          var id = this.getAttribute('data-target');
          var input = id ? document.getElementById(id) : this.parentNode.querySelector('.gondrand-image');
          var frame = wp.media({ title: 'Choisir une image', multiple: false, library: { type: 'image' } });
          frame.on('select', function(){
            var att = frame.state().get('selection').first().toJSON();
            if (input) input.value = att.url;
            var box = input && (input.closest('.gondrand-block') || input.closest('.gondrand-slide') || input.closest('.gondrand-brand'));
            var prev = box && box.querySelector('.gondrand-prev');
            if (prev) { prev.src = att.url; prev.style.display = 'block'; }
            else if (box) {
              var im = document.createElement('img');
              im.className = 'gondrand-prev';
              im.src = att.url;
              box.insertBefore(im, box.firstChild);
            }
          });
          frame.open();
        });
      }
      document.querySelectorAll('.gondrand-pick').forEach(bindPick);
      var addTl = document.getElementById('gondrand-add-tl');
      if (addTl) {
        addTl.addEventListener('click', function(){
          var wrap = document.getElementById('gondrand-tls');
          var tmp = document.createElement('div');
          tmp.innerHTML = <?php echo wp_json_encode(gondrand_timeline_row_html(['year'=>'','text'=>''])); ?>;
          wrap.appendChild(tmp.firstElementChild);
        });
        document.getElementById('gondrand-tls').addEventListener('click', function(e){
          if (e.target.classList.contains('gondrand-del-tl')) {
            e.preventDefault();
            var row = e.target.closest('.gondrand-tl');
            if (row) row.remove();
          }
        });
      }
      var addBrand = document.getElementById('gondrand-add-brand');
      if (addBrand) {
        addBrand.addEventListener('click', function(){
          var wrap = document.getElementById('gondrand-brands');
          var tmp = document.createElement('div');
          tmp.innerHTML = <?php echo wp_json_encode(function_exists('gondrand_brand_row_html') ? gondrand_brand_row_html(['name'=>'','sub'=>'','image'=>'','url'=>'']) : ''); ?>;
          if (!tmp.firstElementChild) return;
          wrap.appendChild(tmp.firstElementChild);
          wrap.querySelectorAll('.gondrand-pick').forEach(bindPick);
        });
        document.getElementById('gondrand-brands').addEventListener('click', function(e){
          if (e.target.classList.contains('gondrand-del-brand')) {
            e.preventDefault();
            var row = e.target.closest('.gondrand-brand');
            if (row) row.remove();
          }
        });
      }
    })();
    </script>
    <style>
      .gondrand-block { background:#fff; border:1px solid #c3c4c7; padding:14px 16px; margin:10px 0; }
      .gondrand-block img.gondrand-prev { max-height:110px; display:block; margin:0 0 10px; }
      .gondrand-block textarea { width:100%; }
    </style>
    <?php
}
