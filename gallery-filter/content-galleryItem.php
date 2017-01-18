<?php			
$list_title = get_post_meta( get_the_ID(), 'gallery_title_post', true );
$list_website = get_post_meta( get_the_ID(), 'gallery_website', true );
$all_tax_to_class = all_tax_to_class(get_the_ID(),array('gallery-company','gallery-country'));
?>
<div class="gallery_filter_item <?php echo implode(' ',$all_tax_to_class);?>">
	<?php if(has_post_thumbnail()):?>
	<div class="gallery_filter_thumbnail">
	<?php the_post_thumbnail('thumbnail');?>
	</div>
	<?php endif;?>
	<div class="gallery_filter_infor">
		<div class="devvn_infor_title">
		<p><?php the_title();?></p>
		<?php if(!empty($list_title) && is_array($list_title)){?>
		<?php foreach ($list_title as $title):?>
		<p><?php echo $title;?></p>
		<?php endforeach;?>
		<?php }?>
		</div>						
		<?php if(!empty($list_website) && is_array($list_website)){?>
		<div class="devvn_infor_website">
		<?php foreach ($list_website as $web):?>
		<p><a target="_blank" href="<?php echo (isset($web['link'])?esc_url($web['link']):'')?>"><?php echo (isset($web['title']) && !empty($web['title']))?esc_attr($web['title']):(isset($web['link'])?$web['link']:'');?></a></p>
		<?php endforeach;?>
		</div>
		<?php }?>						
	</div>
</div>