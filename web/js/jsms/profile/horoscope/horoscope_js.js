/**
 * Dom ready function
 * @param {type} param
 */
$(document).ready(function(){
  initHoroScopeiFrame();
});

/**
 * initHoroScopeiFrame
 * @returns {undefined}
 */
var initHoroScopeiFrame = function(){
  //Basic Check for DOM availbility
  if (0 === $('#horoScopeFrame').length){
    return ;
  }
  ajaxInsertAstroPull(0);  
  $('#horoScopeFrame').css('height',window.innerHeight);
}

/**
 * ajaxInsertAstroPull
 * @param {type} retryAttempt
 * @returns {undefined}
 */
function ajaxInsertAstroPull(retryAttempt){
    if(retryAttempt < 3){
        retryAttempt++;
        var url = "/api/v1/profile/horoscope";
        $.myObj.ajax({
            type: 'POST',
            url: url,
            channel : 'mobile',
            data: {update: "update"},
            success: function(response){
                retryAttempt = 0;
            },
            error: function(response){
                ajaxInsertAstroPull(retryAttempt);
            }
        });
    }
    else{
        retryAttempt = 0;
    }
}