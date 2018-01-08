(function(){
	'use strict';
	var app = angular.module("regApp.directive",[]);

	app.directive('regTab',function(){
		return {
			restrict : 'A',
			replace  :true,
			template : '<div class="bg1">  						    <div class="pad1">  						      <div class="rem_pad1">  						        <div ng-click=\"back()\" ng-class={\'cursp\':enableBack} class="fl wid20p white"> 						        <i ng-class={\'mainsp\':enableBack,\'arow2\':enableBack,\'no_arow2\':!enableBack}  ></i> </div>  						        <div class="fl wid60p txtc white fontthin f16 ">{{label}}</div> <span ng-if="bShowSkip" id=\'skipBtn\' ng-click=\"onSkip()\" class="fr white txtr fontthin f16"> Skip</span>  						        <div class="clr"></div>  						      </div>  						    </div>  						  </div>',
			scope:{
				label:'@data',
				back:'=onBack',
				enableBack:'=',
                bShowSkip:'=showSkip',
                onSkip:'='
			}
		}
	});

	app.directive('regHamField',['$timeout',function(timer){
		return {
			restrict : 'A',
			replace  : true,
			template : "<div class=\"brdr1\" id={{info.id}} name={{info.label}} hamburgermenu={{info.hamburgermenu}} 						dependant={{info.dependant}} 						dshow={{info.dshow}} 						dmove={{info.dmove}} 						dshow={{info.dshow}} 						dhide={{info.dhide}} 						dselect={{info.dselect}} 						dependant={{info.dependant}} 						dcallback={{info.dcallback}} 						dindexPos={{info.dindex}} 						duserDecision={{info.userDecision}} 						depValue={{info.depValue}} 						tapName={{info.tapName}} 						dependant_tapName={{info.dependant_tapName}} 						DBUTTON={{info.DBUTTON}} screenName={{info.screenName}} defaultValue={{info.defaultValue}}> 					        <div class=\"pad1\"> 					          <div class=\"pad2\" style=\"cursor:pointer\"> 					            <div class=\"fl wid94p\"> 					              <div class=\"color8 f12 fontlig\">{{info.label}}</div> 					              <div class=\"color11 f15 pt10 fontlig\">{{info.value}}</div> 					            </div> 					            <div class=\"fr wid4p pt8\"> <i class=\"mainsp arow1\"></i> </div> 					            <div class=\"clr\"></div> 					          </div> 					        </div> 				        </div>",
			scope :{
					info :'=data',
				},
			link: function (scope, elem, attrs, ctrl) {
        scope.info.id = 'reg_'+scope.info.label.replace('\'','');
		            var initHamBurger = function () {
						if(scope.info.hamburgermenu == 0)                
							return ;
						scope.hamObj = new Hamburger(elem);
		            }
		            timer(initHamBurger, 350);
		            //Delete Ham Object
		            scope.$on("$destroy",function( event ) {
                        if(typeof scope.hamObj == 'object')
                        {
                            delete scope.hamObj;
                        }
                    });
                    
		        }	        
		}
	}]);
	
	app.directive('regPinCodeField',['$timeout',function(timer){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 					        <div class="pad1"> 					          <div class="pad2" style="cursor:pointer"> 					            <div class="fl reg_wid90"> 					              <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}}</div> 					              <input class="reginp fontlig fullwid" ng-model="info.value" ng-focus="hover()"    ng-keyup="limitDigit($event,info.value)" 					              ng-blur="checkPinCode(info.value)" type={{info.inputType}} 					              class="color11 f15 pt10 fontlig" placeholder={{info.hint}} 					              value={{info.value}}/> 					            </div> 										            <div ng-if="info.errClass" class="fr wid8p pt8"><i class="mainsp reg_errorIcon"></i> </div> 					            <div class="clr"></div> 					          </div> 					        </div> 				        </div>',
			scope :{
					info :'=data',
					checkPinCode:'=',
					hover:'=hover',
                    onNext:'='
				},
			controller:function($scope,$element,$window,Constants,$timeout)
			{
				$scope.limitDigit = function(event,val)
				{
                    if(event.keyCode == '13')
                    {
                        $scope.onNext();
                        return;
                    }
					if(val && val.toString().length>6)
						$scope.info.value = parseInt($scope.info.value.toString().substring(0,6));
				}
			}	
		}
	}]);

	app.directive('regEmailField',function(Validate,UserDecision){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 									<div class="pad1"> 									  <div class="pad2"> 										<div class="fl reg_wid90"> 										  <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}}</div> 										  <input class="reginp reg_wid90 fontlig" ng-blur="autoEmailCorrect()" ng-keyup="enableNext($event)" ng-model="info.value" ng-if="info.inputType" type={{info.inputType}} placeholder={{info.hint}} class="color11 f15 pt10 fontlig" errorLabel={{info.errorLabel}} value={{info.value}} /> 										</div> 		<div ng-if="info.errClass" class="fr wid8p pt8"><i class="mainsp reg_errorIcon"></i> </div> 										<div class="clr"></div> 									  </div> 									</div> 							</div>',
			scope :{
						info :'=data',
						enableNextBtn:"="
				   },
			controller:function($scope)
			{
                $scope.enableNext=function(event){
                        $scope.enableNextBtn('5');
                }
				$scope.autoEmailCorrect=function()
				{
					$scope.info.userDecision = $scope.info.value;
					$scope.info.errClass = false;
					if(typeof $scope.info.value === "undefined")
						$scope.info.errClass = "regErr";
					if($scope.info.value){
						var screenName =$scope.info.screenName;
						var index =$scope.info.dindex;
						$scope.info.userDecision = $scope.info.value;
						var bVal = Validate.autoEmailCorrect(index,screenName);
						
						bVal = Validate.validateEmail(index,screenName);
						if(bVal)
						{
							UserDecision.store($scope.info.storeKey,$scope.info.value);
						}
					}	
					//$scope.enableNextBtn();
				}	
			}
		}
	});
	
	app.directive('regPhoneField',function(Validate,UserDecision){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 									<div class="pad1"> 									  <div class="pad2"> 										<div class="fl reg_wid80"> 										  <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}} 				  </div> 				  <span style="position:relative;top:6px">+</span> 										  <input class="reginp fontlig"  ng-model="info.isdVal"  maxlength="4" style="width:25%" ng-if="info.inputType" type={{info.inputType}} ng-blur="phoneValidate()" ng-keyup="limitDigit($event,info.isdVal,info.isdMaxlength)" value={{info.isdVal}} placeholder={{info.isdHint}} class="color11 f15 pt10 fontlig" /> 										  <input  class="reginp fontlig" ng-model="info.value" ng-blur="phoneValidate()" ng-keyup="limitDigit($event,info.value,info.maxLength)" style="width:65%" ng-if="info.inputType" type={{info.inputType}} placeholder={{info.hint}} value={{info.value}} class="color11 f15 pt10 fontlig" errorLabel={{info.errorLabel}} /> 										</div> 												<div ng-if="info.errClass" class="fr wid8p pt8"><i class="mainsp reg_errorIcon"></i> </div> 										<div class="clr"></div> 									  </div> 									</div> 							</div>',
			scope :{
							info :'=data',
							enableNextBtn:"=",
                            onNext:'='
				   },
			controller:function($scope)
			{
				$scope.phoneValidate=function()
				{
					var index =$scope.info.dindex;
					$scope.info.userDecision = "";;

                                        $scope.info.errClass = false;
                                        if(typeof $scope.info.value === "undefined" || typeof $scope.info.isdVal === "undefined"){
                                                $scope.info.errClass = "regErr";
					}	
					
					if($scope.info.isdVal || $scope.info.value){
						var screenName =$scope.info.screenName;
						var bVal = Validate.validatePhone(index,screenName);
                                                if(bVal)
                                                {
                                                        $scope.info.userDecision = $scope.info.isdVal +"," + $scope.info.value;
                                                        UserDecision.store($scope.info.storeKey,$scope.info.userDecision);
                                                }
					}
				}
				$scope.limitDigit =function(event,val,iMaxCharLength)
				{
                    if(event.keyCode == '13')
                    {
                        $scope.onNext();
                        return;
                    }
					if(val)
					{
						if( val.toString().length>iMaxCharLength)
						{
							if(iMaxCharLength == $scope.info.isdMaxlength)
							{
								$scope.info.isdVal = parseInt($scope.info.isdVal.toString().substring(0,iMaxCharLength));
							}
							else if((iMaxCharLength == $scope.info.maxLength) && $scope.info.isdVal==91)
							{
								$scope.info.value = parseInt($scope.info.value.toString().substring(0,iMaxCharLength));
							}
							else(($scope.info.maxNriLength == $scope.info.maxLength) && $scope.info.isdVal!=91)
							{
								$scope.info.value = parseInt($scope.info.value.toString().substring(0,$scope.info.maxNriLength))
							}
						}
					}
					$scope.enableNextBtn('5');		
				}
				
			}
		}
	});
	
	app.directive('regPwdField',function(Validate,UserDecision){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 					        <div class="pad1"> 					          <div class="pad2"> 					            <div class="fl reg_wid80"> 					              <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}} 							<span ng-if="!info.value.length" class="color12 f11 {{info.errClass}}">(Min 8 character)</span> 							<span ng-if="info.value.length && info.value.length<8" class="color12 f11 {{info.errClass}}">({{8-info.value.length}} more character)</span> 													      </div> 					              <input class="reginp fontlig" ng-blur="passwordValidate()" ng-model="info.value" ng-change="pwdChange()" ng-keyup="enableNext()" style="width:80%" ng-model="info.model" ng-if="info.inputType" type={{info.inputType}} placeholder={{info.hint}} class="color11 f15 pt10 fontlig" errorLabel={{info.errorLabel}} value={{info.value}} /> 					            </div> 						    		            <div class="fr wid14p txtc" ng-click="pwdHelpText()" style="padding:5px 16px"><div class="f12 color2" ng-if="info.value.length">{{helpText}}</div><div><i ng-if="info.errClass" class="mainsp reg_errorIcon"></i></div></div> 					            <div class="clr"></div> 					          </div> 					        				        </div>',
			scope :{
					info :'=data',
					enableNextBtn:'='
				},	        
			controller:function($scope)
			{
                                $scope.enableNext=function(){
                                	$scope.enableNextBtn('5');
                                }
				$scope.toggle = false;
				$scope.pwdType=$scope.info.inputType==="password"?true:false;
				$scope.helpText = "Show";
				$scope.pwdHelpText = function()
				{
					$scope.toggle = !$scope.toggle;

					if($scope.toggle)
					{
						$scope.helpText="Hide";
						$scope.info.inputType="text";
					}
					else
					{
						$scope.helpText="Show";
						$scope.info.inputType="password";
					}
				}

				$scope.pwdChange =function()
				{
					if($scope.info.value && !($scope.info.value.length) && $scope.pwdType)
					{
						$scope.helpText="Show";
						$scope.info.inputType="password";
						$scope.toggle = false;
					}
					$scope.info.userDecision = $scope.info.value;
					//$scope.enableNextBtn();
				}
				$scope.passwordValidate=function()
				{
					$scope.info.errClass = false;
                                        if(typeof $scope.info.value === "undefined")
                                                $scope.info.errClass = "regErr";
					if($scope.info.value){
						var screenName =$scope.info.screenName;
						var index =$scope.info.dindex;
						$scope.info.userDecision = $scope.info.value;
						var bVal = Validate.validatePassword(index,screenName);
						if(bVal)
						{
							UserDecision.store($scope.info.storeKey,$scope.info.value);
						}
					}
				}
			}	
		}
	});
	
	app.directive('relationShipBtn',function () {
		return {
			restrict: 'A',
			replace  : true,
			template : '<div id={{\'reg_\'+field.label}} name={{field.label}} class="fl wid49p cursp"> 				          <div class="txtc" style="padding-top:15px;padding-bottom:15px" > <i ng-click="onBtnClick(fieldName)" class=" cursp regis_sp {{field.icon}}"></i> 				            <div class="f13 fontlig" ng-class="{\'color2\': field.icon, \'color10\':field.icon.indexOf(\'_sel\') == -1 }" >{{field.label}}</div> 				          </div> 				        </div>',
      		scope:{
				field:'=field',
                onBtnClick:'=onClick',
                fieldName:'@fieldName'
			},
		};
	});
	
	app.directive('relationShipWidget',function(){
		return {
			restrict: 'A',
			replace  : true,
			template : '<div class="bg4"> 					      <div class="fullwid"> 					        <div  ng-repeat="fieldName in fieldsOrderBy"> 					         <div relation-ship-btn on-click="onBtnClick" field="fields[fieldName]" field-name={{fieldName}}></div> 					        </div> 					      </div> 					    </div>',
			scope:{
				fields:'=relationshipData',
				onBtnClick:'=onBtnClick',
				fieldsOrderBy:'=orderBy',
				selectedVal:'@'
			},
		};
	});

	app.directive('nextBtn', [function () {
		return {
			restrict: 'A',
			replace  : true,
			template : '<div class="pt20"> 	<div class="rippleParent" >				      	<div id=\'nextBtn\' ng-click="callNext()" 					      	class="js-Next bg7 white lh30 fullwid dispbl txtc lh50" 					      	ng-class={\'bggrey\':enable==\"false\",\'greyRipple\':enable==\"false\",\'pinkRipple\':enable==\"true\"} 					      	 " 					      	> 					    	  	{{label}} 					      	</div>       </div>					</div>',
      		scope:{
				callNext:'=onNext',
				enable:'@',
				numFileds:'@',
                btnLabel:'@',
                bottomSticky:'@'
			},
			controller:function($scope,$element,Constants)
			{
                $scope.label = "Next";
                //If Bottom Aligned is specified and value is false then remove classes
                if(typeof $scope.bottomSticky !=='undefined' && $scope.bottomSticky == 'false'){
                    $element.find('.js-Next').removeClass('posabs').removeClass('btmo');
                    $element.find('.js-Next').parent().removeClass('pt20');
                }
                if( typeof $scope.btnLabel == "string" )
                    $scope.label = $scope.btnLabel;
				$scope.fieldHeight = parseInt($scope.numFileds) *86 + 57;
				$scope.btmHeight =  Constants.getWindowHeight() - 50;
				if($scope.fieldHeight > $scope.btmHeight)
				{
					$scope.btmHeight = $scope.fieldHeight;
				}
			}
		};
	}]);
	app.directive('nextBtnIncomplete', [function () {
		return {
			restrict: 'A',
			replace  : true,
			template : '<div class="pt20"> 					      	<div ng-href="#/s3" ng-click="callNext()" 					      	class="bg7 white lh30 fullwid dispbl txtc lh50 posabs btmo" 					      	ng-class={\'bggrey\':enable==\"false\"} 					      	 " 					      	> 					    	  	Complete your profile					      	</div>       					</div>',
      		scope:{
				callNext:'=onNext',
				enable:'@',
				numFileds:'@',
			},
			controller:function($scope,Constants)
			{				
				$scope.fieldHeight = parseInt($scope.numFileds) *86 + 57;
				$scope.btmHeight =  Constants.getWindowHeight() - 50;
				if($scope.fieldHeight > $scope.btmHeight)
				{
					$scope.btmHeight = $scope.fieldHeight;
				}
				
			}
		};
	}]);
	app.directive('genderBtn',function(){
		return {
			restrict: 'A',
			replace  : false,
			template : '<div ng-show="gender.show" class="pad19r brdr1" id="gender" name="gender"> 						<div class="brdr12 fullwid"> 							<div ng-click="onBtnClick(\'F\')" class="wid50p txtc fl dispbl pad20 f15"  ng-class={\'bg7\':gender.userDecision==\'F\'}> 							  <div class="txtc wid140" ng-class={\'white\':gender.userDecision==\'F\',\'color2\':gender.userDecision!=\'F\'}> 								<div class="icons1 {{gender.ficon}} lh30 padl15">Female</div> 							  </div> 							</div> 							<div ng-click="onBtnClick(\'M\')" class="wid50p txtc fl dispbl pad20 f15 genSplitter"  ng-class={\'bg7\':gender.userDecision==\'M\'}> 							  <div class="txtc wid140" ng-class={\'white\':gender.userDecision==\'M\',\'color2\':gender.userDecision!=\'M\'}> 								<div class="icons1 {{gender.micon}} lh30 padl15">Male</div> 							  </div> 							 </div> 						  <div class="clr"></div> 						</div> 					</div>',
			scope:
			{ 
				gender:'=',
				onBtnClick:'=onBtnClick',
			}
		};
	});
	
	app.directive('resize', function ($window,Constants) {
    return function (scope, element) {
        var w = angular.element($window);
        scope.getWindowDimensions = function () {
            return {
                'h': w.height(),
                'w': w.width(),
                'nextBtnHeight':Constants.getNextBtnHeight(),
                'headerHeight':Constants.getHeaderHeight(),
            };
        };
        scope.$on("$destroy",function( event ) {
            w.unbind('resize');
        });
       
        w.bind('resize', function ($event) {
			scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
            scope.MaxHeight = newValue.h;
				if(scope.screenName=="s7")
				{
					if($(window).width() >$(window).height())
					{
						$("#IncompletePic").css("left","36%");
						$("#outerDivIncomplete").addClass("pad5Incomplete");
						$("#outerDivIncomplete").removeClass("pad18Incomplete");
						$("#CompleteProfileLink").removeClass("pad18Incomplete");
						$("#CompleteProfileLink").addClass("pad5Incomplete");
					}
					else
					{
						$("#IncompletePic").css("left","30%");
						$("#outerDivIncomplete").addClass("pad18Incomplete");
						$("#outerDivIncomplete").removeClass("pad5Incomplete");
						$("#CompleteProfileLink").removeClass("pad5Incomplete");
						$("#CompleteProfileLink").addClass("pad18Incomplete");
					}
				}
				else
				{
					if(scope.screenName.indexOf(1)!=-1)
						scope.fieldsHeight = newValue.h - newValue.headerHeight;
					else
						scope.fieldsHeight = newValue.h - newValue.nextBtnHeight - newValue.headerHeight;
				}
			}, true);
            scope.$apply();
            if(scope.hamOn === false)
			{
				$event.stopPropagation();
			}
        });
		};
	});
	
	app.directive('incompleteTab',function(){
		return {
			restrict : 'A',
			replace  :true,
			template : '<div class="bg1">  						    <div class="pad1">  						      <div class="rem_pad1">  						        <div ng-click=\"back()\" ng-class={\'cursp\':enableBack} class="fl wid20p white"> 						        <i ng-class={\'arow2\':enableBack} class="mainsp " ></i> </div>  						        <div class="fl wid60p txtc white fontthin f16 ">{{label}}</div>  						        <div class="clr"></div>  						      </div>  						    </div>  						  </div>',
			scope:{
				label:'@data',
				back:'=onBack',
				enableBack:'='
			}
		}
	});
    
    app.directive('ngElementReady', ['$timeout',function(timer) {
        return {
            priority: Number.MIN_SAFE_INTEGER, // execute last, after all other directives if any.
            restrict: "A",
            link: function($scope, $element, $attributes) {
                //$scope.$eval($attributes.ngElementReady); // execute the expression in the attribute.
                timer(function(){
                    var ele = angular.element(document.querySelector( '.loader' ));
                    ele.removeClass('simple').removeClass('dark').removeClass('image');
                },500);
                
            }
        };
    }]);
    
    app.directive('regTextField',['$timeout',function(timer){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 					        <div class="pad1"> 					          <div class="pad2" style="cursor:pointer"> 					            <div class="fl reg_wid90"> 					              <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}}</div> 					              <input class="reginp fontlig fullwid" ng-model="info.value"    ng-keyup="onKeyPress($event,info.value)" 					              type={{info.inputType}} 					              class="color11 f15 pt10 fontlig" placeholder={{info.hint}} 					              value={{info.value}}/> 					            </div> 										            <div ng-if="info.errClass" class="fr wid8p pt8"><i class="mainsp reg_errorIcon"></i> </div> 					            <div class="clr"></div> 					          </div> 					        </div> 				        </div>',
			scope :{
					info :'=data',
          onNext:'=',
          validationType:'@'          
				},
			controller:function($scope,$element,$window,Constants,$timeout,UserDecision)
			{
				$scope.onKeyPress = function(event,val)
				{
          if(event.keyCode == '13')
          {
              $scope.onNext();
              return;
          }
          $scope.info.userDecision = val;
          UserDecision.store($scope.info.storeKey,$scope.info.userDecision);
				}
        //Validation
        $scope.$watch('info.value', function(newValue, oldValue){
            if (typeof $scope.validationType != "undefined" && 
            $scope.validationType == "nameField") {
              // Check if value has changes
              var regex = /[^a-zA-Z'. ]+/g;
              var value = newValue;
              value = value.trim().replace(regex,"");

              if(value.trim() != newValue.trim()){
                $scope.info.value = value;
              }
            }
            
            // Do anything you like here
        });
			}	
		}
	}]);



    app.directive('regNameField',['$timeout',function(timer){
		return {
			restrict : 'A',
			replace  : true,
			template : '<div class="brdr1" id={{\'reg_\'+info.label}} name={{info.label}}> 					        <div class="pad1"> 					          <div class="pad2" style="cursor:pointer"> 					            <div class="fl wid50p"> 					              <div class="color8 f12 fontlig {{info.errClass}}">{{info.label}}</div> 					              <input class="reginp fontlig fullwid" ng-model="info.value"    ng-keyup="onKeyPress($event,info.value)" ng-keyup="enableNext()" 					              type={{info.inputType}} 					              class="color11 f15 pt10 fontlig" placeholder={{info.hint}} 					              value={{info.value}}/> 			    	            </div>  <span id="showAll" rel="N" orel="N" class="fr fontlig pt15 " onclick="CalloverlayName(this);">       <span id="showText" class="vTop padr5 f14">Show to All</span><i class="iconImg2 iconSprite"></i>               </span>  	 										            <div ng-if="info.errClass" class="fr wid8p pt8 mrr10"><i class="mainsp reg_errorIcon"></i> </div> 					            <div class="clr"></div> 					          </div> 					        </div> 				        </div>',
			scope :{
					info :'=data',
          onNext:'=',
          validationType:'@'          
				},
			controller:function($scope,$element,$window,Constants,$timeout,UserDecision)
			{
				$scope.onKeyPress = function(event,val)
				{
          if(event.keyCode == '13')
          {
              $scope.onNext();
              return;
          }
          $scope.info.userDecision = val;
          UserDecision.store($scope.info.storeKey,$scope.info.userDecision);
				}
        //Validation
        $scope.$watch('info.value', function(newValue, oldValue){
            if (typeof $scope.validationType != "undefined" && 
            $scope.validationType == "nameField") {
              // Check if value has changes
//              var regex = /[^a-zA-Z'., ]+/g;
  //            var value = newValue;
    //          value = value.trim().replace(regex,"");

      //        if(value.trim() != newValue.trim()){
        //        $scope.info.value = value;
         //     }
            }
            
            // Do anything you like here
        });
			}	
		}
	}]);
