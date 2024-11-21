<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly
/*
  Plugin Name: Gemfind Diamond Link
  Plugin URI:  https://www.gemfind.com/
  Description: Plugin for listing and filtering out diamonds on the basis of third pary APIs
  Version:     2.5.0
  Author:      Gemfind
  Author URI:  https://www.gemfind.com/
  Text Domain: gemfind-diamond-tool
  Domain Path: /languages
  License:     GPL2
*/
global $wpdb;
define('TBL_DIAMOND_LINK', 'diamond_link_config');
define('DIAMOND_LINK_Path', plugin_dir_path(__FILE__));
define('DIAMOND_LINK_URL', plugin_dir_url(__FILE__));

function gemfindDT_required_plugin()
{
    if (is_admin() && current_user_can('activate_plugins')) {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('admin_notices', 'gemfindDT_required_woocommerce_notice');

            deactivate_plugins(plugin_basename(__FILE__));

            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        } else {
        }
    }
}

add_action('admin_init', 'gemfindDT_required_plugin');

function gemfindDT_required_woocommerce_notice()
{
    esc_html_e('<div class="error"><p><strong>Gemfind Diamond Link is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for Gemfind Diamond Link to work. Please install & activate WooCommerce</a></p></div>', 'diamondlink');
}

register_activation_hook(__FILE__, 'gemfindDT_install');
function gemfindDT_install()
{
    $data = [
        'dealerid' => '',
        'from_email_address' => '',
        'admin_email_address' => '',
        'dealerauthapi' => 'http://api.jewelcloud.com/api/RingBuilder/AccountAuthentication',
        'navigationapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetNavigation?',
        'filterapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondFilter?',
        'filterapifancy' => 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamondFilter?',
        'diamondlistapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamond?',
        'diamondlistapifancy' => 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamond?',
        'diamondshapeapi' => 'http://api.jewelcloud.com/api/ringbuilder/GetShapeByColorFilter?',
        'diamonddetailapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondDetail?',
        'stylesettingapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?',
        'diamondsoptionapi' => 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondsJCOptions?',
        'diamondsinitialfilter' => 'http://api.jewelcloud.com/api/RingBuilder/GetInitialFilter?',
        'enable_hint' => 'true',
        'enable_email_friend' => 'true',
        'enable_schedule_viewing' => 'true',
        'enable_more_info' => 'true',
        'enable_print' => 'true',
        'enable_admin_notify' => 'true',
        'default_view' => 'list',
        'show_hints_popup' => 'Yes',
        'show_copyright' => 'Yes',
        'enable_sticky_header' => 'Yes',
        'enable_price_users' => 'Yes',
        'load_from_woocommerce' => 0,
    ];

    $gemfindDT_diamond_link = get_option('gemfindDT_diamond_link');
    if (empty($gemfindDT_diamond_link)) {
        update_option('gemfindDT_diamond_link', $data);
    }
    $id = wp_insert_post([
        'post_title' => 'Diamond Link',
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_name' => 'diamondlink',
    ]);
    update_option('diamondlink_dl_page_id', $id);

    add_rewrite_rule('diamondlink/([^&]+)', 'index.php?pagename=diamondlink&compare=$matches[1]', 'top');
    flush_rewrite_rules();
}

include 'gemfind-template-assign.php';
add_action('admin_init', 'gemfindDT_create_product_meta_fields');
function gemfindDT_create_product_meta_fields()
{
    // Display Fields
    add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');

    // Save Fields
    add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');

    $the_slug = 'diamondlink';
    $args = [
        'name' => $the_slug,
        'post_type' => 'page',
        'post_status' => 'publish',
        'numberposts' => 1,
    ];
    $my_posts = get_posts($args);
    if ($my_posts) :
        // Update post diamondlink
        $my_post = [
            'ID' => $my_posts[0]->ID,
            'page_template' => 'template-diamondlist.php',
        ];

        // Update the post into the database
        wp_update_post($my_post);
    endif;
}

add_filter('wc_get_price_decimals', 'gemfindDT_change_prices_decimals', 20, 1);
function gemfindDT_change_prices_decimals($decimals)
{
    if (is_cart() || is_checkout()) {
        $decimals = 2;
    }

    return $decimals;
}

