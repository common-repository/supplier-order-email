<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php if ( MCISOE_REAL_ENVIRONMENT !== true || MCISOE_REAL_PDF !== true ): ?>
<div class="dev_mode">
    <p>Development mode is active</p>
</div>
<?php endif;?>

<h2 class="mcisoe_title">SUPPLIER ORDER EMAIL - Settings -</h2>

<!-- Header -->
<div class="mcisoe_header">
    <?php if ( !$options->auth_premium ): ?>
    <p class="mcisoe_description"><?php esc_html_e( 'Do you want the Premium version? ', 'supplier-order-email' );?>
        <a href="https://mci-desarrollo.es/supplier-order-email-premium/?lang=en" target="_blank">
            <?php esc_html_e( 'Get a 30-day free trial here.', 'supplier-order-email' );?></a>
    </p>
    <?php endif;?>

    <p class="mcisoe_description">
        <?php esc_html_e( 'Do you need changes in the plugin? Send us an email to', 'supplier-order-email' );?>
        <a href="mailto:soporte@mci-desarrollo.es">soporte@mci-desarrollo.es</a>
        <?php esc_html_e( 'and we will send you a quote.', 'supplier-order-email' );?>
    </p>

    <?php if ( !$options->auth_premium ): ?>
    <p><?php esc_html_e( 'Thanks for using our plugin. ', 'supplier-order-email' );?>
        <a href='https://wordpress.org/support/plugin/supplier-order-email/reviews/#new-post' target="_blank"
            rel="nofollow">
            <?php esc_html_e( 'You will be collaborating to maintain it if you value  ', 'supplier-order-email' );?><span
                class="stars">★ ★ ★ ★ ★</span>
        </a>
    </p>
    <?php endif;?>
</div>

