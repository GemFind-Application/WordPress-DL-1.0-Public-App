<?php

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly
$back_link = get_site_url() . '/diamondlink';
$login_link = get_site_url() . '/my-account/';
if ($diamond['diamondData']['diamondType'] == 'fancydiamonds') {
    $back_link = get_site_url() . '/diamondlink/navfancycolored';
}
if ($diamond['diamondData']['diamondType'] == 'labcreated') {
    $back_link = get_site_url() . '/diamondlink/navlabgrown';
}
if ($diamond['diamondData']['videoFileName'] == '0') {
    $hasvideo = 0;
}

$login_link = get_site_url() . '/my-account/';


if (!empty($diamond['diamondData'])) {
    // echo '<pre>'; print_r($diamond); exit; 
    $diamondsoptionapi = get_option('gemfind_diamond_link');

    $enable_price_users = $diamondsoptionapi['enable_price_users'];
    $diamondsoption    = gemfindDT_sendRequest(array('diamondsoptionapi' => $diamondsoptionapi['diamondsoptionapi']));
    $show_Certificate_in_Diamond_Search = $diamondsoption[0][0]->show_Certificate_in_Diamond_Search;
    $site_key = $diamondsoptionapi['site_key'];
    $options = gemfindDT_getOptions();
    //print_r($diamondsoptionapi['site_key']);
    // exit();
?>

    <div class="loading-mask gemfind-loading-mask" id="gemfind-loading-mask" style="display: none;">
        <div class="loader gemfind-loader">
            <p>Please wait...</p>
        </div>
    </div>
    <div class="diamonds-product-view">
        <?php if (!empty($options['top_textarea'])) { ?>
            <div class="diamonds-details" style="display:none;">
                <div class="diamond-bar">
                    <?php echo esc_html($options['top_textarea']); ?>
                </div>
            </div>
        <?php } ?>
        <div class="breadcrumbs">
            <ul class="items">
                <li class="item search">
                    <a href="<?php echo esc_html($back_link); ?>" title="Return To Search Results">Return To
                        Search Results</a>
                </li>
            </ul>
        </div>
        <section class="diamonds-search with-specification diamond-page">
            <div class="d-container">
                <div class="d-row">
                    <div class="diamonds-preview no-padding">
                        <div class="diamond-info">
                            <div class="product-thumb">
                                <?php if (isset($diamond['diamondData']['image1'])) { ?>
                                    <div class="thumg-img diamention">
                                        <a href="javascript:;" onclick="gemfindDT_Imageswitch1(event);">
                                            <img src="<?php echo esc_html($loadingimageurl); ?>" data-src="<?php echo esc_html($diamond['diamondData']['image1']); ?>" style="width:auto; height: 40px;" alt="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" title="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" class="thumbimg" id="thumbimg1" />
                                        </a>
                                    </div>
                                    <input type="hidden" id="thumbvar" name="thumbvar" value="<?php echo $diamond['diamondData']['image1']; ?>" />
                                <?php } ?>
                                <div class="thumg-img main_image">
                                    <a href="javascript:;" id="123" onclick="gemfindDT_Imageswitch2(event);">
                                        <img src="<?php echo esc_html($imageurl); ?>" style="width:auto; height: 40px;" alt="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" title="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" class="thumbimg" id="thumbimg2" />
                                    </a>
                                </div>
                                <?php if ($hasvideo) { ?>
                                    <?php if ($type == 1) { ?>
                                        <div class="thumg-img main_video">
                                            <!--<a href="javascript:;" onclick="gemfindDT_Videorun(event);">-->
                                            <a href="javascript:;" onclick="gemfindDT_Videorun();" data-id="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                                <img style="height: 40px;" src="<?php echo esc_html($tszview); ?>" id="img1iframe" class="video" />
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        <div class="thumg-img main_video">
                                            <!-- <a href="javascript:;" onclick="gemfindDT_Videorun(event);"> -->
                                            <a href="javascript:;" onclick="gemfindDT_Videorun();" data-id="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                                <img style="height: 40px;" src="<?php echo esc_html($tszview); ?>" id="img1iframe" class="iframe">
                                            </a>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="diamond-image">
                                <!--  <div class="diamondvideo"
                            id="diamondvideo">
                            <?php if ($type == 1) { ?>
                                <video width="" height="" autoplay="" loop="" muted="" poster preload="none">
                                    <source src="<?php echo esc_html($diamond['diamondData']['videoFileName']); ?>"
                                        type="video/mp4">
                                    </video>
                                <?php } elseif ($type == 2) { ?>
                                    <iframe src="<?php echo esc_html($diamond['diamondData']['videoFileName']); ?>"
                                        id="iframevideo" scrolling="no"></iframe>
                                    <?php } else {
                                    esc_html_e('No Video', 'gemfind-diamond-tool');
                                } ?>
                                </div> -->
                                <div class="diamondimg" id="diamondimg">
                                    <img src="<?php echo esc_html($imageurl); ?>" id="diamondmainimage" alt="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" title="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>">
                                </div>
                            </div>
                            <h2>
                                <?php if ($diamondsoption[0][0]->show_In_House_Diamonds_First) { ?>
                                    <?php esc_html_e('Stock Number', 'gemfind-diamond-tool'); ?>
                                    <span><?php echo esc_html($diamond['diamondData']['stockNumber']); ?></span>
                                <?php } else {  ?>
                                    <?php esc_html_e('SKU#', 'gemfind-diamond-tool'); ?>
                                    <span><?php echo esc_html($diamond['diamondData']['diamondId']); ?></span>
                                <?php } ?>
                            </h2>
                            <?php if ($show_Certificate_in_Diamond_Search) {         ?>
                                <div class="diamond-report">
                                    <p><b><?php esc_html_e('Diamond Grading Report', 'gemfind-diamond-tool'); ?></b></p>
                                    <div class="view_text">
                                        <a href="javascript:void(0);" onclick="javascript:window.open('<?php echo esc_html($diamond['diamondData']['certificateUrl']); ?>','CERTVIEW','scrollbars=yes,resizable=yes,width=860,height=550')"><?php esc_html_e(' View', 'gemfind-diamond-tool'); ?></a>
                                    </div>
                                </div>
                                <div class="diamond-grade">
                                    <div class="grade-logo">
                                        <img src="<?php echo esc_html($diamond['diamondData']['certificateIconUrl']); ?>" style="width:94px; height: 94px; max-width:inherit;" alt="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>" title="<?php echo esc_html($diamond['diamondData']['mainHeader']); ?>">
                                    </div>
                                    <div class="grade-info">
                                        <p><?php echo esc_html($diamond['diamondData']['subHeader']); ?></p>
                                    </div>
                                </div>
                            <?php }  ?>
                        </div>

                        <?php if ($diamond['diamondData']['internalUselink'] == 'Yes') { ?>
                            <?php $dealerInfoarray = (array) $diamond['diamondData']['retailerInfo']; ?>
                            <div class="internaluse">
                                <?php esc_html_e('Internal use Only:', 'gemfind-diamond-tool'); ?> <a href="javascript:;" id="internaluselink" class="internaluselink" title="<?php esc_html_e('Dealer Info', 'gemfind-diamond-tool'); ?>"><?php esc_html_e('Click Here', 'gemfind-diamond-tool'); ?></a> <?php //_e( 'for Dealer Info.', 'gemfind-diamond-tool' );
                                                                                                                                                                                                                                                                                                            ?>
                                <div class="modal fade auth-section" id="auth-section" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            </div>
                                            <div class="modal-body">
                                                <div class="msg" id="msg"></div>
                                                <form class="internaluseform" id="internaluseform" method="post">
                                                    <input type="password" id="auth_password" name="password" value="" placeholder="<?php esc_html_e('Enter Your Gemfind Password', 'gemfind-diamond-tool'); ?>">
                                                    <input name="shopurl" type="hidden" value="<?php echo esc_attr($shop); ?>">
                                                    <button type="submit" onclick="gemfindDT_internaluselink()" title="Submit" class="preference-btn">
                                                        <span><?php esc_html_e('Submit', 'gemfind-diamond-tool'); ?></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade dealer-detail-section" id="dealer-detail-section" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h1 class="modal-title">Vendor Information</h1>
                                            </div>
                                            <div class="modal-body">
                                                <div class="dealer-info-section" id="dealer-info-section">
                                                    <table>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Name:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerName']) ? esc_html($dealerInfoarray['retailerName']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Company:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerCompany']) ? esc_html($dealerInfoarray['retailerCompany']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer City/State:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerCity']) ? esc_html($dealerInfoarray['retailerCity']) : ''; ?>
                                                                /<?php echo esc_html($dealerInfoarray['retailerState']) ? esc_html($dealerInfoarray['retailerState']) : ''; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Contact No.:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerContactNo']) ? esc_html($dealerInfoarray['retailerContactNo']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Email:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerEmail']) ? esc_html($dealerInfoarray['retailerEmail']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Lot number of the item:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerLotNo']) ? esc_html($dealerInfoarray['retailerLotNo']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Stock number of the item:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerStockNo']) ? esc_html($dealerInfoarray['retailerStockNo']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Wholesale Price:', 'gemfind-diamond-tool'); ?></td>
                                                            <?php
                                                            if ($diamond['diamondData']['currencyFrom'] != 'USD') {
                                                                $wholesalePrice = $diamond['diamondData']['currencyFrom'] . $diamond['diamondData']['currencySymbol'] . number_format($diamond['diamondData']['wholeSalePrice']);
                                                            } else {
                                                                $wholesalePrice = $diamond['diamondData']['currencySymbol'] . $diamond['diamondData']['wholeSalePrice'];
                                                            }
                                                            ?>
                                                            <td><?php echo esc_html($diamond['diamondData']['wholeSalePrice']) ? esc_html($wholesalePrice) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Third Party:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['thirdParty']) ? esc_html($dealerInfoarray['thirdParty']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Diamond Id:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['diamondID']) ? esc_html($dealerInfoarray['diamondID']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Seller Name:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['sellerName']) ? esc_html($dealerInfoarray['sellerName']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Seller Address:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['sellerAddress']) ? esc_html($dealerInfoarray['sellerAddress']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Fax:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerFax']) ? esc_html($dealerInfoarray['retailerFax']) : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php esc_html_e('Dealer Address:', 'gemfind-diamond-tool'); ?></td>
                                                            <td><?php echo esc_html($dealerInfoarray['retailerAddress']) ? esc_html($dealerInfoarray['retailerAddress']) : '-'; ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="diamonds-details no-padding diamond-request-form">
                        <div class="diamond-data" id="diamond-data">
                            <div class="specification-title">
                                <h2><?php echo esc_html($diamond['diamondData']['mainHeader']); ?></h2>
                                <h4 class="diamond_spec_container">
                                    <span class="diamond_spec" onclick="gemfindDT_CallSpecification();">Diamond
                                        Specification</span>
                                    <a href="javascript:;" id="spcfctn" onclick="gemfindDT_CallSpecification();" title="Diamond Specification">
                                        <svg data-toggle="tooltip" data-placement="bottom" title="Specification" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                                            <g>
                                                <g id="New_x5F_Post">
                                                    <g>
                                                        <path d="M545.062,286.875c-15.854,0-28.688,12.852-28.688,28.688v239.062h-459v-459h239.062
                                           c15.854,0,28.688-12.852,28.688-28.688S312.292,38.25,296.438,38.25H38.25C17.136,38.25,0,55.367,0,76.5v497.25
                                           C0,594.883,17.136,612,38.25,612H535.5c21.114,0,38.25-17.117,38.25-38.25V315.562
                                           C573.75,299.727,560.917,286.875,545.062,286.875z M605.325,88.95L523.03,6.655C518.556,2.18,512.684,0,506.812,0
                                           s-11.743,2.18-16.218,6.675l-318.47,318.45v114.75h114.75l318.45-318.45c4.494-4.495,6.675-10.366,6.675-16.237
                                           C612,99.297,609.819,93.445,605.325,88.95z M267.75,382.5H229.5v-38.25L506.812,66.938l38.25,38.25L267.75,382.5z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </a>
                                </h4>
                            </div>
                            <div class="diamond-content-data" id="diamond-content-data">
                                <?php if ($show_Certificate_in_Diamond_Search) {         ?>
                                    <div class="diamond-desc">
                                        <p><?php echo esc_html($diamond['diamondData']['subHeader']); ?></p>
                                    </div>
                                <?php  } ?>
                                <?php if (!empty($options['detail_textarea'])) { ?>
                                    <div class="diamond-bar-detail">
                                        <?php echo esc_html($options['detail_textarea']); ?>
                                    </div>
                                <?php } ?>
                                <div class="form-field diamonds-info">
                                    <div class="intro-field">
                                        <ul>
                                             <li>
                                                <strong><?php esc_html_e('Cut:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($diamond['diamondData']['cut'] != '') {
                                                        echo esc_html($diamond['diamondData']['cut']);
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?></p>
                                            </li>
                                            <li>
                                                <strong><?php esc_html_e('Polish:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($diamond['diamondData']['polish'] != '') {
                                                        echo esc_html($diamond['diamondData']['polish']);
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?></p>
                                            </li>
                                            <li>
                                                <strong><?php esc_html_e('Symmetry:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($diamond['diamondData']['symmetry'] != '') {
                                                        echo esc_html($diamond['diamondData']['symmetry']);
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?></p>
                                            </li>
                                           
                                        </ul>
                                        <ul>
                                            <?php
                                            if ($diamond['diamondData']['fancyColorMainBody']) {
                                                $color_to_display = $diamond['diamondData']['fancyColorIntensity'] . ' ' . $diamond['diamondData']['fancyColorMainBody'];
                                            } else {
                                                $color_to_display = $diamond['diamondData']['color'];
                                            }
                                            ?>
                                            <li>
                                                <strong><?php esc_html_e('Color:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($color_to_display != '') {
                                                        echo esc_html($color_to_display);
                                                        //echo $diamond['diamondData']['color'];
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?>
                                                </p>
                                            </li>
                                            <li>
                                                <strong><?php esc_html_e('Clarity:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($diamond['diamondData']['clarity'] != '') {
                                                        echo esc_html($diamond['diamondData']['clarity']);
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?></p>
                                            </li>
                                            <li>
                                                <strong><?php esc_html_e('Fluorescence:', 'gemfind-diamond-tool'); ?></strong>
                                                <p><?php if ($diamond['diamondData']['fluorescence'] != '') {
                                                        echo esc_html($diamond['diamondData']['fluorescence']);
                                                    } else {
                                                        esc_html_e('NA', 'gemfind-diamond-tool');
                                                    } ?></p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-controler">
                                        <?php
                                        $options = gemfindDT_getOptions();

                                        ?>
                                        <ul>
                                            <?php if ($options['enable_hint'] == 'true') : ?>
                                                <li><a href="javascript:;" class="showForm" onclick="gemfindDT_CallShowform(event);" data-target="drop-hint-main"><?php esc_html_e('Drop A Hint', 'gemfind-diamond-tool'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($options['enable_more_info'] == 'true') : ?>
                                                <li><a href="javascript:;" class="showForm" onclick="gemfindDT_CallShowform(event);" data-target="req-info-main"><?php esc_html_e('Request More Info', 'gemfind-diamond-tool'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($options['enable_email_friend'] == 'true') : ?>
                                                <li><a href="javascript:;" class="showForm" onclick="gemfindDT_CallShowform(event);" data-target="email-friend-main"><?php esc_html_e('E-Mail A Friend', 'gemfind-diamond-tool'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($options['enable_print'] == 'true') : ?>
                                                <li><a href="javascript:;" data="<?php echo esc_html($printIcon); ?>" class="prinddia" id="prinddia"><?php esc_html_e('Print Details', 'gemfind-diamond-tool'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($options['enable_schedule_viewing'] == 'true') : ?>
                                                <li><a href="javascript:;" class="showForm" onclick="gemfindDT_CallShowform(event);" data-target="schedule-view-main"><?php esc_html_e('Schedule Viewing', 'gemfind-diamond-tool'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="diamond-action">
                                        <?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
                                            <span><?php
                                                    if ($diamond['diamondData']['showPrice'] == true) {
                                                        if ($diamondsoptionapi['price_row_format'] == 'left') {
                                                            if ($diamond['diamondData']['currencyFrom'] == 'USD') {
                                                                echo "$" . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                            } else {
                                                                echo esc_html(number_format($diamond['diamondData']['fltPrice'])) . ' ' . $diamond['diamondData']['currencySymbol'] . ' ' . $diamond['diamondData']['currencyFrom'];
                                                            }
                                                        } else {
                                                            if ($diamond['diamondData']['currencyFrom'] == 'USD') {
                                                                echo "$" . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                            } else {
                                                                echo esc_html($diamond['diamondData']['currencyFrom']) . ' ' . esc_html($diamond['diamondData']['currencySymbol']) . ' ' . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                            }
                                                        }
                                                    } else {
                                                        echo esc_html('Call For Price');
                                                    }
                                                    ?></span>
                                        <?php  } else { ?>
                                            <p> Please <a href="<?php echo esc_html($login_link); ?>" style="color:#0072ff" ;> Login </a> To
                                                View Price </p>
                                        <?php } ?>
                                        <?php
                                        if (isset($diamond['diamondData']['shape'])) {
                                            $urlshape = str_replace(' ', '-', $diamond['diamondData']['shape']) . '-shape-';
                                        } else {
                                            $urlshape = '';
                                        }
                                        if (isset($diamond['diamondData']['caratWeight'])) {
                                            $urlcarat = str_replace(' ', '-', $diamond['diamondData']['caratWeight']) . '-carat-';
                                        } else {
                                            $urlcarat = '';
                                        }
                                        if (isset($diamond['diamondData']['color'])) {
                                            $urlcolor = str_replace(' ', '-', $diamond['diamondData']['color']) . '-color-';
                                        } else {
                                            $urlcolor = '';
                                        }
                                        if (isset($diamond['diamondData']['clarity'])) {
                                            $urlclarity = str_replace(' ', '-', $diamond['diamondData']['clarity']) . '-clarity-';
                                        } else {
                                            $urlclarity = '';
                                        }
                                        if (isset($diamond['diamondData']['cut'])) {
                                            $urlcut = str_replace(' ', '-', $diamond['diamondData']['cut']) . '-cut-';
                                        } else {
                                            $urlcut = '';
                                        }
                                        if (isset($diamond['diamondData']['certificate'])) {
                                            $urlcert = str_replace(' ', '-', $diamond['diamondData']['certificate']) . '-certificate-';
                                        } else {
                                            $urlcert = '';
                                        }
                                        $urlstring = strtolower($urlshape . $urlcarat . $urlcolor . $urlclarity . $urlcut . $urlcert . 'sku-' . $diamond['diamondData']['diamondId']);
                                        $diamondviewurl = '';
                                        $diamondviewurl = gemfindDT_getDiamondViewUrl($urlstring, $type, get_site_url() . '/diamondlink', $pathprefixshop) . '/' . $diamond['diamondData']['diamondType'];
                                        ?>

                                        <?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
                                            <form action="" method="post" id="product_addtocart_form">
                                                <div class="box-tocart">
                                                    <?php
                                                    if (isset($product_id) && !empty($product_id)) {
                                                        update_post_meta($post_id, '_regular_price', $diamond['diamondData']['fltPrice']);
                                                        update_post_meta($post_id, 'FltPrice', $diamond['diamondData']['fltPrice']);
                                                        update_post_meta($post_id, '_price', $diamond['diamondData']['fltPrice']);
                                                        if ($diamond['diamondData']['showPrice'] == true) {
                                                            if ($diamond['diamondData']['dsEcommerce'] != false) {
                                                    ?>
                                                                <button type="submit" title="Add to Cart" class="addtocart tocart" onclick='gemfindDT_redirectOnCart(event, <?php echo esc_html($post_id); ?>);' id="product-addtocart-button"><?php esc_html_e('Add to Cart', 'gemfind-diamond-tool'); ?></button>
                                                            <?php
                                                            }
                                                        }
                                                    } else {
                                                        if ($diamond['diamondData']['showPrice'] == true) {
                                                            if ($diamond['diamondData']['dsEcommerce'] != false) {
                                                            ?>
                                                                <button type="submit" title="Add to Cart" class="addtocart tocart" onclick='gemfindDT_showLoader(event, <?php echo esc_html(wp_json_encode($diamond)); ?>);' id="product-addtocart-button"><?php esc_html_e('Add to Cart', 'gemfind-diamond-tool'); ?></button>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </form>
                                        <?php
                                        }

                                        $diamondsoption[0][0]->show_Pinterest_Share = 1;
                                        $diamondsoption[0][0]->show_Twitter_Share = 1;
                                        $diamondsoption[0][0]->show_Facebook_Share = 1;
                                        $diamondsoption[0][0]->show_Facebook_Like = 1;
                                        $imageurl = $diamond['diamondData']['image1'];    ?>
                                        <ul class="list-inline social-share">
                                            <?php if ($diamondsoption[0][0]->show_Pinterest_Share) { ?>
                                                <li class="save_pinterest">
                                                    <a class="save_pint" data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url=<?php echo esc_url($diamondviewurl); ?>&media=<?php echo esc_url($imageurl); ?>&description=<?php echo esc_attr($diamond['diamondData']['subHeader']); ?>" data-pin-height="28">
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($diamondsoption[0][0]->show_Twitter_Share) { ?>
                                                <li class="share_tweet">
                                                    <a href="https://twitter.com/share?ref_src=<?php echo esc_html($diamondviewurl); ?>" class="twitter-share-button" data-show-count="false">Tweet</a>
                                                    <script async src="<?php echo esc_url(plugins_url('/assets/js/widgets.js', __FILE__)); ?>" charset="utf-8"></script>
                                                </li>
                                            <?php } ?>
                                            <?php if ($diamondsoption[0][0]->show_Facebook_Share) { ?>
                                                <li class="share_fb">
                                                    <div class="fb-share-button" data-href="<?php echo esc_html($diamondviewurl); ?>" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_html($diamondviewurl); ?>" class="fb-xfbml-parse-ignore">Share</a></div>
                                                </li>
                                            <?php } ?>
                                            <?php if ($diamondsoption[0][0]->show_Facebook_Like) { ?>
                                                <li class="like_fb">
                                                    <div class="fb-like" data-href="<?php echo esc_html($diamondviewurl); ?>" data-width="" data-layout="button_count" data-share="false" data-action="like" data-size="small"></div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="diamond-forms">
                                <?php if ($options['enable_hint'] == true) : ?>
                                    <div class="form-main no-padding diamond-request-form" id="drop-hint-main">
                                        <div class="requested-form">
                                            <h2><?php esc_html_e('Drop A Hint', 'gemfind-diamond-tool'); ?></h2>
                                            <p><?php esc_html_e('Because you deserve this.', 'gemfind-diamond-tool'); ?></p>
                                        </div>
                                        <div class="note" style="display: none;"></div>
                                        <form method="post" enctype="multipart/form-data" data-hasrequired="<?php esc_html_e('* Required Fields', 'gemfind-diamond-tool'); ?>" data-mage-init='{"validation":{}}' class="form-drop-hint" id="form-drop-hint">
                                            <input name="diamondtype" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondType']); ?>">
                                            <input name="diamondurl" type="hidden" value="<?php echo esc_html($diamondviewurl); ?>">
                                            <input name="diamondid" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                            <input name="shopurl" type="hidden" value="<?php echo esc_html($shop); ?>">
                                            <div class="form-field">
                                                <label>
                                                    <input name="name" id="drophint_name" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                                    <span><?php esc_html_e('Your Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="email" id="drophint_email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" type="email" class="" data-validate="{required:true, 'validate-email':true}" placeholder=" ">
                                                    <span><?php esc_html_e('Your E-mail', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="recipient_name" id="drophint_rec_name" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                                    <span><?php esc_html_e('Hint Recipient\'s Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="recipient_email" id="drophint_rec_email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" type="email" class="" data-validate="{required:true, 'validate-email':true}" placeholder=" ">
                                                    <span><?php esc_html_e('Hint Recipient\'s E-mail', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="gift_reason" id="gift_reason" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" type="text" class="" data-validate="{required:true}" placeholder=" ">
                                                    <span><?php esc_html_e('Reason For This Gift', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <textarea name="hint_message" rows="2" cols="20" id="drophint_message" class="" data-validate="{required:true}" placeholder="Add A Personal Message Here ..."></textarea>
                                                </label>
                                                <label>
                                                    <div class="has-datepicker--icon">
                                                        <input name="gift_deadline" id="gift_deadline" autocomplete="false" readonly title="Gift Deadline" value="" type="text" placeholder="Gift Deadline">
                                                    </div>
                                                </label>
                                                <div class="prefrence-action">
                                                    <div class=" prefrence-action action">
                                                        <button type="button" data-target="drop-hint-main" onclick="gemfindDT_Closeform(event);" class="cancel preference-btn btn-cencel">
                                                            <span><?php esc_html_e('Cancel', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                        <button type="submit" onclick="gemfindDT_formSubmit(event,myajax.ajaxurl,'form-drop-hint')" title="Drop Hint" class="preference-btn">
                                                            <span><?php esc_html_e('Drop Hint', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if ($options['enable_email_friend'] == true) : ?>
                                    <div class="form-main no-padding diamond-request-form" id="email-friend-main">
                                        <div class="requested-form">
                                            <h2><?php esc_html_e('E-Mail A Friend', 'gemfind-diamond-tool'); ?></h2>
                                        </div>
                                        <div class="note" style="display: none;"></div>
                                        <form method="post" enctype="multipart/form-data" data-hasrequired="<?php esc_html_e('* Required Fields', 'gemfind-diamond-tool'); ?>" data-mage-init='{"validation":{}}' class="form-email-friend" id="form-email-friend">
                                            <input name="diamondtype" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondType']); ?>">
                                            <input name="diamondurl" type="hidden" value="<?php echo esc_html($diamondviewurl); ?>">
                                            <input name="diamondid" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                            <input name="shopurl" type="hidden" value="<?php echo esc_html($shop); ?>">
                                            <div class="form-field">
                                                <label>
                                                    <input id="email_frnd_name" name="name" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" placeholder="" type="text" class="">
                                                    <span for="email_frnd_name"><?php esc_html_e('Your Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="email" type="email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" placeholder="" id="email_frnd_email" class="">
                                                    <span><?php esc_html_e('Your E-mail', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="friend_name" type="text" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" placeholder="" id="email_frnd_fname" class="">
                                                    <span><?php esc_html_e('Your Friend\'s Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="friend_email" type="email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" placeholder="" id="email_frnd_femail" class="">
                                                    <span><?php esc_html_e('Your Friend\'s E-mail', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <textarea name="message" rows="2" placeholder="Add A Personal Message Here ..." cols="20" id="email_frnd_message" class=""></textarea>
                                                </label>
                                                <div class="prefrence-action">
                                                    <div class=" prefrence-action action">
                                                        <button type="button" data-target="email-friend-main" onclick="gemfindDT_Closeform(event);" class="cancel preference-btn btn-cencel">
                                                            <span><?php esc_html_e('Cancel', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                        <button type="submit" onclick="gemfindDT_formSubmit(event,myajax.ajaxurl,'form-email-friend')" title="Send To Friend" class="preference-btn">
                                                            <span><?php esc_html_e('Send To Friend', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if ($options['enable_more_info'] == true) : ?>
                                    <div class="form-main no-padding diamond-request-form" id="req-info-main">
                                        <div class="requested-form">
                                            <h2><?php esc_html_e('Request More Information', 'gemfind-diamond-tool'); ?></h2>
                                            <p><?php esc_html_e('Our specialists will contact you.', 'gemfind-diamond-tool'); ?></p>
                                        </div>
                                        <div class="note" style="display: none;"></div>
                                        <form method="post" enctype="multipart/form-data" data-hasrequired="<?php esc_html_e('* Required Fields', 'gemfind-diamond-tool'); ?>" data-mage-init='{"validation":{}}' class="form-request-info" id="form-request-info">
                                            <input name="diamondtype" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondType']); ?>">
                                            <input name="diamondurl" type="hidden" value="<?php echo esc_html($diamondviewurl); ?>">
                                            <input name="diamondid" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                            <input name="shopurl" type="hidden" value="<?php echo esc_html($shop); ?>">
                                            <div class="form-field">
                                                <label>
                                                    <input name="name" type="text" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="reqinfo_name" placeholder="" class="">
                                                    <span><?php esc_html_e('Your Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="email" type="email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="reqinfo_email" placeholder="" class="">
                                                    <span><?php esc_html_e('Your E-mail Address', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="phone" type="text" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="reqinfo_phone" placeholder="" class="">
                                                    <span><?php esc_html_e('Your Phone Number', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <textarea name="hint_message" rows="2" cols="20" placeholder="Add A Personal Message Here ..." id="reqinfo_message" class=""></textarea>
                                                </label>
                                                <div class="prefrence-area">
                                                    <p><?php esc_html_e('Contact Preference:', 'gemfind-diamond-tool'); ?></p>
                                                    <ul class="pref_container">
                                                        <li>
                                                            <input type="radio" class="radio required-entry" name="contact_pref" value="By Email">
                                                            <label><?php esc_html_e('By Email', 'gemfind-diamond-tool'); ?></label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" class="radio required-entry" name="contact_pref" value="By Phone">
                                                            <label><?php esc_html_e('By Phone', 'gemfind-diamond-tool'); ?></label>
                                                        </li>
                                                    </ul>
                                                    <div class="prefrence-action">
                                                        <div class=" prefrence-action action">
                                                            <button type="button" data-target="req-info-main" onclick="gemfindDT_Closeform(event);" class="cancel preference-btn btn-cencel">
                                                                <span><?php esc_html_e('Cancel', 'gemfind-diamond-tool'); ?></span>
                                                            </button>
                                                            <button type="submit" onclick="gemfindDT_formSubmit(event,myajax.ajaxurl,'form-request-info')" title="Request" class="preference-btn">
                                                                <span><?php esc_html_e('Request', 'gemfind-diamond-tool'); ?></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if ($options['enable_schedule_viewing'] == true) : ?>
                                    <div class="form-main no-padding diamond-request-form" id="schedule-view-main">
                                        <div class="requested-form">
                                            <h2><?php esc_html_e('Schedule A Viewing', 'gemfind-diamond-tool'); ?></h2>
                                            <p><?php esc_html_e('See This Item & More In Our Store', 'gemfind-diamond-tool'); ?></p>
                                        </div>
                                        <div class="note" style="display: none;"></div>
                                        <form method="post" enctype="multipart/form-data" data-hasrequired="<?php esc_html_e('* Required Fields', 'gemfind-diamond-tool'); ?>" data-mage-init='{"validation":{}}' class="form-schedule-view" id="form-schedule-view">
                                            <input name="diamondtype" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondType']); ?>">
                                            <input name="diamondurl" type="hidden" value="<?php echo esc_html($diamondviewurl); ?>">
                                            <input name="diamondid" type="hidden" value="<?php echo esc_html($diamond['diamondData']['diamondId']); ?>">
                                            <input name="shopurl" type="hidden" value="<?php echo esc_html($shop); ?>">
                                            <div class="form-field">
                                                <label>
                                                    <input name="name" type="text" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="schview_name" placeholder="" class="">
                                                    <span><?php esc_html_e('Your Name', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="email" type="email" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="schview_email" placeholder="" class="">
                                                    <span><?php esc_html_e('Your E-mail Address', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <input name="phone" type="text" onfocus="gemfindDT_focusFunction(this)" onfocusout="gemfindDT_focusoutFunction(this)" id="schview_phone" placeholder="" class="">
                                                    <span><?php esc_html_e('Your Phone Number', 'gemfind-diamond-tool'); ?></span>
                                                </label>
                                                <label>
                                                    <textarea name="hint_message" rows="2" cols="20" placeholder="Add A Personal Message Here ..." id="schview_message" class=""></textarea>
                                                </label>
                                                <label>
                                                    <?php $retailerInfo = (array) $diamond['diamondData']['retailerInfo'];
                                                    $addressList = (array) $retailerInfo['addressList']; ?>
                                                    <select name="location" placeholder="" id="schview_loc">
                                                        <option value=""><?php esc_html_e('--Location--', 'gemfind-diamond-tool'); ?></option>
                                                        <?php foreach ($addressList as $value) {
                                                            $value = (array) $value; ?>
                                                            <option data-locationid="<?php echo esc_html($value['locationID']); ?>" value="<?php echo esc_html($value['locationName']); ?>"><?php echo esc_html($value['locationName']); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </label>
                                                <label>
                                                    <div class="has-datepicker--icon">
                                                        <input name="avail_date" id="avail_date" readonly autocomplete="false" placeholder="When are you available?" title="When are you available?" value="" type="text" />
                                                    </div>
                                                </label>
                                                <?php
                                                /*echo "<pre>";
            print_r((array) $retailerInfo['timingList'][0]);*/
                                                $timingList = (array)$retailerInfo['timingList'];

                                                if (empty($timingList)) {
                                                ?>
                                                    <label class="timing_not_avail" style="display:none;">Slots not available on
                                                        selected date</label>
                                                    <?php
                                                } else {
                                                    foreach ($timingList as $timing) {
                                                        $timingDays[0] = array(
                                                            "sundayStart" => $timing->sundayStart,
                                                            "sundayEnd" => $timing->sundayEnd
                                                        );
                                                        $timingDays[1] = array(
                                                            "mondayStart" => $timing->mondayStart,
                                                            "mondayEnd" => $timing->mondayEnd
                                                        );
                                                        $timingDays[2] = array(
                                                            "tuesdayStart" => $timing->tuesdayStart,
                                                            "tuesdayEnd" => $timing->tuesdayEnd
                                                        );
                                                        $timingDays[3] = array(
                                                            "wednesdayStart" => $timing->wednesdayStart,
                                                            "wednesdayEnd" => $timing->wednesdayEnd
                                                        );
                                                        $timingDays[4] = array(
                                                            "thursdayStart" => $timing->thursdayStart,
                                                            "thursdayEnd" => $timing->thursdayEnd
                                                        );
                                                        $timingDays[5] = array(
                                                            "fridayStart" => $timing->fridayStart,
                                                            "fridayEnd" => $timing->fridayEnd
                                                        );
                                                        $timingDays[6] = array(
                                                            "saturdayStart" => $timing->saturdayStart,
                                                            "saturdayEnd" => $timing->saturdayEnd
                                                        );
                                                        if ($timing->storeClosedSun == "Yes") {
                                                            $dayStatusArr[0] = 0;
                                                        }
                                                        if ($timing->storeClosedMon == "Yes") {
                                                            $dayStatusArr[1] = 1;
                                                        }
                                                        if ($timing->storeClosedTue == "Yes") {
                                                            $dayStatusArr[2] = 2;
                                                        }
                                                        if ($timing->storeClosedWed == "Yes") {
                                                            $dayStatusArr[3] = 3;
                                                        }
                                                        if ($timing->storeClosedThu == "Yes") {
                                                            $dayStatusArr[4] = 4;
                                                        }
                                                        if ($timing->storeClosedFri == "Yes") {
                                                            $dayStatusArr[5] = 5;
                                                        }
                                                        if ($timing->storeClosedSat == "Yes") {
                                                            $dayStatusArr[6] = 6;
                                                        }
                                                        /*echo "<pre>";
                    print_r($dayStatusArr);*/

                                                        foreach ($dayStatusArr as $key => $value) {  ?>
                                                            <span style="display:none;" class="day_status_arr"><?php echo esc_html($value); ?></span>
                                                        <?php
                                                        }
                                                        ?>
                                                        <span class="timing_days" data-location="<?php echo esc_html($timing->locationID); ?>" style="display:none;"><?php echo esc_html(wp_json_encode($timingDays)); ?></span>
                                                    <?php  }  ?>
                                                    <label>
                                                        <select id="appnt_time" class="" placeholder="" name="appnt_time" style="display:none;"></select>
                                                    </label>
                                                <?php } ?>
                                                <div class="prefrence-action">
                                                    <div class=" prefrence-action action">
                                                        <button type="button" data-target="schedule-view-main" onclick="gemfindDT_Closeform(event);" class="cancel preference-btn btn-cencel">
                                                            <span><?php esc_html_e('Cancel', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                        <button type="submit" onclick="gemfindDT_formSubmit(event,myajax.ajaxurl,'form-schedule-view')" title="Request" class="preference-btn">
                                                            <span><?php esc_html_e('Request', 'gemfind-diamond-tool'); ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="diamond-specification cls-for-hide" id="diamond-specification">
                            <div class="specification-info">
                                <div class="specification-title">
                                    <h2><?php esc_html_e('Diamond Details', 'gemfind-diamond-tool'); ?></h2>
                                    <h4>
                                        <a href="javascript:;" id="dmnddtl" onclick="gemfindDT_CallDiamondDetail();">
                                            <svg version="1.1" data-placement="bottom" data-toggle="tooltip" title="Close" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 52 52" width="20px" height="20px" style="enable-background:new 0 0 52 52;display: inline;vertical-align: text-bottom; fill:#828282 !important;" xml:space="preserve">
                                                <g>
                                                    <path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M26,50C12.767,50,2,39.233,2,26
                      S12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z" />
                                                    <path d="M35.707,16.293c-0.391-0.391-1.023-0.391-1.414,0L26,24.586l-8.293-8.293c-0.391-0.391-1.023-0.391-1.414,0
                      s-0.391,1.023,0,1.414L24.586,26l-8.293,8.293c-0.391,0.391-0.391,1.023,0,1.414C16.488,35.902,16.744,36,17,36
                      s0.512-0.098,0.707-0.293L26,27.414l8.293,8.293C34.488,35.902,34.744,36,35,36s0.512-0.098,0.707-0.293
                      c0.391-0.391,0.391-1.023,0-1.414L27.414,26l8.293-8.293C36.098,17.316,36.098,16.684,35.707,16.293z" />
                                                </g>
                                            </svg>
                                        </a>
                                    </h4>
                                </div>
                                <ul>
                                    <?php if (isset($diamond['diamondData']['diamondId'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Stock Number', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['diamondId']); ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['fltPrice'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Price', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p>
                                                    <?php if ($enable_price_users != "yes" || is_user_logged_in()) { ?>
                                                        <?php if ($diamond['diamondData']['showPrice'] == true) { ?>
                                                        <?php
                                                            if ($diamondsoptionapi['price_row_format'] == 'left') {

                                                                if ($diamond['diamondData']['currencyFrom'] == 'USD') {

                                                                    echo "$" . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                                } else {

                                                                    echo esc_html(number_format($diamond['diamondData']['fltPrice'])) . ' ' . esc_html($diamond['diamondData']['currencySymbol']) . ' ' . esc_html($diamond['diamondData']['currencyFrom']);
                                                                }
                                                            } else {

                                                                if ($diamond['diamondData']['currencyFrom'] == 'USD') {

                                                                    echo "$" . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                                } else {

                                                                    echo esc_html($diamond['diamondData']['currencyFrom']) . ' ' . esc_html($diamond['diamondData']['currencySymbol']) . ' ' . esc_html(number_format($diamond['diamondData']['fltPrice']));
                                                                }
                                                            }
                                                        } else {
                                                            echo esc_html('Call For Price');
                                                        } ?>
                                                    <?php } else { ?>
                                                        <a href="<?php echo esc_html($login_link); ?>" style="color:#0072ff" ;> Login </a>
                                                    <?php }  ?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['caratWeight'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Carat Weight', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['caratWeight']); ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['cut'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Cut', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['cut']); ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($color_to_display)) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Color', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($color_to_display); ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['clarity'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Clarity', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['clarity']); ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['depth'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Depth %', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['depth']) ? esc_html($diamond['diamondData']['depth']) . '%' : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['table'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Table %', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['table']) ? esc_html($diamond['diamondData']['table']) . '%' : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['polish'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Polish', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['polish']) ? esc_html($diamond['diamondData']['polish']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['symmetry'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Symmetry', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['symmetry']) ? esc_html($diamond['diamondData']['symmetry']) : '-'; ?></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Origin', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['origin']) ? esc_html($diamond['diamondData']['origin']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['gridle'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Girdle', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['gridle']) ? esc_html($diamond['diamondData']['gridle']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['culet'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Culet', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['culet']) ? esc_html($diamond['diamondData']['culet']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['fluorescence'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Fluorescence', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['fluorescence']) ? esc_html($diamond['diamondData']['fluorescence']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($diamond['diamondData']['measurement'])) { ?>
                                        <li>
                                            <div class="diamonds-details-title">
                                                <p><?php esc_html_e('Measurement', 'gemfind-diamond-tool'); ?></p>
                                            </div>
                                            <div class="diamonds-info">
                                                <p><?php echo esc_html($diamond['diamondData']['measurement']) ? esc_html($diamond['diamondData']['measurement']) : '-'; ?></p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-form">
                <form id="search-diamonds-form" method="post" action="<?php echo esc_html(get_site_url()) . '/' . 'diamondlink/diamondsearch'; ?>">
                    <input name="submitby" id="submitby" type="hidden" value="" />
                    <input name="baseurl" id="baseurl" type="hidden" value="<?php echo esc_html(get_site_url()); ?>" />
                    <input name="shopurl" id="shopurl" type="hidden" value="<?php echo esc_html($shop); ?>" />
                    <input name="path_prefix_shop" id="path_shop_url" type="hidden" value="<?php echo esc_html($pathprefixshop); ?>" />
                    <input name="viewmode" id="viewmode" type="hidden" value="list" />
                    <input type="hidden" name="orderby" id="orderby" value="FltPrice" />
                    <input type="hidden" name="direction" id="direction" value="ASC" />
                    <input type="hidden" name="currentpage" id="currentpage" value="1" />
                    <input type="hidden" name="diamond_shape[]" id="diamond_shape" value="<?php echo esc_html($diamond['diamondData']['shape']); ?>" />
                    <input type="hidden" name="diamond_certificates[]" id="diamond_certificates" value="<?php echo esc_html($diamond['diamondData']['certificate']); ?>" />
                    <?php if ($diamond['diamondData']['fancyColorMainBody']) { ?>
                        <input type="hidden" name="filtermode" id="filtermode" value="navfancycolored">
                    <?php } else if ($diamond['diamondData']['certificate'] == 'GCAL') { ?>
                        <input type="hidden" name="filtermode" id="filtermode" value="navlabgrown">
                    <?php } else { ?>
                        <input type="hidden" name="filtermode" id="filtermode" value="navstandard">
                    <?php } ?>
                    <input type="hidden" name="diamond_carats[from]" value="<?php echo esc_html($diamond['diamondData']['caratWeight']); ?>" />
                    <input type="hidden" name="diamond_carats[to]" value="<?php echo esc_html($diamond['diamondData']['caratWeight']); ?>" />
                    <input type="hidden" name="price[from]" value="0" />
                    <input type="hidden" name="price[to]" value="100000" />
                    <input type="hidden" name="diamond_table[from]" value="0" />
                    <input type="hidden" name="diamond_table[to]" value="100" />
                    <input type="hidden" name="diamond_depth[from]" value="0" />
                    <input type="hidden" name="diamond_depth[to]" value="100" />
                    <input name="itemperpage" id="itemperpage" type="hidden" value="<?php echo esc_html(gemfindDT_getResultsPerPage()); ?>" />

                    <input type="hidden" name="gemfind_diamond_origin" value="" />
                    <input type="submit" name="Submit" id="submit" style="visibility: hidden;">
                </form>
            </div>
            <div class="result filter-advanced">
                <div style="text-align: center; margin: 50px; font-size: 16px;">
                    <b><?php esc_html_e('Loading Similar Diamonds...', 'gemfind-diamond-tool'); ?></b>
                </div>
            </div>
            <div class="print-diamond-details" style="display: none;">
                <div class="dimond_data"></div>
            </div>
            <div id="printMessageBox">Please wait while we create your document</div>
        </section>
    </div>
<?php } else {  ?>
    <div class="modal fade no-info-section" id="no-info-section" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="msg" id="msg">The diamond that was sent to you is unfortunately no longer
                        available.</div>
                    <a href="<?php echo esc_html($back_link); ?>" class="button">Diamonds</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery('#no-info-section').modal({
            backdrop: 'static',
            keyboard: false
        });

        jQuery("#no-info-section .close").click(function() {
            window.location.href = '<?php echo esc_url($back_link); ?>';
        });
    </script>
<?php  } ?>
<!-- <script src="<?php //echo esc_url(plugins_url('/assets/js/jquery.PrintArea.js', __FILE__)); 
                    ?>" nomodule></script> -->


<script>
    jQuery(window).bind("load", function() {
        jQuery("#prinddia").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode
            };
            var dimond_id = <?php echo json_encode(esc_js($diamond['diamondData']['diamondId'])); ?>;
            var diamondtype = <?php echo json_encode(esc_js($diamond['diamondData']['diamondType'])); ?>;
            var shop = '<?php echo $shop; ?>';
            jQuery.ajax({
                url: myajax.ajaxurl,
                data: {
                    action: 'gemfindDT_printdiamond',
                    diamondid: dimond_id,
                    shop: shop
                },
                type: 'POST',
                dataType: 'html',
                cache: true,
                beforeSend: function(settings) {
                    jQuery('#printMessageBox').show();
                },
                success: function(response) {
                    //console.log(response);
                    jQuery(".dimond_data").html(response);


                    setTimeout(function() {
                        jQuery('#printMessageBox').hide();
                        jQuery(".dimond_data").printArea(options);
                    }, 5000);

                },
                error: function(xhr, status, errorThrown) {
                    console.log('Error happens. Try again.');
                    console.log(errorThrown);
                }
            });
        });
    });

    function gemfindDT_showLoader(e, diamondData) {
        e.preventDefault();
        document.getElementById("gemfind-loading-mask").style.display = "block";
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: myajax.ajaxurl,
            data: {
                action: "gemfindDT_add_product_to_cart",
                diamond_name: "<?php echo esc_html(sanitize_url($_SERVER['REQUEST_URI'])); ?>",
                diamond: JSON.stringify(diamondData)
            },
            success: function(response) {
                document.getElementById("gemfind-loading-mask").style.display = "none";
                window.location.href = '<?php echo esc_url(get_site_url()); ?>' + '/cart/' + '?add-to-cart=' + response;
            }
        });
    }
</script>
<script type="text/javascript">
    var src = jQuery('div.diamention img').attr("data-src");
    gemfindDT_imageExists1(src, function(exists) {
        if (exists) {
            jQuery('div.diamention img').attr('src', src);
        } else {
            jQuery('div.diamention img').attr('src', '<?php echo esc_url($noimageurl); ?>');
        }
    });

    function gemfindDT_imageExists1(url, callback) {
        var img = new Image();
        img.onload = function() {
            callback(true);
        };
        img.onerror = function() {
            callback(false);
        };
        img.src = url;
    }

    jQuery("#internaluselink").on('click', function() {
        jQuery('#msg').html('');
        jQuery('#internaluseform input#auth_password').val('');
        jQuery("#auth-section").modal("show");
    });


    function gemfindDT_internaluselink() {
        console.log('click here');
        jQuery('#internaluseform').validate({
            rules: {
                password: {
                    required: true
                }
            },
            submitHandler: function(form) {
                jQuery.ajax({
                    url: myajax.ajaxurl,
                    data: {
                        'action': 'gemfindDT_authenticateDealer',
                        'form_data': jQuery('#internaluseform').serializeArray()
                    },
                    type: 'POST',
                    dataType: 'json',
                    cache: true,
                    beforeSend: function(settings) {
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'block');
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.output.status == 1) {
                            console.log('Coming here');
                            jQuery('#msg').html('<span class="success">' + response.output.msg +
                                '</span>');
                            jQuery("#auth-section").modal("hide");
                            jQuery("#dealer-detail-section").modal("show");
                        } else {
                            jQuery('#msg').html('<span class="error">' + response.output.msg +
                                '</span>').show();
                            jQuery('#internaluseform input#auth_password').val('');
                            jQuery('#msg').fadeOut(5000);
                        }
                        jQuery('.loading-mask.gemfind-loading-mask').css('display', 'none');
                    }
                });
            }
        });


    }

    jQuery(document).ready(function() {
        jQuery('#diamondmainimage').attr("src", jQuery('#thumbvar').val());
    });

    jQuery(window).on('beforeunload', function() {
        jQuery("video").css('visibility', 'hidden');
    });

    jQuery(window).bind("load", function() {
        <?php
        $diamondajax = $diamond;
        $removeaddressList = $diamondajax['diamondData']['retailerInfo']->addressList;
        $removeaddressList[0]->address = $removeaddressList[1]->address = '';
        ?>
        var diamondData = '<?php echo wp_json_encode($diamondajax); ?>';
        var post_diamond_data = JSON.stringify(diamondData);
        var product_track_url = window.location.href;

        setTimeout(function() {
            jQuery.ajax({
                url: myajax.ajaxurl,
                data: {
                    action: "gemfindDT_diamondTracking",
                    diamond_data: post_diamond_data,
                    track_url: product_track_url
                },
                //data: {diamond_data:post_diamond_data,track_url:product_track_url},
                type: 'POST',
                //dataType: 'JSON',
                success: function(response) {
                    console.log(response);
                }
            }).done(function(data) {

            });
        }, 1000);
    });


    function gemfindDT_redirectOnCart(e, post_id) {
        e.preventDefault();
        window.location.href = '<?php echo esc_url(get_site_url()); ?>' + '/cart/' + '?add-to-cart=' + post_id;
    }
</script>
<script>
    function gemfindDT_verifyCaptcha(token) {
        console.log('success!');
    };

    var onloadCallback = function() {
        jQuery(".g-recaptcha").each(function() {
            grecaptcha.render(jQuery(this).attr('id'), {
                'sitekey': '<?php echo $site_key; ?>',
                'callback': gemfindDT_verifyCaptcha
            });
        });
    };
</script>

<?php if (!empty($site_key)) { ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($site_key); ?>" data-callback="onSubmit" data-size="invisible"></div>
<?php } ?>

<div id="detail_DbModal" class="Dblinkmodal">
    <div class="Dbmodallink-content">
        <span class="Dblinkclose">&times;</span>
        <div class="loader_rb" style="display: none;">
            <!-- <i class="fa fa-spinner fa-spin" style="font-size:24px"></i> -->
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/images/diamond_rb.gif'); ?>" style="width: 200px; height: 200px;">
        </div>
        <iframe src="" id="detail_iframevideodb" scrolling="no" style="width:100%; height:98%;" allow="autoplay"></iframe>

        <video width="100%" height="90%" id="detail_mp4video" loop autoplay>
            <source src="" type="video/mp4">
        </video>
    </div>
</div>

<div id="fb-root"></div>

<script async defer crossorigin="anonymous" src="<?php echo esc_url(plugins_url('/assets/js/sdk.js', __FILE__)); ?>#xfbml=1&version=v9.0&appId=1003857163475797&autoLogAppEvents=1" nonce="Uo0Kr4VM"></script>
<script async defer src="<?php echo esc_url(plugins_url('/assets/js/pinit.js', __FILE__)); ?>"></script>

<style type="text/css">
    .grecaptcha-badge {
        visibility: visible !important;
    }
</style>