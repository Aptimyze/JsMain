/**
* @author Lavesh Rawat & Pankaj Khandelwal
* Please do not modify without consulting the Auther of this file.
*/
(function($) {
  $.myObj = {

    ajax: function(objConfig) {

      $.ajax({
        type: objConfig.type, 
        url: objConfig.url,
        headers: (objConfig.headers != undefined)?objConfig.headers:{}, 
        async: (objConfig.async != undefined)?objConfig.async:true,
        data : (objConfig.data != undefined)?objConfig.data:{},
        dataType : (objConfig.dataType != undefined)?objConfig.dataType:'json',
        cache: (objConfig.cache != undefined)?objConfig.cache:false,
        processData: (objConfig.processData != undefined)?objConfig.processData:true,
        context: objConfig,
        contentType: (objConfig.contentType != undefined)?objConfig.contentType:'application/x-www-form-urlencoded',
        timeout: (objConfig.timeout != undefined)?objConfig.timeout:'30000',
        showError: (objConfig.showError != undefined)?objConfig.showError:true,
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
        $("#commonError").slideDown("slow");
        setTimeout('$("#commonError").slideUp("slow")',1500);
      }
        if ( $.isFunction(objConfig.error) ) {
          objConfig.error(data, objConfig.context)   
        }
    }
});
}
}
}(jQuery));
