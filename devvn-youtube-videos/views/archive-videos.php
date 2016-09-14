<?php get_header();?>
<div class="ytvideos_wrap <?php if(get_ytvideos_option('has_sidebar')):?>has_sidebar<?php endif;?>">
	<h1 class="main-title"><?php _e('Videos','devvn')?></h1>
	<?php
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
	?>
	<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
	    <?php if(function_exists('bcn_display'))
	    {
	        bcn_display();
	    }?>
	</div>
	<div class="ytvideos_container">	
		<div class="ytvideos_row">
			<div class="ytvideos_wrap_main">
			<?php if(have_posts()):?>
				<div class="ytvideos_list">
				<?php while (have_posts()):the_post();?>
					<?php ytvideos_get_template_part('content','videos');?>
				<?php endwhile;?>				
				</div>
				<?php ytvideos_paginate();?>
			<?php endif;?>
			</div>
			<?php if(get_ytvideos_option('has_sidebar')):?>
			<div class="ytvideos_wrap_sidebar">
				<?php get_sidebar('videos')?>
			</div>
			<?php endif;//End view sidebar?>
		</div>
	</div>
</div>
<?php get_footer();?>