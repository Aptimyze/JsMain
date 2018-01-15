<?php	

/************************************************************************************************************************
*    FILENAME           : pool_success.php
*    DESCRIPTION        : This is Backend Module which will Help Operator to Build Success Story Pool of his Choice.Operator Can also Delete the Selected Success Entries from the Pool.
*    CREATED BY         : Anurag Gautam
*    Date               : 11th July 2008
***********************************************************************************************************************/

ini_set('max_execution_time',0 );
ini_set('memory_limit',-1);	
include ("connect.inc");
$selected=$_POST['selected'];

if(authenticated($cid))
{
	$name= getname($cid);
	$smarty->assign("name",$name);

	if ($submit)
	{
		$smarty->assign('cid',$cid);
		if($kk=="yes")
		{
				$str1=$_POST['month1'];
				$str2=$_POST['month2'];
				$str3=$_POST['year1'];
				$str4=$_POST['year2'];
				$fromdate= $str3."-".$str1."-"."01"." "."00".":"."00".":"."00";
				$todate= $str4."-".$str2."-"."31"." "."23".":"."23".":"."59";
			
				$sql="SELECT DISTINCT(SUCCESS_POOL.ID_POOL),SUCCESS_STORIES.ID,SUCCESS_STORIES.NAME_H,SUCCESS_STORIES.NAME_W,SUCCESS_STORIES.COMMENTS,INDIVIDUAL_STORIES.HOME_PIC_URL from newjs.SUCCESS_STORIES JOIN newjs.INDIVIDUAL_STORIES ON SUCCESS_STORIES.ID = INDIVIDUAL_STORIES.STORYID left join newjs.SUCCESS_POOL ON SUCCESS_STORIES.ID=SUCCESS_POOL.ID_POOL WHERE SUCCESS_STORIES.DATETIME BETWEEN '$fromdate' AND '$todate' AND  SUCCESS_POOL.ID_POOL IS NULL"; 	
				$result=mysql_query($sql);
				while($row=mysql_fetch_array($result))
				{
					$id[]=$row['ID'];
					$comments[]=$row['COMMENTS'];
					$photo[]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['HOME_PIC_URL']);
					$husb[]=$row['NAME_H'];
					$wife[]=$row['NAME_W'];
				} // Store values in Array & Fetched the value from array
				
				$smarty->assign('fromdate',$fromdate);
				$smarty->assign('todate',$todate);
				$smarty->assign('id',$id);
				$smarty->assign('comments',$comments);
				$smarty->assign('photo',$photo);
				$smarty->assign('husb',$husb);
				$smarty->assign('wife',$wife);
				$smarty->assign('page',1);
				$smarty->assign('show_form',1);
				$smarty->display('pool_success.htm');	
		}

		if($kk1=="yes")
		{
				for ($i=0; $i<count($_POST['check1']); $i++)  //If Checked Value is Yes
				{
					$pi=$_POST['check1'][$i];
					$sql="select DISTINCT(STORYID),STORY,HOME_PIC_URL,NAME1,NAME2 from newjs.INDIVIDUAL_STORIES where STORYID='$pi'";
					$result=mysql_query($sql);

					while($row=mysql_fetch_array($result))
					{
						   $id[]=$row['STORYID'];
						   $comments[]=$row['STORY'];
						   $photo[]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['HOME_PIC_URL']);
					           $husb[]=$row['NAME1']; 
						   $wife[]=$row['NAME2'];
					} 
		
					$sql2="insert into newjs.SUCCESS_POOL(ID_POOL) values ('$pi')";
					mysql_query($sql2); 
				}
				
				$smarty->assign('id',$id);
				$smarty->assign('husb',$husb);
				$smarty->assign('wife',$wife);
				$smarty->assign('comments',$comments);
				$smarty->assign('photo',$photo);			
				$smarty->assign('show_form',1);
				$smarty->assign('page',2);
				$smarty->display('pool_success.htm');		
		}

		if($kk2=="yes")
		{
			$sql="select DISTINCT(INDIVIDUAL_STORIES.STORYID),INDIVIDUAL_STORIES.STORY,INDIVIDUAL_STORIES.NAME1,INDIVIDUAL_STORIES.NAME2,INDIVIDUAL_STORIES.HOME_PIC_URL from newjs.INDIVIDUAL_STORIES,newjs.SUCCESS_POOL where INDIVIDUAL_STORIES.STORYID=SUCCESS_POOL.ID_POOL AND SUCCESS_POOL.CURRENT_LIVE='N' AND SUCCESS_POOL.EVER_LIVE='N'";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result))
			{
				  $id[]=$row['STORYID'];
				  $comments[]=$row['STORY'];
				  $photo[]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['HOME_PIC_URL']);
				  $husb[]=$row['NAME1'];
			          $wife[]=$row['NAME2'];
			} 

			$smarty->assign('id',$id);
			$smarty->assign('comments',$comments);
			$smarty->assign('husb',$husb);
			$smarty->assign('wife',$wife);
			$smarty->assign('photo',$photo);			
			$smarty->assign('show_form',1);
			$smarty->assign('page',3);
			$smarty->display('pool_success.htm');			
		}	

		if($kk3=="yes")
		{
			for ($i=0; $i<count($_POST['check1']); $i++)  //If Checked Value is Yes
			{
				$pi=$_POST['check1'][$i]; //echo
				$sql="delete from newjs.SUCCESS_POOL where ID_POOL='$pi'";
				mysql_query($sql);
			}


			$sql="select DISTINCT(INDIVIDUAL_STORIES.STORYID),INDIVIDUAL_STORIES.STORY,INDIVIDUAL_STORIES.NAME1,INDIVIDUAL_STORIES.NAME2,INDIVIDUAL_STORIES.HOME_PIC_URL from newjs.INDIVIDUAL_STORIES,newjs.SUCCESS_POOL where INDIVIDUAL_STORIES.STORYID=SUCCESS_POOL.ID_POOL AND SUCCESS_POOL.CURRENT_LIVE='N' AND SUCCESS_POOL.EVER_LIVE='N'";
			
			 //$sql="select INDIVIDUAL_STORIES.STORYID,INDIVIDUAL_STORIES.STORY,INDIVIDUAL_STORIES.HOME_PICTURE from newjs.INDIVIDUAL_STORIES,newjs.SUCCESS_POOL where INDIVIDUAL_STORIES.STORYID=SUCCESS_POOL.ID_POOL";

			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result))
			{
				  $id1[]=$row['STORYID'];
				  $comments[]=$row['STORY'];
				  $photo[]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['HOME_PIC_URL']);
				  $husb[]=$row['NAME1'];
				  $wife[]=$row['NAME2'];
			}

			$smarty->assign('id',$id1);
			$smarty->assign('comments',$comments);
			$smarty->assign('photo',$photo);
			$smarty->assign('husb',$husb);
			$smarty->assign('wife',$wife);
			$smarty->assign('show_form',1);
			$smarty->assign('page',4);
			$smarty->display('pool_success.htm');

		}
	}
	else
	{
		if($kk5=="yes")
		{
		$sql="select DISTINCT(INDIVIDUAL_STORIES.STORYID),INDIVIDUAL_STORIES.STORY,INDIVIDUAL_STORIES.NAME1,INDIVIDUAL_STORIES.NAME2,INDIVIDUAL_STORIES.HOME_PIC_URL from newjs.INDIVIDUAL_STORIES,newjs.SUCCESS_POOL where INDIVIDUAL_STORIES.STORYID=SUCCESS_POOL.ID_POOL AND SUCCESS_POOL.CURRENT_LIVE='N' AND SUCCESS_POOL.EVER_LIVE='N'";

			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result))
			{
				  $id1[]=$row['STORYID'];
				  $comments[]=$row['STORY'];
				  $photo[]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['HOME_PIC_URL']);
				  $husb[]=$row['NAME1'];
				  $wife[]=$row['NAME2'];
			}

			$smarty->assign('id',$id1);
			$smarty->assign('comments',$comments);
			$smarty->assign('photo',$photo);
			$smarty->assign('husb',$husb);
			$smarty->assign('wife',$wife);
			$smarty->assign('show_form',1);
			$smarty->assign('page',4);
		}
		else
		{
			$smarty->assign('show_form',0);
		}
		$smarty->assign('cid',$cid);
		$smarty->display('pool_success.htm');
	}
}
else
{
	$msg="Your session has been timed out  ";
	$smarty->assign("cid",$cid);
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

?>
