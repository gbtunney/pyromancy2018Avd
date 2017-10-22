<?php

$functions_file_content = <<<EOD
<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

?>
EOD;

$child_theme_title = $_POST['child_theme_title'];
$child_theme_description = $_POST['child_theme_description'];
$child_theme_uri = $_POST['child_theme_uri'];
$child_theme_author = $_POST['child_theme_author'];
$child_theme_version = $_POST['child_theme_version'];
$child_theme_template = $_POST['child_theme_template'];

if ($child_theme_title === "") {
    wp_die("No child theme title");
}

$script_file_content = <<<EOD
/*
Theme Name:     $child_theme_title
Theme URI:      $child_theme_uri
Template:       $child_theme_template
URI:            $child_theme_uri
Author:         $child_theme_author
Version:        $child_theme_version 
*/
EOD;

$child_theme_dir_path = get_template_directory() . '-child';

$postfix = 0;

while (file_exists($child_theme_dir_path . '-' . $postfix)) {
  $postfix++;
}

$child_theme_dir_path .= '-' . $postfix;

mkdir ($child_theme_dir_path) or wp_die("Couldn't create directory");

$functions_file = fopen($child_theme_dir_path . '/functions.php', 'w') or wp_die("Couldn't create file: functions.php");

fwrite($functions_file, $functions_file_content);

$script_file = fopen($child_theme_dir_path . '/style.css', 'w') or wp_die("Couldn't create file: style.css");

fwrite($script_file, $script_file_content);

?>
