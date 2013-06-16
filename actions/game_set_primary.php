<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać id gry, aby zmienić jej pytanie startowe");

$game_id = (int)$_GET['gid'];
$question_id = (int)$_POST['primary'];
$question_exists = false;

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 4]);
	return null;
}

$questions = get_questions($game_id);

foreach($questions as $q)
	if($q['id_pytania'] == $question_id)
		$question_exists = true;

if(!$question_exists) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 8]);
	return null;
}

change_primary_question($game_id,$question_id);
redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 5]);
?>
