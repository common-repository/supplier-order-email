<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class McisoeHelpers
{
    public static function mcisoe_premium_text( $auth_premium )
    {
        if ( !$auth_premium ) {
            echo " (" . esc_html__( 'Premium version', 'supplier-order-email' ) . ")";
        }
    }

    public static function mcisoe_premium_text_without_echo( $auth_premium )
    {
        if ( !$auth_premium ) {
            return " (" . esc_html__( 'Premium version', 'supplier-order-email' ) . ")";
        } else {
            return '';
        }
    }

    public function search_in_child_theme( $file, $type, $auth_premium )
    {
        $child_template_path = get_stylesheet_directory() . '/supplier-order-email/' . $file;

        if ( file_exists( $child_template_path ) && $auth_premium == '1' ) {
            if ( $type == 'css' ) {
                $template_path = get_stylesheet_directory_uri() . '/supplier-order-email/' . $file;
            } else {
                $template_path = $child_template_path;
            }
        } else {
            if ( $type == 'email' ) {
                $template_path = MCISOE_PLUGIN_DIR . 'includes/email/email-template-parts/' . $file;
            } elseif ( $type == 'pdf' ) {
                $template_path = MCISOE_PLUGIN_DIR . 'includes/email/pdf-template-parts/' . $file;
            } elseif ( $type == 'css' ) {
                $template_path = $html = MCISOE_PLUGIN_URL . 'pdf/css/pdf_styles.css';
            }
        }

        return $template_path;
    }

    public function build_price_currency( $number )
    {
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

            $currency_symbol    = get_woocommerce_currency_symbol();
            $currency_position  = get_option( 'woocommerce_currency_pos' );
            $decimal_separator  = get_option( 'woocommerce_price_decimal_sep' );
            $thousand_separator = get_option( 'woocommerce_price_thousand_sep' );
            $number_of_decimals = get_option( 'woocommerce_price_num_decimals' );

            $number = round( $number, $number_of_decimals );
            $number = number_format( $number, $number_of_decimals, $decimal_separator, $thousand_separator );

            if ( $currency_position == 'left' ) {
                $number = $currency_symbol . $number;
            } elseif ( $currency_position == 'right' ) {
                $number = $number . $currency_symbol;
            } elseif ( $currency_position == 'left_space' ) {
                $number = $currency_symbol . ' ' . $number;
            } elseif ( $currency_position == 'right_space' ) {
                $number = $number . ' ' . $currency_symbol;
            } else {
                $number = $currency_symbol . $number;
            }

        } else {
            $number = round( $number, 2 );
            $number = number_format( $number, 2, '.', '' );
        }

        return $number;
    }

    public function nl_to_br( $string )
    {
        $string = str_replace( array( "\r\n", "\r", "\n", "

				" ), "<br>", $string );

        return $string;
    }

}