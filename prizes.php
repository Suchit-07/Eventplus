<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require 'init.php';
require 'Database.php';

include_once "navbar.php";


if(!$_SESSION['user'] ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "login.php");
}
if($_SESSION['user']['power'] == 1){
    header("Location: " . $_ENV['BASE_URL'] . "index.php");
}

$database = new Database;
$prizes = $database->get_prizes();
var_dump($prizes);
?>
<?php
if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}
?>
<table class="table mt-5 table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Prize</th>
            <th scope="col">Points</th>
            <th scope="col">Choose</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>



