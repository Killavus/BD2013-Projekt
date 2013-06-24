<?php
$available_actions = [
  'index',
  'play',
  'new',
  'continue'
];

$action = isSet($_GET['action']) ? strtolower($_GET['action']) : 'index';

if(signed_in())
  require_once 'pages/play_' . $action . '.php';
?>
