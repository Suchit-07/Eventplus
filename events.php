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
$power = $_SESSION['user']['power'] ?? null;

if($power == 1){
    if($_GET['action'] == 'update' ?? null){
        if(!$_GET['id'] ?? null){
            $error = "Invalid Query Please Try Again Later";
        } elseif(!$_GET['name'] ?? null){
            $error = "Invalid Query Please Try Again Later";
        } elseif(!$_GET['points'] ?? null){
            $error = "Invalid Query Please Try Again Later";
        } elseif(!$_GET['description'] ?? null){
            $error = "Invalid Query Please Try Again Later";
        } elseif(!$_GET['date'] ?? null){
            $error = "Invalid Query Please Try Again Later";
        } elseif($_GET['type'] == null){
            $error = "Invalid Query Please Try Again Later";
        } else{
            $database->update_event($_GET['id'], $_GET['name'], $_GET['points'], $_GET['description'], $_GET['date'], $_GET['type']);
            header("Location: " . $_ENV['BASE_URL'] . "events.php");

        }
    } elseif($_GET['action'] == "delete" ?? null){
        if(!$_GET['id']){
            $error = "Invalid Query Please Try Again Later";
        } else{
            $database->delete_event($_GET['id']);
            header("Location: " . $_ENV['BASE_URL'] . "events.php");

        }
    } elseif($_GET['action'] == "add" ?? null){
        if(!$_GET['name'] ?? null){
            $error = "Please Enter a Valid Name For The Event";
        } elseif(!$_GET['points'] ?? null){
            $error = "Please Enter a Valid Point Value For The Event";
        } elseif(!$_GET['description'] ?? null){
            $error = "Please Enter a Valid Description For The Event";
        } elseif(!$_GET['date'] ?? null){
            $error = "Please Enter a Valid Date and Time For The Event";
        } elseif($_GET['type'] == null){
            $error = "Please Enter a Valid category For The Event";
        } else{
            $database->add_event($_GET['name'],$_GET['points'],$_GET['description'],$_GET['date'],$_GET['type']);
            header("Location: " . $_ENV['BASE_URL'] . "events.php");
        }
    }
} else{
    if($_GET['action'] == 'register' ?? null){
        if($_GET['id']){
            $database->register_student($_SESSION['user']['id'], $_GET['id']);
            header("Location: " . $_ENV['BASE_URL'] . "events.php");
        }
    } elseif($_GET['action'] == 'unregister' ?? null){
        if($_GET['id']){
            if(!$database->is_confirmed($_SESSION['user']['id'], $_GET['id'])){
                $database->unregister_student($_SESSION['user']['id'], $_GET['id']);
                header("Location: " . $_ENV['BASE_URL'] . "events.php");
            }else{
                header("Location: " . $_ENV['BASE_URL'] . "events.php");
            }
        }
    }
}

$response = $database->get_all_events();

if(!$response){
    $error="No Events Added";
}
$sport = false;
$non_sport = false;

