var totalImg=20;
var currentView =1;
var photoURL='/images/jspc/commonimg/loader.gif';
var progress=0;


try
{
    var AlbumControl = {

        chkImagesrc:function(newImageNum){

            var chksrc = $('#photo'+newImageNum).find('img').attr('src');            
            if(chksrc.indexOf('loader')!=-1)
            {
                loadImage("#photo"+newImageNum+" div div img",photoURL[newImageNum-1]); 
            }
        },
        loadAlbumImages: function(param){

            var newImageNum;
            getImageNum =  $('.currentImgS').attr('data-count');            
            if(param=='prev')
            {
                if(getImageNum==1)
                {
                    newImageNum = totalImg;
                }
                else
                {
                    newImageNum = getImageNum -1;
                }
                AlbumControl.chkImagesrc(newImageNum);                
            }
            else
            {
             if(getImageNum == totalImg)
             {
                newImageNum = 1;
            } 
            else
            {
               newImageNum = parseInt(getImageNum) +1;
           }
           AlbumControl.chkImagesrc(newImageNum);
       }


   },
   previous:function()
   {
     if(progress==0 )
     {
        AlbumControl.loadAlbumImages('prev');
        $('.currentImgS').removeClass('currentImgS');
                //eq starts from 0
                $('#photoContainer').prepend($('.albumPhotoImage').eq(totalImg-1)).promise().done(function(){  
                    $('.albumPhotoImage').eq(0).addClass('currentImgS');  
                    AlbumControl.reAjustLeftCal();   
                });
            }   
            
    },
    next:function(){
           if(progress==0 )
           {
            AlbumControl.loadAlbumImages('next');
            $('.currentImgS').removeClass('currentImgS');
            $('#photoContainer').append($('.albumPhotoImage').eq(0)).promise().done(function(){
                $('.albumPhotoImage').eq(0).addClass('currentImgS');
                AlbumControl.reAjustLeftCal();
            }); 
        }           
    },
    reAjustLeftCal: function( ){
        progress=1;
        var imageLeftA = $('#photoContainer').offset().left;
        var wid = $("#photoContainer").width(); 

        for(var loop=1;loop<=totalImg;loop++)
        {
            if(loop<totalImg)
            {
                var toLeft = imageLeftA+(loop)*990;
                var countA = $('.currentImgS').attr('data-count');
                $('.albumPhotoImage').eq(loop).css({"left":toLeft,"display":'none'});
                $("#photoAlbumCaption").text(countA+" / "+totalImg);
            }
            else
            {
                $('.albumPhotoImage').eq(0).css({left:imageLeftA,"display":'none' }).fadeIn('fast',function(){  progress=0;    });
            }
        }
    },
    closeAlbumLayerOne: function(event){

        //check if the click originated for login layer
        if( ($('#login-layer').length ==0) )
        {
            var target = $(event.target).first();

            if( (target.attr('id') == 'commonOverlay') &&  $('#photoLayerMain').css('display') =='block'   )
            {
                 closePhotoAlbum();
            }
            else if( target.hasClass( "js-albumoutlayer" ) == true || (target.hasClass( "js-albumopenlayer2" )) || (target.attr('id') == 'photoAlbumCaption') ||  (target.attr('id') == 'photoAlbumUsername'))
            {
                closePhotoAlbum();
            } 

        }

        
    }
    

};
}
catch(e)
{
    console.log('the exception album '+e);
}

$("#photoAlbumPrev").on('click',function(){

    if(progress==0)
    {
        AlbumControl.previous();
    }
});
$("#photoAlbumNext").on('click',function(){

    if(progress==0)
    {
       AlbumControl.next();
    }
});


//Close Button
$("#photoAlbumClose").on('click',function(){closePhotoAlbum();});

$(document).on('click',function(event){    AlbumControl.closeAlbumLayerOne(event) });  



//********************************************
//Photo Layer Key Handling
//********************************************
function photoLayerKeyHandling(e){
    if(e.keyCode == 37) // left arrow
    {
        $("#photoAlbumPrev").focus().click();
    }
        else if(e.keyCode == 39)    // right arrow
        { 
           $("#photoAlbumNext").focus().click();
       }
        else if(e.keyCode == 27)    // right arrow
        { 
           $("#photoAlbumClose").focus().click();
       }
        else if(e.keyCode == 38)    // right arrow
        { 
           return false;
       }
        else if(e.keyCode == 40)    // right arrow
        { 
           return false;
       }
   }


//********************************************
//Left Adjustment of pictures on button clicks
//********************************************
function LeftAdjustment(direction){
    if(progress==1 || currentView<1 || currentView>totalImg)
        return;
    progress=1;
    var toLeft = $("#photo1").offset().left;
    var wid = $("#photo1").width();
    if(direction=="N"){
        var leftHere = $("#photo1").offset().left-wid;
        currentView++;
        //loadImage("#photo"+currentView+" div div img",photoURL[currentView-1]);
        var nextView = parseInt(currentView)+1;
        loadImage("#photo"+nextView+" div div img",photoURL[nextView-1]);
        
    }
    else{
        var leftHere = $("#photo1").offset().left+wid;
        if(currentView>1)
            currentView--;
    }
    
    
    $("#photoAlbumCaption").text(currentView+" / "+totalImg);
    //nextPrevButtonDisplay(currentView,totalImg);
    
    $("#photo1").animate({left: leftHere},"normal");
    var loop=1;
    
    for(loop=2;loop<=totalImg;loop++){
        var toLeft = leftHere+(loop-1)*990;
        if(loop<totalImg)
        {
            $("#photo"+loop).animate({left: toLeft});
        }
        else if(loop==totalImg)
        {
            $("#photo"+loop).animate({left: toLeft},"normal",function(){progress=0;});
        }
    }

}

