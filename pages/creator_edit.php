<?php 
$game = check_permissions(); 
$game_id = $game['gra']['id_gry'];
$game_name = $game['gra']['nazwa_gry'];
$primary_question_id = $game['pytanie']['id_pytania'];
$help_text_stan = "Możesz zmieniać zmienne (dowolne jakie sobie wymyślisz), np. gold:=300, wood:=wood+50, pod warunkiem,
	że są to zmienne liczbowe.";
$help_text_warunek = "Możesz napisać dowolne wyrażenie, które ma być prawdą/fałszem (0/różne od 0), np. gold>300, gold+wood>500";

$questions = get_questions($game_id);
$answers = get_answers($game_id);
if($answers === null) $answers = [];

function message() {
	if(isSet($_GET['error'])) {
		$errors = [
			1 => "Należy podać nazwę i treść",
			2 => "Nazwa nie może zawierać samych białych znaków",
			3 => "Treść nie może zawierać samych białych znaków",
			4 => "Nie masz uprawnień do modyfikacji tej gry",
			5 => "Pytanie o zadanej nazwie w tej grze już istnieje",
			6 => "Odpowiedź o zadanej nazwie w tej grze już istnieje",
			7 => "Do pytania prawdopodobnie istnieją dowiązania (pytanie startowe lub odpowiedź przenosi do niego)",
			8 => "Pytanie o podanym id w tej grze nie istnieje",
			9 => "Nie udało się załadować pliku",
			10 => "Możesz przesyłać tylko obrazki (pliki .jpg .jpeg .png .gif)",
			11 => "Niespójność transakcji"
		];

		$error_number = (int)$_GET['error'];
		if($error_number == 12)
			$errors[12] = 'Stan: '.get_error_message((int)$_GET['cerror']);

		if($error_number == 13)
			$errors[13] = 'Warunek: '.get_error_message((int)$_GET['cerror']);
		
		$error_message = $errors[$error_number];

		echo "<div class='alert alert-error'>
						<p class='text-center'> <strong>Błąd!</strong> $error_message </p>
					</div>";
	}
	else if(isSet($_GET['success'])){
		$success_m = [
			1 => "Pytanie zostało pomyślnie <strong>dodane</strong>",
			2 => "Odpowiedź została pomyślnie <strong>dodana</strong>",
			3 => "Odpowiedź została pomyślnie <strong>usunięta</strong>",
			4 => "Pytanie zostało pomyślnie <strong>usunięte</strong>",
			5 => "Ustawiono podane pytanie jako startowe"
		];
		
		$succ_message = $success_m[(int)$_GET['success']];

		echo "<div class='alert alert-success'>
						<p class='text-center'> $succ_message </p>
					</div>";
	}
}
?>
<div class="container">
  <div class="row">
    <div class="span12">
      <h1 style="margin-bottom: 18px;">Edycja gry &ldquo;<?php echo $game_name; ?>&rdquo;</h1>
      <p><a href="#" id="set_primary_button" class="btn btn-primary">Ustal pytanie startowe</a>
			lub <a href="?page=creator">powróć na stronę główną</a>.</p>
			<div id="set_primary" style="display:none">
				<div class="radius_border">
					<form class="form-inline" action="actions/game_set_primary.php?gid=<?php echo $game_id; ?>" method="post">
						<select name="primary">
						<?php
						foreach($questions as $quest) {
							$name = $quest['nazwa'];
							$id = $quest['id_pytania'];
							?>
							<option value="<?php echo $id; ?>"> <?php echo $name; ?></option>
							<?php
						}
						?>
						</select>
						<button type="submit" class="btn btn-inverse">Ustaw</button>
					</form>
				</div>
			</div>
      <hr style="margin: 2em 0;" />
    </div>
  </div>
	<div class="row">
		<div class="span12">
			<?php message() ?>
		</div>
	</div>
  <div class="row">
    <div class="span12">
      <h2>Pytania:</h2>
			<button id="add_question_button" type="button" class="btn btn-success"> Stwórz pytanie </button>
			<div id="add_question" style="display: none">
				<div class="radius_border">
				<form enctype="multipart/form-data" action="actions/add_question.php?gid=<?php echo $game_id; ?>" method="post" class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="nazwa"> Nazwa: </label>
						<div class="controls">
							<input id="nazwa" type="text" name="nazwa" class="input-large" placeholder="Nazwa" tabindex=1 />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="zasoby"> Zasoby: </label>
						<div class="controls">
							<input id="zasoby" type="text" name="stan" class="input-large" placeholder="Modyfikowanie zasobów" tabindex=2 />
							<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
								data-content="<?php echo $help_text_stan; ?>" data-original-title="Pomoc">
									<i class="icon-question-sign"> </i></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="warunek"> Warunek: </label>
						<div class="controls">
							<input id="warunek" type="text" name="warunek" class="input-large" placeholder="Warunek na zasoby" tabindex=3 />
							<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
								data-content="<?php echo $help_text_warunek; ?>" data-original-title="Pomoc">
									<i class="icon-question-sign"> </i></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="tresc"> Treść: </label>
						<div class="controls">
							<textarea id="tresc" name="tekst" rows="4" tabindex=4 ></textarea>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<input id="file_button_q" type="button" class="btn btn-small" value="Wybierz obrazek" />
							<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
							<input id="file_hidden_q" name="file" type="file" style="display: none" />
							<span id="file_help_q" class="help-inline"></span>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary"> Dodaj </button>
						</div>
					</div>
				</form>
				</div>
			</div>
      <table class="table">
        <thead>
          <tr>
            <th>Nazwa</th>
            <th>Akcje</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach($questions as $question) {
          $id = $question['id_pytania'];
          $name = $question['nazwa'];
          $row_class = $id == $primary_question_id ? "success" : "";
          ?>
          <tr class="<?php echo $row_class; ?>">
            <td><strong><?php echo $name; ?></strong></td>
            <td>
              <a 
              href="?page=creator&action=edit_component&qid=<?php echo $id; ?>" 
              class="btn btn-primary btn-small">Edytuj</a>
							<?php if($id != $primary_question_id) { ?>
							<a href="actions/delete_question.php?qid=<?php echo $id; ?>&gid=<?php echo $game_id; ?>"
								class="btn btn-danger btn-small">Usuń</a>
							<?php
							} ?>
            </td>
          </tr>
          <?php
        }
        ?>
        </tbody>
      </table>
      <hr style="margin: 2em 0;" />
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <h2>Odpowiedzi:</h2>
			<button id="add_answer_button" class="btn btn-success" type="button"> Stwórz odpowiedź </button>
			<div id="add_answer" style="display: none">
				<?php require_once 'add_answer_form.php'; ?>
			</div>
			<table class="table" style="margin-bottom: 70px">
				<thead>
					<th>Nazwa</th>
					<th>Akcje</th>
				</thead>
				<tbody>
				<?php
				foreach($answers as $ans) {
					$name = $ans['nazwa'];
					$id = $ans['id_odpowiedzi'];
					?>
					<tr>
						<td><strong><?php echo $name; ?></strong></td>
						<td>
							<a href="?page=creator&action=edit_component&ans_id=<?php echo $id; ?>"
								class="btn btn-primary btn-small">Edytuj</a>
							<a href="actions/delete_answer.php?ans_id=<?php echo $id; ?>&gid=<?php echo $game_id; ?>"
								class="btn btn-danger btn-small">Usuń</a>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
    </div>
  </div>
</div>

<script type="text/javascript">
	$("#add_question_button").click(function() {
		$("#add_question").toggle(500);
	});

	$("#add_answer_button").click(function() {
		$("#add_answer").toggle(500);
	});

	$("#set_primary_button").click(function() {
		$("#set_primary").toggle(350);
	});

	$("#file_button_q").click(function() {
		$("#file_hidden_q").click();
	});

	$("#file_hidden_q").bind('change',function() {
		var path = $(this).val().split('\\');
		$("#file_help_q").text(path.pop());
	});
</script>
