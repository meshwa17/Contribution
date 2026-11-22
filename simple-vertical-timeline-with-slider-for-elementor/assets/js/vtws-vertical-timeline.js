/**
 * Simple Vertical Timeline with Slider for Elementor
 *
 * Vanilla JS, no dependencies. Handles:
 * - Vertical timeline with a slide panel, and a vertical accordion on mobile
 * - Breakpoint-based layout classes (set in the widget settings)
 * - Prev / next buttons, keyboard arrows, optional autoplay
 * - Re-initialization inside the Elementor editor
 */
(function () {
	'use strict';

	var DEFAULTS = {
		start: 0,
		autoplay: false,
		speed: 5000,
		pauseHover: true,
		keyboard: true,
		animate: true,
		tabletBp: 860,
		mobileBp: 640,
		collapsible: true,
		mobileScroll: true
	};

	/**
	 * Create one timeline instance.
	 *
	 * @param {HTMLElement} root Widget root element (.vtws).
	 */
	function VtwsTimeline(root) {
		var cfg;
		try {
			cfg = Object.assign({}, DEFAULTS, JSON.parse(root.getAttribute('data-vtws') || '{}'));
		} catch (e) {
			cfg = Object.assign({}, DEFAULTS);
		}

		var track = root.querySelector('.vtws__track');
		var panelsWrap = root.querySelector('.vtws__panels');
		var items = Array.prototype.slice.call(root.querySelectorAll('.vtws__item'));
		var nodes = items.map(function (it) { return it.querySelector('.vtws__node'); });
		var panels = Array.prototype.slice.call(root.querySelectorAll('.vtws__panel'));
		var total = items.length;

		if (!track || !panelsWrap || !total) {
			return null;
		}

		var cur = Math.min(Math.max(0, parseInt(cfg.start, 10) || 0), total - 1);
		var open = true;          // Mobile: is the active item expanded?
		var mode = 'desktop';     // desktop | tablet | mobile
		var timer = null;
		var paused = false;
		var stopped = false;      // Autoplay stopped after user interaction.
		var inView = true;
		var rafId = null;
		var observer = null;
		var destroyed = false;

		var inEditor = !!(window.elementorFrontend && typeof window.elementorFrontend.isEditMode === 'function' && window.elementorFrontend.isEditMode());

		/* ---------------- helpers ---------------- */

		function clamp(i) {
			return Math.min(Math.max(0, i), total - 1);
		}

		function detectMode() {
			var w = window.innerWidth;
			if (w <= cfg.mobileBp) { return 'mobile'; }
			if (w <= cfg.tabletBp) { return 'tablet'; }
			return 'desktop';
		}

		function applyMode(next) {
			mode = next;
			root.classList.toggle('vtws--tablet', mode === 'tablet' || mode === 'mobile');
			root.classList.toggle('vtws--mobile', mode === 'mobile');
		}

		/** Show only the active panel and, on mobile, move it under its node. */
		function place() {
			var mobile = mode === 'mobile';

			panels.forEach(function (panel, k) {
				var active = k === cur;

				if (mobile && active) {
					if (panel.parentNode !== items[k]) { items[k].appendChild(panel); }
				} else if (panel.parentNode !== panelsWrap) {
					panelsWrap.appendChild(panel);
				}

				panel.hidden = !(active && (!mobile || open));
			});
		}

		function restartAnimation(panel) {
			if (!cfg.animate) { return; }
			panel.classList.remove('is-anim');
			void panel.offsetWidth; // Force reflow so the animation restarts.
			panel.classList.add('is-anim');
		}

		/* ---------------- core ---------------- */

		function go(i, opts) {
			opts = opts || {};
			cur = clamp(i);
			var mobile = mode === 'mobile';

			nodes.forEach(function (node, k) {
				node.classList.toggle('is-done', k < cur);
				node.classList.toggle('is-on', k === cur);
				items[k].classList.toggle('is-done', k < cur);
				node.setAttribute('aria-expanded', (k === cur && (!mobile || open)) ? 'true' : 'false');
				if (k === cur) {
					node.setAttribute('aria-current', 'step');
				} else {
					node.removeAttribute('aria-current');
				}
			});

			place();

			var active = panels[cur];
			if (active && !active.hidden) { restartAnimation(active); }

			// Keep keyboard focus on the same kind of control after the DOM move.
			if (opts.focus && active) {
				var target = active.querySelector(opts.focus);
				if (target && target.disabled) {
					target = active.querySelector(opts.focus === '.vtws__next' ? '.vtws__prev' : '.vtws__next');
				}
				if (target && !target.disabled) { target.focus({ preventScroll: true }); }
			}

			if (mobile && open && cfg.mobileScroll && !opts.noScroll) {
				setTimeout(function () {
					if (destroyed) { return; }
					nodes[cur].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
				}, 60);
			}
		}

		/* ---------------- autoplay ---------------- */

		function stopTimer() {
			if (timer) { clearInterval(timer); timer = null; }
		}

		function startTimer() {
			stopTimer();
			if (!cfg.autoplay || inEditor || stopped || total < 2) { return; }
			timer = setInterval(function () {
				if (destroyed || !root.isConnected) { stopTimer(); return; }
				if (paused || !inView || document.hidden) { return; }
				open = true;
				go(cur >= total - 1 ? 0 : cur + 1, { noScroll: true });
			}, cfg.speed);
		}

		function userInteracted() {
			if (cfg.autoplay) {
				stopped = true;
				stopTimer();
			}
		}

		/* ---------------- events ---------------- */

		function onClick(e) {
			var node = e.target.closest('.vtws__node');
			if (node && root.contains(node)) {
				var idx = nodes.indexOf(node);
				if (idx > -1) {
					userInteracted();
					open = (mode === 'mobile' && idx === cur && cfg.collapsible) ? !open : true;
					go(idx);
				}
				return;
			}

			var prev = e.target.closest('.vtws__prev');
			if (prev && root.contains(prev)) {
				userInteracted();
				open = true;
				go(cur - 1, { focus: '.vtws__prev' });
				return;
			}

			var next = e.target.closest('.vtws__next');
			if (next && root.contains(next)) {
				userInteracted();
				open = true;
				go(cur + 1, { focus: '.vtws__next' });
			}
		}

		function onKey(e) {
			if (!cfg.keyboard) { return; }
			if (!e.target.closest || !e.target.closest('.vtws__node, .vtws__btn')) { return; }

			var forward = e.key === 'ArrowDown' || e.key === 'ArrowRight';
			var backward = e.key === 'ArrowUp' || e.key === 'ArrowLeft';
			if (!forward && !backward) { return; }

			e.preventDefault();
			userInteracted();
			open = true;
			go(forward ? cur + 1 : cur - 1, { noScroll: true });
			nodes[cur].focus({ preventScroll: true });
		}

		function onResize() {
			if (destroyed) { return; }
			if (!root.isConnected) { destroy(); return; }
			if (rafId) { cancelAnimationFrame(rafId); }
			rafId = requestAnimationFrame(function () {
				var next = detectMode();
				if (next !== mode) {
					applyMode(next);
					// Nodes carry aria-expanded state that depends on the mode.
					go(cur, { noScroll: true });
				}
			});
		}

		function onEnter() { paused = true; }
		function onLeave() { paused = false; }

		function destroy() {
			destroyed = true;
			stopTimer();
			window.removeEventListener('resize', onResize);
			window.removeEventListener('load', onResize);
			if (observer) { observer.disconnect(); observer = null; }
			root.__vtws = null;
		}

		/* ---------------- init ---------------- */

		root.addEventListener('click', onClick);
		root.addEventListener('keydown', onKey);
		window.addEventListener('resize', onResize);
		window.addEventListener('load', onResize);

		if (cfg.autoplay && cfg.pauseHover) {
			root.addEventListener('mouseenter', onEnter);
			root.addEventListener('mouseleave', onLeave);
			root.addEventListener('focusin', onEnter);
			root.addEventListener('focusout', onLeave);
		}

		if (cfg.autoplay && 'IntersectionObserver' in window) {
			observer = new IntersectionObserver(function (entries) {
				inView = entries[0].isIntersecting;
			}, { threshold: 0.25 });
			observer.observe(root);
		}

		applyMode(detectMode());
		go(cur, { noScroll: true });
		startTimer();

		return { destroy: destroy, go: go };
	}

	/**
	 * Initialize every timeline found inside a scope.
	 *
	 * @param {ParentNode} scope Element or document to search.
	 */
	function initAll(scope) {
		var list = (scope || document).querySelectorAll('.vtws[data-vtws]');
		Array.prototype.forEach.call(list, function (el) {
			if (el.__vtws) { return; }
			el.__vtws = VtwsTimeline(el) || null;
		});
	}

	// Normal front end.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initAll(document); });
	} else {
		initAll(document);
	}

	// Elementor front end / editor (re-runs when the widget is re-rendered).
	var hooked = false;

	function hookElementor() {
		if (hooked || !window.elementorFrontend || !window.elementorFrontend.hooks) { return; }
		hooked = true;
		window.elementorFrontend.hooks.addAction('frontend/element_ready/vtws_vertical_timeline.default', function ($scope) {
			initAll($scope && $scope[0] ? $scope[0] : document);
		});
	}

	hookElementor();
	window.addEventListener('elementor/frontend/init', hookElementor);
	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', hookElementor);
	}
})();
