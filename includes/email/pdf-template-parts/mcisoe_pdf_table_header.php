<?php
/*
This pdf_table_header template can be overridden in a WordPress child theme.
Copy this file (mcisoe_pdf_table_header.php) and duplicate it into "child_theme/supplier-order-email/mcisoe_pdf_table_header.php" to modify the pdf_table_header layout.
 */
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoePdfTableHeader
{
    private $pdf_table_header;

    public function __construct( $options )
    {

        // Table header
        $items_template = '<table class="mcisoe_pdf_table_3">';
        $items_template .= '<thead><tr>';
        $items_template .= '<th style="border:1px solid #ccc;text-align:center;background:' . esc_attr( $options->header_color ) . '">' . __( 'Quantity', 'supplier-order-email' ) . '</th>'; //Quantity
        $items_template .= '<th style="border:1px solid #ccc;text-align:left;background:' . esc_attr( $options->header_color ) . '">' . __( 'Reference', 'supplier-order-email' ) . '</th>'; //SKU
        if ( $options->show_product_img == '1' ) {
            $items_template .= '<th style="border:1px solid #ccc;text-align:center;background:' . esc_attr( $options->header_color ) . '">' . __( 'Image', 'supplier-order-email' ) . '</th>'; //Image
        }
        $items_template .= '<th style="border:1px solid #ccc;text-align:left;background:' . esc_attr( $options->header_color ) . '">' . __( 'Product', 'supplier-order-email' ) . '</th>'; //Name
        ///// START PREMIUM ///////////////////////////
        if ( $options->show_ean == '1' ) {
            $items_template .= '<th style="border:1px solid #ccc;text-align:left;background:' . esc_attr( $options->header_color ) . '">' . __( 'EAN', 'supplier-order-email' ) . '</th>'; //EAN
        }
        if ( $options->show_weight == '1' ) {
            $items_template .= '<th style="border:1px solid #ccc;text-align:left;background:' . esc_attr( $options->header_color ) . '">' . __( 'Weight', 'supplier-order-email' ) . '</th>'; //Weight
        }
        if ( is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) && $options->show_cost_prices == '1' ) {
            $items_template .= '<th style="border:1px solid #ccc;text-align:center;background:' . esc_attr( $options->header_color ) . '">' . __( 'Cost', 'supplier-order-email' ) . '</th>'; //Cost price
        }
        if ( $options->show_price_items == '1' ) {
            $items_template .= '<th style="border:1px solid #ccc;text-align:center;background:' . esc_attr( $options->header_color ) . '">' . __( 'Price', 'supplier-order-email' ) . '</th>'; //Price
        }
        ///// END PREMIUM /////////////////////////////

        $items_template .= '</tr></thead>';

        $this->pdf_table_header = $items_template;
    }

    public function get()
    {
        return $this->pdf_table_header;
    }

}
