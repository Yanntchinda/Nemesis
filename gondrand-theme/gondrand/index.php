<?php
/**
 * Point d’entrée WordPress. Le thème sert le site Gondrand via functions.php.
 */
if (function_exists('gondrand_try_serve')) {
    gondrand_try_serve();
}
status_header(200);
nocache_headers();
$home = get_template_directory() . '/site/index.html';
if (is_readable($home)) {
    echo file_get_contents($home);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Gondrand</title></head>
<body>
<p>Thème Gondrand actif. Placez les fichiers du site dans le dossier <code>site/</code> du thème.</p>
</body>
</html>
