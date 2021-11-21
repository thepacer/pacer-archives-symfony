/* jshint esversion: 6 */

// Javascript
const $ = require('jquery');
global.$ = global.jQuery = $;
import { createPopper } from '@popperjs/core';
const bootstrap = require('bootstrap');
import { Tooltip, Toast, Popover } from 'bootstrap';
require('clndr');
require('datatables.net');
require('datatables.net-bs5');

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../../node_modules/datatables.net-bs5/css/dataTables.bootstrap5.css');
require('../css/admin.scss');

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

const defaultDataTableSettings = {
  pageLength: 100
}

$('#articleTable').DataTable(defaultDataTableSettings)
$('#issueTable').DataTable(defaultDataTableSettings)
$('#volumeTable').DataTable(defaultDataTableSettings)
$('#imageTable').DataTable(defaultDataTableSettings)