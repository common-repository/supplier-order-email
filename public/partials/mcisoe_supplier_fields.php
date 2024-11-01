<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class McisoeSupplierFields
{
    private $options;
    private $email_label;
    private $email_label_help;
    private $supplier_custom_text_label;
    private $supplier_custom_text_help;
    private $supplier_pdf_text_label;
    private $supplier_pdf_text_help;

    private $supplier_custom_text;
    private $supplier_data_text;
    private $supplier_data_text_label;
    private $supplier_data_text_help;

    private $option_disabled       = '';
    private $class_option_disabled = '';

    public function __construct()
    {
        require_once MCISOE_PLUGIN_DIR . 'data/mcisoe_get_data.php';
        $this->options = new McisoeGetData();

        $this->option_disabled       = $this->options->auth_premium ? '' : 'disabled';
        $this->class_option_disabled = $this->options->auth_premium ? "" : "class='mcisoe_option_disabled'";

        $this->email_label                = esc_html( __( "Supplier Email", "supplier-order-email" ) );
        $this->email_label_help           = esc_html( __( "Email address of the supplier.", "supplier-order-email" ) );
        $this->supplier_custom_text_label = esc_html( __( "Supplier custom text (optional)", "supplier-order-email" ) );
        $this->supplier_custom_text_help  = esc_html( __( "This {supplier_custom_text} can be used in the subject and the introductory text of the email and is unique for each supplier.", "supplier-order-email" ) );
        $this->supplier_custom_text       = '';
        $this->supplier_data_text_label   = esc_html( __( "Supplier data text (optional)", "supplier-order-email" ) );
        $this->supplier_data_text_help    = esc_html( __( "This text is printed below the logo in pdf documents. It is used to write additional supplier data such as address, phone...", "supplier-order-email" ) );
    }

    public function add()
    {
        $output = "
        <div class='form-field term-email-wrap'>
        <label for='mcisoe_supplier_email'>{$this->email_label}</label>
        <input type='text' name='mcisoe_supplier_email' id='mcisoe_supplier_email' required>
        <p class='description mci_field_add_taxonomy'>{$this->email_label_help}</p>
        </div>
        ";

        $output .= "
          <div class='form-field'>
          <label for='mcisoe_supplier_custom_text' {$this->class_option_disabled}>{$this->supplier_custom_text_label}</label>
          <textarea name='mcisoe_supplier_custom_text' id='mcisoe_supplier_custom_text' class='mcisoe_supplier_custom_text' {$this->option_disabled}></textarea>
          <p class='description mci_field_add_taxonomy'>{$this->supplier_custom_text_help}";
        $output .= McisoeHelpers::mcisoe_premium_text_without_echo( $this->options->auth_premium );
        $output .= "</p></div>";

        $output .= "<div class='form-field'>
          <label for='mcisoe_supplier_data_text' {$this->class_option_disabled}>{$this->supplier_data_text_label}</label>
          <textarea name='mcisoe_supplier_data_text' id='mcisoe_supplier_data_text' rows='4' columns='3' maxlength='390' {$this->option_disabled}></textarea>
          <p class='description mci_field_add_taxonomy'>{$this->supplier_data_text_help}";
        $output .= McisoeHelpers::mcisoe_premium_text_without_echo( $this->options->auth_premium );
        $output .= "</p></div>";

        //Create nonce field
        $output .= wp_nonce_field( 'mcisoe_supplier_email_nonce', 'mcisoe_supplier_email_nonce', true, false );

        echo $output;

    }

    public function edit( $term )
    {
        $supplier_email       = get_term_meta( $term->term_id, 'mcisoe_supplier_email', true );
        $supplier_email       = isset( $supplier_email ) ? esc_html( sanitize_email( $supplier_email ) ) : '';
        $supplier_custom_text = get_term_meta( $term->term_id, 'mcisoe_supplier_custom_text', true );
        $supplier_custom_text = isset( $supplier_custom_text ) ? esc_textarea( sanitize_textarea_field( $supplier_custom_text ) ) : '';
        $supplier_data_text   = get_term_meta( $term->term_id, 'mcisoe_supplier_data_text', true );
        $supplier_data_text   = isset( $supplier_data_text ) ? esc_textarea( sanitize_textarea_field( $supplier_data_text ) ) : '';

        $output = "
  <tr class='form-field form-required term-email-wrap'>
  <th scope='row'>
  <label for='mcisoe_supplier_email'>{$this->email_label}</label>
  </th>
  <td>
    <input type='email' name='mcisoe_supplier_email' id='mcisoe_supplier_email' pattern='[^ @]*@[^ @]*' value={$supplier_email} required>
    <p class='description'>Enter the email address of the supplier.</p>
    </td>
    </tr>

    <tr class='form-field'>
    <th scope='row'>
    <label for='mcisoe_supplier_email' {$this->class_option_disabled}>{$this->supplier_custom_text_label}</label>
    </th>
    <td>
    <textarea name='mcisoe_supplier_custom_text' id='mcisoe_supplier_custom_text' class='mcisoe_supplier_custom_text' {$this->option_disabled}>{$supplier_custom_text}</textarea>
    <p class='description mci_field_edit_taxonomy'>{$this->supplier_custom_text_help}";
        $output .= McisoeHelpers::mcisoe_premium_text_without_echo( $this->options->auth_premium );
        $output .= "</p></td></tr>";

        $output .= "
          <tr class='form-field'>
    <th scope='row'>
    <label for='mcisoe_supplier_email' {$this->class_option_disabled}>{$this->supplier_data_text_label}</label>
    </th>
    <td>
    <textarea name='mcisoe_supplier_data_text' id='mcisoe_supplier_data_text' class='mcisoe_supplier_data_text' {$this->option_disabled}>{$supplier_data_text}</textarea>
    <p class='description mci_field_edit_taxonomy'>{$this->supplier_data_text_help}";

        $output .= McisoeHelpers::mcisoe_premium_text_without_echo( $this->options->auth_premium );
        $output .= "</p></td></tr>";

        //Create nonce field
        $output .= wp_nonce_field( 'mcisoe_supplier_email_nonce', 'mcisoe_supplier_email_nonce', true, false );

        echo $output;
    }

    public function save( $term_id )
    {
        //Verify nonce field
        if ( isset( $_POST['mcisoe_supplier_email'] ) && !empty( $_POST['mcisoe_supplier_email'] ) ) {

            $supplier_email = $_POST['mcisoe_supplier_email'];
            update_term_meta( $term_id, 'mcisoe_supplier_email', $supplier_email );
        }
        if ( isset( $_POST['mcisoe_supplier_custom_text'] ) && $this->options->auth_premium ) {
            $supplier_custom_text = sanitize_textarea_field( $_POST['mcisoe_supplier_custom_text'] );
            update_term_meta( $term_id, 'mcisoe_supplier_custom_text', $supplier_custom_text );
        }
        if ( isset( $_POST['mcisoe_supplier_data_text'] ) && $this->options->auth_premium ) {
            $supplier_data_text = sanitize_textarea_field( $_POST['mcisoe_supplier_data_text'] );
            update_term_meta( $term_id, 'mcisoe_supplier_data_text', $supplier_data_text );
        }

    }

}