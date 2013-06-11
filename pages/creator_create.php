<?php
function output_box() {
  if(isSet($_GET['error'])) {
    $error_codes = [
      1 => "Nazwa gry nie została podana.",
      2 => "Nazwa gry jest niepoprawna.",
      3 => "Gra o takiej nazwie już istnieje.",
      4 => "Nie udało się stworzyć gry."
    ];

    $error_code = $error_codes[(int)$_GET['error']];

    echo "<div class='alert alert-error'>
            <strong>Błąd!</strong> $error_code
          </div>";
  }
}
?>
<div class="well">
  <div class="container">
    <div class="row">
      <div class="span12">
        <h1>Stwórz grę</h1>
        <p>Stworzenie gry jest niesamowicie proste. Wystarczy wybrać
          nazwę gry (nie może być taka sama jak inna nazwa gry) i gotowe!</p>
        <?php output_box(); ?>
        <form action="actions/create_game.php" method="post" 
          class="form-horizontal">
          <div class="control-group">
            <label class="control-label" for="new-name">Nazwa gry</label>
            <div class="controls">
              <input type="text" id="new-name" name="game[name]" 
                     required="required" minlength="3" tabindex="1" />
              <a href="#" class="setPopover" data-toggle="popover" 
                  data-placement="right" 
                  data-content="Nazwa musi zawierać co najmniej 3 znaki." 
                  data-original-title="Pomoc"><i class="icon-question-sign"></i></a>
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <button type="submit" 
                      class="btn btn-primary">Stwórz grę!</button> 
              lub <a href="?page=creator">powróć na stronę główną</a>.
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
