<?php
include "util.php";

$id = checkPW($mysqli, $_COOKIE["login_token"]);

$error = NULL;
if (isset($_GET["error"]))
  $error = $_GET["error"];

if (isset($_GET["question"]) && isset($_GET["answer"])) {
  $q = intval($_GET["question"]);
  if ($q==0)
    $error = "Fehler beim Antworten der Frage :(";
  else {
    $a = $mysqli->real_escape_string($_GET["answer"]);
    if (!$mysqli->query("INSERT INTO `Antworten` (`Antwort`, `Frage_ID`, `Schueler_ID`) VALUES ('$a', '$q', '$id');"))
      $error = "Fehler beim einfügen in die Datenbank";
  }

  header("Location: http://abibuch.osscarcvo.de/index.php" . ($error == NULL ? "" : "?error=" . urlencode($error)), true, 303);
}
elseif (isset($_GET["email"]) || isset($_GET["date"])) {
  if (isset($_GET["date"])) {
    $date = strtotime($_GET["date"]);
    if ($date){
      if (!$mysqli->query("UPDATE Schueler SET Geburtstag=FROM_UNIXTIME($date) WHERE ID=$id"))
        $error = "Fehler beim einfügen in die Datenbank";
    }
    else
      $error = "Datum nicht valide.";
  }
  if (isset($_GET["email"])) {
    $email = $mysqli->real_escape_string($_GET["email"]);
    if (!$mysqli->query("UPDATE Schueler SET Email='$email' WHERE ID=$id"))
      $error = "Fehler beim einfügen in die Datenbank";
  }
  header("Location: http://abibuch.osscarcvo.de/index.php" . ($error == NULL ? "" : "?error=" . urlencode($error)), true, 303);
}

?>

<!DOCTYPE html>
<html lang="en">
<!--
  Solltest du irgendwelche beschwerden wegen der schlampiegen programmierung haben richte dich bitte an idontcare@trashmail.com
-->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Abijahrbuch 2018</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="open-iconic/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="src/pikaday.css">
  <!-- Custom styles for this template -->
  <link href="narrow-jumbotron.css" rel="stylesheet">
</head>

<body>

  <div id="page" class="container">
    <div class="header clearfix">
      <nav>
        <ul class="nav nav-pills float-right">
          <li class="nav-item">
            <a class="nav-link active" href="signout.php">Logout</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="mailto:robin@kock-hamburg.de">Kontakt</a>
          </li>
        </ul>
      </nav>
      <h3 class="text-muted">Abijahrbuch 2018</h3>
    </div>
<?php 
if ($error != NULL)
  echo '<div class="alert alert-danger">'.$error.'</div>';
