<?php if (!class_exists('UncodeHotfix')): ?>
<?php
/**
 * Base class for talking to API:s
 *
 * Other API classes may extend this class to take use of the
 * POST and GET request functions.
 */
class UncodeAPI {
    var $baseUrl;
    var $session;

    function __construct($baseUrl='http://static.undsgn.com/uncode/endpoint') {
        $this->baseUrl = $baseUrl;
        $this->session = null;
    }

    /**
     * Opens / initializes the curl connection.
     *
     * @return Boolean
     */
    function open() {
        if (empty($this->session)) {
            $this->session = curl_init();

            return true;
        }

        return false;
    }

    /**
     * Closes the curl connection
     *
     * @return Boolean
     */
    function close() {
        if(!empty($this->session)) {
            curl_close($this->session);

            return true;
        }

        return false;
    }

    /**
     * Check if baseUrl is available
     *
     * @return Boolean
     */
    function isBaseAvailable() {
        return $this->request($this->baseUrl) != null;
    }

    /**
     * Send GET request to any endpoint.
     *
     * @param String $endpoint
     * @param Array $headers
     * @param Array $curlParams
     *
     * @return String || null
     */
    function request($endpoint, $headers=array(), $curlParams=array()) {
        $this->open();

        if (empty($headers)) { $headers = array(); }
        
        $cParams = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => str_replace(
                "\0",
                "",
                $this->baseUrl . '/' . $endpoint
            ),
            CURLOPT_USERAGENT => 'Uncode',
            CURLOPT_HTTPHEADER => $headers
        );

        if (!empty($curlParams)) { $cParams += $curlParams; }

        try {
            foreach (array_keys($cParams) as $param) {
                if (empty($cParams[$param])) { continue; }
                
                curl_setopt($this->session, $param, $cParams[$param]);
            }

            $resp = curl_exec($this->session);
        } catch (Exception $e) {
            return null;
        }
        
        return $resp;
    }

    /**
     * Send POST request to any endpoint
     *
     * @param String $endpoint
     * @param Array $fields - [key => value]
     * @param Array $headers
     */
    function requestPost($endpoint, $fields, $headers=array()) {
        $this->open();

        $fields_string = '';

        /* Turning $fields into an array encoded query ($fields_string) */
        foreach($fields as $key => $value) {
            $fields_string .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $fields_string = rtrim($fields_string, '&');
        
        return $this->request($endpoint, $headers, array(
            47 => sizeof($fields),
            10015 => $fields_string
        ));
    }
}
?>
<?php endif; ?>
