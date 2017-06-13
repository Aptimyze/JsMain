function checkIfAllSelected(maxPhotos)
{
	var idName = null;
	var x = 0;
	var nonScreenMore = 0;
	var nonScreenMail = 0;
	var nonScreenPics = 0;
	var picIdScr = new Array();
	var picIdNonScr = new Array();
	var deletePhotoScr = new Array();
	var deletePhotoNonScr = new Array();
	var uploadPhotoScr = new Array();
	var uploadPhotoNonScr = new Array();
	var set_profile_pic = new Array();
	var screenedProfilePicId = document.getElementsByName("screenedProfilePicId")[0].value;
	var source = document.getElementsByName("source")[0].value;
	var profpic = document.getElementById("profpic");
	var thumb = document.getElementById("thumb");

	if (document.getElementsByName("picIdScr[]"))
	{
		picIdScr = document.getElementsByName("picIdScr[]");
	}
	if (document.getElementsByName("picIdNonScr[]"))
	{
		picIdNonScr = document.getElementsByName("picIdNonScr[]");
	}
	if (document.getElementsByName("uploadPhotoScr[]"))
	{
		uploadPhotoScr = document.getElementsByName("uploadPhotoScr[]");
	}
	if (document.getElementsByName("uploadPhotoNonScr[]"))
	{
		uploadPhotoNonScr = document.getElementsByName("uploadPhotoNonScr[]");
	}
	if (document.getElementsByName("deletePhotoScr[]"))
	{
		deletePhotoScr = document.getElementsByName("deletePhotoScr[]");
	}
	if (document.getElementsByName("deletePhotoNonScr[]"))
	{
		deletePhotoNonScr = document.getElementsByName("deletePhotoNonScr[]");
	}
	if (document.getElementsByName("set_profile_pic"))
	{
		set_profile_pic = document.getElementsByName("set_profile_pic");
	}

	//Remove background color
	if (picIdScr)
	{
		for (x=1;x<=picIdScr.length;x++)				//to remove all background colors
		{
			idName = "screenedCounter"+x;
			document.getElementById(idName).style.backgroundColor="#F9F9F9";
		}
	}
	if (picIdNonScr)
	{
		for (x=0;x<picIdNonScr.length;x++)
		{
			idName = "photo"+x;
			document.getElementById(idName).style.backgroundColor="#F9F9F9";
		}
	}
	if (profpic)
	{
		document.getElementById("profilephoto").style.backgroundColor="#F9F9F9";
	}
	if (thumb)
	{
		document.getElementById("thumbnail").style.backgroundColor="#F9F9F9";
	}
	//Removing background color ends
	
	for (x=0;x<deletePhotoScr.length;x++)		//Array manipulation in case file is both browsed and deleted
	{
		if (deletePhotoScr[x].checked)
			uploadPhotoScr[x].value = null;
	}	

	for (x=0;x<deletePhotoNonScr.length;x++)	//Array manipulation in case file is both browsed and deleted
	{
		if (deletePhotoNonScr[x].checked)
			uploadPhotoNonScr[x].value = null;
	}	

	if (uploadPhotoNonScr)			//If non screened photos exist
	{
		for(x=0;x<uploadPhotoNonScr.length;x++)
		{
			if (!uploadPhotoNonScr[x].value)		//If file not browsed
			{
				if (picIdNonScr[x].value.indexOf("ddmore")>=0)		//Add more case
				{
				}
				else if (picIdNonScr[x].value.indexOf("ttach")>=0)		//Attachment case
				{
					if (!deletePhotoNonScr[x].checked)			//File not marked to be deleted
					{
						idName = "photo"+x;
						document.getElementById(idName).style.backgroundColor="#7EB6FF";
						alert("Err2 Image Attachment not browsed/deleted");
						return false;
					}				
				}
				else				//Non screen uploaded pic
				{
					if (!deletePhotoNonScr[x].checked)		//File not marked to be deleted
					{
						idName = "photo"+x;
						document.getElementById(idName).style.backgroundColor="#7EB6FF";
						alert("Err1 NonScreened Image not browsed/deleted");
						return false;
					}
				}
			}
		}
	}	

	if (picIdNonScr)
	{
		for (x=0;x<picIdNonScr.length;x++)		//Loop to get count of different types of non screen pics
		{
			if (picIdNonScr[x].value.indexOf("ddmore")>=0)
				nonScreenMore++;
			else if (picIdNonScr[x].value.indexOf("ttach")>=0)
				nonScreenMail++;
			else
				nonScreenPics++;
		}
	}

	if (set_profile_pic)
	{
		var profilePicCheckedIndex = isElementChecked(set_profile_pic);
		if(profilePicCheckedIndex>=0)			//If profile pic is set
		{
			var isScreenPhotoDeleted = isElementChecked(deletePhotoScr);
			if (isScreenPhotoDeleted>=0)		//If some screened photo is deleted
			{
				if (isValueChecked(set_profile_pic[profilePicCheckedIndex].value,deletePhotoScr))	//If profile pic is marked to delete
				{
					if (countCheckedLength(deletePhotoScr)==picIdScr.length && countCheckedLength(deletePhotoNonScr)>=(nonScreenPics + nonScreenMail) && !isFileBrowsed(uploadPhotoNonScr))			//If no photos are browsed and all are marked to delete
					{
						return true;	
					}
					else		//Profile pic cannot be deleted
					{
						var idNameIndex = parseInt(getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdScr))+1;
						idName = "screenedCounter"+idNameIndex;
						document.getElementById(idName).style.backgroundColor="#7EB6FF";
						alert("Err3...Selected profile pic cannot be deleted. Select another pic as profile pic.");
						return false;
					}
				}	
			}
			
			var isNonScreenPhotoDeleted = isElementChecked(deletePhotoNonScr);
			if (isNonScreenPhotoDeleted>=0)			//If some non screened photo is deleted
                        {
				if (isValueChecked(set_profile_pic[profilePicCheckedIndex].value,deletePhotoNonScr))	//If profile pic is marked to delete
				{
					if (picIdScr)		//If screened pics exist
					{
						if (countCheckedLength(deletePhotoScr)==picIdScr.length && countCheckedLength(deletePhotoNonScr)>=(nonScreenPics + nonScreenMail) && !isFileBrowsed(uploadPhotoNonScr))			//If no photos are browsed and all are marked to delete
						{
							return true;
						}
						else		//Profile pic cannot be deleted
						{
							var idNameIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
                                                	idName = "photo"+idNameIndex;
                                                	document.getElementById(idName).style.backgroundColor="#7EB6FF";
							alert("Err4-1...Selected profile pic cannot be deleted. Select another pic as profile pic.");
							return false;
						}
					}
					else			//If screened pics does not exist
					{
						if (countCheckedLength(deletePhotoNonScr)>=(nonScreenPics + nonScreenMail) && !isFileBrowsed(uploadPhotoNonScr))
						{
							return true;
						}
						else
						{
							var idNameIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
                                                        idName = "photo"+idNameIndex;
                                                        document.getElementById(idName).style.backgroundColor="#7EB6FF";
							alert("Err4-2...Selected profile pic cannot be deleted. Select another pic as profile pic.");
							return false;
						}
					}
				}
			}

			if (source == "mail" || source == "master")		//If its a mail or master scenario
			{
				if (uploadPhotoScr)		//If screened photo exist
				{
					if (set_profile_pic[profilePicCheckedIndex].value == screenedProfilePicId)	//If profile pic is not changed
					{
						if (uploadPhotoScr[0].value)		//If profile pic is browsed
						{
							if (!profpic.value || !thumb.value)	//Cropped profile pic/thumbnail need to be browsed
							{
								document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
								document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
								alert("Err6...Either cropped profile pic or thumbnail is not browsed.");
								return false;
							}
						}
						else				//If profile pic is not browsed
						{
							if (profpic.value || thumb.value)	//Cropped profile pic/thumbnail cannot be browsed
							{
								document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
								document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
								alert("Err5...Invalid cropped profile pic/thumbnail. Reload Page");
								return false;
							}	
						}
					}
					else			//If profile pic is changed
					{
						if (inArray(set_profile_pic[profilePicCheckedIndex].value,picIdScr))	//If new profile pic is in screened section
						{
							var profilePicTypeIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdScr);
							if(!uploadPhotoScr[profilePicTypeIndex].value)	//Browsing not necessary
							{
							}
						}
						else			//If new profile pic is in non screened section
						{
							var profilePicTypeIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
							if(!uploadPhotoNonScr[profilePicTypeIndex].value)	//Browsing required
							{
								var idNameIndex = profilePicTypeIndex;
                                                        	idName = "photo"+idNameIndex;
                                                       		document.getElementById(idName).style.backgroundColor="#7EB6FF";
								alert("Err8...Selected profile pic is not browsed.");
								return false;
							}
						}
						if (!profpic.value || !thumb.value)     //Cropped profile pic/thumbnail need to be browsed
						{
							document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
                                                      	document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
							alert("Err9...Either cropped profile pic or thumbnail is not browsed.");
							return false;
						}
					}
				}
				else		//If screened photo does not exist
				{
					var profilePicTypeIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
					if(!uploadPhotoNonScr[profilePicTypeIndex].value)    //selected profile pic need to be browsed
					{
						var idNameIndex = profilePicTypeIndex;
                                             	idName = "photo"+idNameIndex;
                                             	document.getElementById(idName).style.backgroundColor="#7EB6FF";
						alert("Err10...Selected profile pic is not browsed.");
						return false;
					}
					if (!profpic.value || !thumb.value)     //Cropped profile pic/thumbnail need to be browsed
                                    	{
						document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
                                               	document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
                                          	alert("Err11...Either cropped profile pic or thumbnail is not browsed.");
                                              	return false;
                                    	}
				}
			}
			else if (source == "new" || source == "edit")		//If its a new or edit scenario
			{
				if (picIdScr)		//If screened photo exist
				{
					if (set_profile_pic[profilePicCheckedIndex].value == screenedProfilePicId)      //If profile pic is not changed
					{
						if (profpic.value || thumb.value)       //Cropped profile pic/thumbnail cannot be browsed
						{
							document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
                                                        document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
							alert("Err13...Invalid cropped profile pic/thumbnail. Reload page");
							return false;
						}
					}
					else		//If profile pic is changed
					{
						if (inArray(set_profile_pic[profilePicCheckedIndex].value,picIdScr))    //If new profile pic is in screened section
						{
						}
						else		//If new profile pic is not in screened
						{
							var profilePicTypeIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
							if(!uploadPhotoNonScr[profilePicTypeIndex].value)    //Browsing required
							{
								var idNameIndex = profilePicTypeIndex;
                                                                idName = "photo"+idNameIndex;
                                                                document.getElementById(idName).style.backgroundColor="#7EB6FF";
								alert("Err14...Selected profile pic is not browsed.");
								return false;
							}
						}
						if (!profpic.value || !thumb.value)     //Cropped profile pic/thumbnail need to be browsed
                                        	{
							document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
                                                        document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
							alert("Err15...Either cropped profile pic or thumbnail is not browsed.");
							return false;
						}
					}
				}
				else		//If screened photo does not exists
				{
					var profilePicTypeIndex = getElementIndex(set_profile_pic[profilePicCheckedIndex].value,picIdNonScr);
					if(!uploadPhotoNonScr[profilePicTypeIndex].value)    //selected profile pic need to be browsed
                                        {
						var idNameIndex = profilePicTypeIndex;
                                              	idName = "photo"+idNameIndex;
                                            	document.getElementById(idName).style.backgroundColor="#7EB6FF";
                                                alert("Err16...Selected profile pic is not browsed.");
                                                return false;
                                        }
                                        if (!profpic.value || !thumb.value)     //Cropped profile pic/thumbnail need to be browsed
                                        {
						document.getElementById("profilephoto").style.backgroundColor="#7EB6FF";
                                             	document.getElementById("thumbnail").style.backgroundColor="#7EB6FF";
                                                alert("Err17...Either cropped profile pic or thumbnail is not browsed.");
                                                return false;
                                        }

				}
			}
		}
		else			//If profile pic is not set
		{
			for(x=0;x<uploadPhotoNonScr.length;x++)
                	{
				if (uploadPhotoNonScr[x].value)		//If any non screen pic is browsed
                        	{
					alert("Err12...Select atleast 1 pic as profile pic");
					return false;
				}
			}
		}
	}
 
	var extraPicsCount = checkCount(picIdScr,deletePhotoScr,uploadPhotoNonScr,maxPhotos);
	if (extraPicsCount>0)
	{
		alert("Maximum limit reached. Delete "+extraPicsCount+" photos.");
		return false;
	}
	return true;
}

