<?php
require_once 'src/utils.php';
require_once 'src/database.php';

/* Zwraca odpowiedzi o dla danego pytania. Zwraca null jeżeli takich nie ma. */
function get_answers($game_id,$question_id = null) {
	$db = user_database();
	
	if($question_id === null) {
		$stmt = $db->prepare('SELECT DISTINCT odpowiedz.* FROM odpowiedz
			JOIN pytanie_odpowiedz AS p_o USING(id_odpowiedzi)
			JOIN pytanie ON p_o.id_pytania=pytanie.id_pytania
			WHERE id_gry = :id_gry');
		$stmt->execute([':id_gry' => $game_id]);
	}
	else {
		$stmt = $db->prepare('SELECT DISTINCT odpowiedz.* FROM odpowiedz
			JOIN pytanie_odpowiedz AS p_o USING(id_odpowiedzi)
			JOIN pytanie ON p_o.id_pytania=pytanie.id_pytania
			WHERE id_gry = :id_gry AND id_pytania = :id_pyt');
		$stmt->execute([':id_gry' => $game_id, ':id_pyt' => $question_id]);
	}
	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	if(count($result) < 1)
		return null;
	
	return $result;
}

/* Pobiera odpowiedź o danym ID. */
function get_answer($id) {
	$db = user_database();

	$stmt = $db->prepare('SELECT * FROM odpowiedz WHERE id_odpowiedzi = :id');
	$stmt->execute([':id' => $id]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	return $result[0];
}

/* Pobiera odpowiedź o danej nazwie. */
function get_answer_by_name($name,$game_id) {
	$db = user_database();
	$stmt = $db->prepare('SELECT odpowiedz.* FROM odpowiedz
		JOIN pytanie_odpowiedz AS p_o USING(id_odpowiedzi)
		JOIN pytanie ON p_o.id_pytania=pytanie.id_pytania
		WHERE id_gry = :id_gry AND odpowiedz.nazwa = :nazwa');
	$stmt->execute([':id_gry' => $game_id, ':nazwa' => $name]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if(count($result) < 1)
		return null;
	return $result;
}

/* Dodaje odpowiedź do danej gry. */
function add_answer($ans,$game_id) {
	$db = creator_database();

	$ans['nazwa'] = htmlspecialchars($ans['nazwa']);
	$ans['tresc'] = htmlspecialchars($ans['tresc']);

	try {
		$db->beginTransaction();

		$ans_insert = $db->prepare('INSERT INTO odpowiedz(id_pytania,nazwa,tekst,warunek,stan)
			VALUES(:id_pyt_forward,:nazwa,:tekst,:warunek,:stan) RETURNING id_odpowiedzi');
		$ans_insert->execute([':id_pyt_forward' => $ans['id_pyt_forward'], ':nazwa' => $ans['nazwa'], ':tekst' => $ans['tresc'],
			':warunek' => $ans['warunek'], ':stan' => $ans['stan']]);
		$ans_id = $ans_insert->fetchColumn();
		$ans_insert->closeCursor();

		$quest_ans_insert = $db->prepare('INSERT INTO pytanie_odpowiedz(id_pytania,id_odpowiedzi)
			VALUES(:id_pyt,:id_odp)');
		$quest_ans_insert->execute([':id_pyt' => $ans['id_pytania'], ':id_odp' => $ans_id]);
		$quest_ans_insert->closeCursor();

		$db->commit();
	}
	catch(PDOException $exc) {
		echo $exc->getMessage();
		$db->rollBack();
		return null;
	}

	return $ans_id;
}

/* Usuwa odpowiedź o danym ID. */
function answer_delete($ans_id) {
	$db = creator_database();

	$stmt = $db->prepare('SELECT * FROM odpowiedz WHERE id_odpowiedzi = :id_ans');
	$stmt->execute([':id_ans' => $ans_id]);
	$result = $stmt->fetchAll();

	if($result === false)
		return false;

	$stmt = $db->prepare('DELETE FROM odpowiedz WHERE id_odpowiedzi = :id_ans');
	$stmt->execute([':id_ans' => $ans_id]);

	return true;
}

/* Aktualizuje daną odpowiedź. */
function update_answer($ans) {
	$db = creator_database();

	$ans['nazwa'] = htmlspecialchars($ans['nazwa']);
	$ans['tekst'] = htmlspecialchars($ans['tekst']);

	$stmt = $db->prepare('UPDATE odpowiedz SET (id_pytania,nazwa,tekst,warunek,stan)
		= (:id_pyt,:nazwa,:tekst,:warunek,:stan) WHERE id_odpowiedzi = :id');
	$stmt->execute([':id' => $ans['id'], ':id_pyt' => $ans['id_pytania'], ':nazwa' => $ans['nazwa'],
		':tekst' => $ans['tekst'], ':warunek' => $ans['warunek'], ':stan' => $ans['stan']]);
	$stmt->closeCursor();
}

/* Usuwa odpowiedź z danego pytania. */
function delete_ans_question($qid,$ans_id) {
	$db = creator_database();

	$stmt = $db->prepare('DELETE FROM pytanie_odpowiedz WHERE id_pytania = :id_pyt AND id_odpowiedzi = :id_odp');
	$stmt->execute([':id_pyt' => $qid, ':id_odp' => $ans_id]);
	$stmt->closeCursor();
}

/* Dodaje odpowiedź do danego pytania. */
function add_ans_question($qid,$ans_id) {
	$db = creator_database();

	$stmt = $db->prepare('INSERT INTO pytanie_odpowiedz(id_pytania,id_odpowiedzi)
		VALUES(:id_pyt,:id_odp)');
	$stmt->execute([':id_pyt' => $qid, ':id_odp' => $ans_id]);
	$stmt->closeCursor();
}

?>
