<?php

class devvnDateRange{
    private $post_type_allow = array('shop_order');
    function __construct(){
        // if you do not want to remove default "by month filter", remove/comment this line
        add_filter( 'months_dropdown_results', array($this, 'devvn_remove_month_filter'), 10, 2 );

        // include CSS/JS, in our case jQuery UI datepicker
        add_action( 'admin_enqueue_scripts', array( $this, 'jqueryui' ) );

        // HTML of the filter
        add_action( 'restrict_manage_posts', array( $this, 'form' ) );

        // the function that filters posts
        add_action( 'pre_get_posts', array( $this, 'filterquery' ) );
    }

    function devvn_remove_month_filter($months, $post_type){
        if(in_array($post_type, $this->post_type_allow))
            return array();
        return $months;
    }

    function jqueryui(){
        global $typenow;
        if(in_array($typenow, $this->post_type_allow)) {
            wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css');
            wp_enqueue_script('jquery-ui-datepicker');
        }
    }

    function form(){
        global $typenow;
        if(in_array($typenow, $this->post_type_allow)){
            $from = (isset($_GET['devvnDateFrom']) && $_GET['devvnDateFrom']) ? $_GET['devvnDateFrom'] : '';
            $to = (isset($_GET['devvnDateTo']) && $_GET['devvnDateTo']) ? $_GET['devvnDateTo'] : '';

            ?>
            <style>
                input[name="devvnDateFrom"], input[name="devvnDateTo"] {
                    line-height: 28px;
                    height: 28px;
                    margin: 0;
                    width: 125px;
                }
            </style>

            <input type="text" name="devvnDateFrom" placeholder="Từ ngày" value="<?php echo $from; ?>"/>
            <input type="text" name="devvnDateTo" placeholder="Đến ngày" value="<?php echo $to; ?>"/>

            <script>
                jQuery(function ($) {
                    var from = $('input[name="devvnDateFrom"]'),
                        to = $('input[name="devvnDateTo"]');

                    $('input[name="devvnDateFrom"], input[name="devvnDateTo"]').datepicker({dateFormat: "yy-mm-dd"});
                    from.on('change', function () {
                        to.datepicker('option', 'minDate', from.val());
                    });
                    to.on('change', function () {
                        from.datepicker('option', 'maxDate', to.val());
                    });
                });
            </script>
            <?php
        }
    }
    function filterquery( $admin_query ){
        global $pagenow, $typenow;
        if (
            is_admin()
            && $admin_query->is_main_query()
            && in_array( $pagenow, array( 'edit.php', 'upload.php' ) )
            && in_array( $typenow, $this->post_type_allow )
            && ( ! empty( $_GET['devvnDateFrom'] ) || ! empty( $_GET['devvnDateTo'] ) )
        ) {

            $admin_query->set(
                'date_query',
                array(
                    'after' => isset($_GET['devvnDateFrom']) ? $_GET['devvnDateFrom'] : '', // any strtotime()-acceptable format!
                    'before' => isset($_GET['devvnDateTo']) ? $_GET['devvnDateTo'] : '',
                    'inclusive' => true, // include the selected days as well
                    'column'    => 'post_date' // 'post_modified', 'post_date_gmt', 'post_modified_gmt'
                )
            );
        }
        return $admin_query;
    }

}
new devvnDateRange();
