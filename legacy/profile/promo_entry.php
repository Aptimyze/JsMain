<?php
/**
*       Filename        :       promo_entry.php
*       Description     :       script to capture the All the fields of the users required for  the Promotional Form.
*       Created by      :       Tanu Gupta
*       Created on      :       07-03-2007
**/

include "connect.inc";
connect_db();

$smarty->assign("SUBMIT_ERROR","none");     //By Default Error is none
$occupation_exist=0;          //by default occupation field assumes to non-existence

//By default setting the gender field true for MALE
$smarty->assign("gender_M","checked='checked'");

//Auto login of the user while user will go to the promotional entry form.
if($Submit)//If user submits the form
{
	//If the user is not coming from banner by submitting form there , then we have to set the profileid to blank
	if($profileid==0)
		$profileid="";
	
	//Checking the authentication since the user can some directly from banner by submitting form there there
	$data=authenticated($checksum);
        if($data)
        {
                 $profileid=$data['PROFILEID'];         //getting the profileid
        }

	$error=0;//Used to count the no. of errors make by user while filling the form

	
	//Check only when pre login case persists
	$sSQL="select PHONE_RES,PHONE_MOB,GENDER,OCCUPATION,CITY_RES,AGE,EMAIL,INCOME,PINCODE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";

	$result=mysql_query_decide($sSQL);
	if($row=mysql_fetch_array($result))
	{
		if($email=="")
			$email=$row['EMAIL'];
		if($age=="")
			$age=$row['AGE'];
		if($city=="")
			$city=$row['CITY_RES'];
		if($occupation=="")
			$occupation=$row['OCCUPATION'];
		if($income=="")
			$income=$row['INCOME'];
		
		if($gender=="")
			$gender=$row['GENDER'];
		if($residence=="")
			$residence=$row['PHONE_RES'];
 		if($mobile=="")
			$mobile=$row['PHONE_MOB'];
		if($pincode=="")
			$pincode=$row['PINCODE'];
	}
	if($row['EMAIL']==""|| $row['AGE']==""|| $row['OCCUPATION']==""|| $row['CITY_RES']==""|| $row['GENDER']=="" || $row['PINCODE']=="" )
	{	$PROFILE_SET=1;}
	
        
	
	//checks the email
	if($profileid==""||$PROFILE_SET==1)
	{
		$flag_e=checkemail($email,'N');
		if($flag_e==1)
		{
			$smarty->assign("EMAIL_ERROR","red");
			$error++;
		}

		//checks for age field value
		if(trim($age)=="" || (!intval($age))||(intval($age)<18)||(intval($age)>70))
		{  
			$smarty->assign("AGE_ERROR","red");
			$error++;
		}

		//checks for the city field
		if($city==-1 || $city=="")
		{
			$smarty->assign("CITY_ERROR","red");
			$error++;
		}
		
		
		
		//This case is checked since the Login user may have not fill the occupation field at the time of registration.

		if($occupation==-1 ||$occupation=="")
		{
			$smarty->assign("OCCUPATION_ERROR","red");
			$error++;
		}
	}
	
	//if no checkbox is clicked of Option_select
	if($ALL_LOAN=="" && $LOAN1=="" && $LOAN2==''&& $LOAN3=='' && $LOAN4=='')
	{
		$smarty->assign("OPTION_ERROR","Please select atleast one checkbox below, to avail best deals and offers");
		$error++;
	}

	//added by Vibhor to make banner and landing page fields compatible
	if(($Name != "") && ($Name != "Name"))
		$name=$Name;
	if(($Surname != "") && ($Surname != "Surname"))
		$sname=$Surname;
	//end

	//checks for name field
	if((trim($name)=="") || (!preg_match("/^[a-z ]|'+$/i",$name)) || (substr_count($name,"'")>1) || ($name=="'") ||(strpos($name,"'")==1) || (strrpos($name,"'")===(strlen($name)-1)))
	{
		$smarty->assign("NAME_ERROR","red");
		$error++;
	} 

	//checks for the sname field
	if(trim($sname)=="" || (!preg_match("/^[a-z ]|'+$/i",$sname)) || (substr_count($sname,"'")>1) || ($sname=="'") ||(strpos($sname,"'")==1) || (strrpos($sname,"'")===(strlen($sname)-1)))
	{
		$smarty->assign("SURNAME_ERROR","red");
		$error++;
	}
	//checks for the pincode field
                if($pincode=="" || !(is_numeric($pincode)))
                {
                        $pincode_error=1;
                        $smarty->assign("PINCODE_ERROR","red");
                        $error++;
                }
	if($profileid==""||$PROFILE_SET==1)
        {

		if(trim($residence)==""&&trim($mobile)=="")
		{
			$smarty->assign("RESIDENCE_ERROR","RED");
			$smarty->assign("MOBILE_ERROR","RED");
		}
		//checks for residence phone
		$flag_r=checkrphone($residence);
		if($flag_r!=1)
		{
			if(strlen($residence)<6||strlen($residence)>15)
			{
				$smarty->assign("RESIDENCE_ERROR","RED");
				$flag_r=1;
				$error++;
				$res_ins=1;			
			}
		}
		   			
		$flag_m=0;
		if(trim($mobile)!="")
		{	
			if(!preg_match("#^[+]?[0-9]+$#", $mobile))
			{
				$smarty->assign("MOBILE_ERROR","red");	
				$flag_m=1; 
				$error++;
			}
			else 
			{
				if((strlen($mobile)<10)||(strlen($mobile)>15))
				{
					$smarty->assign("MOBILE_ERROR","RED");
					$flag_m=1;
					$error++;
				}
			}
		}
                                                                                                                             
		//if mobile is wrong and residence no. is wrong
		if(($flag_r==1))
		{
			if($res_ins!=1)
			{
				if(trim($residence)!="")
				{
					$error++;
					$smarty->assign("RESIDENCE_ERROR","red");
				}
				else if($flag_m==1||trim($mobile)=="")
				{
					$error++;
					$smarty->assign("RESIDENCE_ERROR","RED");
				}
			}
	 	}
	}
	if($LOAN4)
	{		
		if(!$FixedDeposits && !$MutualFunds && !$SavingAccounts && !$Einvestment)
		{
			$error++;
			$smarty->assign("INVESTMENT_ERROR","RED");
		}
	}
	if($LOAN3)
	{
		if(trim($InsuranceAmount)=="")
		{
			$error++;
			$smarty->assign("INSURANCE_ERROR","RED");
		}
	}
	if($LOAN3)
	{
		if(trim($InsuranceAmount)!="")
		{
			if(!ereg ("^[-]?[0-9]+([\.][0-9]+)?$", trim($InsuranceAmount)))
			{
				$error++;
				$smarty->assign("INSURANCE_ERROR","RED");
			}
		}
	}	
	if($error>0)
	{
		//Setting the drop down values in HTM file
		
		$city1=create_dd($city,"City_India");
		$smarty->assign("city",$city1);

		$occupation1=create_dd($occupation,"Occupation");
		$smarty->assign("occupation",$occupation1);

		$income1=create_dd($income,"Income");
		$smarty->assign("income",$income1);
		$smarty->assign("error",$error);
		$smarty->assign("LoanAmount1",$LoanAmount1);
		$smarty->assign("Budget1",$Budget1);
		if($property_identified=="N")
			$smarty->assign("property_identified2","checked");
		else
			$smarty->assign("property_identified1","checked");
		if($property=="Y")
			$smarty->assign("property1","checked");
		else
			$smarty->assign("property2","checked");
		$smarty->assign("LoanAmount2",$LoanAmount2);
		$smarty->assign("Budget2",$Budget2);
		//$smarty->assign("Bank",$Bank);
		if($Insurance1 =="GeneralInsurance")
			$smarty->assign("GeneralInsurance","checked");
		else
			$smarty->assign("GeneralInsurance","");
		if($Insurance2=="LifeInsurance")
			$smarty->assign("LifeInsurance","checked");
		else
			$smarty->assign("LifeInsurance","");

		$smarty->assign("Pincode1",$Pincode1);
		$smarty->assign("InsuranceAmount",$InsuranceAmount);
		if($FixedDeposits!="")
			$smarty->assign("FixedDeposits","checked");
		if($MutualFunds!="")
			$smarty->assign("MutualFunds","checked");
		if($SavingAccounts!="")
			$smarty->assign("SavingAccounts","checked");
		if($Einvestment!="")
			$smarty->assign("Einvestment","checked");	

		//Setting the drop down values ends here//

		$smarty->assign("SUBMIT_ERROR","inline"); //Showing the error statement to user
		$smarty->assign("TOTAL_ERROR",$error);   //Showing the total error to the user

		if($ALL_LOAN!="")                       //if ALL_LOAN checkbox is clicked 
			$smarty->assign("ALL_LOAN","checked"); 

		//Getting the all checkboxes status and setting their status back because of error
		for($i=1;$i<=4;$i++)                      
		{
			if($_POST["LOAN$i"]!="")
			{
				$smarty->assign("LOAN$i","checked");
				$smarty->assign("loan_$i","inline");
			}
			else
				$smarty->assign("loan_$i","none");
		}
		//Above code setting the checkbox ends here

		if($PROFILE_SET!=1)
			$smarty->assign("profileid",$profileid);
		else	
			$smarty->assign("profileid",0);
		$smarty->assign("name",stripslashes($name));
		$smarty->assign("sname",stripslashes($sname));
		$smarty->assign("email",stripslashes($email));
		$smarty->assign("residence",stripslashes($residence));
		$smarty->assign("mobile",stripslashes($mobile));
		$smarty->assign("age",stripslashes($age));
		$smarty->assign("gender_$gender","checked='checked'");
		$smarty->assign("SOURCE",$SOURCE);

		//Assigns values only when pincode is integer
		if($pincode_error!=1)
			$smarty->assign("PINCODE",$pincode);

		$smarty->display("finance_mailer_landing.htm");
	}
	else                               //If no error is submitting form
	{
		//Getting the checkboxes values who are clicked by the User
		if($ALL_LOAN!="")	
			$OPTION_SELECT="ALL ";
		else
		{
			//Getting the loan values that the user is selecting
			for($i=1;$i<=4;$i++)
			{
				if($_POST["LOAN$i"]!="")
				$OPTION_SELECT.=$_POST["LOAN$i"].", ";
			}
			$OPTION_SELECT=substr($OPTION_SELECT,0,(strlen($OPTION_LENGTH)-2));
			//loan values intake ends here//		
		}
		//Extra Details Added By Tapan Arora
		
		if($ALL_LOAN=="" && $LOAN1)
		{
			
			$LOAN_AGAINST_PROP_AMOUNT=$LoanAmount1;
			$LOAN_AGAINST_PROP_BUDGET=$Budget1;
			$PROPERTY_IDENTIFIED=$property_identified;
		}
		else
		{	
			$LOAN_AGAINST_PROP_AMOUNT='N/A';
			$LOAN_AGAINST_PROP_BUDGET='N/A';
			$PROPERTY_IDENTIFIED='N/A';
		}
		if($ALL_LOAN=="" && $LOAN2)
		{
			$PERSONALLOANAMOUNT=$LoanAmount2;
			$PERSONALLOANBUDGET=$Budget2;
			//$BANKOFINTERACTION=$Bank;
		}
		else
		{
			$PERSONALLOANAMOUNT='N/A';
			$PERSONALLOANBUDGET='N/A';
			//$BANKOFINTERACTION='N/A';
		}
		if(($ALL_LOAN=="" && $LOAN3) || $ALL_LOAN )
		{
			if($Insurance1 == "GeneralInsurance")
				$GENERALINSURANCE="Y";
			else
				$GENERALINSURANCE="N";
			if($Insurance2 == "LifeInsurance")
				$LIFEINSURANCE="Y";
			else
				$LIFEINSURANCE="N";
			$INSURANCEAMOUNT=$InsuranceAmount;
		}
		else
		{
			$GENERALINSURANCE='N/A';
			$LIFEINSURANCE='N/A';
			$INSURANCEAMOUNT='N/A';
		}
		if(($ALL_LOAN=="" && $LOAN4) || $ALL_LOAN )
                {		
			if($FixedDeposits)
				$FIXEDDEPOSIT="Y";
			else
				$FIXEDDEPOSIT="N";
			if($MutualFunds)
				$MUTUALFUND="Y";
			else
				$MUTUALFUND="N";
			if($SavingAccounts)
				$SAVINGACCOUNT="Y";
			else
				$SAVINGACCOUNT="N";
			if($Einvestment)
				$EINVESTMENT="Y";
			else
				$EINVESTMENT="N";
		}
		else
		{
			$FIXEDDEPOSIT='N/A';
			$MUTUALFUND='N/A';
			$SAVINGACCOUNT='N/A';
			$EINVESTMENT='N/A';
		}
		if($ALL_LOAN)
		{
			$LOAN_AGAINST_PROP_AMOUNT=$LoanAmount1;
                        $LOAN_AGAINST_PROP_BUDGET=$Budget1;
                        $PERSONALLOANAMOUNT=$LoanAmount2;
                        $PERSONALLOANBUDGET=$Budget2;
                        //$BANKOFINTERACTION=$Bank;
			$PROPERTY_IDENTIFIED=$property_identified;
		}
		
	//tracking for what is the source of leads is being done by source variable that will come from source. and by response time of the PROMOTIONAL_MAIL

		$ts=time();
		$today=date('Y-m-d G:i:s',$ts);

		//User is not login and not also not through passing PROFILEID and USERNAME in site
		if($profileid=="")
		{ 
			 //Inserting the record into PROMOTIONAL MAIL
			$sql="insert into newjs.PROMOTIONAL_MAIL (PROFILEID,EMAIL,ENTRY_TIME,RESPONSE_TIME,OCCUPATION,INCOME,NAME,SURNAME,RESIDENCE,MOBILE,AGE,CITY,GENDER,OPTION_SELECT,PINCODE,LOAN_AGAINST_PROP_AMOUNT,LOAN_AGAINST_PROP_BUDGET,PERSONALLOANBUDGET,INSURANCEAMOUNT,GENERALINSURANCE,LIFEINSURANCE,FIXEDDEPOSIT,MUTUALFUND,SAVINGACCOUNT,EINVESTMENT,PERSONALLOANAMOUNT,SOURCE,PROPERTY_IDENTIFIED) values('-1','$email','$today','$today','$occupation','$income','$name','$sname','$residence','$mobile','$age','$city','$gender','$OPTION_SELECT','$pincode','$LOAN_AGAINST_PROP_AMOUNT','$LOAN_AGAINST_PROP_BUDGET','$PERSONALLOANBUDGET','$INSURANCEAMOUNT','$GENERALINSURANCE','$LIFEINSURANCE','$FIXEDDEPOSIT','$MUTUALFUND','$SAVINGACCOUNT','$EINVESTMENT','$PERSONALLOANAMOUNT','$SOURCE','$PROPERTY_IDENTIFIED')";
			
		}
		else
		{
			/* if hidden value of profileid is not blank
			   if coming through link contains USERNAME and PROFILEID or 
			   if LOGIN */

			//getting the Username
			$sql1="select PHONE_RES,PHONE_MOB,USERNAME,AGE,GENDER,OCCUPATION,INCOME,EMAIL,CITY_RES,PINCODE from newjs.`JPROFILE` where  activatedKey=1 and PROFILEID='$profileid'";
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

				$username=$result['USERNAME'];					

				//if the user is already applied for the promotioan mail then update the values in TABLE
	 			$sql="select ID from newjs.PROMOTIONAL_MAIL where PROFILEID='$profileid'";
			        $res1=mysql_query_decide($sql);
		                if($row=mysql_fetch_array($res1))
			        { 
				       $id=$row['ID'];
				       $sql="UPDATE newjs.PROMOTIONAL_MAIL  SET EMAIL='$email',OCCUPATION='$occupation',INCOME='$income',NAME='$name',SURNAME='$sname',RESIDENCE='$residence',MOBILE='$mobile',AGE='$age',CITY='$city',GENDER='$gender',  RESPONSE_TIME='$today',USERNAME='".addslashes($username)."',OCCUPATION='$occupation',INCOME='$income',OPTION_SELECT='$OPTION_SELECT',PINCODE='$pincode', LOAN_AGAINST_PROP_AMOUNT='$LOAN_AGAINST_PROP_AMOUNT' , LOAN_AGAINST_PROP_BUDGET='$LOAN_AGAINST_PROP_BUDGET' , PERSONALLOANBUDGET='$PERSONALLOANBUDGET', INSURANCEAMOUNT='$INSURANCEAMOUNT',GENERALINSURANCE='$GENERALINSURANCE', LIFEINSURANCE='$LIFEINSURANCE', FIXEDDEPOSIT='$FIXEDDEPOSIT', MUTUALFUND='$MUTUALFUND', SAVINGACCOUNT='$SAVINGACCOUNT', EINVESTMENT='$EINVESTMENT',PERSONALLOANAMOUNT='$PERSONALLOANAMOUNT', SOURCE='$SOURCE',PROPERTY_IDENTIFIED='$PROPERTY_IDENTIFIED'  WHERE PROFILEID='$profileid'";
				}
				else //if not present in promotional table then inserts the records
			        {
				       $sql="insert into newjs.PROMOTIONAL_MAIL (PROFILEID,USERNAME,EMAIL,ENTRY_TIME,RESPONSE_TIME,OCCUPATION,INCOME,NAME,SURNAME,RESIDENCE,MOBILE,AGE,CITY,GENDER,OPTION_SELECT,PINCODE,LOAN_AGAINST_PROP_AMOUNT,LOAN_AGAINST_PROP_BUDGET,PERSONALLOANBUDGET,INSURANCEAMOUNT,GENERALINSURANCE,LIFEINSURANCE,FIXEDDEPOSIT,MUTUALFUND,SAVINGACCOUNT,EINVESTMENT,PERSONALLOANAMOUNT,SOURCE,PROPERTY_IDENTIFIED) values('$profileid','".addslashes($username)."','$email','$today','$today','$occupation','$income','$name','$sname','$residence','$mobile','$age','$city','$gender','$OPTION_SELECT','$pincode','$LOAN_AGAINST_PROP_AMOUNT','$LOAN_AGAINST_PROP_BUDGET','$PERSONALLOANBUDGET','$INSURANCEAMOUNT','$GENERALINSURANCE','$LIFEINSURANCE','$FIXEDDEPOSIT','$MUTUALFUND','$SAVINGACCOUNT','$EINVESTMENT','$PERSONALLOANAMOUNT','$SOURCE','$PROPERTY_IDENTIFIED')";
				 }
			}
		}
		mysql_query_decide($sql) or die(mysql_error_js());
		$smarty->display("finance_thankyou.html");//To display the Thank you page
	}
}   
else   //If coming first time to this page
{
	$smarty->assign("ALL_LOAN","checked");  //By default making the ALL checkbox checked 
	$smarty->assign("gender_M","checked='checked'");  //By default making the Male  gender   checkbox checked
	$throughid=0;                          //variable userd for checking the user coming from link
	$throughlogin=0;			//variable checking the user coming when he is already login
        $profileid=0;                          //By default the PROFILEID is Zero

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

	if($profileid!=0)		//Getting all the data regarding that profileid
	{
		$sSQL="select GENDER,OCCUPATION,CITY_RES,AGE,EMAIL,PHONE_RES,PHONE_MOB,INCOME,PINCODE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sSQL); 
		if($row=mysql_fetch_array($result))   //if record present that set the values in htm file
		{
			//creating the dropdowns///////////
			if($row['EMAIL']==""||$row['AGE']==""||$row['CITY_RES']==""||$row['OCCUPATION']==""||$row['GENDER']=="" || $row['PINCODE']=="" )
                 		$PROFILE_SET=1;
			if($PROFILE_SET==1)
			{
				$email=$row['EMAIL'];
				$age=$row['AGE'];
				$city_res=$row['CITY_RES'];
				$gender=$row['GENDER'];
				$occupation=$row['OCCUPATION'];
				$income=$row['INCOME'];
				$smarty->assign("gender_$gender","checked=true");
				$smarty->assign("PINCODE",$row['PINCODE']);
				$smarty->assign("profileid",0);
	
			}
			else
				$smarty->assign("profileid",$profileid);
			
			$smarty->assign("residence",$row['PHONE_RES']);
		 	$smarty->assign("mobile",$row["PHONE_MOB"]);
  		}
	}
	
	
	//setting the dropdowns values
	for($i=1;$i<=4;$i++)
                {
                        if($_POST["LOAN$i"]=="")
                        {
                                $smarty->assign("loan_$i","none");
                        }
                }
	

	$city1=create_dd($city_res,"City_India");
	
	$occupation1=create_dd($occupation,"Occupation");
	$income1=create_dd($income,"Income");
	$smarty->assign("LoanAmount1",$LoanAmount1);
	$smarty->assign("Budget1",$Budget1);
	$smarty->assign("LoanAmount2",$LoanAmount2);
	$smarty->assign("Budget2",$Budget2);
	$smarty->assign("GeneralInsurance","checked");
	$smarty->assign("FixedDeposits","checked");
	$smarty->assign("property2","checked");
	$smarty->assign("email",$email);
	$smarty->assign("city",$city1);
	$smarty->assign("occupation",$occupation1);
	$smarty->assign("income",$income1);
	$smarty->assign("age",$age);
	$smarty->assign("SOURCE",$SOURCE);
	$smarty->assign("property_identified1","checked");
	$smarty->display("finance_mailer_landing.htm");//First display
}
?>
