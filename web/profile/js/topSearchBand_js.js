function Set_Cookie( name, value, expires, path, domain, secure )
{
        document.cookie = name + "=" +escape( value ) +
        ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
        ( ( path ) ? ";path=" + path : "" ) +
        ( ( domain ) ? ";domain=" + domain : "" ) +
        ( ( secure ) ? ";secure" : "" );
}

function checkData()
{
        var lowage=$("#lage").val();
        var hiage=$("#hage").val();
        document.search_partner.CLICKTIME.value=(new Date()).getTime();
        if((lowage<=hiage))
        {
                Set_Cookie('searchTrackingCookie', '1','' , '/', '', '' );
                return true;
        }
        else
        {
                alert("Lower age limit should be lesser than or equal to higher age limit.For eg. 20 to 25");
                return false;
        }
}

function populateCasteFromReligion()
{
        var religion = $("#religion").val();
        if(religion=="DONT_MATTER" || religion=="")
                var id = "religion0";
        else
                var id = "religion"+religion;

        $("#caste").html($("#"+id).val());
}


function loadXMLDoc(dname)
{
        if (window.XMLHttpRequest)
        {
                xhttp=new XMLHttpRequest();
        }
        else
        {
                xhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.open("GET",dname,false);
        try { xhttp.responseType = "msxml-document"; } catch (e) { };
        xhttp.send("");
        return xhttp.responseXML;
}

function displayResult(searchid,URL,newfoot,field,value)
{ 
        if(!document.getElementById("topSearchBand"))
                return;

	if(!searchid)
		searchid = 0;

	if(newfoot)
		xml=loadXMLDoc(URL+"/search/topSearchBand/N/"+searchid+"?newfooter="+newfoot+"&field="+field+"&value="+value);
	else if(typeof prof_checksum !== 'undefined')
	{ 
		var MYJSParam="";
		if(typeof myJS != "undefined"){ 
			var currentTime = (new Date).getTime();
			MYJSParam = "&time="+currentTime;
		}
		xml=loadXMLDoc(URL+"/search/topSearchBand/N/"+searchid+"?profilechecksum="+prof_checksum+MYJSParam);
        }
        else
	{
            xml=loadXMLDoc(URL+"/search/topSearchBand/N/"+searchid);
        }
        xsl=loadXMLDoc(URL+"/xslt/topSearchBand1.xsl");

        // code for IE
        if (window.ActiveXObject || "ActiveXObject" in window)
        {
                ex=xml.transformNode(xsl);
                document.getElementById("topSearchBand").innerHTML=ex;
        }
        // code for Mozilla, Firefox, Opera, Chrome etc.
        else if (document.implementation && document.implementation.createDocument)
        {
                xsltProcessor=new XSLTProcessor();
                xsltProcessor.importStylesheet(xsl);
                resultDocument = xsltProcessor.transformToFragment(xml,document);
                document.getElementById("topSearchBand").appendChild(resultDocument);
        }
}

//All the header function
$(document).ready(function(){
	$("#head_logout").bind("click", function(){
	logOutCheck(SITE_URL+"/profile/logout.php");
	});
	$("#head_logout").css("cursor","pointer");
	
	$("#closeBand").bind("click", function(){
	setBand(0);
	});
	$("#openBand").bind("click", function(){
	setBand(1);
	});
	if(typeof(showSearchBand)=="undefined")
		showSearchBand=0;
		
	if(showSearchBand)
		setBand(1);
	else
		setBand(0);
});
	var isOpenSB=0;
	
	function setBand (state)
	{
		if(typeof(ishomepage)=="undefined")
			ishomepage=0;
			
		//open/close the header band
		if (state)
		{   
		    if(isOpenSB==0)
			{
				displayResult(searchId,SITE_URL);
				isOpenSB=1;
			}
			if(ishomepage)
			{
			}
			else
			{
				$("#closeBand").show();
				$("#openBand").hide();
			}
			$("#topSearchBand").show();
			$("#advancedSearch").show();
			
		} 
		else 
		{
			if(ishomepage)
			{
				
			}
			else
			{
				$("#closeBand").hide();
				$("#openBand").show();
			}
			$("#topSearchBand").hide();
			$("#advancedSearch").hide();
			

		}
	} 

