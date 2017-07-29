<?php
/*
Insert to functions.php
*/
function back_to_top(){
	ob_start();
	?>
	<a title="<?php _e('Lên đầu trang','devvn');?>" href="#" class="scrollTo_top"><i class="fa fa-arrow-up"></i></a>
	<style>
	.scrollTo_top{
		position: fixed;
		bottom: 50px;
		right: 10px;
		display: block;
		width: 30px;
		height: 30px;
		line-height: 30px;
		text-align: center;
		background: #4f1c78;
		color: #fff;
		text-decoration: none;	
		display: none;
		-webkit-border-radius: 0;
		-moz-border-radius: 0;
		-khtml-border-radius: 0;
		border-radius: 0;
		-webkit-box-shadow: inset 0 -2px 0 rgba(0,0,0,.2);
		-moz-box-shadow: inset 0 -2px 0 rgba(0,0,0,.2);
		-khtml-box-shadow: inset 0 -2px 0 rgba(0,0,0,.2);
		box-shadow: inset 0 -2px 0 rgba(0,0,0,.2);
	}
	.scrollTo_top:hover{
		background: #e03232;	
		color: #fff;	
	}
	</style>
	<script>
	(function($){
		$(document).ready(function() {
			$('.scrollTo_top').hide();
			$(window).scroll(function () {
				if( $(this).scrollTop() > 100 ) {
					$('.scrollTo_top').fadeIn(300);
				}
				else {
					$('.scrollTo_top').fadeOut(300);
				}
			});		 
			$('.scrollTo_top').click(function(){
				$('html, body').animate({scrollTop:0}, 500 );
				return false;
			});
		});
	})(jQuery);
	</script>
	<?php
	echo ob_get_clean();
}
add_action('wp_footer', 'back_to_top', 50);
