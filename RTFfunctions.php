<?php
/**
* This program is intended to make a complete data file with RTF from a marked-up text file
* Mark-up tags recognised:<h1> to <h4>  (headings 1 to 4), <p> (plain text) and <b> (bold), <pb> for page break
@parameter 
 @author Craig Duncan
 @created 
 @updated 8 January 2016
*
* Functions that require use of global variables must use the 'global' keyword to declare/pass them within the function.
*
*
*/

function addString ($I) //This function adds a string to the default data String ($RTFdata)
{
global $RTFdata;
$R=PHP_EOL;
$RTFdata=$RTFdata.$R.$I;
return;
}

/*
Document <header>--><docfmt>

<ansi>::ansi format, 
<viewkindN>: (1= pagelayout, 0 = none)
<defN>: default font is number 3 in this font table, 
<deflangN>: Engl Australian
<doctypeN>: doctype0=general (not letter or email)
<\noultrlspc> don't underline trailing spaces (for Jap compat))
*/
function makeRTFstart()
{
global $RTFdata,$basicRTF,$fontTable,$colTable;

$basicRTF='{\rtf1\ansi\stshfloch3\deff3\deftab720\deflang3081\defformat\doctype0\viewkind1\noultrlspc'; 
//in font table fprq2 = variable fonts, fprq0 = fixed width (pitch) fnil = no font family for that font fcharset0 = default?
$font0='{\f0 \froman\fprq2\fcharset0 Garamond;}';
$font1='{\f1 \froman \fprq2 Lucida Sans;}';
$font2='{\f2\fswiss\fprq2\fcharset0 Arial;}';
$font3='{\f3\fswiss\fprq2\fcharset0 Arial;}'; 
$font4='{\f4\fswiss\fprq2\fcharset0 Arial;}';
$fontTable='{\fonttbl'.$font0.$font1.$font2.$font3.$font4.'}';
$colTable='{\colortbl;\red0\green0\blue0;\red128\green128\blue128;}';
//in theory <footer> could be parsed and inserted when needed but this approach works
//$footer='{\footer\pard\qr Page \chpgn  of {\field{\*\fldinst  NUMPAGES }}\par}';  <----right aligned, with text
$footer='{\footer\pard\qc \chpgn \par}';  //centred with just page number.  Word can also do this just with fields 
$footerf='\titlepg{\footerf\pard\qc \par}'; // titlepg, footerf needed for first page footer with no page number
$RTFdata=$basicRTF;
 addString ($fontTable);
 addString ($colTable);
 addString ($footer); 
 addString ($footerf);

 return $RTFdata;
}

function SetRTFstyles ()
{ 
include('RTFstyles1.php');
}

/*
Stylesheet production for <header>
nb: the 'snext' codeword in RTF only useful once document is opened up in Word or RTF editor
*/

function makeStyleSheet ()
{
global $IndentStyle,$BaseStyle,$Base2Style,$H1style, $H2style, $H3style, $H4style;

$TrowStyle='\ql \li0\ri0\trkeep\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tblind0\tblindtype3\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv'; 
$SF=array();
$SF[0]="{\stylesheet";
$SF[1]='{\s1'.$BaseStyle.'\snext1 Base;}';
$SF[2]='{\s2'.$Base2Style.'\snext2 BaseNorm;}';
$SF[3]="{\s9".$H4style."\sbasedon1\snext9 SDlvl 4;}"; 
$SF[4]="{\s10".$H3style."\sbasedon1\snext10 SDlvl 3;}";
$SF[5]="{\s11".$H2style."\sbasedon1\snext11  SDlvl 2;}"; //name the style, add the paragraph info, then the based on, next and name 

$SF[6]="{\s12".$H1style."\sbasedon1\snext11 SDlvl 1;}"; //next para style will be H2
//table style default
$SF[7]="{\s25".$IndentStyle."\sbasedon1\snext1  Indent;}"; //name the style, add the paragraph info, then the based on, next and name 
//$SF[8]='{\ts13\tsrowd'.$TrowStyle.' \snext13 Table;}';
$SF[8]='{\ts13\tsrowd'.$TrowStyle.' \snext13 Table;}';

//var_dump($SF);
for ($st=0;$st<9; $st++) 
{
$styledata=$styledata.$SF[$st];
}
$endBrace="}";
$styledata=$styledata.$endBrace;
addString($styledata);
return;
}

