window.onload = function () {
	// only apply to IE
	if (!/*@cc_on!@*/0) return;

	// find every element to test
	var all = document.getElementsByTagName('*'), i = all.length;

	// fast reverse loop
	while (i--) {
		// if the scrollWidth (the real width) is greater than
		// the visible width, then apply style changes
		if (all[i].scrollWidth > all[i].offsetWidth) {
			all[i].style['paddingBottom'] = '20px';
			all[i].style['overflowY'] = 'hidden';
		}
	}
};

(function (window, document) {

	/**
	 * Element.closest() polyfill
	 * https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
	 */
	if(!Element.prototype.closest) {
		console.log('Prototype Closest');

		if(!Element.prototype.matches) {
			Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
		}
		Element.prototype.closest = function (s) {
			var el = this;
			var ancestor = this;
			if(!document.documentElement.contains(el)) return null;
			do {
				if(ancestor.matches(s)) return ancestor;
				ancestor = ancestor.parentElement;
			} while (ancestor !== null);
			return null;
		};
	}


	var nav_trigger = document.querySelector('.nav-trigger'),
		header = document.querySelector('#header'),
		title = document.querySelector('.heading-content'),
		nav = document.querySelector('.nav'),
		modals = document.querySelectorAll('.modal');

	var header_height = header.offsetHeight

	if(title) title.setAttribute('style', 'margin-top:' + header_height + 'px;')

	window.addEventListener('resize', function (e) {
		header_height = header.offsetHeight
	});

	document.addEventListener('keydown', function (e) {
		if(e.keyCode == 27) {
			closeModal(modals, 'is-active')
		}
	});

	document.addEventListener('click', function (e) {

		if(e.target.closest('.nav-trigger-box, .nav-trigger')) {
			showHideNav(nav, nav_trigger)
		}

		if(e.target.matches('[data-modal="sign-in"]')) {
			openModal(modals, e, 'is-active')
		}

		if(e.target.matches('.modal-close, [data-modal="close"]')) {
			closeModal(modals, 'is-active')
		}

	});

	//
	// FUNCTIONS
	//

	/**
	 * Adds a class (str) class to modals with a dataset matching the event target dataset
	 * @param {NodeList} elems Node List of all modals in the DOM
	 * @param {Object} event   User event to listen for
	 * @param {String} str     Name of class to add
	 */

	var openModal = function (elems, event, str) {
		if(typeof elems !== 'object') return;

		Array.from(elems).forEach(function (el) {
			if(event.target.dataset.modal == el.dataset.modal) {
				el.classList.add(str)
			}
		})
	}

	/**
	 * Removes the active class (str) from active modals
	 * @param {NodeList} elems Node List of all modals in the DOM
	 * @param {String} str     Name of class to add
	 */

	var closeModal = function (elems, str) {
		if(typeof elems !== 'object') return;

		Array.from(elems).forEach(function (el) {
			if(el.classList.contains(str)) {
				el.classList.remove(str)
			}
		})
	}

	/**
	 * Show and hide navigation menu
	 * @param {Node} elem   The navigation menu to show and hide
	 * @param {Node} target The nav icon (usually the so called hamburger icon)
	 */

	var showHideNav = function (elem, target) {
		var height = elem.firstElementChild.clientHeight;

		target.classList.toggle('is-active')
		elem.classList.toggle('is-shown')

		if(elem.classList.contains('is-shown')) {
			elem.setAttribute('style', 'max-height:' + height + 'px; top:' + header_height + 'px;')
		} else {
			elem.setAttribute('style', 'max-height: 0; top:' + header_height + 'px;')
		}

	}

})(window, document);
