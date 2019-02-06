<?php
/* action: write RTF file to local directory
@ created January 2016
@author Craig Duncan
@ revised 10 January 2016*/

//$docname="mydoc";
//session_start();
$docname=$_SESSION["filename"];
//set local permissions  or try 1) chmod 777 mojodox 2) chmod -R 644 mojodox from parent directory
//$parentpath="/Users/craigduncan/Sites/mojodox";
//chmod($parentpath,0644);
$path="/Users/craigduncan/Sites/mojodox/output/";
//chmod($path,0644);
$filename=$path.$docname.".rtf";
$somecontent=$RTFdata;
//chmod($filename, 0750);
//echo `whoami`;
//echo $somecontent;
// make sure permissions are set for folder where files will be written
if (!file_exists($filename)) {
  touch($filename);
}
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);

    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }

    echo "Successfully saved RTF file ".$filename; //, wrote ($somecontent) to file ($filename)";

    fclose($handle);
	 echo "<html><body><p> Click <a href=\"GUI.html\">here</a> to go back to selection page</p>";

	 echo "</body></html>";
  	 //header("Location:05.html");
  	 exit();

?>
