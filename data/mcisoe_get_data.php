<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoeGetData
{
    //Suppliers
    public $suppliers;

    //Basic options
    public $email_intro;
    public $email_subject;
    public $select_email_admin;
    public $replace_address;

    public $auth_premium;
    public $auth_lemon;
    public $auth_mciapi;
    public $new_structure_2_0;
    public $delete_all_data;

    //Premium options
    public $pay_email;
    public $header_color;
    public $store_logo;
    public $hide_customer;
    public $show_customer_email;
    public $show_customer_phone;
    public $show_ean;
    public $show_notes;
    public $show_order_total;
    public $show_order_number;
    public $show_payment_method;
    public $show_shipping_method;

    public $show_price_items;
    public $show_shortdesc;
    public $show_product_attributes;
    public $show_product_variations;
    public $show_product_meta;

    public $show_cost_prices;
    public $show_cost_total;

    public $attach_pdf;
    public $store_pdf;
    public $company_info;
    public $special_meta;
    public $email_copy;
    public $from_email;
    public $show_product_img;
    public $show_weight;
    public $product_img_width;
    public $email_trigger;

    public $cancel_all_emails;

    public function __construct()
    {
        $this->suppliers = $this->get_suppliers();

        //Load Basic options
        $this->email_intro        = sanitize_textarea_field( get_option( 'mcisoe_email_intro' ) );
        $this->email_subject      = sanitize_text_field( get_option( 'mcisoe_email_subject' ) );
        $this->select_email_admin = sanitize_text_field( get_option( 'mcisoe_select_email_admin' ) );
        $this->replace_address    = sanitize_text_field( get_option( 'mcisoe_replace_address' ) );

        $this->auth_premium      = sanitize_text_field( get_option( 'mcisoe_auth_premium' ) );
        $this->auth_lemon        = sanitize_text_field( get_option( 'mcisoe_auth_lemon' ) );
        $this->auth_mciapi       = sanitize_text_field( get_option( 'mcisoe_auth_mciapi' ) );
        $this->new_structure_2_0 = sanitize_text_field( get_option( 'mcisoe_new_structure_2_0' ) );
        $this->delete_all_data   = sanitize_text_field( get_option( 'mcisoe_delete_all_data' ) );
        $this->pay_email         = sanitize_text_field( get_option( 'mci_pay_email' ) );

        //Load Premium options
        if ( $this->auth_premium ) {
            $this->header_color            = sanitize_hex_color( get_option( 'mcisoe_header_color' ) );
            $this->store_logo              = sanitize_text_field( get_option( 'mcisoe_store_logo' ) );
            $this->hide_customer           = sanitize_text_field( get_option( 'mcisoe_hide_customer' ) );
            $this->show_customer_email     = sanitize_text_field( get_option( 'mcisoe_show_customer_email' ) );
            $this->show_customer_phone     = sanitize_text_field( get_option( 'mcisoe_show_customer_phone' ) );
            $this->show_ean                = sanitize_text_field( get_option( 'mcisoe_show_ean' ) );
            $this->show_notes              = sanitize_text_field( get_option( 'mcisoe_show_notes' ) );
            $this->show_order_total        = sanitize_text_field( get_option( 'mcisoe_show_order_total' ) );
            $this->show_order_number       = sanitize_text_field( get_option( 'mcisoe_show_order_number' ) );
            $this->show_price_items        = sanitize_text_field( get_option( 'mcisoe_show_price_items' ) );
            $this->show_product_attributes = sanitize_text_field( get_option( 'mcisoe_show_product_attributes' ) );
            $this->show_product_meta       = sanitize_text_field( get_option( 'mcisoe_show_product_meta' ) );
            $this->show_shortdesc          = sanitize_text_field( get_option( 'mcisoe_show_shortdesc' ) );
            $this->show_cost_prices        = sanitize_text_field( get_option( 'mcisoe_show_cost_prices' ) );
            $this->show_cost_total         = sanitize_text_field( get_option( 'mcisoe_show_cost_total' ) );
            $this->show_payment_method     = sanitize_text_field( get_option( 'mcisoe_show_payment_method' ) );
            $this->show_shipping_method    = sanitize_text_field( get_option( 'mcisoe_show_shipping_method' ) );
            $this->attach_pdf              = sanitize_text_field( get_option( 'mcisoe_attach_pdf' ) );
            $this->store_pdf               = sanitize_text_field( get_option( 'mcisoe_store_pdf' ) );
            $this->company_info            = sanitize_textarea_field( get_option( 'mcisoe_company_info' ) );
            $this->special_meta            = sanitize_text_field( get_option( 'mcisoe_special_meta' ) );
            $this->email_copy              = !empty( get_option( 'mcisoe_email_copy' ) ) ? sanitize_email( get_option( 'mcisoe_email_copy' ) ) : get_option( 'admin_email' );
            $this->from_email              = !empty( get_option( 'mcisoe_from_email' ) ) ? sanitize_email( get_option( 'mcisoe_from_email' ) ) : get_option( 'admin_email' );
            $this->cancel_all_emails       = sanitize_text_field( get_option( 'mcisoe_cancel_all_emails' ) );
            $this->show_product_img        = sanitize_text_field( get_option( 'mcisoe_show_product_img' ) );
            $this->product_img_width       = sanitize_text_field( get_option( 'mcisoe_product_img_width' ) );
            $this->show_weight             = sanitize_text_field( get_option( 'mcisoe_show_weight' ) );
            $this->email_trigger           = sanitize_text_field( get_option( 'mcisoe_email_trigger' ) );
        } else {

            $this->header_color            = sanitize_hex_color( MCISOE_HEADER_COLOR );
            $this->store_logo              = '0';
            $this->hide_customer           = '0';
            $this->show_customer_email     = '0';
            $this->show_customer_phone     = '0';
            $this->show_ean                = '0';
            $this->show_notes              = '0';
            $this->show_order_total        = '0';
            $this->show_order_number       = '0';
            $this->show_price_items        = '0';
            $this->show_product_attributes = '0';
            $this->show_product_meta       = '0';
            $this->show_shortdesc          = '0';
            $this->show_cost_prices        = '0';
            $this->show_cost_total         = '0';
            $this->show_payment_method     = '0';
            $this->show_shipping_method    = '0';
            $this->attach_pdf              = '0';
            $this->store_pdf               = '0';
            $this->company_info            = '';
            $this->special_meta            = '';
            $this->email_copy              = sanitize_email( get_option( 'admin_email' ) );
            $this->from_email              = sanitize_email( get_option( 'admin_email' ) );
            $this->cancel_all_emails       = '0';
            $this->show_product_img        = '0';
            $this->product_img_width       = '100';
            $this->show_weight             = '0';
            $this->auth_premium            = false;
            $this->email_trigger           = 'processing';

        }

        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

            if ( function_exists( 'is_plugin_active' ) ) {
                if ( !is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) ) {
                    $this->show_cost_prices = '0';
                    $this->show_cost_total  = '0';
                }
            }
        }
    }

    public function get_suppliers()
    {
        //Get terms of Suppliers taxonomy
        $supplier_terms = get_terms( [
            'taxonomy'   => 'supplier',
            'hide_empty' => false]
        );

        if ( !empty( $supplier_terms ) ) {

            $suppliers = [];

            foreach ( $supplier_terms as $supplier_term ) {

                if ( is_object( $supplier_term ) ) {

                    //Get termmeta of each supplier
                    $supplier_email = sanitize_email( get_term_meta( $supplier_term->term_id, 'mcisoe_supplier_email', true ) );
                    //Get supplier custom text
                    $supplier_custom_text = sanitize_textarea_field( get_term_meta( $supplier_term->term_id, 'mcisoe_supplier_custom_text', true ) );
                    //Get supplier data text
                    $supplier_data_text = sanitize_textarea_field( get_term_meta( $supplier_term->term_id, 'mcisoe_supplier_data_text', true ) );

                    //Add supplier to array
                    array_push( $suppliers, [
                        'term_id'              => sanitize_text_field( $supplier_term->term_id ),
                        'name'                 => sanitize_text_field( $supplier_term->name ),
                        'email'                => $supplier_email,
                        'supplier_custom_text' => $supplier_custom_text,
                        'supplier_data_text'   => $supplier_data_text,
                    ] );
                }
            }

            //If there are suppliers
            return $suppliers;
        } else {
            //If no terms are found
            return false;
        }
    }
}