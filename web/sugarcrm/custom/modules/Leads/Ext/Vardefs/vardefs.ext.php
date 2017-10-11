<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2011-02-14 13:15:03
$dictionary['Lead']['fields']['status']['default']='13';



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

 


 // created: 2012-05-30 14:53:43
$dictionary['Lead']['fields']['last_name']['required']=false;

 

 // created: 2012-05-30 13:10:21
$dictionary['Lead']['fields']['lead_source']['default']='2';
$dictionary['Lead']['fields']['lead_source']['audited']=false;
$dictionary['Lead']['fields']['lead_source']['options']='lead_source_list';

$dictionary['Lead']['unified_search']=false;
$dictionary['Lead']['unified_search_default_enabled']=false; 
?>
