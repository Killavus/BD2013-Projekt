<!-- <div class="container">
  <div class="row">
    <div class="span12">
      <h1>Zagraj</h1>
      <p>Bedziesz grau w gre</p>
    </div>
  </div> -->
  <?php 
    $games = get_all_games();
    $continuable_games=get_continuable_games();
  ?>
<!--  <div class="row">
    <div class="span12" style="margin-top: 3em">
      <h2>Dostępne gry:</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nazwa gry:</th>
            <th>Akcje:</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach($games as $game) {
          $game = $game['gra'];
          $id = $game['id_gry'];
        ?>
        <tr>
          <td><?php echo $game['nazwa_gry']; ?></td>
          <td>
						<a href="?page=play&action=new&gid=<?php echo $id; ?>"
							class="btn btn-small btn-primary">Nowa gra</a>
              <?php if(in_array($game['id_gry'], $continuable_games)) { ?>
            <a href="?page=play&action=continue&gid=<?php echo $id; ?>" 
              class="btn btn-small btn-info">Kontynuuj</a>&nbsp;  
              <?php } ?>
          </td>
        </tr>
         <?php } ?>
        </tbody>      
      </table>
    </div>
  </div>
</div> -->

<!-- szukajka -->

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
<script type="text/javascript">
<?php

$array_js = "";
foreach($continuable_games as $game_c) {
	$array_js = $array_js.$game_c.",";
}
rtrim($array_js,",");
$array_js = "[".$array_js."]";
echo "var continuable = ".$array_js.";";

?>
</script>
<script type="text/javascript" src="js/search.js"></script>
