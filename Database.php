<?php
use PDO;
class Database{
    public $db;

    public function __construct(){
        $this->db = new PDO("mysql:host=localhost;dbname=event",$_ENV['DB_USER'],$_ENV['DB_PASS']);
    }

    public function authenticate($email, $password){
        $user_find_query = $this->db->prepare('SELECT * from user where email = :email');

        $user_find_query->execute([
            ':email' => $email,
        ]);

        $user_response = $user_find_query->fetch(PDO::FETCH_ASSOC);
        if(!$user_response){
            return false;
        }
        if(password_verify($password, $user_response['password_hash'])){
            return $user_response;
        } else{
            return false;
        }
    }

    public function check_user_exists($email){
        $user_find_query = $this->db->prepare('SELECT * from user where email = :email');

        $user_find_query->execute([
            ':email' => $email,
        ]);

        $user_response = $user_find_query->fetch(PDO::FETCH_ASSOC);

        if($user_response != NULL){
            return true;
        } else{
            return false;
        }
    }

    public function check_first_login($email){
        $user_find_query = $this->db->prepare('SELECT * from user where email = :email');

        $user_find_query->execute([
            ':email' => $email,
        ]);

        $user_response = $user_find_query->fetch(PDO::FETCH_ASSOC);

        if($user_response['first_login'] == 1){
            return true;
        } else{
            return false;
        }
    }

