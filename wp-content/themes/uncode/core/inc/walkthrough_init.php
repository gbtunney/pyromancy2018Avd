<?php

function initial_walkthrough() {
    $_SESSION['ignore_walkthrough'] = null;
    unset($_SESSION['ignore_walkthrough']);
}


$theme = wp_get_theme();

if (!empty($theme)) {
    if (is_user_logged_in()) {

        if (isset($_GET['uncode_reinstall'])) {
            delete_option('uncode_installed');
            initial_walkthrough();
        }

        if (substr_count(strtolower($theme->get('Name')), 'uncode') > 0) {
            if (isset($_POST['ignore_walkthrough'])) {
                $_SESSION['ignore_walkthrough'] = 1;
            }

            if (
                !empty($_REQUEST['action']) &&
                !empty($_REQUEST['walkthrough']) &&
                empty($_REQUEST['_wpnonce'])
            ) {
                require_once( get_template_directory() . '/core/inc/plugin_install_ajax.php' );
            }
            if (empty(get_option('uncode_installed')) &&
                empty($_SESSION['ignore_walkthrough']) &&
                empty($_REQUEST['_wpnonce']) ) {
                require_once( get_template_directory() . '/core/inc/initial_walkthrough.php' );
                exit;
            }
        }
    }
}
