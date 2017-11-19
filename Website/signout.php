<?php 
setcookie("login_token", "", time() - 3600);
header("Location: http://abibuch.osscarcvo.de/signin.php", true, 303);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    You are now signed out;
</body>
</html>