<?php

use App\Bootstrap;
use KanbanBoard\Authentication;

require '../../vendor/autoload.php';
require 'Bootstrap.php';

$bootstrap = new Bootstrap(new Authentication());
$bootstrap->run();

