function showTab(thisForm,pageSource)
{
		//alert(document.getElementById("bride").value);
		if(pageSource == 'N')
		{
			document.getElementById("bride").className = "js-tab-open w50";
			document.getElementById("groom").className = "js-tab-close w50";
		//	document.getElementById("groom").style.border="2px solid #b0b0b0";
			//document.getElementById("groom").style.color="#7f7f7f";
			document.getElementById("rightProfiles").style.display="none";
			
			document.getElementById("groom").style.visibility="visible";
		}
		else if(pageSource == 'B')
		{
			document.getElementById("groom").style.display = "none";	
		}
		else if(pageSource == 'G')
		{
			document.getElementById("bride").style.display = "none";	
		}
}
function changeTab(clickedId)
{
	var otherId;
	if(clickedId == "bride")
	{
		otherId = document.getElementById("groom");
		document.getElementById("leftProfiles").style.display="inline";
		document.getElementById("rightProfiles").style.display="none";
	}
	else
	{
	    otherId = document.getElementById("bride");
	    document.getElementById("rightProfiles").style.display="inline";
	    document.getElementById("leftProfiles").style.display="none";
	}
	
	clickedId = document.getElementById(clickedId);
	clickedId.className = "js-tab-open w50";
	//clickedId.style.border="";
	//clickedId.style.color="";
	otherId.className = "js-tab-close w50";
	//otherId.style.border="2px solid #b0b0b0";
	//otherId.style.color="#7f7f7f";	
}
