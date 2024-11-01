<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class McisoeMasterEmail
{

    public function triggers()
    {
        require_once MCISOE_PLUGIN_DIR . 'data/mcisoe_get_data.php';
        $options = new McisoeGetData;

        if ( !$options->cancel_all_emails ) {

            if ( $options->email_trigger == 'on-hold' || $options->email_trigger == 'processing' ) {

                add_action( 'woocommerce_order_status_on-hold_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_processing_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_pending_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_failed_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_completed_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_refunded_to_' . $options->email_trigger, [$this, 'send_emails'] );
                add_action( 'woocommerce_order_status_cancelled_to_' . $options->email_trigger, [$this, 'send_emails'] );
            }
        }
    }

    public function trigger_process_shop_order_hook( $post_id )
    {
        $user_id = get_current_user_id();

        if ( current_user_can( 'manage_options' ) && isset( $post_id ) ) {
            $order_id = $post_id;

            $send_manually = false;
            $send_manually = apply_filters( 'mcisoe_send_email_manually', $order_id );

            if ( $send_manually == true && is_bool( $send_manually ) ) {
                $this->send_emails( $order_id );
            }
        }
    }

    public function send_emails( $order_id )
    {
        //Get wp_suppliers list
        require_once MCISOE_PLUGIN_DIR . 'data/mcisoe_get_data.php';
        $options      = new McisoeGetData;
        $wp_suppliers = $options->suppliers;

        // Initzialize the response wp_mail_ok
        $wp_mail_ok = true;

        ////////////////////////////////////////////////////////////////////////////////////////////
        // Send email for each supplier ////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////
        foreach ( $wp_suppliers as $wp_supplier ) {

            //Get order data
            $order = wc_get_order( $order_id );

            // Get wp_supplier data
            $wp_supplier_email = sanitize_email( $wp_supplier['email'] );
            $wp_supplier_name  = sanitize_text_field( $wp_supplier['name'] );

            //Get order items
            $items = $order->get_items();

            ///////////////////////////////////////
            //Create header
            require_once MCISOE_PLUGIN_DIR . 'includes/email/mcisoe_header.php';
            $header       = new McisoeHeader( $options, $order, $wp_supplier );
            $email_header = $header->email_header();
            $pdf_header   = $header->pdf_header();

            // Create Customer data
            require_once MCISOE_PLUGIN_DIR . 'includes/email/mcisoe_customer_data.php';
            $customer_data = new McisoeCustomerData( $options, $order, $wp_supplier['term_id'] );
            $email_content = $customer_data->email_customer_data();
            $pdf_content   = $customer_data->pdf_customer_data();

            // Create product items list
            require_once MCISOE_PLUGIN_DIR . 'includes/email/mcisoe_items_list.php';
            $items_list     = new McisoeItemsList( $items, $wp_supplier, $options );
            $email_table    = $items_list->email_items_list;
            $pdf_table      = $items_list->pdf_items_list;
            $supplier_total = $items_list->order_total;
            $cost_total     = $items_list->cost_total;

            // Create totals
            require_once MCISOE_PLUGIN_DIR . 'includes/email/mcisoe_totals.php';
            $totals_email = new McisoeTotals( $supplier_total, $options, $cost_total, $order, $wp_supplier );
            $email_totals = $totals_email->get_totals();
            $totals_pdf   = new McisoeTotals( $supplier_total, $options, $cost_total, $order, $wp_supplier );
            $pdf_totals   = $totals_pdf->get_pdf_totals();

            // Create footer
            require_once MCISOE_PLUGIN_DIR . 'includes/email/mcisoe_footer.php';
            $footer       = new McisoeFooter( $options, $order );
            $email_footer = $footer->email_footer();
            $pdf_footer   = $footer->pdf_footer();

            // Data for email
            $to        = $wp_supplier_email;
            $subject   = $header->email_subject; //Get filtered subject
            $headers[] = 'Content-Type: text/html';
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'From: ' . sanitize_text_field( get_bloginfo() ) . ' <' . sanitize_email( $options->from_email ) . '>';
            $headers[] = 'Reply-To: ' . sanitize_email( $options->from_email );
            $message   = $email_header . $email_content . $email_table . $email_totals . $email_footer;

            $pdf_message = $pdf_header . $pdf_content . $pdf_table . $pdf_totals . $pdf_footer;

            if ( $items_list->match_supplier ) {

                // Create PDF with DOMPDF
                require_once MCISOE_PLUGIN_DIR . 'pdf/mcisoe_pdf.php';
                $pdf         = new McisoePdf( $order_id, $pdf_message, $wp_supplier_name, $options );
                $pdf_message = $pdf->create();
                $pdf_path    = $pdf->get_path();

                $attachments = [$pdf_path];
                $attachments = apply_filters( 'mcisoe_email_attachments', $attachments, $order_id, $wp_supplier_name );

                // Send email to supplier
                if ( $options->attach_pdf == '1' ) {
                    $response = wp_mail( $to, $subject, $message, $headers, $attachments );
                } else {
                    $response = wp_mail( $to, $subject, $message, $headers );
                }

                if ( !$response ) {
                    $wp_mail_ok = false;
                }

                // Send email to admin
                if ( $options->select_email_admin == '1' ) {

                    $wp_admin_email = sanitize_email( $options->email_copy );
                    $subject_admin  = __( 'Email sent to the supplier', 'supplier-order-email' ) . ': ' . $wp_supplier_name;
                    $intro_admin    = '<p>' . __( 'An order email has been sent to the supplier.', 'supplier-order-email' ) . '</p>';
                    $intro_admin .= '<b style="display:block;margin-bottom:20px;">' . __( 'This is a copy of the email sent to', 'supplier-order-email' ) . ': ' . $wp_supplier_name . ' (' . $wp_supplier_email . ')</b>';
                    $intro_admin .= '<p>----</p>';
                    $message = $intro_admin . $message;

                    if ( $options->attach_pdf == '1' ) {
                        $response = wp_mail( $wp_admin_email, $subject_admin, $message, $headers, $attachments );
                    } else {
                        $response = wp_mail( $wp_admin_email, $subject_admin, $message, $headers );
                    }

                    if ( !$response ) {
                        $wp_mail_ok = false;
                    }
                }
                if ( $options->attach_pdf == '1' && $options->store_pdf != '1' ) {
                    //Delete temp PDF file
                    $pdf->delete_file();
                }
            }

        }
        ////////////////////////////////////////////////////////////////////////////////////////////
        // End send email for each supplier //////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////

        return $wp_mail_ok;
    }

    public function init()
    {

        $this->triggers();

        // Trigger email when order update button is clicked
        add_action( 'woocommerce_process_shop_order_meta', [$this, 'trigger_process_shop_order_hook'] );
    }

} // End class McisoeSendEmails
