<?php
<<<<<<< HEAD
 /* require_once 'src/core.php';
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
*/
 
=======
$available_actions = [
  'index',
  'play',
  'new',
  'continue'
];

$action = isSet($_GET['action']) ? strtolower($_GET['action']) : 'index';

//if(signed_in())
  //require_once 'pages/play_' . $action . '.php';
>>>>>>> 28543e770f40d75ac38d234de73ccf45325847a2
?>

<div class="container">
	<div class="page-header">
		<h2>Wybierz grę! <small> Możesz zagrać w dowolną stworzoną przez kogoś grę </small> </h2>
	</div>
	<div class="pull-right">
		<input type="text" class="search-query search_input" search-connection="games" placeholder="Szukaj" >
	</div>
	<table id="games" class="table table-striped search_table" filter=2 >
		<thead>
			<th> Nazwa gry </th>
			<th> Admin </th>
			<th> Starter </th>
		</thead>
		<tbody search="ajax/game_filter.php">
		</tbody>
	</table>
</div>
<script type="text/javascript" src="js/search.js"></script>
