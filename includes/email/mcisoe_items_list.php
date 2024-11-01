<?php
if ( !defined( 'ABSPATH' ) ) {exit;}

class MciSoeItemsList
{
    private $items;
    private $wp_supplier;
    private $options;

    public $order_total;
    public $cost_total;
    public $email_items_list;
    public $pdf_items_list;
    public $match_supplier;
    public $helpers;
    public $item_supplier;

    public function __construct( $items, $wp_supplier, $options )
    {
        require_once MCISOE_PLUGIN_DIR . 'helpers/mcisoe_helpers.php';
        $this->helpers          = new McisoeHelpers;
        $this->items            = $items;
        $this->wp_supplier      = $wp_supplier;
        $this->options          = $options;
        $this->order_total      = 0;
        $this->cost_total       = 0;
        $this->match_supplier   = false;
        $this->email_items_list = '';
        $this->pdf_items_list   = '';

        $this->create_items_list( 'email' );
        $this->create_items_list( 'pdf' );

    }

    public function create_items_list( $type )
    {
        $items_list = '';

        if ( $type == 'email' ) {
            $th_template_file = 'mcisoe_table_header.php';
            $th_class_name    = 'MciSoeTableHeader';
        } elseif ( $type == 'pdf' ) {
            $th_template_file = 'mcisoe_pdf_table_header.php';
            $th_class_name    = 'MciSoePdfTableHeader';
        }

        // Imprimir la cabecera de la tabla desde el template
        require_once $this->helpers->search_in_child_theme( $th_template_file, $type, $this->options->auth_premium );
        $table_header = new $th_class_name( $this->options );
        $items_list .= $table_header->get();

        $items_list .= '<tbody>';
        foreach ( $this->items as $item ) {

            // Hook para enviar correos a todos los proveedores
            $send_emails_to_all_suppliers = apply_filters( 'send_emails_to_all_suppliers', false );

            if ( $send_emails_to_all_suppliers && $this->options->auth_premium ) {
                ///// INICIO PREMIUM ///////////////////////////
                $item_suppliers = $this->select_all_yoast_suppliers( $item );
                ///// FIN PREMIUM /////////////////////////////
            } else {
                $item_suppliers = $this->select_yoast_parent_supplier( $item );
            }

            foreach ( $item_suppliers as $item_supplier ) {

                if ( $item_supplier == (int) $this->wp_supplier['term_id'] ) {

                    $this->match_supplier = true;

                    $product_complete = $item->get_product();
                    $product_qty      = sanitize_text_field( $item['quantity'] );
                    $product_sku      = !empty( $product_complete->get_sku() ) ? sanitize_text_field( $product_complete->get_sku() ) : '';
                    $product_name     = !empty( $item['name'] ) ? sanitize_text_field( $item['name'] ) : '';

                    // INICIO PREMIUM
                    $item_data = [];

                    if ( $this->options->show_product_img == '1' ) {
                        $product_img              = $this->get_product_img( $item );
                        $item_data['product_img'] = $product_img;
                    } else {
                        $item_data['product_img'] = '';
                    }

                    if ( $this->options->show_weight == '1' ) {
                        $product_weight              = !empty( $product_complete->get_weight() ) ? sanitize_text_field( $product_complete->get_weight() ) : '';
                        $item_data['product_weight'] = $product_weight;
                    } else {
                        $item_data['product_weight'] = '';
                    }

                    if ( $this->options->show_shortdesc == '1' ) {
                        $product_shortdesc      = !empty( $product_complete->get_short_description() ) ? sanitize_text_field( $product_complete->get_short_description() ) : '';
                        $item_data['shortdesc'] = $product_shortdesc;
                    } else {
                        $item_data['shortdesc'] = '';
                    }

                    if ( $this->options->show_ean == '1' ) {
                        $product_ean      = !empty( $product_complete->get_meta( '_alg_ean' ) ) ? sanitize_text_field( $product_complete->get_meta( '_alg_ean' ) ) : '';
                        $item_data['ean'] = $product_ean;
                    } else {
                        $item_data['ean'] = '';
                    }

                    if ( $this->options->show_price_items == '1' ) {
                        $price_item         = $item['total'] + $item['total_tax'];
                        $product_price      = $this->helpers->build_price_currency( $price_item );
                        $item_data['price'] = $product_price;
                    } else {
                        $item_data['price'] = '';
                    }

                    if ( $this->options->show_product_attributes == '1' ) {
                        $product_attributes      = !empty( $this->get_product_attributes( $item ) ) ? $this->get_product_attributes( $item ) : '';
                        $item_data['attributes'] = $product_attributes;
                    } else {
                        $item_data['attributes'] = '';
                    }

                    if ( $this->options->show_product_meta == '1' ) {
                        $product_meta      = $this->get_product_meta_fields( $item );
                        $item_data['meta'] = $product_meta;
                    } else {
                        $item_data['meta'] = '';
                    }

                    if ( $this->options->special_meta != '' ) {
                        $special_meta              = $this->get_special_product_meta_fields( $item );
                        $item_data['special_meta'] = $special_meta;
                    } else {
                        $item_data['special_meta'] = '';
                    }

                    if ( $this->options->show_order_total == '1' && $type == 'email' ) {
                        $this->order_total += round( $item['total'], 2 ) + round( $item['total_tax'], 2 );
                    }

                    if ( $this->options->show_cost_total == '1' && $type == 'email' ) {
                        $product_cost        = !empty( $this->get_line_cost( $item ) ) ? $this->get_line_cost( $item ) : '';
                        $product_cost_format = $this->helpers->build_price_currency( $product_cost );
                        $item_data['cost']   = $product_cost_format;
                        $this->cost_total += (float) $product_cost;
                    } else {
                        $item_data['cost'] = '';
                    }

                    if ( $type == 'email' ) {
                        $tbody_template_file = 'mcisoe_table_content.php';
                        $tbody_class_name    = 'MciSoeTableContent';
                    } elseif ( $type == 'pdf' ) {
                        $tbody_template_file = 'mcisoe_pdf_table_content.php';
                        $tbody_class_name    = 'MciSoePdfTableContent';
                    }

                    require_once $this->helpers->search_in_child_theme( $tbody_template_file, $type, $this->options->auth_premium );
                    $table_content = new $tbody_class_name( $this->options, $product_sku, $product_name, $product_qty, $item_data['ean'], $item_data['price'], $item_data['attributes'], $item_data['meta'], $item_data['cost'], $item_data['shortdesc'], $item, $item_data['special_meta'], $item_data );
                    $items_list .= $table_content->get();
                }
            }
        }

        if ( $type == 'email' ) {
            $this->email_items_list = $items_list;
        } elseif ( $type == 'pdf' ) {
            $this->pdf_items_list = $items_list;
        }
        ///// END PREMIUM /////////////////////////////
    }

