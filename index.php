<?php
  require_once 'src/core.php';
  @include_once 'headers/' . get_page('register') . '.php';
?>
<!doctype html>
<html lang="pl">
  <head>
    <title>BD2013 &ndash; Gry Tekstowe</title>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/boostrap-responsive.css" />
    <link rel="stylesheet" href="css/main.css" />

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  </head>
  <body>
    <div class="navbar navbar-inverse">
      <div class="navbar-inner" style="border-radius: 0">
        <div class="container">
          <a class="brand" href="#">Game Maker</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="#">Zaloguj się</a></li>
              <li><a href="#">Lista gier</a></li>
              <li><a href="#">Dla Twórców</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php require_once 'pages/' . get_page('register') . '.php'; ?>
  </body>
</html>
