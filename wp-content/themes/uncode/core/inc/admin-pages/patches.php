<?php
require_once get_template_directory() . '/core/inc/UncodeAPI.class.php';
require_once get_template_directory() . '/core/inc/UncodeHotfix.class.php';

/*function patches_add_news_notification() {
    global $menu;

    $hotfix = new UncodeHotfix('http://www.mocky.io/v2');
    $patches_count = $hotfix->countCommittedPatches([
        'key' => 'merged',
        'value' => false
    ]);

    foreach ( $menu as $key => $value ) {

        if ( $menu[$key][2] == 'uncode-menu' || $menu[$key][2] == 'patches' ) {
            $menu[$key][0] .= ' ' . "<span class='update-plugins count-$patches_count'><span class='update-count'>$patches_count</span></span>";
            return;

        }

    }
}
add_action( 'admin_menu', 'patches_add_news_notification' );*/

function patches_callback() {
    if (!isInstallationLegit() || requiredDataEmpty()) { wp_die(); }

    $hotfix = new UncodeHotfix();

    if (isset($_POST['merge_patch'])) {
        $patch_id = $_POST['patch_id'];
        $patch = $hotfix->getCommittedPatch($patch_id);
        
        if ($hotfix->mergePatch($patch)) {
            $patch->merged = true;

            $hotfix->commitPatch($patch);
        }
    }

    if (isset($_POST['delete_patch'])) {
        $patch_id = $_POST['patch_id'];
        $patch = $hotfix->getCommittedPatch($patch_id);
        
        $hotfix->uncommitPatch($patch);
    }
    
    /* Fetch all patches and sort them by 'merged' */
    $patches = $hotfix->getCommittedPatches(null, function ($a, $b) {
        return $a->merged == true;
    });

?>
<div class="wrap" id="uncode-patches">
    <h1><?php esc_html_e('Patches', 'uncode'); ?>
        <span class="uncode-heading-subtitle"><?php esc_html_e( "The easiest and fastest way to fix issues on fly", "uncode" ); ?></span>
    </h1>

    <table class='wp-list-table widefat fixed striped posts'>
        <thead>
            <th><?php esc_html_e( "Fix", "uncode" ); ?></th>
            <th><?php esc_html_e( "Path", "uncode" ); ?></th>
            <th><?php esc_html_e( "Date", "uncode" ); ?></th>
            <th></th>
        </thead>
        <tbody id='the-list'>
            <?php foreach ($patches as $patch) { ?>
                <?php if (isset($patch->merged)) { if ($patch->merged) { continue; }} ?>
                <tr class='rss-widget'>
                    <td><?php echo $patch->desc; ?></td>
                    <td><code><?php echo $patch->path; ?></code></td>
                    <td><small><?php echo $patch->date; ?></small></td>
                    
                    <td>
                        <form method='POST'> 
                            <input type='hidden' name='patch_id' value='<?php echo $patch->id; ?>'>
                            <input type='submit' class='button button-primary' name='merge_patch' value='<?php esc_html_e( "Apply", "uncode" ); ?>'>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
 }

function patches_submenu_page() {
    $hotfix = new UncodeHotfix('http://www.mocky.io/v2');
    $patches_count = $hotfix->countCommittedPatches(array(
        'key' => 'merged',
        'value' => false
    ));

    if (isInstallationLegit() && !requiredDataEmpty()) {
        if ($patches_count > 0) {
            add_submenu_page(
                'uncode-menu',
                esc_html__('Patches ', 'uncode') . '<span class="update-plugins count-'.$patches_count.'"><span class="update-count">'.$patches_count.'</span></span>',
                esc_html__('Patches ', 'uncode') . '<span class="update-plugins count-'.$patches_count.'"><span class="update-count">'.$patches_count.'</span></span>',
                'manage_options',
                'patches',
                'patches_callback' );
        }
    }
}
//add_action('admin_menu', 'patches_submenu_page');


if (!wp_next_scheduled('patch_task_hook' )) {
    wp_schedule_event(time(), 'hourly', 'patch_task_hook');
}
add_action('patch_task_hook', 'patch_task_function');

function patch_task_function() {
    $hotfix = new UncodeHotfix();
    $hotfix->commitPatches();
}

//uncomment this when debugging
//patch_task_function();
?>
