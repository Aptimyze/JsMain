var totalPics;
var allUrlsArray;
var allTitlesArray;
var allKeywordsArray;

function basicData() 
{
	totalPics = parseInt($.trim($("#totalPhotos").val()));
	allUrlsArray = $.trim($("#allUrls").html()).split("###");
	allTitlesArray = $.trim($("#allTitles").html()).split("###");
	allKeywordsArray = $.trim($("#allKeywords").html()).split("###");
	if(totalPics>0)
	{
		$("#centerAlbumPic").attr("src",$.trim(allUrlsArray[0]).replace(/&amp;/gi,"&"));
                $("#centerAlbumPic").css({"max-width":"500px","max-height":"450px"});
		if($.trim(allTitlesArray[0]) || $.trim(allKeywordsArray[0]))
		{
			$("#titleAndTagsLayer").css("visibility","visible");
			if($.trim(allTitlesArray[0]))	
			{
				//$("#titleSpan").show();
				$("#centerAlbumTitle").html($.trim(allTitlesArray[0]));
			}
			if($.trim(allKeywordsArray[0]))	
			{
				//$("#tagsSpan").show();
				$("#centerAlbumTag").html($.trim(allKeywordsArray[0]));
			}
		}
		else
		{
			$("#titleAndTagsLayer").css("visibility","hidden");
			$("#titleSpan").hide();
			$("#tagsSpan").hide();
		}
	}
}

function previousClickMouseOver()
{
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=1)
	{
		$("#albumPrevious1").hide();
		$("#albumPrevious2").hide();
		$("#albumPrevious3").show();
	}
}
function previousClickMouseOut()
{
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=1)
	{
		$("#albumPrevious1").hide();
		$("#albumPrevious2").show();
		$("#albumPrevious3").hide();
	}
	else
	{
		$("#albumPrevious1").show();
		$("#albumPrevious2").hide();
		$("#albumPrevious3").hide();
	}
}
function previousClick()
{
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=1)
	{
		showLoader();
		$("#currentPhotoNo").html(currentPic-1);
		$("#albumNext1").hide();
                $("#albumNext2").show();
                $("#albumNext3").hide();
		if(currentPic==2)
		{
			$("#albumPrevious1").show();
                	$("#albumPrevious2").hide();
                	$("#albumPrevious3").hide();
		}
		$("#centerAlbumPic").attr("src",$.trim(allUrlsArray[currentPic-2]).replace(/&amp;/gi,"&"));
		if($.trim(allTitlesArray[currentPic-2]) || $.trim(allKeywordsArray[currentPic-2]))
		{
			$("#titleAndTagsLayer").css("visibility","visible");
			if($.trim(allTitlesArray[currentPic-2]))
			{
				//$("#titleSpan").show();
				$("#centerAlbumTitle").html($.trim(allTitlesArray[currentPic-2]));
			}
			if($.trim(allKeywordsArray[currentPic-2]))
			{
				//$("#tagsSpan").show();
				$("#centerAlbumTag").html($.trim(allKeywordsArray[currentPic-2]));
			}
		}
		else
		{
			$("#titleAndTagsLayer").css("visibility","hidden");
			$("#titleSpan").hide();
			$("#tagsSpan").hide();
		}
	}
}

function nextClickMouseOver()
{
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=totalPics)
	{
		$("#albumNext1").hide();
		$("#albumNext2").hide();
		$("#albumNext3").show();
	}
}
function nextClickMouseOut()
{
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=totalPics)
	{
		$("#albumNext1").hide();
		$("#albumNext2").show();
		$("#albumNext3").hide();
	}
	else
	{
		$("#albumNext1").show();
		$("#albumNext2").hide();
		$("#albumNext3").hide();
	}
}
function nextClick()
{ 
	var currentPic = parseInt($.trim($("#currentPhotoNo").html()));
	if(currentPic!=totalPics)
	{
		showLoader();
		$("#currentPhotoNo").html(currentPic+1);
		$("#albumPrevious1").hide();
                $("#albumPrevious2").show();
                $("#albumPrevious3").hide();
		if(currentPic==totalPics-1)
		{
			$("#albumNext1").show();
                	$("#albumNext2").hide();
                	$("#albumNext3").hide();
		}
		$("#centerAlbumPic").attr("src",$.trim(allUrlsArray[currentPic]).replace(/&amp;/gi,"&"));
		if($.trim(allTitlesArray[currentPic]) || $.trim(allKeywordsArray[currentPic]))
                {
                        $("#titleAndTagsLayer").css("visibility","visible");
                        if($.trim(allTitlesArray[currentPic]))
                        {
                                //$("#titleSpan").show();
                                $("#centerAlbumTitle").html($.trim(allTitlesArray[currentPic]));
                        }
                        if($.trim(allKeywordsArray[currentPic]))
                        {
                                //$("#tagsSpan").show();
                                $("#centerAlbumTag").html($.trim(allKeywordsArray[currentPic]));
                        }
                }
                else
                {
                        $("#titleAndTagsLayer").css("visibility","hidden");
                        $("#titleSpan").hide();
                        $("#tagsSpan").hide();
                }
	}
}

function hideLoader()
{
	$("#centerAlbumPic").show();
	$("#imgLoader").hide();
}
function showLoader()
{
	$("#centerAlbumPic").hide();
	$("#imgLoader").show();
}
