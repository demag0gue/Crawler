<?php

require 'config.php';
require 'lib/app.php';
require 'lib/module.php';
require 'lib/response.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

new App();