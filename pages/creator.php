<?php
$available_actions = [
  'index',
  'create',
  'edit',
  'delete',
  'edit_question',
  'add_question',
  'delete_question',
  'add_answer',
  'edit_answer',
  'delete_answer',
  'permissions'
];

$action = isSet($_GET['action']) ? strtolower($_GET['action']) : 'index';

if(signed_in())
  require_once 'pages/creator_' . $action . '.php';
?>
