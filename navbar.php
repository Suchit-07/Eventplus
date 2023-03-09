<html>
<head>
    <title> EventPlus </title>
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <link href="bootstrap-5.1.3-dist/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <script src="bootstrap-5.1.3-dist/js/jquery.min.js"></script>
    <script src="bootstrap-5.1.3-dist/js/bootstrap-datepicker.js"></script>

</head>
<body class="bg-light" style="overflow-x: hidden;">
    <nav style="background-color:#384f45;"class="navbar navbar-expand-md navbar-light sticky-top">
        <a href="<?=$_ENV["BASE_URL"]?>" class="navbar-brand h1 text-light m-2">EventPlus</a>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <?php
                if(!$_SESSION['user']?? null){
                    echo('<a class="nav-item nav-link text-white" href="'. $_ENV["BASE_URL"]. 'login.php">Log In</a>');

                }
                if($_SESSION['user']['power'] == 1 ?? null){
                    echo('<a class="nav-item nav-link text-white" href="'. $_ENV["BASE_URL"]. 'create_user.php">Add User</a>');
                }
                if($_SESSION['user'] ?? null){
                    echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'leaderboard.php">Leaderboard</a>');
                    echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'events.php">Events</a>');
                    echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'change_password.php">Change Password</a>');
                    echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'prizes.php">Prizes</a>');

                }
                echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'faq.php">FAQ</a>');

                if($_SESSION['user'] ?? null){
                    echo('<a class="nav-item nav-link text-white" href="' . $_ENV["BASE_URL"]. 'logout.php">Log Out</a>');

                }

                ?>                
            </div>
        </div>
    </nav>
