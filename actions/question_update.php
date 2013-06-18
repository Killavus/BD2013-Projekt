<?php
chdir('..');
require_once 'src/core.php';

$new_question['id_pytania'] = (int)$_GET['qid'];
$new_question['nazwa'] = $_POST['nazwa'];
$new_question['stan'] = $_POST['stan'];
$new_question['warunek'] = $_POST['warunek'];
$new_question['tekst'] = $_POST['tekst'];
$game_id = get_game_id($new_question['id_pytania'],'P');

if(empty($new_question['nazwa']) or empty($new_question['tekst'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'],'error' => 2]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_question['nazwa'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 3]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_question['tekst'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 4]);
	return null;
}

//Sprawdzenia stanu i warunku - TO DO

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 1]);
	return null;
}

update_question($new_question);
redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'success' => 3]);

?>
