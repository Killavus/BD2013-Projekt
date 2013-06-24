<?php
  if(isset($_GET['gid']))
  {
    $gid=$_GET['gid'];
    $session_id=continue_game($gid);
    $game=get_game($gid);
    
    $session=get_session($session_id);
    $qid=$session['id_pytania'];
    
    //print 'Kontynuujemy gre o id '.$gid.', numer sesji to: '.$session_id.' a pytanie to '.$qid."\n";
    redirect_to('play.php', ['page'=>'play', 'action' => 'play', 'sid' => $session_id, 'qid' => $qid]);
  }
?>

