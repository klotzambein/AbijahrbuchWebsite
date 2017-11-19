<?php
function connect2DB(){
    include 'private.php';
    $mysqli = new mysqli("localhost", $mysqli_username, $mysqli_password, $mysqli_db, 3306);
    $mysqli->set_charset('utf8');
    return $mysqli;
}

function checkPW(&$mysqli, $pw) {
    if (isset($pw) && ctype_upper($pw))
    {
      $mysqli = connect2DB();
      $res = $mysqli->query("SELECT ID FROM Schueler WHERE Schueler.Passwort='$pw'");
      if ($res->num_rows > 0) {
          return $res->fetch_assoc()["ID"];
      }
      header("Location: http://abibuch.osscarcvo.de/signout.php", true, 303);
      echo 'Redirecting.. <a href="signout.php">signout</a>';
      die();
      return;
    }
    header("Location: http://abibuch.osscarcvo.de/signin.php", true, 303);
    echo 'Redirecting.. <a href="signin.php">signin</a>';
    die();
    return;
}
?>