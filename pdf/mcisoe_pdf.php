<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use Dompdf\Dompdf;

class McisoePdf
{
    private $helpers;
    private $order_id;
    private $pdf_message;
    private $supplier_name;
    private $file_path;
    private $options;

    public function __construct( $order_id, $pdf_message, $supplier_name, $options )
    {
        require_once MCISOE_PLUGIN_DIR . 'helpers/mcisoe_helpers.php';
        $this->helpers       = new McisoeHelpers;
        $this->order_id      = $order_id;
        $this->pdf_message   = $pdf_message;
        $this->supplier_name = $supplier_name;
        $this->options       = $options;
    }

    public function create()
    {
        if ( $this->options->attach_pdf == '1' ) {

            //Select CSS file in child theme and link rel to hmtl code
            $css_for_pdf = $this->helpers->search_in_child_theme( 'pdf_styles.css', 'css', $this->options->auth_premium );
            $lang        = get_bloginfo( 'language' );

            $html = "<!DOCTYPE html>
                              <html lang='{$lang}'>
                                <head>";
            $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
            $html .= '<meta name="viewport" content="width=device-width,initial-scale=1">';
            $html .= '<link rel="stylesheet" type="text/css" media="dompdf" href="' . esc_url( $css_for_pdf ) . '"/>';
            $html .= $this->pdf_message;

            $dompdf = new Dompdf();
            $dompdf->load_html( $html );
            $dompdf->setPaper( 'A4', 'portrait' );
            $dompdf->set_option( 'isRemoteEnabled', true );
            if ( MCISOE_REAL_PDF == false ) {
                ob_clean(); //Test
            }
            $dompdf->render();

            //Get WordPress upload dir
            $upload_dir = wp_upload_dir();
            $upload_dir = $upload_dir['basedir'];

            //Create dir if not exist
            if ( !file_exists( $upload_dir . '/supplier-order-email/pdf-files/' ) ) {
                mkdir( $upload_dir . '/supplier-order-email/pdf-files/', 0700, true );
            }

            $file_path       = $upload_dir . '/supplier-order-email/pdf-files/' . $this->order_id . '_' . $this->supplier_name . '.pdf';
            $this->file_path = $file_path;

            if ( MCISOE_REAL_PDF == false ) {
                $dompdf->stream( $this->file_path, array( "Attachment" => false ) ); //Test
                exit( 0 ); //Test
            } else {
                $output = $dompdf->output();
                file_put_contents( $file_path, $output );
                $attachments = array( $file_path );
            }

        } else {
            $attachments = array();
        }
        return $attachments;
    }

    public function delete_file()
    {
        //Delete temp file
        unlink( $this->file_path );
    }

    public function get_path()
    {
        return $this->file_path;
    }

    public function init()
    {
        $this->create_pdf();
    }
}
