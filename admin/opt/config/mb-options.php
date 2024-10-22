<?php
// phpcs:ignore
/**
 * Description: Options for a metabox.config file,
 * seems better readable as placed in a separate file, i.e.
 * referred here from the metabox.php.
 */

if (! defined('ABSPATH')) {
    exit;
}


    /*
     * Here have to get the global option, because 
     * checking Max_Boxy_Track::enabled() won't work here,
     * probably due order priority.
     */
    // Later this is checked in this file, but as well in premium plugin
    $enabled_conversions        = isset(get_option('_maxboxy_options')[ 'enable_conversions' ])
                                ?       get_option('_maxboxy_options')[ 'enable_conversions' ] : '';


    // the following options get outputted if the Pro plugin is active
    $auto_loading_disabled_op   = class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== ''
                                ? array( 'disabled'  =>  esc_html__('Disabled', 'maxboxy') )
                                : array();

    $auto_loading_desc          = ! class_exists('Max_Boxy_Pro') || class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() === ''
                                ? array( 'desc'  =>  esc_html__('With the Pro version you can disable overall loading, then load the panel with shortcode on a particular page or a template.',    'maxboxy') )
                                : array();

    $get_id                     = isset($_GET['post']) ? $_GET['post'] : get_the_ID();
    $auto_loading_from_splitter = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                && ( get_post_type($get_id) === 'float_any' || get_post_type($get_id) === 'inject_any' ) // without the 'wp_block' since it doesnt have global loading option
                                    // Set 5th param as "visible" or "true" to set it as visible ony if the 'is_splitted_from' is empty.
                                ? array( 'dependency'  =>  array( 'is_splitted_from', '==', '', 'true', 'visible' ) )
                                : array();

    $test_mode                  = array(
                                    'id'            => 'test_mode',
                                    'type'          => 'checkbox',
                                    'title'         => esc_html__('Test mode', 'booster-sweeper'),
                                    'help'          => esc_html__('Load the panel only for the logged in users who can edit pages, i.e. you.', 'booster-sweeper'),
                                );
                        
    $field_auto_loading         = array(
                                    'id'            => 'auto_loading',
                                    'type'          => 'button_set',
                                    'title'         => esc_html__('Global Loading', 'maxboxy'),
                                    'help'          => esc_html__('By default panel is loaded immediately over the site. By disabling it here, you can still call it from the shortcode, e.g. set it for the particular page or use it on the template.', 'maxboxy'),
                                    'default'       => 'enabled',
                                    'options'       => array(
                                        'enabled'   =>  esc_html__('Enabled', 'maxboxy'),
                                    ) +$auto_loading_disabled_op,
                                ) +$auto_loading_desc +$auto_loading_from_splitter;


    $auto_loading_splitter_info = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true && ( get_post_type($get_id) === 'float_any' || get_post_type($get_id) === 'inject_any')
                                ? array(
                                    'id'            => 'auto_loading_from_splitter_info',
                                    'type'          => 'notice',
                                    'style'         => 'info',
                                    'content'       => esc_html__('Global loading is controlled from the Splitter instance.', 'maxboxy'),
                                    'dependency' => array( 'is_splitted_from', '!=', '', 'true'),
                                )
                                : // empty field coz we cannot set array() since it will return "Field not found"
                                    $empty_field;


    /*
     * Differentiate options availabe on the basic & the pro version,
     * For Splitter metabox
     */
    // $get_license = class_exists( 'Max_Boxy_Pro' ) && Max_Boxy_Pro::getLicense() !== '' ? true : false;
    // if ( $get_license !== false && function_exists( 'maxboxy_metabox_settings_splitter' ) ) {
    if (function_exists('maxboxy_metabox_settings_splitter')) {

        $mb_splitter_info = maxboxy_metabox_settings_splitter_info();
        $mb_splitter = maxboxy_metabox_settings_splitter();

        // else - if the pro plugin isn't active
    } else {

        $mb_splitter_info = array(

            array(
                'type'      => 'submessage',
                'style'     => 'info',
                'content'   => esc_html__('Serve panel variations.', 'maxboxy'),
            ),
            array(
                'type'      => 'callback',
                'function'  => 'maxboxy_upgrade_call',
            ),

        );

        /* 
         * there's no splitter free version,
         * this is just to bypass the error notices
         * when the splitter plugin isn't active
         */
        $mb_splitter = array(
            $empty_field,
        );
    }


    /*
     * Differentiate options availabe on the basic & the pro version,
     * For Conditionals metabox.
     */
    // if the pro license is active
    if (function_exists('maxboxy_metabox_settings_conditionals')) {
        $tabs_conditional = maxboxy_metabox_settings_conditionals();
        // else - if the pro plugin isn't active
    } else {

        /*
         * Particular pages tab.
         */
        $particlular_pages_tab = array(

            'title'     => '',
            'title'     => esc_html__('Pages', 'maxboxy'),
            'icon'      => 'fas fa-file-alt',
            'fields'    => array(
                array(
                    'type'      => 'submessage',
                    'style'     => 'info',
                    'content'   => esc_html__('Apply to particular pages', 'maxboxy'),
                ),
                array(
                    'type'      => 'callback',
                    'function'  => 'maxboxy_upgrade_call',
                )
            )

        );

        /*
         * Appearance tab.
         */
        $trigger_appear_tab = array(

            'title'     => '',
            'title'     => esc_html__('Triggers', 'maxboxy'),
            'icon'     => 'fas fa-file-import',
            'fields'    => array(
                array(
                    'type'      => 'submessage',
                    'style'     => 'info',
                    'content'   => esc_html__('Conditional trigger appearance options', 'maxboxy'),
                ),
                array(
                    'type'      => 'callback',
                    'function'  => 'maxboxy_upgrade_call',
                )
            )

        );

        /*
         * Schedule tab.
         */
        $schedule_tab = array(

            'title'     => '',
            'title'     => esc_html__('Schedule', 'maxboxy'),
            'icon'      => 'fas fa-calendar-alt',
            'fields'    => array(
                array(
                    'type'      => 'submessage',
                    'style'     => 'info',
                    'content'   => esc_html__('Schedule options', 'maxboxy'),
                ),
                array(
                    'type'      => 'callback',
                    'function'  => 'maxboxy_upgrade_call',
                )
            )

        );

        /*
         * Join the conditionals tabs.
         */
        $tabs_conditional = array(

            array(
                'id'        => 'box_options',
                'type'      => 'tabbed',
                'class'     => 'major-maxboxy-options',
                'tabs'      => array(
                    $particlular_pages_tab,
                    $trigger_appear_tab,
                    $schedule_tab,
                ),
            )

        );

    } // end the pro plugin isn't active condition



    /*
     * Base tab.
     */

    // first, set the z-index default as a placeholder, pulled from the plugin settings.
    $zindex             = isset(get_option('_maxboxy_options')[ 'zindex' ])
                        ?       get_option('_maxboxy_options')[ 'zindex' ] : '';

    $get_zindex_default = ! empty($zindex) ? $zindex : 999;

    $panel_additional_lable = array(
        'id'            => 'panel_additional_lable',
        'type'          => 'textarea',
        'title'         => esc_html__('Additional label over the panel', 'maxboxy'),
        'help'          => esc_html__('Set additional message that can appear along the panel', 'maxboxy'),
    );

    $add_classes_field  = function_exists('maxboxy_metabox_settings_basic_pro') && ! empty(maxboxy_metabox_settings_basic_pro()[ 'add_classes' ])
                        ? maxboxy_metabox_settings_basic_pro()[ 'add_classes' ]
                        : $empty_field;

    $trigger_anim_fields= function_exists('maxboxy_metabox_settings_basic_pro') && ! empty(maxboxy_metabox_settings_basic_pro()[ 'trigger_anims' ])
                        ? maxboxy_metabox_settings_basic_pro()[ 'trigger_anims' ]
                        : array(
                            [
                                'type'      => 'callback',
                                'function'  => 'maxboxy_upgrade_call',
                            ]
                        );


    // Starter tab - Float Any.
    $starter_tab_floatany = array(

        'title'     => esc_html__('Base', 'maxboxy'),
        'icon'      => 'fas fa-sliders-h',
        'fields'    => array(

            array(
                'id'            => 'panel_popup_style',
                'type'          => 'select',
                'title'         => esc_html__('Showing style', 'maxboxy'),
                'help'          => esc_html__('Set in which way the panel is revealed.', 'maxboxy'),
                'options'       => array(
                                        'bump'              => esc_html__('Bump in',                'maxboxy'),
                                        'fade'              => esc_html__('Fade in',                'maxboxy'),
                                        'slide-horizontal'  => esc_html__('Slide in horizontally',  'maxboxy'),
                                        'slide-vertical'    => esc_html__('Slide in vertically',    'maxboxy'),
                                        'push-up'           => esc_html__('Push up',    'maxboxy'),
                                        'push-left'         => esc_html__('Push left',  'maxboxy'),
                                        'push-right'        => esc_html__('Push right', 'maxboxy'),
                                        'push-down'         => esc_html__('Push down',  'maxboxy'),
                                ),
                'default'       => 'bump',
            ),
            array(
                'id'            => 'zindex',
                'type'          => 'number',
                'title'         => esc_html__('Z-index', 'maxboxy'),
                'help'          => esc_html__('If other element is overlapping the box, with "z-index" give a higher priority to the box in a stack order. Default is 999, unless you changed it from the plugin\'s global settigns.', 'maxboxy'),
                'placeholder'   => $get_zindex_default, // pulled from the plugin global settings
            ),
            array(
                'id'            => 'use_overlay',
                'type'          => 'checkbox',
                'title'         => esc_html__('Apply lightbox', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_type',
                'type'          => 'button_set',
                'title'         => esc_html__('Panel type', 'maxboxy'),
                'help'          => esc_html__('Basically, determine the way the panel is opened and closed', 'maxboxy'),
                'options'       => array(
                    'toggler'     => esc_html__('Toggler', 'maxboxy'),
                    'closer'      => esc_html__('Closer',  'maxboxy'),
                ),
                'default'       => 'closer',
                'class'         => 'maxboxy-panel-type',
            ),
            array(
                'id'            => 'panel_roles',
                'type'          => 'button_set',
                'title'         => esc_html__('Panel roles (Optional)', 'maxboxy'),
                'help'          => esc_html__('Multiple selections allowed. However, be aware that sometimes combination of roles may be contradictional and even may get incompatible. "Hidden" means that the panel will be loading but not revealed, you have to set a initialization button somewhere on the page (Available with Pro version). "Banish" will work only if the Conversion module is enabled from the global settings. It means, once closed, the panel won\'t show again until the user\'s browser storage is cleaned. "Exiter" means a panel will be shown when a user tries to exit the browser (usually used together with "Hidden" option). "Hover out" role will close the panel when the mouse leaves the panel. With "Rotator" option, panel\'s elements will be rotatted. "Igniter" is the feature of the Toggler panel type, which set the panel initially closed with a button to ignite it.', 'maxboxy'),
                'options'       => array(
                    'role-hidden'   =>  esc_html__('Hidden',    'maxboxy'),
                    'role-exit'     =>  esc_html__('Exiter',    'maxboxy'),
                    'role-banish'   =>  esc_html__('Banish',    'maxboxy'),
                    'role-hoverer'  =>  esc_html__('Hover out', 'maxboxy'),
                    'role-rotator'  =>  esc_html__('Rotator',   'maxboxy'), // Has to be set as 2nd from the behind, coz in adminizer.js it's showing/hidding based on the panel_type selection.
                    'role-igniter'  =>  esc_html__('Igniter',   'maxboxy'), // Has to be set as last, so that it's popped-out from the array if the closer is selected
                                                                            // (done from the Max_Boxy_Options' basics() with array_pop ).
                                                                            // Also in adminizer.js it's showing/hidding based on the panel_type selection.
                ),
                'multiple'      => true,
                'class'         => 'maxboxy-button-set-span maxboxy-panel-roles',
            ),
            array(
                'id'            => 'rotator_on',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - active time (seconds)', 'maxboxy'),
                'help'          => esc_html__('Set how long the notification item will last as live. Default is 5 seconds.', 'maxboxy'),
                'default'       => 5,
                'attributes'    => array(
                    'min'           => 1,
                ),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            array(
                'id'            => 'rotator_off',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - off time (seconds)', 'maxboxy'),
                'help'          => esc_html__('Duration between two rotating items, i.e. the time before showing the next notification. Default is 10 seconds, minimum 1 second.', 'maxboxy'),
                'default'       => 10,
                'attributes'    => array(
                    'min'           => 1,
                ),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            array(
                'id'            => 'rotator_close_pause',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - user\'s closing pause (seconds)', 'maxboxy'),
                'help'          => esc_html__('Pause rotation on notification\'s closing - If the user closes a panel, wait for the specified time before continuing with the next notification.', 'maxboxy'),
                'attributes'    => array(
                    'min'           => 0,
                ),
                'dependency'    => array('panel_type|panel_roles','any|any','closer|role-rotator'),
            ),
            array(
                'id'            => 'rotator_repeat',
                'type'          => 'checkbox',
                'title'         => esc_html__('Repeat rotation', 'maxboxy'),
                'help'          => esc_html__('After the last notification item is shown, it will start over again from the first one, and continue endlessly.', 'maxboxy'),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            $panel_additional_lable,
            $add_classes_field,

        ),

    );

    // Starter tab - Inject Any.
    $starter_tab_injectany = array(

        'title'     => esc_html__('Base', 'maxboxy'),
        'icon'      => 'fas fa-sliders-h',
        'fields'    => array(

            array(
                'id'            => 'panel_popup_style',
                'type'          => 'select',
                'title'         => esc_html__('Showing style', 'maxboxy'),
                'help'          => esc_html__('Set in which way the panel is revealed.', 'maxboxy'),
                'options'       => array(
                                        'onpageload'        => esc_html__('On page load',           'maxboxy'),
                                        'bump'              => esc_html__('Bump in',                'maxboxy'),
                                        'fade'              => esc_html__('Fade in',                'maxboxy'),
                                        'slide-horizontal'  => esc_html__('Slide in horizontally',  'maxboxy'),
                                        'slide-vertical'    => esc_html__('Slide in vertically',    'maxboxy'),
                                        'alignwide'         => esc_html__('Align wide',             'maxboxy'),
                                        'alignfull'         => esc_html__('Align full',             'maxboxy'),
                                ),
                'default'       => 'onpageload',
            ),
            array(
                'id'            => 'zindex',
                'type'          => 'number',
                'title'         => esc_html__('Z-index', 'maxboxy'),
                'help'          => esc_html__('If other element is overlapping the box, with "z-index" give a higher priority to the box in a stack order. This may usefull if some of beneath options are used (lightbox or sticky). Default is 2.', 'maxboxy'),
                'placeholder'   => '2',
            ),
            array(
                'id'            => 'use_overlay',
                'type'          => 'checkbox',
                'title'         => esc_html__('Apply lightbox', 'maxboxy'),
            ),
            array(
                'id'            => 'set_sticky',
                'type'          => 'checkbox',
                'title'         => esc_html__('Set as Sticky', 'maxboxy'),
                'help'          => esc_html__('While scrolling down, it is sticking the panel to the top of its parent container.', 'maxboxy'),
            ),
            array(
                'id'            => 'box_align',
                'type'          => 'button_set',
                'title'         => esc_html__('Alignment', 'maxboxy'),
                'desc'          => esc_html__('If the panel\'s size is set but not with 100% width, you can align its postion.', 'maxboxy'),
                'options'       => array(
                                        'start'  => esc_html__('Start',     'maxboxy'),
                                        'center' => esc_html__('Center',    'maxboxy'),
                                        'end'    => esc_html__('End',       'maxboxy'),
                                    ),
                'default'       => 'start',
            ),
            array(
                'id'            => 'panel_type',
                'type'          => 'button_set',
                'title'         => esc_html__('Panel type', 'maxboxy'),
                'help'          => esc_html__('Basically, determine the way the panel is opened and closed', 'maxboxy'),
                'options'       => array(
                    'toggler'    => esc_html__('Toggler', 'maxboxy'),
                    'closer'     => esc_html__('Closer',  'maxboxy'),
                ),
                'default'       => 'closer',
                'class'         => 'maxboxy-panel-type',
            ),
            array(
                'id'            => 'panel_roles',
                'type'          => 'button_set',
                'title'         => esc_html__('Panel roles (Optional)', 'maxboxy'),
                'help'          => esc_html__('Multiple selections allowed. However, be aware that sometimes combination of roles may be contradictional and even may get incompatible. "Hidden" means that the panel will be loading but not revealed, you have to set a initialization button somewhere on the page (Available with Pro version). "Banish" will work only if the Conversion module is enabled from the global settings. It means, once closed, the panel won\'t show again until the user\'s browser storage is cleaned. "Exiter" means a panel will be shown when a user tries to exit the browser (usually used together with "Hidden" option). "Hover out" role will close the panel when the mouse leaves the panel. With "Rotator" option, panel\'s elements will be rotatted. "Igniter" is the feature of the Toggler panel type, which set the panel initially closed with a button to ignite it.', 'maxboxy'),
                'options'       => array(
                    'role-hidden'   =>  esc_html__('Hidden',    'maxboxy'),
                    'role-banish'   =>  esc_html__('Banish',    'maxboxy'),
                    'role-hoverer'  =>  esc_html__('Hover out', 'maxboxy'),
                    'role-rotator'  =>  esc_html__('Rotator',   'maxboxy'), // Has to be set as 2nd from the behind, coz in adminizer.js it's showing/hidding based on the panel_type selection.
                    'role-igniter'  =>  esc_html__('Igniter',   'maxboxy'), // Has to be set as last, so that it's popped-out from the array if the closer is selected
                                                                            // (done from the Max_Boxy_Options::basics() with array_pop ).
                                                                            // Also in adminizer.js it's showing/hidding based on the panel_type selection.
                ),
                'multiple'      => true,
                'class'         => 'maxboxy-button-set-span maxboxy-panel-roles',
            ),
            array(
                'id'            => 'rotator_on',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - active time (seconds)', 'maxboxy'),
                'help'          => esc_html__('Set how long the notification item will last as live. Default is 5 seconds.', 'maxboxy'),
                'default'       => 5,
                'attributes'    => array(
                    'min'           => 1,
                ),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            array(
                'id'            => 'rotator_off',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - off time (seconds)', 'maxboxy'),
                'help'          => esc_html__('Duration between two rotating items, i.e. the time before showing the next notification. Default is 10 seconds, minimum 1 second.', 'maxboxy'),
                'default'       => 10,
                'attributes'    => array(
                    'min'           => 1,
                ),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            array(
                'id'            => 'rotator_close_pause',
                'type'          => 'number',
                'title'         => esc_html__('Rotation - user\'s closing pause (seconds)', 'maxboxy'),
                'help'          => esc_html__('Pause rotation on notification\'s closing - If the user closes a panel, wait for the specified time before continuing with the next notification.', 'maxboxy'),
                'attributes'    => array(
                    'min'           => 0,
                ),
                'dependency'    => array('panel_type|panel_roles','any|any','closer|role-rotator'),
            ),
            array(
                'id'            => 'rotator_repeat',
                'type'          => 'checkbox',
                'title'         => esc_html__('Repeat rotation', 'maxboxy'),
                'help'          => esc_html__('After the last notification item is shown, it will start over again from the first one, and continue endlessly.', 'maxboxy'),
                'dependency'    => array('panel_roles','any','role-rotator'),
            ),
            $panel_additional_lable,
            $add_classes_field,

        )

    );



    /*
     * Colors tab.
     */

    /* Floatany */

    // set colors tab
    $colors_tab = array(

        'title'     => esc_html__('Colors', 'maxboxy'),
        'icon'      => 'fas fa-paint-brush',
        'fields'    => array(
            
            array(
                'id'            => 'panel_popup_bg',
                'type'          => 'background',
                'title'         => esc_html__('Panel\'s background', 'maxboxy'),
            ),
            array(
                'type'          => 'submessage',
                'style'         => 'normal',
                'content'       => esc_html__('The background visibility depends on the transparency of the blocks you use. Also if you apply padding from the "Size" tab, that will result in the background set from here becoming visible.', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_popup_color',
                'type'          => 'color',
                'title'         => esc_html__('Panel\'s text color', 'maxboxy'),
                'help'          => esc_html__('If the block doesn\'t have the text color set or if it doesn\'t inherit it from the global settings, it will inherit from here.', 'maxboxy'),
            ),
            array(
                'type'          => 'subheading',
                'content'       => esc_html__('Toggler/closser', 'maxboxy'),
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_bg',
                'type'          => 'color',
                'title'         => esc_html__('Background', 'maxboxy'),
                'default'       => '#333333',
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_color',
                'type'          => 'color',
                'title'         => esc_html__('Color', 'maxboxy'),
                'default'       => '#ffffff',
                'dependency'    => array('unset_toggler','!=','true', true),
            ),

        )

    );


    /*
     * Sizes tab.
     */

     /* FloatAny */
    $set_sizes_tab_floatany = array(

        'title'     => esc_html__('Sizes', 'maxboxy'),
        'icon'      => 'fas fa-arrows-alt',
        'fields'    => array(
            array(
                'type'          => 'submessage',
                'style'         => 'normal',
                'content'       => esc_html__('All size options are optional', 'maxboxy'),
            ),
            array(
                'id'            => 'size_1',
                'type'          => 'dimensions',
                'title'         => esc_html__('Width', 'maxboxy'),
                'help'          => esc_html__('If not specified, width of the content determines it.', 'maxboxy'),
                'height'        => false,
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'default'       => array(
                    'unit'      => '%',
                    ),
            ),
            array(
                'id'            => 'height_100',
                'type'          => 'radio',
                'title'         => esc_html__('100% height', 'maxboxy'),
                'help'          => esc_html__('Initially panel will take as much space in height as it needs to display the content. However, here you can force it to take the 100% of the browser\'s height.', 'maxboxy'),
                'options'       => array(
                    'yes'       => esc_html__('Yes',  'maxboxy'),
                    'no'        => esc_html__('No',   'maxboxy'),
                ),
                'default'       => 'no',
                'inline'        => true,
            ),
            array(
                'id'            => 'size_2',
                'type'          => 'dimensions',
                'title'         => esc_html__('Width (large screens)', 'maxboxy'),
                'help'          => esc_html__('Optionally overwrite for the large screens', 'maxboxy'),
                'height'        => false,
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'default'       => array(
                    'unit'      => '%',
                ),
            ),
            array(
                'id'            => 'height_100_2',
                'type'          => 'radio',
                'title'         => esc_html__('100% height (large screens)', 'maxboxy'),
                'help'          => esc_html__('Optionally overwrite for the large screens', 'maxboxy'),
                'options'       => array(
                                        'inherit'   => esc_html__('Inherit',    'maxboxy'),
                                        'yes'       => esc_html__('Yes',        'maxboxy'),
                                        'no'        => esc_html__('No',         'maxboxy'),
                                ),
                'default'       => 'inherit',
                'inline'        => true,
            ),
            array(
                'type'          => 'subheading',
                'content'       => esc_html__('Spacing', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_padding',
                'type'          => 'spacing', 
                'title'         => esc_html__('Padding', 'maxboxy'),
                'units'         => array( '%', 'px', 'em', 'rem' ),
            ),
            array(
                'type'          => 'subheading',
                'content'       => esc_html__('Border', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_border',
                'type'          => 'border',
                'title'         => esc_html__('Border', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_border_radius',
                'type'          => 'spacing',
                'title'         => esc_html__('Border radius', 'maxboxy'),
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'right_icon'    => false,
                'left_icon'     => false,
                'default'       => array(
                    'top'       => '0',
                    'right'     => '0',
                    'bottom'    => '0',
                    'left'      => '0',
                    'unit'      => 'px',
                    ),
                ),
        ),

    );

     /* InjectAny */
    $set_sizes_tab_injectany = array(

        'title'     => esc_html__('Sizes', 'maxboxy'),
        'icon'      => 'fas fa-arrows-alt',
        'fields'    => array(
            array(
                'type'          => 'submessage',
                'style'         => 'normal',
                'content'       => esc_html__('All size options are optional', 'maxboxy'),
            ),
            array(
                'id'            => 'size_1',
                'type'          => 'dimensions',
                'title'         => esc_html__('Width', 'maxboxy'),
                'help'          => esc_html__('If not specified, width of the content determines it.', 'maxboxy'),
                'height'        => false,
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'default'       => array(
                                        'unit'  => '%',
                                      ),
            ),
            array(
                'id'            => 'size_2',
                'type'          => 'dimensions',
                'title'         => esc_html__('Width (large screens)', 'maxboxy'),
                'help'          => esc_html__('Optionally overwrite for the large screens', 'maxboxy'),
                'height'        => false,
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'default'       => array(
                    'unit' => '%',
                ),
            ),
            array(
                'type'          => 'subheading',
                'content'       => esc_html__('Spacing', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_padding',
                'type'          => 'spacing',
                'title'         => esc_html__('Padding', 'maxboxy'),
                'help'          => esc_html__('Default is 1.5em', 'maxboxy'),
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'default'       => array(
                                        'top'    => '1.5',
                                        'right'  => '1.5',
                                        'bottom' => '1.5',
                                        'left'   => '1.5',
                                        'unit'   => 'em',
                                    ),
            ),
            array(
                'type'          => 'subheading',
                'content'       => esc_html__('Border', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_border',
                'type'          => 'border',
                'title'         => esc_html__('Border', 'maxboxy'),
            ),
            array(
                'id'            => 'panel_border_radius',
                'type'          => 'spacing',
                'title'         => esc_html__('Border radius', 'maxboxy'),
                'units'         => array( '%', 'px', 'em', 'rem' ),
                'right_icon'    => false,
                'left_icon'     => false,
                'default'       => array(
                    'top'       => '0',
                    'right'     => '0',
                    'bottom'    => '0',
                    'left'      => '0',
                    'unit'      => 'px',
                    ),
                ),
        ),

    );


    /*
     * Toggler tab.
     */
    $svg_desc = esc_html__('Overrides a CSS formed icon. Select "No" to use the CSS icon.', 'maxboxy');
    $img_desc = esc_html__('Overrides an icon and SVG', 'maxboxy');

    $toggler_basic_tab  = array(

        array(
            'type'          => 'submessage',
            'style'         => 'normal',
            'content'       => esc_html__('Here you can set how the panel\'s close or toggle on/off button appears.', 'maxboxy'),
        ),
        array(
            'id'            => 'unset_toggler',
            'type'          => 'button_set',
            'title'         => esc_html__('Usage', 'maxboxy'),
            'help'          => esc_html__('You can use, remove the close button, or remove entirely both open and close button. You can still utilize the in-content closing/toggling buttons, no matter which option you set here.', 'maxboxy'),
            'options'       => array(
                'no'        => esc_html__('Use',        'maxboxy'),
                'closer'    => esc_html__('No Closer',  'maxboxy'),
                'all'       => esc_html__('Remove',     'maxboxy'),
            ),
            'default'       => 'no',
            'class'         => 'mb-unset-toggling-default',
        ),
        array(
            'id'            => 'button_open_icon',
            'type'          => 'image_select',
            'title'         => esc_html__('Opener icon (CSS)', 'maxboxy'),
            'help'          => esc_html__('It\'s opening the panel', 'maxboxy'),
            'options'       => array(
                'iks-plus'          => plugins_url('maxboxy/admin/opt/img/i-plus.png'),
                'minus'             => plugins_url('maxboxy/admin/opt/img/i-minus.png'),
                'point-left'        => plugins_url('maxboxy/admin/opt/img/i-point-left.png'),
                'point-right'       => plugins_url('maxboxy/admin/opt/img/i-point-right.png'),
                'point-up'          => plugins_url('maxboxy/admin/opt/img/i-point-up.png'),
                'point-down'        => plugins_url('maxboxy/admin/opt/img/i-point-down.png'),
                'ham'               => plugins_url('maxboxy/admin/opt/img/i-ham.png'),
                'ham-f1'            => plugins_url('maxboxy/admin/opt/img/i-ham-2.png'),
                'ham-f2'            => plugins_url('maxboxy/admin/opt/img/i-ham-3.png'),
                'ham-f3'            => plugins_url('maxboxy/admin/opt/img/i-ham-4.png'),
                'ham-f4'            => plugins_url('maxboxy/admin/opt/img/i-ham-5.png'),
            ),
            'default'       => 'iks-plus',
            'dependency'    => array('unset_toggler|button_open_svg|button_open_img|panel_type','!=|==|==|any','all|no||toggler', '|||true'),
        ),
        array(
            'id'            => 'button_open_svg',
            'type'          => 'image_select',
            'title'         => esc_html__('Opener SVG', 'maxboxy'),
            'desc'          => $svg_desc,
            'options'       => array(
                'no'                => plugins_url('maxboxy/admin/opt/img/svg-none.png'),
                'svg-app'           => plugins_url('maxboxy/admin/opt/img/svg-app.png'),
                'svg-app-2'         => plugins_url('maxboxy/admin/opt/img/svg-app-2.png'),
                'svg-app-3'         => plugins_url('maxboxy/admin/opt/img/svg-app-3.png'),
                'svg-basket'        => plugins_url('maxboxy/admin/opt/img/svg-basket.png'),
                'svg-basket-+'      => plugins_url('maxboxy/admin/opt/img/svg-basket-+.png'),
                'svg-basket-+2'     => plugins_url('maxboxy/admin/opt/img/svg-basket-+2.png'),
                'svg-book'          => plugins_url('maxboxy/admin/opt/img/svg-book.png'),
                'svg-cookie'        => plugins_url('maxboxy/admin/opt/img/svg-cookie.png'),
                'svg-cookie-2'      => plugins_url('maxboxy/admin/opt/img/svg-cookies.png'),
                'svg-chat'          => plugins_url('maxboxy/admin/opt/img/svg-chat.png'),
                'svg-chat-2'        => plugins_url('maxboxy/admin/opt/img/svg-chat-2.png'),
                'svg-chat-3'        => plugins_url('maxboxy/admin/opt/img/svg-chat-3.png'),
                'svg-chat-4'        => plugins_url('maxboxy/admin/opt/img/svg-chat-4.png'),
                'svg-chat-5'        => plugins_url('maxboxy/admin/opt/img/svg-chat-5.png'),
                'svg-chats'         => plugins_url('maxboxy/admin/opt/img/svg-chats.png'),
                'svg-chats-2'       => plugins_url('maxboxy/admin/opt/img/svg-chats-2.png'),
                'svg-chats-3'       => plugins_url('maxboxy/admin/opt/img/svg-chats-3.png'),
                'svg-elipsis-h'     => plugins_url('maxboxy/admin/opt/img/svg-elipsis-h.png'),
                'svg-elipsis-v'     => plugins_url('maxboxy/admin/opt/img/svg-elipsis-v.png'),
                'svg-home'          => plugins_url('maxboxy/admin/opt/img/svg-home.png'),
                'svg-home-2'        => plugins_url('maxboxy/admin/opt/img/svg-home-2.png'),
                'svg-info'          => plugins_url('maxboxy/admin/opt/img/svg-info.png'),
                'svg-location'      => plugins_url('maxboxy/admin/opt/img/svg-location.png'),
                'svg-location-2'    => plugins_url('maxboxy/admin/opt/img/svg-location-2.png'),
                'svg-mail'          => plugins_url('maxboxy/admin/opt/img/svg-mail.png'),
                'svg-pin'           => plugins_url('maxboxy/admin/opt/img/svg-pin.png'),
                'svg-play'          => plugins_url('maxboxy/admin/opt/img/svg-play.png'),
                'svg-settings'      => plugins_url('maxboxy/admin/opt/img/svg-settings.png'),
                'svg-share'         => plugins_url('maxboxy/admin/opt/img/svg-share.png'),
                'svg-share-2'       => plugins_url('maxboxy/admin/opt/img/svg-share-2.png'),
                'svg-plus'          => plugins_url('maxboxy/admin/opt/img/svg-plus.png'),
                'svg-plus-2'        => plugins_url('maxboxy/admin/opt/img/svg-plus-2.png'),
            ),
            'default'       => 'no',
            'dependency'    => array('unset_toggler|button_open_img|panel_type','!=|==|any','all||toggler', '||true'),
        ),
        array(
            'id'            => 'button_open_img',
            'title'         => esc_html__('Opener image', 'maxboxy'),
            'desc'          => $img_desc,
            'type'          => 'media',
            'library'       => 'image',
            'dependency'    => array('unset_toggler|panel_type','!=|any','all|toggler', '|true'),
        ),
        array(
            'id'            => 'button_close_icon',
            'type'          => 'image_select',
            'title'         => esc_html__('Closer icon (CSS)', 'maxboxy'),
            'help'          => esc_html__('It\'s closing the panel', 'maxboxy'),
            'options'       => array(
                'iks-plus'          => plugins_url('maxboxy/admin/opt/img/i-plus.png'),
                'minus'             => plugins_url('maxboxy/admin/opt/img/i-minus.png'),
                'iks'               => plugins_url('maxboxy/admin/opt/img/i-iks.png'),
                'point-left'        => plugins_url('maxboxy/admin/opt/img/i-point-left.png'),
                'point-right'       => plugins_url('maxboxy/admin/opt/img/i-point-right.png'),
                'point-up'          => plugins_url('maxboxy/admin/opt/img/i-point-up.png'),
                'point-down'        => plugins_url('maxboxy/admin/opt/img/i-point-down.png'),
                'ham'               => plugins_url('maxboxy/admin/opt/img/i-ham.png'),
                'ham-f1'            => plugins_url('maxboxy/admin/opt/img/i-ham-2.png'),
                'ham-f2'            => plugins_url('maxboxy/admin/opt/img/i-ham-3.png'),
                'ham-f3'            => plugins_url('maxboxy/admin/opt/img/i-ham-4.png'),
                'ham-f4'            => plugins_url('maxboxy/admin/opt/img/i-ham-5.png'),
            ),
            'default'       => 'iks',
            'dependency'    => array('unset_toggler|button_close_svg|button_close_img','==|==|==','no|no|'),
        ),
        array(
            'id'            => 'button_close_svg',
            'type'          => 'image_select',
            'title'         => esc_html__('Closer SVG', 'maxboxy'),
            'desc'          => $svg_desc,
            'options'       => array(
                'no'                => plugins_url('maxboxy/admin/opt/img/svg-none.png'),
                'svg-app'           => plugins_url('maxboxy/admin/opt/img/svg-app.png'),
                'svg-app-2'         => plugins_url('maxboxy/admin/opt/img/svg-app-2.png'),
                'svg-app-3'         => plugins_url('maxboxy/admin/opt/img/svg-app-3.png'),
                'svg-basket'        => plugins_url('maxboxy/admin/opt/img/svg-basket.png'),
                'svg-basket-x'      => plugins_url('maxboxy/admin/opt/img/svg-basket-x.png'),
                'svg-basket--'      => plugins_url('maxboxy/admin/opt/img/svg-basket--.png'),
                'svg-book'          => plugins_url('maxboxy/admin/opt/img/svg-book.png'),
                'svg-cookie'        => plugins_url('maxboxy/admin/opt/img/svg-cookie.png'),
                'svg-cookie-2'      => plugins_url('maxboxy/admin/opt/img/svg-cookies.png'),
                'svg-chat'          => plugins_url('maxboxy/admin/opt/img/svg-chat.png'),
                'svg-chat-2'        => plugins_url('maxboxy/admin/opt/img/svg-chat-2.png'),
                'svg-chat-3'        => plugins_url('maxboxy/admin/opt/img/svg-chat-3.png'),
                'svg-chat-4'        => plugins_url('maxboxy/admin/opt/img/svg-chat-4.png'),
                'svg-chat-5'        => plugins_url('maxboxy/admin/opt/img/svg-chat-5.png'),
                'svg-chats'         => plugins_url('maxboxy/admin/opt/img/svg-chats.png'),
                'svg-chats-2'       => plugins_url('maxboxy/admin/opt/img/svg-chats-2.png'),
                'svg-chats-3'       => plugins_url('maxboxy/admin/opt/img/svg-chats-3.png'),
                'svg-elipsis-h'     => plugins_url('maxboxy/admin/opt/img/svg-elipsis-h.png'),
                'svg-elipsis-v'     => plugins_url('maxboxy/admin/opt/img/svg-elipsis-v.png'),
                'svg-home'          => plugins_url('maxboxy/admin/opt/img/svg-home.png'),
                'svg-home-2'        => plugins_url('maxboxy/admin/opt/img/svg-home-2.png'),
                'svg-info'          => plugins_url('maxboxy/admin/opt/img/svg-info.png'),
                'svg-location'      => plugins_url('maxboxy/admin/opt/img/svg-location.png'),
                'svg-location-2'    => plugins_url('maxboxy/admin/opt/img/svg-location-2.png'),
                'svg-mail'          => plugins_url('maxboxy/admin/opt/img/svg-mail.png'),
                'svg-pin'           => plugins_url('maxboxy/admin/opt/img/svg-pin.png'),
                'svg-play'          => plugins_url('maxboxy/admin/opt/img/svg-play.png'),
                'svg-settings'      => plugins_url('maxboxy/admin/opt/img/svg-settings.png'),
                'svg-share'         => plugins_url('maxboxy/admin/opt/img/svg-share.png'),
                'svg-share-2'       => plugins_url('maxboxy/admin/opt/img/svg-share-2.png'),
                'svg-minus'         => plugins_url('maxboxy/admin/opt/img/svg-minus.png'),
                'svg-minus-2'       => plugins_url('maxboxy/admin/opt/img/svg-minus-2.png'),
                'svg-x'             => plugins_url('maxboxy/admin/opt/img/svg-x.png'),
                'svg-x-2'           => plugins_url('maxboxy/admin/opt/img/svg-x-2.png'),
            ),
            'default'       => 'no',
            'dependency'    => array('unset_toggler|button_close_img','==|==', 'no|'),
        ),
        array(
            'id'            => 'button_close_img',
            'title'         => esc_html__('Closer image', 'maxboxy'),
            'desc'          => $img_desc,
            'type'          => 'media',
            'library'       => 'image',
            'dependency'    => array('unset_toggler','==', 'no'),
        ),
        array(
            'id'            => 'closer_size',
            'type'          => 'select',
            'title'         => esc_html__('Size', 'maxboxy'),
            'options'       => array(
                'size-m'    => esc_html__('Mini',   'maxboxy'),
                'size-s'    => esc_html__('Small',  'maxboxy'),
                'normal'    => esc_html__('Normal', 'maxboxy'),
                'size-l'    => esc_html__('Large',  'maxboxy'),
                'size-h'    => esc_html__('Huge',   'maxboxy'),
            ),
            'default'       => 'normal',
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'toggler_pos',
            'type'          => 'image_select',
            'title'         => esc_html__('Position', 'maxboxy'),
            //'help'          => esc_html__('', 'maxboxy'),
            'options'       => array(
                '1'          => plugins_url('maxboxy/admin/opt/img/pos-1.png'),
                '2'          => plugins_url('maxboxy/admin/opt/img/pos-2.png'),
                '3'          => plugins_url('maxboxy/admin/opt/img/pos-3.png'),
                '4'          => plugins_url('maxboxy/admin/opt/img/pos-4.png'),
                '5'          => plugins_url('maxboxy/admin/opt/img/pos-5.png'),
                '6'          => plugins_url('maxboxy/admin/opt/img/pos-6.png'),
                '7'          => plugins_url('maxboxy/admin/opt/img/pos-7.png'),
                '8'          => plugins_url('maxboxy/admin/opt/img/pos-8.png'),
                '9'          => plugins_url('maxboxy/admin/opt/img/pos-9.png'),
                '10'         => plugins_url('maxboxy/admin/opt/img/pos-10.png'),
                '11'         => plugins_url('maxboxy/admin/opt/img/pos-11.png'),
                '12'         => plugins_url('maxboxy/admin/opt/img/pos-12.png'),
            ),
            'default'       => '1',
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'closer_styling',
            'type'          => 'select',
            'title'         => esc_html__('Apply style(s)', 'maxboxy'),
            'desc'          => esc_html__('Multiple selections allowed (use control key).', 'maxboxy'),
            'help'          => esc_html__('Change the way the Toggler/closer button(s) are displayed.', 'maxboxy'),
            'options'       => array(
                'squared'   => esc_html__('Squared',      'maxboxy'),
                'rounded'   => esc_html__('Rounded',      'maxboxy'),
                'inside'    => esc_html__('Inside',       'maxboxy'),
                'bordered'  => esc_html__('Bordered',     'maxboxy'),
            ),
            'multiple'      => true,
            'attributes'    => array(
              'style'       => 'height:80px;'
            ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_additional_message',
            'type'          => 'textarea',
            'title'         => esc_html__('Toggler additional message', 'maxboxy'),
            'help'          => esc_html__('Set additional message that can appear along the trigger button.', 'maxboxy'),
            'dependency'    => array('unset_toggler|panel_type','!=|any','all|toggler', '|true'),
        ),
        array(
            'type'          => 'subheading',
            'content'       => esc_html__('Toggler/closer spacing', 'maxboxy'),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_margin',
            'type'          => 'spacing', 
            'title'         => esc_html__('Margin', 'maxboxy'),
            'help'          => esc_html__('It\'s relative to the current position, e.g. if the panel is positioned on the left/bottom affecting margins are left and bottom, other two values wouldn\'t take effect in that case.', 'maxboxy'),
            'after'         => esc_html__('Beneath override individual unit:', 'maxboxy'),
            'units'         => array( 'px', '%', 'em', 'rem', 'vh', 'vw' ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_margin_unit_top',
            'type'          => 'select',
            'placeholder'   => esc_html__('Top', 'maxboxy'),
            'options'       => array(
                //''    => '',
                'px'  => 'px',
                '%'   => '%',
                'em'  => 'em',
                'rem' => 'rem',
                'vw'  => 'vw',
                'vh'  => 'vh',
            ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_margin_unit_left',
            'type'          => 'select',
            'placeholder'   => esc_html__('Left', 'maxboxy'),
            'options'       => array(
                //''    => '',
                'px'  => 'px',
                '%'   => '%',
                'em'  => 'em',
                'rem' => 'rem',
                'vw'  => 'vw',
                'vh'  => 'vh',
            ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_margin_unit_right',
            'type'          => 'select',
            'placeholder'   => esc_html__('Right', 'maxboxy'),
            'options'       => array(
                //''    => '',
                'px'  => 'px',
                '%'   => '%',
                'em'  => 'em',
                'rem' => 'rem',
                'vw'  => 'vw',
                'vh'  => 'vh',
            ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'trigger_margin_unit_bottom',
            'type'          => 'select',
            'placeholder'   => esc_html__('Bottom', 'maxboxy'),
            'options'       => array(
                //''    => '',
                'px'  => 'px',
                '%'   => '%',
                'em'  => 'em',
                'rem' => 'rem',
                'vw'  => 'vw',
                'vh'  => 'vh',
            ),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'eliminate_margin_closer',
            'type'          => 'checkbox', 
            'title'         => esc_html__('Eliminate the margin on close button', 'maxboxy'),
            'desc'          => esc_html__('If checked, margin will be applied just on the opener button.', 'maxboxy'),
            'dependency'    => array('unset_toggler|panel_type','!=|any','all|toggler', '|true'),

        ),

    );

    // add toggler fields to the Tab.
    $set_toggler_tab = array(

        'title'     => esc_html__('Toggler', 'maxboxy'),
        'icon'      => 'fas fa-plus',
        'fields'    => array_merge($toggler_basic_tab, $trigger_anim_fields),

    );


    /*
     * Conversion.
     */

     // goals fields
    $tab_goals_fields = array(

        array(
            'id'            => 'goal',
            'type'          => 'select',
            'title'         => esc_html__('Set a goal', 'maxboxy'),
            'help'          => esc_html__('Set a goal you want to track/achieve, e.g. click on a particular button or form submit.', 'maxboxy'),
            'options'       => array(
                ''          => esc_html__('None',           'maxboxy'),
                'click'     => esc_html__('Click',          'maxboxy'),
                'submit'    => esc_html__('Form submit',    'maxboxy'),
            ),
            //'multiple'      => true,
            //'attributes'    => array(
                //  'style'       => 'height:80px;'
                //),
        ),
        array(
            'id'            => 'goal_form_submit',
            'type'          => 'select',
            'title'         => esc_html__('Form submit checker', 'maxboxy'),
            'options'       => array(
                'form_has_class'    => esc_html__('Form element has a class', 'maxboxy'),
                'panel_find_class'  => esc_html__('Broad check for a class',  'maxboxy'),
            ),
            'help'          => esc_html__('After the submit button is pressed, we\'re able to check for one of the options: 1. Form element has a specified class injected. 2. If the class is revealed somewhere in the panel, but either deeper in the form or outside the form (usually beneath the form).', 'maxboxy'),
            'default'       => 'form_has_class',
            'dependency'    => array('goal','any','submit'),
        ),
        array(
            'id'            => 'goal_click_target_attr',
            'type'          => 'radio',
            'title'         => esc_html__('Element\'s attribute', 'maxboxy'),
            'title'         => esc_html__('Target element by ID or class', 'maxboxy'),
            'options'       => array(
                'class'     => esc_html__('Class',   'maxboxy'),
                'id'        => esc_html__('ID',      'maxboxy'),
            ),
            'default'       => 'class',
            'inline'        => true,
            'dependency'    => array('goal','==','click'),
        ),
        array(
            'id'            => 'goal_attr_value',
            'type'          => 'text',
            'title'         => esc_html__('Enter attribute\'s value', 'maxboxy'),
            'help'          => esc_html__('E.g. target-element',   'maxboxy'),
            'dependency'    => array('goal','!=',''),
        ),
        array(
            'id'            => 'goal_after_banish',
            'type'          => 'checkbox',
            'title'         => esc_html__('Do not show the panel again', 'maxboxy'),
            'help'          => esc_html__('After the goal is met, do not show the panel for the same visitor', 'maxboxy'),
            'dependency'    => array('goal','!=',''),
        ),

    );

    // stats fields.
    $tab_stats_fields = array(

        array(
            'id'            => 'track_loggedin_users',
            'type'          => 'checkbox',
            'title'         => esc_html__('Track logged in users', 'maxboxy'),
            'help'          => esc_html__('By default, users who are logged in the Website are not tracked.', 'maxboxy'),
        ),
        array(
            'id'            => 'echo_stats',
            'type'          => 'callback',
            'function'      => 'maxboxy_stats_call',
        ),
        array(
            'id'            => 'stats_legend',
            'type'          => 'callback',
            'function'      => 'maxboxy_stats_legend',
        ),

    );

    $tab_goals_notactive = array(

            array(
                'type'     => 'notice',
                'style'    => 'info',
                'content'  => esc_html__('Conversions module has to be activated from the ', 'maxboxy') .'<a href="' .esc_url(admin_url('admin.php?page=maxboxy-settings#tab=modules')) .'" target="_self">' .__('global settings', 'maxboxy') .'</a>',
            ),

    );

    // based on Conversion module display fileds or not active meassage.
    $tab_goals_check       = ! empty($enabled_conversions) ? $tab_goals_fields : $tab_goals_notactive;
    $tab_stats_check       = ! empty($enabled_conversions) ? $tab_stats_fields : $tab_goals_notactive;

    // Add the fields in the Goals tab.
    $tab_goals = array(

        'title'     => esc_html__('Goals', 'maxboxy'),
        'icon'      => 'fas fa-bullseye',
        'fields'    => $tab_goals_check,

    );


    /*
     * Stats tab.
     */
    $tab_track = array(

        'title'     => esc_html__('Stats', 'maxboxy'),
        'icon'      => 'fas fa-chart-bar',
        'fields'    => $tab_stats_check,

    );
