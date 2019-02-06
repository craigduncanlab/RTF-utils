<?php
//last updated 28 December 2015
//echo "<html><head></head><body><p>Ready to start.  :)</p>";
//consider outputting to email and also creating an RTF registration form (partially completed)

$data=$RTFdata;
//specify names used in email - these do not have to match the original file but helps
$attachments[] = Array(
               'data' => $data,
               'name' => 'precdoc.rtf',
               //'type' => 'application/vnd.ms-excel'
               'type' => 'application/msword'
            );


$text="A new RTF document has been created (attached).";
$to="craig.duncan@westnet.com.au";  //query whether to prompt for email address
$from = "Craig@TheWeb";
$subject = "New RTF doc from Craig";


//echo "<p>Variables done.  :)</p>";

//Generate a boundary string

            $semi_rand = md5(time());
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";


            //Add the headers for a file attachment


            $headers = "MIME-Version: 1.0\n" .
                       "From: {$from}\n" .
                       "Content-Type: multipart/mixed;\n" .
                       " boundary=\"{$mime_boundary}\"";


            //Add a multipart boundary above the plain message


            $message = "This is a multi-part message in MIME format.\n\n" .
                      "--{$mime_boundary}\n" .
                      "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
                      "Content-Transfer-Encoding: 7bit\n\n" .
                      $text . "\n\n";

//echo "<p>Boundary string done.  :)</p>";

//Add attachments

            foreach($attachments as $attachment){
               $data = chunk_split(base64_encode($attachment['data']));
               $name = $attachment['name'];
               $type = $attachment['type'];

               $message .= "--{$mime_boundary}\n" .
                          "Content-Type: {$type};\n" .
                          " name=\"{$name}\"\n" .
                          "Content-Transfer-Encoding: base64\n\n" .
                          $data . "\n\n" ;
            }

            $message .= "--{$mime_boundary}--\n";
            
//echo "<p>Message content done.  :)</p>";
            mail($to, $subject, $message, $headers);
//If this is on your local apache server what will it do?  Nothing until you install your own SMTP server! 
// nb: on remote sites it works if your ISP has already taken care of this!

?>