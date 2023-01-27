<?php
require 'init.php';

include_once "navbar.php";

if(!$_SESSION['user'] ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "login.php");
}

if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}

if($_SESSION['user']['power'] == 0){
    header("Location: " . $_ENV['BASE_URL'] . "prizes.php?error=" . $error . "&success=" . $success);
} elseif($_SESSION['user']['power'] == 1){
    header("Location: " . $_ENV['BASE_URL'] . "events.php?error=" . $error . "&success=" . $success);
 
}
