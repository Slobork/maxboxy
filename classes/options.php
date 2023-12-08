<?php
// phpcs:ignore
/**
 * Description: Getting Maxboxy options form the Mataboxes
 */

if (! defined('ABSPATH')) { 
    exit; 
}

if (! class_exists('Max_Boxy_Options')) {

    // phpcs:ignore
    class Max_Boxy_Options
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
             $test_mode       = get_post_meta($get_id, 'test_mode', true);

             // set $global_loading as boolean
             $global_loading = $global_loading === 'disabled' ? false : true;

            if (isset($location)) {

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

                $inject_place = $location === 'top'    ? ' place-top'    : 'head';
                $inject_place = $location === 'bottom' ? ' place-bottom' : $inject_place;

                // location - InjectAny or FloatAny
                $place = get_post_type($get_id) === 'float_any' ? $popup_place : $inject_place;

                $args = array(
                    'global'         => $global_loading,
                    'location'       => $place,
                    'test_mode'      => $test_mode,
                );

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
                          && class_exists('Max_Boxy_Reusable_Blocks')
                          && Max_Boxy_Reusable_Blocks::enabled() === true
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

            // panel trig
            $panel_type                = isset($basics[ 'panel_type' ]) ? $basics[ 'panel_type' ]   : 'closer';
            $trig_class                = isset($basics[ 'panel_type' ]) ? ' type-' .$basics[ 'panel_type' ]  : ' type-toggler';

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
            if (Max_Boxy_Track::enabled() !== true && $panel_type === 'closer' && is_array($get_roles) && ($key = array_search('role-banish', $get_roles)) !== false) {
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
            $panel_strain                   = get_post_type($get_id) === 'wp_block' && class_exists('Max_Boxy_Reusable_Blocks') && Max_Boxy_Reusable_Blocks::enabled() === true
                                            ? ' is-reusable-block injectany' .$injectany_preload : $panel_strain;
            $panel_strain                   = get_post_type($get_id) === 'inject_any' ? ' injectany' .$injectany_preload : $panel_strain;

            /*
             * Toggler
             */
            $unset_toggler                  = ! empty($basics[ 'unset_toggler' ]) ? $basics[ 'unset_toggler' ] : 'no';

            // Sets wheather to fully or partially unset the toggler/closer button.
            $unset_toggler_class            = $unset_toggler === 'all'    ?   ' hide-default-toggler-closer' : '';
            $unset_toggler_class            = $unset_toggler === 'closer' ?   ' hide-default-closer' : $unset_toggler_class;

            // toggler data
            $basics[ 'button_open_svg' ]    = isset($basics[ 'button_open_svg' ])  ? $basics[ 'button_open_svg' ] : '';
            $basics[ 'button_close_svg' ]   = isset($basics[ 'button_close_svg' ]) ? $basics[ 'button_close_svg' ] : '';
            $open_button_data               = empty($basics[ 'button_open_img' ]['url'])  && $basics[ 'button_open_svg' ]  === 'no' && $panel_type === 'toggler' && ! empty($basics[ 'button_open_icon' ]) ? ' data-button-open="' .esc_attr($basics[ 'button_open_icon' ]) .'"' : ''; // ' data-button-open="iks-plus"';
            $close_button_data              = empty($basics[ 'button_close_img' ]['url']) && $basics[ 'button_close_svg' ] === 'no' && ! empty($basics[ 'button_close_icon' ]) ? ' data-button-close="' .esc_attr($basics[ 'button_close_icon' ]) .'"' : ''; // ' data-button-close="iks"';
            $_escaped_toggler_data          = $open_button_data .$close_button_data;
            
            $trigger_icon_classes           = ! empty($open_button_data) ? ' has-icon-open' : '';
            $trigger_icon_classes          .= $unset_toggler === 'no' && ! empty($close_button_data) ? ' has-icon-close' : '';

            $icon_open_class                = empty($basics[ 'button_open_img' ]['url'])  && $basics[ 'button_open_svg' ]  === 'no' && $panel_type === 'toggler' && ! empty($basics[ 'button_open_icon' ]) ? ' ' .$basics[ 'button_open_icon' ] : '';
            $icon_close_class               = empty($basics[ 'button_close_img' ]['url']) && $basics[ 'button_close_svg' ] === 'no' && ! empty($basics[ 'button_close_icon' ]) ? ' ' .$basics[ 'button_close_icon' ] : '';

            $toggler_start_title            = $igniter === true ? esc_html__('Open', 'maxboxy') : esc_html__('Close', 'maxboxy');
            $toggler_start_class            = $igniter === true ? $icon_open_class : $icon_close_class;

            // svg
            // compare two svg (if returns 0, the strings are the same)
            $open_close_svg_the_same        = $panel_type === 'toggler' && $basics[ 'button_open_svg' ] !== 'no' && $basics[ 'button_close_svg' ] !== 'no' ? strcmp($basics[ 'button_open_svg' ], $basics[ 'button_close_svg' ]) : 1;
            $open_svg_class                 = empty($basics[ 'button_open_img' ]['url']) && $basics[ 'button_open_svg' ] !== 'no' && $panel_type === 'toggler' ? ' has-svg-open' : '';
            $the_same_svg                   = $open_close_svg_the_same === 0 ? ' close-svg-thesame' : '';
            $close_svg_class                = empty($basics[ 'button_close_img' ]['url']) && $basics[ 'button_close_svg' ] !== 'no' && $open_close_svg_the_same !== 0 ? ' has-svg-close' : $the_same_svg;
            $toggler_svg_classes            = $open_svg_class .$close_svg_class;


            /**
             * Svg icons source
             *
             * Svg icons used from https://www.reshot.com/free-svg-icons/item/essential-minimal-icons-NSKQW8ACT5/
             *
             * @license Reshot Free License
             *
             * @link https://www.reshot.com/license/
             */
            $svg_open = '';
            if (! empty($basics[ 'button_open_svg' ]) ) {
                switch ( $basics[ 'button_open_svg' ] ) {
                case $basics[ 'button_open_svg' ] === 'svg-basket':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Shopping"><path d="M234.825 253.442a9.445 9.445 0 0 0-9.448 9.448v63.44a9.448 9.448 0 0 0 18.896 0v-63.44a9.445 9.445 0 0 0-9.448-9.448zM196.096 253.442a9.451 9.451 0 0 0-9.453 9.448v63.44a9.453 9.453 0 0 0 18.905 0v-63.44a9.448 9.448 0 0 0-9.452-9.448zM273.558 253.442a9.448 9.448 0 0 0-9.452 9.448v63.44a9.45 9.45 0 0 0 18.9 0v-63.44a9.45 9.45 0 0 0-9.447-9.448zM312.283 253.442a9.445 9.445 0 0 0-9.448 9.448v63.44a9.448 9.448 0 1 0 18.896 0v-63.44a9.444 9.444 0 0 0-9.448-9.448z"/><path d="M255.998 73.82c-100.613 0-182.175 81.572-182.175 182.171 0 100.617 81.562 182.189 182.175 182.189 100.608 0 182.18-81.572 182.18-182.189 0-100.6-81.572-182.171-182.18-182.171zm101.285 174.63-14.45 85.465a15.11 15.11 0 0 1-15.108 15.1H180.658a15.104 15.104 0 0 1-15.108-15.1l-14.458-85.465a24.454 24.454 0 0 1 15.293-43.611h1.586l36.106-52.805a9.45 9.45 0 0 1 15.6 10.67l-28.81 42.134h126.646l-28.807-42.134a9.448 9.448 0 1 1 15.601-10.662l36.11 52.796h1.578a24.454 24.454 0 0 1 15.288 43.612z"/></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-book':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Book"><path d="M197.526 193.536h103.421v15.75H197.526zM197.526 219.789h103.421v15.75H197.526z"/><path d="M256 73.82A182.18 182.18 0 0 0 73.82 256c0 100.6 81.567 182.18 182.18 182.18 100.608 0 182.18-81.58 182.18-182.18A182.183 182.183 0 0 0 256 73.82zm71.9 288.914H168.299a2.8 2.8 0 0 1-2.8-2.8v-203.35a2.8 2.8 0 0 1 2.8-2.8H327.9a1.4 1.4 0 0 1 1.4 1.4v206.15a1.4 1.4 0 0 1-1.4 1.4zm26.599-183.744v171.14a12.602 12.602 0 0 1-12.599 12.605h-3.406a24.99 24.99 0 0 0 3.406-12.604V166.387a25.088 25.088 0 0 0-3.406-12.604h3.406a12.602 12.602 0 0 1 12.6 12.604z"/></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-call':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M255.995 73.82A182.18 182.18 0 1 0 438.185 256a182.183 182.183 0 0 0-182.19-182.18zm93.956 251.72c-7.823 12.093-14.617 24.635-47.285 25.382-33.399.035-67.931-27.624-93.085-55.213-25.111-24.433-48.35-55.986-48.314-86.572.747-32.678 13.298-39.48 25.383-47.285a12.372 12.372 0 0 1 12.384 2.232c5.757 5.001 31.007 30.26 31.007 30.26s8.974 7.49 2.778 15.778c-4.922 6.608-16.287 18.773-17.78 20.372l4.271 8.033c5.704 9.404 16.576 25.928 32.854 39.323 10.986 9.017 29.558 18.923 29.558 18.914 2.672-2.479 13.763-12.761 19.95-17.385 8.298-6.205 15.786 2.778 15.786 2.778s25.26 25.26 30.27 31.024a12.361 12.361 0 0 1 2.223 12.358z" data-name="Call"/></svg>';
                    break;
                // ...cookie sign from the @link https://www.reshot.com/free-svg-icons/item/cookie-J8KY6M7DSA/
                case $basics[ 'button_open_svg' ] === 'svg-cookie':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><g data-name="47-Cookie"><path d="M30.15 8.53a1 1 0 0 0-.89-.53H26V6a1 1 0 0 0-1-1h-2V2.26a1 1 0 0 0-.6-.92A16 16 0 1 0 32 16a16 16 0 0 0-1.85-7.47zM16 30a14 14 0 1 1 5-27.07V5a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h2.65A13.81 13.81 0 0 1 30 16a14 14 0 0 1-14 14z"/><path d="M14 9a3 3 0 1 0-3 3 3 3 0 0 0 3-3zm-3 1a1 1 0 1 1 1-1 1 1 0 0 1-1 1zM15 20a3 3 0 1 0 3 3 3 3 0 0 0-3-3zm0 4a1 1 0 1 1 1-1 1 1 0 0 1-1 1z"/><circle cx="25" cy="19" r="2"/><circle cx="7" cy="18" r="2"/><path d="M19 10a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 6a2 2 0 1 1 2-2 2 2 0 0 1-2 2z"/></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-chat':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.175 182.175 0 0 0-182.172 182.18c0 100.6 81.563 182.17 182.172 182.17s182.17-81.57 182.17-182.17c.001-100.618-81.561-182.18-182.17-182.18zm0 284.546a130.638 130.638 0 0 1-58.992-13.825c-4.65 3.717-25.674 19.15-46.53 11.935a45.77 45.77 0 0 0 14.608-35.56c-16.673-17.666-26.684-40.272-26.684-64.916 0-56.54 52.647-102.375 117.598-102.375s117.606 45.835 117.606 102.375c0 56.531-52.654 102.365-117.606 102.365z" data-name="Chat 02"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-chats':
                    $svg_open = '<svg class="trig-svg-open" <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825c-100.608 0-182.17 81.562-182.17 182.18 0 100.6 81.562 182.17 182.17 182.17s182.17-81.57 182.17-182.17c0-100.618-81.562-182.18-182.17-182.18zM182.506 330.36l-45.15 12.094 12.358-46.125a99.123 99.123 0 1 1 32.792 34.031zm192.235 31.29-41.072-11.005a90.234 90.234 0 0 1-83.654 6.645 111.616 111.616 0 0 0 87.16-156.796A90.134 90.134 0 0 1 363.5 319.69z" data-name="Chat 01"/></svg>';
                    break;
                // for the plus sign used @link https://www.svgrepo.com/svg/507398/plus-circle under the MIT @license
                case $basics[ 'button_open_svg' ] === 'svg-plus':
                    $svg_open = '<svg class="trig-svg-open" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M13 9C13 8.44772 12.5523 8 12 8C11.4477 8 11 8.44772 11 9V11H9C8.44772 11 8 11.4477 8 12C8 12.5523 8.44772 13 9 13H11V15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15V13H15C15.5523 13 16 12.5523 16 12C16 11.4477 15.5523 11 15 11H13V9ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" fill="#323232"></path></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-eye':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="View"><path d="M255.998 73.825a182.179 182.179 0 0 0-182.175 182.18c0 100.608 81.567 182.17 182.175 182.17s182.18-81.562 182.18-182.17a182.183 182.183 0 0 0-182.18-182.18zm122.45 187.989c-2.225 2.848-55.222 69.68-122.705 69.68-67.49 0-120.485-66.832-122.709-69.68l-4.547-5.819 4.547-5.817c2.224-2.84 55.218-69.68 122.71-69.68 67.482 0 120.48 66.84 122.703 69.68l4.544 5.818z"/><path d="M255.743 199.404c-47.46 0-88.884 41.08-102.823 56.592 13.931 15.495 55.358 56.592 102.823 56.592 47.452 0 88.88-41.08 102.823-56.593-13.93-15.494-55.37-56.591-102.823-56.591zm-.004 98.858A42.258 42.258 0 1 1 298 255.996a42.265 42.265 0 0 1-42.262 42.266z"/></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-flag':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zm-67.817 276.082a6.3 6.3 0 0 1-12.599 0V178.885a6.3 6.3 0 1 1 12.6 0zM343.57 276.75c-50.05-46.212-92.052 46.204-142.106 0V175.958c50.062 46.204 92.056-46.213 142.106 0z" data-name="Flag"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-home':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm76.636 161.579h-12.037v91.503a18.908 18.908 0 0 1-18.896 18.904h-26.78v-53.56a6.299 6.299 0 0 0-6.297-6.294H232.4a6.3 6.3 0 0 0-6.302 6.294v53.56h-26.771a18.91 18.91 0 0 1-18.906-18.904v-91.503h-11.97a7.879 7.879 0 0 1-5.071-13.905l82.055-69.039a7.89 7.89 0 0 1 10.142 0l81.479 68.547a7.88 7.88 0 0 1-4.421 14.396z" data-name="Home"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-info':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-layer':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M255.993 73.825c-100.608 0-182.17 81.562-182.17 182.18a182.173 182.173 0 0 0 182.17 182.17c100.613 0 182.185-81.562 182.185-182.17a182.18 182.18 0 0 0-182.185-182.18zm102.942 245.074-98.74 48.938a9.426 9.426 0 0 1-8.39 0l-98.736-48.938a9.447 9.447 0 1 1 8.394-16.928l94.54 46.855 94.544-46.855a9.446 9.446 0 1 1 8.389 16.928zm0-35.965-98.74 48.947a9.504 9.504 0 0 1-8.39 0l-98.736-48.929a9.455 9.455 0 0 1 8.394-16.945l94.54 46.854 94.544-46.854a9.446 9.446 0 1 1 8.389 16.927zm0-35.947-98.74 48.929a9.483 9.483 0 0 1-4.193.985 9.626 9.626 0 0 1-4.196-.976l-98.737-48.938a9.451 9.451 0 1 1 8.394-16.937l94.54 46.855 94.544-46.855a9.45 9.45 0 1 1 8.389 16.937zm0-37.74-98.74 48.928a9.35 9.35 0 0 1-8.39 0l-98.736-48.928a9.446 9.446 0 0 1 0-16.927l98.741-48.947a9.457 9.457 0 0 1 8.393 0l98.737 48.947a9.444 9.444 0 0 1-.004 16.927z" data-name="Layer"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-location':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.175 182.175 0 1 0 182.18 182.18A182.177 182.177 0 0 0 256 73.825zm78.715 116.007-69.143 158.555a6.292 6.292 0 0 1-5.775 3.77c-.132 0-.264 0-.404-.01a6.3 6.3 0 0 1-5.643-4.525l-19.248-65.874-65.874-19.248a6.3 6.3 0 0 1-.747-11.822l158.546-69.143a6.298 6.298 0 0 1 8.288 8.297z" data-name="Location"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-mail':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm-93.061 115.972h186.127l.008.03L256 247.865l-93.07-58.04zm202.195 122.598a19.522 19.522 0 0 1-19.52 19.52H166.378a19.525 19.525 0 0 1-19.52-19.52V209.317a19.926 19.926 0 0 1 .308-3.34l102.998 64.23c.132.08.264.132.396.211.132.07.272.14.413.211a10.967 10.967 0 0 0 2.242.87c.079.018.157.044.236.061a11.318 11.318 0 0 0 2.541.317h.017a11.35 11.35 0 0 0 2.544-.317c.075-.017.154-.043.234-.06a11.582 11.582 0 0 0 2.25-.87c.132-.07.272-.14.408-.212.128-.079.268-.132.392-.211l102.99-64.23a19.025 19.025 0 0 1 .307 3.34v103.078z" data-name="Mail"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-pin':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82c-100.617 0-182.18 81.571-182.18 182.171C73.82 356.6 155.383 438.18 256 438.18c100.608 0 182.18-81.57 182.18-182.188 0-100.608-81.572-182.17-182.18-182.17zm-87.346 269.13 43.137-69.504a135.887 135.887 0 0 0 12.27 14.098 137.293 137.293 0 0 0 14.07 12.288zm170.72-113.58c-4.598 4.587-12.376 5.044-21.016 2.1l-23.114 23.106c11.619 18.87 14.387 37.24 5.317 46.318-3.666 3.665-8.869 5.406-14.994 5.406-11.294 0-25.77-5.915-39.788-16.506a23.267 23.267 0 0 1-.933-.721 104.315 104.315 0 0 1-2.988-2.382c-.395-.325-.782-.641-1.177-.975-.94-.8-1.88-1.627-2.821-2.47a55.09 55.09 0 0 1-1.108-.994 117.656 117.656 0 0 1-7.892-7.936c-.818-.905-1.592-1.82-2.365-2.724-.43-.502-.87-.994-1.283-1.503a110.976 110.976 0 0 1-2.909-3.657c-.114-.15-.237-.308-.352-.457-16.436-21.682-21.683-44.49-11.232-54.932 3.674-3.673 8.859-5.405 14.994-5.405 9.035 0 20.092 3.78 31.333 10.696l23.097-23.088c-2.943-8.64-2.486-16.427 2.101-21.015 8.605-8.604 28.372-2.795 44.148 12.981s21.585 35.544 12.981 44.157z" data-name="Pin"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-play':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.182 182.182 0 0 0 256 73.82zm67.825 192.217L218.7 326.734a10.376 10.376 0 0 1-15.566-8.99V196.356a10.38 10.38 0 0 1 15.575-8.99l105.125 60.696a10.376 10.376 0 0 1-.009 17.974z" data-name="Play"/></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-settings':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Setting Cog"><path d="M256 229.123a34.247 34.247 0 1 1-34.251 34.251A34.246 34.246 0 0 1 256 229.123z"/><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.182 182.182 0 0 0 256 73.82zm95.73 208.653-25.875 1.67a72.5 72.5 0 0 1-5.765 13.94l17.112 19.485-27.009 27.017-19.486-17.12a71.936 71.936 0 0 1-13.939 5.765l-1.67 25.875h-38.205l-1.68-25.875a72.325 72.325 0 0 1-13.93-5.765l-19.476 17.12-27.01-27.026 17.104-19.477a71.904 71.904 0 0 1-5.756-13.939l-25.884-1.678v-38.199l25.884-1.67a72.478 72.478 0 0 1 5.756-13.939l-17.103-19.467 27.009-27.027 19.476 17.113a72.14 72.14 0 0 1 13.94-5.757l1.67-25.884H275.1l1.67 25.884a71.907 71.907 0 0 1 13.939 5.757l19.486-17.113 27.009 27.027-17.113 19.468a72.147 72.147 0 0 1 5.765 13.939l25.875 1.67z"/></g></svg>';
                    break;
                case $basics[ 'button_open_svg' ] === 'svg-share':
                    $svg_open = '<svg class="trig-svg-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zM235.732 256a31.94 31.94 0 0 1-1.898 10.81l50.853 24.46a32.649 32.649 0 1 1-7.989 16.594l-54.131-26.032a32.01 32.01 0 1 1 0-51.663l54.14-26.033a30.755 30.755 0 0 1-.44-4.957 32.105 32.105 0 1 1 8.42 21.569l-50.854 24.45a31.852 31.852 0 0 1 1.9 10.802z" data-name="Share"/></svg>';
                    break;
                }
            }

            $svg_close = '';
            if (! empty($basics[ 'button_close_svg' ]) ) {
                switch ( $basics[ 'button_close_svg' ] ) {
                case $basics[ 'button_close_svg' ] === 'svg-basket':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Shopping"><path d="M234.825 253.442a9.445 9.445 0 0 0-9.448 9.448v63.44a9.448 9.448 0 0 0 18.896 0v-63.44a9.445 9.445 0 0 0-9.448-9.448zM196.096 253.442a9.451 9.451 0 0 0-9.453 9.448v63.44a9.453 9.453 0 0 0 18.905 0v-63.44a9.448 9.448 0 0 0-9.452-9.448zM273.558 253.442a9.448 9.448 0 0 0-9.452 9.448v63.44a9.45 9.45 0 0 0 18.9 0v-63.44a9.45 9.45 0 0 0-9.447-9.448zM312.283 253.442a9.445 9.445 0 0 0-9.448 9.448v63.44a9.448 9.448 0 1 0 18.896 0v-63.44a9.444 9.444 0 0 0-9.448-9.448z"/><path d="M255.998 73.82c-100.613 0-182.175 81.572-182.175 182.171 0 100.617 81.562 182.189 182.175 182.189 100.608 0 182.18-81.572 182.18-182.189 0-100.6-81.572-182.171-182.18-182.171zm101.285 174.63-14.45 85.465a15.11 15.11 0 0 1-15.108 15.1H180.658a15.104 15.104 0 0 1-15.108-15.1l-14.458-85.465a24.454 24.454 0 0 1 15.293-43.611h1.586l36.106-52.805a9.45 9.45 0 0 1 15.6 10.67l-28.81 42.134h126.646l-28.807-42.134a9.448 9.448 0 1 1 15.601-10.662l36.11 52.796h1.578a24.454 24.454 0 0 1 15.288 43.612z"/></g></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-book':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Book"><path d="M197.526 193.536h103.421v15.75H197.526zM197.526 219.789h103.421v15.75H197.526z"/><path d="M256 73.82A182.18 182.18 0 0 0 73.82 256c0 100.6 81.567 182.18 182.18 182.18 100.608 0 182.18-81.58 182.18-182.18A182.183 182.183 0 0 0 256 73.82zm71.9 288.914H168.299a2.8 2.8 0 0 1-2.8-2.8v-203.35a2.8 2.8 0 0 1 2.8-2.8H327.9a1.4 1.4 0 0 1 1.4 1.4v206.15a1.4 1.4 0 0 1-1.4 1.4zm26.599-183.744v171.14a12.602 12.602 0 0 1-12.599 12.605h-3.406a24.99 24.99 0 0 0 3.406-12.604V166.387a25.088 25.088 0 0 0-3.406-12.604h3.406a12.602 12.602 0 0 1 12.6 12.604z"/></g></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-call':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M255.995 73.82A182.18 182.18 0 1 0 438.185 256a182.183 182.183 0 0 0-182.19-182.18zm93.956 251.72c-7.823 12.093-14.617 24.635-47.285 25.382-33.399.035-67.931-27.624-93.085-55.213-25.111-24.433-48.35-55.986-48.314-86.572.747-32.678 13.298-39.48 25.383-47.285a12.372 12.372 0 0 1 12.384 2.232c5.757 5.001 31.007 30.26 31.007 30.26s8.974 7.49 2.778 15.778c-4.922 6.608-16.287 18.773-17.78 20.372l4.271 8.033c5.704 9.404 16.576 25.928 32.854 39.323 10.986 9.017 29.558 18.923 29.558 18.914 2.672-2.479 13.763-12.761 19.95-17.385 8.298-6.205 15.786 2.778 15.786 2.778s25.26 25.26 30.27 31.024a12.361 12.361 0 0 1 2.223 12.358z" data-name="Call"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-cookie':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><g data-name="47-Cookie"><path d="M30.15 8.53a1 1 0 0 0-.89-.53H26V6a1 1 0 0 0-1-1h-2V2.26a1 1 0 0 0-.6-.92A16 16 0 1 0 32 16a16 16 0 0 0-1.85-7.47zM16 30a14 14 0 1 1 5-27.07V5a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h2.65A13.81 13.81 0 0 1 30 16a14 14 0 0 1-14 14z"/><path d="M14 9a3 3 0 1 0-3 3 3 3 0 0 0 3-3zm-3 1a1 1 0 1 1 1-1 1 1 0 0 1-1 1zM15 20a3 3 0 1 0 3 3 3 3 0 0 0-3-3zm0 4a1 1 0 1 1 1-1 1 1 0 0 1-1 1z"/><circle cx="25" cy="19" r="2"/><circle cx="7" cy="18" r="2"/><path d="M19 10a4 4 0 1 0 4 4 4 4 0 0 0-4-4zm0 6a2 2 0 1 1 2-2 2 2 0 0 1-2 2z"/></g></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-chat':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.175 182.175 0 0 0-182.172 182.18c0 100.6 81.563 182.17 182.172 182.17s182.17-81.57 182.17-182.17c.001-100.618-81.561-182.18-182.17-182.18zm0 284.546a130.638 130.638 0 0 1-58.992-13.825c-4.65 3.717-25.674 19.15-46.53 11.935a45.77 45.77 0 0 0 14.608-35.56c-16.673-17.666-26.684-40.272-26.684-64.916 0-56.54 52.647-102.375 117.598-102.375s117.606 45.835 117.606 102.375c0 56.531-52.654 102.365-117.606 102.365z" data-name="Chat 02"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-chats':
                    $svg_close = '<svg class="trig-svg-close" <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825c-100.608 0-182.17 81.562-182.17 182.18 0 100.6 81.562 182.17 182.17 182.17s182.17-81.57 182.17-182.17c0-100.618-81.562-182.18-182.17-182.18zM182.506 330.36l-45.15 12.094 12.358-46.125a99.123 99.123 0 1 1 32.792 34.031zm192.235 31.29-41.072-11.005a90.234 90.234 0 0 1-83.654 6.645 111.616 111.616 0 0 0 87.16-156.796A90.134 90.134 0 0 1 363.5 319.69z" data-name="Chat 01"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-close':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.18 182.18 0 0 0 256 73.82zm90.615 272.724a24.554 24.554 0 0 1-34.712 0l-54.664-54.667-57.142 57.146a24.544 24.544 0 0 1-34.704-34.717l57.138-57.128-53.2-53.209a24.547 24.547 0 0 1 34.712-34.717l53.196 53.208 50.717-50.72a24.547 24.547 0 0 1 34.713 34.716l-50.713 50.722 54.659 54.65a24.56 24.56 0 0 1 0 34.717z" data-name="Close"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-eye':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="View"><path d="M255.998 73.825a182.179 182.179 0 0 0-182.175 182.18c0 100.608 81.567 182.17 182.175 182.17s182.18-81.562 182.18-182.17a182.183 182.183 0 0 0-182.18-182.18zm122.45 187.989c-2.225 2.848-55.222 69.68-122.705 69.68-67.49 0-120.485-66.832-122.709-69.68l-4.547-5.819 4.547-5.817c2.224-2.84 55.218-69.68 122.71-69.68 67.482 0 120.48 66.84 122.703 69.68l4.544 5.818z"/><path d="M255.743 199.404c-47.46 0-88.884 41.08-102.823 56.592 13.931 15.495 55.358 56.592 102.823 56.592 47.452 0 88.88-41.08 102.823-56.593-13.93-15.494-55.37-56.591-102.823-56.591zm-.004 98.858A42.258 42.258 0 1 1 298 255.996a42.265 42.265 0 0 1-42.262 42.266z"/></g></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-flag':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zm-67.817 276.082a6.3 6.3 0 0 1-12.599 0V178.885a6.3 6.3 0 1 1 12.6 0zM343.57 276.75c-50.05-46.212-92.052 46.204-142.106 0V175.958c50.062 46.204 92.056-46.213 142.106 0z" data-name="Flag"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-home':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm76.636 161.579h-12.037v91.503a18.908 18.908 0 0 1-18.896 18.904h-26.78v-53.56a6.299 6.299 0 0 0-6.297-6.294H232.4a6.3 6.3 0 0 0-6.302 6.294v53.56h-26.771a18.91 18.91 0 0 1-18.906-18.904v-91.503h-11.97a7.879 7.879 0 0 1-5.071-13.905l82.055-69.039a7.89 7.89 0 0 1 10.142 0l81.479 68.547a7.88 7.88 0 0 1-4.421 14.396z" data-name="Home"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-info':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-layer':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M255.993 73.825c-100.608 0-182.17 81.562-182.17 182.18a182.173 182.173 0 0 0 182.17 182.17c100.613 0 182.185-81.562 182.185-182.17a182.18 182.18 0 0 0-182.185-182.18zm102.942 245.074-98.74 48.938a9.426 9.426 0 0 1-8.39 0l-98.736-48.938a9.447 9.447 0 1 1 8.394-16.928l94.54 46.855 94.544-46.855a9.446 9.446 0 1 1 8.389 16.928zm0-35.965-98.74 48.947a9.504 9.504 0 0 1-8.39 0l-98.736-48.929a9.455 9.455 0 0 1 8.394-16.945l94.54 46.854 94.544-46.854a9.446 9.446 0 1 1 8.389 16.927zm0-35.947-98.74 48.929a9.483 9.483 0 0 1-4.193.985 9.626 9.626 0 0 1-4.196-.976l-98.737-48.938a9.451 9.451 0 1 1 8.394-16.937l94.54 46.855 94.544-46.855a9.45 9.45 0 1 1 8.389 16.937zm0-37.74-98.74 48.928a9.35 9.35 0 0 1-8.39 0l-98.736-48.928a9.446 9.446 0 0 1 0-16.927l98.741-48.947a9.457 9.457 0 0 1 8.393 0l98.737 48.947a9.444 9.444 0 0 1-.004 16.927z" data-name="Layer"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-location':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.175 182.175 0 1 0 182.18 182.18A182.177 182.177 0 0 0 256 73.825zm78.715 116.007-69.143 158.555a6.292 6.292 0 0 1-5.775 3.77c-.132 0-.264 0-.404-.01a6.3 6.3 0 0 1-5.643-4.525l-19.248-65.874-65.874-19.248a6.3 6.3 0 0 1-.747-11.822l158.546-69.143a6.298 6.298 0 0 1 8.288 8.297z" data-name="Location"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-mail':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm-93.061 115.972h186.127l.008.03L256 247.865l-93.07-58.04zm202.195 122.598a19.522 19.522 0 0 1-19.52 19.52H166.378a19.525 19.525 0 0 1-19.52-19.52V209.317a19.926 19.926 0 0 1 .308-3.34l102.998 64.23c.132.08.264.132.396.211.132.07.272.14.413.211a10.967 10.967 0 0 0 2.242.87c.079.018.157.044.236.061a11.318 11.318 0 0 0 2.541.317h.017a11.35 11.35 0 0 0 2.544-.317c.075-.017.154-.043.234-.06a11.582 11.582 0 0 0 2.25-.87c.132-.07.272-.14.408-.212.128-.079.268-.132.392-.211l102.99-64.23a19.025 19.025 0 0 1 .307 3.34v103.078z" data-name="Mail"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-pin':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82c-100.617 0-182.18 81.571-182.18 182.171C73.82 356.6 155.383 438.18 256 438.18c100.608 0 182.18-81.57 182.18-182.188 0-100.608-81.572-182.17-182.18-182.17zm-87.346 269.13 43.137-69.504a135.887 135.887 0 0 0 12.27 14.098 137.293 137.293 0 0 0 14.07 12.288zm170.72-113.58c-4.598 4.587-12.376 5.044-21.016 2.1l-23.114 23.106c11.619 18.87 14.387 37.24 5.317 46.318-3.666 3.665-8.869 5.406-14.994 5.406-11.294 0-25.77-5.915-39.788-16.506a23.267 23.267 0 0 1-.933-.721 104.315 104.315 0 0 1-2.988-2.382c-.395-.325-.782-.641-1.177-.975-.94-.8-1.88-1.627-2.821-2.47a55.09 55.09 0 0 1-1.108-.994 117.656 117.656 0 0 1-7.892-7.936c-.818-.905-1.592-1.82-2.365-2.724-.43-.502-.87-.994-1.283-1.503a110.976 110.976 0 0 1-2.909-3.657c-.114-.15-.237-.308-.352-.457-16.436-21.682-21.683-44.49-11.232-54.932 3.674-3.673 8.859-5.405 14.994-5.405 9.035 0 20.092 3.78 31.333 10.696l23.097-23.088c-2.943-8.64-2.486-16.427 2.101-21.015 8.605-8.604 28.372-2.795 44.148 12.981s21.585 35.544 12.981 44.157z" data-name="Pin"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-play':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.182 182.182 0 0 0 256 73.82zm67.825 192.217L218.7 326.734a10.376 10.376 0 0 1-15.566-8.99V196.356a10.38 10.38 0 0 1 15.575-8.99l105.125 60.696a10.376 10.376 0 0 1-.009 17.974z" data-name="Play"/></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-settings':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Setting Cog"><path d="M256 229.123a34.247 34.247 0 1 1-34.251 34.251A34.246 34.246 0 0 1 256 229.123z"/><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.182 182.182 0 0 0 256 73.82zm95.73 208.653-25.875 1.67a72.5 72.5 0 0 1-5.765 13.94l17.112 19.485-27.009 27.017-19.486-17.12a71.936 71.936 0 0 1-13.939 5.765l-1.67 25.875h-38.205l-1.68-25.875a72.325 72.325 0 0 1-13.93-5.765l-19.476 17.12-27.01-27.026 17.104-19.477a71.904 71.904 0 0 1-5.756-13.939l-25.884-1.678v-38.199l25.884-1.67a72.478 72.478 0 0 1 5.756-13.939l-17.103-19.467 27.009-27.027 19.476 17.113a72.14 72.14 0 0 1 13.94-5.757l1.67-25.884H275.1l1.67 25.884a71.907 71.907 0 0 1 13.939 5.757l19.486-17.113 27.009 27.027-17.113 19.468a72.147 72.147 0 0 1 5.765 13.939l25.875 1.67z"/></g></svg>';
                    break;
                case $basics[ 'button_close_svg' ] === 'svg-share':
                    $svg_close = '<svg class="trig-svg-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zM235.732 256a31.94 31.94 0 0 1-1.898 10.81l50.853 24.46a32.649 32.649 0 1 1-7.989 16.594l-54.131-26.032a32.01 32.01 0 1 1 0-51.663l54.14-26.033a30.755 30.755 0 0 1-.44-4.957 32.105 32.105 0 1 1 8.42 21.569l-50.854 24.45a31.852 31.852 0 0 1 1.9 10.802z" data-name="Share"/></svg>';
                    break;
                }
            }

            $_safe_trig_svg_open            = ! empty($open_svg_class) ? $svg_open : '';
            $_safe_trig_svg_close           = ! empty($close_svg_class) && empty($the_same_svg) ? $svg_close : '';

            // img
            // compare two images (if returns 0, the strings are the same)
            $open_close_img_the_same        = $panel_type === 'toggler' &&  ! empty($basics[ 'button_open_img' ]['url']) && ! empty($basics[ 'button_close_img' ]['url']) ? strcmp($basics[ 'button_open_img' ]['url'], $basics[ 'button_close_img' ]['url']) : 1;

            $open_img_class                 = ! empty($basics[ 'button_open_img' ]['url']) && $panel_type === 'toggler' ? ' has-img-open' : '';
            $the_same_img                   = $open_close_img_the_same === 0 ? ' close-img-thesame' : '';
            $close_img_class                = ! empty($basics[ 'button_close_img' ]['url']) && $open_close_img_the_same !== 0 ? ' has-img-close' : $the_same_img;
            $toggler_img_classes            = $open_img_class .$close_img_class;

            $open_img_alt                   = ! empty($basics[ 'button_open_img' ]['alt'])     ? $basics[ 'button_open_img' ]['alt']     : esc_html__('Open a panel', 'maxboxy');
            $close_img_alt                  = ! empty($basics[ 'button_close_img' ]['alt'])    ? $basics[ 'button_close_img' ]['alt']    : esc_html__('Close a panel', 'maxboxy');
            $open_img_width                 = ! empty($basics[ 'button_open_img' ]['width'])   ? $basics[ 'button_open_img' ]['width']   : 100;
            $close_img_width                = ! empty($basics[ 'button_close_img' ]['width'])  ? $basics[ 'button_close_img' ]['width']  : 100;
            $open_img_height                = ! empty($basics[ 'button_open_img' ]['height'])  ? $basics[ 'button_open_img' ]['height']  : 100;
            $close_img_height               = ! empty($basics[ 'button_close_img' ]['height']) ? $basics[ 'button_close_img' ]['height'] : 100;

            $_escaped_trig_img_open         = ! empty($open_img_class)  ? '<img src="' .esc_url($basics[ 'button_open_img' ]['url']) .'" class="trig-img-open" width="' .esc_attr($open_img_width) .'" height="' .esc_attr($open_img_height) .'" alt="' .esc_attr($open_img_alt) .'">'  : '';
            $_escaped_trig_img_close        = ! empty($close_img_class) && empty($the_same_img) && $unset_toggler === 'no' ? '<img src="' .esc_url($basics[ 'button_close_img' ]['url']) .'" class="trig-img-close" width="' .esc_attr($close_img_width) .'" height="' .esc_attr($close_img_height) .'" alt="' .esc_attr($close_img_alt) .'">' : '';


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

            // additional message for the trigger button
            $trigger_add_message            = $panel_type === 'toggler' && ! empty($basics[ 'trigger_additional_message' ]) ? $basics[ 'trigger_additional_message' ] : '';
            $trigger_add_message_class      = $panel_type === 'toggler' && ! empty($basics[ 'trigger_additional_message' ]) ? ' has-additional-message' : '';
            $rotator_repeat                 = $rotator === true && ! empty($basics[ 'rotator_repeat' ]) ? ' rotator-repeat' : '';

            // additional message for the panel
            $panel_add_lable            = ! empty($basics[ 'panel_additional_lable' ]) ? $basics[ 'panel_additional_lable' ] : '';
            $panel_add_lable_class      = ! empty($basics[ 'panel_additional_lable' ]) ? ' has-additional-label' : '';

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
            $set_panel_bg  .= !empty($panel_bg[ 'background-color' ])           ? 'background-color:'      .$panel_bg[ 'background-color' ]          .';'  :'';

            $panel_color                    =   !empty($basics['panel_popup_color']) ? 'color:' .$basics['panel_popup_color'] .';' : '';

            $trig_bg                        = !empty($basics['panel_shut_bg']) && $basics['panel_shut_bg'] !== '#333333' ? 'background:' .$basics['panel_shut_bg'] .';' : '';

            $trig_color                     = !empty($basics['panel_shut_color']) && $basics['panel_shut_color'] !== '#ffffff' ? 'color:' .$basics['panel_shut_color'] .';' : '';

            $_escaped_panel_trig_style      = ! empty($trig_bg) || ! empty($trig_color)
                                            ? ' style="' .esc_attr($trig_bg) .esc_attr($trig_color) .'"' : '';

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

            $get_pad_top                    = isset($basics[ 'panel_padding' ][ 'top' ]) && is_numeric($basics[ 'panel_padding' ][ 'top' ]) ? abs($basics[ 'panel_padding' ][ 'top' ])   : '';
            $panel_padding                  = ! empty($get_pad_top) ? 'padding-top: ' .$get_pad_top .$padding_unit .';' : '';
            
            $get_pad_left                   = isset($basics[ 'panel_padding' ][ 'left' ]) && is_numeric($basics[ 'panel_padding' ][ 'left' ]) ? abs($basics[ 'panel_padding' ][ 'left' ])  : '';
            $panel_padding                 .= ! empty($get_pad_left) ? 'padding-left: ' .$get_pad_left .$padding_unit .';' : '';
            
            $get_pad_right                  = isset($basics[ 'panel_padding' ][ 'right' ]) && is_numeric($basics[ 'panel_padding' ][ 'right' ]) ? abs($basics[ 'panel_padding' ][ 'right' ]) : '';
            $panel_padding                 .= ! empty($get_pad_right) ? 'padding-right: ' .$get_pad_right .$padding_unit .';' : '';
            
            $get_pad_bottom                 = isset($basics[ 'panel_padding' ][ 'bottom' ]) && is_numeric($basics[ 'panel_padding' ][ 'bottom' ]) ? abs($basics[ 'panel_padding' ][ 'bottom' ]): '';
            $panel_padding                 .= ! empty($get_pad_bottom) ? 'padding-bottom: ' .$get_pad_bottom .$padding_unit .';' : '';

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


            // Closer/toggler styling

            $get_closer_style               = ! empty($basics[ 'closer_styling' ]) ? $basics[ 'closer_styling' ]  : '';
            // convert the array to a string
            $check_closer_style             = is_array($get_closer_style)   ?   implode(' ', $get_closer_style)   : '';
            // prepend a space before the output, so it adds correctly to the existing classes ...$panel_type set on the Basic plugin
            $toggler_styling                = $unset_toggler === 'no' && ! empty($check_closer_style)
                                            ? ' ' .$check_closer_style : '';

            // check is the style 'inside'. We'll need this to dismiss the '-rev' output beneath when it's 'inside'
            $is_inside                      = is_array($get_closer_style) && in_array('inside', $get_closer_style) ? true : false;

            /**
             * Flex reverse when $is_inside !== true: 3, 4, 7, 8, 9 , 11 ? '-rev' : ''
             * Flex reverse when $is_inside === true: 1, 2, 5, 6, 10 , 12 ? '-rev' : ''
             *
             * Column ($get_direction): 5, 6, 7, 8 11, 12   ? 'column' : 'row'
             *
             * Align-center: 9, 10, 11, 12 ? 'align-center' : ''
             * align-end: 2, 4, 6, 8       ? 'align-end' : ''
             */

            $reverse                        = $unset_toggler !== true && $is_inside !== true && isset($basics[ 'toggler_pos' ]) && ($basics[ 'toggler_pos' ] === '3' || $basics[ 'toggler_pos' ] === '4' || $basics[ 'toggler_pos' ] === '7' || $basics[ 'toggler_pos' ] === '8' || $basics[ 'toggler_pos' ] === '9' || $basics[ 'toggler_pos' ] === '11') ? '-rev' : '';
            $reverse                        = $unset_toggler !== true && $is_inside === true && isset($basics[ 'toggler_pos' ]) && ($basics[ 'toggler_pos' ] === '1' || $basics[ 'toggler_pos' ] === '2' || $basics[ 'toggler_pos' ] === '5' || $basics[ 'toggler_pos' ] === '6' || $basics[ 'toggler_pos' ] === '10' || $basics[ 'toggler_pos' ] === '12') ? '-rev' : $reverse;

            $get_direction                  = $unset_toggler !== true && isset($basics[ 'toggler_pos' ]) && ($basics[ 'toggler_pos' ] === '5' || $basics[ 'toggler_pos' ] === '6' || $basics[ 'toggler_pos' ] === '7' || $basics[ 'toggler_pos' ] === '8' || $basics[ 'toggler_pos' ] === '11' || $basics[ 'toggler_pos' ] === '12') ? 'column' : 'row';
            $direction                      = !empty($get_direction) ? ' dir-' .$get_direction .$reverse : '';

            $closer_align_center            = $unset_toggler !== true && isset($basics[ 'toggler_pos' ]) && ($basics[ 'toggler_pos' ] === '9' || $basics[ 'toggler_pos' ] === '10' || $basics[ 'toggler_pos' ] === '11' || $basics[ 'toggler_pos' ] === '12') ? ' align-center' : '';
            $closer_align_end               = $unset_toggler !== true && isset($basics[ 'toggler_pos' ]) && ($basics[ 'toggler_pos' ] === '2' || $basics[ 'toggler_pos' ] === '4' || $basics[ 'toggler_pos' ] === '6' || $basics[ 'toggler_pos' ] === '8') ? ' align-end' : '';
            $closer_align                   = !empty($closer_align_center) ? $closer_align_center : '';
            $closer_align                  .= !empty($closer_align_end)    ? $closer_align_end : '';

            $closer_size                    = $unset_toggler !== true && isset($basics[ 'closer_size' ]) && $basics[ 'closer_size' ] !== 'normal' ? ' ' .$basics[ 'closer_size' ] : '';


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
                'type'                       => $panel_type,
                // classes:
                'strain'                     => $panel_strain,
                'style'                      => $showing_style,
                'roles'                      => $panel_roles,
                'rotator_repeat'             => $rotator_repeat,
                'trig_class'                 => $trig_class,
                'add_classes'                => $add_classes,
                'panel_size'                 => $panel_size_class,
                'direction'                  => $direction,
                'closer_align'               => $closer_align,
                'closer_size'                => $closer_size,
                'toggler_styling'            => $toggler_styling,
                'toggler_start_class'        => $toggler_start_class,
                'trigger_add_message_class'  => $trigger_add_message_class,
                'toggler_svg_classes'        => $toggler_svg_classes,
                'toggler_img_classes'        => $toggler_img_classes,
                'trigger_icon_classes'       => $trigger_icon_classes,
                //'trigger_anim'             => $trigger_anim,
                'unset_toggler_class'        => $unset_toggler_class,
                'panel_add_lable_class'      => $panel_add_lable_class,
                'injectany_align'            => $injectany_align,
                'sticky'                     => $sticky,
                // data:
                'rotator_time'               => $_escaped_rotator_time_data,
                'wrap_style'                 => $_escaped_panel_wrap_style,
                'panel_style'                => $_escaped_panel_data,
                'content_style'              => $_escaped_panel_content_style,
                'trig_style'                 => $_escaped_panel_trig_style,
                'toggler_data'               => $_escaped_toggler_data,
                'trigger_add_message'        => $trigger_add_message,
                'trig_svg_open'              => $_safe_trig_svg_open,
                'trig_svg_close'             => $_safe_trig_svg_close,
                'trig_img_open'              => $_escaped_trig_img_open,
                'trig_img_close'             => $_escaped_trig_img_close,
                'panel_add_lable'            => $panel_add_lable,
                // else:
                'unset_toggler'              => $unset_toggler,
                'toggler_start_title'        => $toggler_start_title,
                'use_overlay'                => $use_overlay,
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
