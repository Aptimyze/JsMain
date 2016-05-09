<?php
class PartnerField
{
	/**
	 * constructor
	 */
	public function __construct()
	{
			$this->pObj=LoggedInProfile::getInstance();
			$this->profileid=$this->pObj->getPROFILEID();
			include_once(sfConfig::get("sf_web_dir")."/classes/Jpartner.class.php");
			$this->partnerObj=new Jpartner;
			$this->mysqlObj=new Mysql;
			$this->myDbName=getProfileDatabaseConnectionName($this->profileid,'',$this->mysqlObj);
			$this->myDb=$this->mysqlObj->connect($this->myDbName);
			$this->partnerObj->setPartnerDetails($this->profileid,$this->myDb,$this->mysqlObj);
	}

	/*
	 * 
	 */
	 public function setPage($actionObj,$request)
	 {
		    	$mstatus=$request->getParameter("partner_mstatus_arr");
			 $mstatusArr=$this->getPMstatus(implode(",",$mstatus?$mstatus:array()));
			 $actionObj->checked_mstatus=$mstatusArr["CHECKED"];
			 $actionObj->hidden_mstatus=$mstatusArr[HIDDEN];
			 $actionObj->shown_mstatus=$mstatusArr[SHOW];
				
				$mtongue=$request->getParameter("partner_mtongue_arr");
				$mtongueArr=$this->getPMtongue(implode(",",$mtongue?$mtongue:array()));
				$actionObj->priority_mtongue=$mtongueArr[PRIORITY];
				$actionObj->mapped_mton=$mtongueArr["CHECKED"];
			 $actionObj->hidden_mton=$mtongueArr[HIDDEN];
			 $actionObj->shown_mton=$mtongueArr[SHOW];
			 
			 
			 $sel_religion=$request->getParameter("partner_religion_arr");
			 if($sel_religion)
			 {
				 for($i=0;$i<count($sel_religion);$i++)
				 {
					 
					 $temp=explode("|X|",$sel_religion[$i]);
					 if($temp[0]!="DM")
					 $pRel[]=$temp[0];
				 }
				 $selectedRel=implode(",",$pRel);
				 
			 }
			 //die($selectedRel);
			$caste=$request->getParameter("partner_caste_arr");
			 $sel_caste=implode(",",$caste?$caste:array());
			$religionArr=$this->getPReligion($selectedRel,$sel_caste,$actionObj);
			$actionObj->checked_religion=$religionArr["CHECKED_RELIGION"];	
			$actionObj->checked_caste=$religionArr["CHECKED_CASTE"];
			
			 $actionObj->hidden_religion=$religionArr[HIDDEN];
			 $actionObj->shown_religion=$religionArr[SHOW];
			 $actionObj->priority_religion=$religionArr[PRIORITY];
			 $actionObj->mapped_rel=$religionArr[MAPPED];
			 $actionObj->checked_caste=$religionArr[CHECKED_CASTE];
	 }
/*
 * return partner lower age on the basis of user's own age
 */
	public function getP_LAGE()
	{
		return $this->partnerObj->getLAGE();
		/*
		if($this->pObj->getGENDER()=="F")
			return ($this->pObj->getAGE()>29)?$this->pObj->getAGE()-2:(($this->pObj->getAGE()>26)?$this->pObj->getAGE()-1:(($this->pObj->getAGE()>22)?$this->pObj->getAGE():21));
		else
		{
			$age=$this->pObj->getAGE()-5;
        	        return ($age<'18')?18:$age;
		}
		*/
	}
/*
 * return partner higher age on the basis of user's own age
 */	
	public function getP_HAGE()
	{
		return $this->partnerObj->getHAGE();
		/*if($this->pObj->getGENDER()=="F")
			$age=($this->pObj->getAGE()>33)?$this->pObj->getAGE()+15:(($this->pObj->getAGE()==33)?47:(($this->pObj->getAGE()==32)?44:(($this->pObj->getAGE()==31)?42:$this->pObj->getAGE()+10)));
		else
		{
			$age=$this->pObj->getAGE();
		}
		return ($age>70)?70:$age;
		*/
	}
	
