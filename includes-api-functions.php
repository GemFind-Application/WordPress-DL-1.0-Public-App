<?php

if (!defined('ABSPATH')) {
	exit();
} // Exit if accessed directly
/**
 * Common function for api calls based on method GET.
 */



if (!function_exists('gemfindDT_getCurlData')) {
	function gemfindDT_getCurlData($url, $headers)
	{
		$request_args = array(
			'headers' => $headers,
			'timeout' => 30, // Adjust the timeout as needed
		);

		$response = wp_remote_get($url, $request_args);

		if (is_wp_error($response)) {
			return null; // Handle the error case, e.g., return an appropriate value or log an error
		}

		$body = wp_remote_retrieve_body($response);
		return json_decode($body);
	}
}
/**
 * Common function for api calls based on method POST.
 */
if (!function_exists('gemfindDT_postCurlData')) {
	function gemfindDT_postCurlData($url, $headers, $data, $method)
	{
		$request_args = array(
			'headers' => $headers,
			'body' => $data,
			'timeout' => 100, // Adjust the timeout as needed
			'method' => 'POST',
		);

		$response = wp_safe_remote_request($url, $request_args);

		if (is_wp_error($response)) {
			return null; // Handle the error case, e.g., return an appropriate value or log an error
		}

		$body = wp_remote_retrieve_body($response);
		return json_decode($body);
	}
}


function gemfindDT_removeDiamond()
{
	$removeId = sanitize_text_field($_POST['selectedcheckboxid']);
	$cookiseCompare = sanitize_text_field($_COOKIE['comparediamondProduct']);
	$data = json_decode(stripslashes(sanitize_text_field($_COOKIE['comparediamondProduct'])), 1);
	$key = array_search($removeId, array_column($data, 'ID'));
	unset($data[$key]);
	$updatedkey = array_values($data);
	setcookie('comparediamondProduct', json_encode($updatedkey, true), time() + (86400 * 30), "/");
	$cookiseCompare = json_decode(stripslashes(sanitize_text_field($_COOKIE['comparediamondProduct'])), 1);
	exit;
}
add_action('wp_ajax_nopriv_gemfindDT_removeDiamond', 'gemfindDT_removeDiamond');
add_action('wp_ajax_gemfindDT_removeDiamond', 'gemfindDT_removeDiamond');


add_action('wp_ajax_nopriv_gemfindDT_getDiamondFilters', 'gemfindDT_getDiamondFilters');
add_action('wp_ajax_gemfindDT_getDiamondFilters', 'gemfindDT_getDiamondFilters');

