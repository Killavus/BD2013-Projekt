<?php
chdir('..');
require_once 'src/core.php';

// Obsługa błędów:

// 1. Brak danych:
if(!isSet($_POST['user']))
  die("Nie udało się zrealizować formularza z powodu braku danych.");

// 2. Użytkownik istnieje już w naszej bazie danych.
if(get_user($_POST['user']['login']) !== null) {
  redirect_to('register', ['error' => 1]);
  return null;
}

// 3. Sprawdzamy, czy wymagane dane zostały podane.
$required = ['login', 'password'];
if(count(array_diff($required, array_keys($_POST['user']))) > 0) {
  redirect_to('register', ['error' => 2]);
  return null;
}

// 4. Sprawdzamy, czy login spełnia nasz regexp.
if(preg_match('/^[a-zA-Z0-9_\-\.]{3,}$/', $_POST['user']['login']) !== 1) {
  redirect_to('register', ['error' => 3]);
  return null;
}
  
// 5. Jeżeli nazwa użytkownika nie została podana - przyjmujemy login.
if(!isSet($_POST['user']['name']) or empty($_POST['user']['name']))
  $_POST['user']['name'] = $_POST['user']['login'];

// 6. Sprawdzamy, czy hasła są takie same:
if($_POST['password-repeat'] !== $_POST['user']['password']) {
  redirect_to('register', ['error' => 4]);
  return null;
}
  
// 7. Wszystko ok, tworzymy użytkownika.
create_user($_POST['user']['login'], $_POST['user']['name'], $_POST['user']['password']);
redirect_to('register', ['success' => 1]);
?>
