<?php
/*
* Author: www.levantoan.com
* Orderby multi meta_query
* Orderby _kksr_avg DESC and _kksr_casts DESC
*/
add_action( 'pre_get_posts', 'orderby_kk_star_ratings' );
function orderby_kk_star_ratings($query){
            if ( !is_admin() && (is_tax(array('category_quotes','author_category')) || is_post_type_archive('devvn_quotes')) && $query->is_main_query() ) {
                $query->set('orderby', array(
                    'meta_value_num' => 'DESC',
                    'kksr_ratings_clause' => 'DESC',
                    'kksr_avg_clause' => 'DESC'
                ));
                $query->set('meta_query' , array(
                    'relation' => 'OR',
                    array(
                        'relation' => 'AND',
                        'kksr_avg_clause' => array(
                            'key'=>'_kksr_avg',
                            'compare' => 'EXISTS',
                        ),
                        'kksr_ratings_clause' => array(
                            'key'=>'_kksr_casts',
                            'compare' => 'EXISTS',
                        )
                    ),
                    array(
                        'key'=>'_kksr_casts',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'=>'_kksr_avg',
                        'compare' => 'NOT EXISTS',
                    )
                ));
            }
        }
