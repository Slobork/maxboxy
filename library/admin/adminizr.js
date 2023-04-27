jQuery(document).ready(function($) {

	  "use strict";


	/**
	 * Reset the individual panel's tracking stats (conversion stats).
	 */
	 var reset_panel_stats	=	$('.maxboxy-reset-stats');
	 
	 reset_panel_stats.on('click', function() {

		 // set data for ajax
		 var data = {
			 'action': 'maxboxy_reset_panel_stats',
			 'post_id': maxboxy_localize.post_id,
			 'nonce': maxboxy_localize.mb_nonce,
			};

			// ajax update
			$.post(
				maxboxy_localize.ajax_url,
				data,
				function(response) {
					reset_panel_stats.addClass('postid-reseted');
					reset_panel_stats.parent().html('<div>' +response +'</div>'); // replace the stats content with a rested values from the response
				}
				);
				
			});


	/**
	 * Remove the "scroll" option from the 'appear_after_event' field for the injectany since it cannot work in that case.
	 */
	if ( $('#_mb_injectany').length && $('#_mb_maxboxy_conditionals').length ) {
		$('[name="_mb_maxboxy_conditionals[box_options][appear_after_event]"] option[value="scroll"]').remove();
	}


	/**
	 * For the panel_type change unset_toggler's leftover.
	 */
	var selected_panel_type = $("input[type='radio'][name='_mb_floatany[box_options][panel_type]']:checked, input[type='radio'][name='_mb_injectany[box_options][panel_type]']:checked").val(),
		unset_toggler_close = $('.mb-unset-toggling-default .csf--button-group > .csf--button:nth-child(2)'); // unset toggler options (2nd option i.e. Closer)


	// ...When Panel type is changed to "Closer", unset_togler may be left with its "Out Closer" option which is surplus
	function leftoverUnsetToggler() {

		var unset_toggler_value = $("input[type='radio'][name='_mb_floatany[box_options][unset_toggler]']:checked").val();

		// if the value of the "unset_toggler" is Closer, i.e. it's selected while the Toggler was the panel_type option and it's leftover now for the Closer selection
		if (unset_toggler_value === 'closer' ) {
			$('.mb-unset-toggling-default')
				.addClass('force-closer-hide')
				.nextAll().css('display', 'none');

			// for the "Remove" button, set it as active
			$('.mb-unset-toggling-default .csf--button-group > .csf--button:last-child').addClass('csf--active');
		}

	}

	// reset the above's function - used when returned to panel_tape's Toggler and on unset_toggler's buttons click
	function leftoverUnsetTogglerReset() {

		if ( $('.mb-unset-toggling-default').prop('class').match(/force-closer-hide/) ) {

			$('.mb-unset-toggling-default')
				.removeClass('force-closer-hide')
				.nextAll().css('display', '');
		}

	}

	// with Toggler, hide Panel Role options that aren't in use (i.e. hidden, exiter, banish)
	if (selected_panel_type === 'toggler' ) {

		$('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:not(:nth-last-of-type(-n+2))').css('display', 'none');
		$('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf-help').css('display', 'none'); // hide the help (?), coz options are much different than 'closer'

	}

	// ...and with Closer,
	if (selected_panel_type === 'closer' ) {

		// ...hide the Igniter role
		$('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:last-child').css('display', 'none');

		// unset toggler options (2nd option i.e. Closer)
		unset_toggler_close.hide();
		leftoverUnsetToggler();

	}

	// ...do the same on the panel type's change (for the Panel type's Toggler click)
	$('.maxboxy-panel-type').find('.csf--button-group > .csf--button:first-child').click( function() {

		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:not(:nth-last-of-type(-n+2))').css('display', 'none');
		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:nth-last-of-type(-n+2)').css('display', '');
		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf-help').css('display', 'none');  // hide the help (?), coz options are much different than 'closer'

		unset_toggler_close.show();
		leftoverUnsetTogglerReset();

		// if the value of the "unset_toggler" is Closer
		var unset_toggler_value = $("input[type='radio'][name='_mb_floatany[box_options][unset_toggler]']:checked").val();
		if (unset_toggler_value === 'closer' ) {
			// remove the class applied to the "Remove" button, i.e. reset the added class in the leftoverUnsetToggler()
			$('.mb-unset-toggling-default .csf--button-group > .csf--button:last-child').removeClass('csf--active');
		}

	});

	// ...and on Panel type's Closer click
	$('.maxboxy-panel-type').find('.csf--button-group > .csf--button:last-child').click( function() {

		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:not(:last-child)').css('display', '');
		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf--button-group > .csf--button:last-child').css('display', 'none');
		$(this).parents('.maxboxy-panel-type').siblings('.maxboxy-panel-roles').find('.csf-help').css('display', '');

		unset_toggler_close.hide(); // unset toggler options (2nd option i.e. Closer)
		leftoverUnsetToggler();

	});

	// if some of the unset_toggler' s buttons are clicked, reset the leftover and actually apply csf default options
	$('.mb-unset-toggling-default .csf--button-group > .csf--button').click( function() {
		leftoverUnsetTogglerReset();
	});


	/**
	 * Since there's a complex addition of the MaxBoxy items in the admin_menu,
	 * it doesn't open (toggles) the submenu when its pages are loaded. The following resolves it.
	 */
	$('.admin_page_maxboxy-settings, .taxonomy-maxboxy_cat, .taxonomy-maxboxy_tag, .maxboxy_page_maxboxy-licenses').find('#toplevel_page_admin-page-maxboxy-settings')
		.addClass('wp-has-current-submenu wp-menu-open')
		.removeClass('wp-not-current-submenu')
		.children('a').addClass('wp-has-current-submenu')
		.attr('aria-haspopup', 'false'); // match the attribute applied to other items

	// ...further the following will mark as the 'curent' item for the settings page
	$('.admin_page_maxboxy-settings #toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li.wp-first-item').addClass('current').children('a').addClass('current');

	/**
	 *  ....however, to mark those items from the behind, we have unequal number of items with Basic and Pro plugin version,
	 * so the fake item is added with PHP @see Max_Boxy::admin_menu.
	 * Now, if the last item is empty, i.e. the Basic version, just hide it
	 */
	if ($('#toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:last-child> a').html() === '') {
		$('#toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:last-child').hide();
	}

	// ...while, if the 2nd from behind is empty, that's the Pro version, have to remove it
	if ($('#toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:nth-last-of-type(2)> a').html() === '') {
		$('#toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:nth-last-of-type(2)').remove();
	}

	// ...Now, marking as 'current' those items from behind will work in both cases, Basic and Pro
	$('.maxboxy_page_maxboxy-licenses #toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:last-child').addClass('current').children('a').addClass('current');
	$('.taxonomy-maxboxy_cat #toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:nth-last-of-type(3)').addClass('current').children('a').addClass('current');
	$('.taxonomy-maxboxy_tag #toplevel_page_admin-page-maxboxy-settings .wp-submenu-wrap > li:nth-last-of-type(2)').addClass('current').children('a').addClass('current');


	/**
	 * With InjectAny location "Head", hide options that cannot be used.
	 */
	var injectany_location	=	$('[name="_mb_injectany_loading[location]"]');

	if ( injectany_location.length ) {

		var loading_metabox		=	$('#_mb_injectany_loading'),
			global_loading_op	=	$("input[type='radio'][name='_mb_injectany_loading[auto_loading]']:checked").val(),
			head_surplus_fields =	$('.hide-on-injectany-head-location'); // class added to the CSF

		// on page's load
		if ( global_loading_op === 'enabled' && injectany_location.val() === 'head' ) {
			// hide all metaboxes except the Conditionals
			loading_metabox.siblings().not('#_mb_maxboxy_conditionals').hide();
			// hide surplus fields
			head_surplus_fields.hide();
		} 

		// ...and on options change
		injectany_location.change(function() {
			
			if ($(this).val() === 'head') {
				loading_metabox.siblings().not('#_mb_maxboxy_conditionals').hide();
				head_surplus_fields.hide();
			} else {
				loading_metabox.siblings().not('#_mb_maxboxy_conditionals').show();
				head_surplus_fields.show();
			}
			
		});
		
		// ...and on global loading option change
		loading_metabox.find('.csf--button-group > .csf--button:first-child').click( function() {
			if ( injectany_location.val() === 'head' ) {
				loading_metabox.siblings().not('#_mb_maxboxy_conditionals').hide();
				head_surplus_fields.hide();
			}
		});

		loading_metabox.find('.csf--button-group > .csf--button:last-child').click( function() {
				loading_metabox.siblings().not('#_mb_maxboxy_conditionals').show();
				head_surplus_fields.show();
		});

	}

	/**
	 * On the splitters CPT - split item that already has been splitted - admin notice about their removal.
	 */
	 if ( $('#_mb_maxboxy_splitter').length ) {

		var splitters_remove_notice = $('.splitters-remove-notice');
		splitters_remove_notice.insertAfter($('#editor'));

	}


	/**
	 * Regular css - instead of loading a separate css file (since it's a small addition of css).
	 */

	// Major maxboxy's settings tabs (in Metaboxes)
	$('.major-maxboxy-options.csf-field-tabbed .csf-tabbed-nav ').css({
		'display'   : 'flex',
		'text-align': 'center',
		'overflow-x': 'auto',
	});

	$('.post-type-float_any, .post-type-inject_any, .post-type-wp_block').find('.postbox-header h2').css({
		'padding': '18 24px',
		'font-style': 'italic',
	});

	$('.major-maxboxy-options.csf-field-tabbed .csf-tabbed-nav a').css({
			'margin-bottom' : 'auto',
			'margin-right': 0,
			//'padding': '10px 12px',
			'padding': '18px 12px',
	});

	$('.major-maxboxy-options.csf-field-tabbed').css({
			'padding' : '1px 0',
	});

	$('.major-maxboxy-options.csf-field-tabbed .csf-field-subheading').css({
			'margin-top' : '40px',
	});

	// remove the border on the tabbed contetn (gets more clearly view)
	$('.major-maxboxy-options.csf-field-tabbed .csf-tabbed-content').css({
		'border' : 'none',
		});


	// remove metabox movers - maxboxy:
	$('#_mb_injectany, #_mb_floatany, #_mb_injectany_loading, #_mb_floatany_loading, #_mb_maxboxy_conversion, #_mb_maxboxy_conditionals, #_mb_maxboxy_splitter_info').find('.handle-order-higher, .handle-order-lower').css('display', 'none');


	// background fields are making horizontal scroller to appear (in panel-indi)
	$('.csf-field.csf-field-background .csf-field-select').css( 'max-width', '82%');


	// hide an empty field
	$( '.floatany-empty-field' ).css('display', 'none');

	// For the buttons set, set space between them, e.g. Panel roles
	$('.maxboxy-button-set-span.csf-field-button_set .csf--button').css({
		'padding'       : '5px 11px',
		'margin-top'    : '5px',
		'margin-right'  : '6px',
		'border-radius' : '0',
	});

	// put the fieldset fields in one row
	$('.fieldset-set-1-row .csf-fieldset-content').css('display', 'flex');

	/**
	 * Stats
	 */

	$('.maxboxy-stats-fraction').css({
		'display'				: 'grid',
		'grid-template-columns'	: 'repeat(3, 1fr)',
		'column-gap'			: '15px',
		'margin-bottom'			: '20px',
	});

	$('.maxboxy-stats-rate-wrap').css({
		'margin-top': '15px',
		'margin-bottom': '5px',
	});

	$('.maxboxy-stats-rate-title').css('border-bottom', '1px dotted');

	$('.maxboxy-stats-rate-main').css('padding', '8px 0');

	$('.maxboxy-stats-rate-cols').css({
		'display'				: 'grid',
		'grid-template-columns'	: 'repeat(5, 1fr)',
		'column-gap'			: '5px',
		'margin-top'			: '5px',
		'font-size' 			: '.95em',
	});
	$('.maxboxy-stats-rate-wrap > div, .maxboxy-stats-rate-cols .rate').css('text-align', 'center');

	$('.maxboxy-stats-rate-cols .multi-stat').css({
		'display': 'flex',
		'flex-direction': 'row',
	});
	
	$('.maxboxy-stats-more-pro').css('margin-bottom', '10px');

	// in splitter's stats - hide the lasr 'hr', visually it's better overview
	$('#maxboxy-splitter-stats-group .splitter-item:last-child hr').css('display', 'none');



}); // end jQuery
