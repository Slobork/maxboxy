/*
 * jQuery doTimeout: Like setTimeout, but better! - v1.0 - 3/3/2010
 * http://benalman.com/projects/jquery-dotimeout-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */


(function ($) {
	"use strict";

	var a = {},
		c = "doTimeout",
		d = Array.prototype.slice;
	$[c] = function () {
		return B.apply(window, [0].concat(d.call(arguments)));
	};
	$.fn[c] = function () {
		var f = d.call(arguments),
			e = B.apply(this, [c + f[0]].concat(f));
		return typeof f[0] === "number" || typeof f[1] === "number" ? this : e;
	};

	// I've changed the function name to start with the upper-case to bypass the JSHint warning regarding the 'this' undefined - https://stackoverflow.com/a/55246026
	function B(l) {
		var m = this,
			h, k = {},
			g = l ? $.fn : $,
			n = arguments,
			i = 4,
			f = n[1],
			j = n[2],
			p = n[3];
		if (typeof f !== "string") {
			i--;
			f = l = 0;
			j = n[1];
			p = n[2];
		}
		if (l) {
			h = m.eq(0);
			h.data(l, k = h.data(l) || {});
		} else {
			if (f) {
				k = a[f] || (a[f] = {});
			}
		}
		if (k.id && clearTimeout(k.id)) {
			delete k.id;
		}

		function e() {
			if (l) {
				h.removeData(l);
			} else {
				if (f) {
					delete a[f];
				}
			}
		}

		function o() {
			k.id = setTimeout(function () {
				k.fn();
			}, j);
		}
		if (p) {
			k.fn = function (q) {
				if (typeof p === "string") {
					p = g[p];
				}

				if (p.apply(m, d.call(n, i)) === true && !q) {
					o();
				} else {
					e();
				}

			};
			o();
		} else {
			if (k.fn) {

				if (j === undefined) {
					e();
				} else {
					 k.fn(j === false);
				}

				return true;

			} else {
				e();
			}
		}
	}

})(jQuery);
