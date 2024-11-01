<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoeBtnOrderList
{

    public function btn_send_email_in_order_list()
    {
        //  START Premium functionality /////////////////////////////////////////////////////////
        $mcisoe_auth_premium = get_option( 'mcisoe_auth_premium' );

        if ( $mcisoe_auth_premium ) { // Check password premium

            //Button to manually send an the order to the supplier from the order list.
            add_filter( 'woocommerce_admin_order_actions', function ( $actions, $order ) {

                $order_id = $order->get_id();
                $nonce    = wp_create_nonce( 'soe_nonce' );

                //Check if post_type is shop_order from url
                if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'shop_order' ) {

                    $actions['soe'] = array(
                        'url'    => admin_url( 'edit.php?post_type=shop_order&send_soe=' . $order_id ) . '&soe_nonce=' . $nonce,
                        'name'   => __( 'Send order email to supplier manually', 'supplier-order-email' ),
                        'action' => 'soe',
                    );
                } elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-orders' ) {

                    $actions['soe'] = array(
                        'url'    => admin_url( 'admin.php?page=wc-orders&send_soe=' . $order_id ) . '&soe_nonce=' . $nonce,
                        'name'   => __( 'Send order email to supplier manually', 'supplier-order-email' ),
                        'action' => 'soe',
                    );
                }

                if ( isset( $_GET['send_soe'] ) && $order_id == $_GET['send_soe'] ) {

                    $verify_nonce = wp_verify_nonce( $_GET['soe_nonce'], 'soe_nonce' );

                    if ( $verify_nonce != false ) {
                        $sendemail  = new McisoeMasterEmail;
                        $wp_mail_ok = $sendemail->send_emails( $_GET['send_soe'] );

                        // Display message if the email was sent or not.
                        if ( $wp_mail_ok ) {
                            printf( '<div class="notice notice-success"><p>%s</p></div>', __( 'The order email has been sent to the suppliers.', 'supplier-order-email' ) );
                            // Redirect to the order list after 3 seconds.
                            echo '<script type="text/javascript">setTimeout(function(){window.location.href = "' . admin_url( 'edit.php?post_type=shop_order' ) . '";}, 3000);</script>';
                        } else {
                            printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', __( 'There was an error sending the order to the suppliers.', 'supplier-order-email' ) );
                        }
                    }
                }

                return $actions;
            }, 10, 2 );

            add_action( 'admin_head', function () {
                echo '<style>.wc-action-button-soe::after { font-family: woocommerce !important; content: "\2709" !important; font-size:23px; line-height: 21px!important;}</style>';
            } );
        }
    }

    public function init()
    {
        $this->btn_send_email_in_order_list();
    }

} //END class
