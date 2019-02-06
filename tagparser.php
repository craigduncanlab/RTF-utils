<?php
/*

---- THE TAGS PARSER ---- (case sensitive)
@author Craig Duncan
@created  December 2015
@revised 7 Januay 2016

For parsing category tags in a text document.  

*/

function loadTags ()
{
global $TAG,$TAGNAME,$TAGNUM;

$tagfiles=array();
$scanfiles=array();
//$getdir=getcwd(); //get current working directory
//echo $getdir;

$DIR='../mojodox/content';
//echo $directory;
$scanfiles=scandir($DIR); //retrieves only filename without path
$scancount=count($scanfiles);

//keep only text files
$tagid=0;
for ($loop=0;$loop<$scancount;$loop++)
	{
	//echo $scanfiles[$loop]," \n";	
	if (substr($scanfiles[$loop],-3)=="txt") {
		$tagfiles[$tagid]=$scanfiles[$loop];
		$tagid++;
		}
	}
$TAGNUM=count($tagfiles); //elements in array
//echo "Number of tag files:$TAGNUM \n"; //count =8 means entries are 0 to 7
$getdir=getcwd();
chdir($DIR);  //change to tags subfolder
for ($loop=0;$loop<$TAGNUM;$loop++)
	{
	$filename=$tagfiles[$loop];
	
	$fp = fopen($filename,'r');
	$firstline=fgets($fp);
	$headlen=strlen($firstline);
	$length=filesize($filename)-$headlen;
	$TAGNAME[$loop]=substr($firstline,0,($headlen-1)); //TAGNAME IGNORES LAST CHARACTER ON FIRST LINE (e.g. line feed chr(0A))
	$TAG[$loop]=fread($fp,$length); //read rest of file (substitution text) into $TAG array
	//echo "$loop,$TAGNAME[$loop],$TAG[$loop] \n";
	fclose($fp);
	}
chdir($getdir);
echo "Tags Loaded <br>";
}

/*
Parser function for replacement tags defined in txt files in 'content' folder
*/

function ContentParser (&$Parse)
{
	
global $TAG,$TAGNAME;
$Parse=str_replace($TAGNAME,$TAG,$Parse);
echo "Content Parser loop completed<br>";
return $Parse;
}

?>
