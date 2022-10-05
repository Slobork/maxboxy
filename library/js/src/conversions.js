/*
 *  All regular jQuery here
 */
jQuery(document).ready(function ($) {

	"use strict";



	/**
	 * Set a page view counter (if the conversion, i.e. tracking is allowed).
	 *
	 * It is different from the countLoads/countViews coz unlike those counts which count for each Panel, this counts Website's page views.
	 * It is stored in localStorage. Later utilized by Appear trigger's "On page views" @see Pro's Max_Boxy_Conditionals::appear_triggers()
	 */
	function pageViewsCounter() {

		if ( $('body').prop('class').match(/maxboxy-tracking-on/) ) {

			if (localStorage.maxboxy_pagecount) {

				localStorage.maxboxy_pagecount = Number(localStorage.maxboxy_pagecount) +1;

			// if not yet defined, i.e 1st load, set it to 1
			} else {

				localStorage.maxboxy_pagecount = 1;

			}

		// if conversion tracking is disabled
		} else {

			if (localStorage.maxboxy_pagecount) {
				localStorage.maxboxy_pagecount = ''; // localStorage.removeItem(maxboxy_pagecount); doesn't work, so lets just empty the key
			}

		}

	}
	pageViewsCounter();


	/**
	 * Panel loads count.
	 */
	$.fn.countLoads = function() {

		this.each( function () {

			var panel			=	$(this),
				panel_id_data	=	typeof panel.data('panel-id')  !== 'undefined'	?	panel.data('panel-id')   : '',
				panel_name		=	'maxboxy-count-loads-' +panel.attr('id'),
				new_visitor		=	typeof(Storage) !== 'undefined' && localStorage.getItem(panel_name) !== 'true' ?	true	:	'';

			// just to be sure, prevent repeating the count on the same page load (important for togglers/igniters/rottators) -> dependant on addClass('load-counted') beneath
			if ( panel_id_data !== '' && panel.prop('class').match(/stats-on/) && !panel.prop('class').match(/load-counted/) ) {

				// set data for ajax
				var data = {
					'action': 'maxboxy_update_load',
					'panel_id': panel_id_data,
					'new_visitor': new_visitor,
					'nonce': maxboxy_localize.mb_nonce,
				};

				// ajax update: views count
				$.post(
					maxboxy_localize.ajax_url,
					data,
					function() {
						panel.addClass('load-counted');

						// Store 'maxboxy-count-loads-' for unique_visitor tracking
						if (typeof(Storage) !== 'undefined') {
							localStorage.setItem(panel_name, 'true');
						}

					}

				);

			}

		});

		return this;

	};

	// Do count if the panel isn't splitted. If its splitted, it'll be counted from the @see splitter.js' splitter() i.e. Pro.
	$('.mboxy-wrap:not(.is-split)').countLoads();


	/**
	 * Count views.
	 */
	$.fn.countViews = function() {

		this.each( function () {

			var panel          =	$(this),
				panel_id_data  =	typeof panel.data('panel-id')  !== 'undefined' ?	panel.data('panel-id')   : '',
				panel_name     =	'maxboxy-is-viewed-' +panel.attr('id'),
				new_visitor    =	typeof(Storage) !== 'undefined' && localStorage.getItem(panel_name) !== 'true'	?	true	:	'';

			// just to be sure, prevent repeating the count on the same page load (important for togglers/igniters/rottators) -> dependant on addClass('view-counted') beneath
			if ( panel_id_data !== '' && panel.prop('class').match(/stats-on/) && !panel.prop('class').match(/view-counted/) ) {

				// set data for ajax
				var data = {
					'action': 'maxboxy_update_views',
					'panel_id': panel_id_data,
					'new_visitor': new_visitor,
					'nonce': maxboxy_localize.mb_nonce,
				};

				// ajax update: views count
				$.post(
					maxboxy_localize.ajax_url,
					data,
					function() {
						panel.addClass('view-counted');

						// Store 'maxboxy-is-viewed-' for unique_visitor tracking
						if (typeof(Storage) !== 'undefined') {
							localStorage.setItem(panel_name, 'true');
						}

					}

				);

			}

		});

		return this;

	};


	/**
	 * Goals complete.
	 */
	$.fn.goalCompleteCount = function() {

		this.each( function () {

			var panel          =	$(this),
				panel_id_data  =	typeof panel.data('panel-id')  !== 'undefined' ?   panel.data('panel-id')   : '',
				panel_name     =	'maxboxy-goal-completed-' +panel.attr('id'),
				new_visitor    =	typeof(Storage) !== 'undefined' && localStorage.getItem(panel_name) !== 'true'	?	true	:	'';

			// prevent repeating the count on the same submit (bubbling) -> dependant on addClass('goal-complete') beneath
			if (!panel.prop('class').match(/goal-complete/) ) {

				// set data for ajax
				var data = {
					'action': 'maxboxy_update_goals',
					'panel_id': panel_id_data,
					'new_visitor': new_visitor,
					'nonce': maxboxy_localize.mb_nonce,
				};

				// ajax update: goals complete
				$.post(
					maxboxy_localize.ajax_url,
					data,
					function() {
						panel.addClass('goal-complete');

						// Store 'maxboxy-goal-completed-' for unique_visitor tracking
						if (typeof(Storage) !== 'undefined') {
							localStorage.setItem(panel_name, 'true');
						}

					}
				);

			}

		});

		return this;

	};


	/**
	 * Goal - on click.
	 */
	$.fn.goalClick = function() {

		this.each( function () {

			var panel               =   $(this),
				goal_target_data    =   typeof panel.data('goal-target')  !== 'undefined' ?   panel.data('goal-target')  : '',
				goal_target         =   panel.find(goal_target_data);

			if (goal_target.length) {

				goal_target.on('click', function() {

					panel.goalCompleteCount();

					// if the goal-after is to ban the panel from appearing further for the user
					if ( $(this).closest('.goal-click').prop('class').match(/goal-after-banish/) ) {
						$(this).panelMarkBanished();
					}

				});

			}

		});

		return this;

	};

	$('.mboxy-wrap.goal-click').goalClick();


	/**
	 * Goal - on submit.
	 */
	$.fn.goalSubmitForm = function() {

		this.on('submit', function() {

			var panel						=   $(this).closest('.goal-submit'),
				submit						=   $(this),
				goal_form_has_class_data    =   typeof panel.data('goal-form-hasclass')     !== 'undefined' ?   panel.data('goal-form-hasclass')    : '',
				goal_panel_find_class_data  =   typeof panel.data('goal-panel-findclass')   !== 'undefined' ?   panel.data('goal-panel-findclass')  : '',
				observe_target 				=	submit.get(0);// observer's target has to be a Node or Element. This will set it

				// Create an observer callback
				var observer = new MutationObserver(function() {

					var check_form_has_class	=	goal_form_has_class_data.length && submit.prop('class').match(goal_form_has_class_data)		?	true : false,
					check_broad_class_search	=	goal_panel_find_class_data.length && panel.find( '.' +goal_panel_find_class_data).length	?	true : false;

					if ( check_form_has_class === true || check_broad_class_search === true ) {

						if (!panel.prop('class').match(/check-goal-submit/) ) {
							panel.addClass('check-goal-submit');

							panel.goalCompleteCount();

							// mark banished
							if ( panel.prop('class').match(/goal-after-banish/) ) {
								submit.panelMarkBanished();
							}

						}

					}

				});

				// Pass in the observe_target node, as well as the observer options
				observer.observe(observe_target, {
					attributes:    true,
					subtree:       true,
					childList:     true,
				});

				// suppose, it should be performance wise to disconnect the observer
				submit.doTimeout( 'disconnect', 500, function () {
					if (panel.prop('class').match(/check-goal-submit/) ) {
						observer.disconnect();
					}
				});

				/**
				 * Allow another submission, i.e. multiple submits without the page refresh.
				 * This makes sence since it will be counted as Volume goals complete and we have unique visitor tracking anyway,
				 * so no need to restrict multiple submits without the refresh.
				 * 'goal-complete' class is added at @see goalCompleteCount(), here we reset it.
				 */
				submit.doTimeout( 'reset', 2000, function () {
					panel.removeClass( 'check-goal-submit goal-complete' );
				});

		});

		return this;

	};

	$('.mboxy-wrap.goal-submit form').goalSubmitForm();




}); // end jQuerys
