var page = require('webpage').create();
var system = require('system');

var lastReceived = new Date().getTime();
var requestCount = 0;
var responseCount = 0;
var requestIds = [];
var startTime = new Date().getTime();
page.settings.userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 Phantomjs';
page.settings.loadImages = false;


page.onResourceReceived = function (response) {
    if(requestIds.indexOf(response.id) !== -1) {
        lastReceived = new Date().getTime();
        responseCount++;
        requestIds[requestIds.indexOf(response.id)] = null;
    }
};
page.onResourceRequested = function (request) {
   
    if ((/http:\/\/.+?\.css/gi).test(request['url']) || (request.headers['Content-Type'] == 'text/css') || (/https:\/\/www.google.com\/recaptcha\/api.js/gi).test(request['url']) ) {
        networkRequest.abort();
    }


    if(requestIds.indexOf(request.id) === -1) {
        requestIds.push(request.id);
        requestCount++;
    }
};

page.open(system.args[1], function () {});

var checkComplete = function () {
  
  if((new Date().getTime() - lastReceived > 1000 &&
     requestCount === responseCount) ||
       new Date().getTime() - startTime > 1000)  {
    clearInterval(checkCompleteInterval);
    console.log(page.content);
    phantom.exit();
  }
}

var checkCompleteInterval = setInterval(checkComplete, 1);
