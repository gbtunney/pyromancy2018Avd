<?php
/**
 * Admin View: Page - Status Report
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function uncode_let_to_num( $size ) {
  $l   = substr( $size, -1 );
  $ret = substr( $size, 0, -1 );
  switch ( strtoupper( $l ) ) {
    case 'P':
      $ret *= 1024;
    case 'T':
      $ret *= 1024;
    case 'G':
      $ret *= 1024;
    case 'M':
      $ret *= 1024;
    case 'K':
      $ret *= 1024;
  }
  return $ret;
}

if ( class_exists('UncodeCommunicator') ) {
	$communicator = new UncodeCommunicator();
	$unread_mess = $communicator->countUnreadItems();
}

if ( class_exists('UncodeHotfix') ) {
	$hotfix = new UncodeHotfix('http://static.undsgn.com/uncode/endpoint');
	$test = array(    
		'key' => 'merged',
	    'value' => false
	);
	$patches_count = $hotfix->countCommittedPatches($test);
}

if ( class_exists('Envato') ) {
	$envato = new Envato();
	$envato->setAPIKey(ENVATO_KEY);
	$toolkitDataEmpty = $envato->toolkitDataEmpty();
	$toolkitData = $envato->getToolkitData();
} else {
	$toolkitDataEmpty = $toolkitData = false;
}

//$update_count = !empty($envato->updateExistsForTheme(ITEM_ID)) ? 1: 0;

if ( function_exists('isInstallationLegit') )
	$installationLegit = isInstallationLegit();
else
	$installationLegit = false;

if (isset($_POST['change_license']) && class_exists('UncodeCommunicator')) {
    //$toolkitData = $envato->getToolkitData();
    $communicator->unRegisterDomains($toolkitData['purchase_code']);
    delete_option('uncode-wordpress-data');
}

if (isset($_POST['envato_update_info'])) {
}

if(isset($_POST['install_uncode'])) {
    $_SESSION['ignore_walkthrough'] = null;
    unset($_SESSION['ignore_walkthrough']);
    ?>
    <script type="text/javascript">window.location.href="<?php echo admin_url(); ?>";</script>
    <?php
}

if (!empty($_SESSION['ignore_walkthrough'])) {
?>
<div class="update-nag">
    <?php echo esc_html__('You need to install Uncode before you can use it.', 'uncode'); ?>
    <form method="POST">
        <input type="submit" class="button button-primary" value="Run Installer" name="install_uncode">
    </form>
</div>
<?php exit; } ?>

<?php
if (!isset($toolkitData['purchase_code'])) { $license_ok = false; } else {
    $license_ok = $communicator->isPurchaseCodeLegit($toolkitData['purchase_code']);
}

$envato_toolkit_active = false;
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('envato-wordpress-toolkit-master/index.php'))
	$envato_toolkit_active = true;

$license_ko_text = $envato_toolkit_active ? esc_html__("Not verified - Please register your copy of Uncode.", "uncode") : sprintf(esc_html__("Not verified - Please register your copy of Uncode. Envato WordPress Toolkit plugin must be active, %s", "uncode"), '<a href="' . esc_url( admin_url('admin.php?page=uncode-plugins') ) . '">' . esc_html__('click here', 'uncode') . '</a>');

$license_ok_text = $license_ok ? "<mark class=\"yes\">".esc_html__("Verified", "uncode")."</mark>" : "<mark class=\"error\">". $license_ko_text ."</mark>";

if (!$installationLegit) {
    $license_ok = false;
    $license_ok_text = "<mark class=\"error\">".esc_html__("Details already used on another installation. Click \"Edit\" again to deactivate it and register this installation.", "uncode")."</mark>";
}
?>

<div class="wrap uncode-wrap" id="option-tree-settings-api">

	<h1><?php echo esc_html__( "Welcome to ", "uncode" ) . '<span class="uncode-name">'.UNCODE_NAME.'</span>'; ?><span class="uncode-version"><?php echo UNCODE_VERSION; ?></span></h1>

	<div class="about-text">
		<?php printf(esc_html__( "%s is installed! Check that all the requirements below are fulfilled and labeled in green.", "uncode" ), UNCODE_NAME); ?>
        </div>

        <?php if ($toolkitDataEmpty && ( isset($_POST['change_license']) ) ) { ?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th data-export-label="System Status"><h4 style="margin-top: 0"><?php echo esc_html__("Product registration", "uncode"); ?></h4></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>

                <form method="POST" id="uncode_api_credentials_form">

					<div class="format-setting-wrap">
						<div class="format-setting-label">
							<h3 class="label"><?php esc_html_e('Envato Username', 'uncode'); ?></h3>
						</div>
						<div class="format-setting has-desc">
							<div class="description"><?php printf(esc_html__('Please insert your Envato username. %s.','uncode'), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/115002308845#envato-purchase-code') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?></div>
							<div class="format-setting-inner">
								<input type="text" name="envato_username" id="envato_username" class="widefat option-tree-ui-input">
							</div>
						</div>
					</div>

					<div class="format-setting-wrap">
						<div class="format-setting-label">
							<h3 class="label"><?php esc_html_e('Envato API-Key', 'uncode'); ?></h3>
						</div>
						<div class="format-setting has-desc">
							<div class="description"><?php printf(esc_html__('Please insert your Envato API Key. %s.','uncode'), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/115002308845#envato-api-key') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?></div>
							<div class="format-setting-inner">
								<input type="text" name="envato_api_key" id="envato_api_key" class="widefat option-tree-ui-input">
							</div>
						</div>
					</div>

					<div class="format-setting-wrap">
						<div class="format-setting-label">
							<h3 class="label"><?php esc_html_e('Envato purchase code', 'uncode'); ?></h3>
						</div>
						<div class="format-setting has-desc">
							<div class="description"><?php printf(esc_html__('Please insert your Envato purchase code. %s.','uncode'), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/115002308845#envato-purchase-code') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?></div>
							<div class="format-setting-inner">
								<input type="text" name="envato_purchase_code" id="envato_purchase_code" class="widefat option-tree-ui-input">
							</div>
						</div>
					</div>

                    <div>
                    	<br>
                    	<input class="button button-primary" type="submit" name="envato_update_info" value="Submit">
                    	<span class="spinner"></span>
	                    <mark style="display: none;" class="error" id="license_error"><?php esc_html_e('Invalid credentials, or your Envato account does not have Uncode among purchased items', 'uncode'); ?></mark>
	                    <mark style="display: none;" class="error" id="license_empty"><?php esc_html_e('Please fill out all required fields', 'uncode'); ?></mark>
                    </div>

                </form>
            </td>
        </tr>
    </tbody>
</table>
        <?php } ?>

<script type="text/javascript">

/**
 * Used to send a POST request.
 *
 * @param String url
 * @param Object data
 * @param function callback(response)
 */
