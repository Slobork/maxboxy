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
    $MB_pro_version = defined('MAXBOXY_PRO_VERSION') ? MAXBOXY_PRO_VERSION : '';
    $print_pro_version = $MB_pro_version !== '' ? ' - Pro Version: ' .MAXBOXY_PRO_VERSION : '';
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
            'footer_credit'     => 'MaxBoxy <small> ' .MAXBOXY_VERSION .$print_pro_version.'</small>',
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
                    'help'          => esc_html__('"Default" means that files will be loaded over the whole site, no matter if the FloatAny/InjectAny panels are utilized on those pages. "On demand" option loads plugin files only on pages where the panels are apearing, but bypassing default WordPress enqueuing and that will output the styles in the "body" tag instaed of the "head" which may break HTML validity. To keep HTML validity, it may be the best if you set the "Site overall" option here and use a plugin like Booster Sweeper to unload the files you do not need on certain pages.', 'maxboxy'),
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
                    'help'          => esc_html__('From entered point onward, it\'s considered to be the large screen. There are the options that depend on this, i.e. hiding content blocks for small or large screen. Default value is "992".', 'maxboxy'),
                    'default'       => 992,
                    'min'           => 200,
                    'max'           => 3000,
                    'unit'          => 'px',
                    'validate'      => 'csf_validate_numeric',
                    'sanitize'      => 'absint',
                ),
                array(
                    'id'        => 'modal_offer',
                    'type'      => 'button_set',
                    'title'     => esc_html__('Load in modal starting panel patterns', 'maxboxy'),
                    'help'      => esc_html__('When you\'re starting a new panel design, a modal popup will be presented with selected starting panel patterns.', 'maxboxy'),
                    'options'   => array(
                        'yes'   => esc_html__('Yes',   'maxboxy'),
                        'no'    => esc_html__('No',    'maxboxy'),
                ),
                'default'       => 'yes',
                'inline'        => true,
                ),
                array(
                    'id'        => 'remove_wpautop',
                    'type'      => 'switcher',
                    'title'     => esc_html__('WP autop', 'maxboxy'),
                    'desc'      => esc_html__('We remove the empty paragraphs when WordPress auto-inject them. Here you can disable that. Recommended: keep it prevented.', 'maxboxy'),
                    'help'      => esc_html__('By default we prevent wpautop. WordPress somethimes have a habit to inject excesive empty paragraphs. We remove this possibility. However, it is affecting the whole site, not just the MaxBoxy panels, so you can turn off this if from any reason is necessary.', 'maxboxy'),
                    'text_on'   => esc_html__('Allowed', 'maxboxy'),
                    'text_off'  => esc_html__('Prevent', 'maxboxy'),
                    'text_width'=> 120,
                ),
                array(
                    'type'      => 'heading',
                    'content'   => esc_html__('Other', 'maxboxy'),
                ),
                $uninstall_setting,
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
                        'content'   => esc_html__('Panel strains are different branches of MaxBoxy (with FloatAny build popups, with InjectAny build in-content panels). Further you can enable MaxBoxy options for WordPress built in feature, i.e. Synced Patterns.', 'maxboxy'),
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
                        'title'     => esc_html__('Enable MaxBoxy for Synced Patterns', 'maxboxy'),
                        'subtitle'  => esc_html__('"Synced Patterns" is WordPress built in feature which output is very similar to our InjectAny. With MaxBoxy you can enhance ', 'maxboxy') .'<a href="' .esc_url(admin_url('edit.php?post_type=wp_block')) .'">' .__('Synced Patterns') .'</a>. See documentation for differences.',
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
                        'content'   => esc_html__('Enabled modules are avaliable across multiple panel strains (InjectAny, FloatAny, Synced Patterns).', 'maxboxy'),
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


    $import_export = esc_html__('Import/Export', 'maxboxy');

    /**
     * Backup tab.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => $import_export,
            'icon'   => 'fas fa-save',
            'fields' => array(
                array(
                    'type'      => 'backup',
                    'sanitize'  => 'sanitize_text_field',
                ),
            )
        )
    );


    /**
     * Docs.
     */
    CSF::createSection(
        $prefix, array(
            'title'  => esc_html__('Documentation', 'maxboxy'),
            'icon'   => 'fas fa-book',
            'fields' => array(
                array(
                    'type'      => 'subheading',
                    'content'   => esc_html__('Each section gives you specific options:', 'maxboxy'),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => esc_html__('General', 'maxboxy'),
                ),
                array(
                    'type'      => 'submessage',
                    'content'   => esc_html__('- Overall options that affect all panels.', 'maxboxy'),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => esc_html__('Strains', 'maxboxy'),
                ),
                array(
                    'type'      => 'submessage',
                    'content'   => esc_html__('- Strains represent different panel types, i.e. InjectAny and FloatAny that are enabled by default. Additionally, you can enable MaxBoxy options for the Synced Patterns.', 'maxboxy'),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => esc_html__('Modules', 'maxboxy'),
                ),
                array(
                    'type'      => 'submessage',
                    'content'   => esc_html__('- Once enabled, modules bring specific functionality and additional options to the MaxBoxy panels.', 'maxboxy'),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => $import_export,
                ),
                array(
                    'type'      => 'submessage',
                    'content'   => esc_html__('- Easily move the common settings from one to another Website.', 'maxboxy'),
                ),
                array(
                    'type'      => 'subheading',
                    'content'   => esc_html__('Quick start creating panels', 'maxboxy'),
                ),
                array(
                    'type'      => 'submessage',
                    'content'   => esc_html__('- From the left navigation menu locate MaxBoxy. Pick InjectAny or FloatAny, then select the "Add new" to commence panel creation.', 'maxboxy'),
                ),
                array(
                    'type'      => 'notice',
                    'style'     => 'info',
                    'content'   => esc_html__('See ', 'maxboxy') .'<a href="https://maxpressy.com/maxboxy/documentation/" target="_blank">' .__('the whole documentation', 'maxboxy') .'</a>' .esc_html__(' for illustrated details. ', 'maxboxy'),
                ),
            )
        )
    );

