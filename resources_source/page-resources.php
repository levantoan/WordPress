<?php
/**
 * Template Name: Resources Page
 */

get_header(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMP_URL_OG?>/resources_source/resources.css">
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type='text/javascript'>
/* <![CDATA[ */
var og_array = {
		"ajax_url" : '<?php echo admin_url('admin-ajax.php');?>',
	};
/* ]]> */
</script>
<script type='text/javascript' src='<?php echo TEMP_URL_OG?>/resources_source/resources.js'></script>

<?php if(!is_resources_logged_in()):?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">      
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="form_resources">
			<?php do_action('before_form_resources');?>		
			<div class="form_mess alert"></div>
			<div id="form_login">
				<h2 class="title-form">Login</h2>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="email">Email address</label>
				    <input type="email" class="form-control" name="email_login" id="email_login" placeholder="Email">
				  </div>			  
				  <div class="form-group"><p>New to Vortex? <a href="#form_register" title="Register" class="gotoForm" data-form="Register">Register</a></p></div>
				  <?php wp_nonce_field('login_nonce_action','login_nonce');?>
				  <button type="submit" class="btn btn-default btn-form btn-login">Login</button>	 <span class="spinner"></span>		  
				</form>
			</div>
			<div id="form_register">
				<h2 class="title-form">Register</h2>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="email">Email address</label>
				    <input type="email" class="form-control" name="email_register" id="email_register" placeholder="Email">
				  </div>
				  <div class="form-group">
				    <label for="company">Company / institution</label>
				    <input type="text" class="form-control" name="company_register" id="company_register" placeholder="Company/institution">
				  </div>
				  <div class="form-group receive_infor_style">
				  	<label><input type="checkbox" value="yes" name="receive_infor_f" id="receive_infor_f"> I would like to Take the Next Step in the Fight Against Cancer and receive informative emails from Vortex Biosciences</label>
				  </div>
				  <div class="form-group"><p>Have an account? <a href="#form_login" title="Login" class="gotoForm" data-form="Login">Login</a></p></div>
				  <?php wp_nonce_field('register_nonce_action','register_nonce');?>
				  <button type="submit" class="btn btn-default btn-form btn-register">Sign Up</button> <span class="spinner"></span>
				</form>
			</div>
			<?php do_action('after_form_resources');?>
		</div>
      </div>      
    </div>
  </div>
</div>
<?php endif;?>
<?php 
$resources_category = array(6,7,8,9);
$category_name = 'resources-category';
?>
<div id="sub-nav">
	<div id="sub-nav-menu">
		<ul>
			<?php foreach ($resources_category as $cat):
			$cat = get_term_by('term_id', $cat, $category_name);
			?>
				<li><a href="javascript:scrollToTheTop('#<?php echo $cat->slug;?>');"><?php echo $cat->name;?></a></li>
			<?php endforeach;?>			
		</ul>
	</div>
</div>
<?php 
$stt = 1;
foreach ($resources_category as $cat_id):
$cat = get_term_by('term_id', $cat_id, $category_name);
?>
<div id="<?php echo $cat->slug;?>">
<div id="contact-info-area" <?php if($stt%2 == 0):?>class="grey"<?php endif;?>>
    <div id="news-section">
        <div id="contact-info-header">
            <h1><?php echo $cat->name;?></h1>
        </div>
        <div id="news-container">
        <?php
        $resources_post = new WP_Query(array(
        	'post_type' 		=> 'all-resources',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
        	'posts_per_page'	=>	-1,
        	'tax_query'			=>	array(
        		array(
					'taxonomy' => $category_name,
					'field'    => 'term_id',
					'terms'    => array($cat_id),
				),
        	)
        ));
        $currentUserID = '';
        $currentUserEmail = get_resources_cookie();
        $currentUserID = intval(get_user_resources_by_email($currentUserEmail));
		if($resources_post->have_posts()):
			while ($resources_post->have_posts()):$resources_post->the_post();
				$news_date = get_the_date();
				if(!is_resources_logged_in()):
					$news_link = '#register';
					//$download = '';
					$class_a = 'login_to_download';
				else:
					$news_link = esc_url(get_field('file_download'));
					//$download = 'download';
					$class_a = 'save_download';
				endif;				
				$news_title = get_the_title();
				echo '<div class="news-item row-fluid">
							<div class="span12"><h3><a target="_blank" href="'.$news_link.'" class="'.$class_a.'" data-fileid="'.get_the_ID().'" data-user="'.$currentUserID.'">' . $news_title . '</a></h3></div>    
					  </div>';
					  //<div class="span3"><h3>' . $news_date . '</h3></div> Removed the Date
			endwhile;	
		else: 
			echo '<div class="news-item row-fluid">
	                <h3>Check back soon</h3>
	            </div>';				                     
		endif;
		wp_reset_query();
		?>
        </div>
    </div>
</div>
</div>
<?php $stt++; endforeach;?>
<?php get_footer(); ?>