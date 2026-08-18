<?php

namespace PXLBSAdminify\Inc\Modules\MenuEditor;

use PXLBSAdminify\Inc\Utils;
use PXLBSAdminify\Inc\Admin\AdminSettings;
use PXLBSAdminify\Inc\Admin\AdminSettingsModel;

// no direct access allowed
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WP Adminify
 *
 * @package WP Adminify: Menu Editor
 *
 * @author WP Adminify <support@wpadminify.com>
 */

class MenuEditorAssets extends AdminSettingsModel
{

    public $max_upload_size;
    public function __construct()
    {
        $this->max_upload_size = \wp_max_upload_size();
        $this->options         = (array) AdminSettings::get_instance()->get();
        add_action('admin_enqueue_scripts', [$this, 'menu_editor_enqueue_scripts'], 100);
    }

    public function menu_editor_enqueue_scripts()
    {
        global $pagenow;

        // 1. Handle pages where get_current_screen() doesn't exist
        $auth_pages = ['wp-login.php', 'wp-signup.php', 'wp-activate.php'];
        if (in_array($pagenow, $auth_pages)) {
            return;
        }
        
        // 2. Handle customize.php
        if ($pagenow === 'customize.php') {
            return;
        }

        $screen = get_current_screen();

        if ($screen && $screen->id === 'toplevel_page_wp-adminify-settings') {
            $this->import_css();

            // Enqueue Styles
            wp_enqueue_style('adminify-icon-picker');
            wp_enqueue_style('adminify-select2', PXLBSADMINIFY_ASSETS . 'vendors/select2/select2' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);
            $this->add_select2_inline_style();
            wp_enqueue_style('adminify-menu-editor', PXLBSADMINIFY_ASSETS . 'css/adminify-menu-editor' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);

            // Enqueue Scripts
            wp_enqueue_script('adminify-select2', PXLBSADMINIFY_ASSETS . 'vendors/select2/select2.min.js', array('jquery'), PXLBSADMINIFY_VER, true);
            $this->add_select2_inline_script();
            wp_enqueue_script('adminify-icon-picker');
            wp_enqueue_script('adminify-menu-editor', PXLBSADMINIFY_ASSETS . 'admin/js/wp-adminify-menu-editor' . Utils::assets_ext('.js'), array('jquery', 'jquery-ui-sortable', 'adminify-icon-picker'), PXLBSADMINIFY_VER, true);

            wp_localize_script(
                'adminify-icon-picker',
                'PXLBSADMINIFY_ICON_PICKER',
                [
                    'is_elementor_active' => Utils::is_plugin_active('elementor/elementor.php'),
                ]
            );
        }

        wp_enqueue_style('dashicons');

        // Plugins Packaged Icons Library
        $plugins_icons = [];
        // if (Utils::is_plugin_active('elementor/elementor.php')) {
        //     $plugins_icons[] = 'elementor-icons';
        // }

        // De-register and Dequeue Scripts/Styles
        // if (!empty($this->options['adminify_assets'])) {
        //     foreach ($this->options['adminify_assets'] as $value) {
        //         wp_dequeue_style($value);
        //         wp_deregister_style($value);
        //     }
        // }

        // Localize Scripts
        $localize_menu_data = [
            'resturl'          => get_rest_url() . 'wpadminify/v2/',
            'ajax_url'         => admin_url('admin-ajax.php'),
            'assets_manager'   => !empty($this->options['adminify_assets']) ? $this->options['adminify_assets'] : '',
            'plugins_icons'    => $plugins_icons,
            'icon_picker_logo' => PXLBSADMINIFY_ASSETS_IMAGE . 'logos/menu-icon.svg',
            'security'         => wp_create_nonce('pxlbsadminify-menu-editor-security-nonce'),
            'max_upload_size'  => size_format(wp_max_upload_size()),
            'can_use_premium'  => jltwp_adminify()->can_use_premium_code__premium_only(),
            'baseurl'          => wp_upload_dir()['baseurl'],
        ];
        wp_localize_script('adminify-menu-editor', 'PXLBSADMINIFY_MENU_EDITOR', $localize_menu_data);
    }

