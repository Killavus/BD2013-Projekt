<?php
chdir('..');
require_once 'src/core.php';

if(!isSet($_POST['login']) or !isSet($_POST['password']))
	die("Brak danych w formularzu.");

$db = user_database();

/* To tak: (pamiętajmy, że pracujemy w PDO, nie używamy funkcji z rodziny mysql_
   To przestarzałe.
   Taki jest sposób na escaping w PDO:
$user = $db->quote($_POST['login']);
$passwd = $db->quote($_POST['password']);
Ale to jest niepotrzebne, bo w sign_in są zapytania przygotowane i to się samo escapuje.
*/

$user = $_POST['login'];
$passwd = $_POST['password'];

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

redirect_to('welcome');
?>
