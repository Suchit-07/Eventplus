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


if($_SESSION['user']['power'] == 1){
  $nine = '';
  $ten = '';
  $eleven = '';
  $twelve = '';
  if($_GET['grade'] == 9){
    $nine = 'selected';
    $ten = '';
    $eleven = '';
    $twelve = '';
  }
  if($_GET['grade'] == 10){
    $nine = '';
    $ten = 'selected';
    $eleven = '';
    $twelve = '';
  }
  if($_GET['grade'] == 11){
    $nine = '';
    $ten = '';
    $eleven = 'selected';
    $twelve = '';
  }
  if($_GET['grade'] == 12){
    $nine = '';
    $ten = '';
    $eleven = '';
    $twelve = 'selected';
  }
  echo('
  <form id="gradeForm">
  <select name="grade" onchange="this.form.submit()">
  <option value="-1"> All Grades </option>
  <option value="9" '.$nine.'> 9th Grade </option>
  <option value="10"'.$ten.'> 10th Grade </option>
  <option value="11"'.$eleven.'> 11th Grade </option>
  <option value="12"'.$twelve.'> 12th Grade </option>
  </select>
  </form>');
}

?>
<table class="table mt-5 table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Email</th>
      <th scope="col">Grade</th>
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
          if(!$_GET['grade'] || $response[$x]['grade'] == $_GET['grade'] || $_GET['grade'] == '-1'){
            echo('<tr>
            <th scope="row">'.($x+1).'</th>
            <td>'.$response[$x]["first_name"].'</td>
            <td>'.$response[$x]["last_name"].'</td>
            <td>'.$response[$x]["email"].'</td> 
            <td>'.$response[$x]["grade"].'th</td> 
            <td>'.$response[$x]["points"].'</td>
        </tr>');
          }
        }
    } else{
        for ($x = 0; $x < count($response); $x++) {
            if($response[$x]['id'] == $_SESSION['user']['id']){
                echo('<tr>
                <th scope="row">'.($x+1).'</th>
                <td> <b>'.$response[$x]["first_name"].' </b></td>
                <td><b>'.$response[$x]["last_name"].'</b></td>
                <td><b>'.$response[$x]["email"].'</b></td> 
                <td><b>'.$response[$x]["grade"].'th</b></td> 
                <td><b>'.$response[$x]["points"].'</b></td> 
                </tr>');
            } else{
                echo('<tr>
                <th scope="row">'.($x+1).'</th>
                <td>Anonymous</td>
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