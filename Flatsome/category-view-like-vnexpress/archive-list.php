<?php
/*
* Flatsome theme
* File in {your-child-theme}/template-parts/posts
*/
?>
<?php if ( have_posts() ) : ?>
	<style>
		.news_latest_top:after {
			content: "";
			display: table;
			clear: both;
		}
		.nlt_left {
			width: 70%;
			float: left;
			margin-bottom: 15px;
		}
		.nlt_right {
			float: right;
			width: 30%;
		}
		@media (min-width: 768px){
			.nlt_left {
				padding-right: 10px;
			}
			.nlt_right {
				padding-left: 10px;
			}
		}
		.nlt_right .news_box_img {
			display: none;
		}
		.img_respon img {
			right: 0;
			width: 100%;
			height: 100%;
			bottom: 0;
			left: 0;
			top: 0;
			position: absolute;
			object-position: 50% 50%;
			object-fit: cover;
		}
		.img_respon {
			position: relative;
			overflow: hidden;
			padding-top: 56.32%;
			height: auto;
			background-position: 50% 50%;
			background-size: cover;
		}
		.news_box_img {
			margin: 0 0 10px;
		}
		.news_box_img {
			width: 300px;
			float: left;
			margin: 0 10px 10px 0;
		}
		.news_box_desc {
			overflow: hidden;
		}
		.news_box_title {
			font-size: 16px;
			font-weight: 700;
			color: #000;
			margin: 0 0 10px 0;
		}
		.news_box_title a{
			color: #000;
		}
		.nlt_left .news_box_img {
			width: 100%;
			margin: 0 0 10px 0;
		}
		.nlt_right .news_box_title {
			font-size: 14px;
			font-weight: normal;
		}
		.news_box:after {
			content: "";
			display: table;
			clear: both;
		}
	</style>
	<div class="news_latest">
		<?php
		global $wp_query;

		$post_count = $wp_query->post_count;

		$posts_per_page = 12;
		$posts_per_page_row2 = 4;
		$post_row2 = ($posts_per_page <= $posts_per_page_row2) ? 0 : $posts_per_page - $posts_per_page_row2;
		$start_row2 = $post_row2 + 1;

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		$stt = 1;
		while(have_posts()):the_post();?>
			<?php if($paged == 1 && ($stt <= $post_row2 || $post_count <= $post_row2 || $post_row2 == 0)):?>
				<?php if($stt == 1):?><div class="news_latest_top"><?php endif;?>
				<?php if($stt == 1):?>
					<div class="nlt_left">
						<div class="news_box news_img_title_desc">
							<a href="<?php the_permalink();?>" title="<?php the_title();?>">
								<?php if(has_post_thumbnail()):?><div class="news_box_img"><div class="img_respon"><?php the_post_thumbnail('medium');?></div></div><?php endif;?>
								<div class="news_box_title"><?php the_title();?></div>
							</a>
							<div class="news_box_desc"><?php the_excerpt();?></div>
						</div>
					</div>
				<?php else:?>
					<?php if($stt == 2):?><div class="nlt_right scrollbar-inner"><?php endif;?>
					<div class="news_box news_img_title">
						<a href="<?php the_permalink();?>" title="<?php the_title();?>">
							<?php if(has_post_thumbnail()):?><div class="news_box_img"><div class="img_respon"><?php the_post_thumbnail('medium');?></div></div><?php endif;?>
							<div class="news_box_title"><?php the_title();?></div>
						</a>
					</div>
					<?php if($stt == $post_row2 || $stt == $post_count):?></div><?php endif;?>
				<?php endif;?>
				<?php if($stt == $post_row2 || $stt == $post_count):?></div><?php endif;?>
			<?php else:?>
				<?php if(($paged != 1 && $stt == 1) || ($paged == 1 && $stt == $start_row2)):?><div class="news_latest_bottom"><?php endif;?>
					<div class="news_box news_img_title">
						<div class="news_box_title"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></div>
						<?php if(has_post_thumbnail()):?><div class="news_box_img">
							<a href="<?php the_permalink();?>" title="<?php the_title();?>"><div class="img_respon"><?php the_post_thumbnail('medium');?></div></a>
						</div><?php endif;?>
						<div class="news_box_desc"><?php the_excerpt();?></div>
					</div>
				<?php if($stt == $post_count):?></div><?php endif;?>
			<?php endif;?>
			<?php $stt++; endwhile;?>
	</div>

	<?php flatsome_posts_pagination(); ?>

<?php else : ?>

	<?php get_template_part( 'template-parts/posts/content','none'); ?>

<?php endif; ?>
