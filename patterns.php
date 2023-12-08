<?php
// phpcs:ignore
/**
 * Description: Patterns for MaxBoxy
 */
if (! defined('ABSPATH')) {
    exit;
}


    /**
     * Register block pattern categories
     */
    add_action(
        'init', function () {

            if (! function_exists('register_block_pattern_category')) {
                return;
            }

            register_block_pattern_category(
                'maxboxy-buttons',
                array( 'label' => esc_html__('MaxBoxy: Functional buttons', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-contact',
                array( 'label' => esc_html__('MaxBoxy: Contact', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-cookies',
                array( 'label' => esc_html__('MaxBoxy: Cookies and GDPR', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-cta',
                array( 'label' => esc_html__('MaxBoxy: CTA', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-infoboxes',
                array( 'label' => esc_html__('MaxBoxy: Info and Warnings', 'maxboxy') )
            );

            /*
             * @todo gallery patterns:
             * register_block_pattern_category(
             *     'maxboxy-gallery',
             *     array( 'label' => esc_html__('MaxBoxy: Gallery', 'maxboxy') )
             * );
             */

            register_block_pattern_category(
                'maxboxy-media',
                array( 'label' => esc_html__('MaxBoxy: Media', 'maxboxy') )
            );


            // Options available only with pro version
            register_block_pattern_category(
                'maxboxy-promo',
                array( 'label' => esc_html__('MaxBoxy: Promo', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-signups',
                array( 'label' => esc_html__('MaxBoxy: Signups', 'maxboxy') )
            );

        }
    );


    /**
     * Register patterns
     */
    add_action(
        'init', function () {
            if (! function_exists('register_block_pattern')) {
                return;
            }

            $get_modal_offer = isset(get_option('_maxboxy_options')[ 'modal_offer' ])
            ?                        get_option('_maxboxy_options')[ 'modal_offer' ] : '';

            $modal_offer   = $get_modal_offer !== 'no'
            ? array(
                'blockTypes' => array( 'core/post-content' ), // puts it in the commencing modal
                'postTypes'  => array( 'float_any', 'inject_any', 'wp_block' ) // ...for specific post_types
            )
            : array();

            /*
             * Buttons
             */

            // button 1
            register_block_pattern(
                'maxboxy/button-pc', [
                'title'         => esc_html__('Panel closer', 'maxboxy'),
                'keywords'      => ['button'],
                'categories'    => ['maxboxy-buttons'],
                'content'       => "<!-- wp:buttons -->
                                <div class=\"wp-block-buttons\">
                                <!-- wp:button {\"className\":\"mboxy-closer\"} -->
                                <div class=\"wp-block-button mboxy-closer\">
                                <a class=\"wp-block-button__link\">Close me</a>
                                </div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons -->",
                ]
            );

            // button 2
            register_block_pattern(
                'maxboxy/button-pt', [
                'title'         => esc_html__('Panel toggler', 'maxboxy'),
                'keywords'      => ['button'],
                'categories'    => ['maxboxy-buttons'],
                'content'       => "<!-- wp:buttons -->
                                <div class=\"wp-block-buttons\">
                                <!-- wp:button {\"className\":\"mboxy-toggler\"} -->
                                <div class=\"wp-block-button mboxy-toggler\">
                                <a class=\"wp-block-button__link\">Toggle me</a>
                                </div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons -->",
                ]
            );


            /*
             * Contacts
             */
            $contact_cceitc_content = [
                'title'         => esc_html__('Common Contact elements in two columns', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'content'       => '<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"3rem"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"backgroundColor":"light-green-cyan","textColor":"black","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-black-color has-light-green-cyan-background-color has-text-color has-background has-link-color" style="padding-bottom:3rem"><!-- wp:columns -->
                <div class="wp-block-columns"><!-- wp:column {"width":"33.33%"} -->
                <div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading -->
                <h2 class="wp-block-heading">Call us</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>(555) 555-555</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:heading -->
                <h2 class="wp-block-heading">Have a Question?</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>You should put a contact form beneath. First, you should install a plugin, for example "Contact form 7", then insert its shortcode. Also remove this paragraph.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:shortcode /--></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->
                
                <!-- wp:columns -->
                <div class="wp-block-columns"><!-- wp:column {"width":"33.33%"} -->
                <div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading -->
                <h2 class="wp-block-heading">Find us</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>Dolor Sit, 567 89</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:heading -->
                <h2 class="wp-block-heading">Follow us</h2>
                <!-- /wp:heading -->
                
                <!-- wp:social-links {"iconColor":"darko-transit","iconColorValue":"#171f29","iconBackgroundColor":"transparent","iconBackgroundColorValue":"transparent","className":"is-style-icecubo-social-outline","layout":{"type":"flex","justifyContent":"left"}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-icecubo-social-outline"><!-- wp:social-link {"url":"#","service":"facebook"} /-->
                
                <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                
                <!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
                <!-- /wp:social-links --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns --></div>
                <!-- /wp:group -->',
            ];

            /**
             * Contact blocks pattern registration (1st for the pattern's block inserter category, 2nd to appear in modal).
             * ...Have to separate these, coz $modal_offer has boundaries for the postTypes (float_any, inject_any, wp_block)
             * So, if all put together, it would dispaly the pattern in the Modal,
             * but not on block inserter outside of the listed postTypes
             */
            register_block_pattern('maxboxy/contact-cceitc', $contact_cceitc_content +['categories' => ['maxboxy-contact']]);
            register_block_pattern('maxboxy/contact-cceitc-modal', $contact_cceitc_content +$modal_offer);

            $contact_cceitc_2_content = [
                'title'         => esc_html__('Common contact elements in two columns 2', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'content'       => '<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"3rem"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"backgroundColor":"pale-cyan-blue","textColor":"black","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-black-color has-pale-cyan-blue-background-color has-text-color has-background has-link-color" style="padding-bottom:3rem"><!-- wp:columns -->
                <div class="wp-block-columns"><!-- wp:column {"width":"33.33%"} -->
                <div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Call us</h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>(555) 555-555</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Find us</h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>Dolor Sit, 567 89</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Follow us</h3>
                <!-- /wp:heading -->
                
                <!-- wp:social-links {"iconColor":"darko-transit","iconColorValue":"#171f29","iconBackgroundColor":"transparent","iconBackgroundColorValue":"transparent","className":"is-style-icecubo-social-outline","layout":{"type":"flex","justifyContent":"left"}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-icecubo-social-outline"><!-- wp:social-link {"url":"#","service":"facebook"} /-->
                
                <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                
                <!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
                <!-- /wp:social-links --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:heading -->
                <h2 class="wp-block-heading">Have a Question?</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p>You should put a contact form beneath. First, you should install a plugin, for example "Contact form 7", then insert its shortcode. Also remove this paragraph.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:shortcode /--></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns --></div>
                <!-- /wp:group -->',
            ];

            register_block_pattern('maxboxy/contact-cceitc2', $contact_cceitc_2_content +['categories' => ['maxboxy-contact']]);
            register_block_pattern('maxboxy/contact-cceitc2-modal', $contact_cceitc_2_content +$modal_offer);

            register_block_pattern(
                'maxboxy/contact-cfwsi', [
                'title' => esc_html__('Contact form with social icons', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'categories'    => ['maxboxy-contact'],

                /*
                 * @todo contentOnly for templateLock, i.e. only editing content inside the pattern,
                 * e.g. for group block <!-- wp:group {"templateLock": "contentOnly"}
                 * @link https://make.wordpress.org/core/2022/10/11/content-locking-features-and-updates/
                 * @link https://richtabor.com/content-only-editing/
                 * @link https://gist.github.com/richtabor/ddeea41ced691721318649bea8ce9db8
                 * @link https://gist.github.com/annezazu/d62acd2514cea558be6cea97fe28ff3c
                 * 
                 * @todo Similar as above, moving & removing prevention. May be used on the trigger button
                 * e.g. for the paragraph block <!-- wp:paragraph {"lock":{"remove":true,"move":true}}
                 * @link https://gist.github.com/annezazu/acee30f8b6e8995e1b1a52796e6ef805
                 * 
                 * ...Another way to lock the blocks is on a template for the post type:
                 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-templates/#locking
                 */

                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"left\",\"level\":3} -->
                                <h3 class=\"has-text-align-left\">Contact us</h3>
                                <!-- /wp:heading -->

                                <!-- wp:shortcode /-->

                                <!-- wp:spacer {\"height\":50} -->
                                <div style=\"height:50px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->

                                <!-- wp:social-links {\"openInNewTab\":true,\"size\":\"has-large-icon-size\",\"className\":\"is-style-default\"} -->
                                <ul class=\"wp-block-social-links has-large-icon-size is-style-default\"><!-- wp:social-link {\"url\":\"\",\"service\":\"pinterest\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"twitter\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"instagram\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"facebook\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"linkedin\"} /--></ul>
                                <!-- /wp:social-links --></div>
                                <!-- /wp:group -->",
                ]
            );

            register_block_pattern(
                'maxboxy/contact-cfegmpeasi', [
                'title'         => esc_html__('Contact form + embed google map + phone + email + address + social icons', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'categories'    => ['maxboxy-contact'],
                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:spacer {\"height\":20} -->
                                <div style=\"height:20px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Contact form here:</h4>
                                <!-- /wp:heading -->

                                <!-- wp:shortcode /-->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Google map here:</h4>
                                <!-- /wp:heading -->

                                <!-- wp:html /-->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\",\"className\":\"is-style-default\"} -->
                                <p class=\"has-text-align-center is-style-default\"><a href=\"tel:+555555555555\">+555 555 555 555</a></p>
                                <!-- /wp:paragraph -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\"><a href=\"mailto:lorem@ipsum.dolor\">lorem@ipsum.dolor</a><br></p>
                                <!-- /wp:paragraph -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\">Lorem ipsum 123 Dolor Sit, 45</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->

                                <!-- wp:social-links {\"openInNewTab\":true,\"size\":\"has-large-icon-size\",\"className\":\"is-style-default\"} -->
                                <ul class=\"wp-block-social-links has-large-icon-size is-style-default\"><!-- wp:social-link {\"url\":\"\",\"service\":\"pinterest\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"twitter\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"instagram\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"facebook\"} /-->

                                <!-- wp:social-link {\"url\":\"\",\"service\":\"linkedin\"} /--></ul>
                                <!-- /wp:social-links --></div>
                                <!-- /wp:group -->",
                ]
            );

            register_block_pattern(
                'maxboxy/contact-cfsiiacb', [
                'title'         => esc_html__('Contact form placeholder + social icons (in a cover block)', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'categories'    => ['maxboxy-contact'],
                'content'       => "<!-- wp:cover {\"overlayColor\":\"vivid-cyan-blue\",\"isDark\":false,\"layout\":{\"type\":\"constrained\"}} -->
                <div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-vivid-cyan-blue-background-color has-background-dim-100 has-background-dim\"></span><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"left\",\"level\":3} -->
                <h3 class=\"wp-block-heading has-text-align-left\">Contact us</h3>
                <!-- /wp:heading -->
                
                <!-- wp:shortcode /-->
                
                <!-- wp:spacer {\"height\":\"50px\"} -->
                <div style=\"height:50px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:social-links {\"openInNewTab\":true,\"size\":\"has-large-icon-size\",\"className\":\"is-style-default\"} -->
                <ul class=\"wp-block-social-links has-large-icon-size is-style-default\"><!-- wp:social-link {\"url\":\"\",\"service\":\"pinterest\"} /-->
                
                <!-- wp:social-link {\"url\":\"\",\"service\":\"twitter\"} /-->
                
                <!-- wp:social-link {\"url\":\"\",\"service\":\"instagram\"} /-->
                
                <!-- wp:social-link {\"url\":\"\",\"service\":\"facebook\"} /-->
                
                <!-- wp:social-link {\"url\":\"\",\"service\":\"linkedin\"} /--></ul>
                <!-- /wp:social-links --></div></div>
                <!-- /wp:cover -->",
                ]
            );


            /*
             * Cookies
             */

            // Cookies - 1
            register_block_pattern(
                'maxboxy/cookies-1', [
                'title'         => esc_html__('Common cookies notice group', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'categories'    => ['maxboxy-cookies'],
                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3} -->
                                <h3 class=\"has-text-align-center\">Your title here...</h3>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\">We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"mboxy-closer\"} -->
                                <div class=\"wp-block-button mboxy-closer\"><a class=\"wp-block-button__link\">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:group -->",
                ]
            );

            // Cookies - 2
            register_block_pattern(
                'maxboxy/cookies-2', [
                'title'         => esc_html__('2 columns - notice and a button', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'categories'    => ['maxboxy-cookies'],
                'content'       => "<!-- wp:columns -->
                                <div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"75%\"} -->
                                <div class=\"wp-block-column\" style=\"flex-basis:75%\"><!-- wp:paragraph -->
                                <p>We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column -->

                                <!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->
                                <div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"mboxy-closer\"} -->
                                <div class=\"wp-block-button mboxy-closer\"><a class=\"wp-block-button__link\">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:column --></div>
                                <!-- /wp:columns -->",
                ]
            );

            // Cookies - 2
            $cookies_2cbg_content = [
                'title'         => esc_html__('Cookies - 2 columns - notice and a button, with a background color', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|default","bottom":"0"}},"elements":{"link":{"color":{"text":"#222222"}}},"color":{"text":"#222222"}},"backgroundColor":"luminous-vivid-amber"} -->
                <div class="wp-block-columns has-luminous-vivid-amber-background-color has-text-color has-background has-link-color" style="color:#222222;margin-top:var(--wp--preset--spacing--default);margin-bottom:0"><!-- wp:column {"width":"75%"} -->
                <div class="wp-block-column" style="flex-basis:75%"><!-- wp:paragraph -->
                <p>We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
                <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
                <div class="wp-block-buttons"><!-- wp:button {"className":"mboxy-closer"} -->
                <div class="wp-block-button mboxy-closer"><a class="wp-block-button__link wp-element-button">I agree</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
            ];

            register_block_pattern('maxboxy/cookies-2cbg', $cookies_2cbg_content +['categories' => ['maxboxy-cookies']]);
            register_block_pattern('maxboxy/cookies-2cbg-modal', $cookies_2cbg_content +$modal_offer);

            // Cookies - 3
            register_block_pattern(
                'maxboxy/cookies-3', [
                'title'         => esc_html__('3 columns', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'categories'    => ['maxboxy-cookies'],
                'content'       => "<!-- wp:columns -->
                                <div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"56.25%\"} -->
                                <div class=\"wp-block-column\" style=\"flex-basis:56.25%\"><!-- wp:paragraph -->
                                <p>We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column -->

                                <!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"18.75%\"} -->
                                <div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:18.75%\"><!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"mboxy-closer\"} -->
                                <div class=\"wp-block-button mboxy-closer\"><a class=\"wp-block-button__link\">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:column -->

                                <!-- wp:column {\"width\":\"25%\"} -->
                                <div class=\"wp-block-column\" style=\"flex-basis:25%\"><!-- wp:paragraph -->
                                <p>You can read more from our <a href=\"#\">Privacy Policy</a>.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column --></div>
                                <!-- /wp:columns -->",
                 ]
            );

            // Cookies - 4
            $cookies_4_content = [
                'title'         => esc_html__('Cookies - 2 + 1 columns', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'content'       => '<!-- wp:group {"style":{"elements":{"link":{"color":{"text":"var:preset|color|black"}}}},"backgroundColor":"light-green-cyan","textColor":"black"} -->
                                <div class="wp-block-group has-black-color has-light-green-cyan-background-color has-text-color has-background has-link-color"><!-- wp:columns -->
                                <div class="wp-block-columns"><!-- wp:column {"width":"75%"} -->
                                <div class="wp-block-column" style="flex-basis:75%"><!-- wp:paragraph -->
                                <p>We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column -->
                                
                                <!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
                                <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
                                <div class="wp-block-buttons"><!-- wp:button {"className":"mboxy-closer"} -->
                                <div class="wp-block-button mboxy-closer"><a class="wp-block-button__link wp-element-button">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:column --></div>
                                <!-- /wp:columns -->
                                
                                <!-- wp:separator {"opacity":"css","style":{"spacing":{"margin":{"top":"2rem","bottom":"2.5rem"}}}} -->
                                <hr class="wp-block-separator has-css-opacity" style="margin-top:2rem;margin-bottom:2.5rem"/>
                                <!-- /wp:separator -->
                                
                                <!-- wp:paragraph {"align":"center"} -->
                                <p class="has-text-align-center">You can read more from our <a href="#">Privacy Policy</a>.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:group -->',
            ];

            register_block_pattern('maxboxy/cookies-4', $cookies_4_content +['categories' => ['maxboxy-cookies']]);
            register_block_pattern('maxboxy/cookies-4-modal', $cookies_4_content +$modal_offer);

            // Cookies - 5
            register_block_pattern(
                'maxboxy/cookies-5', [
                'title'         => esc_html__('2 columns + large button', 'maxboxy'),
                'keywords'      => ['gdpr', 'cookies'],
                'categories'    => ['maxboxy-cookies'],
                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:columns -->
                                <div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"75%\"} -->
                                <div class=\"wp-block-column\" style=\"flex-basis:75%\"><!-- wp:paragraph -->
                                <p>We use cookies… Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column -->

                                <!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->
                                <div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:paragraph -->
                                <p>You can read more from our <a href=\"#\">Privacy Policy</a>.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:column --></div>
                                <!-- /wp:columns -->

                                <!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"width\":75,\"className\":\"is-style-outline mboxy-closer\"} -->
                                <div class=\"wp-block-button has-custom-width wp-block-button__width-75 is-style-outline mboxy-closer\"><a class=\"wp-block-button__link\">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:group -->",
                ]
            );


            /*
             * CTA
             */

            // CTA 1
            register_block_pattern(
                'maxboxy/cta-cctag', [
                'title'         => esc_html__('Common CTA (in group block)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action'],
                'categories'    => ['maxboxy-cta'],
                'content'       => '<!-- wp:group -->
                <div class="wp-block-group"><!-- wp:spacer {"height":"40px"} -->
                <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:heading {"textAlign":"center","style":{"color":{"text":"#414446"}}} -->
                <h2 class="wp-block-heading has-text-align-center has-text-color" style="color:#414446">Major attention message!</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {"align":"center","style":{"color":{"text":"#414446"}}} -->
                <p class="has-text-align-center has-text-color" style="color:#414446">Write additional text here.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:spacer {"height":"20px"} -->
                <div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"vertical","flexWrap":"wrap"}} -->
                <div class="wp-block-buttons"><!-- wp:button {"width":50,"className":"has-custom-width wp-block-button__width-25 is-style-outline"} -->
                <div class="wp-block-button has-custom-width wp-block-button__width-50 wp-block-button__width-25 is-style-outline"><a class="wp-block-button__link wp-element-button">Call to action</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons --></div>
                <!-- /wp:group -->',
                ]
            );

            // CTA 2
            register_block_pattern(
                'maxboxy/cta-ctapc', [
                'title'         => esc_html__('CTA (in a cover block) - Suitable for a background image', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action'],
                'categories'    => ['maxboxy-cta'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#f6f0de\",\"contentPosition\":\"bottom center\",\"isDark\":false} -->
                <div class=\"wp-block-cover is-light has-custom-content-position is-position-bottom-center\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-100 has-background-dim\" style=\"background-color:#f6f0de\"></span><div class=\"wp-block-cover__inner-container\"><!-- wp:spacer -->
                <div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"black\",\"fontSize\":\"large\"} -->
                <p class=\"has-text-align-center has-black-color has-text-color has-large-font-size\">YOUR ATTENTION MESSAGE!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\",\"orientation\":\"horizontal\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button {\"textColor\":\"black\",\"width\":50,\"className\":\"has-custom-width wp-block-button__width-25 is-style-outline\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 wp-block-button__width-25 is-style-outline\"><a class=\"wp-block-button__link has-black-color has-text-color wp-element-button\">Get it!</a></div>
                <!-- /wp:button -->
                
                <!-- wp:button {\"textColor\":\"black\",\"width\":50,\"className\":\"mboxy-closer is-style-outline\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 mboxy-closer is-style-outline\"><a class=\"wp-block-button__link has-black-color has-text-color wp-element-button\">No thanks.</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons --></div></div>
                <!-- /wp:cover -->",
                ]
            );

            // CTA 3
            $contact_cta_cmogb_content = [
                'title'         => esc_html__('Ciber Monday offer (group block)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action', 'Ciber Monday'],
                'content'       => "<!-- wp:group {\"style\":{\"border\":{\"radius\":\"100%\"}},\"gradient\":\"luminous-dusk\"} -->
                <div class=\"wp-block-group has-luminous-dusk-gradient-background has-background\" style=\"border-radius:100%\"><!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"white\",\"className\":\"is-style-default\"} -->
                <p class=\"has-text-align-center is-style-default has-white-color has-text-color\">SPECIAL OFFER</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:group {\"style\":{\"spacing\":{\"margin\":{\"top\":\"var:preset|spacing|default\",\"bottom\":\"4em\"}}},\"className\":\"is-style-default\",\"layout\":{\"type\":\"constrained\"}} -->
                <div class=\"wp-block-group is-style-default\" style=\"margin-top:var(--wp--preset--spacing--default);margin-bottom:4em\"><!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"3.5em\",\"fontStyle\":\"normal\",\"fontWeight\":\"100\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"right\":\"var:preset|spacing|default\",\"left\":\"var:preset|spacing|default\"}}},\"textColor\":\"black\"} -->
                <p class=\"has-text-align-center has-black-color has-text-color\" style=\"margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:3.5em;font-style:normal;font-weight:100\">Ciber</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"lineHeight\":\"0\",\"fontStyle\":\"normal\",\"fontWeight\":\"700\",\"fontSize\":\"3.5em\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"right\":\"var:preset|spacing|default\",\"left\":\"var:preset|spacing|default\"}}},\"textColor\":\"white\"} -->
                <p class=\"has-text-align-center has-white-color has-text-color\" style=\"margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:3.5em;font-style:normal;font-weight:700;line-height:0\">Monday</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:group -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"white\"} -->
                <p class=\"has-text-align-center has-white-color has-text-color\">UP TO 50% OFF!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\",\"orientation\":\"vertical\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"has-custom-width wp-block-button__width-25 is-style-fill\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-25 is-style-fill\"><a class=\"wp-block-button__link wp-element-button\">SHOP NOW</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->
                
                <!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer --></div>
                <!-- /wp:group -->",
            ];

            register_block_pattern('maxboxy/cta-cmogb', $contact_cta_cmogb_content +['categories' => ['maxboxy-cta']]);
            register_block_pattern('maxboxy/cta-cmogb-modal', $contact_cta_cmogb_content +$modal_offer);

            // CTA 4
            register_block_pattern(
                'maxboxy/cta-bfocwbiagio', [
                'title'         => esc_html__('Black Friday offer (cover with background image and gradient in opacity)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action', 'Black Friday'],
                'categories'    => ['maxboxy-cta'],
                'content'       => "<!-- wp:cover {\"url\":\"" .esc_url(plugins_url('/library/img/bg-bottom-highlight.jpeg', __FILE__)) ."\",\"dimRatio\":60,\"gradient\":\"cool-to-warm-spectrum\",\"style\":{\"color\":{}}} -->
                <div class=\"wp-block-cover\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-60 has-background-dim wp-block-cover__gradient-background has-background-gradient has-cool-to-warm-spectrum-gradient-background\"></span><img class=\"wp-block-cover__image-background\" alt=\"\" src=\"" .esc_url(plugins_url('/library/img/bg-bottom-highlight.jpeg', __FILE__)) ."\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:group -->
                <div class=\"wp-block-group\"><!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->

                <!-- wp:paragraph {\"align\":\"center\",\"className\":\"is-style-default\"} -->
                <p class=\"has-text-align-center is-style-default\">SPECIAL OFFER</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:group {\"style\":{\"spacing\":{\"margin\":{\"top\":\"var:preset|spacing|default\",\"bottom\":\"4em\"}}},\"className\":\"is-style-default\",\"layout\":{\"type\":\"constrained\"}} -->
                <div class=\"wp-block-group is-style-default\" style=\"margin-top:var(--wp--preset--spacing--default);margin-bottom:4em\"><!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"70px\",\"fontStyle\":\"normal\",\"fontWeight\":\"700\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"}}},\"textColor\":\"black\"} -->
                <p class=\"has-text-align-center has-black-color has-text-color\" style=\"margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-top:0;padding-right:var(--wp--preset--spacing--default);padding-bottom:0;padding-left:var(--wp--preset--spacing--default);font-size:70px;font-style:normal;font-weight:700\">Black</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"lineHeight\":\"0\",\"fontStyle\":\"normal\",\"fontWeight\":\"700\",\"fontSize\":\"70px\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"}}},\"textColor\":\"black\"} -->
                <p class=\"has-text-align-center has-black-color has-text-color\" style=\"margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-top:0;padding-right:var(--wp--preset--spacing--default);padding-bottom:0;padding-left:var(--wp--preset--spacing--default);font-size:70px;font-style:normal;font-weight:700;line-height:0\">Friday</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:group -->
                
                <!-- wp:paragraph {\"align\":\"center\"} -->
                <p class=\"has-text-align-center\">UP TO 50% OFF!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\",\"orientation\":\"vertical\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button {\"width\":50,\"className\":\"has-custom-width wp-block-button__width-25 is-style-outline\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 wp-block-button__width-25 is-style-outline\"><a class=\"wp-block-button__link wp-element-button\">SHOP NOW</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->
                
                <!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer --></div>
                <!-- /wp:group --></div></div>
                <!-- /wp:cover -->",
                ]
            );

            // CTA 5
            $cta_cocwbi_content = [
                'title'         => esc_html__('Christmas offer (cover with background image)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action', 'Christmass'],
                'content'       => '<!-- wp:cover {"url":"' .esc_url(plugins_url("/library/img/bg-christmas-three.jpeg", __FILE__)) .'","dimRatio":10,"layout":{"type":"constrained"}} -->
                <div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-10 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' .esc_url(plugins_url("/library/img/bg-christmas-three.jpeg", __FILE__)) .'" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:spacer {"height":"3vh"} -->
                <div style="height:3vh" aria-hidden="true" class="wp-block-spacer"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:paragraph {"align":"center","placeholder":"Write title…"} -->
                <p class="has-text-align-center">HO HO HO!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|default","bottom":"4em"}}},"className":"is-style-default","layout":{"type":"constrained"}} -->
                <div class="wp-block-group is-style-default" style="margin-top:var(--wp--preset--spacing--default);margin-bottom:4em"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"70px","fontStyle":"normal","fontWeight":"700","lineHeight":"1.2"},"spacing":{"margin":{"top":"0","right":"var:preset|spacing|default","bottom":"0","left":"var:preset|spacing|default"},"padding":{"right":"var:preset|spacing|default","left":"var:preset|spacing|default"}}},"className":"is-style-default"} -->
                <p class="has-text-align-center is-style-default" style="margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:70px;font-style:normal;font-weight:700;line-height:1.2">Christmas time!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"align":"center","style":{"color":{"text":"#800303"},"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"55px","lineHeight":"1.3"},"spacing":{"margin":{"right":"var:preset|spacing|default","left":"var:preset|spacing|default","top":"10px","bottom":"10px"},"padding":{"right":"var:preset|spacing|default","left":"var:preset|spacing|default"}}},"className":"is-style-default"} -->
                <p class="has-text-align-center is-style-default has-text-color" style="color:#800303;margin-top:10px;margin-right:var(--wp--preset--spacing--default);margin-bottom:10px;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:55px;font-style:normal;font-weight:700;line-height:1.3">BIG SALE</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:group -->
                
                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center">GET UP TO 50% OFF ON SELECTED PRODUCTS!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"vertical"}} -->
                <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"vivid-red","width":50,"style":{"elements":{"link":{"color":{"text":"var:preset|color|vivid-red"}}}},"className":"has-custom-width wp-block-button__width-25 is-style-outline"} -->
                <div class="wp-block-button has-custom-width wp-block-button__width-50 wp-block-button__width-25 is-style-outline"><a class="wp-block-button__link has-vivid-red-color has-white-background-color has-text-color has-background has-link-color wp-element-button"><strong>SEE PRODUCTS!</strong></a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->
                
                <!-- wp:spacer {"height":"3vh"} -->
                <div style="height:3vh" aria-hidden="true" class="wp-block-spacer"></div>
                <!-- /wp:spacer --></div></div>
                <!-- /wp:cover -->',
            ];

            register_block_pattern('maxboxy/cta-cocwbi', $cta_cocwbi_content +['categories' => ['maxboxy-cta']]);
            register_block_pattern('maxboxy/cta-cocwbi-modal', $cta_cocwbi_content +$modal_offer);


            // CTA 6
            $cta_voiagbwb_content = [
                'title'         => esc_html__('Valentine offer (in a group block with background)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action', 'Christmass'],
                'content'       => "<!-- wp:group {\"style\":{\"border\":{\"radius\":{\"topLeft\":\"0%\",\"topRight\":\"100%\",\"bottomLeft\":\"100%\",\"bottomRight\":\"100%\"}},\"color\":{\"gradient\":\"linear-gradient(140deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%)\"}}} -->
                <div class=\"wp-block-group has-background\" style=\"border-top-left-radius:0%;border-top-right-radius:100%;border-bottom-left-radius:100%;border-bottom-right-radius:100%;background:linear-gradient(140deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%)\"><!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"className\":\"is-style-default\"} -->
                <p class=\"has-text-align-center is-style-default\">ONLY TODAY</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:group {\"style\":{\"spacing\":{\"margin\":{\"top\":\"var:preset|spacing|default\",\"bottom\":\"4em\"}}},\"className\":\"is-style-default\",\"layout\":{\"type\":\"constrained\"}} -->
                <div class=\"wp-block-group is-style-default\" style=\"margin-top:var(--wp--preset--spacing--default);margin-bottom:4em\"><!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"3.5em\",\"fontStyle\":\"normal\",\"fontWeight\":\"100\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"right\":\"var:preset|spacing|default\",\"left\":\"var:preset|spacing|default\"}},\"color\":{\"text\":\"#63196f\"}}} -->
                <p class=\"has-text-align-center has-text-color\" style=\"color:#63196f;margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:3.5em;font-style:normal;font-weight:100\"><em>Valentine's Day</em></p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"typography\":{\"lineHeight\":\"0.5\",\"fontStyle\":\"normal\",\"fontWeight\":\"700\",\"fontSize\":\"2.5em\"},\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"right\":\"var:preset|spacing|default\",\"left\":\"var:preset|spacing|default\"}},\"color\":{\"text\":\"#dd0e87\"}}} -->
                <p class=\"has-text-align-center has-text-color\" style=\"color:#dd0e87;margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-right:var(--wp--preset--spacing--default);padding-left:var(--wp--preset--spacing--default);font-size:2.5em;font-style:normal;font-weight:700;line-height:0.5\">MEGA SALE</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:group -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#414446\"}}} -->
                <p class=\"has-text-align-center has-text-color\" style=\"color:#414446\">UP TO 50% OFF!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\",\"orientation\":\"vertical\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"has-custom-width wp-block-button__width-25 is-style-fill\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-25 is-style-fill\"><a class=\"wp-block-button__link wp-element-button\">SHOP NOW</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->
                
                <!-- wp:spacer {\"height\":\"3vh\"} -->
                <div style=\"height:3vh\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer --></div>
                <!-- /wp:group -->",
            ];

            register_block_pattern('maxboxy/cta-voiagbwb', $cta_voiagbwb_content +['categories' => ['maxboxy-cta']]);
            register_block_pattern('maxboxy/cta-voiagbwb-modal', $cta_voiagbwb_content +$modal_offer);

            // CTA 7
            $cta_cwawbiatced_content = [
                'title'         => esc_html__('Cover with a wrinkly background image and two colums (Ebook download)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action', 'Christmass'],
                'content'       => "<!-- wp:cover {\"url\":\"" .esc_url(plugins_url('/library/img/bg-wrinkly.jpeg', __FILE__)) ."\",\"dimRatio\":50,\"customOverlayColor\":\"#f6f0de\",\"contentPosition\":\"center center\",\"isDark\":false} -->
                <div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\" style=\"background-color:#f6f0de\"></span><img class=\"wp-block-cover__image-background\" alt=\"\" src=\"" .esc_url(plugins_url('/library/img/bg-wrinkly.jpeg', __FILE__)) ."\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"verticalAlignment\":null} -->
                <div class=\"wp-block-columns\"><!-- wp:column {\"verticalAlignment\":\"center\"} -->
                <div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"style\":{\"typography\":{\"fontSize\":\"34px\"}}} -->
                <p class=\"has-text-align-center\" style=\"font-size:34px\"><strong><em>Get the FREE e-book</em></strong>!</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"spacing\":{\"margin\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"},\"padding\":{\"top\":\"0\",\"right\":\"var:preset|spacing|default\",\"bottom\":\"0\",\"left\":\"var:preset|spacing|default\"}}}} -->
                <p class=\"has-text-align-center\" style=\"margin-top:0;margin-right:var(--wp--preset--spacing--default);margin-bottom:0;margin-left:var(--wp--preset--spacing--default);padding-top:0;padding-right:var(--wp--preset--spacing--default);padding-bottom:0;padding-left:var(--wp--preset--spacing--default)\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button {\"textColor\":\"base\",\"width\":50,\"style\":{\"color\":{\"background\":\"#373131\"}},\"className\":\"is-style-outline\"} -->
                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 is-style-outline\"><a class=\"wp-block-button__link has-base-color has-text-color has-background wp-element-button\" style=\"background-color:#373131\">Download now!</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons --></div>
                <!-- /wp:column -->
                
                <!-- wp:column -->
                <div class=\"wp-block-column\"><!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"is-style-rounded\"} -->
                <figure class=\"wp-block-image size-full is-style-rounded\"><img src=\"" .esc_url(plugins_url('/library/img/coffee-and-tablet.jpeg', __FILE__)) ."\" alt=\"\"/></figure>
                <!-- /wp:image --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns --></div></div>
                <!-- /wp:cover -->",
            ];

            register_block_pattern('maxboxy/cta-cwawbiatced', $cta_cwawbiatced_content +['categories' => ['maxboxy-cta']]);
            register_block_pattern('maxboxy/cta-cwawbiatced-modal', $cta_cwawbiatced_content +$modal_offer);


            /*
             * Info and warning boxes
             *
             * svg icons used from https://www.reshot.com/free-svg-icons/item/essential-minimal-icons-NSKQW8ACT5/
             * @license Reshot Free License
             * @link https://www.reshot.com/license/
             */

            $info_i_content = [
                'title'         => esc_html__('Info - i', 'maxboxy'),
                'keywords'      => ['info box', 'info'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"}},"color":{"background":"#e6eff9","text":"#0d0deb"},"elements":{"link":{"color":{"text":"#0d0deb"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#0d0deb;background-color:#e6eff9;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width: 100px; display: block;fill: #0D0DEB;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#0d0deb"},":hover":{"color":{"text":"#17496f"}}}},"color":{"text":"#17496f"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#17496f"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
            ];

            /**
             * Info box 1 (1st for the pattern's block inserter category, 2nd to appear in modal).
             * ...Have to separate these, coz $modal_offer has boundaries for the postTypes (float_any, inject_any, wp_block)
             * So, if all put together, it would dispaly the pattern in the Modal,
             * but not on block inserter outside of the listed postTypes
             */
            register_block_pattern('maxboxy/infobox-info-i', $info_i_content +['categories' => ['maxboxy-infoboxes']]);
            register_block_pattern('maxboxy/infobox-info-i-modal', $info_i_content +$modal_offer);

            // info box 2
            register_block_pattern(
                'maxboxy/infobox-info-pin', [
                'title'         => esc_html__('Info - Pin', 'maxboxy'),
                'keywords'      => ['info box', 'info', 'pin'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"}},"color":{"background":"#e6eff9","text":"#0d0deb"},"elements":{"link":{"color":{"text":"#0d0deb"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#0d0deb;background-color:#e6eff9;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width: 100px; display: block; fill: #0D0DEB;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82c-100.617 0-182.18 81.571-182.18 182.171C73.82 356.6 155.383 438.18 256 438.18c100.608 0 182.18-81.57 182.18-182.188 0-100.608-81.572-182.17-182.18-182.17zm-87.346 269.13 43.137-69.504a135.887 135.887 0 0 0 12.27 14.098 137.293 137.293 0 0 0 14.07 12.288zm170.72-113.58c-4.598 4.587-12.376 5.044-21.016 2.1l-23.114 23.106c11.619 18.87 14.387 37.24 5.317 46.318-3.666 3.665-8.869 5.406-14.994 5.406-11.294 0-25.77-5.915-39.788-16.506a23.267 23.267 0 0 1-.933-.721 104.315 104.315 0 0 1-2.988-2.382c-.395-.325-.782-.641-1.177-.975-.94-.8-1.88-1.627-2.821-2.47a55.09 55.09 0 0 1-1.108-.994 117.656 117.656 0 0 1-7.892-7.936c-.818-.905-1.592-1.82-2.365-2.724-.43-.502-.87-.994-1.283-1.503a110.976 110.976 0 0 1-2.909-3.657c-.114-.15-.237-.308-.352-.457-16.436-21.682-21.683-44.49-11.232-54.932 3.674-3.673 8.859-5.405 14.994-5.405 9.035 0 20.092 3.78 31.333 10.696l23.097-23.088c-2.943-8.64-2.486-16.427 2.101-21.015 8.605-8.604 28.372-2.795 44.148 12.981s21.585 35.544 12.981 44.157z" data-name="Pin"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#0d0deb"},":hover":{"color":{"text":"#17496f"}}}},"color":{"text":"#17496f"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#17496f"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // info box 3
            register_block_pattern(
                'maxboxy/infobox-info-flag', [
                'title'         => esc_html__('Info - Flag', 'maxboxy'),
                'keywords'      => ['info box', 'info', 'flag'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"}},"color":{"background":"#e6eff9","text":"#0d0deb"},"elements":{"link":{"color":{"text":"#0d0deb"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#0d0deb;background-color:#e6eff9;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width: 100px; display: block;fill: #0D0DEB;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zm-67.817 276.082a6.3 6.3 0 0 1-12.599 0V178.885a6.3 6.3 0 1 1 12.6 0zM343.57 276.75c-50.05-46.212-92.052 46.204-142.106 0V175.958c50.062 46.204 92.056-46.213 142.106 0z" data-name="Flag"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#0d0deb"},":hover":{"color":{"text":"#17496f"}}}},"color":{"text":"#17496f"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#17496f"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // green box 1
            $info_bulb_content = [
                'title'         => esc_html__('Info - Bulb on', 'maxboxy'),
                'keywords'      => ['info box', 'success', 'bulb'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#e0ffec","text":"#38b265"},"elements":{"link":{"color":{"text":"#38b265"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#38b265;background-color:#e0ffec;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #38b265;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82a182.18 182.18 0 1 0 0 364.36c100.608 0 182.18-81.571 182.18-182.18S356.608 73.82 256 73.82zm-1.512 265.333c-15.662 0-28.354-13.456-28.354-30.067h56.703c0 16.611-12.695 30.067-28.349 30.067zm26.306-45.87h-52.62c-1.42-15.495-38.232-28.995-38.232-67.71 0-41.291 30.885-63.185 64.538-64.011h.008c33.654.826 64.53 22.72 64.53 64.01.004 38.716-36.81 52.216-38.224 67.711z" data-name="Tips On"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#38955a"},":hover":{"color":{"text":"#0b321a"}}}},"color":{"text":"#0b321a"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#0b321a"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
            ];

            register_block_pattern('maxboxy/infobox-info-bulb', $info_bulb_content +['categories' => ['maxboxy-infoboxes']]);
            register_block_pattern('maxboxy/infobox-info-bulb-modal', $info_bulb_content +$modal_offer);

            // green box 2
            register_block_pattern(
                'maxboxy/infobox-info-bell', [
                'title'         => esc_html__('Info - Bell', 'maxboxy'),
                'keywords'      => ['info box', 'success', 'bell'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#e0ffec","text":"#38b265"},"elements":{"link":{"color":{"text":"#38b265"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#38b265;background-color:#e0ffec;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #38b265;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825c-100.613 0-182.18 81.562-182.18 182.17a182.18 182.18 0 0 0 364.36 0c0-100.608-81.572-182.17-182.18-182.17zm0 282.92a23.683 23.683 0 0 1-23.682-23.678h47.36A23.68 23.68 0 0 1 256 356.745zm80.015-47.425c0 8.753-7.092 9.334-15.841 9.334H191.822c-8.749 0-15.837-.58-15.837-9.334v-1.512a15.814 15.814 0 0 1 9.009-14.247l5.03-43.418a66.01 66.01 0 0 1 52.41-64.591v-16.857a13.572 13.572 0 0 1 27.146 0v16.857a66.01 66.01 0 0 1 52.404 64.591l5.032 43.427a15.793 15.793 0 0 1 9.009 14.238v1.512z" data-name="Notification"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#38955a"},":hover":{"color":{"text":"#0b321a"}}}},"color":{"text":"#0b321a"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#0b321a"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // green box 3
            register_block_pattern(
                'maxboxy/infobox-info-thumbup', [
                'title'         => esc_html__('Info - Thumb up', 'maxboxy'),
                'keywords'      => ['info box', 'success', 'thumb'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#e0ffec","text":"#38b265"},"elements":{"link":{"color":{"text":"#38b265"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#38b265;background-color:#e0ffec;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #38b265;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.177 182.177 0 0 0-182.179 182.18c0 100.608 81.563 182.17 182.179 182.17 100.61 0 182.18-81.562 182.18-182.17A182.182 182.182 0 0 0 256 73.825zm-44.63 231.1a17.811 17.811 0 1 1-35.623 0v-75.93a17.811 17.811 0 1 1 35.623 0zm125.806-1.275c0 13.913-8.13 18.897-22.051 18.897h-64.047a25.198 25.198 0 0 1-25.198-25.198v-63s-1.248-10.477 10.354-20.33a104.718 104.718 0 0 0 23.097-29.11c13.517-25.628 21.603-33.09 27.773-31.086 22.808 7.382 11.4 41.202 4.043 55.327h20.83a25.203 25.203 0 0 1 25.199 25.198v69.302z" data-name="Like"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#38955a"},":hover":{"color":{"text":"#0b321a"}}}},"color":{"text":"#0b321a"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#0b321a"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // warning box 1
            $info_warning_exclamation_content = [
                'title'         => esc_html__('Warning - Exclamation mark', 'maxboxy'),
                'keywords'      => ['info box', 'warning', 'exclamation mark'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#fef8ee","text":"#f0b849"},"elements":{"link":{"color":{"text":"#f0b849"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#f0b849;background-color:#fef8ee;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #f0b849;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825a182.175 182.175 0 1 0 182.18 182.179A182.182 182.182 0 0 0 256 73.824zm-.791 288.79a18.897 18.897 0 1 1 18.905-18.897 18.901 18.901 0 0 1-18.905 18.898zm23.466-83.47a22.047 22.047 0 0 1-44.094 0V162.595a22.047 22.047 0 1 1 44.094 0z" data-name="Warning"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#f0b849"},":hover":{"color":{"text":"#2e2506"}}}},"color":{"text":"#2e2506"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#2e2506"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
            ];
            register_block_pattern('maxboxy/infobox-warning-exclamation-mark', $info_warning_exclamation_content +['categories' => ['maxboxy-infoboxes']]);
            register_block_pattern('maxboxy/infobox-warning-exclamation-mark-modal', $info_warning_exclamation_content +$modal_offer);

            // warning box 2
            register_block_pattern(
                'maxboxy/infobox-warning-bulb-off', [
                'title'         => esc_html__('Warning -Bulb off', 'maxboxy'),
                'keywords'      => ['info box', 'warning', 'bulb'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#fef8ee","text":"#f0b849"},"elements":{"link":{"color":{"text":"#f0b849"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#f0b849;background-color:#fef8ee;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #f0b849;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Tips Off"><path d="M254.488 180.463c-21.164.694-45.641 12.875-45.641 45.114 0 16.33 9.677 26.016 19.924 36.272a108.034 108.034 0 0 1 11.299 12.524h28.832a107.343 107.343 0 0 1 11.303-12.524c10.24-10.256 19.916-19.942 19.916-36.272 0-32.239-24.477-44.42-45.633-45.114z"></path><path d="M256 73.825c-100.613 0-182.179 81.562-182.179 182.17a182.18 182.18 0 1 0 364.359 0c0-100.608-81.563-182.17-182.18-182.17zm-1.511 265.333c-15.663 0-28.354-13.456-28.354-30.076h56.703c0 16.62-12.697 30.076-28.35 30.076zm26.305-45.88h-52.62c-1.42-15.485-38.232-28.994-38.232-67.7 0-41.3 30.885-63.194 64.538-64.02h.01c33.652.826 64.533 22.72 64.533 64.02-.001 38.706-36.815 52.215-38.23 67.7z"></path></g></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#f0b849"},":hover":{"color":{"text":"#2e2506"}}}},"color":{"text":"#2e2506"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#2e2506"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // warning box 3
            register_block_pattern(
                'maxboxy/infobox-warning-star', [
                'title'         => esc_html__('Orange box - Star', 'maxboxy'),
                'keywords'      => ['info box', 'warning', 'star'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"top":{"width":"4px"},"right":{},"bottom":{},"left":{}},"color":{"background":"#fef8ee","text":"#f0b849"},"elements":{"link":{"color":{"text":"#f0b849"}}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#f0b849;background-color:#fef8ee;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #f0b849;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.825c-100.613 0-182.18 81.562-182.18 182.171A182.18 182.18 0 1 0 256 73.825zm101.549 164.513-45.756 38.778 14.233 58.306c2.51 10.89-9.478 19.53-18.97 13.677L256 317.572l-51.056 31.527c-9.76 6.134-21.761-2.787-18.967-13.668l14.23-58.315-45.751-38.778c-8.654-7.25-4.189-21.207 7.246-22.323l59.977-4.465 22.6-55.521c4.465-10.319 19.253-10.319 23.436 0l22.598 55.52 59.985 4.466a12.663 12.663 0 0 1 7.25 22.323z" data-name="Star"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#f0b849"},":hover":{"color":{"text":"#2e2506"}}}},"color":{"text":"#2e2506"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#2e2506"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // danger box 1
            $info_danger_stop_content = [
                'title'         => esc_html__('Danger - Stop', 'maxboxy'),
                'keywords'      => ['info box', 'danger', 'stop'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"color":{"background":"#f7e9ed","text":"#eb0d16"},"elements":{"link":{"color":{"text":"#eb0d16"}}},"border":{"top":{"width":"4px"}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#eb0d16;background-color:#f7e9ed;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #eb0d16;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Stop"><path d="M210.798 323.773A81.103 81.103 0 0 0 323.35 211.22zM256 175.282a81.078 81.078 0 0 0-63.685 131.335l113.88-113.872A80.69 80.69 0 0 0 256 175.282z"></path><path d="M256 73.82c-100.616 0-182.179 81.572-182.179 182.172C73.821 356.6 155.383 438.18 256 438.18c100.61 0 182.18-81.572 182.18-182.188 0-100.61-81.571-182.172-182.18-182.172zm0 288.94a106.339 106.339 0 1 1 106.339-106.338A106.458 106.458 0 0 1 256 362.761z"></path></g></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#eb0d16"},":hover":{"color":{"text":"#3e1e1e"}}}},"color":{"text":"#3e1e1e"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#3e1e1e"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
            ];

            register_block_pattern('maxboxy/infobox-danger-stop', $info_danger_stop_content +['categories' => ['maxboxy-infoboxes']]);
            register_block_pattern('maxboxy/infobox-danger-stop-modal', $info_danger_stop_content +$modal_offer);

            // danger box 2
            register_block_pattern(
                'maxboxy/infobox-danger-close', [
                'title'         => esc_html__('Danger - Close', 'maxboxy'),
                'keywords'      => ['info box', 'danger', 'close'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"color":{"background":"#f7e9ed","text":"#eb0d16"},"elements":{"link":{"color":{"text":"#eb0d16"}}},"border":{"top":{"width":"4px"}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#eb0d16;background-color:#f7e9ed;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #eb0d16;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 73.82A182.18 182.18 0 1 0 438.18 256 182.18 182.18 0 0 0 256 73.82zm90.615 272.724a24.554 24.554 0 0 1-34.712 0l-54.664-54.667-57.142 57.146a24.544 24.544 0 0 1-34.704-34.717l57.138-57.128-53.2-53.209a24.547 24.547 0 0 1 34.712-34.717l53.196 53.208 50.717-50.72a24.547 24.547 0 0 1 34.713 34.716l-50.713 50.722 54.659 54.65a24.56 24.56 0 0 1 0 34.717z" data-name="Close"></path></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#eb0d16"},":hover":{"color":{"text":"#3e1e1e"}}}},"color":{"text":"#3e1e1e"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#3e1e1e"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );

            // danger box 3
            register_block_pattern(
                'maxboxy/infobox-danger-shield', [
                'title'         => esc_html__('Danger - Shield', 'maxboxy'),
                'keywords'      => ['info box', 'danger', 'shield'],
                'categories'    => ['maxboxy-infoboxes'],
                'content'       => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"color":{"background":"#f7e9ed","text":"#eb0d16"},"elements":{"link":{"color":{"text":"#eb0d16"}}},"border":{"top":{"width":"4px"}}}} -->
                <div class="wp-block-columns has-text-color has-background has-link-color" style="border-top-width:4px;color:#eb0d16;background-color:#f7e9ed;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:column {"width":"8%","layout":{"type":"constrained"}} -->
                <div class="wp-block-column" style="flex-basis:8%"><!-- wp:html -->
                <svg style="width: auto; min-width: 40px; max-width:100px; display: block; fill: #eb0d16;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g data-name="Shield Off"><path d="m228.782 218.655-16.101 16.111 27.21 27.21-27.21 27.203 16.101 16.11 27.211-27.211 27.215 27.211 16.107-16.11-27.221-27.203 27.221-27.21-16.107-16.111-27.215 27.22-27.211-27.22z"></path><path d="M255.993 73.82c-100.608 0-182.17 81.571-182.17 182.18 0 100.6 81.562 182.18 182.17 182.18 100.613 0 182.185-81.571 182.185-182.18S356.606 73.82 255.993 73.82zm.01 282.85c-93.746 0-100.424-158.616-100.424-158.616l100.414-42.733h.01l100.414 42.733s-6.68 158.616-100.415 158.616z"></path></g></svg>
                <!-- /wp:html --></div>
                <!-- /wp:column -->
                
                <!-- wp:column {"width":"","style":{"elements":{"link":{"color":{"text":"#eb0d16"},":hover":{"color":{"text":"#3e1e1e"}}}},"color":{"text":"#3e1e1e"}}} -->
                <div class="wp-block-column has-text-color has-link-color" style="color:#3e1e1e"><!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Diam sit amet nisl suscipit adipiscing bibendum est ultricies. Sit amet aliquam id diam maecenas ultricies mi. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus. Elit duis tristique sollicitudin nibh.</p>
                <!-- /wp:paragraph --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns -->',
                ]
            );


            /*
             * Media
             */

            // media 1
            register_block_pattern(
                'maxboxy/media-cmgycati', [
                'title'         => esc_html__('Common media group (e.g. youtube code and text intro', 'maxboxy'),
                'keywords'      => ['media', 'video', 'html'],
                'categories'    => ['maxboxy-media'],
                'content'       => "<!-- wp:group -->
                <div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\"} -->
                <h2 class=\"has-text-align-center\"></h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {\"align\":\"center\"} -->
                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:html /--></div>
                <!-- /wp:group -->",
                ]
            );

            // media 2
            register_block_pattern(
                'maxboxy/media-emycatiwbc', [
                'title'         => esc_html__('Embed media (e.g. youtube code) and text intro (in a cover block)', 'maxboxy'),
                'keywords'      => ['media', 'video', 'html'],
                'categories'    => ['maxboxy-media'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#f6f0de\",\"isDark\":false} -->
                <div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-100 has-background-dim\" style=\"background-color:#f6f0de\"></span><div class=\"wp-block-cover__inner-container\"><!-- wp:group -->
                <div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\"} -->
                <h2 class=\"has-text-align-center\"></h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {\"align\":\"center\"} -->
                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:html /--></div>
                <!-- /wp:group --></div></div>
                <!-- /wp:cover -->",
                ]
            );

            // media 3
            $media_eyvabctaiacb_content = [
                'title'         => esc_html__('Example youtube video and a button call to action (in a cover block)', 'maxboxy'),
                'keywords'      => ['media', 'video', 'html'],
                'content'       => "<!-- wp:cover {\"customGradient\":\"linear-gradient(0deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%)\",\"isDark\":false} -->
                <div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient\" style=\"background:linear-gradient(0deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%)\"></span><div class=\"wp-block-cover__inner-container\"><!-- wp:group -->
                <div class=\"wp-block-group\"><!-- wp:spacer {\"height\":\"1.5em\"} -->
                <div style=\"height:1.5em\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer -->
                
                <!-- wp:heading {\"textAlign\":\"center\"} -->
                <h2 class=\"wp-block-heading has-text-align-center\">You're Going To Love This</h2>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {\"align\":\"center\"} -->
                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:html -->
                <iframe width=\"100%\" height=\"400\" src=\"https://www.youtube-nocookie.com/embed/aqz-KE-bpKQ\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>
                <!-- /wp:html -->
                
                <!-- wp:buttons {\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->
                <div class=\"wp-block-buttons\"><!-- wp:button -->
                <div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\">Join now!</a></div>
                <!-- /wp:button --></div>
                <!-- /wp:buttons -->
                
                <!-- wp:spacer {\"height\":\"0.5em\"} -->
                <div style=\"height:0.5em\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                <!-- /wp:spacer --></div>
                <!-- /wp:group --></div></div>
                <!-- /wp:cover -->",
            ];

            register_block_pattern('maxboxy/eyvabctaiacb', $media_eyvabctaiacb_content +['categories' => ['maxboxy-media']]);
            register_block_pattern('maxboxy/eyvabctaiacb-modal', $media_eyvabctaiacb_content +$modal_offer);


            /*
             * Signups
             */

            // Signup 1
            register_block_pattern(
                'maxboxy/signup-cng', [
                'title'         => esc_html__('Common newsletter group', 'maxboxy'),
                'keywords'      => ['Signup'],
                'categories'    => ['maxboxy-signups'],
                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":4,\"fontSize\":\"large\"} -->
                                <h4 class=\"has-text-align-center has-large-font-size\">GET OUR WEEKLY&nbsp;NEWSLETTER</h4>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center has-text-color\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse nec tincidunt nisi. Nam neque mi, tempor in pretium et, tincidunt ac urna. Nulla libero ligula, congue ut hendrerit in, posuere et ligula.</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:html /--></div>
                                <!-- /wp:group -->",
                ]
            );

            // Signup 2
            register_block_pattern(
                'maxboxy/signup-iacb', [
                'title'         => esc_html__('In a cover block', 'maxboxy'),
                'keywords'      => ['Signup'],
                'categories'    => ['maxboxy-signups'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#f6f0de\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#f6f0de\"><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"52px\"}}} -->
                                <h2 class=\"has-text-align-center\" style=\"font-size:52px\"><strong>JOIN 1000+ PALS</strong></h2>
                                <!-- /wp:heading -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4,\"fontSize\":\"large\"} -->
                                <h4 class=\"has-text-align-center has-large-font-size\">GET OUR WEEKLY&nbsp;NEWSLETTER</h4>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#f3eed3\"}}} -->
                                <p class=\"has-text-align-center has-text-color\" style=\"color:#f3eed3\">Jump in now to receive the newest hot stories and helpful information</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:html /--></div></div>
                                <!-- /wp:cover -->",
                ]
            );

            // Signup 3
            register_block_pattern(
                'maxboxy/signup-iacbwai', [
                'title'         => esc_html__('In a cover block with an image', 'maxboxy'),
                'keywords'      => ['Signup'],
                'categories'    => ['maxboxy-signups'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#f6f0de\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#f6f0de\"><div class=\"wp-block-cover__inner-container\"><!-- wp:image {\"sizeSlug\":\"large\",\"className\":\"is-style-default\"} -->
                                <figure class=\"wp-block-image size-large is-style-default\"><img src=\"https://via.placeholder.com/1200x450\"/></figure>
                                <!-- /wp:image -->
                                <!-- wp:spacer {\"height\":30} -->
                                <div style=\"height:30px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->

                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":3} -->
                                <h3 class=\"has-text-align-center\">Subscribe To Our Newsletter</h3>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean iaculis, velit a bibendum sodales.</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:html /--></div></div>
                                <!-- /wp:cover -->",
                ]
            );

            // Signup 4
            $signup_spiacbwaia_content = [
                'title'         => esc_html__('Signup placeholder in a cover block with an image aside', 'maxboxy'),
                'keywords'      => ['Signup, news'],
                'content'       => "<!-- wp:cover {\"customGradient\":\"linear-gradient(90deg,rgb(219,219,255) 0%,rgb(9,60,150) 100%)\",\"isDark\":false,\"layout\":{\"type\":\"constrained\"}} -->
                <div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient\" style=\"background:linear-gradient(90deg,rgb(219,219,255) 0%,rgb(9,60,150) 100%)\"></span><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"verticalAlignment\":null,\"align\":\"full\"} -->
                <div class=\"wp-block-columns alignfull\"><!-- wp:column {\"verticalAlignment\":\"center\"} -->
                <div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3} -->
                <h3 class=\"wp-block-heading has-text-align-center\">Join our newsletter!</h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {\"align\":\"center\"} -->
                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:html /--></div>
                <!-- /wp:column -->
                
                <!-- wp:column {\"verticalAlignment\":\"center\"} -->
                <div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:image {\"sizeSlug\":\"full\",\"linkDestination\":\"none\",\"className\":\"is-style-rounded\"} -->
                <figure class=\"wp-block-image size-full is-style-rounded\"><img src=\"" .esc_url(plugins_url('/library/img/coffee-and-tablet.jpeg', __FILE__)) ."\" alt=\"\"/></figure>
                <!-- /wp:image --></div>
                <!-- /wp:column --></div>
                <!-- /wp:columns --></div></div>
                <!-- /wp:cover -->",
            ];

            register_block_pattern('maxboxy/signup-spiacbwaia', $signup_spiacbwaia_content +['categories' => ['maxboxy-signups']]);
            register_block_pattern('maxboxy/signup-spiacbwaia-modal', $signup_spiacbwaia_content +$modal_offer);


            /*
             * 5 - higher priority than default,
             * so it loads before the patterns added by the Pro plugin
             */
        }, 5
    );
