// any CSS you require will output into a single css file (app.css in this case)
// require('tabler-ui/dist/assets/css/dashboard.css');

import 'bootstrap';
import './bootstrap';
import '@tabler/core';

require('bootstrap/scss/_media.scss');
require('@tabler/core/dist/css/tabler.min.css');
require('./css/app.scss');
require('./css/fontawesome.min.css');
require('./css/brands.min.css');
require('./css/solid.css');

const $ = require('jquery');

global.jQuery = $;
global.$ = global.jQuery;
