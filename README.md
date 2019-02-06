<<<<<<< HEAD
README

MOJODOX documentation by Craig Duncan
Most of the program so far was written starting before 2015, but large update during Dec 2015-January 2016, before I finished off last part of my B.Sc.
[This guide written 12.2.17]

This is a prototype program, written in PHP.  It is running on a local Apache Server.  
The aim is to produce a program able to create a modular document assembly program, from text files with tags.  
It will merge document templates with user data.
The file output is intended to be Word-readable RTF document, with pre-defined styles and outline numbering, from text files with tags.  

Text files used for input will contain tags to indicate formatting.  At the moment, this has to be hand-coded in, but it is intended to be relatively simple, like HTML tags.
The program is designed to be modular, in the sense that blocks of text (clauses or other objects like an execution clause) can be defined for a document, and the program will expand each as required.
Current specific data:
At present there are client logins, stored client data for the existing document types, and custom menus for each client that specify the documents available to that client, plus the data files available to that client.  For example, a client may have access to a lease precedent, but may be able to choose which data file they want to use with that precedent.
Output:
 It will save all generated documents to a file called mydoc.rtf in the mojodox folder.  It also saves a .txt file which is the complete file before RTF processing.
 Email:
 This is a small addition (see mailer.php), but at present it is not active and just saves to the local folder.


Further work is needed to create menus for editing the contents of the clauses using the browser.  At the moment they can be edited by hand in a text editor.

-------------------
Initialisation files:

These define the files that define the pre-set menues
See ClientMenus folder.
Generally in JSON format.

-------------------
Logins/pw  [These select the pre-set menus for each person]]

TZMI bgriffin
leaseequity jim
lathlain strive
demo guest
admin duncan

-------------------
Main programs:

login.html - presents login screen and calls checklogin.php.  If login is OK, calls GUI.html
GUI.html - first page to present options and calls main.php when submit button is pressed.
main.php
[If there are no errors in the first document selected, it then does these things:
(a) processes the text file by replacing all tags in 3 iterations, to produce the output text file with tags]
(b) creates and RTF format in memory from the processed text file
(c) writes RTF and TXT to disk.
{nb: it has an option to include an email mailer as well, by calling mailer.php}
mailer.php (not used yet)
[contains address details, and will incorporate the attachment into a mail message and send]
checklogin.php
[checks login and creates a session for this user]
tagparser.php
[Processes files in the 'content' folder and finds all the tags used]
dataparser.php 
[Processes files in the 'data' folder and finds all the tags used for terminal data.  i.e. <tag> intended for insertion of text with no further replacement intended.]
RTFstyles1.php
RTFfunctions.php
[contains most of the functions that translate text tags in the processed file into an RTF file readable in Word]
writeTXTlocal.php 
[This program writes all of the final content of the file, with just the style tags left in to show what will be passed to the RTF program for final output.  In this form, it is not dissimilar to a file that could be used as the body of an HTML page]
writeRTFlocal.php
[The main program to turn a tagged text file into an RTF document]
-------------------

Folders

Content-->holds specific clauses.  The program tagparser.php reads in the contents of this folder, and stores all the tags and contents for later.  At present it does not use the actual name of the file, but instead uses the name given on the first line.  This means that if the filename changes, there is no consequence.  On the other hand, if there is more than one file with the same tag name there could be conflict.  Some checking for duplication could be introduced.
Recipes-->
Data-->Client specific data for all relevant documents in client-specific menu (JSON file)
Client Menus--> Contains .ini files for the menus.  The .ini files contain a list of what displays in the dropdown, and a reference to the document type.  The document type is simply a text file in the 'content' folder, like any other clause, except that it contains a list of tags that are the specific clauses that you want to include.  

Login-->Contains password details.  Also specifies what to show in the two dropdowns: 1) the documents available 2) The data files available

-------------------
Textfile contents.

These can contain any texts, and a <tag> that indicates it should be replaced with text from another file in the contents folder. 
This will replace text, up to about 4 layers deep. (i.e. it can keep opening tags that appear in sub-clauses to about 4 layers).
At present these do not operate like XML: text to be replaced is indicated by a single <tag> rather than being defined with opening and closing tags like an XML block,

Reserved names for a <tag> items are those that names used for specific data in the files in the data folder.
Any tags that refer to the tags in data folders are 'terminating', in the sense that it will replace the contents with the specific text in that data folder.  It will not go further.

The common formatting tags you can use in a textfile are explained below.

-------------------
Formatting tags for files in the content folder.

In .txt files in the content folder, a <tag> should either be:
1) A <tag> with the name (?) of another file of text in the content folder; or
2) A pre-defined text style tag (see below).

-------------------
Text style tags (for files in content folder)

These text style tags will look like they have a similarity with html tags.  They are a kind of mix between html and custom tags.  Only the opening tag is needed.
We do not need to check for end tags because the program will only change tags when it encounters another style tag.
Paragraph formatting like <p> and <kn> can be used together, just like selecting those options in Word.

At present, it will retain the same formatting across multiple lines until it gets to another paragraph or heading tag.  This means you can just put in a tag for the style you want, then just type text on the next line and it will create that next line with the same style until you change it.
e.g. The following has 3 lines all with the heading 3 <h3> style, then it reverts to heading 2.
<h3>Comply with all policy decisions of the P & C Canteen Committee as communicated by the Chair of that sub-committee or a delegate of the President.Be subject to the lawful directions of the Chair of the P & C Canteen Committee.Inform the Chair of the P & C Canteen Committee if unable to undertake duties on a particular day due to illness or other reasons.
<h2>The Canteen Manager agrees to be responsible for the daily operation of the school canteen. The duties are to:


Key:  (in general see RTFfunctions.php file where it sets up arrays for replacing these text tags with pre-defined strings for the RTF documents, and combinations of RTF strings)
-----
<h1> Heading 1 in the predefined style
<p>paragraph text
<n>
<kn>keep with next
<pb> page break

Text style tags (a little bit like how WordPerfect worked)
<cen>centred
<u>underline </u> end underline
<b>bold  </b> end bold
<i>italics
<tab>a tab

Tables:
<tr> Table row
<tc> Table cell
<cellnobord>
<cellsign>


-------------------
Notes on execution clauses:

These ultimately rely on tables, but for simplicity with RTF production, I have a hierarchy of tags, so that generally you just need to refer to something like <corpexec> to get a corporate execution clause and the program will take care of the rest.

<corpexec> relies on <partyexec>
In turn <partyexec> relies on <CompanySign>, which is the file that contains the specific formatting for the table rows and cells.
To further simplify how that is written (having regard to RTF requirements), <CompanySign> includes some references that are specific to table cells, but should not be needed by most users.
<cellnobord>
<cellsign>

-------------------
RTF styles information.

RTFstyles1.php contains a lot of the information and some further detailed explanation of how Word versions work with styles and what RTF readers can/cannot do.
RTFfunctions.php contains the code that helps with inserting detailed RTF information for cells for tables.  This saves the need for further <tag> definition in the content files.

TO DO:
Interfacing so that this program could be re-written in another language but keep the same interface structure.
Consider if different kinds of tags or XML would work better to distinguish between definitions, clauses, defined terms within clauses.

=======
# mojodox
>>>>>>> 88aae7ea9522c7a93d050c44823afd9ef474a56a