// function gemfindDT_get_attribute_id_from_name($name)
// {
//     global $wpdb;
//     $attribute_id = $wpdb->get_var("SELECT attribute_id
//   FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
//   WHERE attribute_name LIKE '$name'");

//     return $attribute_id;
// }

function gemfindDT_get_attribute_id_from_name($name)
{
    global $wpdb;

    $attribute_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT attribute_id
            FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
            WHERE attribute_name LIKE %s",
            $name
        )
    );

    return $attribute_id;
}


/**
 * For creating WooCommerce product custom fields.
 */
function gemfindDT_woocommerce_product_custom_fields()
{
    global $woocommerce, $post;
    echo esc_html('<div class="product_custom_field">');
    // Price Per Carat Field
    woocommerce_wp_text_input([
        'id' => 'costPerCarat',
        'placeholder' => 'Price Per Carat',
        'label' => __('Price Per Carat', 'woocommerce'),
        'desc_tip' => 'true',
    ]);

    //Diamond Image 1
    woocommerce_wp_text_input([
        'id' => 'image1',
        'placeholder' => 'Diamond Image1',
        'label' => __('Diamond Image1', 'woocommerce'),
    ]);

    //Diamond Image 2
    woocommerce_wp_text_input([
        'id' => 'image2',
        'placeholder' => 'Diamond Image2',
        'label' => __('Diamond Image2', 'woocommerce'),
    ]);

    //Video Filename Field
    woocommerce_wp_text_input([
        'id' => 'videoFileName',
        'placeholder' => 'Video File Name',
        'label' => __('Video File Name', 'woocommerce'),
    ]);

    //Certificate Number Field
    woocommerce_wp_text_input([
        'id' => 'certificateNo',
        'placeholder' => 'Certificate Number',
        'label' => __('Certificate Number', 'woocommerce'),
    ]);

    //Certificate URL Field
    woocommerce_wp_text_input([
        'id' => 'certificateUrl',
        'placeholder' => 'Certificate URL',
        'label' => __('Certificate URL', 'woocommerce'),
    ]);

    //Certificate icon URL Field
    woocommerce_wp_text_input([
        'id' => 'certificateIconUrl',
        'placeholder' => 'Certificate icon URL',
        'label' => __('Certificate icon URL', 'woocommerce'),
    ]);

    //Carat Field
    woocommerce_wp_text_input([
        'id' => 'caratWeight',
        'placeholder' => 'Carat Standard',
        'label' => __('Carat Standard', 'woocommerce'),
    ]);

    //Depth Field
    woocommerce_wp_text_input([
        'id' => 'depth',
        'placeholder' => 'Depth Standard',
        'label' => __('Depth Standard', 'woocommerce'),
    ]);

    //Table Field
    woocommerce_wp_text_input([
        'id' => 'table',
        'placeholder' => 'Table Standard',
        'label' => __('Table Standard', 'woocommerce'),
    ]);

    //Measurement Field
    woocommerce_wp_text_input([
        'id' => 'measurement',
        'placeholder' => 'Measurement',
        'label' => __('Measurement', 'woocommerce'),
    ]);

    //Origin Field
    woocommerce_wp_text_input([
        'id' => 'origin',
        'placeholder' => 'Origin',
        'label' => __('Origin', 'woocommerce'),
    ]);

    //Gridle Field
    woocommerce_wp_text_input([
        'id' => 'gridle',
        'placeholder' => 'Gridle',
        'label' => __('Gridle', 'woocommerce'),
    ]);

    //Culet Field
    woocommerce_wp_text_input([
        'id' => 'culet',
        'placeholder' => 'Culet',
        'label' => __('Culet', 'woocommerce'),
    ]);

    //Cut Field
    woocommerce_wp_text_input([
        'id' => 'cut',
        'placeholder' => 'Cut',
        'label' => __('Cut', 'woocommerce'),
    ]);

    //Internal Use Link Field
    woocommerce_wp_text_input([
        'id' => 'internalUselink',
        'placeholder' => 'Internal Use link',
        'label' => __('Internal Use link', 'woocommerce'),
    ]);

    //Gridle Field
    woocommerce_wp_text_input([
        'id' => 'caratWeightFancy',
        'placeholder' => 'Carat Fancy',
        'label' => __('Carat Fancy', 'woocommerce'),
    ]);
    //Gridle Field
    woocommerce_wp_text_input([
        'id' => 'depthFancy',
        'placeholder' => 'Depth Fancy',
        'label' => __('Depth Fancy', 'woocommerce'),
    ]);
    //Gridle Field
    woocommerce_wp_text_input([
        'id' => 'tableFancy',
        'placeholder' => 'Table Fancy',
        'label' => __('Table Fancy', 'woocommerce'),
    ]);
    //Gridle Field
    woocommerce_wp_text_input([
        'id' => 'productType',
        'placeholder' => 'Product Type',
        'label' => __('Product Type', 'woocommerce'),
    ]);

    echo esc_html('</div>');
}

