<?php
//Add to wp-config.php
//Multi Domain for a site
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);


//Add this to head. SEo
<link rel="canonical" href="your main doamin" />
