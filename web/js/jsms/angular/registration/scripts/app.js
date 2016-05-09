(function(){
'use strict';
	var app = angular.module("regApp",[	'ngTouch',
										'ngRoute',										
										'ngAnimate',
										'ngResource',
										'regApp.factories',
										'regApp.services',
										'regApp.directive',
										'regApp.controllers']);

	//Config
	app.config(function($routeProvider){
		//Routing info
		$routeProvider
        .when('/jeevansathi',
		{
			controller 	:	'SplashController',
			templateUrl :	'/angular/registration/Partials/regSplash.html' 
		})
		.when('/s1',
		{
			controller 	:	'CreateForController',
			templateUrl :	'/angular/registration/Partials/createFor.html' 
		})
		.when('/s2',
		{
			controller	:	'PersonalDetailsController',
			templateUrl	:	'/angular/registration/Partials/personalDetails.html'
		})
		.when('/s3',
		{
			controller	:	'CareerDetailsController',
			templateUrl	:	'/angular/registration/Partials/careerDetails.html'
		})
		.when('/s4',
		{
			controller	:	'SocialDetailsController',
			templateUrl	:	'/angular/registration/Partials/socialDetails.html'
		})
		.when('/s5',
		{
			controller	:	'LoginDetailsController',
			templateUrl	:	'/angular/registration/Partials/loginDetails.html'
		})
		.when('/s6/:Incomplete',
		{
			controller	:	'AboutDetailsController',
			templateUrl	:	'/angular/registration/Partials/aboutDetails.html'
		})
		.when('/s7',
		{
			controller	:	'CompleteProfileController',
			templateUrl	:	'/angular/registration/Partials/complete-profile.html',
		})
		.when('/s8',
		{
			controller	:	'IncompleteProfileController',
			templateUrl	:	'/angular/registration/Partials/incomplete.html'
		})
        .when('/s9',
		{
			controller	:	'FamilyDetailController',
			templateUrl	:	'/angular/registration/Partials/familyDetails.html'
		})
        .when('/s10',
		{
			controller	:	'AboutFamilyController',
			templateUrl	:	'/angular/registration/Partials/aboutFamily.html'
		})
		.otherwise({redirectTo : '/s1'});
	});
    app.run(function($rootScope,Gui,$timeout){
       $rootScope.$on("$routeChangeError", function(event, current, previous, rejection) {
            $timeout(function(){
                event.currentScope.$apply(function(){
                event.currentScope.bHardReload = true;
                event.currentScope.serverErrors = []
                event.currentScope.serverErrors[0] = "Not connected to internet. Check your internet connection";
                Gui.showModalWindow(event.currentScope);
             }); 
            });
        }); 
    });

})();
