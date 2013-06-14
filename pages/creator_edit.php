<?php 
$game = check_permissions(); 
$game_id = $game['gra']['id_gry'];
$game_name = $game['gra']['nazwa_gry'];
$primary_question_id = $game['pytanie']['id_pytania'];

$questions = get_questions($game_id);
?>
<div class="container">
  <div class="row">
    <div class="span12">
      <h1 style="margin-bottom: 18px;">Edycja gry &ldquo;<?php echo $game_name; ?>&rdquo;</h1>
      <p><a href="?page=creator&action=set_primary&gid=<?php echo $game_id; ?>" 
        class="btn btn-primary">Ustal pytanie startowe</a> lub <a href="?page=creator">powróć na stronę główną</a>.</p>
      <hr style="margin: 2em 0;" />
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <h2>Pytania:</h2>
      <!-- <p><a href="?page=creator&action=add_answer&gid=<?php echo $game_id; ?>" class="btn btn-success">Stwórz pytanie</a></p> -->
			<button id="add_question_button" type="button" class="btn btn-success"> Stwórz pytanie </button>
			<div id="add_question" style="display: none">
				<form action="action/add_question.php" class="form-horizontal">
				<div style="margin: 10px; padding: 20px; border: 1px solid skyblue; -moz-border-radius: 10px; border-radius: 10px; -webkit-border-radius: 10px;">
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
								data-content="Wskazówki do napisania poprawnego warunku (nie wiem jakie póki co)" data-original-title="Pomoc">
									<i class="icon-question-sign"> </i></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="warunek"> Warunek: </label>
						<div class="controls">
							<input id="warunek" type="text" name="warunek" class="input-large" placeholder="Warunek na zasoby" tabindex=3 />
							<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
								data-content="Wskazówki do napisania poprawnego warunku (nie wiem jakie póki co)" data-original-title="Pomoc">
									<i class="icon-question-sign"> </i></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="tresc"> Treść: </label>
						<div class="controls">
							<textarea id="tresc" name="tekst" rows="4" tabindex=4 > </textarea>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary"> Dodaj </button>
						</div>
					</div>
				</div>
				</form>
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
              href="?page=creator&action=edit_question&qid=<?php echo $id; ?>" 
              class="btn btn-primary btn-small">Edytuj</a>
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
      <p><a href="?page=creator&action=add_answer&gid=<?php echo $game_id; ?>" 
        class="btn btn-success">Stwórz odpowiedź</a></p>
    </div>
  </div>
</div>

<script type="text/javascript">
	$("#add_question_button").click(function() {
		$("#add_question").toggle(500);
	});
</script>
