var page = require('webpage').create();

var lastReceived = new Date().getTime();
var requestCount = 0;
var responseCount = 0;
var requestIds = [];
var startTime = new Date().getTime();
page.settings.userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 Phantomjs';

page.settings.loadImages = false;
var system = require('system');
var address = system.args[1];

// page.onResourceRequested = function (request) {
//     console.log("The new url is: "+request.url);
//     var hash = location.split('#')[1] || '';
//     if(hash) 
//     {

//         hash = "http://192.168.183.75/" + hash;
//         console.log("changed url: "+hash);
//         request.changeUrl(hash);
//     }
//     system.stderr.writeLine('= onResourceRequested()');
//     system.stderr.writeLine('  request: ' + JSON.stringify(request, undefined, 4));
// };
 
// page.onResourceReceived = function(response) {
//     system.stderr.writeLine('= onResourceReceived()' );
//     system.stderr.writeLine('  id: ' + response.id + ', stage: "' + response.stage + '", response: ' + JSON.stringify(response));
// };
 
// page.onLoadStarted = function() {
//     system.stderr.writeLine('= onLoadStarted()');
//     var currentUrl = page.evaluate(function() {
//         return window.location.href;
//     });
//     system.stderr.writeLine('  leaving url: ' + currentUrl);
// };
 
// page.onLoadFinished = function(status) {
//     system.stderr.writeLine('= onLoadFinished()');
//     system.stderr.writeLine('  status: ' + status);
// };
 
// page.onNavigationRequested = function(url, type, willNavigate, main) {
//     system.stderr.writeLine('= onNavigationRequested');
//     system.stderr.writeLine('  destination_url: ' + url);
//     system.stderr.writeLine('  type (cause): ' + type);
//     system.stderr.writeLine('  will navigate: ' + willNavigate);
//     system.stderr.writeLine('  from page\'s main frame: ' + main);
// };
 
// page.onResourceError = function(resourceError) {
//     system.stderr.writeLine('= onResourceError()');
//     system.stderr.writeLine('  - unable to load url: "' + resourceError.url + '"');
//     system.stderr.writeLine('  - error code: ' + resourceError.errorCode + ', description: ' + resourceError.errorString );
// };
 
page.onError = function(msg, trace) {
    system.stderr.writeLine('= onError()');
    var msgStack = ['  ERROR: ' + msg];
    if (trace) {
        msgStack.push('  TRACE:');
        trace.forEach(function(t) {
            msgStack.push('    -> ' + t.file + ': ' + t.line + (t.function ? ' (in function "' + t.function + '")' : ''));
        });
    }
    system.stderr.writeLine(msgStack.join('\n'));
};

page.open(address, function () {
    console.log("address is: " + address);
    console.log(page.content);
    phantom.exit();
});
