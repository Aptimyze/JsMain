<?php
include ("connect.inc");
$db2=connect_db();
$data=authenticated($cid);

if(isset($data))//successful login
{

//	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$privilage = getprivilage($cid);
   	$priv = explode("+",$privilage);

//	$misname=getname($cid);

/*	if(in_array('PA',$priv))
	{
		$user="n";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showprofilestoassign_new.php?name=$name&user=$user&cid=$cid\">Assign Photo Profiles</a>";
	}
*/
	if(in_array('TH',$priv) || in_array('CP',$priv) || in_array('IP',$priv) || in_array('admin',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/tieups/usr_select.php?cid=$cid\">View MIS</a>";
        }
	if(in_array('UM',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/tieups/registered_members_us.php?cid=$cid\">View MIS</a>";
	}

	if(in_array('PU',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showprofilestoscreen.php?name=$user&cid=$cid\">View assigned photo profiles</a>";
	}
	if(in_array('A',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/alternate.php?name=$user&cid=$cid&val=new\">Assign Profiles</a>";
	}
	if(in_array('NU',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/userview.php?name=$user&cid=$cid\">View assigned profiles</a>";
	}
	if(in_array('OR',$priv))  // 'OR' privilage for top admin viewing order records
        {
                $linkarr[]="<a href=\"$SITE_URL/billing/order_records.php?name=$user&cid=$cid\">View Order Records</a>";
        }
	if(in_array('S',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Search Profile</a>";
        }
	if(in_array('R',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/retrievepage.php?user=$name&cid=$cid\">Retrieve Profile</a>";
        }
        if(in_array('TA',$priv))//thumbnail administrator
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_assign.php?name=$name&cid=$cid\">Assign Thumbnails</a>";
        }
        if(in_array('TU',$priv))//thumbnail operator
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_screen.php?username=$name&cid=$cid\">View Assigned Thumbnails</a>";
        }
	if(in_array('F',$priv)) //Feedback operator
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/feedback_check.php?user=$name&cid=$cid\">Feedback Check</a>";
        }
	if(in_array('BU',$priv)) //billing entry operator
        {
                 $linkarr[]="<a href=\"$SITE_URL/billing/billingview.php?user=$name&cid=$cid\">Billing</a>";
        }
	if(in_array('BA',$priv)) //billing admin
        {
                 $linkarr[]="<a href=\"$SITE_URL/billing/billingview.php?user=$name&cid=$cid\">Billing</a>";
        }
	if(in_array('PA',$priv))
	{
		$user="n";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showprofilestoassign_new.php?name=$name&user=$user&cid=$cid\">Assign Photo Profiles</a>";
	}

	if(in_array('RA',$priv))//resources admin
        {
                $linkarr[]="<a href=\"$SITE_URL/resources/resources_admin_cat.php?username=$username&cid=$cid\">Resources Admin</a>";
        }

//	if($misname=="shiv")
	if(in_array('MA',$priv) || in_array('MB',$priv) || in_array('MC',$priv) || in_array('MD',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/mis/mainpage.php?name=$misname&cid=$cid\">View MIS</a>";
	}

	if(in_array('MP',$priv)) //manage homepage photo
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_homepage_photo.php?name=$username&cid=$cid\">Manage HomePage Photo</a>";
	}

	if(in_array('ES',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/mailid_view.php?cid=$cid\">View improper profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/mailid_list.php?cid=$cid\">Search improper profiles</a>";
        }

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("mainpage.htm");

}
else//login failed
{
	$smarty->assign("username","$name");
	$smarty->display("jsconnectError.tpl");
}
?>
