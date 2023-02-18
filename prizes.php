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
    header("Location: " . $_ENV['BASE_URL'] . "admin_prize.php");
}

$database = new Database;
$prizes = $database->get_prizes();

$user = $database->get_prize_select($_SESSION['user']['email']);
$chosen_prizes = $database->get_chosen_prizes($_SESSION['user']['id']);

usort($prizes, fn($a, $b) => $a['points'] <=> $b['points']);
?>
<?php
if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}

if($_GET['submit'] ?? null){
    if($_GET['check']){
        $response = $database->pick_prize($_GET['check'], $_SESSION['user']['email']);
        if($response){
            header("Location: " . $_ENV['BASE_URL'] . "prizes.php?error=" . $response);
        }else{
            $message = 'Successfully Chose Prize! Collect Your Prize From an Admin';
            header("Location: " . $_ENV['BASE_URL'] . "prizes.php?success=" . $message);
        }
    }
}
?>
<div class="text-start">
<p class="m-3">Current Point(s): <?= $user[0]['points'] ?><p>
<p class="m-3">Chosen Prize(s): <?php foreach($chosen_prizes as $element => $x){
    if($element == array_key_last($chosen_prizes)){
        echo($x);
    }else{
        echo($x . ', ');
    }
}?> <p>
</div>
<form>
<table class="table mt-5 table-hover justify-content-center">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Prize</th>
            <th scope="col">Point(s) Needed</th>
            <th scope="col">Choose</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $num = 1;
            for ($x = 0; $x < count($prizes); $x++) {
                if($user[0]["points"] >= $prizes[$x]["points"]){
                    $canBuy = '';
                    $color = 'text-success';
                } else{
                    $canBuy = 'disabled';
                    $color = 'text-danger';
                }
                
                if($user[0]['prize_select'] == 0){
                    $canBuy = 'disabled';
                }

                if(!in_array($prizes[$x]["prize"], $chosen_prizes)){
                echo('<tr>
                <th scope="row">'.($num).'</th>
                <td>'.$prizes[$x]["prize"].'</td>
                <td class="'.$color.'">'.$prizes[$x]["points"].'</td>
                <td><input value="'.$prizes[$x]['id'].'" name="check" type="radio" '.$canBuy.' required></td> 
            </tr>');
            $num++;
                }
            }
        ?>

    </tbody>
</table>
<?php

if($user[0]['prize_select'] == 0){
    echo('<h5 class="m-3"> Currently not chosen for a prize </h5>');
} elseif ($user[0]['prize_select'] == 1){
    echo('<h5 class="m-3"> Congrats you have won a prize! Select your prize above </h5>');
}

if($user[0]['prize_select'] == 1){
    echo('<button name="submit" value="1"type="submit" class="m-2 btn btn-primary border border-dark">Choose</button>');
}
?>
 </form>