    private function select_yoast_parent_supplier( $item )
    {
        $taxonomy = 'supplier';

        // Get supplier primary taxonomy if exists (Yoast SEO)
        if ( !function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
            $primary_cat_id = yoast_get_primary_term_id( $taxonomy, $item->get_product_id() );
        } else {
            $primary_cat_id = false;
        }

        $suppliers = [];

        if ( isset( $primary_cat_id ) && is_plugin_active( 'wordpress-seo/wp-seo.php' ) && $primary_cat_id !== false && $primary_cat_id !== "" ) {

            $primary_cat      = get_term( $primary_cat_id, $taxonomy );
            $primary_cat_name = $primary_cat->term_id;
            $suppliers[]      = $primary_cat_name;

        } else {
            //If Yoast is inactive or not has primary category
            $item_taxonomy_terms = get_the_terms( $item->get_product_id(), 'supplier' );
            $first_cat_id        = $item_taxonomy_terms != false ? $item_taxonomy_terms[0]->term_id : '';
            $suppliers[]         = $first_cat_id;
        }

        return $suppliers;
    }

    private function select_all_yoast_suppliers( $item )
    {
        $taxonomy = 'supplier';

        // Get supplier primary taxonomy if exists (Yoast SEO)
        if ( !function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
            // $primary_cat_id = yoast_get_primary_term_id( $taxonomy, $item->get_product_id() );
            $all_cats = wp_get_post_terms( $item->get_product_id(), $taxonomy );
        } else {
            $all_cats = false;
        }

        if ( isset( $all_cats ) && is_plugin_active( 'wordpress-seo/wp-seo.php' ) && $all_cats !== false && $all_cats !== "" && is_array( $all_cats ) ) {

            $suppliers = [];
            foreach ( $all_cats as $cat ) {
                $suppliers[] = $cat->term_id;
            }

        } else {
            //If Yoast is inactive or not has primary category
            $item_taxonomy_terms = get_the_terms( $item->get_product_id(), 'supplier' );
            $first_cat_id        = $item_taxonomy_terms != false ? $item_taxonomy_terms[0]->term_id : '';
            $suppliers[]         = $first_cat_id;
        }

        return $suppliers;
    }

