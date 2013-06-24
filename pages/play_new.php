<?php
  if(isset($_GET['gid']))
  {
    $gid=$_GET['gid'];
    $session_id=begin_game($gid);
    $game=get_game($gid);
    $qid=deduce_question_id($game);
    //print 'Zaczynamy gre o id '.$gid.', numer sesji to: '.$session_id.' a pytanie to '.$qid."\n";
    redirect_to('play.php', ['page'=>'play', 'action' => 'play', 'sid' => $session_id, 'qid' => $qid]);
  }
?>
