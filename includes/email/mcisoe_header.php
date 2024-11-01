<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class McisoeHeader
{
    public $helpers;
    public $email_header;
    public $pdf_header;

    public $header;
    private $options;
    private $order;
    public $email_subject;
    public $email_intro;
    private $logo_original_width;
    private $store_logo;
    private $img_store_logo;
    private $wp_supplier;

    private $order_id;
    private $header_color;
    private $site_name;

    public function __construct( $options, $order, $wp_supplier )
    {
        require_once MCISOE_PLUGIN_DIR . 'helpers/mcisoe_helpers.php';
        $this->helpers = new McisoeHelpers;

        $this->header        = '';
        $this->options       = $options;
        $this->order         = $order;
        $this->wp_supplier   = $wp_supplier;
        $this->email_subject = $this->options->email_subject;
        $this->email_intro   = $this->options->email_intro;
        $this->filter_email_labels( $this->wp_supplier ); // Filter email for subject and intro
        $this->store_logo = $this->options->store_logo;
        $this->logo_original_width;
        $this->img_store_logo = $this->get_img_store_logo();

        //Get data for header
        $this->order_id = $this->order->get_id();
        if ( !$this->order_id ) {return;}
        $this->header_color  = sanitize_hex_color( $this->options->header_color );
        $this->site_name     = sanitize_text_field( get_bloginfo( 'name' ) );
        $this->email_subject = sanitize_text_field( $this->email_subject );
        $this->email_intro   = sanitize_textarea_field( $this->email_intro );
        $this->email_intro   = $this->helpers->nl_to_br( $this->email_intro );

    }

    public function email_header()
    {
        if ( $this->options->store_logo != '1' ) {
            $img_store_logo = '';
        } else {
            $img_store_logo = $this->img_store_logo;
        }
        //Print email header from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_email_header.php', 'email', $this->options->auth_premium );
        $email_header       = new MciSoeEmailHeader( $this->header_color, $this->site_name, $this->email_intro, $this->email_subject, $this->options->auth_premium, $img_store_logo, $this->logo_original_width, $this->order );
        $this->email_header = $email_header->get();

        return $this->email_header;
    }

    public function pdf_header()
    {
        //Print pdf header from template. Select file in child theme
        require_once $this->helpers->search_in_child_theme( 'mcisoe_pdf_header.php', 'pdf', $this->options->auth_premium );
        $pdf_header       = new MciSoePdfHeader( $this->header_color, $this->site_name, $this->email_intro, $this->email_subject, $this->options->auth_premium, $this->img_store_logo, $this->logo_original_width, $this->order );
        $this->pdf_header = $pdf_header->get();

        return $this->pdf_header;
    }

    public function get_img_store_logo()
    {
        $custom_logo = get_custom_logo();

        if ( is_child_theme() ) {
            $parent_theme = wp_get_theme()->parent();
            $theme_name   = $parent_theme->get( 'Name' );
        } else {
            $theme_name = wp_get_theme()->get( 'Name' );
        }

        //Check if is child theme from Flatsome theme.
        if ( $theme_name == 'Flatsome' ) {

            $site_logo_id  = flatsome_option( 'site_logo' );
            $custom_logo_2 = wp_get_attachment_image_src( $site_logo_id, 'large' );
            if ( $custom_logo_2 ) {
                $custom_logo = "<img src='{$custom_logo_2[0]}' alt='{$this->site_name}'>";
            }

            $this->img_store_logo = $custom_logo;

        } elseif ( has_custom_logo() && $this->options->auth_premium == '1' ) {
            $logo                      = get_theme_mod( 'custom_logo' );
            $image                     = wp_get_attachment_image_src( $logo, 'full' );
            $image_url                 = $image[0];
            $this->logo_original_width = (int) $image[1];
            $this->img_store_logo      = "<img src='{$image_url}' alt='{$this->site_name}'>";

        } elseif ( $custom_logo && $this->options->auth_premium == '1' ) {

            $this->img_store_logo = $custom_logo;

        } else {

            $this->logo_original_width = 0;
            $this->img_store_logo      = '';
        }

        return $this->img_store_logo;
    }

    public function filter_email_labels( $wp_supplier )
    {
        //Get order_date WordPress format
        $wp_date_format = get_option( 'date_format' );
        $order_date     = sanitize_text_field( $this->order->get_date_created()->date( $wp_date_format ) );
        //Get order number
        $order_id = sanitize_text_field( $this->order->get_id() );

        //Get supplier_custom_text
        $term_id              = $this->wp_supplier['term_id'];
        $supplier_custom_text = sanitize_textarea_field( get_term_meta( $term_id, 'mcisoe_supplier_custom_text', true ) );

        if ( $this->options->auth_premium == '1' ) {
            $this->email_subject = str_replace( '{order_number}', esc_html( $order_id ), $this->email_subject );
            $this->email_subject = str_replace( '{order_date}', esc_html( $order_date ), $this->email_subject );
            $this->email_subject = str_replace( '{supplier_name}', esc_html( $wp_supplier['name'] ), $this->email_subject );
            $this->email_subject = str_replace( '{supplier_custom_text}', esc_textarea( $supplier_custom_text ), $this->email_subject );

            //Hook for filter email subject
            $this->email_subject = apply_filters( 'mcisoe_email_subject', $this->email_subject, $order_id, $wp_supplier );

            $this->email_intro = str_replace( '{order_number}', esc_html( $order_id ), $this->email_intro );
            $this->email_intro = str_replace( '{order_date}', esc_html( $order_date ), $this->email_intro );
            $this->email_intro = str_replace( '{supplier_name}', esc_html( $wp_supplier['name'] ), $this->email_intro );
            $this->email_intro = str_replace( '{supplier_custom_text}', esc_textarea( $supplier_custom_text ), $this->email_intro );
        }
    }
}