app.directive('regCasteNoBarField',function(Gui){
                return {
                        restrict : 'A',
                        replace  : true,
                        template : '<div class="brdr1" id={{"reg_casteNoBar"}} name={{"reg_casteNoBar"}}><div class="pad1"><div class="pad2"><div class="fl reg_wid90 casteNoBar_check"><input type="checkbox" ng-model="info.value" ng-click="optionSelected()" id="casteNoBar_check" /><label class="fontlig" for="casteNoBar_check">{{info.label}}</label></div><div class="clr"></div></div></div></div>',
                        scope :{
                                        info :'=data',
                                },
                        controller:function($scope)
                        {       
                                var updateData = true;
                                if($scope.info.value == "true") {
                                        $scope.info.value = true;
                                }
                                $scope.optionSelected = function() 
                                {
                                        setTimeout(function () {
                                                var output={};
                                                output["casteNoBar"] = {"label":'casteNoBar',"value":$scope.info.value};
                                                Gui.updateGuiFields('s4',5,output);
                    }, 50);
                                                
                                }
                        }       
                }
        });

})()
function CalloverlayName(thisObject){
        var selectedVal = $(thisObject).attr("rel");
        var tickSelectedShow = "tickSelected iconSprite";
        var tickSelectedNoShow = "";
        if(selectedVal == 'N'){
             tickSelectedNoShow = tickSelectedShow;
             tickSelectedShow = '';
        }
        overlayNameTemplate=$("#nameSettingOverlay").html();
        $("#nameSettingOverlay").html('');
        overlayNameTemplate=overlayNameTemplate.replace(/\{\{tickSelectedShow\}\}/g,tickSelectedShow); 
        overlayNameTemplate=overlayNameTemplate.replace(/\{\{tickSelectedNoShow\}\}/g,tickSelectedNoShow);
        $("#nameSettingOverlay").append(overlayNameTemplate);
        $("#nameSettingOverlay").removeClass('dn');
        $("#nameSettingOverlay").css("min-height",screen.height);
        NameOverLayerAnimation();
}
function NameOverLayerAnimation(close)
{       
        if (close)
        {
                $("#nameSettingOverlay").removeClass("top_2").addClass('top_3');
                setTimeout(function () {
                        $("#nameSettingOverlay").addClass("dn").removeClass("top_3").css("margin-top", "").addClass("top_1");
                }, animationtimer3s);
        } else
        {
                var height = $("#nameSettingOverlay").outerHeight();
                var sh = Math.floor(($(window).height() - height) / 2);

                $("#nameSettingOverlay").removeClass("dn");
                setTimeout(function () {
                        $("#nameSettingOverlay").removeClass("top_1").css("margin-top", sh).addClass("top_2");
                }, 10);
        }

}
$("document").ready(function () {
		window.displayName = "Y";
        $(document).on("click",".changeSetting",function(){
                $(this).parent().find(".changeSetting i").removeClass("tickSelected iconSprite");
                $(this).find("i").addClass("tickSelected iconSprite");
		window.displayName = $(this).attr('rel');
        });
        $(document).on("click","#doneBtn",function(){
		var showToAll = "Show to All";
		var Dontshow = "Don't Show";
                var selectedVal = $(".changeSetting .iconTick.tickSelected").parent().attr('rel');
                if(selectedVal=='N'){
                        $("#showText").html(Dontshow);
                }else{
                        $("#showText").html(showToAll);
                }
                $("#showAll").attr('rel',selectedVal);
                NameOverLayerAnimation(1);
        });
});