function gemfindDT_getDiamondFilters()
{
	global $wpdb;
	$options              = gemfindDT_getOptions();
	$dealerID             = $options['dealerid'];
	$filterapi            = $options['filterapi'];
	$default_shape_filter = sanitize_text_field($_POST['default_shape_filter']);
	$saveinitialvalue 	  = sanitize_text_field($_POST['saveinitialvalue']);
	$alldata = get_option('gemfind_diamond_link');
	$diamondsoption    	   = gemfindDT_sendRequest(array('diamondsoptionapi' 	 => $alldata['diamondsoptionapi']));
	$diamondsinitialfilter = gemfindDT_sendRequest(array('diamondsinitialfilter' => $alldata['diamondsinitialfilter']));
	if (sanitize_text_field($_POST['filter_type']) == 'navstandard') {
		$save_filter_cookie_data    = json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savefiltercookie'])), 1);
		$back_cookie_data           = json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvalue'])), 1);
		$saveinitialvalue           = json_decode(stripslashes(sanitize_text_field($_POST['saveinitialvalue'])), 1);
		$intitialfiltercookie 		= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_intitialfiltercookie'])), 1);
		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	} elseif ($_POST['filter_type'] == 'navlabgrown') {
		$save_filter_cookie_data 	= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savefiltercookielabgrown'])), 1);
		$back_cookie_data        	= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvaluelabgrown'])), 1);
		$saveinitialvalue           = json_decode(stripslashes(sanitize_text_field($_POST['saveinitialvalue'])), 1);
		$intitialfiltercookie 		= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_intitialfiltercookielabgrown'])), 1);
		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	} elseif (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
		$save_filter_cookie_data 	= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savefiltercookiefancy'])), 1);
		$back_cookie_data        	= json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvaluefancy'])), 1);
		$saveinitialvalue           = json_decode(stripslashes(sanitize_text_field($_POST['saveinitialvalue'])), 1);
		if ($save_filter_cookie_data) {
			$savedfilter = (object) $save_filter_cookie_data;
		} elseif ($back_cookie_data) {
			$savedfilter = (object) $back_cookie_data;
		} elseif ($saveinitialvalue) {
			$savedfilter = (object) $saveinitialvalue;
		} else {
			$savedfilter = "";
		}
	}

	$shapeArray = $symmArray = $polishArray = $fluorArray = $cutArray = $clarityArray = $colorArray = $certiArray = $fancycolorArray = $intintensityArray = $overtoneArray = array();
	if (!empty($saveinitialvalue) && !empty($intitialfiltercookie)) {
		$allshapes = $diamondsinitialfilter[1][0]->shapes;
		$shapeArray = array_column($allshapes, 'shapeName');
		$shapeArray = array_map('gemfindDT_nestedLowercase', $shapeArray);

		$allcuts = $diamondsinitialfilter[1][0]->cutRange;
		$cutArray = array_column($allcuts, 'cutId');

		$allcolors = $diamondsinitialfilter[1][0]->colorRange;
		$colorArray = array_column($allcolors, 'colorId');

		$allclarity = $diamondsinitialfilter[1][0]->clarityRange;
		$clarityArray = array_column($allclarity, 'clarityId');

		$allcarat = $diamondsinitialfilter[1][0]->caratRange;
		if (!empty($allcarat)) {
			$savedfilter->caratMin = floor(array_column($allcarat, 'minCarat')[0]);
			$savedfilter->caratMax = floor(array_column($allcarat, 'maxCarat')[0]);
		}

		$allprice  = $diamondsinitialfilter[1][0]->priceRange;
		if (!empty(floor(array_column($allprice, 'maxPrice')[0]))) {
			$savedfilter->PriceMin = floor(array_column($allprice, 'minPrice')[0]);
			$savedfilter->PriceMax = floor(array_column($allprice, 'maxPrice')[0]);
		}

		$allpolish = $diamondsinitialfilter[1][0]->polishRange;
		$polishArray = array_column($allpolish, 'polishId');

		$allsymmetry = $diamondsinitialfilter[1][0]->symmetryRange;
		$symmArray = array_column($allsymmetry, 'symmetryId');

		$allfluor = $diamondsinitialfilter[1][0]->fluorescenceRange;
		$fluorArray = array_column($allfluor, 'fluorescenceId');

		$alldepth = $diamondsinitialfilter[1][0]->depthRange;
		$savedfilter->depthMin = floor(array_column($alldepth, 'minDepth')[0]);
		$savedfilter->depthMax = floor(array_column($alldepth, 'maxDepth')[0]);

		$alltable  = $diamondsinitialfilter[1][0]->tableRange;
		$savedfilter->tableMin = floor(array_column($alltable, 'minTable')[0]);
		$savedfilter->tableMax = floor(array_column($alltable, 'maxTable')[0]);

		$allcerti = $diamondsinitialfilter[1][0]->certificateRange;
		$certiArray = array_column($allcerti, 'certificateName');
	}

	if ($back_cookie_data != "" || $save_filter_cookie_data != "") {

		if (isset($savedfilter->shapeList)) {
			$shapeArray = explode(',', $savedfilter->shapeList);
		}
		if (isset($savedfilter->SymmetryList)) {
			$symmArray = explode(',', $savedfilter->SymmetryList);
		}

		if (isset($back_cookie_data['depthMin']) || isset($back_cookie_data['depthMax'])) {
			$savedfilter->depthMin = $back_cookie_data['depthMin'];
			$savedfilter->depthMax = $back_cookie_data['depthMax'];
		}

		if (isset($save_filter_cookie_data['depthMin']) || isset($save_filter_cookie_data['depthMax'])) {
			$savedfilter->depthMin = $save_filter_cookie_data['depthMin'];
			$savedfilter->depthMax = $save_filter_cookie_data['depthMax'];
		}

		if (isset($back_cookie_data['caratMin']) || isset($back_cookie_data['caratMax'])) {
			$savedfilter->caratMin = $back_cookie_data['caratMin'];
			$savedfilter->caratMax = $back_cookie_data['caratMax'];
		}

		if (isset($save_filter_cookie_data['caratMin']) || isset($save_filter_cookie_data['caratMax'])) {
			$savedfilter->caratMin = $save_filter_cookie_data['caratMin'];
			$savedfilter->caratMax = $save_filter_cookie_data['caratMax'];
		}

		if (isset($back_cookie_data['PriceMin']) || isset($back_cookie_data['PriceMax'])) {
			$savedfilter->PriceMin = $back_cookie_data['PriceMin'];
			$savedfilter->PriceMax = $back_cookie_data['PriceMax'];
		}

		if (isset($save_filter_cookie_data['PriceMin']) || isset($save_filter_cookie_data['PriceMax'])) {
			$savedfilter->PriceMin = $save_filter_cookie_data['PriceMin'];
			$savedfilter->PriceMax = $save_filter_cookie_data['PriceMax'];
		}

		if (isset($back_cookie_data['tableMin']) || isset($back_cookie_data['tableMax'])) {
			$savedfilter->tableMin = $back_cookie_data['tableMin'];
			$savedfilter->tableMax = $back_cookie_data['tableMax'];
		}

		if (isset($save_filter_cookie_data['tableMin']) || isset($save_filter_cookie_data['tableMax'])) {
			$savedfilter->tableMin = $save_filter_cookie_data['tableMin'];
			$savedfilter->tableMax = $save_filter_cookie_data['tableMax'];
		}

		if (isset($savedfilter->polishList)) {
			$polishArray = explode(',', $savedfilter->polishList);
		}
		if (isset($savedfilter->FluorescenceList)) {
			$fluorArray = explode(',', $savedfilter->FluorescenceList);
		}
		if (isset($savedfilter->CutGradeList)) {
			$cutArray = explode(',', $savedfilter->CutGradeList);
		}
		if (isset($savedfilter->ColorList)) {
			$colorArray = explode(',', $savedfilter->ColorList);
		}
		if (isset($savedfilter->ClarityList)) {
			$clarityArray = explode(',', $savedfilter->ClarityList);
		}
		if (isset($savedfilter->FancycolorList)) {
			$fancycolorArray = explode(',', $savedfilter->FancycolorList);
		}
		if (isset($savedfilter->IntintensityList)) {
			$intintensityArray = explode(',', $savedfilter->IntintensityList);
		}
		if (isset($savedfilter->OvertoneList)) {
			$overtoneArray = explode(',', $savedfilter->OvertoneList);
		}
		if (isset($savedfilter->certificate)) {
			$certiArray = explode(',', $savedfilter->certificate);
		}
	}

	if ($default_shape_filter) {
		$shapeArray = array($default_shape_filter);
	}
	$filterapifancy = $options['filterapifancy'];

	if ($dealerID) {
		if (sanitize_text_field($_POST['filter_type']) == 'navstandard' || sanitize_text_field($_POST['filter_type']) == 'navlabgrown') {
			$requestUrl = $filterapi . 'DealerID=' . $dealerID;
		} else if (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
			$requestUrl = $filterapifancy . 'DealerID=' . $dealerID;
		} else {
			$requestUrl = $filterapi . 'DealerID=' . $dealerID;
		}
	} else {
		return;
	}
	$headers = array("Content-Type:application/json");
	if ($options['load_from_woocommerce'] == '1') {
		if (sanitize_text_field($_POST['filter_type']) == 'navlabgrown') {
			$meta_value = 'labcreated';
		} elseif (sanitize_text_field($_POST['filter_type']) == 'navstandard') {
			$meta_value = 'standard';
		}
		if (sanitize_text_field($_POST['filter_type']) == 'navstandard' || sanitize_text_field($_POST['filter_type']) == 'navlabgrown') {
			$max_carat_weight = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeight' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$max_flt_price    = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$max_depth_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depth' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$max_table_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='table' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");

			$min_carat_weight = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeight' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$min_flt_price    = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='_price' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$min_depth_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depth' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");
			$min_table_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='table' AND b.meta_key = 'productType' AND b.meta_value = '" . $meta_value . "'");

			$results      = array();
			$results[0]   = (object) array('message' => 'Success');
			$shapes_array = array();
			$woo_shapes   = get_terms("pa_gemfind_shape", array("hide_empty" => 0));
			foreach ($woo_shapes as $woo_shape) {
				$shapes_array[] = (object) array('$id' => '', 'shapeName' => $woo_shape->name, 'shapeImage' => '');
			}
			$cut_array = array();
			$woo_cuts  = get_terms("pa_gemfind_cut", array("hide_empty" => 0));
			foreach ($woo_cuts as $woo_cut) {
				$cut_array[] = (object) array('$id' => '', 'cutId' => $woo_cut->slug, 'cutName' => $woo_cut->name);
			}
			if ($max_carat_weight == $min_carat_weight) {
				$min_carat_weight = 0;
			}
			$carat_values    = array();
			$carat_values[0] = (object) array('maxCarat' => $max_carat_weight, 'minCarat' => $min_carat_weight);
			if ($max_flt_price == $min_flt_price) {
				$min_flt_price = 0;
			}
			$price_values    = array();
			$price_values[0] = (object) array('maxPrice' => $max_flt_price, 'minPrice' => $min_flt_price);
			//echo $max_flt_price . ' --- ' . $min_flt_price;
			$color_array = array();
			$woo_colors  = get_terms("pa_gemfind_color", array("hide_empty" => 0));
			foreach ($woo_colors as $woo_color) {
				$color_array[] = (object) array(
					'$id'       => '',
					'colorId'   => $woo_color->slug,
					'colorName' => $woo_color->name
				);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			if ($max_depth_value == $min_depth_value) {
				$min_depth_value = 0;
			}
			$depth_values    = array();
			$depth_values[0] = (object) array('maxDepth' => $max_depth_value, 'minDepth' => $min_depth_value);
			if ($max_table_value == $min_table_value) {
				$min_table_value = 0;
			}
			$table_values    = array();
			$table_values[0] = (object) array('maxTable' => $max_table_value, 'minTable' => $min_table_value);
			$polish_array    = array();
			$woo_polishes    = get_terms("pa_gemfind_polish", array("hide_empty" => 0));
			foreach ($woo_polishes as $woo_polish) {
				$polish_array[] = (object) array(
					'$id'        => '',
					'polishId'   => $woo_polish->slug,
					'polishName' => $woo_polish->name
				);
			}
			$symmetry_array = array();
			$woo_symmetries = get_terms("pa_gemfind_symmetry", array("hide_empty" => 0));
			foreach ($woo_symmetries as $woo_symmetry) {
				$symmetry_array[] = (object) array(
					'$id'          => '',
					'symmetryId'   => $woo_symmetry->slug,
					'symmteryName' => $woo_symmetry->name
				);
			}
			$fluorescence_array = array();
			$woo_fluorescences  = get_terms("pa_gemfind_fluorescence", array("hide_empty" => 0));
			foreach ($woo_fluorescences as $woo_fluorescence) {
				$fluorescence_array[] = (object) array(
					'$id'              => '',
					'fluorescenceId'   => $woo_fluorescence->slug,
					'fluorescenceName' => $woo_fluorescence->name
				);
			}
			$results[1] = array(
				'0' => (object) array(
					'shapes'            => $shapes_array,
					'cutRange'          => $cut_array,
					'caratRange'        => $carat_values,
					'priceRange'        => $price_values,
					'colorRange'        => $color_array,
					'clarityRange'      => $clarity_array,
					'depthRange'        => $depth_values,
					'tableRange'        => $table_values,
					'polishRange'       => $polish_array,
					'symmetryRange'     => $symmetry_array,
					'fluorescenceRange' => $fluorescence_array,
					'currencyFrom'      => get_woocommerce_currency(),
					'currencySymbol'    => get_woocommerce_currency_symbol()
				)
			);
		} elseif (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
			$max_carat_weight = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeightFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_flt_price    = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='FltPriceFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_depth_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depthFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$max_table_value  = $wpdb->get_var("SELECT max(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='tableFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");

			$min_carat_weight = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='caratWeightFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_flt_price    = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='FltPriceFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_depth_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='depthFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");
			$min_table_value  = $wpdb->get_var("SELECT min(a.meta_value) FROM wp_postmeta as a INNER JOIN wp_postmeta as b ON a.post_id = b.post_id WHERE a.meta_key='tableFancy' AND b.meta_key = 'productType' AND b.meta_value = 'fancy'");

			$results      = array();
			$results[0]   = (object) array('message' => 'Success');
			$shapes_array = array();
			$woo_shapes   = get_terms("pa_gemfind_fancy_shape", array("hide_empty" => 0));
			foreach ($woo_shapes as $woo_shape) {
				$shapes_array[] = (object) array('$id' => '', 'shapeName' => $woo_shape->name, 'shapeImage' => '');
			}
			$cut_array = array();
			$woo_cuts  = get_terms("pa_gemfind_cut", array("hide_empty" => 0));
			foreach ($woo_cuts as $woo_cut) {
				$cut_array[] = (object) array('$id' => '', 'cutId' => $woo_cut->slug, 'cutName' => $woo_cut->name);
			}
			if ($max_carat_weight == $min_carat_weight) {
				$min_carat_weight = 0;
			}
			$carat_values    = array();
			$carat_values[0] = (object) array('maxCarat' => $max_carat_weight, 'minCarat' => $min_carat_weight);
			if ($max_flt_price == $min_flt_price) {
				$min_flt_price = 0;
			}
			$price_values    = array();
			$price_values[0] = (object) array('maxPrice' => $max_flt_price, 'minPrice' => $min_flt_price);
			$color_array     = array();
			$woo_colors      = get_terms("pa_gemfind_fancy_color", array("hide_empty" => 0));

			foreach ($woo_colors as $woo_color) {
				$term_id       = $woo_color->term_id;
				$term_data     = get_option("taxonomy_$term_id");
				$color_array[] = (object) array(
					'$id'                   => '',
					'diamondColorId'        => $woo_color->slug,
					'diamondColorName'      => $woo_color->name,
					'diamondColorImagePath' => $term_data['fancy_color_img']
				);
			}

			$intensity_array = array();
			$woo_intensities = get_terms("pa_gemfind_fancy_intensity", array("hide_empty" => 0));
			foreach ($woo_intensities as $woo_intensity) {
				$intensity_array[] = (object) array('$id' => '', 'intensityName' => $woo_intensity->name);
			}
			$clarity_array = array();
			$woo_clarities = get_terms("pa_gemfind_fancy_clarity", array("hide_empty" => 0));
			foreach ($woo_clarities as $woo_clarity) {
				$clarity_array[] = (object) array(
					'$id'         => '',
					'clarityId'   => $woo_clarity->slug,
					'clarityName' => $woo_clarity->name
				);
			}
			if ($max_depth_value == $min_depth_value) {
				$min_depth_value = 0;
			}
			$depth_values    = array();
			$depth_values[0] = (object) array('maxDepth' => $max_depth_value, 'minDepth' => $min_depth_value);
			if ($max_table_value == $min_table_value) {
				$min_table_value = 0;
			}
			$table_values    = array();
			$table_values[0] = (object) array('maxTable' => $max_table_value, 'minTable' => $min_table_value);
			$polish_array    = array();
			$woo_polishes    = get_terms("pa_gemfind_fancy_polish", array("hide_empty" => 0));
			foreach ($woo_polishes as $woo_polish) {
				$polish_array[] = (object) array(
					'$id'        => '',
					'polishId'   => $woo_polish->slug,
					'polishName' => $woo_polish->name
				);
			}
			$symmetry_array = array();
			$woo_symmetries = get_terms("pa_gemfind_fancy_symmetry", array("hide_empty" => 0));
			foreach ($woo_symmetries as $woo_symmetry) {
				$symmetry_array[] = (object) array(
					'$id'          => '',
					'symmetryId'   => $woo_symmetry->slug,
					'symmteryName' => $woo_symmetry->name
				);
			}
			$fluorescence_array = array();
			$woo_fluorescences  = get_terms("pa_gemfind_fancy_fluorescence", array("hide_empty" => 0));
			foreach ($woo_fluorescences as $woo_fluorescence) {
				$fluorescence_array[] = (object) array(
					'$id'              => '',
					'fluorescenceId'   => $woo_fluorescence->slug,
					'fluorescenceName' => $woo_fluorescence->name
				);
			}
			$results[1] = array(
				'0' => (object) array(
					'shapes'            => $shapes_array,
					'cutRange'          => $cut_array,
					'caratRange'        => $carat_values,
					'priceRange'        => $price_values,
					'diamondColorRange' => $color_array,
					'intensity'         => $intensity_array,
					'clarityRange'      => $clarity_array,
					'depthRange'        => $depth_values,
					'tableRange'        => $table_values,
					'polishRange'       => $polish_array,
					'symmetryRange'     => $symmetry_array,
					'fluorescenceRange' => $fluorescence_array,
					'currencyFrom'      => get_woocommerce_currency(),
					'currencySymbol'    => get_woocommerce_currency_symbol()
				)
			);
		}
	} else {
		$results = gemfindDT_getCurlData($requestUrl, $headers);
		// echo '<pre>'; print_r($results[1][0]->diamondFilterRange); exit; 

	}
	if (sizeof($results) > 1 && $results[0]->message == 'Success') {
		foreach ($results[1] as $value) {
			$value = (array) $value;
		}
	}
	unset($curl);
	// for sort if cookie is set
	$back_order_by = ($back_cookie_data['orderBy'] != '') ? $back_cookie_data['orderBy'] : 'Size';
	$back_order_direction = ($back_cookie_data['direction'] != '') ? $back_cookie_data['direction'] : 'ASC';

	$priceRange = $diamondsinitialfilter[1][0]->priceRange;
	// echo('<pre>');
	// print_r($priceRange);
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	$show_Advance_options_as_Default_in_Diamond_Search = $diamondsoption[0][0]->show_Advance_options_as_Default_in_Diamond_Search;
	$show_hints_popup = $alldata['show_hints_popup'];

	$isShowPrice = $results[1][0]->isShowPrice;


	$html = '<div class="filter-details 1';
	if ($show_Advance_options_as_Default_in_Diamond_Search != 1) {
		$html .= ' hide-advance-options';
	}
	$html .= '">
		<input name="viewmode" id="viewmode" type="hidden" value="' . $savedfilter->viewmode . '">
		<input name="itemperpage" id="itemperpage" type="hidden" value="20">
		<input name="diamondfilter" id="diamondfilter" type="hidden" value="">
		<input type="hidden" name="orderby" id="orderby" value="' . $back_order_by . '">
		<input type="hidden" name="direction" id="direction" value="' . $back_order_direction . '">
		<input type="hidden" name="currentpage" id="currentpage" value="' . $savedfilter->currentPage . '">
		<input type="hidden" name="did" id="did" value="">
		<input type="hidden" name="backdiamondid" id="backdiamondid" value="' . $back_cookie_data['backdiamondid'] . '">
		<div class="shape-container shape-flex">';
	$html .= '<div class="filter-main filter-alignment-right">
		<div class="filter-for-shape shape-bg">
		<h4>Shape ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'shape'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
		<ul id="shapeul">';
	foreach ($value['shapes'] as $shape) {
		$shapeNameselected = (in_array(strtolower($shape->shapeName), $shapeArray)) ? 'selected active' : '';
		$select_checked = (in_array(strtolower($shape->shapeName), $shapeArray)) ? 'true"' : 'false';
		$html.= '<li class="' . strtolower($shape->shapeName) . ' " title="' . $shape->shapeName . '">
			<div class="shape-type ' . $shapeNameselected . '">
			<input type="checkbox" class="input-assumpte"  id="diamond_shape_' . strtolower($shape->shapeName) . '"  name="diamond_shape[]" value="' . strtolower($shape->shapeName) . '" checked="checked" >
			</div>
			<label for="diamond_shape_' . $shape->shapeName . '">' . $shape->shapeName . '</label>
			</li>';
	}
	// for fancycolored
	if (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
		$html .= '</ul>
			</div>
			</div>';
	}
	if (sanitize_text_field($_POST['filter_type']) == 'navstandard' || sanitize_text_field($_POST['filter_type']) == 'navlabgrown') {
		$html .= '</ul>
			</div>
			</div>
			<div class="filter-main filter-alignment-left">
			<div class="filter-for-shape shape-bg">
			<h4>Cut ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'cut'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
			<div class="cut-main">';
	}

	if (sanitize_text_field($_POST['filter_type']) != 'navfancycolored') {
		$cutName = '';
		$i = 0;
		foreach ($value['cutRange'] as $cut) {
			$i++;
			if (count($value['cutRange']) + 1 != $i) {
				$cutName .= '"' . $cut->cutName . '"' . ',';
			} else {
				$cutName .= '"' . $cut->cutName . '"';
			}
			if (!next($value['cutRange'])) {
				$cutName .= '"Last"';
				$value['cutRange'][]  = (object) ['cutId' => '000', 'cutName' => 'Last'];
			}
		}
		$Cutselected = '';
		$i = 0;
		if (!empty($cutArray)) {
			foreach ($cutArray as $cut) {
				$i++;
				if (count($cutArray) != $i) {
					$Cutselected .= $cut . ',';
				} else {
					$Cutselected .= $cut;
				}

				if ($i == 1) {
					$cutRangedataleft = $cut;
				}
				if (count($cutArray) == $i) {
					$cutRangedataright = (int)$cut + 1;
				}
			}
		}
		// if (empty($Cutselected)) {
		// 			$Cutselected = '1,2,3,4,5';
		// }
		$cutRangedataright = $value['cutRange'][count($value['cutRange']) - 2]->cutId + 1 ?>
		<script type="text/javascript">
			var cutRangeName = '[<?php echo $cutName; ?>]';
		</script>
	<?php
		$html .= '<div class="polish-slider right-block ui-slider">
			<input type="hidden" name="diamond_cut[]" id="diamond_cut" class="diamond_cut" value="' . esc_attr($Cutselected) . '" data-cutRangedataleft="1" data-cutRangedataright="2">
			<div class="price-slider right-block">
			<div id="cutRange-slider" data-steps="' . count($value['cutRange']) . '">
			<input type="hidden" name="cutRangeleft" id="cutRangeleft" class="cutRangedataleft" value="' . esc_attr($cutRangedataleft) . '">
			<input type="hidden" name="cutRangeright" id="cutRangeright" class="cutRangedataright" value="' . esc_attr($cutRangedataright) . '">
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>';
	}
	$html .= '</div>';
	// $currency = ($value['currencyFrom'] != 'USD') ? $value['currencyFrom'] : $value['currencySymbol'];

	$currency = esc_html($value['currencyFrom']) != 'USD' ? esc_html($value['currencyFrom']) . esc_html($value['currencySymbol']) : '$';

	// echo '<pre>'; print_r($value['currencySymbol']); exit; 

	$colorClass = (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') ? "fancy-color" : "";
	$html .= '<div class="color-filter shape-bg ' . esc_attr($colorClass) . '" id="norcolor">
		<h4>Color ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'color'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>';
	if ($_POST['filter_type'] == 'navstandard' || $_POST['filter_type'] == 'navlabgrown') {
		$colorName = '';
		$i = 0;
		foreach ($value['colorRange'] as $color) {
			$i++;
			$colorRangedataleft = $value['colorRange'][0]->colorId;
			if (count($value['colorRange']) + 1 != $i) {
				$colorName .= '"' . $color->colorName . '"' . ',';
			} else {
				$colorName .= '"' . $color->colorName . '"';
				$colorRangedataright = $color->colorId;
			}
			if (!next($value['colorRange'])) {
				$colorName .= '"Last"';
				$value['colorRange'][]  = (object) ['colorId' => '000', 'colorName' => 'Last'];
			}
		}
		$selectedActive = '';
		$i = 0;
		if (!empty($colorArray)) {
			foreach ($colorArray as $color) {
				$i++;
				if (count($colorArray) != $i) {
					$selectedActive .= $color . ',';
				} else {
					$selectedActive .= $color;
				}
				$colorRangedataleft = $colorArray[0];
				if (count($colorArray) == $i) {
					$colorRangedataright = $value['colorRange'][$i]->colorId;
				}
			}
		}
		if (empty($selectedActive)) {
  			$selectedActive = '68,69,70,71,72,73,74,75,76,77,78,79,80';
		}
	?>
		<script type="text/javascript">
			var ColorRangeName = '[<?php echo $colorName; ?>]';
		</script>
	<?php
		$totalColorrange = count($value['colorRange']);
		$colorRangedataright = $value['colorRange'][count($value['colorRange']) - 2]->colorId + 1;
		$html .= '<div class="cut-main">
				<div class="polish-slider right-block ui-slider">
					<div class="price-slider right-block">
	              		<div id="colorRange-slider" data-steps="' . $totalColorrange . '">
	              			<input type="hidden" name="diamond_color[]" id="diamond_color" class="diamond_color" value="' . $selectedActive . '" >
		              		<input type="hidden" name="colorRangeleft" id="colorRangeleft" class="colorRangedataleft" value="' . $value['colorRange'][0]->colorId . '">
	    	          		<input type="hidden" name="colorRangeright" id="colorRangeright" class="colorRangedataright" value="' . $colorRangedataright . '">
	              		</div>
	            	</div>
	            </div>
	            </div>';
	}
	if ($_POST['filter_type'] == 'navfancycolored') {
		$html .= '<div class="cut-main">';
		$diamondColorName = '';
		$fi = 0;
		foreach ($value['diamondColorRange'] as $diamondColor) {
			$fi++;
			if (count($value['diamondColorRange']) + 1 != $fi) {
				$diamondColorName .= '"' . $diamondColor->diamondColorName . '"' . ',';
			} else {
				$diamondColorName .= '"' . $diamondColor->diamondColorName . '"';
			}
			if (!next($value['diamondColorRange'])) {
				$diamondColorName .= '"Last"';
				$value['diamondColorRange'][]  = (object) ['$id' => '000', 'diamondColorId' => 'Last'];
			}
		}
		$diamondColorselected = '';
		$fi = 0;
		if (!empty($fancycolorArray)) {
			foreach ($fancycolorArray as $diamondColor) {
				$fi++;
				if (!empty($diamondColor)) {
					if (count($fancycolorArray) != $fi) {
						$diamondColorselected .= $diamondColor . ',';
					} else {
						$diamondColorselected .= $diamondColor;
					}
				}
				if ($fi == 1 && !empty($diamondColor)) {
					$diamondColorRangedataleft = array_search($diamondColor, array_column($value['diamondColorRange'], 'diamondColorName')) + 1;
				}
				if (count($fancycolorArray) - 1 == $fi) {
					$diamondColorRangedataright = array_search($diamondColor, array_column($value['diamondColorRange'], 'diamondColorName')) + 2;
				}
			}
		}
		if (empty($diamondColorselected)) {
  			$diamondColorselected = 'blue,pink,yellow,champagne,green,grey,purple,chameleon,violet';
		}
	?>
		<script type="text/javascript">
			var diamondColorRangeName = '[<?php echo $diamondColorName; ?>]';
		</script>
		<?php
		if ($diamondColorRangedataright == "") {
			$diamondColorRangedataright = (array)$value['diamondColorRange'][count($value['diamondColorRange']) - 2];
			$diamondColorRangedataright = $diamondColorRangedataright['$id'];
		}
		$html .= '
			<div class="polish-slider right-block ui-slider">
			<div class="diamondColor-slider right-block">
			<input type="hidden" name="diamond_fancycolor[]" id="diamond_fancycolor" class="diamond_fancycolor" value="' . $diamondColorselected . '" data-diamondColorRangedataleft="1" data-diamondColorRangedataright="2">
			<div class="diamondColor-slider right-block">
			<div id="diamondColorRange-slider" data-steps="' . count($value['diamondColorRange']) . '">
			<input type="hidden" name="diamondColorRangeleft" id="diamondColorRangeleft" class="diamondColorRangedataleft" value="' . $diamondColorRangedataleft . '">
			<input type="hidden" name="diamondColorRangeright" id="diamondColorRangeright" class="diamondColorRangedataright" value="' . $diamondColorRangedataright . '">
			</div>
			</div>
			</div>
			</div>
			</div>';
	}
	$html .= '
		</div>';
	if ($_POST['filter_type'] == 'navfancycolored') {
		$html .= '<div class="color-filter fancy-IntIntensity-filter shape-bg">
		<h4>Fancy Intensity';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'fancy_intensity'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
			<div class="cut-main">';
		$intensityName = '';
		$i = 0;
		foreach ($value['intensity'] as $intensity) {
			$i++;
			if (count($value['intensity']) + 1 != $i) {
				$intensityName .= '"' . $intensity->intensityName . '"' . ',';
			} else {
				$intensityName .= '"' . $intensity->intensityName . '"';
			}
			if (!next($value['intensity'])) {
				$intensityName .= '"Last"';
				$value['intensity'][]  = (object) ['$id' => '000', 'intensityName' => 'Last'];
			}
		}
		$intensityselected = '';
		$i = 0;
		if (!empty($intintensityArray)) {
			foreach ($intintensityArray as $intensity) {
				$i++;
				if (!empty($intensity)) {
					if (count($intintensityArray) != $i) {
						$intensityselected .= $intensity . ',';
					} else {
						$intensityselected .= $intensity;
					}
				}
				if ($i == 1 && !empty($intensity)) {
					$intensityRangedataleft = array_search($intensity, array_column($value['intensity'], 'intensityName')) + 1;
				}
				if (count($intintensityArray) - 1 == $i) {
					$intensityRangedataright = array_search($intensity, array_column($value['intensity'], 'intensityName')) + 2;
				}
			}
		}			
			if (empty($intensityselected)) {
	  		$intensityselected = 'faint,v.light,light,f.light,fancy,intense,vivid,deep,dark,';
			}	
		?>
		<script type="text/javascript">
			var intensityRangeName = '[<?php echo $intensityName; ?>]';
		</script>
	<?php
		if ($intensityRangedataright == "") {
			$intensityRangedataright = (array)$value['intensity'][count($value['intensity']) - 2];
			$intensityRangedataright = $intensityRangedataright['$id'];
		}
		$html .= '
			<div class="polish-slider right-block ui-slider">
			<div class="intensity-slider right-block">
			<input type="hidden" name="diamond_intintensity[]" id="diamond_intintensity" class="diamond_intintensity" value="' . $intensityselected . '" data-intensityRangedataleft="1" data-intensityRangedataright="2">
			<div class="intensity-slider right-block">
			<div id="intensityRange-slider" data-steps="' . count($value['intensity']) . '">
			<input type="hidden" name="intensityRangeleft" id="intensityRangeleft" class="intensityRangedataleft" value="' . $intensityRangedataleft . '">
			<input type="hidden" name="intensityRangeright" id="intensityRangeright" class="intensityRangedataright" value="' . $intensityRangedataright . '">
			</div>
			</div>
			</div>
			</div>';
		$html .= '</div></div>';
	}
	$html .= '<div class="color-filter clarity-filter shape-bg">
		<h4>Clarity ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="showfilterinfo(' . "'clarity'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
				<div class="cut-main">';
	$clarityName = '';
	$i = 0;
	foreach ($value['clarityRange'] as $clarity) {
		$i++;
		if (count($value['clarityRange']) + 1 != $i) {
			$clarityName .= '"' . $clarity->clarityName . '"' . ',';
		} else {
			$clarityName .= '"' . $clarity->clarityName . '"';
		}
		if (!next($value['clarityRange'])) {
			$clarityName .= '"Last"';
			$value['clarityRange'][]  = (object) ['clarityId' => '000', 'clarityName' => 'Last'];
		}
	}

	$clarityselected = '';
	$i = 0;
	if (!empty($clarityArray)) {
		foreach ($clarityArray as $clarity) {
			$i++;
			if (!empty($clarity)) {
				if (count($clarityArray) != $i) {
					$clarityselected .= $clarity . ',';
				} else {
					$clarityselected .= $clarity;
				}
			}
			if ($i == 1 && !empty($clarity)) {
				$clarityRangedataleft = $clarity;
			}
			if (count($clarityArray) == $i && !empty($clarity)) {
				$clarityRangedataright = $clarity;
			}
		}
	}
	if (empty($clarityselected)) {
  		$clarityselected = '1,2,3,4,5,6,7,8,9,10,11';
	}
	?>
	<script type="text/javascript">
		var clarityRangeName = '[<?php echo $clarityName; ?>]';
	</script>
	<?php
	if ($clarityRangedataright == "") {
		$clarityRangedataright = $value['clarityRange'][count($value['clarityRange']) - 2]->clarityId + 1;
	}
	$html .= '<div class="price-slider right-block ui-slider">
							<input type="hidden" name="diamond_clarity[]" id="diamond_clarity" class="diamond_clarity" value="' . $clarityselected . '" data-clarityRangedataleft="1" data-clarityRangedataright="2">
		                	<div class="price-slider right-block">
			              		<div id="clarityRange-slider" data-steps="' . count($value['clarityRange']) . '">
				              		<input type="hidden" name="clarityRangeleft" id="clarityRangeleft" class="clarityRangedataleft" value="' . $clarityRangedataleft . '">
			    	          		<input type="hidden" name="clarityRangeright" id="clarityRangeright" class="clarityRangedataright" value="' . $clarityRangedataright . '">
			              		</div>
		            		</div>
	        		</div>';
	$html .= '</div></div>';
	$carat_from = isset($savedfilter->caratMin) ? $savedfilter->caratMin : $value['caratRange'][0]->minCarat;
	$carat_to = isset($savedfilter->caratMax) ? $savedfilter->caratMax : $value['caratRange'][0]->maxCarat;
	if ($carat_from == $carat_to) {
		$carat_from = 0;
	}
	if ($carat_from == "") {
		$carat_from = $value['caratRange'][0]->minCarat;
	}
	if ($carat_to == "") {
		$carat_to = $value['caratRange'][0]->maxCarat;
	}

	$html .= '<div class="shape-container shape-flex carat-price eq_wrapper">
		<div class="filter-main filter-alignment-right">
		<div class="filter-for-shape shape-bg">
		<h4 class="">Carat ';
	if ($show_hints_popup == 'yes') {
		$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'carat'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
	}
	$html .= '</h4>
		<div class="slider_wrapper">
		<div class="carat-main ui-slider" id="noui_carat_slider" data-min="' . esc_html($value['caratRange'][0]->minCarat) . '" data-max="' . esc_attr($value['caratRange'][0]->maxCarat) . '">
		<input type="text" class="ui-slider-val slider-left" name="diamond_carats[from]" value="' . esc_attr($carat_from) . '" data-type="min" autocomplete="off" inputmode="decimal" pattern="[-+]?[0-9]*[.,]?[0-9]+">
		<input type="text" class="ui-slider-val slider-right" name="diamond_carats[to]" value="' . esc_attr($carat_to) . '" data-type="max" autocomplete="off" inputmode="decimal" pattern="[-+]?[0-9]*[.,]?[0-9]+">
		<input type="hidden" name="caratto" class="slider-right-val" value="' . esc_html($value['caratRange'][0]->maxCarat) . '">
		</div>
		</div>
		</div>
		</div>';
	$price_from = isset($savedfilter->PriceMin) ? $savedfilter->PriceMin : $value['priceRange'][0]->minPrice;
	$price_to = isset($savedfilter->PriceMax) ? $savedfilter->PriceMax : $value['priceRange'][0]->maxPrice;
	if ($price_from == $price_to) {
		$price_from = 0;
	}
	if ($options['price_row_format'] == 'left') {
		$class_left = 'price-filter_left';
	} else {
		$class_left = '';
	}

	if ($options['price_row_format'] == 'left') {
		$class_right = 'price-filter_right';
	} else {
		$class_right = '';
	}
	// echo 'ram';print_r($currency); exit; 
	if ($isShowPrice == '1') {
		$html .= '<div class="filter-main filter-alignment-left">
		<div class="filter-for-shape shape-bg">
		<h4 class="">Price ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'price'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
		<div class="slider_wrapper">
		<div id="noui_price_slider" class="price-main ui-slider" data-min="' . esc_html($value['priceRange'][0]->minPrice) . '" data-max="' . ($value['priceRange'][0]->maxPrice) . '"> 
		<div class="price-left ' . esc_attr($class_left) . '">
		<span class="currency-icon">' . esc_attr($currency) . '</span>
		<input class="ui-slider-val slider-left" id="rb_min_price" type="text" data-type="min" name="price[from]" value="' . str_replace(',', '', esc_attr($price_from)) . '" autocomplete="off" pattern="[-+]?[0-9]*[.,]?[0-9]+" inputmode="numeric">
		</div>
		<div class="price-right ' . esc_attr($class_right) . '">
		<span class="currency-icon">' . esc_attr($currency) . '</span>
		<input class="ui-slider-val slider-right" id="rb_max_price" type="text" data-type="max" name="price[to]" value="' . str_replace(',', '', esc_attr($price_to)) . '" autocomplete="off" inputmode="numeric">
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>';
	}

	if ($show_Advance_options_as_Default_in_Diamond_Search == 1) {
		$depthFrom = isset($savedfilter->depthMin) ? $savedfilter->depthMin : floor($value['depthRange'][0]->minDepth);
		$depthTo   = isset($savedfilter->depthMax) ? $savedfilter->depthMax : floor($value['depthRange'][0]->maxDepth);
		$html .= '<div class="filter-advanced shape-bg">
			<button class="accordion">Advanced Search</button>
			<div class="panel cls-for-hide">
			<div class="shape-container shape-flex eq_wrapper">
			<div class="filter-main filter-alignment-left">
			<div class="filter-for-shape shape-bg">
			<h4 class="">Depth ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'depth'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
			<div class="slider_wrapper">
			<div class="depth-main ui-slider" id="noui_depth_slider" data-min="' . esc_html(floor($value['depthRange'][0]->depthMin)) . '" data-max="' . esc_html($value['depthRange'][0]->maxDepth) . '">
			<div class="depth-left">
			<input type="number" class="ui-slider-val slider-left" name="diamond_depth[from]" value="' . $depthFrom . '" data-type="min" autocomplete="off" pattern="[\d\.]*">
			<span class="currency-icon">%</span>
			</div>
			<div class="depth-right">
			<input type="number" class="ui-slider-val slider-right" name="diamond_depth[to]" value="' . $depthTo . '" data-type="max" autocomplete="off" pattern="[\d\.]*">
			<span class="currency-icon">%</span>
			<input type="hidden" name="depthTo" class="slider-right-val" value="' . esc_attr($value['depthRange'][0]->maxDepth) . '"> 
			</div>                     
			</div>
			</div>
			</div>
			</div>
			<div class="filter-main filter-alignment-right">
			<div class="filter-for-shape shape-bg">
			<h4 class="">Table ';
		$tableFrom = isset($savedfilter->tableMin) ? $savedfilter->tableMin : floor($value['tableRange'][0]->minTable);
		$tableTo   = isset($savedfilter->tableMax) ? $savedfilter->tableMax : floor($value['tableRange'][0]->maxTable);
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'fluorescence'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
			<div class="slider_wrapper">
			<div class="tableper-main ui-slider" id="noui_tableper_slider" data-min="' . esc_html(floor($value['depthRange'][0]->minTable)) . '" data-max="' . esc_html($value['tableRange'][0]->maxTable) . '">
			<div class="table-left">
			<input type="number" class="ui-slider-val slider-left" name="diamond_table[from]" value="' . esc_attr($tableFrom) . '" data-type="min" autocomplete="off" pattern="[\d\.]*">
			<span class="currency-icon">%</span>
			</div>
			<div class="table-right">
			<input type="number" class="ui-slider-val slider-right" name="diamond_table[to]" value="' . esc_attr($tableTo) . '" data-type="max" autocomplete="off" pattern="[\d\.]*">
			<span class="currency-icon">%</span>
			<input type="hidden" name="tableTo" class="slider-right-val" value="' . esc_attr($value['tableRange'][0]->maxTable) . '"> 
			</div>                     
			</div>
			</div>
			</div>
			</div>
			</div>
			<div class="shape-container shape-flex">
			<div class="filter-advanced-main advance-left">
			<div class="polish-depth">
			<h4>Polish ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'polish'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4>
			<div class="cut-main">';
		$polishName = '';
		$i = 0;
		//print_r($value['polishRange']);
		foreach (esc_attr($value['polishRange']) as $polish) {
			$i++;
			if (esc_attr(count($value['polishRange'])) + 1 != $i) {
				$polishName .= '"' . $polish->polishName . '"' . ',';
			} else {
				$polishName .= '"' . $polish->polishName . '"';
			}
			if (!next(esc_attr($value['polishRange']))) {
				$polishName .= '"Last"';
				$value['polishRange'][]  = (object) ['polishId' => '000', 'polishName' => 'Last'];
			}
		}
		$polishSelected = '';
		$i = 0;
		if (!empty($polishArray)) {
			foreach ($polishArray as $polish) {
				$i++;
				if (count($polishArray) != $i) {
					$polishSelected .= $polish . ',';
				} else {
					$polishSelected .= $polish;
				}

				if ($i == 1) {
					$polishRangedataleft = $polish;
				}
				if (count($polishArray) == $i) {
					$polishRangedataright = $polish;
				}
			}
		}

		// echo '<pre>';
		// print_r($polishName);
		// exit;
	?>
		<script type="text/javascript">
			var polishRangeName = [<?php echo esc_js($polishName); ?>];
		</script>
		<?php
		$polishRangedataright = esc_attr($value['polishRange'])[esc_attr(count($value['polishRange'])) - 2]->polishId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
			<input type="hidden" name="diamond_polish[]" id="diamond_polish" class="diamond_polish" value="' . esc_attr($polishSelected) . '" data-polishRangedataleft="1" data-polishRangedataright="2">
			<div class="price-slider right-block">
			<div id="polishRange-slider" data-steps="' . esc_html(count($value['polishRange'])) . '">
			<input type="hidden" name="polishRangeleft" id="polishRangeleft" class="polishRangedataleft" value="' . esc_attr($polishRangedataleft) . '">
			<input type="hidden" name="polishRangeright" id="polishRangeright" class="polishRangedataright" value="' . esc_attr($polishRangedataright) . '">
			</div>
			</div>
			</div>';
		$html .= '</div>
			</div></div>
			<div class="shape-container shape-flex">
			<div class="filter-advanced-main advance-left">
			<div class="polish-depth filter-Fluoroscence">
			<h4>Fluorescence';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'fluorescence'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4><div class="cut-main">';
		$fluorName = '';
		$i = 0;
		foreach (esc_attr($value['fluorescenceRange']) as $fluor) {
			$i++;
			if (esc_attr(count($value['fluorescenceRange'])) + 1 != $i) {
				$fluorName .= '"' . $fluor->fluorescenceName . '"' . ',';
			} else {
				$fluorName .= '"' . $fluor->fluorescenceName . '"';
			}
			if (!next(esc_attr($value['fluorescenceRange']))) {
				$fluorName .= '"Last"';
				$value['fluorescenceRange'][]  = (object) ['fluorescenceId' => '000', 'fluorescenceName' => 'Last'];
			}
		}
		$fluorSelected = '';
		$i = 0;
		if (!empty($fluorArray)) {
			foreach ($fluorArray as $fluor) {
				$i++;
				if (count($fluorArray) != $i) {
					$fluorSelected .= $fluor . ',';
				} else {
					$fluorSelected .= $fluor;
				}

				if ($i == 1) {
					$fluorRangedataleft = $fluor;
				}
				if (count($fluorArray) == $i) {
					$fluorRangedataright = $fluor;
				}
			}
		}
		?>
		<script type="text/javascript">
			var fluorRangeName = '[<?php echo $fluorName; ?>]';
		</script>
		<?php
		$fluorRangedataright = esc_attr($value['fluorescenceRange'])[esc_attr(count($value['fluorescenceRange'])) - 2]->fluorescenceId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
			<input type="hidden" name="diamond_fluorescence[]" id="diamond_fluorescence" class="diamond_fluorescence" value="' . esc_attr($fluorSelected) . '" data-fluorRangedataleft="1" data-fluorRangedataright="2">
			<div class="price-slider right-block">
			<div id="fluorRange-slider" data-steps="' . esc_html(count($value['fluorescenceRange'])) . '">
			<input type="hidden" name="fluorRangeleft" id="fluorRangeleft" class="fluorRangedataleft" value="' . esc_attr($fluorRangedataleft) . '">
			<input type="hidden" name="fluorRangeright" id="fluorRangeright" class="fluorRangedataright" value="' . esc_attr($fluorRangedataright) . '">
			</div>
			</div>
			</div>';
		$html .= '</div>
			</div>
			</div>
			<div class="filter-advanced-main advance-right">
			<div class="polish-depth">
			<h4>Symmetry ';
		if ($show_hints_popup == 'yes') {
			$html .= '<span class="show-filter-info" onclick="gemfindDT_showfilterinfo(' . "'symmetry'" . ');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
		$html .= '</h4><div class="cut-main">';
		$symmetryName = '';
		$i = 0;
		foreach (esc_attr($value['symmetryRange']) as $symmetry) {
			$i++;
			if (esc_attr(count($value['symmetryRange'])) + 1 != $i) {
				$symmetryName .= '"' . $symmetry->symmteryName . '"' . ',';
			} else {
				$symmetryName .= '"' . $symmetry->symmteryName . '"';
			}
			if (!next(esc_attr($value['symmetryRange']))) {
				$symmetryName .= '"Last"';
				$value['symmetryRange'][]  = (object) ['symmetryId' => '000', 'symmteryName' => 'Last'];
			}
		}
		$symmetrySelected = '';
		$i = 0;
		if (!empty($symmArray)) {
			foreach ($symmArray as $symmetry) {
				$i++;
				if (count($symmArray) != $i) {
					$symmetrySelected .= $symmetry . ',';
				} else {
					$symmetrySelected .= $symmetry;
				}

				if ($i == 1) {
					$symmetryRangedataleft = $symmetry;
				}
				if (count($symmArray) == $i) {
					$symmetryRangedataright = $symmetry;
				}
			}
		}
		?>
		<script type="text/javascript">
			var symmetryRangeName = '[<?php echo $symmetryName; ?>]';
		</script>
	<?php
		$symmetryRangedataright = esc_attr($value['symmetryRange'])[esc_attr(count($value['symmetryRange'])) - 2]->symmetryId + 1;
		$html .= '<div class="polish-slider right-block ui-slider">
			<input type="hidden" name="diamond_symmetry[]" id="diamond_symmetry" class="diamond_symmetry" value="' . esc_attr($symmetrySelected) . '" data-symmetryRangedataleft="1" data-symmetryRangedataright="2">
			<div class="price-slider right-block">
			<div id="symmetryRange-slider" data-steps="' . esc_html(count($value['symmetryRange'])) . '">
			<input type="hidden" name="symmetryRangeleft" id="symmetryRangeleft" class="symmetryRangedataleft" value="' . esc_attr($symmetryRangedataleft) . '">
			<input type="hidden" name="symmetryRangeright" id="symmetryRangeright" class="symmetryRangedataright" value="' . esc_attr($symmetryRangedataright) . '">
			</div>
			</div>
			</div>';
		$html .= '</div>
			</div>
			</div>
			</div>
			<div class="filter-advanced-main advance-right">
			<div class="polish-depth advanced-certificate">
			<h4>Certificates</h4>
			<div class="certificate-div">';
		if ($show_Certificate_in_Diamond_Search) {
			$html .= '<select name="diamond_certificates[]" multiple="multiple" id="certi-dropdown" placeholder="Certificates" class="testSelAll SumoUnder" tabindex="-1">';
			foreach (esc_attr($value['certificateRange']) as $certificate) {
				$selectedActive = (in_array(str_replace(' ', '_', $certificate->certificateName), $certiArray)) ? 'selected="selected"' : '';
				if ($certificate->certificateName != 'Show All Cerificate' && $certificate->certificateName != '') {
					$html .= '<option value="' . $certificate->certificateName . '" class="navstandard_gcal" ' . $selectedActive . '>' . $certificate->certificateName . '</option>';
				}
			}
			$html .= '</select>';
		}
		$html .= '</div>
			</div>
			</div>
			</div>
			</div>
			</div>';
	}
	$html .= '</div>';
	$allowed_html = array(
		'div' => array(
			'id' => array(),
			'class' => array(),
			'data-min' => array(),
			'data-max' => array(),
			'data-steps' => array(),
		),
		'input' => array(
			'name' => array(),
			'id' => array(),
			'type' => array(),
			'value' => array(),
			'class' => array(),
			'data-type' => array(),
			'data-min' => array(),
			'data-max' => array(),
			'autocomplete' => array(),
			'inputmode' => array(),
		),
		'h4' => array(
			'class' => array(),
		),
		'ul' => array(
			'id' => array(),
		),
		'li' => array(
			'class' => array(),
			'title' => array(),
		),
		'script' => array(
			'type' => array(),
		),
		'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
		),
		'span' => array(
			'class' => array(),
		),
		'img' => array(
			'src' => array(),
			'alt' => array(),
			'title' => array(),
			'width' => array(),
			'height' => array(),
		),
		'style' => array(
			'type' => array(),
		),
		'br' => array(),
		'hr' => array(),
	);
	echo wp_kses($html, $allowed_html);
	?>
	<script type="text/javascript">
		jQuery("#certi-dropdown").SumoSelect({
			csvDispCount: 2,
			okCancelInMulti: false,
			selectAll: true,
			forceCustomRendering: true,
			triggerChangeCombined: false,
			captionFormatAllSelected: "Show All Certificates"
		});
	</script>
	<?php
	// echo wp_strip_all_tags($html);
	die();
}

function gemfindDT_getDiamondDetails() {
    $Id = $_POST['id'];
    $shop = $_POST['shop'];
    
    // Determine type based on HTTP_REFERER if no type is sent via POST
    if ($_POST['type'] == "NA") {
        if (strpos($_SERVER["HTTP_REFERER"], "labcreated") !== false) {
            $type = 'labcreated';
        } else {
            $type = '';
        }
    } else {
        $type = $_POST['type'];
    }

    $diamond = getDiamondById_dl($Id, $type, $shop);  
    echo json_encode($diamond);
    exit;
}

add_action( 'wp_ajax_nopriv_gemfindDT_getDiamondDetails', 'gemfindDT_getDiamondDetails' );
add_action( 'wp_ajax_gemfindDT_getDiamondDetails', 'gemfindDT_getDiamondDetails' );

/**
 * Returns list of diamonds with all other parameters i.e. diamonds per page.
 */
add_action('wp_ajax_nopriv_gemfindDT_getDiamonds', 'gemfindDT_getDiamonds');
add_action('wp_ajax_gemfindDT_getDiamonds', 'gemfindDT_getDiamonds');
function gemfindDT_getDiamonds()
{


	if (sanitize_text_field($_POST['filter_type']) == 'navstandard') {
		$back_cookie_data        = json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvalue'])), 1);
	} elseif (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
		$back_cookie_data        = json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvaluefancy'])), 1);
	} else {
		$back_cookie_data        = json_decode(stripslashes(sanitize_text_field($_COOKIE['wp_dl_savebackvaluelabgrown'])), 1);
	}



	$alldata = get_option('gemfind_diamond_link');
	$enable_price_users    			 = $alldata['enable_price_users'];
	$filter_type                     = sanitize_text_field($_POST['filter_type']);
	$shop                            = sanitize_text_field($_POST['shop']);
	$filter_data                     = rest_sanitize_array($_POST['filter_data']);
	$options                         = gemfindDT_getOptions();
	$request                         = array();
	$request['diamond_carats']       = array();
	$request['price']                = array();
	$request['diamond_depth']        = array();
	$request['diamond_table']        = array();
	$request['diamond_shape']        = array();
	$request['diamond_cut']          = array();
	$request['diamond_color']        = array();
	$request['diamond_clarity']      = array();
	$request['diamond_polish']       = array();
	$request['diamond_symmetry']     = array();
	$request['diamond_certificates'] = array();
	$request['diamond_fluorescence'] = array();
	$request['diamond_fancycolor']   = array();
	$request['diamond_intintensity'] = array();


	foreach ($filter_data as $data) {
		if ($data['name'] == 'diamond_shape[]') {
			$request['diamond_shape'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_cut[]') {
			$request['diamond_cut'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_color[]') {
			$request['diamond_color'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_clarity[]') {
			$request['diamond_clarity'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_polish[]') {
			$request['diamond_polish'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_fluorescence[]') {
			$request['diamond_fluorescence'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_symmetry[]') {
			$request['diamond_symmetry'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_certificates[]') {
			$request['diamond_certificates'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_fancycolor[]') {
			$request['diamond_fancycolor'][] = $data['value'];
		}
		if ($data['name'] == 'diamond_intintensity[]') {
			$request['diamond_intintensity'][] = $data['value'];
		}
		$request[$data['name']] = $data['value'];
	}
	// echo'<pre>'; 
	// print_r($request['diamond_shape']);
	// exit;
	// $file = 'log_api.txt';
	// file_put_contents($file, 'testing');



	$request['diamond_carats']['from'] = $request['diamond_carats[from]'];
	$request['diamond_carats']['to']   = $request['diamond_carats[to]'];
	$request['price']['from']          = $request['price[from]'];
	$request['price']['to']            = $request['price[to]'];

	$request['diamond_depth']['from']  = $request['diamond_depth[from]'];
	$request['diamond_depth']['to']    = $request['diamond_depth[to]'];
	$request['diamond_table']['from']  = $request['diamond_table[from]'];
	$request['diamond_table']['to']    = $request['diamond_table[to]'];

	unset($request['diamond_carats[from]']);
	unset($request['diamond_carats[to]']);
	unset($request['price[from]']);
	unset($request['price[to]']);
	unset($request['diamond_depth[from]']);
	unset($request['diamond_depth[to]']);
	unset($request['diamond_table[from]']);
	unset($request['diamond_table[to]']);
	if ($request == null) {
		$diamond = [
			'meta'       => ['code' => 400, 'message' => __('No arguments supplied.')],
			'data'       => [],
			'pagination' => [],
			'perpage'    => gemfindDT_getResultPerPage()
		];
		return $diamond;
	}
	if (!is_array($request)) {
		$diamond = [
			'meta'       => ['code' => 400, 'message' => $request],
			'data'       => [],
			'pagination' => [],
			'perpage'    => gemfindDT_getResultPerPage()
		];
		return $diamond;
	}

	$shapeValue    = $certificate = $fluorescence = $fancycolor = $colorcontent = $claritycontent =
		$cutcontent = $polishcontent = $symmetrycontent = $fancycolorcontent = $intintensitycontent = [];
	$shapesContent = $symmetrycontentContent = $certificatesContent = $fluorescenceContent =
		$fancycolorContent = $colorcontentContent = $claritycontentContent = $cutcontentContent =
		$polishcontentContent = $symmetrycontentContent = $fancycolorcontentContent =
		$intintensitycontentContent = $itemperpage = '';
	$hasvideo      = 'Yes';
	// Convert the Shapes list into gemfind form

	if (array_key_exists('diamond_shape', $request)) {
		foreach ($request["diamond_shape"] as $value) {
			$shapeValue[] = strtolower($value);
		}
		$shapesContent = implode(',', $shapeValue);
	}

	// Convert the Certificate array into gemfind form
	if (array_key_exists('diamond_certificates', $request)) {
		foreach ($request["diamond_certificates"] as $values) {
			$certificate[] = $values;
		}
		$certificatesContent = implode(',', $certificate);
	}
	// Convert the Fluorescence list into gemfind form
	if (array_key_exists('diamond_fluorescence', $request)) {
		foreach ($request["diamond_fluorescence"] as $value) {
			$fluorescence[] = strtolower($value);
		}
		$fluorescenceContent = implode(',', $fluorescence);
	}

	// Convert the color list into gemfind form
	if (array_key_exists('diamond_color', $request)) {
		foreach ($request["diamond_color"] as $value) {
			$colorcontent[] = strtolower($value);
		}
		$colorcontentContent = implode(',', $colorcontent);
	}

	// Convert the clarity list into gemfind form
	if (array_key_exists('diamond_clarity', $request)) {
		foreach ($request["diamond_clarity"] as $value) {
			$claritycontent[] = strtolower($value);
		}
		$claritycontentContent = implode(',', $claritycontent);
	}

	// Convert the Cut list into gemfind form
	if (array_key_exists('diamond_cut', $request)) {
		foreach ($request["diamond_cut"] as $value) {
			$cutcontent[] = strtolower($value);
		}
		$cutcontentContent = implode(',', $cutcontent);
	}

	// Convert the Polish list into gemfind form
	if (array_key_exists('diamond_polish', $request)) {
		foreach ($request["diamond_polish"] as $value) {
			$polishcontent[] = strtolower($value);
		}
		$polishcontentContent = implode(',', $polishcontent);
	}

	// Convert the Symmetry list into gemfind form
	if (array_key_exists('diamond_symmetry', $request)) {
		foreach ($request["diamond_symmetry"] as $value) {
			$symmetrycontent[] = strtolower($value);
		}
		$symmetrycontentContent = implode(',', $symmetrycontent);
	}


	// Convert the DiamondId list into gemfind form
	if (isset($request['did'])) {
		$did = $request['did'];
	} else {
		$did = '';
	}

	// echo'<pre>'; 
	// print_r($request);
	// exit;

	// Create the request array to sumbit to gemfind
	$requestData = [
		'shapes'                   => $shapesContent,
		'fluorescence_intensities' => $fluorescenceContent,
		'size_from'                => ($request["diamond_carats"]["from"]) ? $request["diamond_carats"]["from"] : '',
		'size_to'                  => ($request["diamond_carats"]["to"]) ? $request["diamond_carats"]["to"] : '',
		'color'                    => $colorcontentContent,
		'clarity'                  => $claritycontentContent,
		'cut'                      => $cutcontentContent,
		'polish'                   => $polishcontentContent,
		'symmetry'                 => $symmetrycontentContent,
		'price_from'               => ($request["price"]["from"]) ? str_replace(',', '', $request["price"]["from"]) : '',
		'price_to'                 => ($request["price"]["to"]) ? str_replace(',', '', $request["price"]["to"]) : '',
		'diamond_table_from'       => (intval($request["diamond_table"]["from"])) ? intval($request["diamond_table"]["from"]) : '',
		'diamond_table_to'         => (intval($request["diamond_table"]["to"])) ? intval($request["diamond_table"]["to"]) : '',
		'depth_percent_from'       => (intval($request["diamond_depth"]["from"])) ? intval($request["diamond_depth"]["from"]) : '',
		'depth_percent_to'         => (intval($request["diamond_depth"]["to"])) ? intval($request["diamond_depth"]["to"]) : '',
		'labs'                     => $certificatesContent,
		'page_number'              => ($request['currentpage']) ? $request['currentpage'] : '',
		'page_size'                => ($request['itemperpage']) ? $request['itemperpage'] : gemfindDT_getResultPerPage(),
		'sort_by'                  => ($request['orderby']) ? $request['orderby'] : '',
		'sort_direction'           => ($request['direction']) ? $request['direction'] : '',
		'did'                      => $did,
		'hasvideo'                 => $hasvideo,
		'Filtermode'               => ($request['filtermode']) ? $request['filtermode'] : 'navstandard',
		'diamondFilterRange' 		=> ($request['diamondfilter']) ? $request['diamondfilter'] : '',

	];


	if (isset($request['filtermode'])) {
		if ($request['filtermode'] != 'navstandard' && $request['filtermode'] != 'navlabgrown') {
			// Convert the Symmetry list into gemfind form			
			if (array_key_exists('diamond_fancycolor', $request)) {
				foreach ($request["diamond_fancycolor"] as $value) {
					$fancycolorcontent[] = strtolower($value);
				}
				$fancycolorcontentContent = implode(',', $fancycolorcontent);
			}

			// Convert the Symmetry list into gemfind form
			if (array_key_exists('diamond_intintensity', $request)) {
				foreach ($request["diamond_intintensity"] as $value) {
					$intintensitycontent[] = strtolower($value);
				}
				$intintensitycontentContent = implode(',', $intintensitycontent);
			}

			$fancyData   = [
				'FancyColor'   => $fancycolorcontentContent,
				'intIntensity' => $intintensitycontentContent
			];
			$requestData = array_merge($requestData, $fancyData);
		}
	}
	$result = gemfindDT_sendRequest($requestData);
	// echo '<pre>'; print_r($result); exit; 

	// get option api call
	$diamondsoptionapi = get_option('gemfind_diamond_link');
	$diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $diamondsoptionapi['diamondsoptionapi']));

	// $filterapi    = gemfindDT_sendRequest(array('filterapi' => $diamondsoptionapi['navigationapi']));

	// echo '<pre>';print_r($diamondsoption);exit; 
	$show_in_house_column = $diamondsoption[0][0]->show_In_House_Diamonds_Column_with_SKU;
	// echo '<pre>'; print_r($show_in_house_column); exit;
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	// echo '<pre>'; print_r($show_Certificate_in_Diamond_Search); exit;


	if ($request['orderby'] == 'Cut') {
		unset($request['orderby']);
		$request['orderby'] = 'shape';
	}
	if ($request['orderby'] == 'Size') {
		unset($request['orderby']);
		$request['orderby'] = 'caratWeight';
	}
	if ($request['orderby'] == 'FltPrice' || $request['orderby'] == 'Size' || $request['orderby'] == 'Depth' || $request['orderby'] == 'TableMeasure' || $request['orderby'] == 'Measurements' || $request['orderby'] == 'caratWeight') {
		$orderby = 'meta_value_num';
	} else {
		$orderby = 'meta_value';
	}
	if (isset($request['did']) && $request['did'] != '') {
		$meta_key   = '_sku';
		$meta_value = $request['did'];
	} else {
		$meta_key = $request['orderby'];
	}
	$paged = ($request['currentpage']) ? $request['currentpage'] : 1;
	$tax_query = array();
	$tax_query = array('relation' => 'AND');
	if ($filter_type == 'navstandard' || $filter_type == 'navlabgrown') {
		$meta_key = 'productType';
		if (isset($request['diamond_shape']) && !empty($request['diamond_shape'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_shape',
					'field'    => 'slug',
					'terms'    => $request['diamond_shape'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_color']) && !empty($request['diamond_color'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_color',
					'field'    => 'slug',
					'terms'    => $request['diamond_color'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_clarity']) && !empty($request['diamond_clarity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_clarity',
					'field'    => 'slug',
					'terms'    => $request['diamond_clarity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_symmetry']) && !empty($request['diamond_symmetry'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_symmetry',
					'field'    => 'slug',
					'terms'    => $request['diamond_symmetry'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_certificates']) && !empty($request['diamond_certificates'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_certificate',
					'field'    => 'slug',
					'terms'    => $request['diamond_certificates'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		$meta_query = array('relation' => 'AND');
		if (isset($request['diamond_carats']) && !empty($request['diamond_carats'])) {
			$meta_query[] = array(
				'key'     => 'caratWeight',
				'value'   => array($request['diamond_carats']['from'], $request['diamond_carats']['to']),
				'compare' => 'BETWEEN'
			);
		}
		if (isset($request['price']) && !empty($request['price'])) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array($request['price']['from'], $request['price']['to']),
				'compare' => 'BETWEEN'
			);
		}
		if (isset($request['diamond_depth']) && !empty($request['diamond_depth'])) {
			$meta_query[] = array(
				'key'     => 'depth',
				'value'   => floor($request['diamond_depth']['to'] + 1),
				'compare' => '<='
			);
		}

		if (isset($request['diamond_table']) && !empty($request['diamond_table'])) {
			$meta_query[] = array(
				'key'     => 'table',
				'value'   => floor($request['diamond_table']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['did']) && !empty($request['did'])) {
			$meta_query[] = array(
				'key'     => '_sku',
				'value'   => $request['did'],
				'compare' => '='
			);
		}
		$meta_query[] = array(
			'key'     => 'product_type',
			'value'   => 'gemfind',
			'compare' => '='
		);
		if ($filter_type == 'navlabgrown') {
			$meta_query[] = array(
				'key'     => 'productType',
				'value'   => 'labcreated',
				'compare' => '='
			);
		}
	} elseif ($filter_type == 'navfancycolored') {
		$meta_key = 'fancyColorIntensity';
		if (isset($request['diamond_shape']) && !empty($request['diamond_shape'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_shape',
					'field'    => 'slug',
					'terms'    => $request['diamond_shape'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_fancycolor']) && !empty($request['diamond_fancycolor'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_color',
					'field'    => 'slug',
					'terms'    => $request['diamond_fancycolor'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_clarity']) && !empty($request['diamond_clarity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_clarity',
					'field'    => 'slug',
					'terms'    => $request['diamond_clarity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_cut']) && !empty($request['diamond_cut'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_cut',
					'field'    => 'slug',
					'terms'    => $request['diamond_cut'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_symmetry']) && !empty($request['diamond_symmetry'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_symmetry',
					'field'    => 'slug',
					'terms'    => $request['diamond_symmetry'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_certificates']) && !empty($request['diamond_certificates'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_certificate',
					'field'    => 'slug',
					'terms'    => $request['diamond_certificates'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_intintensity']) && !empty($request['diamond_intintensity'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_intensity',
					'field'    => 'slug',
					'terms'    => $request['diamond_intintensity'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_polish']) && !empty($request['diamond_polish'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_polish',
					'field'    => 'slug',
					'terms'    => $request['diamond_polish'],
					'operator' => 'IN'
				);
		}
		if (isset($request['diamond_fluorescence']) && !empty($request['diamond_fluorescence'])) {
			$tax_query[] =
				array(
					'taxonomy' => 'pa_gemfind_fancy_fluorescence',
					'field'    => 'slug',
					'terms'    => $request['diamond_fluorescence'],
					'operator' => 'IN'
				);
		}
		$meta_query = array('relation' => 'AND');
		if (isset($request['diamond_carats']) && !empty($request['diamond_carats'])) {
			$meta_query[] = array(
				'key'     => 'caratWeight',
				'value'   => $request['diamond_carats']['to'],
				'compare' => '<='
			);
		}
		if (isset($request['price']) && !empty($request['price'])) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => $request['price']['to'],
				'compare' => '<='
			);
		}
		if (isset($request['diamond_depth']) && !empty($request['diamond_depth'])) {
			$meta_query[] = array(
				'key'     => 'depth',
				'value'   => floor($request['diamond_depth']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['diamond_table']) && !empty($request['diamond_table'])) {
			$meta_query[] = array(
				'key'     => 'table',
				'value'   => floor($request['diamond_table']['to'] + 1),
				'compare' => '<='
			);
		}
		if (isset($request['did']) && !empty($request['did'])) {
			$meta_query[] = array(
				'key'     => '_sku',
				'value'   => $request['did'],
				'compare' => '='
			);
		}
		$meta_query[] = array(
			'key'     => 'product_type',
			'value'   => 'gemfind',
			'compare' => '='
		);
	}

	if ($options['load_from_woocommerce'] == '1') {
		unset($result['diamonds']);
		unset($result['total']);
		$loop = $args = new WP_Query(array(
			'post_type'      => array('product'),
			'post_status'    => 'publish',
			'posts_per_page' => $request['itemperpage'],
			'paged'          => $paged,
			'meta_key'       => $meta_key,
			'tax_query'      => $tax_query,
			'meta_query'     => $meta_query,
			'orderby'        => $request['orderby'],
			'order'          => $request['direction']
		));
		while ($loop->have_posts()) : $loop->the_post();
			global $product;
			$isFancy                           = get_post_meta($product->get_id(), 'fancyColorIntensity', true);
			$product_meta                      = get_post_meta($product->get_ID(), '', false);
			$product_attributes                = unserialize($product_meta['_product_attributes'][0]);
			$diamondInfo                       = array();
			$diamondInfo['diamondId']          = $product_meta['_sku'][0];
			$diamondInfo['diamondImage']       = get_the_post_thumbnail_url();
			$diamondInfo['biggerDiamondimage'] = $product_meta['image2'][0];
			$filter_type                       = (isset($request['filtermode']) && $request['filtermode'] != '') ? $request['filtermode'] : 'navstandard';
			if ($filter_type == 'navstandard' || $filter_type == 'navlabgrown') {
				$shape = explode(',', $product->get_attribute('pa_gemfind_shape'));
				if (is_array($shape)) {
					$diamondInfo['shape'] = $shape[0];
				} else {
					$diamondInfo['shape'] = $product->get_attribute('pa_gemfind_shape');
				}
				$polish = explode(',', $product->get_attribute('pa_gemfind_polish'));
				if (is_array($polish)) {
					$diamondInfo['polish'] = $polish[0];
				} else {
					$diamondInfo['polish'] = $product->get_attribute('pa_gemfind_polish');
				}
				$cert = explode(',', $product->get_attribute('pa_gemfind_certificate'));
				if (is_array($cert)) {
					$diamondInfo['cert'] = $cert[0];
				} else {
					$diamondInfo['cert'] = $product->get_attribute('pa_gemfind_certificate');
				}
				$clarity = explode(',', $product->get_attribute('pa_gemfind_clarity'));
				if (is_array($clarity)) {
					$diamondInfo['clarity'] = $clarity[0];
				} else {
					$diamondInfo['clarity'] = $product->get_attribute('pa_gemfind_clarity');
				}
				$color = explode(',', $product->get_attribute('pa_gemfind_color'));
				if (is_array($color)) {
					$diamondInfo['color'] = $color[0];
				} else {
					$diamondInfo['color'] = $product->get_attribute('pa_gemfind_color');
				}
				$symmetry = explode(',', $product->get_attribute('pa_gemfind_symmetry'));
				if (is_array($symmetry)) {
					$diamondInfo['symmetry'] = $symmetry[0];
				} else {
					$diamondInfo['symmetry'] = $product->get_attribute('pa_gemfind_symmetry');
				}
				$fluorescence = explode(',', $product->get_attribute('pa_gemfind_fluorescence'));
				if (is_array($fluorescence)) {
					$diamondInfo['fluorescence'] = $fluorescence[0];
				} else {
					$diamondInfo['fluorescence'] = $product->get_attribute('pa_gemfind_fluorescence');
				}
				$cut = explode(',', $product->get_attribute('pa_gemfind_cut'));
				if (is_array($cut)) {
					$diamondInfo['cut'] = $cut[0];
				} else {
					$diamondInfo['cut'] = $product->get_attribute('pa_gemfind_cut');
				}
				$diamondInfo['carat'] = $product_meta['caratWeight'][0];
				$diamondInfo['depth'] = $product_meta['depth'][0];
				$diamondInfo['table'] = $product_meta['table'][0];
				$diamondInfo['price'] = $product_meta['_price'][0];
			} elseif ($filter_type == 'navfancycolored') {
				$isFancy = get_post_meta($product->get_id(), 'fancyColorIntensity', true);
				if (isset($isFancy) && $isFancy != '') {
					$shape = explode(',', $product->get_attribute('pa_gemfind_fancy_shape'));
					if (is_array($shape)) {
						$diamondInfo['shape'] = $shape[0];
					} else {
						$diamondInfo['shape'] = $product->get_attribute('pa_gemfind_fancy_shape');
					}
					$polish = explode(',', $product->get_attribute('pa_gemfind_fancy_polish'));
					if (is_array($polish)) {
						$diamondInfo['polish'] = $polish[0];
					} else {
						$diamondInfo['polish'] = $product->get_attribute('pa_gemfind_fancy_polish');
					}
					$cert = explode(',', $product->get_attribute('pa_gemfind_fancy_certificate'));
					if (is_array($cert)) {
						$diamondInfo['cert'] = $cert[0];
					} else {
						$diamondInfo['cert'] = $product->get_attribute('pa_gemfind_fancy_certificate');
					}
					$clarity = explode(',', $product->get_attribute('pa_gemfind_fancy_clarity'));
					if (is_array($clarity)) {
						$diamondInfo['clarity'] = $clarity[0];
					} else {
						$diamondInfo['clarity'] = $product->get_attribute('pa_gemfind_fancy_clarity');
					}
					$color = explode(',', $product->get_attribute('pa_gemfind_fancy_color'));
					if (is_array($color)) {
						$diamondInfo['color'] = $color[0];
					} else {
						$diamondInfo['color'] = $product->get_attribute('pa_gemfind_fancy_color');
					}
					$symmetry = explode(',', $product->get_attribute('pa_gemfind_fancy_symmetry'));
					if (is_array($symmetry)) {
						$diamondInfo['symmetry'] = $symmetry[0];
					} else {
						$diamondInfo['symmetry'] = $product->get_attribute('pa_gemfind_fancy_symmetry');
					}
					$fluorescence = explode(',', $product->get_attribute('pa_gemfind_fancy_fluorescence'));
					if (is_array($fluorescence)) {
						$diamondInfo['fluorescence'] = $fluorescence[0];
					} else {
						$diamondInfo['fluorescence'] = $product->get_attribute('pa_gemfind_fancy_fluorescence');
					}
					$diamondInfo['carat'] = $product_meta['caratWeightFancy'][0];
					$diamondInfo['depth'] = $product_meta['depthFancy'][0];
					$diamondInfo['table'] = $product_meta['tableFancy'][0];
					$diamondInfo['price'] = $product_meta['FltPriceFancy'][0];
				}
			}
			$diamondInfo['measurement']    = $product_meta['measurement'][0];
			$diamondInfo['certificateUrl'] = $product_meta['certificateUrl'][0];
			$diamondInfo['gridle']         = $product_meta['gridle'][0];
			$diamondInfo['culet']          = $product_meta['culet'][0];
			$result['diamonds'][]          = (object) $diamondInfo;
		endwhile;
		wp_reset_query();
		$result['total'] = $loop->found_posts;
	}


	$num = ceil($result['total'] / gemfindDT_getResultPerPage());
	// echo '<pre>'; print_r($result); exit; 




	if ($result['diamonds'] != null || $result['total'] != 0) {
		$count = 0;
		if ($request['currentpage'] > 1) {
			$count = ($request['itemperpage']) ? $request['itemperpage'] : gemfindDT_getResultPerPage() * ($request['currentpage'] - 1);
		}

		$diamond = [
			'meta'       => ['code' => 200],
			'data'       => $result['diamonds'],
			'pagination' => [
				'currentpage' => $request['currentpage'],
				'count'       => $count,
				'limit'       => count($result['diamonds']),
				'total'       => $result['total']
			],
			'perpage'    => ($request['itemperpage']) ? $request['itemperpage'] : gemfindDT_getResultPerPage()
		];
	} else {
		$diamond = [
			'meta'       => ['code' => 404, 'message' => "No Product Found"],
			'data'       => [],
			'pagination' => ['total' => $result['total']],
			'perpage'    => gemfindDT_getResultPerPage()
		];
	}
	$headers = array("Content-Type:application/json");



	// get option api call
	$diamondsfilternapi = get_option('gemfind_diamond_link');

	// $diamondsoption    = gemfindDT_sendRequest(array('filterapi' => $diamondsfilternapi['filterapi']));
	$dealerID = $diamondsfilternapi['dealerid'];
	if ($dealerID) {
		if (sanitize_text_field($_POST['filter_type']) == 'navstandard') {
			$requestUrl = $diamondsfilternapi['filterapi'] . 'DealerID=' . $dealerID . '&IsLabGrown=false';
		} else if (sanitize_text_field($_POST['filter_type']) == 'navlabgrown') {
			$requestUrl = $diamondsfilternapi['filterapi'] . 'DealerID=' . $dealerID . '&IsLabGrown=true';
		} else if (sanitize_text_field($_POST['filter_type']) == 'navfancycolored') {
			$requestUrl = $diamondsfilternapi['filterapifancy'] . 'DealerID=' . $dealerID;
		} else {
			$requestUrl = $diamondsfilternapi['filterapi'] . 'DealerID=' . $dealerID;
		}
	} else {
		return;
	}

	// $requestUrl = $diamondsfilternapi['filterapi'] . 'DealerID=' .$dealerid;
	// echo '<pre>'; print_r($_POST['filter_type']); exit;
	$results = gemfindDT_getCurlData($requestUrl, $headers);
	// echo '<pre>'; print_r(results); exit;


	// $alldata = get_option('gemfind_diamond_link');
	// $filterapi    = gemfindDT_sendRequest($alldata['filterapi']);


	// echo'<pre>';
	// print_r($results);
	// exit;

	$html = '';
	// $diamond_data = getDiamondFilters();
	// echo'<pre>';
	// print_r($diamond_data);
	// exit;
	if (isset($diamond['pagination']['total']) && $diamond['pagination']['total'] != 0) : ?>
		<?php
		$login_link = get_site_url() . '/my-account/';
		$grid_active_class = '';
		$list_active_class = '';
		// check weater cookies is set for view grid / list
		if (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'grid') {
			$cls_hide_grid = 'cls-for-hide';
			$grid_active_class = 'active';
		} elseif (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'list') {
			$cls_hide_list = 'cls-for-hide';
			$list_active_class = 'active';
		}
		// code for check if cookies is empty then get default view from admin settings		
		if (!isset($back_cookie_data['viewmode']) && $options['default_view'] == 'grid') {
			$grid_active_class = 'active';
			$cls_hide_grid = 'cls-for-hide';
		}
		if (!isset($back_cookie_data['viewmode']) && ($options['default_view'] == 'list' || empty($options['default_view']))) {
			$list_active_class = 'active';
			$cls_hide_list = 'cls-for-hide';
		}
		?>
		<div class='search-details no-padding'>
			<div class='searching-result'>
				<div class='number-of-search'>
					<?php $dia_count = 0; ?>
					<p><strong><?php echo esc_html(number_format($diamond['pagination']['total'])); ?></strong>Similar Diamonds | </p>
					<p>Compare Items (<span id="totaldiamonds"><?php echo esc_html($dia_count); ?></span>)</p>
				</div>
				<div class='search-in-table' id='searchintable'>
					<input type='text' name='searchdidfield' id='searchdidfield' placeholder='Search Diamond Stock #'><a href='javascript:;' title='close' id='resetsearchdata'>X</a>
					<button id='searchdid' title='Search Diamond'></button>
				</div>
				<div class='view-or-search-result'>
					<div class='change-view-result'>
						<ul>
							<li class='grid-view' data-toggle="tooltip" data-placement="top" title="Grid view">
								<a href='javascript:;' class="<?php echo esc_url($grid_active_class); ?>">Grid view</a>
							</li>
							<li class='list-view' data-toggle="tooltip" data-placement="top" title="List view">
								<a href='javascript:;' class="<?php echo esc_url($list_active_class); ?>">list view</a>
							</li>
						</ul>
						<div class='item-page'>
							<p class='leftpp'>Per Page</p>

							<select class='pagesize SumoUnder' id='pagesize' name='pagesize' onchange='gemfindDT_ItemPerPage(this)' tabindex='-1'>
								<?php
								$all_options = gemfindDT_getAllOptions();
								foreach ($all_options as $value) {
								?>
									<option value='<?php echo esc_html($value['value']); ?>'><?php echo esc_html($value['label']); ?></option>
								<?php
								}	?>
							</select>
						</div>
						<?php if ($show_in_house_column) { ?>
							<div class='item-page'>
								<p class='leftpp'>Diamond Filter</p>
								<?php //echo '<pre>'; print_r($results[1][0]->diamondFilterRange); exit; 
								?>
								<select class='diamondfilter SumoUnderd' id='diamondfilter' name='diamondfilter' onchange='gemfindDT_DimondFilter(this)' tabindex='-1'>
									<?php foreach ($results[1][0]->diamondFilterRange as $diamond_value) { ?>
										<option value='<?php echo esc_html($diamond_value->filterName); ?>'><?php echo esc_html($diamond_value->filterName); ?></option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>

						<div class='grid-view-sort cls-for-hide'>
							<select name='gridview-orderby' id='gridview-orderby' class='gridview-orderby SumoUnder' onchange='gemfindDT_gridSort(this)' tabindex='-1'>
								<option value='Cut' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Cut') ? 'selected' : '' ?>>Shape</option>
								<option value='Size' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Size') ? 'selected' : '' ?>>Carat</option>
								<option value='Color' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Color') ? 'selected' : '' ?>>Color</option>
								<option value='ClarityID' <?php echo (esc_html($back_cookie_data['orderBy']) == 'ClarityID') ? 'selected' : '' ?>>Clarity</option>
								<option value='CutGrade' <?php echo (esc_html($back_cookie_data['orderBy']) == 'CutGrade') ? 'selected' : '' ?>>Cut</option>
								<option value='Depth' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Depth') ? 'selected' : '' ?>>Depth</option>
								<option value='TableMeasure' <?php echo (esc_html($back_cookie_data['orderBy']) == 'TableMeasure') ? 'selected' : '' ?>>Table</option>
								<option value='Polish' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Polish') ? 'selected' : '' ?>>Polish</option>
								<option value='Symmetry' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Symmetry') ? 'selected' : '' ?>>Symmetry</option>
								<option value='Measurements' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Measurements') ? 'selected' : '' ?>>Measurement</option>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<option value='Certificate' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Certificate') ? 'selected' : '' ?>>Certificate</option>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<option value='Inhouse' <?php echo (esc_html($back_cookie_data['orderBy']) == 'Inhouse') ? 'selected' : '' ?>>In House</option>
								<?php } ?>
								<option value='FltPrice' <?php echo (esc_html($back_cookie_data['orderBy']) == 'FltPrice' || empty(esc_html($back_cookie_data['orderBy']))) ? 'selected' : '' ?>>Price</option>
							</select>
							<div class='gridview-dir-div'>
								<a href='javascript:;' onclick='gemfindDT_gridDire("DESC")' id='ASC' title='Set Descending Direction' class='<?php echo (esc_html($back_cookie_data['direction']) == 'ASC' || empty(esc_html($back_cookie_data['direction']))) ? 'active' : '' ?>'>ASC</a>
								<a href='javascript:;' title='Set Ascending Direction' class="<?php echo (esc_html($back_cookie_data['direction']) == 'DESC') ? 'active' : '' ?>" onclick='gemfindDT_gridDire("ASC")' id='DESC'>DESC</a>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class='search-details no-padding'>
			<div class='table-responsive <?php echo esc_html($cls_hide_grid); ?>' id='list-mode'>
				<table class='table' id='result_table'>
					<thead class="table_header_wrapper">
						<tr>
							<th scope='col'>javascript:;</th>
							<th scope='col' class='table-sort' title="Shape Sorting Asc/Desc" id='Cut' onclick='gemfindDT_fnSort("Cut");'>Shape</th>
							<th scope='col' class='table-sort' title="Carat Sorting Asc/Desc" id='Size' onclick='gemfindDT_fnSort("Size");'>Carat</th>
							<th scope='col' class='table-sort' title="Color Sorting Asc/Desc" id='Color' onclick='gemfindDT_fnSort("Color");'>Color</th>
							<?php if ($filter_type == 'navfancycolored') { ?>
								<th scope='col' class='table-sort' title="Intensity Sorting Asc/Desc" id='FancyColorIntensity' onclick='gemfindDT_fnSort("FancyColorIntensity");'>Intensity</th>
							<?php } ?>
							<th scope='col' class='table-sort' title="Clarity Sorting Asc/Desc" id='ClarityID' onclick='gemfindDT_fnSort("ClarityID");'>Clarity</th>
							<th scope='col' class='table-sort' title="Cut Sorting Asc/Desc" id='CutGrade' onclick='gemfindDT_fnSort("CutGrade");'>Cut</th>
							<th scope='col' class='table-sort' id='Depth' title="Depth Sorting Asc/Desc" onclick='gemfindDT_fnSort("Depth");'>Depth</th>
							<th scope='col' class='table-sort' id='TableMeasure' title="Table Sorting Asc/Desc" onclick='gemfindDT_fnSort("TableMeasure");'>Table</th>
							<th scope='col' class='table-sort' id='Polish' title="Polish Sorting Asc/Desc" onclick='gemfindDT_fnSort("Polish");'>Polish</th>
							<th scope='col' class='table-sort' id='Symmetry' title="Symmetry Sorting Asc/Desc" onclick='gemfindDT_fnSort("Symmetry");'>Sym.</th>
							<th scope='col' class='table-sort' id='Measurements' title="Measurement Sorting Asc/Desc" onclick='gemfindDT_fnSort("Measurements");'>Measurement</th>
							<?php if ($show_Certificate_in_Diamond_Search) { ?>
								<th scope='col' class='table-sort' id='Certificate' title="Certificate Sorting Asc/Desc" onclick='gemfindDT_fnSort("Certificate");'>Cert.</th>
							<?php } ?>
							<?php if ($show_in_house_column) { ?>
								<th scope='col' class='table-sort' title="In House Sorting Asc/Desc" id='inhouse' onclick='gemfindDT_fnSort("Inhouse");'>In House</th>
							<?php } ?>


							<th scope='col' class='table-sort' id='FltPrice' title="Price Sorting Asc/Desc" onclick='gemfindDT_fnSort("FltPrice");'> Price (<?php echo isset($diamond['data']) ? $diamond['data'][0]->currencyFrom : ''; ?>)

							</th>


							<!-- <th scope='col' class='video-data' id='dia_video'>Video</th> -->
							<th scope='col' class='view-data' id='dia_view'>View</th>
						</tr>
					</thead>

					<tbody>
						<?php

						foreach ($diamond['data'] as $diamondData) {
							//  echo '<pre>'; print_r($diamond['data']); exit;
							if ($diamondData->fancyColorMainBody) {
								if ($diamondData->diamondImage) {
									$imageurl = $diamondData->diamondImage;
								} else {
									$imageurl = $noimageurl;
								}
								// for showing color value in column of color ( fancycolor ) 
								//$color_to_display = $diamondData->fancyColorIntensity . ' ' . $diamondData->fancyColorMainBody;
								$color_to_display = $diamondData->fancyColorMainBody;
								$Intensity_to_display = $diamondData->fancyColorIntensity;
							} else {
								if ($diamondData->biggerDiamondimage) {
									$imageurl = $diamondData->biggerDiamondimage;
								} else {
									$imageurl = $noimageurl;
								}
								// for showing color value in column of color ( standard tab )
								$color_to_display = $diamondData->color;
							}
							if ($diamondData->isLabCreated) {
								$type = 'labcreated';
							} elseif ($filter_type == 'navfancycolored') {
								$type = 'fancydiamonds';
							} elseif (strpos(sanitize_url($_SERVER["HTTP_REFERER"]), "navfancycolored") !== false) {
								$type = 'fancydiamonds';
							} else {
								$type = '';
							}
							if (isset($diamondData->shape)) {
								$urlshape = str_replace(' ', '-', $diamondData->shape) . '-shape-';
							} elseif ($filter_type == 'navfancycolored') {
								$type = 'fancydiamonds';
							} else {
								$urlshape = '';
							}
							if (isset($diamondData->carat)) {
								$urlcarat = str_replace(' ', '-', $diamondData->carat) . '-carat-';
							} else {
								$urlcarat = '';
							}
							if (isset($diamondData->color)) {
								$urlcolor = str_replace(' ', '-', $diamondData->color) . '-color-';
							} else {
								$urlcolor = '';
							}
							if (isset($diamondData->clarity)) {
								$urlclarity = str_replace(' ', '-', $diamondData->clarity) . '-clarity-';
							} else {
								$urlclarity = '';
							}
							if (isset($diamondData->cut)) {
								$urlcut = str_replace(' ', '-', $diamondData->cut) . '-cut-';
							} else {
								$urlcut = '';
							}
							if (isset($diamondData->cert)) {
								$urlcert = str_replace(' ', '-', $diamondData->cert) . '-certificate-';
							} else {
								$urlcert = '';
							}
							$urlstring      = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamondData->diamondId);
							$diamondviewurl = '';
							$diamondviewurl = gemfindDT_getDiamondViewUrl($urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop) . '/' . $type;	?>
							<tr id="<?php echo esc_html($diamondData->diamondId); ?>" class="<?php echo esc_html($class); ?>">
								<th scope="row" class="table-selecter">
									<input type="checkbox" name="compare" value="<?php echo esc_html($diamondData->diamondId); ?>">
									<div class="state"><label>Fill</label></div>
								</th>
								<td class="Cutcol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<span class="imagecheck" data-src="<?php echo esc_html($imageurl); ?>" data-srcbig="<?php echo esc_html($diamondData->biggerDiamondimage); ?>" data-id="<?php echo esc_html($diamondData->diamondId); ?>"></span>
									<img src="<?php echo esc_html($loaderimg); ?>" width="21" height="18" alt="<?php echo esc_html($diamondData->shape) . ' ' . esc_html($diamondData->carat) . ' CARAT'; ?>" title="<?php echo esc_html($diamondData->shape) . ' ' . esc_html($diamondData->carat) . ' CARAT'; ?>">
									<span class="shape-name"><?php echo esc_html($diamondData->shape); ?></span>
								</td>
								<td class="Sizecol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo esc_html($diamondData->carat); ?>
								</td>
								<td class="Colorcol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo (esc_html($color_to_display) != '') ? esc_html($color_to_display) : '-'; ?>
								</td>
								<?php if ($diamondData->fancyColorMainBody) { ?>
									<td class="Intensitycol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
										<?php echo (esc_html($Intensity_to_display) != '') ? esc_html($Intensity_to_display) : '-'; ?>
									</td>
								<?php } ?>
								<td class="ClarityIDcol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo esc_html(($diamondData->clarity) != '') ? esc_html($diamondData->clarity) : '-'; ?>
								</td>
								<td class="CutGradecol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php
									if ($diamondData->cut == 'Good') {
										echo esc_html('G');
									} else if ($diamondData->cut == 'Very good') {
										echo esc_html('VG');
									} else if ($diamondData->cut == 'Excellent') {
										echo esc_html('Ex');
									} else if ($diamondData->cut == 'Fair') {
										echo esc_html('F');
									} else if ($diamondData->cut == 'Ideal') {
										echo esc_html('I');
									} else {
										echo esc_html('-');
									}					?>
								</td>
								<td class="Depthcol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo (esc_html($diamondData->depth) != '') ? esc_html($diamondData->depth) : '-'; ?>
								</td>
								<td class="TableMeasurecol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo (esc_html($diamondData->table) != '') ? esc_html($diamondData->table) : '-'; ?>
								</td>
								<td class="Polishcol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php
									if ($diamondData->polish == 'Good') {
										echo esc_html('G');
									} else if ($diamondData->polish == 'Very good') {
										echo esc_html('VG');
									} else if ($diamondData->polish == 'Excellent') {
										echo esc_html('Ex');
									} else if ($diamondData->polish == 'Fair') {
										echo esc_html('F');
									} else {
										echo esc_html('-');
									} 					?>
								</td>
								<td class="Symmetrycol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php
									if ($diamondData->symmetry == 'Good') {
										echo esc_html('G');
									} else if ($diamondData->symmetry == 'Very good') {
										echo esc_html('VG');
									} else if ($diamondData->symmetry == 'Excellent') {
										echo esc_html('Ex');
									} else if ($diamondData->symmetry == 'Fair') {
										echo esc_html('F');
									} else {
										echo esc_html('-');
									}					?>
								</td>
								<td class="Measurementscol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<?php echo esc_html($diamondData->measurement); ?>
								</td>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<td class="Certificatecol"><a href="javascript:void(0)" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location(.href='<?php echo esc_html($diamondviewurl); ?>'"><?php echo esc_html($diamondData->cert); ?></a>
									</td>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<td class="inhousecol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
										<?php echo esc_html($diamondData->inhouse); ?>
									</td>
								<?php } ?>


								<td class="FltPricecol" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>'); ">
									<?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
										<?php
										$dprice = $diamondData->fltPrice;
										$dprice = str_replace(',', '', $dprice);
										if ($diamondData->showPrice == true) {
											if ($options['price_row_format'] == 'left') {

												if ($diamondData->currencyFrom == 'USD') {

													echo "$" . esc_html(number_format($dprice));
												} else {

													echo esc_html(number_format($dprice)) . ' ' . esc_html($diamondData->currencySymbol) . ' ' . esc_html($diamondData->currencyFrom);
												}
											} else {

												if ($diamondData->currencyFrom == 'USD') {

													echo "$" . esc_html(number_format($dprice));
												} else {

													echo $diamondData->currencyFrom . ' ' . $diamondData->currencySymbol . ' ' . number_format($dprice);
												}
											}
										} else {
											echo esc_html('Call');
										} ?>
									<?php } else { ?>
										<a href="<?php echo esc_html($login_link); ?>" style="color:#0072ff" ;> Login </a>
									<?php }  ?>
								</td>

								
								<!-- <td class="view-data dia_viewcol" onclick="gemfindDT_SetBackValue('<?php //echo esc_html($diamondData->diamondId); ?>'); location.href='<?php echo esc_html($diamondviewurl); ?>'">
									<a href="javascript:;" title="View Diamond">
										<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="29px" height="17px" viewBox="0 0 72.767 72.766" style="enable-background:new 0 0 72.767 72.766;" xml:space="preserve">
											<g>
												<g id="Eye_Outline">
													<g>
														<path d="M72.163,34.225C71.6,33.292,58.086,11.352,37.48,11.352h-2.195c-20.605,0-34.117,21.94-34.681,22.873
											c-0.805,1.323-0.805,2.993,0,4.316c0.564,0.94,14.076,22.873,34.682,22.873h2.195c20.604,0,34.118-21.933,34.683-22.873
											C72.968,37.218,72.968,35.548,72.163,34.225z M37.48,53.141h-2.195c-12.696,0-22.625-11.793-26.242-16.758
											c3.621-4.971,13.546-16.758,26.242-16.758h2.195c12.7,0,22.632,11.802,26.246,16.766C60.125,41.363,50.237,53.141,37.48,53.141z
											M36.383,29.66c-3.666,0-6.633,3.016-6.633,6.724c0,3.716,2.967,6.725,6.633,6.725c3.664,0,6.635-3.009,6.635-6.725
											C43.018,32.675,40.047,29.66,36.383,29.66z"></path>
													</g>
												</g>
											</g>
										</svg>
									</a>
								</td> -->
								
								<td class="view-data dia_viewcol" onmouseover="onMouseOverMoreInfo(this)" onmouseout="onMouseOutMoreInfo(this)">
								    <a href="javascript:;" title="More Info">
								        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
								    </a>

								    <div class="icon-list">
								        <a href="javascript:;" title="View Diamond" onclick="gemfindDT_SetBackValue('<?php echo $diamondData->diamondId; ?>'); location.href='<?php echo $diamondviewurl; ?>'">
								            <svg fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
												 viewBox="0 0 512 512" xml:space="preserve">
											<g>
												<g>
													<path d="M256,189.079c-36.871,0.067-66.853,30.049-66.92,66.923c0.068,36.871,30.049,66.853,66.92,66.92
														c36.871-0.067,66.853-30.049,66.92-66.92C322.853,219.128,292.871,189.147,256,189.079z M256,289.461
														c-18.48,0-33.46-14.98-33.46-33.46c0-18.48,14.98-33.46,33.46-33.46s33.46,14.98,33.46,33.46
														C289.46,274.481,274.48,289.461,256,289.461z"/>
												</g>
											</g>
											<g>
												<g>
													<path d="M509.082,246.729C451.986,169.028,353.822,89.561,256,89.231c-98.014,0.33-196.379,80.332-253.082,157.498
														c-3.89,5.576-3.89,12.965,0,18.541C60.015,342.972,158.179,422.44,256,422.769c98.014-0.329,196.38-80.332,253.082-157.498
														C512.973,259.693,512.973,252.305,509.082,246.729z M256,356.379c-55.407-0.027-100.351-44.974-100.38-100.378
														c0.029-55.405,44.975-100.354,100.38-100.38c55.407,0.027,100.351,44.974,100.38,100.38
														C356.351,311.406,311.407,356.353,256,356.379z"/>
												</g>
											</g>
											</svg>
								        </a>
								        <?php if ($diamondData->hasVideo == true && !empty($diamondData->videoFileName)) { ?>
								        <a href="javascript:;" title="Watch Video"  class="triggerVideo" data-id="<?php echo $diamondData->diamondId; ?>" onclick="gemfindDT_showModaldblink()">
								          
								            <svg fill="#000000" width="20px" height="20px" viewBox="-4 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
												<title>video</title>
												<path d="M15.5 13.219l6.844-3.938c0.906-0.531 1.656-0.156 1.656 0.938v11.625c0 1.063-0.75 1.5-1.656 0.969l-6.844-3.969v2.938c0 1.094-0.875 1.969-1.969 1.969h-11.625c-1.063 0-1.906-0.875-1.906-1.969v-11.594c0-1.094 0.844-1.938 1.906-1.938h11.625c1.094 0 1.969 0.844 1.969 1.938v3.031z"></path>
												</svg>
								        </a>
								    <?php } ?>
								        <a href="javascript:;" title="Additional Information" data-id="<?php echo $diamondData->diamondId; ?>" onclick="showAdditionalInformation('<?php echo $diamondData->diamondId; ?>')">
								            <svg height="20px" width="20px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
												 viewBox="0 0 512 512"  xml:space="preserve">											
												<g>
													<path class="st0" d="M290.671,135.434c37.324-3.263,64.949-36.175,61.663-73.498c-3.241-37.324-36.152-64.938-73.476-61.675
														c-37.324,3.264-64.949,36.164-61.686,73.488C220.437,111.096,253.348,138.698,290.671,135.434z"/>
													<path class="st0" d="M311.31,406.354c-16.134,5.906-43.322,22.546-43.322,22.546s20.615-95.297,21.466-99.446
														c8.71-41.829,33.463-100.86-0.069-136.747c-23.35-24.936-53.366-18.225-79.819,7.079c-17.467,16.696-26.729,27.372-42.908,45.322
														c-6.55,7.273-9.032,14.065-5.93,24.717c3.332,11.515,16.8,17.226,28.705,12.871c16.134-5.895,43.3-22.534,43.3-22.534
														s-12.595,57.997-18.869,87c-0.874,4.137-36.06,113.292-2.505,149.18c23.35,24.949,53.343,18.226,79.819-7.066
														c17.467-16.698,26.729-27.373,42.908-45.334c6.55-7.263,9.009-14.054,5.93-24.706C336.66,407.733,323.215,402.01,311.31,406.354z"
														/>
												</g>
											</svg>
								        </a>
								    </div>
								</td>
							</tr>
						<?php  } 	?>
					</tbody>
				</table>
			</div>
			<div class='search-view-grid <?php echo (isset($cls_hide_list)) ? $cls_hide_list : '';
											echo (!isset($cls_hide_list) && !isset($cls_hide_grid)) ? 'cls-for-hide' : ''; ?>' id='grid-mode'>
				<div class='grid-product-listing'>
					<?php
					// $file = 'diamondlink_api_log.txt';
					// file_put_contents($file, $diamond['data']);

					foreach ($diamond['data'] as $diamondData) {
						if ($diamondData->fancyColorMainBody) {
							if ($diamondData->diamondImage) {
								$imageurl = $diamondData->diamondImage;
							} else {
								$imageurl = $noimageurl;
							}
							// for showing color value in column of color ( fancycolor ) 
							$color_to_display = $diamondData->fancyColorIntensity . ' ' . $diamondData->fancyColorMainBody;
						} else {
							if ($diamondData->biggerDiamondimage) {
								$imageurl = $diamondData->biggerDiamondimage;
							} else {
								$imageurl = $noimageurl;
							}
							// for showing color value in column of color ( standard tab )
							$color_to_display = $diamondData->color;
						}
						if ($diamondData->isLabCreated) {
							$type = 'labcreated';
						} elseif ($filter_type == 'navfancycolored') {
							$type = 'fancydiamonds';
						} else {
							$type = '';
						}
						if (isset($diamondData->shape)) {
							$urlshape = str_replace(' ', '-', $diamondData->shape) . '-shape-';
						} else {
							$urlshape = '';
						}
						if (isset($diamondData->carat)) {
							$urlcarat = str_replace(' ', '-', $diamondData->carat) . '-carat-';
						} else {
							$urlcarat = '';
						}
						if (isset($diamondData->color)) {
							$urlcolor = str_replace(' ', '-', $diamondData->color) . '-color-';
						} else {
							$urlcolor = '';
						}
						if (isset($diamondData->clarity)) {
							$urlclarity = str_replace(' ', '-', $diamondData->clarity) . '-clarity-';
						} else {
							$urlclarity = '';
						}
						if (isset($diamondData->cut)) {
							$urlcut = str_replace(' ', '-', $diamondData->cut) . '-cut-';
						} else {
							$urlcut = '';
						}
						if (isset($diamondData->cert)) {
							$urlcert = str_replace(' ', '-', $diamondData->cert) . '-certificate-';
						} else {
							$urlcert = '';
						}
						$urlstring      = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamondData->diamondId);
						$diamondviewurl = '';
						$diamondviewurl = gemfindDT_getDiamondViewUrl($urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop) . '/' . $type;
						$class = ($diamondData->diamondId == $back_cookie_data['did']) ? "selected_grid" : "";
						$loaderimg = DIAMOND_LINK_URL . "assets/images/loader-2.gif"; ?>

						<div class="search-product-grid <?php echo esc_html($class); ?>" id="<?php echo esc_html($diamondData->diamondId); ?>">
							<?php if ($diamondData->hasVideo == true) { ?>
								<a href="javascript:;" data-id="<?php echo esc_html($diamondData->diamondId); ?>" class="triggerVideo" onclick="gemfindDT_showModaldblink()"> <i class="fa fa-video-camera"> </i> </a>
							<?php } ?>

							<div class="product-images">
								<?php
								if ($filter_type == 'navfancycolored') {
									$diamondImage = $diamondData->diamondImage;
								} else {
									$diamondImage = $diamondData->biggerDiamondimage;
								}	?>
								<img src="<?php echo esc_html($imageurl); ?>" class="main-diamond-img" alt="<?php echo esc_html($diamondData->shape) . ' ' . esc_html($diamondData->carat); ?> CARAT" title="<?php echo esc_html($diamondData->shape) . ' ' . esc_html($diamondData->carat); ?> CARAT">
								<div style="display:none;"><?php echo esc_html($diamondData->videoFileName); //print_r($diamondData); 
															?></div>
								<?php if ($diamondData->hasVideo && preg_match('/^.*\.(mp4|mov)$/i', $diamondData->videoFileName)) {				   ?>
									<video class="diamond_video" width="" height="" autoplay="" loop="" muted="" playsinline="" style="display: none;">
										<source src="<?php //echo $diamondData->videoFileName; 
														?>" type="video/mp4">
									</video>
								<?php } ?>

							</div>
							<div class="product-details">
								<div class="product-item-name"><a href="<?php echo esc_html($diamondviewurl); ?>" onclick="gemfindDT_SetBackValue('<?php echo esc_html($diamondData->diamondId); ?>');" title="View Diamond"><span><?php echo esc_html($diamondData->shape); ?> <strong><?php echo esc_html($diamondData->carat); ?></strong> CARAT</span>
										<span><?php echo esc_html($color_to_display); ?>, <?php echo esc_html($diamondData->clarity); ?>, <?php echo esc_html($diamondData->cut); ?></span>
									</a>
								</div>
								<?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
									<?php
									$dprice = $diamondData->fltPrice;
									// echo '<pre>'; print_r($diamondData); exit; 
									$dprice = str_replace(',', '', $dprice);
									?>
									<div class="product-box-pricing"><a href="<?php echo esc_html($diamondviewurl); ?>" title="View Diamond"><span>
												<?php if ($diamondData->showPrice == true) { ?>

												<?php if ($options['price_row_format'] == 'left') {

														if ($diamondData->currencyFrom == 'USD') {

															echo "$" . esc_html(number_format($dprice));
														} else {

															echo esc_html(number_format($dprice)) . ' ' . esc_html($diamondData->currencySymbol) . ' ' . esc_html($diamondData->currencyFrom);
														}
													} else {

														if ($diamondData->currencyFrom == 'USD') {

															echo "$" . esc_html(number_format($dprice));
														} else {

															echo esc_html($diamondData->currencyFrom) . ' ' . esc_html($diamondData->currencySymbol) . ' ' . esc_html(number_format($dprice));
														}
													}
												} else {
													echo esc_html('Call For Price');
												} ?>
											</span></a>
									</div>
								<?php  } else { ?>
									<p class="enable-price"> Please <a href="<?php echo esc_html($login_link); ?>" style="color:#0072ff" ;> Login </a> To View Price </p>
								<?php } ?>
								<div class="product-box-action">
									<input type="checkbox" name="compare" value="<?php echo esc_html($diamondData->diamondId); ?>">
									<div class="state"><label>Add to compare</label></div>
								</div>
							</div>
							<div class="product-video-icon" style="display:none;"><?php if ($diamondData->hasVideo && preg_match('/^.*\.(mp4|mov)$/i', $diamondData->videoFileName)) { ?><span title="Video" class="videoicon">
										<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="29px" height="17px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
											<g>
												<g>
													<path d="M387.118,500.728c0,0,55.636,0,55.636-55.637V166.909c0-55.637-55.636-55.637-55.636-55.637H55.636
							c0,0-55.636,0-55.636,55.637v278.182c0,55.637,55.636,55.637,55.636,55.637H387.118z" />
													<polygon points="475.162,219.958 475.162,393.043 612,500.728 612,111.272    " />
												</g>
											</g>
										</svg>
									</span> <?php } ?>
							</div>
							<div class="product-slide-button"><a href="javascript:void(0)" class="trigger-info">menu</a>
							</div>
							<div class="product-inner-info">
								<ul>
									<li>
										<p><span>Diamond ID </span><span><?php echo esc_html($diamondData->diamondId); ?></span>
										</p>
									</li>
									<li>
										<p><span>Shape</span><span><?php echo esc_html($diamondData->shape); ?></span></p>
									</li>
									<li>
										<p>
											<span>Carat</span><span><?php echo (esc_html($diamondData->carat)) ? esc_html($diamondData->carat) : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Color</span><span><?php echo (esc_html($color_to_display)) ? esc_html($color_to_display) : '-'; ?></span>
										</p>
									</li>
									<?php if ($diamondData->fancyColorMainBody) { ?>
										<li>
											<p>
												<span>Intensity</span><span><?php echo (esc_html($Intensity_to_display)) ? esc_html($Intensity_to_display) : '-'; ?></span>
											</p>
										</li>
									<?php } ?>
									<li>
										<p>
											<span>Clarity</span><span><?php echo (esc_html($diamondData->clarity)) ? esc_html($diamondData->clarity) : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Cut</span><span><?php echo (esc_html($diamondData->cut)) ? esc_html($diamondData->cut) : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Depth</span><span><?php echo (esc_html($diamondData->depth)) ? esc_html($diamondData->depth) . '%' : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Table</span><span><?php echo (esc_html($diamondData->table)) ? esc_html($diamondData->table) . '%' : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Polish</span><span><?php echo (esc_html($diamondData->polish)) ? esc_html($diamondData->polish) : '-'; ?></span>
										</p>
									</li>
									<li>
										<p>
											<span>Symmetry</span><span><?php echo (esc_html($diamondData->symmetry)) ? esc_html($diamondData->symmetry) : '-'; ?></span>
										</p>
									</li>
									<li>
										<p><span>Measurement</span><span><?php echo esc_html($diamondData->measurement); ?></span>
										</p>
									</li>
									<?php if ($show_Certificate_in_Diamond_Search) { ?>
										<li>
											<p><span>Certificate</span><span><a href="javascript:;" onclick="javascript:window.open('<?php echo esc_html($diamondData->certificateUrl); ?>','CERTVIEW','scrollbars=yes,resizable=yes,width=860,height=550')"><?php echo esc_html($diamondData->cert); ?></a></span>
											</p>
										</li>
									<?php } ?>
									<?php if ($show_in_house_column) { ?>
										<li>
											<p><span>In House</span><span><?php echo esc_html($diamondData->inhouse); ?></span></p>
										</li>
									<?php } ?>
									<li>
										<p>
											<span>Price</span>
											<?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
												<span>
													<?php if ($diamondData->showPrice == true) {
														echo (esc_html($diamondData->currencyFrom) != 'USD') ? esc_html($diamondData->currencyFrom) . esc_html(number_format($diamondData->fltPrice)) : esc_html($diamondData->currencySymbol) . esc_html(number_format($diamondData->fltPrice));
													} else {
														echo esc_html('Call For Price');
													} ?>
												</span>
											<?php } else { ?>
												<span> <a href="<?php echo esc_html($login_link); ?>" style="color:#0072ff" ;> Login </a> </span>
											<?php } ?>
										</p>
									</li>
								</ul>
							</div>
							<input type="hidden" name="diamondtype" id="diamondtype-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($type); ?>">
							<input type="hidden" name="diamondshape" id="diamondshape-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->shape); ?>">
							<input type="hidden" name="diamondsku" id="diamondsku-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->diamondId); ?>">
							<input type="hidden" name="diamondcarat" id="diamondcarat-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->carat); ?>">
							<input type="hidden" name="diamondtable" id="diamondtable-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->table); ?>">
							<input type="hidden" name="diamondcolor" id="diamondcolor-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($color_to_display); ?>">
							<input type="hidden" name="diamondpolish" id="diamondpolish-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->polish); ?>">
							<input type="hidden" name="diamondsymm" id="diamondsymm-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->symmetry); ?>">
							<input type="hidden" name="diamondclarity" id="diamondclarity-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->clarity); ?>">
							<input type="hidden" name="diamondflr" id="diamondflr-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->fluorescence); ?>">
							<input type="hidden" name="diamonddepth" id="diamonddepth-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->depth); ?>">
							<input type="hidden" name="diamondmeasure" id="diamondmeasure-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->measurement); ?>">
							<input type="hidden" name="diamondcerti" id="diamondcerti-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_html($diamondData->cert); ?>">
							<input type="hidden" name="diamondcutgrade" id="diamondcutgrade-<?php echo esc_html($diamondData->diamondId); ?>" value="<?php echo esc_attr($diamondData->cut); ?>">
							<?php
							if ($filter_type == 'navfancycolored') {
								$diamondImage = $diamondData->diamondImage;
							} else {
								$diamondImage = $diamondData->biggerDiamondimage;
							}
							?>
							<?php if ($diamondData->showPrice == true) {
								$diamondPrice =  $diamondData->price;
							} else {
								$diamondPrice = 'Call For Price';
							}
							?>
							<input type="hidden" name="diamondimage" id="diamondimage-<?php echo esc_attr($diamondData->diamondId); ?>" value="<?php echo esc_attr($diamondImage); ?>">
							<!-- <input type="hidden" name="diamondprice" id="diamondprice-<?php echo esc_attr($diamondData->diamondId); ?>" value="<?php echo esc_attr($diamondPrice); ?>"> -->
							<?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
								<input type="hidden" name="diamondprice" id="diamondprice-<?php echo esc_attr($diamondData->diamondId); ?>" value="<?php echo esc_attr($diamondPrice); ?>">
							<?php } else { ?>
								<input type="hidden" name="diamondprice" id="diamondprice-<?php echo esc_attr($diamondData->diamondId); ?>" value='<a href="<?php echo esc_attr($login_link); ?>" style="color:#0072ff";> Login </a>' ;>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class='grid-paginatin' style='text-align:center;'>
				<div class='btn-compare'><a href='javaScript:void(0)' onclick="gemfindDT_SetBackValue();" id='compare-main'>Compare (<span id="totaldiamonds1">0</span>)</a>
				</div>
				<?php
				$current = 1;
				$number  = $diamond['perpage'];
				$pages   = ceil($diamond['pagination']['total'] / $number);
				if ($diamond['pagination']['currentpage'] > 1) {
					$current = $diamond['pagination']['currentpage'];
				}
				if ($current - 1 == 0) {
					$value = 1;
				} else {
					$value = $current - 1;
				}	?>
				<div class='pagination-div pagination_scroll'>
					<input type="hidden" name="tool_version" value="Version 2.7.0">
					<ul>
						<li class="grid-next-double">
							<a href="javascript:void(0);" onclick="gemfindDT_PagerClick('1');"></a>
						</li>
						<li data-toggle="tooltip" data-placement="bottom" title="Previous" <?php echo ($current == 1) ? 'class="disabled grid-next"' : 'class="grid-next"' ?>>
							<a href="javascript:void(0);" <?php if (($current - 1) != 0) { ?> onclick="gemfindDT_PagerClick('<?php echo (esc_html($value)) ?>');" <?php } ?>><?php echo (esc_attr($value)) ?></a>
						</li>
						<?php
						$lastPageNumber = '';
						for ($i = 1; $i <= $pages; $i++) {
							if ($i <> $current) {
								if ($i >= $current + 3) {
									continue;
								}
								if ($i <= $current - 3) {
									continue;
								}				?>
								<li>
									<a href='javascript:void(0);' onclick='gemfindDT_PagerClick("<?php echo esc_attr($i); ?>");'><?php echo esc_attr($i); ?></a>
								</li>
							<?php
							} else {				?>
								<li class='active'>
									<a href='javascript:void(0);' class='active' onclick='gemfindDT_PagerClick("<?php echo esc_attr($i); ?>");'><?php echo esc_attr($i); ?></a>
								</li>
						<?php
							}
						}		?>
						<li data-toggle="tooltip" data-placement="bottom" title="Next" <?php echo ($current == $pages) ? 'class="disabled grid-previous"' : 'class="grid-previous"' ?>>
							<a href="javascript:void(0);" <?php if ($current != $pages) { ?> onclick="gemfindDT_PagerClick('<?php echo esc_attr($current) + 1; ?>');" <?php } ?>><?php echo esc_attr($current) + 1; ?></a>
						</li>
						<li class="grid-previous-double">
							<a href="javascript:void(0);" onclick="gemfindDT_PagerClick('<?php echo esc_attr($pages); ?>');"></a>
						</li>
					</ul>
				</div>
				<?php
				if ($current == 1) {
					$from = 1;
					$to   = $number;
				} else {
					$from = (($current - 1) * $number) + 1;
					$to   = ($current * $number);
				}

				if ($diamond['pagination']['total'] < $to) {
					$to = $diamond['pagination']['total'];
				} ?>
				<div class='page-checked'>
					<div class='result-bottom'>Results <?php echo esc_attr(number_format($from)); ?>
						to <?php echo esc_attr(number_format($to)); ?>
						of <?php echo esc_attr(number_format($diamond['pagination']['total'])); ?> </div>
				</div>
			</div>
		</div>
		</div>
	<?php else : ?>
		<div class='search-details no-padding'>
			<div class='searching-result'>
				<div class='number-of-search'>
					<p><strong><?php echo esc_attr(number_format($diamond['pagination']['total'])); ?></strong>Similar Diamonds
					</p>
				</div>
				<div class='search-in-table' id='searchintable'>
					<input type='text' name='searchdidfield' id='searchdidfield' placeholder='Search Diamond Stock #'><a href='javascript:;' title='close' id='resetsearchdata'>X</a>
					<button id='searchdid' title='Search Diamond'></button>
				</div>
				<div class='view-or-search-result'>
					<div class='change-view-result'>
						<ul>
							<li class='grid-view'><a href='javascript:;' class='<?php echo (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'grid') ? 'active' : ''; ?>'>Grid view</a></li>
							<li class='list-view'><a href='javascript:;' class="<?php echo (isset($back_cookie_data['viewmode']) && $back_cookie_data['viewmode'] == 'list') ? 'active' : '';
																				echo esc_attr($class) ?>">list view</a></li>
						</ul>
						<div class='item-page'>
							<p class='leftpp'>Per Page</p>
							<select class='pagesize SumoUnder' id='pagesize' name='pagesize' onchange='gemfindDT_ItemPerPage(this)' tabindex='-1'>
								<?php
								$all_options = gemfindDT_getAllOptions();
								foreach ($all_options as $value) {
								?>
									<option value='<?php echo esc_attr($value['value']); ?>'><?php echo esc_attr($value['label']); ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div class='grid-view-sort cls-for-hide'>
							<select name='gridview-orderby' id='gridview-orderby' class='gridview-orderby SumoUnder' onchange='gemfindDT_gridSort(this)' tabindex='-1'>
								<option value='Cut'>Shape</option>
								<option value='Size'>Carat</option>
								<option value='Color'>Color</option>
								<?php if ($filter_type == 'navfancycolored') { ?>
									<option value='FancyColorIntensity'>Intensity</option>
								<?php } ?>
								<option value='ClarityID'>Clarity</option>
								<option value='CutGrade'>Cut</option>
								<option value='Depth'>Depth</option>
								<option value='TableMeasure'>Table</option>
								<option value='Polish'>Polish</option>
								<option value='Symmetry'>Symmetry</option>
								<option value='Measurements'>Measurement</option>
								<?php if ($show_Certificate_in_Diamond_Search) { ?>
									<option value='Certificate'>Certificate</option>
								<?php } ?>
								<?php if ($show_in_house_column) { ?>
									<option value='Inhouse'>In House</option>
								<?php } ?>
								<option value='FltPrice' selected='selected'>Price</option>
							</select>
							<div class='gridview-dir-div'>
								<a href='javascript:;' onclick='gemfindDT_gridDire("DESC")' id='ASC' title='Set Descending Direction' class='active'>ASC</a><a href='javascript:;' title='Set Ascending Direction' onclick='gemfindDT_gridDire("ASC")' id='DESC'>DESC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="search-details no-padding no-result-main">
			<div class="searching-result no-result-div">
				<?php esc_html_e('No Data Found.', 'gemfind-diamond-tool') ?>
			</div>
		</div>
	<?php endif; ?>

	<div id="myDblinkModal" class="Dblinkmodal">
		<!-- Modal content -->
		<div class="Dbmodallink-content">

			<span class="Dblinkclose">&times;</span>
			<div class="loader_rb" style="display: none;">
				<!-- <i class="fa fa-spinner fa-spin" style="font-size:24px"></i> -->
				<img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/images/diamond_rb.gif'); ?>" style="width: 200px; height: 200px;">
			</div>
			<iframe src="" id="iframevideodblink" scrolling="no" style="width:100%; height:98%;" allow="autoplay"></iframe>
		</div>
	</div>

		<!-- Additional Information Modal -->
		<div id="dl-diamondInfoModal" class="dl-modal">
	    <div class="dl-modal-content">
	        <span class="dl-close">&times;</span>
	        <h2>Additional Information</h2>
	        <table id="dl-diamond-info-table">	           
	        </table>
	    </div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			//SET TOTAL DIAMOND ON LOAD
			var cookiesarraylenn = compareItemsarray.length
			if (JSON.parse(localStorage.getItem("compareItems"))) {
				var localStoragedataload = JSON.parse(localStorage.getItem("compareItems")).length;
			} else {
				var localStoragedataload = 0;
			}
			var totalcntarray = cookiesarraylenn + localStoragedataload;
			jQuery('#totaldiamonds').text(totalcntarray);
			jQuery('#totaldiamonds1').text(totalcntarray);

			var backdid = jQuery('#backdiamondid').val();
			if (backdid) {
				jQuery('.search-details #list-mode #' + backdid).addClass('selected_row');
				setTimeout(function() {
					gemfindDT_ResetBackCookieFilter();
					jQuery('#backdiamondid').val("");
				}, 800);
			}
			var is_enable_sticky = jQuery('#sticky_header').val();
			if (is_enable_sticky == 'yes' && jQuery('.table_header_wrapper').length) {
				var stickyTop = jQuery('.table_header_wrapper').offset().top;
				jQuery(window).scroll(function() {
					var windowTop = jQuery(window).scrollTop();
					if (stickyTop < windowTop) {
						jQuery('.table_header_wrapper').addClass('fixed-table-head');
					} else {
						jQuery('.table_header_wrapper').removeClass('fixed-table-head');
					}
				});
			}

			jQuery('[data-toggle="tooltip"]').tooltip({
				trigger: "hover"
			});
			// scroll enable for list page only
			if (jQuery('body').hasClass('diamond-list-page')) {
				var viewmode = jQuery('#viewmode').val();
				var headerheight = jQuery('header').outerHeight() + jQuery('#top-header').outerHeight();
				if (viewmode == '' || viewmode == 'list') {
					if (jQuery('#list-mode tbody tr').hasClass('selected_row')) {
						setTimeout(function() {
							jQuery('html, body').animate({
								scrollTop: jQuery('.selected_row').offset().top - headerheight
							}, 1000);
						}, 100);
					}
				} else {
					if (jQuery('#grid-mode .grid-product-listing div').hasClass('selected_grid')) {
						setTimeout(function() {
							jQuery('html, body').animate({
								scrollTop: jQuery('#grid-mode .selected_grid').offset().top - headerheight
							}, 1000);
						}, 100);
					}
				}
			}
		});

		jQuery('#compare-main').unbind().click(function() {
			console.log('form is submitting');
			gemfindDT_compareDiamonds();
		});

		jQuery('#comparetop').unbind().click(function() {
			console.log('form is submitting');
			gemfindDT_compareDiamonds();
		});

		jQuery("input:checkbox[name=compare]").click(function() {
			var selectedcheckboxid = jQuery(this).val();
			var checkbox = jQuery(this).is(':checked');

			if (checkbox == true) {
				var maxAllowed = 5;
				var cnt = compareItemsarray.length;
				if (JSON.parse(localStorage.getItem("compareItems"))) {
					var localStoragedata = JSON.parse(localStorage.getItem("compareItems")).length;
				} else {
					var localStoragedata = 0;
				}
				var totalcnt = cnt + localStoragedata;
				if (totalcnt > maxAllowed) {
					jQuery(this).prop("checked", "");
					alert('You can select a maximum of 6 diamonds to compare! Please check your compare item page you have some items in your compare list.');
					return false;
				}

				compareItemsarray.push(selectedcheckboxid);
				var datacompare = {};
				datacompare.Image = jQuery("#diamondimage-" + selectedcheckboxid).val(), datacompare.Shape = jQuery("#diamondshape-" + selectedcheckboxid).val(), datacompare.Type = jQuery("#diamondtype-" + selectedcheckboxid).val(), datacompare.Sku = jQuery("#diamondsku-" + selectedcheckboxid).val(), datacompare.Carat = jQuery("#diamondcarat-" + selectedcheckboxid).val(), datacompare.Table = jQuery("#diamondtable-" + selectedcheckboxid).val(), datacompare.Color = jQuery("#diamondcolor-" + selectedcheckboxid).val(), datacompare.Polish = jQuery("#diamondpolish-" + selectedcheckboxid).val(), datacompare.Symmetry = jQuery("#diamondsymm-" + selectedcheckboxid).val(), datacompare.Clarity = jQuery("#diamondclarity-" + selectedcheckboxid).val(), datacompare.Fluorescence = jQuery("#diamondflr-" + selectedcheckboxid).val(), datacompare.Depth = jQuery("#diamonddepth-" + selectedcheckboxid).val(), datacompare.Measurement = jQuery("#diamondmeasure-" + selectedcheckboxid).val(), datacompare.Cert = jQuery("#diamondcerti-" + selectedcheckboxid).val(), datacompare.Cut = jQuery("#diamondcutgrade-" + selectedcheckboxid).val(), datacompare.Price = jQuery("#diamondprice-" + selectedcheckboxid).val(), datacompare.ID = jQuery("#diamondsku-" + selectedcheckboxid).val();
				compareItems.push(datacompare);

				var total_diamonds = compareItemsarray.length + localStoragedata;
				//console.log(total_diamonds);
				//jQuery('#totaldiamonds').text(total_diamonds);
				if (total_diamonds <= 6) {
					jQuery('#totaldiamonds').text(total_diamonds);
					jQuery('#totaldiamonds1').text(total_diamonds);
				}

				// console.log(datacompare);
			} else {
				//console.log(selectedcheckboxid +" unchecked");
				if (JSON.parse(localStorage.getItem("compareItems"))) {
					var localStoragedata = JSON.parse(localStorage.getItem("compareItems")).length;
				} else {
					var localStoragedata = 0;
				}

				compareItemsarray.pop(selectedcheckboxid);
				var total_diamonds = compareItemsarray.length + localStoragedata;
				jQuery('#totaldiamonds').text(total_diamonds);
				jQuery('#totaldiamonds1').text(total_diamonds);

				jQuery.each(compareItems, function(key, value) {
					if (value) {
						if (selectedcheckboxid == value.ID) {
							compareItems.splice(key, 1);
						}
					}
				});
			}
		});

		function gemfindDT_compareDiamonds() {
			var count = compareItems.length;
			var shop_url = '<?php echo esc_url(get_site_url()); ?>';
			if (count > 0) {
				var expire = new Date();
				expire.setDate(expire.getDate() + 0.2 * 24 * 60 * 60 * 1000);
				var compareClickCount = localStorage.getItem("compareClick");
				var finalItems = [];

				if (compareClickCount == '1') {
					const compareItemsNew = JSON.parse(localStorage.getItem("compareItems"));
					// console.log(compareItems);
					// console.log(compareItemsNew);

					finalItems = compareItemsNew.concat(compareItems);
					// console.log(finalItems);;

					jQuery.cookie("comparediamondProduct", JSON.stringify(finalItems), {
						path: '/',
						expires: expire
					});

					localStorage.setItem("compareItems", JSON.stringify(finalItems));
				} else {
					finalItems = compareItems;

					jQuery.cookie("comparediamondProduct", JSON.stringify(finalItems), {
						path: '/',
						expires: expire
					});
				}

				if (compareClickCount == null) {
					localStorage.setItem("compareClick", 1);
					localStorage.setItem("compareItems", JSON.stringify(finalItems));
				}
				console.log(JSON.parse(Cookies.get("comparediamondProduct")).length);
				//return false;
				window.location.href = shop_url + '/diamondlink/compare';

			} else {
				if (JSON.parse(localStorage.getItem("compareItems"))) {
					window.location.href = shop_url + '/diamondlink/compare';
				} else {
					alert('Please select minimum 2 diamonds to compare.');
				}
				document.querySelector('#navcompare').classList.remove('active');
			}
		}


		jQuery("span.imagecheck").each(function() {
			var id = jQuery(this).attr("data-id");
			if (jQuery('input#viewmode').val() == 'list') {
				var src = jQuery(this).attr("data-src");
				gemfindDT_imageExists(src, function(exists) {
					if (exists) {
						jQuery('tr#' + id + ' td img').attr('src', src);
					} else {
						jQuery('tr#' + id + ' td img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL . '/assets/images/no-image.jpg'); ?>');
						jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL . '/assets/images/no-image.jpg'); ?>');
						jQuery('input#diamondimage-' + id).val('<?php echo esc_url(DIAMOND_LINK_URL . '/assets/images/no-image.jpg'); ?>');
					}
				});
			} else {
				if (jQuery('input#filtermode').val() == 'navfancycolored') {
					var src = jQuery(this).attr("data-src");
					gemfindDT_imageExists(src, function(exists) {
						if (exists) {
							jQuery('tr#' + id + ' td img').attr('src', src);
						} else {
							jQuery('tr#' + id + ' td img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
							jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
							jQuery('input#diamondimage-' + id).val('<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
						}
					});
				} else {
					var src = jQuery(this).attr("data-srcbig");
					gemfindDT_imageExists(src, function(exists) {
						if (exists) {
							jQuery('tr#' + id + ' td img').attr('src', src);
						} else {
							jQuery('tr#' + id + ' td img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
							jQuery('div#' + id + ' div.product-images img').attr('src', '<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
							jQuery('input#diamondimage-' + id).val('<?php echo esc_url(DIAMOND_LINK_URL); ?>/assets/images/no-image.jpg');
						}
					});
				}
			}
		});


		function gemfindDT_imageExists(url, callback) {
			var img = new Image();
			img.onload = function() {
				callback(true);
			};
			img.onerror = function() {
				callback(false);
			};
			img.src = url;
		}

		jQuery('.pagination_scroll').click(
			function(e) {

				if (jQuery(window).width() <= 991) {
					jQuery('html, body').animate({
						scrollTop: jQuery('#scrollPage').position().top
					}, 800);
				} else {
					jQuery('html, body').animate({
						scrollTop: jQuery('#noui_carat_slider').position().top
					}, 800);
				}


			});



		//Grid View
		//    jQuery('.search-product-grid').each(function(num,val){
		//      var divid = jQuery(this).attr('id');
		//      var videosrc = jQuery('#grid-mode #'+divid+' div.product-images video source').attr('src');      
		//        jQuery.ajax({
		//          url: myajax.ajaxurl,
		//          data: {'action':'checkvideo', 'setting_video_url': videosrc},
		//          type: 'POST',                    
		//          cache: true,
		//          success: function(response) {
		//              if(response != 1){
		//                jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-video').remove();
		// 			jQuery('#grid-mode #'+divid+' div.product-video-icon').show();
		//              }else{
		//                jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-video').remove();
		//                jQuery('#grid-mode #'+divid+' div.product-images video').remove();
		// 			jQuery('#grid-mode #'+divid+' div.product-video-icon').remove();
		//              }
		//          }
		//      });
		//    });

		//    //Added code for hover video
		//    jQuery(".search-product-grid").mouseenter(function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//        jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','none');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','block');
		//        }else{
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//        }
		//    });
		//    jQuery(".search-product-grid").mouseleave(function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','none');
		//        }
		//    });
		// //Mobile event for video
		// jQuery(".search-product-grid").on("touchstart",function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','none');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','block');
		//        }else{
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//        }
		//    });
		// jQuery(".search-product-grid").on("touchend",function(){
		//        var divid = jQuery(this).attr('id');
		//        if(jQuery('#grid-mode #'+divid+' div.product-images video').length){
		//          jQuery('#grid-mode #'+divid+' div.product-images .main-diamond-img').css('display','block');
		//          jQuery('#grid-mode #'+divid+' div.product-images video').css('display','none');
		//        }
		//    });
		var videoList = document.getElementsByTagName("video");
		jQuery(videoList).on('loadstart', function(event) {
			jQuery(this).addClass('loading');
		});
		jQuery(videoList).on('canplay', function(event) {
			jQuery(this).removeClass('loading');
		});

		function gemfindDT_showModaldblink() {
			jQuery("#iframevideodblink").removeAttr("src");
			jQuery('#myDblinkModal').modal('show');
			jQuery('.loader_rb').show();
			var divid = jQuery(event.currentTarget).data('id');
			jQuery.ajax({
				type: "POST",
				url: myajax.ajaxurl,
				data: {
					action: 'gemfindDT_getdiamondlinkvideos',
					product_id: divid
				},
				cache: true,
				success: function(response) {
					response = JSON.parse(response);
					if (response.showVideo == true) {
						setTimeout(function() {
							jQuery("#iframevideodblink").attr("src", response.videoURL);
							jQuery('.loader_rb').hide();
							jQuery('#iframevideodblink').show();
						}, 3000);
					}
				}
			});
		}

		
		jQuery(".Dblinkclose").click(function() {
			jQuery('#myDblinkModal').modal('hide');
		});

		function onMouseOverMoreInfo(element) {
		    const iconList = element.querySelector('.icon-list');
			console.log(" coming here ");
		    if (iconList) {
		        iconList.classList.add('show'); // Show the icon list
		    }
		}

		function onMouseOutMoreInfo(element) {
		    const iconList = element.querySelector('.icon-list');
		    if (iconList) {
		        iconList.classList.remove('show'); // Hide the icon list
		    }
		}

		var modal = document.getElementById("dl-diamondInfoModal");

		function showAdditionalInformation(diamondId) {
		    var domain = "<?php echo $_SERVER['HTTP_HOST']; ?>";

		    // Show the global loader
		    jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
		    document.body.classList.add("dl-AddInfoModal");
		    
		    // Check if on the diamond detail page
		    const isDetailPage = window.location.pathname.includes('/diamondlink/product/');
		    let diamondType = 'NA';

		    if (!isDetailPage) {
		        diamondType = document.getElementById('diamondtype-' + diamondId).value;
		    }

		    $.ajax({
		        type: "POST",
		        url: myajax.ajaxurl,
		        data: {
		            action: 'gemfindDT_getDiamondDetails',
		            id: diamondId,
		            shop: "https://" + domain,
		            type: diamondType,
		        },
		        success: function(response) {
		            // Parse the JSON response
		            var data = JSON.parse(response);
		            var diamondData = data.diamondData;

		            // Reference to the table element
		            var table = document.getElementById("dl-diamond-info-table");

		            // Create table rows dynamically based on the diamondData object
		            table.innerHTML = `
		                <tr><th>Diamond ID</th><td>${diamondData.diamondId}</td></tr>             
		                <tr><th>Shape</th><td>${diamondData.shape || 'N/A'}</td></tr>
		                <tr><th>Carat Weight</th><td>${diamondData.caratWeight || 'N/A'}</td></tr>               
		                <tr><th>Color</th><td>${diamondData.color || 'N/A'}</td></tr>
		                <tr><th>Clarity</th><td>${diamondData.clarity || 'N/A'}</td></tr>
		                <tr><th>Cut Grade</th><td>${diamondData.cutGrade || 'N/A'}</td></tr>
		                <tr><th>Depth</th><td>${diamondData.depth || 'N/A'}</td></tr>
		                <tr><th>Table</th><td>${diamondData.table || 'N/A'}</td></tr>
		                <tr><th>Polish</th><td>${diamondData.polish || 'N/A'}</td></tr>
		                <tr><th>Certificate</th><td>${diamondData.certificate || 'N/A'}</td></tr>
		                <tr><th>Measurement</th><td>${diamondData.measurement || 'N/A'}</td></tr>
		                <tr><th>Price</th><td>${diamondData.fltPrice || 'N/A'}</td></tr>              
		            `;
		            
		            // Show the modal
		            modal.style.display = "block";

		            // Hide the global loader
		            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
		        },
		        error: function() {
		            // Handle errors if needed
		            alert("An error occurred while fetching the diamond details.");
		            
		            // Hide the global loader even if there's an error
		            jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
		        }
		    });
		}


		// Event listener to close the modal when the close button is clicked
	    var closeModal = document.getElementsByClassName("dl-close")[0];

	    closeModal.onclick = function() {
	        modal.style.display = "none";
	        document.body.classList.remove("dl-AddInfoModal");
	    };

	    // Event listener to close the modal when clicking outside of the modal content
	    window.onclick = function(event) {
	        if (event.target == modal) {
	            modal.style.display = "none";
	        	document.body.classList.remove("dl-AddInfoModal");
	        }
	    };
	</script>
<?php
	die();
}
/**
 * For diamondHint form email.
 */
function gemfindDT_resultdrophint()
{

	$form_data      = rest_sanitize_array($_POST['form_data']);
	$all_options    = gemfindDT_getOptions();
	$hint_post_data = array();
	$alldata = get_option('gemfind_diamond_link');
	$diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$hint_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}

	if ($hint_post_data && $hint_post_data['name'] != "" && $hint_post_data['email'] != "" && $hint_post_data['recipient_email'] != "") {
		try {
			$diamondData = gemfindDT_getDiamondById($hint_post_data['diamondid'], $hint_post_data['diamondtype'],  $hint_post_data['shopurl']);

			$retaileremail = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername  = ($diamondData['diamondData']['vendorName'] ? $diamondData['diamondData']['vendorName'] : $store_detail);
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';

			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
					<td class="consumer-title">Lab:</td>
					<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
					</tr>';
			}
			$templateVars = array(
				'retailername'    => $retailername,
				'vendorContactNo' => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'retaileremail'   => $diamondData['diamondData']['vendorEmail'],
				'name'            => $hint_post_data['name'],
				'email'           => $hint_post_data['email'],
				'recipient_name'  => $hint_post_data['recipient_name'],
				'recipient_email' => $hint_post_data['recipient_email'],
				'gift_reason'     => $hint_post_data['gift_reason'],
				'hint_message'    => $hint_post_data['hint_message'],
				'gift_deadline'   => $hint_post_data['gift_deadline'],
				'diamond_url'     => $hint_post_data['diamondurl'],
				'show_Certificate' => (isset($show_Certificate)) ? $show_Certificate : '',
			);
			// Sender email
			$transport_sender_template = gemfindDT_hint_email_template_sender();
			$senderValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail,
				'{{name}}'            => $hint_post_data['name'],
				'{{recipient_email}}' => $hint_post_data['recipient_email'],
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{diamond_url}}'     => $hint_post_data['diamondurl'],
				'{{vendorContactNo}}' => $templateVars['vendorContactNo'],
				'{{retaileremail}}'   => $templateVars['retaileremail'],
			);
			$sender_email_body         = str_replace(array_keys($senderValueReplacement), array_values($senderValueReplacement), $transport_sender_template);
			$sender_subject            = "You Sent A Little Hint To " . $hint_post_data['recipient_name'];
			$senderFromAddress         = $all_optinos['from_email_address'];
			$headers                   = array('From: ' . $senderFromAddress . '');
			$senderToEmail             = $hint_post_data['email'];
			wp_mail($senderToEmail, $sender_subject, $sender_email_body, $headers);

			// Receiver email
			$transport_receiver_template = gemfindDT_hint_email_template_receiver();
			$receiverValueReplacement    = array(
				'{{shopurl}}'         => $shopurl,
				'{{shop_logo}}'       => $store_logo,
				'{{shop_logo_alt}}'   => $store_detail,
				'{{recipient_name}}'  => $hint_post_data['recipient_name'],
				'{{gift_reason}}'     => $hint_post_data['gift_reason'],
				'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
				'{{hint_message}}'    => $hint_post_data['hint_message'],
				'{{diamond_url}}'     => $hint_post_data['diamondurl'],
				'{{vendorContactNo}}' => $templateVars['vendorContactNo'],
				'{{retaileremail}}'   => $templateVars['retaileremail'],
			);
			$receiver_email_body         = str_replace(array_keys($receiverValueReplacement), array_values($receiverValueReplacement), $transport_receiver_template);
			$receiver_subject            = "A Little Hint from " . $hint_post_data['name'];
			$receiver_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $receiver_fromAddress . '');
			$receiver_toEmail            = $hint_post_data['recipient_email'];
			wp_mail($receiver_toEmail, $receiver_subject, $receiver_email_body, $headers);

			// Retailer email
			if ($all_options['enable_admin_notify'] == 'true') {
				$transport_retailer_template = gemfindDT_hint_email_template_retailer();
				$retailerValueReplacement    = array(
					'{{shopurl}}'         => $shopurl,
					'{{shop_logo}}'       => $store_logo,
					'{{shop_logo_alt}}'   => $store_detail,
					'{{retailername}}'    => $retailername,
					'{{gift_reason}}'     => $hint_post_data['gift_reason'],
					'{{gift_deadline}}'   => $hint_post_data['gift_deadline'],
					'{{hint_message}}'    => $hint_post_data['hint_message'],
					'{{diamond_url}}'     => $hint_post_data['diamondurl'],
					'{{recipient_email}}' => $hint_post_data['recipient_email'],
					'{{name}}'            => $hint_post_data['name'],
					'{{email}}'           => $hint_post_data['email'],
					'{{recipient_name}}'  => $hint_post_data['recipient_name'],
					'{{show_Certificate}}'   => $templateVars['show_Certificate'],

				);
				$retailer_email_body         = str_replace(array_keys($retailerValueReplacement), array_values($retailerValueReplacement), $transport_retailer_template);
				$retailer_subject            = "Someone Wants To Drop You A Hint";
				$retailer_fromAddress        = $all_optinos['from_email_address'];
				$headers                     = array('From: ' . $retailer_fromAddress . '');
				$retailer_toEmail            = $retaileremail;
				wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			}
			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);

			$result  = json_encode(array('output' => $data));
			$allowed_html = array(
				'div' => array(
					'id' => array(),
					'class' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'data-steps' => array(),
				),
				'input' => array(
					'name' => array(),
					'id' => array(),
					'type' => array(),
					'value' => array(),
					'class' => array(),
					'data-type' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'autocomplete' => array(),
					'inputmode' => array(),
				),
				'h4' => array(
					'class' => array(),
				),
				'ul' => array(
					'id' => array(),
				),
				'li' => array(
					'class' => array(),
					'title' => array(),
				),
				'script' => array(
					'type' => array(),
				),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'img' => array(
					'src' => array(),
					'alt' => array(),
					'title' => array(),
					'width' => array(),
					'height' => array(),
				),
				'style' => array(
					'type' => array(),
				),
				'br' => array(),
				'hr' => array(),
			);
			echo wp_kses($result, $allowed_html);
			// echo esc_html($result);
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action('wp_ajax_nopriv_gemfindDT_checkvideo', 'gemfindDT_checkvideo');
add_action('wp_ajax_gemfindDT_checkvideo', 'gemfindDT_checkvideo');
function gemfindDT_checkvideo()
{
	$setting_video_url = '';
	$setting_video_url = sanitize_url($_POST['setting_video_url']);
	$headers = gemfindDT_is_404($setting_video_url);
	echo esc_html($headers);
	exit;
}

add_action('wp_ajax_nopriv_gemfindDT_resultdrophint', 'gemfindDT_resultdrophint');
add_action('wp_ajax_gemfindDT_resultdrophint', 'gemfindDT_resultdrophint');

function gemfindDT_getdiamondlinkvideos()
{
	$productId = absint($_POST['product_id']);
	//$requestUrl = 'http://api.jewelcloud.com/api/jewelry/GetVideoUrl?InventoryID='.$productId.'&Type=Jewelry';
	$requestUrldb = 'http://api.jewelcloud.com/api/jewelry/GetVideoUrl?InventoryID=' . $productId . '&Type=Diamond';

	$response = wp_remote_get($requestUrldb);

	if (is_wp_error($response)) {
		wp_send_json(['videoURL' => '', 'showVideo' => '']);
	}

	$body = wp_remote_retrieve_body($response);
	$resultsdb = json_decode($body, true);

	$resultdatadb = array(
		'videoURL' => $resultsdb['videoURL'],
		'showVideo' => $resultsdb['showVideo'],
	);
	// echo '<pre>';
	// print_r(esc_html(wp_json_encode($resultdatadb)));
	// exit();
	echo wp_json_encode($resultdatadb);
	exit;
	// wp_send_json($resultdatadb);
}
add_action('wp_ajax_nopriv_gemfindDT_getdiamondlinkvideos', 'gemfindDT_getdiamondlinkvideos');
add_action('wp_ajax_gemfindDT_getdiamondlinkvideos', 'gemfindDT_getdiamondlinkvideos');

/**
 * For email a friend.
 */
function gemfindDT_resultemailfriend()
{
	$form_data              = rest_sanitize_array($_POST['form_data']);
	$all_options            = gemfindDT_getOptions();
	$email_friend_post_data = array();

	$alldata = get_option('gemfind_diamond_link');
	$diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;

	foreach ($form_data as $data) {
		$email_friend_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}

	if ($email_friend_post_data && $email_friend_post_data['email'] != "" && $email_friend_post_data['friend_email'] != "" && $email_friend_post_data['message'] != "") {
		try {
			$diamondData = gemfindDT_getDiamondById($email_friend_post_data['diamondid'], $email_friend_post_data['diamondtype'],  $email_friend_post_data['shopurl']);
			//$retaileremail = $diamondData['diamondData']['vendorEmail'];	
			$retaileremail = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername  = ($diamondData['diamondData']['vendorName'] ? $diamondData['diamondData']['vendorName'] : $store_detail);
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';
			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
					<td class="consumer-title">Lab:</td>
					<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
					</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$';

			if ($diamondData['diamondData']['showPrice'] == 1) {
				$price  = $diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
			} else {
				$price = 'Call For Price';
			}

			$templateVars  = array(
				'name'              => $email_friend_post_data['name'],
				'email'             => $email_friend_post_data['email'],
				'friend_name'       => $email_friend_post_data['friend_name'],
				'friend_email'      => $email_friend_post_data['friend_email'],
				'message'           => $email_friend_post_data['message'],
				'diamond_url'       => (isset($email_friend_post_data['diamondurl'])) ? $email_friend_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerStockNo'   => (isset($diamondData['diamondData']['retailerInfo']->retailerStockNo)) ? $diamondData['diamondData']['retailerInfo']->retailerStockNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'           => $shopurl,
				'{{shop_logo}}'         => $store_logo,
				'{{shop_logo_alt}}'     => $store_detail,
				'{{name}}'              => $templateVars['name'],
				'{{email}}'             => $templateVars['email'],
				'{{friend_name}}'       => $templateVars['friend_name'],
				'{{friend_email}}'      => $templateVars['friend_email'],
				'{{message}}'           => $templateVars['message'],
				'{{diamond_id}}'        => $templateVars['diamond_id'],
				'{{diamond_url}}'       => $templateVars['diamond_url'],
				'{{shape}}'             => $templateVars['shape'],
				'{{size}}'              => $templateVars['size'],
				'{{cut}}'               => $templateVars['cut'],
				'{{color}}'             => $templateVars['color'],
				'{{clarity}}'           => $templateVars['clarity'],
				'{{depth}}'             => $templateVars['depth'],
				'{{table}}'             => $templateVars['table'],
				'{{measurment}}'        => $templateVars['measurment'],
				'{{certificate}}'       => $templateVars['certificate'],
				'{{show_Certificate}}'  => $templateVars['show_Certificate'],
				'{{price}}'             => $templateVars['price'],
				'{{wholeSalePrice}}'    => $templateVars['wholeSalePrice'],
				'{{vendorName}}'        => $retailername,
				'{{vendorStockNo}}'     => $templateVars['vendorStockNo'],
				'{{vendorEmail}}'       => $templateVars['vendorEmail'],
				'{{vendorContactNo}}'   => $templateVars['vendorContactNo'],
				'{{vendorFax}}'         => $templateVars['vendorFax'],
				'{{vendorAddress}}'     => $templateVars['vendorAddress'],
				'{{retailerName}}'      => $templateVars['retailerName'],
				'{{retailerEmail}}'     => $templateVars['retailerEmail'],
				'{{retailerContactNo}}' => $templateVars['retailerContactNo'],
				'{{retailerStockNo}}'   => $templateVars['retailerStockNo'],
				'{{retailerFax}}'       => $templateVars['retailerFax'],
				'{{retailerAddress}}'   => $templateVars['retailerAddress'],

			);

			// Sender email
			$transport_sender_template = gemfindDT_email_friend_email_template_sender();
			$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
			$sender_subject            = "A Friend Wants To Share With You";
			$senderFromAddress         = $all_optinos['from_email_address'];
			$headers                   = array('From: ' . $senderFromAddress . '');
			$senderToEmail             = $email_friend_post_data['email'];
			wp_mail($senderToEmail, $sender_subject, $sender_email_body, $headers);

			// Receiver email
			$transport_receiver_template = gemfindDT_email_friend_email_template_receiver();
			$receiver_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_receiver_template);
			$receiver_subject            = "A Friend Wants To Share With You";
			$receiver_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $senderFromAddress . '');
			$receiver_toEmail            = $email_friend_post_data['friend_email'];
			wp_mail($receiver_toEmail, $receiver_subject, $receiver_email_body, $headers);

			// Retailer email
			$transport_retailer_template = gemfindDT_email_friend_email_template_retailer();
			$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
			$retailer_subject            = "A Friend Wants To Share With You";
			$retailer_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $retailer_fromAddress . '');
			$retailer_toEmail            = $retaileremail;
			wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);

			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			$allowed_html = array(
				'div' => array(
					'id' => array(),
					'class' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'data-steps' => array(),
				),
				'input' => array(
					'name' => array(),
					'id' => array(),
					'type' => array(),
					'value' => array(),
					'class' => array(),
					'data-type' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'autocomplete' => array(),
					'inputmode' => array(),
				),
				'h4' => array(
					'class' => array(),
				),
				'ul' => array(
					'id' => array(),
				),
				'li' => array(
					'class' => array(),
					'title' => array(),
				),
				'script' => array(
					'type' => array(),
				),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'img' => array(
					'src' => array(),
					'alt' => array(),
					'title' => array(),
					'width' => array(),
					'height' => array(),
				),
				'style' => array(
					'type' => array(),
				),
				'br' => array(),
				'hr' => array(),
			);
			echo wp_kses($result, $allowed_html);
			// echo esc_html($result);
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
	die();
}

add_action('wp_ajax_nopriv_gemfindDT_resultemailfriend', 'gemfindDT_resultemailfriend');
add_action('wp_ajax_gemfindDT_resultemailfriend', 'gemfindDT_resultemailfriend');

function gemfindDT_resultscheview()
{
	$form_data          = rest_sanitize_array($_POST['form_data']);
	$all_options        = gemfindDT_getOptions();
	$sch_view_post_data = array();
	$alldata = get_option('gemfind_diamond_link');
	$diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$sch_view_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	if ($sch_view_post_data && $sch_view_post_data['email'] != "" && $sch_view_post_data['phone'] != "" && $sch_view_post_data['avail_date'] != "") {
		try {
			$diamondData = gemfindDT_getDiamondById($sch_view_post_data['diamondid'], $sch_view_post_data['diamondtype'], $sch_view_post_data['shopurl']);
			//$retaileremail = $diamondData['diamondData']['vendorEmail'];
			$retaileremail            = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername             = ($diamondData['diamondData']['vendorName'] ? $diamondData['diamondData']['vendorName'] : $store_detail);
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';

			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
				<td class="consumer-title">Lab:</td>
				<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
				</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$';

			if ($diamondData['diamondData']['showPrice'] == 1) {
				$price  = $diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
			} else {
				$price = 'Call For Price';
			}

			$templateVars             = array(
				'name'              => $sch_view_post_data['name'],
				'email'             => $sch_view_post_data['email'],
				'phone'             => $sch_view_post_data['phone'],
				'hint_message'      => $sch_view_post_data['hint_message'],
				'location'          => $sch_view_post_data['location'],
				'avail_date'        => $sch_view_post_data['avail_date'],
				'appnt_time'        => $sch_view_post_data['appnt_time'],
				'diamond_url'       => (isset($sch_view_post_data['diamondurl'])) ? $sch_view_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'shape'             => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);
			$templateValueReplacement = array(
				'{{shopurl}}'       => $shopurl,
				'{{shop_logo}}'     => $store_logo,
				'{{shop_logo_alt}}' => $store_detail->shop->name,
				'{{name}}'          => $templateVars['name'],
				'{{email}}'         => $templateVars['email'],
				'{{phone}}'         => $templateVars['phone'],
				'{{hint_message}}'  => $templateVars['hint_message'],
				'{{location}}'      => $templateVars['location'],
				'{{appnt_time}}'    => $templateVars['appnt_time'],
				'{{avail_date}}'    => $templateVars['avail_date'],
				'{{diamond_id}}'    => $templateVars['diamond_id'],
				'{{diamond_url}}'   => $templateVars['diamond_url'],
				'{{shape}}'         => $templateVars['shape'],
				'{{size}}'          => $templateVars['size'],
				'{{cut}}'           => $templateVars['cut'],
				'{{color}}'         => $templateVars['color'],
				'{{clarity}}'       => $templateVars['clarity'],
				'{{depth}}'         => $templateVars['depth'],
				'{{table}}'         => $templateVars['table'],
				'{{measurment}}'    => $templateVars['measurment'],
				'{{certificate}}'   => $templateVars['certificate'],
				'{{price}}'         => $templateVars['price'],
				'{{show_Certificate}}'   => $templateVars['show_Certificate'],
				'{{retailerName}}'  => $retailername

			);

			$transport_retailer_template = gemfindDT_schedule_view_email_template_admin();
			$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
			$retailer_subject            = "Request To Schedule A Viewing";
			$retailer_fromAddress        = $all_optinos['from_email_address'];
			$headers                     = array('From: ' . $retailer_fromAddress . '');
			if ($all_options['enable_admin_notify'] == true) {
				// Retailer email				
				$retailer_toEmail            = $retaileremail;
				wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			}

			$userEmail = $templateVars['email'];
			if (isset($userEmail) && $userEmail != "") {
				$transport_user_template = gemfindDT_schedule_view_email_template_user();
				$user_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_user_template);
				wp_mail($userEmail, $retailer_subject, $user_email_body, $headers);
			}

			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			$allowed_html = array(
				'div' => array(
					'id' => array(),
					'class' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'data-steps' => array(),
				),
				'input' => array(
					'name' => array(),
					'id' => array(),
					'type' => array(),
					'value' => array(),
					'class' => array(),
					'data-type' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'autocomplete' => array(),
					'inputmode' => array(),
				),
				'h4' => array(
					'class' => array(),
				),
				'ul' => array(
					'id' => array(),
				),
				'li' => array(
					'class' => array(),
					'title' => array(),
				),
				'script' => array(
					'type' => array(),
				),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'img' => array(
					'src' => array(),
					'alt' => array(),
					'title' => array(),
					'width' => array(),
					'height' => array(),
				),
				'style' => array(
					'type' => array(),
				),
				'br' => array(),
				'hr' => array(),
			);
			echo wp_kses($result, $allowed_html);
			// echo esc_html($result);
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
		$data   = array('status' => 0, 'msg' => $message);
		$result = json_encode(array('output' => $data));
		$allowed_html = array(
			'div' => array(
				'id' => array(),
				'class' => array(),
				'data-min' => array(),
				'data-max' => array(),
				'data-steps' => array(),
			),
			'input' => array(
				'name' => array(),
				'id' => array(),
				'type' => array(),
				'value' => array(),
				'class' => array(),
				'data-type' => array(),
				'data-min' => array(),
				'data-max' => array(),
				'autocomplete' => array(),
				'inputmode' => array(),
			),
			'h4' => array(
				'class' => array(),
			),
			'ul' => array(
				'id' => array(),
			),
			'li' => array(
				'class' => array(),
				'title' => array(),
			),
			'script' => array(
				'type' => array(),
			),
			'a' => array(
				'href' => array(),
				'title' => array(),
				'target' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'img' => array(
				'src' => array(),
				'alt' => array(),
				'title' => array(),
				'width' => array(),
				'height' => array(),
			),
			'style' => array(
				'type' => array(),
			),
			'br' => array(),
			'hr' => array(),
		);
		echo wp_kses($result, $allowed_html);
		// echo esc_html($result);
		die();
	}
	$message = 'Not found all the required fields';
	$data    = array('status' => 0, 'msg' => $message);
	$result  = json_encode(array('output' => $data));
	$allowed_html = array(
		'div' => array(
			'id' => array(),
			'class' => array(),
			'data-min' => array(),
			'data-max' => array(),
			'data-steps' => array(),
		),
		'input' => array(
			'name' => array(),
			'id' => array(),
			'type' => array(),
			'value' => array(),
			'class' => array(),
			'data-type' => array(),
			'data-min' => array(),
			'data-max' => array(),
			'autocomplete' => array(),
			'inputmode' => array(),
		),
		'h4' => array(
			'class' => array(),
		),
		'ul' => array(
			'id' => array(),
		),
		'li' => array(
			'class' => array(),
			'title' => array(),
		),
		'script' => array(
			'type' => array(),
		),
		'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
		),
		'span' => array(
			'class' => array(),
		),
		'img' => array(
			'src' => array(),
			'alt' => array(),
			'title' => array(),
			'width' => array(),
			'height' => array(),
		),
		'style' => array(
			'type' => array(),
		),
		'br' => array(),
		'hr' => array(),
	);
	echo wp_kses($result, $allowed_html);
	// echo esc_html($result);
	die();
}

add_action('wp_ajax_nopriv_gemfindDT_resultscheview', 'gemfindDT_resultscheview');
add_action('wp_ajax_gemfindDT_resultscheview', 'gemfindDT_resultscheview');

/**
 * Will send request info in mail.
 */
function gemfindDT_resultreqinfo1()
{
	$form_data     = rest_sanitize_array($_POST['form_data']);
	$all_options   = gemfindDT_getOptions();
	$req_post_data = array();
	$alldata = get_option('gemfind_diamond_link');
	$diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $alldata['diamondsoptionapi']));
	$show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
	foreach ($form_data as $data) {
		$req_post_data[$data['name']] = $data['value'];
	}
	$store_detail = get_bloginfo('name');
	$store_logo   = $all_options['shop_logo'];
	// get website logo dynamic if not provided in admin setting
	if ($store_logo == '') {
		$logo_url_check = et_get_option('divi_logo');
		$store_logo = stristr($logo_url_check, 'http://') ?: stristr($logo_url_check, 'https://');
		if ($store_logo == '') {
			$store_logo = site_url() . et_get_option('divi_logo');
		}
	}
	if ($req_post_data && $req_post_data['name'] !== "" && $req_post_data['email'] && $req_post_data['phone'] && $req_post_data['contact_pref']) {
		try {
			$diamondData   = gemfindDT_getDiamondById($req_post_data['diamondid'], $req_post_data['diamondtype'], $req_post_data['shopurl']);
			$retaileremail = ($all_options['admin_email_address'] ? $all_options['admin_email_address'] : $diamondData['diamondData']['vendorEmail']);
			$retailername  = $diamondData['diamondData']['retailerInfo']->retailerName;
			$certificateUrl  = (isset($diamondData['diamondData']['certificateUrl'])) ? ' <a href=' . $diamondData['diamondData']['certificateUrl'] . '>View Certificate</a>' : '';

			if ($show_Certificate_in_Diamond_Search) {
				$certificate   = (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : 'Not Available';
				$certificateNo = (isset($diamondData['diamondData']['certificateNo'])) ? $diamondData['diamondData']['certificateNo'] : '';
				$certificateUrl = (isset($certificateUrl)) ? $certificateUrl : '';
				$show_Certificate = '<tr>
					<td class="consumer-title">Lab:</td>
					<td class="consumer-name">' . $certificateNo . ' ' . $certificate . ' ' . $certificateUrl . '</td>
					</tr>';
			}

			$currency = $diamondData['diamondData']['currencyFrom'] != 'USD' ? $diamondData['diamondData']['currencySymbol'] : '$';

			if ($diamondData['diamondData']['showPrice'] == 1) {
				$price  = $diamondData['diamondData']['fltPrice'] ? $currency . number_format($diamondData['diamondData']['fltPrice']) : '';
			} else {
				$price = 'Call For Price';
			}

			$templateVars  = array(
				'name'              => (isset($req_post_data['name'])) ? $req_post_data['name'] : '',
				'email'             => (isset($req_post_data['email'])) ? $req_post_data['email'] : '',
				'phone'             => (isset($req_post_data['phone'])) ? $req_post_data['phone'] : '',
				'hint_message'      => (isset($req_post_data['hint_message'])) ? $req_post_data['hint_message'] : '',
				'contact_pref'      => (isset($req_post_data['contact_pref'])) ? $req_post_data['contact_pref'] : '',
				'diamond_url'       => (isset($req_post_data['diamondurl'])) ? $req_post_data['diamondurl'] : '',
				'diamond_id'        => (isset($diamondData['diamondData']['diamondId'])) ? $diamondData['diamondData']['diamondId'] : '',
				'size'              => (isset($diamondData['diamondData']['caratWeight'])) ? $diamondData['diamondData']['caratWeight'] : '',
				'shape'               => (isset($diamondData['diamondData']['shape'])) ? $diamondData['diamondData']['shape'] : '',
				'cut'               => (isset($diamondData['diamondData']['cut'])) ? $diamondData['diamondData']['cut'] : '',
				'color'             => (isset($diamondData['diamondData']['color'])) ? $diamondData['diamondData']['color'] : '',
				'clarity'           => (isset($diamondData['diamondData']['clarity'])) ? $diamondData['diamondData']['clarity'] : '',
				'depth'             => (isset($diamondData['diamondData']['depth'])) ? $diamondData['diamondData']['depth'] : '',
				'table'             => (isset($diamondData['diamondData']['table'])) ? $diamondData['diamondData']['table'] : '',
				'measurment'        => (isset($diamondData['diamondData']['measurement'])) ? $diamondData['diamondData']['measurement'] : '',
				'certificate'       => (isset($diamondData['diamondData']['certificate'])) ? $diamondData['diamondData']['certificate'] : '',
				'price'             => $price,
				'vendorID'          => (isset($diamondData['diamondData']['vendorID'])) ? $diamondData['diamondData']['vendorID'] : '',
				'vendorName'        => (isset($diamondData['diamondData']['vendorName'])) ? $diamondData['diamondData']['vendorName'] : '',
				'vendorEmail'       => (isset($diamondData['diamondData']['vendorEmail'])) ? $diamondData['diamondData']['vendorEmail'] : '',
				'vendorContactNo'   => (isset($diamondData['diamondData']['vendorContactNo'])) ? $diamondData['diamondData']['vendorContactNo'] : '',
				'vendorStockNo'     => (isset($diamondData['diamondData']['vendorStockNo'])) ? $diamondData['diamondData']['vendorStockNo'] : '',
				'vendorFax'         => (isset($diamondData['diamondData']['vendorFax'])) ? $diamondData['diamondData']['vendorFax'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'wholeSalePrice'    => (isset($diamondData['diamondData']['wholeSalePrice'])) ? $diamondData['diamondData']['wholeSalePrice'] : '',
				'vendorAddress'     => (isset($diamondData['diamondData']['vendorAddress'])) ? $diamondData['diamondData']['vendorAddress'] : '',
				'retailerName'      => (isset($diamondData['diamondData']['retailerInfo']->retailerName)) ? $diamondData['diamondData']['retailerInfo']->retailerName : '',
				'retailerID'        => (isset($diamondData['diamondData']['retailerInfo']->retailerID)) ? $diamondData['diamondData']['retailerInfo']->retailerID : '',
				'retailerEmail'     => (isset($diamondData['diamondData']['retailerInfo']->retailerEmail)) ? $diamondData['diamondData']['retailerInfo']->retailerEmail : '',
				'retailerContactNo' => (isset($diamondData['diamondData']['retailerInfo']->retailerContactNo)) ? $diamondData['diamondData']['retailerInfo']->retailerContactNo : '',
				'retailerFax'       => (isset($diamondData['diamondData']['retailerInfo']->retailerFax)) ? $diamondData['diamondData']['retailerInfo']->retailerFax : '',
				'retailerAddress'   => (isset($diamondData['diamondData']['retailerInfo']->retailerAddress)) ? $diamondData['diamondData']['retailerInfo']->retailerAddress : '',
				'retailerStockNo'   => (isset($diamondData['diamondData']['retailerInfo']->retailerStockNo)) ? $diamondData['diamondData']['retailerInfo']->retailerStockNo : '',
				'show_Certificate'	=> (isset($show_Certificate)) ? $show_Certificate : '',
			);

			$templateValueReplacement = array(
				'{{shopurl}}'           => $shopurl,
				'{{shop_logo}}'         => $store_logo,
				'{{shop_logo_alt}}'     => 'Gemfind Diamond Link',
				'{{name}}'              => $templateVars['name'],
				'{{email}}'             => $templateVars['email'],
				'{{phone}}'             => $templateVars['phone'],
				'{{hint_message}}'      => $templateVars['hint_message'],
				'{{contact_pref}}'      => $templateVars['contact_pref'],
				'{{diamond_id}}'        => $templateVars['diamond_id'],
				'{{diamond_url}}'       => $templateVars['diamond_url'],
				'{{size}}'              => $templateVars['size'],
				'{{shape}}'             => $templateVars['shape'],
				'{{cut}}'               => $templateVars['cut'],
				'{{color}}'             => $templateVars['color'],
				'{{clarity}}'           => $templateVars['clarity'],
				'{{depth}}'             => $templateVars['depth'],
				'{{table}}'             => $templateVars['table'],
				'{{measurment}}'        => $templateVars['measurment'],
				'{{certificate}}'       => $templateVars['certificate'],
				'{{price}}'             => $templateVars['price'],
				'{{show_Certificate}}'  => $templateVars['show_Certificate'],
				'{{wholeSalePrice}}'    => $templateVars['wholeSalePrice'],
				'{{vendorName}}'        => $templateVars['vendorName'],
				'{{vendorStockNo}}'     => $templateVars['vendorStockNo'],
				'{{vendorEmail}}'       => $templateVars['vendorEmail'],
				'{{vendorContactNo}}'   => $templateVars['vendorContactNo'],
				'{{vendorFax}}'         => $templateVars['vendorFax'],
				'{{vendorAddress}}'     => $templateVars['vendorAddress'],
				'{{retailerName}}'      => $retailername,
				'{{retailerEmail}}'     => $templateVars['retailerEmail'],
				'{{retailerContactNo}}' => $templateVars['retailerContactNo'],
				'{{retailerFax}}'       => $templateVars['retailerFax'],
				'{{retailerAddress}}'   => $templateVars['retailerAddress'],
				'{{retailerStockNo}}'   => $templateVars['retailerStockNo'],

			);

			if ($all_options['enable_admin_notify'] == true) {
				// Retailer email
				$transport_retailer_template = gemfindDT_info_email_template_retailer();
				$retailer_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_retailer_template);
				$retailer_subject            = "Request For More Info";
				$retailer_fromAddress        = $all_optinos['from_email_address'];
				$headers                     = array('From: ' . $retailer_fromAddress . '');
				$retailer_toEmail            = $retaileremail;
				wp_mail($retailer_toEmail, $retailer_subject, $retailer_email_body, $headers);
			}

			// Sender email
			$transport_sender_template = gemfindDT_info_email_template_sender();
			$sender_email_body         = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $transport_sender_template);
			$sender_subject            = "Request For More Info";
			$sender_fromAddress        = $all_optinos['from_email_address'];
			$headers                   = array('From: ' . $senderFromAddress . '');
			$sender_toEmail            = $req_post_data['email'];
			wp_mail($sender_toEmail, $sender_subject, $sender_email_body, $headers);
			$message = 'Thanks for your submission.';
			$data    = array('status' => 1, 'msg' => $message);
			$result  = json_encode(array('output' => $data));
			$allowed_html = array(
				'div' => array(
					'id' => array(),
					'class' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'data-steps' => array(),
				),
				'input' => array(
					'name' => array(),
					'id' => array(),
					'type' => array(),
					'value' => array(),
					'class' => array(),
					'data-type' => array(),
					'data-min' => array(),
					'data-max' => array(),
					'autocomplete' => array(),
					'inputmode' => array(),
				),
				'h4' => array(
					'class' => array(),
				),
				'ul' => array(
					'id' => array(),
				),
				'li' => array(
					'class' => array(),
					'title' => array(),
				),
				'script' => array(
					'type' => array(),
				),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'img' => array(
					'src' => array(),
					'alt' => array(),
					'title' => array(),
					'width' => array(),
					'height' => array(),
				),
				'style' => array(
					'type' => array(),
				),
				'br' => array(),
				'hr' => array(),
			);
			echo wp_kses($result, $allowed_html);
			// echo esc_html($result);
			die();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}
}

add_action('wp_ajax_nopriv_gemfindDT_resultreqinfo1', 'gemfindDT_resultreqinfo1');
add_action('wp_ajax_gemfindDT_resultreqinfo1', 'gemfindDT_resultreqinfo1');

/**
 * For print diamond.
 */
function gemfindDT_printdiamond()
{
	if (strpos(sanitize_url($_SERVER["HTTP_REFERER"]), "labcreated") !== false) {
		$_POST['diamondtype'] = 'labcreated';
	} else {
		$_POST['diamondtype'] = '';
	}

	//print_r($_SERVER["HTTP_REFERER"]);
	$printData = array('diamond_id' => absint($_POST['diamondid']), 'shop' => sanitize_text_field($_POST['shop']));
	$diamond   = gemfindDT_getDiamondById(absint($_POST['diamondid']), sanitize_text_field($_POST['diamondtype']), sanitize_text_field($_POST['shop']));

?>
	<div class="printdiv" id="printdiv">
		<div class="print-header" style="background-color:#1979c3 !important; color: #fff !important;">
			<div class="header-container">
				<div class="header-title">
					<h2 style="color: #fff !important;"><?php esc_html_e('Diamond Detail', 'gemfind-diamond-tool'); ?></h2>
				</div>
				<div class="header-date">
					<h4 style="color: #fff !important;"><?php echo esc_html(date("d/m/Y")); ?></h4>
				</div>
			</div>
		</div>
		<section class="diamonds-search with-specification diamond-page">
			<div class="d-container">
				<div class="d-row">
					<div class="diamonds-print-preview no-padding" style="background-color: #f7f7f7 !important;      border: 1px solid #e8e8e8 !important;">

						<div class="diamond-info-two">
							<img src="<?php echo esc_html($diamond['diamondData']['image2']) ?>" style="height: 100px;width: auto;" />
							<img src="<?php echo esc_html($diamond['diamondData']['image1']) ?>" style="height: 100px;width: 165px;" />
						</div>
						<div class="print-info">
							<p>SKU# <span><?php echo esc_html($diamond['diamondData']['diamondId']) ?></span></p>
						</div>
					</div>
					<div class="print-diamond-certifications">
						<div class="diamonds-grade">
							<img src="<?php echo esc_html($diamond['diamondData']['certificateIconUrl']) ?>" style="height: 75px;width: 75px;" />
						</div>
						<div class="diamonds-grade-info">
							<p><?php echo esc_html($diamond['diamondData']['subHeader']); ?></p>
						</div>
					</div>
					<div class="print-details">
						<div class="diamond-title">
							<div class="diamond-name">
								<h2><?php echo esc_html($diamond['diamondData']['mainHeader']); ?></h2>
								<p><?php echo esc_html($diamond['diamondData']['subHeader']); ?></p>
							</div>
							<div class="diamond-price" style="text-align: right;">
								<span><?php
										echo (esc_html($diamond['diamondData']['currencyFrom']) != 'USD') ?
											esc_html($diamond['diamondData']['currencyFrom']) . esc_html($diamond['diamondData']['currencySymbol']) . number_format($diamond['diamondData']['fltPrice']) :
											esc_html($diamond['diamondData']['currencySymbol']) . number_format($diamond['diamondData']['fltPrice']);
										?></span>
							</div>
						</div>
						<div class="diamond-inner-details">
							<ul>
								<?php if (isset($diamond['diamondData']['diamondId'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Stock Number', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['diamondId']) ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['fltPrice'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Price', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo (esc_html($diamond['diamondData']['currencyFrom']) != 'USD') ? $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']) : $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['fltPrice']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['caratWeight'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Carat Weight', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['caratWeight']) ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['cut'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Cut', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['cut']) ?></p>
										</div>
									</li>
								<?php } ?>
								<?php
								if ($diamond['diamondData']['fancyColorMainBody']) {
									$color_to_display = $diamond['diamondData']['fancyColorIntensity'] . ' ' . $diamond['diamondData']['fancyColorMainBody'];
								} else {
									$color_to_display = $diamond['diamondData']['color'];
								}          ?>
								<?php if (isset($color_to_display)) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Color', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($color_to_display); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['clarity'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Clarity', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['clarity']) ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['certificate'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Report', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<span><?php echo esc_html($diamond['diamondData']['certificate']); ?></span>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['depth'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Depth %', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['depth']) . '%'; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['table'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Table %', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['table']) . '%'; ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['polish'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Polish', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['polish']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['symmetry'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Symmetry', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['symmetry']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['gridle'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Girdle', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['gridle']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['culet'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Culet', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['culet']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['fluorescence'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Fluorescence', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['fluorescence']); ?></p>
										</div>
									</li>
								<?php } ?>
								<?php if (isset($diamond['diamondData']['measurement'])) { ?>
									<li>
										<div class="diamond-specifications">
											<p><?php esc_html_e('Measurement', 'gemfind-diamond-tool'); ?></p>
										</div>
										<div class="diamond-quality">
											<p><?php echo esc_html($diamond['diamondData']['measurement']); ?></p>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php
	die();
}

add_action('wp_ajax_nopriv_gemfindDT_printdiamond', 'gemfindDT_printdiamond');
add_action('wp_ajax_gemfindDT_printdiamond', 'gemfindDT_printdiamond');


/**
 * For allowing html content in mail.
 */
function gemfindDT_wpse27856_set_content_type()
{
	return "text/html";
}

add_filter('wp_mail_content_type', 'gemfindDT_wpse27856_set_content_type');

/**
 * For creating product in WooCommerce upon add to cart.
 */
function gemfindDT_add_product_to_cart()
{
	$server_uri   = sanitize_text_field($_POST['diamond_name']);
	$diamond_path = rtrim($server_uri, '/');
	$diamond_path = end(explode('/', $diamond_path));
	$diamond      = json_decode(stripslashes(sanitize_text_field($_POST['diamond'])), 1);
	$post_id      = gemfindDT_get_product_by_sku($diamond['diamondData']['diamondId']);
	if (isset($post_id) && $post_id != '') {
		return;
	}
	$post = array(
		'post_author'  => get_current_user_id(),
		'post_content' => '',
		'post_name'    => $diamond_path,
		'post_status'  => "publish",
		'post_title'   => $diamond['diamondData']['mainHeader'],
		'post_parent'  => '',
		'post_excerpt' => $diamond['diamondData']['subHeader'],
		'post_type'    => "product",
	);
	//Create post
	$post_id = wp_insert_post($post, $wp_error);
	update_post_meta($post_id, '_sku', $diamond['diamondData']['diamondId']);
	update_post_meta($post_id, '_regular_price', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, 'costPerCarat', $diamond['diamondData']['costPerCarat']);
	update_post_meta($post_id, 'image1', $diamond['diamondData']['image1']);
	update_post_meta($post_id, 'image2', $diamond['diamondData']['image2']);
	update_post_meta($post_id, 'videoFileName', $diamond['diamondData']['videoFileName']);
	update_post_meta($post_id, 'certificateNo', $diamond['diamondData']['certificateNo']);
	update_post_meta($post_id, 'certificateUrl', $diamond['diamondData']['certificateUrl']);
	update_post_meta($post_id, 'certificateIconUrl', $diamond['diamondData']['certificateIconUrl']);
	update_post_meta($post_id, 'measurement', $diamond['diamondData']['measurement']);
	update_post_meta($post_id, 'origin', $diamond['diamondData']['origin']);
	update_post_meta($post_id, 'gridle', $diamond['diamondData']['gridle']);
	update_post_meta($post_id, 'culet', $diamond['diamondData']['culet']);
	update_post_meta($post_id, 'cut', $diamond['diamondData']['cut']);
	update_post_meta($post_id, '_price', $diamond['diamondData']['fltPrice']);
	update_post_meta($post_id, 'Color', $diamond['diamondData']['color']);
	update_post_meta($post_id, 'ClarityID', $diamond['diamondData']['clarity']);
	update_post_meta($post_id, 'CutGrade', $diamond['diamondData']['cut']);
	update_post_meta($post_id, 'TableMeasure', $diamond['diamondData']['table']);
	update_post_meta($post_id, 'Polish', $diamond['diamondData']['polish']);
	update_post_meta($post_id, 'Symmetry', $diamond['diamondData']['symmetry']);
	update_post_meta($post_id, 'Measurements', $diamond['diamondData']['measurement']);
	update_post_meta($post_id, 'Certificate', $diamond['diamondData']['certificate']);
	update_post_meta($post_id, 'shape', $diamond['diamondData']['shape']);
	update_post_meta($post_id, 'product_type', 'gemfind');
	update_post_meta($post_id, 'internalUselink', $diamond['diamondData']['internalUselink']);
	$productAttr = array();
	if (isset($diamond['diamondData']['fancyColorMainBody']) && !empty($diamond['diamondData']['fancyColorMainBody'])) {
		$productAttr['pa_gemfind_fancy_certificate']  = $diamond['diamondData']['certificate'];
		$productAttr['pa_gemfind_fancy_clarity']      = $diamond['diamondData']['clarity'];
		$productAttr['pa_gemfind_fancy_color']        = $diamond['diamondData']['fancyColorMainBody'];
		$productAttr['pa_gemfind_fancy_fluorescence'] = $diamond['diamondData']['fluorescence'];
		$productAttr['pa_gemfind_fancy_polish']       = $diamond['diamondondData']['polish'];
		$productAttr['pa_gemfind_fancy_shape']        = $diamond['diamondData']['shape'];
		$productAttr['pa_gemfind_fancy_symmetry']     = $diamond['diamondData']['symmetry'];
		$productAttr['pa_gemfind_fancy_video']        = $diamond['diamondData']['videoFileName'];
		$productAttr['pa_gemfind_fancy_intensity']    = $diamond['diamondData']['fancyColorIntensity'];
		$productAttr['pa_gemfind_fancy_origin']       = $diamond['diamondData']['origin'];
		update_post_meta($post_id, 'caratWeightFancy', $diamond['diamondData']['caratWeight']);
		update_post_meta($post_id, 'tableFancy', $diamond['diamondData']['table']);
		update_post_meta($post_id, 'depthFancy', $diamond['diamondData']['depth']);
		update_post_meta($post_id, 'FltPriceFancy', $diamond['diamondData']['fltPrice']);
		update_post_meta($post_id, 'fancyColorIntensity', $diamond['diamondData']['fancyColorIntensity']);
		//$productAttr['pa_fancy_cut']        	= $diamond['diamondData']['cut'];
		update_post_meta($post_id, 'productType', 'fancy');
	} else {
		$productAttr['pa_gemfind_certificate']  = $diamond['diamondData']['certificate'];
		$productAttr['pa_gemfind_clarity']      = $diamond['diamondData']['clarity'];
		$productAttr['pa_gemfind_color']        = $diamond['diamondData']['color'];
		$productAttr['pa_gemfind_fluorescence'] = $diamond['diamondData']['fluorescence'];
		$productAttr['pa_gemfind_polish']       = $diamond['diamondondData']['polish'];
		$productAttr['pa_gemfind_shape']        = $diamond['diamondData']['shape'];
		$productAttr['pa_gemfind_symmetry']     = $diamond['diamondData']['symmetry'];
		$productAttr['pa_gemfind_video']        = $diamond['diamondData']['videoFileName'];
		$productAttr['pa_gemfind_cut']          = $diamond['diamondData']['cut'];
		update_post_meta($post_id, 'caratWeight', $diamond['diamondData']['caratWeight']);
		update_post_meta($post_id, 'table', $diamond['diamondData']['table']);
		update_post_meta($post_id, 'depth', $diamond['diamondData']['depth']);
		update_post_meta($post_id, 'FltPrice', $diamond['diamondData']['fltPrice']);
		if (isset($diamond['diamondData']['origin']) && $diamond['diamondData']['origin'] == 'LAB-CREATED') {
			update_post_meta($post_id, 'productType', 'labcreated');
		} else {
			update_post_meta($post_id, 'productType', 'standard');
		}
	}
	$product_attributes = array();
	foreach ($productAttr as $key => $value) {
		wp_set_object_terms($post_id, $value, $key, true);
		$product_attributes_meta    = get_post_meta($post_id, '_product_attributes', true);
		$count                      = (is_array($product_attributes_meta)) ? count($product_attributes_meta) : 0;
		$product_attributes[$key] = array(
			'name'        => $key,
			'value'       => $value,
			'position'    => $count, // added
			'is_visible'  => 1,
			'is_taxonomy' => 1
		);
	}
	update_post_meta($post_id, '_product_attributes', $product_attributes);
	//update_post_meta( $post_id, '_visibility', 'visible' );
	$terms = array('exclude-from-catalog', 'exclude-from-search');
	wp_set_object_terms($post_id, $terms, 'product_visibility');
	update_post_meta($post_id, '_stock_status', 'instock');
	update_post_meta($post_id, '_wc_min_qty_product', 0);
	update_post_meta($post_id, '_wc_max_qty_product', 1);
	$image_url = (isset($diamond['diamondData']['colorDiamond']) && !empty($diamond['diamondData']['colorDiamond'])) ? $diamond['diamondData']['colorDiamond'] : $diamond['diamondData']['image2'];
	gemfindDT_custom_thumbnail_set($post_id, $image_url, 'featured_image');
	echo esc_html($post_id);
	die();
}

add_action('wp_ajax_nopriv_gemfindDT_add_product_to_cart', 'gemfindDT_add_product_to_cart');
add_action('wp_ajax_gemfindDT_add_product_to_cart', 'gemfindDT_add_product_to_cart');

function gemfindDT_mj_taxonomy_add_custom_field($tag)
{
	//check for existing taxonomy meta for term ID
	$t_id      = $tag->term_id;
	$term_meta = get_option("taxonomy_$t_id");	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="cat_Image_url"><?php esc_html_e('Attribute Image Url'); ?></label></th>
		<td>
			<input type="text" name="term_meta[fancy_color_img]" id="term_meta[fancy_color_img]" size="3" style="width:60%;" value="<?php echo esc_attr($term_meta['fancy_color_img']) ? esc_attr($term_meta['fancy_color_img']) : ''; ?>"><br />
			<span class="description"><?php esc_html_e('Image for Fancy Color: use full url', 'gemfind_diamond_link'); ?></span>
		</td>
	</tr>
<?php
}

add_action('pa_gemfind_fancy_color_add_form_fields', 'gemfindDT_mj_taxonomy_add_custom_field', 10, 2);
add_action('pa_gemfind_fancy_color_edit_form_fields', 'gemfindDT_mj_taxonomy_add_custom_field', 10, 2);

add_action('edited_pa_gemfind_fancy_color', 'gemfindDT_save_fancy_color_field', 10, 2);
function gemfindDT_save_fancy_color_field($term_id)
{
	if (isset($_POST['term_meta'])) {
		$t_id      = $term_id;
		$term_meta = get_option("taxonomy_$t_id");
		$cat_keys  = array_keys(sanitize_text_field($_POST['term_meta']));
		foreach ($cat_keys as $key) {
			if (isset($_POST['term_meta'][$key])) {
				$term_meta[$key] = sanitize_text_field($_POST['term_meta'])[$key];
			}
		}
		update_option("taxonomy_$t_id", $term_meta);
	}
}
function gemfindDT_nestedLowercase($value)
{
	if (is_array($value)) {
		return array_map('gemfindDT_nestedLowercase', $value);
	}
	return strtolower($value);
}
require_once('general-functions.php');
