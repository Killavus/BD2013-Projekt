<?php
require_once 'src/utils.php';
require_once 'src/database.php';

/* Pobiera id pytań i ich nazwy dla danej gry. 
   Zwraca tablicę z tymi danymi. W przypadku błędu będzie to []. */
function get_questions($game) {
  $id = deduce_game_id($game);
  if((int)$id < 1)
    return [];

  $db = user_database();

  $stmt = $db->prepare('SELECT id_pytania, nazwa FROM pytanie 
                          WHERE id_gry = :id');
  $stmt->execute([':id' => $id]);

  $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  return $questions;
}

/* Pobiera pojedyńcze pytanie z bazy danych. Zwraca null, gdy nie powiedzie się pobieranie pytania. */
function get_question($question) {
  $id = deduce_question_id($question);

  $db = user_database();

  $stmt = $db->prepare('SELECT pytanie.id_pytania, 
                               pytanie.nazwa AS nazwa_pytania, 
                               pytanie.warunek AS warunek_pytania,
                               pytanie.stan AS stan_pytania,
                               pytanie.tekst AS tekst_pytania,
                               odpowiedz.*,
                               obrazek.*
                               FROM pytanie
                               LEFT JOIN obrazek USING(id_obrazka)
                               LEFT JOIN pytanie_odpowiedz USING(id_pytania)
                               LEFT JOIN odpowiedz USING(id_odpowiedzi)
                               WHERE pytanie.id_pytania = :id');

  $stmt->execute([':id' => $id]);

  $result_set = ['pytanie' => [], 'odpowiedzi' => []];
  
  $data_set = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if(count($data_set) < 1)
    return null;

  $result_set['pytanie']['id_pytania'] = $data_set[0]['id_pytania'];
  $result_set['pytanie']['nazwa'] = $data_set[0]['nazwa_pytania'];
  $result_set['pytanie']['stan'] = $data_set[0]['stan_pytania'];
  $result_set['pytanie']['warunek'] = $data_set[0]['warunek_pytania'];
  $result_set['pytanie']['tekst'] = $data_set[0]['tekst_pytania'];
  $result_set['pytanie']['src'] = $data_set[0]['src'];
  $result_set['pytanie']['alt'] = $data_set[0]['alt'];

  foreach($data_set as $row) {
		if($row['id_odpowiedzi'] !== null) {
    	$result_set['odpowiedzi'][] = only($row, ['id_odpowiedzi', 
																								'id_pytania',
                                              	'nazwa',
                                              	'tekst',
                                              	'warunek',
                                              	'stan']);
		}
  }
  $stmt->closeCursor();
  return $result_set;
}

// Wyszukuje pytanie o zadanej nazwie w odpowiedniej grze (zakładam, że nazwy mogą się powtarzać, o ile są w różnych grach)
function get_question_by_name($q_name,$game_id) {
	$db = user_database();

	$stmt = $db->prepare('SELECT * FROM pytanie WHERE nazwa = :q_name AND id_gry = :game_id');
	$stmt->execute([':q_name' => $q_name, ':game_id' => $game_id]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	if(count($result) < 1){
		return null;
	}
	return $result;
}

/* Dodaje pytanie do danej gry. */
function add_question($question,$game_id,$user = null) {
	$user_id = deduce_user_id($user);
	$number = null;

	$question['nazwa'] = htmlspecialchars($question['nazwa']);
	$question['tekst'] = htmlspecialchars($question['tekst']);

	$db = creator_database();

	if($question['image'] === false) {
		$stmt = $db->prepare('INSERT INTO pytanie(id_gry,id_uzytkownika,nazwa,tekst,stan,warunek)
			VALUES(:id_gry,:id_uzytkownika,:nazwa,:text,:stan,:warunek)');
		$stmt->execute([':id_gry' => $game_id, ':id_uzytkownika' => $user_id, ':nazwa' => $question['nazwa'],
			':text' => $question['tekst'], ':stan' => $question['stan'], ':warunek' => $question['warunek']]);
		$stmt->closeCursor();

		return null;
	}
	else {
		try {
			$db->beginTransaction();
	
			$stmt = $db->prepare('INSERT INTO obrazek(src) VALUES(:source) RETURNING id_obrazka');
			$stmt->execute([':source' => "image"]);
			$number = $stmt->fetchColumn();
			$stmt->closeCursor();
	
			$stmt = $db->prepare('UPDATE obrazek SET src = :source WHERE id_obrazka = :id');
			$stmt->execute([':source' => "img/image".$number.".".$question['extension'], ':id' => $number]);
			$stmt->closeCursor();
	
			$stmt = $db->prepare('INSERT INTO pytanie(id_gry,id_uzytkownika,nazwa,tekst,stan,warunek,id_obrazka)
				VALUES(:id_gry,:id_uzytkownika,:nazwa,:text,:stan,:warunek,:id_obrazka)');
			$stmt->execute([':id_gry' => $game_id, ':id_uzytkownika' => $user_id, ':nazwa' => $question['nazwa'],
				':text' => $question['tekst'], ':stan' => $question['stan'], ':warunek' => $question['warunek'], ':id_obrazka' => $number]);
			$stmt->closeCursor();
	
			$db->commit();
		}
		catch(PDOException $exc) {
			echo $exc->getMessage();
			$db->rollBack();
		}

		return $number;
	}
}

/* Usuwa pytanie o danym ID. */
function question_delete($q_id) {
	$db = creator_database();

	try {
		$db->beginTransaction();

		$del_ans_connections = $db->prepare('DELETE FROM pytanie_odpowiedz WHERE id_pytania = :id_pyt');
		$del_ans_connections->execute([':id_pyt' => $q_id]);
		$del_ans_connections->closeCursor();

		$del_question = $db->prepare('DELETE FROM pytanie WHERE id_pytania = :id_pyt');
		$del_question->execute([':id_pyt' => $q_id]);
		$del_question->closeCursor();

		$db->commit();
	}
	catch(PDOException $exc) {
		$db->rollBack();
		return $exc->getMessage();
	}

	return null;
}

/* Aktualizuje pytanie - wysyłamy cały hash reprezentujący pytanie. */
function update_question($new) {
	$db = creator_database();
	$number = null;

	$new['nazwa'] = htmlspecialchars($new['nazwa']);
	$new['tekst'] = htmlspecialchars($new['tekst']);
	
	if($new['image'] === false) {
		$stmt = $db->prepare('UPDATE pytanie SET (nazwa,stan,tekst,warunek) = (:nazwa,:stan,:tekst,:warunek) WHERE id_pytania = :id_pyt');
		$stmt->execute([':id_pyt' => $new['id_pytania'], ':nazwa' => $new['nazwa'],
			':stan' => $new['stan'], ':tekst' => $new['tekst'], ':warunek' => $new['warunek']]);
		$stmt->closeCursor();
	}
	else {
		try {
			$db->beginTransaction();
			
			$stmt = $db->prepare('INSERT INTO obrazek(src) VALUES(:source) RETURNING id_obrazka');
			$stmt->execute([':source' => "image"]);
			$number = $stmt->fetchColumn();
			$stmt->closeCursor();
	
			$stmt = $db->prepare('UPDATE obrazek SET src = :source WHERE id_obrazka = :id');
			$stmt->execute([':source' => "img/image".$number.".".$new['extension'], ':id' => $number]);
			$stmt->closeCursor();

			$stmt = $db->prepare('UPDATE pytanie SET (nazwa,stan,tekst,warunek,id_obrazka) = (:nazwa,:stan,:tekst,:warunek,:id_obrazka)
				WHERE id_pytania = :id_pyt');
			$stmt->execute([':id_pyt' => $new['id_pytania'], ':nazwa' => $new['nazwa'],
				':stan' => $new['stan'], ':tekst' => $new['tekst'], ':warunek' => $new['warunek'], ':id_obrazka' => $number]);
			$stmt->closeCursor();

			$db->commit();
		}
		catch(PDOException $exc) {
			echo $exc->message();
			$db->rollBack();
			return null;
		}

		return $number;
	}
}
?>
