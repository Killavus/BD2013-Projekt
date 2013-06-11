<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_POST['game']))
  die("Brak danych w formularzu.");

$db = creator_database();

$game = $_POST['game'];

// 1. Użytkownik nie podał nazwy gry. 
if(!isSet($game['name'])) {
  redirect_to('creator', ['action' => 'create', 'error' => 1]);
  return null;
}

// Wycinamy tagi HTMLowe.
$name = strip_tags($game['name']);

// 2. Nazwa gry jest niepoprawna.
if(strlen($name) < 3) {
  redirect_to('creator', ['action' => 'create', 'error' => 2]);
  return null;
}

// 3. Gra istnieje.
if(game_exists($name)) {
  redirect_to('creator', ['action' => 'create', 'error' => 3]);
  return null;
}

$id = create_game($name);
if($id === null) {
  redirect_to('creator', ['action' => 'create', 'error' => 4]);
  return null;
}

redirect_to('creator', ['action' => 'edit', 'gid' => $id, 'created' => 1]);
?>
