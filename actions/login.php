<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_POST['login']) or !isSet($_POST['password']))
	die("Brak danych w formularzu.");

$user = mysql_real_escape_string($_POST['login']);
$passwd = mysql_real_escape_string($_POST['password']);

if(preg_match('/^[a-zA-Z0-9_\-\.]{3,}$/', $user) !== 1) {
	redirect_to('login',['error' => 1]);
	return null;
}

if(empty($user)){
	redirect_to('login',['error' => 2]);
	return null;
}

if(sign_in($user,$passwd) === false){
	redirect_to('login',['error' => 3]);
	return null;
}

redirect_to('welcome',null);

?>
