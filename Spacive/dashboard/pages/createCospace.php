<?php
$target_dir = "../../uploads/";
$target_file = $target_dir .time()."_".basename($_FILES["photo"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
// name, governorate,location,email,detailed_address,capacity,amenities,phone_number

    $name = $_POST["name"];
    $governorate = $_POST["governorate"];
    $location = $_POST["location"];
    $detailed_address = $_POST["detailed_address"];
    $capacity = $_POST["capacity"];
    $amenities = $_POST["amenities"];
    $email = ( isset( $_POST["email"]) ) ? $_POST("email") : NULL;
    $phone_number = (isset($_POST["phone_number"])) ? $_POST("phone_number") : NULL;

}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["photo"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "The file ".time()."_".basename( $_FILES["photo"]["name"]). " has been uploaded.";
        echo "\n";
        // echo $target_file;
        //mandatories( name,governorate,location,detailed_address,capacity,amenities,phone_number,email,picture)

    $conn = new PDO("mysql:host=localhost;dbname=cospace","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       try {
            $stmt = $conn->prepare("INSERT INTO cospace (name, governorate,location,email,detailed_address,capacity,amenities,phone_number,picture)
                                       VALUES (:name, :governorate,:location,:email,:detailed_address,:capacity,amenities,phone_number,:picture)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':governorate', $governorate);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':detailed_address', $detailed_address);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':amenities', $amenities);
            $stmt->bindParam(':phone_number', $phone_number);
            $file = time()."_".basename( $_FILES["photo"]["name"]);
            $stmt->bindParam(':picture', $file);

            $result = $stmt->execute();
            if ($result != NULL) {
                echo "<script> alert('success') </script>";
            }
        } catch(PDOException $e) {
            echo  $e->getMessage();
        }

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>