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
if($_SESSION['user']['power'] == 0){
    header("Location: " . $_ENV['BASE_URL'] . "prize.php");
}

$database = new Database;
$prizes = $database->get_prizes();

usort($prizes, fn($a, $b) => $a['points'] <=> $b['points']);

if($_GET['action'] == 'delete' ?? null){
    if($_GET['id'] ?? null){
        if(!$database->delete_prize($_GET['id'])){
            $error = 'Something went wrong deleting the prize. Please try again later';
        } else{
            header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php");
        }
    }
} elseif($_GET['action'] == 'add' ?? null){
    if(!$_GET['name'] ?? null){
        $error = 'Please Enter a Name';
    } elseif(!$_GET['points'] ?? null){
        $error = 'Please Enter a Point Value';
    } else{
        if(!$database->add_prize($_GET['name'], $_GET['points'])){
            $error = 'Something went wrong adding the prize. Please try again later';
        } else{
            header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php");
        }
    }
} elseif($_GET['action'] == 'random' ?? null){
    $response1 = $database->pick_random_winner();
    $winner = $response1['first_name'] .' '. $response1['last_name'];

    $response2 = $database->pick_highest_winner();
    if(!$response1 && !$response2){
        $error = 'All students are currently selected for a prize';
        header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php?error=" . $error);
    } else if(!$response2 && $response1){
        $success = 'Winner is: ' . $winner;
        header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php?success=" . $success);
    }
    else{
        $success = 'Winners are: ' . $response2['first_name'] .' '. $response2['last_name']. ', ' . $winner;
        header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php?success=" . $success);
    }
}

if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}
?>
<div class="col">
<button type="button" class="m-1 btn btn-primary border-2 border-dark"data-bs-toggle="modal" data-bs-target="#addModal">Add Prize </button>
<a class="m-1 btn btn-success border-2 border-dark" href="<?=$_ENV['BASE_URL']?>admin_prize.php?action=random">Pick Winners </a>

</div>
<form>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <input type="hidden" name="action" value="add">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3> Add Prize </h3>
                <button type="button" onClick="window.location.reload();" class="close btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>


                <div class="modal-body">
                <div class="form-outline mb-4">
                <label class="form-label" for="form3Example3">Name</label>
                <input name="name" type="text" id="form3Example3" class="form-control form-control-md" placeholder="Enter prize name" required>

                <label class="form-label mt-4" for="form3Example3">Points Needed</label>
                <input name="points" type="number" id="form3Example3" class="md-4 form-control form-control-md" placeholder="Enter points needed for prize" required>

                </div>
                </div>


                <div class="modal-footer">
                <button type="button" onClick="window.location.reload();" class="btn btn-secondary border border-dark" data-bs-dismiss="modal">Close</button>
                <button name="submit" type="submit" class="btn btn-primary border border-dark">Add Event</button>
                </div>
            </div>
            </div>
            </div>
</form>

<div class="mt-5 mb-5 jumbotron text-center">
<h1 class="display-3">Prize</h1>
</div>

<table class="table mt-5 table-hover">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Prize</th>
            <th scope="col">Point(s) Needed</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php
            
            for ($x = 0; $x < count($prizes); $x++) {
                echo('<tr>
                <th scope="row">'.($x+1).'</th>
                <td>'.$prizes[$x]["prize"].'</td>
                <td>'.$prizes[$x]["points"].'</td>
                <td> <a href="admin_prize.php?action=delete&id='.$prizes[$x]["id"].'" class="btn btn-danger border border-dark"> x </a>
            </tr>');
            }
        ?>

    </tbody>
</table>

