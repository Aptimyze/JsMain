<?php
include('connect.inc');
$db=connect_db();
$data=authenticated($cid);
if($data)
{
	$name= getname($cid);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
	
	if($source_sel)
	{
		if($page=='P2')
			$boxpage=$page;
		
		if($page=='P2')
			$sql_src_sel="SELECT IMAGE FROM MIS.SEM_PAGE_CUSTOMIZE WHERE SOURCE='$source_sel' AND ACTIVE='Y' AND BOX='B1' AND PAGE='$boxpage'";
		else
			$sql_src_sel="SELECT IMAGE FROM MIS.SEM_PAGE_CUSTOMIZE WHERE SOURCE='$source_sel' AND ACTIVE='Y' AND BOX='$box' AND PAGE='$boxpage'";
		$res_src_sel=mysql_query($sql_src_sel,$db);
		if($row_src_sel=mysql_fetch_array($res_src_sel))
		{
			$image_sel=$row_src_sel['IMAGE'];
			$smarty->assign('IMAGE',$image_sel);
		}
	}

	if($save)
	{
		$smarty->assign("submit",'Y');
		if($box)
			$page='P1';
		else if($boxpage){
			$page=$boxpage;
			$box='B1';
		}

		if($source && $elm2)
		{
			$sql="SELECT ID FROM MIS.SEM_PAGE_CUSTOMIZE WHERE SOURCE='$source' AND PAGE='$page' AND BOX='$box' AND ACTIVE ='Y'";
			$res=mysql_query($sql,$db);
			while($row=mysql_fetch_array($res))
			{
				$id=$row['ID'];
				$sql_2="UPDATE MIS.SEM_PAGE_CUSTOMIZE SET ACTIVE='N' WHERE ID='$id'";
				mysql_query($sql_2,$db);
			}
			
			if($image=='')
				$image=$image_sel;

			if($source)
			{
				$sql_1="INSERT INTO MIS.SEM_PAGE_CUSTOMIZE (SOURCE,PAGE,BOX,CONTENT,IMAGE) VALUES ('$source','$page','$box','$elm2','$image')";
				mysql_query($sql_1,$db);
			}
		}
	}

	if($showedit || ($regpage=='Y' && $page !='P1'))
	{
		$smarty->assign('showeditor','Y');
		$smarty->assign("boxpage",$page);
		$smarty->assign("box",$box);
	}
	$source=array();
	$sql_src="SELECT SourceID FROM MIS.SOURCE WHERE ACTIVE='Y'";
	$res_src=mysql_query($sql_src,$db);
	while($row_src=mysql_fetch_array($res_src))
	{
		$source[]=$row_src['SourceID'];
	}


	$smarty->assign('SOURCE',$source);
	$smarty->assign('SOURCE_SEL',$source_sel);
	$smarty->assign("page",$page);
	$smarty->assign("regpage",$regpage);
	$smarty->assign("menu",$menu);
	$smarty->display('sem.htm');
}
?>
