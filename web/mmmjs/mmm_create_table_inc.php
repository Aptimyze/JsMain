<?php

$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);


/**
*	Filename	:	mmm_create_table_inc.php
*	Included	:	connect.inc
*	Description	:	It contains general functions for create_table event :-
**/


/**
*	Included	:	connect.inc
*	Description	:	Contains general functions which are most commonly required
**/	


/**
*	Tpls used :a)mmm_create_table.tpl
			   b)mmm_create_table1.tpl
			   c)mmm_message.tpl
**/

include "connect.inc";


function get_mailq($server_file)
{
	$fp=fopen("$server_file","r");
	$temp=fgets($fp,4096);
	return $temp;
}
	
		/**
		*	Function	:	get_valid_mailers
		*	Input		:
		*	Output		:	array of mailers_id
		*	Description	:	This function will find all the mailers who are in 'vd' state
		**/

	
function get_valid_mailers($mailer_id)
{
	global $smarty;
	$sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='vd' AND DEL='N'";
	$result=mysql_query($sql) or die("Could not connect MAIN_MAILER in mmm_create_table.php");
		
	$no=mysql_num_rows($result);
		
	if($no==0)
	{
		$message="There is no active mailer for which variable has been defined please create mailer first ";
		$smarty->assign("message",$message);
		$smarty->display("mmm_message.htm"); 	
		die;	
	}
	else 
	{
		while($row=mysql_fetch_array($result))
		{
			$mailer_id_arr[]=array("mailer_id"=>$row[MAILER_ID], "mailer_name"=>$row[MAILER_NAME]);
		}
	}
		
	return $mailer_id_arr;
}
	
		/**
		*	Function	:	get_subquery_result
		*	Input		:	mailer_id
		*	Output		:	No of results
		*	Description	:	It takes the query of mailer from the database and then finds the no of records tht satisfy that criteria from 	JOBALERT DATABASE and returns them
		**/

function get_subquery_result($mailer_id)
{
	$sql="SELECT SUB_QUERY,MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql) or die("Could not connect MAIN_MAILER IN mmm_create_table.php");
	$row=mysql_fetch_array($result);
	$sql_sub=stripslashes($row[SUB_QUERY]);
	
	if($row['MAILER_FOR']=='J')
	{
		mysql_query($sql_sub);
		$sql_num = "select FOUND_ROWS() as NUM";
		$result_num = mysql_query($sql_num) or die("could not find number of rows");
		$myrow_num = mysql_fetch_array($result_num);
		$no=$myrow_num[NUM];
	}
	else if($row['MAILER_FOR']=='9')
	{
		$db99 = connect_db_99("property");
		mysql_query($sql_sub,$db99);
		$sql_num = "select FOUND_ROWS() as NUM";
		$result_num = mysql_query($sql_num,$db99) or die("could not find number of rows");
		$myrow_num = mysql_fetch_array($result_num);
		$no=$myrow_num[NUM];
		mysql_close($db99);
		
	}
	
		
	return $no;
}

		/**
		*	Function	:	get_sub_sub_query
		*	Input		:	mailer_id	
		*	Output		:	sub query(as string)
		*	Description	:	It finds the sub_query associated with the mailer and finaly returns the query part
		**/

	
function get_sub_sub_query($mailer_id)
{
	// THIS PART finds the sub query
	$sql_select_subquery="SELECT SUB_QUERY FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result_select_subquery=mysql_query($sql_select_subquery) or die("Could note select database".mysql_error());
	$row_select_subquery=mysql_fetch_array($result_select_subquery);
	$sub_query=stripslashes($row_select_subquery[SUB_QUERY]);
	
	$temp=explode("FROM",$sub_query);
	
	// this part gives the after "FROM" part of sub query
	$sub_sub_query=$temp[1];
	
	return $sub_sub_query;
}

		/**
		*	Function	:	get_table_field
		*	Input		:	mailer_id
		*	Output		:	array of fields whose data needs to be sended in the mailer
		*	Description	:	Finds the table_var_name (variables) fields array which needs to be sent in template
		**/

