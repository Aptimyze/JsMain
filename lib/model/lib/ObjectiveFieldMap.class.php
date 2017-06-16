<?php
class ObjectiveFieldMap
{
	public static function getFieldMapKey($profileField,$from_whr="")
	{
			$objectiveFieldMap=array(
				'COUNTRY_RES'=>'country',
				'CITY_RES'=>'city_india',
				'EDU_LEVEL'=>'education_label',
				'RELATION'=>'relation',
				'COUNTRY_BIRTH'=>'country',
				'RES_STATUS'=>'residency_status',
				'BTYPE'=>'bodytype',
				'INCOME'=>'income_level',
				'FAMILY_BACK'=>'family_background',
				'EDU_LEVEL_NEW'=>'education',
				'MARRIED_WORKING'=>'working_marriage',
				'MOTHER_OCC'=>'mother_occupation',
				'LIVE_WITH_PARENTS'=>'live_with_parents',
				'FAMILY_INCOME'=>'income_level',
				'GOING_ABROAD'=>'settling_abroad',
				'MTONGUE' =>'community',
				'SHOWMESSENGER'=>'privacy_option',
				'SHOWADDRESS'=>'privacy_option',
				'HAVECHILD'=>'children',
				'P_LRS'=>'lincome',
				'P_HRS'=>'hincome',
				'P_LDS'=>'lincome_dol',
				'P_HDS'=>'hincome_dol',
				'T_BROTHER'=>'sibling',
				'T_SISTER'=>'sibling',
				'M_BROTHER'=>'sibling',
				'M_SISTER'=>'sibling',
				'PARENT_CITY_SAME'=>'live_with_parents',
				'SHOWPHONE'=>'privacy_option',
				'SHOWMOBILE'=>'privacy_option',
				'HIV'=>'hiv_edit',
				'NATIVE_COUNTRY'=>'country',
				'NATIVE_CITY'=>'city_india',
				'NATIVE_STATE'=>'state_india',
				'ID_PROOF_TYPE'=>'id_proof_type',
				'ADDR_PROOF_TYPE'=>'addr_proof_type',
				
			);
		if($from_whr=="MR")
			$objectiveFieldMap["RELATIONSHIP"]="relationship_minireg";
		if(array_key_exists($profileField,$objectiveFieldMap))
			return $objectiveFieldMap[$profileField];
		else
			return strtolower($profileField);
	}
}
