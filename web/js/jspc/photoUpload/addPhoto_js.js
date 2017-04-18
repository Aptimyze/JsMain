var alreadyPhotoCount=0,picCount=0,getBackLink=0,firstResponse=0,_SEARCH_RESULTS_PER_PAGE=0,imageLoadComplete=0,oldDeletedCount=0,newDeletedCount=0,successfulUploads=0;
var lastAction='';
var nowUploadingTotal=0, nowUploaded=0,errorCount=0;
var fbImportData={};
var fbImportUrls={};
$(document).ready(function() 
{
	$("#cropperPic").attr("src",mainPicUrl);
	lastAction = "LOAD";
	$('body').on('click touchstart', '.js-del', function()
	{
	$(this).parent().parent().remove()
	});
	$("body").on("click",'.js-addPhotoOnError',function()
	{
		showPopupLayer();
	});

	if(alreadyPhotoCount=='')
		alreadyPhotoCount = 0;
	if(maxPhotosPresent())
	{
		disableUpload();
	}
	handleContinueMessage();
	hideLayers();
	showPrivacySetting(photoDisplay);
	addPhotosInFolder();
	showSetProfImages();
	setNoPhotoFrames();
	handleUploadType();
	setChangePhotoButton();
	if(cropper)
	{
		$("body").addClass("scrollhid");
		$(".js-overlay").show();
		$(".js-cropper").show();
	}

	if(typeof(fromCALphoto)!='undefined' && fromCALphoto=='1')
	{	
		 $('html, body').animate({
        scrollTop: $("#photoPrivacyDiv").offset().top
    }, 500);

     }
	
});

