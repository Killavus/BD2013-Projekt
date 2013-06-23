<?php

$GLOBALS['g_session_id'] = null;

//zaczyna grę i zwaca id jej sesji jezeli istniala już sesja to wywala ją
function begin_game($game_id)
{
  if(!signed_in()) die("Musisz być zalogowany!");
  $game=get_game($game_id);
  $question_id=deduce_question_id($game);
  $user_id=deduce_user_id(current_user());
  
  $db = user_database();
  
  try { 
    $db->beginTransaction();
    $sesion_delete = $db->prepare("DELETE FROM sesja WHERE sesja.id_uzytkownika=:id_uzytkownika");
   
    $sesion_delete->execute([':id_uzytkownika' => $user_id]);
    
    $db->commit();
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  $GLOBALS['g_session_id']=null;
  return null;
  }
  
  
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
  $GLOBALS['g_session_id']=null;
  return null;
  }
  $GLOBALS['g_session_id']=$sesion_id;
  return $sesion_id;
}

//kontynuuje gre - zwraca id sesji zaczętej wcześniej gry
function continue_game($game_id)
{
  if(!signed_in()) die("Musisz być zalogowany!");
  $user_id=deduce_user_id(current_user());
  
  $db = user_database();
  
  try {
    $db->beginTransaction();
    $sesion_insert = $db->prepare("SELECT id_sesji FROM sesja WHERE id_uzytkownika=:id_uzytkownika 
                                                                AND id_gry=:id_gry");
   
    $sesion_insert->execute([':id_uzytkownika' => $user_id, 
                             ':id_gry' => $game_id]);
    
    $sesion_id = $sesion_insert->fetchColumn();
    
    $db->commit();
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  $GLOBALS['g_session_id']=null;
  return null;
  }
  $GLOBALS['g_session_id']=$sesion_id;
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

function get_current_session_id()
{
  global $g_session_id;
  //
  return $g_session_id;
}

function get_current_session()
{
  $session_id=get_current_session_id();
  if($session_id === null) return null;
  return get_session($session_id);
}

?>