function inArray(value,chkArray)		//Function to check if a value is present in an array
{
	for(var i=0;i<chkArray.length;i++)
	{
		if (chkArray[i].value == value)
			return true;
	}
	return false;
}

function isValueChecked(value,chkArray)		//Function to check if a value is present in an array as well as it is checked from the front end
{
	for(var i=0;i<chkArray.length;i++)
        {
		if (chkArray[i].checked && chkArray[i].value == value)
			return true;
	}
	return false
}

function isElementChecked(chkArray)		//Function to check if the array has atleast 1 element which is checked from the front end
{
	for (var i=0;i<chkArray.length;i++)
	{
		if (chkArray[i].checked)
			return i;
	}
	return -1;
}

function countCheckedLength(chkArray)		//Function to get number of elements checked in an array
{
	var length = 0;
	for (var i=0;i<chkArray.length;i++)
        {
		if (chkArray[i].checked)
			length++;
	}
	return length;
}

function isFileBrowsed(chkArray)		//Function to check if a file is browsed in the upload array
{
	for (var i=0;i<chkArray.length;i++)
        {
		if (chkArray[i].value)
			return true;
	}
	return false;
}

function getElementIndex(value,chkArray)		//Function to get the element index from the array
{
        for(var i=0;i<chkArray.length;i++)
        {
                if (chkArray[i].value == value)
                        return i;
        }
        return -1;
}

