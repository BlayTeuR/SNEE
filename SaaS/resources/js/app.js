import './bootstrap';

import Alpine from 'alpinejs';

import './loading.js';
import '../css/notifications.css';
import { saveNotificationBeforeReload } from './notifications';
window.saveNotificationBeforeReload = saveNotificationBeforeReload;

window.Alpine = Alpine;

Alpine.start();