var wpost = function (url, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            callback(xhr.responseText);
        }
    }
    xhr.open('POST', url, true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.send(JSON.stringify(data));  
}

if ( document.getElementById('uncode_api_credentials_form') !== null ) {

	document.getElementById('uncode_api_credentials_form').querySelector('[name="envato_update_info"]').addEventListener("mousedown", function(e) {
	    var license_empty_dom = document.getElementById('license_empty');
	    e.preventDefault();

        license_empty_dom.style.display = 'none';
	});
	
	document.getElementById('uncode_api_credentials_form').querySelector('[name="envato_update_info"]').addEventListener("click", function(e) {
	    e.preventDefault();

	    var user_name_field = document.getElementById('uncode_api_credentials_form').querySelector('[name="envato_username"]'),
	    	api_key_field = document.getElementById('uncode_api_credentials_form').querySelector('[name="envato_api_key"]'),
	    	purchase_code_field = document.getElementById('uncode_api_credentials_form').querySelector('[name="envato_purchase_code"]'),
	    	user_name = user_name_field.value,
	    	api_key = api_key_field.value,
	    	purchase_code = purchase_code_field.value,
	    	license_empty_dom = document.getElementById('license_empty');

	    if ( user_name === '' || api_key === '' || purchase_code === '' ) {

	    	if ( user_name === '' )
	    		user_name_field.classList.add("error");
	    	if ( api_key === '' )
	    		api_key_field.classList.add("error");
	    	if ( purchase_code === '' )
	    		purchase_code_field.classList.add("error");

            license_empty_dom.style.display = 'inline-block';

	    	return false;

	    } else {

	    	var $spinner = document.getElementById('uncode_api_credentials_form').querySelector('[class*="spinner"]');

	    	$spinner.classList.add("is-active");

		    wpost(
		        ajaxurl + '?action=update_license',
		        {
		            "user_name": user_name,
		            "api_key": api_key,
		            "purchase_code": purchase_code
		    },
		        function(data) {
		            var obj = JSON.parse(data);

		            if (obj['success'] == 'false' || obj['success'] == false) {
		                var license_error_dom = document.getElementById('license_error');
		                $spinner.classList.remove("is-active");
		                license_error_dom.style.display = 'inline-block';

		                //license_error_dom.innerHTML = obj['data'];
		            } else {
		                document.location.href = document.location.href;
		            }
		        }
		    );

		}
	});
}

