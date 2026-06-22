/**
 * Media picker para o widget Banner do blog.
 */
(function ($) {
	const initBannerWidget = (root) => {
		const imageIdInput = root.querySelector('[data-banner-image-id]');
		const preview = root.querySelector('[data-banner-preview]');
		const selectBtn = root.querySelector('[data-banner-select]');
		const removeBtn = root.querySelector('[data-banner-remove]');

		if (!imageIdInput || !preview || !selectBtn || !removeBtn) {
			return;
		}

		let frame = null;

		const setPreview = (attachment) => {
			if (!attachment || !attachment.url) {
				preview.innerHTML = '';
				return;
			}
			const alt = attachment.alt ? String(attachment.alt) : '';
			preview.innerHTML = `<img src="${attachment.url}" alt="${alt}" style="max-width:100%;height:auto;display:block;" />`;
		};

		selectBtn.addEventListener('click', (event) => {
			event.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: selectBtn.textContent || 'Selecionar imagem',
				button: { text: 'Usar esta imagem' },
				library: { type: 'image' },
				multiple: false,
			});

			frame.on('select', () => {
				const attachment = frame.state().get('selection').first().toJSON();
				imageIdInput.value = attachment.id ? String(attachment.id) : '';
				setPreview(attachment);
				removeBtn.style.display = attachment.id ? '' : 'none';
			});

			frame.open();
		});

		removeBtn.addEventListener('click', (event) => {
			event.preventDefault();
			imageIdInput.value = '';
			preview.innerHTML = '';
			removeBtn.style.display = 'none';
		});
	};

	const boot = (context) => {
		const scope = context || document;
		scope.querySelectorAll('[data-blog-banner-widget]').forEach(initBannerWidget);
	};

	$(document).on('widget-added widget-updated', (_event, widget) => {
		if (widget && widget.length) {
			boot(widget[0]);
		}
	});

	$(() => boot(document));
})(jQuery);