function handleUploadType()
{
	if(uploadType=="C"||uploadType=="F")
	{
		showPopupLayer();
	}
}
$(function()
{
	$("body").on("click",'.js-fbPhoto',function()
	{
		toggleTick($(this));
	});
	$(".pudropdown dt span").click(function() 
	{
                $(".pudropdown dd ul").toggle();
		  $(".js-arrow").toggle();
	});
	$(".pudropdown dd ul li").click(function() 
	{
		var text = $(this).html();
		var option = $(this).attr("data-toSave");
		var selected = $(".pudropdown dt span").attr("data-selected");
		if(selected == option)
		{
			showPrivacySetting(option);
			return;
		}
                $("#js-ph-lT").append('<img src="/images/loader_extra_small.gif" class="fr">');
		var randomnumber=Math.floor(Math.random()*11111);
		var url="/api/v1/social/changePhotoPrivacy";
		var postParams = "photo_display="+option+"&rnumber="+randomnumber+"&json=1";
		$.myObj.ajax
		({
			url: url,
			type: "GET",
			data: postParams,
			datatype: 'json',
			async: true,
			success: function (res) {
				if(res.responseMessage=="Successful")
					showPrivacySetting(res.output);
			}
		});
	});
});
function showPrivacySetting(res)
{
  var text = $("#privacy"+res).html();
  $(".js-arrow").hide();
                if(res == "C")
                        $("#note").css('visibility','visible');
                else
                        $("#note").css('visibility','hidden');
  $(".pudropdown dt span").attr("data-selected",res);
  $(".pudropdown dt span").html(text);
  $(".pudropdown dd ul").hide();                
}
function showPopupLayer()
{
		if(!maxPhotosPresent())
		{
			$(".js-overlay").show();
			$(".popupLayer").show();
			$("body").addClass("scrollhid");
		}
		else
		{
			lastAction = "MAX_PHOTO";
			handleContinueMessage();
		}
}
function showSelectPhotoLayer()
{
    newPhotoCount = successfulUploads - newDeletedCount;
    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
	if(totalCount>1)
	{	
		$(".js-overlay").show();
		$(".selectPhoto").show();
		showSetProfRecentAddedPics();
		$("body").addClass("scrollhid");
		$("#selectOtherPhoto").hide();
		$(".phsel").removeClass("phsel");
		$(".selectPhotoTick"+profilePicPictureId).addClass("phsel");
	}
}
$(function()
{
	$(".changePhoto").click(function()
	{
		showSelectPhotoLayer();
	});

	$("#addMoreButton").click(function()
	{
		showPopupLayer();
	});
	$(".addFromComp").click(function()
	{
		if(!maxPhotosPresent())
		{
			hideLayers();	
			uploadFromComputer();
		}
		else
		{
			lastAction = "MAX_PHOTO";
			handleContinueMessage();
		}
	});
	$(".addFromFb").click(function()
	{
		if(!maxPhotosPresent())
		{
			hideLayers();	
			window.open("/social/import1?importSite=facebook&import=1&popup=1","Page","menubar=no, status=no, scrollbars=no, menubar=no, width=520, height=570");
			return false;
		}
		else
		{
			lastAction = "MAX_PHOTO";
			handleContinueMessage();
		}
	});
	$("body").on("click",'.js-overlay',function()
	{
		if($(".js-aboveLayer").hasClass('js-cropper'))
			window.location="/social/addPhotos";
		else
			hideLayers();	
	});
	$(".js-cancel").click(function()
	{
		hideLayers();	
		if($(this).hasClass("js-fbCancel"))
		{
			$("#js-addImportAlbum").css('top','0px');
			$("#js-fbCountDiv").html("");
			fbImportData={};
			fbImportUrls={};
		}
	});
	$("#continue").click(function()
	{
		continueToNext();
	});

	$("#skip_continue").click(function()
	{
		continueToNext();
	});
	$("#select").click(function()
	{
		if ($('span.phsel').length) 
		{
			var pictureid = $(".phsel").attr("data-pictureid");
			setProfilePicProcess(pictureid);
		}
	});
	$("#uploadFb").click(function()
	{
		uploadFb();
		hideLayers();	
	});
	$("#js-cropperClose").click(function()
	{
		window.location="/social/addPhotos";
	});
});
function continueToNext()
{
    newPhotoCount = successfulUploads - newDeletedCount;
    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
    if(newPhotoCount>0 && havePhoto!="Y")
    {
	if(totalCount==1)
		window.location="/social/addPhotos?cropper=1";
	else
		showSelectPhotoLayer();//select profile photo
    }
    else
    {
		if(showMyjs==1)
			window.location="/";
		else
			window.location="/profile/viewprofile.php?ownview=1";
				
    }
}
function showCross(ele)
{
	var pictureid = ele.children("i.photocross").attr("data-pictureid");
	$(".dp"+pictureid).show();
}
function hideCross(ele)
{
	var pictureid = ele.children("i.photocross").attr("data-pictureid");
	$(".dp"+pictureid).hide();
}
function deletePhoto(ele)
{
		var pictureid = ele.attr("data-pictureid");
	    newPhotoCount = successfulUploads - newDeletedCount;
	    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
		if(profilePicPictureId==pictureid && totalCount>1)
		{
			showSelectPhotoLayer();
			var message = "To delete a profile photo, please first select another photo as profile photo";
			$("#selectProfilePhotoError").html(message);
			$("#selectOtherPhoto").show();
			return;
		}
		deletionProcess(pictureid);
}
function hideLayers()
{
	$("body").removeClass("scrollhid");
	$("#js-fbCountDiv").html("");
	$(".js-overlay").hide();
	$(".popupLayer").hide();
	$(".recentPhotos").remove();
	$(".selectPhoto").hide();
	$(".js-cropper").hide();
        $(".js-fbImport").hide();
	fbImportData={};
	fbImportUrls={};
}
function addPhotosInFolder()
{
	if(!$.isEmptyObject(photosDetails))
	{
		$.each(photosDetails,function(ind,value)
		{
			var index = pictureids[ind];
			var millisecondsStr='';
			if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1)
			{
				millisecondsStr = new Date().getTime();
				millisecondsStr="?"+millisecondsStr;
			}
			setPhotoInHtm(value+millisecondsStr,index);
			$(".uploadingBar"+index).hide();
			$('.uploading'+index).removeClass("opa50");
		});
	}
}
function setPhotoInHtm(photoUrl,photoid,count)
{
          listHtml ='<li>            <div class="disp-tbl txtc pos-rel imagePreview opa50 uploading{{COUNT}}" style = "background-size:100% 100%;background-image: url({{PHOTO_URL}});">               <i class="cursp sprite2 pos-abs photocross deletePhoto dp{{COUNT}}" id="deletePhoto{{COUNT}}" data-pictureid="{{PICTUREID}}" style="display:none;"></i>              <div class="disp-cell vmid ">                <span id="{{PHOTO_ID}}" ><img  class="vtop pudim1"  oncontextmenu="return false;" galleryimg="NO" src="/profile/ser4_images/transparent_img.gif"></span>                </div>                <div class="pos_abs fullwid stillUploading uploadingBar{{COUNT}}">                        <div class="puwid9 brdr12 pum3">                                <div class="bg_pink mrl0 uploadingPercent{{COUNT}}" style="width:0px;">                                </div>                        </div>                </div>            </div>          </li>';
	listHtml = listHtml.replace(/{{PHOTO_URL}}/g, photoUrl);
	listHtml = listHtml.replace(/userPhoto/g, "userPhoto"+photoid);
	listHtml = listHtml.replace(/{{PHOTO_ID}}/g, photoid);
	pictureid = photoid;
	if(!pictureid)
	{
		pictureid=count;
	}
          listHtml = listHtml.replace(/{{PICTUREID}}/g, pictureid);
	if(!count)
		count=photoid;
          listHtml = listHtml.replace(/{{COUNT}}/g, count);
	  $("#photoFolder").append(listHtml);
	  $("#userPhoto"+photoid).show();
	if(photoid)
	{
		bindCardEvents(photoid);
	}

}
function bindCardEvents(pictureid)
{
        $(".dp"+pictureid).on("click",function()
        {
                deletePhoto($(this));
        });
        $(".uploading"+pictureid).on("mouseenter mouseover",function()
        {
                showCross($(this));
        });
        $(".uploading"+pictureid).on("mouseleave mouseout",function()
        {
                hideCross($(this));
        });
}
function createPreviewTextHtm()
{
	listHtml = $('#previewText').html();
	$("#photoFolder").append(listHtml);
}
function removePreviewText()
{
	$("#photoFolder li#previewTxt").remove();
}
function showSetProfRecentAddedPics()
{
        if(!$.isEmptyObject(compLoadedFinalImagesArr))
        {
                $.each(compLoadedFinalImagesArr,function(index,value)
                {
                        setPhotoInSelectPhotoPreviewHtm(value,index);
                });
        }

}
function showSetProfImages()
{
        if(!$.isEmptyObject(photosDetails))
        {
                $.each(photosDetails,function(ind,value)
                {
			var index = pictureids[ind];
                        setPhotoInSelectPhotoHtm(value,index);
                });
        }
}
function setPhotoInSelectPhotoHtm(value,index)
{
	listHtml = $("#selectPhotoDiv").html();
	listHtml = listHtml.replace(/{{PICTUREID}}/g, index);
	$("#selectPhotoList").append(listHtml);
	var htm = "<img class='pudim3' src='"+value+"'/>";
	$(".selectPhotoLi"+index).children('div').append(htm);
	selectPhotoTickBind(index);
}
function selectPhotoTickBind(index)
{
	$(".selectPhotoLi"+index).click(function()
	{
		$(".phsel").removeClass("phsel");
		$(".selectPhotoTick"+index).addClass("phsel");
	});
}
function setPhotoInSelectPhotoPreviewHtm(value,index)
{
	listHtml = $("#selectPhotoDiv").html();
	listHtml = listHtml.replace(/{{PICTUREID}}/g, index);
	$("#selectPhotoList").append(listHtml);
	var htm = "<img class='pudim3' src='"+value+"'/>";
	$(".selectPhotoLi"+index).children('div').append(htm);
	$('#selectPhotoList li:last-child').addClass("recentPhotos");
	selectPhotoTickBind(index);
}

