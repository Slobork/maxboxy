<?php
// phpcs:ignore
/**
 * Description: Admin Options - for the Framework config.
 */

if (! defined('ABSPATH')) {
    exit;
}

    /*
     * Set a unique slug-like ID.
     */
    $prefix = '_maxboxy_options';

    CSF::createOptions(
        $prefix,
        array(
            /*
             * 'menu_type' => 'menu' isn't the best solution, here's why...
             * If set as main 'menu' and post types (inject_any, float_any) are added
             * to it, setting 'show_sub_menu' => false - won't work,
             * i.e. it only lists all Tabs of the Options panel as submenus.
             * ...So, that's why I have set 'admin_menu' for it,
             * @see Max_Boxy::admin_menu(). It acts as a parent of this submenu.
             */
            'menu_type'         => 'submenu',
            'menu_parent'       => 'maxboxy-settings', // created at @see Max_Boxy::admin_menu().
            /*
             * 'menu_title' => esc_html__( 'Settings', 'maxboxy' ), // this won't work,
             * instead it uses the title of the registered admin menu item
             * from the Max_Boxy::admin_menu().
             */
            'menu_slug'         => 'maxboxy-settings', // has to be the same as registered top menu item on the Max_Boxy::admin_menu()
            'framework_title'   => 'MaxBoxy',
            'footer_credit'     => 'MaxBoxy <small> v1.0.3</small>',
            'footer_text'       => ' ',
            'theme'             => 'light',
            'show_bar_menu'     => false,
            //'ajax_save'       => false, // in order for validation to work this would need to be false
            //'save_defaults'   => false, // this must be on default, i.e. 'true' in order to skip printing empty floatany-inline-css
            'output_css'        => false,
            'enqueue_webfont'   => false,
        )
    );


    /*
     * VARS for the Framework config
     */

    // Replaces the fields available in the pro version.
    $empty_field = array(
        'type'      => 'content',
        'content'   => '',
        'class'     => 'floatany-empty-field',
    );

    /*
     * You can also add a class to any field to hide it in the future, 
     * if there's a need.
     * ...If the Pro version is deactivated, in order to keep its settings, just
     * add the "empty_class" to hide the field when its just the base plugin active
     */
    $empty_class = array( 'class' => 'floatany-empty-field');

    if (! is_multisite() || (is_multisite() && is_main_site())) {

        $uninstall_setting = array(
                                'id'        => 'uninstall_setting',
                                'type'      => 'checkbox',
                                'title'     => esc_html__('Uninstall settings if plugin is deleted', 'maxboxy'),
                                'help'      => esc_html__('If this is checked, all FloatAny seetings will be deleted when the plugin is removed, otherwise the settings will be preserved.', 'maxboxy'),
        );

        // else - multisite but not the main site
    } else {
        $uninstall_setting = $empty_field;
    }

    $upgrade_notice = esc_html__('Upgrade to pro version!', 'maxboxy');



    /*
     * Differentiate options availabe on the basic & the pro version
     */

    // If the splitter plugin is active.
    if (function_exists('maxboxy_framework_settings_splitter')) {

        $splitter_module = maxboxy_framework_settings_splitter();

        // else - if the splitter plugin isn't active
    } else {

        $splitter_module = array(

        /*
         *   // @todo The following should be set once the Splitter is developped and the $empty_field removed:
         *   array(
         *       'type'      => 'subheading',
         *       'content'   => esc_html__( 'A/B Splitter', 'maxboxy' ),
         *   ),
         *   array(
         *       'type'       => 'notice',
         *       'style'      => 'info',
         *       'content'    => esc_html__( 'Splitter needs separate addon active - ', 'maxboxy' ) .'<a href="https://maxpressy.com/maxboxy/splitter/" target="_blank">' .__( 'See Splitter addon', 'maxboxy' ) .'</a>',
         *   ),
        */

            $empty_field

        );
    }

    // if the pro plugin is active
    if (function_exists('maxboxy_framework_settings_pro_modules')) {

        $pro_modules = maxboxy_framework_settings_pro_modules();

        // else - if the pro plugin isn't active
    } else {

        $pro_modules = array(

            array(
                'type'      => 'subheading',
                'content'   => esc_html__('Conditionals', 'maxboxy'),
            ),
            array(
                'type'       => 'notice',
                'style'      => 'info',
                'content'    => $upgrade_notice,
            ),
            array(
                'type'      => 'subheading',
                'content'   => esc_html__('Duplicator', 'maxboxy'),
            ),
            array(
                'type'       => 'notice',
                'style'      => 'info',
                'content'    => $upgrade_notice,
            ),

        );

    }


    /*
     * Begin options
     */


    /*
     * General Options.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('General', 'maxboxy'),
            'icon'   => 'fa fa-home',
            'fields' => array(
                array(
                    'id'            => 'enqueue_place',
                    'type'          => 'button_set',
                    'title'         => esc_html__('Loading plugin files', 'maxboxy'),
                    'help'          => esc_html__('"Default" means that files will be loaded over the whole site, no matter if the FloatAny panels are utilized on those pages. "On demand" option loads plugin files only on pages where the panels are apearing, but bypassing default WordPress enqueuing.', 'maxboxy'),
                    'options'       => array(
                                            'overall'   => esc_html__('Site overall',   'maxboxy'),
                                            'on_demand' => esc_html__('On demand',      'maxboxy'),
                                    ),
                    'default'       => 'overall',
                    'inline'        => true,
                ),
                array(
                    'id'            => 'large_screen_break_point',
                    'type'          => 'slider',
                    'title'         => esc_html__('Large screen breaking point', 'maxboxy'),
                    'help'          => esc_html__('From entered point onward, it\'s considered to be the large screen. There are the options that depend on this. Default value is "992".', 'maxboxy'),
                    'default'       => 992,
                    'min'           => 200,
                    'max'           => 3000,
                    'unit'          => 'px',
                    'validate'      => 'csf_validate_numeric',
                    'sanitize'      => 'absint',
                ),
                array(
                    'id'            => 'zindex',
                    'type'          => 'number',
                    'placeholder'   => 999,
                    'title'         => esc_html__('Z-index', 'maxboxy'),
                    'help'          => esc_html__('If other element is overlapping the box, with "z-index" give a higher priority to the box in a stack order. Default is quite high: 999, most likely you won\'t neet to overwrite it. Still, here you can enter the new default value. Further, you can override that for each panel from its settings.', 'maxboxy'),
                ),
                array(
                    'type'      => 'heading',
                    'content'   => esc_html__('Other', 'maxboxy'),
                ),
                $uninstall_setting,
            )
        )
    );


    /**
     * Colors tab.
     */
    $panel_bg_title     = esc_html__('Panel\'s background', 'maxboxy');
    $panel_col_title    = esc_html__('Panel\'s text color', 'maxboxy');
    $closer_subheading  = esc_html__('Close/toggle button', 'maxboxy');
    $closer_bg_title    = esc_html__('Background', 'maxboxy');
    $closer_col_title   = esc_html__('Color', 'maxboxy');

    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('Colors', 'maxboxy'),
            'icon'   => 'fas fa-palette',
            'fields' => array(
                array(
                    'type'      => 'heading',
                    'content'   => 'FloatAny',
                ),
                array(
                    'type'      => 'content',
                    'content'   => esc_html__('Colors applied here act as default for FloatAny panels. Further, you can override that for each panel from its settings.', 'maxboxy'),
                ),
                array(
                    'id'        => 'floatany_bg',
                    'type'      => 'background',
                    'title'     => $panel_bg_title,
                    'default'   => array(
                                    'background-color' => '#e8e2b7',
                    ),
                ),
                array(
                    'id'        => 'floatany_color',
                    'type'      => 'color',
                    'title'     => $panel_col_title,
                    'default'   => '#4b4b4b',
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => $closer_subheading,
                ),
                array(
                    'id'        => 'floatany_shut_bg',
                    'type'      => 'color',
                    'title'     => $closer_bg_title,
                    'default'   => '#333333',
                ),
                array(
                    'id'        => 'floatany_shut_color',
                    'type'      => 'color',
                    'title'     => $closer_col_title,
                    'default'   => '#ffffff',
                ),
                array(
                    'type'      => 'heading',
                    'content'   => 'InjectAny',
                ),
                array(
                    'type'      => 'content',
                    'content'   => esc_html__('Colors applied here act as default for InjectAny panels. Further, you can override that for each panel from its settings.', 'maxboxy'),
                ),
                array(
                    'id'        => 'injectany_bg',
                    'type'      => 'background',
                    'title'     => $panel_bg_title,
                    'default'   => array(
                                    'background-color' => '#e8e2b7',
                    ),
                ),
                array(
                    'id'        => 'injectany_color',
                    'type'      => 'color',
                    'title'     => $panel_col_title,
                    'default'   => '#4b4b4b',
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => $closer_subheading,
                ),
                array(
                    'id'        => 'injectany_shut_bg',
                    'type'      => 'color',
                    'title'     => $closer_bg_title,
                    'default'   => '#333333',
                ),
                array(
                    'id'        => 'injectany_shut_color',
                    'type'      => 'color',
                    'title'     => $closer_col_title,
                    'default'   => '#ffffff',
                ),
                array(
                    'type'      => 'heading',
                    'content'   => esc_html__('Reusable blocks', 'maxboxy'),
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'type'      => 'content',
                    'content'   => esc_html__('Colors applied here act as default for Reusable block. Further, you can override that for each panel from its settings.', 'maxboxy'),
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'id'        => 'reusable_bg',
                    'type'      => 'background',
                    'title'     => $panel_bg_title,
                    'default'   => array(
                        'background-color' => '#e8e2b7',
                    ),
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'id'        => 'reusable_color',
                    'type'      => 'color',
                    'title'     => $panel_col_title,
                    'default'   => '#4b4b4b',
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => $closer_subheading,
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'id'        => 'reusable_shut_bg',
                    'type'      => 'color',
                    'title'     => $closer_bg_title,
                    'default'   => '#333333',
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
                array(
                    'id'        => 'reusable_shut_color',
                    'type'      => 'color',
                    'title'     => $closer_col_title,
                    'default'   => '#ffffff',
                    'dependency'=> array('enable_wp_block','==','true', true),
                ),
            )
        )
    );


    /*
     * Strains tab.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('Strains', 'maxboxy'),
            'icon'   => 'fas fa-project-diagram',
            'fields' => array_merge(
                array(
                    array(
                        'type'      => 'content',
                        'content'   => esc_html__('Panel strains are different branches of MaxBoxy (with FloatAny build popups, with InjectAny build in-content panels). Further you can enable MaxBoxy options for WordPress built in feature, i.e. Reusable blocks.', 'maxboxy'),
                    ),
                    array(
                        'type'      => 'content',
                        'content'   => esc_html__('Create InjectAny panels from ', 'maxboxy') .'<a href="' .esc_url(admin_url('edit.php?post_type=inject_any')) .'">' .__('here') .'</a>',
                    ),
                    array(
                        'type'      => 'content',
                        'content'   => esc_html__('Create FloatAny panels from ', 'maxboxy') .'<a href="' .esc_url(admin_url('edit.php?post_type=float_any')) .'">' .__('here') .'</a>',
                    ),
                    array(
                        'id'        => 'enable_wp_block',
                        'type'      => 'switcher',
                        'title'     => esc_html__('Enable MaxBoxy for Reusable blocks', 'maxboxy'),
                        'subtitle'  => esc_html__('"Reusable blocks" is WordPress built in feature which output is very similar to our InjectAny. With MaxBoxy you can enhance ', 'maxboxy') .'<a href="' .esc_url(admin_url('edit.php?post_type=wp_block')) .'">' .__('Reusable blocks') .'</a>. See documentation for differences.',
                    ),
                )
            )
        )
    );


    /*
     * Modules tab.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('Modules', 'maxboxy'),
            'icon'   => 'fas fa-puzzle-piece',
            'fields' => array_merge(
                array(
                    array(
                        'type'      => 'content',
                        'content'   => esc_html__('Enabled modules are avaliable across multiple panel strains (InjectAny, FloatAny, Reusable blocks).', 'maxboxy'),
                    ),
                    array(
                        'type'      => 'subheading',
                        'content'   => esc_html__('Conversions', 'maxboxy'),
                    ),
                    array(
                        'type'      => 'content',
                        'content'   => esc_html__('Conversions module gives you oportunity to set the goals with each panel, but also some other options depend on it. Basically any panel\'s feature, that is dependant on the browser\'s local storage API (which is a feature similar to Cookies, but in a bit modern way), will require Conversions module to be active. See documentation for more info.', 'maxboxy'),
                    ),
                    array(
                        'id'        => 'enable_conversions',
                        'type'      => 'switcher',
                        'title'     => esc_html__('Enable Conversions module', 'maxboxy'),
                    ),
                ),
                $pro_modules,
                $splitter_module
            )
        )
    );


    // if the pro license isn't activated
    $get_license = class_exists('Max_Boxy_Pro') && Max_Boxy_Pro::getLicense() !== '' ? true : false;
    if ($get_license === false) {

        /*
         * Upgrade tab.
         */
        CSF::createSection(
            $prefix, array(
                'title'  => esc_html__('Upgrade', 'maxboxy'),
                'icon'   => 'fas fa-sign-in-alt',
                'fields' => array(
                    array(
                        'type'      => 'callback',
                        'function'  => 'maxboxy_upgrade_call',
                    ),
                )
            )
        );

    }


    /**
     * Backup tab.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('Export/Import', 'maxboxy'),
            'icon'   => 'fas fa-save',
            'fields' => array(
                array(
                    'type'      => 'backup',
                    'sanitize'  => 'sanitize_text_field',
                ),
            )
        )
    );
