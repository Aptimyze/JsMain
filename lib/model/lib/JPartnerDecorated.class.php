<?php

class JPartnerDecorated extends JPartner{
			private $FuncToLabelMap;
			private $dosntMatterArr;
			public $fromPage;
	        public function __construct($table='',$page_source='')
			{
				parent::__construct($table);
				$fromPage="Edit";
				$this->FuncToLabelMap=array(
					"PARTNER_ELEVEL"=>"education",
					"GENDER"=>"gender",
					"CHILDREN"=>"children",
					"LHEIGHT"=>"height",
					"HHEIGHT"=>"height",
					"PARTNER_CITYRES"=>"city",
					"PARTNER_COUNTRYRES"=>"country",
					"PARTNER_ELEVEL_NEW"=>"education",
					"PARTNER_ELEVEL"=>"education_label",
					"PARTNER_CASTE"=>"caste",
					"PARTNER_MTONGUE"=>"community",
					"PARTNER_MANGLIK"=>"manglik_label",
					"PARTNER_MSTATUS"=>"marital_status",
					"PARTNER_DIET"=>"diet",
					"PARTNER_COMP"=>"complexion",
					"PARTNER_BTYPE"=>"bodytype",
					"PARTNER_SMOKE"=>"smoke",
					"PARTNER_DRINK"=>"drink",
					"HANDICAPPED"=>"handicapped",
					"NHANDICAPPED"=>"nature_handicap",
					"PARTNER_OCC"=>"occupation",
					"PARTNER_RES_STATUS"=>"rstatus",
					"PARTNER_RELIGION"=>"religion",
					"PARTNER_NAKSHATRA"=>"nakshatra",
					"LINCOME_DOL"=>"lincome_dol",
					"HINCOME_DOL"=>"hincome_dol",
					"LINCOME"=>"lincome",
					"HINCOME"=>"hincome",
					"PARTNER_INCOME"=>"partner_income",
					"STATE"=>"state_india",
					"OCCUPATION_GROUPING"=>"occupation_grouping",
				);
				//Add  Fields to this array if want to show Does Not matter in value instead of hyphen
				$this->dosntMatterArr=array(
					"PARTNER_MSTATUS",
					"PARTNER_COUNTRYRES",
					"PARTNER_CITYRES",
					"PARTNER_RELIGION",
					"PARTNER_MTONGUE",
					"PARTNER_MANGLIK",
					"PARTNER_CASTE",
					"CHILDREN",
				);
				if($page_source){
					
					$this->from_seo=true;
					$this->page_source=$page_source;
					
			}
			}
			public function __call($name,$arguments){
				if(preg_match("/^getDecorated/",$name)){
					 $funcName=str_replace("getDecorated","get",$name);
					 $labelName=str_replace("getDecorated","",$name);						
					 if($this->fromPage=="View")
						$def_val="";
					else
						$def_val=in_array($labelName,$this->dosntMatterArr)?"Doesn't Matter":"-";

					if(array_key_exists($labelName,$this->FuncToLabelMap)){
						if($labelName=='PARTNER_INCOME')
						{
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");

							$minID=$this->getLINCOME_DOL();
							$maxID=$this->getHINCOME_DOL();
							$minIR=$this->getLINCOME();
							$maxIR=$this->getHINCOME();
							if($minID!='' && $minIR!=''){
							if($minIR==0)
							{
								if($maxIR)
									$rsText="Rs. 0 to ".FieldMap::getFieldLabel('hincome',$maxIR);
								else
									$rsText="Rs. No Income";
							}
							else
								$rsText=FieldMap::getFieldLabel('lincome',$minIR)." to ".FieldMap::getFieldLabel('hincome',$maxIR);
							if($rsText)
								$varr[]=str_replace("to and above","and above",$rsText);

							if($minID==0)
							{
								if($maxID)
									$rsDoll=" $0 to ".FieldMap::getFieldLabel('hincome_dol',$maxID);
								else if($rsText != "Rs. No Income")
									$rsDoll=" $ No Income";
								
								//If Rs And Dollar Both are no income 
								if($rsText == "Rs. No Income" && $rsDoll==null)
									$varr[0] = "No Income"; 
							}
							else
								$rsDoll=FieldMap::getFieldLabel('lincome_dol',$minID)." to ".FieldMap::getFieldLabel('hincome_dol',$maxID);
							if($rsDoll)
								$varr[]=str_replace("to and above","and above",$rsDoll);
							if($varr)
								$PARTNER_INCOME_NEW=implode(", &nbsp;",$varr);
							}
							else
								$PARTNER_INCOME_NEW="-";
							
							return $PARTNER_INCOME_NEW;
						}
						$partner_stripped_label=str_replace("PARTNER_","",$labelName);
						if($this->from_seo && is_array($this->seo_related_fields) && in_array($partner_stripped_label,$this->seo_related_fields))
						{
							$partnerStr = str_replace("'","",$this->$funcName());
							$partnerArr = explode(",",$partnerStr);
							if($partnerArr)
							{
									foreach($partnerArr as $key=>$val)
									{
										$valStr = FieldMap::getFieldLabel($this->FuncToLabelMap[$labelName],$val);
										$this->partnerLink[$partner_stripped_label];
										if(is_array($this->partnerLink[$partner_stripped_label]) && array_key_exists($val,$this->partnerLink[$partner_stripped_label]))
										{
											
											$partnerSeoArr[] = "<a href=".$this->partnerLink[$partner_stripped_label][$val]." style='color:#000000'>".$valStr."</a>";
											
										}
										else
											$partnerSeoArr[] = $valStr;

									}
									 $partnerSeo = implode(", ",$partnerSeoArr);
							}
							return $partnerSeo;
						}
						else{
						$val=JsCommon::getMultiLabels($this->FuncToLabelMap[$labelName],$this->$funcName(),$def_val);
						//Meter part is to be removed for height label
						if($this->FuncToLabelMap[$labelName]=="height"){
							$valArr=explode("(",$val);
							$val=$valArr[0];
							
						}
					}
					}
					else{
						if(method_exists("JPartner",$funcName))
							$val=$this->$funcName();
						else 
							throw new JsException("Please call a method of JPartner");
					}
					return $val;
				}
			}
			function setSeoLinks(){
				
				if($this->from_seo){
					$dbObj= new NEWJS_COMMUNITY_PAGES();
				
					$partnerCaste = $this->getPARTNER_CASTE();
					$partnerMtongue = $this->getPARTNER_MTONGUE();
					$partnerCity = $this->getPARTNER_CITYRES();
					$partnerCountry = $this->getPARTNER_COUNTRYRES();
					$partnerReligion = $this->getPARTNER_RELIGION();
					$partnerOccupation = $this->getPARTNER_OCC();
                                        
                                        if($partnerCity && substr($partnerCity, 0, 1) != "'")
                                            $partnerCity = $this->addQuotesToCity($partnerCity);
					
					$partnerLinkArr = $dbObj->getLink($partnerCaste, $partnerOccupation, $partnerReligion, $partnerMtongue, $partnerCity, $partnerCountry,$this->page_source);
					
					$this->seo_related_fields=array('OCC','CITYRES','MTONGUE','RELIGION','CASTE','STATE','COUNTRYRES');
			
					if($partnerLinkArr)
					{
						foreach($partnerLinkArr as $key=>$partnerLinkUrl)
						{
							$link=$partnerLinkUrl["URL"];
							$type=$partnerLinkUrl["TYPE"];
							$val = $partnerLinkUrl["VALUE"];
							if($link)
							  $link="$link";
							if(strtoupper($type) == 'MTONGUE' )
							  $partnerLink["MTONGUE"][$val] = $link;
							else if(strtoupper($type) == 'OCCUPATION' )
							  $partnerLink["OCC"][$val] = $link;
							else if(strtoupper($type) == 'CITY' )
							  $partnerLink["CITYRES"][$val] = $link;
							else if(strtoupper($type) == 'RELIGION' )
							  $partnerLink["RELIGION"][$val] = $link;
							else if(strtoupper($type) == 'CASTE' )
							  $partnerLink["CASTE"][$val] = $link;	
							else if(strtoupper($type) == 'STATE' )
								$partnerLink["STATE"][$val]=$link;
							else if(strtoupper($type) == 'COUNTRY' )
								$partnerLink["COUNTRYRES"][$val]=$link;
							unset($link);
							unset($type);

						}
					}
					$this->partnerLink=$partnerLink;
			}
		}
                private function addQuotesToCity($cityWithoutQuotes){
                    $cityArr = explode(',', $cityWithoutQuotes);
                    $cityWithQuotes = implode("','", $cityArr);
                    $cityWithQuotes = "'".$cityWithQuotes."'";
                    return $cityWithQuotes;
                }
}
