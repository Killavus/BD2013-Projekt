<div class="container">
  <div class="row">
    <div class="span12">
      <h1>Twórz gry!</h1>
      <p>Możesz w każdej chwili stworzyć dowolną grę, o jakiej zamarzysz. Wystarczy,
      że klikniesz na przycisk, wybierzesz nazwę gry i możesz do woli ją rozwijać!
      Chcesz robić grę z kumplem? Nie ma problemu - system uprawnień pozwoli Ci w
      prosty sposób ustalić z kim chcesz współpracować.</p>
      <a class="btn btn-primary" href="?page=creator&action=create">Stwórz grę</a>
        </div>
  </div>
  <?php if(has_modifiable_games()) { 
    $games = get_modifiable_games();
  ?>
  <div class="row">
    <div class="span12" style="margin-top: 3em">
      <h2>Twoje gry:</h2>
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
            <a href="?page=creator&action=edit&gid=<?php echo $id; ?>" 
              class="btn btn-small btn-primary">Edytuj</a>&nbsp;
            <?php if($game['ranga'] == 'A') ?>
            <a href="?page=creator&action=delete&gid=<?php echo $id; ?>"
              class="btn btn-small btn-danger">Usuń</a>
          </td>
        </tr>
        <?php
        }
        ?>
        </tbody>      
      </table>
    </div>
  </div>
  <?php } ?>
</div>
