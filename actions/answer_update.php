<?php
chdir('..');
require_once 'src/core.php';

$new_answer['id'] = (int)$_GET['ans_id'];
$new_answer['nazwa'] = $_POST['nazwa'];
$new_answer['id_pytania'] = $_POST['id_pytania'];
$new_answer['stan'] = $_POST['stan'];
$new_answer['warunek'] = $_POST['warunek'];
$new_answer['tekst'] = $_POST['tekst'];
$game_id = get_game_id($new_answer['id'],'O');

if(empty($new_answer['nazwa']) or empty($new_answer['tekst'])) {
	redirect_to('creator',['action' => 'edit_component','ans_id' => $new_answer['id'], 'error' => 5]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_answer['nazwa'])) {
	redirect_to('creator',['action' => 'edit_component','ans_id' => $new_answer['id'], 'error' => 3]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_answer['tekst'])) {
	redirect_to('creator',['action' => 'edit_component','ans_id' => $new_answer['id'], 'error' => 4]);
	return null;
}

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit_component','ans_id' => $new_answer['id'], 'error' => 1]);
	return null;
}

if(!empty($new_answer['stan']) && check_assignments($new_answer['stan']) === false) {
	redirect_to('creator',['action' => 'edit_component', 'ans_id' => $new_answer['id'], 'error' => 12, 'cerror' => get_last_error()]);
  return null;
}

if(!empty($new_answer['warunek']) && check_expression($new_answer['warunek']) === false) {
	redirect_to('creator',['action' => 'edit_component', 'ans_id' => $new_answer['id'], 'error' => 13, 'cerror' => get_last_error()]);
	return null;
}


update_answer($new_answer);
redirect_to('creator',['action' => 'edit_component','ans_id' => $new_answer['id'], 'success' => 4]);

?>
