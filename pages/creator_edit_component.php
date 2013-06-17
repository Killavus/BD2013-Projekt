<?php

if(!isSet($_GET['qid']) and !isSet($_GET['ans_id']))
	die("Musisz podać id pytania albo id odpowiedzi");

$what = isSet($_GET['qid']) ? 'P' : 'O'; // informacja czy edytujemy pytanie czy odpowiedź
$id = $what == 'P' ? (int)$_GET['qid'] : (int)$_GET['ans_id'];
$game_id = get_game_id($id,$what);
$questions = get_questions($game_id);
$quest = $what == 'P' ? get_question($id) : null;
//$answer = $what == 'O' ? get_answer($id) : null;

?>

<div class="container">
	<div class="page-header">
		<h3>Edycja <?php echo $what == 'P' ? "pytania <i>\"".$quest['pytanie']['nazwa']."\"</i>" : "odpowiedzi "; ?></h3>
	</div>
	<?php if($what == 'P') { ?>
		<div style="margin: 10px; padding: 20px; border: 1px solid skyblue; -moz-border-radius: 10px; border-radius: 10px; -webkit-border-radius: 10px">
			<form action="actions/question_update.php" method="post" class="form-horizontal">
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
						<textarea id="tekst" name="tekst" rows="4"> <?php echo $quest['pytanie']['tekst']; ?> </textarea>
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
	<?php
	}
	else { ?>

	<?php
	} ?>
</div>
