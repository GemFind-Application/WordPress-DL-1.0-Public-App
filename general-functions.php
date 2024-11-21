<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly
/**
 * @param $requestParam
 *
 * @return array
 */
function gemfindDT_sendRequest($requestParam)
{
    // echo '<pre>';print_r($requestParam); exit;
    $Shape = $CaratMin = $CaratMax = $PriceMin = $PriceMax = $ColorId = $ClarityId = $CutGradeId = $TableMin = $TableMax = $DepthMin = $DepthMax = $SymmetryId = $PolishId = $FluorescenceId = $Certificate = $OrderBy = $OrderType = $PageNumber = $PageSize = $InHouseOnly = $SOrigin = $query_string = $DID = $FancyColor = $intIntensity = $diamondFilterRange = $HasVideo = '';
    if ($requestParam) {
        $options = gemfindDT_getOptions();
        $DealerID = 'DealerID=' . $options['dealerid'] . '&';

        if (array_key_exists('shapes', $requestParam)) {
            if ($requestParam['shapes']) {
                $Shape = 'Shape=' . $requestParam['shapes'] . '&';
            }
        }
        if (array_key_exists('size_from', $requestParam)) {
            if ($requestParam['size_from']) {
                $CaratMin = 'CaratMin=' . $requestParam['size_from'] . '&';
            }
        }
        if (array_key_exists('size_to', $requestParam)) {
            if ($requestParam['size_to']) {
                $CaratMax = 'CaratMax=' . $requestParam['size_to'] . '&';
            }
        }
        if (array_key_exists('price_from', $requestParam)) {
            if ($requestParam['price_from']) {
                $PriceMin = 'PriceMin=' . $requestParam['price_from'] . '&';
            } else {
                $PriceMin = 'PriceMin=0&';
            }
        }
        if (array_key_exists('price_to', $requestParam)) {
            if ($requestParam['price_to']) {
                $PriceMax = 'PriceMax=' . $requestParam['price_to'] . '&';
            }
        }
        if (array_key_exists('depth_percent_from', $requestParam)) {
            if ($requestParam['depth_percent_from']) {
                $DepthMin = 'DepthMin=' . $requestParam['depth_percent_from'] . '&';
            } else {
                $DepthMin = 'DepthMin=0&';
            }
        }
        if (array_key_exists('depth_percent_to', $requestParam)) {
            if ($requestParam['depth_percent_to']) {
                $DepthMax = 'DepthMax=' . $requestParam['depth_percent_to'] . '&';
            }
        }
        if (array_key_exists('diamond_table_from', $requestParam)) {
            if ($requestParam['diamond_table_from']) {
                $TableMin = 'TableMin=' . $requestParam['diamond_table_from'] . '&';
            } else {
                $TableMin = 'TableMin=0&';
            }
        }
        if (array_key_exists('diamond_table_to', $requestParam)) {
            if ($requestParam['diamond_table_to']) {
                $TableMax = 'TableMax=' . $requestParam['diamond_table_to'] . '&';
            }
        }
        if (array_key_exists('color', $requestParam)) {
            if ($requestParam['color']) {
                $ColorId = 'ColorId=' . $requestParam['color'] . '&';
            }
        }
        if (array_key_exists('clarity', $requestParam)) {
            if ($requestParam['clarity']) {
                $ClarityId = 'ClarityId=' . $requestParam['clarity'] . '&';
            }
        }
        if (array_key_exists('cut', $requestParam)) {
            if ($requestParam['cut']) {
                $CutGradeId = 'CutGradeId=' . $requestParam['cut'] . '&';
            }
        }
        if (array_key_exists('symmetry', $requestParam)) {
            if ($requestParam['symmetry']) {
                $SymmetryId = 'SymmetryId=' . $requestParam['symmetry'] . '&';
            }
        }
        if (array_key_exists('polish', $requestParam)) {
            if ($requestParam['polish']) {
                $PolishId = 'PolishId=' . $requestParam['polish'] . '&';
            }
        }
        if (array_key_exists('fluorescence_intensities', $requestParam)) {
            if ($requestParam['fluorescence_intensities']) {
                $FluorescenceId = 'FluorescenceId=' . $requestParam['fluorescence_intensities'] . '&';
            }
        }
        if (array_key_exists('labs', $requestParam)) {
            if ($requestParam['labs']) {
                $Certificate = 'Certificate=' . $requestParam['labs'] . '&';
            }
        }
        if (array_key_exists('sort_by', $requestParam)) {
            if ($requestParam['sort_by']) {
                $OrderBy = 'OrderBy=' . $requestParam['sort_by'] . '&';
            }
            if ($requestParam['sort_by'] == 'Inhouse') {
                $OrderBy .= 'ShowInhouseFirst=true&';
            }
        }
        if (array_key_exists('sort_direction', $requestParam)) {
            if ($requestParam['sort_direction']) {
                $OrderType = 'OrderType=' . $requestParam['sort_direction'] . '&';
            }
        }
        if (array_key_exists('page_number', $requestParam)) {
            if ($requestParam['page_number']) {
                $PageNumber = 'PageNumber=' . $requestParam['page_number'] . '&';
            }
        }
        if (array_key_exists('page_size', $requestParam)) {
            if ($requestParam['page_size']) {
                $PageSize = 'PageSize=' . $requestParam['page_size'];
            }
        }
        if (array_key_exists('InHouseOnly', $requestParam)) {
            if ($requestParam['InHouseOnly']) {
                $InHouseOnly = '&InHouseOnly=' . $requestParam['InHouseOnly'];
            }
        }
        if (array_key_exists('origin', $requestParam)) {
            if ($requestParam['origin']) {
                $SOrigin = '&SOrigin=' . $requestParam['origin'] . '&';
            }
        }

        if (array_key_exists('did', $requestParam)) {
            if ($requestParam['did']) {
                $DID = 'DID=' . $requestParam['did'] . '&';
            }
        }

        if (array_key_exists('hasvideo', $requestParam)) {
            if ($requestParam['hasvideo']) {
                $HasVideo = 'HasVideo=' . $requestParam['hasvideo'] . '&';
            }
        }
        if (array_key_exists('diamondFilterRange', $requestParam)) {
            if (($requestParam['diamondFilterRange'] && $requestParam['diamondFilterRange'] == 'in-house') || $requestParam['diamondFilterRange'] == 'virtual') {
                $diamondFilterRange = '&diamondfilter=' . $requestParam['diamondFilterRange'];
            }
        }
        if (array_key_exists('navigationapi', $requestParam)) {
            $requestUrl = $options['navigationapi'] . 'DealerID=' . $options['dealerid'];
        }
        if (array_key_exists('diamondsoptionapi', $requestParam)) {
            $requestUrl = $options['diamondsoptionapi'] . 'DealerID=' . $options['dealerid'];
        }
        if (array_key_exists('diamondsinitialfilter', $requestParam)) {
            $requestUrl = $options['diamondsinitialfilter'] . 'DealerID=' . $options['dealerid'];
        }
        if (array_key_exists('Filtermode', $requestParam)) {
            if ($requestParam['Filtermode'] != 'navstandard' && $requestParam['Filtermode'] != 'navlabgrown') {
                if (array_key_exists('FancyColor', $requestParam)) {
                    if ($requestParam['FancyColor']) {
                        $FancyColor = 'FancyColor=' . $requestParam['FancyColor'] . '&';
                    }
                }
                if (array_key_exists('intIntensity', $requestParam)) {
                    if ($requestParam['intIntensity']) {
                        $requestParam['intIntensity'] = str_replace(' ', '+', $requestParam['intIntensity']);
                        $intIntensity = 'intIntensity=' . $requestParam['intIntensity'] . '&';
                    }
                }
                $IsLabGrown = '&IsLabGrown=false';
                $query_string = $DealerID . $Shape . $CaratMin . $CaratMax . $PriceMin . $PriceMax . $ClarityId . $CutGradeId . $TableMin . $TableMax . $DepthMin . $DepthMax . $SymmetryId . $PolishId . $FluorescenceId . $FancyColor . $intIntensity . $Certificate . $SOrigin . $DID . $OrderBy . $OrderType . $PageNumber . $PageSize . $InHouseOnly . $diamondFilterRange . $IsLabGrown;
                $requestUrl = $options['diamondlistapifancy'] . $query_string;
            } else {
                if ($requestParam['Filtermode'] == 'navlabgrown') {
                    $IsLabGrown = '&IsLabGrown=true';
                } else {
                    $IsLabGrown = '&IsLabGrown=false';
                }
                $query_string = $DealerID . $Shape . $CaratMin . $CaratMax . $PriceMin . $PriceMax . $ColorId . $ClarityId . $CutGradeId . $TableMin . $TableMax . $DepthMin . $DepthMax . $SymmetryId . $PolishId . $FluorescenceId . $Certificate . $SOrigin . $DID . $OrderBy . $OrderType . $PageNumber . $PageSize . $InHouseOnly . $diamondFilterRange . $IsLabGrown;
                $requestUrl = $options['diamondlistapi'] . $query_string;
            }
        }
    }
    $args = [
        'method' => 'GET',
        'headers' => ['Content-type: application/x-www-form-urlencoded'],
    ];

    // Make the API request
    $api_response = wp_remote_get($requestUrl, $args);

    if (is_wp_error($api_response) || $api_response['response']['code'] !== 200) {
        // Handle the error case
        $returnData = ['diamonds' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.'];
        return $returnData;
    }

    $results = json_decode($api_response['body']);
    // Continue processing $results as needed

    if (!array_key_exists('navigationapi', $requestParam) && $results->diamondList != '' && $results->count > 0) {
        $returnData = ['diamonds' => $results->diamondList, 'total' => $results->count];
    } elseif (array_key_exists('navigationapi', $requestParam)) {
        $returnData = $results;
    } elseif (array_key_exists('diamondsoptionapi', $requestParam)) {
        $returnData = $results;
    } elseif (array_key_exists('diamondsinitialfilter', $requestParam)) {
        $returnData = $results;
    } else {
        $returnData = ['diamonds' => [], 'total' => 0];
    }

    return $returnData;
}

/**
 * @return int
 */
function gemfindDT_getResultPerPage()
{
    return 20;
}

/**
 * @param $shop
 *
 * @return int
 */
function gemfindDT_getOptions()
{
    $options = get_option('gemfind_diamond_link');

    return $options;
}

/**
 * Get options for the per page list on grid.
 */
function gemfindDT_getAllOptions()
{
    return [
        [
            'label' => 20,
            'value' => 20,
        ],
        [
            'label' => 50,
            'value' => 50,
        ],
        [
            'label' => 100,
            'value' => 100,
        ],
    ];
}

/**
 * @param $param ,$type,$shopurl,$pathprefixshop
 *
 * @return string
 */
function gemfindDT_getDiamondViewUrl($param, $type, $shopurl, $pathprefixshop)
{
    $route = $shopurl . $pathprefixshop . '/product/';

    return gemfindDT_getUrl($route, ['path' => $param, 'type' => $type, '_secure' => true]);
}

/**
 * @param $route ,$params
 *
 * @return string
 */
function gemfindDT_getUrl($route = '', $params = [])
{
    if ($params['path']) {
        return $route . $params['path'];
    } else {
        return $route . $params['id'];
    }
}

if (!function_exists('gemfindDT_is_404')) {
    function gemfindDT_is_404($url)
    {
        $response = wp_safe_remote_head($url);

        if (is_wp_error($response)) {
            // Handle the error here
            return true; // Assume 404 on error
        } else {
            $httpCode = wp_remote_retrieve_response_code($response);
            if ($httpCode === 404) {
                return true; // URL is not found (404)
            } else {
                return false; // URL is found (not 404)
            }
        }
    }
}

if (!function_exists('gemfindDT_getDiamondSkuByPath')) {
    function gemfindDT_getDiamondSkuByPath($path)
    {
        $urlstring = $path;
        $temp = explode('/', $urlstring);
        if (end($temp) == 'labcreated') {
            $urlstring = rtrim($urlstring, '/labcreated');
        } elseif (end($temp) == 'fancydiamonds') {
            $urlstring = rtrim($urlstring, '/fancydiamonds');
        }
        $urlarray = explode('-sku-', $urlstring);
        return rtrim($urlarray[1]);
    }
}

/**
 * For getting style settings for our shop.
 */

function gemfindDT_getStyleSettings()
{
    $options = gemfindDT_getOptions();
    $DealerID = 'DealerID=' . $options['dealerid'];
    $query_string = $DealerID;
    $stylesettingapi = $options['stylesettingapi'];
    $requestUrl = $stylesettingapi . $query_string . '&ToolName=DL';

    $args = [
        'method' => 'GET',
        'headers' => ['Content-type: application/x-www-form-urlencoded'],
    ];

    $response = wp_remote_post($requestUrl, $args);

    if (is_wp_error($response)) {
        // Handle the error case
        return ['settings' => []];
    }

    $results = json_decode($response['body']);

    if ($results && isset($results[0][0])) {
        $settings = (array) $results[0][0];
        return ['settings' => $settings];
    }

    return ['settings' => []];
}


/**
 * @param $shop
 *
 * @return string
 */
function gemfindDT_getCurrencySymbol()
{
    $options = gemfindDT_getOptions();
    $DealerID = 'DealerID=' . $options['dealerid'];
    $query_string = $DealerID;
    $stylesettingapi = $options['filterapi'];
    $requestUrl = $stylesettingapi . $query_string;

    $response = wp_remote_get($requestUrl);

    if (is_wp_error($response)) {
        // Handle the error case
        return '';
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body);

    if (is_array($results) && sizeof($results) > 1 && $results[0]->message == 'Success') {
        foreach ($results[1] as $value) {
            $value->currencySymbol = $value->currencySymbol == "US$" ? "$" : $value->currencySymbol;
            return $value->currencyFrom != 'USD' ? $value->currencyFrom . $value->currencySymbol : $value->currencySymbol;
        }
    }

    return '';
}

/**
 * @return array|product
 */
function gemfindDT_getProduct()
{
    $diamond_path = rtrim(sanitize_url($_SERVER['REQUEST_URI']), '/'); //end( explode( '/', $_SERVER['REQUEST_URI'] ) );
    $temp = explode('/', $diamond_path);
    if (end($temp) == 'labcreated') {
        $type = end($temp);
    } elseif (end($temp) == 'fancydiamonds') {
        $type = end($temp);
    } else {
        $type = '';
    }
    $id = gemfindDT_getDiamondSkuByPath($diamond_path);
    $shop = get_site_url();
    $diamond_product = gemfindDT_getDiamondById($id, $type, $shop);

    return $diamond_product;
}

/**
 * @param $id
 * @param $shop
 *
 * @return array
 */
function gemfindDT_getDiamondById($id, $type, $shop)
{
    $IslabGrown = '';
    $IsFancy = '';
    if ($type && $type == 'labcreated') {
        $IslabGrown = '&IslabGrown=true';
    } elseif ($type && $type == 'fancydiamonds') {
        $IsFancy = '&IsFancy=true';
    } else {
        $IslabGrown = '';
        $IsFancy = '';
    }
    $options = gemfindDT_getOptions();
    $DealerID = 'DealerID=' . $options['dealerid'] . '&';
    $DID = 'DID=' . $id;
    $query_string = $DealerID . $DID . $IslabGrown . $IsFancy;
    //echo $query_string;
    $requestUrl = $options['diamonddetailapi'] . $query_string;
    $response = wp_remote_get($requestUrl);
    if (is_wp_error($response)) {
        return ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.'];
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body);

    if (is_wp_error($results)) {
        return ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.'];
    }

    if (isset($results->message) || !isset($results->diamondId)) {
        return ['diamondData' => []];
    }

    $diamondData = (array) $results;
    $diamondData['diamondType'] = $type;

    return ['diamondData' => $diamondData];
}

/**
 * For setting general per page parameters of diamond listing.
 */
function gemfindDT_getResultsPerPage()
{
    return 20;
}

/**
 * For programatically set post thumbnail.
 *
 * @param string $post_id id of the post to set thumbnail to.
 * @param string $image_url url of an image to set as post thumbnail.
 */
function gemfindDT_custom_thumbnail_set($post_id, $image_url, $image_type)
{
    // Add Featured Image to Post
    $image_url = $image_url;
    $image_name = end(explode('/', $image_url));
    $upload_dir = wp_upload_dir(); // Set upload folder
    $image_data = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
    $filename = basename($unique_file_name); // Create image file name

    // Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    // Create the image  file on the server
    file_put_contents($file, $image_data);

    // Check image file type
    $wp_filetype = wp_check_filetype($filename, null);

    // Set attachment data
    $attachment = [
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit',
    ];

    // Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

    // Include image.php
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

    // Assign metadata to attachment
    wp_update_attachment_metadata($attach_id, $attach_data);

    // And finally assign featured image to post
    if ($image_type == 'featured_image') {
        set_post_thumbnail($post_id, $attach_id);
    } elseif ($image_type == 'gallery_image') {
        add_post_meta($post_id, '_product_image_gallery', $attach_id);
    }
}

/**
 * Check if url is returning 404.
 *
 * @param string $url id of the post to set thumbnail to.
 */
function gemfindDT_is_url_404($url)
{
    $diamond_image = preg_replace('/\s/', '', $url);

    $response = wp_safe_remote_head($diamond_image);

    if (is_wp_error($response)) {
        return -1; // Handle the error case
    }

    $httpCode = wp_remote_retrieve_response_code($response);

    return $httpCode;
}

/**
 * Will authenticate dealer.
 */

function gemfindDT_authenticateDealer()
{
    $form_data = rest_sanitize_array($_POST['form_data']);
    $all_options = gemfindDT_getOptions();
    $auth_post_data = [];

    foreach ($form_data as $data) {
        $auth_post_data[$data['name']] = $data['value'];
    }

    $request_body = json_encode(['DealerID' => $all_options['dealerid'], 'DealerPass' => $auth_post_data['password']]);
    $request_args = [
        'headers' => [
            'Content-Type' => 'application/json',
            'cache-control' => 'no-cache',
        ],
        'body' => $request_body,
        'timeout' => 30,
        'sslverify' => false, // Only set to false if you trust the source
    ];

    $response = wp_remote_post($all_options['dealerauthapi'], $request_args);

    if (is_wp_error($response)) {
        error_log('Gemfind Authentication Error: ' . $response->get_error_message());
        $data = ['status' => 0, 'msg' => 'An error has occurred.'];
    } else {
        $body = wp_remote_retrieve_body($response);
        $trimmed_body = trim($body);
        $decoded_body = htmlspecialchars_decode($trimmed_body); // Decode HTML entities

        if (strcasecmp($decoded_body, '"User successfully authenticated."') === 0) {
            $data = ['status' => 1, 'msg' => 'User successfully authenticated.'];
        } elseif (strcasecmp($decoded_body, '"User not authenticated."') === 0 || strcasecmp($decoded_body, '"User not found!"') === 0) {
            $data = ['status' => 2, 'msg' => 'User not authenticated.'];
        } else {
            $data = ['status' => 0, 'msg' => 'An error has occurred.'];
        }
    }

    wp_send_json(['output' => $data]);
    die();
}


add_action('wp_ajax_nopriv_gemfindDT_authenticateDealer', 'gemfindDT_authenticateDealer');
add_action('wp_ajax_gemfindDT_authenticateDealer', 'gemfindDT_authenticateDealer');

/**
 * Gets product id from sku of the product.
 */
function gemfindDT_get_product_by_sku($sku)
{
    global $wpdb;
    $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    if ($product_id) {
        return $product_id;
    }

    return null;
}

/*
 * Changing the minimum quantity to 2 for all the WooCommerce products
 */
function gemfindDT_woocommerce_quantity_input_min_callback($min, $product)
{
    $min = 1;

    return $min;
}

add_filter('woocommerce_quantity_input_min', 'gemfindDT_woocommerce_quantity_input_min_callback', 10, 2);
/*
 * Changing the maximum quantity to 5 for all the WooCommerce products
 */
function gemfindDT_woocommerce_quantity_input_max_callback($max, $product)
{
    $max = 1;

    return $max;
}

add_filter('woocommerce_quantity_input_max', 'gemfindDT_woocommerce_quantity_input_max_callback', 10, 2);

function gemfindDT_wc_qty_add_product_field()
{
    echo esc_html('<div class="options_group">');
    woocommerce_wp_text_input([
        'id' => '_wc_min_qty_product',
        'label' => __('Minimum Quantity', 'woocommerce-max-quantity'),
        'placeholder' => '',
        'desc_tip' => 'true',
        'description' => __('Optional. Set a minimum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity'),
    ]);
    echo esc_html('</div>');
    echo esc_html('<div class="options_group">');
    woocommerce_wp_text_input([
        'id' => '_wc_max_qty_product',
        'label' => __('Maximum Quantity', 'woocommerce-max-quantity'),
        'placeholder' => '',
        'desc_tip' => 'true',
        'description' => __('Optional. Set a maximum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity'),
    ]);
    echo esc_html('</div>');
}

add_action('woocommerce_product_options_inventory_product_data', 'gemfindDT_wc_qty_add_product_field');

/*
 * This function will save the value set to Minimum Quantity and Maximum Quantity options
 * into _wc_min_qty_product and _wc_max_qty_product meta keys respectively
 */
function gemfindDT_wc_qty_save_product_field($post_id)
{
    $val_min = trim(get_post_meta($post_id, '_wc_min_qty_product', true));
    $new_min = sanitize_text_field($_POST['_wc_min_qty_product']);
    $val_max = trim(get_post_meta($post_id, '_wc_max_qty_product', true));
    $new_max = sanitize_text_field($_POST['_wc_max_qty_product']);

    if ($val_min != $new_min) {
        update_post_meta($post_id, '_wc_min_qty_product', $new_min);
    }
    if ($val_max != $new_max) {
        update_post_meta($post_id, '_wc_max_qty_product', $new_max);
    }
}

add_action('woocommerce_process_product_meta', 'gemfindDT_wc_qty_save_product_field');

/*
 * Setting minimum and maximum for quantity input args.
 */
function gemfindDT_wc_qty_input_args($args, $product)
{
    $product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();

    $product_min = gemfindDT_wc_get_product_min_limit($product_id);
    $product_max = gemfindDT_wc_get_product_max_limit($product_id);
    if (!empty($product_min)) {
        // min is empty
        if (false !== $product_min) {
            $args['min_value'] = $product_min;
        }
    }
    if (!empty($product_max)) {
        // max is empty
        if (false !== $product_max) {
            $args['max_value'] = $product_max;
        }
    }
    if ($product->managing_stock() && !$product->backorders_allowed()) {
        $stock = $product->get_stock_quantity();
        $args['max_value'] = min($stock, $args['max_value']);
    }

    return $args;
}

add_filter('woocommerce_quantity_input_args', 'gemfindDT_wc_qty_input_args', 10, 2);
function gemfindDT_wc_get_product_max_limit($product_id)
{
    $qty = get_post_meta($product_id, '_wc_max_qty_product', true);
    if (empty($qty)) {
        $limit = false;
    } else {
        $limit = (int) $qty;
    }

    return $limit;
}

function gemfindDT_wc_get_product_min_limit($product_id)
{
    $qty = get_post_meta($product_id, '_wc_min_qty_product', true);
    if (empty($qty)) {
        $limit = false;
    } else {
        $limit = (int) $qty;
    }

    return $limit;
}

/*
 * Validating the quantity on add to cart action with the quantity of the same product available in the cart.
 */
function gemfindDT_wc_qty_add_to_cart_validation($passed, $product_id, $quantity, $variation_id = '', $variations = '')
{
    $product_min = gemfindDT_wc_get_product_min_limit($product_id);
    $product_max = gemfindDT_wc_get_product_max_limit($product_id);
    if (!empty($product_min)) {
        // min is empty
        if (false !== $product_min) {
            $new_min = $product_min;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }
    if (!empty($product_max)) {
        // min is empty
        if (false !== $product_max) {
            $new_max = $product_max;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }
    $already_in_cart = gemfindDT_wc_qty_get_cart_qty($product_id);
    $product = wc_get_product($product_id);
    $product_title = $product->get_title();

    if (!is_null($new_max) && !empty($already_in_cart)) {
        if ($already_in_cart + $quantity > $new_max) {
            // oops. too much.
            $passed = false;
            wc_add_notice(apply_filters('isa_wc_max_qty_error_message_already_had', sprintf(__('You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-max-quantity'), $new_max, $product_title, '<a href="' . esc_url(wc_get_cart_url()) . '">' . __('your cart', 'woocommerce-max-quantity') . '</a>', $already_in_cart), $new_max, $already_in_cart), 'error');
        }
    }

    return $passed;
}

add_filter('woocommerce_add_to_cart_validation', 'gemfindDT_wc_qty_add_to_cart_validation', 1, 5);

/*
 * Get the total quantity of the product available in the cart.
 */
function gemfindDT_wc_qty_get_cart_qty($product_id, $cart_item_key = '')
{
    global $woocommerce;
    $running_qty = 0; // iniializing quantity to 0
    // search the cart for the product in and calculate quantity.
    foreach ($woocommerce->cart->get_cart() as $other_cart_item_keys => $values) {
        if ($product_id == $values['product_id']) {
            if ($cart_item_key == $other_cart_item_keys) {
                continue;
            }
            $running_qty += (int) $values['quantity'];
        }
    }

    return $running_qty;
}

/*
 * Validate product quantity when cart is UPDATED
 */
function gemfindDT_wc_qty_update_cart_validation($passed, $cart_item_key, $values, $quantity)
{
    $product_min = gemfindDT_wc_get_product_min_limit($values['product_id']);
    $product_max = gemfindDT_wc_get_product_max_limit($values['product_id']);
    if (!empty($product_min)) {
        // min is empty
        if (false !== $product_min) {
            $new_min = $product_min;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }
    if (!empty($product_max)) {
        // min is empty
        if (false !== $product_max) {
            $new_max = $product_max;
        } else {
            // neither max is set, so get out
            return $passed;
        }
    }
    $product = wc_get_product($values['product_id']);
    $already_in_cart = gemfindDT_wc_qty_get_cart_qty($values['product_id'], $cart_item_key);
    if ($already_in_cart + $quantity > $new_max) {
        wc_add_notice(apply_filters('wc_qty_error_message', sprintf(__('You can add a maximum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity'), $new_max, $product->get_name(), '<a href="' . esc_url(wc_get_cart_url()) . '">' . __('your cart', 'woocommerce-max-quantity') . '</a>'), $new_max), 'error');
        $passed = false;
    }
    if ($already_in_cart + $quantity < $new_min) {
        wc_add_notice(apply_filters('wc_qty_error_message', sprintf(__('You should have minimum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity'), $new_min, $product->get_name(), '<a href="' . esc_url(wc_get_cart_url()) . '">' . __('your cart', 'woocommerce-max-quantity') . '</a>'), $new_min), 'error');
        $passed = false;
    }

    return $passed;
}

add_filter('woocommerce_update_cart_validation', 'gemfindDT_wc_qty_update_cart_validation', 1, 4);

/*
product tracking script
*/
function gemfindDT_diamondTracking()
{
    $diamond = isset($_POST['diamond_data']);
    $all_options = gemfindDT_getOptions();
    $final_track_url = sanitize_url($_POST['track_url']);
    $diamonddata = json_decode(json_decode(stripslashes($_POST['diamond_data'])), true);

    $RetailerID = $VendorID = $DInventoryID = $URL = $StyleNumber = $DealerStockNumber = $RetailerStockNumber = $Price = $DiamondId = $caratWeight = $cut = $color = $clarity = $depth = $table = $polish = $symmetry = $Girdle = $Culet = $Fluorescence = $Measurements = $Certificate = $CertificateNo = $TableMes = $CutGrade = $SellingPrice = $FltPrice = $UsersIPAddress = '';

    $RetailerID = 'RetailerID=' . (isset($diamonddata['diamondData']['dealerId']) ? sanitize_text_field($diamonddata['diamondData']['dealerId']) . '&' : '&');
    $VendorID = 'VendorID=' . (isset($diamonddata['diamondData']['retailerInfo']['retailerID']) ? sanitize_text_field($diamonddata['diamondData']['retailerInfo']['retailerID']) . '&' : '&');
    $DInventoryID = 'DInventoryID=' . sanitize_text_field($diamonddata['diamondData']['diamondId']) . '&';
    $URL = 'URL=' . urlencode($final_track_url) . '&';
    $DiamondId = 'DiamondID=' . sanitize_text_field($diamonddata['diamondData']['diamondId']) . '&';
    $DealerStockNumber = isset($diamonddata['diamondData']['retailerInfo']['retailerStockNo']) ? sanitize_text_field($diamonddata['diamondData']['retailerInfo']['retailerStockNo']) . '&' : '&';

    $caratWeight = isset($diamonddata['diamondData']['caratWeight']) ? sanitize_text_field($diamonddata['diamondData']['caratWeight']) . '&' : '&';
    $cut = isset($diamonddata['diamondData']['shape']) ? sanitize_text_field($diamonddata['diamondData']['shape']) . '&' : '&';
    $color = isset($diamonddata['diamondData']['color']) ? sanitize_text_field($diamonddata['diamondData']['color']) . '&' : '&';
    $clarity = isset($diamonddata['diamondData']['clarity']) ? sanitize_text_field($diamonddata['diamondData']['clarity']) . '&' : '&';
    $depth = isset($diamonddata['diamondData']['depth']) ? sanitize_text_field($diamonddata['diamondData']['depth']) . '&' : '&';
    $table = isset($diamonddata['diamondData']['table']) ? sanitize_text_field($diamonddata['diamondData']['table']) . '&' : '&';
    $polish = isset($diamonddata['diamondData']['polish']) ? sanitize_text_field($diamonddata['diamondData']['polish']) . '&' : '&';
    $symmetry = isset($diamonddata['diamondData']['symmetry']) ? sanitize_text_field($diamonddata['diamondData']['symmetry']) . '&' : '&';
    $Girdle = isset($diamonddata['diamondData']['gridle']) ? sanitize_text_field($diamonddata['diamondData']['gridle']) . '&' : '&';
    $Culet = isset($diamonddata['diamondData']['culet']) ? sanitize_text_field($diamonddata['diamondData']['culet']) . '&' : '&';
    $Fluorescence = isset($diamonddata['diamondData']['fluorescence']) ? sanitize_text_field($diamonddata['diamondData']['fluorescence']) . '&' : '&';
    $Measurements = isset($diamonddata['diamondData']['measurement']) ? sanitize_text_field($diamonddata['diamondData']['measurement']) . '&' : '&';
    $Certificate = isset($diamonddata['diamondData']['certificate']) ? sanitize_text_field($diamonddata['diamondData']['certificate']) . '&' : '&';
    $CertificateNo = isset($diamonddata['diamondData']['certificateNo']) ? sanitize_text_field($diamonddata['diamondData']['certificateNo']) . '&' : '&';
    $TableMes = isset($diamonddata['diamondData']['table']) ? sanitize_text_field($diamonddata['diamondData']['table']) . '&' : '&';
    $CutGrade = isset($diamonddata['diamondData']['cut']) ? sanitize_text_field($diamonddata['diamondData']['cut']) . '&' : '&';
    $SellingPrice = isset($diamonddata['diamondData']['fltPrice']) ? sanitize_text_field($diamonddata['diamondData']['fltPrice']) . '&' : '&';
    $FltPrice = isset($diamonddata['diamondData']['fltPrice']) ? sanitize_text_field($diamonddata['diamondData']['fltPrice']) . '&' : '&';

    $UsersIPAddress = 'UsersIPAddress=' . gemfindDT_getRealIpAddr();

    $posturl = str_replace(' ', '+', 'https://platform.jewelcloud.com/DiamondTracking.aspx?' . $RetailerID . $VendorID . $DInventoryID . $URL . 'DealerStockNo=' . $DealerStockNumber . 'Carat=' . $caratWeight . 'Cut=' . $cut . 'Color=' . $color . 'Clarity=' . $clarity . 'Depth=' . $depth . 'Polish=' . $polish . 'Symmetry=' . $symmetry . 'FltPrice=' . $FltPrice . 'SellingPrice=' . $SellingPrice . 'Girdle=' . $Girdle . 'Culet=' . $Culet . 'Fluorescence=' . $Fluorescence . 'Measurements=' . $Measurements . 'Certificate=' . $Certificate . 'CertificateNo=' . $CertificateNo . 'TableMes=' . $TableMes . 'CutGrade=' . $CutGrade . $UsersIPAddress);

    $response = wp_remote_get($posturl);

    if (is_wp_error($response)) {
        esc_html_e('error', 'gemfind-diamond-tool');
    } else {
        esc_html_e('tracked', 'gemfind-diamond-tool');
    }
}

add_action('wp_ajax_nopriv_gemfindDT_diamondTracking', 'gemfindDT_diamondTracking');
add_action('wp_ajax_gemfindDT_diamondTracking', 'gemfindDT_diamondTracking');

function gemfindDT_getRealIpAddr()
{
    if (!empty(sanitize_url($_SERVER['HTTP_CLIENT_IP']))) {
        //check ip from share internet
        $ip = sanitize_url($_SERVER['HTTP_CLIENT_IP']);
    } elseif (!empty(sanitize_url($_SERVER['HTTP_X_FORWARDED_FOR']))) {
        //to check ip is pass from proxy
        $ip = sanitize_url($_SERVER['HTTP_X_FORWARDED_FOR']);
    } else {
        $ip = sanitize_url($_SERVER['REMOTE_ADDR']);
    }

    return $ip;
}

include 'emails/email_friend_email_template_receiver.php';
include 'emails/email_friend_email_template_sender.php';
include 'emails/email_friend_email_template_retailer.php';
include 'emails/hint_email_template_receiver.php';
include 'emails/hint_email_template_retailer.php';
include 'emails/hint_email_template_sender.php';
include 'emails/info_email_template_retailer.php';
include 'emails/info_email_template_sender.php';
include 'emails/schedule_view_email_template_admin.php';
include 'emails/schedule_view_email_template_user.php';
