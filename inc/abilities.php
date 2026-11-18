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
                    'readonly'    => true,
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
                        'type'        => 'integer',
                        'minimum'     => 1,
                        'description' => __( 'The ID of the post to query, must be a positive integer. It must belong to one of the MaxBoxy post types, i.e. the ID of a FloatAny or InjectAny post.', 'maxboxy' ),
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
                                    ],
                                    'background-position' => [ 'type' => 'string' ],
                                    'background-repeat' => [ 'type' => 'string' ],
                                    'background-attachment' => [ 'type' => 'string' ],
                                    'background-size' => [ 'type' => 'string' ],
                                ],
                            ],
                            'panel_popup_color' => [ 'type' => 'string' ],
                            'panel_shut_bg' => [ 'type' => 'string' ],
                            'panel_shut_color' => [ 'type' => 'string' ],
                            'size_1' => [
                                'type' => 'object',
                                'properties' => [ 'width' => [ 'type' => 'string' ], 'unit' => [ 'type' => 'string' ] ],
                            ],
                            'height_100' => [ 'type' => 'string' ],
                            'size_2' => [
                                'type' => 'object',
                                'properties' => [ 'width' => [ 'type' => 'string' ], 'unit' => [ 'type' => 'string' ] ],
                            ],
                            'height_100_2' => [ 'type' => 'string' ],
                            'panel_padding' => [
                                'type' => 'object',
                                'properties' => [
                                    'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                    'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                    'unit' => [ 'type' => 'string' ],
                                ],
                            ],
                            'panel_border' => [
                                'type' => 'object',
                                'properties' => [
                                    'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                    'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                    'style' => [ 'type' => 'string' ], 'color' => [ 'type' => 'string' ],
                                ],
                            ],
                            'panel_border_radius' => [
                                'type' => 'object',
                                'properties' => [
                                    'top' => [ 'type' => 'string' ], 'right' => [ 'type' => 'string' ],
                                    'bottom' => [ 'type' => 'string' ], 'left' => [ 'type' => 'string' ],
                                    'unit' => [ 'type' => 'string' ],
                                ],
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
                    'conversion_options' => [
                        'type' => 'object',
                        'properties' => [
                            'goal' => [ 'type' => 'string' ],
                            'goal_form_submit' => [ 'type' => 'string' ],
                            'goal_click_target_attr' => [ 'type' => 'string' ],
                            'goal_attr_value' => [ 'type' => 'string' ],
                            'goal_after_banish' => [ 'type' => ['string', 'boolean', 'null'] ],
                            'track_loggedin_users' => [ 'type' => ['string', 'boolean', 'null'] ],
                        ],
                    ],
                    'conditional_options' => [
                        'type' => 'object',
                        'properties' => [
                            'set_particular_pages' => [ 'type' => 'string' ],
                            'post_types_add' => [ 'type' => 'string' ],
                            'posts_by_id' => [ 'type' => 'string' ],
                            'by_regex' => [ 'type' => 'string' ],
                            'all_archives' => [ 'type' => 'string' ],
                            'all_cats' => [ 'type' => 'string' ],
                            'all_tags' => [ 'type' => 'string' ],
                            'all_authors' => [ 'type' => 'string' ],
                            'all_dates' => [ 'type' => 'string' ],
                            'all_product_cats' => [ 'type' => 'string' ],
                            'all_product_tags' => [ 'type' => 'string' ],
                            'appear_if_elm_present' => [ 'type' => 'string' ],
                            'appear_if_elm_present_attr' => [ 'type' => 'string' ],
                            'appear_after_event' => [ 'type' => 'string' ],
                            'appear_after_time' => [ 'type' => 'string' ],
                            'appear_after_scroll' => [ 'type' => 'string' ],
                            'appear_after_elmview_attr' => [ 'type' => 'string' ],
                            'appear_after_elmview_value' => [ 'type' => 'string' ],
                            'appear_after_elmview_repeat' => [ 'type' => 'string' ],
                            'appear_on_pageviews' => [ 'type' => 'string' ],
                            'pageviews_number' => [ 'type' => 'string' ],
                            'appear_users' => [ 'type' => 'string' ],
                            'appear_for_devices' => [ 'type' => 'string' ],
                            'appear_by_referrer' => [ 'type' => 'string' ],
                            'appear_for_referrer_host' => [ 'type' => 'string' ],
                            'appear_for_referrer_url' => [ 'type' => 'string' ],
                            'appear_for_country' => [ 'type' => 'string' ],
                            'appear_for_city' => [ 'type' => 'string' ],
                            'appear_date_start' => [ 'type' => 'string' ],
                            'appear_time_start' => [ 'type' => 'string' ],
                            'appear_date_end' => [ 'type' => 'string' ],
                            'appear_time_end' => [ 'type' => 'string' ],
                            'recurring_time_start' => [ 'type' => 'string' ],
                            'recurring_time_end' => [ 'type' => 'string' ],
                        ],
                    ],
                    'splitter_info' => [ 'type' => ['string', 'null'] ],
                ],
                'required' => [ 'post_type' ],
                'description' => __( 'Result is pulled from the various post meta fields: "auto_loading", "location" and "test_mode" are data from each post no matter the type InjectAny or FloatAny, while "post_type" is the generated post type. Further, under the "type_and_style_options" parameter is the data that is pulled from "_mb_injectany" or "_mb_floatany" post meta fields, based on which post type it is for the specified post ID. Also, other parameters are post type independent, "conversion_options" and "conditional_options", are getting the data from the meta fields "_mb_maxboxy_conversion" and "_mb_maxboxy_conditionals" respectively. "splitter_info" parameter contains information received from the "_mb_maxboxy_splitter_info" meta field if the MaxBoxy splitter Addon is active.', 'maxboxy' ),
            ],
            
        ));
        
        // Register the ability to list all block patterns.
        wp_register_ability( 'maxboxy/list-patterns', array(
            'label' => __( 'MaxBoxy - List Patterns', 'maxboxy' ),
            'description' => __( 'Lists MaxBoxy pattern categories by default. Set category to list patterns in that category. Set pattern_name to retrieve one pattern\'s block markup.', 'maxboxy' ),
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
                'properties' => [
                    'categories' => [
                        'type'        => 'boolean',
                        'description' => __( 'Return the available MaxBoxy pattern categories when true. Defaults to true; ignored when category or pattern_name is provided.', 'maxboxy' ),
                        'default'     => true,
                    ],
                    'category' => [
                        'type'        => 'string',
                        'description' => __( 'Optional exact category slug. When provided, return all MaxBoxy patterns assigned to this category.', 'maxboxy' ),
                    ],
                    'pattern_name' => [
                        'type'        => 'string',
                        'description' => __( 'Optional exact pattern name, such as "maxboxy/cookies-1", to return one pattern and its content.', 'maxboxy' ),
                    ],
                ],
                'additionalProperties' => false,
            ],
            'output_schema' => [
                'type'  => 'array',
                'items' => [
                    'type'       => [ 'string', 'object' ],
                    'properties' => [
                        'name'       => [ 'type' => 'string' ],
                        'title'      => [ 'type' => 'string' ],
                        'description' => [ 'type' => 'string' ],
                        'categories' => [
                            'type'  => 'array',
                            'items' => [ 'type' => 'string' ],
                        ],
                        'content' => [ 'type' => 'string' ],
                    ],
                    'required' => [ 'name', 'title', 'description', 'categories' ],
                    'additionalProperties' => false,
                ],
                'description' => __( 'Returns category slugs by default. When category or pattern_name is provided, returns pattern objects with name, title, description, and categories. A specified pattern_name returns one pattern and its content. An unmatched category or pattern_name returns an error explaining that the input may be misspelled.', 'maxboxy' )
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
        $conversion_options = $conversion_options['box_options'] ?? (object) array();
        
        $conditional_options = get_post_meta( $id, '_mb_maxboxy_conditionals', true );
        $conditional_options = $conditional_options['box_options'] ?? (object) array();
        
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
            'conversion_options' => $conversion_options,
            'conditional_options' => $conditional_options,
        )+ $splitter_info;

    }
}


