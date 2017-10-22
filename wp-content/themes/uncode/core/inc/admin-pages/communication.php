<?php
require_once get_template_directory() . '/core/inc/UncodeAPI.class.php';
require_once get_template_directory() . '/core/inc/UncodeNewsItem.class.php';
require_once get_template_directory() . '/core/inc/UncodeCommunicator.class.php';


/*function add_news_notification() {
    global $menu;

    $communicator = new UncodeCommunicator();

    //var_dump($communicator->registerDomain('8zu0qmmuwoe3xy2254ks1b20zljkmn8j', 'example.org' , 'example@example.com', 'example@example.com'));
    //
    
    $unread_mess = 0;
    foreach ($communicator->getUnreadItems() as $item) {
        $unread_mess += 1;
    } 

    $message_info = json_decode(get_option('uncode_messaging'));

    foreach ( $menu as $key => $value ) {

        if ( $menu[$key][2] == 'communication' || $menu[$key][2] == 'uncode-menu' ) {
            $menu[$key][0] .= ' ' . "<span class='update-plugins count-$unread_mess'><span class='update-count'>$unread_mess</span></span>";
            return;

        }

    }
}
add_action( 'admin_menu', 'add_news_notification' );*/


function communication_callback() {
    $communicator = new UncodeCommunicator();

    if (isset($_POST['news_id'])){
        $option = json_decode(get_option('uncode_messaging'));
        foreach ($option as $op) {
            if ($op->id == $_POST['news_id']) {
                $op->read = true;
                break;
            }
        }
        update_option('uncode_messaging', json_encode($option));
    }
    $unread_count = $communicator->countUnreadItems();

if ($unread_count > 0) {
?>
<div class="wrap" id="uncode-communications">
    <h1><?php esc_html_e('Communications', 'uncode'); ?>
        <span class="uncode-heading-subtitle"><?php printf(esc_html__( "Stay up to date with the latest about %s", "uncode" ), UNCODE_NAME); ?></span> 
    </h1>
    <div class='postbox'>
    <div class='inside'>
<?php
}

    if (!$communicator->isBaseAvailable()) {
?>
            <h3 style='color: #d54e21;'>Service is not responding</h3>
            <p>
                <a href='<?php echo $communicator->baseUrl; ?>'>
                    <?php echo $communicator->baseUrl; ?>
                </a>
                is not available. 
            </p>
<?php
    } else {
        $communicator->fetchItems();
        $communicator->render_items();
    }
if ($unread_count > 0) {
?>
    </div>
    </div>
</div><!-- /.wrap -->
<?php
}

$communicator->close();
}

function communication_submenu_page() {
    $communicator = new UncodeCommunicator();
    $unread_count = $communicator->countUnreadItems();

    if ($unread_count > 0) {
        add_submenu_page(
            'uncode-menu',
            esc_html__('Communication ', 'uncode') . '<span class="update-plugins count-'.$unread_count.'"><span class="update-count">'.$unread_count.'</span></span>',
            esc_html__('Communication ', 'uncode') . '<span class="update-plugins count-'.$unread_count.'"><span class="update-count">'.$unread_count.'</span></span>',
            'manage_options',
            'communication',
            'communication_callback',
            20 );
    }
}
//add_action('admin_menu', 'communication_submenu_page');