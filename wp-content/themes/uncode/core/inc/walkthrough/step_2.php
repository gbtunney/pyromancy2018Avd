<h1>Default plugins</h1>
<div>
    <p>This will install the default plugins included with Uncode.</p>
</div>
<?php
$plugin_list = [
    "Envato" =>
    "http://envato.github.io/wp-envato-market/dist/envato-market.zip",
    "Layer Slider"
    => get_template_directory_uri() . '/core/plugins_activation/plugins/layersliderwp-5.6.10.installable.zip',
    "Uncode Core"
    => get_template_directory_uri() . '/core/plugins_activation/plugins/uncode-core.zip',
    "Uncode Visual Composer"
    => get_template_directory_uri() . '/core/plugins_activation/plugins/uncode-js_composer.zip', 
    "Uncode Related Posts for WordPress"
    => get_template_directory_uri() . '/core/plugins_activation/plugins/uncode-related-posts-for-wp.zip',
    'Uncode Dave\'s WordPress Live Search'
    => get_template_directory_uri() . '/core/plugins_activation/plugins/uncode-daves-wordpress-live-search.zip',
    'Visual Composer Clipboard'
    =>  get_template_directory_uri() . '/core/plugins_activation/plugins/vc_clipboard.zip',
    'VC Particles Background'
    => get_template_directory_uri() . '/core/plugins_activation/plugins/vcparticlesbackground.zip',
    'Revolution Slider'
    => get_template_directory_uri() . '/core/plugins_activation/plugins/revslider.zip'
];
?>

<ul class="list-group" id="plugins">
    <?php $index = 0; foreach($plugin_list as $plugin_name=>$plugin_url) { ?>
    <li class="list-group-item list-group-item-action" id="<?php echo ('plugin_' . $index); ?>" data-url="<?php echo $plugin_url; ?>">
    <?php echo $plugin_name; ?>
    <div class="loader pull-right"></div>

    <img id="<?php echo ('check_plugin_' . $index); ?>" class="checkmark pull-right" src="<?php echo ( get_template_directory_uri() . '/core/assets/images/ic_check_circle_white_24px.svg'); $index++ ?>" >
    </li>
    <?php } ?>
</ul>

<input type='hidden' name='step' value="<?php echo ($step + 1); ?>">
<div class="text-right">
    <button class="btn btn-primary" id="install_plugins_btn">Install plugins</button>
    <button class="btn btn-primary" id="next_btn" style="display: none;" type="submit">Next</button>
</div>

<script>
function doAjaxCalls(plugin_names, plugin_urls, i) {
  plugin_name = plugin_names[i];
  plugin_url = plugin_urls[i];

  // Start spinner
  $('#plugins').find('#plugin_' + i).find('.loader').show();

  $.ajax({
  method: "POST",
    url: ajaxurl,
    contentType: "application/json; charset=UTF-8", 
    data: JSON.stringify({
    plugin_name: plugin_name,
      plugin_url: plugin_url})
     }).always(function(data, textStatus, error){
     }).done(function(data) {
       if (data["success"]) {
	 $('#plugins').find('#plugin_' + i).find('.loader').hide(200, function(){
	   $('#check_plugin_' + i).show(200); 
	   $('#plugin_' + i).addClass('list-group-item-success');

	   if (i < (plugin_names.length - 1)) {
	     doAjaxCalls(plugin_names, plugin_urls, i+1);
       } else {
	 $('#next_btn').show(200);
      }
	  });
       }
     }).fail(function() {
       $('#plugins').find('#plugin_' + i).find('.loader').hide(200, function(){
	 $('#plugin_' + i).addClass('list-group-item-danger');
	})
     });

  };


$(function() {

  $('#install_plugins_btn').on('click', function(ev) {
    ev.preventDefault();

    var plugin_names =  [<?php echo '"'.implode('","', array_keys($plugin_list)).'"' ?>];
    var plugin_urls = [<?php echo '"'.implode('","', $plugin_list).'"' ?>];

    doAjaxCalls(plugin_names, plugin_urls, 0);

    $('#install_plugins_btn').prop('disabled', true);
});
});
</script>
