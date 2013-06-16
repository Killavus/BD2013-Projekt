<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać id gry, do której ma być dodane pytanie");

$game_id = $_GET['gid'];
$question['nazwa'] = $_POST['nazwa'];
$question['stan'] = $_POST['stan'];
$question['warunek'] = $_POST['warunek'];
$question['tekst'] = $_POST['tekst'];

if($question['nazwa'] == "" or $question['tekst'] == "")
	redirect_to('creator',['action' => 'edit', 'error' => 1]);

//tutaj zapewne jeszcze powinno się pojawić sprawdzenie poprawności warunku i stanu.

$q = get_question_by_name($question['nazwa'],$game_id);

if($q !== null)
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 2]);
	
if(!can_modify_game($game_id))
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 3]);

add_question($question,$game_id);
redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 1]);
?>
