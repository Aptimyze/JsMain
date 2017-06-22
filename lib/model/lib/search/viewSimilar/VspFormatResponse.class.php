<?php

/**
* @brief : This file will format the output for vsp response
* @author Ankit Shukla
* @created 2017-06-19
*/

class VspFormatResponse{
    
    const vspResponseArray = array("OFFSET" => "",
            "HEIGHT" => "",
            "DECORATED_HEIGHT" => "HEIGHT" ,
            "USERNAME" => "USERNAME",
            "PROFILEID" => "PROFILEID",
            "RELIGION" => "",
            "DECORATED_RELIGION" => "RELIGION",
            "CASTE" => "",
            "DECORATED_CASTE" => "CASTE",
            "MTONGUE" => "",
            "DECORATED_MTONGUE" => "MTONGUE",
            "EDU_LEVEL_NEW" => "",
            "DECORATED_EDU_LEVEL_NEW" => "edu_level_new",
            "INCOME" => "",
            "DECORATED_INCOME" => "INCOME",
            "OCCUPATION" => "",
            "DECORATED_OCCUPATION" => "OCCUPATION",
            "AGE" => "AGE",
            "LAST_LOGIN_DT" => "",
            "ENTRY_DT" => "ENTRY_DT",
            "SUBSCRIPTION" => "SUBSCRIPTION",
            "CITY_RES" => "",
            "DECORATED_CITY_RES" => "CITY",
            "COUNTRY_RES" => "",
            "DECORATED_COUNTRY_RES" => "",
            "MSTATUS" => "MSTATUS",
            "COLLEGE" => "COLLEGE",
            "PG_COLLEGE" => "PG_COLLEGE",
            "COMPANY_NAME" => "COMPANY_NAME",
            "NATIVE_CITY" => "NATIVE_CITY",
            "NATIVE_STATE" => "NATIVE_STATE",
            "ANCESTRAL_ORIGIN" => "ANCESTRAL_ORIGIN",
            "NAME_OF_USER" => "",
            "paid_joined_viewed_icon" => "",
            "CHECK_PHONE" => "",
            "HOROSCOPE" => "SHOW_HOROSCOPE",
            "GUNASCORE" => "GUNA",
            "RECOMMENDED" => "",
            "LINKEDIN" => "",
            "HIV" => "",
            "userLoginStatus" => "LAST_LOGIN_DT",
            "availforchat" => "",
            "STATIC_UNAME" => "",
            "PROFILECHECKSUM" => "PROFILECHECKSUM",
            "FEATURED" => "",
            "HAVEPHOTO" => "HAVEPHOTO",
            "PRIVACY" => "",
            "PHOTO_DISPLAY" => "PHOTO_DISPLAY",
            "GENDER" => "GENDER",
            "PHOTO_REQUEST" => "",
            "PHOTO_REQUESTED" => "IS_PHOTO_REQUESTED",
            "CHAT_REQUEST" => "",
            "CONTACT_REQUEST" => "",
            "CONTACT_SENT" => "",
            "CONTACT_REPLIED" => "",
            "CONTACT_STATUS" => "",
            "BOOKMARKED" => "IS_BOOKMARKED",
            "BOLDLISTING" => "",
            "VERIFY_ACTIVATED_DT" => "",
            "VERIFICATION_SEAL" => "VERIFICATIONSEAL",
            "VERIFICATION_STATUS" => "",
            "PHOTO" => "",
            "ALBUM_COUNT" => "PHOTO_COUNT"
    );
    
    
    public function __construct(){
        
        
    }
    
    public function formatSolrResponse(){
        
    }
    
    public function formatTupleResponse($pValue){
        $pValue = (array)$pValue;
        foreach(self::vspResponseArray as $key=>$val){
            if($val!=""){    
                if($key == "BOOKMARKED"){
                    if($pValue[$val] == 1)
                       $pValue[$val] = 'Y';
                    else
                       $pValue[$val] = 'N';
                }
                $returnArray[$key] = $pValue[$val];
            }
        }
        return $returnArray;
    }
}