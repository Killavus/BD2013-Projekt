<?php
  require_once 'src/core.php';
  //testowanie zaczynania gry
  begin_game(1, current_user());
  print continue_game(1, current_user());
?>
