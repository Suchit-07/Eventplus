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

$database = new Database;
$response = $database->get_leaderboard();

usort($response, function($a, $b) {
    return $b['points'] <=> $a['points'];
});

?>
<table class="table mt-5 table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Email</th>
      <th scope="col">Points</th>
    </tr>
  </thead>
  <tbody>
    <!-- <tr>
      <th scope="row">1</th>
      <td>Test</td>
      <td>Test</td>
      <td>test5@test.com</td> 
      <td>Yes</td>
    </tr>  -->
    <?php
    if($_SESSION['user']['power'] == 1){
        for ($x = 0; $x < count($response); $x++) {
            echo('<tr>
            <th scope="row">'.($x+1).'</th>
            <td>'.$response[$x]["first_name"].'</td>
            <td>'.$response[$x]["last_name"].'</td>
            <td>'.$response[$x]["email"].'</td> 
            <td>'.$response[$x]["points"].'</td>
        </tr>');
        }
    } else{
        for ($x = 0; $x < count($response); $x++) {
            if($response[$x]['id'] == $_SESSION['user']['id']){
                echo('<tr>
                <th scope="row">'.($x+1).'</th>
                <td>'.$response[$x]["first_name"].'</td>
                <td>'.$response[$x]["last_name"].'</td>
                <td>'.$response[$x]["email"].'</td> 
                <td>'.$response[$x]["points"].'</td>
                </tr>');
            } else{
                echo('<tr>
                <th scope="row">'.($x+1).'</th>
                <td>Anonymous</td>
                <td>Anonymous</td>
                <td>Anonymous</td> 
                <td>'.$response[$x]["points"].'</td>
                </tr>');
            }
        }
    }
    ?>
  </tbody>
</table>