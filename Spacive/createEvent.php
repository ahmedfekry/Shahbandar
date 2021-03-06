<?php
$target_dir = "uploads/";
$target_file = $target_dir .time()."_".basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    $name = $_POST["name"];
    $description = $_POST["description"];
    $link = $_POST["link"];

    

}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
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
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ".time()."_".basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        echo "\n";
        // echo $target_file;
    $conn = new PDO("mysql:host=localhost;dbname=cospace","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       try {
            $stmt = $conn->prepare("INSERT INTO event (name, description, link,picture)
                                       VALUES (:name, :description,:link,:picture)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':link', $link);
            $file = time()."_".basename( $_FILES["fileToUpload"]["name"]);
            $stmt->bindParam(':picture', $file);

            $result = $stmt->execute();
            if ($result != NULL) {
                echo "success";
            }
        } catch(PDOException $e) {
            echo  $e->getMessage();
        }

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>