<?php
require_once get_template_directory() . '/core/inc/UncodeAPI.class.php';
require_once get_template_directory() . '/core/inc/Envato.class.php';
require_once get_template_directory() . '/core/inc/UncodeCommunicator.class.php';



function update_uncode_admin_notice_warning() {

    global $pagenow;

    $envato = new Envato();
    $envato->setAPIKey(ENVATO_KEY);

    $toolkitData = $envato->getToolkitData();

    $envato_update_url = admin_url('admin.php?page=updates');

    if ($envato->updateExistsForTheme(ITEM_ID) ) {
        if ( $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'updates' ) {
            /* */
        } else {

        ?>
            <div class="notice notice-warning is-dismissible">
                 
                <p>
    <?php
                if(!$envato->toolkitDataEmpty()) {

                    printf( wp_kses( __( 'A new version of Uncode is available! <a href="%1$s">Please update now.</a>', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $envato_update_url ) );
                    
                } else {

                    printf( wp_kses( __( 'A new version of Uncode is available! <a href="%1$s">Please enter your Envato API credentials to update.</a>', 'uncode' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $envato_update_url ) );

                }
                ?>
                </p>
            </div>
        <?php
        }
    }
}
//add_action( 'admin_notices', 'update_uncode_admin_notice_warning' );



/*function add_update_news_notification() {
    global $menu;

    $envato = new Envato();
    $envato->setAPIKey(ENVATO_KEY);
    
    $count = !empty($envato->updateExistsForTheme(ITEM_ID)) ? 1: 0;

    foreach ( $menu as $key => $value ) {

        if ( $menu[$key][2] == 'updates' || $menu[$key][2] == 'uncode-menu' ) {
            $menu[$key][0] .= ' ' . "<span class='update-plugins count-$count'><span class='update-count'>$count</span></span>";
            return;

        }

    }
}
add_action( 'admin_menu', 'add_update_news_notification' );*/


function updates_callback() {
    if (!isInstallationLegit() || requiredDataEmpty()) { wp_die(); }

    $envato = new Envato();
    $envato->setAPIKey(ENVATO_KEY);

?>
<div class="wrap update-uncode">
    <h1><?php esc_html_e('Updates', 'uncode'); ?>
        <span class="uncode-heading-subtitle"><?php printf(esc_html__( "Get the latest version of %s", "uncode" ), UNCODE_NAME); ?></span>  
    </h1>

<?php
    if (isset($_POST['uncode_update'])) {
        $toolkitData = $envato->getToolkitData();
        $toolkit = new Envato_Protected_API(
            $toolkitData['user_name'],
            $toolkitData['api_key']
        );

        if (!defined('UPDATE_ZIP_URL')) {
            $download_url = $toolkit->wp_download(ITEM_ID);
        } else {
            $download_url = UPDATE_ZIP_URL;
        }

        file_put_contents('/tmp/uncode.zip', fopen($download_url, 'r'));

        $my_theme = wp_get_theme();
        $ctv = $my_theme->get('Version');

        $backup_dir = ABSPATH . 'wp-content/uncode-backups';
        $zip_path = $backup_dir . '/uncode-backup_'.$ctv.'_'.time().'_.zip';

        if (!file_exists($backup_dir)) { mkdir($backup_dir); }

        $dir = get_template_directory();
        $zip_file = $zip_path;

        // Get real path for our folder
        $rootPath = realpath($dir);

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                if (substr_count($name, '.git') > 0) { continue; }

                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        $theme_location = '/tmp/uncode.zip';
        $zip = new ZipArchive();
        if ($zip->open($theme_location) === TRUE) {
            $zip->extractTo(get_template_directory() . '/../');
            $zip->close();
        }

        $last_err = error_get_last();

        if (empty($last_err)) {
            ?>
            <script type='text/javascript'>window.location.href="<?php echo admin_url('admin.php?page=uncode-menu'); ?>";</script>
            <?php
        } else {
            ?>
            <div class='update-nag'>
            <?php echo esc_html__($last_err['message'], 'uncode'); ?>
            </div>
            <?php 
        }
    }

    if ($envato->updateExistsForTheme(ITEM_ID)) { ?>
            <script type='text/javascript'>
                function showUpdateSpinner() {
                    document.getElementById('updateSpinner').style.display = 'inline-block';
                }
            </script>
            <div class='update-nag'>
                <form method="POST">
                    Uncode <?php echo $envato->getThemeVersion(ITEM_ID); ?> is available.
                    <?php if (!$envato->toolkitDataEmpty()) { ?> 
                    <input
                    type='submit'
                    class='button button-primary'
                    name='uncode_update'
                    value='Update now' onclick='showUpdateSpinner();'>
<div id='updateSpinner'
style='background-image: url("<?php echo admin_url('images/spinner-2x.gif'); ?>"); width: 16px; height: 16px; background-size: 100%; display: inline-block; vertical-align: middle; display: none;'>
</div>
                    <?php } else { ?>
                    <a href="<?php echo admin_url('admin.php?page=uncode-menu');?>">Please enter your Envato API credentials to update.</a>
                    <?php } ?>
                </form>
            </div>
        <?php }
    ?>
</div>
<?php
}

function updates_submenu_page() {
    $envato = new Envato();
    $envato->setAPIKey(ENVATO_KEY);
    $check_update = $envato->updateExistsForTheme(ITEM_ID);
    $count = !empty($check_update) ? 1: 0;


    if (isInstallationLegit() && !requiredDataEmpty()) {
        if ($count > 0) {
            add_submenu_page(
                'uncode-menu',
                esc_html__('Updates ', 'uncode') . '<span class="update-plugins count-'.$count.'"><span class="update-count">'.$count.'</span></span>',
                esc_html__('Updates ', 'uncode') . '<span class="update-plugins count-'.$count.'"><span class="update-count">'.$count.'</span></span>',
                'manage_options',
                'updates',
                'updates_callback' );
        }
    }
}
//add_action('admin_menu', 'updates_submenu_page');



function uncode_setup__options(){
    delete_option('uncode-wordpress-data');
    delete_option('uncode_installed');
}
add_action('switch_theme', 'uncode_setup__options');
