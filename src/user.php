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

    $result = $stmt->fetchRow(PDO::FETCH_ASSOC);
    if($result === false) $result = null;

    $GLOBALS['g_user'] = $result;
  }
  
  return $g_user;
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
  $password_hash = crypt($password . APP_SECRET);
  $user_agent = md5($_SERVER['HTTP_USER_AGENT']);

  $db = user_database();

  $check = $db->prepare('SELECT id_uzytkownika FROM uzytkownicy 
                          WHERE LOWER(nazwa) = :nazwa AND
                                hash_passwd = :hash');
  $check->execute([
    ':nazwa' => $user_name,
    ':hash' => $password_hash
  ]);

  $user_id = $check->fetchColumn();
  $check->closeCursor();
  if($user_id === FALSE)
    return false;

  new_session($user_id, $user_agent, $expires);
  return true;
}

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

  setcookie('bd2013_session', $new_session_id, time() + $expires);
}
?>
