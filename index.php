<?PHP
        require_once('session.php');
        $logged=check_session();
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js">
</script>
</head>
<body>
<?PHP show_menu($logged); ?>
Hello <?PHP echo $user['username']; ?>! This is my website.
</body>
</html>
