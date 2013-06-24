<?php
chdir('..');
require_once 'src/core.php';

$word = isSet($_GET['word']) ? $_GET['word'] : '';
$games = search_games($word);

echo json_encode($games);
?>
