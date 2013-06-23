<?php
  require_once 'src/core.php';
  //testowanie zaczynania gry
  print begin_game(2, current_user())."<br/>";;
  print continue_game(1, current_user())."<br/>";
  $gry=get_continuable_games(current_user());
  print 'Kontunowalne gry('.count($gry)."):<br/>";
  foreach($gry as $gra)
  print $gra."<br/>";
  
?>
