<?php

function backups_callback() {
   
    if (isset($_POST['delete_backup'])) {
        $backup_file = $_POST['backup_id'];

        unlink(ABSPATH . 'wp-content/uncode-backups/' . $backup_file);
    }

    if (isset($_POST['revert_backup'])) {
        $backup_file = $_POST['backup_id'];
        $backup_location = ABSPATH . 'wp-content/uncode-backups/' . $backup_file;

        $zip = new ZipArchive;
        if ($zip->open($backup_location) === TRUE) {
            $zip->extractTo(get_template_directory());
            $zip->close();
        }
    }

    
    if (!file_exists(ABSPATH . 'wp-content/uncode-backups')) {
        $backups = array();
    } else {
        $backups = scandir(ABSPATH . 'wp-content/uncode-backups');
    }

    $_backups = array();

    foreach ($backups as $backup) {
        $file_ending = explode('.', $backup);
        if (empty($file_ending)) { continue; }
        if (sizeof($file_ending) < 2) { continue; }
        if ($file_ending[sizeof($file_ending) - 1] != 'zip') { continue; }

        $_backups[] = $backup;
    }

    usort($_backups, function($a, $b) {
        $a_backup_date = explode('_', $a);
        $a_backup_date = $a_backup_date[2];
        $a_backup_date_sub = explode('_', $a_backup_date);
        $a_backup_date = strtotime(str_replace('UTC', ' ', $a_backup_date_sub[0]));
        $a_date = date('Y-m-d h:i:s',$a_backup_date);
        
        $b_backup_date = explode('_', $b);
        $b_backup_date = $b_backup_date[2];
        $b_backup_date_sub = explode('_', $b_backup_date);
        $b_backup_date = strtotime(str_replace('UTC', ' ', $b_backup_date_sub[0]));
        $b_date = date('Y-m-d h:i:s',$b_backup_date);

        return $a_date < $b_date;
    });

    ?>
<div class="wrap" id="uncode-backups">
    <h1><?php esc_html_e('Backups', 'uncode'); ?>
        <span class="uncode-heading-subtitle"><?php esc_html_e( "Restore back a previous version of the theme saved in your server", "uncode" ); ?></span> 
    </h1>
    <table class='wp-list-table widefat fixed striped posts'>
        <thead>
            <th><?php esc_html_e( "Backup File", "uncode" ); ?></th>
            <th><?php esc_html_e( "Backup Version", "uncode" ); ?></th>
            <th><?php esc_html_e( "Backup Date", "uncode" ); ?></th>
            <th></th>
        </thead>
        <tbody id='the-list'>
            <?php foreach ($_backups as $backup) { ?>
            <?php
                $backup_version = explode('_', $backup);
                $backup_version = $backup_version[1];
                $backup_date = explode('_', $backup);
                $backup_date = $backup_date[2];
            ?>
                <tr class='rss-widget'>
                    <td><?php echo $backup; ?></td>
                    <td><?php echo $backup_version; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $backup_date); ?></td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='backup_id' value='<?php echo $backup; ?>'>
                            <input type='submit' class='button button-primary' name='revert_backup' value='<?php esc_html_e( "Revert", "uncode" ); ?>'>
                            <input type='submit' class='button button-warning' name='delete_backup' value='<?php esc_html_e( "Delete", "uncode" ); ?>'>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
    <?php
}

function backups_submenu_page() {
    $backups_count = sizeof(getBackups());

    if ($backups_count > 0) {
        add_submenu_page(
            'uncode-menu',
            esc_html__('Backups', 'uncode'),
            esc_html__('Backups', 'uncode'),
            'manage_options',
            'backups',
            'backups_callback');
    }
}
//add_action('admin_menu', 'backups_submenu_page');