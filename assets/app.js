/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// Webpack Encore test
console.log('Hello Webpack Encore !');

// start the Stimulus application
import './bootstrap';

// returns the final, public path to this file
// path is relative to this file - e.g. assets/images/logo.png
import logoPath from './images/WildSeriesMainLogo.svg';

let html = `<img src="${logoPath}" alt="Wild Series Main logo">`;

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
