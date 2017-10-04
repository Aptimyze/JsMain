/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if('serviceWorker' in navigator) 
{
    var config = {
    messagingSenderId: "209380179960" // replace the id with the infoedge account
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();
  var url = ssl_siteUrl+"/js/sw.js"; 
    
  navigator.serviceWorker.register(url) 
          .then((registration) => {
              registration.update();  // update the new service worker
              messaging.useServiceWorker(registration);
              messaging.requestPermission()
                      .then(function() {
                          return messaging.getToken();
              })
                      .then(function(regId) {
                          var relativeUrl = "/api/v1/notification/insertChromeId";
                          $.ajax({
                                type: 'POST',
                                url: relativeUrl,
                                data:{
                                    regId: regId,
                                },
                                success: function(data) {
                                }
                            });
              })
                      .catch(function (err) {
                          alert(err);
              })
  });
  
}



