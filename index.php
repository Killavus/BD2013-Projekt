<?php
  require_once 'src/core.php';
  @include_once 'headers/' . get_page('welcome') . '.php';

  if(isSet($_GET['logout']))
    sign_out();

  function menu_link($page_name, $name) {
    if(isSet($_GET['page']) and $_GET['page'] == $page_name)
      echo '<li class="active"><a href="?page=' . $page_name . '">' .
              $name . '</li>';
    else
      echo '<li><a href="?page=' . $page_name .'">' . $name . '</li>';
  }
?>
<!doctype html>
<html lang="pl">
  <head>
    <title>BD2013 &ndash; Gry Tekstowe</title>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/main.css" />

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/global.js"></script>
  </head>
  <body>
    <div class="navbar navbar-inverse">
      <div class="navbar-inner" style="border-radius: 0">
        <div class="container">
          <a class="brand" href="?page=welcome">Game Maker</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <?php 
              if(signed_in()) {
                menu_link('play', 'Zagraj');
                menu_link('creator', 'Twórz');
                menu_link('settings', 'Ustawienia');
              }
              ?>
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
