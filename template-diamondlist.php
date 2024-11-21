<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly
/**
 * Template Name: Diamond List
 */
get_header();
include_once('head.php');
$the_slug = 'diamondlink';
$args     = array(
    'name'        => $the_slug,
    'post_type'   => 'page',
    'post_status' => 'publish',
    'numberposts' => 1
);
$my_posts = get_posts($args);
if ($my_posts) :

    // Update post diamondlink
    $my_post = array(
        'ID'            => $my_posts[0]->ID,
        'page_template' => 'template-diamondlist.php',
    );

    // Update the post into the database
    wp_update_post($my_post);
endif;
$options = gemfindDT_getOptions();
if (!empty($options['dealerid'])) {
    $navigationapi      = get_option('gemfind_diamond_link');
    $results            = gemfindDT_sendRequest(array('navigationapi' => $navigationapi['navigationapi']));
    // echo '<pre>';
    // print_r($results);
    // exit;
    $show_hints_popup   = $navigationapi['show_hints_popup'];
    $sticky_header      = $navigationapi['enable_sticky_header'];
    $enable_price_users = $navigationapi['enable_price_users'];
    if ($navigationapi['default_view'] == "") {
        $navigationapi['default_view'] = "list";
    }
?>
    <section id="gemfind-product-demo-site" class="diamond-list-screen">
        <div class="site-wrapper">
            <div class="grid">
                <main class="main-content grid__item" id="MainContent" role="main">
                    <?php if (!empty($options['top_textarea'])) { ?>
                        <div class="diamonds-details" style="display:none;">
                            <div class="diamond-bar">
                                <?php echo esc_html($options['top_textarea']); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="placeholder-content">
                        <div class="placeholder-content_item box1"></div>
                        <div class="placeholder-filter">
                            <div class="placeholder-filter_inner placeholder-flex">
                                <div class="placeholder-content_item head"></div>
                                <div class="placeholder-filter_box placeholder-flex">
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                    <div class="placeholder-content_item box"></div>
                                </div>
                            </div>
                            <div class="placeholder-filter_inner placeholder-flex">
                                <div class="placeholder-content_item head"></div>
                                <div class="placeholder-content_item box-list placeholder-filter_box"></div>
                            </div>
                            <div class="placeholder-filter_inner placeholder-flex">
                                <div class="placeholder-content_item head"></div>
                                <div class="placeholder-content_item box-list placeholder-filter_box"></div>
                            </div>
                            <div class="placeholder-filter_inner placeholder-flex">
                                <div class="placeholder-content_item head"></div>
                                <div class="placeholder-content_item box-list placeholder-filter_box"></div>
                            </div>
                            <div class="placeholder-filter_inner placeholder-flex">
                                <div class="placeholder-content_item head"></div>
                                <div class="placeholder-content_item box-list placeholder-filter_box"></div>
                            </div>
                            <div class="placeholder-content_item"></div>
                        </div>
                    </div>
                    <div class="loading-mask gemfind-loading-mask" style="display:none;">
                        <div class="loader gemfind-loader">
                            <p>Please wait...</p>
                        </div>
                    </div>
                    <div id="search-diamonds">
                        <form id="search-diamonds-form" method="post" action="" class="search_form">
                            <input name="submitby" id="submitby" type="hidden" value="ajax">
                            <input name="defaultshapevalue" id="defaultshapevalue" type="hidden" value="<?php echo isset($_REQUEST['shape']) ? esc_attr(strtolower(sanitize_text_field($_REQUEST['shape']))) : ''; ?>">
                            <input name="sticky_header" id="sticky_header" type="hidden" value="<?php echo esc_html($sticky_header); ?>">
                            <section class="diamonds-search">
                                <div class="d-container">
                                    <div class="d-row">
                                        <div class="diamonds-details no-padding" style="display: none;">
                                            <div class="diamonds-filter">
                                                <div class="diamond-filter-title">
                                                    <ul class="filter-left" id="navbar">
                                                        <?php
                                                        $filter_mode = '';
                                                        $i = 0;
                                                        foreach ($results as $result) {
                                                            foreach ($result as $key => $value) {
                                                                if (strtolower($key) != 'navrequest' && strtolower($key) != '$id' && strtolower($key) != 'navadvanced') {
                                                                    if ($value) {
                                                                        if ($i == 0) {
                                                                            $filter_mode = strtolower($key);
                                                                        }
                                                        ?>
                                                                        <li class="<?php echo ($i == 0) ? 'active' : ''; ?>" id="<?php echo strtolower($key); ?>">
                                                                            <a href="javascript:;" onclick="gemfindDT_mode('<?php echo esc_js(strtolower($key)); ?>'); gemfindDT_diamond_search();" title="<?php echo esc_attr($value); ?>" id="<?php echo (strtolower($key) == 'navcompare') ? 'comparetop' : ''; ?>">
                                                                                <?php echo esc_html($value); ?>
                                                                            </a>
                                                                            <?php if (strtolower($key) != 'navcompare' && $show_hints_popup == 'yes') {  ?>
                                                                                <span class="show-filter-info" onclick="gemfindDT_showfilterinfo('<?php echo esc_js(strtolower($key)); ?>');"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                                                            <?php  } ?>
                                                                        </li>

                                                        <?php
                                                                        $i++;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                    <ul class="filter-right">
                                                        <li><a href="javascript:;" onclick="gemfindDT_SaveFilter();">Save
                                                                Search</a>
                                                        </li>
                                                        <li><a href="javascript:;" onclick="gemfindDT_ResetFilter();">Reset</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="filter-main-div" id="filter-main-div">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <?php
                            $uri         = sanitize_url($_SERVER['REQUEST_URI']);
                            $path        = parse_url($uri, PHP_URL_PATH);
                            $filename    = pathinfo($path, PATHINFO_FILENAME);
                            //echo "Filename:".$filename;
                            /*$filter_mode = 'navstandard';*/
                            if ($filename == 'navstandard') {
                                $filter_mode = 'navstandard';
                            } else if ($filename == 'navfancycolored') {
                                $filter_mode = 'navfancycolored';
                            } else if ($filename == 'navlabgrown') {
                                $filter_mode = 'navlabgrown';
                            }
                            // in case filter_mode blank then
                            if ($filter_mode == '') {
                                $filter_mode = 'navstandard';
                            }
                            ?>
                            <input type="hidden" name="filtermode" id="filtermode" value="<?php echo esc_html($filter_mode) ?>">
                            <input type="submit" name="Submit" id="submit" style="visibility: hidden;">
                        </form>
                    </div>
                    <div class="placeholder-content">
                        <div class="placeholder-content_table">
                            <?php if ($navigationapi['default_view'] == "grid") { ?>
                                <div class="placeholder-product_list">
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                    <div class="placeholder-content_list">
                                        <div class="placeholder-content_item round-img"></div>
                                        <div class="placeholder-content_item placeholder-product"></div>
                                        <div class="placeholder-content_item placeholder-price"></div>
                                        <div class="placeholder-content_item placeholder-compare"></div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($navigationapi['default_view'] == "list") { ?>
                                <div class="placeholder-content_tableInner">
                                    <div class="placeholder-content_item box1"></div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                    <div class="placeholder-content_item placeholder-table_listing"> </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="placeholder-content_footer placeholder-flex">
                            <div class="placeholder-content_item button-box"></div>
                            <div class="placeholder-content_item head"></div>
                        </div>
                    </div>
                    <div class="result filter-advanced" id="scrollPage">
                    </div>
                    <?php
                    $gemfind_diamond_link = get_option('gemfind_diamond_link');
                    if ($gemfind_diamond_link['show_copyright'] == "yes") {                    ?>
                        <div class="copyright-text" style="display:none;">
                            <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
                        </div>
                    <?php  } ?>
                </main>
            </div>
            <hr>
        </div>
    </section>
<?php
} else { ?>
    <div class="error">
        <p>
            <strong>Error: </strong><?php esc_html_e("Dealer ID not found. Kindly insert correct Dealer ID in plugin configuration.", "gemfind-diamond-tool"); ?>
        </p>
    </div>
<?php } ?>
<script type="text/javascript">
    // for adding class to body
    jQuery(document).ready(function() {
        jQuery('body').addClass('diamond-list-page');
        var is_enable_sticky = jQuery('#sticky_header').val();
        if (is_enable_sticky == 'yes' && jQuery('.table_header_wrapper').length) {
            jQuery('body').removeClass('et_fixed_nav');
            jQuery('#page-container').css('padding-top', '0px');
        }
    });
</script>
<div class="modal fade" id="show-filter-info" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="note"></p>
            </div>
        </div>
    </div>
</div>
<?php if ($show_hints_popup == 'yes') { ?>
    <script type="text/javascript">
        var filtertype = '';

        function gemfindDT_showfilterinfo(filtertype) {
            var info_html = '';
            var baseurl = '<?php echo esc_url(DIAMOND_LINK_URL); ?>';
            var shopname = '<?php echo esc_html(sanitize_text_field($_SERVER['REQUEST_URI'])) == 'bylu.myshopify.com' ? esc_html('Ken & Dana Design') : esc_html('Our site'); ?>';
            console.log(filtertype);
            if (typeof filtertype !== 'undefined' && (filtertype == 'navstandard' || filtertype == 'natural_diamonds' || filtertype == 'mined')) {
                info_html = 'Formed over billions of years, natural diamonds are mined from the earth.  Diamonds are the hardest mineral on earth, which makes them an ideal material for daily wear over a lifetime.  Our natural diamonds are conflict-free and GIA certified.';
            }
            if (typeof filtertype !== 'undefined' && (filtertype == 'navfancycolored' || filtertype == 'colored_diamonds')) {
                info_html = 'Also known as fancy color diamonds, these are diamonds with colors that extend beyond GIA’s D-Z color grading scale. They fall all over the color spectrum, with a range of intensities and saturation. The most popular colors are pink and yellow.';
            }
            if (typeof filtertype !== 'undefined' && (filtertype == 'navlabgrown' || filtertype == 'lab_grown_diamonds')) {
                info_html = 'Lab-grown diamonds are created in a lab by replicating the high heat and high pressure environment that causes a natural diamond to form. They are compositionally identical to natural mined diamonds (hardness, density, light refraction, etc), and the two look exactly the same. A lab-grown diamond is an attractive alternative for those seeking a product with less environmental footprint.';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'shape') {
                info_html = '<p>A diamond’s shape is not the same as a diamond’s cut. The shape refers to the general outline of the stone, and not its light refractive qualities. Look for a shape that best suits the ring setting you have chosen, as well as the recipient’s preference and personality. Here are some of the more common shapes that ' + shopname + ' offers:</p><div class="popup-Diamond-Table" style="height:160px;"><ol class="list-unstyled"><li><span class="popup-Dimond-Sketch round"></span><span>Round</span></li><li><span class="popup-Dimond-Sketch asscher"></span><span>Asscher</span></li><li><span class="popup-Dimond-Sketch marquise"></span><span>Marquise</span></li><li><span class="popup-Dimond-Sketch oval"></span><span>Oval</span></li><li><span class="popup-Dimond-Sketch cushion"></span><span>Cushion</span></li><li><span class="popup-Dimond-Sketch radiant"></span><span>Radiant</span></li><li><span class="popup-Dimond-Sketch pear"></span><span>Pear</span></li><li><span class="popup-Dimond-Sketch emerald"></span><span>Emerald</span></li><li><span class="popup-Dimond-Sketch heart"></span><span>Heart</span></li><li><span class="popup-Dimond-Sketch princess"></span><span>Princess</span></li></ol></div>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'cut') {
                info_html = '<p>Not to be confused with shape, a diamond’s cut rating tells you how well its proportions interact with light. By evaluating the angles and proportions of the diamond, the cut grade is designed to tell you how sparkly and brilliant your stone is. Cut grading is usually not available for fancy shapes (any shape that is not round), because the mathematical formula that determines light return becomes less reliable when different length to width ratios are factored in.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'carat') {
                info_html = '<p>Carat is a unit of measurement to determine a diamond’s weight. Typically, a higher carat weight means a larger looking diamond, but that is not always the case. Look for the mm measurements of the diamond to determine its visible size.</p><img src="' + baseurl + '/assets/images/carat.jpg" alt="Carat">';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'price') {
                info_html = 'This refer to different type of Price to filter and select the appropriate ring as per your requirements. Look for a best suit Price of your chosen diamond.';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'color') {
                info_html = '<p>The color scale measures the degree of colorlessness in a diamond. D is the highest and most  colorless grade, but also the most expensive. To get the most value for your budget, look for an eye colorless stone. For most diamonds, this is in the F-H range.</p><img src="' + baseurl + '/assets/images/color.jpg" alt="Color">';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'clarity') {
                info_html = '<p>A diamond’s clarity refers to the tiny traces of natural elements that are trapped inside the stone. 99% of diamonds contain inclusions or flaws. You do not need a flawless diamond - they are very rare and expensive - but you want to look for one that is perfect to the naked eye. Depending on the shape of the diamond, the sweet spot for clarity is usually between VVS2 to SI1.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'depth') {
                info_html = '<p>Depth percentage is the height of the diamond measured from the culet to the table, divided by the width of the diamond. The lower the depth %, the larger the diamond will appear (given the same weight), but if this number is too low then the brilliance of the diamond will be sacrificed. The depth percentage is one of the elements that determines the Cut grading.  </p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'table') {
                info_html = '<p>Table percentage is the width of a diamond’s largest facet (the table) divided by its overall width. It tells you how big the “face” of a diamond is.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'polish') {
                info_html = '<p>Polish describes how smooth the surface of a diamond is. Aim for an Excellent or Very Good polish rating.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'symmetry') {
                info_html = '<p>Symmetry describes how symmetrical the diamond is cut all the way around, which is a contributing factor to a diamond’s sparkle and brilliance. Aim for an Excellent or Very Good symmetry rating for round brilliant shapes, and Excellent to Good for fancy shapes.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'fluorescence') {
                info_html = '<p>Fluorescence tells you how a diamond responds to ultraviolet light - does it glow under a black light? Diamonds with no fluorescence are generally priced higher on the market, but it is rare for fluorescence to have any visual impact on the diamond; some fluorescence can even enhance the look of the stone.  ' + shopname + ' recommends searching for diamonds with none to medium fluorescence, and keeping open the option of strong fluorescence for additional value.</p>';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'fancy_color') {
                info_html = 'Filter By Fancy Color';
            }
            if (typeof filtertype !== 'undefined' && filtertype == 'fancy_intensity') {
                info_html = 'The main color, and if there is a secondary color, together define the color tone, however the strength of color is defined by the intensity level. The intensity level can be anywhere from a very soft shade to a very strong shade, and the stronger the shade the more valuable the diamond.';

            }

            if (typeof filtertype == 'undefined') {
                info_html = "";
            }

            jQuery('#show-filter-info .modal-body p').html(info_html);
            filtertype = '';
            jQuery("#show-filter-info").modal("show");
        }
    </script>
    <div class="modal fade" id="show-filter-info" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!-- <h4 class="modal-title"></h4> -->
                </div>
                <div class="modal-body">
                    <p class="note"></p>
                </div>
            </div>
        </div>
    </div>
<?php  }  ?>
<?php get_footer(); ?>