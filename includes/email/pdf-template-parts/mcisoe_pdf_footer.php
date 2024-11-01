<?php
/*
This pdf_footer template can be overridden in a WordPress child theme.
Copy this file (mcisoe_pdf_footer.php) and duplicate it into "child_theme/supplier-order-email/mcisoe_pdf_footer.php" to modify the footer layout.
 */
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoePdfFooter
{
    public $pdf_footer;

    public function __construct( $site_name, $auth_premium )
    {
        $pdf_footer = "<footer class='mcisoe_pdf_footer'>" . __( 'Thanks', 'supplier-order-email' ) . ', ' . $site_name . "</footer>";
        $pdf_footer .= "</body>";
        $pdf_footer .= "</html>";

        $this->pdf_footer = $pdf_footer;
    }

    public function get()
    {
        return $this->pdf_footer;
    }

}