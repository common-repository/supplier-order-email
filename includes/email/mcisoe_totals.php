<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoeTotals
{
    private $supplier_total;
    private $email_supplier_total;
    private $pdf_supplier_total;
    private $options;
    private $cost_total;
    private $order;
    private $payment_method;
    private $shipping_method;
    private $helpers;
    private $wp_supplier;

    public function __construct( $supplier_total, $options, $cost_total, $order = null, $wp_supplier = null )
    {
        $this->supplier_total = sanitize_text_field( $supplier_total );
        $this->cost_total     = sanitize_text_field( $cost_total );
        $this->options        = $options;
        $this->order          = $order;
        $this->wp_supplier    = $wp_supplier;

        require_once MCISOE_PLUGIN_DIR . 'helpers/mcisoe_helpers.php';
        $this->helpers = new McisoeHelpers;
    }

    public function supplier_total()
    {
        if ( $this->options->show_order_total == '1' && $this->supplier_total > 0 ) {
            $label_order_total = __( 'Total', 'supplier-order-email' );
            $label_order_total .= "\n";
            $order_total          = $this->helpers->build_price_currency( $this->supplier_total );
            $this->supplier_total = $label_order_total . $order_total;
        } else {
            $this->supplier_total = '';
        }
    }

    public function cost_total()
    {
        if ( is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) && $this->cost_total > 0 && $this->options->show_cost_total == '1' ) {
            $label_cost_total = __( 'Cost', 'supplier-order-email' );
            $label_cost_total .= "<br>";
            $cost_total       = $this->helpers->build_price_currency( $this->cost_total );
            $this->cost_total = $label_cost_total . $cost_total;
        } else {
            $this->cost_total = '';
        }
    }

    private function payment_method()
    {
        $payment_method = sanitize_text_field( $this->order->get_payment_method_title() );

        if ( $this->options->show_payment_method == '1' && $payment_method != '' ) {

            //Hook for filter payment method
            if ( $this->options->auth_premium ) {
                $payment_method = apply_filters( 'mcisoe_payment_method', $payment_method, $this->order );
            }

            $label_payment_method = __( 'Payment method', 'supplier-order-email' );
            $this->payment_method = $label_payment_method . ': ' . esc_html( $payment_method );
        } else {
            $this->payment_method = '';
        }
    }

    private function shipping_method()
    {
        $shipping_method = sanitize_text_field( $this->order->get_shipping_method() );

        if ( $this->options->show_shipping_method == '1' && $shipping_method != '' ) {

            //Hook for filter shipping method
            if ( $this->options->auth_premium ) {
                $shipping_method = apply_filters( 'mcisoe_shipping_method', $shipping_method, $this->order );
            }

            $label_shipping_method = __( 'Shipping method', 'supplier-order-email' );
            $this->shipping_method = $label_shipping_method . ': ' . esc_html( $shipping_method );

        } else {
            $this->shipping_method = '';
        }

    }

    public function get_totals()
    {
        $this->supplier_total();
        $this->cost_total();
        $this->payment_method();
        $this->shipping_method();

        //Print totals from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_email_totals.php', 'email', $this->options->auth_premium );
        $supplier_totals = new MciSoeEmailTotals( $this->supplier_total, $this->options, $this->cost_total, $this->payment_method, $this->shipping_method, $this->order, $this->wp_supplier );

        $this->email_supplier_total = $supplier_totals->get() . "</tbody></table>";

        return $this->email_supplier_total;
    }

    public function get_pdf_totals()
    {
        $this->supplier_total();
        $this->cost_total();
        $this->payment_method();
        $this->shipping_method();

        //Print totals from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_pdf_totals.php', 'pdf', $this->options->auth_premium );
        $supplier_totals = new MciSoePdfTotals( $this->supplier_total, $this->options, $this->cost_total, $this->payment_method, $this->shipping_method, $this->order, $this->wp_supplier );

        $this->pdf_supplier_total = $supplier_totals->get() . "</tbody></table>";

        return $this->pdf_supplier_total;
    }
}
