<?php
require 'init.php';
require 'Database.php';

$database = new Database();

$database->leaderboard_as_xls();
?>