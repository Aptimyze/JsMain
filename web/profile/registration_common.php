<?php

function display_related_castes($caste,$searchid,$checksum,$type,$mtongue)
{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        global $smarty;
                                                                                                                             
        if($type=='caste')
        {
                $sql="select distinct REL_CASTE from CASTE_COMMUNITY where PARENT_CASTE ='$caste'";
                $result=mysql_query_decide($sql);
                $count=0;
                $num=mysql_num_rows($result);
                //if no CASTE has been found in CASTE_MAPPING table then show him his CASTE and ALL option
                if($num<1)
                {
                        $rel_caste[$count]["LABEL"]=$CASTE_DROP[$caste];
                        $rel_caste[$count]["VALUE"]=$caste;
                        $count++;
                                                                                                                             
			//Added By lavesh for caste having small label as hindu.
			//All make no sense when caste is same as religion or there is only one caste.
			if(!in_array($caste,array(1,2,14,148,149,153,154,162,173)))
			{
				$other_caste=explode(":",$CASTE_DROP[$caste]);
                                $rel_caste[$count]["LABEL"]='All '.$other_caste[0];

				$sql="SELECT VALUE FROM newjs.CASTE WHERE LABEL='$other_caste[0]'";
                                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $row=mysql_fetch_array($result);
                                $caste_value=$row['VALUE'];

                                $rel_caste[$count]["VALUE"]=$caste_value;
                                $count++;
			}
			else
                                $smarty->assign("only_two_caste",1);
			//Ends Here

                        $rel_caste[$count]["LABEL"]='';
                        $rel_caste[$count]["VALUE"]='';
                        $count++;
                }
                else
                {
			$rel_caste[$count]["LABEL"]=$CASTE_DROP[$caste];
                        $rel_caste[$count]["VALUE"]=$caste;
                        $count++;

                        while($myrow=mysql_fetch_array($result))
                        {
                                $caste1=$myrow["REL_CASTE"];
                                $rel_caste[$count]["LABEL"]=$CASTE_DROP[$caste1];
                                $rel_caste[$count]["VALUE"]=$caste1;
                                                                                                                             
                                $count++;
                        }
                        while(($count % 3) !=0 )
                        {
                                $rel_caste[$count]["LABEL"]='';
                                $rel_caste[$count]["VALUE"]='';
				$count++;
                        }
                }

		//$rel_caste[$count]["LABEL"]="Doesn't Matter";
		//$rel_caste[$count]["VALUE"]='All';
                                                                                                                             
		$smarty->assign("REL_CASTE",$CASTE_DROP[$caste]);
		$smarty->assign("RELATED_CASTES",$rel_caste);
		$show_caste_mapping='Y';
		$smarty->assign("SHOW_CASTE_MAPPING",$show_caste_mapping);
		$smarty->assign("RELATED_CASTES_HTM",$smarty->fetch("related_castes_registration.htm"));
	}
	elseif($type=='mtongue')
	{
		$sql="select distinct COMMUNITY from CASTE_COMMUNITY where PARENT_CASTE ='$caste' AND COMMUNITY!=0";
		$result=mysql_query_decide($sql);
		$count=0;
		$num=mysql_num_rows($result);
		//if no MTONGUE has been found in CASTE_MAPPING table then dont show him MTONGUE section
		if($num<1)
		{
			$show_mtongue_mapping='N';
			/*$mtongue1=small_label_select("MTONGUE",$mtongue);
			$rel_mtongue[$count]["LABEL"]=$mtongue1[0];
			$rel_mtongue[$count]["VALUE"]=$mtongue;
			$count++;
			$rel_mtongue[$count]["LABEL"]='All';
			$rel_mtongue[$count]["VALUE"]='All';
			$count++;
			$rel_mtongue[$count]["LABEL"]='';
			$rel_mtongue[$count]["VALUE"]='';*/
		}
		else
		{
			while($myrow=mysql_fetch_array($result))
			{
				$mtongue=$myrow["COMMUNITY"];
				$mtongue1=small_label_select("MTONGUE",$mtongue);
				$rel_mtongue[$count]["LABEL"]=$mtongue1[0];
				$rel_mtongue[$count]["VALUE"]=$mtongue;
				$count++;
			}
			while(($count % 3) !=0 )
			{
				$rel_mtongue[$count]["LABEL"]='';
				$rel_mtongue[$count]["VALUE"]='';
				$count++;
			}
		$show_mtongue_mapping='Y';
		}

		//$rel_mtongue[$count]["LABEL"]="Doesn't Matter";
		//$rel_mtongue[$count]["VALUE"]='All';

		$smarty->assign("RELATED_MTONGUE",$rel_mtongue);
		$smarty->assign("SHOW_MTONGUE_MAPPING",$show_mtongue_mapping);
		$smarty->assign("RELATED_MTONGUE_HTM",$smarty->fetch("related_mtongue_registration.htm"));
	}
}                                                                                                                            

