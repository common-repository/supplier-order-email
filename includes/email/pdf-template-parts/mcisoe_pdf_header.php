<?php
/*
This pdf_header template can be overridden in a WordPress child theme.
Copy this file (mcisoe_pdf_header.php) and duplicate it into "child_theme/supplier-order-email/mcisoe_pdf_header.php" to modify the header layout in pdf document.
 */
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoePdfHeader
{
    public $pdf_header;

    public function __construct( $header_color, $site_name, $email_intro, $email_subject, $auth_premium, $store_logo, $logo_original_width, $order = null )
    {

        $head = "<title>{$site_name} - {$email_subject}</title>
                </head>
                <body>
                ";
        if ( $store_logo == '' ) {
            $header_bar = "<h1 class='mcisoe_pdf_title' style='background:{$header_color};'>{$site_name}</h1>";
        } else {
            $header_bar = "<div class='mcisoe_pdf_logo'>{$store_logo}</div>";
        }

        $text_document_type = esc_html( __( 'Purchase Order', 'supplier-order-email' ) );
        $document_type      = "<h2 class='mcisoe_pdf_document_type'>{$text_document_type}</h2>";

        // Email intro
        $email_intro = !empty( $email_intro ) ? "<p style='margin:35px 0 30px 0;text-align:left;'>{$email_intro}</p>" : "";
        $email_intro = ""; // If you want to show email intro in below of logo pdf document, remove or comment this line.

        $this->pdf_header = $head . $header_bar . $document_type . $email_intro;

    }

    public function get()
    {
        return $this->pdf_header;
    }

}
