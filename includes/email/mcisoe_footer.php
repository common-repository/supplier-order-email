<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoeFooter
{
    public $helpers;
    public $email_footer;
    public $pdf_footer;
    private $options;
    private $order;
    private $footer;

    public function __construct( $options, $order )
    {
        require_once MCISOE_PLUGIN_DIR . 'helpers/mcisoe_helpers.php';
        $this->helpers      = new McisoeHelpers;
        $this->email_footer = '';
        $this->pdf_footer   = '';
        $this->options      = $options;
        $this->order        = $order;
    }

    public function email_footer()
    {
        $order_id = $this->order->get_id();
        if ( !$order_id ) {
            return;
        }

        $site_name = sanitize_text_field( get_bloginfo( 'name' ) );

        //Print table content from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_email_footer.php', 'email', $this->options->auth_premium );
        $email_footer       = new MciSoeEmailFooter( $site_name, $this->options->auth_premium );
        $this->email_footer = $email_footer->get();

        return $this->email_footer;
    }

    public function pdf_footer()
    {
        $order_id = $this->order->get_id();
        if ( !$order_id ) {
            return;
        }

        $site_name = sanitize_text_field( get_bloginfo( 'name' ) );

        //Print table content from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_pdf_footer.php', 'pdf', $this->options->auth_premium );
        $pdf_footer   = new MciSoePdfFooter( $site_name, $this->options->auth_premium );
        $this->footer = $pdf_footer->get();

        return $this->footer;
    }
}
