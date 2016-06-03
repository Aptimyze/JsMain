<?php

require '../src/facebook.php';
# We require the library  
  
# Creating the facebook object  
$facebook = new Facebook(array(
'appId' => '111253005617820',
'secret' => 'f6bd8c0d1277a637f578b2cd8fdc0edc',
'cookie' => true
));

if(is_null($facebook->getUser()))
{
header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
exit;
} 

# Let's see if we have an active session 
$session = $facebook->getSession(); 
//print_r($session);


$xx="https://graph.facebook.com/".$session['uid']."?access_token=".$session['access_token'];
echo $xx;
?>
