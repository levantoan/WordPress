<?php get_header();?>
<div class="ytvideos_wrap <?php class_ytvideos()?>">
	<h1 class="main-title"><?php _e('Videos','devvn')?></h1>
	<?php
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
	?>
	<?php do_action('breadcrumbs_ytvideos')?>
	<div class="ytvideos_container">	
		<div class="ytvideos_row">
			<div class="ytvideos_wrap_main">
			<?php do_action('before_archive_ytvideos_main')?>
			<?php if(have_posts()):?>
				<div class="ytvideos_list">
				<?php while (have_posts()):the_post();?>
					<?php ytvideos_get_template_part('content','videos');?>
				<?php endwhile;?>				
				</div>
				<?php ytvideos_paginate();?>
			<?php endif;?>
			<?php do_action('after_archive_ytvideos_main')?>
			</div>
			<?php if(get_ytvideos_option('has_sidebar') && (get_ytvideos_option('page_has_sidebar') == 'all' || get_ytvideos_option('page_has_sidebar') == 'archive')):?>
				<?php ytvideos_get_template_part('sidebar','videos');?>
			<?php endif;//End view sidebar?>
		</div>
	</div>
</div>
<?php get_footer();?>