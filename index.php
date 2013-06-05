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
    <div class="container">
      <h1>Witamy!</h1>
      <p>Aby korzystać z aplikacji, należy się zarejestrować.</p>
      <form action="actions/register.php" method="post" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="new-username">Nazwa użytkownika</label>
          <div class="controls">
            <input type="text" id="new-username" name="user[name]" 
              placeholder="Domyślnie jak login" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="new-login">Login</label>
          <div class="controls">
            <input type="text" id="new-login" name="user[login]"
              required="required" pattern="[a-zA-Z0-9_\-\.]{3,}" />
            <span class="help-block">Małe, duże litery, cyfry oraz . i -. Od 3 znaków.</span>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="new-password">Hasło</label>
          <div class="controls">
            <input type="text" id="new-password" name="user[password]"
              required="required" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="new-password-repeat">Powtórz hasło</label>
          <div class="controls">
            <input type="text" id="new-password-repeat" name="password-repeat"
              required="required" />
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-primary">Zarejestruj</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>
