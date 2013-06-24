<!-- potrzebne: $game_id, $questions -->

<?php
$q_edit = isSet($_GET['qid']) ? (int)$_GET['qid'] : -1;
$help_text_stan = "Możesz zmieniać zmienne (dowolne jakie sobie wymyślisz), np. gold:=300, wood:=wood+50, pod warunkiem,
	że są to zmienne liczbowe.";
$help_text_warunek = "Możesz napisać dowolne wyrażenie, które ma być prawdą/fałszem (0/różne od 0), np. gold>300, gold+wood>500";
?>

<div class="radius_border">
	<form enctype="multipart/form-data" action="actions/add_answer.php?gid=<?php echo $game_id; ?>" method="post" class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="ans_nazwa"> Nazwa: </label>
			<div class="controls">
				<input id="ans_nazwa" type="text" name="nazwa" class="input-large" placeholder="Nazwa" tabindex=5 />
			</div>
		</div>
		<div class="control-group" <?php if($q_edit > 0) echo 'style="display: none"'; ?> >
			<label class="control-label" for="select"> Do pytania: </label>
			<div class="controls">
				<select id="select" name="reference_question" tabindex=6 >
					<?php
						foreach($questions as $quest){
							$name = $quest['nazwa'];
							$id = $quest['id_pytania'];
							?>
							<option value="<?php echo $id; ?>" <?php if($id == $q_edit) echo "selected"; ?> > <?php echo $name; ?> </option>
							<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="select2"> Przenosi do: </label>
			<div class="controls">
				<select id="select2" name="forward_question">
				<?php
					foreach($questions as $quest) {
						$name = $quest['nazwa'];
						$id = $quest['id_pytania'];
						?>
						<option value="<?php echo $id; ?>"> <?php echo $name; ?> </option>
						<?php
					}
				?>
				</select>
				<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
					data-content="To pole określa, do którego pytania zostanie przekierowany użytkownik po wybraniu tej
						odpowiedzi" data-original-title="Pomoc"> <i class="icon-question-sign"> </i> </a>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="stan"> Stan: </label>
			<div class="controls">
				<input id="stan" type="text" name="stan" class="input-large" placeholder="Stan" tabindex=7 />
				<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
					data-content="<?php echo $help_text_stan; ?>" data-original-title="Pomoc"> <i class="icon-question-sign"> </i> </a>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="ans_warunek"> Warunek: </label>
			<div class="controls">
				<input id="ans_warunek" type="text" name="warunek" class="input-large" placeholder="Warunek" tabindex=8 />
				<a href="#" class="setPopover" data-toggle="popover" data-placement="right"
					data-content="<?php echo $help_text_warunek; ?>" data-original-title="Pomoc"> <i class="icon-question-sign"> </i> </a>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="answer"> Treść odpowiedzi: </label>
			<div class="controls">
				<textarea id="answer" name="tresc" rows="2" tabindex=9></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary"> Dodaj </button>
			</div>
		</div>
	</form>
</div>
