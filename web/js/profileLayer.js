function saveProfilePic(pic_id,type,source)
{
	if (type == "done")
	{
		document.getElementById("output_layer_data1").innerHTML="<div style=\"text-align:center;\" valign=\"middle\"><img src = \"IMG_URL/images/loadingAnimation.gif\" /><br />Please Wait</div>";
		parent.location.href= "/social/viewAllPhotos/none";
	}
	else{
	var x1 = document.getElementById("x1").value;
	var y1 = document.getElementById("y1").value;
	var x2 = document.getElementById("x2").value;
	var y2 = document.getElementById("y2").value;
	var width = document.getElementById("width").value;
	var height = document.getElementById("height").value;
	var params = pic_id+","+x1+","+y1+","+x2+","+y2+","+width+","+height+","+type+","+source;
	var url = "/social/saveProfilePic/"+params;
	if (type == "profile")
	{
		document.getElementById("profile_layer_data").innerHTML="<table width = \"690\" height = \"420\" align = \"center\"><tr><td valign = \"center\"><center><img src = \"IMG_URL/images/loader_big.gif\" /><br /><br />Please Wait</center></td></tr></table>";
	}
	else if (type == "thumbnail")
	{
		document.getElementById("profile_layer_data").style.display = "none";
		document.getElementById("thumbnail_layer_data").style.display = "none";
        	document.getElementById("loader_layer_data").style.display = "block";
	}
	else
	{
	}
	sendRequest('GET',url);
	}
}

function skipLayer()
{
	document.getElementById("profile_layer_data").innerHTML="<table width = \"690\" height = \"420\" align = \"center\"><tr><td valign = \"center\"><center><img src = \"IMG_URL/images/loader_big.gif\" /><br /><br />Please Wait</center></td></tr></table>";
}

function exitLayer()
{
	parent.$.colorbox.close();
}

function exitLayer1()
{
	parent.location.href= "/social/viewAllPhotos/none";
}

function onEndCrop( coords, dimensions ) {
                        $( 'x1' ).value = coords.x1;
                        $( 'y1' ).value = coords.y1;
                        $( 'x2' ).value = coords.x2;
                        $( 'y2' ).value = coords.y2;
                        $( 'width' ).value = dimensions.width;
                        $( 'height' ).value = dimensions.height;
                }

function cropper() 
{ 
        new Cropper.ImgWithPreview( 'testImage',
    	{ 
   			minWidth: 75, 
       		minHeight: 100,
			maxWidth : 230,
			maxHeight : 310, 
          	height : 310,
			Width : 230,
			ratioDim: { x: 150, y: 200 },
           	displayOnInit: false, 
         	onEndCrop: onEndCrop,
           	previewWrap: 'previewArea'
      	})
}

function cropper1() 
{ 
   	new Cropper.ImgWithPreview( 'testImage',
    	{ 
       		minWidth: 60, 
                minHeight: 60,
                ratioDim: { x: 60, y: 60 },
                displayOnInit: false, 
                onEndCrop: onEndCrop,
                previewWrap: 'previewArea'
     	}) 
}
