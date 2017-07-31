<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'monitoreoGa',
    'username'  => 'root',
    'password'  => 'Gaddp552014',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => '',
    'port'		=>'3307',
]);

$capsule->setAsGlobal();

