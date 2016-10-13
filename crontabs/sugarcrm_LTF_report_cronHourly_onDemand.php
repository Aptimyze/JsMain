<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");

$db2=connect_db();
$db =connect_737();
mysql_query("set session wait_timeout=600",$db2);
	//************************************    Condition after submit state  ***************************************
		$usernameArr 	=array('ZUYX2251'=>'pooja.s','ZUVW9971'=>'salman.khan','ZUVV7307'=>'bharti.kutwal','ZUVV6961'=>'divya.p','ZUVU8689'=>'pooja.s','ZUZY5195'=>'bharti.kutwal','ZUVS5023'=>'namita.david','ZUZZ5727'=>'namita.david','ZUVS9085'=>'namita.david','ZUVS6275'=>'Deepak.sharma','ZUVS4939'=>'divya.p','ZUZZ6983'=>'divya.p','ZUZY8823'=>'preeti.bhojne','ZUZZ9935'=>'Deepak.sharma','ZUZT4573'=>'divya.p','ZUUA6639'=>'ravi.lashkari','ZUZU5007'=>'ravi.lashkari','ZUUX6801'=>'salman.khan','ZUZZ7189'=>'bharti.kutwal','ZUUX5019'=>'bharti.kutwal','ZUUA8685'=>'namita.david','ZUZU1697'=>'namita.david','ZUUX6949'=>'Deepak.sharma','ZUUY2721'=>'ankita.saraf','ZUYY2587'=>'ankita.saraf','ZUAR9489'=>'Savita.chouhan','ZUUU3133'=>'bharti.kutwal','ZUTA1595'=>'damini.verma','ZUUR9467'=>'Savita.chouhan','ZUUR9199'=>'hemlata.chavhan','ZUTZ0181'=>'pooja.s','ZUAX0551'=>'damini.verma','ZUWX9295'=>'Deepak.sharma','ZUUA6117'=>'hemlata.chavhan','ZUTZ7863'=>'hemlata.chavhan','ZUTY7365'=>'divya.p','ZUUY4425'=>'hemlata.chavhan','ZUVX2269'=>'hemlata.chavhan','ZUXU2407'=>'hemlata.chavhan','ZUTY5317'=>'hemlata.chavhan','ZUWY7045'=>'hemlata.chavhan','ZUXU7519'=>'hemlata.chavhan','ZUTY7603'=>'hemlata.chavhan','ZUVY6689'=>'pooja.s','ZUXW8141'=>'pooja.s','ZUUX0513'=>'pooja.s','ZUAW5103'=>'pooja.s','ZUWR7431'=>'pooja.s','ZUVA2657'=>'pooja.s','ZUUW0681'=>'pooja.s','ZUTY7877'=>'pooja.s','ZUWY6219'=>'salman.khan','ZUUZ5967'=>'salman.khan','ZUVW8799'=>'salman.khan','ZUVV1273'=>'salman.khan','ZUXV5543'=>'salman.khan','ZUUZ3855'=>'salman.khan','ZUVZ1907'=>'salman.khan','ZUVT5881'=>'salman.khan','ZUUY3551'=>'salman.khan','ZUXA2611'=>'salman.khan','ZUWV7383'=>'salman.khan','ZUVA1525'=>'salman.khan','ZUVA3355'=>'salman.khan','ZUVT3299'=>'salman.khan','ZUXX1173'=>'salman.khan','ZUVZ9211'=>'salman.khan','ZUXZ2443'=>'bharti.kutwal','ZUXU3807'=>'bharti.kutwal','ZUUY2333'=>'bharti.kutwal','ZUXW3689'=>'bharti.kutwal','ZUTY4517'=>'bharti.kutwal','ZUVY0559'=>'bharti.kutwal','ZUUA5599'=>'bharti.kutwal','ZUTY6237'=>'bharti.kutwal','ZUXR9053'=>'bharti.kutwal','ZUXY6127'=>'bharti.kutwal','ZUVY6121'=>'bharti.kutwal','ZUWA3877'=>'damini.verma','ZUWX6409'=>'damini.verma','ZUWR8197'=>'damini.verma','ZUVW9679'=>'damini.verma','ZUUZ9303'=>'damini.verma','ZWWV4103'=>'damini.verma','ZUUX3735'=>'damini.verma','ZUUZ3427'=>'preeti.bhojne','ZUWY1697'=>'preeti.bhojne','ZUZR2887'=>'preeti.bhojne','ZUVX1763'=>'preeti.bhojne','ZUWY0515'=>'preeti.bhojne','ZUWA1887'=>'Deepak.sharma','ZUUZ4729'=>'Deepak.sharma','ZUUA6965'=>'Deepak.sharma','ZUVW8877'=>'Deepak.sharma','ZUUX6489'=>'Deepak.sharma','ZUVY4653'=>'Deepak.sharma','ZUVT4813'=>'Deepak.sharma','ZUTY4887'=>'Deepak.sharma','ZUXV1179'=>'Deepak.sharma','ZUTY5551'=>'Deepak.sharma','ZUWY0395'=>'Deepak.sharma','ZUTY6289'=>'Deepak.sharma','ZUXS7491'=>'Deepak.sharma','ZUTY6903'=>'Deepak.sharma','ZUUZ6605'=>'Deepak.sharma','ZUUX7631'=>'Deepak.sharma','ZUUX1633'=>'Deepak.sharma','ZUWU0585'=>'Deepak.sharma','ZUXW2259'=>'Savita.chouhan','ZUWW3347'=>'Savita.chouhan','ZUUZ8785'=>'Savita.chouhan','ZUWU0991'=>'Savita.chouhan','ZUUX7957'=>'Savita.chouhan','ZUVT9903'=>'Savita.chouhan','ZUVA7429'=>'Savita.chouhan','ZUWS3743'=>'Savita.chouhan','ZUWU2235'=>'Savita.chouhan','ZUUZ7147'=>'Savita.chouhan','ZUVY3567'=>'Savita.chouhan','ZUTY6749'=>'Savita.chouhan','ZUXZ7643'=>'Savita.chouhan','ZUWA3179'=>'Savita.chouhan','ZUWR9891'=>'Savita.chouhan','ZUUZ8279'=>'Savita.chouhan','ZUWV4439'=>'vandana.p','ZUXT5031'=>'vandana.p','ZUUX8101'=>'vandana.p','ZUWW1785'=>'vandana.p','ZUWR1029'=>'vandana.p','ZUWW7993'=>'vandana.p','ZUVA7589'=>'vandana.p','ZUWA0421'=>'vandana.p','ZUTX2371'=>'Deepak.sharma','ZUAR7171'=>'damini.verma','ZUXR1899'=>'damini.verma','ZUVY5209'=>'Savita.chouhan','ZUVZ6403'=>'namita.david','ZUXU0149'=>'damini.verma','ZUTV4569'=>'vandana.p','ZUTU5631'=>'divya.p','ZUTU4933'=>'preeti.bhojne','ZUTU5931'=>'vandana.p','ZUTT0933'=>'salman.khan','ZUXX7923'=>'bharti.kutwal','ZUXU3597'=>'Deepak.sharma','ZUTT1339'=>'Deepak.sharma','ZUZV0883'=>'damini.verma','ZUTT9503'=>'preeti.bhojne','ZUVZ3307'=>'Savita.chouhan');
		$exec_name_arr 	=array();
                $uname_arr      =array(); 
                $uname_str      ='';
		$type	 	='REG';
	
		// Get the currently active executives from the PSWRDS table
                $sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE LAST_LOGIN_DT>='$last15Days'";
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
