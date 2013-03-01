<?php

$myFile = "counter.txt";
$fh = fopen($myFile, 'r');
$theData = fread($fh, 150);
fclose($fh);
print $theData;
?>