    /**Import Menu CSS */
    public function import_css()
    {
        $menu_editor_custom_css  = '';
        $menu_editor_custom_css .= '.wp-adminify #wpbody-content .page-title-action{ top: -3px !important; )
        .dropdown-content{ position: relative; }
        #adminify_import_menu{cursor: pointer;overflow: hidden;font-size: 500px;position: absolute;top: 38px;z-index: 1;width: 100%;height: 30px;left: 0;-webkit-appearance: none;opacity: 0;cursor: pointer;}
        .icon-picker-container {
            position          : absolute;
            width             : 550px;
            height            : 290px;
            font-size         : 14px;
            background-color  : #fff;
            -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            box-shadow        : 0 1px 2px rgba(0, 0, 0, 0.1);
            overflow          : hidden;
            padding           : 5px;
            box-sizing        : border-box;
            z-index           : 9999;
        }
        .icon-picker-container {
            margin-left: -220px;
            margin-top : 50px;
            width      : 30%;
            z-index    : 999999 !important;
        }
        li.jltma-icommon {
            margin: 3px 3px !important;
        }
        li.jltma-icommon a {
            border : none !important;
            padding: 1px 2px !important;
        }
        .icon-picker-container ul li.jltma-icommon a:hover{
            background: none !important;
        }

        .icon-picker-container ul {
            margin       : 0;
            padding      : 0;
            margin-top   : 8px;
            margin-bottom: 10px;
        }
        .icon-picker-container ul li a span {
            width     : 20px;
            height    : 20px;
            font-size : 20px;
            display   : block;
            text-align: left;
        }
        .icon-picker-container ul li {
            display: inline-block;
            margin : 5px;
            float  : left;
        }
        .icon-picker-container ul li a {
            display        : block;
            text-decoration: none;
            color          : #373737;
            padding        : 6px 10px;
            border         : 1px solid #eee;
        }
        .icon-picker-container ul li a:hover {
            border-color: #999;
            background  : #efefef;
        }
        .icon-picker-control {
            height: 32px;
            height: 64px;
        }
        .icon-picker-control a {
            padding        : 5px;
            text-decoration: none;
            line-height    : 32px;
            width          : 25px;
        }
        .icon-picker-control a span {
            display       : inline;
            vertical-align: middle;
        }
        .icon-picker-control input {
            width: 200px;
        }
        .icon-picker-control p {
            text-align: left;
            margin    : 0;
            padding   : 3px 10px;
        }
        .icon-picker-control select {
            margin : 0 auto;
            display: inline-block;
            width  : auto;
        }
        /* DIV Button with Preview */
        div.button.icon-picker {
            font-size  : 24px;
            height     : 30px;
            width      : 30px;
            margin     : 0;
            padding    : 0;
            line-height: 30px;
            text-align : center;
        }
        .button.icon-picker:before{
            content    : "\f504";
            font-family: dashicons;
            font-size  : 30px;
        }
        .icon-picker-close{
            float      : right;
            display    : inline-block;
            padding    : 2px;
            background : #ccc;
            cursor     : pointer;
            font-weight: 600;
        }
        .jltma-pro-badge{
            position    : absolute;
            z-index     : 333;
            text-align  : center;
            padding-left: 23%;
            font-size   : 70px !important;
            padding-top : 10%;
        }
        .top-badge{
            padding-left: 20%;
            padding-top : 0;
        }
        .jltma-disabled{
            pointer-events: none;
            opacity       : 0.4;
        }
        .select2-option-disabled{
            opacity: 0.5;
            cursor: not-allowed;
        }
        .select2-container--default .select2-results__option[aria-disabled=true]{
            background: none !important;
            color: inherit;
            opacity: 0.5!important;
        }
        .select2-search-hint{
            color: #999;
            font-style: italic;
        }
        select[name="hidden_for"].select2-hidden-accessible{
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0,0,0,0) !important;
            border: 0 !important;
        }
        .adminify_sub_menu_item .select2-container,
        .adminify_menu_item .select2-container{
            width: 100% !important;
        }';