function get_table_field($mailer_id)
{
	// This part finds the all the field of database that need to be extracted 
	$sql_get_field="SELECT TABLE_VAR_NAME FROM MAIL_VARS WHERE MAILER_ID=$mailer_id";
	$result_get_field=mysql_query($sql_get_field) or die("Could not get field".mysql_error());
	while($row_get_field=mysql_fetch_array($result_get_field))
	{
		if($row_get_field[TABLE_VAR_NAME]=="PROFILEID")
		{
			$sql_temp = "SELECT MAILER_FOR FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
			$res_temp = mysql_query($sql_temp);
			$row_temp = mysql_fetch_array($res_temp);
			if($row_temp['MAILER_FOR']=='J')
				$field_arr[]="newjs.JPROFILE.PROFILEID";
			else if($row_temp['MAILER_FOR']=='9')
				$field_arr[]="property.PROFILE.PROFILEID";
		}
		else
			$field_arr[]=$row_get_field[TABLE_VAR_NAME];
	}
	echo"<br><br><br>";
	return $field_arr;
}
	
	//MAIN TABLE NAME
	$table_name=$mailer_id."mailer";

		/**
		*	Function	:	create_full_table
		*	Input		:	table_name, array of table fields(need to be fetched),sub_query
		*	Output		:
		*	Description	:	It creates the table depending upon the sub_query and the fields which needs to be extracted from the main database of JOBALERT
		**/

	
