<?php

$GLOBALS['g_session_id'] = null;

//zaczyna grę i zwaca id jej sesji jezeli istniala już sesja to wywala ją
function begin_game($game_id, $user=NULL)
{
  end_game($game_id, $user); //usuwamy grę jeżeli instniała
  if(!signed_in()) die("Musisz być zalogowany!");
  $game=get_game($game_id);
  $question_id=deduce_question_id($game);
  $user_id=deduce_user_id($user);
  
  $db = user_database();
  
  try { 
    $db->beginTransaction();
    $session_delete = $db->prepare("DELETE FROM sesja WHERE sesja.id_uzytkownika=:id_uzytkownika
                                                       AND sesja.id_gry=:id_gry");
   
    $session_delete->execute([':id_uzytkownika' => $user_id, ':id_gry' => $game_id]);
    
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
    $session_insert = $db->prepare("INSERT INTO sesja(id_uzytkownika, 
                                                     id_gry, 
                                                     id_pytania) 
                                   VALUES(:id_uzytkownika, 
                                          :id_gry,
                                          :id_pytania) 
                                   RETURNING id_sesji");
   
    $session_insert->execute([':id_uzytkownika' => $user_id, 
                             ':id_gry' => $game_id,
                             ':id_pytania' => $question_id]);
    
    $session_id = $session_insert->fetchColumn();
    
    $db->commit();
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  $GLOBALS['g_session_id']=null;
  return null;
  }
  $GLOBALS['g_session_id']=$session_id;
  return $session_id;
}

//kontynuuje gre - zwraca id sesji zaczętej wcześniej gry
function continue_game($game_id, $user=NULL)
{
  if(!signed_in()) die("Musisz być zalogowany!");
  $user_id=deduce_user_id($user);
  
  $db = user_database();
  
  try {
    $db->beginTransaction();
    $session_insert = $db->prepare("SELECT id_sesji FROM sesja WHERE id_uzytkownika=:id_uzytkownika 
                                                                AND id_gry=:id_gry");
   
    $session_insert->execute([':id_uzytkownika' => $user_id, 
                             ':id_gry' => $game_id]);
    
    $session_id = $session_insert->fetchColumn();
    
    $db->commit();
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  $GLOBALS['g_session_id']=null;
  return null;
  }
  $GLOBALS['g_session_id']=$session_id;
  return $session_id;
}

function end_game($game_id, $user=NULL)
{
  if(!signed_in()) die("Musisz być zalogowany!");
  $user_id=deduce_user_id($user);
  
  $db = user_database();
  
  try {
    $db->beginTransaction();
    $session_delete = $db->prepare("DELETE FROM sesja WHERE id_uzytkownika=:id_uzytkownika 
                                                     AND id_gry=:id_gry
                                                     RETURNING id_sesji");
   
    $session_delete->execute([':id_uzytkownika' => $user_id, 
                             ':id_gry' => $game_id]);
    
    $session_id = $session_delete->fetchColumn();
    
    $db->commit();
    
    try {
      $db->beginTransaction();
      $vars_delete = $db->prepare("DELETE FROM srodowisko WHERE id_sesji=:id_sesji");
     
      $vars_delete->execute([':id_sesji' => $session_id]);
      
      $db->commit();
    }
    catch(PDOException $pdo) {
    echo $pdo->getMessage();
    $db->rollBack();
    }
    
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  }
  
 
  $GLOBALS['g_session_id']=null;
}

function end_game_by_session($session_id)
{
  $db = user_database();
  
  try {
    $db->beginTransaction();
    $session_delete = $db->prepare("DELETE FROM sesja WHERE id_sesji=:id_sesji");
   
    $session_delete->execute([':id_sesji' => $session_id]);
    
    $session_id = $session_delete->fetchColumn();
    
    $db->commit();
    
    try {
      $db->beginTransaction();
      $vars_delete = $db->prepare("DELETE FROM srodowisko WHERE id_sesji=:id_sesji");
     
      $vars_delete->execute([':id_sesji' => $session_id]);
      
      $db->commit();
    }
    catch(PDOException $pdo) {
    echo $pdo->getMessage();
    $db->rollBack();
    }
    
  }
  catch(PDOException $pdo) {
  echo $pdo->getMessage();
  $db->rollBack();
  }
  
 
  $GLOBALS['g_session_id']=null;
}


//zwraca sesje na podstawie jej id
function get_session($session_id) {

  if((int)$session_id < 1)
    return null;

  $db = user_database();

  $stmt = $db->prepare('SELECT id_sesji, id_gry, id_pytania, id_uzytkownika, punkty, rozpoczecie FROM sesja
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
  if($GLOBALS['g_session_id'] === null)
  {
    if(isset($_GET['sid']))
    {
      $session_id=$_GET['sid'];
      if(!user_has_session($session_id)) die('Sesja nie należy do użytkownika');
      $GLOBALS['g_session_id']=$_GET['sid']; //trzeba sprawdzić czy sesja jest i czy nalerzy do użytkownika
    }
    else
    return 0;
  }
  return $GLOBALS['g_session_id'];
}

//zwraca aktualną sesje
function get_current_session()
{
  $session_id=get_current_session_id();
  if($session_id === null) return null;
  return get_session($session_id);
}

//zwraca prawdę jeżeli user ma sesję
function user_has_session($session_id, $user = NULL)
{
  $session=get_session($session_id);
  if($session['id_uzytkownika']==deduce_user_id($user))
  {
    return true;
  }
  return false;
}

//zwraca liste id kontynuowalnych gier
function get_continuable_games($user = NULL) {
  $id = deduce_user_id($user);

  if((int)$id < 1)
    return [];

  $db = user_database();
  $stmt = $db->prepare("SELECT sesja.id_gry FROM sesja WHERE id_uzytkownika=:id_uzytkownika");

  $stmt->execute([':id_uzytkownika' => $id]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  $games = [];
  foreach($result as $data_row) {
    $games[] = $data_row['id_gry'];
  }

  return $games;
}


?>
