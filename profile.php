<?php
    // Initialize the session
    session_start();
    require_once('session.php');
    $logged = check_session();
    require_once('menu.php');
    $host = gethostname();
    require_once "{$host}config.php";

    if (isset($_POST['but_upload'])) {
        $name = $_FILES['file']['name'];
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        // Select file type
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         // Valid file extensions
        $extensions_arr = array("jpg","jpeg","png","gif");

        // Check extension
        if( in_array($imageFileType,$extensions_arr) ){
            $query = "insert into images(name) values('".$name."')";
            mysqli_query($db,$query);

            // Upload file
            move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
        }
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
    <?PHP show_menu($logged); ?>
    <form method="post" action="" enctype='multipart/form-data'>
        <input type='file' name='file' />
        <input type='submit' value='Save name' name='but_upload'>
    </form>
</body>
</html>
