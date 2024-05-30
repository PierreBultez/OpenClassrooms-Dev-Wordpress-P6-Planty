<?php
//** Activation du thème enfant **//

// Empêche l'accès direct
if (!defined('ABSPATH')) {
    exit; // Quitte si accès direct
}

// Enqueue les styles et scripts du thème parent et du thème enfant
function enqueue_parent_child_styles() {
    // Enqueue le style du thème parent
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue le style du thème enfant
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'enqueue_parent_child_styles');

function ajouter_lien_admin_si_connecte($items, $args) {
    if ($args->theme_location == 'primary') {
        if (is_user_logged_in()) {
            $lien_admin = '<li id="menu-item-27" li class="menu-item"><a href="' . admin_url() . '">Admin</a></li>';
            $items_array = explode('</li>', $items, 2); // Diviser après le premier élément
            if (count($items_array) > 1) {
                $items = $items_array[0] . '</li>' . $lien_admin . $items_array[1];
            } else {
                $items = $lien_admin . $items;
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'ajouter_lien_admin_si_connecte', 10, 2);


?>