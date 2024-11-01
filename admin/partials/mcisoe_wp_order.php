<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class McisoeWpOrder
{
    public function __construct()
    {
        //Add supplier column to order items table in order edit page admin
        add_action( 'woocommerce_admin_order_item_headers', [$this, 'add_order_item_header'] );
        add_action( 'woocommerce_admin_order_item_values', [$this, 'add_order_item_values'], 10, 3 );

    }

    public function add_order_item_header()
    {
        $column_name = __( 'Supplier', 'supplier-order-email' );
        echo '<th class="mcisoe_header_supplier">' . esc_html( $column_name ) . '</th>';
    }

    public function add_order_item_values( $_product, $item, $item_id = null )
    {
        $product_id = wc_get_order_item_meta( $item_id, '_product_id', true );

        $supplier_id = $this->select_yoast_parent_supplier( $product_id );

        $supplier_name = get_term( $supplier_id, 'supplier' );
        $name          = !empty( $supplier_name->name ) ? $supplier_name->name : '-';

        echo '<td class="mcisoe_fill_supplier">' . esc_html( $name ) . '</td>';
    }

    public function select_yoast_parent_supplier( $product_id )
    {
        $taxonomy = 'supplier';

        // Get supplier primary taxonomy if exists (Yoast SEO)
        if ( !function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
            $primary_cat_id = yoast_get_primary_term_id( $taxonomy, $product_id );
        } else {
            $primary_cat_id = false;
        }

        if ( isset( $primary_cat_id ) && is_plugin_active( 'wordpress-seo/wp-seo.php' ) && $primary_cat_id !== false && $primary_cat_id !== "" ) {

            $primary_cat      = get_term( $primary_cat_id, $taxonomy );
            $primary_cat_name = $primary_cat->term_id;
            $supplier         = $primary_cat_name;

        } else {
            //If Yoast is inactive or not has primary category
            $item_taxonomy_terms = get_the_terms( $product_id, 'supplier' );
            $first_cat_id        = $item_taxonomy_terms != false ? $item_taxonomy_terms[0]->term_id : '';
            $supplier            = $first_cat_id;
        }

        return $supplier;
    }

}