// This is the callback that runs when the ability is executed.
if (! function_exists('maxboxy_ability_callback_patterns_list') ) {
    function maxboxy_ability_callback_patterns_list( $input = array() ) {

        $input = is_array( $input ) ? $input : array();
        $list_categories = ! array_key_exists( 'categories', $input ) || ! empty( $input['categories'] );
        $category = isset( $input['category'] ) && is_string( $input['category'] ) ? trim( $input['category'] ) : '';
        $pattern_name = isset( $input['pattern_name'] ) && is_string( $input['pattern_name'] ) ? trim( $input['pattern_name'] ) : '';
 
        $registry = WP_Block_Patterns_Registry::get_instance();
        $all_patterns = $registry->get_all_registered();

        $maxboxy_patterns = array();
        foreach ( $all_patterns as $pattern ) {
            $name = isset( $pattern['name'] ) && is_string( $pattern['name'] ) ? $pattern['name'] : '';

            if ( ! str_starts_with( $name, 'maxboxy/' ) ) {
                continue;
            }

            $maxboxy_patterns[] = $pattern;
        }

        if ( '' === $pattern_name && '' === $category && $list_categories ) {
            $categories = array();

            foreach ( $maxboxy_patterns as $pattern ) {
                $pattern_categories = isset( $pattern['categories'] ) && is_array( $pattern['categories'] )
                    ? $pattern['categories']
                    : array();

                foreach ( $pattern_categories as $pattern_category ) {
                    if ( is_string( $pattern_category ) && '' !== $pattern_category ) {
                        $categories[ $pattern_category ] = true;
                    }
                }
            }

            $output = array_keys( $categories );
            sort( $output, SORT_NATURAL | SORT_FLAG_CASE );

            return $output;
        }

        $output = array();
        foreach ( $maxboxy_patterns as $pattern ) {
            $name = $pattern['name'];

            if ( '' !== $pattern_name && $name !== $pattern_name ) {
                continue;
            }

            $pattern_categories = isset( $pattern['categories'] ) && is_array( $pattern['categories'] )
                ? array_values( array_filter( $pattern['categories'], 'is_string' ) )
                : array();

            if ( '' !== $category && ! in_array( $category, $pattern_categories, true ) ) {
                continue;
            }

            $item = array(
                'name'        => $name,
                'title'       => isset( $pattern['title'] ) ? $pattern['title'] : '',
                'description' => isset( $pattern['description'] ) ? $pattern['description'] : '',
                'categories'  => $pattern_categories,
            );

            if (! empty($pattern_name) && isset( $pattern['content'] ) ) {
                $item['content'] = $pattern['content'];
            }

            $output[] = $item;
        }

        usort( $output, static function ( $a, $b ) {
            return strcmp( $a['name'], $b['name'] );
        } );

        if ( empty( $output ) && '' !== $pattern_name ) {
            if ( '' !== $category ) {
                return new WP_Error(
                    'maxboxy_pattern_not_found',
                    sprintf(
                        __( 'No pattern named "%1$s" was found in category "%2$s". The result is empty; check both values for misspellings.', 'maxboxy' ),
                        $pattern_name,
                        $category
                    )
                );
            }

            return new WP_Error(
                'maxboxy_pattern_not_found',
                sprintf(
                    __( 'No pattern named "%s" was found. The result is empty; check the pattern name for a misspelling, or list patterns in a category to see valid names.', 'maxboxy' ),
                    $pattern_name
                )
            );
        }

        if ( empty( $output ) && '' !== $category ) {
            return new WP_Error(
                'maxboxy_pattern_category_not_found',
                sprintf(
                    __( 'No MaxBoxy patterns were found in category "%s". The result is empty; check the category slug for a misspelling, or request the available categories.', 'maxboxy' ),
                    $category
                )
            );
        }

        return $output;

    }
}