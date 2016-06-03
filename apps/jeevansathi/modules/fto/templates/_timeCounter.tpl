<script type="text/javascript">
var expiryDateReceived = "~$expiryDate`";
var currentDateReceived = "~$currentDate`";
var DateRegexIST = /([^-]*)-([^-]*)-([^-]*)T([^-]*)\:([^-]*)\:([^-]*)\+([^-]*)\:([^-]*)/;
var DateRegexEST = /([^-]*)-([^-]*)-([^-]*)T([^-]*)\:([^-]*)\:([^-]*)\-([^-]*)\:([^-]*)/;
var ExpiryDateRegexString = expiryDateReceived.match(DateRegexEST);
if (!ExpiryDateRegexString) {
  ExpiryDateRegexString = expiryDateReceived.match(DateRegexIST);
}
var CurrentDateRegexString = currentDateReceived.match(DateRegexEST);
if (!CurrentDateRegexString) {
  CurrentDateRegexString = currentDateReceived.match(DateRegexIST);
}
var expiry;
var current;
$(function () {
    if($.browser.msie) {
    expiry = new Date(ExpiryDateRegexString[1], 
      ExpiryDateRegexString[2] - 1,  
      ExpiryDateRegexString[3],
      ExpiryDateRegexString[4],
      ExpiryDateRegexString[5],
      ExpiryDateRegexString[6]); 
    current = new Date(CurrentDateRegexString[1],
      CurrentDateRegexString[2] - 1,
      CurrentDateRegexString[3],
      CurrentDateRegexString[4],
      CurrentDateRegexString[5],
      CurrentDateRegexString[6]);
    } else {
    expiry = new Date(expiryDateReceived);
    current = new Date(currentDateReceived);
    }
    if ('~$fromOfferPage`' != 'D') { 
    $.extend($.countdown, {
_sectionClass: "fto-txt-timer mar8left", 
_fontSizeClass: "", 
_widthStyle: "",
_marginLeft: "mar5left"
});
    }
    else {
      $.extend($.countdown, {
_sectionClass: "fto-txt-timer mar16left", 
_fontSizeClass: "fs16", 
_widthStyle: "width: 60px;",
_marginLeft: "mar12left"
});
}
$("#countdown").countdown({until: expiry, currentDate: current, significant: 4, serverSync: syncServerTime, alwaysExpire: true, onExpiry: function() {
    
    $("#altCountDown").css("display", "block");
    $("#countdown").remove();
    $("#timeLeft").remove();
    }
    });
});

function syncServerTime() {
  return new Date(current);
}
</script>

<div id="countdown">
</div>