function display_city()
{
	mail('lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com','profile/registration_common.php called display_city()','profile/registration_common.php called display_city()');
}

function check_edu_status($Edu_level)
{
        $Edu_level_arr=explode(",",$Edu_level);
        $engineers="3,13,16,18";
        $professional="3,4,6,7,8,10,13,14,16,17,18,19,20,21";
        $doctors="17,19";
        $postgrad="6,7,8,10,11,12,13,14,15,16,17,18,19,20,21";
        $engineers=explode(",",$engineers);
        $professional=explode(",",$professional);
        $doctors=explode(",",$doctors);
        $postgrad=explode(",",$postgrad);
        for($i=0;$i<count($engineers);$i++)
        {
                if(in_array($engineers[$i],$Edu_level_arr))
                        $eng=1;
                else
                {
                        $eng=0;
                        break;
                }
        }
        if($eng==1)
                $eng1='ENG';
        for($i=0;$i<count($professional);$i++)
        {
                if(in_array($professional[$i],$Edu_level_arr))
                        $prof=1;
                else
                {
                        $prof=0;
                        break;
                }
        }
        if($prof==1)
                $prof1='PROF';

	for($i=0;$i<count($doctors);$i++)
        {
                if(in_array($doctors[$i],$Edu_level_arr))
                        $doc=1;
                else
                {
                        $doc=0;
                        break;
                }
        }
        if($doc==1)
                $doc1='DOC';
        for($i=0;$i<count($postgrad);$i++)
        {
                if(in_array($postgrad[$i],$Edu_level_arr))
                        $pg=1;
                else
                {
                        $pg=0;
                        break;
                }
        }
        if($pg==1)
                $pg1='PG';
        //echo "ENGG  :".$eng1."  PROFFF :".$prof1." DOCC :".$doc1." PGG :".$pg1;
        return $eng1.$prof1.$doc1.$pg1;
}


function check_country_status($Country)
{
        $Country_arr=explode(",",$Country);
        $north_america="128,22";
        $middle_east="1,10,63,99,125";
        $australia="7,82";
        $asia="11,14,25,48,52,70,74,80,88,101,103,110,119,92";
	$europe="33,35,40,39,126,42";
        $north_america=explode(",",$north_america);
        $middle_east=explode(",",$middle_east);
        $australia=explode(",",$australia);
        $asia=explode(",",$asia);
	$europe=explode(",",$europe);
        for($i=0;$i<count($north_america);$i++)
        {
                if(in_array($north_america[$i],$Country_arr))
                        $na=1;
                else
                {
                        $na=0;
                        break;
                }
        }
        if($na==1)
                $na1='NA';
        for($i=0;$i<count($middle_east);$i++)
        {
                if(in_array($middle_east[$i],$Country_arr))
                        $me=1;
                else
                {
                        $me=0;
                        break;
                }
        }
        if($me==1)
                $me1='ME';

	for($i=0;$i<count($australia);$i++)
        {
                if(in_array($australia[$i],$Country_arr))
                        $aus=1;
                else
                {
                        $aus=0;
                        break;
                }
        }
        if($aus==1)
                $aus1='AUS';
        for($i=0;$i<count($asia);$i++)
        {
                if(in_array($asia[$i],$Country_arr))
                        $as=1;
                else
                {
                        $as=0;
                        break;
                }
        }
        if($as==1)
                $as1='ASIA';
	for($i=0;$i<count($europe);$i++)
        {
                if(in_array($europe[$i],$Country_arr))
                        $eu=1;
                else
                {
                        $eu=0;
                        break;
                }
        }
        if($eu==1)
                $eu1='EU';

        return $na1.$me1.$aus1.$as1.$eu1;
}
?>
