<?php
/*********************************************************************************************
* FILE NAME   : city.php
* DESCRIPTION : It is used for locating Country and city for astro users through astro database
* DATE        : May 22, 2005
* MADE BY     : Kush Asthana
*********************************************************************************************/

include "connect.inc";
connect_db();
if($action=="check")
{
	if(trim($tState)=="")
		$str1="select * from ASTROYOGI_CITY where COUNTRY_CODE='".trim($tCountry)."' and CITYNAME like '".trim($tCity)."%' AND ACTIVE='Y' order by CITYNAME";
	else
		$str1="select * from ASTROYOGI_CITY where COUNTRY_CODE='".trim($tCountry)."' and CITYNAME like '".trim($tCity)."%' and STATE_CODE='".trim($tState)."' AND ACTIVE='Y' order by CITYNAME";
	
	$result=mysql_query_decide($str1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$str1,"ShowErrTemplate");;
	if(mysql_num_rows($result)==0)
	{
		$flag="Y";
		$smarty->assign("flag",$flag);
		$smarty->assign("cityflag_value",$cityflag);
		//$smarty->assign("frominput","1");
		$smarty->assign("frominput",$frominput);
		$smarty->assign("action_value",$action);
		$smarty->display("astro1.htm");
	}
	else
	{
		$myrow=mysql_fetch_array($result);
		$country_name=trim($myrow['COUNTRY']);
		if($country_name=="INDIA")
                	$country_name="India";
		$state=trim($tState);
		$country=trim($tCountry);
		$k=0;
		do
		{
		        $tempcity=$myrow["CityName"];
        		$tempcity=explode(" ",$tempcity);
        		$totrow=count($tempcity);
			$label='';
			for($i=0;$i<$totrow;$i++)
			{
				$label.= strtoupper(substr($tempcity[$i],0,1)).strtolower(substr($tempcity[$i],1,strlen($tempcity[$i])))." ";
			}
				$value=trim($myrow["CityName"]);
			$city_label[]= array(
						"LABEL" => $label,
						"VALUE" => $value,
					  );
		}while($myrow=mysql_fetch_array($result));
		
		$smarty->assign("city_label",$city_label);
		
		if($state != '')
		{
			$sql="select * from ASTROYOGI_STATE where State_code='$state'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");;
			$myrow=mysql_fetch_array($result);
			$state_name=$myrow['State'];
			$smarty->assign("rstate_value",$state_name);
		}
		$smarty->assign("rcountry_value",$country_name);
		//$smarty->assign("frominput","1");
		$smarty->assign("frominput",$frominput);
		$smarty->assign("action_value","my");
		$smarty->assign("cityflag_value",$cityflag);
	
		$smarty->display("astro2.htm");
	}
                                                                                                                                                                                                  
}
elseif($action=="my")
{       
	
	$sfcity=$rcity;
	$scountry=$rcountry;
	$sstate=$rstate;
	if($sstate=="")
        	$sfstate='';
	else
		$sfstate=", ".$sstate;



	$sfcity= explode(" ",$sfcity);
	$totsrow=count($sfcity);
	$scity='';
	for($i=0;$i<$totsrow;$i++)
	{
        	$scity_temp[]=strtoupper(substr($sfcity[$i],0,1)).strtolower(substr($sfcity[$i],1,strlen($sfcity[$i])));
	}
	$scity=implode(" ",$scity_temp);	
		
        if($scity=="")
	{
	        echo("Please select city");
		$smarty->assign("cityflag_value",$cityflag);
		//$smarty->assign("frominput","1");
		$smarty->assign("frominput",$frominput);
		$smarty->assign("action_value","check");
		$smarty->display("astro1.htm");
	}
        else
	{
		echo "<html>";
		echo "<head>";
		echo "<script>";
		echo "function js()";
		echo "{	";
		if(($cityflag==1)&&($frominput==0))
			echo "window.opener.document.form1.button_birth.value='$scity$sfstate'+', '+'$scountry';window.opener.document.form1.birth_place.value='$scity$sfstate';window.opener.document.form1.birth_country.value='$scountry';self.close()";
		elseif(($cityflag==1)&&($frominput==1))
			echo "window.opener.document.form1.City_Birth.value='$scity$sfstate';self.close()";
		elseif($cityflag==2)
			echo "window.opener.document.form1.button_current.value='$scity$sfstate'+', '+'$scountry';window.opener.document.form1.current_place.value='$scity$sfstate';window.opener.document.form1.current_country.value='$scountry';self.close()";
		elseif($cityflag==3)
			echo "window.opener.document.form1.sbutton_birth.value='$scity$sfstate%>'+', '+'$scountry';window.opener.document.form1.sbirth_place.value='$scity$sfstate';window.opener.document.form1.sbirth_country.value='$scountry';self.close()";
		elseif($cityflag==4)
			echo "window.opener.document.form1.tbutton_birth.value='$scity$sfstate'+', '+'$scountry';window.opener.document.form1.tbirth_place.value='$scity$sfstate';window.opener.document.form1.tbirth_country.value='$scountry';self.close()";
		elseif($cityflag==5)
			echo "window.opener.document.form1.fpbutton_birth.value='$scity$sfstate'+', '+'$scountry';window.opener.document.form1.fpcity.value='$scity"."$sfstate';window.opener.document.form1.fpcountry.value='$scountry';self.close()";
		echo " }";
		echo "</script>";
		echo "</head>";
		echo "<body onload=js();>";
		echo "<form name=form_empty>";
		echo "</form>";
		echo "</body>";
		echo "</html>";
	}
}
else
{
	//$smarty->assign("frominput","1");
	$smarty->assign("frominput",$frominput);
	$smarty->assign("action_value","check");
	$smarty->assign("cityflag_value",$cityflag);
	$smarty->display("astro1.htm");
}
?>