function checkCount(picIdScr,deletePhotoScr,uploadPhotoNonScr,maxPhotos)		//Function to get extra pics uploaded above the max limit
{
	var i = 0;
	var count1=0;
	var delScrLength = 0;
	if (picIdScr)
	{
		if (deletePhotoScr)
		{
			for (i=0;i<deletePhotoScr.length;i++)
        		{
                		if (deletePhotoScr[i].checked)
                        		delScrLength++;
        		}
			count1 = picIdScr.length - delScrLength;
		}
		else
		{
			count1 = picIdScr.length;
		}
	}

	for (var i=0;i<uploadPhotoNonScr.length;i++)
	{
		if (uploadPhotoNonScr[i].value)
			count1++;
	}

	var extraPics = -1;
	if (count1>maxPhotos)
	{
		extraPics = count1-maxPhotos;
	}
	return extraPics;
}

function setProfilePicture(flag)		//Functon to show/hide the profpic and thumbnail browse buttons
{
        if(flag == 'N')
        {
                document.getElementById("profilephotoBlock").style.display='';
                document.getElementById("thumbnailBlock").style.display='';
        }
        else if(flag == 'P')
        {
                if(document.getElementById('scrProfilePicId').value)
                {
                        document.getElementById("profilephotoBlock").style.display='';
                        document.getElementById("thumbnailBlock").style.display='';
                }
                else
                {
                        document.getElementById("profpic").value=null;
                        document.getElementById("thumb").value=null;
                        document.getElementById("profilephotoBlock").style.display='none';
                        document.getElementById("thumbnailBlock").style.display='none';
                }
        }
}

