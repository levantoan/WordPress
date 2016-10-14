<?php 
global $resources_option;
?>
<div class="wrap">
	<h1>Resources Setting</h1>

	<form method="post" action="options.php" novalidate="novalidate">
	<?php
	settings_fields( 'resources-options-group' );
	?>
		<h2>Mailchimp Setting</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="mailchimp_api_key">Mailchimp API KEY</label></th>
					<td>
						<input type="text" class="regular-text" id="mailchimp_api_key" name="resources_options[mailchimp_api_key]" value="<?php echo $resources_option['mailchimp_api_key']?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="mailchimp_list_id">Mailchimp List ID</label></th>
					<td>
						<input type="text" class="regular-text" id="mailchimp_list_id" name="resources_options[mailchimp_list_id]" value="<?php echo $resources_option['mailchimp_list_id']?>"/>
					</td>
				</tr>				
				<?php do_settings_fields('resources-options-group', 'default'); ?>
			</tbody>
		</table>		
		<?php do_settings_sections('resources-options-group', 'default'); ?>
		<?php submit_button();?>
	</form>	
	<h2>Export</h2>
	<?php wp_nonce_field('export_nonce_action','export_nonce');?>
	<p class="submit"><input type="button" name="export" id="export" class="button button-primary" value="Export data"></p>
	<script>
	(function($){
		$("#export").click(function(){
			var nonce = $('#export_nonce').val();
			window.location.href = '<?php echo admin_url('admin-ajax.php?action=export_data_resourece&nonce=')?>'+nonce;
			return false;
		});
	})(jQuery);
	</script>
</div>