<?php if (!class_exists('Envato')): ?>
<?php
class Envato extends UncodeAPI {
    var $api_key;

    function __construct($baseUrl='https://api.envato.com/v3/market') {
        parent::__construct($baseUrl); 
    }

    /**
     * Set API key.
     *
     * @param String $api_key
     *
     * @return Boolean
     */
    function setAPIKey($api_key) {
        if (empty($api_key)) { return false; }

        $this->api_key = $api_key;

        return true;
    }

    /**
     * Get version of specified wordpress theme.
     *
     * @param String $id
     *
     * @return String | null
     */
    function getThemeVersion($id) {
        $response = $this->request("/catalog/item?id=$id", array(
            "Authorization: Bearer $this->api_key"
        ));

        try {
            $response = json_decode($response);
        } catch (Exception $e) {
            return null;
        }

        if (empty($response)) {
            return null;
        }

        if (!isset($response->wordpress_theme_metadata)) {
            return null;
        }

        if (!isset($response->wordpress_theme_metadata->version)) {
            return null;
        }

        return $response->wordpress_theme_metadata->version;
    }

    /**
     * Get stored data about current installation
     *
     * @return Array || null
     */
    function getToolkitData() {
        $option = get_option('uncode-wordpress-data');
        $option = (Array)json_decode($option); 

        if (!empty($option)) {
            return (Array)$option; 
        }

        return null;
    }

    /**
     * Check if data about current installation is empty
     *
     * @return Boolean
     */
    function toolkitDataEmpty() {
        $data = $this->getToolkitData();

        if (empty($data)) { return true; }

        if (
            empty($data['user_name']) ||
            empty($data['api_key']) ||
            empty($data['purchase_code'])
        ) { return true; }
    }

    /**
     * Used to check if an update is available for a specific theme.
     * This function compares the local theme version with the remote version.
     *
     * @param String $id
     *
     * @return True
     */
    function updateExistsForTheme($id) {
        $local_version = wp_get_theme()->get('Version');
        $remote_version = $this->getThemeVersion($id);

        return $local_version != $remote_version && (
            !empty($local_version) && !empty($remote_version)
        );

        return $local_version != $remote_version; 
    }

    /**
     * Get download for certain item using purchase_code.
     *
     * @param String $item_id
     * @param String $purchase_code
     */
    function getDownload($item_id, $purchase_code) {
        $response = $this->request(
            "/buyer/list-purchases",
            array(
                "Authorization: Bearer $this->api_key"
            )
        );

        return $response;
    }
}
?>
<?php endif; ?>
