<?php
// phpcs:ignore
if (! defined('ABSPATH') ) {
    exit;
}
/**
 * Register new abilities and their categories beneath.
 *
 * @package MaxBoxy
 */


// First, register a category, or use one of the existing categories.
add_action( 'wp_abilities_api_categories_init', 'maxboxy_register_ability_category' );
/**
 * Register a custom ability category.
 *
 * @return void
 */
if (! function_exists('maxboxy_register_ability_category') ) {
    
    function maxboxy_register_ability_category() {
        wp_register_ability_category(
            'maxboxy-ai-tools',
            array(
                'label'       => __( 'MaxBoxy AI Tools', 'maxboxy' ),
                'description' => __( 'Abilities of the MaxBoxy theme that provide AI-powered functionality.', 'maxboxy' ),
            )
        );
    }

}

// Register an ability and apply it to the previously registered category.
add_action( 'wp_abilities_api_init', 'maxboxy_register_abilities' );

if (! function_exists('maxboxy_register_abilities') ) {

    function maxboxy_register_abilities() {
        if ( ! function_exists( 'wp_register_ability' ) ) {
            return;
        }

        // Register the ability to list all block patterns.
        wp_register_ability( 'maxboxy/get-maxboxy-panel-options', array(
            'label' => __( 'MaxBoxy - Get MaxBoxy Panel\'s Options', 'maxboxy' ),
            'description' => __( 'Enter the ID of the MaxBoxy panel post (either the ID of FloatAny or InjectAny) to get its options.', 'maxboxy' ),
            'category' => 'maxboxy-ai-tools',
            'execute_callback' => 'maxboxy_ability_callback_get_maxboxy_panel_options',
            'permission_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
            'meta' => array(
                'public'       => true,
                'show_in_rest' => true,
                'mcp'          => array(
                    'public' => true,
                ),
                'annotations'  => array(
                    'readonly'    => false,
                    'destructive' => false,
                    'idempotent'  => true, // Idempotent describes what happens if the same ability runs more than once with the same input:
                                           // true — means repeating it has no additional effect after the first run. For example, “set the page title to About” is idempotent: running it again leaves the title as About. 
                                           // Esencially, check is it the same result (effect) each time? Idempotent does not check if the returned data is identical each time. Idempotence describes repeated calls’ effects, not whether the returned data is permanently identical.
                ),
            ),
            /**
             * JSON Schema describing the data the ability expects when it runs.
             * WordPress uses it to validate the input and to tell clients,
             * including AI tools, which arguments they can provide
             */
            'input_schema' => [
                'type'       => 'object',
                'properties' => [
                    'panel_id' => [
                        'type'        => 'number',
                        'description' => __( 'The ID of the post to query. It must belong to one of the MaxBoxy post types, i.e. the ID of a FloatAny or InjectAny post.', 'maxboxy' ),
                    ],
                ],
                'required' => [ 'panel_id' ],
                'additionalProperties' => false,
            ],
            'output_schema' => [
                'type'       => 'object',
                'properties' => [
                    'post_type' => [ 'type' => 'string' ],
                    'auto_loading' => [ 'type' => 'string' ],
                    'location' => [ 'type' => 'string' ],
                    'test_mode' => [ 'type' => 'string' ],

                    'type_and_style_options' => [
                        'type' => 'object',
                            'properties' => [
                                'panel_popup_style' => [ 'type' => 'string' ],
                                'zindex' => [ 'type' => 'string' ],
                                'use_overlay' => [ 'type' => 'string' ],
                                
                                // for injectAny
                                'set_sticky' => [ 'type' => 'string' ],
                                'box_align' => [ 'type' => 'string' ],

                                'panel_type' => [ 'type' => 'string' ],
                                'panel_roles' => [ 'type' => 'array', 'items' => [ 'type' => 'string' ] ],
                                'rotator_on' => [ 'type' => 'string' ],
                                'rotator_off' => [ 'type' => 'string' ],
                                'rotator_close_pause' => [ 'type' => 'string' ],
                                'rotator_repeat' => [ 'type' => 'string' ],
                                'panel_additional_lable' => [ 'type' => 'string' ],
                                'add_classes' => [ 'type' => 'string' ],
                                'panel_popup_bg' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'background-color' => [ 'type' => 'string' ],
                                        'background-image' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'url' => [ 'type' => 'string' ],
                                                'id' => [ 'type' => 'string' ],
                                                'width' => [ 'type' => 'string' ],
                                                'height' => [ 'type' => 'string' ],
                                                'thumbnail' => [ 'type' => 'string' ],
                                                'alt' => [ 'type' => 'string' ],
                                                'title' => [ 'type' => 'string' ],
                                                'description' => [ 'type' => 'string' ],
                                            ],
                                            'additionalProperties' => false,
                                        ],
                                        'background-position' => [ 'type' => 'string' ],
                                        'background-repeat' => [ 'type' => 'string' ],
                                        'background-attachment' => [ 'type' => 'string' ],
                                        'background-size' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'panel_popup_color' => [ 'type' => 'string' ],
                                'panel_shut_bg' => [ 'type' => 'string' ],
                                'panel_shut_color' => [ 'type' => 'string' ],
                                'size_1' => [
                                    'type' => 'object',
                                    'properties' => [ 'width' => [ 'type' => 'string' ], 'unit' => [ 'type' => 'string' ] ],
                                    'additionalProperties' => false,
                                ],
                                'height_100' => [ 'type' => 'string' ],
                                'size_2' => [
                                    'type' => 'object',
                                    'properties' => [ 'width' => [ 'type' => 'string' ], 'unit' => [ 'type' => 'string' ] ],
                                    'additionalProperties' => false,
                                ],
                                'height_100_2' => [ 'type' => 'string' ],
                                'panel_padding' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                        'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                        'unit' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'panel_border' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                        'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                        'style' => [ 'type' => 'string' ], 'color' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'panel_border_radius' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                        'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                        'unit' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'unset_toggler' => [ 'type' => 'string' ],
                                'button_open_icon' => [ 'type' => 'string' ],
                                'button_open_svg' => [ 'type' => 'string' ],
                                'button_open_img' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'url' => [ 'type' => 'string' ], 'id' => [ 'type' => 'string' ],
                                        'width' => [ 'type' => 'string' ], 'height' => [ 'type' => 'string' ],
                                        'thumbnail' => [ 'type' => 'string' ], 'alt' => [ 'type' => 'string' ],
                                        'title' => [ 'type' => 'string' ], 'description' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'button_close_icon' => [ 'type' => 'string' ],
                                'button_close_svg' => [ 'type' => 'string' ],
                                'button_close_img' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'url' => [ 'type' => 'string' ], 'id' => [ 'type' => 'string' ],
                                        'width' => [ 'type' => 'string' ], 'height' => [ 'type' => 'string' ],
                                        'thumbnail' => [ 'type' => 'string' ], 'alt' => [ 'type' => 'string' ],
                                        'title' => [ 'type' => 'string' ], 'description' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'closer_size' => [ 'type' => 'string' ],
                                'toggler_pos' => [ 'type' => 'string' ],
                                'closer_styling' => [ 'type' => 'array', 'items' => [ 'type' => 'string' ] ],
                                'trigger_additional_message' => [ 'type' => 'string' ],
                                'trigger_margin' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                        'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                        'unit' => [ 'type' => 'string' ],
                                    ],
                                    'additionalProperties' => false,
                                ],
                                'trigger_margin_unit_top' => [ 'type' => 'string' ],
                                'trigger_margin_unit_left' => [ 'type' => 'string' ],
                                'trigger_margin_unit_right' => [ 'type' => 'string' ],
                                'trigger_margin_unit_bottom' => [ 'type' => 'string' ],
                                'eliminate_margin_closer' => [ 'type' => 'string' ],
                                'trigger_anim' => [ 'type' => 'string' ],
                                'trigger_anim_echo_interval' => [ 'type' => 'string' ],                            
                            ],
                            'required' => [ 'panel_type' ],
                        ],

                    'conversion_options' => [ 'type' => 'object' ],
                    'conditional_options' => [ 'type' => 'object' ],
                    'splitter_info' => [ 'type' => 'string' ],
                ],
                //'required' => [ 'location' ],
                'additionalProperties' => false,
            ],
            
        ));
        
        // Register the ability to list all block patterns.
        wp_register_ability( 'maxboxy/list-patterns', array(
            'label' => __( 'MaxBoxy - List Patterns', 'maxboxy' ),
            'description' => __( 'Lists all patterns provided by the MaxBoxy (both, provided by the basic and Pro plugin).', 'maxboxy' ),
            'category' => 'maxboxy-ai-tools',
            'execute_callback' => 'maxboxy_ability_callback_patterns_list',
            'permission_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
            'meta' => array(
                'public'       => true,
                'show_in_rest' => true,
                'mcp'          => array(
                    'public' => true,
                ),
                'annotations'  => array(
                    'readonly'    => true,
                    'destructive' => false,
                    'idempotent'  => true, // Idempotent describes what happens if the same ability runs more than once with the same input:
                                           // true — means repeating it has no additional effect after the first run. For example, “set the page title to About” is idempotent: running it again leaves the title as About. 
                                           // Esencially, check is it the same result (effect) each time? Idempotent does not check if the returned data is identical each time. Idempotence describes repeated calls’ effects, not whether the returned data is permanently identical.
                ),
            ),
            'input_schema' => [
                'type'       => 'object',
                'properties' => [],
                'additionalProperties' => false,
                'description' => __( 'No input is required.', 'maxboxy' ),
            ],
            'output_schema' => [
                'type'  => 'array',
                'items' => [
                    'type'       => 'object',
                    'properties' => [
                        'name'       => [ 'type' => 'string' ],
                        'title'      => [ 'type' => 'string' ],
                        'description' => [ 'type' => 'string' ],
                        'categories' => [
                            'type'  => 'array',
                            'items' => [ 'type' => 'string' ],
                        ],
                    ],
                    'required' => [ 'name', 'title', 'description', 'categories' ],
                ],
            ],
        ));

    } // function maxboxy_register_abilities end
} // function_exists check end



