import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
	Alpine.data('carCreateWizard', (config = {}) => ({
		step: config.startStep ?? 1,
		licensePlate: config.initialLicensePlate ?? '',
		validationMessage: '',
		checkUrl: config.checkUrl ?? '',
		isChecking: false,

		init() {
			if (this.licensePlate) {
				this.licensePlate = this.formatLicensePlate(this.licensePlate);
			}
		},

		normalizeLicensePlate(value) {
			return String(value ?? '').toUpperCase().replace(/[^A-Z0-9]/g, '');
		},

		formatLicensePlate(value) {
			const normalized = this.normalizeLicensePlate(value);

			if (normalized.length !== 6) {
				return normalized;
			}

			return `${normalized.slice(0, 2)}-${normalized.slice(2, 4)}-${normalized.slice(4, 6)}`;
		},

		async checkLicensePlate() {
			this.validationMessage = '';

			if (!this.checkUrl) {
				this.validationMessage = 'Kentekencontrole is niet beschikbaar.';
				return;
			}

			this.isChecking = true;

			try {
				const response = await fetch(`${this.checkUrl}?license_plate=${encodeURIComponent(this.licensePlate)}`, {
					headers: {
						Accept: 'application/json',
					},
				});

				const data = await response.json();

				if (!response.ok) {
					this.validationMessage = data?.message ?? data?.errors?.license_plate?.[0] ?? 'Het kenteken is ongeldig.';
					this.step = 1;
					return;
				}

				this.licensePlate = data.license_plate ?? this.formatLicensePlate(this.licensePlate);
				this.step = 2;
			} catch (error) {
				this.validationMessage = 'Kentekencontrole is mislukt. Probeer het opnieuw.';
			} finally {
				this.isChecking = false;
			}
		},

		goBack() {
			this.step = 1;
			this.validationMessage = '';
		},
	}));
});

Alpine.start();
