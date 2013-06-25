<?php
chdir('..');
require_once 'src/core.php';

$new_question['id_pytania'] = (int)$_GET['qid'];
$new_question['nazwa'] = $_POST['nazwa'];
$new_question['stan'] = $_POST['stan'];
$new_question['warunek'] = $_POST['warunek'];
$new_question['tekst'] = $_POST['tekst'];
$game_id = get_game_id($new_question['id_pytania'],'P');

if(empty($new_question['nazwa']) or empty($new_question['tekst'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'],'error' => 2]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_question['nazwa'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 3]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$new_question['tekst'])) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 4]);
	return null;
}

if(!empty($new_question['stan']) && check_assignments($new_question['stan']) === false) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 12, 'cerror' => get_last_error()]);
  return null;
}

if(!empty($new_question['warunek']) && check_expression($new_question['warunek']) === false) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 13, 'cerror' => get_last_error()]);
	return null;
}

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 1]);
	return null;
}

$uploadDir = $extension = null;
$nameBeg = "image";

if($_FILES['file']['error'] == UPLOAD_ERR_NO_FILE)
	$new_question['image'] = false;
else if($_FILES['file']['error'] == UPLOAD_ERR_OK) {
	if(($_FILES['file']['type'] == 'image/gif') or ($_FILES['file']['type'] == 'image/jpeg')) {
		$new_question['image'] = true;
		$uploadDir = $_FILES['file']['tmp_name'];
		$name = explode(".",$_FILES['file']['name']);
		$extension = array_pop($name);
	} 
	else {
		redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 6]);
		return null;
	} 
} 
else {
	redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 7]);
	return null;
}								 

$new_question['extension'] = $extension;
$imageNumber = update_question($new_question);
if($new_question['image']) {
	if($imageNumber === null) {
		redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'error' => 8]);
	} 
   
	$finalPath = "img/".$nameBeg.$imageNumber.".".$extension;
	move_uploaded_file($uploadDir,$finalPath);
}
redirect_to('creator',['action' => 'edit_component', 'qid' => $new_question['id_pytania'], 'success' => 3]);

?>
