<?php

function message(){
	if(isSet($_GET['error'])) {
		$errors = [
			1 => "Nie masz uprawnień do modyfikacji tej gry",
			2 => "Nazwa i treść pytania nie mogą być puste",
			3 => "Nazwa nie może składać się z samych białych znaków",
			4 => "Treść nie może składać się z samych białych znaków",
			5 => "Nazwa i treść odpowiedzi nie mogą być puste"
		];

		$text = $errors[(int)$_GET['error']];

		echo "<div class='alert alert-error'>
						<p class='text-center'> <strong>Błąd!</strong> $text </p>
					</div>";
	}
	else if(isSet($_GET['success'])) {
		$success = [
			1 => "Pomyślnie <strong>usunięto</strong> powiązanie",
			2 => "Pomyślnie <strong>dodano</strong> powiązanie",
			3 => "<strong>Zaktualizowano</strong> dane pytania",
			4 => "<strong>Zaktualizowano</strong> dane odpowiedzi"
		];

		$text = $success[(int)$_GET['success']];

		echo "<div class='alert alert-success'>
						<p class='text-center'> $text </p>
					</div> ";
	}
}

function referenced($ans_id,$ans) {
	foreach($ans as $answer)
		if($answer['id_odpowiedzi'] === $ans_id)
			return true;
	return false;
}

if(!isSet($_GET['qid']) and !isSet($_GET['ans_id']))
	die("Musisz podać id pytania albo id odpowiedzi");

$what = isSet($_GET['qid']) ? 'P' : 'O'; // informacja czy edytujemy pytanie czy odpowiedź
$id = $what == 'P' ? (int)$_GET['qid'] : (int)$_GET['ans_id'];
$game_id = get_game_id($id,$what);
$questions = get_questions($game_id);
$quest = $what == 'P' ? get_question($id) : null;
$answers_in_game = get_answers($game_id);
$answer = $what == 'O' ? get_answer($id) : null;

?>

<div class="container">
	<div class="page-header">
		<h3>
			Edycja <?php echo $what == 'P' ? "pytania <i>\"".$quest['pytanie']['nazwa']."\"</i>" : "odpowiedzi "; ?>
			<small> <a href="?page=creator&action=edit&gid=<?php echo $game_id; ?>">powrót</a> </small>
		</h3>
	</div>
	<?php message(); ?>
	<?php if($what == 'P') { ?>
		<div class="radius_border">
			<form action="actions/question_update.php?qid=<?php echo $id; ?>" method="post" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="nazwa"> Nazwa: </label>
					<div class="controls">
						<input id="nazwa" type="text" name="nazwa" value="<?php echo $quest['pytanie']['nazwa']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="stan"> Zasoby: </label>
					<div class="controls">
						<input id="stan" name="stan" type="text" value="<?php echo $quest['pytanie']['stan']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="warunek"> Warunek: </label>
					<div class="controls">
						<input id="warunek" name="warunek" type="text" value="<?php echo $quest['pytanie']['warunek']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="tekst"> Treść: </label>
					<div class="controls">
						<textarea id="tekst" name="tekst" rows="4"><?php echo $quest['pytanie']['tekst']; ?></textarea>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary"> Zapisz </button>
					</div>
				</div>
			</form>
		</div>
		<div style="margin-top: 30px">
			<h4>Odpowiedzi do pytania "<?php echo $quest['pytanie']['nazwa']; ?>"</h4>
			<table class="table">
				<thead>
					<th>Nazwa</th>
					<th>Treść</th>
					<th>Akcje</th>
				<thead>
				<tbody>
					<?php
					foreach($quest['odpowiedzi'] as $ans) {
						$nazwa = $ans['nazwa'];
						$text = $ans['tekst'];
						?>
						<tr>
							<td><?php echo $nazwa; ?></td>
							<td><?php echo $text; ?></td>
							<td>
								<a href="?page=creator&action=edit_component&ans_id=<?php echo $ans['id_odpowiedzi']; ?>"
									class="btn btn-primary btn-small">Edytuj</a>
								<a href="actions/delete_ans_quest.php?qid=<?php echo $id; ?>&ans_id=<?php echo $ans['id_odpowiedzi']; ?>"
									class="btn btn-danger btn-small">Odepnij</a>
							</td>
						</tr>
						<?php
					} ?>
				</tbody>
			</table>
		</div>
		<div class="radius_border" style="float: right">
			<form action="actions/add_ans_quest.php?qid=<?php echo $id; ?>" class="form-inline" method="post">
				Podepnij odpowiedź:
				<select name="answer">
					<?php
						foreach($answers_in_game as $answer) {
							$id_ans = $answer['id_odpowiedzi'];
							$name = $answer['nazwa'];
							if(referenced($id_ans,$quest['odpowiedzi'])) continue;
							?>
							<option value="<?php echo $id_ans; ?>"><?php echo $name; ?></option>
							<?php
						}
					?>
				</select>
				<button class="btn btn-inverse" type="submit"> Podepnij </button>
			</form>
		</div>
	<?php
	}
	else { ?>
		<div class="radius_border">
			<form action="actions/answer_update.php?ans_id=<?php echo $id; ?>" method="post" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="ans_nazwa">Nazwa:</label>
					<div class="controls">
						<input id="ans_nazwa" name="nazwa" type="text" value="<?php echo $answer['nazwa']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ans_reference">Przenosi do:</label>
					<div class="controls">
						<select name="id_pytania">
							<?php
							foreach($questions as $q) {
								$q_id = $q['id_pytania'];
								$q_nazwa = $q['nazwa'];
								?>
								<option value="<?php echo $q_id; ?>"
									<?php if($q_id === $answer['id_pytania']) echo "selected='selected'"; ?>>
									<?php echo $q_nazwa; ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ans_stan">Stan:</label>
					<div class="controls">
						<input id="ans_stan" type="text" name="stan" value="<?php echo $answer['stan']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ans_warunek">Warunek:</label>
					<div class="controls">
						<input id="ans_warunek" type="text" name="warunek" value="<?php echo $answer['warunek']; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="ans_tekst">Treść:</label>
					<div class="controls">
						<textarea id="ans_tekst" name="tekst" cols=3><?php echo $answer['tekst']; ?></textarea>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Zapisz</button>
					</div>
				</div>
			</form>
		</div>
	<?php
	//print_r($answer);
	//echo "<br />";
	//print_r($questions);
	} ?>
</div>
