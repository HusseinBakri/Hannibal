<?php
DEFINE ('DB_USER', 'AdaptiveUserNew');
DEFINE ('DB_PASSWORD', 'T3BQQ43F');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'adaptivedb');
function db_connect() {
  $link = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  //mysql_select_db(DB_NAME);
}

function db_close() {
  mysqli_close();
}
