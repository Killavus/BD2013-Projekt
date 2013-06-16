<?php
function message_box(){
	if(isSet($_GET['error'])){
		$error_codes = [
			1 => "Błędny format loginu.",
			2 => "Login nie może być pusty.",
			3 => "Niepoprawne dane logowania."
		];

		$error_code = $error_codes[(int)$_GET['error']];

		echo "<div class='alert alert-error'>
						<p class='text-center'><strong>Błąd!</strong> $error_code </p>
					</div>";
	}
}
?>

<div class="well">
	<?php
	if(!signed_in()){
		message_box() ?>
		<div class="container">
			<div class="span5 offset3 text-center" style="padding-bottom: 20px">
				<h3>Logowanie <small>zaloguj się i dołącz do nas!</small></h3>
			</div>
			<div class="span5 offset2">
				<form action="actions/login.php" method="post" class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="login">Login</label>
						<div class="controls">
							<input type="text" id="login" name="login" placeholder="Login">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Hasło</label>
						<div class="controls">
							<input type="password" id="passwd" name="password" placeholder="Hasło">
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary"> Zaloguj </button>
							lub
							<a href="?page=register"> Zarejestruj się </a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
		}
		else { ?>
			<div class="text-center">
				<p>Jesteś już <strong>zalogowany</strong>. Możesz zagrać w grę lub stworzyć własną.</p>
			</div>
		<?php
		} ?> 
</div>
