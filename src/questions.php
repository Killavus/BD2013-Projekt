<?php
require_once 'src/utils.php';
require_once 'src/database.php';

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
                               JOIN obrazek USING(id_obrazka)
                               JOIN pytanie_odpowiedz USING(id_pytania)
                               JOIN odpowiedz USING(id_odpowiedzi)
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
    $result_set['odpowiedzi'][] = only($row, ['id_odpowiedzi', 
                                              'nazwa',
                                              'tekst',
                                              'warunek',
                                              'stan']);
  }
  $stmt->closeCursor();
  return $result_set;
}

// wyszukuje pytanie o zadanej nazwie w odpowiedniej grze (zakładam, że nazwy mogą się powtarzać, o ile są w różnych grach)
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

function add_question($question,$game_id,$user = null) {
	$user_id = deduce_user_id($user);

	$db = creator_database();
	$stmt = $db->prepare('INSERT INTO pytanie(id_gry,id_uzytkownika,nazwa,tekst,stan,warunek)
		VALUES(:id_gry,:id_uzytkownika,:nazwa,:text,:stan,:warunek)');
	$stmt->execute([':id_gry' => $game_id, ':id_uzytkownika' => $user_id, ':nazwa' => $question['nazwa'],
		':text' => $question['tekst'], ':stan' => $question['stan'], ':warunek' => $question['warunek']]);
}
?>
