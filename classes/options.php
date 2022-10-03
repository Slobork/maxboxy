<?php
/**
 * Description: Getting Maxboxy options form the Mataboxes
 * 
 * PHP version 7.3.5
 * 
 * @category Conversion
 * @package  MaxBoxy
 * @author   MaxPressy <webmaster@maxpressy.com>
 * @license  GPL v2 or later
 * @link     maxpressy.com
 */
if (! defined('ABSPATH')) { 
    exit; 
}

if (! class_exists('Max__Boxy__Options')) {


    /**
     * Gt options from the metaboxes
     *
     * @category Conversion
     * @package  MaxBoxy
     * @author   MaxPressy <webmaster@maxpressy.com>
     * @license  GPL v2 or later
     * @link     maxpressy.com
     */
    class Max__Boxy__Options
    {

        /**
         * Loading options.
         *
         * @param int $get_id Receive the id of the panel.
         * 
         * @return array.
         */
        public static function loading( $get_id )
        {

            $asign_metabox = get_post_type($get_id) === 'float_any'
                          || get_post_type($get_id) === 'inject_any'
                           ? true : false;

            if ($asign_metabox === false) {
                return;
            }

             /*
              * *_loading Meta box's data is unserialized, so get it directly
              * without the prefix, i.e. not using _mb_floatany like 
              * _mb_floatany_loading | _mb_injectany_loading.
              * ...Just use its fields directly, e.g. 'location' (location is under 
              * _loading mtabox as well)
              */
             $global_loading  = get_post_meta($get_id, 'auto_loading', true);
             $location        = get_post_meta($get_id, 'location', true);

            // set $global_loading as boolean
            $global_loading = $global_loading === 'disabled' ? false : true;

            if (isset($location)) {

                if (get_post_type($get_id) === 'float_any') {

                    // default popup place
                    $popup_place     = ' place-center-centered';

                    $elm_popup_place = $location ===  'left-topped'     ? ' place-left-topped'     : $popup_place;
                    $elm_popup_place = $location ===  'left-centered'   ? ' place-left-centered'   : $elm_popup_place;
                    $elm_popup_place = $location ===  'left-bottomed'   ? ' place-left-bottomed'   : $elm_popup_place;
                    $elm_popup_place = $location ===  'center-topped'   ? ' place-center-topped'   : $elm_popup_place;
                    $elm_popup_place = $location ===  'center'          ? ' place-center-centered' : $elm_popup_place;
                    $elm_popup_place = $location ===  'center-bottomed' ? ' place-center-bottomed' : $elm_popup_place;
                    $elm_popup_place = $location ===  'right-topped'    ? ' place-right-topped'    : $elm_popup_place;
                    $elm_popup_place = $location ===  'right-centered'  ? ' place-right-centered'  : $elm_popup_place;
                    $elm_popup_place = $location ===  'right-bottomed'  ? ' place-right-bottomed'  : $elm_popup_place;

                    $popup_place = $elm_popup_place;

                        $args = array(
                            'global'   => $global_loading,
                            'location' => $popup_place,
                        );

                    // For the InjectAny
                } else if (get_post_type($get_id) === 'inject_any' ) {

                    $inject_place = $location === 'top'    ? ' place-top'    : 'head';
                    $inject_place = $location === 'bottom' ? ' place-bottom' : $inject_place;

                    $args = array(
                        'global'   => $global_loading,
                        'location' => $inject_place,
                    );

                }

            }

            return $args;

        }


        /**
         * Basics options.
         * 
         * @param int $get_id Receive the id of the panel.
         * 
         * @return array.
         */
        public static function basics( $get_id )
        {

            $asign_metabox = get_post_type($get_id) === 'float_any'  ? '_mb_floatany'  : false;
            $asign_metabox = get_post_type($get_id) === 'inject_any' ? '_mb_injectany' : $asign_metabox;
            $asign_metabox = get_post_type($get_id) === 'wp_block'
                          && class_exists('Max__Boxy__Reusable_blocks')
                          && Max__Boxy__Reusable_blocks::enabled() === true
                          ? '_mb_injectany' : $asign_metabox;

            if ($asign_metabox === false) {
                return;
            }

            $settings   =   get_post_meta($get_id, $asign_metabox, true);
            $basics     =   isset($settings[ 'box_options' ]) ?  $settings[ 'box_options' ] : '';


            // Popup style. Default -> "bumpin" for FloatAny and onload" for InjectAny
            $showing_style =   get_post_type($get_id) === 'float_any' ? ' style-bumpin' : ' style-onload';

                // bypass any potential notices & warnings
            if (isset($basics[ 'panel_popup_style' ])) {

                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'onpageload'       ? ' style-onload'           : $showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'bump'             ? ' style-bumpin'           : $elm_showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'fade'             ? ' style-fadein'           : $elm_showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'slide-horizontal' ? ' style-slide-horizontal' : $elm_showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'slide-vertical'   ? ' style-slide-vertical'   : $elm_showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'alignwide'        ? ' style-alignwide'        : $elm_showing_style;
                $elm_showing_style = $basics[ 'panel_popup_style' ] === 'alignfull'        ? ' style-alignfull'        : $elm_showing_style;

                $showing_style     = ! empty($basics[ 'panel_popup_style' ]) ? $elm_showing_style : $showing_style;

            }

            // z-index
            $zindex                    = isset($basics[ 'zindex' ]) && is_numeric($basics[ 'zindex' ]) ? 'z-index:' .intval($basics[ 'zindex' ]) .';' : '';
            $_escaped_panel_wrap_style = ! empty($zindex) ? ' style="' .esc_attr($zindex) . '"' : '';

            // overlay
            $use_overlay               = ! empty($basics[ 'use_overlay' ]) ? true : false;

            // sticky
            $sticky                    = ! empty($basics[ 'set_sticky' ])  ? ' is-sticky' : '';

            // panel shut
            $panel_type                = isset($basics[ 'panel_type' ]) ? $basics[ 'panel_type' ]   : 'closer';
            $shut_class                = isset($basics[ 'panel_type' ]) ? ' type-' .$basics[ 'panel_type' ]  : ' type-toggler';

            // panel roles
            $get_roles                 = ! empty($basics[ 'panel_roles' ]) ? $basics[ 'panel_roles' ] : '';

            /*
             * If the type is 'closer' but 'role-igniter' is leftover from the
             * previos toggler type selection, remove 'role-igniter'
             */
            if ($panel_type === 'closer' && is_array($get_roles) && in_array('role-igniter', $get_roles)) {
                array_pop($get_roles);
            }

            /*
             * If the Conversion tracking isn't enabled, prevent output of the
             * 'role-banish' coz in JS localStorage is used to handle banished panels
             */
            if (Max__Boxy__Track::enabled() !== true && $panel_type === 'closer' && is_array($get_roles) && ($key = array_search('role-banish', $get_roles)) !== false) {
                unset($get_roles[$key]);
            }

            // Convert array to a string
            $roles       = is_array($get_roles) ? implode(' ', $get_roles) : '';
            /*
             * If the 'closer' is selected, prepend a space before
             * the output, so it adds correctly to the existing classes
             */
            $panel_roles = $panel_type === 'closer' && ! empty($roles)  ? ' ' .$roles : '';


            /*
             * Rotator and igniter are checked multiple times below,
             * since make the condtion check here.
             */
            $rotator     = is_array($get_roles) && in_array('role-rotator', $get_roles)    ?   true :   false;
            $igniter     = $panel_type === 'toggler' && is_array($get_roles) && in_array('role-igniter', $get_roles) ? true : false;

            $hidden      = $panel_type === 'closer' && is_array($get_roles) && in_array('role-hidden', $get_roles)   ? true : false;

            /*
             * With toggler - role-rotator and role-igniter are the only
             * options in UI, other options are hidden. So only check for them.
             */
            $panel_roles                   .= $panel_type === 'toggler' && $rotator === true ? ' role-rotator' : '';
            $panel_roles                   .= $igniter === true ? ' role-igniter' : '';

            $injectany_preload              = $showing_style === ' style-onload' && $igniter === false && $hidden === false ? ' on' : ''; // reveal on page load, i.e. no waiting to add 'on'
            $panel_strain                   = get_post_type($get_id) === 'float_any' ? ' floatany' : '';
            $panel_strain                   = get_post_type($get_id) === 'wp_block' && class_exists('Max__Boxy__Reusable_blocks') && Max__Boxy__Reusable_blocks::enabled() === true
                                            ? ' is-reusable-block injectany' .$injectany_preload : $panel_strain;
            $panel_strain                   = get_post_type($get_id) === 'inject_any' ? ' injectany' .$injectany_preload : $panel_strain;

            // for the toggler/igniter button
            $open_button_data               = $panel_type === 'toggler' && ! empty($basics[ 'button_open' ])
                                            ? ' data-button-open="' .esc_attr($basics[ 'button_open' ]) .'"' : ''; // ' data-button-open="iks-plus"';

            $close_button_data              = ! empty($basics[ 'button_close' ])
                                            ? ' data-button-close="' .esc_attr($basics[ 'button_close' ]) .'"' : ''; // ' data-button-close="iks"';

            $_escaped_toggler_data          = $open_button_data  .$close_button_data;

            $open_button_class              = $panel_type === 'toggler' && ! empty($basics[ 'button_open' ])
                                            ?   ' ' .$basics[ 'button_open' ] : ' iks-plus';

            $close_button_class             = ! empty($basics[ 'button_close' ])
                                            ? ' ' .$basics[ 'button_close' ] : ' iks';

            $toggler_start_title            = $igniter === true ? esc_html__('Open', 'maxboxy') : esc_html__('Close', 'maxboxy');
            $toggler_start_class            = $igniter === true ? $open_button_class : $close_button_class;

            // Rotator - on time span
            $rotator_on                     = isset($basics[ 'rotator_on' ]) && is_numeric($basics[ 'rotator_on' ]) ? abs($basics[ 'rotator_on' ]) : 5; // default 5s
            $_escaped_rotator_on_data       = ' data-rotator-on="'  .esc_attr($rotator_on) .'"';

            // Rotator - off time span
            $rotator_off                    = isset($basics[ 'rotator_off' ]) && is_numeric($basics[ 'rotator_off' ]) ? abs($basics[ 'rotator_off' ]) : 10; // default 10s
            $_escaped_rotator_off_data      = ' data-rotator-off="'  .esc_attr($rotator_off) .'"';

            // Rotator - pause on user's closing
            $rotator_close_pause            = isset($basics[ 'rotator_close_pause' ]) && is_numeric($basics[ 'rotator_close_pause' ]) ? abs($basics[ 'rotator_close_pause' ]) : '';
            $_escaped_rota_close_pause_data = $panel_type === 'closer' && ! empty($rotator_close_pause) ? ' data-rotator-close-pause="'  .esc_attr($rotator_close_pause) .'"' : '';

            $_escaped_rotator_time_data     = $rotator === true ? $_escaped_rotator_on_data .$_escaped_rotator_off_data .$_escaped_rota_close_pause_data : '';
            $rotator_repeat                 = $rotator === true && ! empty($basics[ 'rotator_repeat' ]) ? ' rotator-repeat' : '';

            // mark closing with hover-out panel type
            $mark_hoverout_close            = $panel_type === 'closer' && is_array($get_roles) && in_array('role-hoverer', $get_roles) && ! empty($basics[ 'mark_hoverout_closing' ]) ? ' mark-hoverout-close' : '';

            /*
             * Additional classes - the field is in the group (i.e. in tab),
             * so we need to sanitize it here.
             */
            $add_classes = ! empty($basics[ 'add_classes' ]) ? ' ' .sanitize_text_field($basics[ 'add_classes' ]) : '';

            /*
             * Colors
             */

            // panel background
            $panel_bg       = isset($basics[ 'panel_popup_bg' ])                ? $basics[ 'panel_popup_bg' ] : '';

            $set_panel_bg   = ! empty($panel_bg[ 'background-image' ][ 'url' ]) ? 'background-image:url('  .$panel_bg[ 'background-image' ][ 'url' ] .');' :'';
            $set_panel_bg  .= ! empty($panel_bg[ 'background-repeat' ])         ? 'background-repeat:'     .$panel_bg[ 'background-repeat' ]         .';'  :'';
            $set_panel_bg  .= ! empty($panel_bg[ 'background-position' ])       ? 'background-position:'   .$panel_bg[ 'background-position' ]       .';'  :'';
            $set_panel_bg  .= ! empty($panel_bg[ 'background-attachment' ])     ? 'background-attachment:' .$panel_bg[ 'background-attachment' ]     .';'  :'';
            $set_panel_bg  .= ! empty($panel_bg[ 'background-size' ])           ? 'background-size:'       .$panel_bg[ 'background-size' ]           .';'  :'';

            // get default value from the settings
            $floatany_bg_bc          = isset(get_option('_maxboxy_options')[ 'floatany_bg' ][ 'background-color' ])
                                     ?       get_option('_maxboxy_options')[ 'floatany_bg' ][ 'background-color' ] : '';

            $injectany_bg_bc         = isset(get_option('_maxboxy_options')[ 'injectany_bg' ][ 'background-color' ])
                                     ?       get_option('_maxboxy_options')[ 'injectany_bg' ][ 'background-color' ] : '';

            $reusable_bg_bc          = isset(get_option('_maxboxy_options')[ 'reusable_bg' ][ 'background-color' ])
                                     ?       get_option('_maxboxy_options')[ 'reusable_bg' ][ 'background-color' ] : '';

            $floatany_bg_bc_default  = ! empty($floatany_bg_bc)  ? $floatany_bg_bc  : '#e8e2b7'; // plugin's default, from css file, is '#e8e2b7'
            $injectany_bg_bc_default = ! empty($injectany_bg_bc) ? $injectany_bg_bc : '#e8e2b7'; // plugin's default, from css file, is '#e8e2b7'
            $reusable_bg_bc_default  = ! empty($reusable_bg_bc)  ? $reusable_bg_bc  : '#e8e2b7'; // plugin's default, from css file, is '#e8e2b7'

            // print if bg doesn't equals to the default value
            $set_panel_bg  .= get_post_type($get_id) === 'float_any'  && $panel_bg[ 'background-color' ] !== $floatany_bg_bc_default  ? 'background-color:' .$panel_bg[ 'background-color' ] .';' :'';
            $set_panel_bg  .= get_post_type($get_id) === 'inject_any' && $panel_bg[ 'background-color' ] !== $injectany_bg_bc_default ? 'background-color:' .$panel_bg[ 'background-color' ] .';' :'';
            $set_panel_bg  .= get_post_type($get_id) === 'wp_block'   && $panel_bg[ 'background-color' ] !== $reusable_bg_bc_default  ? 'background-color:' .$panel_bg[ 'background-color' ] .';' :'';

            // color - get default value from the settings
            $floatany_panel_color          = isset(get_option('_maxboxy_options')[ 'floatany_color' ])
                                           ?       get_option('_maxboxy_options')[ 'floatany_color' ] : '';

            $injectany_panel_color         = isset(get_option('_maxboxy_options')[ 'injectany_color' ])
                                           ?       get_option('_maxboxy_options')[ 'injectany_color' ] : '';

            $reusable_panel_color          = isset(get_option('_maxboxy_options')[ 'reusable_color' ])
                                           ?       get_option('_maxboxy_options')[ 'reusable_color' ] : '';

            $floatany_panel_color_default  = ! empty($floatany_panel_color)  ? $floatany_panel_color  : '#4b4b4b'; // plugin's default, from the css file
            $injectany_panel_color_default = ! empty($injectany_panel_color) ? $injectany_panel_color : '#4b4b4b'; // plugin's default, from the css file
            $reusable_panel_color_default  = ! empty($reusable_panel_color)  ? $reusable_panel_color  : '#4b4b4b'; // plugin's default, from the css file

            // panel color (if !== default)
            $panel_color                    =   get_post_type($get_id) === 'float_any'  && $basics['panel_popup_color'] !== $floatany_panel_color_default  ? 'color:' .$basics['panel_popup_color'] .';' : '';
            $panel_color                   .=   get_post_type($get_id) === 'inject_any' && $basics['panel_popup_color'] !== $injectany_panel_color_default ? 'color:' .$basics['panel_popup_color'] .';' : '';
            $panel_color                   .=   get_post_type($get_id) === 'wp_block'   && $basics['panel_popup_color'] !== $reusable_panel_color_default  ? 'color:' .$basics['panel_popup_color'] .';' : '';

            // shut bg & color - get default value from the settings
            $floatany_shut_bg               = isset(get_option('_maxboxy_options')[ 'floatany_shut_bg' ])
                                            ?       get_option('_maxboxy_options')[ 'floatany_shut_bg' ] : '';

            $injectany_shut_bg              = isset(get_option('_maxboxy_options')[ 'injectany_shut_bg' ])
                                            ?       get_option('_maxboxy_options')[ 'injectany_shut_bg' ] : '';

            $reusable_shut_bg               = isset(get_option('_maxboxy_options')[ 'reusable_shut_bg' ])
                                            ?       get_option('_maxboxy_options')[ 'reusable_shut_bg' ] : '';

            $floatany_shut_bg_default       = ! empty($floatany_shut_bg)  ? $floatany_shut_bg  : '#333333';
            $injectany_shut_bg_default      = ! empty($injectany_shut_bg) ? $injectany_shut_bg : '#333333';
            $reuable_shut_bg_default        = ! empty($reusable_shut_bg)  ? $reusable_shut_bg  : '#333333';

            $shut_bg                        = get_post_type($get_id) === 'float_any'  && $basics['panel_shut_bg'] !== $floatany_shut_bg_default  ? 'background:' .$basics['panel_shut_bg'] .';' : '';
            $shut_bg                       .= get_post_type($get_id) === 'inject_any' && $basics['panel_shut_bg'] !== $injectany_shut_bg_default ? 'background:' .$basics['panel_shut_bg'] .';' : '';
            $shut_bg                       .= get_post_type($get_id) === 'wp_block'   && $basics['panel_shut_bg'] !== $reuable_shut_bg_default   ? 'background:' .$basics['panel_shut_bg'] .';' : '';

            $floatany_shut_color            = isset(get_option('_maxboxy_options')[ 'floatany_shut_color' ])
                                            ?       get_option('_maxboxy_options')[ 'floatany_shut_color' ] : '';

            $injectany_shut_color           = isset(get_option('_maxboxy_options')[ 'injectany_shut_color' ])
                                            ?       get_option('_maxboxy_options')[ 'injectany_shut_color' ] : '';

            $reusable_shut_color            = isset(get_option('_maxboxy_options')[ 'reusable_shut_color' ])
                                            ?       get_option('_maxboxy_options')[ 'reusable_shut_color' ] : '';

            $floatany_shut_color_default    = ! empty($floatany_shut_color)  ? $floatany_shut_color  : '#ffffff';
            $injectany_shut_color_default   = ! empty($injectany_shut_color) ? $injectany_shut_color : '#ffffff';
            $reusable_shut_color_default    = ! empty($reusable_shut_color)  ? $reusable_shut_color  : '#ffffff';

            $shut_color                     = get_post_type($get_id) === 'float_any'  && $basics['panel_shut_color'] !== $floatany_shut_color_default  ? 'color:' .$basics['panel_shut_color'] .';' : '';
            $shut_color                    .= get_post_type($get_id) === 'inject_any' && $basics['panel_shut_color'] !== $injectany_shut_color_default ? 'color:' .$basics['panel_shut_color'] .';' : '';
            $shut_color                    .= get_post_type($get_id) === 'wp_block'   && $basics['panel_shut_color'] !== $reusable_shut_color_default  ? 'color:' .$basics['panel_shut_color'] .';' : '';

            $_escaped_panel_shut_style      = ! empty($shut_bg) || ! empty($shut_color)
                                            ? ' style="' .esc_attr($shut_bg) .esc_attr($shut_color) .'"' : '';

             /*
              * Sizes
              */
            $width                          = ! empty($basics[ 'size_1' ][ 'width' ]) ? $basics[ 'size_1' ][ 'width' ] : '';
            $height                         = isset($basics[ 'height_100' ]) && $basics[ 'height_100' ] === 'yes' ? true : false;
            $unit                           = ! empty($basics[ 'size_1' ][ 'unit' ]) ? $basics[ 'size_1' ][ 'unit' ] : '';
            $_escaped_width_data            = ! empty($width)  ? ' data-panel-width="'  .esc_attr($width)  .esc_attr($unit) .'"' : '';
            $_escaped_height_data           = $height === true ? ' data-panel-height="100%"'  : '';

            $width_large                    = ! empty($basics[ 'size_2' ][ 'width' ])  ?  $basics[ 'size_2' ][ 'width' ] : '';
            $height_large                   = isset($basics[ 'height_100_2' ]) && $basics[ 'height_100_2' ] === 'yes' ? '100%' : false;
            $height_large                   = $height === true && isset($basics[ 'height_100_2' ]) && $basics[ 'height_100_2' ] === 'no' ? 'auto' : $height_large;
            $unit_large                     = ! empty($basics[ 'size_2' ][ 'unit' ])   ?  $basics[ 'size_2' ][ 'unit' ] : '';
            $_escaped_width_data_large      = ! empty($width_large)   ? ' data-panel-large-width="'.esc_attr($width_large).esc_attr($unit_large) .'"' : '';
            $_escaped_height_data_large     = $height_large !== false ? ' data-panel-large-height="' .esc_attr($height_large) .'"' :'';

            $_escaped_panel_data            = $_escaped_width_data .$_escaped_height_data .$_escaped_width_data_large .$_escaped_height_data_large;
            $panel_size_class               = ! empty($_escaped_panel_data) ? ' with-size' : '';

            // if size isn't 100 panel alignment is relevant for the injectany box
            $injectany_align                = isset($basics[ 'box_align' ]) ? ' align-' .$basics[ 'box_align' ] : '';

            // panel's padding
            $padding_unit                   = isset($basics[ 'panel_padding' ][ 'unit' ]) ? $basics[ 'panel_padding' ][ 'unit' ] : '';
            $default_pad                    = '1.5em';

            $get_pad_top                    = isset($basics[ 'panel_padding' ][ 'top' ]) && is_numeric($basics[ 'panel_padding' ][ 'top' ]) ? abs($basics[ 'panel_padding' ][ 'top' ])   : '';

            $panel_padding                  = $get_pad_top .$padding_unit !== $default_pad    ? 'padding-top: ' .$get_pad_top .$padding_unit .';'       : '';


            $get_pad_left                   = isset($basics[ 'panel_padding' ][ 'left' ]) && is_numeric($basics[ 'panel_padding' ][ 'left' ]) ? abs($basics[ 'panel_padding' ][ 'left' ])  : '';
            $panel_padding                 .= $get_pad_left .$padding_unit !== $default_pad   ? 'padding-left: ' .$get_pad_left .$padding_unit .';'     : '';

            $get_pad_right                  = isset($basics[ 'panel_padding' ][ 'right' ]) && is_numeric($basics[ 'panel_padding' ][ 'right' ]) ? abs($basics[ 'panel_padding' ][ 'right' ]) : '';
            $panel_padding                 .= $get_pad_right .$padding_unit !== $default_pad  ? 'padding-right: ' .$get_pad_right .$padding_unit .';'   : '';

            $get_pad_bottom                 = isset($basics[ 'panel_padding' ][ 'bottom' ]) && is_numeric($basics[ 'panel_padding' ][ 'bottom' ]) ? abs($basics[ 'panel_padding' ][ 'bottom' ]): '';
            $panel_padding                 .= $get_pad_bottom .$padding_unit !== $default_pad ? 'padding-bottom: ' .$get_pad_bottom .$padding_unit .';' : '';

            // panel's border
            $border                         = isset($basics[ 'panel_border' ]) ?   $basics[ 'panel_border' ] : '';
            $border_style                   = isset($border[ 'style' ])        ?   ' ' .$border[ 'style' ] : '';
            $border_color                   = ! empty($border[ 'color' ])      ?   ' ' .$border[ 'color' ] : '';
            $border_top                     = isset($border[ 'top' ]) && is_numeric($border[ 'top' ])       ? 'border-top:' .abs($border[ 'top' ])       .'px' .$border_style .$border_color .';' : '';
            $border_right                   = isset($border[ 'right' ]) && is_numeric($border[ 'right' ])   ? 'border-right:' .abs($border[ 'right' ])   .'px' .$border_style .$border_color .';' : '';
            $border_bottom                  = isset($border[ 'bottom' ]) && is_numeric($border[ 'bottom' ]) ? 'border-bottom:' .abs($border[ 'bottom' ]) .'px' .$border_style .$border_color .';' : '';
            $border_left                    = isset($border[ 'left' ]) && is_numeric($border[ 'left' ])     ? 'border-left:' .abs($border[ 'left' ])     .'px' .$border_style .$border_color .';' : '';

            $panel_border                   = $border_top .$border_right .$border_bottom .$border_left;

            // panel's border
            $border_radius                  = isset($basics[ 'panel_border_radius' ]) ?   $basics[ 'panel_border_radius' ] : '';
            $border_radius_unit             = isset($border_radius[ 'unit' ])        ?   $border_radius[ 'unit' ] : '';
            $border_radius_top_left         = isset($border_radius[ 'top' ]) && is_numeric($border_radius[ 'top' ])       ? 'border-top-left-radius:' .abs($border_radius[ 'top' ])        .$border_radius_unit .';' : '';
            $border_radius_top_right        = isset($border_radius[ 'right' ]) && is_numeric($border_radius[ 'right' ])   ? 'border-top-right-radius:' .abs($border_radius[ 'right' ])     .$border_radius_unit .';' : '';
            $border_radius_bottom_left      = isset($border_radius[ 'bottom' ]) && is_numeric($border_radius[ 'bottom' ]) ? 'border-bottom-left-radius:' .abs($border_radius[ 'bottom' ])  .$border_radius_unit .';' : '';
            $border_radius_bottom_right     = isset($border_radius[ 'left' ]) && is_numeric($border_radius[ 'left' ])     ? 'border-bottom-right-radius:' .abs($border_radius[ 'left' ])   .$border_radius_unit .';' : ''; // using spacing field, that's why 'left' is usedhere

            $panel_border_radius            = $border_radius_top_left .$border_radius_top_right .$border_radius_bottom_left .$border_radius_bottom_right;


             /*
              * Toggler
              */
             $unset_toggler                  = ! empty($basics[ 'unset_toggler' ]) ? $basics[ 'unset_toggler' ] : 'no';

            // Sets wheather to fully or partially unset the toggler/closer button.
            $unset_toggler_class            = $unset_toggler === 'all'    ?   ' default-toggler-closer-unset' : '';
            $unset_toggler_class            = $unset_toggler === 'closer' ?   ' default-closer-unset' : $unset_toggler_class;

            $reverse                        = $unset_toggler !== true && ! empty($basics[ 'reverse_direction' ])  ?   '-rev' : '';
            $direction                      = $unset_toggler !== true && ! empty($basics[ 'direction' ]) && isset($basics['reverse_direction']) ? ' dir-' .$basics['direction'] .$reverse : '';
            $closer_align                   = $unset_toggler !== true && ! empty($basics[ 'closer_align' ]) ? ' align-' .$basics[ 'closer_align' ] : '';
            $closer_size                    = $unset_toggler !== true && isset($basics[ 'closer_size' ]) && $basics[ 'closer_size' ] !== 'normal' ? ' ' .$basics[ 'closer_size' ] : '';


            // Closer/toggler styling

            $get_closer_style               = ! empty($basics[ 'closer_styling' ]) ? $basics[ 'closer_styling' ]  : '';
            // convert the array to a string
            $check_closer_style             = is_array($get_closer_style)   ?   implode(' ', $get_closer_style)   : '';
            // prepend a space before the output, so it adds correctly to the existing classes ...$panel_type set on the Basic plugin
            $toggler_styling                = $unset_toggler === 'no' && ! empty($check_closer_style)
                                            ? ' ' .$check_closer_style : '';

            // trigger animation
            //$trigger_anim                   =   $unset_toggler === 'no' && ! empty( $basics[ 'trigger_anim' ] ) && ( $panel_type === 'toggler' || $panel_type === 'igniter' ) ?  ' trigger-anim-rotate'   : '';
            $trigger_anim = '';


            /*
             * Pick the styling from diffrent vars
             */
            $_escaped_panel_content_style   = ! empty($panel_padding) || ! empty($panel_border) || ! empty($panel_border_raus) || ! empty($set_panel_bg) || ! empty($panel_color)
                                            ? ' style="' .esc_attr($panel_padding) .esc_attr($panel_border) .esc_attr($panel_border_radius) .esc_attr($set_panel_bg) .esc_attr($panel_color) .'"' : '';

            // gather arguments
            $args = array(
                'type'                  => $panel_type,
                // classes:
                'strain'                => $panel_strain,
                'style'                 => $showing_style,
                'roles'                 => $panel_roles,
                'rotator_repeat'        => $rotator_repeat,
                'mark_hoverout_closing' => $mark_hoverout_close,
                'shut_class'            => $shut_class,
                //'trigger_anim'        => $trigger_anim,
                'add_classes'           => $add_classes,
                'panel_size'            => $panel_size_class,
                'direction'             => $direction,
                'closer_align'          => $closer_align,
                'closer_size'           => $closer_size,
                'toggler_styling'       => $toggler_styling,
                'toggler_start_class'   => $toggler_start_class,
                'unset_toggler_class'   => $unset_toggler_class,
                'injectany_align'       => $injectany_align,
                'sticky'                => $sticky,
                // data:
                'rotator_time'          => $_escaped_rotator_time_data,
                'wrap_style'            => $_escaped_panel_wrap_style,
                'panel_style'           => $_escaped_panel_data,
                'content_style'         => $_escaped_panel_content_style,
                'shut_style'            => $_escaped_panel_shut_style,
                'toggler_data'          => $_escaped_toggler_data,
                // else:
                'unset_toggler'         => $unset_toggler,
                'toggler_start_title'   => $toggler_start_title,
                'use_overlay'           => $use_overlay,
            );

            return $args;

        }


        /**
         * Conversions options, i.e. goals, tracking.
         *
         * @param int $get_id Receive the id of the panel.
         * 
         * @return array.
         */
        public static function conversions( $get_id )
        {

            $settings                       = get_post_meta($get_id, '_mb_maxboxy_conversion', true);
            $conversions                    = isset($settings[ 'box_options' ]) ?  $settings[ 'box_options' ]   : '';

            $goal                           = ! empty($conversions[ 'goal' ])                   ? $conversions[ 'goal' ]  : '';
            $goal_submit_check              = ! empty($conversions[ 'goal_form_submit' ])       ? $conversions[ 'goal_form_submit' ]  : '';
            $goal_click_target_attr         = ! empty($conversions[ 'goal_click_target_attr' ]) ? $conversions[ 'goal_click_target_attr' ]  : '';
            $set_goal_click_target_attr     = $goal_click_target_attr  === 'id'  ? '#' : '.'; // ID or class
            $goal_attr_value                = ! empty($conversions[ 'goal_attr_value' ])        ?   sanitize_html_class($conversions[ 'goal_attr_value' ])  : ''; // text field in a group - needs sanitization here

            $_escaped_goal_click_target     = $goal === 'click'  && ! empty($goal_attr_value)   ?   ' data-goal-target="'  .esc_attr($set_goal_click_target_attr) .esc_attr($goal_attr_value)  .'"'  : '';

            $_escaped_goal_form_has_class   = $goal === 'submit' && $goal_submit_check === 'form_has_class'   && ! empty($goal_attr_value) ? ' data-goal-form-hasclass="'   .esc_attr($goal_attr_value).'"' : '';
            $_escaped_goal_panel_find_class = $goal === 'submit' && $goal_submit_check === 'panel_find_class' && ! empty($goal_attr_value) ? ' data-goal-panel-findclass="' .esc_attr($goal_attr_value).'"' : '';

            $_escaped_goals                 = $_escaped_goal_click_target .$_escaped_goal_form_has_class .$_escaped_goal_panel_find_class;

            $goalset                        = ! empty($goa) ? ' goalset goal-' .$goal : '';

            $goal_after_banish              = ! empty($goa) && ! empty($conversions[ 'goal_after_banish' ]) ? ' goal-after-banish' : '';

            $track_loggedin_users           = ! empty($conversions[ 'track_loggedin_users' ]) ? true : false;

            $args = array(
                'goalset'         => $goalset,
                 'banish'         => $goal_after_banish,
                 'goal_data'      => $_escaped_goals,
                 'track_loggedin' => $track_loggedin_users,
            );

            return $args;

        }


    } // end class

} // end class exists check
