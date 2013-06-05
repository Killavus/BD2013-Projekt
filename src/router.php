<?php
function page_whitelist() {
  return [
    'register',
    'game',
    'modify',
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

  header('Location: ' . http_build_query($args));
}
?>
