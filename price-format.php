<?php
function devvn_price( $price, $args = array() ) {

    $args = apply_filters(
        'devvn_price_args',
        wp_parse_args(
            $args,
            array(
                'ex_tax_label'       => false,
                'currency'           => apply_filters( 'devvn_currency_symbol', '₫' ),
                'decimal_separator'  => apply_filters( 'devvn_get_price_decimal_separator', '.' ),
                'thousand_separator' => stripslashes( apply_filters( 'devvn_get_price_thousand_separator', ',' ) ),
                'decimals'           => absint( apply_filters( 'devvn_get_price_decimals', 0 ) ),
                'price_format'       => apply_filters( 'devvn_price_format', '%2$s%1$s' ),
            )
        )
    );

    $unformatted_price = $price;
    $negative          = $price < 0;
    $price             = apply_filters( 'raw_devvn_price', floatval( $negative ? $price * -1 : $price ) );
    $price             = apply_filters( 'formatted_devvn_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

    if ( apply_filters( 'devvn_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
        $price = preg_replace( '/' . preg_quote( apply_filters( 'devvn_get_price_decimal_separator', ',' ), '/' ) . '0++$/', '', $price );
    }

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="devvn-Price-currencySymbol">' . $args['currency'] . '</span>', $price );
    $return          = '<span class="devvn-Price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';

    if ( $args['ex_tax_label'] ) {
        $return .= ' <small class="devvn-Price-taxLabel tax_label">Đã bao gồm VAT</small>';
    }

    return apply_filters( 'devvn_price', $return, $price, $args, $unformatted_price );
}
