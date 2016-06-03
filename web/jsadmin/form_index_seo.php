<?php
/***********************************************************************************************
 FILENAME     : index_seo.php
 DESCRIPTION  : Get Title, Description and Keywords from the user to be displayed
                on index.php for Jeevansathi. It will output a file that will be included
	        from index.php
 CREATED BY   : Rahul Tara 
 CREATED ON   : 30 May,2005
***********************************************************************************************/

include("connect.inc");

if(authenticated($cid))
{
	$filename ="../profile/index_data_seo.php";
	if($Submit)
	{

		// Write the contents of title,keywords,description in file
		if(!$handle = fopen($filename,"w"))
		{
			echo "Cannot create file index_seo.htm";
			exit;
		}

		$str = "<?php\n";
		if (fwrite($handle,$str) === FALSE) 
		{
			echo "Cannot open file profile/index_data_seo.php";
			exit;
		}

		$title = addslashes(stripslashes($title));
		$title = str_replace('$','\$',$title);
		$str = "\$Title=\"".$title."\";\n";
                if (fwrite($handle,$str) === FALSE)
                {
                        echo "Cannot write Title to file profile/index_data_seo.php";
                        exit;
                }

                $description = addslashes(stripslashes($description));
                $description = str_replace('$','\$',$description);
		$str = "\$Description=\"".$description."\";\n";
                if (fwrite($handle,$str) === FALSE)
                {
                        echo "Cannot write Description to file profile/index_data_seo.php";
                        exit;
                }

                $keywords = addslashes(stripslashes($keywords));
                $keywords = str_replace('$','\$',$keywords);
		$str = "\$Keywords=\"".$keywords."\";\n";
                if (fwrite($handle,$str) === FALSE)
                {
                        echo "Cannot write Keywords to file profile/index_data_seo.php";
                        exit;
                }

		$str = "?>";
                if (fwrite($handle,$str) === FALSE)
                {
                        echo "Cannot write to file profile/index_data_seo.php";
                        exit;
                }

		fclose($handle);
		
                $smarty->assign("CID",$cid);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("MSG","Meta information successfully inserted in index file");
		$smarty->display("form_index_seo.htm");
	}
	else
	{

		include($filename);
		
		$smarty->assign("TITLE",stripslashes($Title));
		$smarty->assign("DESCRIPTION",stripslashes($Description));
		$smarty->assign("KEYWORDS",stripslashes($Keywords));

		$smarty->assign("CID",$cid);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->display("form_index_seo.htm");
	}
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