	/*
 * return partner lower height on the basis of user's own height
 */
	public function getP_LHEIGHT()
	{
		return $this->partnerObj->getLHEIGHT();
		/*
		if($this->pObj->getGENDER()=="F")
			return $this->pObj->getHEIGHT();
		else
			return ($this->pObj->getHEIGHT()-10);
			*/ 
		
	}
	
	/*
 * return partner higher height on the basis of user's own height
 */
	public function getP_HHEIGHT()
	{
		return $this->partnerObj->getHHEIGHT();
		/*
		if($this->pObj->getGENDER()!="F")
                        $height=$this->pObj->getHEIGHT();
                else
                        $height=($this->pObj->getHEIGHT()+10);
		return ($height>37)?37:$height;
		*/
	}
	
	/*
	 * return user's partner mtongue on basis on user's own mtongue
	 */
	public function getPMtongue($values)
	{
		if(!$values)
			$values=str_replace("'","",$this->partnerObj->getPARTNER_MTONGUE());
				
			//$cur_values=str_replace("'","",$this->pObj->getMTONGUE());
			
			$val_array=explode(",",$values);
			
			$dbObj=new NEWJS_MTONGUE;
			$res=$dbObj->getDATA();
                $hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_DM\"> <label id=\"partner_mtongue_label_DM\">All</label><br>";
                for($i=0;$i<count($res);$i++)
                {
												$myrow=$res[$i];
                        $mtongue_region=$myrow['REGION'];
                        if($mtongue_region!=$mtongue_region_old)
                        {
                        	if($mtongue_region==5)
                                {
                                                //2339:lavesh -- All Hindi will come below North Option.
                                       $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n"; 
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
                                 }
                                 elseif($mtongue_region==4)
                                 {
                        	        $shown_vals.=" <span style=\"color:#0a89fe;\">North</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
					$hidden_vals.= "<input type=\"checkbox\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\" value=\"10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\"><label id=\"partner_mtongue_label_10|#|41|#|33|#|27|#|7|#|28|#|13|#|14|#|15|#|70|#|36|#|10,19,33,7,28,13,41\">North</label><br>";
					 $flag_allhindi.= "<option value=\"10,19,33,7,28,13,41\"  >All Hindi</option>\n";
                                       $hidden_vals_hindi.=" <input type=\"checkbox\" value=\"10,19,33,7,28,13,41\" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_10,19,33,7,28,13,41\"> <label id=\"partner_mtongue_label_10,19,33,7,28,13,41\">All Hindi</label><br>";
                                       $shown_vals_hindi.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_10,19,33,7,28,13,41\" value=\"10,19,33,7,28,13,41\"><label id=\"partner_mtongue_displaying_label_10,19,33,7,28,13,41`\">All Hindi</label><br>";
					if($flag_allhindi)
                                        {
                                        	$hidden_vals.=$hidden_vals_hindi;
                                        	$shown_vals.=$shown_vals_hindi;
                                        }
				}
				elseif($mtongue_region==3)
                                {
                                     $shown_vals.=" <span style=\"color:#0a89fe;\">West</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_20|#|12|#|19|#|34|#|30|#|9\" value=\"20|#|12|#|19|#|34|#|30|#|9\"><label id=\"partner_mtongue_label_20|#|12|#|19|#|34|#|30|#|9\">West</label><br>";
                                }
                                elseif($mtongue_region==2)
                                {
                                          $shown_vals.=" <span style=\"color:#0a89fe;\">South</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\" value=\"31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\"><label id=\"partner_mtongue_label_31|#|3|#|16|#|17|#|2|#|18|#|35|#|71\">South</label><br>";
                                }
                                elseif($mtongue_region==1)
                                {
                                                $shown_vals.=" <span style=\"color:#0a89fe;\">East</span><div class=\"clear\" style=\"line-height:5px;\"> &nbsp;</div>";
                                                $hidden_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\" value=\"6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\"><label id=\"partner_mtongue_label_6|#|25|#|5|#|4|#|21|#|22|#|23|#|24|#|29|#|32\">East</label><br>";
                                }
                                $mtongue_region_old=$mtongue_region;
			}
			if($myrow['VALUE']=='30' && $mtongue_region==4)
                                $myrow['VALUE']='70';
			$val=$myrow['VALUE'];
			if($myrow['VALUE']=='19' && $mtongue_region==4)
				$val=41;
			if($myrow['VALUE']=='36' && $mtongue_region==2)
				$val=71;
			//echo $val." ->".$values." <BR>";
			if($val==$cur_values)
			{
				$allHindiStr = '10,19,33,7,28,13,41';
				$allHindi = explode(',','10,19,33,7,28,13,41');
				if(in_array($cur_values,$allHindi))
				{
					$priLabel = "All Hindi";
					$cur_values = $allHindiStr;
				}
				else
					$priLabel = $myrow['LABEL'];
			}
			$mtongue_arr[]=$val;
			$label_arr[$val]=$myrow['LABEL'];
			$hidden_vals.=" <input type=\"checkbox\" value=".$val." name=\"partner_mtongue_arr[]\" id=\"partner_mtongue_".$val."\"> <label id=\"partner_mtongue_label_".$val."\">".$myrow['LABEL']."</label><br>";
			if(in_array($val,$val_array))
			{
					if($checked)
						$checked.=",'".$val."'";
					else
						$checked.="'".$val."'";
			}
			else
                        $shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$val."\" value=".$val." ><label id=\"partner_mtongue_displaying_label_".$val."\">".$myrow['LABEL']."</label><br>";
		}
		$priority_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mtongue_displaying_arr[]\" id=\"partner_mtongue_displaying_".$cur_values."\" value=".$cur_values."><label id=\"partner_mtongue_displaying_label_".$cur_values."\">".$priLabel."</label><br>";
		
		return array("PRIORITY"=>$priority_vals,"HIDDEN"=>$hidden_vals,"SHOW"=>$shown_vals,"CHECKED"=>$checked);
		
	}
	/*
 * return partner religion on user's own religion
 */
	public function getPReligion($rvalues,$cvalues,$actionObj)
	{
		//echo $rvalues;die;
				$rval_array=Array();
				$cval_array=Array();
				if(!$rvalues)
					$rvalues=str_replace("'","",$this->partnerObj->getPARTNER_RELIGION());
				if($rvalues)	
					$rval_array=explode(",",$rvalues);
				
				//User own religion
				if(!$rvalues)	
				$cur_religion=$this->pObj->getRELIGION();	
				
				if(!$cvalues)
					$cvalues=str_replace("'","",$this->partnerObj->getPARTNER_CASTE());
				
					
				if($cvalues)	
					$actionObj->cval_array=explode(",",$cvalues);
				else
						$cval_array=explode(",",$this->pObj->getCASTE());
				
				
				
				$dbObj=new NEWJS_RELIGION;
				$reg_result=$dbObj->getDATA();
				
				$rel_arr= Array();
				$i=1;
        	for($z=0;$z<count($reg_result);$z++)
        	{
								$rel_flag=0;
								$row=$reg_result[$z];
								
	                $label = $row['LABEL'];
	                $religion_value = $row['VALUE'];
	                if(in_array($religion_value,$rval_array))
										$rel_flag=1;
	                
									$caste_str='';
									$dbObj=new NEWJS_CASTE;
									$casteRes=$dbObj->getDATA($religion_value);
									
	                
	                for($k=0;$k<count($casteRes);$k++)
	                {
													$row_caste=$casteRes[$k];
													$caste_value = $row_caste['VALUE'];
													$val=$caste_value;
													if(in_array($val,$cval_array))
													{
															$checked_caste[]=$val;
													}

	                        $caste_label_arr = explode(": ",$row_caste['LABEL']);
	                        if($caste_label_arr[1])
	                        $caste_label = $caste_label_arr[1];
	                        else
	                        $caste_label = $caste_label_arr[0];
	                        $caste_label=str_replace(" ",":",$caste_label);
	                        $caste_str .= $caste_value."$".$caste_label."#";
        	        }
        	        $religion_str = $religion_value."|X|".$caste_str;
       if($rel_flag)
					$rel_arr[]=$religion_str;
					
			if(intval($religion_value)==intval($cur_religion))
			{
				$religion_arr[0]=$religion_str;
				$label_arr[0]=$label;
			}
				
			$religion_arr[$i]=$religion_str;
			$label_arr[$i]=$label;
			$i++;
		}

		$religion_str=$religion_arr[0];
		$label=$label_arr[0];
		$priority_vals= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label_arr[0]."</label><br>";
		$mapped_rel=$religion_str;
		$len=count($religion_arr);
		for($i=1;$i<$len;$i++)
		{
			$religion_str=$religion_arr[$i];
			$label=$label_arr[$i];
        	        $hidden_vals.=" <input type=\"checkbox\" value=".$religion_str." name=\"partner_religion_arr[]\" id=\"partner_religion_".$religion_str."\"> <label id=\"partner_religion_label_".$religion_str."\">".$label."</label><br>";
     
			if(!in_array($religion_str,$rel_arr))
				$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_religion_displaying_arr[]\" id=\"partner_religion_displaying_".$religion_str."\" value=".$religion_str."><label id=\"partner_religion_displaying_label_".$religion_str."\">".$label."</label><br>";
			
				
		}
			$rel_flag=0;
               		unset($caste_str);
      if(count($rel_arr)>0)
				$checked_religion="'".implode("','",$rel_arr)."'";
			if(count($checked_caste)>0)
				$checkedcaste="'".implode("','",$checked_caste)."'";	
      return array("HIDDEN"=>$hidden_vals,"SHOW"=>$shown_vals,"PRIORITY"=>$priority_vals,"CHECKED_CASTE"=>$checkedcaste,"CHECKED_RELIGION"=>$checked_religion);
			
	}
	/*
 * return partner marital status options on basis on user own marital status
 */
	public function getPMstatus($values)
	{
		if(!$values)
			$values=$this->partnerObj->getPARTNER_MSTATUS();
		if(!$values)
			$values=$this->pObj->getMSTATUS();
			
		$values=str_replace("'","",$values);	
		$flag=($this->pObj->getRELIGION()!=2)?1:0;
		$val_array=explode(',',$values);
		$hidden_vals.=" <input type=\"checkbox\" value=\"DM\" name=\"partner_mstatus_arr[]\" id=\"partner_mstatus_DM\"> <label id=\"partner_mstatus_label_DM\">Doesn't Matter</label><br>";
		$MSTATUS=FieldMap::getFieldLabel("marital_status",'',1);
		
		foreach($MSTATUS as $value=>$label)
		{
			if(!$flag || ($flag && $value!='M'))
			{	
				$hidden_vals.=" <input type=\"checkbox\" value=".$value." name=\"partner_mstatus_arr[]\" id=\"partner_mstatus_".$value."\"> <label id=\"partner_mstatus_label_".$value."\">".$label."</label><br>";
				$val="".$value."";
				
				
				if(in_array($val,$val_array))
				{
					if($checked)
						$checked.=",'".$val."'";
					else
						$checked.="'".$val."'";
				}
				else
				{
					$shown_vals.= "<input type=\"checkbox\" class=\"chbx \" name=\"partner_mstatus_displaying_arr[]\" id=\"partner_mstatus_displaying_".$value."\" value=".$value."><label id=\"partner_mstatus_displaying_label_".$value."\">".$label."</label><br>";
				}
			}
		}
		return array("CHECKED"=>$checked,"HIDDEN"=>$hidden_vals,"SHOW"=>$shown_vals);
	}
	
