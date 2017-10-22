<?php

function prefix_ajax_update_license() {
    $postdata = (Array)json_decode(file_get_contents("php://input"));

    $user_name = str_replace(' ', '', isset($postdata['user_name']) ? $postdata['user_name'] : '');
    $api_key = str_replace(' ', '', isset($postdata['api_key']) ? $postdata['api_key'] : '');
    $purchase_code = str_replace(' ', '', isset($postdata['purchase_code']) ? $postdata['purchase_code'] : '');

    $communicator = new UncodeCommunicator();
    $envato = new Envato();
    $envato->setAPIKey(ENVATO_KEY);

    $toolkitData = $envato->getToolkitData();

    $toolkit = new Envato_Protected_API(
        $user_name,
        $api_key
    );
    $download_url = $toolkit->wp_download(ITEM_ID);

    $errors = $toolkit->api_errors();

    $ok_purchase_code = $communicator->isPurchaseCodeLegit($purchase_code);

    if (!empty($errors)) {
        $err_keys = array_keys($errors);
        $_errors = array();

        foreach($err_keys as $errkey) {
            $_errors[] = $errors[$errkey];
        }

        wp_send_json_error($_errors);
    }

    if ($ok_purchase_code) {
        $data = array(
            'user_name' => $user_name,
            'purchase_code' => $purchase_code,
            'api_key' => $api_key
        );
    } else {
        wp_send_json_error(array("Invalid purchase_code"));
    }

    if (!empty($errors) || !$ok_purchase_code) {
        wp_send_json_error(array("ERROR"));
    } else {
        update_option('uncode-wordpress-data', json_encode($data));

        $server_name = empty($_SERVER['SERVER_NAME']) ?
            $_SERVER['HTTP_HOST']: $_SERVER['SERVER_NAME'];

        $communicator->registerDomain($data['purchase_code'], $server_name, $data['user_name']);
    }

    wp_send_json_success(array("Information was updated"));

    wp_die();
}
add_action( 'wp_ajax_update_license', 'prefix_ajax_update_license' );
