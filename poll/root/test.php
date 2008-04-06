<?php
require_once '../inc/class_PollBase_inc.php';
$poll = new Poll(1);

echo $poll->getTitle();

$poll->createImageFolder();

?>
1