	public function UpdateDPP($request)
	{
		$this->partnerObj->setPARTNER_CASTE($this->UpdateField($request->getParameter("partner_caste_arr")));
		$this->partnerObj->setPARTNER_MSTATUS($this->UpdateField($request->getParameter("partner_mstatus_arr")));
		$this->partnerObj->setPARTNER_MTONGUE($this->UpdateField($this->correctPartnerMtongue($request->getParameter("partner_mtongue_arr"))));
		$this->partnerObj->setPARTNER_RELIGION($this->UpdateField($request->getParameter("partner_religion_arr"),1));
		
		//print_r($this->partnerObj);die;
		//print_r($request->getParameter("partner_religion_arr"));
		//echo $this->UpdateField($request->getParameter("partner_religion_arr"),1);die;
		$param=$request->getParameter("reg");
		//print_r($param);die;
		//echo $param["p_lage");die;
		$this->partnerObj->setLAGE($param["p_lage"]);
		$this->partnerObj->setHAGE($param["p_hage"]);
		$this->partnerObj->setLHEIGHT($param["p_lheight"]);
		$this->partnerObj->setHHEIGHT($param["p_hheight"]);
		$this->setIncome($param);
		
		//$this->partnerObj->setDPP('R');
		
		
		$scase= "DATE=now()";
		$this->partnerObj->updatePartnerDetails($this->myDb,$this->mysqlObj);
		
		
	}
	private function setIncome($param)
	{
		$rsLIncome=$param[p_lrs];$rsHIncome=$param[p_hrs];$doLIncome=$param[p_lds];$doHIncome=$param[p_hds];
		
		if($rsLIncome || $rsLIncome=='0' || $rsHIncome || $rsHIncome=='0' || $doLIncome || $doLIncome=='0' || $doHIncome || $doHIncome=='0')
		{
			if($rsLIncome || $rsLIncome =='0')
                {
                         $rArr["minIR"]=intval($rsLIncome);
                         $rsIncomeMentioned=1;
                }
                if($rsHIncome || $rsHIncome=='0')
                        $rArr["maxIR"]=intval($rsHIncome);
                if($doLIncome || $doLIncome=='0')
                {
                        $dArr["minID"]=intval($doLIncome);
                        $doIncomeMentioned=1;
                }
                if($doHIncome || $doHIncome =='0')
                        $dArr["maxID"]=intval($doHIncome);

                if($rsIncomeMentioned && $doIncomeMentioned)      
					$incomeObj=new IncomeMapping($rArr,$dArr);
                elseif($rsIncomeMentioned)
					$incomeObj=new IncomeMapping($rArr,"");
                else
					$incomeObj=new IncomeMapping("",$dArr);

                $resulArr = $incomeObj->incomeMapping();
			}
			$this->partnerObj->setPARTNER_INCOME($resulArr['istr']);
			$this->partnerObj->setLINCOME($resulArr['rsLIncome']);
			$this->partnerObj->setHINCOME($resulArr['rsHIncome']);
			$this->partnerObj->setLINCOME_DOL($resulArr['doLIncome']);	
			$this->partnerObj->setHINCOME_DOL($resulArr['doHIncome']);
	}
	private function correctPartnerMtongue($profile_mtongue_arr)
	{
		//Correction for sindhi
		if($partner_mtongue_arr)
    {
                foreach($partner_mtongue_arr as $key=>$val)
                {
                        if($val == 70)
                        {
                                unset($val);
                                $partner_mtongue_arr[$key][$val] = 30;
                        }
                }
    }

			$mtongue_string=trim(implode(",",$profile_mtongue_arr),"'");

			//keywords contacin array of all mtongue values specified in the string
			$keywords = preg_split("/\|#\|+|','+|,/", $mtongue_string);
			$keywords=array_unique($keywords);
			return $keywords;
	}
	private function UpdateField($arr,$isRel=0)
	{
		if(is_array($arr))
		foreach($arr as $key=>$val)
		{
			if($val!="DM" && $val!="All" && $val!="")
			{
				$val=$this->removeSpecialCharacters($val,$isRel);
				$tempArr[]="'".$val."'";
			}	
		}
		
		if(is_array($tempArr))
		{
			return implode(",",$tempArr);
		}
		return "";
	}
	public function removeSpecialCharacters($var,$isRel)
	{
		if($isRel)
		{
			$vv=explode("|X|",$var);
			$var=$vv[0];
		}
		
		$var=str_replace("'","",$var);
		$var=str_replace("\\","",$var);
		return $var;
	}
}
