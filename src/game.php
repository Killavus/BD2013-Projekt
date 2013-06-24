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

// usuwa grę o zadanym id. Zwraca true - jeśli usunie, false - jeśli nie ma takiej gry
function game_delete($game_id) {
	$game_id = (int)$game_id; // nie jestem pewna, czy mogę tak zrobić i czy to jest sensowne
	
	$db = creator_database();

	$stmt = $db->prepare('SELECT * FROM gra WHERE id_gry = :id_gry');
	$stmt->execute([':id_gry' => $game_id]);

	$result = $stmt->fetchAll();
	if($result === false) return false;

	$stmt = $db->prepare('DELETE FROM gra WHERE id_gry = :id_gry');
	$stmt->execute([':id_gry' => $game_id]);

	return true;
}

function change_primary_question($game_id, $question_id) {
	$db = creator_database();

	$stmt = $db->prepare('UPDATE gra SET id_pytania = :id_pyt WHERE id_gry = :id_gry');
	$stmt->execute([':id_pyt' => $question_id, ':id_gry' => $game_id]);
	$stmt->closeCursor();
}

// funkcja zwracająca id gry na podstawie id pytania bądź id odpowiedzi
function get_game_id($id,$base) { // base = 'P' (pytanie) lub 'O' (odpowiedź)
	$db = user_database();
	$result = null;

	if($base == 'P'){
		$stmt = $db->prepare('SELECT id_gry FROM pytanie WHERE id_pytania = :id_pyt');
		$stmt->execute([':id_pyt' => $id]);
		$result = $stmt->fetchColumn();
		$stmt->closeCursor();
	}
	else {
		$stmt = $db->prepare('SELECT id_gry FROM odpowiedz
			JOIN pytanie_odpowiedz AS p_o USING(id_odpowiedzi)
			JOIN pytanie ON pytanie.id_pytania = p_o.id_pytania
			WHERE id_odpowiedzi = :id_odp');
		$stmt->execute([':id_odp' => $id]);
		$result = $stmt->fetchColumn();
		$stmt->closeCursor();
	}

	return $result;
}

function search_games($word) {
	$new_word = "%".$word."%";
	$db = user_database();

	$stmt = $db->prepare('SELECT id_gry,gra.nazwa AS nazwa_gry,u.login,u.nazwa FROM gra
		JOIN uprawnienie USING(id_gry)
		JOIN uzytkownik AS u USING(id_uzytkownika)
		WHERE gra.nazwa LIKE :name');
	$stmt->execute([':name' => $new_word]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	return $result;
}
?>
