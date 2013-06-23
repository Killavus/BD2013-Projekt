<?php
function has_modifiable_games($user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 1)
    return false;

  $db = user_database();

  $stmt = $db->prepare("SELECT COUNT(*) FROM gra
                          JOIN uprawnienie USING(id_gry)
                          WHERE id_uzytkownika = :id
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

  $db = user_database();
  $rank_sql = '';
  if(is_array($rank)) {
    $rank = array_map([$db, 'quote'], $rank);
    $rank_sql = ' IN(' . join(', ', $rank) . ')';
  }
  else
    $rank_sql = " = '$rank'";

  if((int)$game_id < 1 or (int)$user_id < 1)
    return false;

  $stmt = $db->prepare("SELECT COUNT(*) FROM uprawnienie 
                          WHERE id_gry = :id_gry
                          AND id_uzytkownika = :id_uzytkownika
                          AND ranga $rank_sql");

  $stmt->execute([':id_gry' => $game_id, ':id_uzytkownika' => $user_id]);

  $res = $stmt->fetchColumn();
  $stmt->closeCursor();

  return $res > 0;
}

function _set_rank($user_id, $game_id, $rank) {
	if(!in_array($rank,array('G','T','A')))
		return false;
	
	$db = creator_database();

	$stmt = $db->prepare('DELETE FROM uprawnienie
		WHERE id_uzytkownika = :id_uz AND id_gry = :id_gry');
	$stmt->execute([':id_uz' => $user_id, ':id_gry' => $game_id]);
	$stmt->closeCursor();

	$stmt_insert = $db->prepare('INSERT INTO uprawnienie(id_uzytkownika,id_gry,ranga)
		VALUES(:id_uz,:id_gry,:rank)');
	$stmt_insert->execute([':id_uz' => $user_id, ':id_gry' => $game_id, ':rank' => $rank]);
	$stmt->closeCursor();
	
	return true;
}

function get_modifiable_games($user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 1)
    return [];

  $db = user_database();
  $stmt = $db->prepare("SELECT gra.id_gry, gra.nazwa AS nazwa_gry, ranga, pytanie.* FROM gra
                          JOIN uprawnienie USING(id_gry)
                          JOIN pytanie USING(id_pytania)
                          WHERE uprawnienie.id_uzytkownika = :id 
                          AND ranga IN ('A', 'T')");

  $stmt->execute([':id' => $id]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  $games = [];
  foreach($result as $data_row) {
    $games[] = [
      'gra' => only($data_row, ['id_gry', 'nazwa_gry', 'ranga']),
      'pytanie' => except($data_row, ['id_gry', 'nazwa_gry', 'ranga'])
    ];
  }

  return $games;
}

function create_game($name, $user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 0)
    return null;

  $game_id = null;
  $db = creator_database();
  try {
    $db->beginTransaction();
    $game_insert = $db->prepare('INSERT INTO gra(nazwa) VALUES(:nazwa) 
      RETURNING id_gry');

    $game_insert->execute([':nazwa' => $name]);
    
    $game_id = $game_insert->fetchColumn();
    $game_insert->closeCursor();

    $question_insert = $db->prepare("INSERT INTO 
        pytanie(id_gry, id_uzytkownika, nazwa, tekst)
        VALUES(:id_gry, :id_uzytkownika, 'START', 'Zmodyfikuj mnie!') 
        RETURNING id_pytania");

    $question_insert->execute([':id_gry' => $game_id, 
                               ':id_uzytkownika' => $id]);

    $question_id = $question_insert->fetchColumn();
    $question_insert->closeCursor();

    $game_update = $db->prepare('UPDATE gra SET id_pytania = :id_pytania 
        WHERE id_gry = :id_gry');

    $game_update->execute([':id_pytania' => $question_id, 
                           ':id_gry' => $game_id]);

    $game_update->closeCursor();

    $permission_insert = $db->prepare("INSERT INTO 
                                       uprawnienie(id_uzytkownika, 
                                                  id_gry, ranga)
                                       VALUES(:id_uzytkownika,
                                              :id_gry,
                                              'A')");

    $permission_insert->execute([':id_uzytkownika' => $id,
                                 ':id_gry' => $game_id]);

    $db->commit();
  }
  catch(PDOException $pdo) {
    echo $pdo->getMessage();
    $db->rollBack();
    return null;
  }

  return $game_id;
}

function check_permissions() {
  if(!isSet($_GET['gid']))
    die("Musisz podać ID gry.");

  $game = get_game((int)$_GET['gid']);
  if($game === null)
    die("Gra o podanym ID nie istnieje.");

  if(!can_modify_game($game))
    die("Nie możesz edytować tej gry.");

  return $game;
}
?>
