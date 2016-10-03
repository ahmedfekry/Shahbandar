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
        $id = $r->space->id;

        $result1 = array();
        try {
            $stmt = $conn->prepare("DELETE FROM space WHERE id = :id");
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

    $app->post('/getSpace', function($request,$response) use ($app){
        global $conn;
        $r = json_decode($request->getBody());
        $id = $r->space->id;

        $result1 = array();
        try {
            $stmt = $conn->prepare("SELECT * FROM space WHERE id=:id");
            $stmt->bindParam(':id',$id);

            $stmt->execute();
            $temp = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($temp != NULL) {
                $result1["status"] = "success";
                $result1["message"] = "space is retrived successfully";
                $result1["space"] = $temp;
            }else{
                $result1["status"] = "failed";
                $result1["message"] = "space is not found";
            }

        } catch (PDOException $e) {
            $result1['status'] = 'failed';
            $result1['message'] = $r->getMessage();

        }
        return $response->write( json_encode($result1) );

    });




    $app->post('/getSpaces',function ($request,$response) use ($app){
        global $conn;

        $r = json_decode($request->getBody());

        $result = array();
        try {
            if (isset($r->space->location) and !isset($r->space->governorate)) {
            
                $stmt = $conn->prepare("SELECT * FROM space WHERE location = :location");
                $stmt->bindParam(':location',$r->space->location);
                
            
            }elseif (!isset($r->space->location) and isset($r->space->governorate)) {
            
                $stmt = $conn->prepare("SELECT * FROM space WHERE governorate = :governorate");
                $stmt->bindParam(':governorate',$r->space->governorate);
               
            }elseif (isset($r->space->location) and isset($r->space->governorate)) {
            
                // echo "string";
                $stmt = $conn->prepare("SELECT * FROM space WHERE (location = :location AND governorate = :governorate)");
                $stmt->bindParam(':location',$r->space->location);
                $stmt->bindParam(':governorate',$r->space->governorate);
                
            }else{
                $stmt = $conn->prepare("SELECT * FROM space ");
            }
            if ($stmt->execute()) {
                $result['status'] = 'success';
                $result['message'] = 'events retrived successfully';
                $array = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $array[] = $row;
                }
                    $result['spaces'] =  $array;
            }
            
        } catch (PDOException $e) {
            $result['status'] = 'failed';
            $result['message'] = $e->getMessage();
        }

        return $response->write(json_encode($result));
    });



    $app->post('/create', function ($request, $response) use ($app){

        global $conn;

        $r = json_decode($request->getBody());
        

        // required 
        $name =  $r->space->name;
        $governorate = $r->space->governorate;
        $location = $r->space->location;
        $detailed_address =  $r->space->detailed_address;
        $phone_number = $r->space->phone_number;
        $capacity_per_room = $r->space->capacity_per_room;

        //optional
        $additional_information = ( isset($r->space->additional_information) ) ? $r->space->additional_information : NULL;
        $email = ( isset($r->space->email) ) ? $r->space->email : NULL;
        $longitude = ( isset($r->space->longitude) ) ? $r->space->longitude : NULL;
        $latitude = ( isset($r->space->latitude) ) ? $r->space->latitude : NULL;
        $picture = ( isset($r->space->picture) ) ? $r->space->picture : NULL;
        $price = ( isset($r->space->price) ) ? $r->space->price : NULL;


        $result1 = array();            
        try {

                $stmt = $conn->prepare("INSERT INTO space (name,governorate,location,detailed_address, phone_number,capacity_per_room,additional_information,email,longitude,latitude,picture,price)
                                        VALUES (:name,:governorate,:location,:detailed_address,:phone_number,:capacity_per_room,:additional_information,:email,:longitude,:latitude,:picture,:price)");
                // required
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':governorate', $governorate);
                $stmt->bindParam(':location', $location);
                $stmt->bindParam(':detailed_address', $detailed_address);
                $stmt->bindParam(':phone_number', $phone_number);
                $stmt->bindParam(':capacity_per_room', $capacity_per_room);
                // optional
                $stmt->bindParam(':additional_information', $additional_information);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':longitude', $longitude);
                $stmt->bindParam(':latitude', $latitude);
                $stmt->bindParam(':picture', $picture);
                $stmt->bindParam(':price', $price);

                $result = $stmt->execute();
                if ($result != NULL) {
                    $result1["status"] = "success";
                    $result1["message"] = "space created successfully";
                    $result1["space_id"] = $conn->lastInsertId();
                } else {
                    $result1["status"] = "failed";
                    $result1["message"] = "Failed to create space. Please try again";
                }
            } catch(PDOException $e) {
                $result1["status"] = 'Failed';
                $result1["message"] = $e->getMessage();
        }

        return $response->write( json_encode($result1) );
    });



    $app->get('/getSpacesName',function ($request,$response) use ($app){
        global $conn;

        $r = json_decode($request->getBody());

        $result = array();
        try {
            
            $stmt = $conn->prepare("SELECT id,name FROM space ");
            
            if ($stmt->execute()) {
                $result['status'] = 'success';
                $result['message'] = 'spaces retrived successfully';
                $array = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $array[] = $row;
                }
                    $result['spaces'] =  $array;
            }
            
        } catch (PDOException $e) {
            $result['status'] = 'failed';
            $result['message'] = $e->getMessage();
        }

        return $response->write(json_encode($result));
    });



    $app->run();




?>