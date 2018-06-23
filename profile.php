<?php
    // http://makitweb.com/upload-and-store-an-image-in-the-database-with-php/
    // Initialize the session
    require_once('functions.php');
    $user = session_check();
    if (!isset($user['id'])) {
        header("location: index.php");
        exit;
    }
    $host = gethostname();
    require_once "{$host}config.php";

    if (isset($_POST['but_upload'])) {
        $name = $_FILES['file']['name'];
        $target_dir = "avatars/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        // Select file type
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         // Valid file extensions
        $extensions_arr = array("jpg","jpeg","png","gif");
        // Check extension
        if( in_array($imageFileType,$extensions_arr) ){
            // $query = "insert into images(name) values('".$user['username']."') ON DUPLICATE KEY UPDATE";
            // mysqli_query($db,$query);


            $usrname = $user['username'];
            //INSERT 
            $dbMaster = mysqli_connect(DB_SERVER_MASTER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

           if($dbMaster === false){
              die("ERROR(dbMaster): Could not connect. " . mysqli_connect_error());
              exit;
           } 
 
	    $query = " INSERT INTO images ( name, extension )  VALUES ( '$usrname', '$imageFileType' ) "; 
            $result = mysqli_query($dbMaster, $query); 

            if( $result )
            {
                echo 'Success';
                // Upload file
                move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$user['username']);
            }
            else
            {
                echo 'Query Failed';
            }

           
        }
    }


    if (isset($_POST['testBut'])) {
	    $dbSlave = mysqli_connect(DB_SERVER_SLAVE,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	    
	    $sql = sprintf("select name from images where name='%s'",mysqli_real_escape_string($dbSlave, $user['username']));

        $result = mysqli_query($dbSlave,$sql);
        $row = mysqli_fetch_array($result);

        $image = (is_null($row)) ? "default" : $row['name'];
	    $image_src = "avatars/".$image;
	    // echo $image_src;
        // echo '<img src="'.$image_src.'" height="100" width="100">';
    }
  

    
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Settings</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">

        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <?PHP show_menu($user); ?>
    <form method="post" action="" enctype='multipart/form-data'>
        <input type='file' name='file' />
        <input type='submit' value='Save name' name='but_upload'>
        <input type='submit' value='Show smthing' name='testBut'>
    </form>
    <?PHP echo '<img src="'.$image_src.'" height="100" width="100">'; ?>
</body>
</html>