// This is the callback that runs when the ability is executed.
if (! function_exists('maxboxy_ability_callback_get_maxboxy_panel_options') ) {
    function maxboxy_ability_callback_get_maxboxy_panel_options($input) {
        $id = isset( $input['panel_id'] ) ? absint( $input['panel_id'] ) : 0;

        if ( ! $id || ! get_post( $id ) ) {
            return array(
                'post_type' => __( 'The post ID is incorrect.', 'maxboxy' ),
            );
        }

        // serialized data from post meta
        $floatany = get_post_meta( $id, '_mb_floatany', true );
        $floatany = is_array($floatany) ? $floatany : array();
        $injectany = get_post_meta( $id, '_mb_injectany', true );
        $injectany = is_array($injectany) ? $injectany : array();
        $merged_fa_ia = array_merge( (array) $floatany, (array) $injectany );

        if ( ! isset( $merged_fa_ia ) || ! is_array( $merged_fa_ia ) || ! isset( $merged_fa_ia['box_options'] ) || ! is_array( $merged_fa_ia['box_options'] ) ) {
            return array(
                'post_type' => __( 'No MaxBoxy options were found for this post ID.', 'maxboxy' ),
            );
        }

        // unserialized data from post meta
        $auto_loading = get_post_meta( $id, 'auto_loading', true );
        $location = get_post_meta( $id, 'location', true );
        $test_mode = get_post_meta( $id, 'test_mode', true );

        $conversion_options = get_post_meta( $id, '_mb_maxboxy_conversion', true );
        $conditional_options = get_post_meta( $id, '_mb_maxboxy_conditionals', true );
        
        $splitter_info = class_exists('Max_Boxy_Splitter') ? get_post_meta( $id, '_mb_maxboxy_splitter_info', true ) : null;
        $splitter_info = $splitter_info !== null ? array(
            'splitter_info' => $splitter_info['is_splitted_from'],
        ) : array();

        $post_type = get_post_type($id);

        return array(
            'post_type' => $post_type,
            'auto_loading' => $auto_loading,
            'location' => $location,
            'test_mode' => $test_mode,
            'type_and_style_options' => $merged_fa_ia['box_options'],
            'conversion_options' => $conversion_options['box_options'] ?? array(),
            'conditional_options' => $conditional_options['box_options'] ?? array(),
        )+ $splitter_info;

    }
}


// This is the callback that runs when the ability is executed.
if (! function_exists('maxboxy_ability_callback_patterns_list') ) {
    function maxboxy_ability_callback_patterns_list() {

        $registry = WP_Block_Patterns_Registry::get_instance();
        $all_patterns = $registry->get_all_registered();

        $output = [];
        
        foreach ( $all_patterns as $pattern ) {
            if ( strpos( $pattern['name'], 'maxboxy/' ) !== 0 ) {
                continue;
            }

            $output[] = [
                'name'       => $pattern['name'],
                'title'      => $pattern['title'],
                'description' => isset( $pattern['description'] ) ? $pattern['description'] : '',
                'categories' => isset( $pattern['categories'] ) ? $pattern['categories'] : [],
            ];
        }

        usort( $output, function ( $a, $b ) {
            return strcmp( $a['name'], $b['name'] );
        } );

        return $output;

    }
}