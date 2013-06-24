<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać id gry, do której ma zostać dodana odpowiedź");

$game_id = (int)$_GET['gid'];
$answer['nazwa'] = $_POST['nazwa'];
$answer['id_pytania'] = $_POST['reference_question'];
$answer['id_pyt_forward'] = $_POST['forward_question'];
$answer['stan'] = $_POST['stan'];
$answer['warunek'] = $_POST['warunek'];
$answer['tresc'] = $_POST['tresc'];

if(empty($answer['nazwa']) or empty($answer['tresc'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 1]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$answer['nazwa'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 2]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$answer['tresc'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 3]);
	return null;
}

if(!can_modify_game($game_id)){
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 4]);
}

$a = get_answer_by_name($answer['nazwa'],$game_id);

if($a !== null) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 6]);
	return null;
}

add_answer($answer,$game_id);																												
redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 2]);
?>
