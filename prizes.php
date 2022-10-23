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
// var_dump($prizes);
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

<div class="container m-5">
    <div class="card text-center border border-dark" style="display:inline-block;width:10%;--bs-bg-opacity: .3;">
        <div class="card-body">
            <h5 class="card-title">Big Eraser</h5>
            <h6 class="card-subtitle mb-2 text-muted">10 point(s)</h6>
        </div>
    </div>

    <div class="card text-center border border-dark" style="display:inline-block;width:10%;margin-left:79%">
        <div class="card-body">
            <h5 class="card-title">Pizza</h5>
            <h6 class="card-subtitle mb-2 text-muted">15 point(s)</h6>
        </div>
    </div>

    <div class="progress mt-1">
    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
    </div>
</div>

<div class="container m-5">
    <div class="card text-center border border-dark bg-success" style="display:inline-block;width:10%;--bs-bg-opacity: .3;">
        <div class="card-body">
            <h5 class="card-title">Eraser</h5>
            <h6 class="card-subtitle mb-2 text-muted">5 point(s)</h6>
        </div>
    </div>

    <div class="card text-center border border-dark" style="display:inline-block;width:10%;margin-left:79%">
        <div class="card-body">
            <h5 class="card-title">Big Eraser</h5>
            <h6 class="card-subtitle mb-2 text-muted">10 point(s)</h6>
        </div>
    </div>

    <div class="progress mt-1">
    <div class="progress-bar" role="progressbar" style="width: 40%">7</div>
    </div>
</div>

<div class="container m-5">
    <div class="card text-center border border-dark bg-success" style="display:inline-block;width:10%;--bs-bg-opacity: .3;">
        <div class="card-body">
            <h5 class="card-title">Pencil</h5>
            <h6 class="card-subtitle mb-2 text-muted">1 point(s)</h6>
        </div>
    </div>

    <div class="card text-center border border-dark bg-success" style="display:inline-block;width:10%;--bs-bg-opacity: .3;margin-left:79%">
        <div class="card-body">
            <h5 class="card-title">Eraser</h5>
            <h6 class="card-subtitle mb-2 text-muted">5 point(s)</h6>
        </div>
    </div>

    <div class="progress mt-1">
    <div class="progress-bar" role="progressbar" style="width: 100%">7</div>
    </div>
</div>

<div class="container m-5">
    <div class="card text-center border border-dark bg-success" style="display:inline-block;width:10%;margin-left:89%;--bs-bg-opacity: .3">
        <div class="card-body">
            <h5 class="card-title">Pencil</h5>
            <h6 class="card-subtitle mb-2 text-muted">1 point(s)</h6>
        </div>
    </div>

    <div class="progress mt-1">
    <div class="progress-bar" role="progressbar" style="width: 100%">7</div>
    </div>
</div>