function create_full_table($table_name,$field_arr,$sub_sub_query,$mail_type,$timer,$response)
{
	//separate jeevansathi and 99acres table creation
	$temp = explode("mailer",$table_name);
	$mailer_id = $temp[0];
	$sql = "SELECT JSWALKIN,MAILER_FOR,MMM_SPLIT FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
	$res = mysql_query($sql) or logError("in mmm_create_table_inc.php",$sql);
	$row = mysql_fetch_array($res);
	$Jor9 = $row['MAILER_FOR'];
	$jswalkin=$row["JSWALKIN"];		
	$mmm_split=$row['MMM_SPLIT'];
	if($Jor9=='9')
	{
		$nfa = array();
		$db99 = connect_db_99("property");

		echo $sq1="CREATE TABLE IF NOT EXISTS `$table_name` (PROFILEID bigint unsigned, EMAIL varchar(100), NAME varchar(255),PHONE varchar(20))";

		global $db;
	        mysql_query($sq1,$db) or die("Could not create table ".mysql_error($db)) ;

		echo $sq1="delete from `$table_name`";
		mysql_query($sq1,$db) or die("Could not delete from table ".mysql_error($db)) ;


		/**********Populate table now***************/

		$db99 = connect_db_99("property");
		
		if($mmm_split == 'Y') {
			$sql1="SELECT SQL_CACHE DISTINCT(PROFILEID),EMAIL,NAME,PHONE FROM ".$sub_sub_query." ORDER BY RAND()";
	        $res = mysql_query($sql1,$db99) or die(mysql_error($db99));
	       //$row = mysql_fetch_array($res);
			mysql_close($db99);
			$file_name = 'MMM_CSV_'.$mailer_id.'mailer.csv';
			$path = $_SERVER['DOCUMENT_ROOT'];
			$fp = fopen("$path/mmmjs/cheetah_csv/$file_name", 'w');
			$content = '';
			$index = 1;
			$content = array("PROFILEID","EMAIL","NAME","PHONE");
			fputcsv($fp, $content);
			while($row = mysql_fetch_array($res))
			{
				if($index%2 == 0 ){
					$t = addslashes($row['EMAIL']);
					$n=addslashes($row['NAME']);
					$profileid=$row['PROFILEID'];
					$phoneNo=$row['PHONE'];
					$sql2 = "INSERT INTO $table_name (PROFILEID,EMAIL,NAME,PHONE) VALUES ('$profileid','$t','$n','$phoneNo')";
					mysql_query($sql2,$db) or die(mysql_error($db));
				}
				else {
					$profileid = $row['PROFILEID'];
					$email = $row['EMAIL'];
					$name=$row['NAME'];
					$phoneNo=$row['PHONE'];
					$content = array("$profileid","$email","$name","$phoneNo");
		            fputcsv($fp, $content);
				}
				$index ++;
			}
			fclose($fp);
		}
		else {
			 $sql = "SELECT SQL_CACHE DISTINCT(PROFILEID),EMAIL,NAME,PHONE FROM ".$sub_sub_query;
			$res = mysql_query($sql,$db99) or die(mysql_error($db99));
			mysql_close($db99);
			while($row = mysql_fetch_array($res))
			{
				$t = addslashes($row['EMAIL']);
				$n=addslashes($row['NAME']);
				$profileid=$row['PROFILEID'];
				$phoneNo=$row['PHONE'];
				$sql2 = "INSERT INTO $table_name (PROFILEID,EMAIL,NAME,PHONE) VALUES ('$profileid','$t','$n','$phoneNo')";
				mysql_query($sql2,$db) or die(mysql_error($db));
				
			}
		}
		//      This part creates index on email field

                if($mail_type != 'ja' && $mail_type != 'nja')
                {
                        $sql_create_index="ALTER IGNORE TABLE $table_name ADD  UNIQUE  INDEX(PROFILEID), ADD INDEX(EMAIL)"; // adding index on profileid instead of email as there cab be different profiles with same email id Added by NEHA on 06 Aug 2012
                       
                }
		global $db;
                mysql_query($sql_create_index,$db) or die("Could not Create Index ".mysql_error());

	}
	else if($Jor9 == 'J')
	{
		// this part creates the malier table in mmm databse with desired result
		$sq1="CREATE TABLE $table_name ENGINE=MyISAM MAX_ROWS=10000000 AVG_ROW_LENGTH=100 SELECT ";
		$temp="";
		$s=sizeof($field_arr);
		for($i=0;$i<$s;$i++)
		{
			$temp.=" ".$field_arr[$i].",";
		}
		$temp=substr($temp,0,strlen($temp)-1);
		$sq1.=$temp;
		if($jswalkin && !in_array("CITY_RES",$field_arr))
		$sq1.=", "."CITY_RES";
		if($jswalkin && !in_array("COUNTRY_RES",$field_arr))
		$sq1.=", "."COUNTRY_RES";

		if($mail_type != 'crm' )
			$sq1.=" "." FROM ".$sub_sub_query;
		else if($mail_type == 'crm')
			$sq1.=" "." EMAIL FROM ".$sub_sub_query;
		mysql_query($sq1) or die("Could not create table ".mysql_error()) ;

	
	// 	This part creates index on email field

		if($mail_type != 'ja' && $mail_type != 'nja')
		{
			$sql_create_index="ALTER IGNORE TABLE $table_name ADD  UNIQUE  INDEX(EMAIL)";
		}
		mysql_query($sql_create_index) or die("Could not Create Index ".mysql_error());
	}
}

	
		/**
		*	Function	:rename_table
		*	Input		:new_table_name,old_table_name,mailer_id,server_id,type of response
		*	Output		:
		*	Description	:Ihis function is called when data is sent through single server in that case it simply renames main table to the new table name(new name depends upon server id)
		**/

