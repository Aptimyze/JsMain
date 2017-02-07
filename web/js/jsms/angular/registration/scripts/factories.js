(function(){
	'use strict';
	var app = angular.module('regApp.factories',[]);
	var regErr ="regErr";

	//Constants
	app.factory ('Constants',function($window){
		var factory = {};
		var arrUrl = {'BaseUrl':'/register/','ApiBaseUrl':'/api/v1/register/'};
		var iHeaderHeight = 60;
		var iNextBtnHeight = 50;
        var pixelCode = "alert('k');";
		var arrErors = {
			"RELIGION_ERROR_1":"Choose married only for muslim male.",
			"DOB_ERROR_0":"Please provide date of birth",
			"DOB_ERROR_1":"Provide a valid date of birth",
			"DOB_ERROR_2":"Profile should be at least 18 years old to register",
			"DOB_ERROR_3":"Profile should be at least 21 years old to register",
			"PINCODE_ERROR_1":"Provide a valid pincode",
			"PINCODE_ERROR_2":"Provide a pincode",
			"EMAIL_REQUIRED":"Provide an email.",
			"NAME_REQUIRED":"Provide the name of user for whom account is being created",
			"EMAIL_INVALID":"Provide a valid email.",
			"NAME_INVALID":"Provide a valid name.",
			"EMAIL_INVALID_DOMAIN":"Provide a valid email.",
			"EMAIL_EXIST":"An account with this Email already exists",
			"PASSWORD_REQUIRED":"Provide a password",
			"PASSWORD_INVALID":"Provide a valid password.",
			"PASSWORD_COMMON":"The password you have chosen is not secure",
			"MOBILE_REQUIRED":"Provide a mobile number",
			"MOBILE_INVALID":"Provide a valid mobile number",
			"ISD_REQUIRED":"Provide an ISD code",
			"ISD_INVALID":"Provide a valid ISD code"
		};
        var emailCorrections = {
			"gamil.com" : "gmail.com",
			"gmai.com" :"gmail.com",
			"gmil.com":"gmail.com",
			"gmal.com":"gmail.com",
			"gmaill.com":"gmail.com",
			"gmail.co":"gmail.com",
			"gail.com":"gmail.com",
			"gmail.om":"gmail.com",
			"gmali.com":"gmail.com",
			"gmail.con":"gmail.com",
			"gmail.co.in":"gmail.com",
			"gmail.cm":"gmail.com",
			"gmail.in":"gmail.com",
			"gimal.com":"gmail.com",
			"gnail.com":"gmail.com",
			"gimail.com":"gmail.com",
			"g.mail.com":"gmail.com",
			"gmailil.com":"gmail.com",
			"gmail.cim":"gmail.com",
			"gemail.com":"gmail.com",
			"gmall.com":"gmail.com",
			"gmail.com.com":"gmail.com",
			"gmeil.com":"gmail.com",
			"gmsil.com":"gmail.com",
			"gmail.comn":"gmail.com",
			"gmail.cpm":"gmail.com",
			"gimel.com":"gmail.com",
			"gmailo.com":"gmail.com",
			"gmile.com":"gmail.com",
			"fmail.com":"gmail.com",
			"yhoo.com":"yahoo.com",
			"yaho.com":"yahoo.com",
			"yahool.com":"yahoo.com",
			"yhaoo.com":"yahoo.com",
			"yahoo.co":"yahoo.com",
			"yaoo.com":"yahoo.com",
			"yhaoo.co.in":"yahoo.com",
			"yahoo.com.in":"yahoo.co.in",
			"yamil.com":"ymail.com",
			"yhoo.in":"yahoo.in",
			"yahho.com":"yahoo.com",
			"yahoo.com.com":"yahoo.com",
			"redifmail.com":"rediffmail.com",
			"reddifmail.com":"rediffmail.com",
			"reddffmail.com":"rediffmail.com",
			"rediffmaill.com":"rediffmail.com",
			"rediffmai.com":"rediffmail.com",
			"rediffmal.com":"rediffmail.com",
			"reddiffmail.com":"rediffmail.com",
			"redifffmail.com":"rediffmail.com",
			"rediffimail.com":"rediffmail.com",
			"rediiffmail.com":"rediffmail.com",
			"rediifmail.com":"rediffmail.com",
			"rediffmil.com":"rediffmail.com",
			"rediffmail.co":"rediffmail.com",
			"rediffmail.con":"rediffmail.com",
			"rediffmail.cm":"rediffmail.com",
			"rediffmial.com":"rediffmail.com",
			"redffimail.com":"rediffmail.com",
			"rdiffmail.com":"rediffmail.com",
			"radiffmail.com":"rediffmail.com"
        };
        
        var msgTimeOut = 3500;
        var animationTimeOut = 500;
		factory.getBaseUrl = function()
		{
			return arrUrl['BaseUrl'];
		}
        factory.ApiBaseUrl = function()
        {
            return arrUrl['ApiBaseUrl'];
        }
		factory.getWindowWidth =function()
		{
			return $window.innerWidth;
		}
		factory.getWindowHeight = function()
		{
			return $window.innerHeight;
		}
		factory.getHeaderHeight =function()
		{
			return iHeaderHeight;
		}
		factory.getNextBtnHeight =function()
		{
			return iNextBtnHeight;
		}
		factory.getErrorMsg = function(key)
		{
			return arrErors[key];
		}
		factory.getEmailCorrections = function()
		{
			return emailCorrections; 
		}
		factory.getMsgTimeOut = function()
		{
			return msgTimeOut;
		}
        factory.getAnimationTimeOut = function()
        {
            return animationTimeOut;
        }
        factory.setPixelCode = function(pixel)
        {
            pixelCode = pixel;
        }
        factory.getPixelCode = function()
        {
            return pixelCode;
        }
		return factory;
	});	

	app.factory ('Gui',function(UserDecision,Storage,Constants,$timeout,$location,$anchorScroll){
		var factory = {};
		var slideDir = "slide-left";
		var notFilled = "Not Filled In";
		var notFilledISD ='ISD';
		var bDoItOnce = false;
        var bFirstViewDir = true;
    var bFromIncomplete = false;    
		var customRelationShipWidget = {
							'self':{'icon':'selficon','label':'Self','value':'1'},
							'rel':{'icon':'relicon','label':'Relative','value':'4R'},
							'bro':{'icon':'broicon','label':'Brother','value':'6'},
							'sis':{'icon':'sisicon','label':'Sister','value':'6D'},
							'son':{'icon':'sonicon','label':'Son','value':'2'},
							'dau':{'icon':'dauicon','label':'Daughter','value':'2D'},
							'mb':{'icon':'mbicon','label':'Marriage Bureau','value':'5'},
							'fri':{'icon':'friicon','label':'Friend','value':'4F'},
							};
		var regOptionalFields = {
		"s4": [{"label":"Children","value":notFilled,"show":"false","screenName":"s4","userDecision":"","dindex":"0","storeKey":"havechild",dshow:"children"},
			{"label":"Caste","value":notFilled,"show":"false","screenName":"s4","userDecision":"","dindex":"1","storeKey":"caste",dshow:"reg_caste_"}],
        "s9": [{"label":"Of which married","value":notFilled,"show":"false","screenName":"s4","userDecision":"","dindex":"0","storeKey":"m_brother",dshow:"m_brother","labelPrefix":" brother(s) of which married "},
			{"label":"Of which married","value":notFilled,"show":"false","screenName":"s4","userDecision":"","dindex":"1","storeKey":"m_sister",dshow:"m_sister","labelPrefix":" sister(s) of which married "},
			{"label":"City","value":notFilled,"show":"false","storeKey":"native_city","screenName":"s9","dshow":"reg_city_","userDecision":"","dindex":"2","labelPrefix":"-"}],
		};
		
		var listOfScreens = ['s1','s2','s3','s4','s5','s6','s9','s10'];
		var regFields = {
		"s1" : [{"label":"Posted By","value":notFilled,"show":"true","screenName":"s1","ficon":"ficon","micon":"micon","storeKey":"relationship","userDecision":"","dshow":"relationship","errorLabel":"","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dindex":"0","tapName":"Create Profile For"}],
		"s2" : [
			{"label":"Gender","value":notFilled,"show":"true","screenName":"s2","userDecision":"","dindex":"0","dshow":"gender","storeKey":"gender","errorLabel":""},
			{"label":"Date of birth","value":notFilled,"show":"true","multiField":"1","screenName":"s2","storeKey":"dtofbirth","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"dtofbirth","userDecision":"","dindex":"1","depValue":"","errorLabel":"","tapName":"Date of birth"},
			{"label":"Height","value":notFilled,"show":"true","screenName":"s2","storeKey":"height","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"height","userDecision":"","dindex":"2","tapName":"Height","defaultValue":"13"},
			{"label":"Country living in","value":notFilled,"show":"true","storeKey":"country_res","screenName":"s2","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"country_res","userDecision":"","dindex":"3","tapName":'Country',"dependant_tapName":""},
			{"label":"State living in","value":notFilled,"show":"false","storeKey":"state_res","screenName":"s2","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"state_res","userDecision":"","dindex":"4","tapName":'State',"dependant_tapName":""},
			{"label":"City living in","value":notFilled,"show":"false","storeKey":"city_res","screenName":"s2","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"reg_city_jspc","userDecision":"","dindex":"5","tapName":'City',"dependant_tapName":""},
			{"label":"Area Pincode","value":"","inputType":"number","hint":"Your area pincode","show":"true","screenName":"s2","storeKey":"pincode","hamburgermenu":"0","dindex":"6","errClass":""}
				],
		"s3" : [
			{"label":"Highest Education","value":notFilled,"show":"true","screenName":"s3","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"edu_level_new","userDecision":"","dindex":"0","storeKey":"edu_level_new","tapName":"Highest Education"},
      {"label":"PG degree (optional)","value":notFilled,"show":"false","screenName":"s3","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"degree_pg","userDecision":"","dindex":"1","storeKey":"degree_pg","tapName":"PG Degree","required":"false"},
      {"label":"PG college (optional)","value":"","show":"false","screenName":"s3","hamburgermenu":"0","userDecision":"","dindex":"2","storeKey":"pg_college","inputType":"text","hint":notFilled,"required":"false"},
      {"label":"Other PG degree (optional)","value":"","show":"false","screenName":"s3","hamburgermenu":"0","userDecision":"","dindex":"3","storeKey":"other_pg_degree","inputType":"text","hint":notFilled,"required":"false"},
      {"label":"UG degree (optional)","value":notFilled,"show":"false","screenName":"s3","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"degree_ug","userDecision":"","dindex":"4","storeKey":"degree_ug","tapName":"UG Degree","required":"false"},
      {"label":"UG college (optional)","value":"","show":"false","screenName":"s3","hamburgermenu":"0","userDecision":"","dindex":"5","storeKey":"college","inputType":"text","hint":notFilled,"required":"false"},
      {"label":"Other UG degree (optional)","value":"","show":"false","screenName":"s3","hamburgermenu":"0","userDecision":"","dindex":"6","storeKey":"other_ug_degree","inputType":"text","hint":notFilled,"required":"false"},
			{"label":"Work Area","value":notFilled,"show":"true","screenName":"s3","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"occupation","userDecision":"","dindex":"7","storeKey":"occupation","tapName":"Work Area"},
			{"label":"Annual Income","value":notFilled,"show":"true","screenName":"s3","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"income","userDecision":"","depValue":"","dindex":"8","storeKey":"income","tapName":"Annual Income"}
				],
		"s4": [
			{"label":"Marital Status","value":notFilled,"show":"true","screenName":"s4","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"children","dshow":"reg_mstatus","userDecision":"","dindex":"0","storeKey":"mstatus","optIndex":"0","tapName":"Martial Status","dependant_tapName":"Have Children"},
			{"label":"Mother Tongue","value":notFilled,"show":"true","screenName":"s4","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"reg_mtongue","userDecision":"","dindex":"1","storeKey":"mtongue","tapName":"Mother Tongue"},
			{"label":"Religion-Caste","value":notFilled,"show":"true","screenName":"s4","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"reg_caste_","depValue":"","dshow":"religion","userDecision":"","dindex":"2","storeKey":"religion","optIndex":"1","tapName":"Religion","dependant_tapName":""},
      {"label":"Horoscope match is necessary? (optional)","value":notFilled,"show":"true","screenName":"s4","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","depValue":"","dshow":"horoscope_match","userDecision":"","dindex":"3","storeKey":"horoscope_match","tapName":"Horoscope match is necessary?","dependant_tapName":"","required":"false"}
			],
		"s5": [
      {"label":"Full Name","value":"","show":"true","screenName":"s5","hamburgermenu":"0","userDecision":"","dindex":"0","storeKey":"name_of_user","inputType":"text","hint":notFilled,"required":"true"},
			{"label":"Email ID","value":"","show":"true","screenName":"s5","inputType":"email","hint":notFilled,"storeKey":"email","errorLabel":"","dindex":"1","errClass":"","isAutoCorrected":"false"},
			{"label":"Password","value":"","show":"true","screenName":"s5","inputType":"password","hint":notFilled,"helpText":"Show","storeKey":"password","errorLabel":"","dindex":"2","errClass":""},
			{"label":"Phone Number","value":"","show":"true","screenName":"s5","inputType":"number","hint":notFilled,"storeKey":"phone_mob","errorLabel":"","dindex":"3","isdVal":91,"isdHint":notFilledISD,'isdMaxlength':'4','maxLength':'10','maxNriLength':'14',"errClass":""},
			{"screenName":"s5","storeKey":"displayname","dindex":4
			}
			],
		"s6": [	{"label":"Email ID","value":"","show":"true","screenName":"s6","storeKey":"yourinfo",'errorLabel':"","hint":"Introduce yourself (Don't mention your name). Write about your values, beliefs/goals, aspirations/interests and hobbies.\n\n\n This text will be screened by our team.","userDecision":""}],
        "s9": [
			{"label":"Family Type","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"family_type","userDecision":"","dindex":"0","storeKey":"family_type","tapName":"Family Type"},
			{"label":"Family Values","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","dshow":"family_values","userDecision":"","dindex":"1","storeKey":"family_values","tapName":"Family Values"},
            {"label":"Family Status","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","depValue":"","dshow":"family_status","userDecision":"","dindex":"2","storeKey":"family_status","tapName":"Family Status"},
			{"label":"Family Income","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","depValue":"","dshow":"family_income","userDecision":"","dindex":"3","storeKey":"family_income","tapName":"Family Income"},
            {"label":"Father's Occupation","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","depValue":"","dshow":"family_back","userDecision":"","dindex":"4","storeKey":"family_back","tapName":"Father's Occupation"},
            {"label":"Mother's Occupation","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"","depValue":"","dshow":"mother_occ","userDecision":"","dindex":"5","storeKey":"mother_occ","tapName":"Mother's Occupation"},
            {"label":"Brother(s)","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"m_brother","depValue":"","dshow":"t_brother","userDecision":"","dindex":"6","storeKey":"t_brother","tapName":"Brother(s)","dependant_tapName":"Of which married","optIndex":"0"},
            {"label":"Sister(s)","value":notFilled,"show":"true","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"m_sister","depValue":"","dshow":"t_sister","userDecision":"","dindex":"7","storeKey":"t_sister","tapName":"Sister(s)","dependant_tapName":"Of which married","optIndex":"1"},
			{"label":"Family based out of","value":notFilled,"show":"true","storeKey":"native_state","screenName":"s9","hamburgermenu":"1","dmove":"right","dhide":"single","dselect":"radio","dependant":"reg_city_","depValue":"","dshow":"native_state_jsms","userDecision":"","dindex":"8","tapName":'Family based out of',"optIndex":"2","dependant_tapName":"Family based out of"},
			{"label":"Please specify(city)","value":"","show":"false","storeKey":"ancestral_origin","screenName":"s9","hamburgermenu":"0","dindex":"9","hint":notFilled,"inputType":"text","userDecision":"","required":"false"},
            {"label":"Gothra","value":"","inputType":"text","hint":notFilled,"show":"true","screenName":"s9","storeKey":"gothra","hamburgermenu":"0","dindex":"10"},
            
			],
            "s10": [{"label":"About family","value":"","show":"true","screenName":"s10","storeKey":"familyinfo",'errorLabel':"","hint":"Write about your parents and brothers or sisters. Where do they live? What are they doing?","userDecision":""}],
		};
		
		factory.getRelationShipWidget = function(){
			return customRelationShipWidget;
		}
		
		factory.getSlideDir = function()
		{
            if(bFirstViewDir)
                return "";
			return slideDir;
		}
		factory.setSlideDir = function(szDir)
		{
			if(szDir=="right")
				slideDir = "slide-right";
			else if(szDir=="left")
				slideDir = "slide-left";	
            if(bFirstViewDir)
                bFirstViewDir = false;
		}
		factory.hideLoader =function($scope)
		{
			$scope.bLoaderWindow = false;
			factory.hideModalWidow($scope);
		}
		factory.showLoader = function($scope)
		{
            $timeout(function(){
                $scope.bLoaderWindow = true;
                $scope.bModalWindow =true;    
            },1);
		}
		factory.hideModalWidow = function($scope)
		{
            if(typeof $scope.serverErrors!="undefined" && $scope.serverErrors.length)
                $scope.serverErrors = [];
			factory.clearErrors($scope.screenName);
			$scope.bModalWindow =false;
		}
		factory.showModalWindow = function($scope)
		{
			$scope.bModalWindow =true;
			$timeout(function(){if($scope.bModalWindow)factory.hideModalWidow($scope);},Constants.getMsgTimeOut());
		}
        factory.toastMsg = function($scope,szMsg)
        {
            if(szMsg && szMsg.length)
            {
                factory.hideModalWidow($scope);
                $scope.serverErrors = new Array();
                $scope.serverErrors[0] = szMsg;
                factory.showModalWindow($scope);
            }
        }
		factory.sanitizeString = function(val)
		{
                    if(typeof val !== "string")
                        return;
			if(val)
			{
				if(val.search('&quot') !=-1);
					val = val.replace(/&quot;/g,'"');
				if(val.search('&amp') !=-1);
					val = val.replace(/&amp;/g,'&');
				if(val!=false && typeof val === "string")							
					return val;
			}
		}
		factory.calcSlideDir = function(newUrl,oldUrl)
		{
			var newScreenName = newUrl.split('#/s');
			var oldScreenName = oldUrl.split('#/s');
			var slideDir = "";
			
			if(newScreenName[1] && oldScreenName[1])
			{
				if(parseInt(newScreenName[1]) < parseInt(oldScreenName[1]))
				{
					slideDir = "right";
				}
				else
				{
					slideDir = "left";
				}
				factory.setSlideDir(slideDir);
				return slideDir;
			}
			return false;
		}
		factory.getLabel = function(fieldMapKey,userDecision)
		{
			var val = null;
			if(fieldMapKey)
			{
				val = Storage.getLabel(fieldMapKey,userDecision);
				if(typeof val === 'object') 
				{
					var kyz = Object.keys(val);
					if(kyz.length === 1)
					{
						val = val[kyz[0]];
					}				
				}
				return factory.sanitizeString(val);
			}
			return val;
		}
		factory.initDobField = function(field,preFilledData)
		{
			if(!field)
				return ;
				
			var day = field.dshow+"_"+"day";
			var month = field.dshow+"_"+"month";
			var year = field.dshow+"_"+"year";
			var arrValue = [];
			var arrDecision = [];
			var monthLabel = ['Jan','Feb','Mar','April','May','June','July','Aug','Sept','Oct','Nov','Dec'];
			if(preFilledData[day])
			{
				arrValue.push(preFilledData[day]);
				arrDecision.push(preFilledData[day]);
			}
			else
			{
				arrDecision.push("1");
			}
			
			if(preFilledData[month])
			{	
				arrValue.push(monthLabel[preFilledData[month]-1]);
				arrDecision.push(preFilledData[month]);
			}
			else
			{
				arrDecision.push("1");
			}
			
			if(preFilledData[year])
			{
				arrValue.push(preFilledData[year]);
				arrDecision.push(preFilledData[year]);
			}
			
			if(arrValue.length === 0)
			{
				arrDecision = [];
			}
			else
			{
				field.value = arrValue.join(" ");
				field.userDecision= arrDecision.join(",");
			}
			
		}
		factory.clearErrors = function(screenName)
		{
			angular.forEach(regFields[screenName], function(field,key) {
				field.errorLabel = "";
			});
		}
		factory.handleDepValue = function(field)
		{
			var	arrDependantFields = [];
			var iCount = 0;
			switch(field.storeKey)
			{
				case 'gender':
					arrDependantFields[iCount] = regFields['s4'][0];/*Martial Status*/
                    arrDependantFields[iCount].value = notFilled;
                    arrDependantFields[iCount].userDecision = "";
					arrDependantFields[++iCount] = regFields['s2'][1];/*DOB Field*/                    
				break;
				case 'country_res':
					arrDependantFields[iCount] = regFields['s3'][8];/*Income*/
                    arrDependantFields[iCount].value = notFilled;
                    arrDependantFields[iCount].userDecision = "";
                    arrDependantFields[++iCount] = regFields['s9'][3];/*Family Income*/
                    Storage.storeUserData('familyIncomeDep',parseInt(field.userDecision));
                   // if(field.userDecision.indexOf('51')!=-1)/*Make optional field show as true*/
                     //   regOptionalFields["s2"]['0'].show = true;
                    factory.updateISDVal(field.userDecision);
				break;
				case 'state_res':
				    Storage.storeUserData('stateDep',field.userDecision);				
				break;
				case 'mtongue':
					arrDependantFields[iCount] = regFields['s4'][2];/*Religion*/
				break;
				default:
					return;
			}
			if(typeof arrDependantFields === "object" && arrDependantFields.length)
			{
				for(var i = 0;i<=iCount;i++)
				{
					arrDependantFields[i].depValue = field.userDecision;
				}
			}
		}
		factory.initRegFields = function(screenName,userStoreKey)
		{
			var preFilledData = null;
      if(typeof userStoreKey === "string"){
        var oldUDKey = UserDecision.getUDKey();
        UserDecision.setUDKey(userStoreKey);
        userStoreKey = oldUDKey;
      }
      
      preFilledData= UserDecision.getScreenData(screenName);
			if(preFilledData == null){
        UserDecision.setUDKey(userStoreKey);
				return ;
      }	
			angular.forEach(regFields[screenName], function(field,key) {
				if(field.dshow == "dtofbirth" && screenName == "s2")
				{
					factory.initDobField(field,preFilledData);
					return;
				}
				if(field.storeKey=="native_state" && screenName=="s9" && !preFilledData['native_state'])
				{
                                        if(preFilledData["native_country"]=="0")
                                                return;

                                        field.userDecision = preFilledData["native_country"];
					val = factory.getLabel("native_country_jsms",field.userDecision);
					if(val)
						field.value = val;
					return;
				}					
				if( field.storeKey && 
					(preFilledData[field.storeKey] && 
					preFilledData[field.storeKey] != "undefined")
				  )
				{
					if(field.dshow && field.hamburgermenu == "1")
						Storage.getData(field.dshow);
					
					var val = null;
					field.userDecision = preFilledData[field.storeKey];
					
					if(field.dshow)
					{
						val = factory.getLabel(field.dshow,field.userDecision);
						if(val)
							field.value = val;
					}
					else
					{
						field.value = field.userDecision;
						if(field.storeKey === "phone_mob")
						{
							var arrPhone = field.value.split(',')
							field.isdVal = parseInt(arrPhone[0]);
							field.value = parseInt(arrPhone[1]);
						}
						if(field.inputType && field.inputType ==="number")
						{
							field.value = parseInt(field.value);
						}
					}
                    //handle Dep Value
                    factory.handleDepValue(field);
					//Handle Optional Field
					if(field.optIndex)
					{
						val = null;
						var optIndex = parseInt(field.optIndex);
						var optionalField = regOptionalFields[screenName][optIndex];
						var depKey = regOptionalFields[screenName][optIndex].storeKey;
						if(preFilledData[depKey] && preFilledData[depKey] != "undefined")
						{
							optionalField.userDecision = preFilledData[depKey];
						}
						if(field.storeKey === "religion")
						{
							if(optionalField.dshow === 'reg_caste_')
							{
								optionalField.dshow = optionalField.dshow + field.userDecision + '_';
								if(field.userDecision == "1")
									optionalField.dshow = optionalField.dshow + field.depValue;
							}
						}
						if(optionalField.dshow)
						{
                            Storage.getData(optionalField.dshow);
							val = factory.getLabel(optionalField.dshow,optionalField.userDecision);
                            
                            var labelPrefix = " - ";
                            if(typeof optionalField.labelPrefix == "string" &&
                                optionalField.labelPrefix.length){
                                labelPrefix = optionalField.labelPrefix;
                            }
                                
							if(val)
							{
								optionalField.value = val
								field.value += labelPrefix +val;
							}
						}
					}			
				}
			});
      UserDecision.setUDKey(userStoreKey);
		}
		factory.getRegFields = function(screenName)
		{
			if(screenName && screenName !== "undefined" && listOfScreens.indexOf(screenName) !=-1)
				return regFields[screenName];
			return regFields;
		}
		factory.getRegOptionalFields = function(screenName)
		{
			return regOptionalFields[screenName];
		}	
		factory.updateGuiFields = function(screenName,indexPos,output)
		{
			var fields = factory.getRegFields(screenName);
			var optionFields = factory.getRegOptionalFields(screenName);
			if(fields[indexPos].multiField == "1")
			{
				factory.updateMultiGuiFields(screenName,indexPos,output);
				return ;
			}
				if(fields[indexPos].storeKey=="native_country" && output.hasOwnProperty(fields[indexPos].dshow) && output[fields[indexPos].dshow].value!="NI")
				{
					fields[indexPos].storeKey="native_state";
				}
			if((fields[indexPos].storeKey=="native_state" && output.hasOwnProperty(fields[indexPos].dshow) && output[fields[indexPos].dshow].value=="NI")||(fields[indexPos].storeKey=="native_country" && output.hasOwnProperty("native_country_jsms"))|| 
	(fields[indexPos].storeKey=="native_state" && output.hasOwnProperty("native_country_jsms") && $.isNumeric(output["native_country_jsms"].value)))
			{
				UserDecision.store("native_country",output['native_country_jsms']['value']);
				UserDecision.store("native_state",'');
				UserDecision.store("native_city",'');
				fields[indexPos].userDecision=output['native_country_jsms'].value;
				fields[indexPos].value=output['native_country_jsms'].label;
				fields[indexPos].storeKey="native_country";
				fields[indexPos].errorLabel = "";	
				fields[indexPos].value = factory.sanitizeString(fields[indexPos].value);
				var iIndex              = fields[indexPos].optIndex;
				optionFields[iIndex].value =  "native_city";
				optionFields[iIndex].userDecision = "";
				factory.handleDepValue(fields[indexPos]);
				if(output.hasOwnProperty(fields[indexPos].dshow))
					output[fields[indexPos].dshow].value='';
                                if(output['native_country_jsms']['value']=="0")
                                {
                                        fields[indexPos].value = "Not Filled In";
                                }
				return;
			}
			else if(fields[indexPos].storeKey=="native_state" && output.hasOwnProperty(fields[indexPos].dshow) && output[fields[indexPos].dshow].value!="NI")
			{
				UserDecision.store("native_country",'51');
				UserDecision.store("native_state",output['native_state_jsms']['value']);
				$.each(output, function (key,val){ 
				if(key.indexOf('reg_city')>-1)
				{
					UserDecision.store("native_city",val['value']);
					depUserSelection = val['value'];
					depLabel		= val['label'];
				}
				var iIndex              = fields[indexPos].optIndex;
				if(output['native_state_jsms']['value']=="0")
				{
					UserDecision.store("native_city",'');
					depUserSelection='';
					depLabel='';
                                        fields[indexPos].value = "Not Filled In";
				}
				else
				{
					labelPrefix = optionFields[iIndex].labelPrefix;
					fields[indexPos].value = output['native_state_jsms']['label'] + labelPrefix + depLabel;
				}

				optionFields[iIndex].value =  depLabel;
				optionFields[iIndex].userDecision = depUserSelection;
				fields[indexPos].userDecision=output['native_state_jsms']['value'];
				fields[indexPos].errorLabel = "";	
				fields[indexPos].depValue= depUserSelection;
				fields[indexPos].value = factory.sanitizeString(fields[indexPos].value);
				factory.handleDepValue(fields[indexPos]);
				return;
				 });
			}
			else
			{
			fields[indexPos].userDecision=output[fields[indexPos].dshow].value;
			fields[indexPos].value=output[fields[indexPos].dshow].label;
			fields[indexPos].errorLabel = "";	
			if(fields[indexPos].dependant && fields[indexPos].dependant.length )
			{
				var depUserSelection    = "";
				var depLabel            = "";
                
                var iIndex              = fields[indexPos].optIndex;
                var labelPrefix         = " - ";
                
                if( typeof optionFields[iIndex].labelPrefix == "string" &&  
                    optionFields[iIndex].labelPrefix.length)
                {
                    labelPrefix = optionFields[iIndex].labelPrefix;
                }
                
				if(output[fields[indexPos].dependant])
				{
					depUserSelection = output[fields[indexPos].dependant].value;
					depLabel		= output[fields[indexPos].dependant].label;
					fields[indexPos].value = fields[indexPos].value + labelPrefix + depLabel;
				}
				if(iIndex && iIndex.length)
				{
					optionFields[iIndex].value =  depLabel;
					optionFields[iIndex].userDecision = depUserSelection;
                  
					UserDecision.store(optionFields[iIndex].storeKey,optionFields[iIndex].userDecision);
				}
			}
			UserDecision.store(fields[indexPos].storeKey,fields[indexPos].userDecision);
			//parse
			fields[indexPos].value = factory.sanitizeString(fields[indexPos].value);
			factory.handleDepValue(fields[indexPos]);
			}
			if(fields[indexPos].storeKey=="country_res")
			{
				fields[4].userDecision='';
				fields[5].userDecision='';
                                UserDecision.store("state_res",'');
                                UserDecision.store("city_res",'');
                                fields[4].value = "Not Filled In";
                                fields[5].value = "Not Filled In";
			}
			if(fields[indexPos].storeKey=="state_res")
                        {
                                UserDecision.store("city_res",'');
                                fields[5].value = "Not Filled In";
				fields[5].userDecision='';
                        }

		}
			
		factory.updateMultiGuiFields = function(screenName,indexPos,output)
		{
			var fields = factory.getRegFields(screenName);
			var optionFields = factory.getRegOptionalFields(screenName);
			
			var type = fields[indexPos].dshow;
			
			if(type==="dtofbirth")
			{
				var day = type+"_"+"day";
				var month = type+"_"+"month";
				var year = type+"_"+"year";
				fields[indexPos].value = output[day].label + " " + output[month].label + " " + output[year].label ;
				fields[indexPos].userDecision= output[day].value + "," + output[month].value + "," + output[year].value;
				
				UserDecision.store(day,output[day].value);
				UserDecision.store(month,output[month].value);
				UserDecision.store(year,output[year].value);
			}
		}
		factory.setShow=function(screenName,searchKey,searchVal,bStatus)
		{
			angular.forEach(regFields[screenName], function(field,key) {
				if(field[searchKey] == searchVal)
				{
					field.show = bStatus;
				}
			});
		}
		factory.resetField=function(screenName,searchKey,searchVal)
		{
			angular.forEach(regFields[screenName], function(field,key) {
				if(field[searchKey] == searchVal)
				{
          if(field.hamburgermenu == 1) {
            field.value = notFilled;
          } else {
            field.value = "";
          }
					field.userDecision = "";
					field.errorLabel = "";
					UserDecision.remove(field.storeKey);
				}
			});
		}
		factory.cleanErrorLabel = function(screenName)
		{
			angular.forEach(regFields[screenName], function(field,key) {
				field.errorLabel = "";
			});
		}
		factory.updateISDVal = function(szCountryCode)
		{
			if(!szCountryCode || typeof updateISDVal === "undefined")
			{
				szCountryCode = regFields['s2']['3'].userDecision;
			}
			var isdData = JSON.parse(Storage.getData('isd'));
			var isdVal = 91;
			szCountryCode = parseInt(szCountryCode);

			if(isdData.hasOwnProperty(szCountryCode))
			{
				isdVal = isdData[szCountryCode];
			}
			regFields['s5'][2].isdVal = parseInt(isdVal);
			return isdVal; 
		}
		factory.getNextScreenData = function(screenName)
		{
			var szKeys = "";
			angular.forEach(regFields[screenName],function(field,key)
			{
				if(field.dshow && !field.multiField && field.hamburgermenu == "1")
					szKeys = (szKeys.length)?szKeys+","+field.dshow:field.dshow;
				
				//Handle Optional Field
				if(field.optIndex)
				{
					var optIndex = parseInt(field.optIndex);
					var optionalField = regOptionalFields[screenName][optIndex];
					
					if(optionalField.dshow)
					{
						szKeys = (szKeys.length)?szKeys+","+optionalField.dshow:optionalField.dshow;
					}
				}	
			});
			if(szKeys.length)
				Storage.getData(szKeys,"A");
		}
		//Init Gui Elements
		if(bDoItOnce == false)
		{
			bDoItOnce = true;
			var allowedScreen = ['s1','s2','s3','s4','s5','s6','s9','s10'];
			for(var i=0;i<allowedScreen.length;i++)
			{
				factory.initRegFields(allowedScreen[i]);
			}
		}
		factory.storeIncompleteData = function(incompleteData)
		{
			var allowedScreen = ['s1','s2','s3','s4','s5'];
			angular.forEach(incompleteData,function(objField,key)
			{
				var storeKey = objField.key.toLowerCase();
				if(storeKey=="relation")
					storeKey ="relationship";
				for(var istrd=0;istrd<allowedScreen.length;istrd++)
				{
					factory.setShow(allowedScreen[istrd],'storeKey',storeKey,false);
				}
			});
		}
		
		factory.isRegFieldInitialized = function()
		{
			return bDoItOnce;
		}
    
		factory.setIncompleteFlow = function(bStatus)
    {
      bFromIncomplete = bStatus;
      if(bStatus)
        Storage.storeUserData('_iFlow',1);//store incomplete flow status
      else
        Storage.removeUserData('_iFlow');
    }
      
    factory.isIncompleteFlow = function()
    {
      if(bFromIncomplete == false)
        bFromIncomplete = Storage.getUserData('_iFlow') ? true : false;
      
      return bFromIncomplete;
    }
    
    factory.scrollToNextField = function(screenName,indexPos)
    {
      var nextIndex = parseInt(indexPos);++nextIndex;
      var nextFied = regFields[screenName][nextIndex];
      
      if(factory.isRegFieldInitialized() &&  nextFied.hamburgermenu=="0" && nextFied.show){
        var id = 'reg_'+nextFied.label.replace('\'','');

        $timeout(function(){
          $location.hash(id);
          $anchorScroll();
        },1);
      }
    }
    
    
		return factory;
	});
	
	app.factory ('UserDecision',function(Storage){
		var factory={};
		var editFieldArr={};

		var fieldsStoreKey={
			"s1":["relationship"],
			"s2":["gender","dtofbirth_day","dtofbirth_month","dtofbirth_year","height","state_res","country_res","city_res","pincode"],
			"s3":["edu_level_new","pg_college","degree_pg","other_pg_degree","college","degree_ug","other_ug_degree","occupation","income"],
			"s4":["mstatus","mtongue","religion","caste","havechild","horoscope_match"],
			"s5":["name_of_user","email","password","phone_mob"],
			"s6":["yourinfo"],
            "s9":["t_brother","m_brother","t_sister","m_sister","family_type","family_values","family_status","family_income","family_back","mother_occ","gothra","native_country","native_state","native_city","ancestral_origin"],
            "s10":["familyinfo"],
			};
		var szUDKey = 'UD';
		factory.Storage = Storage;
		factory.store = function(key,value)
		{
			var data = factory.Storage.getUserData(szUDKey);
			if(!data)	
				data = {};	
      if(value === null) {value = "";}
			data[key.toString()] = value.toString();

			factory.Storage.storeUserData(szUDKey,data);
			return;
		}
		factory.remove = function(key)
		{
			var data = factory.Storage.getUserData(szUDKey);
			if(!data)	
				return;
			
			delete data[key.toString()];
			if(key=="relationship")
			{	
				delete data["gender"];
				delete data["dtofbirth_day"];
				delete data["dtofbirth_month"];
				delete data["dtofbirth_year"];
			}
			
			if(key=="country_res")
			{	
				delete data["city_res"];
				delete data["state_res"];
				delete data["pincode"];
			}
			if(key=="state_res")
			{
				delete data['city_res'];
				delete data['pincode']
			}
			if(key=="native_country")
			{
				delete data["native_state"];
				delete data["native_city"];
			}
			if(key=="mstatus")
			{	
				delete data["havechild"];
			}
			
			if(key=="dtofbirth")
			{	
				delete data["dtofbirth_day"];
				delete data["dtofbirth_month"];
				delete data["dtofbirth_year"];
			}

			factory.Storage.storeUserData(szUDKey,data);
			return;
		}
		var onRelationShip = function(value)
		{
			var gender = "";
			var label= "";
			if(value == "2" || value=="6")//Male
			{
				gender = "M";
				label = "Male";
			}
			else if(value == "2D" || value=="6D")//FeMale
			{
				gender = "F";
				label = "Female";
			}
			factory.store('gender',gender);
			return;
		}
    factory.setUDKey = function(szKey)
    {
      if(typeof szKey == "string")
        szUDKey = szKey;  
    }
    factory.getUDKey = function()
    {
      return szUDKey;
    }
		factory.getUD = function()
		{
			return factory.Storage.getUserData(szUDKey);
		}
		factory.getScreenData = function(screenName)
		{
			var data =factory.Storage.getUserData(szUDKey);
			var fieldKey = fieldsStoreKey[screenName];
			var screenData = {};
			if(data)
			{
				angular.forEach(fieldKey, function(key) {
					if(data[key])
						screenData[key] = data[key];
				});
				return screenData;
			}
			return null;
		}
		factory.removeUD = function()
		{
			factory.Storage.removeUserData(szUDKey);
		}
		
		factory.setIncompleteData=function(incompleteJson)
		{
			var submitJson=factory.Storage.getUserData("UD");
			angular.forEach(submitJson,function(value,key)
			{
				editFieldArr[key.toUpperCase()]=value;
			});	
			editFieldArr["GENDER"]=incompleteJson[0].value;
			factory.Storage.storeUserData("UD",editFieldArr);
		}
		
		factory.getIncompleteData=function()
		{
			return editFieldArr;
		}
		return factory;
	});
	
	app.factory('ApiData',function(Gui,TrackParams){
		var factory = {};
		var regPage1Fields={ 
			'reg[_csrf_token]':'a4ec6e42ea632a304661bc3b8a6180cd',
			'reg[relationship]':'',
			'reg[gender]':'',
			'reg[dtofbirth][day]':'',
			'reg[dtofbirth][month]':'',
			'reg[dtofbirth][year]':'',
			'reg[height]':'',
			'reg[country_res]':'',
			'reg[state_res]':'',
			'reg[city_res]':'',
			'reg[pincode]':'',
			'reg[mstatus]':'',
			'reg[mtongue]':'',
			'reg[religion]':'',
			'reg[caste]':'',
			'reg[edu_level_new]':'',
			'reg[occupation]':'',
			'reg[income]':'',
			'reg[email]':'',
			'reg[displayname]':'',
			'reg[password]':'',
			'reg[phone_mob][isd]':'',
			'reg[phone_mob][mobile]':'',
			'reg[havechild]':'',
			'reg[trackingParams]':'',
      'reg[pg_college]':'',
      'reg[degree_pg]':'',
      'reg[college]':'',
      'reg[degree_ug]':'',
      'reg[other_pg_degree]':'',
      'reg[other_ug_degree]':'',
      'reg[name_of_user]':'',
      'reg[horoscope_match]':''
		};
		var regPage2Fields={
			'reg[_csrf_token]':'a4ec6e42ea632a304661bc3b8a6180cd',
			'reg[yourinfo]':'',
			'reg[trackingParams]':''
		};
        var regPage3Fields={
			'reg[_csrf_token]':'a4ec6e42ea632a304661bc3b8a6180cd',
			'reg[gothra]':'',
            'reg[t_sister]':'',
            'reg[m_sister]':'',
            'reg[t_brother]':'',
            'reg[m_brother]':'',
            'reg[family_back]':'',
            'reg[mother_occ]':'',
            'reg[family_status]':'',
            'reg[family_type]':'',
            'reg[family_values]':'',
            'reg[familyinfo]':'',
            'reg[family_income]':'',
            'reg[native_country]':'',
            'reg[native_state]':'',
            'reg[native_city]':'',
      'reg[ancestral_origin]':'',
			'reg[trackingParams]':''
		};
		var generateFormData=function(inputArray,regPageArray)
		{
			angular.forEach(inputArray,function(value,key){
				if(key.toString() === 'dtofbirth' && value && value !="undefined")
				{
					var arrDate = value.split(',');
					var szKey = "reg[dtofbirth][day]";
					regPageArray[szKey] = (typeof arrDate[0] === "undefined")?"":arrDate[0];
					
					szKey = "reg[dtofbirth][month]";
					regPageArray[szKey] = (typeof arrDate[1] === "undefined")?"":arrDate[1];
					
					szKey = "reg[dtofbirth][year]";
					regPageArray[szKey] = (typeof arrDate[2] === "undefined")?"":arrDate[2];
					
				}
				else if(key.toString() === 'phone_mob' && value && value !="undefined")
				{
					var arrPhone = value.split(',');
					var szKey = "reg[phone_mob][isd]";
					regPageArray[szKey] = (typeof arrPhone[0] === "undefined")?"":arrPhone[0];
					
					szKey = "reg[phone_mob][mobile]";
					regPageArray[szKey] = (typeof arrPhone[1] === "undefined")?"":arrPhone[1];				
				}
				else if(key.toString() === 'relationship' && value && value !="undefined")
				{
					if(value.indexOf('4')!=-1)/*For Friend and relative we have to pass one value*/
						value = '4';
					var szKey = "reg["+key.toString().trim()+"]";
					regPageArray[szKey] = (typeof value === "undefined")?"":value;	
				}
				else
				{
					var szKey = "reg["+key.toString().trim()+"]";
					regPageArray[szKey] = (typeof value === "undefined")?"":value;
				}
			});
		}
		
		factory.getApiData = function(pageName)
		{
			if(pageName.indexOf('1') != -1)
			{
				var outputPageData = {};
				var allowedScreen = ['s1','s2','s3','s4','s5'];
        var allowedFieldName = ['gender','degree_pg','degree_ug','displayname'];
				for(var i=0;i<allowedScreen.length;i++)
				{
					if(Gui.isRegFieldInitialized() === false)
						Gui.initRegFields(allowedScreen[i]);
					var fields = Gui.getRegFields(allowedScreen[i]);
					var optFields = Gui.getRegOptionalFields(allowedScreen[i]);
					angular.forEach(fields,function(field,key){
						if (field.show || allowedFieldName.indexOf(field.storeKey) !== -1)
						{
							outputPageData[field.storeKey] = field.userDecision;
							if(field.optIndex && optFields)
							{
								outputPageData[optFields[parseInt(field.optIndex)].storeKey] = optFields[parseInt(field.optIndex)].userDecision;
							}
						}
					});
				}
				generateFormData(outputPageData,regPage1Fields);
                try{
                    var trackParams = TrackParams.getTrackingParams();
                    regPage1Fields['reg[trackingParams]'] = JSON.stringify(trackParams);
                    if(trackParams && trackParams.source)
                        regPage1Fields['reg[source]'] = trackParams.source;
                }catch(e){
                    //console.log(e.stack);
                }
				return regPage1Fields;
			}
			if(pageName.indexOf('2') != -1)
			{
				var allowedScreen = ['s6'];
				var fields = Gui.getRegFields(allowedScreen[0]);
                
				regPage2Fields['reg[yourinfo]'] = fields[0].userDecision;

                try{
                    var serverTrackParams = TrackParams.getTrackingParams();
                    regPage2Fields['reg[trackingParams]'] =  JSON.stringify(serverTrackParams);
                }catch(e)
                {
                    //console.log(e.stack);
                }
				return regPage2Fields;
			}
            if(pageName.indexOf('3') != -1)
            {
                var outputPageData = {};
                var allowedScreen = ['s9','s10'];
                var screenName = allowedScreen[0]
               
				var fields = Gui.getRegFields(screenName);
				
                if(Gui.isRegFieldInitialized() === false)
                    Gui.initRegFields(screenName);
                
                var fields = Gui.getRegFields(screenName);
                var optFields = Gui.getRegOptionalFields(screenName);
                
                angular.forEach(fields,function(field,key){
                    if(field.show && field.userDecision && field.userDecision.length)
                    {
                      outputPageData[field.storeKey] = field.userDecision;
                      if(field.optIndex && optFields)
                      {
                          outputPageData[optFields[parseInt(field.optIndex)].storeKey] = optFields[parseInt(field.optIndex)].userDecision;
                      }
                    }
                });
               if(outputPageData.hasOwnProperty('native_state') && outputPageData['native_state']!='')
		{
			outputPageData['native_country']='51';
		}
                generateFormData(outputPageData,regPage3Fields);
                var aboutFamilyField = Gui.getRegFields(allowedScreen[1]);
				regPage3Fields['reg[familyinfo]'] = aboutFamilyField[0].userDecision;
                
				return regPage3Fields;
            }
		}
		
		return factory;
	});
	
	app.factory('Storage',function(){
		var factory={};
		var storage = new SessionStorage;
		
		factory.storeUserData = function(key,value)
		{
			storage.storeUserData(key,JSON.stringify(value));
		}
		factory.getUserData = function(key)
		{
            if(storage.isDebugMode() == false)
            {
                var data = storage.getUserData(key);
                if(data != null && typeof data == "string")
                    return JSON.parse(data);
            }
            return null;
		}
		factory.getData = function(key,mode)
		{
			return storage.getData(key,"",mode);
		}
		factory.getLabel = function(key,value)
		{
			return storage.getCorrespondingLabel(key,value);
		}
		factory.removeUserData = function(key)
		{
			return storage.removeUserData(key);
		}
		return factory;
	});
	
	app.factory ('Validate',function($window,Gui,Constants,UserDecision){
		var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");
		var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
		var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
	        var isd_regex = /^[+]?[0-9]+$/;
	        var name_regex = /^[A-z ]+$/;
	        var isdCodes = ["0", "91","+91"];
		// auto correct email array
		var corrections =Constants.getEmailCorrections();
                var factory = {};

		factory.validateLoginFields = function(screenName)
		{
            Gui.cleanErrorLabel(screenName);
            var fields 	= Gui.getRegFields(screenName);
			var screenName 	=fields[0].screenName;
			var nameIndex	=fields[0].dindex;
			var emailIndex	=fields[1].dindex;
			var passIndex  	=fields[2].dindex;			
			var phoneIndex  =fields[3].dindex;

			var mobile	=fields[phoneIndex].value;
			var mobileISD	=fields[phoneIndex].isdVal;
			fields[phoneIndex].userDecision=mobileISD+','+mobile;
			
			var nameValid 	=factory.validateName(nameIndex, screenName);
			var emailValid 	=factory.validateEmail(emailIndex, screenName);
			var pwdValid 	=factory.validatePassword(passIndex,screenName);
			var phoneValid 	=factory.validatePhone(phoneIndex,screenName);
			if(nameValid && emailValid && pwdValid && phoneValid){			
				Gui.cleanErrorLabel(screenName);
			if(screenName=="s5")
			{
				UserDecision.store("displayname",$window.displayName);
				fields[4].userDecision=$window.displayName;
				fields[4].value=$window.displayName;
			}
				return true;
			}
			return false;
        	}
		factory.validateName = function(index,screenName)
		{
			var fields      =Gui.getRegFields(screenName);
                        var name       =fields[index].value;
			var nameError = '';
			var name_of_user;

			name_of_user = name.replace(/\./gi, " ");
			name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
			name_of_user = name_of_user.replace(/\,|\'/gi, "");
			name_of_user = $.trim(name_of_user.replace(/\s+/gi, " "));

		        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
		        if($.trim(name_of_user)== "" || !allowed_chars.test($.trim(name_of_user)))
			{
				nameError= "Please provide a valid Full Name";
        		}
			else
			{
				var nameArr = name_of_user.split(" ");
				if(nameArr.length<2)
				{
					nameError = "Please provide your first name along with surname, not just the first name";
				}
			}
			if(nameError){
				fields[index].errorLabel =nameError;
				if(name)
					fields[index].errClass=regErr;
				return;
			}
			else
				fields[index].errClass='';
			return true;
		}
		factory.validateEmail = function(index,screenName)
		{
                        var fields      =Gui.getRegFields(screenName);
                        var email       =fields[index].value;

			var emailError ='';
			if(email=='')
				emailError =Constants.getErrorMsg('EMAIL_REQUIRED');			
			if(!emailError){
				var emailPattern =factory.emailPattern(email);
				if(!emailPattern)
					emailError =Constants.getErrorMsg('EMAIL_INVALID');
			}
			if(!emailError){
	                        var emailDomain =factory.invalidDomain(email);
	                        if(!emailDomain)
	                                emailError =Constants.getErrorMsg('EMAIL_INVALID_DOMAIN:');
			}
			if(emailError){
				fields[index].errorLabel =emailError;
				if(email)
					fields[index].errClass=regErr;
				return;
			}
			else
				fields[index].errClass='';
			return true;
		}
	        factory.emailPattern =function(email) 
        	{
			if(!email_regex.test(email))
				return false;
			else
				return true;
        	}
		factory.invalidDomain =function(email)
		{
			var value =email;
			var start = value.indexOf('@');
			var end = value.lastIndexOf('.');
			var diff = end-start-1;
			var user = value.substr(0,start);
			var len = user.length;
			var domain = value.substr(start+1,diff).toLowerCase();

			if(invalidDomainArr.indexOf(domain.toLowerCase()) != -1)
				return false;
			else if(domain == 'gmail')
			{
				if(!(len >= 6 && len <=30))
					return false;
			}
			else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
			{
				if(!(len >= 4 && len <=32))
					return false;
			}
			else if(domain == 'rediff')
			{
				if(!(len >= 4 && len <=30))
					return false;
			}
			else if(domain == 'sify')
			{
				if(!(len >= 3 && len <=16))
					return false;
			}
			return true;
		}
		// Auto-email correct
		factory.autoEmailCorrect =function(index,screenName)
		{
			var fields  =Gui.getRegFields(screenName);
			var email 	=fields[index].value;

			var domain = email.split('@');
			var oldDom = domain[1];
			oldDom = oldDom.toLowerCase();
			if (!corrections[oldDom]){
        		        return true;
			}
            var stringToReplace = corrections[oldDom];
            email =domain[0] + '@' +stringToReplace;
			if(fields[index].isAutoCorrected == "false")
            {
                fields[index].value =email;
                fields[index].isAutoCorrected = "true";
                fields[index].userDecision=fields[index].value;
            }
            
        	return;
		}
		factory.validatePassword = function(index,screenName)
		{
                        var fields      =Gui.getRegFields(screenName);
                        var password    =fields[index].value;
			var email	=fields[0].value;

			var passError ='';
			if(password==''){
				passError =Constants.getErrorMsg('PASSWORD_REQUIRED');
			}
			if(password && password.length<8)
				passError =Constants.getErrorMsg('PASSWORD_INVALID');

			if(!passError){
				var passCommon =factory.checkCommonPassword(password);
				var userPassMatch =factory.checkPasswordUserName(password,email);
				if(!passCommon || !userPassMatch)
					passError =Constants.getErrorMsg('PASSWORD_COMMON');
			}
			if(passError){
				fields[index].errorLabel=passError;
				if(password)
					fields[index].errClass=regErr;
				return;
			}
			else
				fields[index].errClass='';
			return true;
		}
		factory.checkCommonPassword =function(pass)
		{	
			if(invalidPasswords.indexOf(pass.toLowerCase()) != -1)
        	        	return false;
        		return true;
		}
		factory.checkPasswordUserName =function(pass, email){
			if(typeof email === "undefined")
				return true;
		        var end = email.indexOf('@');
		        var username = email.substr(0,end);
			if((String(pass) != String(username) && String(pass) != String(email)))
				return true;
			return
		}
		factory.validatePhone = function(index,screenName)
		{
			var fields      =Gui.getRegFields(screenName);
			var mobile    	=fields[index].value;
			var mobileISD  	=fields[index].isdVal;

			var mobileError ='';
			if(!mobile || mobile==''){
				var mobileError =Constants.getErrorMsg('MOBILE_REQUIRED');
			}
			if(!mobileError){
				var mobileCheck =factory.checkMobile(mobileISD, mobile);
				if(mobileCheck){
					if(mobileCheck==1)
						mobileError =Constants.getErrorMsg('MOBILE_INVALID');			
					else if((mobileCheck==2))
						mobileError =Constants.getErrorMsg('ISD_REQUIRED');
					else if(mobileCheck==3)
						mobileError =Constants.getErrorMsg('ISD_INVALID');	
				}
			}
			if(mobileError){
				fields[index].errorLabel =mobileError;
				if(mobile && (mobileCheck==1))
					fields[index].errClass=regErr;
				if(mobileISD && (mobileCheck==2 || mobileCheck==3))
					fields[index].errClass=regErr;
				return false;
			}
			else
				fields[index].errClass='';
			return true;
		}
		factory.checkMobile =function(mobileISD, mobile)
		{
			if(mobileISD)
				mobileISD =mobileISD.toString().trim();
			else
				mobileISD='';	
			if(mobile)
				mobile =mobile.toString().trim();
			else
				mobile='';	
			if(isNaN(mobile))
				return 1;
        		if(isdCodes.indexOf(mobileISD)!=-1 && mobile.length!=10)
				return 1;
        		else if(isdCodes.indexOf(mobileISD)==-1 && mobile && (mobile.length<6 || mobile.length>14))
        			return 1;        
        		else if(mobileISD == '')
        		        return 2;
			else if(mobileISD && !isd_regex.test(mobileISD))
				return 3;
        		return;
		}
		factory.validateDob =function()
		{
			var bInValidDate = false;
			var allFields = Gui.getRegFields("s2");
			var dobField = allFields[1];
			var genderField = allFields[0];
			if(dobField.userDecision && dobField.userDecision.length)
			{
				var arrDob = dobField.userDecision.split(",");
				var correspondingDate = new Date(arrDob[2],parseInt(arrDob[1])-1,arrDob[0]);
				var szErrorKey = '';
				if(correspondingDate.getDate() !== parseInt(arrDob[0]))
					bInValidDate = true;
				
				if(correspondingDate.getMonth()+1 !== parseInt(arrDob[1]))
					bInValidDate = true;
				
				if(correspondingDate.getFullYear() !== parseInt(arrDob[2]))
					bInValidDate = true;
				
				/*Year check for male and female*/
				if(!bInValidDate)
				{
					var currDate 		= new Date();
					var maxMaleYear 	= currDate.getFullYear() - 21;
					var maxFeMaleYear 	= currDate.getFullYear() - 18;
					var maxMonth	 	= currDate.getMonth() + 1;
					var maxDate 		= currDate.getDate();
					var maxYear  		= maxFeMaleYear;					
					if(genderField.userDecision == 'M')
					{
						maxYear 		= maxMaleYear;
					}
					
					if(correspondingDate.getFullYear() === maxYear)
					{
                        var selectedMonth = correspondingDate.getMonth() + 1;
						if(selectedMonth > maxMonth || (selectedMonth === maxMonth && correspondingDate.getDate() > maxDate))
						{
							bInValidDate = true;
							szErrorKey = 'DOB_ERROR_2';/*Female Should be 18  Year old*/
							if(genderField.userDecision == 'M')
								szErrorKey = 'DOB_ERROR_3';/*Female Should be 18  Year old*/
						}
					}
				}
				else
				{
					szErrorKey = 'DOB_ERROR_1';
				}
			}
			else
			{
				szErrorKey = 'DOB_ERROR_0';
			}
			if(bInValidDate)
			{
				Gui.cleanErrorLabel('s2');
				Gui.resetField('s2',"dshow","dtofbirth");
				dobField.errorLabel = Constants.getErrorMsg(szErrorKey);;
			}
			return (!bInValidDate);
		}
		factory.validateScreen = function(screenName)
		{
			var fields = Gui.getRegFields(screenName);
			var bValid = true;
			Gui.cleanErrorLabel(screenName);
			angular.forEach(fields, function(field,key) {
        var skipField = false;
        if (field.hasOwnProperty("required") && field.required == "false") {
          skipField = true;
        }
        
				if(field && field.show && 
           !skipField && 
           (!field.userDecision || field.userDecision.length===0)
          )
				{
					bValid = false;
					var vowels = ['a','e','i','o','u'];
					var sym = "a ";
					if(vowels.indexOf(field.label[0].toLowerCase()) != -1)
						sym = "an ";
					field.errorLabel = "Provide " + sym + field.label.toLowerCase()
				}
			});
			return bValid;
		}
		factory.validatePinCode = function(screenName)
		{
			var bInValidDate = false;
			var field = Gui.getRegFields("s2")[6];
			var cityField = Gui.getRegFields("s2")[5];
			var bValid = true;
			field.errClass='';
			var ArrayPincode={'DE00':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Provide a pincode that belongs to Delhi"},"MH04":{0:["400","401","410","421","416"],1:3,2:"Provide a pincode that belongs to Mumbai"},"MH08":{0:["410","411","412","413"],1:3,2:"Provide a pincode that belongs to Pune"}};
			
			if(field.show && typeof field.value !== "undefined" && field.value && field.value.toString().length)
			{
				Gui.cleanErrorLabel(screenName);
				if(!field.value || field.value.toString().length===0)
				{
					field.errorLabel = Constants.getErrorMsg('PINCODE_ERROR_2');
					bValid = false;
				}
				if(cityField.userDecision)
					var initial = field.value.toString().substring(0,ArrayPincode[cityField.userDecision][1]);
				if(bValid && field.value && field.value.toString().length <6)
				{
					//Gui.resetField('s2',"dindex","4");
					field.errorLabel = Constants.getErrorMsg('PINCODE_ERROR_1');
					bValid = false;
				}
				
				if(bValid && ArrayPincode[cityField.userDecision][0].indexOf(initial) === -1)
				{
					bValid = false;
					//Gui.resetField('s2',"dindex","4");
					field.errorLabel = ArrayPincode[cityField.userDecision][2];
				}
			}
			
			if(bValid)
			{
				Gui.cleanErrorLabel(screenName);
				field.userDecision = field.value;
			}
			else
			{
				field.errClass=regErr;
			}
			return bValid;
		}
		
		factory.validateReligion = function()
		{
			var bValid = true;
			var fields = Gui.getRegFields("s4");
			var mstatusField = fields[0];
			var religionField = fields[2];
			var gender = Gui.getRegFields("s2")[0];
			
			if(mstatusField.userDecision === 'M' && (gender.userDecision == 'F' || religionField.userDecision != "2"))
			{
				//Married Option for male muslim only else invalid
				religionField.errorLabel = Constants.getErrorMsg('RELIGION_ERROR_1');
				bValid = false;
			}
			return bValid;
		}
		
		factory.validateIncomplete = function(screenFieldObj)
		{
			var bValid = true;
			angular.forEach(screenFieldObj, function(field,key) {
				if(field && !field.show && field.storeKey!=='gender' && (!field.userDecision || field.userDecision.length===0))
				{
					bValid = false;
					var vowels = ['a','e','i','o','u'];
					var sym = "a ";
					if(vowels.indexOf(field.label[0].toLowerCase()) != -1)
						sym = "an ";
					field.errorLabel = "Provide " + sym + field.label.toLowerCase();
				}
				else
				{
					field.errorLabel = "";
				}
			});
			return bValid;
		}
		
		factory.myTrim = function(inputString)
        {
            if (typeof inputString != "string") { return inputString; }
            var retValue = inputString;
            var ch = retValue.substring(0, 1);
            while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
               retValue = retValue.substring(1, retValue.length);
               ch = retValue.substring(0, 1);
            }
            ch = retValue.substring(retValue.length-1, retValue.length);
            while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
               retValue = retValue.substring(0, retValue.length-1);
               ch = retValue.substring(retValue.length-1, retValue.length);
            }
            while (retValue.indexOf("  ") != -1) {
               retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
            }
            return retValue;
        }
        
        factory.trim_newline = function(string)
        {
            return string.replace(/^\s*|\s*$/g, "");
        }
        
		return factory;
	});	
	
	app.factory('TrackParams',function(Storage,Gui,$http,DevTrack){
		var factory = {};
		var trackParamsKeys = ["domain","source","tieup_source","adnetwork1","adnetwork","account","campaign","adgroup","keyword","match","lmd","secondary_source","groupname","newsource","reg_comp_frm_ggl","reg_comp_frm_ggl_nri"];
		var serverTrackParams = {};
		var actualTrackParams = {};
		var storeTrackParams = false;
        var clientInfo = {};
        clientInfo.trackDoneFor = [];
        var clientInfoKey = 'ci';
		factory.fillTrackingParams = function(szSearchQuery)
		{
			if(szSearchQuery.length)
			{
				szSearchQuery = szSearchQuery.split('?');
				szSearchQuery = szSearchQuery[1];
				var arrSearchQuery = szSearchQuery.split('&');
				for(var i=0;i<arrSearchQuery.length;i++)
				{
					var params = arrSearchQuery[i].split('=');
					if(actualTrackParams == null || !actualTrackParams)
                        actualTrackParams = {};
					actualTrackParams[params[0].toString()] = params[1].toString();
				}
				Storage.storeUserData('trackParams',actualTrackParams);
			}
		}
		
		factory.initTrackParams = function()
		{
			actualTrackParams = Storage.getUserData('trackParams');
			serverTrackParams = Storage.getUserData('trackServerParams');
		}
		
		factory.getTrackingParams = function()
		{
			factory.initTrackParams();
            if(actualTrackParams || typeof actualTrackParams == "object")
            {
                angular.forEach(actualTrackParams,function(value,key){
                    if(serverTrackParams == null || typeof serverTrackParams == "undefined")
                        serverTrackParams = {};
                    if(!serverTrackParams.hasOwnProperty(key))
                    {
                        serverTrackParams[key] = value;
                    }
                });
            }
			return serverTrackParams;
		}
        factory.updateClientInfo = function(screenName)
        {
            clientInfo.view = screenName;
            DevTrack.UpdateTrack({},{Info:clientInfo},function(data){
                clientInfo.trackDoneFor.push(screenName);
                Storage.storeUserData(clientInfoKey,clientInfo);
            },function(error){
                //console.log(error);
            });
        }
        factory.trackClientInfo = function(screenName)
        {return;
            if(!screenName || typeof screenName != "string" || !screenName.length)
                return ;
            var info = Storage.getUserData(clientInfoKey);
            if(info == null)
            {
                clientInfo = {};
                clientInfo.trackDoneFor = [];
            }
            else
            {
                clientInfo = info;
                if(!clientInfo.hasOwnProperty('trackDoneFor'))
                    clientInfo.trackDoneFor = [];
            }
           
            if(clientInfo.trackDoneFor.indexOf(screenName)==-1)
                factory.updateClientInfo(screenName);

            if(screenName && screenName.indexOf('6')!=-1)
            {
                Storage.removeUserData(clientInfoKey);
            }
        }
        factory.resetClientInfo = function()
        {return;
            clientInfo.trackDoneFor = [];
        }
		return factory;
	});
})();
