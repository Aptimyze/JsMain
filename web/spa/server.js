// import the webserver module, and create a server
var server = require('webserver').create();

// start a server on port 8080 and register a request listener
server.listen(8090, function(request, response) {

  var page = require('webpage').create();//new WebPage();
  page.settings.userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';

  page.open('http://localhost:8080/login', function (status) {
    if (status !== 'success') {
      console.log('Unable to load the address!');
      phantom.exit();
    } else {
      window.setTimeout(function () {
        // page.render(output);
        // phantom.exit();
        response.statusCode = 200;
        response.write('Upcoming Events in Oxfordshire:\n');
        response.write(page.content);
        response.close();
        }, 1000); // Change timeout as required to allow sufficient time 
    }
  });

  // page.open("http://192.168.183.75:80/login", function(){
  // // page.open("http://localhost:8081/login", function(){


  //   response.statusCode = 200;
  //   response.write('Upcoming Events in Oxfordshire:\n');
  //   response.write(page.content);
  //   response.close();

  //   // We want to keep phantom open for more requests, so we
  //   // don't exit the process. Instead we close the page to
  //   // free the associated memory heap
  //   //
  //   // phantom.exit();

  //   page.close();

  // });
});