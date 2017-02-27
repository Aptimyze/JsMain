(function(){
	'use strict';
	var app = angular.module('regApp.controllers',['regApp.factories','regApp.services']);
	//Create For Controller
	app.controller("CreateForController",function($scope,Gui,$location,UserDecision,$window,TrackParams,Constants,Validate,$route){
		
		$scope.slideDir = Gui.getSlideDir();
		$scope.screenName = "s1";
		$scope.nextScreenName = "s2";
		
		$scope.fieldsOrderBy = ['self','rel','bro','sis','son','dau','mb','fri'];
		$scope.relationWidget = Gui.getRelationShipWidget();
		$scope.fields = Gui.getRegFields($scope.screenName);
		
		$scope.bModalWindow = false;
		$scope.bEnableBack = true;
		$scope.bHardReload = false;
        
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getHeaderHeight() - /*Top Header Height*/72;
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myNext = function()
		{
			Gui.getNextScreenData($scope.nextScreenName);
			var validate =Validate.validateScreen($scope.screenName);
			if(!validate){
				Gui.showModalWindow($scope);	
				return false;
			}
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();
			$location.path('/s2');
            if($scope.bHardReload)
                $route.reload();
			return true;
		}
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return false;
			//TODO Call Home Page
            $window.location.href = '/';
			return true;
		}
		$scope.drawWidget = function(fieldName)
		{
			$scope.relationWidget[fieldName].icon = ($scope.relationWidget[fieldName].icon.search("_")==-1)?$scope.relationWidget[fieldName].icon+'_sel':($scope.relationWidget[fieldName].icon);
			angular.forEach($scope.relationWidget, function(value, key) {
				if(key!=fieldName)
				{
					$scope.relationWidget[key].icon =($scope.relationWidget[key].icon.split("_")[0]);
				}
			});
		}
		
		$scope.onRelation = function(fieldName)
		{	
			var output = {};
			var outputGen = {};
			var label = "";
			var genVal = "";
			$scope.drawWidget(fieldName);
			var relation = $scope.relationWidget[fieldName].value;

			if(relation.indexOf("2") !=-1 || relation.indexOf("6") !=-1 )
			{
				label = "Male";
				genVal= "M";
				if(relation.indexOf('D') !=-1)
				{
					label = "Female";
					genVal= "F";
				}
				Gui.setShow('s2','label','Gender',false);
			}
			else
			{
				Gui.setShow('s2','label','Gender',true);
			}
			output["relationship"] = {"label":$scope.relationWidget[fieldName].label,"value":relation};
			outputGen["gender"] = {"label":label,"value":genVal};
			Gui.updateGuiFields($scope.screenName,0,output);
			Gui.updateGuiFields($scope.nextScreenName,0,outputGen);
			
			$scope.myNext();
		}
		
		$scope.initRelationShipWidget = function()
		{
			$scope.relationShip = Gui.getRegFields($scope.screenName)[0];
			$scope.selectedVal = "";
			
			if($scope.relationShip.userDecision)
			{
				angular.forEach($scope.relationWidget, function(field, key) {
					if(field.value == $scope.relationShip.userDecision)
					{
						$scope.relationShip.value = key;
					}
				});
				$scope.selectedVal = $scope.relationShip.value;
				$scope.drawWidget($scope.selectedVal);
			}
		}
		$scope.trackingParam = function()
		{
			TrackParams.fillTrackingParams($window.location.search);
		}
    $scope.onLogin = function()
    {
      $window.location.href = '/static/logoutPage';
    }
        //TrackParams.trackClientInfo($scope.screenName);
		$scope.initRelationShipWidget();
		$scope.trackingParam();
	});

	//Personal Details Controller
	app.controller("PersonalDetailsController",function($scope,Gui,$location,UserDecision,Validate,$timeout,Constants,TrackParams,$route){
		
		$scope.slideDir = Gui.getSlideDir();
		$scope.tabName="Personal Details";
		
		$scope.bNextEnable = false;
		$scope.bModalWindow = false;
		$scope.bEnableBack = true;
		$scope.bHardReload = false;
        
		$scope.screenName = 's2';
		$scope.previousScreenName = 's1';
		$scope.nextScreenName="s3";
		
		$scope.fields = Gui.getRegFields($scope.screenName);
		
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		
		$scope.gender = $scope.fields[0];
		$scope.dob	  = $scope.fields[1];
        
		$scope.hamOn = false;
		$scope.hamTrigger = function(value,refHamObj)
		{
			$scope.hamOn = value;
			$scope.currHamObj = refHamObj;
		}
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return ;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			$location.path('/'+$scope.previousScreenName);
		}
		$scope.enableNextBtn = function()
		{
			var validate =Validate.validateScreen($scope.screenName);
			$scope.bNextEnable = false;
			if(validate)
			{
				$scope.bNextEnable = true;
			}
		}
		$scope.myNext = function()
		{
			Gui.getNextScreenData($scope.nextScreenName);
			var validate = $scope.checkPinCode();
			if(!validate){
				Gui.showModalWindow($scope);
				return;
			}
			validate = Validate.validateScreen($scope.screenName);
			if(!validate){
				Gui.showModalWindow($scope);
				return;
			}
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();
            
            var pinCodeField = $scope.fields[6];
            var timeOut = 1;
            if(pinCodeField.show)
            {
                timeOut = 150;//Put some delay for hiding virtual keypad
            }
            $timeout(function(){
                $location.path($scope.nextScreenName);
                if($scope.bHardReload)
                    $route.reload();
            },timeOut);
		}
		$scope.myFormSubmit = function(ele,output,json,indexPos)
		{
			$scope.hamOn = false;
			Gui.updateGuiFields($scope.screenName,indexPos,output);
			
			var error = false; 
			if(indexPos == '1')/*Dob*/
			{
				if(!Validate.validateDob())
				{
					error = true;
					Gui.showModalWindow($scope);
				}
			}
			if(indexPos == '3')/*Country*/
			{
				var countryField = $scope.fields[3];
				$scope.initStateWidget();
				$scope.initCityWidget();
			}
			if(indexPos=='4')
			{
				$scope.initCityWidget();
			}
			if(indexPos=='5')
			{
				$scope.initPinCodeWidget();
			}
			if(!error)
    		{
                $scope.enableNextBtn();
            }      
		}
		$scope.genderBtn = function (val)
		{
			$scope.gender.micon = "micon";
			$scope.gender.ficon = "ficon";
			var output = {};
			var label = "";
			var value = "";
			
			if(val.toUpperCase() == 'F')
			{
				$scope.gender.ficon = "ficon_sel";
				$scope.gender.userDecision = val;
				value = val;
				label = "Female";
			}
			if(val.toUpperCase() == 'M')
			{
				$scope.gender.micon = "micon_sel";
				$scope.gender.userDecision = val;
				value = val;
				label = "Male";
			}
			//Reset Date
			if($scope.dob.depValue.length === 0 || ($scope.dob.depValue != $scope.gender.userDecision))
			{
				Gui.resetField($scope.screenName,"dshow","dtofbirth");
			}
			output["gender"] = {"label":label,"value":val};
            if(!($scope.gender.value == label && $scope.gender.userDecision==val))
            {
                $scope.myFormSubmit("",output,"",0);
            }    
			
			$scope.enableNextBtn();
		}
		$scope.initGenderWidget= function()
		{
			var data = UserDecision.getScreenData($scope.previousScreenName);
			if(data)
			{
				var val = data['relationship'];
				$scope.gender.show = true;
				if(val && (typeof val !== "undefined") && (val.indexOf("2")!=-1 || val.indexOf("6")!=-1))
					$scope.gender.show = false;
			}
			$scope.genderBtn($scope.gender.userDecision);
		}
		$scope.initPinCodeWidget = function()
		{
			var countryField = $scope.fields[3];
			var cityField = $scope.fields[5];
			var pinCodeField = $scope.fields[6];
			var optFields = Gui.getRegOptionalFields($scope.screenName);
			var allowedCity = ['DE00','MH04','MH08'];
			pinCodeField.show=true;
		
			if(parseInt(countryField.userDecision) != 51 || allowedCity.indexOf(cityField.userDecision) === -1)
			{
				pinCodeField.show=false;
			}
		}
		$scope.initStateWidget = function()
		{
			var countryField = $scope.fields[3];
			var stateField = $scope.fields[4];
			if(parseInt(countryField.userDecision)==51)
			{
				stateField.show=true;
			}
			else
			{
				stateField.show=false;
			}
		}
		$scope.initCityWidget = function()
		{
			var countryField = $scope.fields[3];
			var stateField = $scope.fields[4];
			var cityField = $scope.fields[5];
                        if((stateField.userDecision && parseInt(countryField.userDecision)==51)||parseInt(countryField.userDecision)==128)
                        {
                                cityField.show=true;
                        }
                        else
                        {
                                cityField.show=false;
                        }
		}
		$scope.checkPinCode = function()
		{					
			var error = 0;
			var pinCodeField = $scope.fields[6];
			if(!Validate.validatePinCode($scope.screenName))
			{
				error = 1;
				Gui.showModalWindow($scope);
			}
			if(!error && pinCodeField.value && pinCodeField.value.toString().length && pinCodeField.userDecision)
			{
				UserDecision.store(pinCodeField.storeKey,pinCodeField.userDecision);
			}
			if(!error)
				$scope.enableNextBtn();
			return !error;
		}
		
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.hamOn)
			{
				$scope.currHamObj.hideHamburger();
				$scope.hamOn = false;
				event.preventDefault(); // This prevents the navigation from happening
			}
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
		$scope.onPinHover =function()
		{
			var notFilled = "Not Filled In";
			var pinCodeField = $scope.fields[6];
			var countryField = $scope.fields[3];
			var hieghtField = $scope.fields[2];
			var dobField = $scope.fields[1];
			var genderField = $scope.fields[0];
			var stateField = $scope.fields[4];
			var cityField = $scope.fields[5];
			
			if(genderField.value && genderField.value.length && genderField.value !== notFilled && 
			dobField.value && dobField.value.length && dobField.value !== notFilled && 
			hieghtField.value && hieghtField.value.length && hieghtField.value !== notFilled && 
			countryField.value && countryField.value.length && countryField.value !== notFilled )
			{
				$scope.bNextEnable = true;
			}
		}
        $scope.showHamMsg = function(szMsg)
        {
            Gui.toastMsg($scope,szMsg);
        }
        //TrackParams.trackClientInfo($scope.screenName);
		$scope.initPinCodeWidget();
		$scope.initStateWidget();
		$scope.initCityWidget();
		$scope.initGenderWidget();
		$scope.enableNextBtn();
	});

	//Career Details Controller
	app.controller("CareerDetailsController",function($scope,$location,Gui,Validate,$timeout,Constants,TrackParams,$route,Storage){
		$scope.slideDir = Gui.getSlideDir();
		
		$scope.screenName = 's3';
		$scope.previousScreenName = 's2';
		$scope.nextScreenName="s4";
		
		$scope.tabName="Career Details";
		$scope.bModalWindow = false;
		$scope.bNextEnable = false;
		$scope.bEnableBack = true;
		$scope.bHardReload = false;
        
		$scope.fields = Gui.getRegFields($scope.screenName);
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		
    $scope.degreeGroupMap = {};
		$scope.hamOn = false;
		$scope.hamTrigger = function(value,refHamObj)
		{
			$scope.hamOn = value;
			$scope.currHamObj = refHamObj;
		}
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return ;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			$location.path($scope.previousScreenName);
		}
		$scope.myNext = function()
		{
			Gui.getNextScreenData($scope.nextScreenName);
			var validate =Validate.validateScreen($scope.screenName);
			if(!validate){
				Gui.showModalWindow($scope);	
				return;
			}
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();
			$location.path($scope.nextScreenName);
            if($scope.bHardReload)
                $route.reload();
		}
		$scope.enableNextBtn = function()
		{
			var validate =Validate.validateScreen($scope.screenName);
			$scope.bNextEnable = false;
			if(validate)
			{
				$scope.bNextEnable = true;
			}
		}
		$scope.myFormSubmit = function(ele,output,json,indexPos)
		{
			Gui.updateGuiFields($scope.screenName,indexPos,output);
      if(indexPos == '0')/*Highest education*/
			{
        $scope.initEducationFields();
			}
			$scope.hamOn = false;
			$scope.enableNextBtn();
		}
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.hamOn)
			{
				$scope.currHamObj.hideHamburger();
				$scope.hamOn = false;
				event.preventDefault(); // This prevents the navigation from happening
			}
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
    $scope.showHamMsg = function (szMsg)
    {
      Gui.toastMsg($scope, szMsg);
    }
    $scope.initEducationFields = function (init)
    {
      var highestEducationField = $scope.fields[0];
      
      var pgDegree = $scope.fields[1];
      var pgCollege = $scope.fields[2];
      var otherPgDegree = $scope.fields[3];
      
      var ugDegree = $scope.fields[4];
      var ugCollege = $scope.fields[5];
      var otherUgDegree = $scope.fields[6];
      
      var bUGDegree = false;
      var bPGDegree = false;
      var bPhdDegree = false;
      
      if ($scope.degreeGroupMap['g'].indexOf(highestEducationField.userDecision.toString()) !== -1) {
        bUGDegree = true;
      }
      
      if ($scope.degreeGroupMap['pg'].indexOf(highestEducationField.userDecision.toString()) !== -1) {
        bPGDegree = true;
      }
      
      if ($scope.degreeGroupMap['phd'].indexOf(highestEducationField.userDecision.toString()) !== -1) {
        bPGDegree = true;
        bPhdDegree = true;
      }
       
      if (true == bPGDegree) {
        //PreFilled Pg_Degree With same value and hide it its not phd
        if(bPhdDegree == false) {
          var output = {};
          output[pgDegree.dshow] = {"label":highestEducationField.value,"value":highestEducationField.userDecision};
          Gui.updateGuiFields($scope.screenName,pgDegree.dindex,output);
          pgDegree.show = false;
        } else {
          pgDegree.show = true;
        }
        ugDegree.show = true;
        ugCollege.show = true;
        otherUgDegree.show = true;
        
        pgCollege.show = true;
        otherPgDegree.show = true;
      } else if (true == bUGDegree) {
        //PreFilled Ug_Degree With same value and hide it
        var output = {};
        output[ugDegree.dshow] = {"label":highestEducationField.value,"value":highestEducationField.userDecision}
        Gui.updateGuiFields($scope.screenName,ugDegree.dindex,output);
        ugDegree.show = false;
        
        ugCollege.show = true;
        otherUgDegree.show = true;
        
        pgDegree.show = false;
        pgCollege.show = false;
        otherPgDegree.show = false;
        Gui.resetField('s3','dindex',pgDegree.dindex);
        Gui.resetField('s3','dindex',pgCollege.dindex);
        Gui.resetField('s3','dindex',otherPgDegree.dindex);
      } else {
        ugDegree.show = false;
        ugCollege.show = false;
        otherUgDegree.show = false;
        Gui.resetField('s3','dindex',ugDegree.dindex);
        Gui.resetField('s3','dindex',ugCollege.dindex);
        Gui.resetField('s3','dindex',otherUgDegree.dindex);
        
        pgDegree.show = false;
        pgCollege.show = false;
        otherPgDegree.show = false;
        Gui.resetField('s3','dindex',pgDegree.dindex);
        Gui.resetField('s3','dindex',pgCollege.dindex);
        Gui.resetField('s3','dindex',otherPgDegree.dindex);
      }
     
    }
    /*
     * initUGAndPGDegreeMap
     * @returns {}
     */
    $scope.initUGAndPGDegreeMap = function() {
     
      var arrDegreeGroups = JSON.parse(Storage.getData("degree_grouping_reg","S"));
      
      $.each(arrDegreeGroups,function(key1,data1)
      {
        $.each(data1,function(key2,data2)
        {
          $.each(data2,function(value,label)
          {      
            $scope.degreeGroupMap[value] = label.split(',');
          });
        });
      });
    }
    //TrackParams.trackClientInfo($scope.screenName);
    $scope.initUGAndPGDegreeMap();
    $scope.initEducationFields(1);
		$scope.enableNextBtn();
	});
	
	//Social Details Controller
	app.controller("SocialDetailsController",function($scope,$location,Gui,Validate,$timeout,Constants,TrackParams,$route){

		$scope.slideDir = Gui.getSlideDir();
		
		$scope.screenName = 's4';
		$scope.previousScreenName = 's3';
		$scope.nextScreenName="s5";
		$scope.bHardReload = false;
        
		$scope.fields = Gui.getRegFields($scope.screenName);
	
		$scope.tabName="Social Details";
		
		$scope.bModalWindow = false;
		$scope.bNextEnable = false;
		$scope.bEnableBack = true;
		
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		
		$scope.hamOn = false;
		$scope.hamTrigger = function(value,refHamObj)
		{
			$scope.hamOn = value;
			$scope.currHamObj = refHamObj;
		}
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return ;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			$location.path($scope.previousScreenName);
		}
		$scope.myNext = function()
		{
			Gui.getNextScreenData($scope.nextScreenName);
			var validate =Validate.validateScreen($scope.screenName);
			if(!validate){
				Gui.showModalWindow($scope);		
				return;
			}
			validate = Validate.validateReligion();
			
			if(!validate){
				Gui.showModalWindow($scope);		
				return;
			}
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();
			$location.path($scope.nextScreenName);
            if($scope.bHardReload)
                $route.reload();
		}
		$scope.enableNextBtn = function()
		{
			var validate =Validate.validateScreen($scope.screenName);
			$scope.bNextEnable = false;
			if(validate)
			{
				$scope.bNextEnable = true;
			}
		}
		$scope.myFormSubmit = function(ele,output,json,indexPos)
		{
			Gui.updateGuiFields($scope.screenName,indexPos,output);
      
			if($scope.screenName=='s4' && indexPos==2)
			{
				$scope.initHoroscope();
				$scope.initCasteNoBar();
			}
			$scope.hamOn = false;
			$scope.enableNextBtn();
		}
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.hamOn)
			{
				$scope.currHamObj.hideHamburger();
				$scope.hamOn = false;
				event.preventDefault(); // This prevents the navigation from happening
			}
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
        $scope.showHamMsg = function(szMsg)
        {
            Gui.toastMsg($scope,szMsg);
        }
    $scope.initHoroscope = function()    
    {
      var allowedReligion = ['1','4','7','9'];
      var religionFieldIndex= 2;
      var horoscopeFieldIndex = 4;
      
      if(allowedReligion.indexOf($scope.fields[religionFieldIndex].userDecision) != '-1') {
        $scope.fields[horoscopeFieldIndex].show = true;
      } else {
        $scope.fields[horoscopeFieldIndex].show = false;
        Gui.resetField('s4','dindex',horoscopeFieldIndex);
      }
    }
	$scope.initCasteNoBar = function()    
	{
	        var allowedReligion = ['1','4','9'];
		var religionFieldIndex= 2;
		var casteNoBarFieldIndex = 3;

	       if(allowedReligion.indexOf($scope.fields[religionFieldIndex].userDecision) != '-1') 
		{
		       $scope.fields[casteNoBarFieldIndex].show = true;
		} 
		else 
		{
		       $scope.fields[casteNoBarFieldIndex].show = false;
		       Gui.resetField('s4','dindex',casteNoBarFieldIndex);
		}     
	 }
	$scope.initHoroscope();
	$scope.initCasteNoBar();
	$scope.enableNextBtn();
        //TrackParams.trackClientInfo($scope.screenName);
	});

	//Login Details Controller
	app.controller("LoginDetailsController",function($scope,$location,UserDecision,Gui,Validate,Register,ApiData,$timeout,Constants,TrackParams,$window,$route){
		
		$scope.slideDir = Gui.getSlideDir();
		$scope.tabName="Login Details";
		
		$scope.screenName = 's5';
		$scope.previousScreenName = 's4';
		$scope.nextScreenName="s6";
		
		$scope.bModalWindow = false;
		$scope.bNextEnable = false;
		$scope.bEnableBack = true;
		$scope.bLoaderWindow = false;
		$scope.bHardReload = false;
        
		$scope.fields = Gui.getRegFields($scope.screenName);
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight()-8;
		
		$scope.serverErrors = {};
		
		$scope.field_pwd = $scope.fields[2];
		$scope.field_email = $scope.fields[1];
		$scope.field_phone = $scope.fields[3];
    $scope.field_name = $scope.fields[0];
		
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return ;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			$location.path($scope.previousScreenName);
		}
		$scope.enableNextBtn = function(loginScreen)
		{
			if(loginScreen==5){
				var pwd 	=$scope.field_pwd.value; 
				var email	=$scope.field_email.value;
				var phone	=$scope.field_phone.value;
				var isd 	=$scope.field_phone.isdVal;
				var name	=$scope.field_name.value;
				if(typeof name === "undefined")
					name=1;
				if(typeof email === "undefined")
					email =1;
				if(typeof pwd === "undefined")
					pwd =1;
				if(typeof phone === "undefined")
					phone =1;	
				if(typeof isd === "undefined")
					isd =1;

				if(email && pwd && phone && isd && name)
	                        	$scope.bNextEnable = true;
				else
					$scope.bNextEnable = false;	
			}
			else{
				var validate =Validate.validateScreen($scope.screenName);
				$scope.bNextEnable = false;
				if(validate)
				{
						$scope.bNextEnable = true;
				}
			}
		}
		$scope.registerUser =function()
		{
			Gui.showLoader($scope);
			ApiData.getApiData('1');
			Register.page1(function(data)
			{
				Gui.hideLoader($scope);
					if(data.responseStatusCode === "0")
					{
						Gui.setSlideDir('left');
						$scope.slideDir = Gui.getSlideDir();
                        
                        if(data.LANDINGPAGE === 'HOMEPAGE')
                        {
                            var serverTrackParams = TrackParams.getTrackingParams();
                            $timeout(function(){
                                var groupName = '';
                                var adnetwork1 = '';
                                try{
                                    groupName = serverTrackParams.groupname;
                                    adnetwork1 = serverTrackParams.adnetwork1;
                                }catch(e){
                                    //console.log(e.stack);
                                }
                                
                                var urlParams = "?fromReg=1&groupname="+groupName+"&adnetwork1=" + adnetwork1;
                                $window.location.href = "/profile/mainmenu.php"+urlParams;
        					},500);
                            //TrackParams.resetClientInfo();
                        }
                        else if (data.LANDINGPAGE === 'SCREEN_6')
                        {
                            $timeout(function(){
                              $location.path($scope.nextScreenName+"/AboutMeRegister");
                              if($scope.bHardReload)
                                  $route.reload();
                            },250);  
                        } 
						UserDecision.removeUD();
					}
					else//Error Case
					{
						//Handle Error Message
						$scope.serverErrors = data.error;
						Gui.showModalWindow($scope);
					}
				},function(error){
					Gui.hideLoader($scope);
					$scope.serverErrors = new Array();
					$scope.serverErrors[0] = "Something Went Wrong. Try again"
					if(error.status == 0)
					{
						$scope.serverErrors[0] = "Not connected to internet. Check your internet connection";
					}
					
					Gui.showModalWindow($scope);
			});
		}
		$scope.myNext = function()
		{
			Gui.getNextScreenData($scope.nextScreenName);
			$scope.validateTrue =Validate.validateLoginFields($scope.screenName);
			if(!$scope.validateTrue){
				Gui.showModalWindow($scope);		
				return;
			}
			$scope.registerUser();				
		}
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
    $scope.openInNewTab = function(pageName)
    {
        if(pageName.toLowerCase().indexOf("terms")!=-1)
            $window.open('/static/page/disclaimer','_blank');
        if(pageName.toLowerCase().indexOf("privacy")!=-1)
            $window.open('/static/page/privacypolicy','_blank');
    }
		$scope.enableNextBtn();
        //TrackParams.trackClientInfo($scope.screenName);
	});
	
	//About Details Controller
	app.controller("AboutDetailsController",function($scope,$location,$window,Gui,UserDecision,$timeout,Register,ApiData,Constants,Storage,$routeParams,Incomplete,TrackParams,$route,Validate){
		$scope.slideDir = Gui.getSlideDir();
		if($routeParams.Incomplete=="AboutMe" || $routeParams.Incomplete=="AboutMeDirect")
			$scope.tabName="Provide Missing Details";
		else
			$scope.tabName="About me";
		if($routeParams.Incomplete=="AboutMe" || $routeParams.Incomplete=="AboutMeDirect")
			$scope.SubmitName="Complete your Profile";
		else{
			$scope.SubmitName="Create My Profile";
            $scope.nextScreenName = 's9';
        }
        
        $scope.bModalWindow = false;
		$scope.bNextEnable = false;
        $scope.bHardReload = false;
        
		$scope.screenName = 's6';
		$scope.Height =  Constants.getWindowHeight()-70;
		$scope.fields = Gui.getRegFields($scope.screenName);
		$scope.aboutUser = $scope.fields[0];
        
        $scope.myDetail = new String();
        $scope.myDetail = $scope.aboutUser.hint;
        $scope.myDetailLen = 0;
        
        if($scope.aboutUser.userDecision.length!=0){
            $scope.myDetail = $scope.aboutUser.userDecision;
            $scope.myDetailLen = $scope.myDetail.length;
        }
        
        $scope.bShowHint = true;
        // if($scope.tabName=="About me")
        //     TrackParams.trackClientInfo($scope.screenName);
        $scope.serverErrors = {};
		if($routeParams.Incomplete=="AboutMe")
			$scope.bEnableBack = true;
		else
			$scope.bEnableBack = false;
		$scope.MaxHeight = Constants.getWindowHeight();
        
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			if($routeParams.Incomplete=="AboutMe"){
				
				var editFieldArr=Storage.getUserData("UD");
				Storage.storeUserData("abt_me",editFieldArr);
				$timeout(function(){
                    $location.path('/s8');
                },250);
			}
			else
			{	
                $timeout(function(){
                    $location.path('/s5');
                    $route.reload();
                },250);
            }
		}
		$scope.registerUser = function()
		{
			if($routeParams.Incomplete=="AboutMe" || $routeParams.Incomplete=="AboutMeDirect")
			{
				var editFieldArr={};
				editFieldArr=Storage.getUserData("UD",editFieldArr);
				if(editFieldArr["yourinfo"]!==undefined)
					editFieldArr["YOURINFO"]=editFieldArr["yourinfo"];
				
				if($routeParams.Incomplete=="AboutMe"){
					if(editFieldArr["RELATIONSHIP"])
					{
						editFieldArr["RELATION"]=editFieldArr["RELATIONSHIP"];
						delete(editFieldArr.RELATIONSHIP);
					}
					if(editFieldArr['DTOFBIRTH_YEAR'])
					{
						editFieldArr["DTOFBIRTH"]=editFieldArr['DTOFBIRTH_YEAR']+"-"+editFieldArr['DTOFBIRTH_MONTH']+"-"+editFieldArr['DTOFBIRTH_DAY'];
						delete(editFieldArr.DTOFBIRTH_DAY);
						delete(editFieldArr.DTOFBIRTH_MONTH);
						delete(editFieldArr.DTOFBIRTH_YEAR);
					}
					if(editFieldArr["PHONE_MOB"])
					{
						var phoneArr=editFieldArr["PHONE_MOB"].split(",");
						delete(editFieldArr.PHONE_MOB);
						editFieldArr["PHONE_MOB"]={};
						editFieldArr["PHONE_MOB"]["isd"]=phoneArr[0];
						editFieldArr["PHONE_MOB"]["mobile"]=phoneArr[1];				
					}
					delete(editFieldArr.GENDER);
				}
				delete(editFieldArr.yourinfo);
				Storage.removeUserData("UD");
				Storage.removeUserData("abt_me");
				Storage.removeUserData("incomepleteData");
				//Storage.storeUserData("UD",editFieldArr);
				$scope.success="";
				var channel="";
				if($("#channel").val()=="INCOM_SMS")
					channel=$("#channel").val();
				stopTouchEvents(1,1,1);
				$.when(			
						$.ajax({
						  url: "/api/v1/profile/editsubmit?incomplete=Y&channel="+channel,
						  type: 'POST',
						  datatype: 'json',
						  headers: { 'X-Requested-By': 'jeevansathi' },
						  cache: true,
						  async: true,
						  data: {editFieldArr : editFieldArr},
						  success: function(result) {
								startTouchEvents(100);  
								if(result.responseStatusCode!=='0'){
									$scope.showServerError(result.responseMessage);
									$scope.success=0;
								}
								else if(result.responseStatusCode=='0'){
									$scope.showServerError("Your profile is now complete");
									$scope.success=1;
								}
									
						},
						  error: function(result)
						  {
							  startTouchEvents(100);
							 $scope.showServerError(result.responseMessage);
							 $scope.success=2;
						  }
						 })
					).then(function() {
						if($scope.success==1)
						{
              //Now forward to FamilyFlow
              var familyData = '_familyData';
              Gui.initRegFields('s9',familyData);
              Gui.initRegFields('s10',familyData);
              
              var tmpData = Storage.getUserData(familyData);
              Storage.storeUserData('UD',tmpData);
              Storage.removeUserData(familyData);
              Gui.setIncompleteFlow(true);
              
              $timeout(function(){
                 $scope.hideModalWidow(); 
                 $location.path('/s9');
                 if($scope.bHardReload)
                      $route.reload();
              },500);
						}
						else
						{
							setTimeout(function(){window.location="/register/newJsmsReg?incompleteUser=1";},1000);
						} // Alerts 200
					});
			}
			else
			{
                Gui.getNextScreenData($scope.nextScreenName);
				Gui.showLoader($scope);
				ApiData.getApiData('2');
				Register.page2(function(data){
					Gui.hideLoader($scope);
					if(data.responseStatusCode === "0")
					{
						$scope.serverErrors[0] = "Registration Complete";
						$scope.bModalWindow= true;
						UserDecision.removeUD();
                        
                        Gui.showModalWindow($scope);
                        if(data.LANDINGPAGE === 'FAMILYPAGE'){
                            $timeout(function(){
                               $scope.hideModalWidow(); 
                               $location.path('/s9');
                               if($scope.bHardReload)
                                    $route.reload();
                            },500);
                        }           
					}
					else//Error Case
					{
						//Handle Error Message
						$scope.serverErrors[0] = data.responseMessage;
						if(data.error)
							$scope.serverErrors = data.error;
						Gui.showModalWindow($scope);
                        if(data.LANDINGPAGE === 'SCREEN_1')
                        {
                           $timeout(function(){
                               $scope.bModalWindow = false; 
                               $location.path('/s1');
                               if($scope.bHardReload)
                                    $route.reload();
                           },500);
                           //TrackParams.resetClientInfo();
                        }
					}
				},function(error){
					Gui.hideLoader($scope);
					
					$scope.serverErrors[0] = "Something Went Wrong. Try again"
					if(error.status == 0)
					{
						$scope.serverErrors[0] = "Not connected to internet. Check your internet connection";
					}
					
					Gui.showModalWindow($scope);
					
				});	
			}
		}
		$scope.myNext = function()
		{ 
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();

			if($scope.aboutUser.value.length<100)
			{
				$scope.aboutUser.errorLabel = "Please fill atleast 100 characters. ";
				Gui.showModalWindow($scope);
				return;
			}
			
			$scope.registerUser();
			//TODO : if all goes well, clean localStorage values
		}
		$scope.onDetailChange = function(value)
		{
            if(value.length==0)
            {
                $scope.bShowHint =true;
                $scope.myDetail = $scope.aboutUser.hint;
            }
			$scope.aboutUser.userDecision = value;
			UserDecision.store($scope.aboutUser.storeKey,$scope.aboutUser.userDecision);

		}
        $scope.onKeyPress = function()
        {
            if($scope.bShowHint)
                return;
            $scope.aboutUser.value = Validate.myTrim($scope.myDetail);
            $scope.aboutUser.value = Validate.trim_newline($scope.aboutUser.value);
            $scope.myDetailLen = $scope.aboutUser.value.length;
                
            if($scope.bShowHint)
                $scope.myDetailLen = 0;
        }
        
        $scope.hideHint = function()
        {
            $scope.bShowHint = false;
            
            if($scope.myDetail.indexOf($scope.aboutUser.hint) !=-1)
                $scope.myDetail = "";
        }
		//$scope.countClass = 'color2';
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
            if(newUrl.indexOf('s5')!=-1)
            {
                event.preventDefault(); // This prevents the navigation from happening
            }
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
		//server Error for Incomplete 
		$scope.showServerError=function(errorText)
		{
			if(!errorText)
				errorText="Something went Wrong";
				
			var correctHtml='<div class="errClass"><div class="pad12_e white f13 op1">'+errorText+'</div></div>';
			$("#scrollContent").prepend(correctHtml);
			setTimeout(function(){$(".errClass").addClass("showErr").css("top",$("#overlayHead").outerHeight());},5);
			setTimeout(function(){$(".errClass").removeClass("showErr");
				setTimeout(function(){$(".errClass").remove();},300);
			},2000);
		}
		Gui.initRegFields($scope.screenName);
        $scope.onKeyPress();
	});
	
	
	//Complete Profile Controller
	app.controller("CompleteProfileController",function($scope,$location,$window,Gui,UserDecision,$timeout,Register,ApiData,Constants,Incomplete,Storage,$route){
		
		$scope.bEnableBack = true;
		$scope.bModalWindow = false;
		$scope.bNextEnable = false;
        $scope.bHardReload = false;
        
		$scope.screenName = 's7';		
		$scope.nextScreenName = "s8";
		$scope.MaxHeight = Constants.getWindowHeight();
		
		$scope.isAbout=false;
		stopTouchEvents(1,1,1);
		Incomplete.incompleteApi(function(data){
			if(data.responseStatusCode === "7" && data.Incomplete!=null)
			{
				Storage.storeUserData("incomepleteData",data.Incomplete);
				$scope.profilePicUrl=data.ProfilePicUrl;
				setProfileCompletetion(data.ProfileCompletionScore);
				//$("#profilepic").attr("src",data.ProfilePicUrl);
				if(data.Incomplete!=="")
				{
					angular.forEach(data.Incomplete,function(objField,key)
					{
						if(objField.key=="YOURINFO" && data.Incomplete.length==2)
							$scope.isAbout=true;
					});
				}
				startTouchEvents(1,1,1);						
			}
			else if(data.responseStatusCode === "9")
			{
				setTimeout(function(){window.location="/static/LogoutPage";},1000);
				//startTouchEvents(1,1,1);
			}
			else//Error Case
			{
				setTimeout(function(){window.location="/profile/mainmenu.php";},1000);
				/*startTouchEvents(1,1,1);
				if(data.responseMessage)
					$scope.serverErrors[0] = data.responseMessage;
				else
					$scope.serverErrors[0] = "Something went wrong";
				Gui.showModalWindow($scope);*/
			}
			
		},function(error){
			$scope.serverErrors[0] = "Something went wrong";
			Gui.showModalWindow($scope);
		});
		$scope.myBack = function()
		{
			//$location.path('/s3');
			//TODO Call Home Page	
		}
		$scope.bModalWindow = false;
		$scope.serverErrors = {};
		$scope.hideModalWidow = function()
		{
			$scope.serverErrors = {};
			Gui.clearErrors($scope.screenName);
			$scope.bModalWindow =false;
		}
		$scope.showModalWindow = function()
		{
			$scope.bModalWindow =true;
			$timeout(function(){$scope.hideModalWidow();},Constants.getMsgTimeOut());		
		}
		$scope.onIncomplete = function(fieldName)
		{	
			Gui.setSlideDir('left');
			$scope.slideDir = Gui.getSlideDir();
			
			if($scope.isAbout)
			{
                $location.path('/s6/AboutMeDirect');
                if($scope.bHardReload)
                    $route.reload();
            }
			else
			{
                $location.path('/s8');
                if($scope.bHardReload)
                    $route.reload();
            }
				
		}
		
		//$scope.countClass = 'color2';
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
            if(newUrl.indexOf('s6')==-1 && newUrl.indexOf('s8')==-1)
            {
                event.preventDefault(); // This prevents the navigation from happening
            }
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
		$scope.skipIncompleteLogout = function()
		{	
			window.location='/P/logout.php';
		}
		
	});
	
	//Incomplete Details Controller
	app.controller("IncompleteProfileController",function($scope,Gui,$location,UserDecision,Validate,$timeout,Constants,Incomplete,Storage,$route){
		//Page1
		$scope.fields1 = Gui.getRegFields('s1');
		
		//Page2
		$scope.fields2 = Gui.getRegFields('s2');
		$scope.gender = $scope.fields2[0];
		$scope.dob	  = $scope.fields2[1];
		
		//Page3
		$scope.fields3 = Gui.getRegFields('s3');
		
		//Page4
		$scope.fields4 = Gui.getRegFields('s4');
		
		//Page5
		$scope.fields5 = Gui.getRegFields('s5');
		$scope.field_phone = $scope.fields5[2];
		
		
		$scope.serverErrors = {};
		
		$scope.previousScreenName = 's7';
		$scope.screenName = 's2';
		$scope.nextScreenName="s6";
		
		$scope.isAbout=false;
		$scope.isRelation=false;
		$scope.isPhoneMob=false;
		$scope.isDob=false;
		$scope.isReligion=false;
		$scope.isValid=true;
		$scope.incompleteData=Storage.getUserData("incomepleteData");
		$scope.isDobValid=true;
		
		Gui.storeIncompleteData($scope.incompleteData);
		if($scope.incompleteData!==null)
		{
			angular.forEach($scope.incompleteData,function(objField,key)
			{
				if(objField.key=="GENDER"){
					Storage.storeUserData('UD',{gender:objField.value});
					//Reset Gender
					var output={};
					output["gender"] = {"label":'Gender',"value":objField.value};
					Gui.updateGuiFields('s2',0,output);
				}
				if(objField.key=="YOURINFO")
					$scope.isAbout=true;
				if(objField.key=="RELATION")
					$scope.isRelation=true;
				if(objField.key=="PHONE_MOB")
					$scope.isPhoneMob=true;
				if(objField.key=="DTOFBIRTH"){
					$scope.isDob=true;
					if($scope.dob.depValue.length === 0 || ($scope.dob.depValue != $scope.gender.userDecision))
					{
						$scope.dob.depValue=$scope.gender.userDecision;
					}
				}
				if(objField.key=="RELIGION")
					$scope.isReligion=true;
				
			});
			if($scope.isAbout && $scope.incompleteData.length==2){
				$location.path('/s6/AboutMeDirect');
                if($scope.bHardReload)
                    $route.reload();
				Storage.removeUserData("UD");
			}
				//$scope.enableNextBtn();
		}
		else//Error Case
		{
			$scope.serverErrors[0] = "Something went wrong";
			Gui.showModalWindow($scope);
		}
		
		$scope.slideDir = Gui.getSlideDir();
		$scope.tabName="Provide Missing Details";
		if($scope.isAbout)
			$scope.nextText="Next";
		else
			$scope.nextText="Complete your profile";
		$scope.bNextEnable = false;
		$scope.bModalWindow = false;
		$scope.bEnableBack = true;
		$scope.bHardReload = false;
		
		
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		
		$scope.hamOn = false;
		$scope.hamTrigger = function(value,refHamObj)
		{
			$scope.hamOn = value;
			$scope.currHamObj = refHamObj;
		}
		
		//common functions for 
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return ;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			$location.path('/s7');			
		}
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
		
		$scope.enableNextBtn = function()
		{
			$scope.validation();
			if($scope.isValid)
			{
				$scope.bNextEnable = true;
			}
			else
				$scope.bNextEnable = false;
		}
		$scope.myNext = function()
		{
			
			$scope.validation();
			if(!$scope.isValid)
				Gui.showModalWindow($scope);
			else
			{
				Gui.setSlideDir('left');
				$scope.slideDir = Gui.getSlideDir();
				var origUD=Storage.getUserData("UD");
				if($scope.incompleteData)
					UserDecision.setIncompleteData($scope.incompleteData);
				var editFieldArr={};
				editFieldArr=UserDecision.getIncompleteData();
				if(!$scope.isAbout){
					if($scope.isRelation)
					{
						if(editFieldArr["RELATIONSHIP"]){
							editFieldArr["RELATION"]=editFieldArr["RELATIONSHIP"];
							delete(editFieldArr.RELATIONSHIP);
						}
					}
					if($scope.isDob)
					{
						if(editFieldArr["DTOFBIRTH_YEAR"]){
							editFieldArr["DTOFBIRTH"]=editFieldArr['DTOFBIRTH_YEAR']+"-"+editFieldArr['DTOFBIRTH_MONTH']+"-"+editFieldArr['DTOFBIRTH_DAY'];
							delete(editFieldArr.DTOFBIRTH_DAY);
							delete(editFieldArr.DTOFBIRTH_MONTH);
							delete(editFieldArr.DTOFBIRTH_YEAR);
						}
					}
					if($scope.isPhoneMob)
					{
						if(editFieldArr["PHONE_MOB"])
						{
							var phoneArr=editFieldArr["PHONE_MOB"].split(",");
							delete(editFieldArr.PHONE_MOB);
							editFieldArr["PHONE_MOB"]={};
							editFieldArr["PHONE_MOB"]["isd"]=phoneArr[0];
							editFieldArr["PHONE_MOB"]["mobile"]=phoneArr[1];
						}
					}
					if(editFieldArr.GENDER)
						delete(editFieldArr.GENDER);
				}
				var prevAbtMe=Storage.getUserData("abt_me");
				if(prevAbtMe){
					if(prevAbtMe["yourinfo"])
					editFieldArr["yourinfo"]=prevAbtMe["yourinfo"];
				}
				if($scope.isAbout){
					Storage.removeUserData("UD");
					Storage.storeUserData("UD",editFieldArr);
					$location.path('/s6/AboutMe');
                    $route.reload();
				}
				else{
					var channel="";
					if($("#channel").val()=="INCOM_SMS")
						channel=$("#channel").val();
					Storage.removeUserData("UD");
					Storage.removeUserData("abt_me");
					Storage.removeUserData("incomepleteData");
					stopTouchEvents(1,1,1);
					$.when(			
						$.ajax({
						  url: "/api/v1/profile/editsubmit?incomplete=Y&AUTHCHECKSUM=&channel="+channel,
						  type: 'POST',
						  datatype: 'json',
						  headers: { 'X-Requested-By': 'jeevansathi' },       

						  cache: true,
						  async: true,
						  data: {editFieldArr : editFieldArr},
						  success: function(result) {
							  startTouchEvents(100);  
								if(result.responseStatusCode!=='0'){
									$scope.showServerError(result.responseMessage);
									$scope.success=0;
								}
								else if(result.responseStatusCode=='0'){
									$scope.showServerError("Your profile is now complete");
									$scope.success=1;
								}
									
						},
						  error: function(result)
						  {
							  startTouchEvents(100);
							 $scope.showServerError(result.responseMessage);
							 $scope.success=2;
						  }
						 })
					).then(function() {
						if($scope.success==1)
						{
							if($scope.isPhoneMob)
								setTimeout(function(){window.location="/phone/jsmsDisplay";},1000);
							else
								setTimeout(function(){window.location="/profile/mainmenu.php";},1000);
						}
						else
						{
							setTimeout(function(){window.location="/register/newJsmsReg?incompleteUser=1";},1000);
						} // Alerts 200
					});
				}
		}
	}
		$scope.validation=function()
		{
			$scope.isValid=true;
			if($scope.isRelation && !Validate.validateIncomplete($scope.fields1))
				$scope.isValid=false;
			if(!Validate.validateIncomplete($scope.fields2))
				$scope.isValid=false;
			else if($scope.isDob  && !Validate.validateDob()){
				$scope.isValid=false;
			}
			
			if(!Validate.validateIncomplete($scope.fields3))
				$scope.isValid=false;
			if(!Validate.validateIncomplete($scope.fields4))
				$scope.isValid=false;
			else if($scope.isReligion && !Validate.validateReligion()){
				$scope.isValid=false;
			}
			if($scope.isPhoneMob && !Validate.validatePhone($scope.fields5[2].dindex,'s5')){
				$scope.isValid=false;
			}
			else
			{
				$scope.fields5[2].errorLabel="";
			}
		}
		$scope.phoneValidation=function()
		{
			$scope.validation();
			$scope.enableNextBtn();
		}
		$scope.myFormSubmit = function(ele,output,json,indexPos)
		{
			$scope.hamOn = false;
			if($scope.currHamObj)
				$scope.screenName = $scope.currHamObj.screenName;
			
			Gui.updateGuiFields($scope.screenName,indexPos,output);	
			var checkValid=true;
			if($scope.screenName=='s2' && indexPos==1 &&  !Validate.validateDob())
			{
				checkValid=false;
				Gui.showModalWindow($scope);
				//$scope.showServerError("provide a valid date of birth");
			}
                        if(indexPos == '3')/*Country*/
			{
				var countryField = $scope.fields2[3];
				$scope.initIncompleteStateWidget();
				$scope.initIncompleteCityWidget();
			}
                        if(indexPos=='4')
			{
				$scope.initIncompleteCityWidget();
			}
			if(checkValid)
			{
				$scope.validation();
				$scope.enableNextBtn();
			}
			
		}
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.hamOn)
			{
				$scope.currHamObj.hideHamburger();
				$scope.hamOn = false;
				event.preventDefault(); // This prevents the navigation from happening
			}
			if($scope.bModalWindow)
			{
				$scope.hideModalWidow();
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
		$scope.showServerError=function(errorText)
		{
			if(!errorText)
				errorText="Something went Wrong";
				
			var correctHtml='<div class="errClass"><div class="pad12_e white f14 txtc op1">'+errorText+'</div></div>';
			$("#scrollContent").prepend(correctHtml);
			setTimeout(function(){$(".errClass").addClass("showErr").css("top",$("#overlayHead").outerHeight());},5);
			setTimeout(function(){$(".errClass").removeClass("showErr");
				setTimeout(function(){$(".errClass").remove();},300);
			},2000);
		}
                $scope.initIncompleteStateWidget = function()
		{
			var countryField = $scope.fields2[3];
			var stateField = $scope.fields2[4];
			if(parseInt(countryField.userDecision)==51)
			{
				stateField.show=false;
			}
			else
			{
				stateField.show=true;
			}
		}
		$scope.initIncompleteCityWidget = function()
		{
			var countryField = $scope.fields2[3];
			var stateField = $scope.fields2[4];
			var cityField = $scope.fields2[5];
                        if((stateField.userDecision && parseInt(countryField.userDecision)==51)||parseInt(countryField.userDecision)==128)
                        {
                                cityField.show=false;
                        }
                        else
                        {
                                cityField.show=true;
                        }
		}
		 $scope.showHamMsg = function(szMsg)
        {
            Gui.toastMsg($scope,szMsg);
        }
		$scope.enableNextBtn();
                $scope.initIncompleteStateWidget();
               $scope.initIncompleteCityWidget();
	});
    
    //Splash Controller
    app.controller("SplashController",function($scope,Gui,$location,UserDecision,$window,TrackParams,Constants,$route,$timeout){
      $scope.MaxHeight = Constants.getWindowHeight();     
      $scope.slideDir = Gui.getSlideDir();
      $scope.$on("$viewContentLoaded",function(){
          $timeout(function(){
            Gui.setSlideDir('left');
            $scope.slideDir = Gui.getSlideDir();
            $location.path('/s1');
            $route.reload();
          },4000);
      });
    });
    
    //Family Section Controller
    app.controller("FamilyDetailController",function($scope,Constants,$route,$timeout,Gui,UserDecision,TrackParams,ApiData,$location,$sce){
        $scope.slideDir = Gui.getSlideDir();
		
		$scope.screenName = 's9';
		$scope.previousScreenName = 's6';
		$scope.nextScreenName="s10";
		
		$scope.tabName="Family Details";
		$scope.bModalWindow = false;
		$scope.bNextEnable = true;
		$scope.bEnableBack = false;
		$scope.bHardReload = false;
       		$scope.optionFields = Gui.getRegOptionalFields($scope.screenName); 
        $scope.bShowSkip   = true;
		$scope.fields = Gui.getRegFields($scope.screenName);
		$scope.MaxHeight = Constants.getWindowHeight();
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight();
		
		$scope.hamOn = false;
		$scope.hamTrigger = function(value,refHamObj)
		{
			$scope.hamOn = value;
			$scope.currHamObj = refHamObj;
		}
        
        $scope.onSkip = function()
        {
            $scope.myNext();
            
        }
        $scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
        
        $scope.myFormSubmit = function(ele,output,json,indexPos)
		{
			Gui.updateGuiFields($scope.screenName,indexPos,output);

        if($scope.screenName=="s9" && indexPos==8)
        {
        $scope.initOtherCity();
        }
			$scope.hamOn = false;
		}
		$scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
			if($scope.hamOn)
			{
				$scope.currHamObj.hideHamburger();
				$scope.hamOn = false;
				event.preventDefault(); // This prevents the navigation from happening
			}
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
            if(newUrl.indexOf($scope.nextScreenName)===-1){
                event.preventDefault(); // This prevents the navigation from happening
            }
		});
       
		$scope.myNext = function()
		{
            Gui.setSlideDir('left');
            $scope.slideDir = Gui.getSlideDir();
            $timeout(function(){
                $location.path($scope.nextScreenName);
                if($scope.bHardReload)
                    $route.reload();
            },250);
            
            return true;
		}
        $scope.showHamMsg = function(szMsg)
        {
            Gui.toastMsg($scope,szMsg);
        }
    $scope.initOtherCity = function()
    {
	var otherCityIndex = 9;
	var dec = $scope.optionFields[2].userDecision;
      if(dec!='' && dec==="0") {
        $scope.fields[otherCityIndex].show = true;
      } else {
        $scope.fields[otherCityIndex].show = false;
        Gui.resetField('s9','dindex',otherCityIndex);
      }
    }
    $scope.initOtherCity();

        //TrackParams.trackClientInfo($scope.screenName);
    });
    
    //About Family Controller
    app.controller("AboutFamilyController",function($scope,$location,$window,Gui,UserDecision,$timeout,Register,ApiData,Constants,Storage,$routeParams,Incomplete,TrackParams,$route,Validate){
        $scope.slideDir = Gui.getSlideDir();
		
		$scope.tabName="About family";
		
		$scope.SubmitName="Done";
		
        $scope.bModalWindow = false;
		$scope.bNextEnable = true;
        $scope.bHardReload = false;
        
		$scope.screenName = 's10';
        $scope.previousScreenName = 's9';
        
		$scope.Height =  Constants.getWindowHeight()-70;
		$scope.fields = Gui.getRegFields($scope.screenName);
		$scope.aboutFamily = $scope.fields[0];
        
        $scope.myDetail = new String();
        $scope.myDetail = $scope.aboutFamily.value;
        if($scope.aboutFamily.userDecision.length===0)
            $scope.myDetail = $scope.aboutFamily.hint;
        
		$scope.myDetailLen = 0;
        $scope.bShowHint = true;
        
        // if($scope.tabName=="About me")
        //     TrackParams.trackClientInfo($scope.screenName);
        $scope.serverErrors = {};
		
		$scope.bEnableBack = true;
		
		$scope.MaxHeight = Constants.getWindowHeight();
        
		$scope.fieldsHeight = Constants.getWindowHeight() - Constants.getNextBtnHeight() - Constants.getHeaderHeight();
		$scope.hideModalWidow = function(){Gui.hideModalWidow($scope)};
        
        $scope.redirectHome = function()
        {
            Gui.showLoader($scope);
            var serverTrackParams = TrackParams.getTrackingParams();
            $timeout(function(){
                var groupName = '';
                var adnetwork1 = '';
                var source = '';
                try{
                    groupName = serverTrackParams.groupname;
                    adnetwork1 = serverTrackParams.adnetwork1;
                    source = serverTrackParams.source;
                }catch(e){
                    //console.log(e.stack);
                }
                var urlParams = "?fromReg=1&groupname="+groupName+"&adnetwork1=" + adnetwork1+"&source=" + source;
                
                //If From Incomplete Flow then dont pass url params
                if(Gui.isIncompleteFlow()){
                  urlParams = "";
                  Storage.removeUserData('_iFlow');
                }
                Storage.removeUserData('familyIncomeDep');
                $window.location.href = "/profile/mainmenu.php"+urlParams;
            },500);
            //TrackParams.resetClientInfo();            
        }
        $scope.onDetailChange = function(value)
		{
            if(value.length==0)
            {
                $scope.bShowHint =true;
                $scope.myDetail = $scope.aboutFamily.hint;
            }
			$scope.aboutFamily.userDecision = value;
			UserDecision.store($scope.aboutFamily.storeKey,$scope.aboutFamily.userDecision);

		}
        
        $scope.onKeyPress = function()
        {
            if($scope.bShowHint)
                return;
            $scope.aboutFamily.value = Validate.myTrim($scope.myDetail);
            $scope.aboutFamily.value = Validate.trim_newline($scope.aboutFamily.value);
            $scope.myDetailLen = $scope.aboutFamily.value.length;
                
            if($scope.bShowHint)
                $scope.myDetailLen = 0;
        }
        $scope.hideHint = function()
        {
            $scope.bShowHint = false;
            
            if($scope.myDetail.indexOf($scope.aboutFamily.hint) !=-1)
                $scope.myDetail = "";
        }
		$scope.myBack = function()
		{
			if(!$scope.bEnableBack)
				return;
			Gui.setSlideDir('right');
			$scope.slideDir = Gui.getSlideDir();
			
            $timeout(function(){
                $location.path($scope.previousScreenName);
                if($scope.bHardReload)
                    $route.reload();
            },250);
		}
        
        $scope.updateFamilyValue = function()
		{
			Gui.showLoader($scope);
			ApiData.getApiData('3');
			Register.page3(function(data)
			{
				Gui.hideLoader($scope);
					if(data.responseStatusCode === "0")
					{
						Gui.setSlideDir('left');
						$scope.slideDir = Gui.getSlideDir();
                        $scope.redirectHome();
						UserDecision.removeUD();
					}
					else if(data.responseStatusCode === "9")
                    {
                        $scope.serverErrors = new Array();
                        $scope.serverErrors = data.responseMessage;
						Gui.showModalWindow($scope);
                    }
                    else//Error Case
					{
						//Handle Error Message
                        $scope.serverErrors = new Array();
						$scope.serverErrors = data.error;
						Gui.showModalWindow($scope);
					}
				},function(error){
					Gui.hideLoader($scope);
					$scope.serverErrors = new Array();
					$scope.serverErrors[0] = "Something Went Wrong. Try again"
					if(error.status == 0)
					{
						$scope.serverErrors[0] = "Not connected to internet. Check your internet connection";
					}
					Gui.showModalWindow($scope);
			});
		}
        
        $scope.myNext = function()
		{
			$scope.updateFamilyValue();				
		}
        
        $scope.$on('$locationChangeStart', function (event, newUrl, oldUrl) 
		{
            if(newUrl.indexOf($scope.previousScreenName)===-1)
            {
                event.preventDefault(); // This prevents the navigation from happening
            }
			if($scope.bModalWindow)
			{
				Gui.hideModalWidow($scope);
				event.preventDefault(); // This prevents the navigation from happening
			}
		});
    });
})();

