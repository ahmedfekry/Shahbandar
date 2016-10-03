<?php
$target_dir = "../../uploads/space/";
$target_file = $target_dir .time()."_".basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "<script>alert('File is an image - " . $check["mime"] . ".');</script>";
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }
                // (name, governorate,location,email,detailed_address,capacity,amenities,phone_number,picture)

    $name = $_POST['name'];
    $governorate = $_POST['governorate'];
    $location = $_POST['location'];
    $email = ( isset( $_POST['email'] ) ) ? $_POST["email"] : NULL;
    $detailed_address = $_POST["detailed_address"];
    $capacity = $_POST["capacity"];
    $amenities = $_POST["amenities"];
    $phone_number = (isset( $_POST["phone_number"] )) ? $_POST["phone_number"] : NULL;

}
// Check if file already exists
if (file_exists($target_file)) {
    echo "<script>alert('Sorry, file already exists.')</script>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<script>alert('Sorry, your file is too large.')</script>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.')</script>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<script>alert('Sorry, your file was not uploaded.');</script>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // echo "The file has been uploaded.";
        // echo "\n";
        // echo $target_file;
        //mandatories( name,governorate,location,detailed_address,capacity,amenities,phone_number,email,picture)

    $conn = new PDO("mysql:host=localhost;dbname=cospace","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       try {
            $stmt = $conn->prepare("INSERT INTO space (name, governorate,location,detailed_address,capacity,amenities,email,phone_number) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $governorate);
            $stmt->bindParam(3, $location);
            $stmt->bindParam(4, $detailed_address);
            $stmt->bindParam(5, $capacity);
            $stmt->bindParam(6, $amenities);
            $stmt->bindParam(7, $email);
            $stmt->bindParam(8, $phone_number);
            // $stmt->bindParam(9, $file);

            $result = $stmt->execute();
            if ($result != NULL) {
                $file = $conn->lastInsertId()."_space_".time()."_".basename( $_FILES["fileToUpload"]["name"]) ;
                $stmt = $conn->prepare("UPDATE space SET picture=? WHERE id=?");
                $stmt->bindParam(1,$file);
                $stmt->bindParam(2,$conn->lastInsertId());

                $result = $stmt->execute();
                if ($result) {
                    echo "<script> alert('success') </script>";
                    header('location: forms.html');
                    # code...
                }
            }
        } catch(PDOException $e) {
            echo  $e->getMessage();
        }

    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
    }
}
?>