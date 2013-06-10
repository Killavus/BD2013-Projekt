<?php
function has_modifiable_games($user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 1)
    return false;

  $db = user_database();

  $stmt = $db->prepare("SELECT COUNT(id_gry) FROM gra
                          JOIN uprawnienie USING(id_gry)
                          WHERE uprawnienie.id_uzytkownika = :id
                          AND ranga IN('A', 'T')");

  $stmt->execute([':id' => $id]);

  $result = $stmt->fetchColumn();
  $stmt->closeCursor();

  return $result > 0;
}

function is_game_admin($game, $user = NULL) {
  return _has_rank($game, $user, 'A');
}

function is_game_creator($game, $user = NULL) {
  return _has_rank($game, $user, 'A');
}

function can_modify_game($game, $user = NULL) {
  return _has_rank($game, $user, ['A', 'T']);
}

function _has_rank($game, $user, $rank) {
  $game_id = deduce_game_id($game);
  $user_id = deduce_user_id($user);

  $rank_sql = '';
  if(is_array($rank))
    $rank_sql = ' IN(' . join(', ', $rank) . ')';
  else
    $rank_sql = " = '$rank'";

  if((int)$game_id < 1 or (int)$user_id < 1)
    return false;

  $db = user_database();
  $stmt = $db->prepare("SELECT COUNT(*) FROM uprawnienie 
                          WHERE id_gry = :id_gry
                          AND id_uzytkownika = :id_uzytkownika
                          AND ranga $rank_sql");

  $stmt->execute([':id_gry' => $game_id, ':id_uzytkownika' => $user_id]);

  $res = $stmt->fetchColumn();
  $stmt->closeCursor();

  return $res > 0;
}

function get_modifiable_games($user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 1)
    return [];

  $db = user_database();
  $stmt = $db->prepare("SELECT id_gry, nazwa, ranga, pytanie.* FROM gra
                          JOIN uprawnienie USING(id_gry)
                          JOIN pytanie USING(id_pytania)
                          WHERE id_uzytkownika = :id 
                          AND ranga IN ('A', 'T')");

  $stmt->execute([':id' => $id]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  $games = [];
  foreach($result as $data_row) {
    $games[] = [
      'gra' => only($data_row, ['id_gry', 'nazwa', 'ranga']),
      'pytanie' => except($data_row, ['id_gry', 'nazwa', 'ranga'])
    ];
  }

  return $games;
}
?>
