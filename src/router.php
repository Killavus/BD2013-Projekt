<?php
require_once 'src/config.php';

function page_whitelist() {
  return [
    'register',
    'creator',
    'play',
    'welcome',
    'login'
  ];
}

function get_page($default) {
  $page = isSet($_GET['page']) ? $_GET['page'] : $default;
  if(!in_array($page, page_whitelist()))
    $page = $default;

  return $page;
}

/* UWAGA! Pamiętajcie o tym, że redirecty muszą być w 'headers', 
   nie w 'pages'! 
   Oczywiście w skryptach obsługujących formularze też należy ich używać.
*/
function redirect_to($page, $args) {
  $args['page'] = get_page($page);
  
  header('Location: ' . ROOT . '/index.php?' . http_build_query($args));
}
?>
