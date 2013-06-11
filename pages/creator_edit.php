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
      <p><a href="?page=creator&action=add_answer&gid=<?php echo $game_id; ?>" class="btn btn-success">Stwórz pytanie</a></p>
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
