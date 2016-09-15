<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>
<div class="wrap">
	<h1><?php _e('YouTube Videos Setting','devvn')?></h1>
	<form method="post" action="options.php" novalidate="novalidate">
	<?php
	settings_fields( 'ytvideos-settings-group' );	
	?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="number_col">Number Col</label></th>
					<td>
						<select id="number_col" name="ytvideos_settings[number_col]">
							<?php for ($i=1 ; $i<=apply_filters('max_ytvideos_col', 5); $i++){?>
							<option value='<?php echo $i?>' <?php selected($i,get_ytvideos_option('number_col'))?>><?php echo $i?></option>
							<?php };?>							
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="posts_per_page">Number Col</label></th>
					<td>
						<select id="posts_per_page" name="ytvideos_settings[posts_per_page]">
							<?php for ($i=1 ; $i<=apply_filters('max_ytvideos_per_page', 20); $i++){?>
							<option value='<?php echo $i?>' <?php selected($i,get_ytvideos_option('posts_per_page'))?>><?php echo $i?></option>
							<?php };?>							
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="has-sidebar">View Sidebar</label></th>
					<td>
						<label><input id="has-sidebar" type="checkbox" name="ytvideos_settings[has_sidebar]" value='1' <?php checked('1',get_ytvideos_option('has_sidebar'))?>/> View sidebar</label><br/>
						<label><input type="radio" name="ytvideos_settings[page_has_sidebar]" value='all' <?php checked('all',get_ytvideos_option('page_has_sidebar'))?>/> Both</label><br/>
						<label><input type="radio" name="ytvideos_settings[page_has_sidebar]" value='archive' <?php checked('archive',get_ytvideos_option('page_has_sidebar'))?>/> Archive page</label><br/>
						<label><input type="radio" name="ytvideos_settings[page_has_sidebar]" value='single' <?php checked('single',get_ytvideos_option('page_has_sidebar'))?>/> Single page</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sidebar-position">Sidebar Position</label></th>
					<td>
						<label><input id="sidebar-position" type="radio" name="ytvideos_settings[sidebar_position]" value='right' <?php checked('right',get_ytvideos_option('sidebar_position'))?>/> Right sidebar</label><br>
						<label><input id="sidebar-position" type="radio" name="ytvideos_settings[sidebar_position]" value='left' <?php checked('left',get_ytvideos_option('sidebar_position'))?>/> Left sidebar</label>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table">
			<tbody>	
			<h2 class="title">Style Customer</h2>
				<tr>
					<th scope="row"><label for="padding_left_right">Padding Left - Right:</label></th>
					<td>
						<input id="padding_left_right" type="number" min="0" name="ytvideos_settings[padding_left_right]" value="<?php echo get_ytvideos_option('padding_left_right');?>"/> px
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="margin_bottom">Margin bottom video box:</label></th>
					<td>
						<input id="margin_bottom" type="number" min="0" name="ytvideos_settings[margin_bottom]" value="<?php echo get_ytvideos_option('margin_bottom');?>"/> px
					</td>
				</tr>	
				<tr>
					<th scope="row"><label for="main_width">Main width:</label></th>
					<td>
						<input id="main_width" type="number" min="0" name="ytvideos_settings[main_width]" value="<?php echo get_ytvideos_option('main_width');?>"/> %
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sidebar_width">Sidebar width:</label></th>
					<td>
						<input id="sidebar_width" type="number" min="0" name="ytvideos_settings[sidebar_width]" value="<?php echo get_ytvideos_option('sidebar_width');?>"/> %
					</td>
				</tr>			
			</tbody>
		</table>
		<table class="form-table">
			<tbody>	
			<h2 class="title">Responsive Customer</h2>
				<tr>
					<th scope="row"><label for="respon_1200">Client width:<br><small>@meida (max-width: 1199px)</small></label></th>
					<td>
						<input id="respon_1200" type="number" name="ytvideos_settings[respon_1200]" value="<?php echo get_ytvideos_option('respon_1200');?>"/> Column
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="respon_992">Client width:<br><small>@meida (max-width: 991px)</small></label></th>
					<td>
						<input id="respon_992" type="number" name="ytvideos_settings[respon_992]" value="<?php echo get_ytvideos_option('respon_992');?>"/> Column
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="respon_768">Client width:<br><small>@meida (max-width: 767px)</small></label></th>
					<td>
						<input id="respon_768" type="number" name="ytvideos_settings[respon_768]" value="<?php echo get_ytvideos_option('respon_768');?>"/> Column
					</td>
				</tr>
			</tbody>
		</table>
		<?php do_settings_fields('ytvideos-settings-group', 'default'); ?>
		<?php do_settings_sections('ytvideos-settings-group', 'default'); ?>
		<?php submit_button();?>
	</form>
</div>
