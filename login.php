<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require 'init.php';
require 'Database.php';

include_once "navbar.php";

if(isset($_SESSION['user'])){
    header("Location: " . $_ENV['BASE_URL']);
}

if(isset($_POST['submit'])){
    if(!$_POST['email'] ?? false){
        $error = 'Please Enter a Valid Email';
    } elseif(!$_POST['password'] ?? false){
        $error = 'Please Enter Your Password';
    } else{
        $database = new Database;
        $authentication = $database->authenticate($_POST['email'], $_POST['password']);

        if(!$authentication){
            $error = 'Email and Password are Incorrect';
        } else{
            if($authentication['power'] == 0 ?? 0){
              if($authentication['first_login'] == 1 ?? null){
                header("Location: " . $_ENV['BASE_URL'] . "change_password.php?success=Please%20Change%20Temporary%20Password&email=" . $_POST['email']);
              }else{
                if(!$_SESSION['user']){
                    $_SESSION['user'] = $authentication;
                }
                header("Location: " . $_ENV['BASE_URL'] . "?success=Successfully%20Logged%20In");
              }
            }else{
                if(!$_SESSION['user']){
                    $_SESSION['user'] = $authentication;
                }
                header("Location: " . $_ENV['BASE_URL'] . "?success=Successfully%20Logged%20In");
              }

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

  <div class="col-md-8 col-lg-6 col-xl-4 p-5 border border-dark">
    <form method="post" action="login.php">
    <h1 class="text-center"> Log In </h1>
      <!-- Email input -->
      <div class="form-outline mb-4">
      <label class="form-label" for="form3Example3">Email address</label>
        <input name="email" type="email" id="form3Example3" class="form-control form-control-lg"
          placeholder="Enter email address" required>
        
      </div>

      <!-- Password input -->
      <div class="form-outline mb-3">
      <label class="form-label" for="form3Example4">Password</label>
        <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
          placeholder="Enter password" required> 
      </div>


      <div class="justify-content-center text-center text-lg-start mt-4 pt-2">
        <button name="submit" type="submit" class="form-control form-control-lg btn btn-primary btn-lg border border-dark">Login</button>
      </div>

    </form>
  </div>
</div>
</div>