<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'init.php';
require 'Database.php';

$database = new Database();

$database->array_to_db($database->xlsToArray());
?>