/**
 * For saving WooCommerce custom fields created.
 */
function gemfindDT_woocommerce_product_custom_fields_save($post_id)
{
    // Cost Per Carat Text Field
    $woocommerce_costPerCarat = sanitize_text_field($_POST['costPerCarat']);
    if (!empty($woocommerce_costPerCarat)) {
        update_post_meta($post_id, 'costPerCarat', esc_attr($woocommerce_costPerCarat));
    }

    // Diamond Image1 Field
    $woocommerce_image1 = sanitize_url($_POST['image1']);
    if (!empty($woocommerce_image1)) {
        update_post_meta($post_id, 'image1', esc_attr($woocommerce_image1));
    }

    // Diamond Image2 Field
    $woocommerce_image2 = sanitize_url($_POST['image2']);
    if (!empty($woocommerce_image2)) {
        update_post_meta($post_id, 'image2', esc_attr($woocommerce_image2));
    }

    // Video File Name Field
    $woocommerce_videoFileName = sanitize_url($_POST['videoFileName']);
    if (!empty($woocommerce_videoFileName)) {
        update_post_meta($post_id, 'videoFileName', esc_attr($woocommerce_videoFileName));
    }

    // Certificate No Field
    $woocommerce_certificateNo = sanitize_text_field($_POST['certificateNo']);
    if (!empty($woocommerce_certificateNo)) {
        update_post_meta($post_id, 'certificateNo', esc_attr($woocommerce_certificateNo));
    }

    // Certificate URL Field
    $woocommerce_certificateUrl = sanitize_url($_POST['certificateUrl']);
    if (!empty($woocommerce_certificateUrl)) {
        update_post_meta($post_id, 'certificateUrl', esc_attr($woocommerce_certificateUrl));
    }

    // Certificate icon URL Field
    $woocommerce_certificateIconUrl = sanitize_url($_POST['certificateIconUrl']);
    if (!empty($woocommerce_certificateIconUrl)) {
        update_post_meta($post_id, 'certificateIconUrl', esc_attr($woocommerce_certificateIconUrl));
    }

    // Carat Field
    $woocommerce_caratWeight = sanitize_text_field($_POST['caratWeight']);
    if (!empty($woocommerce_caratWeight)) {
        update_post_meta($post_id, 'caratWeight', esc_attr($woocommerce_caratWeight));
    }

    // Depth Field
    $woocommerce_depth = sanitize_text_field($_POST['depth']);
    if (!empty($woocommerce_depth)) {
        update_post_meta($post_id, 'depth', esc_attr($woocommerce_depth));
    }

    // Table Field
    $woocommerce_table = sanitize_text_field($_POST['table']);
    if (!empty($woocommerce_table)) {
        update_post_meta($post_id, 'table', esc_attr($woocommerce_table));
    }

    // Measurement Field
    $woocommerce_measurement = sanitize_text_field($_POST['measurement']);
    if (!empty($woocommerce_measurement)) {
        update_post_meta($post_id, 'measurement', esc_attr($woocommerce_measurement));
    }

    // Origin Field
    $woocommerce_origin = sanitize_text_field($_POST['origin']);
    if (!empty($woocommerce_origin)) {
        update_post_meta($post_id, 'origin', esc_attr($woocommerce_origin));
    }

    // Gridle Field
    $woocommerce_gridle = sanitize_text_field($_POST['gridle']);
    if (!empty($woocommerce_gridle)) {
        update_post_meta($post_id, 'gridle', esc_attr($woocommerce_gridle));
    }

    // Culet Field
    $woocommerce_culet = sanitize_text_field($_POST['culet']);
    if (!empty($woocommerce_culet)) {
        update_post_meta($post_id, 'culet', esc_attr($woocommerce_culet));
    }

    // Cut Field
    $woocommerce_cut = sanitize_text_field($_POST['cut']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'cut', esc_attr($woocommerce_cut));
    }

    // Cut Field
    $woocommerce_cut = sanitize_url($_POST['internalUselink']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'internalUselink', esc_attr($woocommerce_cut));
    }
    // Cut Field
    $woocommerce_cut = sanitize_text_field($_POST['caratWeightFancy']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'caratWeightFancy', esc_attr($woocommerce_cut));
    }
    // Cut Field
    $woocommerce_cut = sanitize_text_field($_POST['depthFancy']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'depthFancy', esc_attr($woocommerce_cut));
    }
    // Cut Field
    $woocommerce_cut = sanitize_text_field($_POST['tableFancy']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'tableFancy', esc_attr($woocommerce_cut));
    }
    // Cut Field
    $woocommerce_cut = sanitize_text_field($_POST['productType']);
    if (!empty($woocommerce_cut)) {
        update_post_meta($post_id, 'productType', esc_attr($woocommerce_cut));
    }
}

