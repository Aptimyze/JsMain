<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');

    $t = new lime_test(16, new lime_output_color());
//$prod = new Producer();
while(1){
$prod = new Producer();	
$prod->sendMessage(array('process' =>'MAIL','data'=>array('type' => 'ACCEPTCONTACT','body'=>array('senderid'=>3143002,'receiverid'=>301 ) ) ));
}
