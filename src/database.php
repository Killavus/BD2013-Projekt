<?php
require_once 'src/config.php';

function user_database() {
  return new PDO(host_data(), USER_DB_USER, USER_DB_PASS);
}

function creator_database() {
  return new PDO(host_data(), CREATOR_DB_USER, CREATOR_DB_PASS);
}

function admin_database() {
  return new PDO(host_data(), ADMIN_DB_USER, ADMIN_DB_PASS);
}

function host_data() {
  return 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
}
?>
