<?php
/*
Action: Collate txt clauses, run document parser and convert txt file with tags to RTF
@created 2015
@revised 10 January 2016
@
*/

/*-- obtain filename entered for this session --*/
//get session data
session_start();
$_SESSION["filename"]=$_POST['fnamesave'];

/*-- continue -- */
$myDoc=$_POST['docchoice']; //set%myDoc to whatever starting <category>  or recipe name is submitted
$userfile=$_POST['datachoice'];
if ($myDoc=="Choose document") {
echo "no document choice";
echo "<html><body><p> Click <a href=\"GUI.html\">here</a> to go back to selection page</p>";
exit();
}

if ($userfile=="Choose data file")
{
echo "no data file choice";
echo "<html><body><p> Click <a href=\"GUI.html\">here</a> to go back to selection page</p>";
exit();
}
/* Include and call the setup functions for use with document grammar and data parsers  */

include('tagparser.php');  //includes ContentParser function
include('dataparser.php'); //includes GetConfig function
getConfig($userfile);
loadTags();
loadVars();

/*  Parse category tags that are similar to BNF syntax with data tags as the terminal tags */

ContentParser($myDoc); //replace shotcut tags in recipe - do this before running RTF CodesParser.  3 iterations = 4 tags deep (top down)
ContentParser($myDoc);// alternative is to do bottom up parses as tags are loaded
ContentParser($myDoc);
DataParser($myDoc); //replaces variable tags with contents of data/mydata.ini or data file. Putting it here should exclude conflict with content of files or RTF

/*
WRITE PRODUCED TXT FILE LOCALLY
*/
include('writeTXTlocal.php');

/*
Make an RTF file from the tagged text file
*/
include('RTFfunctions.php');  //includes CodesParser and all relevant functions
$RTFdata=""; //This variable holds the string data for the RTF document to be created

//DO TXT2RTF
RTFsetup();
CodesParser($myDoc);  //replaces markup in text file with suitable RTF-based paragraph and outline numbering code

makeRTFheader (); //start of RTF file data :: $RTFdata.
$RTFdata=$RTFdata.$myDoc."}";


/* EMAIL OPTION
If you are using a server with SMTP server then you can email to a specified email address by uncommenting the next line */
//include('mailer.php');

/* LOCAL COPY OPTION
If using a local server, this will save the document as RTF mydoc.rtf into local folder */
include('writeRTFlocal.php');

echo "<br>All done";
?>