/*
 Create hybrid-list table for  RTF <header>, compatible Word 2000 onwards.  First list level is ilvl0 in the styles 
*/

function makeListTable ()
{
$listtype="\list\listtemplateid1\listhybrid\listid1"; 
$listTable="{\*\listtable{".$listtype; 
$level=array();
$level[1]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid21 \'02\'00.;}{\levelnumbers\'01;}\b\\f3\\fi-709\li709\s12}"; 
$level[2]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid22 \'03\'00.\'01;}{\levelnumbers\'01\'03;}\\f3\\fi-709\li709\s11}";
$level[3]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid23 \'03(\'02);}{\levelnumbers\'02;}\\f3\\fi-709\li1418\s10}";
$level[4]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid24 \'03(\'03);}{\levelnumbers\'02;}\\f3\\fi-709\li2126\s9}";
$level[5]="{\listlevel\levelnfc3\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid25 \'03(\'04);}{\levelnumbers\'02;}\\f3\\fi-709\li2835}";
$level[6]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid26 \'03(\'05);}{\levelnumbers\'02;}\\f3\\fi-709\li3544}";
$level[7]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid27 \'02\'06.;}{\levelnumbers\'01;}\\f3\\fi-709\li709}";
$level[8]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid28 \'03(\'07);}{\levelnumbers\'02;}\\f3\\fi-709\li1418}";
$level[9]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid29 \'03(\'08);}{\levelnumbers\'02;}\\f3\\fi-708\li2126}";
$listEnd="{\listname Headings2;}}}";

/*

//simple list with 9 levels read/write compatible with Word 1997 onwards
$listtype="\list\listtemplateid1\listsimple0\listid1";  
$listTable="{\*\listtable{".$listtype; 
$level=array();
$level[1]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0 \levelnorestart0{\leveltext \'02\'00.;}{\levelnumbers\'01;}\b\\f3\\fs24\\fi-709\li709\s12}"; 
$level[2]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03\'00.\'01;}{\levelnumbers\'01\'03;}\\f3\\fi-709\li709\s11}";
$level[3]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'02);}{\levelnumbers\'02;}\\f3\\fi-709\li1418\s10}";
$level[4]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'03);}{\levelnumbers\'02;}\\f3\\fi-709\li2126\s9}";
$level[5]="{\listlevel\levelnfc3\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'04);}{\levelnumbers\'02;}\\f3\\fi-709\li2835}";
$level[6]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'05);}{\levelnumbers\'02;}\\f3\\fi-709\li3544}";
$level[7]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'02\'06.;}{\levelnumbers\'01;}\\f3\\fi-709\li709}";
$level[8]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'07);}{\levelnumbers\'02;}\\f3\\fi-709\li1418}";
$level[9]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0\levelnorestart0{\leveltext \'03(\'08);}{\levelnumbers\'02;}\\f3\\fi-708\li2126}";
$listEnd="{\listname myList1;}}}";

for ($lt=1;$lt<10;$lt++) 
{
$listTable=$listTable.$level[$lt];
}
$listTable=$listTable.$listEnd;
addString($listTable);

*/



for ($lt=1;$lt<10;$lt++) 
{
$listTable=$listTable.$level[$lt];
}
$listTable=$listTable.$listEnd;
addString($listTable);

}

/**
* 
* A function to make a list override table
* 
* The list override table is important because a formatting override allows a paragraph to be part of a list and to be numbered along 
* with the other members of the list, but to have different style & formatting properties
* see http://www.biblioscape.com/rtf15_spec.htm#Heading21
* This particular list override overrides 'listid1' list in list table but does nothing; this override index is ls1 
* It preserves the outline numbered list with 9 levels defined by listid1 (due to listoverridecount0?)
*
*/

function makeListOR()
{
	global $listOR2;
	$listOR2="{\*\listoverridetable{\listoverride\listid1\listoverridecount0\ls1}}"; 
	addString($listOR2);
}

/**
* -------------------INFORMATION (PROPERTIES) TABLE DATA FORMAT: STD ENTRY -----------------------
*
*/