    public function change_password($email, $password, $new_password){
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        if($this->authenticate($email, $password)){
            $password_update_query = $this->db->prepare('UPDATE user
            SET password_hash = :password, first_login = 0 WHERE email = :email');

            $password_update_query->execute([
                ':password' => $new_password_hash,
                ':email' => $email,
            ]);

            return true;
        } else{
            return false;
        }
    }

    public function create_user($email, $first_name, $last_name){
        $password = $this->randomPassword();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user_create_query = $this->db->prepare('INSERT into user (email, first_name, last_name, password_hash, power, first_login, points) VALUES (:email, :first_name, :last_name, :password, 0, 1, 0)');

        $user_create_query->execute([
            ':email' => $email,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':password' => $password_hash,
        ]);

        return $password;

    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function get_all_events(){
        $event_query = $this->db->prepare('SELECT * from event');
        $event_query->execute();
        $response = $event_query->fetchAll();

        return $response;
    }

    public function update_event($id, $name, $points, $description, $date, $type){
        $participant_query = $this->db->prepare('SELECT * from participants where event_id = :id and confirmed = 1');
        $participant_query->execute([
            ':id' => $id,
        ]);
        $participant_response = $participant_query->fetchAll();

        $event_query = $this->db->prepare('SELECT * from event where id = :id');
        $event_query->execute([
            ':id' => $id,
        ]);
        $event_response = $event_query->fetchAll();
        foreach($participant_response as $p){
            $user_query = $this->db->prepare('SELECT * from user where id = :id');
            $user_query->execute([
                ':id' => $p['user_id'],
            ]);
            $user_response = $user_query->fetchAll();

            $new_points = ($points - $event_response[0]['points'])+$user_response[0]['points'];

            $user_update_query = $this->db->prepare('UPDATE user SET points = :points WHERE id = :id');
            $user_update_query->execute([
                ':points' => $new_points,
                ':id' => $p['user_id'],
            ]);
        }

        $update_query = $this->db->prepare('UPDATE event
        SET name = :name, points = :points, description = :description, date = :date, type = :type
        WHERE id = :id');

        $update_query->execute([
            ':name' => $name,
            ':points' => $points,
            ':description' => $description,
            ':date' => $date,
            ':type' => $type,
            ':id' => $id,
        ]);

    }

    public function delete_event($id){
        $participant_query = $this->db->prepare('SELECT * from participants where event_id = :id and confirmed = 1');
        $participant_query->execute([
            ':id' => $id,
        ]);
        $participant_response = $participant_query->fetchAll();

        $event_query = $this->db->prepare('SELECT * from event where id = :id');
        $event_query->execute([
            ':id' => $id,
        ]);
        $event_response = $event_query->fetchAll();

        foreach($participant_response as $p){
            $user_query = $this->db->prepare('SELECT * from user where id = :id');
            $user_query->execute([
                ':id' => $p['user_id'],
            ]);
            $user_response = $user_query->fetchAll();

            $points = $user_response[0]['points'] - $event_response[0]['points'];

            $user_update_query = $this->db->prepare('UPDATE user SET points = :points WHERE id = :id');
            $user_update_query->execute([
                ':points' => $points,
                ':id' => $p['user_id'],
            ]);
        }
        $participant_delete_query = $this->db->prepare('DELETE FROM participants WHERE event_id = :id');
        $participant_delete_query->execute([
            ':id' => $id,
        ]);

        $event_delete_query = $this->db->prepare('DELETE FROM event WHERE id=:id');

        $event_delete_query->execute([
            ':id' => $id,
        ]);
    }

    public function add_event($name, $points, $description, $date, $type){
        $add_query = $this->db->prepare('INSERT INTO event (name, points, description, date, type) VALUES (:name, :points, :description, :date, :type)');
        $add_query->execute([
            ':name' => $name,
            ':points' => $points,
            ':description' => $description,
            ':date' => $date,
            ':type' => $type,
        ]);

    }

    public function register_student($user_id, $event_id){
        if(!$this->is_registered($user_id, $event_id)){
            $add_query = $this->db->prepare('INSERT INTO participants (user_id, event_id, confirmed) VALUES (:user_id, :event_id, 0)');
            $add_query->execute([
                ':user_id' => $user_id,
                'event_id' => $event_id,
            ]);
        }
    }

    public function is_registered($user_id, $event_id){
        $query = $this->db->prepare('SELECT * from participants where user_id = :user_id and event_id = :event_id');
        $query->execute([
            ':user_id' => $user_id,
            ':event_id' => $event_id,
        ]);

        $response = $query->fetchAll();
        return $response ?? null;
    }

    public function unregister_student($user_id, $event_id){
        if($this->is_registered($user_id, $event_id)){
            $delete_query = $this->db->prepare('DELETE FROM participants where user_id = :user_id and event_id = :event_id');
            $delete_query->execute([
                ':user_id' => $user_id,
                'event_id' => $event_id,
            ]);
        }
    }
    public function get_registered_students($event_id){
        $participant_query = $this->db->prepare('SELECT * FROM participants where event_id = :event_id');
        $participant_query->execute([
            ':event_id' => $event_id,
        ]);

        $participant_response = $participant_query->fetchAll();
        
        $info = [];
        foreach($participant_response as $p){
            $user_query = $this->db->prepare('SELECT * from user where id = :id');
            $user_query->execute([
                ':id' => $p['user_id'],
            ]);
            $user_response = $user_query->fetchAll();
            array_push($user_response, $p);
            array_push($info, $user_response);
        }
        return $info;
    }

    public function confirmed($user_id, $event_id){
        $query = $this->db->prepare('SELECT * from participants where user_id = :user_id and event_id = :event_id');
        $query->execute([
            ':user_id' => $user_id,
            ':event_id' => $event_id,
        ]);

        $response = $query->fetchAll();
        if(!$response[0]['confirmed']){
            $event_query = $this->db->prepare('SELECT * from event where id = :event_id');
            $event_query->execute([
                ':event_id' =>$event_id,
            ]);

            $event_response = $event_query->fetchAll();

            $user_query = $this->db->prepare('SELECT * from user where id = :user_id');
            $user_query->execute([
                ':user_id' =>$user_id,
            ]);

            $user_response = $user_query->fetchAll();

            $participant_update_query = $this->db->prepare('UPDATE participants set confirmed = 1 where event_id = :event_id and user_id = :user_id');
            $participant_update_query->execute([
                ':event_id' => $event_id,
                ':user_id' => $user_id
            ]);

            $total_points = $event_response[0]['points'] + $user_response[0]['points'];

            $user_update_query = $this->db->prepare('UPDATE user set points = :points where id = :user_id');
            $user_update_query->execute([
                ':points' => $total_points,
                ':user_id' => $user_id,
            ]);
        }

    }

    public function get_leaderboard(){
        $get_query = $this->db->prepare('SELECT * from user where power=0');
        $get_query->execute();

        $response = $get_query->fetchAll();
        return $response;
    }

    public function is_confirmed($user_id, $event_id){
        $query = $this->db->prepare('SELECT * from participants where user_id = :user_id and event_id = :event_id and confirmed = 1');
        $query->execute([
            ':user_id' => $user_id,
            ':event_id' => $event_id,
        ]);

        $response = $query->fetchAll();
        return $response;
    }

    public function get_prizes(){
        $query = $this->db->prepare('SELECT * from prizes');
        $query->execute();
        return $query->fetchAll();

    }
    
}
