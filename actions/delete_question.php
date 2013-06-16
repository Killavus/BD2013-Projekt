<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['qid']))
	die("Należy podać id pytania, żeby je usunąć.");

$q_id = (int)$_GET['qid'];

if(!isSet($_GET['gid']))
	$game_id = find_game($q_id);
else $game_id = (int)$_GET['gid'];

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit', 'error' => 4, 'gid' => $game_id]);
	return null;
}

$result = question_delete($q_id);
if($result !== null) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 7]);
	return null;
}

redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 4]);
?>
