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
  $id = deduce_question_id();

  $db = user_database();

  $stmt = $db->prepare('SELECT id_pytania, 
                               nazwa AS nazwa_pytania, 
                               warunek AS warunek_pytania,
                               stan AS stan_pytania,
                               tekst AS tekst_pytania,
                               odpowiedz.*,
                               obrazek.*
                               FROM pytanie
                               JOIN obrazek USING(id_obrazka)
                               JOIN pytanie_odpowiedz USING(id_pytania)
                               JOIN odpowiedz USING(id_odpowiedzi)
                               WHERE id_pytania = :id');

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
?>