/* photo Import */
function afterValidateFbAuth()
{
	hideLayers();
	$(".js-overlay").show();
	$(".js-fbImport").show();
	var postParams = 'importSite=facebook&listAlbum=1';
	var infoArr = {};
	infoArr["type"] = "album";
	infoArr["url"] = "/api/v3/social/import";
	sendFbPhotoRequest(postParams,infoArr);

	$("body").on("click",'.js-importAlbum',function(){
		if($(this).hasClass('cursp'))
		{
			//set pointer towards selected import album
			setActiveAlbumPointer(this);
			
			var postParams = 'importSite=facebook&listPhotos='+this.id;
			var infoArr = {};
			infoArr["url"] = "/api/v3/social/import";
			infoArr["type"] = "photos";
			sendFbPhotoRequest(postParams,infoArr);
		}
	});
}

function sendFbPhotoRequest(postParams,infoArr)
{
	var url = infoArr["url"];
	var type = infoArr["type"];
	$.myObj.ajax({
		url: url,
		dataType: 'json',
	        cache: 'false',
                data: postParams,
		tryCount : 0,
		retryLimit : 20,
		showError : false,
		type: 'GET',
		timeout: 60000,
		beforeSend: function( xhr ) {
			if(type=='album')
			{
				$(".js-ImportLoader").show();
				$(".js-ImportLoaderHide").hide();
                                $("#js-addImportAlbum").html('');
                                $("#js-addImportPhotos").html('');
				$("#selectedAlbumPointer").css('top', '30px');
			}
			if(type=='photos')
			{
				$("#photoImportLoader").html('<img src="/images/searchImages/loader_small.gif">');
        	                $("#js-addImportPhotos").html('');
				$(".js-ImportLoader2").show();
				$(".js-addImportPhotos").hide();
				$("#js-addImportAlbum").addClass("js-disabled");
			}
		},
		success: function(response) {
			this.tryCount=0;
			/** reading json of response **/
			$.each(response, function(key, val)
			{
				if (key == 'albums') {
					if(val)
						albumsCount = val.length;
					else 
						albumsCount = 0;
					//load import slider
					if(albumsCount>0)
					{	
						loadImportedPhotosSlider(albumsCount);	
						loadAlbums(val);
					}
					//load albums 
				}
				if (key == 'photos') {
					$("#js-addImportAlbum").removeClass("js-disabled");
					loadPhotos(val,response.active);
				}
				if (key == 'active') {
					var abc =  val;
				}
			});

			if(type=='album')	
			{
				if(albumsCount==0)
				{
					$("#albumImportLoader").html('<div>You have no albums in your facebook account. To add photos from another source <a href="/social/addPhotos?uploadType=F">Click here</a></div>');
				}
				else
				{
				$(".js-ImportLoader").hide();
				$(".js-ImportLoaderHide").show();
				}
			}
			if(type=='photos')
			{
				$(".js-ImportLoader2").hide();
				$(".js-addImportPhotos").show();
			}
			$(".js-importAlbum").addClass('cursp');
			$("#"+response.active).removeClass('cursp');
		},
		error: function(xhr) {
			this.tryCount++;
			if (this.tryCount < this.retryLimit) 
			{
				$.ajax(this);
				return;
			}            
			else
			{
				this.tryCount=0;
				var msg = '<div>Something went wrong, please try again later.</div>';
				$(".js-importAlbum").addClass('cursp');
				if(type=='photos')
				{
					$("#js-addImportAlbum").removeClass("js-disabled");
					$("#photoImportLoader").html(msg);
				}
				if(type=='album')
					$("#albumImportLoader").html(msg);
			}
			return;
		}
	});
}