    private function get_product_attributes( $item )
    {
        // Get Attributes
        $attributes = '';

        $product_complete   = $item->get_product();
        $product_attributes = $product_complete->get_attributes();

        foreach ( $product_attributes as $attribute => $value ) {

            if ( isset( $value ) && is_object( $value ) && !empty( $value ) ) {

                $attributes .= '<li>';

                // Defines attribute label
                $name = sanitize_text_field( $value->get_name() );
                if ( strpos( $name, 'pa_' ) !== false ) {
                    $name = str_replace( 'pa_', '', $name );
                    $name = ucfirst( $name );
                }
                $attributes .= $name . ': ';

                //Defines name of options (terms) of attribute
                $option_names = [];

                if ( is_array( $value->get_terms() ) || is_object( $value->get_terms() ) ) {

                    foreach ( $value->get_terms() as $option ) {

                        $option_name    = sanitize_text_field( get_term( $option )->name );
                        $option_names[] = sanitize_text_field( $option_name );
                    }
                    //Build list name of options separated by comma
                    $attributes .= implode( ', ', $option_names );
                    $attributes .= '</li>';

                } else {

                    foreach ( $value->get_options() as $option ) {

                        $option_name    = sanitize_text_field( $option );
                        $option_names[] = sanitize_text_field( $option_name );
                    }
                    $attributes .= implode( ', ', $option_names );
                    $attributes .= '</li>';
                }
            }
        } //end foreach

        return $attributes;
    }

    private function get_product_meta_fields( $item )
    {
        //Get the product meta and product variations
        $product_id            = $item->get_product_id();
        $product               = wc_get_product( $product_id );
        $product_custom_fields = get_post_custom( $product_id );

        // Get meta_items for product line // Obtener meta_items para la línea de producto
        $product_meta_items = "";
        $meta_items         = $item->get_formatted_meta_data();

        $meta_items = apply_filters( 'mcisoe_product_meta', $meta_items, $item );

        if ( $meta_items && !empty( $meta_items ) ) {

            foreach ( $meta_items as $meta_item ) {

                if ( $meta_item->key !== '_metadate' ) {
                    $product_meta_items .= '<li>';
                    $product_meta_items .= sanitize_text_field( $meta_item->display_key );
                    $product_meta_items .= ': ';
                    $product_meta_items .= sanitize_text_field( $meta_item->display_value );
                }
            }
            $product_meta_items .= '</li>';

        } //end if meta_items

        $product_meta_items = apply_filters( 'mcisoe_product_below_meta', $product_meta_items, $item );

        return $product_meta_items;
    }

    private function get_special_product_meta_fields( $item )
    {
        //Get array from input in settings panel
        $special_meta_array = $this->options->special_meta;
        $special_meta_array = explode( ',', $special_meta_array );
        $special_meta_array = array_map( 'trim', $special_meta_array );

        // Hook for filter special meta array
        $special_meta_array = apply_filters( 'mcisoe_special_meta_array', $special_meta_array, $item );

        //Get the product meta
        $all_meta = $item->get_meta_data();

        if ( !empty( $all_meta ) ) {

            $special_meta_values = "";

            foreach ( $special_meta_array as $special_meta ) {

                $meta_value = $item->get_meta( $special_meta, true );

                $meta_value = wp_kses( $meta_value, array(
                    'a' => array(
                        'href'   => array(
                            'protocols' => array( 'http', 'https' ) ),
                        'title'  => array(),
                        'target' => array( '_blank' ),
                    ),
                ) );

                //If $special meta has _ in fisrt position or last position, remove it. If has _ in both positions, replace it by space
                if ( strpos( $special_meta, '_' ) === 0 ) {
                    $special_meta = substr( $special_meta, 1 );
                }
                if ( strrpos( $special_meta, '_' ) === strlen( $special_meta ) - 1 ) {
                    $special_meta = substr( $special_meta, 0, -1 );
                }
                $special_meta = str_replace( '_', ' ', $special_meta );

                if ( !empty( $meta_value ) ) {
                    $special_meta_values .= '<li>';
                    $special_meta_values .= $special_meta;
                    $special_meta_values .= ': ';
                    $special_meta_values .= $meta_value;
                    $special_meta_values .= '</li>';
                }
            }

        } else {
            $special_meta_values = "";
        }

        return $special_meta_values;
    }

    private function get_line_cost( $item )
    {
        $cost = sanitize_text_field( $item->get_meta( '_wc_cog_item_total_cost' ) );
        $cost = str_replace( ',', '.', $cost );
        $cost = (float) $cost;
        $cost = number_format( $cost, 2, '.', '' );

        return $cost;
    }

    private function get_product_img( $item )
    {
        //If product has variations, get the image from item variation
        if ( $item->get_variation_id() ) {
            $variation_id = $item->get_variation_id();
            $variation    = wc_get_product( $variation_id );
            $image_id     = $variation->get_image_id();
            $image_url    = wp_get_attachment_image_url( $image_id, 'large' );

            return esc_url( $image_url );
        } else {
            //If product has no variations, get the image from product
            $product_id = $item->get_product_id();
            $product    = wc_get_product( $product_id );
            $image_id   = $product->get_image_id();
            $image_url  = wp_get_attachment_image_url( $image_id, 'large' );

            return esc_url( $image_url );
        }
    }

} //End class
