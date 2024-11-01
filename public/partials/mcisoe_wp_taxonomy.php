<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class McisoeWpTaxonomy
{
    private $supplier_fields;

    public function create_taxonomy_suppliers()
    {
        $labels = array(
            'name'                       => 'Suppliers',
            'singular_name'              => 'Supplier',
            'menu_name'                  => 'Suppliers',
            'all_items'                  => 'All Suppliers',
            'parent_item'                => 'Parent Supplier',
            'parent_item_colon'          => 'Parent Suppliers:',
            'new_item_name'              => 'Name of new Supplier',
            'add_new_item'               => 'New Supplier',
            'edit_item'                  => 'Edit Supplier',
            'update_item'                => 'Update Supplier',
            'separate_items_with_commas' => 'Separate Item with commas',
            'search_items'               => 'Search Suppliers',
            'add_or_remove_items'        => 'Add or remove Suppliers',
            'choose_from_most_used'      => 'Choose from the most used Suppliers',
            'not_found'                  => 'Not Found',
        );

        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'rewrite'           => ['slug' => 'supplier', 'with_front' => false],
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
        );

        // Registering 'supplier' Taxonomy
        register_taxonomy( 'supplier', 'product', $args );

        //Adds an already registered taxonomy 'supplier' to an object type 'product'
        register_taxonomy_for_object_type( 'supplier', 'product' );
    }

    public function add_supplier_fields()
    {
        if ( !is_admin() ) {return;}
        $this->supplier_fields->add();
    }

    public function edit_supplier_field( $term )
    {
        if ( !is_admin() ) {return;}
        $this->supplier_fields->edit( $term );
    }

    public function save_supplier_custom_field( $term_id )
    {
        if ( !is_admin() ) {return;}
        $this->supplier_fields->save( $term_id );
    }

    public function add_supplier_btn()
    {
        echo esc_html_e( 'To configure the "Supplier Order Email" plugin settings', 'supplier-order-email' );
        echo ':<br>';
        echo '<a class="mcisoe_link_to_options" id="add_supplier_btn" href="' . MCISOE_PLUGIN_OPTIONS_PAGE . '">' . __( "Go to 'Supplier Order Email' settings", "supplier-order-email" ) . '</a>';
    }

    public function hide_supplier_fields()
    {
        //If the current screen is taxonomy 'supplier' & post type 'product'
        if ( get_current_screen()->taxonomy == 'supplier' ) {
            wp_enqueue_style( 'mcisoe-taxonomy-css', MCISOE_PLUGIN_URL . 'admin/css/mcisoe_hide_taxonomy_fields.css' );
        }
    }

    public function display_supplier_email_error()
    {
        $error = '<div class="error">
                    <p>Please enter a valid email address for the supplier.</p>
                  </div>';
        echo $error;
    }

    //Add meta field column and Fill the rows with the data mcisoe_supplier_email meta field
    public function modify_supplier_column( $columns )
    {
        $columns['supplier_email']       = esc_html( 'Supplier Email', 'supplier-order-email' );
        $columns['supplier_custom_text'] = esc_html( 'Supplier custom text', 'supplier-order-email' );
        $columns['supplier_data_text']   = esc_html( 'Supplier data text', 'supplier-order-email' );
        unset( $columns['description'] );
        unset( $columns['slug'] );
        unset( $columns['posts'] );

        return $columns;
    }

    public function fill_supplier_column( $empty, $column, $term_id )
    {
        if ( $column == 'supplier_email' ) {
            $supplier_email = get_term_meta( $term_id, 'mcisoe_supplier_email', true );
            return $supplier_email;
        }
        if ( $column == 'supplier_custom_text' ) {
            $supplier_custom_text = get_term_meta( $term_id, 'mcisoe_supplier_custom_text', true );
            return $supplier_custom_text;
        }
        if ( $column == 'supplier_data_text' ) {
            $supplier_data_text = get_term_meta( $term_id, 'mcisoe_supplier_data_text', true );
            return $supplier_data_text;
        }

    }

    public function init()
    {
        $this->create_taxonomy_suppliers();

        require_once MCISOE_PLUGIN_DIR . 'public/partials/mcisoe_supplier_fields.php';
        $this->supplier_fields = new McisoeSupplierFields();

        // Add custom field to taxonomy 'supplier' in admin area (edit screen) and (add new screen)
        add_action( 'supplier_add_form_fields', [$this, 'add_supplier_fields'] );
        add_action( 'supplier_add_form_fields', [$this, 'add_supplier_btn'] );
        add_action( 'supplier_edit_form_fields', [$this, 'edit_supplier_field'] );

        // Insert css stylesheet to hide fields
        add_action( 'admin_enqueue_scripts', [$this, 'hide_supplier_fields'] );

        // Save custom field 'mcisoe_supplier_email' to database
        add_action( 'created_supplier', [$this, 'save_supplier_custom_field'] );
        add_action( 'edited_supplier', [$this, 'save_supplier_custom_field'] );

        // Add column 'supplier_email' to taxonomy 'supplier' in admin area and fill the rows. Also hide the description, slug & quantity columns.
        add_filter( 'manage_edit-supplier_columns', [$this, 'modify_supplier_column'] );
        add_filter( 'manage_supplier_custom_column', [$this, 'fill_supplier_column'], 10, 3 );

    }
}