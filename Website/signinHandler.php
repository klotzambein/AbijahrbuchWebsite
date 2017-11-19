<?php 
include "util.php";

$mysqli = connect2DB();

if (isset($_GET["code"]) && strtoupper($_GET["code"]) == "CNIUL") {
    setcookie("login_token", strtoupper($_GET["code"]), isset($_GET["remember"]) ? time() + (86400 * 356) : 0, "/");
    header("Location: http://abibuch.osscarcvo.de/index.php", true, 303);
}
else {
    header("Location: http://abibuch.osscarcvo.de/signin.php?bad", true, 303);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    Redirecting...
    <script>
        window.location.replace("http://abibuch.osscarcvo.de/index.php");
    </script>
</body>
</html>