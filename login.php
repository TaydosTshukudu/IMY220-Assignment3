<?php
    // See all errors and warnings
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);

    // Your database details might be different
    $mysqli = mysqli_connect("localhost", "root", "", "dbUser");

    $email = isset($_POST["loginName"]) ? $_POST["loginName"] : false;
    $pass = isset($_POST["loginPassw"]) ? $_POST["loginPassw"] : false;
 
?>

<!DOCTYPE html>
<html>
<head>
    <title>IMY 220 - Assignment 3</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta charset="utf-8" />
    <meta name="author" content="Thato Tshukudu">
    <!-- Replace Name Surname with your name and surname -->
</head>
<body>
    <div class="container">
        <?php
            if($email && $pass){
            
            
                $query = "SELECT * FROM tbUsers WHERE email = '$email' AND password = '$pass'";
                $res = $mysqli->query($query);
                if($row = mysqli_fetch_array($res)){
                    echo     "<table class='table table-bordered mt-3'>
                                <tr>
                                    <td>Name</td>
                                    <td>" . $row['name'] . "</td>
                                <tr>
                                <tr>
                                    <td>Surname</td>
                                    <td>" . $row['surname'] . "</td>
                                <tr>
                                <tr>
                                    <td>Email Address</td>
                                    <td>" . $row['email'] . "</td>
                                <tr>
                                <tr>
                                    <td>Birthday</td>
                                    <td>" . $row['birthday'] . "</td>
                                <tr>
                            </table>";
                
                    echo     "<form enctype='multipart/form-data' action='' method='POST'>
                                <div class='form-group'>
                                    <input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
                                    <input type='hidden' name='loginName' value='$email'/>
                                    <input type='hidden' name='loginPassw' value='$pass'/>
                                    <input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
                                </div>
                              </form>";
                }
                else{
                    echo     '<div class="alert alert-danger mt-3" role="alert">
                                  You are not registered on this site!
                              </div>';
                }
            }
            else{
                echo     '<div class="alert alert-danger mt-3" role="alert">
                              Could not log you in
                          </div>';
            }
        ?>
    </div>
</body>
</html>

<?php

        if(isset($_POST["submit"])){
            
            
                $id = ord($_POST["loginName"]);
            
                //  $filename = $_POST['picToUpload'];
                $target_dir = "gallery/";
                $target_file = $target_dir . basename($_FILES["picToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            
                if(isset($_POST["submit"])) {
                    
                    $check = getimagesize($_FILES["picToUpload"]["tmp_name"]);
                    if($check !== false) {
                       
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }
     
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }
        
                if ($_FILES["picToUpload"]["size"] > 1048576) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
         
                if($imageFileType != "jpg" && $imageFileType != "jpeg") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
            
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
              
                } else {
                    if (move_uploaded_file($_FILES["picToUpload"]["tmp_name"], $target_file)) {
                       
                        
                        //$mysqli = mysqli_connect("localhost", "root", "", "dbgallery");
                        
                        $file = basename( $_FILES["picToUpload"]["name"]);

                       
    
                            $mysqli = mysqli_connect("localhost", "root", "", "dbUser");
                            if ($mysqli->connect_error) {
                                die("Connection failed: " . $mysqli->connect_error);
                            }
                        
                            $query = "INSERT INTO tbgallery (user_id, filename) VALUES( '$id' , '$file')";
                           // $res = $mysqli->query($query);
                        
                            
                     
                                if ($mysqli->query($query) === TRUE) {
                                   
                                } else {
                                    echo "Error: " . $query . "<br>" . $mysqli->error;
                                }

                        //$db = null;
                        
                        
                        $dir = "gallery/";
                        $a = scandir($dir);
                        
                        $sql = "SELECT filename FROM tbgallery WHERE user_id='$id'";
      
                        $result = mysqli_query($mysqli,$sql) or die("MySQL error: " . mysqli_error($mysqli) . "<hr>\nQuery: $sql");
                        
                       
                       
                       echo "<div class='container'>";
                       echo "<h1>Image Gallery </h1>";
                       
                         echo "<div class='row imageGallery'>";
                        
                        
                        while($row = mysqli_fetch_array($result))
                              {
                                 echo "<div class='col-3' style='background-image: url(gallery/".$row['filename'].")'> </div>";
                                
                                //echo "<img src='gallery/".$row['filename']."' height='100' width='100'/><br>\n";
                                  
                              }
                       echo "</div>";
                       echo "</div>";
                             
                        
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            
        }

?>



