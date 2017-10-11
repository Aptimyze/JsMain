(function(){
	'use strict';
	var app = angular.module('regApp.services',['ngResource','regApp.factories']);

	app.factory('Register',function($resource,Constants,ApiData){
		var urlPage1 = Constants.getBaseUrl() + 'newJsmsPage1';
		var urlPage2 = Constants.getBaseUrl() + 'newJsmsPage2';
		var urlPage3 = Constants.ApiBaseUrl() + 'page3';
		return $resource(urlPage1,{},{
			page1 : {
				url:urlPage1,
				method:'POST',
				cache:false,
				params:ApiData.getApiData('1'),
				headers:{'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
				'Accept': 'application/json'},
				timeout:30000
			},
			page2 : {
				url:urlPage2,
				method:'POST',
				cache:false,
				params:ApiData.getApiData('2'),
				headers:{'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
				'Accept': 'application/json'},
				timeout:30000
			},
            page3 : {
                url:urlPage3,
                method:'POST',
                cache:false,
                params:ApiData.getApiData('3'),
                headers:{'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
                'Accept': 'application/json'},
                timeout:30000
            },
		});
	});
	app.factory('Incomplete',function($resource){
		var urlPage1 = '/api/v1/profile/editprofile?sectionFlag=incomplete';
		
		return $resource(urlPage1,{},{
			incompleteApi : {
				url:urlPage1,
				method:'GET',
				cache:false,
				headers:{'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
				'Accept': 'application/json'},
				},
		});
	});
    app.factory('DevTrack',function($resource,Constants){
		var urlPage = Constants.getBaseUrl() + 'trackJsmsReg';
		return $resource(urlPage,{},{
            UpdateTrack : {
                url:urlPage,
                method:'POST',
                cache:false,
                headers:{'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
				'Accept': 'application/json'},
                params:{info:'@Info'}
            }    
		});
	});
})();
