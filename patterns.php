<?php

/**
 * Description: Patterns for MaxBoxy
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
                array( 'label' => esc_html__('MaxBoxy: Buttons', 'maxboxy') )
            );
        
            register_block_pattern_category(
                'maxboxy-contact',
                array( 'label' => esc_html__('MaxBoxy: Contact', 'maxboxy') )
            );

            register_block_pattern_category(
                'maxboxy-cookies',
                array( 'label' => esc_html__('MaxBoxy: Cookies & GDPR', 'maxboxy') )
            );
        
            register_block_pattern_category(
                'maxboxy-cta',
                array( 'label' => esc_html__('MaxBoxy: CTA', 'maxboxy') )
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

            // Contact 1
            register_block_pattern(
                'maxboxy/contact-cfwsi', [
                'title' => esc_html__('Contact form with social icons', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'categories'    => ['maxboxy-contact'],

                /* 
                 * @todo put in the modal on the page load:
                 * @link https://github.com/WordPress/gutenberg/pull/41791
                 * 'blockTypes' => array( 'core/post-content' ),
                 *  For specified post types, will work with Gutenberg plugin active,
                 * integration planned from wp 6.1:
                 * 'postTypes' => array( 'float_any', 'inject_any', 'wp_block' ),
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

            // Contact 2
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
                                <h4 class=\"has-text-align-center\">Contact form here...</h4>
                                <!-- /wp:heading -->
                                
                                <!-- wp:shortcode /-->
                                
                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Google map here...</h4>
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

            // Contact 3
            register_block_pattern(
                'maxboxy/contact-cfegmsiwacb', [
                'title'         => esc_html__('Contact form + embed google map + social icons (with a cover background)', 'maxboxy'),
                'keywords'      => ['contact', 'contact us'],
                'categories'    => ['maxboxy-contact'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#7cc9f8\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#7cc9f8\"><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->
                                
                                <!-- wp:shortcode /-->
                                
                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->
                                
                                <!-- wp:html /-->
                                
                                <!-- wp:heading {\"textAlign\":\"center\",\"level\":4} -->
                                <h4 class=\"has-text-align-center\">Your title here...</h4>
                                <!-- /wp:heading -->
                                
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
            register_block_pattern(
                'maxboxy/cookies-4', [
                'title'         => esc_html__('2 columns + 1', 'maxboxy'),
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
                                <div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"mboxy-closer\"} -->
                                <div class=\"wp-block-button mboxy-closer\"><a class=\"wp-block-button__link\">I agree</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:column --></div>
                                <!-- /wp:columns -->
                                
                                <!-- wp:separator -->
                                <hr class=\"wp-block-separator\"/>
                                <!-- /wp:separator -->
                                
                                <!-- wp:spacer {\"height\":10} -->
                                <div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->
                                
                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\">You can read more from our <a href=\"#\">Privacy Policy</a>.</p>
                                <!-- /wp:paragraph --></div>
                                <!-- /wp:group -->",
                ]
            );
        
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
                'title'         => esc_html__('Common CTA group', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action'],
                'categories'    => ['maxboxy-cta'],
                'content'       => "<!-- wp:group -->
                                <div class=\"wp-block-group\"><!-- wp:spacer {\"height\":40} -->
                                <div style=\"height:40px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->
                                
                                <!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"color\":{\"text\":\"#414446\"}}} -->
                                <h2 class=\"has-text-align-center has-text-color\" style=\"color:#414446\">Major attention message!</h2>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#414446\"}}} -->
                                <p class=\"has-text-align-center has-text-color\" style=\"color:#414446\">Write additional text here.</p>
                                <!-- /wp:paragraph -->
                                
                                <!-- wp:spacer {\"height\":20} -->
                                <div style=\"height:20px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->
                                
                                <!-- wp:buttons {\"contentJustification\":\"center\",\"orientation\":\"vertical\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center is-vertical\"><!-- wp:button {\"className\":\"has-custom-width wp-block-button__width-25 is-style-outline\"} -->
                                <div class=\"wp-block-button has-custom-width wp-block-button__width-25 is-style-outline\"><a class=\"wp-block-button__link\">Call to action</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div>
                                <!-- /wp:group -->",
                ]
            );

            // CTA 2
            register_block_pattern(
                'maxboxy/cta-ctapc', [
                'title'         => esc_html__('CTA positioned central', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action'],
                'categories'    => ['maxboxy-cta'],
                'content'       => "<!-- wp:cover {\"customGradient\":\"linear-gradient(0deg,rgb(254,205,165) 0%,rgb(254,45,45) 33%,rgb(107,0,62) 100%)\"} -->
                                <div class=\"wp-block-cover has-background-dim has-background-gradient\" style=\"background:linear-gradient(0deg,rgb(254,205,165) 0%,rgb(254,45,45) 33%,rgb(107,0,62) 100%)\"><div class=\"wp-block-cover__inner-container\"><!-- wp:spacer {\"height\":10} -->
                                <div style=\"height:10px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->
                                
                                <!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"color\":{\"text\":\"#ffffff\"}}} -->
                                <h2 class=\"has-text-align-center has-text-color\" style=\"color:#ffffff\">Major attention message!</h2>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#ffffff\"}}} -->
                                <p class=\"has-text-align-center has-text-color\" style=\"color:#ffffff\">Write additional text here.</p>
                                <!-- /wp:paragraph -->
                                
                                <!-- wp:spacer {\"height\":30} -->
                                <div style=\"height:30px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->
                                
                                <!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"width\":75,\"style\":{\"color\":{\"background\":\"#8c1111\"}}} -->
                                <div class=\"wp-block-button has-custom-width wp-block-button__width-75\"><a class=\"wp-block-button__link has-background\" style=\"background-color:#8c1111\">GET IT</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div></div>
                                <!-- /wp:cover -->",
                ]
            );

            // CTA 3
            register_block_pattern(
                'maxboxy/cta-ctapbpfbi', [
                'title'         => esc_html__('CTA positioned bottom (perfect for background image)', 'maxboxy'),
                'keywords'      => ['CTA', 'call to action'],
                'categories'    => ['maxboxy-cta'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#a9c3ed\",\"minHeight\":500,\"contentPosition\":\"bottom center\",\"className\":\"has-background-gradient is-position-top-center\"} -->
                                <div class=\"wp-block-cover has-background-dim has-custom-content-position is-position-bottom-center has-background-gradient is-position-top-center\" style=\"background-color:#a9c3ed;min-height:500px\"><div class=\"wp-block-cover__inner-container\"><!-- wp:spacer -->
                                <div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
                                <!-- /wp:spacer -->

                                <!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"black\",\"fontSize\":\"large\"} -->
                                <p class=\"has-text-align-center has-black-color has-text-color has-large-font-size\">YOUR ATTENTION MESSAGE!</p>
                                <!-- /wp:paragraph -->
                                
                                <!-- wp:buttons {\"contentJustification\":\"center\"} -->
                                <div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"textColor\":\"black\",\"width\":50,\"className\":\"has-custom-width wp-block-button__width-25 is-style-outline\"} -->
                                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 wp-block-button__width-25 is-style-outline\"><a class=\"wp-block-button__link has-black-color has-text-color\">Get it!</a></div>
                                <!-- /wp:button -->

                                <!-- wp:button {\"textColor\":\"black\",\"width\":50,\"className\":\"mboxy-closer is-style-outline\"} -->
                                <div class=\"wp-block-button has-custom-width wp-block-button__width-50 mboxy-closer is-style-outline\"><a class=\"wp-block-button__link has-black-color has-text-color\">No thanks.</a></div>
                                <!-- /wp:button --></div>
                                <!-- /wp:buttons --></div></div>
                                <!-- /wp:cover -->",
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
                                <h2 class=\"has-text-align-center\">Write your heading here...</h2>
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
                'title'         => esc_html__('Embed media (e.g. youtube code) and text intro (with background cover)', 'maxboxy'),
                'keywords'      => ['media', 'video', 'html'],
                'categories'    => ['maxboxy-media'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#6340a7\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#6340a7\"><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"center\"} -->
                                <h2 class=\"has-text-align-center\">Write your heading here...</h2>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {\"align\":\"center\"} -->
                                <p class=\"has-text-align-center\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                <!-- /wp:paragraph -->
                                
                                <!-- wp:html /--></div></div>
                                <!-- /wp:cover -->",
                ]
            );


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
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#002b43\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#002b43\"><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"52px\"}}} -->
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
                'maxboxy/signup-iacbwi', [
                'title'         => esc_html__('In a cover block with image', 'maxboxy'),
                'keywords'      => ['Signup'],
                'categories'    => ['maxboxy-signups'],
                'content'       => "<!-- wp:cover {\"customOverlayColor\":\"#5b95b0\"} -->
                                <div class=\"wp-block-cover has-background-dim\" style=\"background-color:#5b95b0\"><div class=\"wp-block-cover__inner-container\"><!-- wp:image {\"sizeSlug\":\"large\",\"className\":\"is-style-default\"} -->
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

            /*
             * 5 - higher priority than default, 
             * so it loads before the patterns added by the Pro plugin
             */
        }, 5 
    );
