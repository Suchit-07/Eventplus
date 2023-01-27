<?php

// init.php has everything to initialize the application
require 'init.php';
require 'Database.php';

// navbar.php is the navigation bar at the top of the screen
include_once "navbar.php";

// this checks if the user is already logged in
if(isset($_SESSION['user'])){
  // if they are already logged it it'll redirect them to the main page
    header("Location: " . $_ENV['BASE_URL']);
}

// this checks if the user pressed the submit button
if(isset($_POST['submit'])){

    // this checks if any of the mandatory inputs are empty
    if(!$_POST['email'] ?? false){

      // displays an error to the user if any input is empty
        $error = 'Please Enter a Valid Email';
    } elseif(!$_POST['password'] ?? false){
        $error = 'Please Enter Your Password';
    } else{

      // creates a new Database class which contains all the methods to communicate with the database
        $database = new Database;

      // authenticates the user with the given email and password
        $authentication = $database->authenticate($_POST['email'], $_POST['password']);

        if(!$authentication){
          // displays an error to the user if the email/password was incorrect
            $error = 'Email and Password are Incorrect';
        } else{
          // checks if the user is a student
            if($authentication['power'] == 0 ?? 0){

              // checks if the this is the first time a student logged in
              if($authentication['first_login'] == 1 ?? null){

                // if it is, it propmts the user to change their temporary password
                header("Location: " . $_ENV['BASE_URL'] . "change_password.php?success=Please%20Change%20Temporary%20Password&email=" . $_POST['email']);
              }else{
                // if it's not, it finishes logging the user in
                if(!$_SESSION['user']){
                    $_SESSION['user'] = $authentication;
                }

                // redirects user to the main page
                header("Location: " . $_ENV['BASE_URL'] . "?success=Successfully%20Logged%20In");
              }

            }else{
              // finished logging in for admin users
                if(!$_SESSION['user']){
                    $_SESSION['user'] = $authentication;
                }
                // redirects admin users to main page
                header("Location: " . $_ENV['BASE_URL'] . "?success=Successfully%20Logged%20In");
              }

        }
    }
}

?>

<?php
// displays error or success notifications to users
if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}
?>

<!-- HTML code for the log in form -->
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