<?php
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

$plugin_url = $data->plugin_url;
$plugin_name = $data->plugin_name;

$plugin_dir = explode('wp-admin', $_SERVER['SCRIPT_FILENAME'])[0];
$plugin_path = $plugin_dir . 'wp-content/plugins';
if (!file_exists($plugin_path . "/zip")) {
    mkdir($plugin_path . "/zip");
}

$zipfile_name = $plugin_path . '/zip/' . $plugin_name . '.zip';
if (!file_put_contents($zipfile_name, fopen($plugin_url, 'r'))) {
    wp_send_json_error();
}

$zip = new ZipArchive;
$res = $zip->open($zipfile_name);
if ($res === TRUE) {
    $zip->extractTo($plugin_path);
    $name_index = $zip->getNameIndex(0);
    $zip->close();

    $name_index = str_replace('/', '', $name_index);
        
    $activate_name = $plugin_path . '/' . $name_index;

    $php_files = glob($activate_name . '/*.php');
    
    foreach ($php_files as $php_file) {
        activate_plugin($php_file);
    }

    wp_send_json_success();
} else {
    wp_send_json_error();
}

wp_die();
?>
