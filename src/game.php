<?php
require_once 'src/user.php';
require_once 'src/database.php';
require_once 'src/utils.php';

require_once 'src/game_creator.php';
require_once 'src/game_player.php';

/* Zwraca grę o podanym ID i początkowe pytanie, lub null jeżeli taka gra nie istnieje. */
function get_game($game) {
  $id = deduce_game_id($game);

  if((int)$id < 1)
    return null;

  $db = user_database();

  $stmt = $db->prepare('SELECT gra.nazwa AS nazwa_gry, pytanie.* FROM gra
                          JOIN pytanie USING(id_pytania)
                          WHERE gra.id_gry = :id');

  $stmt->execute([':id' => $id]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  if($result === false)
    return null;

  return [
    'gra' => only($result, ['id_gry', 'nazwa_gry']),
    'pytanie' => except($result, ['id_gry', 'nazwa_gry'])
  ];
}

function game_exists($name) {
  $db = user_database();

  $stmt = $db->prepare('SELECT COUNT(*) FROM gra WHERE nazwa = :nazwa');

  $stmt->execute([':nazwa' => $name]);

  $result = $stmt->fetchColumn();
  $stmt->closeCursor();

  var_dump($result);

  return $result > 0;
}
?>
