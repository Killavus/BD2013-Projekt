<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać id gry, do której ma być dodane pytanie");

$game_id = (int)$_GET['gid'];
$question['nazwa'] = $_POST['nazwa'];
$question['stan'] = $_POST['stan'];
$question['warunek'] = $_POST['warunek'];
$question['tekst'] = $_POST['tekst'];

if(empty($question['nazwa']) or empty($question['tekst'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 1]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$question['nazwa'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 2]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$question['tekst'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 3]);
	return null;
}

//tutaj zapewne jeszcze powinno się pojawić sprawdzenie poprawności warunku i stanu.

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 4]);
	return null;
}

$q = get_question_by_name($question['nazwa'],$game_id);

if($q !== null) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 5]);
	return null;
}	

add_question($question,$game_id);
redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 1]);
?>
