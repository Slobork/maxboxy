<?php
/**
 * Description: Metabox options
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

    /*
     * Set the most of the options in a separate file, seems as better readability.
     */
    require_once 'mb-options.php';


    $reusable_blocks_enabled = isset(get_option('_maxboxy_options')[ 'enable_wp_block' ])
                             ?       get_option('_maxboxy_options')[ 'enable_wp_block' ] : '';

    $add_reusable_blocks              = ! empty($reusable_blocks_enabled) ? array('wp_block') : array();
    $colors_tab_injectany_or_reusable = ! empty($reusable_blocks_enabled) && get_post_type($get_id) === 'wp_block' ? $colors_tab_reusable : $colors_tab_injectany;

    /*
     * Loading - Inject Any.
     */
    CSF::createMetabox(
        '_mb_injectany_loading', array(
            'title'     => esc_html__('Loading', 'maxboxy'),
            'post_type' => array( 'inject_any' ),
            'context'   => 'side',
            'priority'  => 'high',
            'data_type' => 'unserialize',
        )
    );


    CSF::createSection(
        '_mb_injectany_loading', array(
           //'title'      => esc_html__('Load', 'maxboxy'),
           'fields' => array(
               $field_auto_loading,
               $auto_loading_splitter_info,
               array(
                   'id'        => 'location',
                   'type'      => 'select',
                   'title'     => esc_html__('Location', 'maxboxy'),
                   'help'      => esc_html__('"Head" option allows only specific code that can be put to the <head> section of the page. You should use HTML block in order to output the styles/scripts and similar code to the page\'s head. Code that isn\'t properly structured for the head output will be stripped away.', 'maxboxy'),
                   'options'   => array(
                       'head'   => esc_html__('Head',          'maxboxy'),
                       'top'    => esc_html__('Top <body>',    'maxboxy'),
                       'bottom' => esc_html__('Bottom <body>', 'maxboxy'),
                   ),
                   'default'   => 'top',
                   'dependency'=> array('auto_loading','!=','disabled'),
               ),
            ),
        )
    );


    /*
     * Loading - Float Any.
     */
    CSF::createMetabox(
        '_mb_floatany_loading', array(
            'title'     => esc_html__('Loading', 'maxboxy'),
            'post_type' => array( 'float_any' ),
            'context'   => 'side',
            'priority'  => 'high',
            'data_type' => 'unserialize',
        )
    );


    CSF::createSection(
        '_mb_floatany_loading', array(
            'fields' => array(
                $field_auto_loading,
                $auto_loading_splitter_info,
                array(
                    'id'            => 'location',
                    'type'          => 'select',
                    'title'         => esc_html__('Location', 'maxboxy'),
                    'desc'          => esc_html__('Even if the Global loading is disabled, set the location of the panel', 'maxboxy'),
                    'options'       => array(
                        'left-topped'       => esc_html__('Left topped',        'maxboxy'),
                        'left-centered'     => esc_html__('Left centered',      'maxboxy'),
                        'left-bottomed'     => esc_html__('Left bottomed',      'maxboxy'),
                        'center-topped'     => esc_html__('Center topped',      'maxboxy'),
                        'center'            => esc_html__('Center',             'maxboxy'),
                        'center-bottomed'   => esc_html__('Center bottomed',    'maxboxy'),
                        'right-topped'      => esc_html__('Right topped',       'maxboxy'),
                        'right-centered'    => esc_html__('Right centered',     'maxboxy'),
                        'right-bottomed'    => esc_html__('Right bottomed',     'maxboxy'),
                    ),
                    'default'       => 'center',
                ),
            ),
        )
    );


    /*
     * Type & style - Float Any.
     */
    CSF::createMetabox(
        '_mb_floatany', array(
            'title'       => esc_html__('Type & style', 'maxboxy'),
            'post_type'   => 'float_any',
            'context'     => 'side',
            'priority'    => 'default',
          )
    );

    CSF::createSection(
        '_mb_floatany', array(
            'fields' => array(
                array(
                    'id'    => 'box_options',
                    'type'  => 'tabbed',
                    'class' => 'major-maxboxy-options',
                    'tabs'  => array(
                        $starter_tab_floatany,
                        $colors_tab_floatany,
                        $set_sizes_tab_floatany,
                        $set_toggler_tab,
                    ),
                ),
            )
        )
    );


    /*
     * Type & style - Inject Any.
     */
    CSF::createMetabox(
        '_mb_injectany', array(
            'title'       => esc_html__('Type & style', 'maxboxy'),
            'post_type'   => array_merge(array('inject_any'), $add_reusable_blocks),
            'context'     => 'side',
            'priority'    => 'default',
        )
    );

    CSF::createSection(
        '_mb_injectany', array(
            'fields' => array(
                array(
                    'id'    => 'box_options',
                    'type'  => 'tabbed',
                    'class' => 'major-maxboxy-options',
                    'tabs'  => array(
                          $starter_tab_injectany,
                          $colors_tab_injectany_or_reusable,
                          $set_sizes_tab_injectany,
                          $set_toggler_tab,
                    ),
                ),
            )
        )
    );


    /*
     * Conversion.
     */
    CSF::createMetabox(
        '_mb_maxboxy_conversion', array(
            'title'       => esc_html__('Conversion', 'maxboxy'),
            'post_type'   => array_merge(array( 'inject_any', 'float_any' ), $add_reusable_blocks),
            'context'     => 'side',
        )
    );

    CSF::createSection(
        '_mb_maxboxy_conversion', array(
            'fields' => array(
                array(
                    'id'    => 'box_options',
                    'type'  => 'tabbed',
                    'tabs'  => array(
                        $tab_goals,
                        $tab_track,
                    )
                ),
            ),
        )
    );

    /*
     * Until the Splitter goes live, lets make it conditional with class exists,
     * latter this condition should be omitted and plugin inactive mesages left 
     * to $mb_splitter_info.
     */
    if (class_exists('Max_Boxy_Splitter')) {

        /*
         * Splitter info.
         */
        CSF::createMetabox(
            '_mb_maxboxy_splitter_info', array(
                'title'       => esc_html__('Splitter (A/B Testing)', 'maxboxy'),
                'post_type'   =>array_merge(array( 'inject_any', 'float_any' ), $add_reusable_blocks),
                'context'     =>'side',
            )
        );

        CSF::createSection(
            '_mb_maxboxy_splitter_info', array(
                'fields' => $mb_splitter_info,
            )
        );

    }


    /*
     * Conditional settings.
     */
    CSF::createMetabox(
        '_mb_maxboxy_conditionals', array(
            'title'       => esc_html__('Conditionals', 'maxboxy'),
            'post_type'   => array_merge(array( 'inject_any', 'float_any' ), $add_reusable_blocks),
            'context'     => 'side',
            'priority'    => 'low',
        )
    );

    CSF::createSection(
        '_mb_maxboxy_conditionals', array(
            'fields' => $tabs_conditional,
        )
    );


    /**
     * Splitter.
     */
    CSF::createMetabox(
        '_mb_maxboxy_splitter', array(
            'title'       => esc_html__('Splitting items', 'maxboxy'),
            'theme'       => 'light',
            'post_type'   => array( 'maxboxy_splitter' ),
            'context'     => 'normal',
        )
    );

    CSF::createSection(
        '_mb_maxboxy_splitter', array(
            'title'  => esc_html__('Items', 'maxboxy'),
            'fields' => $mb_splitter,
        )
    );

    CSF::createSection(
        '_mb_maxboxy_splitter', array(
            'title'      => esc_html__('Stats', 'maxboxy'),
            'fields' => array(
                array(
                    'id'       => 'echo_stats',
                    'type'     => 'callback',
                    'function' => 'maxboxy_splitters_stats',
                ),
            )
        )
    );

    CSF::createMetabox(
        '_mb_maxboxy_splitter_loading', array(
            'title'            => esc_html__('Loading', 'maxboxy'),
            'post_type'        => array( 'maxboxy_splitter' ),
            'context'          => 'normal',
            'data_type'        =>'unserialize',
        )
    );

    CSF::createSection(
        '_mb_maxboxy_splitter_loading', array(
            'fields' => array(
                array(
                    'type'     => 'content',
                    'content'  => esc_html__('You can takeover the "Global loading" option from the selected panels and control it from here. Global loading controls the output of items from MaxBoxy strains that have such feature, i.e. FloatAny or InjectAny panels. Reusable blocks do not have that feaure.', 'maxboxy'),
                ),
                $field_auto_loading,
            ),
        )
    );