function rename_table($new_table_name,$old_table_name,$mailer_id,$server_id,$response)
{
				
	//$new_table_name=$mailer_id."mailer_s."$server_id;
	echo "Old table name---$old_table_name <br>";
	echo "New table name---$new_table_name <br>";	
	global $smarty;		
	global $db;		
	echo $sql_rename="RENAME TABLE $old_table_name TO $new_table_name";
	mysql_query($sql_rename,$db) or die("Could not Rename table:".mysql_error());

	$sql = "select * from MAILER_SERVER where MAILER_ID = '$mailer_id' and SID = '$server_id'";
        $res = mysql_query($sql,$db) or die($sql." : ".mysql_query());

	if(mysql_num_rows($res) > 0)
        {
                $sql_update = "update MAILER_SERVER set TABLE_NAME = '$new_table_name' where MAILER_ID = '$mailer_id' and SID = '$server_id'";
                mysql_query($sql_update,$db) or die($sql_update." : ".mysql_query());
        }
        else
        {
			
		$sql_update="insert into MAILER_SERVER(MAILER_ID, SID, TABLE_NAME) values('$mailer_id','$server_id','$new_table_name')";
		mysql_query($sql_update,$db) or die("Could not update the s1_table name");
	}			
	if($response=="i")
	{
		$sql_alter="ALTER TABLE $new_table_name ADD `SENT` TINYINT DEFAULT '0' NOT NULL ,ADD RESPONSE TINYINT DEFAULT '0' NOT NULL";
		mysql_query($sql_alter,$db) or die("Could not insert field SENT, RESPONSE in create_table .php ".mysql_error());
		
		
	}
	else 
	{
		$sql_alter="ALTER TABLE $new_table_name ADD `SENT` TINYINT DEFAULT '0' NOT NULL";
		mysql_query($sql_alter,$db) or die("Could not insert field SENT in create_table .php ".mysql_error());	
;

	}
			
	$sql_update_state="UPDATE MAIN_MAILER SET STATE='tc' WHERE MAILER_ID=$mailer_id";
	mysql_query($sql_update_state,$db) or die("could not update state in split table func".mysql_error());	
			
	$message="Table $new_table_name  has been created ";
	$smarty->assign("message",$message);
	$smarty->assign("mailer_id",$mailer_id);
	$sql = "SELECT MMM_SPLIT FROM MAIN_MAILER WHERE MAILER_ID='".$mailer_id."'";
    $res = mysql_query($sql,$db) or die(mysql_error().$sql);
    $row = mysql_fetch_array($res);
    $mmm_split = $row['MMM_SPLIT'];
    if($mmm_split == 'Y')
    	$smarty->assign("mmm_split",'Y');
    else
    	$smarty->assign("mmm_split",'N');	
	$smarty->display("mmm_message.htm");
}

function split_limit($limit_arr,$n,$j,$table_name,$from_table_name,$response,$srvid,$mailer_id)
{
	$flimit = 0;
	for($x=$j-1; $x>=$j-2 && $x>-1; $x--)
		$flimit += $limit_arr[$x];

	$tlimit = $limit_arr[$j];
/*	
	if(sizeof($limit_arr)-1 > $j)
	{
		$tlimit = 0;
		for($y=$j; $y>=$j-1 && $y>0; $y--)
        	        $tlimit += $limit_arr[$y];
	}
	else
		$tlimit = $n;
*/
 	$sql="CREATE TABLE $table_name ENGINE=MyISAM MAX_ROWS=10000000 AVG_ROW_LENGTH=100 SELECT * FROM $from_table_name LIMIT $flimit,$tlimit";
//        echo "sql is : $sql<br><br>";
	mysql_query($sql) or die("Could not create table".$table_name.":".mysql_error());

	$mail_type = get_mail_type($mailer_id);
		
	if($mail_type != 'ja' && $mail_type != 'nja')
		$sql = "ALTER TABLE $table_name ADD INDEX ( `EMAIL` )";
	else
		$sql = "ALTER TABLE $table_name ADD INDEX ( `USERNAME` ), ADD PRIMARY KEY (`ID`)";
        mysql_query($sql);


	$sql = "select * from MAILER_SERVER where MAILER_ID = '$mailer_id' and SID = '$srvid'";
	$res = mysql_query($sql) or die($sql." : ".mysql_query());	
	if(mysql_num_rows($res) > 0)
	{
		$sql_update = "update MAILER_SERVER set TABLE_NAME = '$table_name' where MAILER_ID = '$mailer_id' and SID = '$srvid'";
		mysql_query($sql_update) or die($sql_update." : ".mysql_query());
	}
	else
	{
		$sql_update="insert into MAILER_SERVER(MAILER_ID, SID, TABLE_NAME) values('$mailer_id','$srvid','$table_name')";
        	mysql_query($sql_update) or die("Could not update the s1_table name");
	}

	if($response=="i")
        {
                $sql_alter="ALTER TABLE $table_name ADD `SENT` TINYINT DEFAULT '0' NOT NULL ,ADD RESPONSE TINYINT DEFAULT '0' NOT NULL";
                mysql_query($sql_alter) or die("Could not insert field SENT, RESPONSE in create_table .php ".mysql_error());
                                                                                                 
        }
        else
        {
                $sql_alter="ALTER TABLE $table_name ADD `SENT` TINYINT DEFAULT '0' NOT NULL";
                mysql_query($sql_alter) or die("Could not insert field SENT in create_table .php ".mysql_error());
;
                                                                                                 
        }


}




			/**
		*	Function	:	split_table
		*	Input		:	table1_name,table2_name,fraction1,fraction2(parts in which you want to divide your table,from_table_name,response_type
		*	Output		:
		*	Description	:	This scripts splits the from table into 2 parts depending upon the fraction passed and finally deletes the from_table(main table)
		**/

			
