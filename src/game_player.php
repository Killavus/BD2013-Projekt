<?php
  /* Pobiera wszystkie gry z bazy danych. */
  function get_all_games() {

    $db = user_database();
    $stmt = $db->prepare("SELECT gra.id_gry, gra.nazwa AS nazwa_gry, pytanie.* FROM gra
                            JOIN pytanie USING(id_pytania)");

    $stmt->execute([]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $games = [];
    foreach($result as $data_row) {
      $games[] = [
        'gra' => only($data_row, ['id_gry', 'nazwa_gry']),
        'pytanie' => except($data_row, ['id_gry', 'nazwa_gry'])
      ];
    }

    return $games;
  }
?>
