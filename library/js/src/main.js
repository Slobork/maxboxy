/**
 *  All regular jQuery here
 */
jQuery(document).ready(function ($) {

	"use strict";



	/**
	 * Process panel based on its type & conditionals.
	 */
	$.fn.maxboxyOn = function() {

		this.each( function () {

				$(this).doTimeout( 'load_panel', 20, function () {

					// proceed if the panel isn't marked as banished
					if ( !$(this).prop('class').match(/is-banished/) ) {

						var wait_class                      =   $(this).prop('class').match(/on-appear-condition/)		? true : false,
							on_elm_present_class            =   $(this).prop('class').match(/appear-elm-present/)		? true : false,
							on_time_class                   =   $(this).prop('class').match(/appear-after-time/)		? true : false,
							on_scroll_class                 =   $(this).prop('class').match(/appear-after-scroll/)		? true : false,
							on_elm_inview_class             =   $(this).prop('class').match(/appear-after-elm-inview/)	? true : false,
							on_elm_outview_class            =   $(this).prop('class').match(/appear-after-elm-outview/)	? true : false,
							on_until_pageviews_class        =   $(this).prop('class').match(/appear-until-pageviews/)	? true : false,
							on_after_pageviews_class        =   $(this).prop('class').match(/appear-after-pageviews/)	? true : false,
							is_igniter                      =   $(this).prop('class').match(/role-igniter/)		? true : false,
							data_elm_present                =   typeof $(this).data('appear-elm-present')       !== 'undefined'     ?   $(this).data('appear-elm-present')          : '',
							data_time                       =   typeof $(this).data('appear-after-time')        !== 'undefined'     ?   $(this).data('appear-after-time') *1000     : '',
							data_scroll                     =   typeof $(this).data('appear-after-scroll')      !== 'undefined'     ?   $(this).data('appear-after-scroll')         : '',
							data_elm_inview                 =   typeof $(this).data('appear-after-elm-inview')  !== 'undefined'     ?   $(this).data('appear-after-elm-inview')     : '',
							data_elm_outview                =   typeof $(this).data('appear-after-elm-outview') !== 'undefined'     ?   $(this).data('appear-after-elm-outview')    : '',
							data_until_pageviews            =   typeof $(this).data('appear-until-pageviews')   !== 'undefined'     ?   $(this).data('appear-until-pageviews')      : '',
							data_after_pageviews            =   typeof $(this).data('appear-after-pageviews')   !== 'undefined'     ?   $(this).data('appear-after-pageviews')      : '',
							panel                           =   $(this);

						var showPanel  = function() {

								// igniter is handled differently, i.e. by adding the .igniteon to the trigger instead of .on class
								if ( is_igniter === true ) {

								panel.find('.trig-default').addClass('igniteon');
								panel.panelSize();

							} else {

								panel
								.addClass('on')
								.panelOverlayOn()
								.panelSize()
								.panelRotator();

								if ( $('body').prop('class').match(/maxboxy-tracking-on/) ) {
									panel.countViews();
								}

							}

						};

						// when necessary to remove the classes added by showPanel()
						var closePanel  = function() {

							if ( is_igniter === true ) {

								panel.find('.trig-default').removeClass('igniteon');

							} else {

								panel
								.removeClass('on')
								.panelOverlayOff();

							}

						};


						if (wait_class === true) {

						   // first, check per page condition (i.e. elm present & page views)
						   if (
							   /**
								* parse with || for:
								* 1. when 'elm is present' is an option but not that page
								* 2. until pageviews is an option but count over reached
								* 3. after pageviews is an option but count not yet reached
								*/
							   (on_elm_present_class === true && !$(data_elm_present).length)  ||  (on_until_pageviews_class === true  && localStorage.maxboxy_pagecount > data_until_pageviews)  ||  (on_after_pageviews_class === true  && localStorage.maxboxy_pagecount < data_after_pageviews)
							   ) {

								   // set 'return' if the above statments are met, so beneath code won't process
								   return;

							} else if (
							/**
							 * parse with || for:
							 * 1. when 'elm is present' is an option exist on the page
							 * 2. until pageviews is an option and count not yet reached
							 * 3. after pageviews is an option and count is reached
							 */
								(on_elm_present_class === true && $(data_elm_present).length)  ||  (on_until_pageviews_class === true  && localStorage.maxboxy_pagecount <= data_until_pageviews)  ||  (on_after_pageviews_class === true  && localStorage.maxboxy_pagecount >= data_after_pageviews)
							) {

								// other event conditional classes do not exists
								if (on_time_class === false && on_scroll_class === false && on_elm_inview_class === false && on_elm_outview_class === false) {

									showPanel();

								}

							}

							// ...now, check event based conditions
							if (on_time_class === true) {

								$(this).doTimeout( data_time, showPanel);

							} else if (on_scroll_class === true) {

								$(window).scroll(function () {

									if ($(this).scrollTop() > data_scroll) {

										showPanel();

									} else {

										// here it goes revers from the showPanel()
										closePanel();

									}

								});

							} else if ($(data_elm_inview).length) {

								// it didn't work on the initial load until I set the doTimeout above the .on('scroll', function ()
								$(this).doTimeout( 'visibility', 200, function () {

									$(window).on('scroll', function () {

										var window_height           = $(window).height(),
											window_top_position     = $(window).scrollTop(),
											window_bottom_position  = (window_top_position + window_height),
											element_height          = $(data_elm_inview).outerHeight(),
											element_top_position    = $(data_elm_inview).offset().top,
											element_bottom_position = element_top_position + element_height,
											view_point              = (element_bottom_position >= window_top_position) && (element_top_position <= window_bottom_position);

											if (view_point === true) {
												showPanel();
											} else if (panel.prop('class').match(/appear-after-elm-view-repeat/)) {
												closePanel();
											}

										});

									});

							} else if ($(data_elm_outview).length) {

									$(this).doTimeout( 'visibility', 200, function () {

										$(window).on('scroll', function () {

											var window_top_position     = $(window).scrollTop(),
												element_height          = $(data_elm_outview).outerHeight(),
												element_top_position    = $(data_elm_outview).offset().top,
												element_bottom_position = element_top_position + element_height;

											if (element_bottom_position < window_top_position) {
												showPanel();
											} else if (panel.prop('class').match(/appear-after-elm-view-repeat/)) {
												closePanel();
											}

									});

								});

							}

						// without the wait class
						} else {

							showPanel();

						}

					} // end marked as banished check

				});

			});

			return this;

	};


	/**
	 * On page load - Float Any.
	 */
	function onLoadFloatAny() {

		// set it for the initial opened panel
		var is_opened_panel = $('.floatany:not(.role-hidden):not(.is-split)');

		if (is_opened_panel.length) {

			is_opened_panel.maxboxyOn();

		}

	}
	onLoadFloatAny();


	/**
	 * Observing Inject Any appeareance on the page.
	 */
	function injectanyToObserver() {

		var options = {
			root: null,
			//rootMargin: "-10px",
			threshold: 0.1 // do not execute immediately, coz a section can be marked even though it's actually on the edge
		};

		var observer	=	new IntersectionObserver(injectanyToObserverCallback, options),
			items		=	document.querySelectorAll('.injectany:not(.role-hidden):not(.is-split)'); // watch the class

		for(var i in items) {

			if(!items.hasOwnProperty(i)) {
				continue;
			}
			observer.observe(items[i]);

		}

	}


	/**
	 * Callback for the injectanyToObserver() i.e. activate the Inject Any.
	 */
	function injectanyToObserverCallback(entries) {

		entries.forEach(function (entry) {

			var id_val		=	entry.target.getAttribute('id'),
				panel_id	=	$('#' +id_val);

			if (entry.isIntersecting) {

				// with 'intersected' class prevent the maxboxyOn() executing multiple times,
				// which prevents the appereance of the panel again if it's closed i.e. for closer panel
				if ( ! $(panel_id).prop('class').match(/intersected/) ) {

					$(panel_id)
					.addClass('intersected')
					.maxboxyOn();

					// set alignwide/alignfull classes
					if ( $(panel_id).prop('class').match(/style-alignwide/) ) {
						$(panel_id).addClass('alignwide');
					} else if ( $(panel_id).prop('class').match(/style-alignfull/) ) {
						$(panel_id).addClass('alignfull');
					}

				}

			// entry isn't intersecting anymore
			} else {

				// do not start until 'intersected' class is added ...i.e. prevent doing it prior the element is reached
				if ( $(panel_id).prop('class').match(/intersected/) ) {
					// ...fire the panelOverlayOff() once the intersection is over
					$(panel_id).panelOverlayOff();

					// ...also once intersecting is over, remove the overlay's mark
					if ( $(panel_id).prop('class').match(/ia-overlayed/) ) {
						$(panel_id).removeClass('ia-overlayed');
					}

				}

			}

		});

	}


	/**
	 * Watching by IntersectionObserver for Inject Any.
	 */
	if (window.IntersectionObserver) {
		injectanyToObserver();
	}


	/**
	 * Panel overlay - On.
	 */
	$.fn.panelOverlayOn = function() {

		this.each( function () {

			if ( $(this).find('>.mboxy-overlay').length ) {

				$(this).find('>.mboxy-overlay').addClass('on');

				// add scroll blocking if it's floatany.
				if ( $(this).prop('class').match(/floatany/) ) {
					$('body').addClass('maxboxy-overlay-on');
				}

				// If it's injectany mark it as overlayed.
				// This is significant for removing the overlay once the panel is out of viewport or on closing.
				if ( $(this).prop('class').match(/injectany/) ) {
					$(this).addClass( 'ia-overlayed' );
				}

			}

		});

		return this;

	};



	/**
	 * Panel overlay - Off.
	 */
	$.fn.panelOverlayOff = function() {

		this.each( function () {

			if ( $(this).find('>.mboxy-overlay').length ) {

				$(this).find('>.mboxy-overlay').removeClass('on');

				// remove scroll blocking if it's floatany
				if ( $(this).prop('class').match(/floatany/) ) {
					$('body').removeClass('maxboxy-overlay-on');
				}

			}

		});

		return this;

	};



	/**
	 * Panel rotator.
	 */
	$.fn.panelRotator = function() {

		this.each( function () {

			var panel           =   $(this),
				rotator_repeat  =   panel.prop('class').match(/rotator-repeat/) ? true : false,
				time_on         =   typeof panel.data('rotator-on')   !== 'undefined'     ?   panel.data('rotator-on')  *1000     : 5000,  // with *1000 convert seconds to miliseconds
				time_off        =   typeof panel.data('rotator-off')  !== 'undefined'     ?   panel.data('rotator-off') *1000     : 10000; // with *1000 convert seconds to miliseconds

			if (panel.prop('class').match(/role-rotator/)) {

				panel.addClass('rotator-started');

				// if exist, move all 'style' 'script' & 'link' tags to the beginning so they do not obstacle the next() traversing
				panel.find('> .mboxy > .mboxy-content').prepend(panel.find('> .mboxy > .mboxy-content').children('style, script, link'));

				// excluding 'style, script' tags with .not()
				var item = panel.find('> .mboxy > .mboxy-content').children().not('style, script');

				// ...mark the rotating items and set the 1st as active
				item.first().addClass('rotator-first rotator-active');
				item.last().addClass('rotator-last');

				var has_child_items  =   panel.find('.mboxy-content > .mboxy-rotator-parentor');

				if (has_child_items.length) {

					// first & last() won't make it when there's multiple .mboxy-rotator-parentor ...so set the $.each
					$.each(has_child_items, function () {

						var child_item = $(this).find('>ul>li');

						child_item
							.addClass('rotator-child')
							.first().addClass('rotator-first rotator-active')
							.last().addClass('rotator-last');

					});

				}

				// used multiple times, so the timeout is set here in the function
				var panelClose = function () {

					// On the set time, close the panel
					panel.doTimeout( 'closePanel', time_on, function () {

						panel
							.removeClass('on')
							.panelOverlayOff();

					});

				};

				// show the panel and close
				var panelDance = function () {

					//...show the panel
					panel
						.addClass('on')
						.panelOverlayOn();

					// ...close it on the delayed time (time is set in the function, coz panelClose is used multiple times)
					panelClose();

				};

				// marking the rotator item as active
				var rotatorDance = function () {

					// Swith to the next rotator item
					$.fn.moveToNext = function() {

						this.each( function() {

							$(this)
								.removeClass('rotator-active')
								.next().addClass('rotator-active');

						});

						return this;

					};

					// Return to the first item (executes when the rotator is repeating)
					$.fn.moveToFirst = function() {

						this.each( function() {

							$(this)
								.removeClass('rotator-active')
								.siblings('.rotator-first').addClass('rotator-active'); // ...activate the first one, so it gets executed again if the rotator is repeating

						});

						return this;

					};

					var rotator_active          =   panel.find('> .mboxy > .mboxy-content > .rotator-active'),
						rotator_last_active     =   rotator_active.length && rotator_active.prop('class').match(/rotator-last/)  ? true : false,
						has_children            =   rotator_active.length && rotator_active.prop('class').match(/mboxy-rotator-parentor/) ? true : false,
						rotator_children_end    =   rotator_active.length && rotator_active.prop('class').match(/rotator-children-end/)  ? true : false,
						mark_last               =   rotator_last_active === true && (has_children === false || has_children === true && rotator_children_end === true)   ? true : false,
						rotator_active_time     =   time_on + 850; // increment for 850 so it gives the time to panel to close, coz style transition (fadein, slide-horizontal, slide-vertical) are 800ms

					rotator_active.doTimeout( 'setRotatorDance', rotator_active_time, function () {

						// if rotator is repeating & and the last item is active
						if (rotator_repeat === true && mark_last === true) {

							rotator_active.moveToFirst(); // ...activate the first one, so it gets executed again if the rotator is repeating

						// if rotator isn't repeating & and the active item is the last one
						} else if (rotator_repeat === false && mark_last === true) {

							rotator_active.removeClass('rotator-active');

							panel.addClass('rotator-end'); // ...end rotation

						// ...everything else, i.e. independent from the rotator_repeat & mark_last
						} else {

							// if the active item is parentor, i.e. has children
							if (rotator_active.prop('class').match(/mboxy-rotator-parentor/)) {

								var child_active = rotator_active.find('.rotator-child.rotator-active');

								// active child isn't the last one
								if (child_active.not('.rotator-last').length) {

									child_active.moveToNext(); // ...just move to the next item

								// active child is the last one
								} else if (child_active.prop('class').match(/rotator-last/)) {

									child_active.moveToFirst(); // ...activate the first one, so it gets executed again if the rotator is repeating

									// further, set the parentor's behavior:

									// ...if the parentor item isn't the last item
									if (!rotator_active.prop('class').match(/rotator-last/)) {

										rotator_active.moveToNext();

									// if the rotator isn't repeating & the parentor item is the last top item (may be the only top item)
									} else if (rotator_repeat === false && rotator_active.prop('class').match(/rotator-last/)) {

										rotator_active
											.removeClass('rotator-active')
											.addClass('rotator-children-end');

										panel.addClass('rotator-end'); // ...end rotation

									// if the rotator is repeating & the parentor item is the last item & paretor item isn't the first rotator's item at the same time, i.e. it has more than one top item
									} else if (rotator_repeat === true && rotator_active.prop('class').match(/rotator-last/) && !rotator_active.prop('class').match(/rotator-first/)) {

										rotator_active.moveToFirst(); // ...activate the first one, so it gets executed again if the rotator is repeating

									}

								}

							// regular item
							} else {

								rotator_active.moveToNext();

							}

						}

					});

				};


				/**
				 * This is for the first rotator's item, i.e. when the panel gets initally open.
				 * ...After that it will be proceeding with the setInterval, i.e. doRotatorDance
				 */
				panelDance();
				rotatorDance();

				var interval =  time_on + time_off;

				var doRotatorDance = function() {

					if (panel.prop('class').match(/rotator-end/)) {

						clearInterval(doRotatorDance);

					// show the panel if .rotator-end doesn't exists
					} else {

						// if the rotator isn't paused by user's closing
						if (!panel.prop('class').match(/rotator-pause/)) {

							panelDance();
							rotatorDance();

						}

					}

				};

				setInterval(doRotatorDance, interval);

			} // end .role-rotator exists check

		});

		return this;

	};



	/**
	 * Exiter panel (show on browser closing approach).
	 */
	$.fn.exiterPanel = function() {

		this.each( function () {

			var panel = $(this);

			// proceed if the panel isn't marked as banished & mouse pointer aproches to the browser's edge
			$(document).on('mouseout', function (e) {

				if( !panel.prop('class').match(/is-banished/) && e.clientY < 0 ) {

					panel
					.addClass('on')
					.panelOverlayOn();

					if ( $('body').prop('class').match(/maxboxy-tracking-on/) ) {
						panel.countViews();
					}

					// rotaotor
					if (panel.prop('class').match(/role-rotator/)) {

						// if rotation is already run and finished, initialize it again
						if (panel.prop('class').match(/rotator-end/)) {

							panel
								.removeClass('rotator-end')
								.panelRotator();

						// if rotation isn't yet run, initialize it (this prevents another initializatoin during the existing rotation run)
						} else if (!panel.prop('class').match(/rotator-started/)) {

							panel.panelRotator();

						}

					}

				}

			});

		});

		return this;

	};

	$('.mboxy-wrap.role-exit').exiterPanel();



	/**
	 * Remove Banished Panel - prevent its appearing.
	 *
	 * Done by removing the panel if it's added to the localStorage as 'maxboxy-banish-'.
	 */
	$.fn.panelRemoveBanished = function() {

		this.each( function () {

			var panel		=	$(this),
				panel_name	= 'maxboxy-banish-' +panel.attr('id');

			if (localStorage.getItem(panel_name) === 'true') {

				// remove the panel
				$(this)
					.addClass('is-banished') // has to be marked as is-banished so exiter & basic panel aren't processed, even though it's removed from the page's source just beneath
					.remove();

			}

		});

		return this;

	};

	$('.role-banish, .goal-after-banish').panelRemoveBanished();



	/**
	 * Panel Mark Banished.
	 *
	 * Mark the panel as banised from further showing.
	 */
	$.fn.panelMarkBanished = function() {

		this.each( function () {

			if (typeof(Storage) !== 'undefined') {

				var panel       =   $(this).closest('.mboxy-wrap'),
					panel_name  =   'maxboxy-banish-' +panel.attr('id');

				// Store banished item ...no need to check here for the .maxboxy-tracking-on, coz the role-banish output is prevented on the PHP if the Conversion module is disabled
				localStorage.setItem(panel_name, 'true');

				// mark as banished, so exiter panel or repeating trigger on the same page's load aren't processed
				panel.addClass('is-banished');

			}

		});

		return this;

	};


	/**
	 * Toggler/closer's animation.
	 */
	$.fn.trigAnim = function() {

		this.each( function () {

			var trig      = $(this),
				echo      = $(this).prop('class').match(/anim-echo/)    ? true : false,
				onload    = $(this).prop('class').match(/anim-onload/)  ? true : false,
				clicker   = $(this).prop('class').match(/anim-doclick/) ? true : false,
				echo_time = typeof trig.data('anim-echo') !== 'undefined' ? trig.data('anim-echo') *1000 : 15000; // with *1000 convert seconds to miliseconds


			function pickAnim() {
				var rotate = trig.prop('class').match(/anim-rotate/) ? true : false;
				if (rotate === true) {
					trig.doTimeout(100, 'addClass', 'anim-do-rotate') // give a timeout, otherwise won't work
						.doTimeout(1500, 'removeClass', 'anim-do-rotate');
				}

				var shadow = trig.prop('class').match(/anim-shadow/) ? true : false;
				if (shadow === true) {
					trig.doTimeout(100, 'addClass', 'anim-do-shadow') // give a timeout, otherwise won't work
						.doTimeout(1500, 'removeClass', 'anim-do-shadow');
				}

				var pulse = trig.prop('class').match(/anim-pulse/) ? true : false;
				if (pulse === true) {
					trig.doTimeout(100, 'addClass', 'anim-do-heartBeat') // give a timeout, otherwise won't work
						.doTimeout(1500, 'removeClass', 'anim-do-heartBeat');
				}

				var shakeX = trig.prop('class').match(/anim-shake-h/) ? true : false;
				if (shakeX === true) {
						trig.doTimeout(100, 'addClass', 'anim-do-shakeX') // give a timeout, otherwise won't work
							.doTimeout(1500, 'removeClass', 'anim-do-shakeX');
				}

				var shakeY = trig.prop('class').match(/anim-shake-v/) ? true : false;
				if (shakeY === true) {
					trig
					.doTimeout(100, 'addClass', 'anim-do-shakeY') // give a timeout, otherwise won't work
					.doTimeout(1500, 'removeClass', 'anim-do-shakeY');
				}
			}

			var animEcho = function() {
				if (trig.prop('class').match(/anim-over/)) {
					clearInterval(animEcho);
				} else {
					pickAnim();
				}
			};

			if (onload === true) {
				pickAnim();
			}

			if (clicker === true) {
				pickAnim();
			}

			if (echo === true) {
				setInterval(animEcho, echo_time);
			}

		});

		return this;

	};


	/**
	 * Close the panel.
	 */
	$.fn.panelCloser = function() {

		this.on('click', function () {

			var panel	=	$(this).closest('.mboxy-wrap');

			// if it's injectany with overlay, with first click just remove the overlay
			if ( panel.prop('class').match(/ia-overlayed/) ) {
				panel
				.panelOverlayOff()
				.removeClass('ia-overlayed'); // remove the marker which will further allow to be closed as any other panel

			// ...now act as any other closer panel (i.e. no injectany overlay)
			} else {

				var	count_fa_overlay_on	=	$( '.floatany > .mboxy-overlay.on' ).length; // by using .floatany we are not counting injectany if overlay is added there since there scroll blocking isn't in use

				// if less than 1 overlays are open (i.e. do not close the overlay if there's more than one open) && overlay is inside the current box
				if (count_fa_overlay_on <= 1 && panel.find('>.mboxy-overlay.on').length) {
					$('body').removeClass('maxboxy-overlay-on');
				}

				// if the box is marked as .nospace, run its Desstroy function
				if (panel.prop('class').match(/nospace/) ) {
					//$(this).doTimeout(100, doNospaceDestroy);
					panel.panelNospaceDestroy();
				}

				// remove 'on' classes...
				panel
					.removeClass('on') // ...for the panel
					.find('>.mboxy-overlay').removeClass('on'); // ...for the overlay

				var rotator_close_pause =   typeof panel.data('rotator-close-pause')   !== 'undefined'  ?   panel.data('rotator-close-pause')  *1000     : false, // with *1000 convert seconds to miliseconds
					role_rotator        =   panel.prop('class').match(/role-rotator/) ? true : false;

				if (role_rotator === true && rotator_close_pause !== false) {

					panel
						.addClass('rotator-pause')
						.doTimeout(rotator_close_pause, 'removeClass', 'rotator-pause');

				}

				// remove inline-set
				if (panel.prop('class').match(/inline-set/) ) {

					panel.children().css({
									'left'      : '',
									'right'     : '',
									'top'       : '',
								});

					panel.find('.mboxy-content').css('overflow', '' );

					panel.doTimeout(20, 'removeClass', 'inline-set');

				}

				// banish panel
				var panel_ban = $(this).closest('.mboxy-wrap.role-banish').length ? true : false;

				// add the dismissed panel to the localStorage
				if (panel_ban === true) {
					$(this).panelMarkBanished();
				}

			}

		});

		return this;

	};

	$('.type-closer .mboxy-overlay, .mboxy-closer').panelCloser().trigAnim();


	/**
	 * Toggler of panels - covers both, toggler & igniter.
	 */
	$.fn.panelToggler = function() {

		this.on('click', function (e) {

			e.preventDefault();

			var panel =  $(this).closest('.mboxy-wrap');

			// if it's injectany with overlay, with first click just remove the overlay
			if ( panel.prop('class').match(/ia-overlayed/) ) {
				panel
				.panelOverlayOff()
				.removeClass('ia-overlayed'); // remove the marker which will further allow to  be closed as any other panel

			// ...now act as any other closer panel (i.e. no injectany overlay)
			} else {

				// show/hide the panel
				panel.toggleClass('on');

				/*
				 * Overlay for FloatAny (InjectAny is handled on its intersection @see injectanyToObserverCallback() with
				 * maxboxyOn() which utilises panelOverlayOn() by adding .ia-overlayed).
				 * ...Here we go with toggleClass, so do not use panelOverlayOn().
				 */
				if (panel.prop('class').match(/floatany/) && panel.find( '>.mboxy-overlay' ).length ) {
					panel.find( '>.mboxy-overlay' ).toggleClass( 'on' );
					$('body').toggleClass('maxboxy-overlay-on');
				}

				panel.find('.mboxy-toggler.trig-default').toggleClass('igniteon');

				var trig_icon         = panel.find( '.trig-icon' ),
					data_button_open  = typeof trig_icon.data('button-open')  !== 'undefined'  ?  trig_icon.data('button-open')  : '',
					data_button_close = typeof trig_icon.data('button-close') !== 'undefined'  ?  trig_icon.data('button-close') : '';

				// toggle open/close classes (i.e. responsible for swithcing its icons)
				if (data_button_open.length || data_button_close.length) {
					trig_icon.toggleClass( data_button_open + ' ' + data_button_close );
				}

				var toggler =   panel.find( '>.mboxy >.trigger' ),
					open    =   maxboxy_localize.toggler_title_open,
					close   =   maxboxy_localize.toggler_title_close;

				// switch toggler's title attr (open/close)
				if ( toggler.length && toggler.prop('class').match(/igniteon/) ) {
					toggler.attr('title', open);
				} else {
					toggler.attr('title', close);
					panel.panelNospace();

					if ( $('body').prop('class').match(/maxboxy-tracking-on/) ) {
						panel.countViews(); // ...count views of the panel on the open
					}
				}

				// rotaotor
				if (panel.prop('class').match(/role-rotator/)) {

					// if is igniter and rotator not yet initialized
					if (panel.prop('class').match(/role-igniter/) && !panel.prop('class').match(/rotator-started/)) {

						// ...initialize the rotator
						panel.panelRotator();

					// no more rotating items (just close the panel)
					} else if (panel.prop('class').match(/rotator-end/)) {

						panel.removeClass('on');

					// toggle the pause, i.e. pause/continue the rotator on the panel toggling
					} else {

						panel.toggleClass('rotator-pause');

					}

				}

				// Anim on every toggler's click
				if (panel.find( '>.mboxy >.trigger' ).prop('class').match(/anim-click/)) {

					panel.find( '>.mboxy >.trigger' )
						.addClass('anim-doclick')
						.trigAnim();

				}
				if (panel.find( '>.mboxy >.trigger' ).prop('class').match(/anim-echo/)) {
					panel.find( '>.mboxy >.trigger' )
						.addClass('anim-over');
				}

			}

		});

		return this;

	};




	// apply the toggling
	var panel_toggler = $('.mboxy-toggler, .type-toggler .mboxy-overlay');
		panel_toggler.panelToggler().trigAnim();


	/**
	 * Close a panel with hover-out from the panel.
	 */
	$.fn.panelHoverShut = function() {

		this.on({

			mouseleave: function () {

				$(this).removeClass('on');

				// check is it inline opened
				var is_inline  =   $(this).prop('class').match(/inline-set/) ? true : false;

				if ( is_inline === true ) {

					// target a .mboxy with .children()
					$(this).children().css({
						'display'   : '',
						'left'      : '',
						'right'     : '',
						'top'       : '',
					});

					// remove the closer mark set on the trigger element
					var mboxy_id             = $(this).attr('id'),
						elm_marked_closed   = $('.mboxy-mark-close');

					$.each(elm_marked_closed, function () {
						var matchingElements = $(this).children().attr( "href" ) === '#' +mboxy_id ? true : false;

						if ( matchingElements === true ) {
							$(this).removeClass('mboxy-mark-active');
						}
					});

					$(this).doTimeout(20, 'removeClass', 'inline-set');

				}

				// have to init the banished when hoverer closer is set, coz the previous is set on close's click i.e. with panelCloser
				if ( $(this).prop('class').match(/role-banish/) ) {
					$(this).panelMarkBanished();
				}

			}

		});

		return this;

	};

	$( '.type-closer.role-hoverer' ).panelHoverShut();


	/**
	 * Close the additional message on the igniter
	 */
	$('.additional-message-killer').on('click', function (e) {
		e.stopPropagation();
		$(this).parent().hide();
	});

	/**
	 * Mark a panel with 'nospace' class when there's no room in a viewport.
	 */
	 $.fn.panelNospace = function() {

		this.each( function () {

			var is_on      =   $(this).prop('class').match(/on/)        ?	true : false,
				is_inline  =   $(this).prop('class').match(/injectany/)	?	true : false;

			// if it isn't injectany
			if (is_on === true && is_inline === false) {

				var panel_height    =   $(this).find('>.mboxy').outerHeight(),
					window_height   =   $(window).outerHeight();

				if (panel_height > window_height) {

					//$(this).find( '> .mboxy > .mboxy-content' )
					$(this).find( '> .mboxy' )
						.css({
							//'height' : window_height -50, // with -50 make sure the close button is in the view
							'height' : window_height,
						});

					$(this).addClass('nospace');

				}

			}

		});

		return this;

	};

	function doNospace() {
		$('.mboxy-wrap').panelNospace();
	}

	$(this).doTimeout(1350, doNospace); // wait for elements to render


	/**
	 * For removing 'nospace' class.
	 */
	$.fn.panelNospaceDestroy = function() {

		this.each( function () {

			$(this).find( '> .mboxy' ).css('height', '');
			$(this).removeClass('nospace');

		});

		return this;

	};


	/**
	 * Removing 'nospace' class.
	 *
	 * It's for resize function.
	 */
	function doNospaceDestroy() {
		var  nospace = $('.nospace');
		if ( nospace.length ) {
			 nospace.panelNospaceDestroy();
		}
	}


	/**
	 *  Panel's dimensions
	 */
	$.fn.panelSize = function() {

		this.each( function () {
			var with_size				=	$(this).find('.with-size'),
				width_1st 				=   typeof with_size.data('panel-width')  !== 'undefined' ?	with_size.data('panel-width')  : '',
				height_1st				=   typeof with_size.data('panel-height') !== 'undefined' ?	with_size.data('panel-height') : '',
				//on							=   $(this).prop('class').match(/on/)		?	true	:	false,
				nospace					=   $(this).prop('class').match(/nospace/)	?	true	:	false,
				current_screen_width	=   $(window).width();

			if (with_size.length && nospace === false) {

				with_size.css({
					'width':  width_1st,
					'height': height_1st,
					'display': 'flex', // if not set, display: block will be printed
				});

				// ...overwrite for the large screens
				if (current_screen_width >= screenBreakPoint()) {

					var width_large     =   with_size.data('panel-large-width'),
						height_large    =   with_size.data('panel-large-height'),
						width_new   	=   typeof width_large  !== 'undefined'   ?   width_large     :   width_1st,
						height_new  	=   typeof height_large !== 'undefined'   ?   height_large    :   height_1st;

						with_size.css({
							'width':  width_new,
							'height': height_new,
						});

				}

			}

		});

		return this;

	};

	/**
	 * Panel sizes - for resize function.
	 */
	function doPanelSize() {
		$('.mboxy-wrap').panelSize();
	}


	/**
	 * Large screen breaking point.
	 */
	function screenBreakPoint() {
		var		large_screen_break_point = typeof new_large_screen_break_point !== 'undefined' && new_large_screen_break_point !== 992 ? new_large_screen_break_point  : 992;
		return	large_screen_break_point;
	}



	/**
	 * Disable based on ScreenSize.
	 */
	$.fn.disableForScreens = function() {

		function disOnSize() {

			if ($(window).width() < screenBreakPoint()) {

				$('.dis-screen-small').addClass('is-screen-disabled');
				$('.dis-screen-large').removeClass('is-screen-disabled');

			} else {

				$('.dis-screen-small').removeClass('is-screen-disabled');
				$('.dis-screen-large').addClass('is-screen-disabled');

			}

		}

		return disOnSize();

	};

	/**
	 * Disable for screens - for resize function.
	 */
	function doDisableForScreens() {
		$(window).disableForScreens();
	}


	/**
	 * Media alignfull.
	 */
	function media_aligner() {

		var media_align_full = $('.mboxy .alignfull');

		if (media_align_full.length) {

			$.each(media_align_full, function () {

				var mboxy_content    = $(this).closest('.mboxy-content'),
					paddL           = mboxy_content.css('padding-left'),
					paddR           = mboxy_content.css('padding-right');

				$(this).css({
					'margin-left': '-' +paddL,
					'margin-right': '-' +paddR,
					'width': 'auto',
					// max-width set with css
				});

			});

		}

		var media_align_wide = $('.mboxy .alignwide');
		if (media_align_wide.length) {

			$.each(media_align_wide, function () {

				var mboxy_content    = $(this).closest('.mboxy-content'),
					// 10 appearing after the comma (,) is the radix - the best practis is to specify it, though ECMAScript 5 assumes it's 10
					// for the alignwide we're splitting the size /2
					paddL = parseInt( mboxy_content.css('padding-left'), 10 ) /2,
					paddR = parseInt( mboxy_content.css('padding-right'), 10 ) /2;
				$(this).css({
					'margin-left': '-' +paddL +'px',
					'margin-right': '-' +paddR +'px',
					'width': 'auto',
					// max-width set with css
				});

			});

		}

	}
	$(this).doTimeout(400, media_aligner);



	/**
	 * On resize.
	 */
	$(window).on('resize', function () {

		doNospaceDestroy();

		doDisableForScreens();

		doPanelSize();

		$(this).doTimeout(400, media_aligner);

		$(this).doTimeout(500, doNospace);

	});




}); // end jQuerys
