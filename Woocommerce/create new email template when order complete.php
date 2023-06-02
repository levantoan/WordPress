<?php
add_filter('woocommerce_email_classes', 'devvn_email_coupon_completed');
function devvn_email_coupon_completed($emails){

    if ( ! class_exists( 'WC_Email_Coupon_Completed', false ) ) :

        class WC_Email_Coupon_Completed extends WC_Email {

            public function __construct() {
                $this->id             = 'coupon_completed_order';
                $this->customer_email = true;
                $this->title          = __( 'Coupon Completed', 'woocommerce' );
                $this->description    = __( 'Email này sẽ gửi mã giảm giá cho khách khi đơn hàng hoàn thành.', 'woocommerce' );
                $this->template_html  = 'emails/customer-coupon-completed.php';
                $this->template_plain = 'emails/plain/customer-coupon-completed.php';
                $this->placeholders   = array(
                    '{order_date}'   => '',
                    '{order_number}' => '',
                );

                // Triggers for this email.
                add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );

                // Call parent constructor.
                parent::__construct();
            }

            public function trigger( $order_id, $order = false ) {
                $this->setup_locale();

                if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
                    $order = wc_get_order( $order_id );
                }

                if ( is_a( $order, 'WC_Order' ) ) {
                    $this->object                         = $order;
                    $this->recipient                      = $this->object->get_billing_email();
                    $this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
                    $this->placeholders['{order_number}'] = $this->object->get_order_number();
                }

                if ( $this->is_enabled() && $this->get_recipient() ) {
                    $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
                }

                $this->restore_locale();
            }

            public function get_default_subject() {
                return __( 'Tặng bạn mã giảm giá', 'woocommerce' );
            }

            public function get_default_heading() {
                return __( 'Tặng bạn mã giảm giá', 'woocommerce' );
            }

            public function get_default_additional_content() {
                return __( 'Thanks for shopping with us.', 'woocommerce' );
            }

            public function get_content_html() {
                return wc_get_template_html(
                    $this->template_html,
                    array(
                        'order'              => $this->object,
                        'email_heading'      => $this->get_heading(),
                        'additional_content' => $this->get_additional_content(),
                        'sent_to_admin'      => false,
                        'plain_text'         => false,
                        'email'              => $this,
                    )
                );
            }

            public function get_content_plain() {
                return wc_get_template_html(
                    $this->template_plain,
                    array(
                        'order'              => $this->object,
                        'email_heading'      => $this->get_heading(),
                        'additional_content' => $this->get_additional_content(),
                        'sent_to_admin'      => false,
                        'plain_text'         => true,
                        'email'              => $this,
                    )
                );
            }

        }

    endif;

    $emails['WC_Email_Coupon_Completed'] = new WC_Email_Coupon_Completed();
    return $emails;
}
