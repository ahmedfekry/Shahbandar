<?php
    require '../../vendor/autoload.php';
    // require 'models/registered_user.php';
    require_once 'passwordHash.php';

    $app = new \Slim\App;
    // $registerdUser = new RegisteredUser();
    $conn = new PDO("mysql:host=localhost;dbname=cospace","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION)) {
    session_start();
    }


    
    $app->post('/signUp', function ($request, $response) use ($app){

        global $conn;

        $r = json_decode($request->getBody());
        
        $email =  $r->user->email;
        $full_name =  $r->user->full_name;
        $phone_number =  $r->user->phone_number;
        $password =  $r->user->password;

        $result1 = array();
        try{
            $stmt = $conn->prepare("SELECT 1 FROM `user` WHERE email=:email or phone_number=:phone_number ");
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':phone_number',$phone_number);
            $stmt->execute();

            $isUserExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$isUserExists){
                $password = passwordHash::hash($password);

                $stmt = $conn->prepare("INSERT INTO user (full_name,email,phone_number,password)
                                        VALUES (:full_name, :email, :phone_number,:password)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phone_number);
                $stmt->bindParam(':password', $password);

                $result = $stmt->execute();

                if ($result != NULL) {
                    $result1["status"] = "success";
                    $result1["message"] = "User account created successfully";
                    $result1["uid"] = $conn->lastInsertId();
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    $_SESSION['uid'] = $result["uid"];
                    $_SESSION['full_name'] = $full_name;
                    $_SESSION['email'] = $email;

                    // _sns = name
                    $cookie_name = "_sns";
                    $cookie_value = $full_name;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/"); // 86400 = 1 day

                    // _ses = email
                    $cookie_name = "_ses";
                    $cookie_value = $email;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/"); // 86400 = 1 day

                } else {
                    $result1["status"] = "error";
                    $result1["message"] = "Failed to create customer. Please try again";
                }
            }else{
                $result1["status"] = "error";
                $result1["message"] = "An user with the provided phone or email or username exists!";
            }
        }catch(PDOException $e) {
            $result1["status"] = 'Failed';
            $result1["message"] = $e->getMessage();
        }

//      $result = $registerdUser->sign_up($first_name,$last_name,$username,$email,$phone_number,$password);
        return $response->write( json_encode($result1) );
    });

    $app->post('/signIn',function ($request,$response) use ($app){
        # code...
        global $conn;

        $header = json_decode($request->getBody());
      
        $email = $header->user->email;
        $password = $header->user->password;

        $result1 = array();
        try {
            $stmt = $conn->prepare("SELECT * FROM `user` WHERE email=:email");
            $stmt->bindParam(':email',$email);

            $stmt->execute();

            $isUserExists = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($isUserExists != NULL) {
                if (passwordHash::check_password($isUserExists['password'],$password)) {
                    # code...
                    $result1["status"] = "success";
                    $result1["message"] = "Loging successfully";
                    $result1["uid"] = $isUserExists['id'];
                    $result1["full_name"] = $isUserExists['full_name'];
        
                    $_SESSION['uid'] = $isUserExists["id"];
                    $_SESSION['full_name'] = $isUserExists["full_name"];
                    $_SESSION['email'] = $isUserExists["email"];
                    // session_commit();
                    // $result1['session'] = $_SESSION['uid'];
                    // return $response;
                    // _sns = name
                    $cookie_name = "_sns";
                    $cookie_value = $isUserExists['full_name'];
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/"); // 86400 = 1 day

                    // _ses = email
                    $cookie_name = "_ses";
                    $cookie_value = $isUserExists["email"];
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/"); // 86400 = 1 day

                } else {
                    $result1["status"] = "failed";
                    $result1["message"] =" Wrong password or Email";
                    // return $response;
                }

            } else {
                $result1["status"] = "Failed";
                $result1["message"] = "No such user exists";
                // return $response;
            }
        } catch (PDOException $e) {
            $result1["status"] = $e->getMessage();
        }
        // $result = array('message' => "success");

        return $response->write(json_encode($result1) );
    });

    $app->post('/islogged',function ($request,$response) use ($app){
        $result1 = array();
        if (isset($_SESSION['uid'])) {
            # code...
            $result1['status'] = "success";
        }else{
            $result1['status'] = "Failed";
        }
        return $response->write(json_encode($result1) );
    });


    $app->post('/signOut',function ($request,$response) use ($app){
            if(isset($_SESSION['uid']))
            {
                unset($_SESSION['uid']);
                unset($_SESSION['name']);
                unset($_SESSION['email']);
                if (isset($_COOKIE['_sns'])) {
                    setcookie("_sns", "", time() - 3600);

                }
                if (isset($_COOKIE['_ses'])) {
                    setcookie('_ses',"", time() - 3600);
                }
                
                $msg="Logged Out Successfully...";
            }
            else
            {
                $msg = "Not logged in...";
            }
            $result1 = array('status' => "success",'message' => 'signed out successfully' );
            return $response->write(json_encode($result1) );
    });

    $app->post('/getSession',function ($request,$response) use ($app){
        $result1 = array();
        if(isset($_SESSION['uid']))
        {
            $result1["uid"] = $_SESSION['uid'];
            $result1["full_name"] = $_SESSION['full_name'];
            $result1["email"] = $_SESSION['email'];
        }
        else
        {
            $result1["uid"] = '';
            $result1["name"] = 'Guest';
            $result1["email"] = '';
        }

        return $response->write(json_encode($result1) );

    });

    $app->run();

    //     public function getSession(){
    //         $response = array();
    //         if(isset($_SESSION['uid']))
    //         {
    //             $response["uid"] = $_SESSION['uid'];
    //             $response["name"] = $_SESSION['name'];
    //             $response["email"] = $_SESSION['email'];
    //         }
    //         else
    //         {
    //             $response["uid"] = '';
    //             $response["name"] = 'Guest';
    //             $response["email"] = '';
    //         }
    //         return $response;
    //     }

?>