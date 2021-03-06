<?php

use App\Foundation\Config;
use Illuminate\Database\Capsule\Manager AS Capsule;

$config = Config::getInstance();

$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $config->get('database.host'),
    'database' => $config->get('database.name'),
    'username' => $config->get('database.user'),
    'password' => $config->get('database.pass'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
], 'default');

$capsule->setAsGlobal();
$capsule->bootEloquent();

Capsule::connection();