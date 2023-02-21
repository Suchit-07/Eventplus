<?php
require 'init.php';
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
require 'init.php';
require 'Database.php';

$target_dir = "xls/";
$target_file = $target_dir . 'leaderboard.xls';
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if($imageFileType == 'xls'){
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $database = new Database();
        $database->array_to_db($database->xlsToArray());
        $message ='Successfully Uploaded CSV';
        header("Location: " . $_ENV['BASE_URL'] . "leaderboard.php?success=" . $message);
    }else{
        $message ='Something Went Wrong. Please Try Again Later';
        header("Location: " . $_ENV['BASE_URL'] . "leaderboard.php?error=" . $message);
    }
}