function fileInputDisplay(param)
{
        if(param == 1)
		$("#appPhotoDiv").hide();
        else if(param == 2)
		$("#appPhotoDiv").show();
}

function performChecks(param)
{
	if(param == 1)
	{
		if($('input[name=photoActionRadio]:radio:checked').val() == "Approve")
		{
			return true;
		}
		else if($('input[name=photoActionRadio]:radio:checked').val() == "Edit")
		{
			if($('input[type="file"]').val())
                	{
                        	return true;
                	}
                	else
                	{
                        	alert("Err1 - APP photo not browsed");
                        	return false;
                	}
		}
		else
			return false;
	}
	else if(param == 2)
	{
		if($('input[type="file"]').val())
		{
			return true;
		}
		else
		{
			alert("Err2 - APP photo not browsed");
			return false;
		}
	}
	else
		return false;
}

function ProfileMainAction(perform){

if(perform=="approve")
    {$(".depends").slideDown(200);
        $(".deleteType").prop("checked", false);
    }
else if(perform=="delete")
    {$(".depends").slideUp(200);
        $(".deleteType").prop("checked", true);
        
    }
else if(perform=="retain")
 {$(".depends").slideUp(200);
        //$(".deleteType").prop("checked", true);
       var screenedProfilePicId = document.getElementsByName("screenedProfilePicId")[0].value;
       $('input[name=set_profile_pic][value=screened'+screenedProfilePicId+']').prop("checked", true);
        
    }
};

function TotalMainAction(perform){
    
if(perform=="approve")
    {
        $(".approve").prop("checked", true);
        ProfileMainAction("approve");
    }
else if(perform=="delete")
    {
        $(".delete").prop("checked", true);
        ProfileMainAction("delete");
    }
    
else if(perform=="edit")
    {
        $(".edit").prop("checked", true);
        ProfileMainAction("approve");
    }
};


function checkedRadio(perform){
    $(".approve").prop("checked", false);

}


  $(window).scroll(function(){
      if ($(this).scrollTop() > 185) {
          $('.topDetails').addClass('topDetailsFix');
      } else {
          $('.topDetails').removeClass('topDetailsFix');
      }
  });
