<?php
function message() {
	if(!isSet($_GET['deleted'])) return;

	if($_GET['deleted'] == 1)
		echo "<div class='alert alert-success'>
						<p class='text-center'> Gra została pomyślnie <strong>usunięta</strong>.</p>
					</div>";

	else if($_GET['deleted'] == 0)
		echo "<div class='alert alert-error'>
						<p class='text-center'> <strong>Błąd!</strong> Nie masz uprawnień do usunięcia tej gry. </p>
					</div>";
}
?>

<div class="well">
	<div class="container">
		<?php message() ?>
		<p> <a href="?page=creator">Wróć</a> do spisu gier. </p>
	</div>
</div>
