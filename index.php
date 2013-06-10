<?php
  require_once 'src/core.php';
  @include_once 'headers/' . get_page('welcome') . '.php';

  if(isSet($_GET['logout']))
    sign_out();
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
              <li><a href="#">Lista gier</a></li>
              <li><a href="#">Dla Twórców</a></li>
            </ul>
						<?php if(signed_in()) { ?>
						<li class="listNoneStyle pull-right navbar-text">
							<a href="index.php?logout=1"> wyloguj </a>
						</li>
						<li class="listNoneStyle pull-right navbar-text">
							<?php printf("Zalogowany jako <strong>%s</strong>", current_user()['nazwa']) ?>
						<?php
						}
						else { ?>
						<li class="listNoneStyle pull-right navbar-text">
							<a href="index.php?page=login"> zaloguj się </a>
						</li>
						<li class="listNoneStyle pull-right navbar-text"> <strong> Niezalogowany </strong> </li>
						<?php
						} ?>
          </div>
        </div>
      </div>
    </div>
    <?php require_once 'pages/' . get_page('welcome') . '.php'; ?>
  </body>
</html>
