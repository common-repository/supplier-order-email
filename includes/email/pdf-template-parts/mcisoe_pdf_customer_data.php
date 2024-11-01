<?php
/*
This pdf customer data template can be overridden in a WordPress child theme.
Copy this file (mcisoe_pdf_customer_data.php) and duplicate it into "child_theme/supplier-order-email/mcisoe_pdf_customer_data.php" to modify the customer_data layout.
 */
if ( !defined( 'ABSPATH' ) ) {exit;}

class MciSoePdfCustomerData
{
    public $pdf_customer_data;
    public $supplier_data_label;
    public $blog_name;
    public $store_address;
    public $store_postcode;
    public $store_city;
    public $store_country;

    public function __construct( $order_number, $address_error, $address, $phone, $customer_email, $delivery_date, $customer_notes, $auth_premium, $order = null, $delivery_date_odd_plugin = "", $supplier_data = null, $options = null, $company_info = null )
    {
        $this->supplier_data_label = !empty( $supplier_data['data_text'] ) ? esc_html( __( 'Supplier:', 'supplier-order-email' ) ) : '';
        $this->blog_name           = !empty( get_option( 'blogname' ) ) ? get_option( 'blogname' ) : '';
        $this->store_address       = !empty( get_option( 'woocommerce_store_address' ) ) ? get_option( 'woocommerce_store_address' ) : '';
        $this->store_postcode      = !empty( get_option( 'woocommerce_store_postcode' ) ) ? get_option( 'woocommerce_store_postcode' ) : '';
        $this->store_city          = !empty( get_option( 'woocommerce_store_city' ) ) ? get_option( 'woocommerce_store_city' ) : '';
        $this->store_country       = !empty( get_option( 'woocommerce_default_country' ) ) ? get_option( 'woocommerce_default_country' ) : '';

        //Get order date
        $order_date_label = esc_html( __( 'Order date', 'supplier-order-email' ) ) . ': ';
        $order_date       = $order->get_date_created();
        $order_date       = date_i18n( get_option( 'date_format' ), strtotime( $order_date ) );

        $table_1 = "<table class='mcisoe_pdf_table_1'>";

        //Company info
        $table_1 .= !empty( $company_info ) ? "<tr><td>{$company_info}</td></tr>" : "";

        //Supplier data
        if ( !empty( $supplier_data['data_text'] ) ) {
            $table_1 .= "<tr><th>{$this->supplier_data_label}</th></tr>";
            $table_1 .= !empty( $supplier_data['name'] ) ? "<tr><td>{$supplier_data['name']}</td></tr>" : "";
            $table_1 .= !empty( $supplier_data['data_text'] ) ? "<tr><td>{$supplier_data['data_text']}</td></tr>" : "";
            $table_1 .= !empty( $supplier_data['email'] ) ? "<tr><td>{$supplier_data['email']}</td></tr>" : "";
        }
        $table_1 .= "</table>";

        $table_2 = "<table class='mcisoe_pdf_table_2'>";
        $table_2 .= !empty( $order_number ) ? "<tr><th class='mcisoe_pdf_order_number'>{$order_number}</th></tr>" : "";
        $table_2 .= !empty( $order_date ) ? "<tr><td class='mcisoe_pdf_order_date'>{$order_date_label}{$order_date}</td></tr>" : "";

        if ( $options->hide_customer !== '1' ) {
            $table_2 .= !empty( $address ) ? "<tr><td>{$address}</td></tr>" : "";
            $table_2 .= !empty( $phone ) ? "<p style='margin:0 0 0 0;text-align:left;'> {$phone} </p>" : "";
            $table_2 .= !empty( $customer_email ) ? "<p style='margin:0;text-align:left;'> {$customer_email} </p>" : "";
        }
        $table_2 .= "</table>";

        $supplier_custom_text = "";
        //Uncomment the line below to display the supplier_custom_text above the product table
        //$supplier_custom_text = !empty( $supplier_data['custom_text'] ) ? "<p class='mcisoe_pdf_supplier_custom_text'>{$supplier_data['custom_text']}</p>" : "";

        $delivery_date     = !empty( $delivery_date ) ? "<p style='margin:10px 0 0;text-align:left;'> {$delivery_date} </p>" : "";
        $delivery_date_odd = !empty( $delivery_date_odd_plugin ) ? "<p style='margin:10px 0 0;text-align:left;'> {$delivery_date_odd_plugin} </p>" : "";

        if ( $options->hide_customer !== '1' ) {
            $customer_notes = !empty( $customer_notes ) ? "<p style='margin:10px 0;text-align:left;'> {$customer_notes} </p>" : "";
        }

        $this->pdf_customer_data = $table_1 . $table_2 . $supplier_custom_text;
    }

    public function get()
    {
        return $this->pdf_customer_data;
    }

}
