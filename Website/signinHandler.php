<?php 
include "util.php";

if (isset($_GET["code"]) && ctype_upper(trim(strtoupper($_GET["code"])))) {
    $mysqli = connect2DB();
    $res = $mysqli->query("SELECT ID FROM Schueler WHERE Schueler.Passwort='".trim(strtoupper($_GET["code"]))."'");
    if ($res->num_rows > 0) {
        setcookie("login_token", trim(strtoupper($_GET["code"])), isset($_GET["remember"]) ? time() + (86400 * 356) : 0, "/");
        header("Location: http://abibuch.osscarcvo.de/index.php", true, 303);
    }
    else {
        header("Location: http://abibuch.osscarcvo.de/signin.php?bad", true, 303);
    }
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