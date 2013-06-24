<?php
function message() {
	if(isSet($_GET['success'])) {
		echo "<div class='alert alert-success'>
						<p class='text-center'> Poprawnie <strong>zaktualizowano</strong> nazwę użytkownika </p>
					</div> ";
	}
}
?>

<div class="well">
	<div class="container">
		<h4>Twoje aktualne dane:</h4>
		<?php message(); ?>
		<form action="actions/update_user.php" method="post" class="form-horizontal" style="margin-top: 30px">
			<div class="control-group">
				<label class="control-label" for="login">Login:</label>
				<div class="controls">
					<input type="text" value="<?php echo $g_user['login']; ?>" disabled >
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="nazwa">Nazwa:</label>
				<div class="controls">
					<input type="text" value="<?php echo $g_user['nazwa']; ?>" name="nazwa" >
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input type="submit" class="btn btn-primary" value="Zapisz" >
				</div>
			</div>
		</form>
	</div>
</div>