?>
    <div class="modal fade" id="changeName" tabindex="-1" role="dialog" aria-labelledby="changeNameLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="changeNameLabel">Namen ändern</h4>
          </div>
          <div class="modal-body">
            <p>
              Um deinen Namen zu ändern schicke bitte entweder eine Email an
              <a href="mailto:robin@kock-hamburg.de">robin@kock-hamburg.de</a> oder spreche uns einfach an.
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="uploadPhoto" tabindex="-1" role="dialog" aria-labelledby="uploadPhotoLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="uploadPhotoLabel">Photo Hochladen</h4>
          </div>
          <div class="modal-body">
            <form id="imgSelect" action="upload.php" method="POST" enctype="multipart/form-data">
              <p>
                Clicke auf auswählen um ein Bild hoch zu laden.
              </p>
              <label class="btn btn-primary" id="uploadBtn">
                Auswählen
                <input type="file" id="uploadInput" name="uploadInput" accept="image/*" hidden onchange="$('#uploadPhoto').modal('toggle'); $('#imgSelect')[0].submit(); $('#uploadInput').attr('disabled', 'disabled'); $('#page').attr('hidden', 'hidden'); $('#loading').removeAttr('hidden');">
              </label>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="img-wrapper">
          <img class="col-md-12" src="<?php $imgPath = $mysqli->query("SELECT Bild FROM Schueler WHERE ID=$id")->fetch_assoc()["Bild"]; echo isset($imgPath) ? $imgPath : "img\avatar.png"; ?>" />
          <div class="img-overlay">
            <button class="btn btn-lg" data-toggle="modal" data-target="#uploadPhoto">
              <span class="oi oi-pencil"></span>
            </button>
          </div>
        </div>
      </div>
      <?php $sch = $mysqli->query("SELECT Name, Profil, UNIX_TIMESTAMP(Geburtstag) AS Geb, Email from Schueler WHERE Schueler.ID=$id")->fetch_assoc(); ?>
      <form class="col-md-8" action="index.php" method="GET">
        <h1>
          <span><?php echo $sch["Name"]; ?></span>
          <button style="margin-left: 3em; margin-bottom: 0.5em;" type="button" class="btn btn-sm pull-right" data-toggle="modal" data-target="#changeName">
            <span class="oi oi-pencil"></span>
          </button>
        </h1>
        <div class="form-group">
          <label for="exampleInputEmail">Email address</label>
          <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="max@musterman.de" name="email" value="<?php echo $sch["Email"]; ?>">
          <small id="emailHelp" class="form-text text-muted">Sollte es Fragen oder Änderungen geben schreiben wir dieser E-mail Adresse. Wichtige Infos kommen aber auch in
            den Abi-Chat.</small>
        </div>
        <div class="form-group">
          <label for="profil">Profil</label>
          <input type="text" class="form-control" id="profil" aria-describedby="emailHelp" disabled value="<?php echo $sch["Profil"]; ?>">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Geburtstag</label>
          <input type="text" class="form-control" id="datepicker" required="true" name="date" placeholder="20-4-1968" value="<?php echo date("d-n-Y", $sch["Geb"]); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Speichern</button>
      </form>
    </div> 
    <hr>
    <h2>Fragen:</h2>
    <p>
      Du sollst fragen über dich beantworten. Fülle einfach so viele aus wie du willst. Bitte nach jeder frage auf Speichern clicken.
    </p>
    <div class="row marketing">
<?php 
$result = $mysqli->query("SELECT Fragen.Frage AS Frage, Fragen.ID AS ID, aw.AW AS Antwort FROM Fragen LEFT JOIN ( SELECT Antworten.Frage_ID AS F_ID, Antworten.Antwort AS AW FROM Antworten INNER JOIN ( SELECT Frage_ID, MAX(ErstellungsZeit) AS maxTime FROM Antworten GROUP BY Frage_ID ) ms ON Antworten.Frage_ID = ms.Frage_ID AND Antworten.ErstellungsZeit = maxTime WHERE Antworten.Schueler_ID = $id) aw ON aw.F_ID = Fragen.ID");
while($array = $result->fetch_assoc()) { ?>
      <form class="col-lg-6" style="margin-bottom: 2em;">
        <h5><?php echo $array["Frage"]; ?></h5>
        <input type="hidden" name="question" value="<?php echo $array["ID"]; ?>">
        <div class="input-group">
          <textarea class="col-md-8 form-control" rows="1" name="answer"><?php if($array["Antwort"] != NULL) echo $array["Antwort"]; ?></textarea>
          <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Speichern</button>
          </span>
        </div>
      </form>
<?php 
}
?>
    </div>
    <footer class="footer">
      <p>&copy; Robin Kock 2017</p>
    </footer>

  </div>
  <div id="loading" class="container" hidden>
    <div style="margin-top:5em;" class="row">
      <h3 class="col-md-offset-4 col-md-4">Bild wird hochgeladen</h3>
    </div>
    <div class="row">
      <button class="col-md-offset-5 col-md-2 btn btn-cancel" onclick="window.location.replace('http://abibuch.osscarcvo.de/index.php');">Abbrechen</button>
    </div>
  </div>
  <!-- /container -->

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="src/pikaday.js"></script>
  <script>
    var picker = new Pikaday({
      field: $('#datepicker')[0],
      format: 'D-M-YYYY',
      toString(date, format) {
        // you should do formatting based on the passed format,
        // but we will just return 'D/M/YYYY' for simplicity
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
      },
      parse(dateString, format) {
        // dateString is the result of `toString` method
        const parts = dateString.split('-');
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1] - 1, 10);
        const year = parseInt(parts[1], 10);
        return new Date(year, month, day);
      }
    });
  </script>
</body>

</html>