function loadAlbums(val)
{
	var albumStructure = $("#js-addImportAlbumInd").html();
	var albumOffset = 0;
	$.each(val, function(key1, val1)
	{
		var mapObj = {
			'{importAlbumName}': removeNull(val1.name),
			'{importAlbumCount}': removeNull(val1.count),
			'{importAlbumId}': removeNull(val1.albumId),
			'{importAlbumOffset}': albumOffset
		};
		var albumTuple = $.ReplaceJsVars(albumStructure, mapObj);
		$("#js-addImportAlbum").append(albumTuple);
		var htm = "<img src="+removeNull(val1.url)+" class='pudim3 vtop'/>";
		$("#album"+val1.albumId).append(htm);
		++albumOffset;
	});
}

function loadPhotos(val,albumDiv)
{
	var albumPosition = $("#"+albumDiv).parent().index();
        var albumStructure = $("#js-addImportPhotosInd").html();

        $("#js-addImportPhotos").html('');
	var cnt = 0;
        $.each(val, function(key1, val1)
        {
                var mapObj = {
			'{photo_number}':removeNull(key1),
                        '{saveUrl}': removeNull(val1.save),
                };
                var albumTuple = $.ReplaceJsVars(albumStructure, mapObj);
                $("#js-addImportPhotos").append(albumTuple);
		var htm = "<img src="+removeNull(val1.display)+" class='pudim5 brdr-1'/>";
		if (key1 == 0)
		{
			$(".photonumber").append(htm);
		}
		else
		{
			$(".photonumber"+key1).append(htm);
		}
		cnt++;
        });
	if(fbImportData["'"+albumPosition+"'"])
	{
		$.each(fbImportData["'"+albumPosition+"'"], function(k,v)
		{
			var pos=parseInt(k.replace(/'/g,""))+1;
			$("#js-addImportPhotos li:nth-child("+pos+")").children("i").addClass("phsel");
		});
	}
}
/* photo Import */
