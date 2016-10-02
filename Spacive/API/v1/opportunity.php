<?php
    require '../../vendor/autoload.php';
    // require 'models/registered_user.php';
    require_once 'passwordHash.php';

    $app = new \Slim\App;
    // $registerdUser = new RegisteredUser();
    $conn = new PDO("mysql:host=localhost;dbname=cospace","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
     $app->post('/delete', function($request,$response) use ($app){
        global $conn;
        $r = json_decode($request->getBody());
        $id = $r->event->id;

        $result1 = array();
        try {
            $stmt = $conn->prepare("DELETE FROM opportunity WHERE id = :id");
            $stmt->bindParam(':id',$id);

            $stmt->execute();
            $result1['status'] = 'success';
            $result1['message'] = 'record deleted successfully';

        } catch (PDOException $e) {
            $result1['status'] = 'failed';
            $result1['message'] = $r->getMessage();

        }
        return $response->write( json_encode($result1) );

    });

     



    $app->run();




?>