<?php
if(!isSet($_GET['gid']))
  die("Musisz podać ID gry.");

$game = get_game((int)$_GET['gid']);
if($game === null)
  die("Gra o podanym ID nie istnieje.");
$game = $game['gra'];
?>
<div class="container">
  <div class="row">
    <div class="span12">
      <h1 style="margin-bottom: 18px;">Edycja gry &ldquo;<?php echo $game['nazwa_gry']; ?>&rdquo;</h1>
      <p><a href="?page=creator&action=set_primary&gid=<?php echo $game['id_gry']; ?>" 
        class="btn btn-primary">Ustal pytanie startowe</a> lub <a href="?page=creator">powróć na stronę główną</a>.</p>
      <hr style="margin: 2em 0;" />
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <h2>Pytania:</h2>
      <p><a href="?page=creator&action=add_answer&gid=<?php echo $game['id_gry']; ?>" class="btn btn-success">Stwórz pytanie</a></p>
      <hr style="margin: 2em 0;" />
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <h2>Odpowiedzi:</h2>
      <p><a href="?page=creator&action=add_answer&gid=<?php echo $game['id_gry']; ?>" class="btn btn-success">Stwórz odpowiedź</a></p>
    </div>
  </div>
</div>
