<?php
// phpcs:ignore
/**
 * Description: MaxBoxy options for the Reusable blocks
 */

if (! defined('ABSPATH')) { 
    exit; 
}


if (! class_exists('Max_Boxy_Reusable_Blocks')) {
    
    add_filter(
        'render_block', array ( 'Max_Boxy_Reusable_Blocks', 'render' ), 10, 2
    );

    // phpcs:ignore
    class Max_Boxy_Reusable_Blocks
    {

        /**
         * Check are the MaxBoxy options enabled for 'wp_block' 
         * i.e. reusable blocks from MaxBoxy settings.
         * 
         * @return boolean
         */
        public static function enabled()
        {

            $enabled = isset(get_option('_maxboxy_options')[ 'enable_wp_block' ])
                     ?       get_option('_maxboxy_options')[ 'enable_wp_block' ] : '';

            $enabled = ! empty($enabled) ? true : false;

                return $enabled;

        }

        /**
         * Callback for the filter
         * 
         * Output modified reusable block.
         * 
         * @param string $block_content The whole content of the reusable block.
         * @param string $block         Targeted block,
         *                              here it's core/block, i.e. reusable block.
         * 
         * @return string.
         */
        public static function render( $block_content, $block )
        {

            // core/block is reusable block
            if (self::enabled() === true && $block['blockName'] === 'core/block') {

                // get the object (WP_Post) of the reusable block
                $post = get_post($block['attrs']['ref']);

                if (isset($post)) {

                    $is_shorty      = true;
                    $is_ajax_call   = false;
                    $name           = sanitize_title($post->post_title);
                    $get_id         = $post->ID;
                    // no maxboxy's loading metabox options with reusable blocks:
                    $loading        = null;
                    $basics         = class_exists('Max_Boxy_Options') ? Max_Boxy_Options::basics($get_id)      : '';
                    $goals          = class_exists('Max_Boxy_Options') ? Max_Boxy_Options::conversions($get_id) : '';

                    $conditionals_on = class_exists('Max_Boxy_Conditionals') ? true : false;
                    $conditionals    = array(
                        'page_pass'   => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id)    : true,
                        'schedule'    => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                        // Max_Boxy_Conditionals::appear_triggers returns array:
                        'appear_pass'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]   : true,
                        'appear_classes' => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ] : '',
                        'appear_data'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]    : '',
                    );

                    $splitter   = class_exists('Max_Boxy_Splitter') && Max_Boxy_Splitter::enabled() === true
                                ? Max_Boxy_Splitter::panel_options($get_id)
                                : array( 'on' => false, 'classes' => '', 'prerender' => false, 'id' => '' );
        
                    // if splitter isn't used
                    if (empty($splitter[ 'on' ])) {

                        $panel = Max_Boxy::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                        // output
                        $block_content = $panel;
                        return $block_content;

                    } else { // splitter used

                        // get the splitter item associated with this panel
                        $get_id               = isset($splitter[ 'id' ]) ? $splitter[ 'id' ] : '';
                        $splitter_item        = Max_Boxy_Splitter::splitter_item($get_id);
                        $_safe_splitter_group = ! empty($splitter_item) ? $splitter_item : '';
        
                        // if splitters aren't prerendered
                        if (empty($splitter[ 'prerender' ])) {
                                
                            // ...output just the splitter group
                            if (! empty($_safe_splitter_group)) {
                                // _safe = escaped earlier
                                return $_safe_splitter_group; 
                            }
        
                        } else { // if splitters are prerendered

                            // $get_id is the $splitter[ 'id' ]
                            $settings           = get_post_meta($get_id, '_mb_maxboxy_splitter', true);
                            $floatany_ids       = ! empty($settings[ 'floatany_ids' ])  ? $settings[ 'floatany_ids' ]  : array();
                            $injectany_ids      = ! empty($settings[ 'injectany_ids' ]) ? $settings[ 'injectany_ids' ] : array();
                            $wp_block_ids       = ! empty($settings[ 'wp_block_ids' ])  ? $settings[ 'wp_block_ids' ]  : array();
                            $selected_splitters = array_merge($floatany_ids, $injectany_ids, $wp_block_ids);

                            foreach ( $selected_splitters as $id ) {

                                $get_id   = $id;
                                $post     = get_post($get_id);
                                // for the splitters name can be only post_title
                                $name     = sanitize_title(get_post($get_id)->post_title);
        
                                $loading = class_exists('Max_Boxy_Options') ? Max_Boxy_Options::loading($get_id)     : '';
                                $basics  = class_exists('Max_Boxy_Options') ? Max_Boxy_Options::basics($get_id)      : '';
                                $goals   = class_exists('Max_Boxy_Options') ? Max_Boxy_Options::conversions($get_id) : '';
        
                                $conditionals_on = class_exists('Max_Boxy_Conditionals') ? true : false;
                                $conditionals    = array(
                                     'page_pass'  => $conditionals_on === true ? Max_Boxy_Conditionals::pages($get_id)    : true,
                                     'schedule'   => $conditionals_on === true ? Max_Boxy_Conditionals::schedule($get_id) : '',
                                     // Max_Boxy_Conditionals::appear_triggers returns array:
                                     'appear_pass'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_check' ]    : true,
                                     'appear_classes' => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_classes' ]  : '',
                                     'appear_data'    => $conditionals_on === true ? Max_Boxy_Conditionals::appear_triggers($get_id)[ 'appear_data' ]     : '',
                                );

                                $panel[] = Max_Boxy::panel($get_id, $post, $is_shorty, $is_ajax_call, $name, $loading, $basics, $goals, $conditionals, $splitter);

                            }

                            $join_panels        = ! empty($panel)       ? $panel : ''; // gather the panels
                            $_safe_panels_group = ! empty($join_panels) ? implode('', $join_panels) : ''; // prepere for output

                            // output splitter group + panel group,
                            // _safe = escaped earlier.
                            return $_safe_splitter_group .$_safe_panels_group;

                        }

                    }

                }
                    
            }

            return $block_content;
        
        }


    }

}