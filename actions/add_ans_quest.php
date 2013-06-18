<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['qid']) or !isSet($_POST['answer']))
	die("Należy podać id pytania i odpowiedzi");

$qid = (int)$_GET['qid'];
$ans_id = (int)$_POST['answer'];
$game_id = get_game_id($qid,'P');

if(!can_modify_game($game_id)){
	redirect_to('creator',['action' => 'edit_component', 'qid' => $qid, 'error' => 1]);
	return null;
}

add_ans_question($qid,$ans_id);
redirect_to('creator',['action' => 'edit_component', 'qid' => $qid, 'success' => 2]);

?>
