<div class="container">
  <div class="row">
    <div class="span12">
      <h1>Zagraj</h1>
      <p>Bedziesz grau w gre</p>
    </div>
  </div>
  <?php 
    $games = get_all_games();
    $continuable_games=get_continuable_games();
  ?>
  <div class="row">
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
 
</div>
