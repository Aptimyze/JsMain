<?php
 $dictionary['Lead']['indices'][] = 
        array('name' =>'idx_phone_mobile', 'type'=>'index', 'fields'=>array('phone_mobile'));
 $dictionary['Lead']['indices'][] = 
        array('name' =>'idx_phone_home', 'type'=>'index', 'fields'=>array('phone_home'));
$dictionary['Lead']['fields'][] =
	array('name' => 'startdate',
            'vname' => 'LBL_STARTDATE',
            'type' => 'date',
	    'source' => 'non-db',
            'massupdate' => false,
          );
$dictionary['Lead']['fields'][] =
	array('name' => 'enddate',
            'vname' => 'LBL_ENDDATE',
            'type' => 'date',
	    'source' => 'non-db',
            'massupdate' => false,
	);

?> 
