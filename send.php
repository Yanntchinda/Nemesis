<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method']);
    exit;
}

$cfg = require __DIR__ . '/config-mail.php';
$to = $cfg['to'] ?? 'accueil.dg@gondrand.fr';

function field($key) {
    $v = $_POST[$key] ?? '';
    if (is_array($v)) $v = implode(', ', $v);
    return trim(strip_tags($v));
}

$email = field('email');
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'email']);
    exit;
}

$honeypot = field('website');
if ($honeypot !== '') {
    echo json_encode(['ok' => true]);
    exit;
}

$lines = [
    'Type de transport' => field('type'),
    'Incoterms' => field('incoterms'),
    'Société' => field('company'),
    'Contact' => field('contact'),
    'Téléphone' => field('phone'),
    'E-mail' => $email,
    'Départ (ville)' => field('from') ?: field('from_city'),
    'Départ (pays)' => field('from_country'),
    'Arrivée (ville)' => field('to') ?: field('to_city'),
    'Arrivée (pays)' => field('to_country'),
    'Type de colis' => field('parcel'),
    'Poids (kg)' => field('weight'),
    'Dimensions' => field('dimensions'),
    'Marchandise dangereuse' => field('dangerous'),
    'Commentaires' => field('comments'),
];

$body = "Nouvelle demande de devis — Gondrand\n";
$body .= "Date : " . date('Y-m-d H:i:s') . "\n";
$body .= "IP : " . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n\n";
foreach ($lines as $label => $value) {
    if ($value !== '') $body .= $label . " : " . $value . "\n";
}

$subject = 'Demande de devis Gondrand' . (field('type') ? ' — ' . field('type') : '');
$from = $cfg['from'];
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . ($cfg['name'] ?? 'Gondrand') . ' <' . $from . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . PHP_VERSION,
];

$ok = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));

if (!$ok) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'mail']);
    exit;
}

echo json_encode(['ok' => true]);
