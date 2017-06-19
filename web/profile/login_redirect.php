<?php
	require_once("connect.inc");
	$db=connect_db();
	//Added by Rahul Tara to determine number of contacts initiated since last login	
	include("sms_service.inc");
	
	$get1=unserialize(stripslashes($get1));
	$post1=unserialize(stripslashes($post1));
	
	if(is_array($get1))
	{
		foreach($get1 as $key => $value)
		{
			$$key=$value;
		}
	}
	
	if(is_array($post1))
	{
		foreach($post1 as $key => $value)
		{
			$$key=$value;
		}
	}
	
	$data=authenticated();
	
	if(isset($data))
	{
		$profileid = $data["PROFILEID"];
		$last_login_dt = $data["LAST_LOGIN_DT"];
		$mod_dt= $data["MOD_DT"];
		$checksum=$data["CHECKSUM"];
		$SITE_URL=$data["SITE_URL"];

		// code for chat
		$pid = $data['PROFILEID'];
		
		$uni_id=1.4;//to be changed for every new version of the messenger window
		
		$username_new=$data["USERNAME"];

                //added by nikhil for messenger
		/*echo("<html> <script type=\"text/javascript\" language=\"javascript\">function loadpopunder(url,name,winfeatures){ win2=window.open(url,name,winfeatures);
		if(win2)
		{
	        	win2.blur();
	        	window.focus();
		}
	}</script><body onload=\"loadpopunder('http://$CHAT_URL/profile/mwindow.php?profileid=$pid&username=$username_new&uni_id=$uni_id&alreadyloggedin=1','jsmessenger','width=225,height=300,status=1,scrollbars=0,resizable=no');\"></body></html>");
		*/
		if($login2contact)
		{	
			if($data["INCOMPLETE"]=="Y")
			{
				$callValidate=1;
				$logindone='Y';
				include_once("login_intermediate_pages.php");
		                intermediate_page();
			}
			else 
			{
				if($contacttype=="single")
				{
					//suggest_profile Added By lavesh and used for counting number of user that accept initial contact through suggested Profile.
					if($matchalert_mis_variable)
					{
						$matchalert_mis_arr=explode("###",$matchalert_mis_variable);

						if($matchalert_mis_arr[0]!='')
						{
							$dt=date("Y-m-d");
							$logic_used=$matchalert_mis_arr[0];
							$recomending=$matchalert_mis_arr[1];
							$is_user_active=$matchalert_mis_arr[2];

							echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&username=$username&suggest_profile=$suggest_profile&logic_used=$logic_used&recomending=$recomending&is_user_active=$is_user_active&clicksource=$clicksource&stype=$stype\"></body></html>";
							exit;
						}

					}
					echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&username=$username&suggest_profile=$suggest_profile&clicksource=$clicksource&stype=$stype\"></body></html>";
				}
				elseif($contacttype=="multiple")
				{
					$logindone='Y';
					include("searchaction.php");
				}
			}
			exit;
		}
		if($data["INCOMPLETE"]=="Y")
		{
			$callValidate=1;
			$logindone='Y';
			include_once("login_intermediate_pages.php");
		        intermediate_page();
			exit;
		}
		$gender=$data["GENDER"];

		if($searchonline)
		{
			echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/quick_search.php?checksum=$checksum&searchonline=1\"></body></html>";
			exit;
		}

		//Added by lavesh. Call appropriate intermediate page on rotation basis.
		include_once("login_intermediate_pages.php");
		intermediate_page();

        echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/mainmenu.php?checksum=$checksum\"></body></html>";
        exit;
	}
	else 
	{
		TimedOut();
	}
?>
