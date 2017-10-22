<?php if (!class_exists('UncodeNewsItem')): ?>
<?php
class UncodeNewsItem {
    var $title;
    var $body;
    var $date;
    var $url;
    var $type;
    var $version;
    var $id;
    var $name;

    function __construct($title=null,
        $body=null,
        $type=null,
        $name=null,
        $date=null,
        $url=null
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->name = $name;
        $this->date = $date;
        $this->url = $url;

        if (empty($this->url)) {
            $this->url = '#';
        }

        $option = json_decode(get_option('uncode_messaging'));
        if (empty($option)) {
            $option = array();
        } 

        if (!$this->exists()) {
            $this->id = $this->getID();

            $option[] = array(
                'id'=> $this->getID(),
                'title' => $this->title,
                'body' => $this->body,
                'type' => $this->type,
                'name' => $this->name,
                'date' => $this->date,
                'object' => serialize($this),
                'read'=> false
            );
        }

        update_option('uncode_messaging', json_encode($option));
    }

    /**
     * Render HTML for this item.
     * (Puts HTML String in current buffer)
     *
     * @return Void
     */
    function render() { 
?>
        <li>
            <form method='POST'>
                <input type="hidden" name="news_id" value="<?php echo $this->getID(); ?>">
                <button type="submit" class="notice-dismiss"></button>
            </form>
            <div class="communication-item-content">
                <h3>
                    <a href="<?php echo $this->url; ?>">
                    <?php echo $this->title; ?> (<?php echo $this->name; ?>)
                    </a>
                </h3>
                <?php if (!empty($this->date)) { ?>
                    <span class="rss-date"><?php echo $this->date; ?></span>
                <?php } ?>
                <?php if (!empty($this->body)) { ?>
                    <div class="rssSummary">
                        <?php echo wpautop($this->body); ?>
                    </div>
                <?php } ?>
            </div>
        </li>
<?php
    }
    
    /**
     * Get ID of newsItem
     *
     * @return String
     */
    function getID() {
        return (!empty($this->id)) ?
            $this->id :
            md5($this->title . $this->body . $this->date);
    }

    /**
     * Check if this item is read
     *
     * @return Boolean
     */
    function isRead() {
        if (!$this->exists()) { return false; }
        
        return $this->getInstance()->read;
    }
    
    /**
     * Get database instance of this item
     *
     * @return StdClass
     */
    function getInstance() {
        $option = json_decode(get_option('uncode_messaging'));

        if (empty($option)) {
            return null;
        }

        $exists = false;
        foreach ( $option as $op ) {
            if ($op->id == $this->getID()) {
                return $op;     
            }
        }

        return null;
    }
    
    /**
     * Check if item exists in database
     *
     * @return Boolean
     */
    function exists() {
        $instance = $this->getInstance();
        return !empty($instance); 
    }
}
?>
<?php endif ?>
