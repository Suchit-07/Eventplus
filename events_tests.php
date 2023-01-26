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
            $error = "Please Enter a Name";
        } elseif(!$_GET['points'] ?? null){
            $error = "Please Enter a Point Value";
        } elseif(!$_GET['description'] ?? null){
            $error = "Please Enter a Description";
        } elseif(strlen($_GET['description']) > 254){
            $error = "Description Cannot Be More Than 255 Characters";
        }elseif(!$_GET['date'] ?? null){
            $error = "Please Enter a Date";
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
            header("Location: " . $_ENV['BASE_URL'] . "events.php?getdate=" . substr($_GET['getdate'], 1) ?? '');
        }
    } elseif($_GET['action'] == 'unregister' ?? null){
        if($_GET['id']){
            if(!$database->is_confirmed($_SESSION['user']['id'], $_GET['id'])){
                $database->unregister_student($_SESSION['user']['id'], $_GET['id']);
                header("Location: " . $_ENV['BASE_URL'] . "events.php?getdate=" . substr($_GET['getdate'], 1) ?? '');
            }else{
                header("Location: " . $_ENV['BASE_URL'] . "events.php?getdate=" . substr($_GET['getdate'], 1) ?? '');
            }
        }
    }

    if(!$_GET['getdate'] ?? null){
        $success = "Please Pick a Date";
        $viewAll = True;
        $date = False;
    } elseif($_GET['getdate'] == '-1'){
        $viewAll = True;
        $date = False;
    }else{
        $date = $_GET['getdate'];
        $viewAll = False;
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

<div class="p-3" style="display:grid">
<?php
//Student UI


if($power == 0){ 
    ?>
    <div class="row">
    <div class="col div-margin justify-content-center text-center">
	    <div class="datepicker justify-content-center text-center"></div>
        <button onclick="refresh_list()" class="m-3 col-md-10 btn btn-primary">View All</button>
    </div>
    <div class="col">
    <?php
    foreach($response as $i){
        if($viewAll == True || $date == explode(' ', $i['date'])[0]){
            if($database->is_confirmed($_SESSION['user']['id'], $i['id'])){
                $button = '<p><u>Confirmed</u></p>';
            }elseif($database->is_registered($_SESSION['user']['id'], $i['id'])){
                $button = '<a href="events.php?action=unregister&id='.$i['id'].'&getdate= '.$_GET['getdate'].'" class="btn btn-secondary">Unregister</a>';
            }else{
                $button = '<a href="events.php?action=register&id='.$i['id'].'&getdate= '.$_GET['getdate'].'" class="btn btn-primary">Register</a>';
            }
            echo('
            <div class="col-md-4 card m-3 border border-dark " style="width: 40rem;">
            <div class="card-body">
            <h4 class="card-title">' .$i["name"] . " - " .$i["points"] ." points" .'</h4>
            <hr>
            <p class="card-text">'.$i["description"].'</p>
            <p class="card-text">'.$i["date"].'</p>
            '.$button.'
            </div>
            </div>');
        }
        
    }
} 




//Admin UI
elseif($power == 1){
    ?>
</div>
<div class="p-3" style="display:grid">
<div class="row">
    <?php
    foreach($response as $i){
            echo('
            <div class="col-md-3">
            <div class="card m-3 border border-dark" style="width: 18rem;">
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
                </div>
            ');

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
            </form> 
            </div>');
        
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
<script>
    var urlParams2 = new URLSearchParams(window.location.search).get('getdate');
    var fin_date = urlParams2?.split('-')
    if(fin_date){
        var date_select = new Date(fin_date[0], fin_date[1]-1, fin_date[2])
    } else{
        var date_select = ''
    }
    $('.datepicker').datepicker('setDate', date_select);

    $('.datepicker').on('changeDate', function (event) {
        var urlParams = new URLSearchParams(window.location.search);

        var entries = urlParams.entries()
        var url_without = window.location.href.split('?')[0] + '?';
        
        for(const entry of entries){
            if(entry[0] !== "getdate"){
                url_without += '&' + entry[0]+ '=' + entry[1];
            }
        }

        var url = url_without + '&getdate=' + JSON.stringify(event.date.toJSON()).slice(1,11);
        
        //alert(url)
        window.location.replace(url);
    });

    function refresh_list(){
        var urlParams = new URLSearchParams(window.location.search);

        var entries = urlParams.entries()
        var url_without = window.location.href.split('?')[0] + '?';
        
        for(const entry of entries){
            if(entry[0] !== "getdate"){
                url_without += '&' + entry[0]+ '=' + entry[1];
            }
        }

        var url = url_without + '&getdate=' + '-1';
        window.location.replace(url);
    }
</script>

<style>
    .datepicker,
.table-condensed {
  width: 35rem;
  height:35rem;
  margin: 1.7rem;
}

</style>
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