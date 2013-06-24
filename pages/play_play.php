<?php
  if(!isset($_GET['qid'])) die('Błąd - brak pytania');
  if(!isset($_GET['sid'])) die('Błąd - brak sesji');
  
  $qid=$_GET['qid'];
  $sid=$_GET['sid'];
  $user=current_user();
  $uid=deduce_user_id($user);
  
  print 'Jesteśmy przy pytaniu '.$qid.', sesji '.$sid.', użytkowniku '.$uid."\n";
?>
