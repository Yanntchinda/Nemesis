<?php
/**
 * Adresse qui reçoit les demandes de devis.
 * Changez cette ligne après mise en ligne chez LWS.
 */
return [
    'to'   => 'accueil.dg@gondrand.fr',
    'from' => 'noreply@' . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost'),
    'name' => 'Gondrand France',
];
