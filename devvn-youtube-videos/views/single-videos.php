<?php get_header();?>
<div class="ytvideos_wrap <?php class_ytvideos()?>">
	<h1 class="main-title"><?php the_title();?></h1>	
	<?php do_action('breadcrumbs_ytvideos')?>
	<div class="ytvideos_container">	
		<div class="ytvideos_row">
			<div class="ytvideos_wrap_main">
			<?php do_action('before_single_ytvideos_main')?>
			<?php if(have_posts()):?>
				<div class="single_ytvideos_box">
				<?php while (have_posts()):the_post();
				$videoID = get_ytvideos_data();
				?>
					<?php do_action('before_video_ytvideos')?>
					<div class="videoWrapper"><iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $videoID;?>" frameborder="0" allowfullscreen></iframe></div>
					<?php do_action('after_video_ytvideos')?>
					<div class="single_ytvideos_box_content"><?php the_content();?></div>
					<?php do_action('after_content_ytvideos')?>
				<?php endwhile;?>				
				</div>				
			<?php endif;?>
			<?php do_action('after_single_ytvideos_main')?>
			</div>
			<?php if(get_ytvideos_option('has_sidebar') && (get_ytvideos_option('page_has_sidebar') == 'all' || get_ytvideos_option('page_has_sidebar') == 'single')):?>
				<?php ytvideos_get_template_part('sidebar','videos');?>
			<?php endif;//End view sidebar?>
		</div>
	</div>
</div>
<?php get_footer();?>