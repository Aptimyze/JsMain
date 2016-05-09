var prevCat;
//Function to change the cover photo on the edit page
$.fn.image = function(src,pId) {
    return this.each(function() {
        $(".prf-cover1").css('background', '#Dbdbdb url("'+src+'") no-repeat scroll center center / cover ');
    });
}

//Function containing ajax call to save the selected cover photo
function saveSelectedImage(pId){
    url = "/api/v1/profile/coverphoto?saveCover=1"
    $.ajax({
        type: 'POST',
        url: url,
        data: {coverid: pId},
        success: function(response){
            $("#CPImage").image("/images/jspc/viewProfileImg/"+pId.substring(0,2)+"/"+pId+".jpg",pId);
        }
    });
}

//Function contiaing fade out for closing the image gallery and overlay
function closeOverlay(){
    $("#changeCPLayer").fadeOut("fast",function(){
        $("#commonOverlay").fadeOut("fast"); 
    });
}

//Function to close the change cover category menu and display 'change cover photo' option
function closeChangeCoverCatMenu(){
    $("#changeCPMenu").fadeOut("fast",function(){
        $("#changeCP").fadeIn("fast");
    });
}

//On clicking the change cover photo button
function changeCoverPhotoButton(){
    $("#changeCP").fadeOut("fast",function(){
        $("#changeCPMenu").fadeIn("fast"); 
    });
}

//Function to remove the previous selected category option
function removePreviousCatCss(){
    $("#"+prevCat).removeClass("active fontreg disabledTab");
}

//To bind click on the <li> elements which are generated dynamically
function bindClickOnThumbnails(){
    $(".changedCP").on('click',function(){
        var pId = this.id;
        closeOverlay();
        closeChangeCoverCatMenu();
        saveSelectedImage(pId);
        removePreviousCatCss();
    });
}

function generateLiElemenets(jsonUrl,catId){
    $.each(jsonUrl,function(cat, val){
        if(cat == catId){
            var currJsonUrl = val;
            $.each(currJsonUrl, function(picId, url){
                //$("#changeCPLayerul").append('<li><img id="'+picId+'" class="cursp changedCP vtop" src="'+url+'"></li>');
                $("#changeCPLayerul").append('<li id="'+picId+'" style="background-image:url('+url+');" class="coverlisting changedCP cursp"></li>');
            });
        }
    });
}

function generateCoverPhotoCategories(jsonCategories){
    $.each(jsonCategories,function(id, name){
       $("#coverPhotoCatul").append('<li class="cpCat" id="'+id+'">'+name+'</li>'); 
    });
}

function closeOverlayBtn(){
    $("#closebtnedp").on('click',function(){
        closeOverlay();
        removePreviousCatCss();
    });
}

function onClickOutsideGallery(){
    $("#commonOverlay").on('click',function(){
        if(! ($("#commonOverlay").hasClass("js-dClose")) ){
            closeOverlay();
            removePreviousCatCss();
        }
    })
}
$(document).ready(function(){
    //Get the url of all the categories on page load
    var stringUrl = EditApp.staticTables.getData('cover_photo');
    //Parse the above string url to JSON
    var jsonUrl = JSON.parse(stringUrl);
    var coverPhotoCategories = EditApp.staticTables.getData('cover_photo_categories');
    var jsonCoverPhotoCategories = JSON.parse(coverPhotoCategories);
    generateCoverPhotoCategories(jsonCoverPhotoCategories)
    $("#changeCPBtn").click(function(){
        changeCoverPhotoButton();
    });
    
    $("#toggleCPBtn").click(function(){        
        closeChangeCoverCatMenu();
    });
    
    //On click of cover photo categories
    $(".cpCat").on('click',function(){
        var catId = (this).id;
        $("#"+catId).addClass("active fontreg disabledTab");
        if(prevCat){
            removePreviousCatCss();
        }
        //Empty any previous li elements inside the ul.
        $("#changeCPLayerul").empty();
        
        //Generate Li elements in gallery
        generateLiElemenets(jsonUrl,catId);
        //Bind click on Thumbnails
        bindClickOnThumbnails();
        
        //First the overlay appears, then the gallery
        $("#commonOverlay").fadeIn("fast",function(){
            $("#changeCPLayer").fadeIn("slow");
        });
        prevCat = catId;
    });
    
    closeOverlayBtn();
    onClickOutsideGallery();
    $("#CPImage").image(coverPhotoUrl,0);
});