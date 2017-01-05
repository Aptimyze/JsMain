<?php
/*
 * This Class provide enums for PROFILE VERIFICATION Documents
 * @author Reshu Rajput
 * @created March 12, 2014
*/

class PROFILE_VERIFICATION_DOCUMENTS_ENUM
{
	//Enum for required Attribute and corresponding document type mapping
	public static $ATTRIBUTE_DOCUMENT = array(
						'DOB'=>array('PAN_CARD', 'DL', 'PASSPORT', 'CLASSX_PASSING_CERT', 'OTHER','VOTER_ID','AADHAR_CARD'),
						'ADDRESS'=>array('VOTER_ID', 'RATION_CARD', 'PROPERTY_TAX_RECEIPT', 'RENT_AGREEMENT','OTHER','AADHAR_CARD','TELEPHONE_BILL','ELECTRICITY_BILL'),
						'PARENT_ADDRESS'=>array('VOTER_ID', 'RATION_CARD', 'PROPERTY_TAX_RECEIPT', 'RENT_AGREEMENT','OTHER','AADHAR_CARD','TELEPHONE_BILL','ELECTRICITY_BILL'),
						'HIGHEST_QUALIFICATION'=>array('MARKSHEET', 'DEGREE_CERT', 'TRANSCRIPT', 'OTHER'),
						'INCOME'=>array('SALARY_SLIP', 'OFFER_LTR', 'JOINING_LTR', 'INCREMENT_LTR', 'FORM16', 'ITR', 'OTHER'),
						'DIVORCE'=>array('COURT_ORDER', 'OTHER')
						);

	// Enum for all required attributes
	public static $ATTRIBUTES = array(
					'DOB'=>"Date of Birth",
					'ADDRESS'=>"Self Address",
					'PARENT_ADDRESS'=>"Parent's Address",
					'HIGHEST_QUALIFICATION'=>"Highest Qualification",
					'INCOME'=>"Income",
					'DIVORCE'=>"Divorce",
                                        'FSO'=>"Visited"
					);
	//Enum for different document types 
	public static $DOCUMENTS = array(
					'PAN_CARD'=>"PAN Card",
				 	'DL'=>"Driving License",
					'PASSPORT'=>"Passport",
					'CLASSX_PASSING_CERT'=>"Class X Passing Certificate",
					'OTHER'=>"Any Other",
					'VOTER_ID'=>"Voter ID Card",
					'RATION_CARD'=>"Ration Card",
					'PROPERTY_TAX_RECEIPT'=>"Property Tax Receipt",
					'RENT_AGREEMENT'=>"Rent Agreement",
					'MARKSHEET'=>"Marksheet",
					'DEGREE_CERT'=>"Degree Certificate",
					'TRANSCRIPT'=>"Transcript",
					'SALARY_SLIP'=>"Salary Slip",
					'OFFER_LTR'=>"Offer Letter",
					'JOINING_LTR'=>"Joining Letter",
					'INCREMENT_LTR'=>"Increment Letter",
					'FORM16'=>"Form 16 of Last Financial Year",
					'ITR'=>"Income Tax Return of Last Financial Year",
					'COURT_ORDER'=>"Court Order",
                                        'AADHAR_CARD'=>"Aadhar Card",
                                        'ELECTRICITY_BILL'=>"Electricity Bill",
                                        'TELEPHONE_BILL'=>"Telephone Bill");

        // Enum of possible verified flag values
	public static $VERIFIED_FLAG_ENUM = array("ACCEPTED"=>"Y","DECLINED"=>"N","UNDER_SCREENING"=>"U");
	// Enum of possible deleted flag  values
	public static $DELETED_FLAG_ENUM = array("DELETED"=>"Y","NOT_DELETED"=>"N");
	//Attribute and corrsponding database field mapping Enum
	public static $ATTRIBUTE_FIELD_ENUM = array(
					'DOB'=>array("DTOFBIRTH"),
                                        'ADDRESS'=>array("CONTACT"),
                                        'HIGHEST_QUALIFICATION'=>array("EDU_LEVEL_NEW","PG_DEGREE","UG_DEGREE"),
					'INCOME'=>array("INCOME"),
                                        'DIVORCE'=>array("MSTATUS"),
					'PARENT_ADDRESS'=>array("PARENTS_CONTACT")
						);
        //verification Seal Array id ENUM
        public static $VERIFICATION_SEAL_ARRAY = array(
            "FSO" => "0",
            "DOB" => "1",
            "ADDRESS" => "2",
            "PARENT_ADDRESS" => "3",
            "HIGHEST_QUALIFICATION" => "4",
            "INCOME" => "5",
            "DIVORCE" => "6",
        );
        // Enum of FSO removal reasons
        public static $FSO_REMOVAL_REASON = array(
            "1" => "Marked by mistake",
            "2" => "Parameters verified earlier are edited",
            "0" => "Other",
        );
        /* This function is verify if particular document belong to given attribute
	* @param attribute : Attribute
	* @param doc : document type to be verified in the attribute document maping enum
	* @return true: If its valid else throw exception based on throwException flag
	*/
	static public function verifyAttributeDoc($attribute,$doc,$throwException=true)
        {
		if(!$attribute || !$doc) 
		{	if($throwException==false)
				return false;
			else{
				throw new jsException('',"Empty Attribute or doc are requested in PROFILE_VERIFICATION_DOCUMENTS_ENUM");
			}
		}
		else
		{
			if(in_array($doc,self::$ATTRIBUTE_DOCUMENT[$attribute]))
				return true;
			else
			{
				if($throwException==false)
					return false;
			}
			throw new jsException('',"Invalid Attribute: $attribute and doc: $doc are requested in PROFILE_VERIFICATION_DOCUMENTS_ENUM");
		}

        }

}
