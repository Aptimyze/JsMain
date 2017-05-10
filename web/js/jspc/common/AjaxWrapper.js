/**
* @author Lavesh Rawat & Pankaj Khandelwal
* This file is included in mobile site too.
* Please do not modify without consulting the Author of this file.
*/
(function($) {
  $.myObj = {
    ajax: function(objConfig) {
      //console.log(_rID);
      if (typeof _rID != "undefined" && typeof objConfig.headers != "undefined") {
          objConfig.headers['RID_AJAX'] = _rID;
          var str=_rID + " is the Request id and the ajax header id is " + objConfig['RID_AJAX'] + "\n";
      }
      if ( typeof objConfig.headers != "undefined" )
      {
        objConfig.headers['X-Requested-By'] = 'jeevansathi';
      }
      /*
       * Done for chat because of undefined _rID erroron hide profile page.
       */
      if(typeof _rID == "undefined"){
          _rID = '';
      }
      var oldUrl = window.location.href;
      $.ajax({
        type: objConfig.type, 
        url: objConfig.url,
        headers: (objConfig.headers != undefined)?objConfig.headers:{'RID_AJAX':_rID,'X-Requested-By':'jeevansathi'}, 
        async: (objConfig.async != undefined)?objConfig.async:true,
        data : (objConfig.data != undefined)?objConfig.data:{},
        dataType : (objConfig.dataType != undefined)?objConfig.dataType:'json',
        cache: (objConfig.cache != undefined)?objConfig.cache:false,
        processData: (objConfig.processData != undefined)?objConfig.processData:true,
        context: objConfig,
        contentType: (objConfig.contentType != undefined)?objConfig.contentType:'application/x-www-form-urlencoded',
        timeout: (objConfig.timeout != undefined)?objConfig.timeout:'30000',
        showError: (objConfig.showError != undefined)?objConfig.showError:true,
        updateChatList: (objConfig.updateChatList != undefined)?objConfig.updateChatList:false,
        updateChatListImmediate: (objConfig.updateChatListImmediate != undefined)?objConfig.updateChatListImmediate:false,
        updateNonRosterChatGroups: (objConfig.updateNonRosterChatGroups != undefined)?objConfig.updateNonRosterChatGroups:null,
        beforeSend: function() {
         /** add common code **/
         if ( $.isFunction(objConfig.beforeSend) ) {
           objConfig.beforeSend(objConfig.context);
         }
       },
       complete: function(data,textStatus, xhr){
        if ( $.isFunction(objConfig.complete) ) {

          objConfig.complete(data, objConfig);
        }
        //console.log("before complete",objConfig.updateChatListImmediate,oldUrl,window.location.href);
        //update non roster chat list
        if ( typeof reActivateNonRosterPolling !== 'undefined')
        {
        if($.isFunction(reActivateNonRosterPolling) && ((window.location.href != oldUrl) || objConfig.updateChatList == true || objConfig.updateChatListImmediate == true)){
          reActivateNonRosterPolling("ajax",objConfig.updateChatListImmediate,objConfig.updateNonRosterChatGroups);
        }
        }
      },

      success: function(data, textStatus, xhr) {
       /** add common code **/
       if(data.responseStatusCode == 9)
       {
        var url = "";
        url = window.location.href;
        //console.log("/static/logoutPage?redirectUri="+url);
        window.location.href = "/static/logoutPage?redirectUri="+url;
        

      }
      if(data.responseStatusCode == 7 || data.responseStatusCode == 8)
        location.reload();
      if ( $.isFunction(objConfig.success) ) {
        objConfig.success(data,objConfig.context);
      }
    },
    error: function(data, textStatus, xhr) {
     /** add common code **/
     if(objConfig.showError==true||typeof objConfig.showError=="undefined")
      { 
        if ( typeof objConfig.channel === 'undefined' || objConfig.channel !== 'mobile' )
          {
            showCustomCommonError("Nothing went wrong. Please try again after some time.",1500);
          }
        }
        if ( $.isFunction(objConfig.error) ) {
          objConfig.error(data, objConfig.context)   
        }
    }
});
}
}
}(jQuery));
