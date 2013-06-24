<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_POST['nazwa']))
	die("Należy podać nazwę, na jaką ma się zmienić");

if(!update_user($_POST['nazwa'])) {
	die("lol");
}
redirect_to('settings',['success' => 1]);
?>
