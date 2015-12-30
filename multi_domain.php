<?php
//Add to wp-config.php
//Multi Domain for a site
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);


//Add this to head. SEo
<link rel="canonical" href="your main doamin" />

//Edit canonical in Yoat SEO to main domain

//Remove canonical default
add_filter( 'wpseo_canonical', '__return_false' );

//old domain to new domain
add_filter('wpseo_canonical', 'swpseo_canonical_domain_replace');
function swpseo_canonical_domain_replace($url){
    $domain = 'newdomain.com';//Change this
    $parsed = parse_url(home_url());
    $current_site_domain = $parsed['host'];
    return str_replace($current_site_domain, $domain, $url);
}
