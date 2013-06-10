<?php
$available_actions = [
  'index',
  'create',
  'edit',
  'add_question',
  'add_answer',
  'delete',
  'permissions'
];

$action = isSet($_GET['action']) ? strtolower($_GET['action']) : 'index';

if(signed_in())
  require_once 'pages/creator_' . $action . '.php';
?>
