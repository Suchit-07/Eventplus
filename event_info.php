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
if($_SESSION['user']['power'] != 1 ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "events.php");
}
if(!$_GET['id'] ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "events.php");
}

$database = new Database;
$attendants = $database->get_registered_students($_GET['id']);
// die(var_dump($attendants));

if(isset($_POST['submit'])){
    $confirmed = $_POST['confirmed'];

    foreach($confirmed as $c){
        $database->confirmed($c, $_GET['id']);
        header("Location: " . $_ENV['BASE_URL'] . "event_info.php?id=" . $_GET['id'] );
    }
}

?>
<a class="btn btn-secondary m-3 border border-dark" href="events.php"> Back </a>
<form method="post">
<table class="table mt-5 table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Email</th>
      <th scope="col">Confirmed</th>
    </tr>
  </thead>
  <tbody>
    <!-- <tr>
      <th scope="row">1</th>
      <td>Test</td>
      <td>Test</td>
      <td>test5@test.com</td> 
      <td>Yes</td>
    </tr>
     -->
    <?php
    foreach($attendants as $i){
        if($i[1]['confirmed']){
            $confirmed = "Yes";
        } else{
            $confirmed = "No";
        }
        echo('<tr>
        <th scope="row"><input name="confirmed[]" value="'.$i[0]['id'].'" type="checkbox"></th>
        <td>'.$i[0]['first_name'].'</td>
        <td>'.$i[0]['last_name'].'</td>
        <td>'.$i[0]['email'].'</td> 
        <td>'.$confirmed.'</td>
      </tr>');
    }
    ?>
  </tbody>
</table>
<button name="submit" type="submit" class="btn btn-primary m-3 border border-dark"> Confirm </button>
</form>