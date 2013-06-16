<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['ans_id']))
	die("Aby usunąć pytanie należy podać jego id");

$game_id = (int)$_GET['gid'];
$id = (int)$_GET['ans_id'];

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 4]);
	return null;
}

answer_delete($id);
redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 3]);
?>
