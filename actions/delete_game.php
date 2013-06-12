<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Brak danych w formularzu");

$game_id = (int)$_GET['gid'];

if(can_modify_game($game_id)) {
	if(!game_delete($game_id)) $success = false;
	else $success = true;
}
else $success = false;

redirect_to('creator',['action' => 'delete', 'deleted' => $success ? 1 : 0]);
?>
