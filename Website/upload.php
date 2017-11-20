<?php
include "util.php";

var_dump($_FILES);
die();

$id = checkPW($mysqli, $_COOKIE["login_token"]);

function redirect($loc) {
    header("Location: http://abibuch.osscarcvo.de/$loc", true, 303);
    echo "Redirecting to <a href=\"http://abibuch.osscarcvo.de/$loc\">$loc</a>";
    die();
}

if(!isset($_FILES["uploadInput"]))
    redirect("index.php?error=".urlencode("Bitte eine Datei auswählen."));


$mime = getimagesize($_FILES["uploadInput"]["tmp_name"])["mime"];
if($mime != "image/png" && $mime != "image/jpeg" && $mime != "image/gif" && $mime != "image/bmp")
    redirect("index.php?error=".urlencode("Keine Bilddatei (png, jpeg, gif oder bmp)."));

// Check file size
if ($_FILES["uploadInput"]["size"] > 5000000) //5MB
    redirect("index.php?error=".urlencode("Bilddatei ist zu groß maximale größe 5MB. Sollte dein Bild größer sein benutze entweder JPEG oder kontaktiere uns."));

    
$oldImage = $mysqli->query("SELECT Bild FROM Schueler WHERE ID=$id")->fetch_assoc()["Bild"];
if($oldImage != NULL)
    unlink($oldImage);
    
$imgExtension = substr($mime, 6);
$target_file = "uploads/$id.$imgExtension";

if (move_uploaded_file($_FILES["uploadInput"]["tmp_name"], $target_file)) {
    if ($mysqli->query("UPDATE Schueler SET Bild='$target_file' WHERE ID=$id")!==TRUE)
        redirect("index.php?error=".urlencode("Fataler Fehler beim Hochladen der Datei."));
    redirect("index.php");
} else {
    redirect("index.php?error=".urlencode("Fehler beim Hochladen der Datei."));
}

?>
