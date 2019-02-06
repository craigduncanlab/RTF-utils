<?php
/* action: write txt tags file to local directory
updated 26 February 2017 */

//$docname="mytxt";
//$docname="mydoc";
session_start();
$docname=$_SESSION["filename"];
//make sure you set local permission for output file from parent directory: chmod 777 output
$path="/Users/craigduncan/Sites/mojodox/output/";
$filename=$path.$docname.".txt";
$somecontent=$myDoc;
//set error checking 26.02.17
ini_set('display_errors', 1);
error_reporting(E_ALL);
//chmod($filename, 0750);
//echo `whoami`;
//echo $somecontent;
/* -- Old preliminary checks to see if file existed and was writeable e.g. chmod 666 mydoc.rtf
if (is_writable($filename)) {

    // In our example we're opening $filename in write mode.
    // The file pointer is at the top of the file hence
    // that's where $somecontent will go when we fwrite() it.
    if (!$handle = fopen($filename, 'w+')) {
         echo "Cannot open file ($filename)";
         exit;
    }

    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }

    echo "Successfully saved TXT file ".$clausename,"<br>"; //, wrote ($somecontent) to file ($filename)";

    fclose($handle);
}
 else {
    echo "The file $filename is not writable";
}
*/
//New code which will create a new file and write to it
// The file pointer is at the top of the file hence  that's where $somecontent will go when we fwrite() it.
//chmod($filename,0644);
if (!file_exists($filename)) {
  touch($filename);
}
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);

// Write $somecontent to our opened file.
if (fwrite($handle, $somecontent) === FALSE) {
    echo "Cannot write to file ($filename)";
    exit;
}
echo "Successfully saved TXT file ".$filename,"<br>"; //, wrote ($somecontent) to file ($filename)";

fclose($handle);

?>
