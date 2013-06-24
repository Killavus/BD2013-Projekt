<?php
  if(isset($_GET['gid']))
  {
    $gid=$_GET['gid'];
    $session_id=continue_game($gid);
    print 'Kontynuujemy gre o id '.$gid.', numer sesji to: '.$session_id."\n";
  }
?>