function split_table($sparr,$limit_arr,$n,$mailer_id,$from_table_name,$response)
{
	echo "mailer_id->$mailer_id<br>";
	echo"from tab->$from_table_name<br>";
	echo"response->$response<br>";
	// this table will split the main table into n parts
	global $smarty;
	$no_of_table=sizeof($sparr);
	for($i=0 ; $i<$no_of_table ; $i++)
	{
		$sarr = explode("p",$sparr[$i][spstr]);
                $srvid = $sarr[1];
		$table_name1 = $mailer_id."mailer_s".$srvid;
		split_limit($limit_arr,$n,$i+1,$table_name1,$from_table_name,$response,$srvid,$mailer_id);
//		$table_name=array();
		$table_name[$i]=$table_name1;
	}	
	$sql_update="UPDATE MAIN_MAILER SET S1_TABLE_NAME='$table_name[0]', S2_TABLE_NAME='$table_name[1]' WHERE MAILER_ID=$mailer_id ";
	mysql_query($sql_update) or die("Could not update the s1_table name & s2_tble_name :".mysql_error());
	$sql_update_state="UPDATE MAIN_MAILER SET STATE='tc' WHERE MAILER_ID=$mailer_id";
	mysql_query($sql_update_state) or die("could not update state in split table func".mysql_error());
	$sql_del_table="DROP TABLE $from_table_name";
	mysql_query($sql_del_table) or die("Could not Delete table".mysql_error());
	echo("**************************************************************");
	print_r($table_name);
	echo("**************************************************************");
	if($table_name)
		$str=implode(',',$table_name);
	else
		$str="No";
        $message.=$str;
	$message=" tables";
	$message.=" has been created ";
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");
}		
			
		
		/**
		*	Function	:	get_response
		*	Input		:	mailer_id
		*	Output		:	response_type
		*	Description	:	This function determines the type of response for the given mailer
		**/

function get_response_type($mailer_id)
{
	global $db;
	$sql="SELECT RESPONSE_TYPE FROM MAIN_MAILER WHERE MAILER_ID=$mailer_id";
	$result=mysql_query($sql,$db) or die("could not get the Response type :".mysql_error());
	$row=mysql_fetch_array($result);
	$response=$row['RESPONSE_TYPE'];
	echo"response is $response<br>";
	return $response;
}
			
				
function get_mail_type($mailer_id)	
{
	$sql="SELECT MAIL_TYPE FROM MAIN_MAILER WHERE MAILER_ID='$mailer_id'";
	$result=mysql_query($sql) or die("could not get the mail type :".mysql_error());
        $row=mysql_fetch_array($result);
        $type=$row['MAIL_TYPE'];
        echo"mail type is $type<br>";
        return $type;

}
			

function get_servers()
{
	global $db;
	$sql = "select count(*) as NUM from mmmjs.SERVERS";
	$result = mysql_query($sql,$db) or die("could not get the server count :".mysql_error($db));
	$row=mysql_fetch_array($result);
	return $row[NUM]; 
}

function getip($id)
{
	global $db;
	$sql = "select IP from mmmjs.SERVERS where SID = '$id'";
        $result = mysql_query($sql,$db) or die("could not get the server IP :".mysql_error());
        $row=mysql_fetch_array($result);
        return $row[IP];
}

?>