/**
  *Next Previous Button Handling
  */
  function nextPrevButtonDisplay(currentView,totalImg){
    if(currentView==1 && totalImg>1){
        $("#photoAlbumPrev").hide();
        $("#photoAlbumNext").show();
    }
    else if(currentView==totalImg){
        $("#photoAlbumNext").hide();
        $("#photoAlbumPrev").show();
    }
    else{
        $("#photoAlbumNext").show();
        $("#photoAlbumPrev").show();
    }
}

//Opening Photo Album main function
function openPhotoAlbum(username,profilechecksum,AlbumCount,hasAlbum){
    totalImg=AlbumCount;
    currentView=1;
    progress=0;
    photoURL='/images/jspc/commonimg/loader.gif';
    if(AlbumCount>0){
        unloadScrollBars();
        var imageInsert='';
        var loop=1;
        for(loop=1;loop<=AlbumCount;loop++){
            imageInsert += "<div class='albumPhotoImage' id='photo"+loop+"' data-count='"+loop+"' style='min-width: 990px; position: absolute;'> <div class='disp-tbl fullwid txtc'><div class='disp-cell vmid js-albumoutlayer' style='height:512px'> <img src='/images/jspc/commonimg/loader.gif' oncontextmenu='return false;' onmousedown='return false;' style='max-width:990px;max-height:512px;'/></div></div></div>";
        }
        $("#photoContainer").append(imageInsert);
        $('#photoContainer').find('#photo1').addClass('currentImgS');

        var loop=1;
        var wid = $("#photo1").width();
        var leftHere = ($(window).width()-990)/2+wid;
        for(loop=2;loop<=totalImg;loop++){
            var toLeft = leftHere+(loop-1)*990;
            $("#photo"+loop).css("visiblity","hidden");
            $("#photo"+loop).animate({left: toLeft},1,function(){ $("#photo"+loop).css("visiblity","visible");});
        }
        $("#photoAlbumPrev").show();
        $("#photoAlbumNext").show();
        if(totalImg<2){
            $("#photoAlbumPrev").hide();
            $("#photoAlbumNext").hide();
        }
        ajaxCallForAlbum(username,profilechecksum);
        $("#commonOverlay").fadeIn();
    }
    else{
        return false;
    }
}

//Close button functionality
function closePhotoAlbum(){
    reloadScrollBars();
    $("#photoLayerMain").hide();
    $("#photoContainer .albumPhotoImage").remove();
    $("#commonOverlay").fadeOut();
}

// Ajax call for photo Album
function ajaxCallForAlbum(username,profilechecksum){
    //profilechecksum = '0653943cda604027fee407dc05f10ff5i27392';
    $.ajax({
        type: "GET",
        url: "/api/v1/social/getAlbum?profileChecksum="+profilechecksum,
        dataType: "json",
        success: function (result, status, xResponse) {
          var noPhotoErrorMsg = "User has recently hidden photo(s) from privacy settings.";
          photoURL = result.albumUrls;
          if(result.albumUrls==null)
          {
            if(result.showConditionalPhotoLayer) //code to show layer for upload photo
            {
               // alert("SHOW LAYER :"+result.showLayer);
                $("#commonOverlay").css('display','block');
                $("#commonOverlay").css('background-color','rgba(0, 0, 0, 0.94)');
                $("#conditionalPhotoLayer").css('display','block');
                $("#commonOverlay").click(function(event){
                    if(!$(event.target).parents('div#actualconditionalLayer').length)
                        closeConditionalLayer();
                });
            }
            else
            {
                closePhotoAlbum();
                //alert("No Album Pc Exists "+result.responseMessage);
                var errorMsg =  $("#js-commonErrorMsg").html();
                $("#js-commonErrorMsg").html(noPhotoErrorMsg);
                $("#commonError").slideDown("slow");
                setTimeout('$("#commonError").slideUp("slow")',1500);
                setTimeout(function(errorMsg){$("#js-commonErrorMsg").html(errorMsg);},4000,errorMsg);
            }
              
           }
                else{
                    totalImg = result.albumUrls.length;
                    currentView=1;
                    $("#photoAlbumCaption").text(currentView+" / "+totalImg);
                    $("#photoAlbumUsername").text(username);
                    
                    loadImage("#photo1 div div img",photoURL[0]);
                    loadImage("#photo2 div div img",photoURL[1]);
                    $("#photoLayerMain").show();
                    //$("#photoAlbumPrev").hide();
                    $("#photoAlbumCaption").text(currentView+" / "+totalImg);
                    $("#photoAlbumUsername").text(username);
                    
                }
            }
        });
}

// Load image for photo Album
function loadImage(idOfDiv,Image){
    $(idOfDiv).attr('src', Image)
    .on('load', function() {
        if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
            $("#commonError").slideDown("slow");
            setTimeout('$("#commonError").slideUp("slow")',1500);
        } else {
        }
    });
}

//Close Button
$("#conditionalLayerClose").on('click',function(){closeConditionalLayer();});

//Close button functionality
function closeConditionalLayer(){
    reloadScrollBars();
    $("#conditionalPhotoLayer").hide();    
    $("#commonOverlay").fadeOut();
}
