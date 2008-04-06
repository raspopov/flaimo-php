<?php

session_start();
require_once '../inc/class.AccessBase.inc.php';

$u = new AccessLogin();
echo '<pre>';



var_dump($u->login("HalloDu", "lala"));
//echo $u->getFirstname();
//$l = new AccessLogin();

//echo $l->sendNewPassword('rtgedfuoiooit7zrr99@wt3uiuuiuzizor.com');

//$u = new AccessUser(1);

//echo $u->getUsername();

/*
$conn = new mysqli("localhost", "root", "maxmobil", "access");



if ($conn->multi_query("CALL GetNewItems();")) {
  if ($items = $conn->store_result()) {
    while($item = $items->fetch_array(MYSQLI_ASSOC)) {
      printf("%s", $item["title"]);
    }
    $items->close;
  }

  do {
    # Throw pending results away
  } while ($conn->next_result());
}
*/

/**/


?>

