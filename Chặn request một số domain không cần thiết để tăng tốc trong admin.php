<?php
/*
Author levantoan.com
Chặn request một số domain không cần thiết để tăng tốc trong admin
*/

function block_specific_domains_pre_http_request($false, $parsed_args, $url) {
    $blocked_domains = array(
        'wppopupmaker.com',
        'api.uxthemes.com',
        'connect.advancedcustomfields.com',
    );

    $parsed_url = wp_parse_url($url);

    if (isset($parsed_url['host']) && in_array($parsed_url['host'], $blocked_domains)) {
        //return new WP_Error('blocked_domain', 'Domain này đã bị chặn.');
        return true;
    }

    return $false;
}

add_filter('pre_http_request', 'block_specific_domains_pre_http_request', 10, 3);
