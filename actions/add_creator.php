<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']) or !isSet($_GET['uid']))
	die("Należy podać ID gry oraz użytkownika, aby określić współtwórcę gry");

$game_id = (int)$_GET['gid'];
$user_id = (int)$_GET['uid'];

if(!is_game_admin($game_id)) {
	redirect_to('creator',['action' => 'add_user', 'gid' => $game_id, 'error' => 1]);
	return null;
}

_set_rank($user_id, $game_id, 'T');
redirect_to('creator',['action' => 'add_user', 'gid' => $game_id, 'success' => 1]);

?>
