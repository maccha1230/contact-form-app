import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { initPhoneField } from './contact/phone-field-handler.js';

document.addEventListener('DOMContentLoaded', () => {
    initPhoneField();
});