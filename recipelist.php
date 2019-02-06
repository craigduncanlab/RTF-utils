<?php

$configfile='recipelist.ini';
$recipelist  = parse_ini_file($configfile);
echo "Recipe List: ",$recipelist,'<br>'; 

?>