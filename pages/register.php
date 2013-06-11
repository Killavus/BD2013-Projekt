<?php
function output_box() {
  if(isSet($_GET['success'])) {
    echo "<div class='alert alert-success'>
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
              <strong>Błąd!</strong> $error_code
            </div>";
    }
  }
}
?>
<div class="well">
<div class="container">
  <?php if(!signed_in()) { 
	output_box(); ?>
	<div class="text-center" style="margin-bottom: 40px">
		<h3> Rejestracja </h3>
	</div>
	<div class="span6 offset2">
  <form action="actions/register.php" method="post" class="form-horizontal">
    <div class="control-group">
      <label class="control-label" for="new-username">Nazwa użytkownika</label>
      <div class="controls">
        <input type="text" id="new-username" name="user[name]" 
          placeholder="Domyślnie login" tabindex=1/>
					<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
						data-content="Nazwa będzie widoczna dla innych użytkowników"
							data-original-title="Pomoc"><i class="icon-question-sign"></i></a>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new-login">Login</label>
      <div class="controls">
        <input type="text" id="new-login" name="user[login]"
          required="required" pattern="[a-zA-Z0-9_\-\.]{3,}" tabindex=2/>
					<a href=# class="setPopover" data-toggle="popover" data-placement="right"
						data-content="Używaj tylko małych i dużych liter, cyfr, kropek oraz myślników. Login musi zawierać przynajmniej 3 znaki."
							data-original-title="Pomoc"> <i class="icon-question-sign"></i> </a>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new-password">Hasło</label>
      <div class="controls">
        <input type="password" id="new-password" name="user[password]"
          required="required" tabindex=3/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="new-password-repeat">Powtórz hasło</label>
      <div class="controls">
        <input type="password" id="new-password-repeat" name="password-repeat"
          required="required" tabindex=4/>
      </div>
    </div>
    <div class="control-group">
      <div class="controls">
        <button type="submit"  class="btn btn-primary">Zarejestruj</button>
      </div>
    </div>
  </form>
	</div>
  <?php }
  else {
    $user = current_user();
  ?>
  <p>Aktualnie jesteś zalogowany jako <?php echo $user['nazwa']; ?>.</p>
  <?php
  }
  ?>
</div>
</div>
