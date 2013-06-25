<?php
/*
 * Funkcje odpowiedzialne za użytkownika.
 */

require_once 'src/config.php';
require_once 'src/database.php';

$GLOBALS['g_user'] = null;

/* Pobiera aktualnego użytkownika. */
function current_user() {
  global $g_user;

  if(!isSet($_COOKIE['bd2013_session']))
    return null;

  if($g_user === null) {
    $db = user_database();

    $user_agent = md5($_SERVER['HTTP_USER_AGENT']);

    $stmt = $db->prepare("SELECT uzytkownik.id_uzytkownika, nazwa, login 
                            FROM uzytkownik 
                            JOIN klucz_przegladarki 
                              USING(id_uzytkownika)
                            WHERE klucz = :hash
                              AND wygasa > NOW()
                              AND user_agent = :ua");

    $stmt->execute([':hash' => $_COOKIE['bd2013_session'],
                    ':ua'   => $user_agent]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result === false) $result = null;

    $GLOBALS['g_user'] = $result;
  }
  
  return $g_user;
}

/* Wylogowuje (usuwa sesję użytkownika). Nic nie zwraca. */
function sign_out() {
  if(!isSet($_COOKIE['bd2013_session']))
    return;

  $db = user_database();
  
  $stmt = $db->prepare('DELETE FROM klucz_przegladarki WHERE klucz = :klucz');
  $stmt->execute([':klucz' => $_COOKIE['bd2013_session']]);

  setcookie('bd2013_session', '', 0, '/');
}

/* Pobiera użytkownika po jego nazwie. Zwraca null, jeżeli użytkownik o danej nazwie nie istnieje. 
   Jeżeli $case_insensitive jest ustawione na true, pobiera użytkownika nie zważając na wielkość znaków w loginie. */
function get_user($name, $case_insensitive = true) {
  $key = "login";

  if($case_insensitive) {
    $key = "lower(login)";
    $name = strtolower($name);
  }

  $db = user_database();

  $stmt = $db->prepare("SELECT id_uzytkownika, login, nazwa 
                          FROM uzytkownik 
                          WHERE $key = :name");

  $stmt->execute([':name' => $name]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  if(!$user) $user = null;
  return $user;
}

/* Sprawdza, czy w ogóle użytkownik jest zalogowany. */
function signed_in() {
  return current_user() !== null;
}

/* Próbuje zalogować użytkownika. Zwraca false, gdy się nie uda, true wpp. 
   Ustawia cookies, więc musi być przeprowadzane nim zostanie wysłana 
   jakakolwiek treść. */
function sign_in($user, $password, $expires = 86400) {
  if(signed_in()) return;

  $user_name = strtolower($user);
  $user_agent = md5($_SERVER['HTTP_USER_AGENT']);

  $db = user_database();

  $check = $db->prepare('SELECT id_uzytkownika,hash_passwd FROM uzytkownik 
                          WHERE LOWER(login) = :login');
  
	$check->execute([
    ':login' => $user_name,
	]);
	
	$row = $check->fetch(PDO::FETCH_ASSOC);

  $user_id = $row['id_uzytkownika'];
	$hash = $row['hash_passwd'];
  $check->closeCursor();

  if($user_id === FALSE)
    return false;
	
	if(crypt($password . APP_SECRET, $hash) != $hash)
		return false;

  new_session($user_id, $user_agent, $expires);
  return true;
}

/* Tworzy nową sesję w bazie danych dla danego użytkownika i przeglądarki.
   Nic nie zwraca. */ 
function new_session($user_id, $user_agent, $expires) {
  $db = user_database();
  $expire_date = date('r', time() + $expires); 
  $new_session_id = hash("sha256", 
    strval(time()) . strval($user_id) . APP_SECRET);

  $stmt = $db->prepare('INSERT INTO klucz_przegladarki 
                          VALUES(:id_klucza, 
                                 :ua, 
                                 :wygasa, 
                                 :id_uzytkownika)');

  $stmt->execute([
    ':id_klucza' => $new_session_id,
    ':ua' => $user_agent,
    ':wygasa' => $expire_date,
    ':id_uzytkownika' => $user_id
  ]);

  $stmt->closeCursor();

  setcookie('bd2013_session', $new_session_id, time() + (int)$expires, '/');
}

/* Tworzy nowego użytkownika o zadanym loginie, nazwie użytkownika i haśle. Nic nie zwraca. */
function create_user($login, $name, $password) {
  $db = user_database();

  $stmt = $db->prepare('INSERT INTO uzytkownik(login, nazwa, hash_passwd) 
      VALUES(:login, :nazwa, :haslo)');

  $stmt->execute([':login' => $login, 
                  ':nazwa' => $name, 
                  ':haslo' => crypt($password . APP_SECRET)]);

  $stmt->closeCursor();
}

// Wyszukuje użytkowników, których nazwy zawierają w sobie podane słowo i nie są twórcami/adminami gry
function search_users($word,$game_id) {
	$new_word = "%".$word."%";

	$db = user_database();

	$stmt = $db->prepare('SELECT id_uzytkownika,nazwa FROM uzytkownik
		WHERE nazwa LIKE :word AND id_uzytkownika NOT IN(
			SELECT id_uzytkownika FROM uprawnienie WHERE id_gry = :id_gry
		)
		ORDER BY nazwa');
	$stmt->execute([':word' => $new_word,':id_gry' => $game_id]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	return $result;
}

/* Aktualizuje użytkownika. Zwraca zawsze true. */
function update_user($new_name) {
	if(!signed_in()) return false;
	
	$user = current_user();
	
	$new_name = htmlspecialchars($new_name);
	$db = creator_database();

	$stmt = $db->prepare('UPDATE uzytkownik SET nazwa = :name WHERE id_uzytkownika = :user_id');
	$stmt->execute([':name' => $new_name, ':user_id' => $user['id_uzytkownika']]);
	$stmt->closeCursor();

	return true;
}
?>
