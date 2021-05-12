/* jshint esversion: 6 */

// Javascript
const $ = require('jquery');
global.$ = global.jQuery = $;
import { createPopper } from '@popperjs/core';
import { Tooltip, Toast, Popover } from 'bootstrap';
const bootstrap = require('bootstrap');
global._ = require('underscore');
require('clndr');

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../css/app.scss');

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
