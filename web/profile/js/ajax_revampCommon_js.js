var xmlreqs = new Array(); 
function CXMLReq(type, xmlhttp) 
{ 
	this.type = type; this.xmlhttp = xmlhttp; 
} 
function xmlreqGET(url) 
{ 
	var xmlhttp=false; if (window.XMLHttpRequest) 
	{ 
		xmlhttp=new XMLHttpRequest(); 
		xmlhttp.onreadystatechange = xmlhttpChange; 
		xmlhttp.open("GET",url,true); xmlhttp.send(null); 
	} 
	else if (window.ActiveXObject) 
	{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
		if (xmlhttp) 
		{ 
			xmlhttp.onreadystatechange = xmlhttpChange; 
			xmlhttp.open("GET",url,true); xmlhttp.send(); 
		} 
	} 
	var xmlreq = new CXMLReq('', xmlhttp); xmlreqs.push(xmlreq); 
} 

function xmlreqPOST(url,data) 
{ 
	var xmlhttp=false; 
	if (window.XMLHttpRequest) 
	{ 
		// Mozilla etc. 
		xmlhttp=new XMLHttpRequest(); 
		xmlhttp.onreadystatechange=xmlhttpChange; 
		xmlhttp.open("POST",url,true); 
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		xmlhttp.send(data); 
	} 
	else if (window.ActiveXObject) 
	{ 
		// IE 
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
		if (xmlhttp) 
		{ 
			xmlhttp.onreadystatechange=xmlhttpChange; 
			xmlhttp.open("POST",url,true); 
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			xmlhttp.send(data); 
		} 
	} 
	var xmlreq = new CXMLReq('', xmlhttp); 
	xmlreqs.push(xmlreq); 
} 

function xmlhttpChange() 
{ 
	if (typeof(window['xmlreqs']) == "undefined") 
		return; 
	var xmldoc = null; 
	for(var i=0; i < xmlreqs.length; i++) 
	{ 
		if (xmlreqs[i].xmlhttp.readyState == 4) 
		{ 
			if (xmlreqs[i].xmlhttp.status == 200 || xmlreqs[i].xmlhttp.status == 304) 
			{ 
				if (document.implementation && document.implementation.createDocument) 
				{ 
					xmldoc = document.implementation.createDocument("", "", null); 
				} 
				else if (window.ActiveXObject) 
				{ 
					xmldoc = new ActiveXObject("Microsoft.XMLDOM"); 
				} 
				myjs_checkXmlHttpStatus(xmlreqs[i].xmlhttp.responseText);
				/*
				xmldoc = xmlreqs[i].xmlhttp.responseXML; 
				*/
				xmlreqs.splice(i,1); i--; 
				/*
				handle_response(xmldoc); 
				*/
			} 
			else 
			{ 
				// error 
				xmlreqs.splice(i,1); i--; 
			} 
		} 
	} 
}
function imageOnLoad(username)
{
        eval("var idd=dID('PHOTO_"+username+"');");
        if(idd.src.indexOf("loader_small.gif")==-1)
        {
                idd.style.marginTop='0';
                idd.style.marginLeft='0';
                idd.width='100';
                idd.height='133';
		//astro_icons();
        }
}
function myjs_checkXmlHttpStatus_1()
{
        if (req.readyState != 4)
        {
                return;
        }
        if (req.status == 200)
        {
                var got_responseT = req.responseText.split("$");
                if(got_responseT[1])
                        var got_responseTT=got_responseT[1].split(",");
                var k,i;

                var j='<ul class="lgn_hstry fl" id="lll">';
                j=j + '<li><s>IP Address</s><u>'+ GDATE_TIME_TEXT + '</u></li>'
                for(i=0;i<got_responseT[0];i++)
                {
                        k=got_responseTT[i].split("#");
                        j=j + '<li><s> ' + k[0] + '</s><u>'+ k[1] + '</u></li>';
                }
                j=j + '<li></li></ul><p class="fr m_tr_10">';
                if(got_responseT[3]>=0)
                        j=j + '<a href="#" onClick="myjs_loginhistory(\'S\',' + got_responseT[3] + ');return false;">&lt;Previous</a> ';
                if(got_responseT[2]>=0)
                        j=j + '<a href="#" onClick="myjs_loginhistory(\'S\', ' + got_responseT[2] + ');return false;">Next &gt;</a>';
                j=j + '</p>';
                document.getElementById('lll').innerHTML='';
                document.getElementById('lll').innerHTML=j;
        }
}

