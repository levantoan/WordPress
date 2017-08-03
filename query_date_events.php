<?php
if($s_day || $s_month || $s_year || $e_day || $e_month || $e_year){
    $meta_query = array();
    $start_time = sprintf("%04d", intval($s_year)).sprintf("%02d", intval($s_month)).sprintf("%02d", intval($s_day));
    $end_time = sprintf("%04d", intval($e_year)).sprintf("%02d", intval($e_month)).sprintf("%02d", intval($e_day));
    $meta_query = array('relation' => 'OR');
    if($start_time) {
        $meta_query[0] = array('relation' => 'AND');
        $meta_query[0][] = array(
            'key'       =>  'start_time',
            'value'     =>  $start_time,
            'compare'   =>  '>='
        );
        $meta_query[0][] = array(
            'key'       =>  'start_time',
            'value'     =>  $end_time,
            'compare'   =>  '<='
        );
    }
    if($end_time) {
        $meta_query[1] = array('relation' => 'AND');
        $meta_query[1][] = array(
            'key'       =>  'end_time',
            'value'     =>  $start_time,
            'compare'   =>  '>='
        );
        $meta_query[1][] = array(
            'key'       =>  'end_time',
            'value'     =>  $end_time,
            'compare'   =>  '<='
        );
    }
    $args['meta_query'] = $meta_query;
    /*echo '<pre>';
    print_r($meta_query);
    echo '</pre>';*/
}
