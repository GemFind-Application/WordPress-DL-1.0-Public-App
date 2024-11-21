<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly
/**
 * Template Name: Compare Diamonds
 */
get_header();
include_once( 'head.php' );
$compare_diamond_data = sanitize_text_field($_COOKIE['comparediamondProduct']);
$data = json_decode(sanitize_text_field($_COOKIE['comparediamondProduct']), true);

$navigationapi = get_option( 'gemfind_diamond_link' );
$results       = gemfindDT_sendRequest( array( 'navigationapi' => $navigationapi['navigationapi'] ), get_site_url() );

// $diamondsoptionapi = get_option( 'gemfind_diamond_link' );
// $enable_price_users = $diamondsoptionapi['enable_price_users'];

// if($enable_price_users != "yes" || is_user_logged_in()){
//     $currency = gemfindDT_getCurrencySymbol();
// }else{
//     $currency = '';
// }


if ( isset( $results[0] ) ) : ?>
<style type="text/css">
    tr.remove {
        display: none;
    }
</style>
<section id="gemfind-product-demo-site" class="compare-diamond-screen">
    <main class="main-content grid__item" id="MainContent" role="main">
        <div class="loading-mask gemfind-loading-mask">
            <div class="loader gemfind-loader">
                <p>Please wait...</p>
            </div>
        </div>
        <div id="search-diamonds">
            <section class="compare-product diamonds-search">
                <div class="d-container">
                    <div class="d-row">
                        <div class="diamonds-details no-padding">
                            <div class="diamonds-filter">
                                <div class="filter-title">
                                    <ul class="filter-left">
                                        <?php
                                    foreach ( $results as $result ) {
                                        foreach ( $result as $key => $value ) {
                                            if ( strtolower( $key ) != 'navrequest' && strtolower( $key ) != '$id' && strtolower( $key ) != 'navadvanced' ) {
                                                if ( strtolower( $key ) == 'navstandard' ) {
                                                    $href = get_site_url() . '/diamondlink/navstandard';
                                                } elseif ( strtolower( $key ) == 'navfancycolored' ) {
                                                    $href = get_site_url() . '/diamondlink/navfancycolored';
                                                } elseif ( strtolower( $key ) == 'navlabgrown' ) {
                                                    $href = get_site_url() . '/diamondlink/navlabgrown';
                                                } else {
                                                    $href = "javascript:;";
                                                }
                                                ?>
                                        <li class="<?php echo esc_attr(strtolower($key)) == 'navcompare' ? 'active' : ''; ?>" id="<?php echo esc_attr(strtolower($key)); ?>">
                                            <a href="<?php echo esc_url($href); ?>"
                                                onclick="gemfindDT_mode(<?php echo esc_attr(strtolower($key)); ?>); gemfindDT_diamond_search();"
                                                title="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr(strtolower($key)) == 'navcompare' ? 'comparetop' : ''; ?>">
                                                <?php echo esc_attr($value); ?> </a>
                                        </li>
                                        <?php
                                            }
                                        }
                                    }
                                    ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="compare-info">
                                <?php
                            $noimageurl       = plugins_url( __DIR__ ) . "/assets/images/no-image.jpg";
                            $compare_products = json_decode( stripslashes( $compare_diamond_data ), 1 );
                            # Initialise a new array.
                            $compareItems = [];
                            # Iterate over every associative array in the data array.                            
                            foreach ( $compare_products as $compare_product ) {
                                # Iterate over every key-value pair of the associative array.
                                foreach ( $compare_product as $key => $value ) {
                                    # Ensure the sub-array specified by the key is set.
                                    if ( ! isset( $compareItems[ $key ] ) ) {
                                        $compareItems[ $key ] = [];
                                    }
                                    # Insert the value in the sub-array specified by the key.
                                    $compareItems[ $key ][] = $value;
                                }
                            }
                             if (empty($compare_products)) { ?>
                                <div class="emptydata">
                                    <h2> NO DIAMONDS TO COMPARE </h2>
                                </div>

                                <?php } else {
                            $alphaarray = array( 0 => 'a_col', 1 => 'b_col', 2 => 'c_col', 3 => 'd_col', 4 => 'e_col',5 => 'f_col' );

                            // print_r($compareItems);
                            // exit;
                            ?>
                                <div class="responsive-table">
                                    <table id="compare-sortable">
                                        <?php
                                    $i = 0;
                                    foreach ( $compareItems as $key => $value ):
                                        if ( $key == 'Image' ):
                                            ?>
                                        <thead class="thead-dark">
                                            <tr class="ui-state-default" id="disable-drag">
                                                <?php
                                                for ( $j = 0; $j < count( $value ); $j ++ ) {
                                                    if ( $i ++ == 0 ): ?>
                                                <td></td>
                                                <?php endif; ?>
                                                 <td class="<?php echo $alphaarray[ $j ]; ?>">
                                                        <?php
                                                        $diamond_image = preg_replace( '/\s/', '', $value[ $j ] );
                                                        $handle        = curl_init( $diamond_image );
                                                        curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );

                                                        /* Get the HTML or whatever is linked in $url. */
                                                        $response = curl_exec( $handle );

                                                        /* Check for 404 (file not found). */
                                                        $httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
                                                        curl_close( $handle );
                                                        ?>
                                                        <img alt="No Image" src="<?php if ( $httpCode != 404 ) {
                                                            echo $diamond_image;
                                                        } else {
                                                            echo $noimageurl;
                                                        } ?>"/>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                        else: $k = 0; ?>
                                            <?php if ($key == 'ID'):
                                                continue;
                                            endif; ?>

                                            <tr class="enable-drag">
                                                <?php
                                                for ( $j = 0; $j < count( $value ); $j ++ ):
                                                    if ( $k ++ == 0 ): ?>
                                                <th><a data-toggle="tooltip" data-placement="bottom" title="Remove Row"
                                                        href="javascript:;" class="rowremove"
                                                        onclick="this.parentNode.parentNode.className= 'remove'"><?php esc_html_e('Remove', 'gemfind-diamond-tool'); ?>
                                                    </a>
                                                    <?php if ($key == 'Sku'):
                                                        esc_html_e('#', 'gemfind-diamond-tool');
                                                    endif;
                                                    echo esc_html($key); ?>
                                                </th>
                                                <?php endif; ?>
                                                <td class="<?php echo esc_attr($alphaarray[$j]); ?>">
                                                    <?php
                                                    
                                                    if ($key == 'Price' && preg_match('/[0-9,]/', $value[$j])):
                                                        echo esc_attr(gemfindDT_getCurrencySymbol());
                                                    endif;
                                                    echo esc_attr(str_replace('.00', '', $value[$j])) ? esc_attr(str_replace('.00', '', $value[$j])) : '-';
                                                    if ($key == 'Table' && $value[$j]):
                                                        esc_html_e('%', 'gemfind-diamond-tool');
                                                    endif;
                                                    if ($key == 'Depth' && $value[$j]):
                                                        esc_html_e('%', 'gemfind-diamond-tool');
                                                    endif;
                                                    ?>
                                                </td>
                                                <?php endfor; ?>
                                            </tr>

                                            <?php endif;
                                    endforeach;
                                    ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="compare-actions">
                                                <?php
                                        $k = 0;
                                        for ( $i = 0; $i < count( $compareItems['Sku'] ); $i ++ ):
                                            ?>
                                                <?php if ( $k ++ == 0 ): ?>
                                                <td></td>
                                                <?php endif; ?>
                                                <td class="<?php echo esc_html($alphaarray[$i]); ?>">
                                                    <div class="actions-row">
                                                        <?php
                                                        if (isset($compareItems['Shape'])) {
                                                            $urlshape = esc_attr(str_replace(' ', '-', $compareItems['Shape'][$i])) . '-shape-';
                                                        } else {
                                                            $urlshape = '';
                                                        }
                                                        
                                                        if (isset($compareItems['Carat'])) {
                                                            $urlcarat = esc_attr(str_replace(' ', '-', $compareItems['Carat'][$i])) . '-carat-';
                                                        } else {
                                                            $urlcarat = '';
                                                        }
                                                        if (isset($compareItems['Color'])) {
                                                            $urlcolor = esc_attr(str_replace(' ', '-', $compareItems['Color'][$i])) . '-color-';
                                                        } else {
                                                            $urlcolor = '';
                                                        }
                                                        if (isset($compareItems['Clarity'])) {
                                                            $urlclarity = esc_attr(str_replace(' ', '-', $compareItems['Clarity'][$i])) . '-clarity-';
                                                        } else {
                                                            $urlclarity = '';
                                                        }
                                                        if (isset($compareItems['Cut'])) {
                                                            $urlcut = esc_attr(str_replace(' ', '-', $compareItems['Cut'][$i])) . '-cut-';
                                                        } else {
                                                            $urlcut = '';
                                                        }
                                                        if (isset($compareItems['Cert'])) {
                                                            $urlcert = esc_attr(str_replace(' ', '-', $compareItems['Cert'][$i])) . '-certificate-';
                                                        } else {
                                                            $urlcert = '';
                                                        }
                                                        
                                                        $urlstring = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $compareItems['ID'][$i]);
                                                        
                                                        $type = '';
                                                        if (isset($compareItems['Type'])) {
                                                            $type = $compareItems['Type'][$i];
                                                        }
                                                        $diamondviewurl = gemfindDT_getDiamondViewUrl($urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop) . '/' . $compareItems['Type'][$i];
                                                        ?>
                                                        <a href="<?php echo esc_url($diamondviewurl); ?>"
                                                            class="view-product"><?php esc_html_e('View', 'gemfind-diamond-tool'); ?>
                                                        </a>
                                                        <a data-toggle="tooltip" data-placement="bottom"
                                                            title="Remove Diamond" href="javascript:;"
                                                            class="delete-row"
                                                            onclick="gemfindDT_removeDummy('<?php echo esc_attr($alphaarray[$i]); ?>','<?php echo esc_attr($compareItems['ID'][$i]); ?>')"></a>
                                                    </div>
                                                </td>
                                                <?php endfor;
                                        ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="mobile-compare-view">
                                        <div class="compare-main-container">
                                            <?php
                                        foreach ( $compare_products as $key => $value ):
                                            ?>
                                            <div class="compare-items">
                                                <div class="item-col-1">
                                                    <img alt="Diamond" src="<?php echo esc_html($value['Image']); ?>" />
                                                    <span class="diamond-value shape-type"><?php echo esc_html($value['Image']); ?>" />
                                                </div>
                                                <div class="item-col-2">
                                                    <span class="diamond-value"><?php echo esc_html($value['Carat']) ? esc_html($value['Carat']) : '-'; ?></span>
                                                    <span class="diamond-label"><?php esc_html_e('Carat', 'gemfind-diamond-tool'); ?></span>
                                                    <span class="diamond-value"><?php echo esc_html($value['Clarity']); ?></span>
                                                    <span class="diamond-label"><?php esc_html_e('Clarity', 'gemfind-diamond-tool'); ?></span>
                                                </div>
                                                <div class="item-col-3">
                                                    <span class="diamond-value"><?php echo esc_html($value['Cut']) ? esc_html($value['Cut']) : '-'; ?></span>
                                                    <span class="diamond-label"><?php esc_html_e('Cut', 'gemfind-diamond-tool'); ?></span>
                                                    <span class="diamond-value"><?php echo esc_html($value['Color']); ?></span>
                                                    <span class="diamond-label"><?php esc_html_e('Color', 'gemfind-diamond-tool'); ?></span>
                                                </div>
                                                <div class="item-col-4">
                                                    <?php if($enable_price_users != "yes" || is_user_logged_in()){ ?>
                                                    <span class="diamond-value"><?php if ($value['Price'] && preg_match('/[0-9,]/', $value['Price'])):
                                                        echo esc_html(gemfindDT_getCurrencySymbol() . number_format($value['Price']));
                                                    endif; ?> </span>
                                                    <?php } else { ?>
                                                    <span class="diamond-value"><?php if ($value['Price'] && preg_match('/[0-9,]/', $value['Price'])):
                                                        $value['Price'];
                                                    endif; ?> </span>
                                                    <?php } ?>

                                                    <span class="diamond-label"><?php esc_html_e('Price', 'gemfind-diamond-tool'); ?></span>
                                                    <?php if (isset($value['Shape'])) {
                                                        $urlshape = str_replace(' ', '-', $value['Shape']) . '-shape-';
                                                    } else {
                                                        $urlshape = '';
                                                    }
                                                    
                                                    if (isset($value['Carat'])) {
                                                        $urlcarat = str_replace(' ', '-', $value['Carat']) . '-carat-';
                                                    } else {
                                                        $urlcarat = '';
                                                    }
                                                    if (isset($value['Color'])) {
                                                        $urlcolor = str_replace(' ', '-', $value['Color']) . '-color-';
                                                    } else {
                                                        $urlcolor = '';
                                                    }
                                                    if (isset($value['Clarity'])) {
                                                        $urlclarity = str_replace(' ', '-', $value['Clarity']) . '-clarity-';
                                                    } else {
                                                        $urlclarity = '';
                                                    }
                                                    if (isset($value['Cut'])) {
                                                        $urlcut = str_replace(' ', '-', $value['Cut']) . '-cut-';
                                                    } else {
                                                        $urlcut = '';
                                                    }
                                                    if (isset($value['Cert'])) {
                                                        $urlcert = str_replace(' ', '-', $value['Cert']) . '-certificate-';
                                                    } else {
                                                        $urlcert = '';
                                                    }
                                                    
                                                    $urlstring = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $value['ID']);
                                                    
                                                    $type = '';
                                                    if (isset($value['Type'])) {
                                                        $type = $value['Type'];
                                                    }
                                                    $diamondviewurl = gemfindDT_getDiamondViewUrl($urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop) . '/' . $type;
                                                    ?>
                                                    <a href="<?php echo esc_url($diamondviewurl); ?>"
                                                        class="view-product"><?php esc_html_e('View', 'gemfind-diamond-tool'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php $site_url = get_site_url(); ?>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($) {
                                        jQuery('[data-toggle="tooltip"]').tooltip();
                                    });

                                    function gemfindDT_removeDummy(className, selectedcheckboxid) {
                                        jQuery('.loading-mask').show();
                                        var elements = document.getElementsByClassName(className);
                                        while (elements.length > 0) {
                                            elements[0].parentNode.removeChild(elements[0]);
                                        }
                                        if (jQuery('.compare-actions td').length == 1) {
                                            var redirectURL = '<?php echo esc_attr($site_url); ?>' + '/diamondlink';
                                            window.location.replace(redirectURL);
                                            //console.log("if");
                                        }
                                        compareItemsarray.pop(selectedcheckboxid);
                                        jQuery.ajax({
                                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                            data: {
                                                action: 'gemfindDT_removeDiamond',
                                                selectedcheckboxid: selectedcheckboxid
                                            },
                                            type: 'POST',
                                            success: function(response) {
                                                if (JSON.parse(localStorage.getItem("compareItems"))) {
                                                    let delLocaldata = JSON.parse(localStorage.getItem("compareItems"));
                                                    let removeLocaldata = delLocaldata.map(delLocaldata => delLocaldata.ID);
                                                    let index = delLocaldata.findIndex(delLocaldata => delLocaldata.ID ==
                                                        selectedcheckboxid);
                                                    if (index > -1) {
                                                        delLocaldata.splice(index, 1);
                                                        localStorage.removeItem('compareItems');
                                                    }
                                                    localStorage.setItem('compareItems', JSON.stringify(delLocaldata));
                                                }
                                                jQuery('.loading-mask').hide();
                                            }
                                        });
                                    }
                                </script>
                                <?php
                            if($navigationapi['show_copyright']=="yes"){                    ?>
                                <div class="copyright-text">
                                    <p>Powered By <a href="http://www.gemfind.com" target="_blank">GemFind</a></p>
                                </div>
                                <?php  } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</section>
<?php else : esc_html_e( 'Please configure the Gemfind Diamond Search App from admin.', 'gemfind-diamond-tool' ); endif;
get_footer(); ?>
