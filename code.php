// Hook into the woocommerce_checkout_process action hook
add_action('woocommerce_checkout_process', 'restrict_product_by_zipcode_on_checkout');

function restrict_product_by_zipcode_on_checkout() {
    // Define the restricted product IDs
    $restricted_product_ids = array(610, 611, 612, 704); // Lemon Pie, Blueberry Cheese Pie, Cherry Cheese Pie, Strawberry Cheese Pie

    // Get the entered postcode from the checkout form
    $postcode = isset($_POST['billing_postcode']) ? sanitize_text_field($_POST['billing_postcode']) : '';

    // Flag to indicate if any restricted product is in the cart
    $restricted_product_in_cart = false;

    // Check if any restricted product is in the cart
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (in_array($cart_item['product_id'], $restricted_product_ids)) {
            $restricted_product_in_cart = true;
            break;
        }
    }

    // If a restricted product is in the cart
    if ($restricted_product_in_cart) {
        // Define allowed zip codes
        $allowed_zip_codes = array(
            '95307', '95313', '95316', '95322', '95323', '95326', '95328', '95350',
            '95351', '95354', '95355', '95356', '95357', '95358', '95360', '95361',
            '95363', '95367', '95368', '95380', '95382', '95385', '95386', '95397',
            '95202', '95203', '95204', '95205', '95206', '95207', '95209', '95210',
            '95211', '95212', '95214', '95215', '95219', '95230', '95231', '95296',
            '95297', '95304', '95320', '95330', '95336', '95337', '95361', '95366',
            '95376', '95377', '95385', '95391'
        );

        // Check if the entered postcode is not in the allowed zip codes
        if (!in_array($postcode, $allowed_zip_codes)) {
            // Add an error message and prevent order placement
            wc_add_notice(__('This product can only be purchased for specific zip codes.', 'woocommerce'), 'error');
        }
    }
}
