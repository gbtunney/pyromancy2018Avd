<script type='text/javascript'>
    var ajaxurl = "<?php echo (admin_url('admin-ajax.php') . '?action=install_plugin&walkthrough=1'); ?>";
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php

if (!isset($_GET['_wpnonce'])) {
    $parse_uri = explode('wp-admin', $_SERVER['SCRIPT_FILENAME']);
    require_once( $parse_uri[0] . 'wp-load.php' );
}

if (isset($_POST['install'])) {
  if ($_POST['install'] == 'true') {
    update_option('uncode_installed', true);
    wp_redirect(admin_url("admin.php?page=uncode-menu"));
  }
}

if (!isset($_POST['skip'])) {
    if (isset($_POST['child_theme_title'])) {
      require_once(dirname(realpath(__FILE__)) . '/../create-child-theme.php');
    }
}
?>

<link href="<?php echo (get_template_directory_uri() . '/core/assets/css/initial-walkthrough.css'); ?>" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<form method='POST'>
<input type='submit' name='ignore_walkthrough' class='close-walk' value='X'>
</form>
