<?php

use App\Authentication\Authentication;
use App\Authentication\TokenService;
use App\Bootstrap;

require '../vendor/autoload.php';
require '../app/Bootstrap.php';
require '../app/Authentication/TokenService.php';

$bootstrap = new Bootstrap(new Authentication(new TokenService()));
$bootstrap->run();

