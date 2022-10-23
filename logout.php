<?php
require 'init.php';
session_destroy();
header("Location: " . $_ENV['BASE_URL'] . "login.php");
?>