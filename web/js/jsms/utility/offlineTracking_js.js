(function(){
  $(document).ready(function() {
      $(window).bind("offline", function() {
          ShowTopDownError(["Your are offline."]);
          ~if $trackProfileId`
            localStorage.setItem("offline","~$trackProfileId`");
          ~/if`
      });
      $(window).bind("online", function() {
          ShowTopDownError(["You are now online."]);
          var offlineData = localStorage.getItem("offline");
          console.log(offlineData);
          if(offlineData) {
              trackJsEventGA("jsms","offline",offlineData);
              trackJsEventGA("jsms","online",offlineData);
          }
      });
  });
})();