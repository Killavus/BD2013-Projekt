<?php

$GLOBALS['g_id_session'] = null;

//zaczyna grę i zwaca id jej sesji
function begin_game($game_id)
{
  if(!signed_in()) die("Musisz być zalogowany!");
  $game=get_game($game_id);
  $question_id=deduce_question_id($game);
  $user_id=deduce_user_id(current_user());
  
  print $question_id.$question_id.$user_id; 
  
  $db = creator_database();
  
  try {
    $db->beginTransaction();
    $sesion_insert = $db->prepare("INSERT INTO sesja(id_uzytkownika, 
                                                     id_gry, 
                                                     id_pytania) 
                                   VALUES(:id_uzytkownika, 
                                          :id_gry,
                                          :id_pytania) 
                                   RETURNING id_sesji");
   
    $sesion_insert->execute([':id_uzytkownika' => $user_id, 
                             ':id_gry' => $game_id,
                             ':id_pytania' => $question_id]);
    
    $sesion_id = $sesion_insert->fetchColumn();
    
    $db->commit();
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  $GLOBALS['g_id_session']=null;
  return null;
  }
  $GLOBALS['g_id_session']=$sesion_id;
  return $sesion_id;
}

//zwraca sesje na podstawie jej id
function get_session($session_id) {

  if((int)$session_id < 1)
    return null;

  $db = user_database();

  $stmt = $db->prepare('SELECT id_sesji, id_gry, id_pytania, punkty, rozpoczecie FROM sesja
                        WHERE id_sesji = :id');

  $stmt->execute([':id' => $session_id]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  if($result === false)
    return null;

  return $result;
}

?>
