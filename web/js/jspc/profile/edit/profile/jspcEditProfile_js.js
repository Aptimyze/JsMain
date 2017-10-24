/**
 * jspcEditProfile
 * 
 * @package jeevansathi
 * @subpackage Edit Profile
 * @author Kunal Verma
 * @created 05th Nov 2015
 */
var retryAttempt = 0;
var EditApp = {};
var callBlur = 0;
var DataUpdated = 0;
EditApp = function(){

  try{
    var config = {
    '.chosen-select'           : {},
    '.chosen-select-deselect'  : {allow_single_deselect:true},
    '.chosen-select-no-single' : {disable_search_threshold:10},
    '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
    '.chosen-select-width'     : {width:"100%"},
    '.chosen-select-no-search' : {disable_search:true,width:"100%"},
    '.chosen-select-width-right':{width:"100%"}
  }
    var debugInfo         = false;
    var retryStoreData    = 0;
    var bAjaxInProgress    = false;
    var bErrorInEditAjax  = false; 
    var MAX_RETRY         = 3;  
    var currentIncomeInRs = false;
    var dispNone          = 'disp-none';
    var INT_PHONE_MAX_LEN = 15;
    var Url               = '/api/v1/profile/editprofile?sectionFlag=all'
    var editAppObject     = {needToUpdate:true}; 
    var responseKeyArray  = ["Critical","Details","Education","Career","Family","Lifestyle","Contact","Kundli"];
    var viewResponseKeyArray =["Critical","about","dpp","family","lifestyle","contact"];
    var viewResponseSubSection =["hobbies","sibling"];
    var fieldObject       = function(){return {"key":"","type":"","isEditable":"true","label":"","decValue":"","isUnderScreen":"false","value":""}};
    var editedFields      = {};
    var hideEditFor = [];
    var staticTables      = new SessionStorage;
    var chosenUpdateEvent = "chosen:updated";
    var dataMonthArray = {1: "Jan", 2: "Feb", 3: "Mar", 4: "April", 5: "May", 6: "Jun", 7: "Jul", 8: "Aug", 9: "Sep", 10: "Oct", 11: "Nov", 12: "Dec"};
    var dateTypeArr =[];
      dateTypeArr[0] = {"D":"Day"};
      dateTypeArr[1] = {"M":"Month"};
      dateTypeArr[2] = {"Y":"Year"};
      var dateDataArray = [dateTypeArr];
      var removeDepField = {"m_status":"havechild"};
      var removeDepFieldOn = {"m_status":"Never Married"};
    var inputData = {};
    //Error Map
    var errorMap          = {
                              "RELATION":"Please choose who posted this profile",
                              "CITY_RES":"Please mention the City you are living in",
                              "YOURINFO":"For the benefit of your matches, please write about yourself in at least 100 letters",                       
			      "NAME":"Please provide a valid Full Name",
                              "EMAIL":"Email Required",
                              "EMAIL_WRONG_FORMAT":"Invalid Format",
                              "EMAIL_INVALID_DOMAIN":"Invalid domain",
                              "ISD_INVALID":"Invalid ISD",
                              "STD_INVALID":"Invalid Std",
                              "STD_REQUIRED":"Std Code Required",
                              "ISD_REQUIRED":"ISD Required",
                              "LANDLINE_INVALID":"Invalid",
                              "MOBILE_INVALID":"Invalid",
                              "PINCODE_ERROR":"Invalid Pincode",
                              "ADDR_PROOF_VAL":"Please attach document",
                              "ID_PROOF_VAL":"Please attach document",
                              "ADDR_PROOF_TYPE":"Required",
                              "ID_PROOF_TYPE":"Required",
                              "SAME_EMAIL":"Same Email",
                              "MSTATUS_PROOF":"Please attach Divorced Decree",
                            };
    //Section List
    var BASIC             = "basic";
    var CRITICAL          = "critical";
    var LIKES             = "likes";
    var LIFE_STYLE        = "lifestyle";
    var FAMILY            = "family";
    var EDU_CAREER        = "career";
    var HOROSCOPE         = "horoscope";
    var ABOUT             = "about";
    var CONTACT           = "contact";
    var VERIFICATION      = "verification";
    
    var criticalSectionArray   = ["MSTATUS","MSTATUS_PROOF","DTOFBIRTH"];
    var basicSectionArray   = ["NAME","AADHAAR","GENDER","HAVECHILD","HEIGHT","RELIGION","MTONGUE","CASTE","JAMAAT","SECT","COUNTRY_RES","STATE_RES","CITY_RES","INCOME","RELATION","DISPLAYNAME"];
    var likesSectionArray   = ["HOBBIES_HOBBY","HOBBIES_INTEREST","HOBBIES_MUSIC","HOBBIES_BOOK","FAV_BOOK","HOBBIES_DRESS","FAV_TVSHOW","HOBBIES_MOVIE","FAV_MOVIE","HOBBIES_SPORTS","HOBBIES_CUISINE","FAV_FOOD","FAV_VAC_DEST"];
    var lifeStyleSectionArray = ["DIET","DRINK","SMOKE","OPEN_TO_PET","OWN_HOUSE","HAVE_CAR","RES_STATUS","HOBBIES_LANGUAGE","MATHTHAB","NAMAZ","ZAKAT","FASTING","UMRAH_HAJJ","QURAN","SUNNAH_BEARD","SUNNAH_CAP","HIJAB","HIJAB_MARRIAGE","WORKING_MARRIAGE","DIOCESE","BAPTISED","READ_BIBLE","OFFER_TITHE","SPREADING_GOSPEL","AMRITDHARI","CUT_HAIR","TRIM_BEARD","WEAR_TURBAN","CLEAN_SHAVEN","ZARATHUSHTRI","PARENTS_ZARATHUSHTRI","BTYPE","COMPLEXION","WEIGHT","BLOOD_GROUP","HIV","THALASSEMIA","HANDICAPPED","NATURE_HANDICAP"];
    var familySectionArray = ["PROFILE_HANDLER_NAME","MOTHER_OCC","FAMILY_BACK","T_SISTER","T_BROTHER","SUBCASTE","GOTHRA","GOTHRA_MATERNAL","FAMILY_STATUS","FAMILY_INCOME","FAMILY_TYPE","FAMILY_VALUES","NATIVE_COUNTRY","NATIVE_STATE","NATIVE_CITY","ANCESTRAL_ORIGIN","PARENT_CITY_SAME"];
    var eduCareerSectionArray = ["EDU_LEVEL_NEW","SCHOOL","DEGREE_UG","COLLEGE","DEGREE_PG","PG_COLLEGE","OTHER_UG_DEGREE","OTHER_PG_DEGREE","WORK_STATUS","OCCUPATION","COMPANY_NAME","INCOME","MARRIED_WORKING","GOING_ABROAD"];
    var horoscopeSectionArray = ["HOROSCOPE_MATCH","SUNSIGN","RASHI","NAKSHATRA","MANGLIK","ASTRO_PRIVACY"];
    var aboutSectionArray = ["YOURINFO","FAMILYINFO","EDUCATION","JOB_INFO"];
    var contactSectionArray = ["EMAIL","ALT_EMAIL","PHONE_MOB","MOBILE_OWNER_NAME","MOBILE_NUMBER_OWNER","ALT_MOBILE","ALT_MOBILE_OWNER_NAME","ALT_MOBILE_NUMBER_OWNER","PHONE_RES","PHONE_OWNER_NAME","PHONE_NUMBER_OWNER","TIME_TO_CALL_START","SHOWPHONE_MOB","SHOWPHONE_RES","SHOWALT_MOBILE","CONTACT","SHOWADDRESS","PINCODE","PARENTS_CONTACT","SHOW_PARENTS_CONTACT","PARENT_PINCODE"];
    var verificationSectionArray = ["ID_PROOF_TYPE","ID_PROOF_VAL", "ADDR_PROOF_TYPE", "ADDR_PROOF_VAL"];
   
    var listStaticTables    = {
                                "critical" : 'mstatus',
                                "basic" : 'height_jspc,city_res_jspc,country_res_jspc,jspc_state,mtongue,caste_jspc,sect_jspc,,relationship,income,degree_pg,degree_ug,jamaat',
                                "likes" : 'hobbies_hobby,hobbies_interest,hobbies_music,hobbies_book,hobbies_dress,hobbies_sports,hobbies_cuisine,hobbies_movie',
                                "lifestyle":"diet,drink,smoke,open_to_pet,own_house,have_car,rstatus,hobbies_language,maththab_jspc,namaz,zakat,fasting,umrah_hajj,quran,sunnah_beard,sunnah_cap,hijab,working_marriage,baptised,read_bible,offer_tithe,spreading_gospel,amritdhari,cut_hair,trim_beard,wear_turban,clean_shaven,zarathushtri,parents_zarathushtri,btype,complexion,weight,blood_group,hiv_edit,thalassemia,handicapped,nature_handicap",
                                "family":"mother_occ,family_back,t_sister,t_brother,family_status,family_income,family_type,family_values,parent_city_same,state_india,native_country,native_city",
                                "career":"edu_level_new,degree_ug,degree_pg,work_status,occupation,income,working_marriage,going_abroad",
                                "horoscope":"horoscope_match,rashi,nakshatra,manglik,astro_dob,astro_btime,astro_country_birth,astro_place_birth,sunsign,astro_privacy",
                                "contact":"isd,mobile_number_owner,alt_mobile_number_owner,phone_number_owner,stdcodes",
                                "verification": "id_proof_type,addr_proof_type" //,id_proof_val,address_proof_val
                              }; 
    
    
    var storeTogetherFields         ={"CITY_RES":"COUNTRY_RES","CASTE":"RELIGION","SECT":"RELIGION","NATURE_HANDICAP":"HANDICAPPED","NATIVE_CITY":"NATIVE_STATE","MSTATUS_PROOF":"MSTATUS"} 
    var depDataFields         = {"CITY_RES":"STATE_RES","INCOME":"COUNTRY_RES","CASTE":"RELIGION","SECT":"RELIGION","NATURE_HANDICAP":"HANDICAPPED","NATIVE_CITY":"NATIVE_STATE","CUT_HAIR":"AMRITDHARI","TRIM_BEARD":"AMRITDHARI","WEAR_TURBAN":"AMRITDHARI","CLEAN_SHAVEN":"AMRITDHARI","MATHTHAB":"CASTE","FAMILY_INCOME":"COUNTRY_RES","MOBILE_NUMBER_OWNER":"GENDER","ALT_MOBILE_NUMBER_OWNER":"GENDER","PHONE_NUMBER_OWNER":"GENDER","MSTATUS_PROOF":"MSTATUS"};
    var depFieldSectionID     = {"FAMILY_INCOME":BASIC,"MATHTHAB":BASIC,"INCOME":BASIC}
    
    var fieldMapList        = {"HEIGHT":"height_jspc","COUNTRY_RES":"country_res_jspc","STATE_RES":"jspc_state","CITY_RES":"city_res_jspc","RELATION":"relationship","CASTE":"caste_jspc","SECT":"sect_jspc","RES_STATUS":"rstatus","HIV":"hiv_edit","NATIVE_STATE":"state_india","NATIVE_COUNTRY":"native_country","MATHTHAB":"maththab_jspc","MARRIED_WORKING":"working_marriage", "ID_PROOF_TYPE":"id_proof_type","HAVECHILD":"children","ADDR_PROOF_TYPE":"addr_proof_type","MSTATUS":"mstatus_edit,mstatus_muslim_edit","MSTATUS_EDIT":"mstatus_edit","MSTATUS_MUSLIM_EDIT":"mstatus_muslim_edit","MSTATUS_PROOF":"mstatus_proof","JAMAAT":"jamaat"};
    
    var maxLengthMap              = {"NAME":"40","FAV_BOOK":"300","FAV_FOOD":"300","FAV_MOVIE":"300","FAV_VAC_DEST":"300","FAV_TVSHOW":"300","ANCESTRAL_ORIGIN":"100","YOURINFO":"5000","FAMILYINFO":"1000","EDUCATION":"1000","JOB_INFO":"1000","OTHER_UG_DEGREE":"250","OTHER_PG_DEGREE":"250","COLLEGE":"150","PG_COLLEGE":"150","SCHOOL":"150","PHONE_OWNER_NAME":"40","MOBILE_OWNER_NAME":"40","ALT_MOBILE_OWNER_NAME":"40",'EMAIL':'100','ALT_EMAIL':'100',"SUBCASTE":"250","GOTHRA":"250","GOTHRA_MATERNAL":"250","PROFILE_HANDLER_NAME":"40","DIOCESE":"100","PINCODE":"6","PINCODE":"6","PARENT_PINCODE":"6","WEIGHT":"3","ID_PROOF_NO":30};
    
    //Type of Fields By Default All are 'S' Type means single select
    
    var SINGLE_SELECT_TYPE        = "S";
    var singleSelectWithSearch    = ["CASTE","COUNTRY_RES","STATE_RES","CITY_RES","EDU_LEVEL_NEW","OCCUPATION","NATIVE_STATE","NATIVE_COUNTRY","NATIVE_CITY","DEGREE_UG","DEGREE_PG","ID_PROOF_TYPE","ADDR_PROOF_TYPE","JAMAAT"];
    var NON_EDITABLE_TYPE         = "N";
    
    var OPEN_TEXT_TYPE            = "O";
    var openTextTypeFields        = ["NAME","FAV_BOOK","FAV_FOOD","FAV_MOVIE","FAV_VAC_DEST","FAV_TVSHOW","WEIGHT","PROFILE_HANDLER_NAME","SUBCASTE","GOTHRA","GOTHRA_MATERNAL","DIOCESE","ANCESTRAL_ORIGIN","SCHOOL","COLLEGE","PG_COLLEGE","OTHER_UG_DEGREE","OTHER_PG_DEGREE","COMPANY_NAME","EMAIL","ALT_EMAIL","PHONE_OWNER_NAME","MOBILE_OWNER_NAME","ALT_MOBILE_OWNER_NAME","PINCODE","PARENT_PINCODE","AADHAAR"];
    var UNCOOKED_TYPE		  = "U";
    var unCookedFields = ['DISPLAYNAME'];
    var autoSuggestFields         = ["SUBCASTE","GOTHRA","GOTHRA_MATERNAL","SCHOOL","COLLEGE","PG_COLLEGE","COMPANY_NAME"]; 
    
    var DATE_TYPE                  = "D";
    var dateTypeFields             = ["DTOFBIRTH"];
    var BOX_TYPE                  = "B";
    var boxTypeFields             = ["RELATION","DIET","DRINK","SMOKE","OPEN_TO_PET","OWN_HOUSE","HAVE_CAR","BLOOD_GROUP","HIV","THALASSEMIA","BTYPE","PARENT_CITY_SAME","BAPTISED","READ_BIBLE","OFFER_TITHE","SPREADING_GOSPEL","T_SISTER","T_BROTHER","FAMILY_TYPE","FAMILY_STATUS","FAMILY_VALUES","ZAKAT","FASTING","UMRAH_HAJJ","QURAN","SUNNAH_BEARD","HIJAB","WORKING_MARRIAGE","AMRITDHARI","CUT_HAIR","TRIM_BEARD","WEAR_TURBAN","CLEAN_SHAVEN","ZARATHUSHTRI","PARENTS_ZARATHUSHTRI","MARRIED_WORKING","GOING_ABROAD","HIJAB_MARRIAGE","HAVECHILD"];
    var boxTypeMaxAllowed         = {"RELATION":3};
    
    var MULTIPLE_SELECT_TYPE      = "M";
    var multipleSelectTypeFields  = ["HOBBIES_BOOK","HOBBIES_DRESS","HOBBIES_CUISINE","HOBBIES_HOBBY","HOBBIES_INTEREST","HOBBIES_MUSIC","HOBBIES_SPORTS","HOBBIES_MOVIE","HOBBIES_LANGUAGE"];
    
    var RANGE_TYPE                = "R";
    var rangeTypeFields           = ["TIME_TO_CALL_START"]; 
    
    var TEXT_AREA_TYPE            = "TA";
    var textAreaTypeFields        = ["YOURINFO","FAMILYINFO","EDUCATION","JOB_INFO","CONTACT","PARENTS_CONTACT"];
    
    var PHONE_TYPE                = "P";
    var phoneTypeFields           = ["PHONE_MOB","ALT_MOBILE","PHONE_RES"];
    
    var FILE_TYPE            = "FT";
    var fileTypeFields        = ["ID_PROOF_VAL","ADDR_PROOF_VAL","MSTATUS_PROOF"];
    
    var PRIVACY_TYPE              = "PR";
    var privacyTypeFields          = ["SHOWPHONE_MOB","SHOWPHONE_RES","SHOWALT_MOBILE","SHOWADDRESS","SHOW_PARENTS_CONTACT"]
    
    var rightAlignedFields        = ["SUNSIGN","RASHI","NAKSHATRA","MANGLIK","HOROSCOPE_MATCH","ASTRO_PRIVACY","EMAIL","ALT_EMAIL","CONTACT","PARENTS_CONTACT","ID_PROOF_NO","ID_PROOF_TYPE","ADDR_PROOF_TYPE","ID_PROOF_VAL","ADDR_PROOF_VAL"];
    var rightAlignWithoutPadding  = ["PHONE_OWNER_NAME","PHONE_NUMBER_OWNER","MOBILE_OWNER_NAME","MOBILE_NUMBER_OWNER","ALT_MOBILE_OWNER_NAME","ALT_MOBILE_NUMBER_OWNER","PINCODE","PARENT_PINCODE","ID_PROOF_TYPE","ADDR_PROOF_TYPE","ID_PROOF_VAL","ADDR_PROOF_VAL"];
    
    var rightAlignedSections      = [HOROSCOPE,CONTACT,VERIFICATION];
    
    //isSectionBaked /*Array to store id of baked section*/
    var isSectionBaked            = [];
    
    var isInitialized             = false;
    var notFilledText             = "Not filled in";
    //////////////////////////// Behaviour Map
    var behaviourMap              = {"NAME":"js-name","COUNTRY_RES":"js-country","HANDICAPPED":"js-handicapped","NATIVE_STATE":"js-nativeState","WEIGHT":"js-onlyNumber","DIOCESE":"js-onlyChar","AMRITDHARI":"js-amritdhari","NATIVE_CITY":"js-nativeCity","PROFILE_HANDLER_NAME":"js-onlyChar","EDU_LEVEL_NEW":'js-educationChange',"ANCESTRAL_ORIGIN":'js-forAbout',"FAMILYINFO":"js-forAbout","EDUCATION":"js-forAbout","JOB_INFO":"js-forAbout","YOURINFO":"js-aboutMe","OTHER_UG_DEGREE":"js-forAbout","OTHER_PG_DEGREE":"js-forAbout","FAV_BOOK":"js-forAbout","FAV_FOOD":"js-forAbout","FAV_MOVIE":"js-forAbout","FAV_VAC_DEST":"js-forAbout","FAV_TVSHOW":"js-forAbout","PHONE_OWNER_NAME":"js-onlyChar","MOBILE_OWNER_NAME":"js-onlyChar","ALT_MOBILE_OWNER_NAME":"js-onlyChar","EMAIL":"js-email","ALT_EMAIL":"js-email","PINCODE":"js-pincode","PARENT_PINCODE":"js-pincode","ID_PROOF_TYPE":"js-proofType","ID_PROOF_NO":"js-proofTypeNo","ADDR_PROOF_TYPE":"js-addrProofType","ID_PROOF_VAL":"js-proofVal","ADDR_PROOF_VAL":"js-addrProofVal","STATE_RES":"js-state","CITY_RES":"js-city","MSTATUS":"js-mstatus","MSTATUS_PROOF":"js-mstatus_proof","CASTE":"js-caste","AADHAAR":"js-aadhaar"};
    
    var sidesUIMap                = ["NATIVE_STATE","NATIVE_COUNTRY","T_BROTHER","T_SISTER","YOURINFO","PHONE_OWNER_NAME","MOBILE_OWNER_NAME","ALT_MOBILE_OWNER_NAME","MOBILE_NUMBER_OWNER","PHONE_NUMBER_OWNER","ALT_MOBILE_NUMBER_OWNER","SHOWPHONE_MOB","SHOWPHONE_RES","SHOWALT_MOBILE","PINCODE","PARENT_PINCODE","SHOWADDRESS","SHOW_PARENTS_CONTACT","TIME_TO_CALL_START"];
    
    var requiredArray             = {};
    var previousSectionValue      = {};
    var updateViewColor12Map      = ["fav_book","fav_movie","fav_food","phone_res_status","phone_mob_status","alt_mob_status","alt_email_status","email_status"]
    var multiFieldViewMap         = ["appearance","habbits","assets","religious_beliefs","special_cases","open_to_pets","living","plan_to_work","abroad","horo_match"];
    
    var phoneStatusMap            = ["phone_res_status","phone_mob_status","alt_mob_status","alt_email_status","email_status"];
    var phoneDescriptionMap       = ["landline_desc","alt_mobile_desc","mobile_desc"];
    
    var autoSuggestRequest        = {}; 
    var hintMap                   = {"YOURINFO":"Introduce yourself. Write about your values, beliefs/goals, aspirations/interests and hobbies.","FAMILYINFO":"Write about your parents and brothers or sisters. Where do they live? What are they doing?","EDUCATION":"Which institutions have you attended? What courses/specializations have you studied?","JOB_INFO":"Where are you working currently? You may mention your current job and future career aspirations."};
    
	// array to show top label for a section
    var sectionTopLabelMap        = {"verification":"Upload at least one document"};
    var sectionTopLabelRequired   = ["verification"];
    var duplicateFieldMap         = ['income'];
    
    var duplicateEditFieldMap     = {};
    var ugDegreeMap               = [];
    var pgDegreeMap               = [];
    var stdCodesMap               = [];
    var isdIndianAllowedCodes = ["0", "91","+91"];
    var fileTypePostArray   = ["verification","critical"];
    /*
     * Get Edit Data
     */
    getEditData = function(){
      return $.myObj.ajax({
				url : Url,
				data : ({dataType:"json"}),
				async:true,
				timeout:30000,
        cache:false,
        success:storeData,
        error:function(){
          bErrorInEditAjax = true;
        },
        beforeSend:function(){
          bAjaxInProgress = true;
        },
        complete:function(){
          bAjaxInProgress = false;
        }
			});
    }
    updateNeedToUpdate = function(){
            editAppObject.needToUpdate = true;
    }
    /*
     * getAutoSuggest
     * @param {type} queryData like {q: stringToSend, type: "subcaste", caste: $("#caste_value").val()},
     * @returns {jqXHR}
     */
    getAutoSuggest = function(queryData){
      return $.ajax({
        type: 'GET',
        url: "/profile/autoSug",
        data: queryData,
        cache:true
      });
    }
    
    /*
     * checkEmailStatus
     * @param {type} emailVal
     * @returns {jqXHR}
     */
    checkEmailStatus = function(emailVal){
      var Url = '/profile/edit_profile.php';
      return $.ajax({
				url : Url,
        method:'POST',
				data : ({verify_email:1,email_id:emailVal}),
				timeout:30000,
			});
    }

    /*
     * Store Data in editAppObject
     */
    storeData = function(data,textStatus,jqXHR){
       DataUpdated = 1;
      var userReligion;
      bErrorInEditAjax = false;
      retryStoreData = 0;
      if(editAppObject.needToUpdate === false){
        return ;
      }
      
      if(typeof data == "string")
        data = JSON.parse(data);
      
      hideEditFor = data.cannot_edit_section;
      var profileCompData = data.profileCompletion;
      completeProfileCompletionBlock(profileCompData);
      updateProfileCompletionScore(profileCompData.PCS);
      $("#pcsValue").html(profileCompData.PCS+"%");
      if(data.responseStatusCode != "0"){
        return ;
      }
      
      for(var i=0;i<responseKeyArray.length;i++){
        if(!(responseKeyArray[i] == "Kundli" && (!(userReligion == 1 || userReligion == 4 || userReligion == 7 || userReligion == 9))) )
        {
            var sectionArr = data[responseKeyArray[i]];
        
            for(var j=0;j<sectionArr.length;j++){
              var inAnySection = false;
              var sectionNameArr = [];
              
              if(sectionArr[j].key == "RELIGION")
                  userReligion = sectionArr[j].value;
          
              if(criticalSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(CRITICAL);
                inAnySection = true;
              }
              if(basicSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(BASIC);
                inAnySection = true;
              }

              if(likesSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(LIKES);
                inAnySection = true;
              }

              if(familySectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(FAMILY);
                inAnySection = true;
              }

              if(lifeStyleSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(LIFE_STYLE);
                inAnySection = true;
              }

              if(eduCareerSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(EDU_CAREER);
                inAnySection = true;
              }

              if(horoscopeSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(HOROSCOPE);
                inAnySection = true;
              }
              
              if(aboutSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(ABOUT);
                inAnySection = true;
              }
              
              if(contactSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(CONTACT);
                inAnySection = true;
              }
              
              if(verificationSectionArray.indexOf(sectionArr[j].key) !== -1){
                sectionNameArr.push(VERIFICATION);
                inAnySection = true;
              }
              
              if(inAnySection == false){
                continue;
              }

              var field = new fieldObject;

              field.key             = sectionArr[j].key;
              field.label           = sectionArr[j].label;
              field.value           = sectionArr[j].value === null ? "" : sectionArr[j].value;
              field.decValue        = typeof sectionArr[j].label_val == "string" ? sectionArr[j].label_val : "";
              field.isEditable      = sectionArr[j].edit      === "N" ? false : true;
              field.isUnderScreen   = sectionArr[j].screenBit === "1" ? true  : false;
              field.type            = SINGLE_SELECT_TYPE;/*Single Select Type*/
              
              if(sectionArr[j].key == "AADHAAR"){
                        field.decValue        = typeof sectionArr[j].label_val == "string" ? sectionArr[j].label_val : ""; 
              }
              if((field.value === null || field.value.length === 0) && field.isUnderScreen ){
                field.isUnderScreen = false;
              }
	      if(unCookedFields.indexOf(field.key)!== -1)
	      {
			field.type = UNCOOKED_TYPE;
              }
              if(openTextTypeFields.indexOf(field.key) !== -1){
                field.type          = OPEN_TEXT_TYPE;/*Open Text Type*/
              }
              if(fileTypeFields.indexOf(field.key) !== -1){
                field.type          = FILE_TYPE;/*File Type*/
              }

              if(boxTypeFields.indexOf(field.key) !== -1){
                field.type          = BOX_TYPE;/*Box Type*/
              }
              
              if(dateTypeFields.indexOf(field.key) !== -1){
                field.type          = DATE_TYPE;/*Box Type*/
              }

              if(multipleSelectTypeFields.indexOf(field.key) !== -1){
                field.type          = MULTIPLE_SELECT_TYPE;/*Multiple Select Type*/
              }

              if(rangeTypeFields.indexOf(field.key) !== -1){
                field.type          = RANGE_TYPE;/*Range Type*/
              }
              
              if(textAreaTypeFields.indexOf(field.key) !== -1){
                field.type          = TEXT_AREA_TYPE;/*Text Area Type*/
              }
              
              if(phoneTypeFields.indexOf(field.key) !== -1){
                field.type          = PHONE_TYPE;/*Phone Type*/
              }
              
              if(privacyTypeFields.indexOf(field.key) !== -1){
                field.type          = PRIVACY_TYPE;/*Privacy Type*/
              }
              
              if(field.isEditable === false){
                field.type          = NON_EDITABLE_TYPE;/*Non Editable Type*/
              }
              
              if(field.value.length){
                var sanitizedVal = $('<textarea />').html(field.value).text();
                field.value = sanitizedVal;
              }
              
              if(field.decValue.length){
                var sanitizedDecVal = $('<textarea />').html(field.decValue).text();
                field.decValue = sanitizedDecVal;
              }
              
              for(var itr=0;itr<sectionNameArr.length;itr++){
                var sectionName = sectionNameArr[itr];
                if(editAppObject.hasOwnProperty(sectionName) === false){
                  editAppObject[sectionName] = {};
                  requiredArray[sectionName] = {};
                  previousSectionValue[sectionName] = {};
                }

                field.sectionId       = sectionName;
                if(sectionNameArr.length>1){
                  var orgKey = field.key;
                  field.key = field.key +'_'+sectionName;
                  duplicateEditFieldMap[field.key] = orgKey;
                }
                //Add in Edit App Object
                editAppObject[sectionName][field.key] = field;
                if(sectionNameArr.length>1){
                  var newField = jQuery.extend(true, {}, field);
                  delete field;
                  var field = newField;
                  field.key = orgKey;
                }
              }
              
              
//              if(sectionArr[j].key == "MSTATUS" && sectionArr[j].value == "N"){
//                        if(editAppObject.hasOwnProperty("basic") !== false && editAppObject["basic"].hasOwnProperty("HAVECHILD")!== false){
//                                delete editAppObject["basic"]["HAVECHILD"];
//                        }
//              }

            }
        }   
      }
      showHideEditLink();
      editAppObject.needToUpdate = false;
      isInitialized = true;
      setGlobalVariables();
      if(debugInfo){
        console.log(editAppObject);
      }
    // called here fof horoscope CAL so that layer is shown only after whole edit data is loaded  
    if(typeof(fromCALHoro)!='undefined' && fromCALHoro=='1')createHoroscopeFun();

    }
    
    /*
     * getSectionArray
     * @param {type} sectionId
     * @returns {Array} Array of Section 
     */
    getSectionArray = function(sectionId){
      if(sectionId === CRITICAL){
        return criticalSectionArray;
      }
      if(sectionId === BASIC){
        return basicSectionArray;
      }
      
      if(sectionId === LIKES){
        return likesSectionArray;
      }
      
      if(sectionId === FAMILY){
        return familySectionArray
      }
      
      if(sectionId === LIFE_STYLE){
        return lifeStyleSectionArray;
      }
      
      if(sectionId == EDU_CAREER){
        return eduCareerSectionArray;
      }
      
      if(sectionId == HOROSCOPE){
        return horoscopeSectionArray;
      }
      
      if(sectionId == ABOUT){
        return aboutSectionArray;
      }
      
      if(sectionId == CONTACT){
        return contactSectionArray;
      }
      
      if(sectionId == VERIFICATION){
        return verificationSectionArray;
      }
      
      
      return null;
    }
    
    /*
     *cookoutSidesUIElement
     * @param {type} fieldObject
     * @param {type} fieldDOM
     * @returns {undefined}
     */
    cookoutSidesUIElement = function(fieldObject,fieldDOM){
      if(sidesUIMap.indexOf(fieldObject.key) === -1){
        return;
      }
      
      //Native State Field
      if(fieldObject.key == "NATIVE_STATE"){
        fieldDOM.append($("<span />",{class:"pos-abs f12 js-notFromIndia cursp color8 txtu js-toggleNativeFields",text:"Not from India?",id:"notFromIndia"}));
        return ;
      }
      
      //Native Country Field
      if(fieldObject.key == "NATIVE_COUNTRY"){
        fieldDOM.append($("<span />",{class:"pos-abs f12 js-notFromIndia cursp color8 txtu js-toggleNativeFields",text:"From India?",id:"fromIndia"}));
        return ;
      }
      
      //For Brother & Sister Field
      if(fieldObject.key == "T_BROTHER" || fieldObject.key == "T_SISTER"){
        var html = fieldDOM.find('.js-boxContent').html();
        var firstLiPos = html.indexOf('<li');
        
        var firstHalf = html.substr(0,firstLiPos);
        var secondHalf = html.substr(firstLiPos);
        
        firstHalf = firstHalf.replace('lh40','lh20');
        secondHalf = secondHalf.replace(/option_/g,'sub_option_');
        
        var marriedLabel = "<li value='-1' style='width:160px;cursor:default !important' > How many married? </li>";
        var divDOM = '<i class="reg-sprtie reg-droparrow pos_abs epdpos13 z2 disp-none js-subBoxList"></i><div class="pos_abs sub-mainlist epdpos14 z1 edpdbox boxshadow js-subBoxList disp-none" style="width:651px">';
        fieldDOM.append(divDOM+firstHalf+marriedLabel+secondHalf+'</div>');
        return ;
      }
      
      //For Your Info
      if(fieldObject.key == "YOURINFO"){
        var aboutMeVal = fieldObject.value.replace(/\s\s+/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        fieldDOM.append($("<div />",{class:"pt5 f13 txtr js-aboutLength",text:"Character Count :"+aboutMeVal.length}));
      }
      
      //Add OpenText Fields (Owner Name)
      if(fieldObject.type === OPEN_TEXT_TYPE && rightAlignWithoutPadding.indexOf(fieldObject.key) !== -1 ){
        
        var parentAttr = {class:"edpbrd3 fullwid edpbrad1 clearfix f15 edpp9 pos-rel",id:fieldObject.key.toLowerCase()+'Parent'};
        var inputAttr     = {class:"f15 color11 inpset1 fontlig",type:"text",value:fieldObject.decValue,placeholder:fieldObject.label,id:fieldObject.key.toLowerCase(),autocomplete:"off"}
        var errorLabelAttr = {class:"pos-abs js-errorLabel  f13 colr5 errorRightPad disp-none",text:"Invalid"}
        
        if(maxLengthMap.hasOwnProperty(fieldObject.key) === true){
          inputAttr["maxlength"] = maxLengthMap[fieldObject.key];
        }
        
        if(fieldObject.key.indexOf('PINCODE') !== -1){
          parentAttr.class += " mt25";
        }
       else{
          parentAttr.class += " mt10";
        }
      
        var parentDOM = $("<div />",parentAttr);
        parentDOM.append($("<input />",inputAttr));
        parentDOM.append($("<p />",errorLabelAttr))
        fieldDOM.append(parentDOM);
        
        bindOpenTextCommonEvents(fieldObject);
      }
      
      
      //Chosen field without top padding and label
      if(fieldObject.type === SINGLE_SELECT_TYPE && rightAlignWithoutPadding.indexOf(fieldObject.key) !== -1){
        var parentAttr = {class:"mt10 clearfix f15 js-rightAlign",id:fieldObject.key.toLowerCase()+'Parent'};

        var chosenAttr = {class:"chosen-select-no-search",id:fieldObject.key.toLowerCase(),"data-placeholder":fieldObject.label};
        var fieldDivAttr = {class:"edpbrd3 lh40 fullwid edpbrad1 mt5 pos-rel"};
                

        var parentDiv = $("<div />",parentAttr);
        var fieldDivDom = $("<div />",fieldDivAttr);
        
        //Add Chosen DropDown and options
        var chosenField = $("<select />",chosenAttr);
        var data = JSON.parse(getDataFromStaticTables(fieldObject.key));
        data = getDependantData(fieldObject,data);

        var optionString = "";
        var hideTheField = false;

        if(typeof data != "undefined"){
          optionString = prepareOptionDropDown(data,fieldObject);
        }
        else{
          hideTheField = true;
        }

        chosenField.append(optionString);
        fieldDivDom.append(chosenField);

        parentDiv.append(fieldDivDom);
        fieldDOM.append(parentDiv);

        $('#'+fieldObject.key.toLowerCase()).chosen(config["."+chosenAttr.class]);

        bindChosenCommonEvents(fieldObject);

        if(hideTheField){
          showHideField(fieldObject,"hide");
        } 
      }
      
      //Privacy Type Field 
      if(fieldObject.type === PRIVACY_TYPE && fieldObject.value.length){ 
        var fieldIdParent = '#'+fieldObject.key.toLowerCase()+'Parent';
        //Added this check to remove show no to all option in mobile case.
        if ( !(fieldObject.value == "N"))
        {
          fieldDOM.find(fieldIdParent).find(' ul li[value="'+ fieldObject.value +'"]').addClass('activeopt');
        }
      }
      
      //Range Type Field
      if(fieldObject.type === RANGE_TYPE){  
        var domString = '<div class="pt20" id="time_to_callParent"> <label class="color12">Suitable Time to Call</label>   <div class="clearfix mt10 pos-rel">   <div class="fl edpwid10 edpbrd3 clearfix">     <div class="fl edpbrd4 wid10p edpwid8 txtc edpp9 cursp fontlig pos-rel js-timeClick outline-none" tabindex="0"> <input tabindex="-1" type="text" id="startCall" readonly class="f15 color11 inpset2 edpwid11 edpm1 txtc fontlig  cursp" placeholder="-" value="-"/> <!--start:drop down--> <div class="pos_abs bg-white brdr-2 t1 edp-zi1 edpwid12 js-startCall disp-none">   <div class="pos_rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos5 edp-zi100"></i>     <div class="reg-zi1 fullwid optlist"> <ul class="listnone hor_list timelist">   <li value="1">1</li>   <li value="2">2</li>   <li value="3">3</li>   <li value="4">4</li>   <li value="5">5</li>   <li value="6">6</li>   <li value="7">7</li>   <li value="8">8</li>   <li value="9">9</li>   <li value="10">10</li>   <li value="11">11</li>   <li value="12">12</li> </ul>     </div>   </div> </div> <!--end:drop down-->    </div>     <div class="fl wid10p edpwid8 txtc edpp9 cursp fontlig pos-rel js-timeClick outline-none" tabindex="0"> <input tabindex="-1" type="text" class="f15 color11 inpset2 edpwid13 edpm1 txtc fontlig cursp " id="startAmPm" readonly placeholder="-" value="-"/> <!--start:drop down--> <div class="pos_abs bg-white brdr-2 edppos6 edp-zi1 edpwid10 disp-none js-startAmPm">   <div class="pos_rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos7 edp-zi100"></i>     <div class="reg-zi1 fullwid optlist"> <ul class="listnone hor_list t2list">   <li value="am">AM</li>   <li value="pm">PM</li> </ul>     </div>   </div> </div> <!--end:drop down-->    </div>   </div>   <div class="fl color12 edpp9  ml10  fontlig">to</div>   <div class="fr edpwid10 edpbrd3 clearfix">     <div class="fl edpbrd4 wid10p edpwid8 txtc edpp9 cursp fontlig pos-rel js-timeClick outline-none" tabindex="0"> <input tabindex="-1" type="text" class="f15 color11 inpset2 edpwid11 edpm1 txtc fontlig cursp " placeholder="-" id="endCall" readonly value="-"/> <!--start:drop down--> <div class="pos_abs js-endCall bg-white brdr-2 t1 edp-zi1 edpwid12 callEndPos disp-none">   <div class="pos_rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos5 edp-zi100 endArrowPos"></i>     <div class="reg-zi1 fullwid optlist"> <ul class="listnone hor_list timelist">   <li value="1">1</li>   <li value="2">2</li>   <li value="3">3</li>   <li value="4">4</li>   <li value="5">5</li>   <li value="6">6</li>   <li value="7">7</li>   <li value="8">8</li>   <li value="9">9</li>   <li value="10">10</li>   <li value="11">11</li>   <li value="12">12</li> </ul>     </div>   </div> </div> <!--end:drop down-->    </div>     <div class="fl wid10p edpwid8 txtc edpp9 cursp fontlig pos-rel js-timeClick outline-none" tabindex="0"> <input tabindex="-1" type="text" class="f15 color11 inpset2 edpwid13 edpm1 txtc fontlig cursp" id="endAmPm" readonly placeholder="-" value="-"/>  <div class="pos_abs bg-white brdr-2 edppos6 edp-zi1 edpwid10 disp-none js-endAmPm">   <div class="pos_rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos7 edp-zi100"></i>     <div class="reg-zi1 fullwid optlist"> <ul class="listnone hor_list t2list">   <li value="am">AM</li>   <li value="pm">PM</li> </ul>     </div>   </div> </div>    </div>   </div> <p class="pos-abs f13 js-errorLabel color5 errorRangeTop disp-none">Required</p> </div> </div>';
        
        fieldDOM.append(domString);
        var valArray = fieldObject.value.split(",");
        
        if(valArray.length == 1){
          return ;
        }
        
        var startTime = valArray[0].split(" ")[0].trim();
        var startAmPm = valArray[0].split(" ")[1].trim();
        
        fieldDOM.find('#startCall').attr("value",startTime).val(startTime);
        fieldDOM.find('.js-startCall ul li[value="'+startTime+'"]').addClass('activeopt');
        
        fieldDOM.find('#startAmPm').attr("value",startAmPm).val(startAmPm.toUpperCase());
        fieldDOM.find('.js-startAmPm ul li[value="'+startAmPm+'"]').addClass('activeopt');
        
        var endTime = valArray[1].split(" ")[0].trim();
        var endAmPm = valArray[1].split(" ")[1].trim();
        fieldDOM.find('#endCall').attr("value",endTime).val(endTime);
        fieldDOM.find('.js-endCall ul li[value="'+endTime+'"]').addClass('activeopt');
        
        fieldDOM.find('#endAmPm').attr("value",endAmPm).val(endAmPm.toUpperCase()); 
        fieldDOM.find('.js-endAmPm ul li[value="'+endAmPm+'"]').addClass('activeopt');
      }
    }
    cookNoteTextBeforeSubmitButton = function(domElement,sectionId,configObject){
                var parentAttr    = {class:"clearfix pt30",id:sectionId+'section-bottom'};
                var labelAttr     = {class:"fl pt11 edpcolr3 ",text:''};
                var spanAttr     = {class:"",text:'We will not allow any change in Date of Birth and Marital Status after you submit this form. So please reconfirm the details carefully before submitting.'};
                var divAttr     = {class:"outline-none js-bottomText fl edpwid3 edpbrad1 f13 pos-rel",text:''};
                
                var parentDiv = $("<div />",parentAttr);
                var labelDOM  = $("<label />",labelAttr);
                var divDOM  = $("<div />",divAttr);
                
                divDOM.append($("<span />",spanAttr));
                parentDiv.append(labelDOM);
                parentDiv.append(divDOM);
                domElement.append(parentDiv);
    };
    /*
     * cookSaveCancelButton
     * @param {type} domElement
     * @param {type} fieldObject
     * @param {type} configObject
     * @returns {undefined}
     */
    cookSaveCancelButton = function(domElement,sectionId,configObject){
      var parentAttr    = {class:"clearfix pt30 edpp8"};
      var saveBtnAttr   = {class:"bg_pink brdr-0 txtc fontreg f20 colrw lh46 pl40 pr40 fl js-save cursp",id:"saveBtn"+sectionId,text:"Save",tabindex:"0"};
      var cancelBtnAttr = {class:"bg6 brdr-0 txtc fontreg f20 colrw lh46 pl40 pr40 fl ml20 js-cancel cursp",id:"cancelBtn"+sectionId,text:"Cancel",tabindex:"0"};
      
      if(typeof configObject != "undefined"){
        if(configObject.hasOwnProperty('parentAttr'))
        {
          for (var key in configObject['parentAttr']){
            parentAttr[key] = configObject['parentAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('saveBtnAttr'))
        {
          for (var key in configObject['saveBtnAttr']){
            saveBtnAttr[key] = configObject['saveBtnAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('cancelBtnAttr'))
        {
          for (var key in configObject['cancelBtnAttr']){
            cancelBtnAttr[key] = configObject['cancelBtnAttr'][key];
          }
        }
      }
      
      var parentDiv     = $("<div />",parentAttr);
      var saveBtnDOM    = $("<div />",saveBtnAttr);
      var cancelBtnDOM  = $("<div />",cancelBtnAttr);
      
      parentDiv.append(saveBtnDOM);
      parentDiv.append(cancelBtnDOM);
      
      domElement.append(parentDiv);
    }
	/**
	* cookSectionTopHeading
	* @param {type} domElement form domElement
    * @param {type} sectionId section Id
    * @returns {undefined}
	*/
    cookSectionTopHeading = function(domElement,sectionId){
            if(sectionTopLabelMap.hasOwnProperty(sectionId) === true){
                var parentAttr    = {class:"clearfix",id:sectionId+'section-heading'};
                var labelAttr     = {class:"fl pt11 edpcolr3 uploadone",text:'Upload at least one document'};
                var parentDiv = $("<div />",parentAttr);
                var labelDOM  = $("<label />",labelAttr);
                parentDiv.append(labelDOM);
                domElement.append(parentDiv);
                if(sectionTopLabelRequired.indexOf(sectionId) !== -1){
                        var field = new fieldObject;
                        field.key             = 'uploadone';
                        field.label           = '';
                        field.value           = '';
                        field.decValue        = "";
                        field.isEditable      = false;
                        field.isUnderScreen   = false;
                        field.sectionId   = sectionId;
                        field.type            = NON_EDITABLE_TYPE;/*Single Select Type*/
                        
                        editAppObject[sectionId][field.key] = field;
              }
        }
    }
    /*
     * cookOpenTextField
     * @param {type} domElement
     * @param {type} fieldObject
     * @returns {undefined}
     */
    cookOpenTextField = function(domElement,fieldObject,configObject){
      
      var parentAttr    = {class:"clearfix pt30",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr     = {class:"fl pt11 edpcolr3"};
      var fieldDivAttr  = {class:"fl edpbrd3 lh40 edpwid3 edpbrad1 pos-rel"}
      if(fieldObject.key=="NAME")
      	var inputAttr     = {class:"f15 color11 fontlig wid70p",type:"text",value:fieldObject.decValue,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off"};
      if(fieldObject.key=="AADHAAR"){
        var fieldDivAttr  = {class:"fl edpbrd3 wid351 edpbrad1 pos-rel fl edpp5 edpbrad1 edpbg1"}
      	var inputAttr     = {class:"f15 color11 fontlig wid70p cursp",type:"text",value:fieldObject.decValue,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off"};
        } else
      	var inputAttr     = {class:"f15 color11 fontlig wid94p",type:"text",value:fieldObject.decValue,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off"};
      var errorLabelAttr = {class:"pos-abs js-errorLabel errorChosenTop f13 colr5 disp-none"};
      if(debugInfo){
        var underScreenAttr = {class:"f13 pos-abs js-undSecMsg",text:"Under screening"};
      }
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
        if(configObject.hasOwnProperty('parentAttr'))
        {
          for (var key in configObject['parentAttr']){
            parentAttr[key] = configObject['parentAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('labelAttr'))
        {
          for (var key in configObject['labelAttr']){
            labelAttr[key] = configObject['labelAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('fieldDivAttr'))
        {
          for (var key in configObject['fieldDivAttr']){
            fieldDivAttr[key] = configObject['fieldDivAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('inputAttr'))
        {
          for (var key in configObject['inputAttr']){
            inputAttr[key] = configObject['inputAttr'][key];
          }
        }
        
      }
      
      if(typeof inputAttr.value == "string" && inputAttr.value.length && inputAttr.value.indexOf("kg") && fieldObject.key == "WEIGHT"){
        inputAttr.value = fieldObject.value;
      }
      
      if(debugInfo)
        console.log(fieldObject.key+' : '+inputAttr.value);
      
      if(maxLengthMap.hasOwnProperty(fieldObject.key) === true){
        inputAttr["maxlength"] = maxLengthMap[fieldObject.key];
      }
      var parentDiv = $("<div />",parentAttr);
      var labelDOM  = $("<label />",labelAttr);
      
      labelDOM.text(fieldObject.label);
      parentDiv.append(labelDOM);
      
      var fieldDivDom = $("<div />",fieldDivAttr);
      
      fieldDivDom.append($("<input />",inputAttr));
      
      var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
      errorLabelAttr.text = errorText;
      
      if(rightAlignedFields.indexOf(fieldObject.key) !== -1){
        errorLabelAttr.class += " right0";
      }
      
      fieldDivDom.append($("<p />",errorLabelAttr));
	if(fieldObject.key=="NAME")
	{
	var nameSettingDOM = '            <div id="hoverDiv" class="disp_ib pos-abs r0 mr5 cursp"><span id="showText" class="colrGrey fontlig f12 showToAll disp_ib">Show to All</span><i id="settingsIcon"></i> <ul id="optionDrop" class="optionDrop pos-abs disp-none" data-toSave="displayName"> <li class="selected" id="showYes">Show my name to all </li> <li id="showNo">Don\'t show my name<br> ( You will not be able to see names of other members ) </li>  </ul> </div>';
	fieldDivDom.append(nameSettingDOM);
	}
        
	if(fieldObject.key=="AADHAAR")
	{
	var nameSettingDOM = '            <div id="verify-aadhaar" class="disp_ib pos-abs r0 mr5 cursp bg_pink wid127 txtc" style="right: -143px;top: -1px;height: 40px;"><div style="position: relative;"> <div class="pos-abs z1 wid300 edpbox1 aadhardiv" style="right: -285px;"> <div class="edpp6">   <div class="pos-rel bg-white brdr-1 fullwid edpp7">     <i class="edpic8 sprite2 pos-abs edppos3"></i>     <div class="txtc fontreg">   <p class="colr5 f15 lh30">Provide your Aadhaar no and verify?</p>   <p class="f13 color11">To edit Gender,  please contact customer care: 1-800-419-6299 / help@jeevansathi.com</p>     </div>   </div> </div>  </div> <span id="showText" class="colrw fontlig f16 lh40">Verify</span></div>';
	fieldDivDom.append(nameSettingDOM);
	}
      
      //Add underscreening in debug case only
      if(debugInfo){
        if(fieldObject.isUnderScreen === false){
          underScreenAttr.class += " disp-none";
        }   
        fieldDivDom.append($("<p />",underScreenAttr));
      }
      
      if(autoSuggestFields.indexOf(fieldObject.key) !== -1){
        
        var stringDOM = '<div class="pos_abs bg-white brdr-1 reg-zi1 reg-wid3 js-autoSuggest disp-none" tabindex="0" ><div class="pos_rel fullwid"> <i class="pos_abs sprite2 dpp-up-arrow  test-pos3 reg-zi100"></i> <div class="reg-zi1 fullwid optlist scrolla color11 reg-hgt200" id=""> <ul class="js-autoSuggestOption" id="autoSuggestOption" > </ul></div></div></div>'

        fieldDivDom.append(stringDOM);
      }
      
      parentDiv.append(fieldDivDom);
      
      domElement.append(parentDiv);
      //Bind Common Event Handling
      if(autoSuggestFields.indexOf(fieldObject.key) === -1){//NOrmal Open Text Fields
        bindOpenTextCommonEvents(fieldObject);
      }
      else
      {
        bindAutoSuggestCommonEvents(fieldObject);
      }
    }
    
    /*
     * cookBoxTypeField
     * @param {type} domElement
     * @param {type} fieldObject
     * @returns {undefined}
     */
    cookBoxTypeField = function(domElement,fieldObject,configObject){
      
      var parentAttr    = {class:"clearfix pt30",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr     = {class:"fl pt11 edpcolr3",text:fieldObject.label};
      var fieldDivAttr  = {class:"fl edpbrd3 edpwid3 edpbrad1 cursp pos-rel outline-none js-boxField",id:fieldObject.key.toLowerCase(),tabindex:"0"};
      var decValAttr    = {class:"fl lh40 edpwid3 edpbrad1 js-decVal"};
      if(fieldObject.key.toLowerCase() == "mstatus"){
              var underScreenAttr = {class:"f13 pos-abs js-undSecMsg",text:"Under screening"};
      }
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
      }
        
      var parentDiv = $("<div />",parentAttr);
      var labelDOM  = $("<label />",labelAttr);
      
      parentDiv.append(labelDOM);
      
      var fieldDivDom = $("<div />",fieldDivAttr);
      var decValDom  = $("<div />",decValAttr);
      
      fieldDivDom.append($("<p />",{class:"pos-abs js-errorLabel errorTop f13 colr5 disp-none",text:errorMap[fieldObject.key]}));
           
      var decText = fieldObject.decValue;
      var filledClass= "";
      if(fieldObject.decValue.length == 0){
        decText = notFilledText;
        filledClass = " color12";
      }
      
      decValDom.append($("<span />",{class:"ml10 js-decVal"+filledClass,text:decText}));
      fieldDivDom.append(decValDom);
      
      var data = JSON.parse(getDataFromStaticTables(fieldObject.key));
      data = getDependantData(fieldObject,data);
      
      if(boxTypeMaxAllowed.hasOwnProperty(fieldObject.key) === true)
      {
        var maxAllowedEle = boxTypeMaxAllowed[fieldObject.key];
      }
      
      var optionString = "";
      var hideTheField = false;
      
      if(typeof data != "undefined"){
        optionString =  prepareBoxOptionDropDown(data,fieldObject,maxAllowedEle);
      }
      else{
        hideTheField = true;
      }
      
      var boxContentDOM = $("<div />",{class:"js-boxContent"});      
      boxContentDOM.append(optionString)
      fieldDivDom.append(boxContentDOM);
      if(fieldObject.key.toLowerCase() == "mstatus"){
        if(fieldObject.isUnderScreen === false){
            underScreenAttr.class += " disp-none";
          }   
          fieldDivDom.append($("<p />",underScreenAttr));
  }
      //Add Some Sides UI Element
      if(sidesUIMap.indexOf(fieldObject.key) != -1){
        cookoutSidesUIElement(fieldObject,fieldDivDom);
      }
      
      parentDiv.append(fieldDivDom);
      
      domElement.append(parentDiv);
      //Bind Box Common Events 
      bindBoxCommonEvents(fieldObject,maxAllowedEle);
      
      if(hideTheField){
        showHideField(fieldObject,"hide");
      }
    }
    
    /*
     * cookDateBoxTypeField
     * @param {type} domElement
     * @param {type} fieldObject
     * @returns {undefined}
     */
    cookDateBoxTypeField = function(domElement,fieldObject,configObject){
      
      var parentAttr    = {class:"clearfix pt30",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr     = {class:"fl pt11 edpcolr3",text:fieldObject.label};
      var fieldDivAttr  = {class:"fl edpbrd3 edpwid3 edpbrad1 cursp pos-rel outline-none js-boxField",id:fieldObject.key.toLowerCase(),tabindex:"0"};
      var decValAttr    = {class:"fl lh40 edpwid3 edpbrad1 js-decVal"};
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
      }
        
      var parentDiv = $("<div />",parentAttr);
      var labelDOM  = $("<label />",labelAttr);
      
      parentDiv.append(labelDOM);
      
      var fieldDivDom = $("<div />",fieldDivAttr);
      var decValDom  = $("<div />",decValAttr);
      
      fieldDivDom.append($("<p />",{class:"pos-abs js-errorLabel errorTop f13 colr5 disp-none",text:errorMap[fieldObject.key]}));
           
      var decText = fieldObject.decValue;
      var filledClass= "";
      if(fieldObject.decValue.length == 0){
        decText = notFilledText;
        filledClass = " color12";
      }
      
      decValDom.append($("<span />",{class:"ml10 js-decVal"+filledClass,text:decText}));
      fieldDivDom.append(decValDom);
      
      var optionString = "";
      var hideTheField = false;
      optionString =  prepareDateBoxOptionDropDown(fieldObject,3,fieldObject.value.split("-"));
      var boxContentDOM = $("<div />",{class:"js-boxContent"});      
      boxContentDOM.append(optionString);
      var i = 0;
        $.each(dateDataArray,function(key1,data1)
        {
          $.each(data1,function(key2,data2)
          {
                $.each(data2,function(value,label)
                { 
                        var ClassName = "reg-pos10";
                        if(i ==0){
                             var ClassName = "reg-pos5";   
                        }
                        var parentAttr2    = {class:"js-"+label.toLowerCase()+" sub-mainlist pos_abs "+ClassName+" reg-zi1 regdropbox boxshadow reg-wid12 disp-none"};
                        var parentDOM2  = $("<div />",parentAttr2);
                        var dateAttr    = {id:label.toLowerCase()+'sub',rel:fieldObject.key.toLowerCase()};
                        var dateDOM  = $("<ul />",dateAttr);
                        parentDOM2.append(dateDOM);
                        boxContentDOM.append(parentDOM2);
                        i++;
                })
          })
        })
        
  //return false;
      
      
      fieldDivDom.append(boxContentDOM);
      
      //Add Some Sides UI Element
      if(sidesUIMap.indexOf(fieldObject.key) != -1){
        cookoutSidesUIElement(fieldObject,fieldDivDom);
      }
      parentDiv.append(fieldDivDom);
      domElement.append(parentDiv);

      //Bind Box Common Events 
      bindDateBoxCommonEvents(fieldObject,3);
      
      if(hideTheField){
        showHideField(fieldObject,"hide");
      }
    }
    
    /*
     * cookNonEditableField
     * @param {type} domElement
     * @param {type} fieldObject
     * @returns {undefined}
     */
    cookNonEditableField = function(domElement,fieldObject,configObject){
      
      var parentAttr      = {class:"clearfix pt30"};
      var labelAttr       = {class:"fl pt11 edpcolr3"};
      var fieldDivAttr    = {class:"fl edpbrd3 edpwid3 edpp5 edpbrad1 edpbg1"};
      var childDomString  = '<div class="edpp4 clearfix">    <div class="fl color12">{{FIELD_VALUE}}</div>    <div class="fr edphover1 pos-rel">  <div class="vicons edpic7"></div>  <!--start:hover box-->  <div class="pos-abs wid300 edpbox1"> <div class="edpp6">   <div class="pos-rel bg-white brdr-1 fullwid edpp7">     <i class="edpic8 sprite2 pos-abs edppos3"></i>     <div class="txtc fontreg">   <p class="colr5 f15 lh30">{{FIELD_LABEL}} cannot be edited</p>   <p class="f13 color11">To edit {{FIELD_LABEL}},  please contact customer care: 1-800-419-6299 / help@jeevansathi.com</p>     </div>   </div> </div>  </div>   <!--end:hover box--> </div>   </div>';
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
      }
        
      var parentDiv = $("<div />",parentAttr);
      var labelDOM  = $("<label />",labelAttr);
      
      labelDOM.text(fieldObject.label);
      parentDiv.append(labelDOM);
      
      var fieldDivDom = $("<div />",fieldDivAttr);
           
      childDomString = childDomString.replace(/{{FIELD_VALUE}}/g,fieldObject.decValue);
      childDomString = childDomString.replace(/{{FIELD_LABEL}}/g,fieldObject.label);
      
      fieldDivDom.append(childDomString);
      parentDiv.append(fieldDivDom);
      
      domElement.append(parentDiv);
    }
    
    /*
     * cookChosenSelectField
     * @param {type} domElement
     * @param {type} fieldObject
     * @returns {undefined}
     */
    cookChosenSelectField = function(domElement,fieldObject,configObject){
      
      var parentAttr        = {class:"clearfix pt30",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr         = {class:"fl pt11 edpcolr3",text:fieldObject.label};
      var fieldDivAttr      = {class:"fl edpbrd3 lh40 edpwid3 edpbrad1 pos-rel"};
      var chosenAttr        = {id:fieldObject.key.toLowerCase(),class:"chosen-select-no-search","data-placeholder":notFilledText};
      
      var fieldMapKey = fieldObject.key;
      if(duplicateEditFieldMap.hasOwnProperty(fieldMapKey)){
        fieldMapKey = duplicateEditFieldMap[fieldMapKey];
      }
      //If type of field is single select with search field then
      if(singleSelectWithSearch.indexOf(fieldMapKey) !== -1){
       chosenAttr.class = "chosen-select-width"; 
      }
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
        if(configObject.hasOwnProperty('chosenAttr'))
        {
          for (var key in configObject['chosenAttr']){
            chosenAttr[key] = configObject['chosenAttr'][key];
          }
        }
        if(configObject.hasOwnProperty('parentAttr'))
        {
          for (var key in configObject['parentAttr']){
            parentAttr[key] = configObject['parentAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('labelAttr'))
        {
          for (var key in configObject['labelAttr']){
            labelAttr[key] = configObject['labelAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('fieldDivAttr'))
        {
          for (var key in configObject['fieldDivAttr']){
            fieldDivAttr[key] = configObject['fieldDivAttr'][key];
          }
        }
      }
      
      var parentDiv = $("<div />",parentAttr);
      var labelDOM  = $("<label />",labelAttr);
            
      parentDiv.append(labelDOM);
      
      var fieldDivDom = $("<div />",fieldDivAttr);
      var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
      var errorLabelAttr = {class:"pos-abs js-errorLabel errorChosenTop f13 colr5 disp-none",text:errorText};
      if(rightAlignedFields.indexOf(fieldObject.key) !== -1){
        errorLabelAttr.class += " right0";
      }
      
      fieldDivDom.append($("<p />",errorLabelAttr));
     
      //Add Chosen DropDown and options
      var chosenField = $("<select />",chosenAttr);
        var ky = fieldObject.key;
        if(fieldObject.key == "MSTATUS"){
                  if(editAppObject[BASIC]['RELIGION'].value == "2" && editAppObject[BASIC]['GENDER'].value == "M"){
                          var ky = "MSTATUS_MUSLIM_EDIT";
                  }else{
                          var ky = "MSTATUS_EDIT";
                  }
        }
      var data = JSON.parse(getDataFromStaticTables(ky));
      data = getDependantData(fieldObject,data);
      
      if(debugInfo)
        console.log(fieldObject.key+' : '+fieldObject.value);
      
      var optionString = "";
      var hideTheField = false;
      if(fieldObject.key=="CITY_RES"){
          var countryVal = editAppObject[BASIC]['COUNTRY_RES'].value;
          var stateVal = editAppObject[BASIC]['STATE_RES'].value;
          if(countryVal=='51' && stateVal && stateVal!=0){
              var dataCity = JSON.parse(getDataFromStaticTables(fieldObject.key))[stateVal];
              optionString = prepareOptionDropDown(dataCity,fieldObject);
          }
          else if(countryVal=='128'){
              var dataCity = JSON.parse(getDataFromStaticTables(fieldObject.key))[countryVal];
              optionString = prepareOptionDropDown(dataCity,fieldObject);
          }
          else
              hideTheField = true;
      }
      else if(fieldObject.key=="STATE_RES"){
          if(editAppObject[BASIC]['COUNTRY_RES'].value!='51'){
            hideTheField = true;
          }
          else{
            optionString = prepareOptionDropDown(data,fieldObject);
            if(editAppObject[BASIC]['STATE_RES'].value=='0'){
                var stateFieldObject     = editAppObject[BASIC]["STATE_RES"];
                requiredFieldStore.add(stateFieldObject);
            }
          }
      }
      else if(typeof data != "undefined"){
        optionString = prepareOptionDropDown(data,fieldObject);
      }
      else{
        hideTheField = true;
      }
            
      chosenField.append(optionString);
      fieldDivDom.append(chosenField);
      
      //Add Some Sides UI Element
      if(sidesUIMap.indexOf(fieldObject.key) != -1){
        cookoutSidesUIElement(fieldObject,fieldDivDom);
      }
      
      parentDiv.append(fieldDivDom);
      domElement.append(parentDiv);
      
      $('#'+fieldObject.key.toLowerCase()).chosen(config["."+chosenAttr.class]);
      
      bindChosenCommonEvents(fieldObject);
      
      if(hideTheField){
        showHideField(fieldObject,"hide");
      }
    }
    
    /*
     * cookTextAreaField
     * @param {type} domElement
     * @param {type} fieldObject
     * @param {type} configObject
     * @returns {undefined}
     */
    cookTextAreaField = function(domElement,fieldObject,configObject){
      
      var parentAttr    = {class:"clearfix pt30 fontlig",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr     = {class:"f17 fontlig color12",text:fieldObject.label};
      var fieldDivAttr  = {class:"edpbrd3 mt10 padall-10 js-areaBox pos-rel"}
      var textAreaAttr     = {class:"color11 fontlig f15 fullwid brdr-0 hgt110 bgnone outline-none",type:"text",text:fieldObject.value,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off",value:fieldObject.value}
      
      if(debugInfo){
        var underScreenAttr = {class:"f13 pos-abs js-undSecMsg",text:"Under screening"};
      }
      
      if(hintMap.hasOwnProperty(fieldObject.key) == true){
        textAreaAttr.placeholder = hintMap[fieldObject.key];
      }
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
        if(configObject.hasOwnProperty('textAreaAttr'))
        {
          for (var key in configObject['textAreaAttr']){
            textAreaAttr[key] = configObject['textAreaAttr'][key];
          }
        }
        if(configObject.hasOwnProperty('parentAttr'))
        {
          for (var key in configObject['parentAttr']){
            parentAttr[key] = configObject['parentAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('labelAttr'))
        {
          for (var key in configObject['labelAttr']){
            labelAttr[key] = configObject['labelAttr'][key];
          }
        }
        
        if(configObject.hasOwnProperty('fieldDivAttr'))
        {
          for (var key in configObject['fieldDivAttr']){
            fieldDivAttr[key] = configObject['fieldDivAttr'][key];
          }
        }
      }
      
      if(maxLengthMap.hasOwnProperty(fieldObject.key) === true){
        textAreaAttr["maxlength"] = maxLengthMap[fieldObject.key];
      }
      
      if(fieldObject.key == "YOURINFO"){
        labelAttr.text="";
      }
      
      var parentDiv = $("<div />",parentAttr);
      
      if(labelAttr.text.length){
        var labelDOM  = $("<label />",labelAttr);
        parentDiv.append(labelDOM);
      }
            
      var fieldDivDom = $("<div />",fieldDivAttr);
      
      var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
      fieldDivDom.append($("<p />",{class:"pos-abs js-errorLabel errorTextAreaTop f13 colr5 disp-none",text:errorText}));
      
      if(rightAlignedFields.indexOf(fieldObject.key) === -1){
        fieldDivDom.append($("<textarea />",textAreaAttr));
      }
      else{
        var subDivDom = $("<div />",{class:" wid177 txtc edpp9"});
        subDivDom.append($("<textarea />",textAreaAttr));
        fieldDivDom.append(subDivDom);
        
        var privacyParentAttr   = {class:"fl mt8 pos-rel showset cursp",id:'show_'+fieldObject.key.toLowerCase()+'Parent'};
        var privacyImgAttr      = {class:"vicons edpic9 ",id:'show_'+fieldObject.key.toLowerCase()};
        var privacyDivString    = '<div class="pos-abs bg-white z2 msg1box epdpos9 edpwid12 js-privacySetting disp-none"> <div class="pos-rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos8 edp-zi100"></i> <ul class="listnone  f13 edplist2 brdr-1 mt10 edpbrad1"><li value="Y">Show to my accepted contacts/paid members</li> <li value="N">Don\'t show to anybody</li> </ul> </div></div>';
        
        if(fieldObject.key == "CONTACT"){
          privacyParentAttr.id = 'showaddressParent';
          privacyImgAttr.id = 'showaddress';
        }
        
        //Add Privacy Button
        var privacyParentDOM = $("<div />",privacyParentAttr);
        privacyParentDOM.append($("<i />",privacyImgAttr));
        privacyParentDOM.append(privacyDivString);
        var subSettingDom = $("<div />",{class:"textAreaShow edpp9 iepl10 pos-abs"});
        subSettingDom.append(privacyParentDOM);
        fieldDivDom.append(subSettingDom);
      }
      
      //Add underscreening in debug case only
      if(debugInfo){
        if(fieldObject.isUnderScreen === false){
          underScreenAttr.class += " disp-none";
        }   
        fieldDivDom.append($("<p />",underScreenAttr));
      }
      
      //Add Some Sides UI Element
      if(sidesUIMap.indexOf(fieldObject.key) != -1){
        cookoutSidesUIElement(fieldObject,fieldDivDom);
      }
      
      parentDiv.append(fieldDivDom);
      
      if(fieldObject.key == "YOURINFO"){
        parentDiv.append('<div class="prfbg9 fontreg f13 color11"><div class="padall-10"><p>Introduce yourself. Write about your values, beliefs/goals and aspirations.</p><p>How do you describe yourself ? Your interests and hobbies.</p></div></div>');
      }
      
      domElement.append(parentDiv);
      
      bindTextAreaCommonEvents(fieldObject);
    }
    /*
     * cookTextAreaField
     * @param {type} domElement
     * @param {type} fieldObject
     * @param {type} sectionId
     * @returns {undefined}
     */
    cookFileField = function(domElement,fieldObject,sectionId){
      if(sectionId == CRITICAL){
              var parentAttr    = {class:"clearfix fontlig pt30",id:fieldObject.key.toLowerCase()+'Parent'};
              var labelAttr     = {class:"fl pt11 edpcolr3",text:fieldObject.label};
              var btnAttr     = {class:"bg_pink lh30 f14 colrw txtc brdr-0 cursp disp_ib fullwid pos-rel wid50p dispib",type:"file",text:fieldObject.value,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off",text:'Divorce Decree',id:'idBtn_'+fieldObject.key.toLowerCase()};
              var fieldDivAttr  = {class:"fl edpwid3 edpbrad1 pos-rel outline-none"}
      }else{
                var parentAttr    = {class:"clearfix fontlig pt10",id:fieldObject.key.toLowerCase()+'Parent'};
                var labelAttr     = {class:"f17 fontlig color12",text:fieldObject.label};
                var fieldDivAttr  = {class:"js-fileBox pos-rel"}
                var btnAttr     = {class:"bg_pink mt20 lh30 f14 colrw txtc brdr-0 cursp disp_ib fullwid pos-rel wid50p dispib",type:"file",text:fieldObject.value,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off",text:'Attach',id:'idBtn_'+fieldObject.key.toLowerCase()}
      }
      
      var textAreaAttr     = {class:"color11 fontlig f15 brdr-0 bgnone outline-none wh0 disp-none",type:"file",text:fieldObject.value,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off",value:fieldObject.value}
       var labelAttr2     = {class:"f14 disp_ib color5 padl15 vertM dispib textTru wid40p",id:fieldObject.key.toLowerCase(),text:'jpg/pdf only',id:'idlabel_'+fieldObject.key.toLowerCase(),style:"cursor:text"}
      
      if(debugInfo){
        var underScreenAttr = {class:"f13 pos-abs js-undSecMsg",text:"Under screening"};
      }
      
      if(hintMap.hasOwnProperty(fieldObject.key) == true){
        textAreaAttr.placeholder = hintMap[fieldObject.key];
      }
      
      var parentDiv = $("<div />",parentAttr);
      
      if(labelAttr.text.length){
        var labelDOM  = $("<label />",labelAttr);
        parentDiv.append(labelDOM);
      }
            
      var fieldDivDom = $("<div />",fieldDivAttr);
      var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
      if(sectionId == CRITICAL){
                fieldDivDom.append($("<p />",{class:"pos-rel js-errorLabel f13 colr5 disp-none",text:errorText}));
      }else{
                fieldDivDom.append($("<p />",{class:"pos-abs js-errorLabel f13 colr5 disp-none",text:errorText}));
      }
      
      fieldDivDom.append($("<div />",btnAttr));
      fieldDivDom.append($("<div />",labelAttr2));
      fieldDivDom.append($("<input />",textAreaAttr));
      
      //Add underscreening in debug case only
      if(debugInfo){
        if(fieldObject.isUnderScreen === false){
          underScreenAttr.class += " disp-none";
        }   
        fieldDivDom.append($("<p />",underScreenAttr));
      }
      
      parentDiv.append(fieldDivDom);
      
      
      domElement.append(parentDiv);
      
      //bindTextAreaCommonEvents(fieldObject);
    }
    
    /*
     * cookPhoneField
     * @param {type} domElement
     * @param {type} fieldObject
     * @param {type} configObject
     * @returns {undefined}
     */
    cookPhoneField = function(domElement,fieldObject,configObject){
      
      var parentAttr    = {class:"pt20",id:fieldObject.key.toLowerCase()+'Parent'};
      var labelAttr     = {class:"fontlig color12",text:fieldObject.label};
      var fieldDivAttr  = {class:"edpbrd3 fullwid edpbrad1 mt5 clearfix f15 pos-rel"}
      
      var valueArray    = fieldObject.value.split(",");
      var isdVal        = (valueArray.length>1) ? valueArray[0] : getISDCode();
      var phoneVal      = (valueArray.length>1) ? valueArray[1] : valueArray[0]; 
      
      var isdDivAttr    = {class:"fl edpbrd4 wid10p edpwid4 txtc edpp9"};
      var isdTextAttr   = {class:"f15 color11 inpset1 fullwid fontlig width20",type:"text",value:isdVal,id:fieldObject.key.toLowerCase()+'-isd',autocomplete:"off",maxlength:"4"};
          
      var phoneMaxLength = (isdIndianAllowedCodes.indexOf(isdVal) !== -1)? "10" : INT_PHONE_MAX_LEN.toString();
      
      var phoneDivAttr  = {class:"fl edpwid17 edpp9"};
      var phoneTextAttr = {class:"f15 color11 inpset1 wid94p fontlig",type:"text",value:phoneVal,autocomplete:"off",id:fieldObject.key.toLowerCase()+'-mobile',myMaxLength:phoneMaxLength,maxlength:"15"}
      
       if(fieldObject.key == "PHONE_RES"){//Add Std Field
        var stdVal        = (valueArray.length > 1)? valueArray[1] : getSTDCode();
        
        if(valueArray.length > 1)
          phoneVal = valueArray[2];
        
        var stdDivAttr    = {class:"fl edpbrd4 wid10p edpwid8 txtc edpp9"};
        var stdTextAttr   = {class:"f15 color11 inpset2 edpwid25 edpm1 fontlig",type:"text",value:stdVal,id:fieldObject.key.toLowerCase()+'-std',autocomplete:"off",dbValue:stdVal,maxlength:"6"};
        
        phoneDivAttr.class  = "fl wid10p edpp9 txtc edpwid9";
        phoneTextAttr.class ="f15 color11 inpset2 wid90p edpm1 fontlig";
        phoneTextAttr.value = phoneVal;
        phoneTextAttr.id = fieldObject.key.toLowerCase()+'-landline';
        phoneTextAttr.myMaxLength = "8";
        
        if(isdIndianAllowedCodes.indexOf(isdVal) !== -1){
          phoneTextAttr.myMaxLength = 10 - parseInt(stdVal.length);
        }else{
          phoneTextAttr.myMaxLength = INT_PHONE_MAX_LEN - parseInt(stdVal.length) - parseInt(isdVal.length);
        }
      }
      
      var privacyParentAttr   = {class:"fl mt8 pos-rel showset cursp",id:'show'+fieldObject.key.toLowerCase()+'Parent'};
      var privacyImgAttr      = {class:"vicons edpic9 ",id:'show'+fieldObject.key.toLowerCase()};
      // this string doesn't contain don't show to anybody
      var privacyDivString    = '<div class="pos-abs bg-white z2 msg1box epdpos9 edpwid12 js-privacySetting disp-none"> <div class="pos-rel fullwid"> <i class="pos_abs reg-sprtie reg-droparrow edppos8 edp-zi100"></i> <ul class="listnone  f13 edplist2 brdr-1 mt10 edpbrad1"><li value="Y">Show to All Paid Members</li> <li value="C">Show to only Members I Accept / Express Interest In</li></ul> </div></div>';
      
      if(typeof configObject != "undefined"){
        /*Loop and replace in default One*/
      }
            
      var parentDiv = $("<div />",parentAttr);
      
      if(labelAttr.text.length){
        var labelDOM  = $("<label />",labelAttr);
        parentDiv.append(labelDOM);
      }
            
      var fieldDivDom = $("<div />",fieldDivAttr);
      var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
      fieldDivDom.append($("<p />",{class:"pos-abs js-errorLabel errorPhoneTop f13 colr5 disp-none right0",text:errorText}));
      
      //Add Isd
      var isdDOM = $("<div />",isdDivAttr);
      isdDOM.append($("<span />",{class:"pos-abs",text:"+"}));
      isdDOM.append($("<input />",isdTextAttr));
      fieldDivDom.append(isdDOM);
      
      if(fieldObject.key == "PHONE_RES"){
        //Add Std Field
        var stdDOM = $("<div />",stdDivAttr);
        stdDOM.append($("<input />",stdTextAttr));
        fieldDivDom.append(stdDOM);
      }
      
      //Add Phone 
      var phoneDOM = $("<div />",phoneDivAttr);
      phoneDOM.append($("<input />",phoneTextAttr));
      fieldDivDom.append(phoneDOM);
      
      //Add Privacy Button
      var privacyParentDOM = $("<div />",privacyParentAttr);
      privacyParentDOM.append($("<i />",privacyImgAttr));
      privacyParentDOM.append(privacyDivString);
      fieldDivDom.append(privacyParentDOM);
         
      //Add Field in Parent
      parentDiv.append(fieldDivDom);     
      
      //Add AddOwner Button
      var addOwnerParentDOM = $("<p />",{class:"txtr js-ownerBtn"});
      addOwnerParentDOM.append($("<a />",{class:"color12 f13 disp_ib pt5 cursp js-ownerBtnClick",text:"Add owner",parentName:fieldObject.key.toLowerCase()+'Parent'}));
      parentDiv.append(addOwnerParentDOM);
      
      domElement.append(parentDiv);
      
      bindPhoneCommonEvents(fieldObject);
    }
    
    /*
     * bindChosenCommonEvents
     * @param {type} fieldObject
     * @returns {String}
     */
    bindChosenCommonEvents = function(fieldObject){
      var fieldDOM = $('#' + fieldObject.key.toLowerCase());
      var parentFieldDOM =$('#' + fieldObject.key.toLowerCase()+'Parent');
      var bIsSearchExist = false;
      //If type of field is single select with search field then
      if(singleSelectWithSearch.indexOf(fieldObject.key) !== -1){
       bIsSearchExist = true;
      }
      if(fieldObject.type == MULTIPLE_SELECT_TYPE){
         bIsSearchExist = true;
      }      
      if(bIsSearchExist){
       var container = $('#' + fieldObject.key.toLowerCase() + '_chosen');
       container.on('keyup',function(event){
         if(event.keyCode === 9 || (event.keyCode == 9 && event.shiftKey)){
           return;
         }
           
        $(".chosen-container .chosen-results li").addClass("chosenfloat").removeClass("chosenDropWid");
       });       
      }
      
      //Bind Change Event
      fieldDOM.on('change',function(event){
        var val = $(this).val();
        val =  val === null ? "" : val ;
        if(fieldObject.type === MULTIPLE_SELECT_TYPE && typeof val.join == "function"){
          val = val.join(',');
        }
        parentFieldDOM.find('.js-errorLabel').addClass(dispNone);
        requiredFieldStore.remove(fieldObject);
        storeFieldChangeValue(fieldObject,val);
        
      });
      
    }
    
    /*
     * bindBoxCommonEvents : Click and blur Events
     * @param {type} fieldObject
     * @param {type} maxAllowedEle
     * @returns {undefined}
     */
    bindBoxCommonEvents = function(fieldObject,maxAllowedEle){
      var fieldDOM = $('#' + fieldObject.key.toLowerCase());
      var removeActiveFromMore = false;
      var activeClass = 'activeopt';
      
      var onClick = function(event){
        fieldDOM.find('.js-errorLabel').addClass(dispNone);
        if(event.target && event.target.tagName === "LI"){
          var val = event.target.getAttribute("value");
          
            var arrSelectedEle = fieldDOM.find('ul li.'+activeClass);
            for(var i=0;i<arrSelectedEle.length;i++){
              var ele = $(arrSelectedEle[i]); 
              ele.removeClass(activeClass);
            }
          
          if(val === "-1" && typeof maxAllowedEle != "undefined"){
            fieldDOM.find('.js-subBoxList').removeClass(dispNone);
            $(event.target).addClass(activeClass);
            return ;
          }
                    
          if($(event.target).hasClass('js-boxSubListOption') == false){
            fieldDOM.find('.js-subBoxList').addClass(dispNone);
            fieldDOM.find('ul li[value="-1"]').removeClass(activeClass);
          }else{
            fieldDOM.find('ul li[value="-1"]').addClass(activeClass);
          }
                  
          $(event.target).addClass(activeClass);
          fieldDOM.find('span').removeClass('color12').text($(event.target).text());
          storeFieldChangeValue(fieldObject,val);
          
          fieldDOM.trigger("box-change");
             }
        fieldDOM.find('.js-decVal').addClass(dispNone);
        fieldDOM.find('.boxType').removeClass(dispNone);
        //Show and hide Main Option 
        if(event.target && $(event.target).hasClass('js-decVal') === true || 
           event.target.id==fieldObject.key.toLowerCase()){
          fieldDOM.find('.js-decVal').addClass(dispNone);
          fieldDOM.find('.boxType').removeClass(dispNone);
          
          if( typeof maxAllowedEle != "undefined" && fieldDOM.find('ul li[value="-1"]').hasClass(activeClass)){
            fieldDOM.find('.js-subBoxList').removeClass(dispNone);
          }
          
          if(fieldDOM.find('ul li.activeopt').hasClass('js-boxSubListOption') == true){
            fieldDOM.find('ul li[value="-1"]').addClass('activeopt');
            fieldDOM.find('.js-subBoxList').removeClass(dispNone);
          }
        }
      }
      
      var onBlur  = function(event){
        var arrSelectedEle = fieldDOM.find('ul li.activeopt');
        if(arrSelectedEle.length === 1 && $(arrSelectedEle[0]).attr("value") === "-1"){
          fieldDOM.find('.js-errorLabel').removeClass(dispNone);
          stopEventPropagation(event);
          return false;
        }
        fieldDOM.find('.js-decVal').removeClass(dispNone);
        fieldDOM.find('.boxType').addClass(dispNone);
        fieldDOM.find('.js-subBoxList').addClass(dispNone);
      }
      
      var onKeydown  = function(event){
        
        var arrAllowedKeyCode = [13, 37, 38, 39, 40];

        //To handle left and right arrow key
        if (arrAllowedKeyCode.indexOf(event.keyCode) === -1)
        {
          return;
        }
        
        //Stop propagation of this event
        stopEventPropagation(event, 1);
        event.preventDefault()
        
        var yDir = 0, xDir = 0;
        if (event.keyCode === 38) {/*Up*/        yDir = -1;        }
        if (event.keyCode === 40) {/*Down*/      yDir = 1;         }
        if (event.keyCode === 37) {/*Left*/      xDir = -1;        }
        if (event.keyCode === 39) {/*Right*/     xDir = 1;         }
        
        if(event.keyCode === 13) { 
          onBlur(event);
          return; 
        }
        var selectedOptionArr = fieldDOM.find('ul li.activeopt');
        
        var currentID = -1;
        if(selectedOptionArr.length){
          currentID = parseInt($(selectedOptionArr[selectedOptionArr.length-1]).attr('class').split('option_')[1]);
        }
        
        var numCol = 1; var dir = 0;
        var oldCurrent = currentID;
        if (yDir && currentID >= 0) {
          currentID += numCol * yDir;
          dir = numCol * yDir;
        }

        if (xDir && currentID >= 0) {
          currentID += xDir;
          dir = xDir;
        }
        if (currentID < 0) {
          currentID = 0;
        }
       
        if(fieldDOM.find('ul li.option_'+currentID).length === 0)
          return ;
        
        fieldDOM.find('ul li.option_'+oldCurrent).removeClass('activeopt');
        fieldDOM.find('ul li.option_'+currentID).addClass('activeopt');      
        fieldDOM.find(' div.js-decVal').trigger('click');
        if(fieldDOM.find('ul li.option_'+currentID).hasClass('js-boxSubListOption') == true){
          fieldDOM.find('ul li[value="-1"]').trigger('click').focus();
        }
        fieldDOM.find('ul li.option_'+currentID).trigger('click').focus();
        
      }
      
      var siblingClick = function(event){
        if(event.target && event.target.tagName === "LI"){
          var val = event.target.getAttribute("value");
          
          if(val == "-1"){
            return ;
          }
          var marriedClick = (event.target.getAttribute("class").indexOf('sub_option_')===-1)?false:true;
          var totalClick = marriedClick ? false : true;
          
                    
          if(totalClick){
            
            var arrSelectedEle = fieldDOM.find('.js-boxContent li.'+activeClass);
            for(var i=0;i<arrSelectedEle.length;i++){
              var ele = $(arrSelectedEle[i]); 
              ele.removeClass(activeClass);
            }
            
            arrSelectedEle = fieldDOM.find('.js-subBoxList li.'+activeClass);
            for(var i=0;i<arrSelectedEle.length;i++){
              var ele = $(arrSelectedEle[i]); 
              ele.removeClass(activeClass);
            }
            
            if(val == "0"){
              fieldDOM.find('.js-subBoxList').addClass(dispNone);
            }
            else{
              fieldDOM.find('.js-subBoxList').removeClass(dispNone);
              //var totalWidth = fieldDOM.find(' ul').width();
              var oneCellWidth = fieldDOM.find(' ul li').width();
              var rightVal = val * oneCellWidth + 40;
              fieldDOM.find('.epdpos13').css('left',rightVal+"px");
              val = parseInt(val);
              var subOptionWidth = 162 +val + ((val+1) * oneCellWidth);
              fieldDOM.find('.sub-mainlist').css('width',subOptionWidth+'px');
              //Show and hide sub option////////////////////////
              var itr = 0;
              var loopItr = true;
              do{
               if( fieldDOM.find('ul li.sub_option_'+itr).length && itr <=val ){
                 fieldDOM.find('ul li.sub_option_'+itr).removeClass(dispNone);
               }
               if( fieldDOM.find('ul li.sub_option_'+itr).length && itr >val ){
                 fieldDOM.find('ul li.sub_option_'+itr).addClass(dispNone);
               }
               if(fieldDOM.find('ul li.sub_option_'+itr).length == 0){
                 loopItr = false;
               }
               ++itr;
              }while(loopItr);

              ///////////////////////////////////////////////////
            }
            if(editedFields.hasOwnProperty(fieldObject.sectionId) === false){
              editedFields[fieldObject.sectionId] = {};[]
            }
            if(fieldObject.key == "T_SISTER"){
              editedFields[fieldObject.sectionId]['M_SISTER'] = '';
            }
            if(fieldObject.key == "T_BROTHER"){
              editedFields[fieldObject.sectionId]['M_BROTHER'] = '';
            }
            storeFieldChangeValue(fieldObject,val);
            fieldDOM.find('span').removeClass('color12').text($(event.target).text());
          }      
          
          if(marriedClick){
            var arrSelectedEle = fieldDOM.find('.js-subBoxList li.'+activeClass);
            for(var i=0;i<arrSelectedEle.length;i++){
              var ele = $(arrSelectedEle[i]); 
              ele.removeClass(activeClass);
            }
            if(fieldObject.key == "T_SISTER"){
              editedFields[fieldObject.sectionId]['M_SISTER'] = val;
            }
            if(fieldObject.key == "T_BROTHER"){
              editedFields[fieldObject.sectionId]['M_BROTHER'] = val;
            }
            //storeFieldChangeValue(fieldObject,val);
            var text = fieldDOM.find('.js-boxContent li.'+activeClass).text();
            var siblingText = " sister(s)";
            if(fieldObject.key == 'T_BROTHER'){
              siblingText = " brother(s)"; 
            }
            fieldDOM.find('span').removeClass('color12').text(text + siblingText +' of which married ' + $(event.target).text()); 
            $(fieldDOM).trigger('boxBlur');
          }
          
          $(event.target).addClass(activeClass);
          return ;
        }
        
        //Show and hide Main Option 
        if(event.target && $(event.target).hasClass('js-decVal') === true || 
           event.target.id==fieldObject.key.toLowerCase()){
          fieldDOM.find('.js-decVal').addClass(dispNone);
          fieldDOM.find('.boxType').removeClass(dispNone);
          
          if( fieldDOM.find('.js-boxContent li.'+activeClass).length && fieldDOM.find('.js-boxContent li.'+activeClass).attr('value') != "0"){
            fieldDOM.find('.js-subBoxList').removeClass(dispNone);
          }
        }
      }
      
      var clickFn = onClick;
      
      if(sidesUIMap.indexOf(fieldObject.key) !== -1){
        clickFn = siblingClick
      }
      
      //focus
      fieldDOM.on('focus',clickFn);
      
      //Main Click Event
      fieldDOM.on('click',clickFn);
            
      //Main keydown Event
      fieldDOM.on('keydown',onKeydown);
      
      //MyBlur
      fieldDOM.on('boxBlur',onBlur);

    }
    /*
     * bindBoxCommonEvents : Click and blur Events
     * @param {type} fieldObject
     * @param {type} maxAllowedEle
     * @returns {undefined}
     */
    bindDateBoxCommonEvents = function(fieldObject,maxAllowedEle){
      var fieldDOM = $('#' + fieldObject.key.toLowerCase());
      var removeActiveFromMore = false;
      var activeClass = 'activeopt';
      var inputData = [];
      var onClick = function(event){
        fieldDOM.find('.js-errorLabel').addClass(dispNone);
        if(event.target && (event.target.tagName === "LI" || (event.target.tagName === "SPAN" && event.target.getAttribute("class") == "dropdown_span") || (event.target.tagName === "I" && (event.target.getAttribute("id") == "dayArrow2" || event.target.getAttribute("id") == "monthArrow2" || event.target.getAttribute("id") == "yearArrow2")))){
                if(event.target.tagName == "SPAN" || event.target.tagName == "I"){
                        var valID = event.target.getAttribute("id");
                        var val = $("#"+valID).parent().attr("value");
                }else{
                        var val = event.target.getAttribute("value");
                }
                hideShowList(event,fieldObject.key.toLowerCase());
                if(val == "D"){
                       highlightLI(fieldObject.key.toLowerCase(),"day","S");
                }else{
                        if(val == "M"){
                                highlightLI(fieldObject.key.toLowerCase(),"month","S");
                        
                        }else{
                                if(val == "Y"){
                                        highlightLI(fieldObject.key.toLowerCase(),"year","S");
                                }
                        }
                }
                fieldDOM.trigger("box-change");
        }
        fieldDOM.find('.js-decVal').addClass(dispNone);
        fieldDOM.find('.boxType').removeClass(dispNone);
        //Show and hide Main Option 
        if(event.target && $(event.target).hasClass('js-decVal') === true || 
           event.target.id==fieldObject.key.toLowerCase()){
          fieldDOM.find('.js-decVal').addClass(dispNone);
          fieldDOM.find('.boxType').removeClass(dispNone);
        }
      }
        var createDateList = function () {
                dateHtml = generateList(2, 31, "date", 1);
                $("#daysub").html(dateHtml);
        }
        var createMonthList = function () {
                monthHtml = generateList(2, 12, "month", "Jan");
                $("#monthsub").html(monthHtml);
        }
        var createYearList = function () {
                var d = new Date();
                var n = d.getFullYear();
                var sub = 19;
                var subStart = 18;
                if(editAppObject[BASIC]['GENDER'].value == "M"){
                        sub = 22;
                        subStart = 21;
                }
                yearHtml = generateList(n - sub, n - 70, "year", n - subStart);
                $("#yearsub").html(yearHtml);
        }
        var generateList = function(l, h, c, d){
                var dropHtml = '<li id="{{cusId}}">{{customValue}}</li>{{newLi}}';
                dropHtml = dropHtml.replace(/{{customValue}}/g, d);
                if (c == "month")
                  dropHtml = dropHtml.replace(/{{cusId}}/g, c + "li1");
                else
                  dropHtml = dropHtml.replace(/{{cusId}}/g, c + "li" + d);
          
                var firstLi = '<li id="{{cusId}}">{{customValue}}</li>{{newLi}}';;
                //for year the list is in reverse order
                if (c == "year") {
                  for (i = l; i >= h; i--) {
                    tempHtml = firstLi.replace(/{{customValue}}/g, i);
                    tempHtml = tempHtml.replace(/{{cusId}}/g, c + "li" + i);
                    dropHtml = dropHtml.replace(/{{newLi}}/g, tempHtml);
                  }
                }
                else {
                  for (i = l; i <= h; i++) {
                    if (c == "month")
                      tempHtml = firstLi.replace(/{{customValue}}/g, dataMonthArray[i]);
                    else
                      tempHtml = firstLi.replace(/{{customValue}}/g, i);
                    tempHtml = tempHtml.replace(/{{cusId}}/g, c + "li" + i);
                    dropHtml = dropHtml.replace(/{{newLi}}/g, tempHtml);
                  }
                }
                dropHtml = dropHtml.replace(/{{newLi}}/g, "");
                return(dropHtml);
      }
      var onBlur  = function(event){
              if(callBlur == 0){
                      return true;
              }
        var dayVal = parseInt($("#day_value").attr("rel"));
        var monthVal = $("#month_value").attr("rel");
        var yearVal = parseInt($("#year_value").attr("rel"));
        var dateSelected = "";
        if(!isNaN(dayVal) && typeof dayVal != undefined && dayVal != "Day" && isNaN(monthVal) && typeof monthVal != undefined && monthVal != "Month" && !isNaN(yearVal) && typeof yearVal != undefined && yearVal != "Year" ){
                var dateString = "";
                dateString += dayVal;
                if(dayVal == 2 || dayVal == 22){
                        dateString +="nd ";
                }else{
                        if(dayVal == 1 || dayVal == 21 || dayVal == 31){
                                dateString +="st ";
                        }else{
                                if(dayVal == 3 || dayVal == 23){
                                        dateString +="rd ";
                                }else{
                                        dateString +="th ";
                                }
                        }
                }
                
                dateString += " "+monthVal.substring(0,3)+" "+yearVal;
                
                var monthIntVal =""
                for (i = 1; i <= 12; i++) {
                        if(dataMonthArray[i] == monthVal){
                                if(i<10){
                                        monthIntVal = "0"+i;
                                }else{
                                        monthIntVal = i;
                                }
                        }
                }
                if(dayVal<10){
                        dayVal = "0"+dayVal;
                }
                dateSelected = yearVal+"-"+monthIntVal+"-"+dayVal;
                if(fieldObject.value != dateSelected){
                        storeFieldChangeValue(fieldObject,dateSelected);
                        var bInValidDate = false;
                        var correspondingDate = new Date(yearVal,parseInt(monthIntVal)-1,dayVal);
                        if(correspondingDate.getDate() !== parseInt(dayVal))
                                bInValidDate = true;

                        var M = parseInt(correspondingDate.getMonth())+1;
                        if(M !== parseInt(monthIntVal))
                                bInValidDate = true;

                        if(correspondingDate.getFullYear() !== parseInt(yearVal))
                                bInValidDate = true;
                        
                        if(bInValidDate){
                                var dtOfBirthObj = editAppObject[CRITICAL]["DTOFBIRTH"];
                                var errorMsg = "Please provide a valid date of birth";
                                $('#'+fieldObject.key.toLowerCase()+'Parent').find('.js-errorLabel').text(errorMsg);
                                $('#'+fieldObject.key.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
                                requiredFieldStore.add(dtOfBirthObj);       
                        }else{
                                var dtOfBirthObj = editAppObject[CRITICAL]["DTOFBIRTH"];
                                $('#'+fieldObject.key.toLowerCase()+'Parent').find('.js-errorLabel').text("");
                                $('#'+fieldObject.key.toLowerCase()+'Parent').find('.js-errorLabel').addClass(dispNone);
                                requiredFieldStore.remove(dtOfBirthObj);  
                        }
                        
                }else{
                        if(editedFields.hasOwnProperty(CRITICAL) === true && editedFields[CRITICAL].hasOwnProperty(fieldObject.key.toUpperCase()) === true){
                                delete editedFields[CRITICAL][fieldObject.key.toUpperCase()];
                        }
                }
                fieldDOM.find('span.js-decVal').html(dateString);
        }
                fieldDOM.find('.js-decVal').removeClass(dispNone);
                fieldDOM.find('.boxType').addClass(dispNone);
                fieldDOM.find('.js-subBoxList').addClass(dispNone);
                $("#daysub").parent().attr("style","display:none");
                $("#monthsub").parent().attr("style","display:none");
                $("#yearsub").parent().attr("style","display:none");
      }
      
      var onClick2 = function(event){
                highlightLI(fieldObject.key.toLowerCase(),"day","S");
                fieldDOM.find('.js-decVal').addClass(dispNone);
                fieldDOM.find('.boxType').removeClass(dispNone);
                fieldDOM.find('.boxType').removeClass(dispNone);
      }
      var clickFn = onClick;
      var clickFn2 = onClick2;
      
      //focus
      fieldDOM.find('div.js-decVal').on('focus',clickFn2);
      fieldDOM.find('div.js-decVal').on('click',clickFn2);
      fieldDOM.find('.js-boxContent .boxType').on('focus',clickFn);
      
      //Main Click Event
      fieldDOM.find('.js-boxContent .boxType').on('click',clickFn);
      
      //MyBlur
      fieldDOM.on('blur',onBlur);
        createDateList();
        createMonthList();
        createYearList();
    }
    
    /*
     * 
     * bindOpenTextCommonEvents
     * @param {type} fieldObject
     * @returns {String}
     */
    bindOpenTextCommonEvents = function(fieldObject){
      var fieldID = '#'+fieldObject.key.toLowerCase();
      
      var onOpenTextChange = function(event){
        var value = $(this).val().trim();
        var orgValue = $(this).attr("value");
        if(typeof orgValue != "undefined"){
          orgValue = orgValue.trim();
        }
        else{
          orgValue = "";
        }
        var parentID = '#'+$(this).attr("id")+'Parent';
        var parentLabelID = '#'+$(this).attr("id")+'LabelParent';
        
        if(event.type != "storeOnly"){
          $(parentID).find('.js-errorLabel').addClass(dispNone);
        }
        storeFieldChangeValue(fieldObject,value);
        
        if(value.length === 0) {
          if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
        }
        else if(value != orgValue){
          if(debugInfo)  $(parentID).find('.js-undSecMsg').removeClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
        }
        else{/*Same Value*/
          if(debugInfo) $(parentID).find('.js-undSecMsg').removeClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
          if(fieldObject.isUnderScreen == false) {
            if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
          }
        }
      };
      
      //Bind Change Event
      $(fieldID).on('change',onOpenTextChange);
      //On Blur
      $(fieldID).on('blur',onOpenTextChange);
      //Store Underscreening event 
      $(fieldID).on('storeOnly',onOpenTextChange);
    }
    
    /*
     * bindAutoSuggestCommonEvents
     * @param {type} fieldObject
     * @returns {undefined}
     */
    bindAutoSuggestCommonEvents = function(fieldObject){
      
      var fieldID = '#'+fieldObject.key.toLowerCase();
      var fieldDOM = $('#'+fieldObject.key.toLowerCase()+'Parent');
      
      var handleKeyBoard = function (event) {//Function to handle Keyboard navigation

        var arrAllowedKeyCode = [13, 38, 40];

        if (arrAllowedKeyCode.indexOf(event.keyCode) === -1)
        {
          return;
        }
        var selfParentID = '#' + fieldObject.key.toLowerCase() + 'Parent';
        
        //Stop propagation of this event
        stopEventPropagation(event, 1);
        event.preventDefault();

        var yDir = 0, xDir = 0;
        if (event.keyCode === 38) {/*Up*/
          yDir = -1;
        }
        if (event.keyCode === 40) {/*Down*/
          yDir = 1;
        }
        if (event.keyCode === 37) {/*Left*/
          xDir = -1;
        }
        if (event.keyCode === 39) {/*Right*/
          xDir = 1;
        }

        var currentID = -1, dir = 0;
        var multiUls = '#' + fieldObject.key.toLowerCase() + 'Parent .js-autoSuggestOption';

        var selectedTab = $(multiUls).find(' li.activeopt');
        if (selectedTab.length)
        {
          selectedTab = (typeof selectedTab.id == ("undefined")) ? selectedTab[0] : selectedTab;
          currentID = selectedTab.id.split('_')[selectedTab.id.split('_').length-1];
        }
        
        if (event.keyCode === 13 && currentID>=0) {//handle Enter
          var newID = "#" + fieldObject.key.toLowerCase() + "_" + currentID;
          $(newID).trigger('click');
          return;
        }
        
        //If last selected is out of focus then find in view element as per keyCode
        var currTop = 0;
        if (currentID != -1)
          currTop = $('#' + fieldObject.key.toLowerCase() + "_" + currentID).position().top;

        if (currTop < 0 || currTop > 180) {
          var checkScrollId = -1;
          var arr = $(multiUls + ' li');
          for (i = 0; i < arr.length; i++) {
            var id = arr[i].id;
            var top1 = $('#' + id).position().top;

            if (top1 > 0 && checkScrollId == -1 && yDir === 1/*Down Key*/) {
              checkScrollId = arr[i].id.split('_')[1];
              --checkScrollId;
              break;
            }

            if (top1 > 170 && checkScrollId == -1 && yDir === -1/*Up Key*/) {
              checkScrollId = arr[i].id.split('_')[1];
              ++checkScrollId;
              break;
            }
          }
          $('#' + fieldObject.key.toLowerCase() + "_" + currentID).removeClass('activeopt');
          currentID = checkScrollId;
        }

        ///////////////////////////////////////////
        var numCol = 1;

        numCol = parseInt(numCol);
        currentID = parseInt(currentID);

        var oldCurrent = currentID;
        if (yDir && currentID >= 0) {
          currentID += numCol * yDir;
          dir = numCol * yDir;
        }

        if (xDir && currentID >= 0) {
          currentID += xDir;
          dir = xDir;
        }

        if (currentID < 0) {
          currentID = 0;
        }

        var newID = "#" + fieldObject.key.toLowerCase() + "_" + currentID;
        var oldID = "#" + fieldObject.key.toLowerCase() + "_" + oldCurrent;

        if ($(newID).length == 0 || false === isDomElementVisible(newID)) {
          return;
        }
        if(event.keyCode !== 13){
          $(oldID).removeClass('activeopt');
          $(newID).addClass('activeopt');
        }
        //If First Selection then scroll to top
        if (currentID === 0) {
          $(multiUls).scrollTop(0);
        }

        if (($(newID).position().top) > 179) {
          $(multiUls).parent().scrollTop($(multiUls).parent().scrollTop() + 180);
        }

        if (($(newID).position().top) < 0) {
          $(multiUls).parent().scrollTop($(multiUls).parent().scrollTop() - 180);
        }
      }
      
      var onAutoSuggest = function(event){
        
        var arrAllowedKeyCode = [13, 38, 40];
        if (arrAllowedKeyCode.indexOf(event.keyCode) !== -1)
        {
          handleKeyBoard(event);
          event.preventDefault()
          return;
        }
        //Do White Listing
        if(false == whiteListingKeys(event,"onlyChars")){
          return ;
        }
        
        if((event.ctrlKey && event.which == 86) || (event.shiftKey && event.which == 45)){//Paste
          var regex = /[^a-zA-Z'.() ]+/g; 
          var value = $(this).val();
          value = value.trim().replace(regex,"");
          if(value != $(this).val().trim())
            $(this).val(value);
        }
        
        var fieldID = '#'+fieldObject.key.toLowerCase();
        setTimeout(function(){
          var currentFieldVal = $(fieldID).val().trim().toLowerCase() ;//+ String.fromCharCode(event.keyCode).toLowerCase();
          storeFieldChangeValue(fieldObject,$(fieldID).val().trim());

          var value = currentFieldVal;
          var orgValue = $(fieldID).attr("value");
          if(typeof orgValue != "undefined"){
            orgValue = orgValue.trim();
          }
          else{
            orgValue = "";
          }
          
          var parentID = fieldID+'Parent';
          var parentLabelID = fieldID+'LabelParent';
          
          if(value.length === 0) {
            if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
          }
          else if(value != orgValue){
            if(debugInfo)  $(parentID).find('.js-undSecMsg').removeClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
          }
          else{/*Same Value*/
            if(debugInfo) $(parentID).find('.js-undSecMsg').removeClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
            if(fieldObject.isUnderScreen == false) {
              if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
              $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
            }
          }
          
          
          if(currentFieldVal.length < 2){
            $(selfParentID).find('.js-autoSuggest').addClass(dispNone);
            return;
          }

          var queryData = {q:currentFieldVal,type:fieldID.split('#')[1]}
          if(queryData.type == "subcaste"){
            queryData.caste = editAppObject[BASIC]["CASTE"].value;
          }

          if(queryData.type == "gothra_maternal"){
            queryData.type = "gothra";
          }
           
          if(queryData.type == "pg_college" || queryData.type == "college"){
            queryData.type = "collg";
          }
          
          if(queryData.type == "company_name"){
            queryData.type = "org";
          }
          
          var selfParentID = fieldID+'Parent';
          var request = getAutoSuggest(queryData);

          if(autoSuggestRequest.hasOwnProperty(fieldObject.key) === true){
            autoSuggestRequest[fieldObject.key].abort();
          }

          autoSuggestRequest[fieldObject.key] = request;

          request.success(function(data,textStatus,jqXHR){
          
            if($(document.activeElement).attr('id') != fieldObject.key.toLowerCase()){
              $(selfParentID).find('.js-autoSuggest').addClass(dispNone);
              return;
            }
            
            if(data.length == 0){
              $(selfParentID).find('.js-autoSuggest').addClass(dispNone);
              return;
            }        
            var response = data.split("\n");

            var optionString = "";

            $(selfParentID).find('.js-autoSuggestOption').html("");
            var k =0;
            for(var i=0;i<response.length;i++){
              optionString += "<li id=" +fieldObject.key.toLowerCase()+'_'+k+">"+response[i]+"</li>";++k;
            }

            if(optionString.length){
              $(selfParentID).find('.js-autoSuggestOption').html(optionString);
              $(selfParentID).find('.js-autoSuggest').removeClass(dispNone);
            }else{
              $(selfParentID).find('.js-autoSuggest').addClass(dispNone);
            }

            if(response.length<5){
              $(selfParentID).find('.scrolla').removeClass("reg-hgt200");
            }
            else{
              $(selfParentID).find('.scrolla').addClass("reg-hgt200");
            }
          });
        },10)
        
       
      }
      
      var onAbort = function(){
        if(autoSuggestRequest.hasOwnProperty(fieldObject.key) === true){
          autoSuggestRequest[fieldObject.key].abort();
          delete autoSuggestRequest[fieldObject.key];
        }
      }
      
      var optionClick = function(event){
        if(event.target && event.target.tagName === "LI"){
          var value = $(event.target).text().trim();
          $(fieldID).val(value);
          storeFieldChangeValue(fieldObject,value);
          $(fieldID).parent().find('.js-autoSuggest').addClass(dispNone);
          
          var currentFieldVal = $(fieldID).val().trim().toLowerCase() ;
          value = currentFieldVal;
          var orgValue = $(fieldID).attr("value");
          if(typeof orgValue != "undefined"){
            orgValue = orgValue.trim();
          }
          else{
            orgValue = "";
          }
          
          var parentID = fieldID+'Parent';
          var parentLabelID = fieldID+'LabelParent';
          
          if(value.length === 0) {
            if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
          }
          else if(value != orgValue){
            if(debugInfo)  $(parentID).find('.js-undSecMsg').removeClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
          }
          else{/*Same Value*/
            if(debugInfo) $(parentID).find('.js-undSecMsg').removeClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
            if(fieldObject.isUnderScreen == false) {
              if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
              $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
            }
          }
        }
      }
      //Bind Change Event
      $(fieldID).on('keydown',onAutoSuggest);
            
      //On AutoSuggest Click
      fieldDOM.find('.js-autoSuggest').on('click',optionClick);
      
      //On Focus  
//      fieldDOM.find('.js-autoSuggest').on('focus',onAutoSuggest);
      
      //Abort Request
      fieldDOM.find('.js-autoSuggest').on('abort-request',onAbort);
    }
    
    /*
     * 
     * bindTextAreaCommonEvents
     * @param {type} fieldObject
     * @returns {String}
     */
    bindTextAreaCommonEvents = function(fieldObject){
      var fieldID = '#'+fieldObject.key.toLowerCase();
      
      var onTextAreaChange = function(event){
        var value = $(this).val().trim();
        var orgValue = $(this).attr("value");
        if(typeof orgValue != "undefined"){
          orgValue = orgValue.trim();
        }
        else{
          orgValue = "";
        }
        var parentID = '#'+$(this).attr("id")+'Parent';
        var parentLabelID = '#'+$(this).attr("id")+'LabelParent';
        
        storeFieldChangeValue(fieldObject,value);
        
        if(value.length === 0) {
          if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
        }
        else if(value != orgValue){
          if(debugInfo)  $(parentID).find('.js-undSecMsg').removeClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
        }
        else{/*Same Value*/
          if(debugInfo) $(parentID).find('.js-undSecMsg').removeClass(dispNone);
          $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
          if(fieldObject.isUnderScreen == false) {
            if(debugInfo) $(parentID).find('.js-undSecMsg').removeClass(dispNone);
            $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
          }
        }
      };
      
      //Bind Change Event
      $(fieldID).on('change',onTextAreaChange);
      //On Blur
      $(fieldID).on('blur',onTextAreaChange);
    }
    
    /*
     * bindPhoneCommonEvents
     * @param {type} fieldObject
     * @returns {undefined}
     */
    bindPhoneCommonEvents = function(fieldObject){
            
      var regExIndian=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([789]{1})([0-9]{9})$/;
      var regExIndianLandline=/^[0-9]\d{2,4}[-. ]?\d{6,8}$/;
      var regExInternational=/^\+(?:[0-9][-. ]? ?){6,15}[0-9]$/;
      
      var phonePatternIndia = /^([7-9]{1}[0-9]{9})$/;
      var phonePatternOther = /^([1-9]{1}[0-9]{5,13})$/;
      
      var isd_regex = /^[+]?[0-9]+$/;
      
      var isdFieldID      = '#'+fieldObject.key.toLowerCase()+'-isd';
      var stdFieldID      = '#'+fieldObject.key.toLowerCase()+'-std';
      var mobileFieldID   = '#'+fieldObject.key.toLowerCase()+'-mobile';
      
      if(fieldObject.key == "PHONE_RES"){
        mobileFieldID = '#'+fieldObject.key.toLowerCase()+'-landline';
      }
      
      var validClass = 'edpbrd4';
      var invalidClass = 'brdr-1';
      
      var getSanitizedVal = function(val){
        if(val === null || typeof(val) === "undefined")
          return "";
        
        return val.trim().replace(/^0+/,"").replace(/ /g,"");
      }
      
      var validateISD = function(event,fieldParentID){
        var fieldID = '#'+fieldParentID;
        var fieldKey = fieldParentID.split('Parent')[0].split('-')[0];
        var value = $(fieldID).val();
        
        $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').addClass(dispNone);
        $(fieldID).parent().parent().removeClass(invalidClass).addClass(validClass);
        
        if(!isd_regex.test(value)){
          var errorMsg =  value.length ? errorMap['ISD_INVALID'] : errorMap['ISD_REQUIRED'];
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').text(errorMsg);
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
          $(fieldID).parent().parent().addClass(invalidClass).removeClass(validClass);
          return ;
        }
        
        if(editedFields.hasOwnProperty(CONTACT) === false){
          editedFields[CONTACT] = {};
        }
        
        if(editedFields[CONTACT].hasOwnProperty(fieldKey.toUpperCase()) === false){
          editedFields[CONTACT][fieldKey.toUpperCase()] = {};
        }
        
        if(value.indexOf('+') != -1){
          value = value.trim().split('+')[1];
        }        
        
        //Update Rest of ISD Values also
        var arrPhone = ['PHONE_RES','PHONE_MOB','ALT_MOBILE'];
        for(var i=0;i<arrPhone.length;i++){
          //Update Value
          var mobileKey = '-mobile';
          if(fieldKey.toUpperCase() == "PHONE_RES"){
            mobileKey = '-landline';
          }
          
          var fieldID = arrPhone[i].toLowerCase();
          
          if(fieldKey.toUpperCase() != arrPhone[i])
            $('#'+fieldID+'-isd').val(value);      
          
          //Update mobile maxlength attribute
          var valueLength = 10;
          if(isdIndianAllowedCodes.indexOf(value) !== -1){
            $('#'+fieldID+mobileKey).attr('myMaxLength','10');
          }
          else{
            $('#'+fieldID+mobileKey).attr('myMaxLength',INT_PHONE_MAX_LEN.toString());
          }

//          var mobileValue = getSanitizedVal($('#'+fieldKey+mobileKey).val());
//          $('#'+fieldKey+mobileKey).val(mobileValue.substring(0,valueLength));
          validatePhone(fieldKey.toUpperCase());
          
          //store in edited fields
          if(editedFields[CONTACT].hasOwnProperty(arrPhone[i]) === false){
            editedFields[CONTACT][arrPhone[i]] = {};
          }
          
          editedFields[CONTACT][arrPhone[i]]['isd'] = value;
        }
        
      }
      
      var validateSTD = function(event,fieldParentID){
        var fieldID = '#'+fieldParentID;
        var fieldKey = fieldParentID.split('Parent')[0].split('-')[0];
        var value = $(fieldID).val();
        
        if(value.length){
          value = getSanitizedVal(value);
        }
        
        if(editedFields.hasOwnProperty(CONTACT) === false){
          editedFields[CONTACT] = {};
        }
        
        if(editedFields[CONTACT].hasOwnProperty(fieldKey.toUpperCase()) === false){
          editedFields[CONTACT][fieldKey.toUpperCase()] = {};
        }    
        editedFields[CONTACT][fieldKey.toUpperCase()]['std'] = value;
        
        $(fieldID).parent().parent().find('.js-errorLabel').addClass(dispNone);
        $(fieldID).parent().parent().removeClass(invalidClass).addClass(validClass);
        
        var valid = true;
        
        if(value.length == 0){
          valid = true;
        }
       
        var landlineLength = getSanitizedVal(value);
        var isdVal = getSanitizedVal($('#'+fieldKey+'-isd').val());;
        var fieldMaxLength = INT_PHONE_MAX_LEN;
        if(isdIndianAllowedCodes.indexOf(isdVal) !== -1){
          fieldMaxLength = 10;
        }

        landlineLength = fieldMaxLength - parseInt(landlineLength.length);
        $('#'+fieldKey+'-landline').attr('myMaxLength',landlineLength);
//          var landLineValue = getSanitizedVal($('#'+fieldKey+'-landline').val());
//          $('#'+fieldKey+'-landline').val(landLineValue.substring(0,landlineLength));
       
        if(valid == false){
          $(fieldID).parent().parent().find('.js-errorLabel').removeClass(dispNone);
          $(fieldID).parent().parent().find('.js-errorLabel').text(errorMap['STD_INVALID']);
          $(fieldID).parent().parent().addClass(invalidClass).removeClass(validClass);
          return ;
        }
        
        validatePhone(fieldKey.toUpperCase());
      }
      
      var validateMobile = function(event,fieldParentID){
        var fieldID = '#'+fieldParentID;
        var fieldKey = fieldParentID.split('Parent')[0].split('-')[0];
        var storeKey = fieldParentID.split('Parent')[0].split('-')[1];
        var value = $(fieldID).val();
        value = getSanitizedVal(value);
        
        if(editedFields.hasOwnProperty(CONTACT) === false){
          editedFields[CONTACT] = {};
        }

        if(editedFields[CONTACT].hasOwnProperty(fieldKey.toUpperCase()) === false){
          editedFields[CONTACT][fieldKey.toUpperCase()] = {};
        }
        editedFields[CONTACT][fieldKey.toUpperCase()][storeKey] = value;
        
        $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').addClass(dispNone);
        $(fieldID).parent().parent().removeClass(invalidClass).addClass(validClass);
        
        var valid = false;

        if(value.length < $(fieldID).attr('myMaxLength')){
          valid = false;
        }else{
          valid = true;
        }
        var isdVal = getSanitizedVal($('#'+fieldKey+'-isd').val());
        var stdField = $('#'+fieldKey+'-std');
       
        if($(stdField).length === 0 && isdIndianAllowedCodes.indexOf(isdVal) === -1 && parseInt(value.length)  >= 6 && parseInt(value.length)  <= INT_PHONE_MAX_LEN)
        {//International Number isdVal + Phone Number >= 8 || <=INT_PHONE_MAX_LEN
          valid = true;
        }
        
        if($(stdField).length === 1 && isdIndianAllowedCodes.indexOf(isdVal) === -1 && (parseInt(value.length) + (getSanitizedVal($(stdField).val()).length )) >= 6 && (parseInt(value.length) + (getSanitizedVal($(stdField).val()).length )) <=INT_PHONE_MAX_LEN ){//Internation Landline No
          valid = true;
        }
        
        if($(stdField).length === 1 && isdIndianAllowedCodes.indexOf(isdVal) !== -1 && (parseInt(value.length) + (getSanitizedVal($(stdField).val()).length )) == 10 ){//Indian Landline No
          valid = true;
        }
        
        if(value.length == 0){
          valid = true;
        }
        
        if(valid == false){
          $(fieldID).parent().parent().find('.js-errorLabel').text(errorMap[storeKey.toUpperCase()+'_INVALID']);
          $(fieldID).parent().parent().find('.js-errorLabel').removeClass(dispNone);
          $(fieldID).parent().parent().addClass(invalidClass).removeClass(validClass);
          return ;
        }
        
        validatePhone(fieldKey.toUpperCase());
      }
      
      var validatePhone = function(fieldKey){
        
        var isdFieldID      = '#'+fieldKey.toLowerCase()+'-isd';
        var stdFieldID      = '#'+fieldKey.toLowerCase()+'-std';
        var mobileFieldID   = '#'+fieldKey.toLowerCase()+'-mobile';
        var fieldNameLabel  = "Mobile";
        
        if(fieldKey == "PHONE_RES"){
          mobileFieldID   = '#'+fieldKey.toLowerCase()+'-landline';
          fieldNameLabel = "Landline";
        }
        
        var mobileFieldVal = $(mobileFieldID).val();
        var isdFieldVal = $(isdFieldID).val();
        var stdFieldVal = $(stdFieldID).val();
        
        if(mobileFieldVal.length){
          mobileFieldVal = getSanitizedVal(mobileFieldVal);
        }
        
        if(isdFieldVal.length){
            isdFieldVal = getSanitizedVal(isdFieldVal);
        }
        
        if($(stdFieldID).length && stdFieldVal.length){
          stdFieldVal = getSanitizedVal(stdFieldVal);
        }
        
        
        if(isdFieldVal.length === 0){
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').text(errorMap['ISD_REQUIRED']);
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
          $('#'+fieldKey.toLowerCase()+'-isd').parent().parent().removeClass(validClass).addClass(invalidClass);
          return ;
        }
        
        if( mobileFieldVal.length && $(stdFieldID).length && stdFieldVal.length ==0 ){
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').text(errorMap['STD_REQUIRED']);
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
          $('#'+fieldKey.toLowerCase()+'-isd').parent().parent().removeClass(validClass).addClass(invalidClass);
          return ;
        }
        else if(mobileFieldVal.length==0 && $(stdFieldID).length && stdFieldVal.length ==0)
        {
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').addClass(dispNone);
          $('#'+fieldKey.toLowerCase()+'-isd').parent().parent().addClass(validClass).removeClass(invalidClass);
          return ;
        }
        else if(mobileFieldVal.length==0)
        {
          checkForEmptyFields();
          return ;
        }
        
        
        ///////////////////RegEx Test
        //var completePhoneVal = '+'+isdFieldVal+mobileFieldVal;
        //var regEx = regExIndian;
        var completePhoneVal = mobileFieldVal;
        var regEx = phonePatternIndia;
        
        if(isdIndianAllowedCodes.indexOf(isdFieldVal) === -1 ){
          regEx = phonePatternOther;
        }
          
        if($(stdFieldID).length){
         //completePhoneVal = '+'+isdFieldVal+stdFieldVal+mobileFieldVal; 
         completePhoneVal = stdFieldVal+mobileFieldVal; 
         
         if(isdIndianAllowedCodes.indexOf(isdFieldVal) !== -1 ){
            regEx = regExIndianLandline;
            completePhoneVal = stdFieldVal+mobileFieldVal;
         }
        }
        
        if( false === regEx.test(completePhoneVal)){
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').text('Invalid');
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
          $('#'+fieldKey.toLowerCase()+'-isd').parent().parent().removeClass(validClass).addClass(invalidClass);
        }
        
        //Check if alternate mobile number is equal to mobile no or not?
        var otherFieldKey = "ALT_MOBILE";
        if(fieldKey == "ALT_MOBILE"){
          otherFieldKey = "PHONE_MOB";
        }
        var otherFieldVal = getSanitizedVal($('#'+otherFieldKey.toLowerCase()+'-mobile').val());
        if(mobileFieldVal.length && otherFieldVal.length && mobileFieldVal == otherFieldVal){
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').text('Can\'t be same');
          $('#'+fieldKey.toLowerCase()+'Parent').find('.js-errorLabel').removeClass(dispNone);
        }
      }
      
      var checkForEmptyFields = function(){//One of them must be provided
        var arrPhoneField = ['PHONE_RES','PHONE_MOB'];
        var emptyField = true;
        
        for(var i = 0;i<arrPhoneField.length;i++){
          var isdFieldID      = '#'+arrPhoneField[i].toLowerCase()+'-isd';
          var stdFieldID      = '#'+arrPhoneField[i].toLowerCase()+'-std';
          var mobileFieldID   = '#'+arrPhoneField[i].toLowerCase()+'-mobile';

          if(arrPhoneField[i] == "PHONE_RES"){
            mobileFieldID = '#'+arrPhoneField[i].toLowerCase()+'-landline';
          }
          
          var mobileFieldVal = $(mobileFieldID).val();
          var isdFieldVal = $(isdFieldID).val();
          var stdFieldVal = $(stdFieldID).val();

          if(mobileFieldVal.length){
            mobileFieldVal = getSanitizedVal(mobileFieldVal);
          }

          if(isdFieldVal.length){
              isdFieldVal = getSanitizedVal(isdFieldVal);
          }

          if($(stdFieldID).length && stdFieldVal.length){
            stdFieldVal = getSanitizedVal(stdFieldVal);
          }
          if(mobileFieldVal.length ){
            emptyField = false;
          }
        }
        
        if(emptyField){
          $('#phone_mobParent').find('.js-errorLabel').text("Required").removeClass(dispNone);
          $('#phone_mob-isd').parent().parent().removeClass(validClass).addClass(invalidClass);
        }
      }
      
      var onSaveCheck = function(){
         var arrPhoneField = ['PHONE_RES','PHONE_MOB','ALT_MOBILE'];
        var emptyField = true;
        
        for(var i = 0;i<arrPhoneField.length;i++){
          var isdFieldID      = arrPhoneField[i].toLowerCase()+'-isd';
          var stdFieldID      = arrPhoneField[i].toLowerCase()+'-std';
          var mobileFieldID   = arrPhoneField[i].toLowerCase()+'-mobile';
           if(arrPhoneField[i] == "PHONE_RES"){
            mobileFieldID = arrPhoneField[i].toLowerCase()+'-landline';
          }
          
          validateISD(null,isdFieldID);

          if($('#'+stdFieldID).length)
            validateSTD(null,stdFieldID);

          validateMobile(null,mobileFieldID);
        }  
      }
      ///////////////////////Binding of events///////////////////////////
      
      //ISD Validation
      $(isdFieldID).bind('keydown',function(event){
        var arrAllowedKeyCode = ["187"];//"107",
        if(false == whiteListingKeys(event,"onlyNums") ){
          return false;
        }
        var self = this;
        setTimeout(function(){
          validateISD(event,$(self).attr('id'));
        },0);
        
      });
      //Bind Sanitize on Number
      $(isdFieldID).on('input propertychange',function(event){
        whiteListingKeys(event,"sanitizeNumber");
        var self = this;
        setTimeout(function(){
          validateISD(event,$(self).attr('id'));
        },0);
      });
      //STD Validation
      $(stdFieldID).bind('keydown',function(event){
        if(false == whiteListingKeys(event,"onlyNums")){
          return false;
        }
        var self = this;
        setTimeout(function(){
          validateSTD(event,$(self).attr('id'));
        },0);
        
      });
      //Bind Sanitize on Number
      $(stdFieldID).on('input propertychange',function(event){
        whiteListingKeys(event,"sanitizeNumber",6,true);
        var self = this;
        setTimeout(function(){
          validateSTD(event,$(self).attr('id'));
        },0);
      });
      //Mobile Validationx
      $(mobileFieldID).bind('keydown',function(event){
        if(false == whiteListingKeys(event,"onlyNums",$(event.target).val(),$(event.target).attr('myMaxLength')) ){
          return false;
        }
        var self = this;
        setTimeout(function(){
          validateMobile(event,$(self).attr('id'));
        },0);
        
      });
      //Bind Sanitize on Number
      $(mobileFieldID).on('input propertychange',function(event){
        var limitDigit = $(this).attr('myMaxLength');
        whiteListingKeys(event,"sanitizeNumber",limitDigit,true);
        var self = this;
        setTimeout(function(){
          validateMobile(event,$(self).attr('id'));
        },0);
      });
      //onSave Bind
      $('#'+fieldObject.key.toLowerCase()+'Parent').on('onSave',onSaveCheck);
    }
    
    /*
     * prepareOptionDropDown
     * @param {type} data (JSON Data, must be object type)
     * @param {type} fieldObject
     * @returns {String}
     */
    prepareOptionDropDown = function(data,fieldObject){
      var optionString = "<option value=\"\"></option>" ;
      
      var attrOption = " ";
      var bcloseTagOpt = false;
      
      var cssClassOnOption = "textTru chosenDropWid";/*Class on option DOM*/
      var optionGroupClassOnOption = "brdrb-4 fullwidImp";/*Class on option group DOM*/
      try{
                  
        //Loop the data section 
        $.each(data,function(key1,data1)
        {
          $.each(data1,function(key2,data2)
          {
            $.each(data2,function(value,label)
            { 
              if( value == fieldObject.value || 
                  (typeof fieldObject.value == "string" && 
                   fieldObject.value.split(",").indexOf(value) !== -1
                  )/*For Mulitple*/
                 )
              {
                attrOption+=" selected ";
              }
              if(value == "-1"){
                if(bcloseTagOpt) optionString+='</optgroup>';
                bcloseTagOpt = true;
                optionString+='<optgroup class="'+optionGroupClassOnOption+'" value='+value+' label='+label+'>';
              }
              else{
                optionString+='<option '+attrOption+'class="'+cssClassOnOption+'" value='+value+'>'+label+'</option>';
                attrOption = " ";
              }
            });
          });
        });
      }
      catch(e){
        optionString = "";
        console.log(e);
      }
      return optionString;
    }
    
    /*
     * prepareBoxOptionDropDown
     * @param {type} data (JSON Data, must be object type)
     * @param {type} fieldObject
     * @returns {String}
     */
    prepareDateBoxOptionDropDown = function(fieldObject,maxAllowed,LiValues){
        var optionString = '<ul class="hor_list lh40 boxType disp-none">';
        var cssClassOnLI = "";/*Class on LI DOM*/
        var subOptionString = '<ul class="rlist" >';
        var maxElement = 0, maxWidth   = 490, maxWidthPerEle = 40;
        var styleAttr = "",styleSubList = "";
      
      var calcWidth = function(length){
        maxElement = parseInt(length);
        maxWidthPerEle = maxWidth/maxElement;
        //maxWidthPerEle = Math.floor(maxWidthPerEle);
        styleAttr = " style=\"width:"+(maxWidthPerEle-1)+"px\"";        
      };
      var data = dateDataArray
      try{
        var i=0;
        //Loop the data section 
        $.each(data,function(key1,data1)
        {
          if( typeof(Object.keys(data1).length) != "undefined" && Object.keys(data1).length !== 1){
            calcWidth(Object.keys(data1).length);
          }
          $.each(data1,function(key2,data2)
          {
            if( typeof(Object.keys(data2).length) != "undefined" && Object.keys(data2).length !== 1){
            calcWidth(Object.keys(data2).length);
          }
            
            $.each(data2,function(value,label)
            { 
              cssClassOnLI += "pos-rel option_" + i;
              
              if( value == fieldObject.value )
              {
                cssClassOnLI+=" activeopt";
              }
              
              //Adjust Width for last element
              if(i==maxElement-1){
                //var lastWidth = maxWidth - ((maxWidthPerEle-1)*(maxElement-1));
                styleAttr = " style=\"width:"+(maxWidthPerEle)+"px\"";
              }
              var elementIndex = 2-i;
              if(elementIndex == 1){
                      LiValues[elementIndex] = LiValues[elementIndex].replace(/^0+/, '');
                      LiValues[elementIndex] = dataMonthArray[LiValues[elementIndex]];
              }
              var spanId = label.toLowerCase();
              if(i < maxAllowed){
                optionString+='<li class="'+cssClassOnLI+'" value='+value + styleAttr +'><span id = "'+spanId+'_value" rel="'+LiValues[elementIndex]+'" class = "dropdown_span">'+label+'</span><i id="'+spanId+'Arrow1" class="reg-sprtie reg-droparrow pos_abs reg-pos12 reg-zi100" style="display: none;"></i><i id="'+spanId+'Arrow2" class="icons rarrwdob reg-pos11 pos_abs disp-none" style="display: inline-block;"></i></li>';
                cssClassOnLI = ""
              }
              
              ++i;
              
            });
          });
        });
        
        optionString+='</ul>'
        if(i>maxAllowed){
          var subDivString = '<i class="reg-sprtie reg-droparrow pos_abs epdpos12 z2 disp-none js-subBoxList"></i> <div class="pos_abs sub-mainlist epdpos11 z1 edpdbox boxshadow js-subBoxList disp-none">';
          subOptionString= subDivString + subOptionString+'</ul></div>';
          
          optionString+=subOptionString;
        }
      }
      catch(e){
        optionString = "";
        console.log(e);
      }
      return optionString;
    }
    prepareBoxOptionDropDown = function(data,fieldObject,maxAllowed){
            
      var optionString = '<ul class="hor_list lh40 boxType disp-none">';
      var cssClassOnLI = "";/*Class on LI DOM*/
      var subOptionString = '<ul class="rlist" >';
      
      var breakInSubList = false;
      var maxElement = 0, maxWidth   = 490, maxWidthPerEle = 40;
      var styleAttr = "",styleSubList = "";
      
      var calcWidth = function(length){
        maxElement = parseInt(length);
        maxWidthPerEle = maxWidth/maxElement;        
        if(breakInSubList){
          maxWidthPerEle = maxWidth/(maxAllowed+1);
        }
        //maxWidthPerEle = Math.floor(maxWidthPerEle);
        styleAttr = " style=\"width:"+(maxWidthPerEle-1)+"px\"";        
      };
      
      if(typeof maxAllowed != "undefined"){
        breakInSubList = true;
      }
        
      try{
        var i=0;
        //Loop the data section 
        $.each(data,function(key1,data1)
        {
          if( typeof(Object.keys(data1).length) != "undefined" && Object.keys(data1).length !== 1){
            calcWidth(Object.keys(data1).length);
          }
          $.each(data1,function(key2,data2)
          {
            if( typeof(Object.keys(data2).length) != "undefined" && Object.keys(data2).length !== 1){
            calcWidth(Object.keys(data2).length);
          }
            
            $.each(data2,function(value,label)
            { 
              cssClassOnLI += "option_" + i;
              
              if( value == fieldObject.value )
              {
                cssClassOnLI+=" activeopt";
              }
              
              //Adjust Width for last element
              if(i==maxElement-1){
                //var lastWidth = maxWidth - ((maxWidthPerEle-1)*(maxElement-1));
                styleAttr = " style=\"width:"+(maxWidthPerEle)+"px\"";
              }
                            
              //Add Li option as per maxAllowed Constraint
              if(breakInSubList && i == maxAllowed){
                optionString+='<li class="'+cssClassOnLI+'" value='+"-1" + styleAttr +'>'+"More"+'</li>';
                cssClassOnLI += " js-boxSubListOption "
              }
              
              if(breakInSubList === false || i < maxAllowed){
                optionString+='<li class="'+cssClassOnLI+'" value='+value + styleAttr +'>'+label+'</li>';
                cssClassOnLI = ""
              }
              else if(breakInSubList)
              {
                subOptionString+='<li class="'+cssClassOnLI+'" value='+value + styleAttr + '>'+label+'</li>';
                cssClassOnLI = "js-boxSubListOption "
              }
              
              ++i;
              
            });
          });
        });
        
        optionString+='</ul>'
        if(i>maxAllowed){
          var subDivString = '<i class="reg-sprtie reg-droparrow pos_abs epdpos12 z2 disp-none js-subBoxList"></i> <div class="pos_abs sub-mainlist epdpos11 z1 edpdbox boxshadow js-subBoxList disp-none">';
          subOptionString= subDivString + subOptionString+'</ul></div>';
          
          optionString+=subOptionString;
        }
      }
      catch(e){
        optionString = "";
        console.log(e);
      }
      return optionString;
    }
    
    /*
     * getStaticTables
     * 
     * @param {type} sectionId
     * @returns {undefined}
     */
    storeInStaticTables = function(sectionId){
      var listTables = listStaticTables[sectionId];
      staticTables.getData(listTables);
    }
    
    /*
     * getDataFromStaticTables
     * @param {type} key
     * @returns {undefined}
     */
    getDataFromStaticTables = function(key){
      
      if(duplicateEditFieldMap.hasOwnProperty(key)){
        key = duplicateEditFieldMap[key];
      } 
      
      if(fieldMapList.hasOwnProperty(key)){
        key = fieldMapList[key];
      }
      else{
        key = key.toLowerCase();
      }
            
      return staticTables.getData(key);
    }
    
    /*
     * getDependantData : if dependant then filter the data as per dependantfields
     * @param {type} fieldObject
     * @param {type} data
     * @returns {undefined}
     */
    getDependantData = function(fieldObject,data){
      
      var key = fieldObject.key;
      
      if(duplicateEditFieldMap.hasOwnProperty(key)){
        key = duplicateEditFieldMap[key];
      }
      
      var bDepField = depDataFields.hasOwnProperty(key);
      var arrSikhFields = ["CUT_HAIR","TRIM_BEARD","WEAR_TURBAN","CLEAN_SHAVEN"];
      if(false === bDepField){
        return data;
      }
      
      
      var mainFieldName = depDataFields[key];
      
      if(mainFieldName == "GENDER"){
        return data[profileGender.substr(0,1).toUpperCase()];
      }
      
      var mainSectionId = fieldObject.sectionId;
      
      //Check Section Id of main Field
      if(depFieldSectionID.hasOwnProperty(key) === true){
        mainSectionId = depFieldSectionID[key];
      }
      
      var mainField = editAppObject[mainSectionId][mainFieldName];
      
      if(key == "NATURE_HANDICAP"){/*Nature of handicapped*/
         if((mainField.value == "1" || mainField.value == "2"))
           return data;
         return ;
      }
      
      if(key == "FAMILY_INCOME"){
         return data["51"];
      }
      if( key == "INCOME"){/*Family Income */
        
        var familyIncome = "128"; 
        currentIncomeInRs = false;
        if (mainField.value == "51"){
          familyIncome = "51";
          currentIncomeInRs = true;
        }
        
        return data[familyIncome]; 
      }
      
      if(arrSikhFields.indexOf(key) !== -1 ){
        if( mainField.value == "Y" || mainField.value == null)
          return; 
        
        return data;
      }
      
      return data[mainField.value];
    }
    
    /*
     * Bake a section
     * @param {type} sectionId 
     * @returns {undefined}
     */
    bakeSection = function(sectionId){
      var sectionArray = getSectionArray(sectionId);

      if(null === sectionArray){
        throw new Error("Invalid Section Id Passed");
      }

      if(isSectionBaked.indexOf(sectionId) !== -1){
        $('#'+sectionId+'EditForm').removeClass(dispNone);
        fillSection(sectionId);
        delete editedFields[sectionId];
        return ;/*Already Baked*/
      }
      
      //Store Option in Static Tables
      storeInStaticTables(sectionId);
      
      var editSectionId         = '#'+sectionId+'EditForm';
      var editSectionFormName   = sectionId+'Form';
      
      //Get DOM element
      var editSectionDOM = $(editSectionId);
      $(editSectionDOM).addClass(dispNone);
      //Add Form Tag
      $(editSectionDOM).append("<form id=\"" +editSectionFormName+ "\"></form>");
      var editSectionFormDOM = $('#'+editSectionFormName);
      cookSectionTopHeading(editSectionFormDOM,sectionId);
      //Add Fields as per FieldType
      var firstElementId = ""; 
      for(var i=0;i<sectionArray.length;i++){
        var fieldKey = sectionArray[i];
        if( duplicateEditFieldMap.hasOwnProperty(fieldKey+'_'+sectionId) ){
          fieldKey = fieldKey+'_'+sectionId;
        }
        if( false === editAppObject[sectionId].hasOwnProperty(fieldKey) ){
          if(debugInfo){
            console.log(fieldKey + ' field in section : ' + sectionId + ' does not exist for this profile');
          }
          continue;
        }
         
        var fieldObject = editAppObject[sectionId][fieldKey];
        
        if(typeof fieldObject == "undefined" || fieldObject.key=="PROFILE_HANDLER_NAME"){
          if(debugInfo)
            console.log("i : " + i);
          continue;
        }
        
        if(firstElementId.length === 0){
          firstElementId = fieldObject.key.toLowerCase();
        }
        
        if( fieldObject.type === OPEN_TEXT_TYPE                   && 
            rightAlignedFields.indexOf(fieldObject.key) === -1    && 
            rightAlignWithoutPadding.indexOf(fieldObject.key) === -1
          )
        {
          cookOpenTextField(editSectionFormDOM,fieldObject);
	if(fieldObject.key=="NAME")
	{
		showDisplayNameSettingFirstTime(editAppObject[BASIC]["DISPLAYNAME"]);
		onDisplayNameChange(editAppObject[BASIC]["DISPLAYNAME"]);
	}
        }
        if( fieldObject.type === FILE_TYPE)
        {
          cookFileField(editSectionFormDOM,fieldObject,sectionId);
        }
        
        if(fieldObject.type === OPEN_TEXT_TYPE && rightAlignedFields.indexOf(fieldObject.key) !== -1){
          var parentAttr = {class:'pt20 js-rightAlign'} ; 
          var labelAttr = {class:"color12"};
          var fieldDivAttr = {class:"edpbrd3 lh40 fullwid edpbrad1 mt5 pos-rel"};
          var inputAttr     = {class:"fontlig f15 color11 wid90p brdr-0 padall-10 outw",type:"text",value:fieldObject.decValue,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off"}
          
          var configObj = {'parentAttr':parentAttr, 'labelAttr':labelAttr,'fieldDivAttr':fieldDivAttr,'inputAttr':inputAttr};
          
          cookOpenTextField(editSectionFormDOM,fieldObject,configObj);
        }
        
        if(fieldObject.type === OPEN_TEXT_TYPE && rightAlignWithoutPadding.indexOf(fieldObject.key) !== -1){
          cookoutSidesUIElement(fieldObject,editSectionFormDOM);
        }
        
        if(fieldObject.type === BOX_TYPE){
          cookBoxTypeField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === DATE_TYPE){
          cookDateBoxTypeField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === NON_EDITABLE_TYPE){
          cookNonEditableField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === SINGLE_SELECT_TYPE &&
           rightAlignedFields.indexOf(fieldObject.key) === -1 && 
           rightAlignWithoutPadding.indexOf(fieldObject.key) === -1
          ){
          cookChosenSelectField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === MULTIPLE_SELECT_TYPE){
          cookChosenSelectField(editSectionFormDOM,fieldObject,{'chosenAttr':{"multiple":"","class":"chosen-select-width"}});
        }
        
        if(fieldObject.type === SINGLE_SELECT_TYPE && rightAlignedFields.indexOf(fieldObject.key) !== -1){
          var parentAttr = {class:'pt20 js-rightAlign'} ; 
          var chosenAttr = {class:"chosen-select-width-right"};
          var labelAttr = {class:"color12"};
          var fieldDivAttr = {class:"edpbrd3 lh40 fullwid edpbrad1 mt5 pos-rel"};
          var configObj = {'parentAttr':parentAttr, 'chosenAttr':chosenAttr,'labelAttr':labelAttr,'fieldDivAttr':fieldDivAttr};
          cookChosenSelectField(editSectionFormDOM,fieldObject,configObj);
        }
        
        if(fieldObject.type === SINGLE_SELECT_TYPE && rightAlignWithoutPadding.indexOf(fieldObject.key) !== -1){
          cookoutSidesUIElement(fieldObject,editSectionFormDOM);
        }
        
        if(fieldObject.type === TEXT_AREA_TYPE && rightAlignedFields.indexOf(fieldObject.key) === -1){
          cookTextAreaField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === TEXT_AREA_TYPE && rightAlignedFields.indexOf(fieldObject.key) !== -1){
          var parentAttr    = {class:"pt20 js-rightAlign fontlig",id:fieldObject.key.toLowerCase()+'Parent'};
          var labelAttr     = {class:"fontlig color12",text:fieldObject.label};
          var fieldDivAttr  = {class:"edpbrd3 fullwid edpbrad1 mt10 clearfix f15 js-areaBox pos-rel"}
          var textAreaAttr     = {class:"f15 color11 inpset2 fullwid fontlig f15 edphgt1 edpm1 hgt70 bgnone outline-none",type:"text",text:fieldObject.value,placeholder:notFilledText,id:fieldObject.key.toLowerCase(),autocomplete:"off",value:fieldObject.decValue}
              
          var configObj = {'parentAttr':parentAttr,'labelAttr':labelAttr,'fieldDivAttr':fieldDivAttr,'textAreaAttr':textAreaAttr};
          cookTextAreaField(editSectionFormDOM,fieldObject,configObj);
        }
        
        if(fieldObject.type === PHONE_TYPE){
          cookPhoneField(editSectionFormDOM,fieldObject);
        }
        
        if(fieldObject.type === PRIVACY_TYPE){
          cookoutSidesUIElement(fieldObject,editSectionFormDOM);
        }
        
        if(fieldObject.type === RANGE_TYPE){
          cookoutSidesUIElement(fieldObject,editSectionFormDOM);
        }
        
        var fieldMapKey = fieldObject.key;
        if( duplicateEditFieldMap.hasOwnProperty(fieldMapKey) ){
          fieldMapKey =  duplicateEditFieldMap[fieldMapKey];
        }
        if(behaviourMap.hasOwnProperty(fieldMapKey)){
          $('#'+fieldObject.key.toLowerCase()).addClass(behaviourMap[fieldMapKey]);
        }
        if(fieldKey == "HAVECHILD"){
                if( false !== editAppObject["critical"].hasOwnProperty("MSTATUS")  && editAppObject["critical"]["MSTATUS"].value =="N"){
                        $("#havechildParent").hide();
                }
        }
      }
      if(sectionId == CRITICAL){
        cookNoteTextBeforeSubmitButton(editSectionFormDOM,sectionId);
      }
      //Add Save and Cancel Button
      if(rightAlignedSections.indexOf(sectionId) !== -1){
        var parentAttr    = {class:"clearfix pt20"};
        var saveBtnAttr   = {class:"bg_pink brdr-0 txtc colrw lh44 fl edpwid15 js-save cursp",id:"saveBtn"+sectionId,text:"Save",tabindex:"0"};
        var cancelBtnAttr = {class:"fr edpwid15 bg6 lh44 colrw txtc brdr-0 js-cancel cursp",id:"cancelBtn"+sectionId,text:"Cancel",tabindex:"0"};
        var configObject  = {'parentAttr':parentAttr, 'saveBtnAttr':saveBtnAttr, 'cancelBtnAttr':cancelBtnAttr};
        cookSaveCancelButton(editSectionFormDOM,sectionId,configObject);
      }
      else{
        cookSaveCancelButton(editSectionFormDOM,sectionId);
      }
      
      $(editSectionDOM).removeClass(dispNone);
      isSectionBaked.push(sectionId);
      
      //Focus on first Element
      $('#'+firstElementId).trigger('focus');
    }
    
    /*
     * toggleLoader : To toggle the loader
     * @param {type} bShow : if true then loader is visible and if false then its not hidden else work as toggle
     * @type Function|Function
     */
    var toggleLoader = (function(){
      var bToggle = false;
      var show = function(){
        $('.overlayload').css('top',$(document).scrollTop());
        $('.js-loaderShow').removeClass(dispNone);
        $('body').addClass("scrollhid");
      }
      var hide = function(){
        $('.js-loaderShow').addClass(dispNone);
        $('body').removeClass("scrollhid");
      } 
      return function(bShow){
        if(typeof bShow != "undefined")
          bToggle = !bShow; 
        
        if(bToggle){hide();}else{show()}bToggle=!bToggle; 
      }
    })()
    
    /*
     * storeFieldChangeValue
     * @param {type} fieldObject
     * @param {type} value
     * @returns {undefined}
     */
    storeFieldChangeValue = function(fieldObject,value){
      if(editedFields.hasOwnProperty(fieldObject.sectionId) === false){
        editedFields[fieldObject.sectionId] = {};
      }
      
      if(false === previousSectionValue[fieldObject.sectionId].hasOwnProperty(fieldObject.key)){
        previousSectionValue[fieldObject.sectionId][fieldObject.key] = fieldObject.value;
      }
      else if (true === previousSectionValue[fieldObject.sectionId].hasOwnProperty(fieldObject.key)) {
        previousSectionValue[fieldObject.sectionId][fieldObject.key] = editedFields[fieldObject.sectionId][fieldObject.key];
      }
      
      editedFields[fieldObject.sectionId][fieldObject.key] = value;
    }
    
    /*
     * OnEdit Btn 
     * @returns {undefined}
     */
    onEditEvent = function(sectionId){
      toggleLoader(true);
      var viewName = '.js-'+sectionId+'View';
      if(bAjaxInProgress && (isInitialized == false || editAppObject.needToUpdate == true)){
        setTimeout(function(){onEditEvent(sectionId)},20);
        return;
      }
      
      if(bErrorInEditAjax && retryStoreData < MAX_RETRY && !bAjaxInProgress){
        ++retryStoreData;
        getEditData();
        setTimeout(function(){onEditEvent(sectionId)},20);
        return;
      }
      
      if(bErrorInEditAjax && retryStoreData >= MAX_RETRY && !bAjaxInProgress){
        --retryStoreData;
        toggleLoader(false);
        return;
      }
      
      setTimeout(function(){
        bakeSection(sectionId);
        $(viewName).addClass(dispNone);
        bindBehaviour();
        if(sectionId == CRITICAL){
          initMstatusDocumentMap();
        }
        if(sectionId == BASIC){
	  initJamaat();
        }
        if(sectionId == FAMILY){
          initNativeFields();
          initSiblings();
        }
        if(sectionId == EDU_CAREER){
          initUGAndPGDegreeMap();
          initEducationFields();
        }
        if(sectionId == CONTACT){
          initPhoneFields();
          initPinCodeFields();
        }
        if(sectionId == VERIFICATION){
            initVerificationFields();
        }
        toggleLoader(false);
      },2);
    }
    
    /*
     * Bind Event
     * @returns {undefined}
     */
    BindEvent = function(){
      $('.js-editBtn').on('click',function(){
       this.className += " " + dispNone; 
       onEditEvent(this.getAttribute("data-section-id"));
      });
    }
    
    /*
     * whiteListingKeys
     * @param {type} event 
     * @param {type} namedLogic 
     * @returns {undefined}
     */
    var whiteListingKeys = (function(){
      
      var arrAllowedKeys = [];
      for (i = 65; i <= 90; i++){
        arrAllowedKeys.push(i);
      }
      
      var arrNumberAllowedKeys = [];
      for (i = 48,j=96; i <= 57&&j<=105; i++,j++){
        arrNumberAllowedKeys.push(i);
        arrNumberAllowedKeys.push(j);
      }
      
      var arrBasicKeys = [];
      arrBasicKeys.push(32);//space
      arrBasicKeys.push(46);//delete
      arrBasicKeys.push(8);//backspace
      arrBasicKeys.push(9);//tab
      arrBasicKeys.push(16);//shift
      arrBasicKeys.push(37);//left arrow
      arrBasicKeys.push(39);//right arrow
      arrBasicKeys.push(35);//end
      arrBasicKeys.push(36);//home
      arrBasicKeys.push(45);//insert
      arrBasicKeys.push(116);//F5
      
      var onlyChars = function(event,fieldExtraKeys){
        var key = event.which;
        var extraKeys = [];
        if (event.shiftKey === false) {
          extraKeys.push(222);//quote
          extraKeys.push(190);//dot
          extraKeys.push(110);//dot(numpad)
        }
        
        if(typeof fieldExtraKeys != "undefined" && fieldExtraKeys instanceof Array && fieldExtraKeys.length && event.shiftKey === false){
         for(var k =0;k<fieldExtraKeys.length;k++){
           extraKeys.push(fieldExtraKeys[k]);
         } 
        }
        
        if (key  && 
            !($.inArray(key , arrAllowedKeys) != -1) && 
            !($.inArray(key , extraKeys) != -1) &&
            !($.inArray(key , arrBasicKeys) != -1) 
           )
        {
          event.preventDefault();
          return false;
        }
        return true;
      }
      
      var onlyNumbers = function(event,currentValue,limitDigit){
        var key = event.which;
        var allowedBehviour = false;
        var arrBasicKeys = [];
        arrBasicKeys.push(46);//delete
        arrBasicKeys.push(8);//backspace
        arrBasicKeys.push(9);//tab
        arrBasicKeys.push(16);//shift
        arrBasicKeys.push(37);//left arrow
        arrBasicKeys.push(39);//right arrow
        arrBasicKeys.push(35);//end
        arrBasicKeys.push(36);//home
        arrBasicKeys.push(45);//insert
        arrBasicKeys.push(116);//F5
        
        var arrSpecialBehaviour = [86,67];
        
        if(key && event.ctrlKey == true && arrSpecialBehaviour.indexOf(key)!== -1){
          allowedBehviour = true;
        }
        
        if(key && !allowedBehviour && event.shiftKey == true && arrNumberAllowedKeys.indexOf(key) !== -1){
          event.preventDefault();
          return false;
        }        
        
        if (key  && !allowedBehviour &&
            !($.inArray(key , arrNumberAllowedKeys) != -1) && 
            !($.inArray(key , arrBasicKeys) != -1) 
           )
        {
          event.preventDefault();
          return false;
        }
        
        if(arrNumberAllowedKeys.indexOf(key) !== -1 && arrBasicKeys.indexOf(key) === -1 && typeof limitDigit != "undefined" && typeof currentValue != "undefined" && currentValue.length){
          currentValue = currentValue.trim().replace(/^0+/,"").replace(/ /g,"");
          var currentNumValue = parseInt(currentValue); 
          if(!isNaN(currentNumValue) && currentNumValue.toString().length >= limitDigit){
            event.preventDefault();
            return false;
          }
          
          return true;
        }
      }
      
      var sanitizeNumber = function(event,limitDigit,allowOneZero){
        var self = $(event.target);
        var regex = /^0*/g;
        var onlyNumberRegex = /[^0-9]*/g;
        var value = self.val();
        var replaceZero = "";
        if(typeof allowOneZero != "undefined" && allowOneZero == true){
          regex = /^0+/g;
          replaceZero = "0";
        }
        value = value.trim().replace(regex,replaceZero).replace(onlyNumberRegex,"");
        if((value != self.val().trim() || value.length >limitDigit) && typeof limitDigit != "undefined"){
          self.val(value.substr(0,limitDigit));
          return false;
        }
        
        if(typeof self.attr('myMaxLength') != "undefined")
          var limitDigit = self.attr('myMaxLength');
        
        if(typeof limitDigit != "undefined" && (value != self.val().trim() || value.length >limitDigit)){
          self.val(value.substr(0,limitDigit));
          return false;
        }
        
        if(typeof self.attr('maxlength') != "undefined")
          var limitDigit = self.attr('maxlength');
        
        if(typeof limitDigit != "undefined" && (value != self.val().trim() || value.length >limitDigit)){
          self.val(value.substr(0,limitDigit));
          return false;
        }
        return true;

      }
      
      var forAbout = function(event){
        var key = event.which;
        var arrSymbol = [188,190];
        
        if(key  && event.shiftKey == true && arrSymbol.indexOf(key) !== -1){
          event.preventDefault();
          return false;
        }
        
        return true;
      }
      
      return function(event,namedLogic,currentValue,limitDigit,fieldExtraKeys){
        if(namedLogic == "onlyChars"){
          return onlyChars(event,fieldExtraKeys);
        }
        else if(namedLogic == "onlyNums"){
          return onlyNumbers(event,currentValue,limitDigit);
        }
        else if(namedLogic == "forAbout"){
          return forAbout(event);
        }
        else if(namedLogic == "sanitizeNumber"){
          var allowedOneZero = limitDigit;
          limitDigit = currentValue;
          return sanitizeNumber(event,limitDigit,allowedOneZero);
        }
      }
    })();
    
    /*
     * onSectionSave 
     * @param {type} sectionId
     * @returns {undefined}
     */
updateEduLevelChanges =function(eduLevelVal)
{
      var gradDeg = editAppObject[EDU_CAREER]["DEGREE_UG"];
      var gradCollg = editAppObject[EDU_CAREER]["COLLEGE"];

      var postGradDeg = editAppObject[EDU_CAREER]["DEGREE_PG"];
      var postGradCollg = editAppObject[EDU_CAREER]["PG_COLLEGE"];
      var gradDegID = '#'+gradDeg.key.toLowerCase();
      var postGradDegID = '#'+postGradDeg.key.toLowerCase();
      var other_ugDeg = editAppObject[EDU_CAREER]["OTHER_UG_DEGREE"];
      var other_pgDeg = editAppObject[EDU_CAREER]["OTHER_PG_DEGREE"];
	if(eduLevelVal!='')
	{
		if( $(gradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').length === 1 &&
		  $(gradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').hasClass('activeopt') === false
		)
		{
			editedFields["career"]["DEGREE_PG"]=''
			editedFields["career"]["PG_COLLEGE"]=''
			editedFields["career"]["OTHER_PG_DEGREE"]=''
			
			if(gradDeg.value=='')
			{
				editedFields["career"]["DEGREE_UG"]= eduLevelVal;
			}
		}
		else if($(postGradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').length === 1 &&
		  $(postGradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').hasClass('activeopt') === false
		  )
		{
			if(eduLevelVal != 42 && eduLevelVal != 21 && postGradDeg.value=='')
			{
				editedFields["career"]["DEGREE_PG"]= eduLevelVal;
			}
			
		}
		else
		{
			editedFields["career"]["DEGREE_UG"]='';
			editedFields["career"]["COLLEGE"]='';
			editedFields["career"]["OTHER_UG_DEGREE"]='';
			editedFields["career"]["DEGREE_PG"]='';
			editedFields["career"]["PG_COLLEGE"]='';
			editedFields["career"]["OTHER_PG_DEGREE"]='';
		}
	}
}

    onSectionSave = function(sectionId,showLoader){
      //If no editing happens, then gracefully hide :D
      if(editedFields.hasOwnProperty(sectionId) === false){
        showHideEditSection(sectionId,"hide");
        return;
      }
      
      if(sectionId == CONTACT){
        if(editedFields[CONTACT].hasOwnProperty('PHONE_RES') || editedFields[CONTACT].hasOwnProperty('PHONE_MOB') || editedFields[CONTACT].hasOwnProperty('ALT_MOBILE')){
          $('#phone_resParent').trigger('onSave');
          $('#phone_mobParent').trigger('onSave');
          $('#alt_mobileParent').trigger('onSave');
        }
      }
	var eduLevelVal='';
	if(sectionId==EDU_CAREER)
	{
		if(editedFields["career"].hasOwnProperty("EDU_LEVEL_NEW"))
		{
			eduLevelVal = editedFields["career"]['EDU_LEVEL_NEW'];
		}
	}
	if(eduLevelVal!='')
	{
		updateEduLevelChanges(eduLevelVal);
	}
      for(var key in requiredArray[sectionId]){
        var parentDOM = $('#'+key.toLowerCase()+'Parent'); 
        parentDOM.find('.js-errorLabel').removeClass(dispNone);
      }
            
      
      //Check Any Error Lable is visible or not
      var validationCheck = '#'+sectionId +'EditForm' +' .js-errorLabel:not(.disp-none)';   
      if($(validationCheck).length !== 0 && $(validationCheck).length !== "0"){
        $(document).scrollTop($(validationCheck).offset().top);
        return;
      }
      
      var editFieldArr = editedFields[sectionId];
      var sectionObject = editAppObject[sectionId];
      //Hmmm Some Awful Checks!! 
      if(editFieldArr.hasOwnProperty('WEIGHT') === true){
        editFieldArr['WEIGHT'] = editFieldArr['WEIGHT'].toString().toLowerCase().split('kg')[0].trim(); 
      }
      //Check for valid changes, if same value then delete that key
      var arrIgnore = ['M_BROTHER','M_SISTER','ALT_MOBILE','PHONE_RES','PHONE_MOB','TIME_TO_CALL_START','TIME_TO_CALL_END'];
      for(var fieldKey in editFieldArr){
        //For Resave case : when duplicate fields remaped to its original name
        if (false === sectionObject.hasOwnProperty(fieldKey)) {
          continue;
        }
        if(sectionId == 'verification'){
                
        }else{
                if(arrIgnore.indexOf(fieldKey) === -1 && editFieldArr[fieldKey] == sectionObject[fieldKey].value){
                  delete editFieldArr[fieldKey];  
                }
        }
        
        if(arrIgnore.indexOf(fieldKey) === -1){
          if( fieldKey == 'M_BROTHER' && 
              typeof sectionObject['T_BROTHER'].value == "string" && 
              sectionObject['T_BROTHER'].value.length &&
              editFieldArr[fieldKey] == sectionObject['T_BROTHER'].value.split(",")[1]
            ){
            delete editFieldArr[fieldKey];
          }
          
          if( fieldKey == 'M_SISTER' && 
              typeof sectionObject['T_SISTER'].value == "string" && 
              sectionObject['T_SISTER'].value.length &&
              editFieldArr[fieldKey] == sectionObject['T_SISTER'].value.split(",")[1]
            ){
            delete editFieldArr[fieldKey];
          }
        }
      }
      
      //Now Check count of valid changes, if not gracefully hide :D 
      if(Object.keys(editFieldArr).length === 0){
        showHideEditSection(sectionId,"hide");
        delete editedFields[sectionId];
        return ;
      }
      
      //Check for Dependant fields changes, 
      //if any dependant field existthen add other dependant field also if not exist
      for(var fieldKey in editFieldArr){
        if( storeTogetherFields.hasOwnProperty(fieldKey) && 
            editFieldArr.hasOwnProperty(storeTogetherFields[fieldKey]) === false ){
            if(fieldKey=="CITY_RES")
                editFieldArr[storeTogetherFields[fieldKey]] = sectionObject[storeTogetherFields[fieldKey]].value;
            else
                editFieldArr[depDataFields[fieldKey]] = sectionObject[storeTogetherFields[fieldKey]].value;
        }
      }
      
      //Check For Duplicate Fields and replace with valid keys
      for(var fieldKey in editFieldArr){
        if(duplicateEditFieldMap.hasOwnProperty(fieldKey)){
          editFieldArr[duplicateEditFieldMap[fieldKey]] = editFieldArr[fieldKey];
          delete editFieldArr[fieldKey];
        }
      }
      
      //Check Phone Field
      var arrPhoneFields = ['ALT_MOBILE','PHONE_RES','PHONE_MOB'];
      for(var fieldKey in editFieldArr){
        if(arrPhoneFields.indexOf(fieldKey) !== -1){
          var field = editAppObject[CONTACT][fieldKey];
          var valArray = field.value.split(',');
          
          if(valArray.length){
            if(editFieldArr[fieldKey].hasOwnProperty('isd') == false){
              if(valArray[0].length){
                editFieldArr[fieldKey]['isd'] = valArray[0];
              }
              else{
                editFieldArr[fieldKey]['isd'] = $('#'+field.key.toLowerCase()+'-isd').val();
              }
            }
            
            if(fieldKey == 'PHONE_RES' && editFieldArr[fieldKey].hasOwnProperty('std') == false){
              if(valArray.length >1 && valArray[1].length){
                editFieldArr[fieldKey]['std'] = valArray[1];
              }
            }
            
            if(fieldKey == 'PHONE_RES' && editFieldArr[fieldKey].hasOwnProperty('landline') == false){
              editFieldArr[fieldKey]['landline'] = valArray[2];
            }
            else if(fieldKey != 'PHONE_RES' && editFieldArr[fieldKey].hasOwnProperty('mobile') == false){
              editFieldArr[fieldKey]['mobile'] = valArray[1];
            }
          }
        }
      }
      //Check Time to Call Field
      if( editFieldArr.hasOwnProperty('TIME_TO_CALL_START') || editFieldArr.hasOwnProperty('TIME_TO_CALL_END') ){
        var timeToCallField = editAppObject[CONTACT]['TIME_TO_CALL_START'];
        
        if( timeToCallField.value.length ){
          var valArray = timeToCallField.value.split(",");
          var startTime = valArray[0].split(" ")[0].trim();
          var startAmPm = valArray[0].split(" ")[1].trim();

          var endTime = valArray[1].split(" ")[0].trim();
          var endAmPm = valArray[1].split(" ")[1].trim();
          
          if( startTime == editFieldArr['TIME_TO_CALL_START']['time_to_call_start'] && 
              startAmPm.toLowerCase() ==editFieldArr['TIME_TO_CALL_START']['start_am_pm'].toLowerCase() && 
              endTime == editFieldArr['TIME_TO_CALL_END']['time_to_call_end'] && 
              endAmPm.toLowerCase() ==editFieldArr['TIME_TO_CALL_END']['end_am_pm'].toLowerCase() 
            ){//All Values are same so delete
            delete editFieldArr['TIME_TO_CALL_START'];
            delete editFieldArr['TIME_TO_CALL_END'];
          }
        }
        else{
          if(editFieldArr['TIME_TO_CALL_START']['time_to_call_start'].length == 0 && editFieldArr['TIME_TO_CALL_START']['start_am_pm'].length ==0){
            delete editFieldArr['TIME_TO_CALL_START'];
          }
          if(editFieldArr['TIME_TO_CALL_END']['time_to_call_end'].length == 0 && editFieldArr['TIME_TO_CALL_END']['end_am_pm'].length ==0){
            delete editFieldArr['TIME_TO_CALL_END'];
          }
          
          if(editFieldArr.hasOwnProperty('TIME_TO_CALL_END') || editFieldArr.hasOwnProperty('TIME_TO_CALL_START')){
            $('#time_to_callParent').find('.js-errorLabel').removeClass(dispNone);
          }
        }
        
      }
      
      //Now Check count of valid changes, if not gracefully hide :D 
      if(Object.keys(editFieldArr).length === 0){
        showHideEditSection(sectionId,"hide");
        delete editedFields[sectionId];
        return ;
      }
      var displayNameObj = editAppObject[BASIC]["DISPLAYNAME"];
      if(sectionId== BASIC && !editFieldArr.hasOwnProperty('DISPLAYNAME') && displayNameObj.value=='')
		editFieldArr['DISPLAYNAME']="Y";
      //Okay!, Now lets store it
      if(typeof showLoader != "undefined" && showLoader === false){
      }else{
        toggleLoader(true);
      }
      var editData = new FormData();
      $.each(editFieldArr, function(key, value)
      {
            editData.append('editFieldArr['+key+']', value);

      });
      var eData = {};
      eData.editFieldArr = editFieldArr;
      $.myObj.ajax({
        url: fileTypePostArray.indexOf(sectionId) !== -1?"/api/v1/profile/editsubmitDocuments":"/api/v1/profile/editsubmit",
        type: 'POST',
        datatype: 'json',
        cache: false,
        async: true,
        contentType: fileTypePostArray.indexOf(sectionId) !== -1?false:"application/x-www-form-urlencoded",
        data: fileTypePostArray.indexOf(sectionId) !== -1?editData:eData,
        processData: fileTypePostArray.indexOf(sectionId) !== -1?false:true,
        success: function (result) {
                if(typeof showLoader != "undefined" && showLoader === false){
                }else{
                  toggleLoader(false);
                }
          var statusCode = parseInt(result.responseStatusCode);
          if (statusCode === 0) {
            showHideEditSection(sectionId,"hide");
            editAppObject.needToUpdate = true;
            storeData(JSON.stringify(result.editApi));
            updateView(result.viewApi);
            if(sectionId == "critical" && editedFields[sectionId].hasOwnProperty("MSTATUS") && editedFields[sectionId]["MSTATUS"] == "D"){
                    $(".mstatusUndScnMsg").removeClass("disp-none");
            }
            delete editedFields[sectionId];
            //update self name in chat header
            if(sectionId != 'verification' && eData && eData["editFieldArr"] && eData["editFieldArr"]["NAME"] != undefined){
              if($.isFunction(setChatSelfName)){
                setChatSelfName(eData["editFieldArr"]['NAME'],"chatHeader");
              }
            }
            if(sectionId == "critical"){
                showHideCriticalSection(sectionId);
                }       
          }
          else if(statusCode === 1 &&  result.hasOwnProperty('error'))
          {
            for(var key in result.error){
              var parentId = '#'+key.toLowerCase()+'Parent';
              
              if($(parentId).length == 0)
                continue;
              var errorMsg = getDecoratedServerError(key,result['error'][key]);
              $(parentId).find('.js-errorLabel').text(errorMsg).removeClass(dispNone);
            }
            var validationCheck = '#'+sectionId +'EditForm' +' .js-errorLabel:not(.disp-none)'
            $(document).scrollTop($(validationCheck).offset().top);
          }
          if(sectionId != 'verification' && Object.keys(editFieldArr).length==1 && (result.viewApi.contact && editFieldArr.ALT_EMAIL == result.viewApi.contact.my_alt_email) && editFieldArr.ALT_EMAIL) 
              showAlternateConfirmLayer($("#my_alt_emailView"));
          if(sectionId != 'verification' && Object.keys(editFieldArr).length==1 && (result.viewApi.contact && editFieldArr.EMAIL == result.viewApi.contact.my_email) && editFieldArr.EMAIL) 
              showAlternateConfirmLayer($("#my_emailView"));
              
        },
        error:function(result){
                if(typeof showLoader != "undefined" && showLoader === false){
                }else{
                  toggleLoader(false);
                }
        }
      }); 
    }
    
    /*
     * onSectionCancel
     * @param {type} sectionId
     * @returns {undefined}
     */
    onSectionCancel = function(sectionId){
      fillSection(sectionId);
      if(sectionId == BASIC){
	var displayNameField = editAppObject[BASIC]["DISPLAYNAME"];
	showDisplayNameSettingFirstTime(displayNameField);
      }
      if(sectionId==EDU_CAREER){
        var eduField = editAppObject[EDU_CAREER]["EDU_LEVEL_NEW"];
        onHighestEducationChange(eduField.value,eduField.key);
      }
      delete editedFields[sectionId];
      requiredFieldStore.removeAll(sectionId);
      showHideEditSection(sectionId,"hide");
    }
    
    /*
     * 
     * @param {type} fieldObject
     * @param {type} showOrHide : Show and Hide
     * @param {type} updateStore : True for updating store 
     * @returns {undefined}
     */
    showHideField = function(fieldObject,showOrHide,updateStore){
            
      var fieldParentId = '#' + fieldObject.key.toLowerCase() + 'Parent';
      
      if(showOrHide == "show"){
        $(fieldParentId).removeClass(dispNone);
      }
      else if(showOrHide == "hide"){
        $(fieldParentId).addClass(dispNone);
        $(fieldParentId+" .js-errorLabel:not(.disp-none)").addClass(dispNone);
      }
      //Clear Data and Update Store         
      if(typeof updateStore != "undefined" && updateStore == true){
        storeFieldChangeValue(fieldObject,"");
        
        if(fieldObject.type === SINGLE_SELECT_TYPE || fieldObject.type === MULTIPLE_SELECT_TYPE){
          $('#'+fieldObject.key.toLowerCase()).val("");
          $('#'+fieldObject.key.toLowerCase()).trigger(chosenUpdateEvent)
        }
      }
    }
    showHideEditLink = function(){
                for(var i=0;i<viewResponseKeyArray.length;i++){
                        var section = viewResponseKeyArray[i];
                        if($.inArray( section, hideEditFor) != '-1'){
                                $('#section-'+section.toLowerCase()).find('.js-editBtn').removeClass(dispNone);
                        }
                }
        }
    /*
     * showHideEditSection
     * @param {type} sectionId
     * @param {type} showOrHide : show and hide Edit Section
     * @returns {undefined}
     */
    showHideEditSection = function(sectionId,showOrHide){
      if(getSectionArray(sectionId) === null){
        return ;
      }
      var mainSection = '#section-'+sectionId;
      var sectionView = '.js-'+sectionId+'View';
      var sectionEdit = '#'+sectionId+'EditForm';
      
      if(showOrHide == "show"){
        $(sectionView).addClass(dispNone);
        $(mainSection).find('.js-editBtn').addClass(dispNone);
        $(sectionEdit).removeClass(dispNone); 
      }
      else if(showOrHide == "hide")
      {
        $(sectionView).removeClass(dispNone);
        $(mainSection).find('.js-editBtn').removeClass(dispNone);
        $(sectionEdit).addClass(dispNone);
        $(document).scrollTop($(mainSection).offset().top);
      }
    }
    showHideCriticalSection= function(sectionId){
            $('#section-'+sectionId).find('.js-editBtn').addClass(dispNone);
    }
    /*
     * updateFieldUI
     * @param {type} fieldObject
     * @param {type} typeOfField
     * @param {type} data
     * @returns {undefined}
     */
    updateFieldUI = function(fieldObject,data){
      
      if(typeof data == "undefined"){
              var ky = fieldObject.key;
                if(fieldObject.key == "MSTATUS"){
                          if(editAppObject[BASIC]['RELIGION'].value == "2" && editAppObject[BASIC]['GENDER'].value == "M"){
                                  var ky = "MSTATUS_MUSLIM_EDIT";
                          }else{
                                  var ky = "MSTATUS_EDIT";
                          }
                }
        var data = JSON.parse(getDataFromStaticTables(ky));
        data = getDependantData(fieldObject,data);
      }
      
      var optionString = "";
      var tempValue = fieldObject.value;
      fieldObject.value="-2";
      
      if( fieldObject.type === SINGLE_SELECT_TYPE || fieldObject.type === MULTIPLE_SELECT_TYPE ){
        optionString = prepareOptionDropDown(data,fieldObject);
      }
      else if ( fieldObject.type === BOX_TYPE ){
        optionString = prepareBoxOptionDropDown(data,fieldObject);
      }
      else if ( fieldObject.type === DATE_TYPE ){
        optionString = prepareDateBoxOptionDropDown(fieldObject,3,fieldObject.value);
      }
      
      fieldObject.value=tempValue;
      
      var fieldId = '#'+fieldObject.key.toLowerCase();
      
      $(fieldId+'Parent').find('.js-errorLabel').addClass(dispNone);
      
      if( fieldObject.type === SINGLE_SELECT_TYPE || fieldObject.type === MULTIPLE_SELECT_TYPE ){
        $(fieldId).html("");
        $(fieldId).html(optionString);
        $(fieldId).trigger(chosenUpdateEvent);
      }
      else if ( fieldObject.type === BOX_TYPE ){
        $(fieldId).find('.js-boxContent').html("");
        $(fieldId).find('.js-boxContent').html(optionString);
        $(fieldId).find('span').text(notFilledText).addClass('color12');
      }
      else if ( fieldObject.type === DATE_TYPE ){
        $(fieldId).find('.js-boxContent').html("");
        $(fieldId).find('.js-boxContent').html(optionString);
        $(fieldId).find('span').text(notFilledText).addClass('color12');
      }
    }
    onCasteChange = function(casteVal){
	var religionFieldObject  = editAppObject[BASIC]["RELIGION"];
	var jamaatFieldObject = editAppObject[BASIC]["JAMAAT"];
	if(religionFieldObject.value==2)
	{
		if(casteVal==152)
		{
			showHideField(jamaatFieldObject,"show",false);
		}
		else
		{
			showHideField(jamaatFieldObject,"hide",true);
		}
	}
	else
	{
			showHideField(jamaatFieldObject,"hide",true);
	}
    }
    /*
     * onCountryChange
     * @param {type} countryVal
     * @returns {undefined}
     */
    onCountryChange = function(countryVal){
      var arrCountryWithCities  = ["51","128"];
      
      var cityFieldObject     = editAppObject[BASIC]["CITY_RES"];
      var stateFieldObject     = editAppObject[BASIC]["STATE_RES"];
      var countryFieldObject  = editAppObject[BASIC]["COUNTRY_RES"];
      var incomeFieldObject   = editAppObject[BASIC]["INCOME"+'_'+BASIC];
      
      if(arrCountryWithCities.indexOf(countryVal) === -1){
        //Hide City Field
        requiredFieldStore.remove(stateFieldObject);
        showHideField(cityFieldObject,"hide",true);
        requiredFieldStore.remove(cityFieldObject);
        showHideField(stateFieldObject,"hide",true);
      }
      else{
        var data = JSON.parse(getDataFromStaticTables(cityFieldObject.key));        
        updateFieldUI(cityFieldObject,data[countryVal]);
        requiredFieldStore.remove(cityFieldObject);
        requiredFieldStore.add(cityFieldObject);
        if(countryVal == '128'){
            //Show City Field
            showHideField(cityFieldObject,"show",true);
            showHideField(stateFieldObject,"hide",true);
            requiredFieldStore.remove(stateFieldObject);
        }
        else if(countryVal == '51'){
          var data = JSON.parse(getDataFromStaticTables(stateFieldObject.key));        
          updateFieldUI(stateFieldObject,data);
          showHideField(stateFieldObject,"show",true);
          requiredFieldStore.add(stateFieldObject);
          if($("#state_res").val()!='' && $("#state_res").val()!=null && typeof($("#state_res").val()!='')!="undefined"){
              showHideField(cityFieldObject,"show",true);
          }
          else
              showHideField(cityFieldObject,"hide",true);
        }
      }
      
      if(countryVal != "51" && currentIncomeInRs === false ){
        return ;
      }
      //Change Income Drop Down Also if User Changes from NRI to India and vice versa     
      var data = JSON.parse(getDataFromStaticTables(incomeFieldObject.key));

      var dataKey =  "51";
      currentIncomeInRs = true;
      if(countryVal != "51"){
        dataKey   = "128";
        currentIncomeInRs = false;
      }

      updateFieldUI(incomeFieldObject,data[dataKey]);
      requiredFieldStore.add(incomeFieldObject);
    }
    
    /*
     * onStateChange
     * @param {type} stateVal
     * @returns {undefined}
     */
    onStateChange = function(stateVal){
        var stateFieldObject     = editAppObject[BASIC]["STATE_RES"];
        //Show City Field
        var cityFieldObject     = editAppObject[BASIC]["CITY_RES"];
        showHideField(cityFieldObject,"show",true);
        
        var data = JSON.parse(getDataFromStaticTables(cityFieldObject.key));        
        updateFieldUI(cityFieldObject,data[stateVal]);
        
        requiredFieldStore.remove(stateFieldObject);
        
        if(stateVal == "0"){
            showHideField(cityFieldObject,"hide",true);
            requiredFieldStore.remove(cityFieldObject);
        }
        else{
            requiredFieldStore.add(cityFieldObject);
        }
            
        
    }
    
    onCityChange = function(cityVal){
        var stateFieldObject     = editAppObject[BASIC]["STATE_RES"];
        requiredFieldStore.remove(stateFieldObject);
        if(cityVal=="0" && $("#state_res").val())
            editedFields[BASIC]["CITY_RES"]=$("#state_res").val()+"OT";
    }
    
    /*
     * onChallengedChange
     * @param {type} countryVal
     * @returns {undefined}
     */
    onChallengedChange = function(challengedVal){
      var arrChallengedWithNature   = ["1","2"];
      var natureHandicapFieldObject = editAppObject[LIFE_STYLE]["NATURE_HANDICAP"];
      var challengedField           = editAppObject[LIFE_STYLE]["HANDICAPPED"];
      var previousVal               = getPreviousFieldValue(challengedField);
      
      if(arrChallengedWithNature.indexOf(challengedVal) === -1){
        //Hide Field
        showHideField(natureHandicapFieldObject,"hide",true);
      }
      else if(arrChallengedWithNature.indexOf(previousVal) === -1)
      {
        //Show Field
        showHideField(natureHandicapFieldObject,"show",true);
                
        var data = JSON.parse(getDataFromStaticTables(natureHandicapFieldObject.key));
        updateFieldUI(natureHandicapFieldObject,data);
      }
    }
    
    /*
     * onAmritdhariChange 
     * @param {type} amritdhariVal
     * @returns {undefined}
     */
    onAmritdhariChange = function(amritdhariVal){
      
      var cutHairField          = editAppObject[LIFE_STYLE]["CUT_HAIR"];
      var beardField            = editAppObject[LIFE_STYLE]["TRIM_BEARD"];
      var turbanField           = editAppObject[LIFE_STYLE]["WEAR_TURBAN"];
      var shavenField           = editAppObject[LIFE_STYLE]["CLEAN_SHAVEN"];
      
      if(amritdhariVal == "Y"){
        //Hide Field
        showHideField(cutHairField,"hide",true);
        if(typeof beardField != "undefined")
          showHideField(beardField,"hide",true);
        
        if(typeof turbanField != "undefined")
          showHideField(turbanField,"hide",true);
        
        if(typeof shavenField != "undefined")
          showHideField(shavenField,"hide",true);
      }
      else{
        var data = JSON.parse(getDataFromStaticTables(cutHairField.key));
        showHideField(cutHairField,"show",true);
        updateFieldUI(cutHairField,data);
        if(typeof beardField != "undefined"){
          data = JSON.parse(getDataFromStaticTables(beardField.key));
          showHideField(beardField,"show",true);
          updateFieldUI(beardField,data);
        }
        
        if(typeof turbanField != "undefined"){
          data = JSON.parse(getDataFromStaticTables(turbanField.key));
          showHideField(turbanField,"show",true);
          updateFieldUI(turbanField,data);
        }
        
        if(typeof shavenField != "undefined"){
          data = JSON.parse(getDataFromStaticTables(shavenField.key));
          showHideField(shavenField,"show",true);
          updateFieldUI(shavenField,data);
        }
        
      }
    }
    
    /*
     * 
     * @param {type} nativeStateValue
     * @returns {undefined}
     */
    onNativeStateChange =function(nativeStateValue){
      var nativeStateField  = editAppObject[FAMILY]["NATIVE_STATE"];
      var nativeCityField   = editAppObject[FAMILY]["NATIVE_CITY"];
      var ancestralOrigin   = editAppObject[FAMILY]["ANCESTRAL_ORIGIN"];
      if(nativeStateValue!=0)
      {
      showHideField(nativeCityField,"show",true);
      showHideField(ancestralOrigin,"hide",true);
      showHideUnderScreeningMsg(ancestralOrigin,"hide");
      
      var data = JSON.parse(getDataFromStaticTables(nativeCityField.key));
      updateFieldUI(nativeCityField,data[nativeStateValue]);
      }
      else
      {
      showHideField(nativeCityField,"hide",true);
      }
    }
    
    /*
     * onNativeCityChange
     * @param {type} nativeCityVal
     * @returns {undefined}
     */
    onNativeCityChange = function(nativeCityVal){
      var ancestralOrigin = editAppObject[FAMILY]["ANCESTRAL_ORIGIN"];
      
      if(nativeCityVal == "0"){
        showHideField(ancestralOrigin,"show",true);
        $('#'+ancestralOrigin.key.toLowerCase()).val("");
      }
      else{
        showHideField(ancestralOrigin,"hide",true);
        showHideUnderScreeningMsg(ancestralOrigin,"hide");
      }
    }
    onMstatusChange = function(mstatusVal,fieldID){
            var mstatusProofField = editAppObject[CRITICAL]['MSTATUS_PROOF'];
            var mstatusField = editAppObject[CRITICAL]['MSTATUS'];
            var prevMstatus = editAppObject[CRITICAL]['MSTATUS'].value;
            if(prevMstatus != mstatusVal){
                storeFieldChangeValue(mstatusField,mstatusVal);
            }else{
                delete editedFields[CRITICAL][mstatusField.key];
            }
            $('#mstatus_proofParent').find('.js-errorLabel').addClass(dispNone);
            if(mstatusVal == "D" && editAppObject[CRITICAL]['MSTATUS'].value != "D"){
                        $('#mstatus_proof').val("");
                        $("#idlabel_mstatus_proof").html('jpg/pdf only');
                        $('#mstatus_proofParent').removeClass(dispNone);
                        requiredFieldStore.add(mstatusProofField);
            }else{
                        $('#mstatus_proofParent').addClass(dispNone);
                        requiredFieldStore.remove(mstatusProofField);
            }
    }
    /*
     * onHighestEducationChange
     * @param {type} eduLevelVal
     * @returns {undefined}
     */
    onHighestEducationChange = function(eduLevelVal,fieldID){
//      if(fieldID.indexOf(EDU_CAREER) !==-1)
      {
      var gradDeg = editAppObject[EDU_CAREER]["DEGREE_UG"];
      var gradCollg = editAppObject[EDU_CAREER]["COLLEGE"];
      
      var postGradDeg = editAppObject[EDU_CAREER]["DEGREE_PG"];
      var postGradCollg = editAppObject[EDU_CAREER]["PG_COLLEGE"];
      
        var maxEducation = editAppObject[EDU_CAREER]["EDU_LEVEL_NEW"];
      
      var other_ugDeg = editAppObject[EDU_CAREER]["OTHER_UG_DEGREE"];
      var other_pgDeg = editAppObject[EDU_CAREER]["OTHER_PG_DEGREE"];
      
      var gradDegID = '#'+gradDeg.key.toLowerCase();
      var postGradDegID = '#'+postGradDeg.key.toLowerCase();
      
// highest education is bachelor degree
        if( $(gradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').length === 1 && 
          $(gradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').hasClass('activeopt') === false
        )
        {
          showHideField(gradDeg,"show");
          showHideField(gradCollg,"show");
          showHideField(other_ugDeg,"show");

          $('#'+postGradCollg.key.toLowerCase()).val("");
          $('#'+other_pgDeg.key.toLowerCase()).val("");

          showHideField(postGradDeg,"hide");
          showHideField(postGradCollg,"hide");
          showHideField(other_pgDeg,"hide");
          
          if(gradDeg.isUnderScreen){
            showHideUnderScreeningMsg(gradDeg,"show");
          }
          
          if(gradCollg.isUnderScreen){
            showHideUnderScreeningMsg(gradCollg,"show");
          }
          
          if(other_ugDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_ugDeg,"show");
          }
          
          showHideUnderScreeningMsg(postGradDeg,"hide");
          showHideUnderScreeningMsg(postGradCollg,"hide");
          showHideUnderScreeningMsg(other_pgDeg,"hide");

        }
//highest education is master degree
        else if($(postGradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').length === 1 && 
          $(postGradDegID+' option[value=\"'+parseInt(eduLevelVal)+'\"]').hasClass('activeopt') === false
          )
        {
          showHideField(gradDeg,"show");
          showHideField(gradCollg,"show");
          showHideField(other_ugDeg,"show");
//PhD or MPhil values: 21 and 42 respectively
            showHideField(postGradDeg,"show");
          if(eduLevelVal != 42 && eduLevelVal != 21 && postGradDeg.value=='')
	{
            editedFields["career"]["DEGREE_PG"]= eduLevelVal;
          }
          showHideField(postGradCollg,"show");
          showHideField(other_pgDeg,"show");
          
          if(postGradDeg.isUnderScreen){
            showHideUnderScreeningMsg(postGradDeg,"show");
          }
          
          if(postGradCollg.isUnderScreen){
            showHideUnderScreeningMsg(postGradCollg,"show");
          }
          
          if(other_pgDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_pgDeg,"show");
          }
          
          if(gradDeg.isUnderScreen){
            showHideUnderScreeningMsg(gradDeg,"show");
          }
          
          if(gradCollg.isUnderScreen){
            showHideUnderScreeningMsg(gradCollg,"show");
          }
          
          if(other_ugDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_ugDeg,"show");
          }   
        }
        else{
          showHideField(gradDeg,"hide");
          showHideField(gradCollg,"hide");
          showHideField(other_ugDeg,"hide");

          showHideField(postGradDeg,"hide");
          showHideField(postGradCollg,"hide");
          showHideField(other_pgDeg,"hide");

          $('#'+gradCollg.key.toLowerCase()).val("");
          $('#'+other_ugDeg.key.toLowerCase()).val("");


          $('#'+postGradCollg.key.toLowerCase()).val("");
          $('#'+other_pgDeg.key.toLowerCase()).val("");
          
          showHideUnderScreeningMsg(postGradDeg,"hide");
          showHideUnderScreeningMsg(postGradCollg,"hide");
          showHideUnderScreeningMsg(other_pgDeg,"hide");

          showHideUnderScreeningMsg(gradDeg,"hide");
          showHideUnderScreeningMsg(gradCollg,"hide");
          showHideUnderScreeningMsg(other_ugDeg,"hide");
        }
    
      }
/*
      else if(fieldID.indexOf(BASIC) !==-1){
        if( ugDegreeMap.indexOf(eduLevelVal) != -1 )
        { 
          showHideField(gradDeg,"show");
          showHideField(gradCollg,"show");
          showHideField(other_ugDeg,"show");

          showHideField(postGradDeg,"hide");
          showHideField(postGradCollg,"hide");
          showHideField(other_pgDeg,"hide");
          
          showHideUnderScreeningMsg(postGradDeg,"hide");
          showHideUnderScreeningMsg(postGradCollg,"hide");
          showHideUnderScreeningMsg(other_pgDeg,"hide");
          
          if(gradDeg.isUnderScreen){
            showHideUnderScreeningMsg(gradDeg,"show");
          }
          
          if(gradCollg.isUnderScreen){
            showHideUnderScreeningMsg(gradCollg,"show");
          }
          
          if(other_ugDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_ugDeg,"show");
          }
        }
        else if(pgDegreeMap.indexOf(eduLevelVal) != -1  )
        {
          showHideField(gradDeg,"show");
          showHideField(gradCollg,"show");
          showHideField(other_ugDeg,"show");

          showHideField(postGradDeg,"show");
          showHideField(postGradCollg,"show");
          showHideField(other_pgDeg,"show");
          
          showHideUnderScreeningMsg(postGradDeg,"hide");
          showHideUnderScreeningMsg(postGradCollg,"hide");
          showHideUnderScreeningMsg(other_pgDeg,"hide");

          showHideUnderScreeningMsg(gradDeg,"hide");
          showHideUnderScreeningMsg(gradCollg,"hide");
          showHideUnderScreeningMsg(other_ugDeg,"hide");
          
          if(postGradDeg.isUnderScreen){
            showHideUnderScreeningMsg(postGradDeg,"show");
          }
          
          if(postGradCollg.isUnderScreen){
            showHideUnderScreeningMsg(postGradCollg,"show");
          }
          
          if(other_pgDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_pgDeg,"show");
          }
          
          if(gradDeg.isUnderScreen){
            showHideUnderScreeningMsg(gradDeg,"show");
          }
          
          if(gradCollg.isUnderScreen){
            showHideUnderScreeningMsg(gradCollg,"show");
          }
          
          if(other_ugDeg.isUnderScreen){
            showHideUnderScreeningMsg(other_ugDeg,"show");
          }
          
        }
        else{
          showHideField(gradDeg,"hide");
          showHideField(gradCollg,"hide");
          showHideField(other_ugDeg,"hide");

          showHideField(postGradDeg,"hide");
          showHideField(postGradCollg,"hide");
          showHideField(other_pgDeg,"hide");
          
          showHideUnderScreeningMsg(postGradDeg,"hide");
          showHideUnderScreeningMsg(postGradCollg,"hide");
          showHideUnderScreeningMsg(other_pgDeg,"hide");

          showHideUnderScreeningMsg(gradDeg,"hide");
          showHideUnderScreeningMsg(gradCollg,"hide");
          showHideUnderScreeningMsg(other_ugDeg,"hide");
        }
      }
*/
    }
    
    /*
     * onAboutChange
     * @param {type} aboutMeVal
     * @param {type} event
     * @returns {undefined}
     */
    onNameChange = function(nameVal,event){
	nameVal=$.trim(nameVal);
	var nameField = editAppObject[BASIC]['NAME'];
      var normalBorder ='edpbrd3';
      var errorBorder = 'brdr-1';
        var fieldParentID = '#'+nameField.key.toLowerCase()+'Parent';
      $(fieldParentID).find('.js-areaBox').removeClass(errorBorder).addClass(normalBorder);
      $(fieldParentID).find('.js-errorLabel').addClass(dispNone);
      requiredFieldStore.remove(nameField);
      
        var name_of_user=nameVal;
        name_of_user = name_of_user.replace(/\./gi, " ");
        name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
        name_of_user = name_of_user.replace(/\,|\'/gi, "");
        name_of_user = $.trim(name_of_user.replace(/\s+/gi, " "));
        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
        if($.trim(name_of_user)== "" || !allowed_chars.test($.trim(name_of_user))){
                setError(nameField,"Please provide a valid Full Name",1);
        }else{
                var nameArr = name_of_user.split(" ");
                if(nameArr.length<2){
                        setError(nameField,"Please provide your first name along with surname, not just the first name",1);
                }else{
                     unsetError(nameField);   
                }
        }
      storeFieldChangeValue(nameField,nameVal);
    }
    onAboutChange = function(aboutMeVal,event){
      
      var aboutMeField = editAppObject[ABOUT]["YOURINFO"];
      var fieldParentID = '#'+aboutMeField.key.toLowerCase()+'Parent';
      var fieldID = '#'+aboutMeField.key.toLowerCase();
      
      var normalBorder ='edpbrd3';
      var errorBorder = 'brdr-1';
         
      aboutMeVal = aboutMeVal.replace(/\s\s+/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
      
      $(fieldParentID).find('.js-areaBox').removeClass(errorBorder).addClass(normalBorder);
      $(fieldParentID).find('.js-errorLabel').addClass(dispNone);
      requiredFieldStore.remove(aboutMeField);
      
      if(aboutMeVal.length<100){
        $(fieldParentID).find('.js-areaBox').addClass(errorBorder).removeClass(normalBorder);
        $(fieldParentID).find('.js-errorLabel').removeClass(dispNone);
        requiredFieldStore.add(aboutMeField);
      }
      
      if(aboutMeVal.length>3000){
        $('#'+aboutMeField.key.toLowerCase()).val(aboutMeVal.substr(0,3000));
        storeFieldChangeValue(aboutMeField,aboutMeVal.substr(0,3000));
        $(fieldParentID).find('.js-aboutLength').html("Character Count : 3000");
        event.preventDefault();
        return;
      }
      
      storeFieldChangeValue(aboutMeField,aboutMeVal);
      
      $(fieldParentID).find('.js-aboutLength').html("Character Count : "+aboutMeVal.length)
    }
    
    /*
     * onPrivacyClick
     * @param {type} event
     * @returns {undefined}
     */
    onPrivacyClick = function(privacyVal,parentID){
      var fieldID = parentID.split("Parent")[0];
      
      var fieldObject = editAppObject[CONTACT][fieldID.toUpperCase()];
      $('#'+parentID).find(' ul li.activeopt').removeClass('activeopt');
      storeFieldChangeValue(fieldObject,privacyVal);
    }
    
    /*
     * onOwnerBtnClick
     * @param {type} event
     * @returns {undefined}
     */
    onOwnerBtnClick = function(event){
      var parentName = $(this).attr('parentName');    
      
      if(parentName == "phone_mobParent"){
        var mobileOwnerName = editAppObject[CONTACT]["MOBILE_OWNER_NAME"];
        var mobileNumberOwner = editAppObject[CONTACT]["MOBILE_NUMBER_OWNER"];
        
        $('#phone_mobParent').find('.js-ownerBtn').addClass(dispNone);
        showHideField(mobileOwnerName,"show");
        showHideField(mobileNumberOwner,"show");
      }
      
      if(parentName == "alt_mobileParent"){
        var altMobileOwnerName = editAppObject[CONTACT]["ALT_MOBILE_OWNER_NAME"];
        var altMobileNumberOwner = editAppObject[CONTACT]["ALT_MOBILE_NUMBER_OWNER"]; 
        
        $('#alt_mobileParent').find('.js-ownerBtn').addClass(dispNone);
        showHideField(altMobileOwnerName,"show");
        showHideField(altMobileNumberOwner,"show");
      }
      
      if(parentName == "phone_resParent"){
        var phoneMobileOwnerName = editAppObject[CONTACT]["PHONE_OWNER_NAME"];
        var phoneMobileNumberOwner = editAppObject[CONTACT]["PHONE_NUMBER_OWNER"];
        
        $('#phone_resParent').find('.js-ownerBtn').addClass(dispNone);
        showHideField(phoneMobileOwnerName,"show");
        showHideField(phoneMobileNumberOwner,"show");
      }
      
    }
    
    /*
     * onEmailChange
     * @param {type} event
     * @returns {undefined}
     */
    onEmailChange = function(event){
      emailCurrentId = false;
      savedEmail = editAppObject.contact.ALT_EMAIL.decValue;
      if ( event.target.id == "email" )
      {
        emailCurrentId = true; 
        savedEmail = editAppObject.contact.EMAIL.decValue;
        var parentID = '#emailParent';
        var fieldID = '#email';
        var emailField = editAppObject[CONTACT]['EMAIL'];
      }
      else
      {
        var parentID = '#alt_emailParent';
        var fieldID = '#alt_email';
        var emailField = editAppObject[CONTACT]['ALT_EMAIL'];
      }
      var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
      var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
      
      var emailVal = $(fieldID).val().trim().toLowerCase();
      var valid = true;
      var errorMsg = "";
      var normalBorder ='edpbrd3';
      var errorBorder = 'brdr-1';
      var self = $(fieldID);
            
      var checkInvalidDomain = function(){
        var value = emailVal;
        var start = value.indexOf('@');//starting point index of domain marked by @
        var end = value.lastIndexOf('.');//ending point index of domain marked by .
        var diff = end - start - 1;
        var user = value.substr(0, start);
        var len = user.length;
        var domain = value.substr(start + 1, diff).toLowerCase();//domain string:: substring from start to end point index

        if (invalidDomainArr.indexOf(domain.toLowerCase()) != -1)
          return false;
        else if (domain == 'gmail')
        {
          if (!(len >= 6 && len <= 30))
            return false;
        }
        else if (domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail')
        {
          if (!(len >= 4 && len <= 32))
            return false;
        }
        else if (domain == 'rediff')
        {
          if (!(len >= 4 && len <= 30))
            return false;
        }
        else if (domain == 'sify')
        {
          if (!(len >= 3 && len <= 16))
            return false;
        }
        return true;
      }
      

      if( $("#email").val().toLowerCase() == $("#alt_email").val().toLowerCase() && ( $("#alt_email").val().length > 0 ) && valid){
       valid = false;
       errorMsg = errorMap['SAME_EMAIL'];//Required
      }
      else
      {
        $("div p:contains('Same Email')").addClass('disp-none');
        $("div p:contains('Same Email')").parent('div').removeClass('brdr-1');
        $("div p:contains('Same Email')").parent('div').addClass('edpbrd3');
      }
      
      if(emailVal.length == 0){
        if ( emailCurrentId )
        {
          valid = false
          errorMsg = errorMap['EMAIL'];//Required
        }
      }
      //Pattern Match
      if(valid && !email_regex.test(emailVal)){
        if ( emailVal.length > 0)
        {
          valid = false
          errorMsg = errorMap['EMAIL_WRONG_FORMAT'];//Worng Format
        }
      }
      
      if(valid && !checkInvalidDomain()){
        valid = false
        errorMsg = errorMap['EMAIL_INVALID_DOMAIN'];//Invalid Domain
      }
      
      $(fieldID).parent().removeClass(errorBorder).addClass(normalBorder);
      $(parentID).find('.js-errorLabel').addClass(dispNone);
     
      if ( emailCurrentId )
      {
        if($('#emailAvailable').length == 1){
          $('#emailAvailable').addClass(dispNone);
        }
      }
      
      if(valid == false){
        $(fieldID).parent().removeClass(normalBorder).addClass(errorBorder);
        $(parentID).find('.js-errorLabel').text(errorMsg).removeClass(dispNone);
        return ;
      }
      
      if(emailVal == emailField.value.toLowerCase()){
        return ;
      }
      
      //Call Ajax
      if ( emailCurrentId )
      {
        var request = checkEmailStatus(emailVal);
        if(autoSuggestRequest.hasOwnProperty(emailField.key) === true){
          autoSuggestRequest[emailField.key].abort();
        }
        autoSuggestRequest[emailField.key] = request;
        request.done(function(data){

      
        if($('#emailAvailable').length == 0){
          self.parent().append($("<p />",{class:"avaliableTop pos-abs f13",id:'emailAvailable'}));
        }
        if(data == "not"){
          $('#emailAvailable').text('Available').addClass('colorAva').removeClass('color5').removeClass(dispNone);
        }
        if(data == "exist"){
          $('#emailAvailable').text('Not Available').addClass('color5').removeClass('colorAva').removeClass(dispNone);
        }
      
      });
    }
    }
    
    /*
     * onPincodeChange
     * @param {type} event
     * @returns {undefined}
     */
    onPincodeChange = function(event){
           
      var fieldKey = event.target.id;
      var pincode = $(event.target).val();
      var pincodeArr={'DE00':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Pincode should belong to Delhi"},"MH04":{0:["400","401","410","421","416"],1:3,2:"Pincode should belong to Mumbai"},"MH08":{0:["410","411","412","413"],1:3,2:"Pincode should belong to Pune"}};

      var cityField = editAppObject[BASIC]["CITY_RES"];
      var validBorder = 'edpbrd3';
      var invalidBorder = 'brdr-1';
      
      var errorMsg = "";
      var valid = true;
      
      if(pincode.toString().length < 6)
      {
        errorMsg = errorMap['PINCODE_ERROR'];
        valid = false;
      }
      
      if(fieldKey.indexOf('parent') === -1 && cityField.value.length && pincodeArr.hasOwnProperty(cityField.value)){
        var initial = pincode.toString().substring(0,pincodeArr[cityField.value][1]);
                
        if(valid && pincodeArr[cityField.value][0].indexOf(initial) === -1)
        { 
          errorMsg = pincodeArr[cityField.value][2];
          valid = false;
        } 
      }
      
      if(pincode.toString().length == 0){
        errorMsg = "";
        valid = true;
      }
      
      $('#'+fieldKey+'Parent').removeClass(invalidBorder).addClass(validBorder);
      $('#'+fieldKey+'Parent').find('.js-errorLabel').addClass(dispNone);
      if(valid === false){
        $('#'+fieldKey+'Parent').removeClass(validBorder).addClass(invalidBorder);
        $('#'+fieldKey+'Parent').find('.js-errorLabel').text(errorMsg);
        $('#'+fieldKey+'Parent').find('.js-errorLabel').removeClass(dispNone);
      }
    }
    
    /*
     * onTimeFieldClick
     * @param {type} event
     * @returns {undefined}
     */
    onTimeFieldClick = function(event){
      
      var id = event.target.id;
      
      if(id.length == 0 && event.target.tagName == "DIV" ){
        id = $(event.target).find('input').attr("id");
      }
      
      if( editedFields.hasOwnProperty(CONTACT) == false ){
        editedFields[CONTACT] = {};
        editedFields[CONTACT]["TIME_TO_CALL_START"] = {};
        editedFields[CONTACT]["TIME_TO_CALL_END"] = {};
      }
      
      if(event.target.tagName == "DIV" || event.target.tagName == "INPUT" ){
        var arrTimeField = ['startCall','startAmPm','endCall','endAmPm'];
        var storeKey = "";
        $('.js-'+id).removeClass(dispNone);

        for(var j=0;j<arrTimeField.length;j++){
  
          if(id != arrTimeField[j])
            $('.js-'+arrTimeField[j]).addClass(dispNone);
          
          var storeKey = 'TIME_TO_CALL_END';
          var startOrEnd = "end";
          var subKey = "";
          
          if(arrTimeField[j].indexOf('start') !== -1){
            storeKey = 'TIME_TO_CALL_START';
            startOrEnd = "start";
          }
          
          if(arrTimeField[j].indexOf('Call') !== -1){
            subKey = 'time_to_call_' + startOrEnd;
          }else{
            subKey = startOrEnd + '_am_pm'; 
          }
          
          if(editedFields[CONTACT].hasOwnProperty(storeKey) == false ){
            editedFields[CONTACT][storeKey] = {};
          }
          var timeVal = $('#'+arrTimeField[j]).val().toLowerCase();
          if( timeVal == "-"){
            timeVal = "";
          }
          editedFields[CONTACT][storeKey][subKey] = timeVal;
        }
      }
      
      if(event.target.tagName == "LI"){
        $(event.target).parent().find('.activeopt').removeClass('activeopt');
        $(event.target).addClass('activeopt');
        $(event.currentTarget).find('input').val($(event.target).text());
       
        var storeKey = 'TIME_TO_CALL_END';
        var startOrEnd = "end";
        var subKey = "";
        var inputID = $(event.currentTarget).find('input').attr('id');
        if(inputID.indexOf('start') !== -1){
          storeKey = 'TIME_TO_CALL_START';
          startOrEnd = "start";
        }

        if(inputID.indexOf('Call') !== -1){
          subKey = 'time_to_call_' + startOrEnd;
        }else{
          subKey = startOrEnd + '_am_pm'; 
        }

        if(editedFields[CONTACT].hasOwnProperty(storeKey) == false ){
          editedFields[CONTACT][storeKey] = {};
        }
        editedFields[CONTACT][storeKey][subKey] = $(event.target).text().toLowerCase();
        
        var arrTimeField = ['startCall','startAmPm','endCall','endAmPm'];
      
        $('#startCall').parent().parent().removeClass('brdr-1');
        $('#endCall').parent().parent().removeClass('brdr-1');
        $('#time_to_callParent').find('.js-errorLabel').addClass(dispNone);
        
        if($('#startCall').val().length === 0 || $('#startAmPm').val().length === 0){
          $('#startCall').parent().parent().addClass('brdr-1');
          $('#time_to_callParent').find('.js-errorLabel').removeClass(dispNone);
        }

        if($('#endCall').val().length === 0 || $('#endAmPm').val().length === 0){
          $('#endCall').parent().parent().addClass('brdr-1');
          $('#time_to_callParent').find('.js-errorLabel').removeClass(dispNone);
        }
        
        $('.js-'+inputID).addClass(dispNone);  
      }
    }
    
    /*
     * onTimeFieldBlur
     * @param {type} event
     * @returns {undefined}
     */
    onTimeFieldBlur = function(event){

      var arrTimeField = ['startCall','startAmPm','endCall','endAmPm'];
      
      for(var j=0;j<arrTimeField.length;j++){
        $('.js-'+arrTimeField[j]).addClass(dispNone);
      }
      
      if($('#startCall').val() != "-" && $('#startCall').val().length && $('#startAmPm').val().length && $('#startAmPm').val() != "-"){
        $('#startCall').parent().parent().removeClass('brdr-1');
      }
      
      if($('#endCall').val().length && $('#endAmPm').val().length && $('#endCall').val() != "-" && $('#endAmPm').val() != "-"){
        $('#endCall').parent().parent().removeClass('brdr-1');
      }
      
      if($('#startCall').val().length && $('#startAmPm').val().length && $('#endCall').val().length && $('#endAmPm').val().length && $('#startCall').val() != "-" && $('#startAmPm').val() != "-" && $('#endCall').val() != "-" && $('#endAmPm').val() != "-"){
        $('#time_to_callParent').find('.js-errorLabel').addClass(dispNone);
      }
    }
    validateImage = function(fieldId,fieldKey){
        if(typeof $('#' + fieldId)[0].files[0] == 'undefined' || $('#' + fieldId)[0].files[0] == null){
                return false;
        }
        var file = $('#'+fieldId)[0].files[0];
        var nameArr = file.name.split(".");
        var fileExt = nameArr[nameArr.length-1];
        if (file && fileExt == "jpg" || fileExt == "JPG" || fileExt == "jpeg" || fileExt == "JPEG" || fileExt == "PDF" || fileExt == "pdf") {
        } else {
            $("#idlabel_" + fieldId).html('jpg/pdf only');
            setError(fieldKey,'Invalid file format',1);
            return false;
        }
        if(file.size > 5242880) {
                $("#idlabel_" + fieldId).html('jpg/pdf only');
                setError(fieldKey,'File size exceeds limit (5MB)',1);
                return false;
        } else {
                $("#idlabel_" + fieldId).html(file.name);
                storeFieldChangeValue(fieldKey,file);
                unsetError(fieldKey,'');
                return file;
        }
    }
    onIdProofTypeChange = function(){
        var t1 = geteditedValue("ID_PROOF_TYPE");
        var v1 = geteditedValue("ID_PROOF_VAL","VALUE");
        var t2 = geteditedValue("ADDR_PROOF_TYPE","VALUE");
        var v2 = geteditedValue("ADDR_PROOF_VAL","VALUE");
        onvaluechange(t1,v1,t2,v2,editAppObject["verification"]["ID_PROOF_ADDR"]);
    }
    onIdProofValChange = function(){
        var t1 = geteditedValue("ID_PROOF_TYPE");
        var v1 = 1;
        var t2 = geteditedValue("ADDR_PROOF_TYPE","VALUE");
        var v2 = geteditedValue("ADDR_PROOF_VAL","VALUE");
        onvaluechange(t1,v1,t2,v2,editAppObject["verification"]["ID_PROOF_VAL"]);
        $('#id_proof_val').attr("value",'');
        $('#id_proof_val').val("");
    }
    
    onAddrProofTypeChange = function(){
        var t1 = geteditedValue("ID_PROOF_TYPE","VALUE");
        var v1 = geteditedValue("ID_PROOF_VAL","VALUE");
        var t2 = geteditedValue("ADDR_PROOF_TYPE");
        var v2 = geteditedValue("ADDR_PROOF_VAL","VALUE");
        onvaluechange(t1,v1,t2,v2,editAppObject["verification"]["ADDR_PROOF_TYPE"]);
        
    }
    onAddrProofValChange = function(){
        var t1 = geteditedValue("ID_PROOF_TYPE","VALUE");
        var v1 = geteditedValue("ID_PROOF_VAL","VALUE");
        var t2 = geteditedValue("ADDR_PROOF_TYPE");
        var v2 = 1;
        onvaluechange(t1,v1,t2,v2,editAppObject["verification"]["ADDR_PROOF_VAL"]);
        $('#addr_proof_val').attr("value",'');
        $('#addr_proof_val').val("");
    }
    
    onMstatusProofValChange = function(){
        var mstatusProofTypeField = editAppObject[CRITICAL]["MSTATUS_PROOF"];
        var uploadStatus = validateImage('mstatus_proof',mstatusProofTypeField);
        if(uploadStatus === false){
                return false;
        }else{
                if(mstatusProofTypeField.value !== '' && typeof editedFields[CRITICAL]["MSTATUS_PROOF"] == 'undefined'){
                        storeFieldChangeValue(mstatusProofTypeField,mstatusProofTypeField.value);
                }
        }
    }
    
    geteditedValue = function(fieldKey,fieldtype){
        var fieldObj = editAppObject["verification"][fieldKey];
        if(fieldtype != "VALUE" && fieldObj.value != '' && typeof editedFields[VERIFICATION][fieldKey] == 'undefined'){
               // editedFields["verification"][fieldKey] = fieldObj.value;
                storeFieldChangeValue(fieldObj,fieldObj.value);
        }
        return editedFields["verification"][fieldKey];
    }
    onProofTypeChangeError = function(fieldKey,errorMsg,showHideError){
        if(showHideError === 1){
                if(errorMsg != ''){
                        $('#'+fieldKey+'Parent').find('.js-errorLabel').text(errorMsg);
                }
                $('#'+fieldKey+'Parent').find('.js-errorLabel').removeClass(dispNone);
        }else{
                $('#'+fieldKey+'Parent').find('.js-errorLabel').addClass(dispNone);
        } 
    }
    onvaluechange = function(t1,v1,t2,v2,calledBy){
        var idProofTypeField = editAppObject["verification"]["ID_PROOF_TYPE"];
        var idProofValField = editAppObject["verification"]["ID_PROOF_VAL"];
        var addrProofValField = editAppObject["verification"]["ADDR_PROOF_VAL"];
        var addrProofTypeField = editAppObject["verification"]["ADDR_PROOF_TYPE"];
        if(v1){
                v1 = validateImage('id_proof_val',idProofValField);
        }
        if(v2){
                v2 = validateImage('addr_proof_val',addrProofValField);
        }
        if(v1 === false || v2 === false){
                return false;
        }
            if(!t1 && v1){
                    var showMsg = 1;
                    if(calledBy !=  idProofTypeField){
                       showMsg = 0;
                    }
                    setError(idProofTypeField,'',showMsg);
                    unsetError(idProofValField);
            }
            if(t1 && !v1){
                    var showMsg = 1;
                    if(calledBy !=  idProofValField){
                       showMsg = 0;
                    }
                    setError(idProofValField,'',showMsg);
                    unsetError(idProofTypeField);
            }
            if(!t2 && v2){
                    var showMsg = 1;
                    if(calledBy !=  addrProofTypeField){
                       showMsg = 0;
                    }
                    setError(addrProofTypeField,'',showMsg);
                    unsetError(addrProofValField);
            }
            if(t2 && !v2){
                    var showMsg = 1;
                    if(calledBy !=  addrProofValField){
                       showMsg = 0;
                    }
                    setError(addrProofValField,'',showMsg);
                    unsetError(addrProofTypeField);
            }
            if(t2 || v2){
                 if(!t1 && !v1){
                    unsetError(idProofTypeField);
                    unsetError(idProofValField);
                } 
            }
    }
    setError = function(fieldKey,msg,showMsg){
            requiredFieldStore.add(fieldKey);
            if(showMsg == 1)
                onProofTypeChangeError(fieldKey.key.toLowerCase(),msg,showMsg);
    }
    unsetError = function(fieldKey){
            requiredFieldStore.remove(fieldKey);
            onProofTypeChangeError(fieldKey.key.toLowerCase(),'',0);
    }
    onIdProofNumberChange = function(event){
        var fieldKey = event.target.id;
        var idTypeNum = $(event.target).val();
        var idType = $("#id_proof_type").val();
        var idProofTypeField = editAppObject[VERIFICATION]["ID_PROOF_TYPE"];
       
        if(idType == "" || idType == null)
            requiredFieldStore.add(idProofTypeField);
        
        var idProofNumberField = editAppObject[VERIFICATION]["ID_PROOF_NO"];
        requiredFieldStore.remove(idProofNumberField);
        
        var validBorder = 'edpbrd3';
        var invalidBorder = 'brdr-1';
        switch(idType){
            case "P":
                pattern=/^[a-zA-Z]\d{7}$/;
                break;
            case "U":
                pattern=/^\d{12}$/;
                break;
            case "V":
                pattern=/^[a-zA-Z]{3}\d{7}$/;
                break;
            case "N":
                pattern=/^[a-zA-Z]{5}\d{4}[a-zA-Z]$/;
                break;
            case "D":
                if(idTypeNum.length>18)
                    var invalid=true;
                pattern=/^.*[^\s].*[^\s].*[^\s].*[^\s].*$/;
                break;
            default:
                pattern=/.*/;
                break;
        }
        $('#'+fieldKey+'Parent'+ ' div').removeClass(invalidBorder).addClass(validBorder);
        $('#'+fieldKey+'Parent').find('.js-errorLabel').addClass(dispNone);
        if(idTypeNum.match(pattern) && !invalid){
        }
        else{
            $('#'+fieldKey+'Parent'+ ' div').removeClass(validBorder).addClass(invalidBorder);
            $('#'+fieldKey+'Parent').find('.js-errorLabel').text("Invalid");
            $('#'+fieldKey+'Parent').find('.js-errorLabel').removeClass(dispNone);
            requiredFieldStore.add(idProofNumberField);
        }
    }
    
    /*
     * requiredFieldStore
     * @param {type} fieldObject
     * @returns {undefined}
     */
    var requiredFieldStore = (function(){
            
      return {
        add : function(fieldObject){
          if(requiredArray[fieldObject.sectionId].hasOwnProperty(fieldObject.key) === false ){
            requiredArray[fieldObject.sectionId][fieldObject.key] =fieldObject.key;
          }
        },
        remove : function(fieldObject){
          if(requiredArray[fieldObject.sectionId].hasOwnProperty(fieldObject.key) === true ){
            delete requiredArray[fieldObject.sectionId][fieldObject.key];
          }
        },
        removeAll : function(sectionId){
          requiredArray[sectionId] = {};          
        }
      }
    })();
    
    /*
     * bindBehaviour
     * @returns {undefined}
     */
    bindBehaviour = function(){
      
      //Name Filed KeyDown Behaviour
      $('.js-onlyChar').unbind('keydown').on('keydown',function(event){
        whiteListingKeys(event,"onlyChars");
        var self = $(this);
        setTimeout(function(){
          var regex = /[^a-zA-Z'. ]+/g;
          var value = self.val();
          value = value.trim().replace(regex,"");
          if(value != self.val().trim())
            self.val(value);
        },0);
      });
      
      //Wight KeyDown Behaviour
      $('.js-onlyNumber').unbind('keydown').on('keydown',function(event){
        if(maxLengthMap.hasOwnProperty($(this).attr("id").toUpperCase())){
          var limitDigit = maxLengthMap[$(this).attr("id").toUpperCase()];
        }
        whiteListingKeys(event,"onlyNums",$(this).val(),limitDigit);
        var self = $(this);
        setTimeout(function(){
          var regex = /^0*/g;
          var onlyNumberRegex = /[^0-9]*/g;
          var value = self.val();
          value = value.trim().replace(regex,"").replace(onlyNumberRegex,"");
          if(value != self.val().trim() && typeof limitDigit != "undefined")
            self.val(value.substr(0,limitDigit));
        },0);
        
      });
      //Bind Sanitize on Number
      $('.js-onlyNumber').on('input propertychange',function(event){
        whiteListingKeys(event,"sanitizeNumber");
      })
      //OnCountry Change
      $('.js-country').on('change',function(event){
        onCountryChange($('#country_res').val());
      });
      $('.js-caste').on('change',function(event){
	onCasteChange($("#caste").val());
      });
      
      //OnState Change
      $('.js-state').on('change',function(event){
        onStateChange($('#state_res').val());
      });
      
      //OnCity Change
      $('.js-city').on('change',function(event){
        onCityChange($("#city_res").val());
      });
      
      //OnChallenged Change
      $('.js-handicapped').on('change',function(event){
        onChallengedChange($('#handicapped').val());
      });
      
      //OnNativeState Change
      $('.js-nativeState').on('change',function(event){
        onNativeStateChange($('#native_state').val());
      });
      
      //OnNativeState Change
      $('.js-nativeCity').on('change',function(event){
        onNativeCityChange($('#native_city').val());
      });
      
      //OnAmritdhariChange
      $('.js-amritdhari').on('box-change',function(event){
        onAmritdhariChange($('#amritdhari ul li.activeopt').attr("value"));
      });
      
      //OnHighest Change
      $('.js-educationChange').on('change',function(event){
        onHighestEducationChange($(this).val(),$(this).attr("id"));
      });
      $('.js-mstatus').on('change',function(event){
        onMstatusChange($(this).val(),$(this).attr("id"));
      });
      $('.js-mstatus_proof').on('change',onMstatusProofValChange);
      $('#idBtn_mstatus_proof').unbind('click').on('click',function(event){
        $('.js-mstatus_proof').click();
      });
      //About me field
      $('.js-aboutMe').on('keydown',function(event){
        if(false == whiteListingKeys(event,"forAbout")){
          return false;
        }
        setTimeout(function(){
          onAboutChange($('#yourinfo').val(),event);
        },0);
        
      });
      $(".js-name").on("change",function(event){
	setTimeout(function(){onNameChange($("#name").val(),event);},0);
	});
      //Privacy setting
      $('.js-privacySetting').on('click',function(event){
        if(event.target && event.target.tagName === "LI"){
          onPrivacyClick($(event.target).attr('value'),$(this).parent().attr("id"));
          $(event.target).addClass("activeopt");
          $(event.currentTarget).addClass(dispNone);
        }
      });
      
      //Privacy Icon On mouse Enter
      $('.showset').on('mouseenter',function(){
        $(this).find('.js-privacySetting').removeClass(dispNone);
        $('.js-timeClick').trigger('timeBlur');
      });
      
      //Privacy Icon On Mouse leave
      $('.showset').on('mouseleave',function(){
        $(this).find('.js-privacySetting').addClass(dispNone);
      });
      
      //Owner Btn
      $('.js-ownerBtnClick').on('click',onOwnerBtnClick);
      
      //Email Change
      $('.js-email').unbind('change');
      $('.js-email').unbind('blur');
      $('.js-email').on('keydown',function(event){
        var self = $(this);
        setTimeout(function(){
          self.trigger('storeOnly');
          onEmailChange(event)
        },0);
      });
      $('.js-aadhaar').on('keydown',function(event){
        return false;
      });
      
      //Save Btn
      $('.js-save').unbind('click').on('click',function(event){
                var currId = $(this).attr("id");
                if(currId =="saveBtncritical"){
                                for(var key in requiredArray['critical']){
                                        var parentDOM = $('#'+key.toLowerCase()+'Parent'); 
                                        parentDOM.find('.js-errorLabel').removeClass(dispNone);
                                      }


                                //Check Any Error Lable is visible or not
                                var validationCheck = '#criticalEditForm' +' .js-errorLabel:not(.disp-none)';   
                                if($(validationCheck).length !== 0 && $(validationCheck).length !== "0"){
                                  $(document).scrollTop($(validationCheck).offset().top);
                                  return;
                                }
                                // condition to show msg on the popup 
                                var diffDays = 0;
                                if(editAppObject[CRITICAL]['DTOFBIRTH'] && editedFields.hasOwnProperty(CRITICAL) && editedFields[CRITICAL].hasOwnProperty("DTOFBIRTH")){
                                        var prevDob = editAppObject[CRITICAL]['DTOFBIRTH'].value.split("-");
                                        var Dob = editedFields[CRITICAL]['DTOFBIRTH'].split("-");
                                        var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
                                        var firstDate = new Date(parseInt(Dob[0]),parseInt(Dob[1])-1,parseInt(Dob[2]));
                                        var secondDate = new Date(parseInt(prevDob[0]),parseInt(prevDob[1])-1,parseInt(prevDob[2]));
                                        var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
                                }
                                
                                var showClass = "msg2";
                                var hideClass = "msg1";
                                if(diffDays > 730){
                                        showClass = "msg1";
                                        hideClass = "msg2";
                                }     
                                if(hideClass != "msg2" && editedFields.hasOwnProperty(CRITICAL) && editedFields[CRITICAL].hasOwnProperty("MSTATUS") ){
                                        var prevMstatus = editAppObject[CRITICAL]['MSTATUS'].value;
                                        var Mstatus = editedFields[CRITICAL]['MSTATUS'];
                                        if((prevMstatus == "N" && Mstatus != "N") || prevMstatus != "N" && Mstatus == "N"){
                                                showClass = "msg1";
                                                hideClass = "msg2";
                                        }
                                }
                                if(editedFields.hasOwnProperty("critical") === false || (editedFields.hasOwnProperty("critical") === true && !editedFields[CRITICAL].hasOwnProperty("DTOFBIRTH") && !editedFields[CRITICAL].hasOwnProperty("MSTATUS"))){
                                        showHideEditSection("critical","hide");
                                        return;
                                      }
                                // condition to show msg on the popup end
                                $("#commonOverlay").fadeIn("fast",function(){
                                $("#commonOverlay").on('click',function(){
                                    $(".confirmationBox").fadeOut("fast",function(){
                                        $("#commonOverlay").fadeOut("fast"); 
                                    });
                                }); 
                                $(".btn-popup-cnfrm").on('click',function(){
                                        onSectionSave("critical");
                                        $(".confirmationBox").fadeOut("fast",function(){
                                            $("#commonOverlay").fadeOut("fast"); 
                                        });
                                });
                                $(".btn-popup-cancel").on('click',function(){
                                        $(".confirmationBox").fadeOut("fast",function(){
                                            $("#commonOverlay").fadeOut("fast"); 
                                        });
                                });
                                $(".confirmationBox").fadeIn("fast"); 
                                $("."+showClass).removeClass("disp-none"); 
                                $("."+hideClass).addClass("disp-none"); 
                        });
                }else{
                        onSectionSave(this.id.split('saveBtn')[1]);
                }
      });
      
      //Save Btn Keydown
      $('.js-save').unbind('keydown').on('keydown',function(event){
        if(event.keyCode === 13){
          onSectionSave(this.id.split('saveBtn')[1]);
        }
      });
      
      //Cancel Btn
      $('.js-cancel').unbind('click').on('click',function(event){
        onSectionCancel(this.id.split('cancelBtn')[1]);
      });
      
      //Cancel Btn Keydown
      $('.js-cancel').unbind('keydown').on('keydown',function(event){
        if(event.keyCode === 13){
          onSectionCancel(this.id.split('cancelBtn')[1]);
        }
      });
      
      //NativeFields toggle
      $('.js-toggleNativeFields').bind('click',function(event){
        var toggleVal = $(this).attr('id');
        var natCountryField = editAppObject[FAMILY]["NATIVE_COUNTRY"];
        var natStateField = editAppObject[FAMILY]["NATIVE_STATE"];
        var natCityField = editAppObject[FAMILY]["NATIVE_CITY"];
        var ancestralOrigin = editAppObject[FAMILY]["ANCESTRAL_ORIGIN"];
        
        if(toggleVal == "fromIndia"){
          showHideField(natCountryField,"hide",true)
          showHideField(natStateField,"show",true);
          storeFieldChangeValue(natCountryField,"51");
        }
        
        if(toggleVal == "notFromIndia"){
          showHideField(natStateField,"hide",true);
          showHideField(natCountryField,"show",true);
          storeFieldChangeValue(natCountryField,"");
          $("#native_state").val("");
          $("#native_state").trigger(chosenUpdateEvent);
          showHideField(natCityField,"hide",true);
          showHideField(natStateField,"hide",true);
          showHideField(ancestralOrigin,"hide",true);
          showHideUnderScreeningMsg(ancestralOrigin,"hide");
        }
        
      });
      
      //Pincode 
      $('.js-pincode').unbind('change');
      $('.js-pincode').unbind('blur');
      $('.js-pincode').bind('keydown',function(event){
        
        if(false == whiteListingKeys(event,"onlyNums")){
          return false;
        }
        var self = $(this);
        setTimeout(function(){
          self.trigger('storeOnly');   
          onPincodeChange(event);
        },0); 
      });
       //Bind Sanitize on Number
      $('.js-pincode').on('input propertychange',function(event){
        whiteListingKeys(event,"sanitizeNumber");
      })
      //Time Field Click
      $('.js-timeClick').on('click',function(event){
        onTimeFieldClick(event);
      });
      
      $('.js-timeClick').on('focus',function(event){
        onTimeFieldClick(event);
      });
      
      //Time Field Blur     
      $('.js-timeClick').on('timeBlur',onTimeFieldBlur);
      
      //ID Proof Change
      $('.js-proofType').on('change',onIdProofTypeChange);
      
      $('.js-proofVal').on('change',onIdProofValChange);
      $('#idBtn_id_proof_val').unbind('click').on('click',function(event){
        $('.js-proofVal').click();
      });
      
      
      $('#idBtn_addr_proof_val').unbind('click').on('click',function(event){
        $('.js-addrProofVal').click();
      });
      
      $('.js-addrProofVal').on('change',onAddrProofValChange);
      $('.js-addrProofType').on('change',onAddrProofTypeChange);
      
      $('.js-proofTypeNo').on('keydown',function(event){
            setTimeout(function(){
                onIdProofNumberChange(event);
            },0);
      });
      
      //About Field KeyDown Behaviour
      $('.js-forAbout').unbind('keydown').on('keydown',function(event){
        whiteListingKeys(event,"forAbout")
      });
      $('#daysub').on('click',function(event){
              clickCallBack("day",event,$(this).attr("rel"));
      });
       $('#verify-aadhaar').mouseover(function(event) {
              $(this).find(".aadhardiv").removeClass("edpbox1");
      });
      $("#verify-aadhaar").mouseout(function(event){
              $(this).find(".aadhardiv").addClass("edpbox1");
      });
      $("#verify-aadhaar").bind('click',function(event){
                var url="/static/criticalActionLayerDisplay";
                var ajaxData={'layerId':24};
                var ajaxConfig={'data':ajaxData,'url':url,'dataType':'html'};


                ajaxConfig.success=function(response){
                  $('body').css('overflow','hidden');
                  $('body').prepend(response);
                  showLayerCommon('criticalAction-layer');
                  $('.js-overlay').unbind('click');
                }

                $.myObj.ajax(ajaxConfig);
    
      });
 

      $('#cancelBtncritical').on('click',function(event){
                inputData = {};
                var dob = editAppObject[CRITICAL]['DTOFBIRTH'].value.split("-");
                $("#day_value").html("Day");
                $("#month_value").html("Month");
                $("#year_value").html("Year");
                $("#day_value").attr("rel",dob[2]);
                dob[1] = dob[1].replace(/^0+/, '');
                dob[1] = dataMonthArray[dob[1]];
                $("#month_value").attr("rel",dob[1]);
                $("#year_value").attr("rel",dob[0]);
                $.each(criticalSectionArray,function(key,data)
                {
                        if(dateTypeFields.indexOf(data) !== -1){
                                hideShowList(event,data.toLowerCase());
                                $("#"+data.toLowerCase()).find("#daysub").find(".activeopt").removeClass("activeopt");
                                $("#"+data.toLowerCase()).find("#monthsub").find(".activeopt").removeClass("activeopt");
                                $("#"+data.toLowerCase()).find("#yearsub").find(".activeopt").removeClass("activeopt");
                        }
                });
      });
      $('#monthsub').on('click',function(event){
              clickCallBack("month",event,$(this).attr("rel"));
      });
      $('#yearsub').on('click',function(event){
              clickCallBack("year",event,$(this).attr("rel"));
              callBlur = 1; 
             $("#dtofbirth").trigger("blur");
      });
    }
    function clickCallBack (selectField,eve,sectionId) {
        eve.stopPropagation();
        var target = $(eve.target);
        //check if the target of click in whole of the sublist is a li
        if (target.is("li")) {
          $("#"+sectionId).find("#" + selectField + "_value").html(target.html());
          //for month values are replaced by month numbers
          if (selectField == "month"){
            $("#"+sectionId).find("#" + selectField + "_value").val(eve.target.id.substr(7, eve.target.id.length));
           }else{
            $("#"+sectionId).find("#" + selectField + "_value").val(target.html());
                }
                $("#"+sectionId).find("#" + selectField + "_value").attr("rel",target.html());
          $("#"+sectionId).find("#" + selectField + "sub").find(".activeopt").removeClass("activeopt");
          target.addClass("activeopt");
          $(this).parent().hide();
          hideShowList(eve,sectionId);
          highlightLI(sectionId,selectField,"I");
        }
    }
    hideShowList = function(con,sectionId) {
                $.each(dateDataArray,function(key1,data1)
                {
                        $.each(data1,function(key2,data2)
                        {
                              $.each(data2,function(value,label)
                              {
                                    $("#"+sectionId).find(".js-"+label.toLowerCase()).attr("style","display:none");
                                    $("#"+sectionId).find("#"+label.toLowerCase()+"Arrow1").attr("style","display:none");
                                    if (con == "blur") {
                                        $("#"+sectionId).find("#"+label.toLowerCase()+"Arrow2").attr("style","display:block");
                                    }
                              })
                        })
                })
    }
    highlightLI = function(sectionId,clickedField,INITIAL) { // INITIAL value to show next or currnet li
                var activeClass = 'activeopt';
                var arrSelectedEle = $("#"+sectionId).find(".js-boxContent").find(".boxType").find('li.'+activeClass);
                for(var i=0;i<arrSelectedEle.length;i++){
                        $(arrSelectedEle[i]).removeClass(activeClass);
                }
                if(INITIAL == "S"){
                        $.each(dateDataArray,function(key1,data1)
                        {
                                $.each(data1,function(key2,data2)
                                {
                                      $.each(data2,function(value,label)
                                      {
                                                var display = "display:none";
                                                var display2 = "display:none";
                                                if(label.toLowerCase() == clickedField){
                                                        var display = "display:block";
                                                }else{
                                                        var display2 = "display:block";
                                                }
                                                $("#"+sectionId).find(".js-"+label.toLowerCase()).attr("style",display);
                                                $("#"+sectionId).find("#"+label.toLowerCase()+"Arrow1").attr("style",display);
                                                $("#"+sectionId).find("#"+label.toLowerCase()+"Arrow2").attr("style",display2);

                                      })
                              })
                        })
                        $("#"+sectionId).find('ul li span[id="'+clickedField+'_value"]').parent("li").addClass(activeClass);
                }else{
                        if(INITIAL == "I"){
                                if (clickedField == "day") {
                                        $("#"+sectionId).find(".js-month").attr("style","display:block");
                                        $("#"+sectionId).find("#monthArrow1").attr("style","display:block");
                                        $("#"+sectionId).find("#monthArrow2").attr("style","display:none");
                                        $("#"+sectionId).find("#dayArrow2").attr("style","display:block");
                                        $("#"+sectionId).find('ul li span[id="month_value"]').parent("li").addClass(activeClass);
                                }
                                //open year sublist on month list click
                                if (clickedField == "month") {
                                        $("#"+sectionId).find(".js-year").attr("style","display:block");
                                        $("#"+sectionId).find("#yearArrow1").attr("style","display:block");
                                        $("#"+sectionId).find("#yearArrow2").attr("style","display:none");
                                        $("#"+sectionId).find("#monthArrow2").attr("style","display:block");
                                        $("#"+sectionId).find('ul li span[id="year_value"]').parent("li").addClass(activeClass);
                                }
                                if (clickedField == "year") {
                                        $("#"+sectionId).find("#yearArrow2").attr("style","display:block");
                                }
                        }
                }
    }
    /*
     * BakeEditAppObject
     * @returns {undefined}
     */
    bakeEditAppObject = function(){
      getEditData();//.success(storeData);
    }
    
    /*
     * fillSection : Filled the section with editAppObject Data 
     * @param {String} sectionId
     * @returns {undefined}
     */
    fillSection = function(sectionId){
      var sectionArray = getSectionArray(sectionId);
       //Add Fields as per FieldType
      for(var i=0;i<sectionArray.length;i++){
        var fieldKey = sectionArray[i];
        
        if(duplicateEditFieldMap.hasOwnProperty(fieldKey+'_'+sectionId)){
          fieldKey = fieldKey+'_'+sectionId;
        }
        if(false === editAppObject[sectionId].hasOwnProperty(fieldKey)){
          if(debugInfo){
            console.log(fieldKey +' field in section : ' + sectionId +' does not exist for this profile');
          }
          continue;
        }
          
        var fieldObject = editAppObject[sectionId][fieldKey];
        
        if(fieldObject.key=="CITY_RES"){
          var countryVal = editAppObject[BASIC]['COUNTRY_RES'].value;
          if(countryVal!='51' || (countryVal!='128'))
              continue;
        }
        else if(fieldObject.key=="STATE_RES"){
          if(editAppObject[BASIC]['COUNTRY_RES'].value!='51'){
            continue;
          }
        }
        else if(typeof fieldObject == "undefined"){
          if(debugInfo)
            console.log("i : " + i);
          continue;
        }
        
        var fieldId = '#'+fieldObject.key.toLowerCase();
        var fieldParentId = fieldId + 'Parent';
        var fieldParentLabel = fieldId + 'LabelParent';
        
        var errorBorderClass = 'brdr-1';
        var normalBorderClass = 'edpbrd3';
        //For Open Text Field
        if(fieldObject.type === DATE_TYPE){
                
                $(fieldId).find(".js-errorLabel").addClass(dispNone);
        }
        if(fieldObject.type === OPEN_TEXT_TYPE){
          var value = fieldObject.decValue;
          
          if(typeof value == "string" && value.length && value.indexOf("kg") && fieldObject.key == "WEIGHT"){
            value = fieldObject.value;
          }
          
          if(debugInfo)
            console.log(fieldObject.key+' : '+value);
          
          $(fieldParentId).find('.js-errorLabel').addClass(dispNone);
          
          if($(fieldId).parent().hasClass(errorBorderClass) == true){
            $(fieldId).parent().removeClass(errorBorderClass).addClass(normalBorderClass);
          }
          
          if($(fieldId).parent().hasClass(errorBorderClass) == true){
            $(fieldId).parent().removeClass(errorBorderClass).addClass(normalBorderClass);
          }
          
          if($(fieldParentId+' #emailAvailable').length){
            $('#emailAvailable').addClass(dispNone);
          }
            
          $(fieldId).val(value);
          $(fieldId).attr("value",value);
          if(fieldObject.isUnderScreen && fieldObject.decValue.length && fieldObject.decValue != notFilledText){
            if(debugInfo) $(fieldParentId).find('.js-undSecMsg').removeClass(dispNone);
            $(fieldParentLabel).find('.js-undSecMsg').removeClass(dispNone);
          }else{
                  if(fieldObject.key=="AADHAAR" && !fieldObject.decValue.length ){
                        if(debugInfo) $(fieldParentId).find('.js-undSecMsg').addClass(dispNone);
                        $(fieldParentLabel).find('.js-undSecMsg').addClass(dispNone);
                }
          }
          
          var mainField = "";
          if(fieldObject.key == "ALT_MOBILE_OWNER_NAME"){
            mainField = editAppObject[CONTACT]['ALT_MOBILE'];
          }
          
          if(fieldObject.key == "MOBILE_OWNER_NAME"){
            mainField = editAppObject[CONTACT]['PHONE_MOB'];
          }
          
          if(fieldObject.key == "PHONE_OWNER_NAME"){
            mainField = editAppObject[CONTACT]['PHONE_RES'];
          }
          
          if(typeof mainField == "object" && mainField.value.length == 0){
            if(debugInfo) $(fieldParentId).find('.js-undSecMsg').addClass(dispNone);
            $(fieldParentLabel).find('.js-undSecMsg').addClass(dispNone);
          }
        }
        //For File Field
        if(fieldObject.type === FILE_TYPE){
          //cannot set file type value
                $(fieldParentId).find('.js-errorLabel').addClass(dispNone); 
                var errorText   = errorMap.hasOwnProperty(fieldObject.key) ? errorMap[fieldObject.key] : "Please provide valid value for " + fieldObject.label;
                $(fieldParentId).find('.js-errorLabel').html(errorText); 
        }
        
        //For Box Type Field
        if(fieldObject.type === BOX_TYPE){          
          $(fieldId).find('.js-decVal').removeClass(dispNone);
          $(fieldId).find('.boxType').addClass(dispNone);
          $(fieldId).find('.js-subBoxList').addClass(dispNone);
          if(typeof fieldObject.value == "string" && fieldObject.value.length){
            $(fieldId+' ul li[value="'+fieldObject.value+'"]').trigger('click');
          }
          else{//Not filled in case
            $(fieldId+' span').text(notFilledText).addClass('color12');
            var arrSelected = $(fieldId+' ul li.activeopt');
            for(var j=0;j<arrSelected.length;j++){
              $(arrSelected[j]).removeClass('activeopt');
            }
          }
          $(fieldId).trigger("boxBlur");
        }
        
        //For Date Type Field
        if(fieldObject.type === DATE_TYPE){          
          $(fieldId).find('.js-decVal').removeClass(dispNone);
          $(fieldId).find('.boxType').addClass(dispNone);
          $(fieldId).find('.js-subBoxList').addClass(dispNone);
          if(fieldObject.decValue.length){
            $(fieldId).find('span.js-decVal').text(fieldObject.decValue);
            $(fieldId).find('span.js-decVal').html(fieldObject.decValue);
          }
          else{//Not filled in case
            $(fieldId+' span').text(notFilledText).addClass('color12');
            var arrSelected = $(fieldId+' ul li.activeopt');
            for(var j=0;j<arrSelected.length;j++){
              $(arrSelected[j]).removeClass('activeopt');
            }
          }
          $(fieldId).trigger("boxBlur");
        }
        
        //For Chosen Single Select
        if(fieldObject.type === SINGLE_SELECT_TYPE){
                var ky = fieldObject.key;
                if(fieldObject.key == "MSTATUS"){
                          if(editAppObject[BASIC]['RELIGION'].value == "2" && editAppObject[BASIC]['GENDER'].value == "M"){
                                  var ky = "MSTATUS_MUSLIM_EDIT";
                          }else{
                                  var ky = "MSTATUS_EDIT";
                          }
                }
          var data = JSON.parse(getDataFromStaticTables(ky));
          data = getDependantData(fieldObject,data);
          var hideField = false;
          var optionString = "";
          if(fieldObject.key=="CITY_RES"){
            var countryVal = editAppObject[BASIC]['COUNTRY_RES'].value;
            var stateVal = editAppObject[BASIC]['STATE_RES'].value;
            if(countryVal=='51' && stateVal){
              var dataCity = JSON.parse(getDataFromStaticTables(fieldObject.key))[stateVal];
              optionString = prepareOptionDropDown(dataCity,fieldObject);
            }
            else if(countryVal=='128'){
                var dataCity = JSON.parse(getDataFromStaticTables(fieldObject.key))[countryVal];
                optionString = prepareOptionDropDown(dataCity,fieldObject);
            }
          }
          else if(fieldObject.key=="STATE_RES"){
            if(editAppObject[BASIC]['COUNTRY_RES'].value=='51'){
                optionString = prepareOptionDropDown(data,fieldObject);
            }
          }
          else if(typeof data == "undefined"){
            hideField = true;
          }
          else{
            optionString = prepareOptionDropDown(data,fieldObject);
          }
          
          if(debugInfo)
            console.log(fieldObject.key+' : '+fieldObject.value);
          
          $(fieldId).html("");
          $(fieldId).html(optionString);
          $(fieldId).trigger(chosenUpdateEvent);
          
          if(typeof fieldObject.value == "string" && fieldObject.value.length){
            $(fieldId).val(fieldObject.value);
          }
          else if(typeof fieldObject.value == "number")
          {
              $(fieldId).val(fieldObject.value);
          }
          else{
            $(fieldId).val([]);
          }
          
          $(fieldId).trigger(chosenUpdateEvent);
          
          $(fieldParentId).find('.js-errorLabel').addClass(dispNone);

          if(hideField)
            showHideField(fieldObject,"hide");
          else
            showHideField(fieldObject,"show");
        }
        
        //For Chosen Multi Select
        if(fieldObject.type === MULTIPLE_SELECT_TYPE){
          
          if(debugInfo)
            console.log(fieldObject.key+' : '+fieldObject.value);
          
          if(typeof fieldObject.value == "string" && fieldObject.value.length){
            $(fieldId).val(fieldObject.value.split(','));
          }else{
            $(fieldId).val([]);
            }
          
          $(fieldId).trigger(chosenUpdateEvent);
          $(fieldParentId).find('.js-errorLabel').addClass(dispNone);
        }
        
        //For TextArea
        if(fieldObject.type === TEXT_AREA_TYPE){
          var value = fieldObject.value;
         
          value = $('<textarea />').html(fieldObject.value).text();      
          
          if(typeof value == "string" && fieldObject.key == "YOURINFO"){
            //Update Character Count
            var aboutMeVal = fieldObject.value.replace(/\s\s+/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
            $(fieldParentId).find('.js-areaBox').removeClass('brdr-1').addClass('edpbrd3');
            $(fieldParentId).find('.js-aboutLength').html("Character Count : "+aboutMeVal.length);
          }
          
          $(fieldParentId).find('.js-errorLabel').addClass(dispNone);
          
          $(fieldId).val(value);
          $(fieldId).attr("value",value);
          if(fieldObject.isUnderScreen && fieldObject.decValue.length && fieldObject.decValue != notFilledText){
            if(debugInfo) $(fieldParentId).find('.js-undSecMsg').removeClass(dispNone);
            $(fieldParentLabel).find('.js-undSecMsg').removeClass(dispNone);
          }else{
            if(debugInfo) $(fieldParentId).find('.js-undSecMsg').addClass(dispNone);
            $(fieldParentLabel).find('.js-undSecMsg').addClass(dispNone);
          }
        }
        
        //Phone Field
        if(fieldObject.type == PHONE_TYPE){
          //Handle ISD Std and Mobile Field
          var isdFieldID      = '#'+fieldObject.key.toLowerCase()+'-isd';
          var stdFieldID      = '#'+fieldObject.key.toLowerCase()+'-std';
          var mobileFieldID   = '#'+fieldObject.key.toLowerCase()+'-mobile';

          if(fieldObject.key == "PHONE_RES"){
            mobileFieldID = '#'+fieldObject.key.toLowerCase()+'-landline';
          }

          var validClass = 'edpbrd4';
          var invalidClass = 'brdr-1';
          
          //Remove Error Flag and border
          $(fieldParentId).find('.js-errorLabel').addClass(dispNone);
          $(isdFieldID).parent().parent().removeClass(invalidClass).addClass(validClass);

          var valueArray    = fieldObject.value.split(",");
          var isdVal        = (valueArray.length>1) ? valueArray[0] : getISDCode();
          var phoneVal      = (valueArray.length>1) ? valueArray[1] : valueArray[0]; 
          
          $(isdFieldID).val(isdVal);
          $(isdFieldID).attr("value",isdVal);
          if($(stdFieldID).length){
            var stdVal = (valueArray.length>1) ? valueArray[1] : getSTDCode();
            
            if(valueArray.length > 1)
              phoneVal = valueArray[2];
            
            $(stdFieldID).val(stdVal);
            $(stdFieldID).attr("value",stdVal);
          }
          
          $(mobileFieldID).val(phoneVal);
          $(mobileFieldID).attr("value",phoneVal);
          
          if(phoneVal.length == 0){
            //Hide Underscreening label
            var ownerNameField = "";
            
            if(fieldObject.key == "PHONE_RES"){
              ownerNameField = editAppObject[CONTACT]["PHONE_OWNER_NAME"];
            }
            
            if(fieldObject.key == "ALT_MOBILE"){
              ownerNameField = editAppObject[CONTACT]["ALT_MOBILE_OWNER_NAME"];
            }
            
            if(fieldObject.key == "PHONE_MOB"){
              ownerNameField = editAppObject[CONTACT]["MOBILE_OWNER_NAME"];
            }
            showHideUnderScreeningMsg(ownerNameField,"hide"); 
          }
        }
        
        //Privacy Field
        if(fieldObject.type === PRIVACY_TYPE){ 
          $(fieldParentId).find(' ul li.activeopt').removeClass('activeopt');
          if(fieldObject.value){
            $(fieldParentId).find(' ul li[value="'+ fieldObject.value +'"]').addClass('activeopt');
          }
        }
        
        //Range Field
        if(fieldObject.type === RANGE_TYPE){
          var valArray = fieldObject.value.split(",");
          var fieldParent = '#time_to_callParent';
          
          onTimeFieldBlur();
          $(fieldParent).find('.js-errorLabel').addClass(dispNone);
          $(fieldParent).find('.edpwid10').removeClass('brdr-1');
          
          
          if(valArray.length == 1){
            $(fieldParent).find('#startCall').attr("value","").val("");
            $(fieldParent).find('.js-startCall ul li.activeopt').removeClass('activeopt');

            $(fieldParent).find('#startAmPm').attr("value","").val("");
            $(fieldParent).find('.js-startAmPm ul li.activeopt').removeClass('activeopt');
            
            $(fieldParent).find('#endCall').attr("value","").val("");
            $(fieldParent).find('.js-endCall ul li.activeopt').removeClass('activeopt');

            $(fieldParent).find('#endAmPm').attr("value","").val(""); 
            $(fieldParent).find('.js-endAmPm ul li.activeopt').removeClass('activeopt');
            
            return ;
          }
          
          var startTime = valArray[0].split(" ")[0].trim();
          var startAmPm = valArray[0].split(" ")[1].trim();
          $(fieldParent).find('#startCall').attr("value",startTime).val(startTime);
          $(fieldParent).find('.js-startCall ul li[value="'+startTime+'"]').addClass('activeopt');

          $(fieldParent).find('#startAmPm').attr("value",startAmPm).val(startAmPm);
          $(fieldParent).find('.js-startAmPm ul li[value="'+startAmPm+'"]').addClass('activeopt');

          var endTime = valArray[1].split(" ")[0].trim();
          var endAmPm = valArray[1].split(" ")[1].trim();
          $(fieldParent).find('#endCall').attr("value",endTime).val(endTime);
          $(fieldParent).find('.js-endCall ul li[value="'+endTime+'"]').addClass('activeopt');

          $(fieldParent).find('#endAmPm').attr("value",endAmPm).val(endAmPm); 
          $(fieldParent).find('.js-endAmPm ul li[value="'+endAmPm+'"]').addClass('activeopt');
        }
      }
    }
    
    /*
     * updateView
     * @returns {undefined}
     */
    updateView = function(viewApiResponse){
      updateLastUpdated(viewApiResponse);
      var iterateOnResponse = function(section){
        for(var key in section){
        if(key=="jamaat")
        {
                if(section['caste_val']=="152")
                {
                        $("#jamaatlistitem").show();
                }
                else
                {
                        $("#jamaatlistitem").hide();
                }
        }
        if(key=="m_status")
        {
                if(section['m_status'].toLowerCase()=="never married")
                {
                        $("#havechildParent").hide();
                }
                else
                {
                        $("#havechildParent").show();
                }
        }
        if(key=="aadhar")
        {
                if(section['aadhar']==""){
                        $("#aadhaarView").text("Not filled in").removeClass("edpcolr2").removeClass("color11").addClass("color5");
                        $("#aadhaarLabelParent").find(".js-undSecMsg").addClass("disp-none");
                }else{
                        $("#aadhaarLabelParent").find(".js-undSecMsg").find("span").text("Verified").removeClass("color5").addClass("edpcolr2");
                }
                    
        }
          var viewId = '#'+key.toLowerCase()+'View';
          
          if($(viewId).length === 0 && typeof section[key] == "string"){
            continue ;
          }
          else if( viewResponseSubSection.indexOf(key) !== -1 && typeof section[key] == "object"){
            iterateOnResponse(section[key]);
          }
          
          var colorClass = 'color11';
          var notFilledInClass = 'color5';
          if(updateViewColor12Map.indexOf(key.toLowerCase()) !== -1){
            colorClass = 'color12';
          }
          
          var duplicateID = false
          if(duplicateFieldMap.indexOf(key.toLowerCase()) !== -1){
            duplicateID = viewId + '1';
          }
          
          if(typeof section[key] == "string" && section[key].toLowerCase() === notFilledText.toLocaleLowerCase()){
            $(viewId).text(notFilledText);
            $(viewId).addClass(notFilledInClass).removeClass(colorClass);
            if(duplicateID && isDomElementVisible(duplicateID)){
              $(duplicateID).text(notFilledText);
              $(duplicateID).addClass(notFilledInClass).removeClass(colorClass);
            }
          }
          else if(typeof section[key] == "string" || key == "age")//&& section[key].length)
          { 
            var value = $('<textarea />').html(section[key]).text();
            $(viewId).text(value);
            $(viewId).addClass(colorClass).removeClass(notFilledInClass);
            if(duplicateID && isDomElementVisible(duplicateID)){
              $(duplicateID).text(value);
              $(duplicateID).addClass(colorClass).removeClass(notFilledInClass);
            }
          }
          if(removeDepField.hasOwnProperty(key) == true){
                  var depId = "#li-"+removeDepField[key];
                  if(removeDepFieldOn[key] == section[key]){
                          $(depId).addClass(dispNone);
                  }else{
                          $(depId).removeClass(dispNone);
                  }
                  
          }
          if(multiFieldViewMap.indexOf(key.toLowerCase()) !== -1){
            updateMultiFieldsView(viewId,$(viewId).text());
          }
          
          if(phoneStatusMap.indexOf(key.toLowerCase()) !== -1){
            updatePhoneStatusView(viewId,$(viewId).text());
          }
          
          if(phoneDescriptionMap.indexOf(key.toLowerCase()) !== -1){
            updatePhoneDescView(viewId,$(viewId).text());
          }

        }
      };
      
      if(typeof viewApiResponse == "string")
        viewApiResponse = JSON.parse(viewApiResponse); 
      
      for(var i=0;i<viewResponseKeyArray.length;i++){
        var section = viewApiResponse[viewResponseKeyArray[i]];
        iterateOnResponse(section);

      }
    }
    
    /*
     * 
     * @param {type} fieldId
     * @param {type} value
     * @returns {undefined}
     */
    updateMultiFieldsView = function(fieldId,value){
      var decValue = value; //Like A?,B,C?
      var arr = value.split('?');
      var myDecVal = "";
      
      //Looks for label? - value and  replace it with label<QuesMark> - value
      //Like  Can the girl work after marriage? - Prefer a Housewife is valid decorate value
      for(var j=1;j<arr.length;j++){
        if(arr[j].indexOf(" -") === 0 ){
          myDecVal +=arr[j-1]+'<QuesMark>';
        }
        else{
          myDecVal +=arr[j-1]+'?';
        }
      }
      myDecVal += arr[j-1];
      
      var arrSplit = myDecVal.trim().split('?');

      if(arrSplit.length === 1){
        $(fieldId).html("");
        $(fieldId).append($("<span />",{class:"",text:decValue}));
        return ;
      }

      //Jai ho Product n Designer ki!!!
      var viewDOM = $("<span />");
      var firstEle = arrSplit[0];
      if(firstEle.split(',').length == 1){
        firstEle +='?';
        viewDOM.append($("<span />",{class:"color5",text:firstEle.replace("<QuesMark>","?")}));
      }
      else{
        var firstHalf = firstEle.substring(0,firstEle.lastIndexOf(','))+',';
        var secHalf = firstEle.substring(firstEle.lastIndexOf(',')+1,firstEle.length)+'?';
        
        firstHalf = firstHalf.replace(/<QuesMark>/g,"?");
        secHalf = secHalf.replace(/<QuesMark>/g,"?");
        
        viewDOM.append($("<span />",{class:"",text:firstHalf}));
        viewDOM.append($("<span />",{class:"color5",text:secHalf}));
      }

      //Now loop for rest of the Array
      
      var maxLength = arrSplit[arrSplit.length-1].length === 0 ? arrSplit.length - 1 : arrSplit.length - 2;
      for(var j=1;j<=maxLength;j++){
        var eleText = arrSplit[j];
        if(eleText.trim().length===0)
          continue;
        var firstHalf = eleText.substring(0,eleText.lastIndexOf(','))+',';
        var secHalf = eleText.substring(eleText.lastIndexOf(',')+1,eleText.length)+'?';
        
        firstHalf = firstHalf.replace(/<QuesMark>/g,"?");
        secHalf = secHalf.replace(/<QuesMark>/g,"?");
        
        viewDOM.append($("<span />",{class:"color11",text:firstHalf}));
        viewDOM.append($("<span />",{class:"color5",text:secHalf}));
      }
      
      if(maxLength == arrSplit.length - 2){
        var restText = arrSplit[arrSplit.length-1];
        restText = restText.replace(/<QuesMark>/g,"?");
        viewDOM.append($("<span />",{class:"",text:restText}));
      }
      
      $(fieldId).html("");
      $(fieldId).append(viewDOM);
    }
    
    /*
     * 
     * @param {type} fieldId
     * @param {type} value
     * @returns {undefined}
     */
    updatePhoneStatusView = function(fieldId,value){
      var colorClass = "color5 cursp";
      var removedClass = "color12";
      if(value.toLowerCase() == "verified"){
        colorClass = "color12";
        removedClass = "color5 cursp";
      }

      if ( fieldId == "#alt_email_statusView")
      {
         if(value.toLowerCase() == "verify")
         {
          $("#showAlternateEmailHint").removeClass("disp-none");
          $("#alt_email_statusView").removeClass("disp-none");
         }
         else if ( value.toLowerCase() == "verified" )
         {
          $("#alt_email_statusView").removeClass("disp-none");
          $("#showAlternateEmailHint").addClass("disp-none");
         }
         else
         {
          $("#alt_email_statusView").addClass("disp-none");
          $("#showAlternateEmailHint").addClass("disp-none");

         }
      }
      $(fieldId).addClass(colorClass).removeClass(removedClass);
    }
    
    /*
     * updatePhoneDescView
     * @param {type} fieldId
     * @param {type} value
     * @returns {undefined}
     */
    updatePhoneDescView = function(fieldId,value){
      
      $(fieldId).parent().find('.js-undSecMsg').addClass(dispNone);
      
      var fieldKey = $(fieldId).parent().attr('id').split('LabelParent')[0];//like alt_mobile_owner_nameLabelParent
      var fieldObject = editAppObject[CONTACT][fieldKey.toUpperCase()];
        
      if(value.length && fieldObject.isUnderScreen){//if value exist and underscreening
        $(fieldId).parent().find('.js-undSecMsg').removeClass(dispNone);
      }
    }
    
    /*
     * 
     * @param {type} fieldId
     * @returns {undefined}
     */
    showHideUnderScreeningMsg = function(fieldObject,showOrHide){
      var fieldID = '#'+fieldObject.key.toLowerCase();
      var parentID = fieldID+'Parent';
      var parentLabelID = fieldID+'LabelParent';
      
      if(showOrHide == "show" || showOrHide == "1"){
        if(debugInfo)  $(parentID).find('.js-undSecMsg').removeClass(dispNone);
        $(parentLabelID).find('.js-undSecMsg').removeClass(dispNone);
      }      
	    
      if(showOrHide == "hide" || showOrHide == "0"){
        if(debugInfo) $(parentID).find('.js-undSecMsg').addClass(dispNone);
        $(parentLabelID).find('.js-undSecMsg').addClass(dispNone);
      }
    }
    /*
     * setVariables
     * Function to set some based on editAppObject global Varaibles
     * @returns {undefined}
     */
    setGlobalVariables = function(){
      try{
        currentIncomeInRs = false;
        if(editAppObject[BASIC]["COUNTRY_RES"].value == "51"){
          currentIncomeInRs = true;
        }
      }catch(e){
        console.log(e.stack);
      }      
    }
    
    /*
     * getPreviousValue , 
     * @returns {undefined}
     */
    getPreviousFieldValue = function(fieldObject){
      
      var previousValue = fieldObject.value;
      if(previousSectionValue[fieldObject.sectionId].hasOwnProperty([fieldObject.key]) === true){
        previousValue = previousSectionValue[fieldObject.sectionId][fieldObject.key];
      }
      
      if(typeof previousValue == "undefined"){
        previousSectionValue[fieldObject.sectionId][fieldObject.key] = fieldObject.value;
        previousValue = fieldObject.value;
      }
      
      return previousValue;
    }
    
    /*
     * Function to init Native Fields
     * @returns {undefined}
     */
    initNativeFields = function(){
       var natCountryField = editAppObject[FAMILY]["NATIVE_COUNTRY"];
       var natStateField = editAppObject[FAMILY]["NATIVE_STATE"];
       var natCityField = editAppObject[FAMILY]["NATIVE_CITY"];
       var ancestralOrigin = editAppObject[FAMILY]["ANCESTRAL_ORIGIN"];
       
       if(natCountryField.value == "51" || natCountryField.value == ""){
         showHideField(natCountryField,"hide");
         showHideField(natStateField,"show");
         if(typeof natStateField.value == "string" && natStateField.value.length){
          showHideField(natCityField,"show");
          if(typeof natCityField.value == "string" && natCityField.value.length && natCityField.value == "0"){
            
            //$('#'+ancestralOrigin.key.toLowerCase()).val("");
            showHideField(ancestralOrigin,"show");
          }
          else{
            showHideField(ancestralOrigin,"hide");
          }
         }
         else{
          showHideField(natCityField,"hide");
          showHideField(ancestralOrigin,"hide");
         }
       }
       else{
         showHideField(natCountryField,"show");
         showHideField(natCityField,"hide");
         showHideField(natStateField,"hide");
         showHideField(ancestralOrigin,"hide");
       }
       
       if(natCountryField.value == ""){
         storeFieldChangeValue(natCountryField,"51");
       }
    }
    
    /*
     * initSiblings Fields
     * @returns {undefined}
     */
    initSiblings = function(){
      var sister = editAppObject[FAMILY]["T_SISTER"];
      var brother = editAppObject[FAMILY]["T_BROTHER"];
      if(brother.value !== null){
        var arrBrother = brother.value.split(','); 
        $('#'+brother.key.toLowerCase()+' ul li.'+'option_'+arrBrother[0]).trigger('click');
        $('#'+brother.key.toLowerCase()+' ul li.'+'sub_option_'+arrBrother[1]).trigger('click');
        $('#'+brother.key.toLowerCase()).trigger('boxBlur');
      }
      
      if(sister.value !== null){
        var arrSister = sister.value.split(',');
        $('#'+sister.key.toLowerCase()+' ul li.'+'option_'+arrSister[0]).trigger('click');
        $('#'+sister.key.toLowerCase()+' ul li.'+'sub_option_'+arrSister[1]).trigger('click');
        $('#'+sister.key.toLowerCase()).trigger('boxBlur');
      }
      
      //delete editedFields[FAMILY];
    }
    
    /*
     * initEducationFields
     * @returns {undefined}
     */
    initEducationFields = function(){
      
      var gradDeg = editAppObject[EDU_CAREER]["DEGREE_UG"];
      var gradCollg = editAppObject[EDU_CAREER]["COLLEGE"];
      
      var postGradDeg = editAppObject[EDU_CAREER]["DEGREE_PG"];
      var postGradCollg = editAppObject[EDU_CAREER]["PG_COLLEGE"];
      
      var maxEducation = editAppObject[EDU_CAREER]["EDU_LEVEL_NEW"];
      
      var other_ugDeg = editAppObject[EDU_CAREER]["OTHER_UG_DEGREE"];
      var other_pgDeg = editAppObject[EDU_CAREER]["OTHER_PG_DEGREE"];
      
      var gradDegID = '#'+gradDeg.key.toLowerCase();
      var postGradDegID = '#'+postGradDeg.key.toLowerCase();
      
      if($(gradDegID+' option[value=\"'+parseInt(maxEducation.value)+'\"]').length === 1){
        showHideField(gradDeg,"show");
        showHideField(gradCollg,"show");
        showHideField(other_ugDeg,"show");
        
        showHideField(postGradDeg,"hide",true);
        showHideField(postGradCollg,"hide",true);
        showHideField(other_pgDeg,"hide",true);
      }
      else if($(postGradDegID+' option[value=\"'+parseInt(maxEducation.value)+'\"]').length === 1){
        showHideField(gradDeg,"show");
        showHideField(gradCollg,"show");
        showHideField(other_ugDeg,"show");
        
	showHideField(postGradDeg,"show");
        showHideField(postGradCollg,"show");
        showHideField(other_pgDeg,"show");
      }
      else{
        showHideField(gradDeg,"hide",true);
        showHideField(gradCollg,"hide",true);
        showHideField(other_ugDeg,"hide",true);
        
        showHideField(postGradDeg,"hide",true);
        showHideField(postGradCollg,"hide",true);
        showHideField(other_pgDeg,"hide",true);
      }
      
      
    }
    initMstatusDocumentMap = function(){
            $('#mstatus_proofParent').addClass(dispNone);
    }
    initJamaat = function()
    {
	var caste = editAppObject[BASIC]["CASTE"];
	if(caste!=undefined && caste!='' && caste.hasOwnProperty("value"))
	{
		onCasteChange(caste.value);
	}
	else
	{
		var jamaatFieldObject = editAppObject[BASIC]["JAMAAT"];
		showHideField(jamaatFieldObject,"hide",true);
	}

    }
    /*
     * initUGAndPGDegreeMap
     * @returns {}
     */
    initUGAndPGDegreeMap =function(){
      var arrdegreePG = JSON.parse(getDataFromStaticTables("DEGREE_PG"));
      var arrdegreeUG = JSON.parse(getDataFromStaticTables("DEGREE_UG"));
      
      //Loop the data section 
      $.each(arrdegreePG,function(key1,data1)
      {
        $.each(data1,function(key2,data2)
        {
          $.each(data2,function(value,label)
          { 
            pgDegreeMap.push(value.toString());
          });
        });
      });
      
      //Loop the data section 
      $.each(arrdegreeUG,function(key1,data1)
      {
        $.each(data1,function(key2,data2)
        {
          $.each(data2,function(value,label)
          { 
            ugDegreeMap.push(value.toString());
          });
        });
      });
    }
    
    /*
     * initPhoneFields
     * @returns {undefined}
     */ 
    initPhoneFields = function(){
      //Show and hide Addowner button
      
      var mobileOwnerName = editAppObject[CONTACT]["MOBILE_OWNER_NAME"];
      var mobileNumberOwner = editAppObject[CONTACT]["MOBILE_NUMBER_OWNER"];
      
      if( mobileOwnerName.value.length || mobileNumberOwner.value.length ){
        $('#phone_mobParent').find('.js-ownerBtn').addClass(dispNone);
      }else{
        $('#phone_mobParent').find('.js-ownerBtn').removeClass(dispNone);
        showHideField(mobileOwnerName,"hide");
        showHideField(mobileNumberOwner,"hide");
      }
      
      var altMobileOwnerName = editAppObject[CONTACT]["ALT_MOBILE_OWNER_NAME"];
      var altMobileNumberOwner = editAppObject[CONTACT]["ALT_MOBILE_NUMBER_OWNER"];
      
      if( altMobileOwnerName.value.length || altMobileNumberOwner.value.length ){
        $('#alt_mobileParent').find('.js-ownerBtn').addClass(dispNone);
      }else{
        $('#alt_mobileParent').find('.js-ownerBtn').removeClass(dispNone);
        showHideField(altMobileOwnerName,"hide");
        showHideField(altMobileNumberOwner,"hide");
      }
      
      var phoneMobileOwnerName = editAppObject[CONTACT]["PHONE_OWNER_NAME"];
      var phoneMobileNumberOwner = editAppObject[CONTACT]["PHONE_NUMBER_OWNER"];
      
      if( phoneMobileOwnerName.value.length || phoneMobileNumberOwner.value.length ){
        $('#phone_resParent').find('.js-ownerBtn').addClass(dispNone);
      }else{
        $('#phone_resParent').find('.js-ownerBtn').removeClass(dispNone);
        showHideField(phoneMobileOwnerName,"hide");
        showHideField(phoneMobileNumberOwner,"hide");
      }
    }
    
   /*
    * initPinCodeFields
    * @returns {undefined}
    */
    initPinCodeFields = function(){
      var countryResField = editAppObject[BASIC]["COUNTRY_RES"];
     
      var pinCodeField  = editAppObject[CONTACT]["PINCODE"];
      var parentPinCodeField  = editAppObject[CONTACT]["PARENT_PINCODE"];
      
      if(countryResField.value == "51"){  
        showHideField(pinCodeField,"show");
        showHideField(parentPinCodeField,"show");
      }else{
        showHideField(pinCodeField,"hide");
        showHideField(parentPinCodeField,"hide");
      }
        
    }
    
    /*
     * initVerificationFields
     * @returns {undefined}
     */
    initVerificationFields = function(){
        editedFields[VERIFICATION] = {};
        var idProofTypeField = editAppObject[VERIFICATION]["ID_PROOF_TYPE"];
        if(idProofTypeField.value.length == 0 )
            requiredFieldStore.add(idProofTypeField);
    
        var idProofValField = editAppObject[VERIFICATION]["ID_PROOF_VAL"];
        if(idProofValField.value.length == 0 )
            requiredFieldStore.add(idProofValField);
    
        $("#idlabel_id_proof_val").html('jpg/pdf only');
        $("#idlabel_addr_proof_val").html('jpg/pdf only');
    }
    
    /*
     * getISDCode
     * @returns {undefined}
     */
    getISDCode =function(){
      //ToDO :  Get All 3 Phone field and return any isd value set in them
      var arrField = ["PHONE_MOB","ALT_MOBILE","PHONE_RES"];
      var isdVal = "";
      for(var itr=0;itr<arrField.length;itr++){
        var fieldObject = editAppObject[CONTACT][arrField[itr]];
        var arrValue = fieldObject.value.split(",");
        if(arrValue.length>1){
          isdVal = arrValue[0];
          break;
        }
      }
      
      return isdVal;
    }
    
    /*
     * getSTDCode
     * @returns {undefined}
     */
    getSTDCode = function(){
      var cityResField = editAppObject[BASIC]["CITY_RES"]
      
      if(Object.keys(stdCodesMap).length == 0 ){
        var stdCodesArray = JSON.parse(getDataFromStaticTables("STDCODES"));
        //Loop the data section 
        $.each(stdCodesArray,function(key1,data1)
        {
          $.each(data1,function(key2,data2)
          {
            $.each(data2,function(value,label)
            { 
              stdCodesMap[value.toString()] = label.toString();
            });
          });
        });
      }
      
       
       if(cityResField.value.length && stdCodesMap.hasOwnProperty(cityResField.value)){
        return stdCodesMap[cityResField.value];
       }
       
       return "";
    }
    
    /*
     * getDecoratedServerError
     * @param {type} fieldName
     * @param {type} fieldServerError
     * @returns {undefined}
     */
    getDecoratedServerError = function(fieldName,fieldServerError){
      
      if(fieldName == "EMAIL"){
        
        if(fieldServerError == "This email is already registered in our system"){
          $('#emailParent').find('.avaliableTop').text("Not Available").removeClass('colorAva').addClass('color5').addClass(dispNone);
          fieldServerError = "Not available";
        }
        if(fieldServerError == "Both emails are same"){
          fieldServerError = "Same Email";
        }
        
        if(fieldServerError == "Provide your email in proper format, e.g. raj1984@gmail.com"){
          fieldServerError = "Invalid format";
        }
        if(fieldServerError == "This Email is banned due to terms of use violation"){
          fieldServerError = "Email Banned";
          $('#emailParent').find('.avaliableTop').text("Not Available").removeClass('colorAva').addClass('color5').addClass(dispNone);
        }
      }

      if(fieldName == "ALT_EMAIL"){
        
        if(fieldServerError == "This email is already registered in our system"){
          $('#alt_emailParent').find('.avaliableTop').text("Not Available").removeClass('colorAva').addClass('color5 right0').addClass(dispNone);
          fieldServerError = "Not available";
        }
        
        if(fieldServerError == "Provide your email in proper format, e.g. raj1984@gmail.com"){
          fieldServerError = "Invalid format";
        }

        if(fieldServerError == "Both emails are same"){
          fieldServerError = "Same Email";
        }
        if(fieldServerError == "This Email is banned due to terms of use violation"){
          fieldServerError = "Email Banned";
          $('#alt_emailParent').find('.avaliableTop').text("Not Available").removeClass('colorAva').addClass('color5').addClass(dispNone);
        }
      }
      
      if(fieldServerError == "Please provide valid country isd"){
        fieldServerError = "Invalid ISD code";
      }
      
      if(fieldServerError == "Provide a valid landline number."){
        fieldServerError = "Invalid";
      }
      
      if(fieldServerError == "This Phone is banned due to terms of use violation"){
        fieldServerError = "Phone no. Banned";
      }
      if(fieldServerError == "There are already two other profiles active on Jeevansathi with the same phone number."){
        fieldServerError = "Exists in 2 other profiles";
      }
      return fieldServerError;
    }
    
    /*
     * Main App Function to initialize the Edit App
     */
    initEditApp = function(){
      try{
        bakeEditAppObject();
//        storeData(_e_api);
        BindEvent();
        for(var i=0;i<multiFieldViewMap.length;i++){
          var viewId = '#'+multiFieldViewMap[i]+'View';
          updateMultiFieldsView(viewId,$(viewId).text());
        }
        
        for(var i=0;i<phoneStatusMap.length;i++){
          var viewId = '#'+phoneStatusMap[i]+'View';
          updatePhoneStatusView(viewId,$(viewId).text());
        }
        
      }catch(e){
        console.log(e);
      };
    }
    
    /*
     * get field with index
     */
    getEditAppFields = function(sectionId,fieldKey){
        if(false === editAppObject.hasOwnProperty(sectionId) || false === editAppObject[sectionId].hasOwnProperty(fieldKey)){
          if(debugInfo){
            console.log(fieldKey +' field in section : ' + sectionId +' does not exist for this profile');
          }
          return false;
        }
        return editAppObject[sectionId][fieldKey];
    }
    
  }
  catch(e){
    console.log(e.stack);
  }
  
  if(debugInfo){
    return{
    init : initEditApp,
    staticTables:staticTables,
    toggle:toggleLoader,
    fields:editAppObject,
    editedFields:editedFields,
    getEditAppFields:getEditAppFields,
    updateMulti:updateMultiFieldsView
   };
  }
  return{
    init : initEditApp,
    staticTables:staticTables,
    updateMulti:updateMultiFieldsView,
    onSave:onSectionSave,
    getEditAppFields:getEditAppFields,
    storeFieldChangeValue:storeFieldChangeValue,
    showHideEditSection:showHideEditSection,
    updateNeedToUpdate:updateNeedToUpdate
  };
}();

function updateLastUpdated(data){
    $("#lastModified").text("Last Edited on "+data.about.last_mod);
    $("#profileViews").text("Profile Views "+data.about.profileViews);
    if(data.contact.id_proof_type != "" || data.contact.addr_proof_type != ""){
        $("#section-verification .js-editBtn").text("Edit");
    }else{
        $("#section-verification .js-editBtn").text("Add");
    }
}

function redirectToEditSection(goto){
    $(".editableSections").each(function(i, obj){
        var currSectionId = $(this).attr("data-section-id");
        var currSection = this;
        if(currSectionId == goto){
            if(currSectionId == "uploadhoroscope"){
                currSectionId = "horoscope";
            }
            $('html, body').animate({
                scrollTop: $("#section-"+currSectionId).offset().top}, 'slow',function(){
                $(currSection).trigger("click");
            });
       }
    });
}

function onClickProfileCompleteLinks(){
    $(".editLink").on('click',function(){
        var goto = $(this).attr("myhref");
        redirectToEditSection(goto);
    });
}

function completeProfileCompletionBlock(data){
    if(data.PCS != 100){
        $("#PCSBlock").removeClass("disp-none");
        $("#PCSBlockul").empty();
        $("#PCSBlockul").append('<li class="fontreg">Add details to your profile</li>');
        $.each(data.msgDetails, function(key, val){
            if(key == "PHOTO")
            {
                $("#PCSBlockul").append('<li><a href="'+data.linkDetails[key]+'" class="color11 cursp">'+val+'</a></li>');
            }
            else
            {
                $("#PCSBlockul").append('<li><a myhref="'+data.linkDetails[key]+'" class="color11 cursp editLink">'+val+'</a></li>');
            }
        });
        onClickProfileCompleteLinks();
    }
    else{
        $("#PCSBlock").addClass("disp-none");
    }
}

function clearFileUpload(){
    var input = $("#horoFile");
    input.replaceWith(input.val('').clone(true));
    $("#uploadFileName").val("");
}
function createHoroscopeFun(){
            var horoscopeValue = EditApp.getEditAppFields('horoscope','HOROSCOPE_MATCH').value;
            if(horoscopeValue != 'Y' && horoscopeValue != 'N' ){
                disableUploadBtn();
                $("#horoscopeDiv").removeClass('disp-none');
                $("#bt_yes, #bt_no").addClass("cursp").removeClass("bg6");
                $("#commonOverlay").fadeIn("fast",function(){
                    $("#horoscopeLayer").fadeIn("fast"); 
                });
                ajaxInsertAstroPull(0);
            }else{
                ajaxInsertAstroPull(0);
                $("#commonOverlay").fadeIn("fast",function(){
                    $("#horoscopeLayer").fadeIn("fast"); 
                });
                $("#horoscopeDiv").addClass('disp-none');
                showCreateHoroDiv();
            }
    }
function onCreateUploadHoroBtn(){
    $("#crUpHoroBtn").on('click',createHoroscopeFun );
}

function onaddHoroscopeCloseBtn()
{
    $("#closebtnHL").on('click',function(){
        clearFileUpload();
        resetCreateHoroscope();
        $("#horoscopeLayer").fadeOut("fast",function(){
            $("#commonOverlay").fadeOut("fast",function(){
                hideHoroSuccessDiv();
                showHoroDiv();
                hideCreateHoroDiv();
                removeErrorUpload();
            });
        }); 
        $("#uploadHoroDiv").fadeOut("fast");
    });
}

function onUploadHoroBtn()
{
    $("#uploadHoroBtn").on('click',function(){
        $("#uploadHoroBtn").removeClass("cursp").addClass("bg6");
        $("#createHoroBtn").addClass("cursp").removeClass("bg6");
        hideCreateHoroDiv();
        $("#uploadHoroDiv").slideDown("fast");
    });
}

function ajaxInsertAstroPull(retryAttempt){
    if(retryAttempt < 3){
        retryAttempt++;
        var url = "/api/v1/profile/horoscope";
        $.myObj.ajax({
            type: 'POST',
            url: url,
            data: {update: "update"},
            success: function(response){
                retryAttempt = 0;
            },
            error: function(response){
                ajaxInsertAstroPull(retryAttempt);
            }
        });
    }
    else{
        retryAttempt = 0;
    }
}
function onClickHoroscopeMust(){
    $("#bt_yes,#bt_no").on('click',function(){
        $("#bt_yes").removeClass("cursp").addClass("bg6");
        $("#bt_no").addClass("cursp").removeClass("bg6");
        var horo_match = '';
        if($(this).attr('id') == 'bt_yes'){
                horo_match = 'Y';
        }else{
                horo_match = 'N';
        }
        var horoMatchField = getEditAppFields('horoscope','HOROSCOPE_MATCH');
        EditApp.storeFieldChangeValue(horoMatchField,horo_match);
        EditApp.onSave(horoMatchField.sectionId,false);
        //$("#uploadHoroDiv").slideUp("fast");
         $("#horoscopeDiv").addClass('disp-none');
         
        showCreateHoroDiv();
    });
}
function onCreateHoroBtn()
{
    $("#createHoroBtn").on('click',function(){
        $("#createHoroBtn").removeClass("cursp").addClass("bg6");
        $("#uploadHoroBtn").addClass("cursp").removeClass("bg6");
        $("#uploadHoroDiv").slideUp("fast");
        showCreateHoroDiv();
    });
}

function showCreateHoroDiv(){
    $("#createHoroDiv").slideDown("fast");
}

function hideCreateHoroDiv(){
    $("#createHoroDiv").slideUp("fast");
}

function onBrowseFileBtn()
{
    $("#horoFile").change(function(event){
        enableUploadBtn();
        var filename = $(this).val();
        var nameArr = filename.split("\\");
        filename = nameArr[nameArr.length-1];
//        fileData = event.target.files;
//        file = fileData[0];
        $("#uploadFileName").val(filename);
    });
}

function disableUploadBtn(){
    $("#uploadSubmit").attr('disabled', true);
    $("#uploadSubmit").removeClass("cursp");
}

function enableUploadBtn(){
    $("#uploadSubmit").attr('disabled', false);
    $("#uploadSubmit").addClass("cursp");
}

function addErrorUpload(){
    $("#uploadError").removeClass("color12").addClass("colr5");
}

function removeErrorUpload(){
    $("#uploadError").removeClass("colr5").addClass("color12");
}

function showHoroSuccessDiv(){
    hideHoroDiv();
    $("#horoscopeSuccess").fadeIn("fast",function(){
        setViewHoroscopeDiv();
    });
}

function hideHoroSuccessDiv(){
    $("#horoscopeSuccess").fadeOut("disp-none");
}

function hideHoroDiv(){
    $("#horoscopeDiv").addClass("disp-none");
}

function showHoroDiv(){
    $("#horoscopeDiv").removeClass("disp-none");
}

function submitHoro()
{
    $("#uploadSubmit").on('click',function(){
        var dataToBeSent = new FormData();
        dataToBeSent.append("horoscope",$('#horoFile')[0].files[0]);
        $.ajax({
            url: '/profile/horoscope_upload.php?pchecksum=~$profilechecksum`&registration_horo=1&submitted=1&fromAPI=1',
            type: 'POST',
            data: dataToBeSent,
            processData: false,
            cache: false,
            processData: false, // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
            contentType: false,
            timeout: 360000,
            beforeSend: function(){
                disableUploadBtn();
                showCommonLoader();
            },
            success: function(response){
                if(response == "ERROR"){
                    addErrorUpload();
                }
                else if(response == "OK"){
                    removeErrorUpload();
                    showHoroSuccessDiv();
                }
                enableUploadBtn();
                hideCommonLoader();
                console.log(response);
            },
            error: function(response){
                addErrorUpload();
                enableUploadBtn();
                hideCommonLoader();
                console.log("In Error");
            }
        }); 
    });
}

function onViewHoroscope(){
    $(".js-viewHoro").on('click',function(){
        $.ajax({
          method: "POST",
          url : "/profile/horoscope_astro.php?SAMEGENDER=&FILTER=&ERROR_MES=&view_username="+username+"&SIM_USERNAME="+username+"&type=Horoscope&ajax_error=2&checksum=&profilechecksum="+ProCheckSum+"&randValue=890&from_jspcEdit=1&showDownload=1",
          async:true,
          timeout:20000,
          beforeSend: function(){
              showCommonLoader();
          },
          success:function(response){
          		hideCommonLoader();
              $("#putHoroscope").html(response);              
              $(".js-hideThisDiv").hide();
              $('.js-overlay').fadeIn(200,"linear",function(){ $('#kundli-layer').fadeIn(200,"linear")});            
          }
        }); 
    });
}

function onClickOfHoroscopeOverlay(){
    $("#commonOverlay").on('click',function(){
        $("#removeHoroscopeLayer").fadeOut("fast",function(){
            $("#commonOverlay").fadeOut("fast"); 
        });
        if(! ($("#commonOverlay").hasClass("js-dClose")) ){
            $("#closebtnHL").trigger("click");
            $("#cls-view-horo").trigger("click");
        }
    });
}

function onClickViewHoroCloseBtn(){
    $("#cls-view-horo").on('click',function(){
       $("#kundli-layer").fadeOut("fast",function(){
           $("#commonOverlay").fadeOut("fast");
       }) 
    });
}

function setViewHoroscopeDiv(){
    if($("#viewHoroBlock").length == 0) {
        $("#viewHoroBlockParent").append('<button id="viewHoroBlock" class="bg5 colrw f14 fontlig brdr-0 lh40 txtc fullwid outl1 cursp js-viewHoro">View horoscope</button>');
        onViewHoroscope(); //To bind click event
    }
}

function resetCreateHoroscope(){
    $("#createHoroDiv").empty();
    $("#createHoroDiv").append('<iframe class="brdr-0 fullwid hgt275" src="https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_DataEntry_Matchstro.dll?BirthPlace?js_UniqueID='+id+'&js_year='+yy+'&js_month='+mm+'&js_day='+dd+'"></iframe>');
}

$(document).ready(function() {
  updateProfileCompletionScore(profileCompletionValue);
  EditApp.init();
  $('body').on('click',function(event){
        if($(event.target).hasClass("js-boxContent") || $(event.target).hasClass("js-decVal") || $(event.target).parent().hasClass("boxType") || $(event.target).parent().attr("rel") == "dtofbirth" || $(event.target).hasClass("dropdown_span") || event.target.getAttribute("id") == "dayArrow2" || event.target.getAttribute("id") == "monthArrow2" || event.target.getAttribute("id") == "yearArrow2" || event.target.getAttribute("id") == "dtofbirth"){
                callBlur = 0;
        }else{
                callBlur = 1;
                $("#dtofbirth").trigger("blur");
        }
  });
  $('body').on('focus', '.chosen-container-single input', function(event) {
    if (!$(this).closest('.chosen-container').hasClass('chosen-container-active')){
      $(this).closest('.chosen-container').trigger('mousedown');
    }
    
  
    
    //Some awful checks !!
    var id = $($(this).closest('.chosen-container-single')).attr('id');
    
    if(id.indexOf("native_state") != -1 ||id.indexOf("native_country") != -1){
      $('.js-toggleNativeFields').css('z-index','1012');
    }
    else{
      $('.js-toggleNativeFields').css('z-index','1');
    }
    
    if(!(
       $(event.target).hasClass('js-autoSuggest') || 
       $(event.target).parent().hasClass('js-autoSuggest') ||  
       $(event.target).parent().parent().hasClass('js-autoSuggest') ||
       $(event.target).parent().hasClass('js-autoSuggestOption')
       ))
    {
      $('.js-autoSuggest').addClass('disp-none');
      $('.js-autoSuggest').trigger('abort-request');
    }
    //close all Box Type fields
    $('.js-boxField').trigger('boxBlur');
    $('.js-timeClick').trigger('timeBlur');
  });
  $("#Rbt_yes").click(function(){
      	showCommonLoader();
        $.ajax({
          method: "POST",
          url : "/api/v2/profile/deleteHoroscope",
          async:true,
          data : {profilechecksum:ProCheckSum},
          timeout:20000,
          success:function(response){
          		hideCommonLoader();
              $("#removeHoroscopeLayer").fadeOut("fast",function(){
                $("#commonOverlay").fadeOut("fast"); 
              });
              location.reload();
          }
        });  
      });
    $(".js-deleteHoro").on('click', function(){
                $("#removeHoroscopeDiv").removeClass('disp-none');
                $("#Rbt_yes, #Rbt_no").addClass("cursp").removeClass("bg6");
                $("#commonOverlay").fadeIn("fast",function(){
                    $("#commonOverlay").on('click',function(){
                        $("#removeHoroscopeLayer").fadeOut("fast",function(){
                            $("#commonOverlay").fadeOut("fast"); 
                        });
                    }); 
                    $("#removeHoroscopeLayer").fadeIn("fast"); 
                });
                ajaxInsertAstroPull(0);
    });
    $("#Rbt_no").on('click',function(){
        $("#removeHoroscopeLayer").fadeOut("fast",function(){
            $("#commonOverlay").fadeOut("fast"); 
        });
    });
    $("#removeClosebtnHL").on('click',function(){
        $("#removeHoroscopeLayer").fadeOut("fast",function(){
            $("#commonOverlay").fadeOut("fast"); 
        });
    });
  
  $('body').on('focus', '.js-boxField', function(event) {
    var myId = $(this).attr('id');
    $('.js-boxField:not(#'+myId+')').trigger('boxBlur');
  });
  $('body').on('focus', 'input', function(event) {
    $('.js-boxField').trigger('boxBlur');
  });
  $('body').on('focus', '.js-save', function(event) {
    $('.js-boxField').trigger('boxBlur');
  });
  
  $('body').on('focus', 'textarea', function(event) {
    $('.js-timeClick').trigger('timeBlur');
  });

    onClickProfileCompleteLinks();
    onCreateUploadHoroBtn();
    onaddHoroscopeCloseBtn();
    onUploadHoroBtn();
    onCreateHoroBtn();
    onBrowseFileBtn();
    submitHoro();
    bindShare();
    onViewHoroscope();
    onClickOfHoroscopeOverlay();
    onClickViewHoroCloseBtn();
    onClickHoroscopeMust();

	$("body").on("click",'.js-uploadPhoto',function()
    {
            window.location="/social/addPhotos";
    });
    if(EditWhatNew){
        redirectToEditSection(EditWhatNew);
    }
  
    
    $("body").on('click','#alt_email_statusView',function () {
		if($("#alt_email_statusView").html()!='Verify') return;
                showCommonLoader();
                var ajaxData={'emailType':'2'};
                var ajaxConfig={};
                ajaxConfig.data=ajaxData;
                ajaxConfig.type='POST';
                ajaxConfig.url='/api/v1/profile/sendEmailVerLink';
                ajaxConfig.success=function(resp)
                {
                    showAlternateConfirmLayer($("#my_alt_emailView"));   
                    hideCommonLoader();
                }
                jQuery.myObj.ajax(ajaxConfig);
	});
    $("body").on('click','#email_statusView',function () {
		if($("#email_statusView").html()!='Verify') return;
                showCommonLoader();
                var ajaxData={'emailType':'1'};
                var ajaxConfig={};
                ajaxConfig.data=ajaxData;
                ajaxConfig.type='POST';
                ajaxConfig.url='/api/v1/profile/sendEmailVerLink';
                ajaxConfig.success=function(resp)
                {
                    showAlternateConfirmLayer($("#my_emailView"));   
                    hideCommonLoader();
                }
                jQuery.myObj.ajax(ajaxConfig);
	});

      if(typeof(fromCALAlternate)!= "undefined" && fromCALAlternate == '1')
    {   
        $('html, body').animate({
         scrollTop: ($('#section-basic').offset().top)
      },500);
        var newUrl=document.location.href.replace('fromCALAlternate','');
        history.pushState('', '', newUrl);
    }

    getFieldsOnCal();

});

$(document).mousedown(function (event)
{
    if(!(
       $(event.target).hasClass('js-autoSuggest') || 
       $(event.target).parent().hasClass('js-autoSuggest') ||  
       $(event.target).parent().parent().hasClass('js-autoSuggest') ||
       $(event.target).parent().hasClass('js-autoSuggestOption')
       ))
    {
      $('.js-autoSuggest').addClass('disp-none');
      $('.js-autoSuggest').trigger('abort-request');
    }
    
    //BoxType Field Blur
    if( typeof $(event.target).attr('class') == "string" && 
         $(event.target).attr('class').length             && !(( 
         $(event.target).attr('class').indexOf('sub_option_') || 
         $(event.target).attr('class').indexOf('option_') || 
         $(event.target).hasClass('js-boxSubListOption') ) && 
       ( $(event.target).parent().parent().hasClass('js-boxContent') ||
         $(event.target).parent().parent().hasClass('js-subBoxList') 
       ) ) )
    {
      $('.js-boxField').trigger('boxBlur');
    }
    
    //TimeField Blur
    if( typeof $(event.target).attr('class') == "string" && 
        $(event.target).attr('class').length             && 
     !(
      $(event.target).parent().hasClass('timelist') ||
      $(event.target).parent().hasClass('t2list') ||
      $(event.target).closest('.js-timeClick').length==1
      ))
    {
      $('.js-timeClick').trigger('timeBlur');
    }
    
});

/******* Share Profile *****/
function bindShare(){
//Functioning on click of share button
$('.js-action').click(function(){
    
    var offset = $('.prfbtnbar').offset();
    offset = offset-20;
    if($(this).hasClass('share'))
    {
      $('.js-overlay').fadeIn(200,"linear",function(){ $('#share-layer').fadeIn(300,"linear")});
      $('#confirmationMessage').hide();
    }
  });

//Fucntioning on click of 'cross' button on Share profile div
$('.js-undoAction,.js-overlay').click(function(){
  if($(this).hasClass('undoShare'))
  {
    $('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
    $('#errorText').html("");
  }
  if($(this).hasClass('close'))
  {
    $('#shareProfileDiv').show(300);
    $('#shareProfileTopSection').show(300);
    $('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});
  }
  if($(this).hasClass('js-overlay'))
    $('#share-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});

});

//Functioning on click of 'tick' on share layer. Email validation done first
$('#validateSenderEmail').click(function(e){

  var errorMessage = "Please enter a valid e-mail id";
  var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
  if(!email_regex.test($('#receiverEmail').val()))
  {
    $('#errorText').html(errorMessage);
  }
  else
  { 
    $('#errorText').html("");
    shareProfile();

  }
  return false;
});
}
function showAlternateConfirmLayer(jObject){
    var obj = $("#js-alternateEmailConfirmLayer");
    var msg = obj.find("#altEmailDefaultText").eq(0).val().replace(/\{email\}/g,jObject.eq(0).text().trim());
    obj.find("#altEmailConfirmText").eq(0).text(msg);
    showLayerCommon("js-alternateEmailConfirmLayer");
    obj.find('.closeCommLayer').eq(0).bind('click',function(){
                        closeCurrentLayerCommon();  
                        $(this).unbind();
                        });
                    $('.js-overlay').bind("click", function () 
                        {
                        closeCurrentLayerCommon();  
                        $(this).unbind();
                        });
}
function shareProfile(){
  var dataArr = {};
  var femail = {};
  dataArr["email"] = senderEmail;
  dataArr["name"] = $('#senderName').val();
  dataArr["femail[]"] =$("#receiverEmail").val();
  dataArr["message"] = $("#message").val();
  dataArr["profilechecksum"] = ProCheckSum;
  dataArr["ajax_error"] = "2";
  dataArr["invitation"] = "1";
  dataArr["send"]= "1";
  dataArr["username"] = username;
  showCommonLoader(); 
  $.ajax({
    type : 'POST',
    url : '/profile/forward_profile.php',
    data:  {dataArrObj: JSON.stringify(dataArr), isJson: "1"},
    async:true,
    success:function(response){
      hideCommonLoader();
      if(response == "bye"){
        showConfirmationMessage();
      }
      else if(response == "Mail not sent"){
        showErrorMessage();
      }
      else if(response == "ERROR#Friend Emailid not provided"){
        var errorMessage="Please provide receiver's emailid"
        $('#errorText').html(errorMessage);
      }
    }
  });
}

function showErrorMessage(){
  var errMessage="You have reached the maximum limit of number of profiles you can share in a day.<br> Please try after 24 hours";
  $('#shareProfileDiv').hide();
  $('#shareProfileTopSection').hide();
  $('#confirmationMessage').show();
  $('#addConfirmationMessage').html(errMessage);
}
function showConfirmationMessage(){
  var confMessage="An email containing profile details has been sent to your contact";
  $('#shareProfileDiv').hide();
  $('#shareProfileTopSection').hide();
  $('#confirmationMessage').show();
  $('#addConfirmationMessage').html(confMessage);
}

//Function to update profile completion score on each call
function updateProfileCompletionScore(score){
 $profileScore = $('div#profileCompletionScore').dynameter({
            // REQUIRED.
            value: score,
            min: 0,
            max: 100,
            regions: {
              100: 'warn',
              100: 'error'
            }

        });
}

$('.js-previewAlbum').click(function(){
    var photoData = $(this).attr("data");
    photoData = photoData.split(",");
    var username = photoData[1];
    var profilechecksum = photoData[2];
    var albumCount = photoData[0];
    if((typeof(loggedInJspcUser)!="undefined" && loggedInJspcUser=="") || !profilechecksum){
                return true;
     }
    openPhotoAlbum(username,profilechecksum,albumCount);

})
    function onDisplayNameChange(fieldObject){
        $(".optionDrop li").each(function(index, element) {
            $(this).on("click",function(){
                                $(".optionDrop li").each(function(index, element) {
                                        $(this).removeClass("selected");
                                });
                                $(this).addClass("selected");
				if($(this).attr("id") == "showYes")
				{
					var value = "Y";
					var text = "Show to All";
				}
				else
				{
					var value = "N";
					var text = "Don't Show";
				}
				$("#showText").html(text);
				storeFieldChangeValue(fieldObject,value);
				$("#optionDrop").removeClass("optionDrop");
				setTimeout(function(){ $("#optionDrop").addClass("optionDrop");}, 500);
                        });
        });
	}
	function showDisplayNameSettingFirstTime(fieldObject)
	{
		if(fieldObject.value!="N")
		{
			var show = "#showYes";
			var hide = "#showNo";
			var text = "Show to All";
		}
		else
		{
			var show = "#showNo";
			var hide = "#showYes";
			var text = "Don't Show";
		}
		$(hide).removeClass("selected");
		$(show).addClass("selected");
		$("#showText").html(text);
	}

  /**
   * function opens a field when a parameter is passed in url 
   * 
   */
  function getFieldsOnCal()
  {
    desktopSectionArray = {"education":"career","basic":"basic","about":"about",
      "career":"career","lifestyle":"lifestyle","contact":"contact","family":"family"
    }
    timeoutFieldCheck = 1000;
    section = getUrlParameter('section');
    fieldName = getUrlParameter('fieldName');
    if ( typeof section !== 'undefined' && $("[data-section-id="+desktopSectionArray[section]+"]").length)
    {
      $("[data-section-id="+desktopSectionArray[section]+"]").click();
      $('html, body').animate({
           scrollTop: ($('#section-'+desktopSectionArray[section]).offset().top)
        },'slow'); 
    }

    setTimeout(function() {
      if ( EditApp.getEditAppFields(section,fieldName) != false )
      {
        if ( EditApp.getEditAppFields(section,fieldName).type == "M" || EditApp.getEditAppFields(section,fieldName).type == "S" )
        {
          desktopFieldId = EditApp.getEditAppFields(section,fieldName).key.toLowerCase() + "_chosen";
          fieldType = "dropdown"; 
        }
        else
        {
          desktopFieldId = EditApp.getEditAppFields(section,fieldName).key.toLowerCase();
          fieldType = "text"; 
        }
        openFieldsOnCal(fieldType,desktopFieldId); 
      }                           
    }, timeoutFieldCheck);
  }
  /**
 * Only dummy function | Here it only does the job of closing the layer
 *
 * @param        clickAction  The click action
 * @param        button       The button
 */
function criticalLayerButtonsAction(clickAction ,button){
closeCurrentLayerCommon();
if(button == "B2"){
        $('#criticalAction-layer').attr("style", "display:none;");
}else{
        if(button == ""){
                DataUpdated = 0;
                EditApp.updateNeedToUpdate();
                EditApp.init();
                var handle = setInterval(function(){
                        if(DataUpdated == 1){
                             var aadhar = EditApp.getEditAppFields("basic","AADHAAR");
                                var name = EditApp.getEditAppFields("basic","NAME");
                                $("#nameView").html(name.decValue);
                                if(aadhar.decValue != ""){
                                        $("#aadhaarView").text(aadhar.decValue).addClass("color11").removeClass("color5");
                                        $("#aadhaarLabelParent").find(".js-undSecMsg").removeClass("disp-none");
                                }else{
                                        $("#aadhaarView").text("Not filled in").removeClass("edpcolr2").removeClass("color11").addClass("color5");
                                        $("#aadhaarLabelParent").find(".js-undSecMsg").addClass("disp-none");
                                }
                                showHideEditSection("basic","hide");
                                clearInterval(handle);
                        }
                },500);
        }
}
$('body').css("overflow", "initial");
}

/**
 * Only dummy function
 *
 * @param        button   The button
 * @param        layerId  The layer identifier
 */
function trackingCAL(button/*B1/B2*/, layerId){
 
}
  /**
   * opens a field
   * @param  {String} fieldType dropdown or text
   * @param  {String} fieldId   the id which should be clicked
   */
  function openFieldsOnCal(fieldType,fieldId) 
  {
    if ( fieldType == 'dropdown')
    {
        $("#"+fieldId).trigger('mousedown');
    }
    else
    {
        $("#"+fieldId).focus();
    }
  }
  /**
   * function is used to get url get parameters
   * @return {String}      get parameter
   */
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
