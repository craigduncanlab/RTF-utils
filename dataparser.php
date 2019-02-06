<?php
/*

---- DATA FILES PARSER ---- (case sensitive)
@author Craig Duncan
@created 4 January 2016
@revised 7 January 2016
*/
function loadVars ()
{
global $VARDATA,$VARNUM,$MYDATA;

echo 'starting variable loading <br>';
$DIR='../mojodox/data';
$getdir=getcwd();
chdir($DIR);  
//By changing the value of $MYDATA you can load in entirely new data sets for different clients 
echo "DATA FILE READING: ",$MYDATA,'<br>';
$VARDATA = parse_ini_file($MYDATA);
echo 'Check some data file values load correctly:<br>';
echo $VARDATA['<agreedate>'],'<br>';
echo $VARDATA['<companyname1>'],'<br>';
chdir($getdir);
}

/*
PARSER FOR TAGS DEFINED IN TXT FILES THAT REPRESENT 'TERMINAL' CATEGORIES
$Parse=str_replace("<sometag>",$replacementtext,$Parse);
This will iterate on the string using the arrays for comparison.  It will perform the first replace in the file and check that for replace as well.
It is possible to put multiple tags in one file, if they occur together and are in order
*/

function DataParser (&$Parse)
{
global $VARDATA,$VARNUM;
//Parse
$keyme=array_keys($VARDATA);
$targetme=array_values($VARDATA);
echo $keyme[1];
//If successful in matching some pairs of array, echo counter value
if($Parse=str_replace($keyme,$targetme,$Parse,$counter)) {
echo "Data matches:", $counter,"<br>";
}
echo "Data Parser loop completed<br>";
return $Parse;
}

function getConfig($userfile) {

global $MYDATA,$FontSet;

$configfile='rtfconfig.ini';
$config_array  = parse_ini_file($configfile);
$FontSet=$config_array['fontsize'];
//$MYDATA=$config_array['datafile'];
$MYDATA=$userfile;
echo "FontSet: ",$FontSet,'<br>';
echo "Datafile: ",$MYDATA,'<br>'; 
}

?>
