<?php

function message(){
	if(isSet($_GET['error'])) {
		echo "<div class='alert alert-error'>
						<p class='text-center'> <strong>Błąd!</strong> Nie masz uprawnień do określania współtwórców tej gry </p>
					</div>";
	}
	else if(isSet($_GET['success'])) {
		echo "<div class='alert alert-success'>
						<p class='text-center'> Pomyślnie <strong>dodano</strong> współtwórcę </p>
					</div>";
	}
}

if(!isSet($_GET['gid']))
	die("Należy podać ID gry, aby dodać do niej współtwórcę");

$game = get_game((int)$_GET['gid']);

?>

<div class="container">
	<h3 class="page-header">Wybierz swojego współtwórcę gry "<?php echo $game['gra']['nazwa_gry']; ?>"
		<small><a href="?page=creator&action=edit&gid=<?php echo $game['gra']['id_gry']; ?>">przejdź do edycji</a></small></h3>
	<?php message(); ?>
	<div class="pull-right">
		<input type="text" class="search-query search_input" search-connection="users" placeholder="Szukaj">
	</div>
	<table id="users" class="table table-striped search_table" filter=1 game_id=<?php echo $game['gra']['id_gry']; ?>>
		<thead>
			<tr>
				<th>ID</th>
				<th>Nazwa</th>
				<th>Akcje</th>
			</tr>
		</thead>
		<tbody search="ajax/user_filter.php">
		</tbody>
	</table>
</div>
<script type="text/javascript" src="js/search.js"></script>
