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
            $stmt = $conn->prepare("DELETE FROM event WHERE id = :id");
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

    $app->post('/create', function ($request, $response) use ($app){

        global $conn;

        $r = json_decode($request->getBody());
        
        $name =  $r->event->name;
        $description =  $r->event->description;
        $link =  $r->event->link;
        // $picture =  $r->event->picture;
        $result1 = array();            
        try {
                $stmt = $conn->prepare("INSERT INTO event (name, description, link)
                                        VALUES (:name, :description,:link)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':link', $link);
                $result = $stmt->execute();
                if ($result != NULL) {
                    $result1["status"] = "success";
                    $result1["message"] = "event created successfully";
                    $result1["auction_id"] = $conn->lastInsertId();
                } else {
                    $result1["status"] = "failed";
                    $result1["message"] = "Failed to create event. Please try again";
                }
            } catch(PDOException $e) {
                $result1["status"] = 'Failed';
                $result1["message"] = $e->getMessage();
        }

        return $response->write( json_encode($result1) );
    });

    $app->get('/getAllEvents', function($request,$response) use ($app){
        global $conn;
        $result = array();
        try {
            $stmt = $conn->prepare("SELECT * FROM event");
            $stmt->execute();
            $result['status'] = 'success';
            $result['message'] = 'events retrived successfully';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                # code...
                 $result[$row['id']] = $row;
            }
        } catch (PDOException $e) {
            $result['status'] = "failed";
            $result['message'] = "failed to retrive records";
        }
        return $response->write(json_encode($result));

    });

    $app->run();




?>