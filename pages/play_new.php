<?php
  if(isset($_GET['gid']))
  {
    $gid=$_GET['gid'];
    $session_id=begin_game($gid);
    print 'Zaczynamy gre o id '.$gid.', numer sesji to: '.$session_id."\n";
  }
?>