function makeInfoTable()
{

$operator='{\operator Craig Duncan}';
$author='{\author Craig Duncan}';
$title='{\title Generic Document}';
$keywords='{\keywords Document Suite}';
$docnotes='{\doccomm (c) 2016 Craig Duncan}';
$docsubj='{\subject }';
$doccomp='{\company Craig Duncan, Perth WA}';
$doctime='{\creatim\yr2016\mo1\dy04\hr15\min18}'; 
$infoTable="{\info".$operator.$author.$title.$keywords.$docnotes.$docsubj.$doccomp.$doctime.'}';
	addString($infoTable);
}

/**
* -------------------WRITE THE  <HEADER> SECTION  FOR THE RTF FORMAT -----------------------
*
*  This function needs to be included at end because (like c language) it needs to refer to functions already specified earlier in file
* write the <header>  sub-categories in sequence

* RTF file syntax: {<header><document}
open first { here and close it with } when all text has been added and ready for RTF parsing.
<header> can include <docfmt>
*/

function makeRTFheader ()
{
makeRTFstart();
makeStyleSheet();
makeListTable();
makeListOR();
makeInfoTable();
return;
}

function SetRTFparas()
{ 
global $FontSet,$T1,$Indent1,$Base1,$Base2,$Base2Style,$Cent1,$H1,$H2,$H3,$H4,$IndentStyle,$BaseStyle,$PCstyle,$H1style, $H2style, $H3style, $H4style,$CellSigning,$CellNoBord,$tableEnd;
if ($FontSet=='10') {
$linespace='\sl200'; //200 tw = 10pt = size of default font in pts
$multiplelines='\slmult1';
$fontSize='\f3\fs20'; //fs is in half pts i.e. fs23=11.5pt
}
if ($FontSet=='12') {
$linespace='\sl240'; //240 tw = 12pt = size of default font
$multiplelines='\slmult1';
$fontSize='\f3\fs24';
}

/*

-----  PARAGRAPHS NEED REPEATED STYLE INFORMATION ----
In the RTF grammar, we include within the $H1 paragraph 'sentence' the RTF words for the $H1style.  This is necessary to pick up the font and other information for that paragraph, and so that the reader can find it in the <document> independently of what is shown in the style library in the RTF <header>

*/

$plainLTRpara='\pard\plain \ltrpar'.$fontSize;
$Base1=$plainLTRpara.'\s1'.$BaseStyle;
$Base2=$plainLTRpara.'\s2'.$Base2Style;
$Cent1=$plainLTRpara.'\qc\s1'.$Basestyle;
$Indent1=$plainLTRpara.'\s25'.$IndentStyle;
$H1=$plainLTRpara.'\s12'.$H1style;//we now have a string with the paragraph codes and H1 style codes, except the end para character
$H2=$plainLTRpara.'\s11'.$H2style;
$H3=$plainLTRpara.'\s10'.$H3style;
$H4=$plainLTRpara.'\s9'.$H4style;
$T1='\trowd\ltrrow \trkeep\trkeepfollow\plain '; //\ts13';
echo $T1, "<---T1 defined<br>";
makeCellBorders();
$tableEnd=$plainLTRpara;
}

function signBorder()
{
$cellalign="\clvertalt"; //vertical align top
$celltb="\clbrdrt"; //cell border top
$cellbb=" \clbrdrb";  //cell bottom border keyword
$celllb=" \clbrdrl";  //cell left border keyword
$cellrb=" \clbrdrr"; //cell right border keyword
$cellbdot="\brdrdot\brdrw10"; //cell border dots; width 10 twips (max 255)
$cellnobrd="\brdrtbl"; //table cell no border line
$cellflow=" \cltxlrtb"; //cell text flows left to right and top to bottom
$cellnoshade=" \clshdrawnil"; //no cell shading specified
$signdots=$celltb.$cellbdot.$celllb.$cellnobrd.$cellrb.$cellnobrd.$cellbb.$cellnobrd.$cellflow.$cellnoshade;
return $signdots;
}

function noBorder()
{
//leading spaces are important for cell format code words
$cellalign="\clvertalt"; //vertical align top
$celltb="\clbrdrt"; //cell border top
$cellbb=" \clbrdrb";  //cell bottom border keyword
$celllb=" \clbrdrl";  //cell left border keyword
$cellrb=" \clbrdrr"; //cell right border keyword
$cellbdot="\brdrdot\brdrw10"; //cell border dots; width 10 twips (max 255)
$cellnobrd="\brdrtbl"; //table cell no border line
$cellflow=" \cltxlrtb"; //cell text flows left to right and top to bottom
$cellnoshade=" \clshdrawnil"; //no cell shading specified
$nobox=$cellalign.$celltb.$cellnobrd.$celllb.$cellnobrd.$cellrb.$cellnobrd.$cellbb.$cellnobrd.$cellflow.$cellnoshade;
return $nobox;
}

