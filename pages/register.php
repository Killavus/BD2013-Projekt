<?php
function output_box() {
  if(isSet($_GET['success'])) {
    echo "<div class='alert alert-success'>
            <button type='button' class='close' data-dismiss='alert'>
              &times;
            </button>
            <strong>Super!</strong> Twój użytkownik został stworzony.
          </div>";
  }
  else {
    if(isSet($_GET['error'])) {
      $error_codes = [
        1 => "Ten login jest już zajęty.",
        2 => "Nie wszystkie wymagane dane zostały wysłane.",
        3 => "Login jest w niewłaściwym formacie",
        4 => "Podane hasła nie zgadzają się."
      ];

      $error_code = $error_codes[(int)$_GET['error']];

      echo "<div class='alert alert-error'>
              <button type='button' class='close' data-dismiss='alert'>
                &times;
              </button>
              <strong>Błąd!</strong> $error_code
            </div>";
    }
  }
}
?>
<div class="container">
  <h1>Witamy!</h1>
  <?php if(!signed_in()) { ?>
  <p>Aby korzystać z aplikacji, należy się zarejestrować.</p>
  <?php output_box(); ?>
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
        <input type="password" id="new-password" name="user[password]"
          required="required" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new-password-repeat">Powtórz hasło</label>
      <div class="controls">
        <input type="password" id="new-password-repeat" name="password-repeat"
          required="required" />
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <button type="submit"  class="btn btn-primary">Zarejestruj</button>
      </div>
    </div>
  </form>
  <?php }
  else {
    $user = current_user();
  ?>
  <p>Aktualnie jesteś zalogowany jako <?php echo $user['nazwa']; ?>.</p>
  <?php
  }
  ?>
</div>