/**
 * Small adjustments
 */
jQuery( document ).ready( function ( $ ) {

	//Remove error class from empty fields
	var $uncode_api_credentials_form = $('form#uncode_api_credentials_form'),
		$license_empty = $('#license_empty'),
		$license_error = $('#license_error'),
	$credentials_inputs = $('input[type="text"]', $uncode_api_credentials_form).each(function(){

		var $input = $(this).on('keyup paste', function(){
			$input.removeClass('error');

			if ( ! $('input[type="text"].error', $uncode_api_credentials_form).length )
				$license_empty.add($license_error).hide();
		});

	});

	//Spinner on change license button
	var $change_license_form = $('form#change_license_form').on('submit', function(){
		var $spinner = $('.spinner', this);

		$spinner.addClass('is-active');
	});

});
</script>

<table class="widefat" cellspacing="0" id="status">
		<thead>
			<tr>
				<th colspan="3" data-export-label="System Status"><h4 style="margin-top: 0"><?php esc_html_e( 'System Status', 'uncode' ); ?></h4></th>
			</tr>
		</thead>
        <tbody>
        	<?php if ( class_exists('Envato') ) { ?>
            <tr>
                <td data-export-label="License"><?php echo esc_html__("Product registration", "uncode"); ?></td>
                <td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_html__( 'Please validate your product license as outlined in Envato\'s license terms.', 'uncode' ) . '">[?]</a>'; ?></td>
                <td>
                    <?php echo $license_ok_text; ?>
                    <?php
						if ($envato_toolkit_active) :
					?>
	                    <form id="change_license_form" method="POST">
	                    	<input type="submit" class="button" name="change_license" value="<?php esc_html_e( 'Edit', 'uncode' ); ?>">
	                    	<span class="spinner"></span>
	                    </form>
	                <?php
	                	endif;
	                ?>
                </td>
            </tr>
        	<?php } ?>
            <tr>
                <td data-export-label="License"><?php echo esc_html__("Theme version", "uncode"); ?></td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . sprintf( esc_attr__( 'The version of %s installed on your site.', 'uncode' ), UNCODE_NAME ) . '">[?]</a>'; ?></td>
                <td>
                    <?php $theme_data = wp_get_theme();
                    	if ( $theme_data->parent() )
                    		$parent_theme_version = $theme_data->parent()->version;
                    	else
                    		$parent_theme_version = UNCODE_VERSION;

                    	echo esc_attr($parent_theme_version);
                    ?>
                </td>
            </tr>
			<tr>
				<td data-export-label="WP Version"><?php esc_html_e( 'WP Version', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php bloginfo('version'); ?></td>
			</tr>
			<tr>
				<td data-export-label="Language"><?php esc_html_e( 'Language', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language used by WordPress. Default = English', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php echo get_locale() ?></td>
			</tr>
			<tr>
				<td data-export-label="WP Multisite"><?php esc_html_e( 'WP Multisite', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php if ( is_multisite() ) echo '&#10004;'; else echo '&ndash;'; ?></td>
			</tr>
			<tr>
				<td data-export-label="Frontend stylesheet"><?php esc_html_e( 'Frontend stylesheet', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Uncode is generating a stylesheet when the options are saved. The file must be writtable.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php
					global $wp_filesystem;
					if (empty($wp_filesystem)) {
						require_once (ABSPATH . '/wp-admin/includes/file.php');
					}
					$mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;
					$front_css = get_template_directory() . '/library/css/';
					$creds = request_filesystem_credentials($front_css, '', false, false, array());
					$can_write_front = true;
					if (!!$creds) {
						/* initialize the API */
						if ( ! WP_Filesystem($creds) ) {
							/* any problems and we exit */
							$can_write_front = false;
						}
					}
					$filename = trailingslashit($front_css).'test.txt';
					if ( ! $wp_filesystem->put_contents( $filename, 'Test file contents', $mod_file) ) {
						$can_write_front = false;
					} else {
						$wp_filesystem->delete( $filename );
					}
					$front_css = '..' . substr($front_css, strpos($front_css,"/wp-content"));
					if ($can_write_front) {
						echo '<mark class="yes">' . '&#10004; <code>' . $front_css .'</code></mark> ';
					} else {
						printf( '<div style="color:#0073aa;">' . '&#10004; - ' . wp_kses(__( 'WordPress doesn\'t have direct access to this folder <code>%s</code> due to a confict in the Uncode folder permission or your configuration of WordPress file access is not the direct method. The custom css will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $front_css  );
					}
				?></td>
			</tr>
			<tr>
				<td data-export-label="Font stylesheet"><?php esc_html_e( 'Backend stylesheet', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Uncode is generating a stylesheet when the options are saved. The file must be writtable.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php
					$mod_file = (defined('FS_CHMOD_FILE')) ? FS_CHMOD_FILE : false;
					$back_css = get_template_directory() . '/core/assets/css/';
					$creds = request_filesystem_credentials($back_css, '', false, false, array());
					$can_write_back = true;
					if (!!$creds) {
						/* initialize the API */
						if ( ! WP_Filesystem($creds) ) {
							/* any problems and we exit */
							$can_write_back = false;
						}
					}
					$filename = trailingslashit($back_css).'test.txt';
					if ( ! $wp_filesystem->put_contents( $filename, 'Test file contents', $mod_file) ) {
						$can_write_back = false;
					} else {
						$wp_filesystem->delete( $filename );
					}
					$back_css = '..' . substr($back_css, strpos($back_css,"/wp-content"));
					if ($can_write_back) {
						echo '<mark class="yes">' . '&#10004; <code>' . $back_css .'</code></mark> ';
					} else {
						printf( '<div style="color:#0073aa;">' . '&#10004; - ' . wp_kses(__( 'WordPress doesn\'t have direct access to this folder <code>%s</code> due to a confict in the Uncode folder permission or your configuration of WordPress file access is not the direct method. The custom css will be output inline.', 'uncode' ), array( 'code' => '' )) . '</div>', $back_css  );
					}
				?></td>
			</tr>
			<tr>
				<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WP Memory Limit', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php
					$memory = uncode_let_to_num( WP_MEMORY_LIMIT );

					if ( $memory < 100663296 ) {
						echo '<mark class="error">' . sprintf(esc_html__('%s - We recommend setting memory to at least 96MB. %s.','uncode'), size_format( $memory ), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213459889') . '" target="_blank">'.esc_html__('More info','uncode').'</a>') . '</mark>';
					} else {
						echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
					}
				?></td>
			</tr>
			<tr>
				<td data-export-label="Server Memory Limit"><?php esc_html_e( 'Server Memory Limit', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'This is actually the real memory available for your installation despite the WP memory limit.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td class="real-memory">
					<span class="calculating"><?php esc_html_e( 'Calculating…', 'uncode' ); ?></span>
					<mark class="yes" style="display: none;">%d% MB</mark>
					<mark class="error" style="display: none;"><?php esc_html_e( 'You only have %d% MB available and it\'s not enough to run the system. If you have already increased the memory limit please check with your hosting provider for increase it (at least 96MB is required).','uncode' ); ?></mark>
				</td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td class="get-max-input-vars">
					<span class="calculating"><?php esc_html_e( 'Calculating…', 'uncode' ); ?></span>
					<mark class="yes" style="display: none;"></mark>
					<mark class="error get_data" style="display: none;">%d%<?php printf(esc_html__(' - We recommend setting PHP max_input_vars to at least 3000. %s.','uncode'), '<a href="' . esc_url('//support.undsgn.com/hc/en-us/articles/213459869') . '" target="_blank">'.esc_html__('More info','uncode').'</a>'); ?></mark>
					<mark class="error no_data" style="display: none;"><?php esc_html_e('No available data','uncode'); ?></mark>
					</td>
			</tr>
			<tr>
				<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WP Debug Mode', 'uncode' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'uncode' ) . '">[?]</a>'; ?></td>
				<td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . '&#10004;' . '</mark>'; else echo '&ndash;'; ?></td>
			</tr>
		</tbody>
        </table>
        <?php do_action('uncode_welcome'); ?> 
</div>

<script type="text/javascript">

	jQuery( document ).ready( function ( $ ) {
		$( '.help_tip' ).tipTip({
			attribute: 'data-tip'
		});

		$( 'a.help_tip' ).click( function() {
			return false;
		});

		$.ajax({
			type : 'post',
			url: '<?php echo get_template_directory_uri(); ?>/core/inc/admin-pages/testmemory.php',
			success: function(response) {
				var get_memory_array = String(response).split('\n'),
					get_memory;
				$(get_memory_array).each(function(index, el) {
					var temp_memory = el.replace( /^\D+/g, '');
					if ('%'+temp_memory == el) get_memory = temp_memory;
				});
				var	memory_string;
				if (get_memory < 96) {
					memory_string = $('.real-memory .error');
				} else {
					memory_string = $('.real-memory .yes');
				}
				memory_string.text(memory_string.text().replace("%d%", get_memory));
				$('.calculating').hide();
				memory_string.show();
			},
			error: function(response) {
				var get_memory_array = String(response.responseText).split('\n'),
					get_memory;
				$(get_memory_array).each(function(index, el) {
					var temp_memory = el.replace( /^\D+/g, '');
					if ('%'+temp_memory == el) get_memory = temp_memory;
				});
				var	memory_string;
				if (get_memory < 96) {
					memory_string = $('.real-memory .error');
				} else {
					memory_string = $('.real-memory .yes');
				}
				memory_string.text(memory_string.text().replace("%d%", get_memory));
				$('.calculating').hide();
				memory_string.show();
			}
		});

		var uncode_test_max_input_vars = function($vars){
			var param = {},
				var_string,
				intData;
			for (i = 0; i < $vars; i++) {
				param[i] = 'var_'+i;
			}

			$.ajax({
				url: '<?php echo get_template_directory_uri(); ?>/core/inc/admin-pages/testinputvars.php', 
				data: param,
				type: 'post',
				error: function(){
					$('.get-max-input-vars .calculating').hide();
					$('.get-max-input-vars .error.no_data').fadeIn();
				},
				success: function(data){
					intData = parseInt(data) - 1;
					if ( intData < ($vars-1) ) {
						if ( intData < 2990 ) {
							var_string = $('.get-max-input-vars .error.get_data');
							var_string.html(var_string.html().replace("%d%", intData));
						} else {
							var_string = $('.get-max-input-vars .yes');
							var_string.html(intData);
						}
						$('.get-max-input-vars .calculating').hide();
						var_string.fadeIn();
					} else {
						uncode_test_max_input_vars($vars+10000);
					}
				}
			});

		};
		uncode_test_max_input_vars(10000);

	});

</script>
