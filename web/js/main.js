/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if('serviceWorker' in navigator) 
{
    var config = {
    	messagingSenderId: "323372390615" 
  };
  firebase.initializeApp(config);
  const messaging = firebase.messaging();
  var url = ssl_siteUrl+"/js/sw_fcm.js"; 


  if(Notification.permission === 'default' || Notification.permission === 'granted')
  {
  navigator.serviceWorker.register(url) 
          .then((registration) => {
             setTimeout(function() {
              registration.update();  // update the service worker
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
                                    $("#permissionResponse").html("Notifications enabled for this site");
                                    window.close();
                                }
                            });
              })
              .catch(function (err) {
              });
          }, 1000);
        });
 }
 
else {
    $("#permissionResponse").html("Please enable blocked notifications for this site in chrome://settings");
        setTimeout(function(){window.close();},10000);
    }
}