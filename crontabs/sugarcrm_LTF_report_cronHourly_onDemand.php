<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db2=connect_db();
$db =connect_737();
mysql_query("set session wait_timeout=600",$db2);
	//************************************    Condition after submit state  ***************************************
		$usernameArr 	=array('ZUUA4111'=>'salman.khan','ZUTT3927'=>'deepak.sharma','ZUWU1391'=>'namita.david','ZUSZ2401'=>'hemlata.chavhan','ZUSX1045'=>'namita.david','ZUSY8891'=>'savita.chouhan','ZUSW0535'=>'bharti.kutwal','ZUSX5827'=>'savita.chouhan','ZUSX7607'=>'vandana.p','ZUWY5775'=>'salman.khan','ZUSW7797'=>'namita.david','ZUSV9563'=>'savita.chouhan','ZUSV8435'=>'vandana.p','ZUVW8803'=>'namita.david','ZUUZ4125'=>'savita.chouhan','ZUSU7089'=>'vandana.p','ZUSS0405'=>'salman.khan','ZUSS0909'=>'hemlata.chavhan','ZUVV0229'=>'vandana.p','ZURA6523'=>'vandana.p','ZUWW8889'=>'divya.p','ZUVV3323'=>'pooja.s','ZUXR0953'=>'Preeti.bhojne','ZURY8283'=>'deepak.sharma','ZURX9857'=>'namita.david','ZURW8947'=>'bharti.kutwal','ZUWA4791'=>'namita.david','ZUWU2301'=>'divya.p','ZUXZ5439'=>'salman.khan','ZURV7867'=>'preeti.bhojne','ZUWR4947'=>'pooja.s','ZURU5843'=>'pooja.s','ZUWR5183'=>'deepak.sharma','ZURS5445'=>'pooja.s','ZUWA2325'=>'preeti.bhojne','ZUXR7955'=>'deepak.sharma','ZUWR7379'=>'deepak.sharma','ZUVZ3161'=>'deepak.sharma','ZUVZ5527'=>'deepak.sharma','ZUWT4681'=>'deepak.sharma','ZURR3615'=>'deepak.sharma','ZUWV7593'=>'deepak.sharma','ZUWY2777'=>'deepak.sharma','ZUXR7257'=>'deepak.sharma','ZUXT5719'=>'deepak.sharma','ZUXT2833'=>'deepak.sharma','ZUXT2497'=>'deepak.sharma','ZUVA3697'=>'hemlata.chavhan','ZUWV1787'=>'hemlata.chavhan','ZUWW9133'=>'hemlata.chavhan','ZUWA4449'=>'hemlata.chavhan','ZUXR4019'=>'hemlata.chavhan','ZUVW6727'=>'divya.p','ZURR2107'=>'divya.p','ZURR5263'=>'divya.p','ZURR5905'=>'divya.p','ZTAA4903'=>'divya.p','ZUVY5893'=>'pooja.s','ZUVV0923'=>'pooja.s','ZURR2581'=>'pooja.s','ZURR4411'=>'pooja.s','ZURR5839'=>'pooja.s','ZTAA2883'=>'pooja.s','ZUVZ7563'=>'salman.khan','ZUVX4931'=>'salman.khan','ZURR3381'=>'salman.khan','ZURR3575'=>'salman.khan','ZURR3887'=>'salman.khan','ZUZT2749'=>'salman.khan','ZUWW9663'=>'salman.khan','ZUXU3203'=>'salman.khan','ZUXU5283'=>'salman.khan','ZURR5819'=>'salman.khan','ZUZV7173'=>'salman.khan','ZUYA8407'=>'bharti.kutwal','ZUYS5821'=>'bharti.kutwal','ZURR2343'=>'bharti.kutwal','ZUXW2565'=>'bharti.kutwal','ZURR4079'=>'bharti.kutwal','ZURR4901'=>'bharti.kutwal','ZURR5537'=>'bharti.kutwal','ZUXV0943'=>'bharti.kutwal','ZTAA2187'=>'bharti.kutwal','ZUUA3801'=>'namita.david','ZUWY3239'=>'namita.david','ZUWV5581'=>'namita.david','ZURR5317'=>'namita.david','ZURR5501'=>'namita.david','ZUUX1261'=>'namita.david','ZUYW3497'=>'namita.david','ZURR1307'=>'damini.verme','ZURR2189'=>'damini.verme','ZUWW6341'=>'damini.verme','ZURR4889'=>'damini.verme','ZUXV0829'=>'damini.verme','ZUUY2505'=>'savita.chouhan','ZUZU7107'=>'savita.chouhan','ZUVZ5497'=>'savita.chouhan','ZUWR6469'=>'savita.chouhan','ZUUY5513'=>'savita.chouhan','ZURR4163'=>'savita.chouhan','ZUXS9577'=>'savita.chouhan','ZUWA0613'=>'savita.chouhan','ZUWY7445'=>'savita.chouhan','ZUWY5699'=>'savita.chouhan','ZUZS8429'=>'vandana.p','ZUWY0717'=>'vandana.p','ZUUX2669'=>'vandana.p','ZURR3927'=>'vandana.p','ZURR4921'=>'vandana.p','ZUXZ6531'=>'vandana.p','ZUWA2325'=>'preeti.bhojne','ZURR1929'=>'preeti.bhojne','ZURR3929'=>'preeti.bhojne','ZURR5669'=>'preeti.bhojne','ZURR6197'=>'preeti.bhojne','ZTAA2807'=>'preeti.bhojne','ZUZU5537'=>'namita.david','ZTAZ2107'=>'preeti.bhojne','ZTAZ1703'=>'vandana.p','ZURZ6523'=>'bharti.kutwal','ZTAX0617'=>'salman.khan','ZTAX0803'=>'preeti.bhojne','ZUSV0661'=>'hemlata.chavhan','ZUSY3235'=>'divya.p','ZUSZ4293'=>'bharti.kutwal','ZUTY7279'=>'savita.chouhan','ZUUA9105'=>'tanzima.khan','ZTAZ0775'=>'tanzima.khan','ZTAZ1315'=>'tanzima.khan','ZURS5153'=>'tanzima.khan','ZURS0317'=>'tanzima.khan','ZURT0377'=>'aradhana.b','ZURT9141'=>'aradhana.b','ZURT8571'=>'aradhana.b','ZURS0815'=>'aradhana.b','ZURS0349'=>'kanika.t','ZTAX2169'=>'kanika.t','ZTAZ4787'=>'kanika.t','ZTAZ4063'=>'kanika.t','ZTAZ4577'=>'kanika.t','ZTAZ2763'=>'kanika.t','ZTAZ2893'=>'kanika.t','ZTAZ2983'=>'kanika.t','ZTAZ3353'=>'kanika.t','ZTAZ1523'=>'kanika.t','ZTAZ1675'=>'kanika.t','ZTAZ1829'=>'kanika.t','ZTAZ1973'=>'kanika.t','ZTAZ2131'=>'kanika.t','ZTAZ2577'=>'kanika.t','ZTAZ0867'=>'kanika.t','ZTAZ0995'=>'kanika.t','ZTAZ1063'=>'kanika.t','ZTAZ1179'=>'kanika.t','ZTAZ0625'=>'kanika.t','ZUSY3981'=>'kanika.t','ZUSV5337'=>'kanika.t','ZUSS8765'=>'kanika.t','ZUSS8865'=>'kanika.t','ZUSS9129'=>'kanika.t','ZUSR0375'=>'kanika.t');
		$exec_name_arr 	=array();
                $uname_arr      =array(); 
                $uname_str      ='';
		$type	 	='REG';
		$todayDate      =date("Y-m-d");	
		$last30Days     =date("Y-m-d H:i:s",strtotime("$todayDate -30 days"));
	
		// Get the currently active executives from the PSWRDS table
                $sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE LAST_LOGIN_DT>='$last30Days'";
                $res_unames = mysql_query_decide($sql_unames,$db) or die($sql_unames.mysql_error_js());
                while($row_unames = mysql_fetch_array($res_unames)){
                	$uname_arr[] = $row_unames['USERNAME'];
                }
		$uname_arr =array_unique($uname_arr);
                $uname_str = "'".@implode("','",$uname_arr)."'";

		if($uname_str){
			// Get the sugarcrm registered executives which are currently active in PSWRDS table 
			$sql1 ="SELECT id,user_name from sugarcrm.users where user_name in($uname_str) and id!='1'";
			$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			while($row1=mysql_fetch_array($res1)){
				$exec_name_arr[]=$row1['user_name'];
			}
		}
		foreach($usernameArr as $usernameStr=>$exec_name){
			if(!$usernameStr)
				continue;
			if(!in_array("$exec_name",$exec_name_arr))
				continue;

			$sql2 ="SELECT PROFILEID,ENTRY_DT from newjs.JPROFILE where USERNAME='$usernameStr' AND activatedKey=1";
			$res2 =mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
			$row2=mysql_fetch_array($res2);
			$profileid              =$row2['PROFILEID'];
			$dateVal 		=$row2['ENTRY_DT'];
			if($profileid!= ''){
				$sql_ins="INSERT IGNORE into MIS.LTF (`PROFILEID`,`EXECUTIVE`,`TYPE`,`DATE`,`ENTRY_DT`) VALUES('$profileid','$exec_name','$type','$dateVal',now())";
				echo $sql_ins."\n";			
				mysql_query_decide($sql_ins,$db2) or die("$sql_ins".mysql_error_js());
			}
		}


?>
