<?php
//This class is used to save dpp for the ap profiles in the AP tables
class SaveDppForAP
{
	public function __construct()
	{
	}

	/*
        This is the AP function which is being used to save changed dpp details in AP tables for users having response booster.
	@param - search parameters object, profileid
	@return 1
        */
	public function SaveDppFromSearch($SearchParamtersObj,$profileid)
	{
		$apObj = new AP_DPP_FILTER_ARCHIVE;
                $liveDPP = $apObj->fetchCurrentDPP($profileid);
                unset($apObj);
                if($liveDPP["DPP_ID"])
                        $parameters["ACTED_ON_ID"]=$liveDPP["DPP_ID"];
                if($SearchParamtersObj->getGENDER())
                        $parameters["GENDER"]=$SearchParamtersObj->getGENDER();
                if($SearchParamtersObj->getHANDICAPPED())
                        $parameters["HANDICAPPED"]="'".str_replace(",","','",$SearchParamtersObj->getHANDICAPPED())."'";
                if($SearchParamtersObj->getBTYPE())
                        $parameters["PARTNER_BTYPE"]="'".str_replace(",","','",$SearchParamtersObj->getBTYPE())."'";
                if($SearchParamtersObj->getCASTE())
                        $parameters["PARTNER_CASTE"]="'".str_replace(",","','",$SearchParamtersObj->getCASTE())."'";
                if($SearchParamtersObj->getDIET())
                        $parameters["PARTNER_DIET"]="'".str_replace(",","','",$SearchParamtersObj->getDIET())."'";
                if($SearchParamtersObj->getDRINK())
                        $parameters["PARTNER_DRINK"]="'".str_replace(",","','",$SearchParamtersObj->getDRINK())."'";
                if($SearchParamtersObj->getEDU_LEVEL_NEW())
                        $parameters["PARTNER_ELEVEL_NEW"]="'".str_replace(",","','",$SearchParamtersObj->getEDU_LEVEL_NEW())."'";
                if($SearchParamtersObj->getINCOME())
                        $parameters["PARTNER_INCOME"]="'".str_replace(",","','",$SearchParamtersObj->getINCOME())."'";
                if($SearchParamtersObj->getMANGLIK())
                        $parameters["PARTNER_MANGLIK"]="'".str_replace(",","','",$SearchParamtersObj->getMANGLIK())."'";
                if($SearchParamtersObj->getMTONGUE())
                        $parameters["PARTNER_MTONGUE"]="'".str_replace(",","','",$SearchParamtersObj->getMTONGUE())."'";
		if($SearchParamtersObj->getOCCUPATION())
                        $parameters["PARTNER_OCC"]="'".str_replace(",","','",$SearchParamtersObj->getOCCUPATION())."'";
                if($SearchParamtersObj->getRELATION())
                        $parameters["PARTNER_RELATION"]="'".str_replace(",","','",$SearchParamtersObj->getRELATION())."'";
                if($SearchParamtersObj->getSMOKE())
                        $parameters["PARTNER_SMOKE"]="'".str_replace(",","','",$SearchParamtersObj->getSMOKE())."'";
                if($SearchParamtersObj->getCOMPLEXION())
                        $parameters["PARTNER_COMP"]="'".str_replace(",","','",$SearchParamtersObj->getCOMPLEXION())."'";
                if($SearchParamtersObj->getRELIGION())
                        $parameters["PARTNER_RELIGION"]="'".str_replace(",","','",$SearchParamtersObj->getRELIGION())."'";
                if($SearchParamtersObj->getNATURE_HANDICAP())
                        $parameters["NHANDICAPPED"]="'".str_replace(",","','",$SearchParamtersObj->getNATURE_HANDICAP())."'";
                $parameters["PROFILEID"]=$profileid;
                $parameters["CREATED_BY"]="ONLINE";
		if($SearchParamtersObj->getHAVECHILD())
                        $parameters["CHILDREN"]=$SearchParamtersObj->getHAVECHILD();
                if($SearchParamtersObj->getLAGE())
                        $parameters["LAGE"]=$SearchParamtersObj->getLAGE();
                if($SearchParamtersObj->getHAGE())
                        $parameters["HAGE"]=$SearchParamtersObj->getHAGE();
                if($SearchParamtersObj->getLHEIGHT())
                        $parameters["LHEIGHT"]=$SearchParamtersObj->getLHEIGHT();
                if($SearchParamtersObj->getHHEIGHT())
                        $parameters["HHEIGHT"]=$SearchParamtersObj->getHHEIGHT();
                if($SearchParamtersObj->getCITY_RES())
                        $parameters["PARTNER_CITYRES"]="'".str_replace(",","','",$SearchParamtersObj->getCITY_RES())."'";
                if($SearchParamtersObj->getCOUNTRY_RES())
                        $parameters["PARTNER_COUNTRYRES"]="'".str_replace(",","','",$SearchParamtersObj->getCOUNTRY_RES())."'";
                if($SearchParamtersObj->getMSTATUS())
                        $parameters["PARTNER_MSTATUS"]="'".str_replace(",","','",$SearchParamtersObj->getMSTATUS())."'";

		//MAPPING INCOME VALUES
                if(($SearchParamtersObj->getLINCOME_DOL() || $SearchParamtersObj->getLINCOME_DOL() == '0') && ($SearchParamtersObj->getHINCOME_DOL() || $SearchParamtersObj->getHINCOME_DOL()=='0'))            //DOLLAR VALUE PRESENT
                {
                        if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))            //RUPEE VALUE PRESENT
                        {
                                //BOTH RUPEE AND DOLLAR VALUE PRESENT SO NOTHINGS TO BE DONE
				$parameters["LINCOME"]=$SearchParamtersObj->getLINCOME();
				$parameters["HINCOME"]=$SearchParamtersObj->getHINCOME();
				$parameters["LINCOME_DOL"]=$SearchParamtersObj->getLINCOME_DOL();
				$parameters["HINCOME_DOL"]=$SearchParamtersObj->getHINCOME_DOL();
                        }
                        else    //RUPEE VALUE NOT PRESENT
                        {
                                $dArr["minID"] = $SearchParamtersObj->getLINCOME_DOL();
                                $dArr["maxID"] = $SearchParamtersObj->getHINCOME_DOL();
                                $incomeType = "D";
                                $incomeMappingObj = new IncomeMapping("",$dArr);
                                $incomeMappingObj->getMappedValues();
                                $parameters["LINCOME"] = $incomeMappingObj->getIncomeArr("minIR");
                                $parameters["HINCOME"] = $incomeMappingObj->getIncomeArr("maxIR");
				$parameters["LINCOME_DOL"]=$SearchParamtersObj->getLINCOME_DOL();
				$parameters["HINCOME_DOL"]=$SearchParamtersObj->getHINCOME_DOL();
                                unset($incomeMappingObj);
                        }
                }
                else    //DOLLAR VALUE NOT PRESENT
                {
                        if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))            //RUPEE VALUE PRESENT
                        {
                                $rArr["minIR"] = $SearchParamtersObj->getLINCOME();
                                $rArr["maxIR"] = $SearchParamtersObj->getHINCOME();
                                $incomeType = "R";
                                $incomeMappingObj = new IncomeMapping($rArr,"");
                                $incomeMappingObj->getMappedValues();
				$parameters["LINCOME"]=$SearchParamtersObj->getLINCOME();
				$parameters["HINCOME"]=$SearchParamtersObj->getHINCOME();
                                $parameters["LINCOME_DOL"] = $incomeMappingObj->getIncomeArr("minID");
                                $parameters["HINCOME_DOL"] = $incomeMappingObj->getIncomeArr("maxID");
                                unset($incomeMappingObj);
                        }
                }
		//MAPPING ENDS

                $parameters["DATE"] = date("Y-m-d H:i:s");
		foreach($parameters as $k=>$v)
		{
			if(in_array($v,SearchConfig::$dont_all_labels) || in_array("'".$v."'",SearchConfig::$dont_all_labels))
				$parameters[$k]='';
		}

		$apObj = new ASSISTED_PRODUCT_AP_TEMP_DPP;
                $apObj->replaceData($parameters);
                unset($apObj);
		return 1;
	}
}
?>