foreach($response as $i){
    if($i['type'] == 1 ?? null){
        $sport = true;
    } elseif($i['type'] == 0 ?? null){
        $non_sport = true;
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
<div class="p-3">
<?php

if($power == 0){
    if($sport){
        echo('<hr><h5> Sporting Events </h5>');
    }
    foreach($response as $i){
        if($database->is_confirmed($_SESSION['user']['id'], $i['id'])){
            $button = '<p><u>Confirmed</u></p>';
        }elseif($database->is_registered($_SESSION['user']['id'], $i['id'])){
            $button = '<a href="events.php?action=unregister&id='.$i['id'].'" class="btn btn-secondary">Unregister</a>';
        }else{
            $button = '<a href="events.php?action=register&id='.$i['id'].'" class="btn btn-primary">Register</a>';
        }
        if($i['type']){
            echo('<div class="card m-3 border border-dark" style="width: 18rem;">
            <div class="card-body">
            <h4 class="card-title">' .$i["name"] .'</h4>
            <hr>
            <h6 class="card-title">' .$i["points"] .' Point(s)</h6>
            <p class="card-text">'.$i["description"].'</p>
            <p class="card-text">'.$i["date"].'</p>
            <hr>
            '.$button.'
            </div>
            </div>');
        }
    }
    if($non_sport){
        echo('<hr> <h5> Non-Sporting Events </h5>');
    }
    foreach($response as $i){
        if($database->is_confirmed($_SESSION['user']['id'], $i['id'])){
            $button = '<p><u>Confirmed</u></p>';
        }elseif($database->is_registered($_SESSION['user']['id'], $i['id'])){
            $button = '<a href="events.php?action=unregister&id='.$i['id'].'" class="btn btn-secondary">Unregister</a>';
        }else{
            $button = '<a href="events.php?action=register&id='.$i['id'].'" class="btn btn-primary">Register</a>';
        }
        if(!$i['type']){
            echo('<div class="card m-3 border border-dark" style="width: 18rem;">
            <div class="card-body">
            <h4 class="card-title">' .$i["name"] .'</h4>
            <hr>
            <h6 class="card-title">' .$i["points"] .' Point(s)</h6>
            <p class="card-text">'.$i["description"].'</p>
            <p class="card-text">'.$i["date"].'</p>
            <hr>
            '.$button.'            </div>
            </div>');
        }
    }
} elseif($power == 1){
    if($sport){
        echo('<hr><h5> Sporting Events </h5>');
    }
    foreach($response as $i){
        if($i['type']){
            echo('<div class="card m-3 border border-dark" style="width: 18rem;">
            <div class="card-body">
            <h4 class="card-title">' .$i["name"] .'</h4>
            <hr>
            <h6 class="card-title">' .$i["points"] .' Point(s)</h6>
            <p class="card-text">'.$i["description"].'</p>
            <p class="card-text">'.$i["date"].'</p>
            <hr>
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#exampleModalCenter'.$i["id"].'"> Edit  </button>
            <a href="event_info.php?id='.$i['id'].'" class="btn btn-primary border border-dark"> Attendants </a>

                    </div>
                </div>');

            echo('
            <form method="get" action="events.php">
            <input name="action" type="hidden" value="update"> 
            <input name="id" type="hidden" value="'.$i["id"].'"> 
            <div class="modal fade" id="exampleModalCenter'.$i["id"].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <input name="name" type="text" class="modal-title" id="exampleModalLongTitle" value="'.$i["name"].'">
                <button type="button" onClick="window.location.reload();" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <input name="points" class="col-md-1"type="text" value="'.$i['points'].'"> <h6>Point(s)</h6>
                <input name="description" class="mb-2 col-md-8" type="text" value="'.$i["description"].'">
                <input name="date" class="col-md-8"type="datetime-local" value="'.$i["date"].'">

                <select id="type" class="col-md-3 form-control-sm text-center" name="type">
                    <option name="sport" value="1">Sport</option>
                    <option value="0" name="non-sport">Non-Sport</option>
                </select>
                </div>
                <div class="modal-footer">
                <button type="button" onClick="window.location.reload();" class="btn btn-secondary border border-dark" data-bs-dismiss="modal">Close</button>
                <a href="events.php?action=delete&id='.$i['id'].'" class="btn btn-danger border border-dark">Delete</a>

                <button name="submit" type="submit" class="btn btn-primary border border-dark">Save changes</button>
                </div>
            </div>
            </div>
            </div>
            </form> ');
        }
    }
    if($non_sport){
        echo('<hr> <h5> Non-Sporting Events </h5>');
    }
    foreach($response as $i){
        if(!$i['type']){
            echo('<div class="card m-3 border border-dark" style="width: 18rem;">
            <div class="card-body">
            <h4 class="card-title">' .$i["name"] .'</h4>
            <hr>
            <h6 class="card-title">' .$i["points"] .' Point(s)</h6>
            <p class="card-text">'.$i["description"].'</p>
            <p class="card-text">'.$i["date"].'</p>
            <hr>
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#exampleModalCenter'.$i["id"].'"> Edit  </button>
            <a href="event_info.php?id='.$i['id'].'" class="btn btn-primary border border-dark"> Attendants </a>
                    </div>
                </div>');

            echo('
            <form method="get" action="events.php">
            <input name="action" type="hidden" value="update"> 
            <input name="id" type="hidden" value="'.$i["id"].'"> 
            <div class="modal fade" id="exampleModalCenter'.$i["id"].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <input name="name" type="text" class="modal-title" id="exampleModalLongTitle" value="'.$i["name"].'">
                <button type="button" onClick="window.location.reload();" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <input name="points" class="col-md-1"type="text" value="'.$i['points'].'"> <h6>Point(s)</h6>
                <input name="description" class="mb-2 col-md-8"type="text" value="'.$i["description"].'">
                <input name="date" class="col-md-8"type="datetime-local" value="'.$i["date"].'">

                <select id="type" class="col-md-3 form-control-sm text-center" name="type">
                    <option value="0" name="non-sport">Non-Sport</option>
                    <option name="sport" value="1">Sport</option>

                </select>
                </div>
                <div class="modal-footer">
                <button type="button" onClick="window.location.reload();" class="btn btn-secondary border border-dark" data-bs-dismiss="modal">Close</button>
                <a href="events.php?action=delete&id='.$i['id'].'" class="btn btn-danger border border-dark">Delete</a>

                <button name="submit" type="submit" class="btn btn-primary border border-dark">Save changes</button>
                </div>
            </div>
            </div>
            </div>
            </form> ');
        }
    }
    echo('<button type="button" class="btn btn-link"data-bs-toggle="modal" data-bs-target="#addModal">Add Event </button>');
    echo(' <form method="get" action="events.php">
            <input type="hidden" name="action" value="add">
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <input placeholder="Enter Name"class="form-control" id="name" name="name" type="text" required>
                <button type="button" onClick="window.location.reload();" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <input name="points" class="col-md-1"type="text" required> <h6>Point(s)</h6>
                <input placeholder="Enter Description"name="description" class="mb-2 col-md-8" type="text" required>
                <input name="date" class="col-md-8"type="datetime-local" required>

                <select id="type" class="col-md-3 form-control-sm text-center" name="type">
                    <option value="0" name="non-sport">Non-Sport</option>
                    <option name="sport" value="1">Sport</option>

                </select>
                </div>
                <div class="modal-footer">
                <button type="button" onClick="window.location.reload();" class="btn btn-secondary border border-dark" data-bs-dismiss="modal">Close</button>
                <button name="submit" type="submit" class="btn btn-primary border border-dark">Add Event</button>
                </div>
            </div>
            </div>
            </div>
            </form> ');
}
?>
</div>
<!-- <div class="card m-3 border border-dark" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">FBLA Meeting</h5>
    <h6 class="card-title">5 Point</h6>
    <p class="card-text">FBLA Competetive events meeting</p>
    <a href="#" class="btn btn-primary">Register</a>
  </div>
</div>

<div class="card m-3 border border-dark" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">FBLA Meeting</h5>
    <h6 class="card-title">5 Point</h6>
    <p class="card-text">FBLA Competetive events meeting</p>
    <a href="#" class="btn btn-primary">Edit</a>
  </div>
</div> -->




<!-- Modal -->
<!-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div> -->




