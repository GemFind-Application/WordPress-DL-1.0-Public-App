<?php
if (isset($_POST['settings']) && !empty($_POST['settings'])) {
    global $wpdb;
    unset($_POST['settings']);
    function sanitize_custom_array($input_data)
    {
        // Sanitize each element in the array
        $sanitized_data = array_map('sanitize_text_field', $input_data);

        $sanitized_data['dealerid'] = sanitize_text_field($sanitized_data['dealerid']);
        $sanitized_data['from_email_address'] = sanitize_text_field($sanitized_data['from_email_address']);
        $sanitized_data['admin_email_address'] = sanitize_text_field($sanitized_data['admin_email_address']);
        $sanitized_data['dealerauthapi'] = sanitize_text_field($sanitized_data['dealerauthapi']);
        $sanitized_data['navigationapi'] = sanitize_text_field($sanitized_data['navigationapi']);
        $sanitized_data['filterapi'] = sanitize_text_field($sanitized_data['filterapi']);
        $sanitized_data['filterapifancy'] = sanitize_text_field($sanitized_data['filterapifancy']);
        $sanitized_data['diamondlistapi'] = sanitize_text_field($sanitized_data['diamondlistapi']);
        $sanitized_data['diamondlistapifancy'] = sanitize_text_field($sanitized_data['diamondlistapifancy']);
        $sanitized_data['diamondshapeapi'] = sanitize_text_field($sanitized_data['diamondshapeapi']);
        $sanitized_data['diamonddetailapi'] = sanitize_text_field($sanitized_data['diamonddetailapi']);
        $sanitized_data['stylesettingapi'] = sanitize_text_field($sanitized_data['stylesettingapi']);
        $sanitized_data['diamondsoptionapi'] = sanitize_text_field($sanitized_data['diamondsoptionapi']);
        $sanitized_data['diamondsinitialfilter'] = sanitize_text_field($sanitized_data['diamondsinitialfilter']);
        $sanitized_data['enable_hint'] = sanitize_text_field($sanitized_data['enable_hint']);
        $sanitized_data['enable_email_friend'] = sanitize_text_field($sanitized_data['enable_email_friend']);
        $sanitized_data['enable_schedule_viewing'] = sanitize_text_field($sanitized_data['enable_schedule_viewing']);
        $sanitized_data['enable_more_info'] = sanitize_text_field($sanitized_data['enable_more_info']);
        $sanitized_data['enable_print'] = sanitize_text_field($sanitized_data['enable_print']);
        $sanitized_data['enable_admin_notify'] = sanitize_text_field($sanitized_data['enable_admin_notify']);
        $sanitized_data['default_view'] = sanitize_text_field($sanitized_data['default_view']);
        $sanitized_data['show_hints_popup'] = sanitize_text_field($sanitized_data['show_hints_popup']);
        $sanitized_data['show_copyright'] = sanitize_text_field($sanitized_data['show_copyright']);
        $sanitized_data['enable_sticky_header'] = sanitize_text_field($sanitized_data['enable_sticky_header']);
        $sanitized_data['enable_price_users'] = sanitize_text_field($sanitized_data['enable_price_users']);
        $sanitized_data['shop_logo'] = sanitize_text_field($sanitized_data['shop_logo']);
        $sanitized_data['site_key'] = sanitize_text_field($sanitized_data['site_key']);
        $sanitized_data['secret_key'] = sanitize_text_field($sanitized_data['secret_key']);
        $sanitized_data['top_textarea'] = sanitize_text_field($sanitized_data['top_textarea']);
        $sanitized_data['detail_textarea'] = sanitize_text_field($sanitized_data['detail_textarea']);
        $sanitized_data['diamond_meta_title'] = sanitize_text_field($sanitized_data['diamond_meta_title']);
        $sanitized_data['diamond_meta_description'] = sanitize_text_field($sanitized_data['diamond_meta_description']);
        $sanitized_data['price_row_format'] = sanitize_text_field($sanitized_data['price_row_format']);
        $sanitized_data['diamond_meta_keyword'] = sanitize_text_field($sanitized_data['diamond_meta_keyword']);

        return $sanitized_data;
    }

    if (isset($_POST['gemfind_diamond_link'])) {
        $post_data = sanitize_custom_array($_POST['gemfind_diamond_link']);
    } else {
        echo "Error: 'gemfind_diamond_link' is not set in the POST data.";
    }

    if ($post_data['dealerid'] == '') {
        esc_html_e('<div class="error"><p>Dealerid is required.</p></div>', 'diamondlink');
    }
    $password = $post_data['password'];
    $hashedPassword = wp_hash_password($password);
    $data = [
        'dealerid' => $post_data['dealerid'],
        'password' => $hashedPassword,
        'from_email_address' => $post_data['from_email_address'],
        'admin_email_address' => $post_data['admin_email_address'],
        'dealerauthapi' => $post_data['dealerauthapi'],
        'navigationapi' => $post_data['navigationapi'],
        'filterapi' => $post_data['filterapi'],
        'filterapifancy' => $post_data['filterapifancy'],
        'diamondlistapi' => $post_data['diamondlistapi'],
        'diamondlistapifancy' => $post_data['diamondlistapifancy'],
        'diamondshapeapi' => $post_data['diamondshapeapi'],
        'diamonddetailapi' => $post_data['diamonddetailapi'],
        'stylesettingapi' => $post_data['stylesettingapi'],
        'diamondsoptionapi' => $post_data['diamondsoptionapi'],
        'diamondsinitialfilter' => $post_data['diamondsinitialfilter'],
        'enable_hint' => $post_data['enable_hint'],
        'enable_email_friend' => $post_data['enable_email_friend'],
        'enable_schedule_viewing' => $post_data['enable_schedule_viewing'],
        'enable_more_info' => $post_data['enable_more_info'],
        'enable_print' => $post_data['enable_print'],
        'enable_admin_notify' => $post_data['enable_admin_notify'],
        'default_view' => $post_data['default_view'],
        'show_hints_popup' => $post_data['show_hints_popup'],
        'show_copyright' => $post_data['show_copyright'],
        'enable_sticky_header' => $post_data['enable_sticky_header'],
        'enable_price_users' => $post_data['enable_price_users'],
        'shop_logo' => $post_data['shop_logo'],
        'site_key' => $post_data['site_key'],
        'secret_key' => $post_data['secret_key'],
        'top_textarea' => $post_data['top_textarea'],
        'detail_textarea' => $post_data['detail_textarea'],
        'diamond_meta_title' => $post_data['diamond_meta_title'],
        'diamond_meta_description' => $post_data['diamond_meta_description'],
        'price_row_format' => $post_data['price_row_format'],
        'diamond_meta_keyword' => $post_data['diamond_meta_keyword'],
        'load_from_woocommerce' => isset($_POST['woocommerce']) ? sanitize_text_field($_POST['woocommerce']) : 0,
    ];
    if (isset($post_data['dealerid']) && $post_data['dealerid'] != '') {
        update_option('gemfind_diamond_link', $data);
    }
}
$gemfind_diamond_link = get_option('gemfind_diamond_link');
if (isset($_POST['woocommerce']) || $gemfind_diamond_link['load_from_woocommerce'] == 1) {
    $checked = 'checked="checked"';
} else {
    $checked = '';
}
?>
<div class="wrap">
    <h2><?php esc_html_e('GemFind Diamond Link Options', 'diamondlink'); ?></h2>
    <form method="POST" action="" class="dl_admin_settings">
        <table width="100%" border="0" cellspacing="3" cellpadding="5">
            <tr>
                <td width="200">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Dealer ID', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[dealerid]" value="<?php echo esc_html($gemfind_diamond_link['dealerid']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <!-- <tr>
                <td><strong><?php esc_html_e('Password', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="password" name="gemfind_diamond_link[password]" value="" class="form-control"
                           maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('From Email Address', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[from_email_address]"
                           value="<?php echo esc_html($gemfind_diamond_link['from_email_address']); ?>" class="form-control"
                           maxlength="255">
                </td>
            </tr> -->
            <tr>
                <td><strong><?php esc_html_e('Admin Email Address', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[admin_email_address]" value="<?php echo esc_html($gemfind_diamond_link['admin_email_address']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Dealer Auth API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[dealerauthapi]" value="<?php echo esc_html($gemfind_diamond_link['dealerauthapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Navigation Api', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[navigationapi]" value="<?php echo esc_html($gemfind_diamond_link['navigationapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Filter API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[filterapi]" value="<?php echo esc_html($gemfind_diamond_link['filterapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Filter API Fancy', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[filterapifancy]" value="<?php echo esc_html($gemfind_diamond_link['filterapifancy']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond List API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamondlistapi]" value="<?php echo esc_html($gemfind_diamond_link['diamondlistapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond List API Fancy', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamondlistapifancy]" value="<?php echo esc_html($gemfind_diamond_link['diamondlistapifancy']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Shape API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamondshapeapi]" value="<?php echo esc_html($gemfind_diamond_link['diamondshapeapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Detail API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamonddetailapi]" value="<?php echo esc_html($gemfind_diamond_link['diamonddetailapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Style Setting API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[stylesettingapi]" value="<?php echo esc_html($gemfind_diamond_link['stylesettingapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamonds Option API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamondsoptionapi]" value="<?php echo esc_html($gemfind_diamond_link['diamondsoptionapi']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Initial Filter API', 'diamondlink'); ?></strong></td>
                <td>
                    <input readonly="" type="text" name="gemfind_diamond_link[diamondsinitialfilter]" value="<?php echo esc_html($gemfind_diamond_link['diamondsinitialfilter']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Hint', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_hint]" id="enable_hint" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_hint'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_hint'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Email Friend', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_email_friend]" id="enable_email_friend" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_email_friend'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_email_friend'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Schedule Viewing', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_schedule_viewing]" id="enable_schedule_viewing" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_schedule_viewing'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_schedule_viewing'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable More Info', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_more_info]" id="enable_more_info" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_more_info'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_more_info'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Print', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_print]" id="enable_print" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_print'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_print'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Vendor Notification', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_admin_notify]" id="enable_admin_notify" class="form-control">
                        <option value="true" <?php if ($gemfind_diamond_link['enable_admin_notify'] == 'true') {
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                        </option>
                        <option value="false" <?php if ($gemfind_diamond_link['enable_admin_notify'] == 'false') {
                                                    echo 'selected="selected"';
                                                } ?>>No
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Default view', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[default_view]" id="default_view" class="form-control">
                        <option value="grid" <?php echo esc_html($gemfind_diamond_link['default_view']) == 'grid' ? 'selected' : ''; ?>>Grid</option>
                        <option value="list" <?php echo esc_html($gemfind_diamond_link['default_view']) == 'list' ? 'selected' : ''; ?>>List</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Apply Hints Popup', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[show_hints_popup]" id="show_hints_popup" class="form-control">
                        <option value="yes" <?php echo esc_html($gemfind_diamond_link['show_hints_popup']) == 'yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo esc_html($gemfind_diamond_link['show_hints_popup']) == 'no' ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Copyright text', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[show_copyright]" id="show_copyright" class="form-control">
                        <option value="yes" <?php echo esc_html($gemfind_diamond_link['show_copyright']) == 'yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo esc_html($gemfind_diamond_link['show_copyright']) == 'no' ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Enable Sticky Header?', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_sticky_header]" id="enable_sticky_header" class="form-control">
                        <option value="yes" <?php echo esc_html($gemfind_diamond_link['enable_sticky_header']) == 'yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo esc_html($gemfind_diamond_link['enable_sticky_header']) == 'no' ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Display price only Logged In', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[enable_price_users]" id="enable_price_users" class="form-control">
                        <option value="yes" <?php echo esc_html($gemfind_diamond_link['enable_price_users']) == 'yes' ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo esc_html($gemfind_diamond_link['enable_price_users']) == 'no' ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Shop Logo URL', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[shop_logo]" value="<?php echo esc_html($gemfind_diamond_link['shop_logo']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Google Captcha - Site Key', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[site_key]" value="<?php echo esc_html($gemfind_diamond_link['site_key']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Google Captcha - Secret Key', 'diamondlink'); ?></strong></td>
                <td>
                    <input type="text" name="gemfind_diamond_link[secret_key]" value="<?php echo esc_html($gemfind_diamond_link['secret_key']); ?>" class="form-control" maxlength="255">
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Top TextArea', 'diamondlink'); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_diamond_link[top_textarea]" class="form-control"><?php //echo stripcslashes($gemfind_diamond_link['top_textarea']);
                                                                                                    ?></textarea> -->
                    <?php wp_editor($gemfind_diamond_link['top_textarea'], 'top_textarea', [
                        'wpautop' => true,
                        'media_buttons' => false,
                        'textarea_name' => 'gemfind_diamond_link[top_textarea]',
                        'editor_class' => 'form-control',
                        'textarea_rows' => 10,
                    ]); ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Details TextArea', 'diamondlink'); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_diamond_link[detail_textarea]" class="form-control"><?php //echo stripcslashes($gemfind_diamond_link['detail_textarea']);
                                                                                                        ?></textarea> -->

                    <?php wp_editor($gemfind_diamond_link['detail_textarea'], 'detail_textarea', [
                        'wpautop' => true,
                        'media_buttons' => false,
                        'textarea_name' => 'gemfind_diamond_link[detail_textarea]',
                        'editor_class' => 'form-control',
                        'textarea_rows' => 10,
                    ]); ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Meta Title', 'diamondlink'); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_diamond_link[diamond_meta_title]" class="form-control"><?php //echo stripcslashes($gemfind_diamond_link['diamond_meta_title']);
                                                                                                        ?></textarea> -->
                    <?php wp_editor($gemfind_diamond_link['diamond_meta_title'], 'diamond_meta_title', [
                        'wpautop' => true,
                        'media_buttons' => false,
                        'textarea_name' => 'gemfind_diamond_link[diamond_meta_title]',
                        'editor_class' => 'form-control',
                        'textarea_rows' => 10,
                    ]); ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Meta Keyword', 'diamondlink'); ?></strong></td>
                <td>
                    <!--  <textarea name="gemfind_diamond_link[diamond_meta_keyword]" class="form-control"><?php //echo stripcslashes($gemfind_diamond_link['diamond_meta_keyword']);
                                                                                                            ?></textarea> -->

                    <?php wp_editor($gemfind_diamond_link['diamond_meta_keyword'], 'diamond_meta_keyword', [
                        'wpautop' => true,
                        'media_buttons' => false,
                        'textarea_name' => 'gemfind_diamond_link[diamond_meta_keyword]',
                        'editor_class' => 'form-control',
                        'textarea_rows' => 10,
                    ]); ?>

                </td>
            </tr>
            <tr>
                <td><strong><?php esc_html_e('Diamond Meta Description', 'diamondlink'); ?></strong></td>
                <td>
                    <!-- <textarea name="gemfind_diamond_link[diamond_meta_description]" class="form-control"><?php //echo stripcslashes($gemfind_diamond_link['diamond_meta_description']);
                                                                                                                ?></textarea> -->
                    <?php wp_editor($gemfind_diamond_link['diamond_meta_description'], 'diamond_meta_description', [
                        'wpautop' => true,
                        'media_buttons' => false,
                        'textarea_name' => 'gemfind_diamond_link[diamond_meta_description]',
                        'editor_class' => 'form-control',
                        'textarea_rows' => 10,
                    ]); ?>
                </td>
            </tr>

            <tr>
                <td><strong><?php esc_html_e('Currency Symbol Location', 'diamondlink'); ?></strong></td>
                <td>
                    <select name="gemfind_diamond_link[price_row_format]" id="price_row_format" class="form-control">
                        <option value="right" <?php echo esc_html($gemfind_diamond_link['price_row_format']) == 'right' ? 'selected' : ''; ?>>Right</option>
                        <option value="left" <?php echo esc_html($gemfind_diamond_link['price_row_format']) == 'left' ? 'selected' : ''; ?>>Left</option>
                    </select>
                </td>
            </tr>

            <tr class="submit-button">
                <td><input type="submit" name="settings" id="settings" value="Save" class="button button-primary button-large"></td>
            </tr>
        </table>
    </form>
    <script type="text/javascript">
        jQuery('#woocommerce').on('click', function() {
            if (jQuery('#woocommerce').is(':checked')) {
                jQuery('#woocommerce').attr('checked', true); // Checks it                
            } else {
                jQuery('#woocommerce').attr('checked', false); // Unchecks it
            }
        });
    </script>
    <style type="text/css">
        input[type=checkbox] {
            height: 0;
            width: 0;
            visibility: hidden;
        }

        label {
            cursor: pointer;
            text-indent: -9999px;
            width: 46px;
            height: 23px;
            background: #fbfbfb;
            border: 1px solid #e8eae9;
            display: block;
            border-radius: 100px;
            position: relative;
            -moz-box-shadow: 0px 3px 3px 0px #ccc;
            -webkit-box-shadow: 0px 3px 3px 0px #ccc;
            box-shadow: 0px 3px 3px 0px #ccc;
        }

        label:after {
            content: '';
            position: absolute;
            top: 2px;
            left: 3px;
            width: 18px;
            height: 18px;
            background: #fbfbfb;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, .1), 0 4px 0 rgba(0, 0, 0, .08);
            border-radius: 30px;
            transition: 0.3s;
        }

        input:checked+label {
            background: #349644;
        }

        input:checked+label:after {
            left: calc(100% - 5px);
            transform: translateX(-100%);
        }

        label:active:after {
            width: 80px;
        }

        .extra-info {
            top: 11px;
            position: relative;
            padding-bottom: 0;
            display: block;
        }

        .submit-button {
            clear: both;
            display: block;
            position: relative;
            top: 25px;
        }

        .submit-button .button {
            width: 120px;
            /*height: 38px !important;*/
            font-size: 21px;
        }

        .dl_admin_settings textarea {
            min-height: 90px;
            min-width: 300px;
            resize: none;
        }
    </style>