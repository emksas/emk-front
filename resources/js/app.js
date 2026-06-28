import './bootstrap';
import 'flowbite';
import AppAlert from './services/app-alert';


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
AppAlert.bind();
