<?php
require_once 'src/config.php';

/* Zwraca uchwyt PDO do bazy z uprawnieniami użytkownika. */
function user_database() {
  return new PDO(host_data(), USER_DB_USER, USER_DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}

/* Zwraca uchwyt PDO do bazy z uprawnieniami twórcy. */
function creator_database() {
  return new PDO(host_data(), CREATOR_DB_USER, CREATOR_DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}

/* Zwraca uchwyt PDO do bazy z uprawnieniami administratora. */
function admin_database() {
  return new PDO(host_data(), ADMIN_DB_USER, ADMIN_DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}

/* Tworzy DB identifier z danych z pliku konfiguracyjnego. */
function host_data() {
  return 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
}
?>
