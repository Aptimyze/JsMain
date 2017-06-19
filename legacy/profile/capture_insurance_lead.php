<?php
/******************************************************************************************************************
       Filename        :       capture_insurance_lead.php
       Description     :       script to capture the all the fields of the users required for the insurance 
			       banner & mailer.
       Created by      :       Vibhor Garg
       Created on      :       31-07-2008
*******************************************************************************************************************/

include("connect.inc");
connect_db();

//Auto login of the user while user will go to the insurance entry form.
if($Submit)//If user submits the form
{
	if($General_Insurance == "GI")
	        $General_Insurance="Yes";
	else
		$General_Insurance="No";

	if($Life_Insurance == "LI")
        	$Life_Insurance="Yes";
       	else
        	$Life_Insurance="No";

	if(($First_Name != "Name")&&($First_Name != "First Name"))
		$name=$First_Name;
	else
		$name="";

	if(($Surname != "Surname")&&($Surname != "SurName"))
		$sname=$Surname;
	else
		$sname="";

	$pincode=$Pin_Code;

	if($click_source != 'M')
		$click_source_label = "Mailer";
	else
		$click_source_label = "Banner";

	$error = 0;

	//checks for name field
	if((trim($name)=="") || (!eregi("^[a-z ]|'+$",$name)) || (substr_count($name,"'")>1) || ($name=="'") ||(strpos($name,"'")==1) || (strrpos($name,"'")===(strlen($name)-1)))
	{
		$error++;
		$smarty->assign("NAME_ERROR","red");
	} 

	//checks for the sname field
	if(trim($sname)=="" || (!eregi("^[a-z ]|'+$",$sname)) || (substr_count($sname,"'")>1) || ($sname=="'") ||(strpos($sname,"'")==1) || (strrpos($sname,"'")===(strlen($sname)-1)))
	{
		$error++;
		$smarty->assign("SURNAME_ERROR","red");
	}
	//checks for the pincode field
	if($pincode=="" || !(is_numeric($pincode)))
	{
		$error++;
		$smarty->assign("PINCODE_ERROR","red");
	}
        
	if($profileid == "")
        $data=authenticated($checksum);
        if($data)
        	$profileid=$data['PROFILEID'];

	if($error == 0)
	{
		$today=date("Y-m-d");
		if($profileid=="")
			$profileid="-1";
		$sql1="select USERNAME,PHONE_RES,PHONE_MOB,AGE,GENDER,OCCUPATION,INCOME,EMAIL,CITY_RES,PINCODE from newjs.`JPROFILE` where  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql1);
		if($result=mysql_fetch_array($res))
		{
			if($age=="")
				$age=$result['AGE'];
			if($city=="")
				$city=$result['CITY_RES'];
			if($gender=="")
				$gender=$result['GENDER'];
			if($occupation=="")
				$occupation=$result['OCCUPATION'];		
			if($income=="")
				$income=$result['INCOME'];
			if($email=="")
				$email=$result['EMAIL'];
			if($pincode=="")
				$pincode=$result['PINCODE'];
			if($mobile=="")
				$mobile=$result['PHONE_MOB'];
			if($residence=="")
				$residence=$result['PHONE_RES'];
			$username = $result['USERNAME'];
		}
		$sql="replace into newjs.INSURANCE_MAIL (PROFILEID,USERNAME,NAME,SURNAME,PINCODE,LIFE_INSURANCE,GENERAL_INSURANCE,INSURANCE_AMOUNT,SOURCE,EMAIL_ID,RES_NO,MOB_NO,AGE,GENDER,CITY,OCCUPATION,INCOME,ENTRY_DATE,CLICK_SOURCE) values ('$profileid','$username','$name','$sname','$pincode','$Life_Insurance','$General_Insurance','$Insurance_Amount','$SOURCE','$email','$residence','$mobile','$age','$gender','$city','$occupation','$income','$today','$click_source_label')";
		mysql_query_decide($sql) or die(mysql_error_js());
		$smarty->assign("Done",1);
		$smarty->display("insurance_mailer.htm");//To display the Thank you page
	}
	else
	{
		$smarty->assign("profileid",$profileid);
        	$smarty->assign("SOURCE",$SOURCE);
		$smarty->assign("First_Name",$name);
                $smarty->assign("Surname",$sname);
                $smarty->assign("Pin_Code",$pincode);
		$smarty->display("insurance_mailer.htm");	
	}
}   
else   //If coming first time to this page
{
	$id = $profileid;
	if($id!="")				//checking if coming through link in which profileid is one GET variable
  	{
		$sql="SELECT PASSWORD FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$id' AND  USERNAME='".addslashes($username)."'"; //checking the authenticity of the link //
		$res=mysql_query_decide($sql) or logError("error",$sql);
		$row=mysql_fetch_array($res);
		if(mysql_num_rows($res)>0)
		{
			login($username,$row['PASSWORD']);	
			$profileid=$id;
		}
  	}
	else
	{
	      $data=authenticated($checksum);        //if person is already login
	      if($data)					
	      {
		 $profileid=$data['PROFILEID'];		//getting the profileid
      	      }	
	}
	$smarty->assign("profileid",$profileid);
	$smarty->assign("SOURCE",$SOURCE);
	$smarty->assign("click_source",$click_source);
	$smarty->display("insurance_mailer.htm");//First display
}
?>
