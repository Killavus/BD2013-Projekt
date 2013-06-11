<?php
/* Tworzy słownik na podstawie słownika $array, złożony
   z tylko z kluczy wyspecyfikowanych w $keys.

   Przykład:
   only(['a' => 1, 'b' => 2, 'c' => 3], ['a', 'c']) => ['a' => 1, 'c' => 3])
*/
function only($array, $keys) {
  $array_keys = array_keys($array);

  $result = [];
  foreach($array_keys as $array_key) {
    if(in_array($array_key, $keys))
      $result[$array_key] = $array[$array_key];
  }

  return $result;
}

/* Analogiczna do portion funkcja, która zwraca wszystkie klucze POZA
   z $keys.
*/
function except($array, $keys) {
  $array_keys = array_keys($array);

  $result = [];
  foreach($array_keys as $array_key) {
    if(!in_array($array_key, $keys))
      $result[$array_key] = $array[$array_key];
  }

  return $result;
}

function deduce_user_id($user) {
  if(is_null($user))
    $user = current_user();

  if(is_array($user))
    $user = isSet($user['id_uzytkownika']) ? $user['id_uzytkownika'] : null;

  return $user;
}

function deduce_game_id($game) {
  if(is_array($game))
    $game = isSet($game['id_gry']) ? $game['id_gry'] : null;

  return $game;
}
?>
