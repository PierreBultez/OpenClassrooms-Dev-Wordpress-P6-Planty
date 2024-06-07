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

//** Ajout lien admin si connecté **//

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

//** Envoi formulaire commande **//

function handle_form_submission() {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'submit_commande_form') {
            // Sanitize and validate input data for the commande form
            $fraise = sanitize_text_field($_POST['fraise']);
            $pamplemousse = sanitize_text_field($_POST['pamplemousse']);
            $framboise = sanitize_text_field($_POST['framboise']);
            $citron = sanitize_text_field($_POST['citron']);
            $nom = sanitize_text_field($_POST['nom']);
            $prenom = sanitize_text_field($_POST['prenom']);
            $email = sanitize_email($_POST['email']);
            $adresse = sanitize_text_field($_POST['adresse']);
            $codepostal = sanitize_text_field($_POST['codepostal']);
            $ville = sanitize_text_field($_POST['ville']);
            
            // Handle the form data (e.g., send an email, save to database, etc.)
            $to = 'planty.drinks@gmail.com';
            $subject = 'Nouvelle commande de Planty Drinks';
            $message = "Commande de:\nFraise: $fraise\nPamplemousse: $pamplemousse\nFramboise: $framboise\nCitron: $citron\n\nInformations client:\nNom: $nom\nPrénom: $prenom\nEmail: $email\nAdresse: $adresse\nCode postal: $codepostal\nVille: $ville";
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            
            wp_mail($to, $subject, $message, $headers);
            
            // Redirect or display a success message
            wp_redirect(home_url('/merci'));
            exit;
        } elseif ($_POST['action'] === 'submit_contact_form') {
            // Sanitize and validate input data for the contact form
            $nom = sanitize_text_field($_POST['nom']);
            $email = sanitize_email($_POST['email']);
            $message_content = sanitize_textarea_field($_POST['message']);
            
            // Handle the form data (e.g., send an email, save to database, etc.)
            $to = 'planty.drinks@gmail.com';
            $subject = 'Nouveau message de contact de Planty Drinks';
            $message = "Message de:\nNom: $nom\nEmail: $email\n\nMessage:\n$message_content";
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            
            wp_mail($to, $subject, $message, $headers);
            
            // Redirect or display a success message
            wp_redirect(home_url('/merci-contact'));
            exit;
        }
    }
}
add_action('init', 'handle_form_submission');

//** Si votre thème ne le fait pas déjà, assurez-vous que jQuery est chargé, car WordPress utilise souvent jQuery pour gérer les formulaires. */

function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');

?>