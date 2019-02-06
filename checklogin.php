<?php
/* check if password and username are valid and open up menu items as per permission file
@created 10 January 2016
@revised 10 January 2016
*/
//Input values
$username=$_POST['username']; //set%myDoc to whatever starting <category>  or recipe name is submitted
$pword=$_POST['pword'];
if($pword=="")
{echo "no password";
echo "<html><body><p> Click <a href=\"login.html\">here</a> to go back to login page</p>";
exit();
}
else {echo "password entered <br>";}
//attempt to open and parse user input
$userdata=array();
if(!$filename='login/'.$username.'.php') 
{
echo "filename $filename invalid<br>";
echo "<html><body><p> Click <a href=\"login.html\">here</a> to go back to login page</p>";
exit();
}
if(!$userdata=parse_ini_file($filename)) 
{
echo "login failed<br>";
echo "<html><body><p> Click <a href=\"login.html\">here</a> to go back to login page</p>";
exit();
}
else {echo "parsed file OK<br>";}
//check if pword is correct
if($userdata["pwa"]==$pword)
{
echo "login success<br>";
$userdoclist=$userdata["docmenu"];
if(!$userdatalist=$userdata["datamenu"]) {
echo "unspecified data failure<br>";
echo $userdata ["datamenu"];
exit();
}
//sessionstart
//session_name("MDXsession");
session_start();
$_SESSION["userdocs"]=$userdoclist;
$_SESSION["userdata"]=$userdatalist;
$_SESSION["username"]=$username;
//exit();
//current window becomes the URL
$url="GUI.html";
if(!header("Location: $url")) {
echo "Cannot open GUI<br>";
exit();
}
}
else {echo 'login failed',$keys["pwa"];
echo "<html><body><p> Click <a href=\"login.html\">here</a> to go back to login page</p>";
exit();
}

//echo "<br>Recipe List: ",$keys[0],'<br>'; 
?>