<?php

if ('wpst-admin.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

if ( !is_admin() ) {
	die();
}
if ( !current_user_can( 'manage_options' ) ) :
	wp_die( 'You do not have sufficient permissions to access this page.' );
endif;

if ( isset( $_POST['wpst-submit'] ) ) :
    if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpst-nonce'] ), 'wpst-nonce' ) ) die( 'Invalid Nonce.' );
	if ( function_exists( 'current_user_can' ) && current_user_can( 'edit_plugins' ) ) :
		update_option( 'wpst_country_selected', sanitize_text_field( $_POST['wpst_country_selected'] ) );
		update_option( 'wpst_suggestions_limit', sanitize_text_field( $_POST['wpst_suggestions_limit'] ) );
		update_option( 'wpst_sortfield', sanitize_text_field( $_POST['wpst_sortfield'] ) );
		update_option( 'wpst_sorttype', sanitize_text_field( $_POST['wpst_sorttype'] ) );
		update_option( 'wpst_api_key', sanitize_text_field( $_POST['wpst_api_key'] ) );
		echo '<div class="updated fade"><p>Options updated and saved.</p></div>';
else :
	wp_die( '<p>' . 'You do not have sufficient permissions.' . '</p>' );
endif;
endif;
?>
<div id="wpst-options" class="wrap">
<div id="wpst-options-icon" class="icon32"><br /></div>
<h2><?php _e('WP SEO Title Options', _PLUGIN_NAME_); ?></h2>
<form class="wpst-form" name="wpst-options" method="post" action="">
<h3><?php _e('Country'); ?></h3>
<span class="description"><?php _e('Select the Country that is used for Google requests.', _PLUGIN_NAME_); ?></span>
<table style="min-width:400px">
<tr>
    <td>
    <input type="hidden" id="wpst_country_selected" name="wpst_country_selected" value="" />
	<select id="wpst_country_selected_ul" name="wpst_country_selected_ul" style="min-width:200px;">
	<?php

	$countries = get_option( 'wpst_countries' );
	$wpst_country_selected = get_option( 'wpst_country_selected' );
	foreach ( $countries as $code => $name ) :
		echo '<option value="' . $code . '" data-imagesrc="'._WPST_PATH_.'/images/flags/' . $code . '.png"
				data-description="' . $name . '"';
		if ( $wpst_country_selected == $code ) echo ' selected="selected"';
		echo '>' . strtoupper($code) . '</option>';
	endforeach
	?>
	</select>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#wpst_country_selected_ul').ddslick({
		    width: 80,
		    onSelected: function(data){
		        if(data.selectedIndex >= 0) {
		        	$('#wpst_country_selected').val(data.selectedData.value);
		        }
		    }   
		});
	});
	</script>
    </td>
    <!--<td>
	<input type="hidden" name="upc_action" value="update_list_c" />
	<input type="submit" name="Submit" value="<?php _e('Update List Countries', _PLUGIN_NAME_); ?>" class="button" />
    </td>-->
</tr>
</table>
<br />
<h3>General</h3>
<table class="form-table">
<!--<tr valign="top">
	<th scope="row"><label for="wpst-api-key"><?php _e('API KEY', _PLUGIN_NAME_); ?></label></th>
	<td>
		<input type="text" class="code regular-text" name="wpst_api_key" id="wpst_api_key" value="<?php echo get_option( 'wpst_api_key' ); ?>" />
		<span class="description"><a href="#BETA" target="_blank"><?php _e('Get API KEY', _PLUGIN_NAME_); ?></a></span>
	</td>
</tr>-->
<tr valign="top">
<th scope="row">
<label for="wpst-value"><?php _e('Value'); ?></label>
</th>
<td>
<select id="wpst_sortfield" name="wpst_sortfield" style="min-width:80px;">
<?php
$suggestions_limit = array( __('Volume', _PLUGIN_NAME_) => 'volume', __('CPC', _PLUGIN_NAME_) => 'cpc', __('Profit', _PLUGIN_NAME_) => 'profit' );
foreach ( $suggestions_limit as $limit => $value ) :
	echo '<option value="' . $value . '" ';
	if ( get_option( 'wpst_sortfield' ) == $value ) echo 'selected="selected"';
	echo '>' . $limit . '</option>';
endforeach
?>
</select>
<span class="description"><?php _e('Option to order', _PLUGIN_NAME_); ?></span>
</td></tr>
<tr valign="top">
<th scope="row">
<label for="wpst-value"><?php _e('Order'); ?></label>
</th>
<td>
<select id="wpst_sorttype" name="wpst_sorttype" style="min-width:80px;">
<?php
$suggestions_limit = array( 'DESC' => 'desc', 'ASC' => 'asc' );
foreach ( $suggestions_limit as $limit => $value ) :
	echo '<option value="' . $value . '" ';
	if ( get_option( 'wpst_sorttype' ) == $value ) echo 'selected="selected"';
	echo '>' . $limit . '</option>';
endforeach
?>
</select>
<span class="description"><?php _e('DESC: for a descending sort. ASC: for a ascending sort', _PLUGIN_NAME_); ?></span>
</td></tr>

</table>
<?php wp_nonce_field( 'wpst-nonce', 'wpst-nonce', false ) ?> 
<p class="submit"><input id="wpst-submit" type="submit" name="wpst-submit" class="button-primary wpst-button" value="<?php _e('Save Changes', _PLUGIN_NAME_); ?>" /></p>
</form>
</div>
<div class="clear"></div>