/*

Create simple tags to use wth parser for table creation.
These create strings to hold cell border codes to insert into cell formating string

*/

function makeCellBorders()
{
global $CellSigning,$CellNoBord;

//cell with signing dots on top border with normal 'flow'
$signing=signBorder();
//no border lines around cell
$nobox=noBorder();

/*
cellx sets the width of cells (you need to make these the same for cells in same column of table)
The following codewords will work in Word97 (and more recent) to set table cell right boundary widths.
However, the codes for preferred cell width might be needed for some non-Word RTF readers/editors
*/
$cx1=" \cellx3565";   //15 twips = 1 px. cellx0 (zero) causes autofit
$cx2=" \cellx3910"; 
$cx3=" \cellx8011"; 
//Combine cell definitions and widths -  all 3 must appear at start of table row before cells
$CellSigning=$signing.$cx1.$nobox.$cx2.$signing.$cx3;  
$CellNoBord=$nobox.$cx1.$nobox.$cx2.$nobox.$cx3;
}

/* 
 --- Create arrays to hold values to swap using CodesParser
  This is used for the final conversion of the text-based markup to RTF
   Used for the <document> part of RTF file.
   On line endings all should be catered for.  For windows options (not needed in this instance) see http://php.net/manual/en/function.fopen.php
*/

function setRTFnames ()

{
	global $toggle,$StyleMarkup,$TableMarkup;
	global $Indent1,$Base1,$Base2, $Cent1,$H1,$H2,$H3,$H4,$tableEnd,$inTbl,$footer,$T1,$myRowEnd,$CellSigning,$CellNoBord,$lastRow;

$DOSLF=chr(13).chr(10); //DOS line ending -2 chars
$DOSOUT= '\par'.$DOSLF;
$LF= '\par'.chr(10); //preserve LF in RTF if you want to examine in txt editor
$CR= '\par'.chr(13);
//array for swap values not used elsewhere 
$toggle=array(
'<tab>' => chr(9),
'<b>'=>'\b ',
'</b>'=>'\b0 ',
'<i>'=>'\i ',
'</i>' =>'\i0 ',
'<u>'=>'\ul ',
'</u>'=>'\ul0',
$DOSLF=> '\par',
chr(13)=>$CR,
chr(10)=>$LF,
'</cen>'=>'\par ',
'</p>'=>'\par',
'</n>'=>'\par',
'</h>'=>'\par',
'<pb>'=>'\page ',
'<kn>'=>'\keepn ',
);

//new paragraphs with defined styles nb: no need for braces around these.  \par or \row inherits previous paragraph formatting (including intbl) unless \pard word is read
$StyleMarkup=array(
'<pi>'=>$Indent1,
'<cen>'=>$Cent1,
'<p>'=>$Base1,
'<n>'=>$Base2,
'<h1>'=>$H1,
'<h2>'=>$H2,
'<h3>'=>$H3,
'<h4>'=>$H4,
);

$TableMarkup=array(
'<tc>'=>'\intbl ',
'</tc>'=>' \cell ',
'<tr>'=>$T1,
'</tr>'=>'\row',
'<lr>'=>'\lastrow \row ',
'</table>'=>$tableEnd,
'<cellsign>'=>$CellSigning,
'<cellnobord>'=>$CellNoBord,
);

}

function CodesParser (&$Parse)
{
global $toggle,$StyleMarkup,$TableMarkup;

$tagin=array_keys($toggle);
$RTFout=array_values($toggle);
$Parse=str_replace($tagin,$RTFout,$Parse);

$tagin=array_keys($StyleMarkup);
$RTFout=array_values($StyleMarkup);
$Parse=str_replace($tagin,$RTFout,$Parse);

$tagin=array_keys($TableMarkup);
$RTFout=array_values($TableMarkup);
$Parse=str_replace($tagin,$RTFout,$Parse);

return $Parse;
}

function RTFsetup() {
	
global $IndentStyle,$BaseStyle,$Base2Style,$H1style, $H2style, $H3style, $H4style;
SetRTFstyles();
SetRTFparas();
setRTFnames();
	}

?>