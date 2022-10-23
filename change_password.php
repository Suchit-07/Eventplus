<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require 'init.php';
require 'Database.php';

include_once "navbar.php";
if(isset($_POST['submit'])){
    if($_SESSION['user']['email'] ?? null){
        $email = $_SESSION['user']['email'];
    } elseif($_GET['email'] ?? null){
        $email = $_GET['email'];
    } else{
      $email = $_POST['email'];
    }
    if(!$email ?? null){
        $error = 'Please Enter a Valid Email';
    } elseif(!$_POST['old'] ?? null){
        $error = 'Please Enter Your Old Password';
    } elseif(!$_POST['new'] ?? null){
        $error = 'Please Enter Your New Password';
    } elseif($_POST['old'] == $_POST['new']){
        $error = 'Old and New Passwords Cannot Be The Same';
    } else{
        $database = new Database;

        $auth = $database->change_password($email, $_POST['old'], $_POST['new']);
        if(!$auth){
            $error = 'Email and Password Do Not Match';
        } else{
            if($_SESSION['user'] ?? null){
                header("Location: " . $_ENV['BASE_URL'] . "index.php?success=Successfully%20Changed%20Password");
            } else{
                $database = new Database;
                $auth = $database->authenticate($email, $_POST['new']);
                if($auth){
                  if(!$_SESSION['user']){
                    $_SESSION['user'] = $auth;
                  }
                  header("Location: " . $_ENV['BASE_URL'] . "?success=Successfully%20Logged%20In");
                } else{
                  header("Location: " . $_ENV['BASE_URL'] . "login.php?success=Successfully%20Changed%20Password%20Please%20Log%20In");

                }
                header("Location: " . $_ENV['BASE_URL'] . "login.php?success=Successfully%20Changed%20Password%20Please%20Log%20In");
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

if(!$_SESSION['user'] && !$_GET['email'] ?? null){
echo('
<div class="container-fluid h-75">
<div class="row d-flex justify-content-center align-items-center h-100">

  <div class="col-md-8 col-lg-6 col-xl-4 p-5 border border-dark">
    <form method="post" action="change_password.php">
    <h1 class="text-center"> Change Password </h1>
      <!-- Email input -->
      <div class="form-outline mb-4">
      <label class="form-label" for="email">Email</label>
        <input name="email" type="email" id="email" class="form-control form-control-lg"
          placeholder="Enter email address" required>
        
      </div>

      <div class="form-outline mb-4">
      <label class="form-label" for="old">Old Password</label>
        <input name="old" type="password" id="old" class="form-control form-control-lg"
          placeholder="Enter old password" required>
        
      </div>

      <div class="form-outline mb-4">
      <label class="form-label" for="new">New Password</label>
        <input name="new" type="password" id="new" class="form-control form-control-lg"
          placeholder="Enter new password" required>
        
      </div>


      <div class="justify-content-center text-center text-lg-start mt-4 pt-2">
        <button name="submit" type="submit" class="form-control form-control-lg btn btn-primary btn-lg border border-dark">Update</button>
      </div>

    </form>
  </div>
</div>
</div>
');
} else{
    echo('<div class="container-fluid h-75">
    <div class="row d-flex justify-content-center align-items-center h-100">
    
      <div class="col-md-8 col-lg-6 col-xl-4 p-5 border border-dark">
        <form method="post" action="change_password.php">
        <input type="hidden" name="email" value="'.$_GET['email'].'">
        <h1 class="text-center"> Change Password </h1>
          <div class="form-outline mb-4">
          <label class="form-label" for="old">Old Password</label>
            <input name="old" type="password" id="old" class="form-control form-control-lg"
              placeholder="Enter old password" required>
            
          </div>
    
          <div class="form-outline mb-4">
          <label class="form-label" for="new">New Password</label>
            <input name="new" type="password" id="new" class="form-control form-control-lg"
              placeholder="Enter new password" required>
            
          </div>
    
    
          <div class="justify-content-center text-center text-lg-start mt-4 pt-2">
            <button name="submit" type="submit" class="form-control form-control-lg btn btn-primary btn-lg border border-dark">Update</button>
          </div>
    
        </form>
      </div>
    </div>
    </div>');
}