<div id="mcisoe_content">

    <!-- Suppliers -->
    <div id="mcisoe_suppliers">
        <form action="" method="post">
            <table class="mcisoe_table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'SUPPLIER', 'supplier-order-email' );?></th>
                        <th><?php esc_html_e( 'EMAIL', 'supplier-order-email' );?></th>
                        <?php if ( $options->auth_premium ): ?>
                        <th><?php esc_html_e( 'SUPPLIER CUSTOM TEXT (for subject or intro text)', 'supplier-order-email' );?>
                        </th>
                        <th><?php esc_html_e( 'SUPPLIER DATA TEXT (for PDF document)', 'supplier-order-email' );?></th>
                        <?php endif;?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( $options->suppliers ): ?>
                    <?php foreach ( $options->suppliers as $supplier ): ?>
                    <tr>
                        <td class="mcisoe_nowrap"><?php echo esc_html( $supplier['name'] ) ?></td>
                        <td><?php echo esc_html( $supplier['email'] ) ?></td>
                        <?php if ( $options->auth_premium ): ?>
                        <td><?php echo esc_textarea( $supplier['supplier_custom_text'] ) ?></td>
                        <td><?php echo esc_textarea( $supplier['supplier_data_text'] ) ?></td>
                        <?php endif;?>
                    </tr>
                    <?php endforeach;?>
                    <?php else: ?>
                    <td class="mcisoe_not_suppliers">
                        <?php esc_html_e( 'There are no suppliers created yet. Create new suppliers in', 'supplier-order-email' )?>
                        <?php echo " "; ?>
                        <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=supplier&post_type=product' ) ?>">
                            <?php esc_html_e( 'Products / Suppliers', 'supplier-order-email' )?>
                    </td>
                    <?php endif;?>
                </tbody>
            </table>

            <?php if ( $options->suppliers ): ?>
            <?php esc_html_e( 'To add new Suppliers, edit or delete them', 'supplier-order-email' )?>
            <?php echo " "; ?>
            <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=supplier&post_type=product' ) ?>">
                <?php esc_html_e( 'Go to Suppliers', 'supplier-order-email' )?>
                <?php endif;?>
            </a>
    </div>

    <!-- Options -->
    <div id="mcisoe_options">

        <!-- Email subject -->
        <div class="mcisoe_input">
            <label for="subject"><?php esc_html_e( 'Email subject', 'supplier-order-email' )?></label>
            <input type="text" name="subject" id="subject" value="<?php echo esc_html( $options->email_subject ); ?>">
            <p class="mci_annotation">
                <?php esc_html_e( 'Optional tags: {order_number} {order_date} {supplier_name} {supplier_custom_text}', 'supplier-order-email' );?>
                <?php McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?>
            </p>
        </div>

        <!-- Introductory text -->
        <div class="mcisoe_input">
            <label for="email_intro"><?php esc_html_e( 'Email introductory text', 'supplier-order-email' )?></label>
            <textarea name="email_intro" id="email_intro" rows="4" columns="3"
                maxlength="390"><?php echo esc_textarea( $options->email_intro ); ?></textarea>
            <p class="mci_annotation">
                <?php esc_html_e( 'Optional tags: {order_number} {order_date} {supplier_name} {supplier_custom_text}', 'supplier-order-email' );?>
                <?php McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?>
            </p>
        </div>

        <hr>

        <!-- GENERAL OPTIONS -->
        <h3><?php esc_html_e( 'General options', 'supplier-order-email' )?></h3>

        <!-- Replace address -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="replace_address" id="replace_address"
                <?php if ( $options->replace_address == 1 ) {echo "checked";}?>>
            <label
                for="replace_address"><?php esc_html_e( 'Use the customer´s billing address if the order does not have a shipping address', 'supplier-order-email' );?></label>
        </div>

        <!-- Delete all data -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="delete_all_data" id="delete_all_data"
                <?php if ( $options->delete_all_data == 1 ) {echo "checked";}?>>
            <label
                for="delete_all_data"><?php esc_html_e( 'Delete all data & pdf documents when uninstall the plugin', 'supplier-order-email' );?></label>
        </div>

        <!-- Select admin email -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="select_email_admin" id="select_email_admin"
                <?php if ( $options->select_email_admin == 1 ) {echo "checked";}?>>
            <label
                for="select_email_admin"><?php esc_html_e( 'Send copy of emails to admin when send emails to suppliers', 'supplier-order-email' );?></label>
        </div>
        <!-- // PREMIUM OPTIONS /////////////////////////////////////////// -->

        <!-- Cancel sending automatic emails -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="cancel_all_emails" id="cancel_all_emails"
                <?php if ( $options->cancel_all_emails == 1 ) {echo "checked";}?>
                <?php if ( !$options->auth_premium ) {echo 'disabled';}?>>
            <label for="cancel_all_emails"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Turn off ALL automatic email sending (automatic emails will be disabled).', 'supplier-order-email' );?></label>
        </div>

        <!-- Store admin email copy-->
        <div class="mcisoe_input text_field">
            <label for="email_copy"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Email to receive the order copies. (Leave empty to use WordPress admin email)', 'supplier-order-email' );?></label>
            <input type="email" name="email_copy" id="email_copy"
                value="<?php if ( $options->auth_premium ) {echo esc_html( $options->email_copy );}?>"
                placeholder="<?php echo get_option( 'admin_email' ); ?>"
                <?php if ( !$options->auth_premium ) {echo 'disabled class="mcisoe_option_disabled"';}?>>
        </div>

        <!-- Replace from email -->
        <div class="mcisoe_input text_field">
            <label for="from_email"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Email sender: "From" and "Reply-To" labels for supplier emails. (Leave empty to use WordPress admin email)', 'supplier-order-email' );?></label>
            <input type="email" name="from_email" id="from_email"
                value="<?php if ( $options->auth_premium ) {echo esc_html( $options->from_email );}?>"
                placeholder="<?php echo get_option( 'admin_email' ); ?>"
                <?php if ( !$options->auth_premium ) {echo 'disabled class="mcisoe_option_disabled"';}?>>
            <p class="mci_annotation <?php if ( !$options->auth_premium ) {echo 'mcisoe_option_disabled';}?>">
                <?php esc_html_e( 'Use an email from the same domain as the store (to be friendly with mail servers).', 'supplier-order-email' );?>
            </p>
        </div>

        <!-- Select to trigger the sending of emails to suppliers. Contains 'Processing' and 'On hold' status. -->
        <div class="mcisoe_input mcisoe_select">
            <label for="email_trigger"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Order status to trigger automatic sending emails to suppliers', 'supplier-order-email' );?></label>
            <select name="email_trigger" id="email_trigger"
                <?php if ( !$options->auth_premium ) {echo 'disabled class="mcisoe_option_disabled"';}?>>
                <option value="processing" <?php if ( $options->email_trigger == 'processing' ) {echo 'selected';}?>>
                    <?php esc_html_e( 'Processing', 'supplier-order-email' );?>
                </option>
                <option value="on-hold" <?php if ( $options->email_trigger == 'on-hold' ) {echo 'selected';}?>>
                    <?php esc_html_e( 'On hold', 'supplier-order-email' );?>
                </option>
            </select>
        </div>

        <hr>

        <!-- ORDER OPTIONS IN EMAILS -->
        <h3 <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Order options in emails & pdf documents', 'supplier-order-email' );
McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?></h3>

        <!-- Show header color -->
        <div class="mcisoe_input_color">
            <input type="color" name="header_color" id="header_color"
                value="<?php echo esc_html( $options->header_color ); ?>"
                <?php if ( !$options->auth_premium ) {echo 'disabled';}?>>
            <label for="header_color"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Headers color', 'supplier-order-email' );?>
        </div>

        <!-- Replace email header with store logo -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="store_logo" id="store_logo"
                <?php if ( $options->auth_premium ) {if ( $options->store_logo ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="store_logo"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show store logo instead of header text', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show order number -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_order_number" id="show_order_number"
                <?php if ( $options->auth_premium ) {if ( $options->show_order_number ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_order_number"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show order number', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show customer email -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_customer_email" id="show_customer_email"
                <?php if ( $options->auth_premium ) {if ( $options->show_customer_email ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_customer_email"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show customer email', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show customer phone -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_customer_phone" id="show_customer_phone"
                <?php if ( $options->auth_premium ) {if ( $options->show_customer_phone ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_customer_phone"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show customer phone', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show notes -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_notes" id="show_notes"
                <?php if ( $options->auth_premium ) {if ( $options->show_notes ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_notes"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show customer notes', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show order total -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_order_total" id="show_order_total"
                <?php if ( $options->auth_premium ) {if ( $options->show_order_total ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_order_total"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show total price', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show payment method -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_payment_method" id="show_payment_method"
                <?php if ( $options->auth_premium ) {if ( $options->show_payment_method ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_payment_method"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show payment method', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show shipping method -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_shipping_method" id="show_shipping_method"
                <?php if ( $options->auth_premium ) {if ( $options->show_shipping_method ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_shipping_method"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show shipping method', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show cost total if Plugin is active -->
        <?php if ( is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) ) {?>
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_cost_total" id="show_cost_total"
                <?php if ( $options->auth_premium ) {if ( $options->show_cost_total ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_cost_total"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show cost total', 'supplier-order-email' );?>
            </label>
        </div>
        <?php }?>

        <!-- Hide customer -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="hide_customer" id="hide_customer"
                <?php if ( $options->auth_premium ) {if ( $options->hide_customer ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="hide_customer"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Hide all customer data', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Order options for pdf files -->
        <h3 <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Order options in PDF files', 'supplier-order-email' );
McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?></h3>

        <!-- Attach pdf -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="attach_pdf" id="attach_pdf"
                <?php if ( $options->auth_premium ) {if ( $options->attach_pdf ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="attach_pdf"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Attach pdf file to email', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Store pdf -->
        <div class="mcisoe_checkbox store_pdf">
            <input type="checkbox" name="store_pdf" id="store_pdf"
                <?php if ( $options->auth_premium ) {if ( $options->store_pdf ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="store_pdf"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Store pdf files (in WordPress uploads folder: /wp-content/uploads/supplier-order-email/pdf-files/0001_Supplier.pdf)', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Company info -->
        <div class="mcisoe_input company_info">
            <label for="company_info"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Company info text (for PDF document)', 'supplier-order-email' )?></label>
            <textarea name="company_info" id="company_info" rows="6" columns="3" maxlength="390"
                <?php if ( !$options->auth_premium ) {echo 'disabled class="option_disabled mcisoe_option_disabled"';}?>><?php echo esc_textarea( $options->company_info ); ?></textarea>
            <p class="mci_annotation <?php if ( !$options->auth_premium ) {echo 'option_disabled';}?>">
                <?php esc_html_e( 'This text is displayed below the logo in pdf documents.', 'supplier-order-email' );?>
                <?php McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?>
            </p>
        </div>

        <hr>

        <!-- SHOW PRODUCT ITEM FIELDS -->
        <h3 <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Show Product item fields', 'supplier-order-email' );
McisoeHelpers::mcisoe_premium_text( $options->auth_premium );?></h3>

        <!-- Show product image -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_product_img" id="show_product_img"
                <?php if ( $options->auth_premium ) {if ( $options->show_product_img ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_product_img"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Product image', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Product image width -->
        <div class="mcisoe_checkbox">
            <label for="product_img_width"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Max. image width (pixels)', 'supplier-order-email' );?>
            </label>
            <input type="number" name="product_img_width" id="product_img_width"
                value="<?php if ( $options->auth_premium ) {echo esc_html( $options->product_img_width );}?>" min="20"
                max="500" <?php if ( !$options->auth_premium ) {echo 'disabled class="mcisoe_option_disabled"';}?>>
        </div>

        <!-- Show short description -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_shortdesc" id="show_shortdesc"
                <?php if ( $options->auth_premium ) {if ( $options->show_shortdesc ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_shortdesc"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Short description', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show price items -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_price_items" id="show_price_items"
                <?php if ( $options->auth_premium ) {if ( $options->show_price_items ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_price_items"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Price', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show product weight -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_weight" id="show_weight"
                <?php if ( $options->auth_premium ) {if ( $options->show_weight ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_weight"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Weight', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show product cost prices if Plugin is active -->
        <?php if ( is_plugin_active( 'woocommerce-cost-of-goods/woocommerce-cost-of-goods.php' ) ) {?>
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_cost_prices" id="show_cost_prices"
                <?php if ( $options->auth_premium ) {if ( $options->show_cost_prices ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_cost_prices"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Cost price', 'supplier-order-email' );?>
            </label>
        </div>
        <?php }?>

        <!-- Show ean -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_ean" id="show_ean"
                <?php if ( $options->auth_premium ) {if ( $options->show_ean ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_ean"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'EAN number - compatible with plugin "EAN for WooCommerce" (WP Factory)', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show product attributes -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_product_attributes" id="show_product_attributes"
                <?php if ( $options->auth_premium ) {if ( $options->show_product_attributes ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_product_attributes"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Attributes', 'supplier-order-email' );?>
            </label>
        </div>

        <!-- Show product meta data -->
        <div class="mcisoe_checkbox">
            <input type="checkbox" name="show_product_meta" id="show_product_meta"
                <?php if ( $options->auth_premium ) {if ( $options->show_product_meta ) {echo 'checked';}} else {echo 'disabled';}?>>
            <label for="show_product_meta"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Variations and formatted meta custom fields', 'supplier-order-email' );?>
            </label>
        </div>


        <!-- Show special meta fields -->
        <div class="mcisoe_input text_field">
            <label for="special_meta"
                <?php if ( !$options->auth_premium ) {echo 'class="option_disabled"';}?>><?php esc_html_e( 'Add a list of special product meta custom fields to show (meta fields must exist in the product item)', 'supplier-order-email' );?></label>
            <input type="text" name="special_meta" id="special_meta"
                value="<?php if ( $options->auth_premium ) {echo esc_html( $options->special_meta );}?>"
                placeholder="Example: Gift message, _Special color, Additional Info"
                <?php if ( !$options->auth_premium ) {echo 'disabled class="mcisoe_option_disabled"';}?>>
            <p class="mci_annotation <?php if ( !$options->auth_premium ) {echo 'mcisoe_option_disabled';}?>">
                <?php esc_html_e( 'Type one or more custom meta fields separated by commas to display in product row. Some may contain "_"', 'supplier-order-email' );?>
            </p>
        </div>

    </div>





    <!-- // END PREMIUM OPTIONS /////////////////////////////////////////// -->

    <?php wp_nonce_field( 'mcisoe_nonce_field', 'mcisoe_nonce_field' );?>

    <input class="mcisoe_btn" type="submit" name="submit" value="<?php esc_html_e( 'Save', 'supplier-order-email' );?>">

    <hr>
    <div id="mcisoe_login" <?php if ( $options->auth_premium ) {echo ' class="background_green"';}?>>
        <?php if ( !$options->auth_premium ): ?>

        <!-- <div class="mcisoe_input" id="mci_pay_email">
        <label for="email"><?php esc_html_e( 'Premium registration email:', 'supplier-order-email' );?></label>
        <input type="email" name="mci_pay_email" class="premium-password" value="<?php echo esc_attr( $options->pay_email ); ?>">
      </div> -->

        <div class="mcisoe_input">
            <label for="code_key"><?php esc_html_e( 'License key', 'supplier-order-email' );?></label>
            <input type="password" name="mci_code_key" id="code_key" minlength="20" class="premium-password">
        </div>

        <input class="mcisoe_btn" type="submit" name="submit_mcisoe_activate"
            value="<?php esc_html_e( 'Activate premium', 'supplier-order-email' );?>">

        <a href="https://mci-desarrollo.es/supplier-order-email-premium/?lang=en" target="_blank"
            class="mcisoe_btn green">
            <?php esc_html_e( 'Get 30 days free trial Pro', 'supplier-order-email' );?></a>

        <!-- <a id="mcisoe_show_email_field"><?php esc_html_e( 'Show email field for old registrations', 'supplier-order-email' )?></a> -->

        <?php else: ?>
        <b
            class="mcisoe_success bold"><?php esc_html_e( '&#10687; PREMIUM VERSION IS ACTIVE', 'supplier-order-email' );?></b>
        <?php if ( $options->auth_lemon == '1' ): ?>
        <p class="success secondary_text deactivate_text">
            <?php esc_html_e( 'If you are no longer going to use the Premium options of the plugin in this WooCommerce installation, you can deactivate licenses to reduce the limit of your premium plan so that you can use it on other websites. You can always reactivate it with your License Key.', 'supplier-order-email' );?>
            <input class="mcisoe_btn" type="submit" name="mcisoe_deactivate" id="mcisoe_deactivate"
                value="<?php esc_html_e( 'Deactivate premium license on this website', 'supplier-order-email' );?>">
        </p>
        <?php endif;?>
        <?php endif;?>
    </div>
    <hr>

    </form>

    <div class="mcisoe_footer">
        <div class="instructions_mcisoe">
            <ol>
                <li><?php esc_html_e( 'Create new suppliers in: ', 'supplier-order-email' )?><a
                        href="<?php echo admin_url( 'edit-tags.php?taxonomy=supplier&post_type=product' ) ?>">
                        <?php esc_html_e( 'Products / Suppliers', 'supplier-order-email' )?>
                    </a></li>
                <li><?php esc_html_e( 'Select the supplier of the products in a new selection box that appears when editing each product.', 'supplier-order-email' )?>
                </li>
                <li>
                    <?php esc_html_e( 'When an order changes to "Processing" status, an automatic order email is sent to the supplier to send the corresponding products to the customer.', 'supplier-order-email' )?>
                </li>
            </ol>
            <hr>
            <?php if ( $options->auth_premium ): ?>
            <p>
                <?php esc_html_e( 'To overwrite email and pdf templates in your theme:', 'supplier-order-email' );?>
            </p>
            <ol>
                <li>
                    <?php esc_html_e( 'Copy the files inside the folders "/wp-content/plugins/supplier-order-email/includes/email/email-template-parts/" and "/wp- content/plugins/supplier-order-email/includes/email/pdf-template-parts/"', 'supplier-order-email' );?>
                </li>
                <li>
                    <?php esc_html_e( 'And paste them into "/wp-content/themes/child-theme/supplier-order-email/".', 'supplier-order-email' );?>
                </li>
                <li>
                    <?php esc_html_e( 'The child theme files will overwrite the plugin originals.', 'supplier-order-email' );?>
                </li>
            </ol>
            <?php endif;?>
            <p>
                <a href="https://mci-desarrollo.es/supplier-order-email-manual" target="_blank">
                    <?php esc_html_e( 'View Supplier Order Email Manual', 'supplier-order-email' );?>
                </a>
            </p>
            <?php if ( $options->auth_premium ): ?>
            <p>
                <a href='https://wordpress.org/support/plugin/supplier-order-email/reviews/#new-post' target="_blank"
                    rel="nofollow">
                    <?php esc_html_e( 'Rate our plugin', 'supplier-order-email' );?><span class="stars"> ★ ★ ★ ★
                        ★</span>
                </a>
            </p>
            <?php endif;?>

        </div>
    </div>

</div>

<?php