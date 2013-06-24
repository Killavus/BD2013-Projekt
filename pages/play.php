<?php
  require_once 'src/core.php';
  //testowanie zaczynania gry
  $sesja = begin_game(2, current_user());
  print $sesja."<br/>";;
  print continue_game(1, current_user())."<br/>";
  $gry=get_continuable_games(current_user());
  print 'Kontunowalne gry('.count($gry)."):<br/>";
  foreach($gry as $gra)
  print $gra."<br/>";
  
  set_variable('dupa', '123', $sesja);
  
  print get_variable('dupa', $sesja);
  
  
  end_game(2);
  
?>