function myjs_checkXmlHttpStatus(responseText)
{
	if(responseText)
        {
		if(responseText)
		{
	                var got_responseT = responseText.split("$$$");
			if(got_responseT[0] && got_responseT[1])
			{
				var got_response;
				var idName=got_responseT[0];
				got_response=got_responseT[1].split("%%%");
				if(got_response[0])
				{
					var got_response1 = got_response[0].split("###");
					var str1=got_response1[0] + got_response1[1] + got_response1[2] + '</a>';
					document.getElementById(idName + 0).innerHTML="";
					document.getElementById(idName + 0).innerHTML=str1;
				
				}
				else
					document.getElementById(idName + 0).innerHTML="";

				if(got_response[1])
				{
					var got_response2 = got_response[1].split("###");
					var str2=got_response2[0] + got_response2[1] + got_response2[2] + '</a>';
					document.getElementById(idName + 1).innerHTML="";
					document.getElementById(idName + 1).innerHTML=str2;
				}
				else
					document.getElementById(idName + 1).innerHTML="";

                                if(document.getElementById(idName + 2))
                                {
                                        if(got_response[2])
                                        {
                                                var got_response2 = got_response[2].split("###");
                                                var str2=got_response2[0] + got_response2[1] + got_response2[2] + '</a>';
                                                document.getElementById(idName + 2).innerHTML="";
                                                document.getElementById(idName + 2).innerHTML=str2;
                                        }
                                        else
                                                document.getElementById(idName + 2).innerHTML="";
                                }

			}
			else
			{
				if(document.getElementById(currentClickedId + 0))
					document.getElementById(currentClickedId + 0).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
				if(document.getElementById(currentClickedId + 1))
					document.getElementById(currentClickedId + 1).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
				if(document.getElementById(currentClickedId + 2))
					document.getElementById(currentClickedId + 2).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
			}
		}
		else
		{
			if(document.getElementById(currentClickedId + 0))
				document.getElementById(currentClickedId + 0).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
			if(document.getElementById(currentClickedId + 1))
				document.getElementById(currentClickedId + 1).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
			if(document.getElementById(currentClickedId + 2))
				document.getElementById(currentClickedId + 2).innerHTML='<br><img src="IMG_URL/img_revamp/iconError_16x16.gif"><b>An error has occurred! We will be correcting this problem at the earliest. Kindly check back later.</b>';
		}
	}
}

function includeCSSfile(href)
{
        var head_node = document.getElementsByTagName('head')[0];
        var link_tag = document.createElement('link');
        link_tag.setAttribute('rel', 'stylesheet');
        link_tag.setAttribute('type', 'text/css');
        link_tag.setAttribute('href', href);
        link_tag.setAttribute('name', 'for_home');
        head_node.appendChild(link_tag);
}

function showtrail2(photochecksum,username,e,uniqueIds,big_photo_temp,url,PHOTO_URL)	//Symfony Photo Modification
{
	act_check=uniqueIds;
	eval("dID('IMG_"+act_check+"').style.position='relative'");
	eval("dID('IMG_"+act_check+"').style.zIndex='1002'");
	if(dID(username).style.display!='none')
	return 1;               
	 var mousex=0;
	//var left_shift="95px";
	var left_shift="60px";
	var top_shift="0px";
	var mousey=0;
	if (!e) var e = window.event;
	mousex=e.clientX //to get client window X axis
	virtual_top=e.clientY
	mousey=e.clientY//+document.documentElement.scrollTop//to get client window Y axis
	if(typeof(top.innerHeight)!='undefined')
		var sc_height=top.innerHeight;
	else
		var sc_height=600;

	var total_x=mousex+layer_width
	var total_y=layer_height+mousey+50; // 50px for chat layer
	if(total_y>=sc_height)
	{
		var temp_h=layer_height + 20; // 10 =>20
		temp_h=-1*temp_h+60;
		//if(temp_h<0)
		//      temp_h=temp_h+25;
		top_shift=temp_h+"px";
	}
	if(total_x>=800)
	{
		//now show the layer
		if((mousex-layer_width)<=0)
		{
			//var temp_w=-1*layer_width/2+25;
			var temp_w=-1*layer_width/2;
			left_shift=temp_w+"px";
			if(total_y>=sc_height)
			{
				
				if(temp_h)
				{
					temp_h=temp_h-60;
					top_shift=temp_h+"px";
				}
			}
			else
				top_shift="60px";
			
		}
		else
		{
			var temp_w=layer_width;
			//temp_w=-1*temp_w+25;
			temp_w=-1*temp_w;
			left_shift=temp_w+"px";
		}
	}
	dID(username).style.top=top_shift;
	dID(username).style.left=left_shift;
	dID(username).style.display='inline';
	dID(username).style.zIndex="101";       
	layer_use=username;
	var bigPhoto;
	if(!big_photo_temp)
	{
		bigPhoto = url;				//Symfony Photo Modification
	}
	else
		bigPhoto=big_photo_temp;
        eval("dID('PHOTO_"+username+"').src='"+bigPhoto+"'");
        eval("var idd=dID('PHOTO_"+username+"');");
}
        
