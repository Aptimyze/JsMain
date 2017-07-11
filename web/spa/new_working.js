var page = require('webpage').create();
var system = require('system');

var lastReceived = new Date().getTime();
var requestCount = 0;
var responseCount = 0;
var requestIds = [];
var startTime = new Date().getTime();
page.settings.userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';


page.onResourceReceived = function (response) {
    console.log("The onResourceReceived url is: "+response.url);
    console.log("response count: "+responseCount);
    if(requestIds.indexOf(response.id) !== -1) {
        lastReceived = new Date().getTime();
        responseCount++;
        requestIds[requestIds.indexOf(response.id)] = null;
    }
};
page.onResourceRequested = function (request) {
  console.log("request count: "+requestCount);
  console.log("The onResourceRequested url is: "+request.url);
    if(requestIds.indexOf(request.id) === -1) {
        requestIds.push(request.id);
        requestCount++;
    }
};

// Open the page
page.open(system.args[1], function () {});

var checkComplete = function () {
    // console.log(page.content);
  
  // we allow max 2 seconds to evaluate the last script
  // or MAX 10 seconds for the entire site
  if((new Date().getTime() - lastReceived > 10000 &&
     requestCount === responseCount) ||
       new Date().getTime() - startTime > 10000)  {
    clearInterval(checkCompleteInterval);
    console.log(page.content);
    //phantom.exit();
  }
}

/// Let us check to see if the page is finished rendering
var checkCompleteInterval = setInterval(checkComplete, 1);