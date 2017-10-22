<?php if (!class_exists('UncodeCommunicator')): ?>
<?php
class UncodeCommunicator extends UncodeAPI {
    var $items;

    function __construct($baseUrl='http://static.undsgn.com/uncode') {
        parent::__construct($baseUrl);
        $this->items = array();
    }

    /**
     * Used to fetch items from the specified endpoint.
     *
     * @return Array<UncodeNewsItem>
     */
    function fetchItems() {
        $last_time = get_option('uncode_messaging_last');
        $this_time = time();
        $_items = array();
        $cached = false;

        if (!empty($last_time)) {
            $minutes_since = round(abs($this_time - $last_time) / 60,2);

            if ($minutes_since <= 5) {
                $_items = json_decode(get_option('uncode_messaging'));

                if (!empty($_items)) {
                    $this->items = $_items;
                }

                $cached = true;
            }
        }

        if (!$cached) {
            $response = $this->request('endpoint/communications.json');

            if (empty($response)) {
                $this->items = array();
                return $this->items;
            } else {
                $_items = json_decode($response);
            }

            foreach ($_items as $i => $it) {
                if ($i <= sizeof($_items) -2) { continue; }

                try {
                    $type = isset($it->general) ? 'general' : 'specific';
                    $name = $type;
                    $_item = $it->$type;
                    $blogversion = get_bloginfo('version');

                    if ($type == 'specific') {
                        if (!isset($_item->$blogversion)) {
                            continue;
                        } else {
                            $_item = $_item->$blogversion;
                            $name = $blogversion;
                        }
                    }

                    $date = isset($_item->date) ? $_item->date : null;
                    $url = isset($_item->url) ? $_item->url : '#';

                    $newsItem = new UncodeNewsItem(
                        $_item->title,
                        $_item->body,
                        $type,
                        $name,
                        $date,
                        $url
                    );

                    $this->items[] = $newsItem;
                } catch (Exception $e) {
                    return array();
                }
            }
        }

        update_option('uncode_messaging_last', time());

        return $this->items;
    }

    /**
     * Get items which is not read.
     *
     * @return Iterator
     */
    function getUnreadItems() {
        $items = array();
        foreach ($this->fetchItems() as $item) {
            if ($item instanceof UncodeNewsItem) {
                if($item->isRead()) { continue; }
            } else {
                if (isset($item->read)) {
                    if ($item->read) { continue; }
                }
            }

            $items[] = $item;
            
        }

        return $items;
    }

    /**
     * Used to count unread items/messages.
     *
     * @return Integer
     */
    function countUnreadItems() {
        $count = 0;

        foreach ($this->fetchItems() as $item) {
            if ($item instanceof UncodeNewsItem) {
                if($item->isRead()) { continue; }
            } else {
                if (isset($item->read)) {
                    if ($item->read) { continue; }
                }
            }

            $count++;
        }

        return $count;
    }

    /**
     * Render HTML for all items.
     * (Puts HTML in buffer)
     *
     * @return Void
     */
    function render_items() {
?>
        <ul id="uncode-communicator-list">
<?php
        foreach ($this->getUnreadItems() as $item) {
            if (isset($item->object)) {
                $object = unserialize($item->object);
                $object->render();
            } else {
                $item->render();
            }
        }
?>
        </ul>
<?php
    }

    /**
     * Regsiter a purchaseCode to a domain.
     *
     * @param String $purchaseCode
     * @param String $domain
     *
     * @return Integer - ID of the inserted connection
     */
    function registerDomain($purchaseCode, $domain, $user_name) {
        $resp = $this->requestPost("license/add_license.php", array(
            'purchase_code' => $purchaseCode,
            'domain' => $domain,
            'user_name' => $user_name,
            'user_email' => "null@null.com"
        ));

        return empty($resp) ? null : $resp;
    }
    
    /**
     * Unregister / delete domain connaction from purchaseCode.
     *
     * @param String $purchaseCode
     *
     * @return Boolean
     */
    function unRegisterDomains($purchaseCode) {
        $resp = $this->requestPost("license/delete_license.php", array(
            'purchase_code' => $purchaseCode,
        ));

        if (empty($resp)) { return false; }

        return substr_count(strtolower($resp), "true") > 0 ||
            substr_count(strtolower($resp), "1") > 0;
    }

    /**
     * Get domains where this theme is used with same
     * purchase_code.
     *
     * @param String $purchaseCode
     *
     * @return Array<String> || null
     */
    function getConnectedDomains($purchaseCode) {
        $resp = $this->requestPost("license/get_license.php", array(
            'purchase_code' => $purchaseCode
        ));

        if (empty($resp)) { return null; }

        return $resp;
    }

    /**
     * Get information about purchase_code
     *
     * @param String $purchaseCode
     *
     * @return Object || null
     */
    function getPurchaseInformation($purchaseCode) {
        $response = $this->requestPost("license/check_purchase.php", array(
            'purchase_code' => $purchaseCode
        ));

        if (empty($response)) { return null; }

        $decoded = json_decode($response);

        if (empty($decoded)) { return null; }

        $decoded = (Array) $decoded;

        if (!isset($decoded['verify-purchase'])) { return null; }
        if (empty($decoded['verify-purchase'])) { return null; }
        
        return $decoded;
    }

    /**
     * Check if purchase_code is valid.
     *
     * @param String $purchaseCode
     *
     * @return Boolean
     */
    function isPurchaseCodeLegit($purchaseCode) {
        $get_info = $this->getPurchaseInformation($purchaseCode);
        return !empty($get_info);
    }
}
?>
<?php endif ?>
