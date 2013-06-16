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
			<button id="add_question_button" type="button" class="btn btn-success"> Stwórz pytanie </button>
			<div id="add_question" style="display: none">
				<div style="margin: 10px; padding: 20px; border: 1px solid skyblue; -moz-border-radius: 10px; border-radius: 10px; -webkit-border-radius: 10px">
				<form action="actions/add_question.php?gid=<?php echo $game_id; ?>" method="post" class="form-horizontal">
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
			<button id="add_answer_button" class="btn btn-success" type="button"> Stwórz odpowiedź </button>
			<div id="add_answer" style="display: none">
				<div style="margin: 10px; padding: 20px; border: 1px solid skyblue; -moz-border-radius: 10px; border-radius: 10px; -webkit-border-radius: 10px">
					<form action="actions/add_answer.php?gid=<?php echo $game_id; ?>" method="post" class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="ans_nazwa"> Nazwa: </label>
							<div class="controls">
								<input id="ans_nazwa" type="text" name="nazwa" class="input-large" placeholder="Nazwa" tabindex=5 />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="select"> Do pytania: </label>
							<div class="controls">
								<select id="select" name="reference_question" tabindex=6>
									<?php
										foreach($questions as $quest){
											$name = $quest['nazwa'];
											$id = $quest['id_pytania'];
											?>
											<option value="<?php echo $id; ?>"> <?php echo $name; ?> </option>
											<?php
										}
									?>
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="stan"> Stan: </label>
							<div class="controls">
								<input id="stan" type="text" name="stan" class="input-large" placeholder="Stan" tabindex=7 />
								<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
									data-content="Blah" data-original-title="Pomoc"> <i class="icon-question-sign"> </i> </a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="ans_warunek"> Warunek: </label>
							<div class="controls">
								<input id="ans_warunek" type="text" name="warunek" class="input-large" placeholder="Warunek" tabindex=8 />
								<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
									data-content="Blah" data-original-title="Pomoc"> <i class="icon-question-sign"> </i> </a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="answer"> Treść odpowiedzi: </label>
							<div class="controls">
								<textarea id="answer" rows="2" tabindex=9> </textarea>
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
</script>
