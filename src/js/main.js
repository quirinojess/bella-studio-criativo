/**
 * Scripts do tema.
 */
const setupSiteHeader = () => {
	const header = document.querySelector('.eg-site-header');
	const toggle = document.querySelector('.site-header__menu-toggle');
	const panel = document.getElementById('site-header-panel');
	const shrinkDistance = 120;

	const bar = header?.querySelector('.eg-site-header__bar');
	const topRow = bar?.querySelector('.eg-site-header__top');

	const syncHeaderBarHeight = () => {
		if (!header || !bar) return;
		header.style.setProperty('--header-bar-height', `${bar.offsetHeight}px`);
	};

	const syncHeaderPanelTop = () => {
		if (!header || !bar) return;
		const anchor = topRow || bar;
		const gap = 10;
		const top = Math.ceil(anchor.getBoundingClientRect().bottom + gap);
		header.style.setProperty('--header-panel-top', `${top}px`);
	};

	const updateHeaderScroll = () => {
		if (!header) return;
		const progress = Math.min(1, Math.max(0, window.scrollY / shrinkDistance));
		header.style.setProperty('--header-shrink', progress.toFixed(3));
		header.classList.toggle('is-scrolled', progress > 0.08);
		syncHeaderBarHeight();
		if (header.classList.contains('is-menu-open')) {
			syncHeaderPanelTop();
		}
	};

	let scrollTicking = false;
	window.addEventListener(
		'scroll',
		() => {
			if (scrollTicking) return;
			scrollTicking = true;
			window.requestAnimationFrame(() => {
				updateHeaderScroll();
				scrollTicking = false;
			});
		},
		{ passive: true }
	);
	updateHeaderScroll();
	syncHeaderBarHeight();

	if (bar && typeof ResizeObserver !== 'undefined') {
		const barResizeObserver = new ResizeObserver(() => {
			syncHeaderBarHeight();
		});
		barResizeObserver.observe(bar);
	}

	bar?.addEventListener(
		'transitionend',
		(event) => {
			if (event.target === bar || event.propertyName === 'padding-top' || event.propertyName === 'padding-bottom') {
				syncHeaderBarHeight();
			}
		}
	);

	document.querySelector('.site-header__logo-image .site-logo__svg')?.addEventListener(
		'transitionend',
		(event) => {
			if (event.propertyName === 'max-height' || event.propertyName === 'max-width') {
				syncHeaderBarHeight();
			}
		}
	);

	if (toggle && panel) {
		const setPanelOpen = (open) => {
			if (open) {
				panel.removeAttribute('hidden');
				syncHeaderPanelTop();
				window.requestAnimationFrame(syncHeaderPanelTop);
			} else {
				panel.setAttribute('hidden', '');
			}
			header?.classList.toggle('is-menu-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
		};

		toggle.addEventListener('click', () => {
			const open = panel.hasAttribute('hidden');
			setPanelOpen(open);
		});

		panel.querySelectorAll('a[href]').forEach((link) => {
			link.addEventListener('click', () => {
				if (window.matchMedia('(max-width: 781px)').matches) {
					setPanelOpen(false);
				}
			});
		});

		syncHeaderPanelTop();
	}

	window.addEventListener('resize', () => {
		syncHeaderBarHeight();
		if (header?.classList.contains('is-menu-open')) {
			syncHeaderPanelTop();
		}
	}, { passive: true });

	document.querySelectorAll('.site-categories__mobile-toggle').forEach((btn) => {
		btn.addEventListener('click', () => {
			const nav = btn.closest('.site-categories');
			if (!nav) {
				return;
			}
			const open = !nav.classList.contains('is-open');
			nav.classList.toggle('is-open', open);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
};

/**
 * Carrossel YouTube: deslocamento por translate3d (sem scroll horizontal visível).
 */
const setupYoutubeCarousel = () => {
	const root = document.querySelector('[data-youtube-carousel]');
	if (!root || root.classList.contains('youtube-carousel--duo')) {
		return;
	}

	const viewport = root.querySelector('.youtube-carousel__viewport');
	const track = root.querySelector('[data-youtube-track]');
	const prevButton = root.querySelector('.carousel-arrow--prev');
	const nextButton = root.querySelector('.carousel-arrow--next');

	if (!viewport || !track || !prevButton || !nextButton) {
		return;
	}

	let index = 0;

	const getGapPx = () => {
		const g = window.getComputedStyle(track).gap;
		return parseFloat(g) || 12;
	};

	const getVisibleCount = (total) => {
		const n = window.matchMedia('(max-width: 781px)').matches ? 1 : 6;
		return Math.min(Math.max(1, n), total);
	};

	const applyLayout = () => {
		const cards = track.querySelectorAll('.youtube-card');
		const total = cards.length;
		if (!total) {
			track.style.transform = 'translate3d(0,0,0)';
			prevButton.disabled = true;
			nextButton.disabled = true;
			return;
		}

		const vw = viewport.getBoundingClientRect().width;
		const gap = getGapPx();
		const visible = getVisibleCount(total);
		const cardW = Math.max(85, (vw - gap * (visible - 1)) / visible);

		viewport.style.setProperty('--youtube-card-width', `${Math.round(cardW * 100) / 100}px`);

		const maxIndex = Math.max(0, total - visible);
		index = Math.min(index, maxIndex);

		const offset = index * (cardW + gap);
		track.style.transform = `translate3d(-${offset}px, 0, 0)`;

		prevButton.disabled = index <= 0;
		nextButton.disabled = index >= maxIndex;
	};

	prevButton.addEventListener('click', () => {
		if (index > 0) {
			index -= 1;
			applyLayout();
		}
	});

	nextButton.addEventListener('click', () => {
		const cards = track.querySelectorAll('.youtube-card');
		const total = cards.length;
		const visible = getVisibleCount(total);
		const maxIndex = Math.max(0, total - visible);
		if (index < maxIndex) {
			index += 1;
			applyLayout();
		}
	});

	window.addEventListener('resize', () => {
		applyLayout();
	});

	window.addEventListener('orientationchange', () => {
		setTimeout(applyLayout, 200);
	});

	applyLayout();
};

/**
 * Carrossel Inventário do Mundo: posts menores, até 12 itens.
 */
const setupInventarioCarousel = () => {
	const root = document.querySelector('[data-inventario-carousel]');
	if (!root) {
		return;
	}

	const viewport = root.querySelector('.inventario-carousel__viewport');
	const track = root.querySelector('[data-inventario-track]');
	const prevButton = root.querySelector('.inventario-carousel__arrow.carousel-arrow--prev');
	const nextButton = root.querySelector('.inventario-carousel__arrow.carousel-arrow--next');

	if (!viewport || !track || !prevButton || !nextButton) {
		return;
	}

	let index = 0;

	const getGapPx = () => {
		const g = window.getComputedStyle(track).gap;
		return parseFloat(g) || 12;
	};

	const getVisibleCount = (total) => {
		const n = window.matchMedia('(max-width: 781px)').matches ? 2.25 : 5.35;
		return Math.min(Math.max(1, n), total);
	};

	const applyLayout = () => {
		const cards = track.querySelectorAll('.mini-card');
		const total = cards.length;
		if (!total) {
			track.style.transform = 'translate3d(0,0,0)';
			prevButton.disabled = true;
			nextButton.disabled = true;
			return;
		}

		const vw = viewport.getBoundingClientRect().width;
		const gap = getGapPx();
		const visible = getVisibleCount(total);
		const cardW = Math.max(85, (vw - gap * (visible - 1)) / visible);

		root.style.setProperty('--inventario-card-width', `${Math.round(cardW * 100) / 100}px`);

		const maxIndex = Math.max(0, Math.ceil(total - visible));
		index = Math.min(index, maxIndex);

		const offset = index * (cardW + gap);
		track.style.transform = `translate3d(-${offset}px, 0, 0)`;

		prevButton.disabled = index <= 0;
		nextButton.disabled = index >= maxIndex;
	};

	prevButton.addEventListener('click', () => {
		if (index > 0) {
			index -= 1;
			applyLayout();
		}
	});

	nextButton.addEventListener('click', () => {
		const cards = track.querySelectorAll('.mini-card');
		const total = cards.length;
		const visible = getVisibleCount(total);
		const maxIndex = Math.max(0, Math.ceil(total - visible));
		if (index < maxIndex) {
			index += 1;
			applyLayout();
		}
	});

	window.addEventListener('resize', () => {
		applyLayout();
	});

	window.addEventListener('orientationchange', () => {
		setTimeout(applyLayout, 200);
	});

	applyLayout();
};

/**
 * Carrossel Diário visual: mesmo padrão do YouTube, cartões mais largos (retrato).
 */
const setupVisualDiaryCarousel = () => {
	const root = document.querySelector('[data-visual-diary-carousel]');
	if (!root) {
		return;
	}

	const viewport = root.querySelector('.visual-diary-carousel__viewport');
	const track = root.querySelector('[data-visual-diary-track]');
	const prevButton = root.querySelector('.visual-diary-carousel__arrow.carousel-arrow--prev');
	const nextButton = root.querySelector('.visual-diary-carousel__arrow.carousel-arrow--next');

	if (!viewport || !track || !prevButton || !nextButton) {
		return;
	}

	let index = 0;

	const getGapPx = () => {
		const g = window.getComputedStyle(track).gap;
		return parseFloat(g) || 12;
	};

	const getVisibleCount = (total) => {
		const n = window.matchMedia('(max-width: 781px)').matches ? 1 : 3;
		return Math.min(Math.max(1, n), total);
	};

	const applyLayout = () => {
		const cards = track.querySelectorAll('.visual-diary-card');
		const total = cards.length;
		if (!total) {
			track.style.transform = 'translate3d(0,0,0)';
			prevButton.disabled = true;
			nextButton.disabled = true;
			return;
		}

		const vw = viewport.getBoundingClientRect().width;
		const gap = getGapPx();
		const visible = getVisibleCount(total);
		const cardW = Math.max(60, ((vw - gap * (visible - 1)) / visible) * 0.5);

		viewport.style.setProperty('--visual-diary-card-width', `${Math.round(cardW * 100) / 100}px`);

		const maxIndex = Math.max(0, total - visible);
		index = Math.min(index, maxIndex);

		const offset = index * (cardW + gap);
		track.style.transform = `translate3d(-${offset}px, 0, 0)`;

		prevButton.disabled = index <= 0;
		nextButton.disabled = index >= maxIndex;
	};

	prevButton.addEventListener('click', () => {
		if (index > 0) {
			index -= 1;
			applyLayout();
		}
	});

	nextButton.addEventListener('click', () => {
		const cards = track.querySelectorAll('.visual-diary-card');
		const total = cards.length;
		const visible = getVisibleCount(total);
		const maxIndex = Math.max(0, total - visible);
		if (index < maxIndex) {
			index += 1;
			applyLayout();
		}
	});

	window.addEventListener('resize', () => {
		applyLayout();
	});

	window.addEventListener('orientationchange', () => {
		setTimeout(applyLayout, 200);
	});

	applyLayout();
};

const CONTACT_FORM_SLOT_CLASSES = [
	'contact-form__slot',
	'contact-form__slot--left-1',
	'contact-form__slot--left-2',
	'contact-form__slot--left-3',
	'contact-form__slot--message',
	'contact-form__slot--message-with-submit',
	'contact-form__slot--submit',
];

const getForminatorLabelText = (label) => {
	if (!label) {
		return { text: '', required: false };
	}

	const clone = label.cloneNode(true);
	clone.querySelectorAll('.forminator-required, .forminator-sr-only').forEach((node) => node.remove());
	const required = Boolean(label.querySelector('.forminator-required')) || /\*/.test(label.textContent || '');
	const text = (clone.textContent || '').replace(/\s+/g, ' ').replace(/\*+/g, '').trim();

	return { text, required };
};

const applyContactFormPlaceholders = (form) => {
	const fieldDefaults = {
		'forminator-field-name': 'Seu nome',
		'forminator-field-email': 'E-mail',
		'forminator-field-text': 'Assunto',
		'forminator-field-textarea': 'Mensagem',
		'forminator-field-phone': 'Telefone',
		'forminator-field-select': 'Selecione',
	};

	form.querySelectorAll('.forminator-field').forEach((field) => {
		const label = field.querySelector('.forminator-label');
		let { text, required } = getForminatorLabelText(label);

		if (!text) {
			const fallbackKey = Object.keys(fieldDefaults).find((key) => field.classList.contains(key));
			if (fallbackKey) {
				text = fieldDefaults[fallbackKey];
				required = field.classList.contains('forminator-is_required');
			}
		}

		if (!text) {
			return;
		}

		if (!required) {
			required = Boolean(field.querySelector('[required], [aria-required="true"]'));
		}

		const placeholder = `${text.toUpperCase()}${required ? '*' : ''}`;
		const input = field.querySelector('input:not([type="hidden"]):not([type="submit"]):not([type="button"])');
		const textarea = field.querySelector('textarea');
		const select = field.querySelector('select');

		if (input) {
			input.setAttribute('placeholder', placeholder);
		}

		if (textarea) {
			textarea.setAttribute('placeholder', placeholder);
		}

		if (select) {
			select.setAttribute('data-placeholder', placeholder);
			const emptyOption = select.querySelector('option[value=""]');
			if (emptyOption) {
				emptyOption.textContent = placeholder;
			}
		}
	});
};

const hideContactCharCounter = (form) => {
	form.querySelectorAll(
		'.forminator-field-textarea .forminator-description, .forminator-field-textarea .forminator-limit-text, .forminator-field-textarea .forminator-char-count, .forminator-field-textarea .forminator-field--counter'
	).forEach((node) => {
		node.setAttribute('hidden', '');
		node.style.display = 'none';
	});
};

const setupContactForm = () => {
	const root = document.querySelector('.contact-section__form');
	if (!root) {
		return;
	}

	const form = root.querySelector('.forminator-custom-form');
	if (!form) {
		return;
	}

	const cols = [...form.querySelectorAll('.forminator-col')];
	if (!cols.length) {
		return;
	}

	cols.forEach((col) => {
		CONTACT_FORM_SLOT_CLASSES.forEach((className) => col.classList.remove(className));
	});

	const textareaCol = cols.find((col) => col.querySelector('textarea'));
	const submitCol = cols.find(
		(col) => col.querySelector('.forminator-button-submit') && !col.querySelector('textarea')
	);
	const submitInMessageCol = Boolean(textareaCol?.querySelector('.forminator-button-submit'));

	const leftCols = cols.filter((col) => {
		if (col === textareaCol || col === submitCol) {
			return false;
		}
		if (col.querySelector('.forminator-button-submit')) {
			return false;
		}
		return col.querySelector('input, select, textarea');
	});

	form.classList.add('contact-form--grid');

	leftCols.slice(0, 3).forEach((col, index) => {
		col.classList.add('contact-form__slot', `contact-form__slot--left-${index + 1}`);
	});

	if (textareaCol) {
		textareaCol.classList.add('contact-form__slot', 'contact-form__slot--message');
		if (submitInMessageCol) {
			textareaCol.classList.add('contact-form__slot--message-with-submit');
		}
	}

	if (submitCol) {
		submitCol.classList.add('contact-form__slot', 'contact-form__slot--submit');
	}

	applyContactFormPlaceholders(form);
	hideContactCharCounter(form);
	form.dataset.contactLayoutReady = 'true';
};

const watchContactForm = () => {
	const root = document.querySelector('.contact-section__form');
	if (!root) {
		return;
	}

	const run = () => {
		const form = root.querySelector('.forminator-custom-form');
		if (!form) {
			return;
		}
		if (form.dataset.contactLayoutReady === 'true') {
			return;
		}
		setupContactForm();
	};

	run();
	window.setTimeout(run, 120);
	window.setTimeout(run, 500);

	const observer = new MutationObserver(() => {
		const form = root.querySelector('.forminator-custom-form');
		if (!form || form.dataset.contactLayoutReady === 'true') {
			return;
		}
		setupContactForm();
	});

	observer.observe(root, { childList: true, subtree: true });
};

/**
 * Nossas Marcas: abas à esquerda (fechadas) acumulam à direita ao abrir.
 */
const setupMarcasAccordion = () => {
	document.querySelectorAll('[data-marcas-accordion]').forEach((accordion) => {
		if (accordion.dataset.marcasReady === 'true') {
			return;
		}

		const leftRail = accordion.querySelector('[data-marcas-tabs-left]');
		const rightRail = accordion.querySelector('[data-marcas-tabs-right]');
		const tabs = [...accordion.querySelectorAll('[data-marcas-tab]')];
		const panels = [...accordion.querySelectorAll('[data-marcas-panel]')];
		const stage = accordion.querySelector('.marcas-haccordion__stage');
		const isMobile = () => window.matchMedia('(max-width: 781px)').matches;

		if (!leftRail || !rightRail || !tabs.length) {
			return;
		}

		const getTabIndex = (tab) => Number.parseInt(tab.dataset.marcasIndex || '0', 10);

		const animateTabTravel = (tab, moveFn) => {
			if (isMobile()) {
				moveFn();
				return;
			}

			const first = tab.getBoundingClientRect();
			moveFn();

			requestAnimationFrame(() => {
				const last = tab.getBoundingClientRect();
				const deltaX = first.left - last.left;
				const deltaY = first.top - last.top;

				if (Math.abs(deltaX) < 2 && Math.abs(deltaY) < 2) {
					return;
				}

				tab.classList.add('is-traveling');
				tab.style.transition = 'none';
				tab.style.transform = `translate(${deltaX}px, ${deltaY}px)`;

				requestAnimationFrame(() => {
					tab.style.transition = 'transform 0.58s cubic-bezier(0.22, 1, 0.36, 1)';
					tab.style.transform = 'translate(0, 0)';

					window.setTimeout(() => {
						tab.classList.remove('is-traveling');
						tab.style.transform = '';
						tab.style.transition = '';
					}, 620);
				});
			});
		};

		const ensureCaret = (tab) => {
			if (tab.querySelector('.marcas-haccordion__tab-caret')) {
				return;
			}

			const caret = document.createElement('span');
			caret.className = 'marcas-haccordion__tab-caret';
			caret.setAttribute('aria-hidden', 'true');
			tab.appendChild(caret);
		};

		const removeCaret = (tab) => {
			tab.querySelector('.marcas-haccordion__tab-caret')?.remove();
		};

		const insertTabInLeftRail = (tab) => {
			const index = getTabIndex(tab);
			const leftTabs = [...leftRail.querySelectorAll('[data-marcas-tab]')];
			const insertBefore = leftTabs.find((item) => getTabIndex(item) > index);

			const moveFn = () => {
				tab.classList.remove('is-right', 'is-active');
				tab.classList.add('is-left');
				tab.setAttribute('aria-selected', 'false');
				removeCaret(tab);

				if (insertBefore) {
					leftRail.insertBefore(tab, insertBefore);
				} else {
					leftRail.appendChild(tab);
				}
			};

			animateTabTravel(tab, moveFn);
		};

		const moveTabToRight = (tab) => {
			const moveFn = () => {
				tab.classList.remove('is-left');
				tab.classList.add('is-right');
				ensureCaret(tab);
				rightRail.appendChild(tab);
			};

			animateTabTravel(tab, moveFn);
		};

		const getRightTabs = () => [...rightRail.querySelectorAll('[data-marcas-tab]')];

		const setActivePanel = (index, animate = true) => {
			tabs.forEach((tab, i) => {
				const active = i === index;
				tab.classList.toggle('is-active', active);
				tab.setAttribute('aria-selected', active ? 'true' : 'false');
			});

			panels.forEach((panel, i) => {
				const active = i === index;
				panel.classList.remove('is-entering');

				if (active) {
					panel.removeAttribute('hidden');
					panel.setAttribute('aria-hidden', 'false');
					panel.classList.add('is-active');

					if (animate) {
						void panel.offsetWidth;
						panel.classList.add('is-entering');
					}
				} else {
					panel.classList.remove('is-active', 'is-entering');
					panel.setAttribute('aria-hidden', 'true');

					if (isMobile()) {
						panel.setAttribute('hidden', '');
					} else {
						panel.removeAttribute('hidden');
					}
				}
			});

			if (index >= 0 && stage && panels[index]) {
				const panelBg = getComputedStyle(panels[index]).getPropertyValue('--marcas-panel-bg').trim();
				if (panelBg) {
					stage.style.setProperty('--marcas-panel-bg', panelBg);
				}
			}
		};

		const openTab = (tab) => {
			const index = getTabIndex(tab);
			moveTabToRight(tab);
			setActivePanel(index);
		};

		const closeTab = (tab) => {
			const index = getTabIndex(tab);
			const wasActive = tab.classList.contains('is-active');

			insertTabInLeftRail(tab);

			if (!wasActive) {
				return;
			}

			const remaining = getRightTabs();
			if (remaining.length) {
				setActivePanel(getTabIndex(remaining[remaining.length - 1]));
				return;
			}

			setActivePanel(-1, false);
		};

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				if (isMobile()) {
					const index = getTabIndex(tab);
					const isActive = tab.classList.contains('is-active');

					if (isActive) {
						return;
					}

					tabs.forEach((item) => {
						const onRight = item.classList.contains('is-right');
						if (onRight && item !== tab) {
							insertTabInLeftRail(item);
						}
					});

					moveTabToRight(tab);
					setActivePanel(index);
					return;
				}

				if (tab.classList.contains('is-left')) {
					openTab(tab);
					return;
				}

				if (tab.classList.contains('is-right')) {
					closeTab(tab);
				}
			});
		});

		const initialIndex = tabs.findIndex((tab) => tab.classList.contains('is-active'));
		setActivePanel(initialIndex >= 0 ? initialIndex : 0, false);

		accordion.dataset.marcasReady = 'true';
	});
};

const boot = () => {
	setupSiteHeader();
	setupInventarioCarousel();
	setupVisualDiaryCarousel();
	setupYoutubeCarousel();
	watchContactForm();
	setupMarcasAccordion();
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', boot);
} else {
	boot();
}