function hidetrail2()
{
        if(layer_use)
        {
                dID(layer_use).style.display='none';
                dID(layer_use).style.zIndex="";
                eval("dID('IMG_"+act_check+"').style.position=''");
                eval("dID('IMG_"+act_check+"').style.zIndex='0'");
                layer_use="";
        }

}

function myjs_ajaxValidation(accname,checksum,NEXT_OR_PREVIOUS,loop,total)
{
	currentClickedId=accname;
	if(NEXT_OR_PREVIOUS=='P')
	{
		var to_post = "nextPreviouscrousel=" + accname + "&offset=" + eval(accname+"previousOffset") + "&checksum=" + checksum + "&id=" + id;
		if(accname=='acceptanceacc' || accname=='initialacc')
		{
			eval(accname+"previousOffset-=3");
			eval(accname+"nextOffset-=3");
		}
		else
		{
			eval(accname+"previousOffset-=3");
			eval(accname+"nextOffset-=3");
		}
	}
	else
	{
		var offval=eval(accname+"nextOffset");
		var to_post = "nextPreviouscrousel=" + accname +  "&offset=" + offval + "&checksum=" + checksum + "&id=" + id;
		if(accname=='acceptanceacc' || accname=='initialacc')
		{
			eval(accname+"previousOffset+=3");
			eval(accname+"nextOffset+=3");
		}
		else
		{
			eval(accname+"previousOffset+=3");
			eval(accname+"nextOffset+=3");
		}
	}
	
	if(eval(accname+"previousOffset>=0"))
	{
		document.getElementById(accname + '_Uid2').style.display="none";
		document.getElementById(accname + '_Uid1').style.display="inline";
	}
	else
	{
		document.getElementById(accname + '_Uid1').style.display="none";
		document.getElementById(accname + '_Uid2').style.display="inline";
	}
	if(eval(accname+"nextOffset<" + total))
	{
		document.getElementById(accname + '_Did1').style.display="inline";
		document.getElementById(accname + '_Did2').style.display="none";
	}
	else
	{
		document.getElementById(accname + '_Did2').style.display="inline";
		document.getElementById(accname + '_Did1').style.display="none";
	}
	xmlreqPOST("/P/mainmenu.php",to_post);
	for(i=0;i<loop;i++)
	{
		document.getElementById(accname + i).innerHTML="";
		document.getElementById(accname + i).innerHTML="<b class=\"nme_photo\"><img src='images/loader_small.gif'></b>";
	}
}


function showThislayer(showOrHide)
{
	if(showOrHide=='H')
	{
		document.getElementById('hintbox3').style.display="none";
		common_check=0;
                function_to_call="";
	}
	else
	{
		document.getElementById('hintbox3').innerHTML='<div id="benft_upgr" class="p_rel" style="margin-top:-160px;"><div class="ben_upgd fl f11"><a href="#" onClick="showThislayer(\'H\');return false;" class="fr">[X]</a>Each computer that is connected to the <br />Internet has a clearly identifiable,numeric address, the Internet Protocol (IP) address, comprising four sequences of digits that are separated by periods. IP address are assigned to each computer on the network so that its location and activities can be distinguished from other computers.</div><p class="clr"></p><i class="ben_arw p_abs"></i></div>';

		check_window("showThislayer('H')");
		document.getElementById('hintbox3').style.display="block";
		common_check=1;
                function_to_call="showThislayer('H')";
	}
	return false;
}

function showMlayer(showOrHide)
{
	if(showOrHide=='H')
	{
		document.getElementById('hintbox1').style.display="none";
		common_check=0;
                function_to_call="";
	}
	else
	{
		var memlayer;
		memlayer='<div id="benft_upgr" class="p_rel" style="margin-top:-'+topM+'px;';
		if(erishta)
			memlayer+='margin-left:-150px;';
		memlayer+='"><div class="ben_upgd fl f11" ><b class="fl">Benefits you get on Upgrading</b><a href="" class="fr" onClick="showMlayer(\'H\');return false;">[X]</a><br><br><ol>'+paymessage+'</ol></div><p class="clr"></p><i class="ben_arw p_abs"></i></div>';
		document.getElementById('hintbox1').innerHTML=memlayer;
		check_window("showMlayer('H')");
		document.getElementById('hintbox1').style.display="block";
		common_check=1;
                function_to_call="showMlayer('H')";
	}
}

