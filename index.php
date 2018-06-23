<?PHP
        //we dont need here check
        require_once('functions.php');
	$user=session_check();
	require_once('menu.php');
?>
<html>
<head>
        <title>Home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"></script>
        <style type="text/css">
           body{ font: 14px sans-serif; text-align: center; }
        </style>
</head>
<body>
<?PHP show_menu($user); ?>
Hello <?PHP echo $user['username']; ?>! This is my website.
</body>
</html>