/*
 * Plugin Uninstall Hook
 */
register_deactivation_hook(__FILE__, 'gemfindDT_uninstall');

// function gemfindDT_uninstall()
// {
//     global $wpdb;
//     $sql = 'DROP TABLE IF EXISTS ' . TBL_DIAMOND_LINK;
//     $wpdb->query($sql);
//     $id = get_option('diamondlink_dl_page_id');
//     wp_delete_post($id, true);
// }

function gemfindDT_uninstall()
{
    global $wpdb;

    $table_name = TBL_DIAMOND_LINK; // Assuming TBL_DIAMOND_LINK is the correct constant

    $sql = $wpdb->prepare('DROP TABLE IF EXISTS %s', $table_name);
    $wpdb->query($sql);

    $id = get_option('diamondlink_dl_page_id');
    wp_delete_post($id, true);
}


register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

/*
 * Admin Menu Pages
 */

add_action('admin_menu', 'gemfindDT_menu_page');

function gemfindDT_menu_page()
{
    add_menu_page('Diamond Search', 'GemFind Diamond Link', 'manage_options', 'gemfindDT_diamond_link', 'gemfindDT_diamond_link', 'dashicons-admin-generic', 90);
}

function gemfindDT_diamond_link()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    // echo DIAMOND_LINK_Path;
    // exit();
    include_once DIAMOND_LINK_Path . 'admin/add_gemfind_diamond_link.php';
}

/*add_action( 'admin_enqueue_scripts', 'gemfindDT_enqueue_admin_scripts' );

function gemfindDT_enqueue_admin_scripts() {
 wp_enqueue_style( 'gemfind-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '1.0.1', false );
}*/

/**
 * Enqueues necessary scripts and styes for this plugin's front end section
 */
