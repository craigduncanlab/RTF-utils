<?php
/*
@Action: This is a RTF styles module included in the SetRTFstyles function in file RTFfunctions.php
@author Craig Duncan
@created 2015/2016
@Revised 7 January 2016
*/

//make sure any global variables needed by functions called here are declared
global $Base1,$Indent1,$IndentStyle,$BaseStyle,$Base2Style,$H1style, $H2style, $H3style, $H4style;
$BaseStyle='\sb0\sa120\cf0 ';  //space before, space after defaults, font colour.  20 twips = 1 pt.  Final space is important
$Base2Style='\sb0\sa0\cf0 ';//space before, space after defaults, font colour
//optional:  hyphenation options (right margin breaks)  {\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}
//widow orphan control  \nowidctlpar
$parabord='\brdrb\brdrs\brdrw1\brdrcf1\brsp60{\*\brdrb\brdlncol1\brdlnin0\brdlnout1\brdlndist0}';

//STANDARD INDENT, COLOUR AND SPACING SETTINGS FOR 4 LEVELS OF PARA NUMBERING
//font size is set by setRTFpara function in RTFfunctions
$forecolor='\cf0';
$twips1='709';
$firstlineindent='\fi-'.$twips1; //this is a negative indent?
$general=$linespace.$multiplelines.$firstlineindent.$forecolor;  //linespace and multi lines not defined?
$twips2='1418';
$twips3='2127';
$indent1='\li'.$twips1.'\lin'.$twips1.'\tx'.$twips1.'\ri0\rin0'; //also lin709, rin0 defines space - not necessary?
$indent2='\li'.$twips2.'\lin'.$twips2.'\tx'.$twips2.'\ri0\rin0';
$indent3='\li'.$twips3.'\lin'.$twips3.'\tx'.$twips3.'\ri0\rin0';
$spacing1='\sb360\sa120 ';//space here is important as it forms end of style words
$spacing2='\sb180\sa120 ';
$spacing3='\sb100\sa120 ';
$lineinfo1=$general.$indent1.$spacing1;
$lineinfo2=$general.$indent1.$spacing2;
$lineinfo3=$general.$indent2.$spacing3;
$lineinfo4=$general.$indent3.$spacing3;

$H1style='\ls1\ilvl0'.$parabord.'\keepn'.$lineinfo1.'\b ';//you need a space after the \b for bold codeword and end of style word
$H2style='\ls1\ilvl1'.$lineinfo2; //optional wrap default and font alignment auto: \wrapdefault\faauto \ql left para align
$H3style='\ls1\ilvl2'.$lineinfo3;
$H4style='\ls1\ilvl3'.$lineinfo4;
$indentFont='\f3\fs24 ';  //Final space is important
//ql = align left para
$IndentStyle='\ql \li0\ri0\sb200'.$lineinfo.'\nowidctlpar\tx709\wrapdefault\faauto\rin0\lin0\itap0 \ltrch\fcs0 \b'.$indentFont;

//FOR NUMBERED PARAGRAPHS: CREATE PARAGRAPH STYLE REFERENCES TO THE OVERRIDE LIST ID1 (LS1) AND TO LIST TABLE LEVELS 0 TO 3 (ILVL0...)
/*
To produce the ability to automatically evaluate and increment paragraph numbers within a document e.g. when paragraphs are added or removed , M$ uses a detailed RTF data specification with (a) codewords for paragraphs (b) List tables that contain what M$ calls 'list levels'.

The RTF data that needs to be put into a paragraph section of the data file is limited to 2 main codewords:
(a) the codeword \ilvl followed by the level number indicates the level of a (numbered) list to use for that paragraph; and
(b) the codeword \ls followed by an ID code indicates the 'level list' data table M$Word needs to refer to in order to find the number format for that level.

The 2 codewords could be written to a paragraph directly.   More usefull, associate them with 1 or more styles. Users can then collectively  apply both numberring and formatting settings.

The \lsN reference is actually an ID in another table: the list override table.  The purpose of the list override table is to link the shorthand ID used forr styles in paragraphs with the list level table containing the paragraph list level definition.  The list level table is another table in the RTF data file which lists all the numbering formats, level by level.

LIST TABLES

The latest iteration of Word (~2008) uses what is called a 'hybrid' format list table.

(AA) Programs that want to work with RTF format need to emulate or substitute a function for handling this list level data and tables in the RTF files.  (One way this is possible if the RTF document's 'ilvl' and 'ls' codewords can be parsed paragraph by paragraph, and the program can look up the list level table.  If the document is being written by a program (not a user), it is likely the list tables are already available as arrays and could be processed as relevant data tables, not merely as strings of text written to a file so that only M$ Word can use them.

PROCESSING NUMBERING

M$ Word must use its own internal functions to make sense of the numbering level information in the RTF file.   By doing so, it saves manuall numbering and re-numbering text blocks.

Once parsed and processed, the numbers for each paragraph can be encoded and saved as a static reference into that paragraph section of the RTF file (and/or an array).  If saved within the RTF file, then a base program can avoid the need for any data processing and simply read off the calculated numbers as static text.    This is exactly the workaround that Word uses for other basic programs that do not have support for auto-numbering.  The processed numbers are also written after the '\listtext' codeword to allow the end result to be read by another program.

Alternatively, a program that can write paragraphs ready for Word to read and process can omit the \listtext static numbering and latest versions of $MSWord will not care.  But this will omit the hard-coded numbers and paragraph codes that some other basic programs rely on to read in the final numbers produced by the data in the RTF file.

Some applications can read and display the number text, without the ability to work fully with the RTF format.  OpenOffice has approached Word-level parsing functions for list level numbering, but it may not be able to parse the slightly different list level formats defined by different RTF specifications (e.g. OpenOffice 2009 could work with hybrid lists from 2000 onwards but not the slightly different simple lists format used in Word 97)

TextEdit on mac works with simple lists and itself embeds simple RTF codewords {\listtext	1.	} etc to hard-code each of the numbers in a list within the same paragraph.  To that extent, it can corrupt the paragraph codewords for level numbering (and possibly the list table) in the edited file.

Some RTF readers can apply styles, but some cannot.  This is one reason that Word puts style information into each paragraph (and only additional style properties are added when needed)

OUTLINE LEVELS 0 TO 3
3.  Specify an outline level in a paragraph (optional)
//$H1style='\ls1\ilvl0\outlinelevel0'.$parabord.'\keepn'.$lineinfo1.'\b ';//you need a space after the \b for bold codeword

The outline level code word \outlinelevelN is used for M$Word's outline viewer, and for building table of contents.
ilvl is the primary code to specify the list level that a paagraph style applies to.
An outline level can apply to more than one level.
Using Word's built in styles named 'Heading 1' to 3 will maintain similar outline levels whether you specify them or not.
For this reason, giving your styles a different name to Heading 1 to 3 may give more flexible outlining options.
*/


?>
