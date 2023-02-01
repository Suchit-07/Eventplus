<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require 'init.php';
require 'Database.php';

include_once "navbar.php";
if(!$_SESSION['user'] ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "login.php");
} elseif($_SESSION['user']['power'] < 1 ?? null){
    header("Location: " . $_ENV['BASE_URL'] . "index.php");
}

if(isset($_POST['submit'])){
    if(!$_POST['first'] ?? null){
        $error = 'Please Enter a Valid Email';
    } elseif(!$_POST['last']){
        $error = 'Please Enter First Name';
    } elseif(!$_POST['email']){
        $error = 'Please Enter First Name';
    } elseif(!$_POST['grade']){
      $error = 'Please Enter Grade';
  }else{
        $database = new Database;
        if(!$database->check_user_exists($_POST['email'])){
            $password = $database->create_user($_POST['email'],$_POST['first'], $_POST['last'], $_POST['grade']);

            $success = "This Account's Temporary Password is: $password";
        } else{
            $error = 'User With That Email Already Exists';
        }
    }
}

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
<div class="container-fluid h-75">
<div class="row d-flex justify-content-center align-items-center h-100">

  <div class="col-md-8 col-lg-6 col-xl-4 p-5 border border-black">
    <form method="post" action="create_user.php">
    <h1 class="text-center"> Create User </h1>
      <!-- Email input -->
      <div class="form-outline mb-4">
      <label class="form-label" for="first_name">First Name</label>
        <input name="first" type="text" id="first_name" class="form-control form-control-lg"
          placeholder="Enter first name" required>
        
      </div>

      <div class="form-outline mb-4">
      <label class="form-label" for="last_name">Last Name</label>
        <input name="last" type="text" id="last_name" class="form-control form-control-lg"
          placeholder="Enter last name" required>
        
      </div>

      <div class="form-outline mb-4">
      <label class="form-label" for="email">Email address</label>
        <input name="email" type="email" id="email" class="form-control form-control-lg"
          placeholder="Enter email address" required>
        
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="grade">Grade</label>
        <select class="form-control form-control-lg" id="grade" name="grade">
          <option value="9"> 9th </option>
          <option value="10"> 10th </option>
          <option value="11"> 11th </option>
          <option value="12"> 12th </option>
        </select>
      </div>

      <div class="justify-content-center text-center text-lg-start mt-4 pt-2">
        <button name="submit" type="submit" class="form-control form-control-lg btn btn-primary btn-lg border border-dark">Submit</button>
      </div>

    </form>
  </div>
</div>
</div>