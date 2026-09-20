<?php
/**
 * Point d’entrée WordPress. L’accueil est généré en PHP (modifications WordPress).
 */
if (!defined('ABSPATH')) {
    exit;
}
if (function_exists('gondrand_try_serve')) {
    gondrand_try_serve();
}
if (function_exists('gondrand_render_home')) {
    gondrand_render_home();
}
