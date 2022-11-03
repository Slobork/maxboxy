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
                                    'help'          => esc_html__('Load the panel only for the logged in users who can edit posts, i.e. you.', 'booster-sweeper'),
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


    $add_classes_field  = function_exists('maxboxy_metabox_settings_basic_pro') && ! empty(maxboxy_metabox_settings_basic_pro()[ 'add_classes' ])
                        ? maxboxy_metabox_settings_basic_pro()[ 'add_classes' ]
                        : $empty_field;

    
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
            array(
                'id'            => 'mark_hoverout_closing',
                'type'          => 'checkbox',
                'title'         => esc_html__('Label mark for closing', 'maxboxy'),
                'help'          => esc_html__('Text will be added marking that the panel will be closed on mouse move out of the box.', 'maxboxy'),
                'dependency'    => array('panel_type|panel_roles','any|any','closer|role-hoverer'),
            ),
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
            array(
                'id'            => 'mark_hoverout_closing',
                'type'          => 'checkbox',
                'title'         => esc_html__('Label for hover closing', 'maxboxy'),
                'help'          => esc_html__('Text will be added marking that the panel will be closed on mouse move out of the box.', 'maxboxy'),
                'dependency'    => array('panel_type|panel_roles','any|any','closer|role-hoverer'),
            ),
            $add_classes_field,

        )

    );



    /*
     * Colors tab.
     */

     /* Floatany */
    // get default values from the Admin global settings
    $floatany_bg_bc                 = isset(get_option('_maxboxy_options')[ 'floatany_bg' ][ 'background-color' ])
                                    ?       get_option('_maxboxy_options')[ 'floatany_bg' ][ 'background-color' ] : '';

    $floatany_bg_bc_default         = ! empty($floatany_bg_bc) ? $floatany_bg_bc : '#e8e2b7';

    $floatany_panel_color           = isset(get_option('_maxboxy_options')[ 'floatany_color' ])
                                    ?       get_option('_maxboxy_options')[ 'floatany_color' ] : '';

    $floatany_color_default         = ! empty($floatany_panel_color) ? $floatany_panel_color : '#4b4b4b';


    $floatany_shut_bg               = isset(get_option('_maxboxy_options')[ 'floatany_shut_bg' ])
                                    ?       get_option('_maxboxy_options')[ 'floatany_shut_bg' ] : '';

    $floatany_shut_bg_default       = ! empty($floatany_shut_bg) ? $floatany_shut_bg : '#333333';


    $floatany_shut_color            = isset(get_option('_maxboxy_options')[ 'floatany_shut_color' ])
                                    ?       get_option('_maxboxy_options')[ 'floatany_shut_color' ] : '';

    $floatany_shut_color_default    = ! empty($floatany_shut_color) ? $floatany_shut_color : '#ffffff';

    // set colors tab
    $colors_tab_floatany = array(

        'title'     => esc_html__('Colors', 'maxboxy'),
        'icon'      => 'fas fa-paint-brush',
        'fields'    => array(

            array(
                'id'            => 'panel_popup_bg',
                'type'          => 'background',
                'title'         => $panel_bg_title, // var determined at framework.config
                'default'       => array(
                                'background-color' => $floatany_bg_bc_default,
                ),
            ),
            array(
                'id'            => 'panel_popup_color',
                'type'          => 'color',
                'title'         => $panel_col_title,
                'default'       => $floatany_color_default,
            ),
            array(
                'type'          => 'subheading',
                'content'       => $closer_subheading,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_bg',
                'type'          => 'color',
                'title'         => $closer_bg_title,
                'default'       => $floatany_shut_bg_default,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_color',
                'type'          => 'color',
                'title'         => $closer_col_title,
                'default'       => $floatany_shut_color_default,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),

        )

    );

    /* Injectany */
    // get default values from the settings
    $injectany_bg_bc                = isset(get_option('_maxboxy_options')[ 'injectany_bg' ][ 'background-color' ])
                                    ?       get_option('_maxboxy_options')[ 'injectany_bg' ][ 'background-color' ] : '';

    $injectany_bg_bc_default        = ! empty($injectany_bg_bc) ? $injectany_bg_bc : '#e8e2b7';

    $injectany_panel_color          = isset(get_option('_maxboxy_options')[ 'injectany_color' ])
                                    ?       get_option('_maxboxy_options')[ 'injectany_color' ] : '';

    $injectany_color_default        = ! empty($injectany_panel_color) ? $injectany_panel_color : '#4b4b4b';

    $injectany_shut_bg              = isset(get_option('_maxboxy_options')[ 'injectany_shut_bg' ])
                                    ?       get_option('_maxboxy_options')[ 'injectany_shut_bg' ] : '';

    $injectany_shut_bg_default      = ! empty($injectany_shut_bg) ? $injectany_shut_bg : '#333333';


    $injectany_shut_color           = isset(get_option('_maxboxy_options')[ 'injectany_shut_color' ])
                                    ?       get_option('_maxboxy_options')[ 'injectany_shut_color' ] : '';

    $injectany_shut_color_default   = ! empty($injectany_shut_color) ? $injectany_shut_color : '#ffffff';


    // set colors tab
    $colors_tab_injectany = array(

        'title'     => esc_html__('Colors', 'maxboxy'),
        'icon'      => 'fas fa-paint-brush',
        'fields'    => array(
            array(
                'id'            => 'panel_popup_bg',
                'type'          => 'background',
                'title'         => $panel_bg_title, // var determined at framework.config
                'default'       => array(
                                'background-color' => $injectany_bg_bc_default,
                ),
            ),
            array(
                'id'            => 'panel_popup_color',
                'type'          => 'color',
                'title'         => $panel_col_title,
                'default'       => $injectany_color_default,
            ),
            array(
                'type'          => 'subheading',
                'content'       => $closer_subheading,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_bg',
                'type'          => 'color',
                'title'         => $closer_bg_title,
                'default'       => $injectany_shut_bg_default,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_color',
                'type'          => 'color',
                'title'         => $closer_col_title,
                'default'       => $injectany_shut_color_default,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
        )

    );


    /* Reusable blocks */
    // get default values from the settings
    $reusable_bg_bc                 = isset(get_option('_maxboxy_options')[ 'reusable_bg' ][ 'background-color' ])
                                    ?       get_option('_maxboxy_options')[ 'reusable_bg' ][ 'background-color' ] : '';

    $reusable_bg_bc_default         = ! empty($reusable_bg_bc) ? $reusable_bg_bc : '#e8e2b7';

    $reusable_panel_color           = isset(get_option('_maxboxy_options')[ 'reusable_color' ])
                                    ?       get_option('_maxboxy_options')[ 'reusable_color' ] : '';

    $reusable_color_default         = ! empty($reusable_panel_color) ? $reusable_panel_color : '#4b4b4b';

    $reusable_shut_bg               = isset(get_option('_maxboxy_options')[ 'reusable_shut_bg' ])
                                    ?       get_option('_maxboxy_options')[ 'reusable_shut_bg' ] : '';

    $reusable_shut_bg_default       = ! empty($reusable_shut_bg) ? $reusable_shut_bg : '#333333';


    $reusable_shut_color            = isset(get_option('_maxboxy_options')[ 'reusable_shut_color' ])
                                    ?       get_option('_maxboxy_options')[ 'reusable_shut_color' ] : '';

    $reusable_shut_color_default    = ! empty($reusable_shut_color) ? $reusable_shut_color : '#ffffff';


    // set colors tab
    $colors_tab_reusable = array(

        'title'     => esc_html__('Colors', 'maxboxy'),
        'icon'      => 'fas fa-paint-brush',
        'fields'    => array(
            array(
                'id'            => 'panel_popup_bg',
                'type'          => 'background',
                'title'         => $panel_bg_title, // var determined at framework.config
                'default'       => array(
                                'background-color' => $reusable_bg_bc_default,
                ),
            ),
            array(
                'id'            => 'panel_popup_color',
                'type'          => 'color',
                'title'         => $panel_col_title,
                'default'       => $reusable_color_default,
            ),
            array(
                'type'          => 'subheading',
                'content'       => $closer_subheading,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_bg',
                'type'          => 'color',
                'title'         => $closer_bg_title,
                'default'       => $reusable_shut_bg_default,
                'dependency'    => array('unset_toggler','!=','true', true),
            ),
            array(
                'id'            => 'panel_shut_color',
                'type'          => 'color',
                'title'         => $closer_col_title,
                'default'       => $reusable_shut_color_default,
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
            'help'          => esc_html__('You can still utilize the in-content closing/toggling buttons, no matter which option you set here.', 'maxboxy'),
            'options'       => array(
                'no'        => esc_html__('Use',        'maxboxy'),
                'closer'    => esc_html__('No Closer',  'maxboxy'),
                'all'       => esc_html__('Remove',     'maxboxy'),
            ),
            'default'       => 'no',
            'class'         => 'mb-unset-toggling-default',
        ),
        array(
            'id'            => 'button_open',
            'type'          => 'select',
            'title'         => esc_html__('Opener icon', 'maxboxy'),
            'help'          => esc_html__('It\'s opening the panel', 'maxboxy'),
            'options'       => array(
                'iks-plus'          => esc_html__('Plus (+)',           'maxboxy'),
                'minus'             => esc_html__('Minus (-)',          'maxboxy'),
                'point-left'        => esc_html__('Point left (<) ',    'maxboxy'),
                'point-right'       => esc_html__('Point right (>) ',   'maxboxy'),
                'point-up'          => esc_html__('Point up (^) ',      'maxboxy'),
                'point-down'        => esc_html__('Point down (ˇ) ',    'maxboxy'),
                'ham'               => esc_html__('Hamburger (=) ',     'maxboxy'),
                'ham-f1'            => esc_html__('Hamburger 2 (=) ',   'maxboxy'),
                'ham-f2'            => esc_html__('Hamburger 3 (=) ',   'maxboxy'),
                'ham-f3'            => esc_html__('Hamburger 4 (=) ',   'maxboxy'),
                'ham-f4'            => esc_html__('Hamburger 5 (=) ',   'maxboxy'),
            ),
            'default'       => 'iks-plus',
            'dependency'    => array('unset_toggler|panel_type','!=|any','all|toggler', '|true'),
        ),
        array(
            'id'            => 'button_close',
            'type'          => 'select',
            'title'         => esc_html__('Closer icon', 'maxboxy'),
            'help'          => esc_html__('It\'s closing the panel', 'maxboxy'),
            'options'       => array(
                'iks-plus'          => esc_html__('Plus (+)',           'maxboxy'),
                'minus'             => esc_html__('Minus (-)',          'maxboxy'),
                'iks'               => esc_html__('Closer (x)',         'maxboxy'),
                'point-left'        => esc_html__('Point left (<) ',    'maxboxy'),
                'point-right'       => esc_html__('Point right (>) ',   'maxboxy'),
                'point-up'          => esc_html__('Point up (^) ',      'maxboxy'),
                'point-down'        => esc_html__('Point down (ˇ) ',    'maxboxy'),
                'ham'               => esc_html__('Hamburger (=) ',     'maxboxy'),
                'ham-f1'            => esc_html__('Hamburger 2 (=) ',   'maxboxy'),
                'ham-f2'            => esc_html__('Hamburger 3 (=) ',   'maxboxy'),
                'ham-f3'            => esc_html__('Hamburger 4 (=) ',   'maxboxy'),
                'ham-f4'            => esc_html__('Hamburger 5 (=) ',   'maxboxy'),
            ),
            'default'       => 'iks',
            'dependency'    => array('unset_toggler','==','no'),
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
            'type'          => 'subheading',
            'style'         => 'normal',
            'content'       => esc_html__('Positioning', 'maxboxy'),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'direction',
            'type'          => 'button_set',
            'title'         => esc_html__('Position - Direction', 'maxboxy'),
            'help'          => esc_html__('It is relative to the panel\'s content box, i.e. set would the toggler/closer appear beneath (vertical) or next (horizontal) to the adjacent content box.', 'maxboxy'),
            'options'       => array(
                'row'       => esc_html__('Horizontal', 'maxboxy'),
                'column'    => esc_html__('Vertical',   'maxboxy'),
            ),
            'default'       => 'row',
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'reverse_direction',
            'type'          => 'checkbox',
            'title'         => esc_html__('Position - Reverse order', 'maxboxy'),
            'help'          => esc_html__('Basicaly, toggler/closer button will switch the position with the content box based on the set direction. If checked, toggler/closer button will take the 1st place in order.', 'maxboxy'),
            'dependency'    => array('unset_toggler','!=','all'),
        ),
        array(
            'id'            => 'closer_align',
            'type'          => 'button_set',
            'title'         => esc_html__('Position - alignment', 'maxboxy'),
            'help'          => esc_html__('Align toggler/closer button relative to the content box. In essence, alignment takes different course in regards to the "Direction". This means, if "Direction" is set as "Horizontal", "Alignment" will take vertical course. And vice-versa, if "Direction" is set to "Vertical", "Alignment" will take horizontal course.', 'maxboxy'),
            'options'       => array(
                                    'start'  => esc_html__('Start',     'maxboxy'),
                                    'center' => esc_html__('Center',    'maxboxy'),
                                    'end'    => esc_html__('End',       'maxboxy'),
                                ),
            'default'       => 'start',
            'dependency'    => array('unset_toggler','!=','all'),
        ),

        array(
            'type'          => 'subheading',
            'content'       => esc_html__('Styling', 'maxboxy'),
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
        /* @todo this option will need improvment - offering a couple of more optopns - set with select field
        array(
            'id'            => 'trigger_anim',
            'type'          => 'checkbox',
            'title'         => esc_html__('Trigger animation', 'maxboxy'),
            'help'          => esc_html__('Trigger button (icon) will be animated (rotated)', 'maxboxy'),
            //'dependency'    => array('panel_type','any','toggler,igniter', true),
            //'dependency'    => array('unset_toggler|panel_type','!=|any','true|toggler,igniter', '|true'),
            'dependency'    => array('unset_toggler','!=','true'),

        ),
        */
    );

    // add toggler fields to the Tab.
    $set_toggler_tab = array(

        'title'     => esc_html__('Toggler', 'maxboxy'),
        'icon'      => 'fas fa-plus',

        // if in the future we add any feature available with Pro verion use the following line:
        //'fields'    => array_merge( $toggler_basic_tab, $toggler_advanced ),
        'fields'    => $toggler_basic_tab,

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
