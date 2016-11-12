<?php

require "paths.php";
require "vendor/autoload.php";
require "app/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 'on');
set_time_limit(0);

require "install/migrations/0-user.php";

$UserMigration = new User;
$UserMigration->down();
$UserMigration->up();

echo "migrated userss\n";
