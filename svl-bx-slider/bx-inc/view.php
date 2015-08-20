<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!$bxslider_data && !is_array($bxslider_data)) return;
?>
<ul class="bxslider-<?php echo $idSlider;?>">
<?php
foreach ($bxslider_data as $slider):
	$images = $slider["images"];
	$title = $slider["title"];
	$desc = $slider["desc"];
	$link = $slider["link"];
	$open = $slider["openwindows"];
?>
	<li>
	<?php if($link):?><a href="<?php echo esc_url($link);?>" target="<?php echo ($open) ? "_blank" : "";?>"><?php endif;?>
		<div class="bx-content">
			<img src="<?php echo $images;?>" />
			<?php if($title || $desc):?>
			<div class="bx-title-desc">
				<?php if($title):?><h3><?php echo $title;?></h3><?php endif;?>
				<?php if($desc):?><p><?php echo $desc;?></p><?php endif;ss?>
			</div>
			<?php endif;?>
		</div>
	<?php if($link):?></a><?php endif;?>
	</li>
<?php endforeach;?>
</ul>
<?php
$mode =	($bxslider_setting['mode']) ? $bxslider_setting['mode'] : "horizontal";
$responsive = ($bxslider_setting['responsive'] == "on")? 'true' : 'false';
$pager = ($bxslider_setting['pager'] == "on")? 'true' : 'false';
$controls = ($bxslider_setting['controls'] == "on")? 'true' : 'false';
$nexttext = ($bxslider_setting['nexttext'])? esc_attr($bxslider_setting['nexttext']) : "";
$prevtext = ($bxslider_setting['prevtext'])? esc_attr($bxslider_setting['prevtext']) :"";
$adaptiveHeight = ($bxslider_setting['adaptiveHeight'] == "on")? 'true' : 'false';
?>
<script>
	jQuery(document).ready(function($){
		$(".bxslider-<?php echo $idSlider;?>").bxSlider({
			mode : "<?php echo $mode;?>",
			responsive: <?php echo $responsive;?>,
			pager: <?php echo $pager;?>,
			controls: <?php echo $controls;?>,
			nextText: "<?php echo $nexttext;?>",
			prevText: "<?php echo $prevtext;?>",
			adaptiveHeight: <?php echo $adaptiveHeight;?>
		});
	});
</script>