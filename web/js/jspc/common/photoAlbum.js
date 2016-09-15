var totalImg=20;
var currentView =1;
var photoURL='/images/jspc/commonimg/loader.gif';
var progress=0;

//Previous Button
$("#photoAlbumPrev").on('click',function(){
    if($("#photoAlbumPrev:visible").length>0 && progress==0 && currentView>1)
        LeftAdjustment('P');
});

//Next Button
$("#photoAlbumNext").on('click',function(){
    if($("#photoAlbumNext:visible").length>0 && progress==0 && currentView<totalImg)
        LeftAdjustment('N');
});

//Close Button
$("#photoAlbumClose").on('click',function(){
    closePhotoAlbum();
});


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
    nextPrevButtonDisplay(currentView,totalImg);
        
    $("#photo1").animate({left: leftHere},"normal");
    var loop=1;
    
    for(loop=2;loop<=totalImg;loop++){
        var toLeft = leftHere+(loop-1)*990;
        if(loop<totalImg)
            $("#photo"+loop).animate({left: toLeft});
        else if(loop==totalImg)
            $("#photo"+loop).animate({left: toLeft},"normal",function(){progress=0;});
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
            imageInsert += "<div class='albumPhotoImage' id='photo"+loop+"' style='min-width: 990px; position: absolute;'> <div class='disp-tbl fullwid txtc'><div class='disp-cell vmid' style='height:512px'> <img src='/images/jspc/commonimg/loader.gif' oncontextmenu='return false;' onmousedown='return false;' style='max-width:990px;max-height:512px;'/></div></div></div>";
        }
        $("#photoContainer").append(imageInsert);
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

        $("#photoLayerMain").show();
        $("#commonOverlay").fadeIn();
        $("#photoAlbumPrev").hide();
        $("#photoAlbumCaption").text(currentView+" / "+totalImg);
        $("#photoAlbumUsername").text(username);
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
                if(result.albumUrls==null){
		closePhotoAlbum();
                    //alert("No Album Pc Exists "+result.responseMessage);
                showCustomCommonError(noPhotoErrorMsg,1500);

		}
                else{
                    totalImg = result.albumUrls.length;
                    currentView=1;
                    $("#photoAlbumCaption").text(currentView+" / "+totalImg);
                    $("#photoAlbumUsername").text(username);
                    
                   loadImage("#photo1 div div img",photoURL[0]);
                   loadImage("#photo2 div div img",photoURL[1]);
                   
                }
            }
        });
}

// Load image for photo Album
function loadImage(idOfDiv,Image){
    $(idOfDiv).attr('src', Image)
                    .on('load', function() {
                        if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                            showCustomCommonError("Something went wrong. Please try again after some time.",1500);

                        } else {
                        }
                    });
}
