/* jshint esversion: 6 */

// Javascript
const $ = require('jquery');
global.$ = global.jQuery = $;
import { createPopper } from '@popperjs/core';
const bootstrap = require('bootstrap');
import { Tooltip, Toast, Popover } from 'bootstrap';
require('clndr');
require('../../node_modules/datatables.net/js/jquery.dataTables.js');
require('../../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js');

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../../node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css');
require('../css/admin.scss');

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