function gemfindDT_enqueue_scripts()
{
    $parent = wp_get_post_parent_id(get_the_ID());

    if (is_page('diamondlink') && $parent == 0) {

        wp_enqueue_style('gemfind-bootstrap', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', [], '3.3.5', false);
        wp_enqueue_style('gemfind-font-awesome', plugin_dir_url(__FILE__) . 'assets/css/font-awesome.min.css', [], '4.4.0', false);
        wp_enqueue_style('gemfind-jquery-ui-css', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui.css', [], '1.12.1', false);
        wp_enqueue_style('gemfind-custom', plugin_dir_url(__FILE__) . 'assets/css/custom.css', [], time(), false);
        wp_enqueue_style('gemfind-diamondlist-head', plugin_dir_url(__FILE__) . 'assets/css/diamond_list_head.css', [], time(), false);
        wp_enqueue_script('gemfind-cookie', plugin_dir_url(__FILE__) . 'assets/js/jquery.cookie.js', ['jquery-core'], '1.4.1', false);
        wp_enqueue_script('gemfind-jquery-ui', plugin_dir_url(__FILE__) . 'assets/js/jquery-ui.min.js', ['jquery-core'], '1.12.1', false);
        wp_enqueue_script('gemfind-bootstrap-js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', ['jquery-core'], '3.3.5', false);

        wp_enqueue_script('gemfind-sumoselect-js', plugin_dir_url(__FILE__) . 'assets/js/jquery.sumoselect.js', ['jquery-core'], '1.0.1', false);
        wp_enqueue_style('gemfind-sumoselect', plugin_dir_url(__FILE__) . 'assets/css/sumoselect.css', [], '1.0.1', false);


        /* noUiSlider include */
        wp_enqueue_script('gemfind-jquery-wNumb', plugin_dir_url(__FILE__) . 'assets/js/wNumb.js', ['jquery-core'], '1.2.0', false);
        wp_enqueue_script('gemfind-jquery-nouislider', plugin_dir_url(__FILE__) . 'assets/js/nouislider.js', ['jquery-core'], '10.0.0', false);
        wp_enqueue_style('gemfind-jquery-noUiSlider', plugin_dir_url(__FILE__) . 'assets/css/nouislider.css', [], '10.0.0', false);
        /* end here noUiSlider include */

        if (strpos($path, 'ringbuilder/diamondlink') == false) {
            wp_enqueue_script('gemfind-main', plugin_dir_url(__FILE__) . 'assets/js/main.js', [], time(), true);
        }

        wp_enqueue_script('gemfind-jquery-vaildate', plugin_dir_url(__FILE__) . 'assets/js/jquery.validate.min.js', [], time(), true);

        $uri = sanitize_url($_SERVER['REQUEST_URI']);
        $path = parse_url($uri, PHP_URL_PATH);
        $filename = pathinfo($path, PATHINFO_FILENAME);

        if ($filename != 'diamondlink') {
            wp_enqueue_script('gemfind-view', plugin_dir_url(__FILE__) . 'assets/js/view.js', [], time(), true);

            wp_enqueue_script(
                'print-area-script',
                plugin_dir_url(__FILE__) . 'assets/js/jquery.PrintArea.js',
                array('jquery'),
                '1.0.0',
            );

            // Set additional attributes
            wp_script_add_data('print-area-script', 'nomodule', true);

            wp_enqueue_script('my-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/api.js', array('jquery'), '1.0', true);

            wp_script_add_data('my-plugin-script', 'nomodule', true);

            // Pass data to JavaScript file
            $data_to_pass = array(
                'plugin_path' => plugin_dir_url(__FILE__)
            );
            wp_localize_script('my-plugin-script', 'my_plugin_data', $data_to_pass);
        }
        if ($filename != 'compare' && strpos($path, 'ringbuilder/diamondlink') == false) {
            wp_enqueue_script('gemfind-list-ajax', plugin_dir_url(__FILE__) . 'assets/js/list.js', [], time(), true);
        }
        wp_localize_script('gemfind-list-ajax', 'myajax', ['ajaxurl' => admin_url('admin-ajax.php')]);

        // Enqueue your script


        // Enqueue your script  

        // wp_get_script_tag(
        //     array(
        //         'src'      => plugin_dir_url(__FILE__) . 'assets/js/jquery.PrintArea.js',
        //         'nomodule' => true,
        //     )
        // );


    }
}

add_action('wp_enqueue_scripts', 'gemfindDT_enqueue_scripts');

// function gemfindDT_enqueue_scripts()
// {
//     $parent = wp_get_post_parent_id(get_the_ID());

//     if (is_page('diamondlink') && $parent == 0) {
//         // Enqueue Styles
//         wp_enqueue_style('gemfind-bootstrap', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', [], '3.3.5');
//         wp_enqueue_style('gemfind-font-awesome', plugin_dir_url(__FILE__) . 'assets/css/font-awesome.min.css', [], '4.4.0');
//         wp_enqueue_style('gemfind-jquery-ui', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui.css', [], '1.12.1');
//         wp_enqueue_style('gemfind-custom', plugin_dir_url(__FILE__) . 'assets/css/custom.css', [], time());
//         wp_enqueue_style('gemfind-diamondlist-head', plugin_dir_url(__FILE__) . 'assets/css/diamond_list_head.css', [], time());
//         wp_enqueue_style('gemfind-sumoselect', plugin_dir_url(__FILE__) . 'assets/css/sumoselect.css', [], '1.0.1');
//         wp_enqueue_style('gemfind-jquery-noUiSlider', plugin_dir_url(__FILE__) . 'assets/css/nouislider.css', [], '10.0.0');

//         // Enqueue Scripts
//         wp_enqueue_script('jquery-cookie', plugin_dir_url(__FILE__) . 'assets/js/jquery.cookie.js', ['jquery-core'], '1.4.1', true);
//         wp_enqueue_script('jquery-ui-core');
//         wp_enqueue_script('jquery-ui-slider');
//         wp_enqueue_script('gemfind-jquery-ui', plugin_dir_url(__FILE__) . 'assets/js/jquery-ui.min.js', ['jquery-core'], '1.12.1', false);
//         wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', ['jquery-core'], '3.3.5', true);
//         wp_enqueue_script('sumoselect-js', plugin_dir_url(__FILE__) . 'assets/js/jquery.sumoselect.js', ['jquery-core'], '1.0.1', true);
//         wp_enqueue_script('jquery-wNumb', plugin_dir_url(__FILE__) . 'assets/js/wNumb.js', ['jquery-core'], '1.2.0', true);
//         wp_enqueue_script('jquery-nouislider', plugin_dir_url(__FILE__) . 'assets/js/nouislider.js', ['jquery-core'], '10.0.0', true);



//         if (strpos($path, 'ringbuilder/diamondlink') == false) {
//             wp_enqueue_script('gemfind-main', plugin_dir_url(__FILE__) . 'assets/js/main.js', [], time(), true);
//         }

//         wp_enqueue_script('jquery-validate', plugin_dir_url(__FILE__) . 'assets/js/jquery.validate.min.js', [], time(), true);

//         $uri = sanitize_url($_SERVER['REQUEST_URI']);
//         $path = parse_url($uri, PHP_URL_PATH);
//         $filename = pathinfo($path, PATHINFO_FILENAME);

//         if ($filename != 'diamondlink') {
//             wp_enqueue_script('gemfind-view', plugin_dir_url(__FILE__) . 'assets/js/view.js', [], time(), true);
//         }

//         if ($filename != 'compare' && strpos($path, 'ringbuilder/diamondlink') == false) {
//             wp_enqueue_script('gemfind-list-ajax', plugin_dir_url(__FILE__) . 'assets/js/list.js', [], time(), true);
//         }

//         // Localize scripts
//         wp_localize_script('gemfind-main', 'myajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
//         wp_localize_script('gemfind-list-ajax', 'myajax', ['ajaxurl' => admin_url('admin-ajax.php')]);

//         // Enqueue your script
//         wp_enqueue_script('my-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/api.js', ['jquery'], '1.0', true);

//         // Pass data to JavaScript file
//         $data_to_pass = array(
//             'plugin_path' => plugin_dir_url(__FILE__)
//         );
//         wp_localize_script('my-plugin-script', 'my_plugin_data', $data_to_pass);
//     }
// }

// add_action('wp_enqueue_scripts', 'gemfindDT_enqueue_scripts');

/**
 * Dequeue the twentynineteen theme style.
 *
 * Hooked to the wp_print_styles action, with a late priority (100),
 * so that it is after the style was enqueued.
 */
function gemfindDT_wp_67472455()
{
    if (function_exists('is_woocommerce') && !is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
        wp_dequeue_style('twentynineteen-style');
    }
}

add_action('wp_print_styles', 'gemfindDT_wp_67472455', 100);
/**
 * For adding tag to the url.
 */
add_action('init', 'gemfindDT_wpse26388_rewrites_init');
function gemfindDT_wpse26388_rewrites_init()
{
    add_rewrite_rule('diamondlink/([^&]+)', 'index.php?pagename=diamondlink&compare=$matches[1]', 'top');
}

/**
 * For defining added query tag as part of query_vars
 */
add_filter('query_vars', 'gemfindDT_wpse26388_query_vars');
function gemfindDT_wpse26388_query_vars($query_vars)
{
    $query_vars[] = 'compare';

    return $query_vars;
}

/**
 * For redirecting pretty permalink created to required templates.
 */
function gemfindDT_prefix_url_rewrite_templates()
{
    global $wp_query;
    // $check_labcreated = rtrim( get_query_var( 'compare' ), '/' );
    // $check_labcreated = explode( '/', $check_labcreated );
    // if( end( $check_labcreated ) == 'labcreated' ) {
    // 	add_filter( 'template_include', function () {
    // 		return plugin_dir_path( __FILE__ ) . 'template-diamondlist.php';
    // 	} );
    // }
    if (get_query_var('compare') && get_query_var('compare') == 'navfancycolored') {
        add_filter('template_include', function () {
            return plugin_dir_path(__FILE__) . 'template-diamondlist.php';
        });
    } elseif (get_query_var('compare') && get_query_var('compare') == 'navstandard') {
        add_filter('template_include', function () {
            return plugin_dir_path(__FILE__) . 'template-diamondlist.php';
        });
    } elseif (get_query_var('compare') && get_query_var('compare') == 'navlabgrown') {
        add_filter('template_include', function () {
            return plugin_dir_path(__FILE__) . 'template-diamondlist.php';
        });
    } elseif (get_query_var('compare') && get_query_var('compare') != 'compare') {
        add_filter('template_include', function () {
            return plugin_dir_path(__FILE__) . 'view_diamonds.php';
        });
    } elseif (get_query_var('compare')) {
        add_filter('template_include', function () {
            return plugin_dir_path(__FILE__) . 'compare_diamond.php';
        });
    }
}

add_action('template_redirect', 'gemfindDT_prefix_url_rewrite_templates');
include_once 'includes-api-functions.php'; // Includes all api call functions.

add_action('init', 'gemfindDT_disable_cart_link');
function gemfindDT_disable_cart_link()
{
    if (!is_admin()) {
        function gemfindDT_wc_cart_item_name_hyperlink($link_text, $product_data)
        {
            $title = get_the_title($product_data['product_id']);
            $product_type = get_post_meta($product_data['product_id'], 'product_type', true);
            if ($product_type == 'gemfind') {
                return sprintf('%s', $title);
            } else {
                return $link_text;
            }
        }

        /* Filter to override cart_item_name */
        add_filter('woocommerce_cart_item_name', 'gemfindDT_wc_cart_item_name_hyperlink', 10, 2);
    }
}

add_action('wp_head', 'gemfindDT_get_my_custom_styles', 100);
function gemfindDT_get_my_custom_styles()
{
    echo esc_html('<style>.woocommerce-cart-form__cart-item.cart_item .product-thumbnail{pointer-events:none;}</style>');

    //Added code for update API url
    $gemfindDT_diamond_link = get_option('gemfindDT_diamond_link');
    if (empty(trim($gemfindDT_diamond_link['dealerid']))) {
        $gemfindDT_diamond_link['dealerid'] = '1089';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['dealerauthapi']))) {
        $gemfindDT_diamond_link['dealerauthapi'] = 'http://api.jewelcloud.com/api/RingBuilder/AccountAuthentication';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['navigationapi']))) {
        $gemfindDT_diamond_link['navigationapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetNavigation?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['filterapi']))) {
        $gemfindDT_diamond_link['filterapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondFilter?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['filterapifancy']))) {
        $gemfindDT_diamond_link['filterapifancy'] = 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamondFilter?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamondlistapi']))) {
        $gemfindDT_diamond_link['diamondlistapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetDiamond?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamondlistapifancy']))) {
        $gemfindDT_diamond_link['diamondlistapifancy'] = 'http://api.jewelcloud.com/api/RingBuilder/GetColorDiamond?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamondshapeapi']))) {
        $gemfindDT_diamond_link['diamondshapeapi'] = 'http://api.jewelcloud.com/api/ringbuilder/GetShapeByColorFilter?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamonddetailapi']))) {
        $gemfindDT_diamond_link['diamonddetailapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondDetail?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['stylesettingapi']))) {
        $gemfindDT_diamond_link['stylesettingapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetStyleSetting?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamondsoptionapi']))) {
        $gemfindDT_diamond_link['diamondsoptionapi'] = 'http://api.jewelcloud.com/api/RingBuilder/GetDiamondsJCOptions?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
    if (empty(trim($gemfindDT_diamond_link['diamondsinitialfilter']))) {
        $gemfindDT_diamond_link['diamondsinitialfilter'] = 'http://api.jewelcloud.com/api/RingBuilder/GetInitialFilter?';
        update_option('gemfindDT_diamond_link', $gemfindDT_diamond_link);
    }
}
