<?php
$link = new mysqli("localhost","root","maxmobil", "translator_test");

if (mysqli_connect_errno()) {
   echo "error";
   exit();
}

//mysqli_select_db ($link,"translator_test");
//$link->select_db("translator_test");
if ($result = $link->query("call getLocales ()")) {

   while ($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
    echo $row[0] . "<br>";
    }

    mysqli_free_result($result);

} else { echo "problem :( "; }


?>