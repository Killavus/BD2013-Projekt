<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_GET['gid']))
	die("Należy podać id gry, do której ma być dodane pytanie");

$game_id = (int)$_GET['gid'];
$question['nazwa'] = $_POST['nazwa'];
$question['stan'] = $_POST['stan'];
$question['warunek'] = $_POST['warunek'];
$question['tekst'] = $_POST['tekst'];

if(empty($question['nazwa']) or empty($question['tekst'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 1]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$question['nazwa'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 2]);
	return null;
}

if(!preg_match('/([^ \t\n\r]+)/',$question['tekst'])) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 3]);
	return null;
}

if(!empty($question['stan']) && check_assignments($question['stan']) === false) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 12, 'cerror' => get_last_error()]);
  return null;
}

if(!empty($question['warunek']) && check_expression($question['warunek']) === false) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 13, 'cerror' => get_last_error()]);
	return null;
}

if(!can_modify_game($game_id)) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 4]);
	return null;
}

$q = get_question_by_name($question['nazwa'],$game_id);

if($q !== null) {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 5]);
	return null;
}

$uploadDir = $extension  = null;
$nameBeg = "image";

if($_FILES['file']['error'] == UPLOAD_ERR_NO_FILE)
	$question['image'] = false;
else if($_FILES['file']['error'] == UPLOAD_ERR_OK) {
	if(($_FILES['file']['type'] == 'image/gif') or ($_FILES['file']['type'] == 'image/jpeg')) {
		$question['image'] = true;
		$uploadDir = $_FILES['file']['tmp_name'];
		$name = explode(".",$_FILES['file']['name']);
		$extension = array_pop($name);
	}
	else {
		redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 10]);
		return null;
	}
}
else {
	redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 9]);
	return null;
}

$question['extension'] = $extension;
$imageNumber = add_question($question,$game_id);
if($question['image']) {
	if($imageNumber === null) {
		redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'error' => 11]);
	}

	$finalPath = "/var/www".ROOT."/img/".$nameBeg.$imageNumber.".".$extension;
	move_uploaded_file($uploadDir,$finalPath);
}

redirect_to('creator',['action' => 'edit', 'gid' => $game_id, 'success' => 1]);
?>
