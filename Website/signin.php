<?php
if (isset($_COOKIE["login_token"]) && strtoupper($_COOKIE["login_token"]) == "PRHIC")
{
  header("Location: http://abibuch.osscarcvo.de/index.php", true, 303);
  echo 'Redirecting.. <a href="index.php">index</a>';
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Abijahrbuch - Signin</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
        crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
        crossorigin="anonymous"></script>
  </head>
  
  <body>

    <div class="container">

      <form class="form-signin" method="GET" action="signinHandler.php">
        <h2 class="form-signin-heading">Bitte melde dich mit deinem Code an.</h2>
        <div id="error-alert" class="alert alert-danger" style="display: none;">
          ! Diesen Code gibt es nicht.
        </div>
        <label for="inputCode" class="sr-only">Code</label>
        <input type="text" id="inputCode" name="code" class="form-control" placeholder="Code" required autofocus>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="remember" value="me" checked> Angemeldet bleiben
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Los!</button>
      </form>

    </div> <!-- /container -->


    <script>
      if (window.location.search.indexOf("bad") !== -1)
        $('#error-alert').show();
    </script>
  </body>
</html>
