<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać ID gry");

$game_id = (int)$_GET['gid'];
$word = isSet($_GET['word']) ? $_GET['word'] : '';
$users = search_users($word,$game_id);

echo json_encode($users);

?>
