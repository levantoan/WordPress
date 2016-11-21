<?php
function back_to_top(){
	ob_start();
	?>
	<a title="Lên đầu trang" href="#" class="scrollTo_top"><i class="fa fa-angle-up"></i></a>
	<style>
	.scrollTo_top{
		position: fixed;
		bottom: 20px;
		right: 10px;
		display: block;
		width: 30px;
		height: 30px;
		line-height: 30px;
		text-align: center;
		background: #6d6d6d;
		color: #fff;
		text-decoration: none;	
		display: none;	
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
