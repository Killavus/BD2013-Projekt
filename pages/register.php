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