        // Combine the values from above and minifiy them.
        $menu_editor_custom_css = preg_replace('#/\*.*?\*/#s', '', $menu_editor_custom_css);
        $menu_editor_custom_css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $menu_editor_custom_css);
        $menu_editor_custom_css = preg_replace('/\s\s+(.*)/', '$1', $menu_editor_custom_css);

        wp_add_inline_style('adminify-menu-editor', wp_strip_all_tags($menu_editor_custom_css));
    }

    /**
     * Select 2 Supported script
     */
    private function add_select2_inline_script() {
        ob_start();
        ?>

        jQuery(function($) {
            $('.select-field').select2({width: '100%'});

            $('.copy_to').on('change', function() {
                if ( $(this).val() == 'copy_to_all' ) {
                    $(this).closest('.wp-clone-sites-options').find('.copy_exclude-field-wrapper').show();
                } else {
                    $(this).closest('.wp-clone-sites-options').find('.copy_exclude-field-wrapper').hide();
                }
            });

            $('#option_modules_toggle').on('click', function(e) {
                e.preventDefault();
                $checkboxes = $(this).closest('.line-single--content').find('input[type="checkbox"]');
                $checked = $checkboxes.filter(':checked');
                $status = true;
                if ( $checked.length == $checkboxes.length ) $status = false;
                $checkboxes.each(function(){ $(this).prop('checked', $status) });
            });
        });
        <?php

        $script = ob_get_clean();

        wp_add_inline_script('adminify-select2', $script);
    }

    /**
     * Select2 supported style
     */
    private function add_select2_inline_style() {
        $output_css = '.wp-adminify-settings .dashicons,.wp-adminify-settings .dashicons-before:before{vertical-align:middle}.adminify-status{background:#fff;padding:12px 10px;margin:30px 0;-webkit-border-radius:4px;border-radius:4px;-webkit-box-shadow:0 0 8px rgba(139,148,169,.15);box-shadow:0 0 8px rgba(139,148,169,.15)}.adminify-status.adminify-status--success{border-left:4px solid #48cf5b}.adminify-status.adminify-status--error{border-left:4px solid #f16b6b}.adminify-status p{margin:0}.adminify-status p:not(:last-child){margin-bottom:10px}.wp-clone-sites-options h1{margin:10px 0 30px}.wp-clone-sites-options{max-width:800px; margin: 50px auto;}.container.wp-clone-sites-options form{padding:30px;background:#fff;margin:20px 0;-webkit-border-radius:4px;border-radius:4px;-webkit-box-shadow:0 0 24px rgba(108,111,120,.15);box-shadow:0 0 24px rgba(108,111,120,.15)}.wp-clone-sites-options .select-field{width:100%}.line-single--wrapper{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin-bottom:24px;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;background:#eff0f3;padding:24px 20px;-webkit-border-radius:4px;border-radius:4px}.line-single--title{width:100%;margin-bottom:10px;font-weight:700}.line-single--content{width:100%;display:-webkit-inline-box;display:-webkit-inline-flex;display:-ms-inline-flexbox;display:inline-flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap}.line-single--content>div{width:33.333333%;margin-bottom:8px}button#option_modules_toggle{padding:8px 10px;line-height:1;margin-top:5px;border:none;background:#fff;-webkit-border-radius:4px;border-radius:4px;cursor:pointer;-webkit-box-shadow:0 0 4px #ddd;box-shadow:0 0 4px #ddd}';

        // Use wp_add_inline_style instead of printf to prevent "headers already sent" errors
        wp_add_inline_style('adminify-select2', 'body.toplevel_page_wp-adminify-settings.network-admin{' . wp_strip_all_tags($output_css) . '}');

        $select2_css = '.wp-adminify .select2-container .select2-selection--single .select2-selection__rendered {
            color: #000;
            line-height: 34px;
        }

        .select2-container--default .select2-selection--single, .select2-dropdown, .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d1d1;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            line-height: 1.6;
        }

        .select2-container .select2-selection--multiple .select2-selection__rendered {
            vertical-align: sub;
        }

        span.select2-search.select2-search--inline {
            vertical-align: super;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            background: none !important;
            padding: 0 !important;
        }';

        // Combine the values from above and minifiy them.
        $select2_css = preg_replace('#/\*.*?\*/#s', '', $select2_css);
        $select2_css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $select2_css);
        $select2_css = preg_replace('/\s\s+(.*)/', '$1', $select2_css);
        wp_add_inline_style('adminify-select2', $select2_css);
    }
}