function myjs_loginhistory(showOrHide,next_prev)
{
	if(next_prev)
		var to_post = "profileid=" + profileid + "&country="+ country + "&j=" + next_prev;
	else
		var to_post = "profileid=" + profileid + "&country="+ country;
	if(showOrHide=='H')
	{
		document.getElementById('loginhistoryId').style.display="none";
		common_check=0;
                function_to_call="";
	}
	else
	{
		check_window("myjs_loginhistory('H')");
		common_check=1;
                function_to_call="myjs_loginhistory('H')";

		document.getElementById('loginhistoryId').style.display="block";
		var req = createNewXmlHttpObject();
		req.open("POST","/P/myjs_iplog.php",true);
	        req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        	req.send(to_post);
		document.getElementById('lll').innerHTML='<div style="text-align:center;padding-top:80px"><img src="IMG_URL/img_revamp/loader_big.gif"></div>';
	        req.onreadystatechange = myjs_checkXmlHttpStatus_1;
		var v="'" + window.location + "'"
		if(v.indexOf("here")==-1)
			window.location = window.location + "#here";
		else
			window.location = window.location;
		//location.hash = '#here';
	}
}

function astro_icons()
{
        var docF=document.getElementsByTagName("input");
        var pstring="";
        for(var i=0;i<docF.length;i++)
        {
                if(docF[i].name.match("horo_astro"))
                {
                        pstring1=docF[i].value;
                        pstring=pstring+pstring1+"@";
                }
        }
        var request_url = SITE_URL+"/profile/issue_2472.php?profileid=&checksum="+CHECKSUM+"&caste="+my_caste+"&logged_astro_details="+my_horo_astro+"&compstring="+pstring;
        send_ajax_request(request_url,"","after_astro_call","GET");
}
function after_astro_call()
{
        var res = result;
        var x = res.substr(0).split("/>");
        for (var j=0; j<(x.length-1); j++)
        {
                if(x[j].charAt(0)==">")
                        x[j]=x[j].substr(1,x[j].length);
                var y = x[j].split(":");
                if(typeof(y[2]) != "undefined")
                {
                        var Guna_str = "Guna_"+y[0];

                        var Guna  = y[2].substr(0,4)+"/36";
                        var Lg = y[3].substr(0,2);
                        var Su = y[4].substr(0,2);
                        var Me = y[5].substr(0,2);
                        var Ju = y[6].substr(0,2);
                        var Sa = y[7].substr(0,2);

                        var lagan = "LAGAN_ID_"+y[0];
                        var imgs=new Array;
			var titles=new Array;
                        if(Lg == 1)
                        {
                                imgs[0]="lagan_p.jpg";
                                titles[0]="Lagan Favourable"
                        }
                        else if(Lg == -1)
                        {
                                imgs[1]="lagan_m.jpg";
                                titles[1]="Lagan Unfavourable"
                        }
			if(Su == 1)
                        {
                                imgs[2]="sun_p.jpg"
                                titles[2]="Sun Favourable"
                        }
                        else if(Su == -1)
                        {
                                imgs[3]="sun_m.jpg";
                                titles[3]="Sun Unfavourable";
                        }
                        if(Me == 1)
                        {
                                imgs[4]="murcury_p.jpg";
                                titles[4]="Mercury Favourable"
                        }
                        else if(Me == -1)
                        {
                                imgs[5]="murcury_m.jpg";
                                titles[5]="Mercury Unfavourable"
                        }
                        if(Ju == 1)
                        {
                                imgs[6]="jupitar_p.jpg";
                                titles[6]="Jupitor Favourable"
                        }
                        else if(Ju == -1)
                        {
                                imgs[7]="jupitar_m.jpg";
                                titles[7]="Jupitor Unfavourable"
                        }
                        if(Sa == 1 || Sa == "1")
                        {
                                imgs[8]="saturn_p.jpg";
                                titles[8]="Saturn Favourable"
                        }
			else if(Sa == -1)
                        {
                                imgs[9]="saturn_m.jpg";
                                titles[9]="Saturn Unfavourable"
                        }


                        if(Guna)
                        {
                                var img_str="";
                                var MG_URLL="IMG_URL/profile/ser4_images/";
                                for(var i=0;i<imgs.length;i++)
                                {
                                        if(imgs[i])
                                                img_str=img_str+"<Td><img border=0 src='"+MG_URLL+imgs[i]+"' title='"+titles[i]+"' ></td>";
                                }
                                img_str=img_str+"<td>"+Guna+"</td>";
                                dID(lagan).innerHTML="<table cellspacing=0 cellpadding=3 border=0 style=\"padding:0;margin:0\"><TR>"+img_str+"</tr></table>";
                        }
                }
        }
}
