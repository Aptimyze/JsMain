var ua = navigator.userAgent
var dom = (document.getElementById)? 1:0
var ie4 = (document.all&&!dom)? 1:0
var ie5 = (document.all&&dom)? 1:0
var nn4 =(navigator.appName.toLowerCase() == "netscape" && parseInt(navigator.appVersion) == 4)
var nn6 = (dom&&!ie5)? 1:0
var sNav = (nn4||nn6||ie4||ie5)? 1:0
if(!sNav) {
var ps = navigator.productSub 
}
var cssFilters = ((ua.indexOf("MSIE 5.5")>=0||ua.indexOf("MSIE 6")>=0)&&ua.indexOf("Opera")<0)? 1:0
var Style=[],Text=[],Count=0,sbw=0,move=0,hs="",mx,my,scl,sct,ww,wh,obj,sl,st,ih,iw,vl,hl,sv,evlh,evlw,tbody
function HideTip(item)
{
	if(item=='caste')
		document.getElementById('TipLayer').style.visibility='hidden'
	if(item=='community')
		document.getElementById('TipLayer1').style.visibility='hidden'
	if(item=='city')
		document.getElementById('TipLayer2').style.visibility='hidden'
}
function HideLayer(item)
{
        if(item=='caste')
                window.parent.document.getElementById('TipLayer').style.visibility='hidden'
        if(item=='community')
		window.parent.document.getElementById('TipLayer1').style.visibility='hidden'
        if(item =='city')
		window.parent.document.getElementById('TipLayer2').style.visibility='hidden'
}

var doc_root = ((ie5&&ua.indexOf("Opera")<0||ie4)&&document.compatMode=="CSS1Compat")? "document.documentElement":"document.body"
var PX = (nn6)? "px" :"" 
var oldVal = new String();
var oldVals = new Array();


var mx,my,scl,sct,obj,sl,st,ih,iw,vl,hl,sv,ww,wh,evlh,evlw,sbw

function stm(t,s,item) {
if(item=='caste')
{
	HideLayer('community');
	HideLayer('city');
}
if(item=='community')
{
	HideLayer('caste');
	HideLayer('city');
}
if(item=='city')
{
	HideLayer('caste');
	HideLayer('community');
}
if(sNav) {
        window.onresize = ReloadTip
        document.onclick = MoveTip
        if(nn4) document.captureEvents(Event.MOUSEMOVE)
}
if(nn4||nn6) {
        mx = "e.pageX"
        my = "e.pageY"
        scl = "window.pageXOffset"
        sct = "window.pageYOffset"
        if(nn4) {
                obj = "window.parent.document.TipLayer."
                sl = "left"
                st = "top"
                ih = "clip.height"
                iw = "clip.width"
                vl = "'show'"
                hl = "'hide'"
                sv = "visibility="
        }
        else
	{
		if(item=='caste')
	 		obj = "window.parent.document.getElementById('TipLayer')."
		else if(item=='community')
                        obj = "window.parent.document.getElementById('TipLayer1')."
		else if(item=='city')
                        obj = "window.parent.document.getElementById('TipLayer2')."
	}
}
if(ie4||ie5) {
		if(item=='caste')
			obj = "window.parent.document.getElementById('TipLayer')."
                else if(item=='community')
			obj = "window.parent.document.getElementById('TipLayer1')."
                else if(item=='city')
			obj = "window.parent.document.getElementById('TipLayer2')."

        mx = "event.x"
        my = "event.y"
        scl = "eval(doc_root).scrollLeft"
        sct = "eval(doc_root).scrollTop"
        if(ie5) {
                mx = mx+"+"+scl
                my = my+"+"+sct
        }
}
if(ie4||dom){
        sl = "style.left"
        st = "style.top"
        ih = "offsetHeight"
        iw = "offsetWidth"
        vl = "'visible'"
        hl = "'hidden'"
        sv = "style.visibility="
}
if(ie4||ie5||ps>=20020823) {
        ww = "eval(doc_root).clientWidth"
        wh = "eval(doc_root).clientHeight"
}
else {
        ww = "window.innerWidth"
        wh = "window.innerHeight"
        evlh = eval(wh)
        evlw = eval(ww)
        sbw=15
}
 

  if(sNav) {
  	        if(nn4){
  	          var scrollbars = 'overflow:scroll;';
  	        }else{
  	          var scrollbars = 'overflow-x:hidden;overflow-y:scroll;'; 
  	        }
  	        
		var ab = "" ;var ap = ""
		var titCol = (s[0])? "COLOR='"+s[0]+"'" : ""
		var txtCol = (s[1])? "COLOR='"+s[1]+"'" : ""
		//var titBgCol = "BGCOLOR=#FED4B3 : "
		var titBgCol = "BGCOLOR=#FFFFFF : "     //to set background color of main table
		var txtBgCol = (s[3])? "BGCOLOR='"+s[3]+"'" : ""
		var titBgImg = "BACKGROUND=#FF0DAA"
		var txtBgImg = (s[5])? "BACKGROUND='"+s[2]+"'" : ""
		var titTxtAli = (s[6] && s[6].toLowerCase()!="left")? "ALIGN='"+s[6]+"'" : ""
		var txtTxtAli = (s[7] && s[7].toLowerCase()!="left")? "ALIGN='"+s[7]+"'" : ""   
		var add_height = (s[15])? "HEIGHT='"+s[15]+"'" : ""
		if(!s[8])  s[8] = "Verdana,Arial,Helvetica"
		if(!s[9])  s[9] = "Verdana,Arial,Helvetica"					
		if(!s[12]) s[12] = 1
		if(!s[13]) s[13] = 1
		if(!s[14]) s[14] = 200
		if(!s[16]) s[16] = 0
		if(!s[17]) s[17] = 0
		if(!s[18]) s[18] = 10
		if(!s[19]) s[19] = 10
		if ((screen.width>=1024) && (screen.height>=768))
                {
                         var width_table=500
                }
                else
                {
                        var width_table=465
                }

		hs = s[11].toLowerCase() 
		if(ps==20001108){
		if(s[2]) ab="STYLE='border:px solid"+" "+s[2]+"'"
		ap="STYLE='padding:"+s[17]+"px "+s[17]+"px "+s[17]+"px "+s[17]+"px'"}
		var closeLink=(hs=="sticky")? "<TD width='3%' ALIGN='right' valign='middle'><A HREF='javascript:void(0)' ONCLICK=\"stickyhide(\'"+item+"\')\" STYLE='text-decoration:none;color:"+s[0]+"'><img src=\"/P/imagesnew/close_button_new.gif\" border=0></A>&nbsp;</TD>":""
                var title=(t[0]||hs=="sticky")? "<TABLE "+titBgCol+" WIDTH=100% HEIGHT='17' BORDER='0' CELLPADDING='0' CELLSPACING='0' style='text-align:left;'><TR><TD width='97%' style='font:bold 12px Arial;'"+titTxtAli+"><FONT color='#000000'>&nbsp;&nbsp;&nbsp;"+t[0]+"</font></TD>"+closeLink+"</TR></TABLE>" : ""
                var txt="<TABLE "+titBgImg+" "+ab+" WIDTH="+width_table+" BORDER='0' CELLPADDING='"+s[16]+"' CELLSPACING='0'   "+titBgCol+" ><TR bgcolor='#fffoda'><TD>"+title+"</TD></TR><TR><TD><div style='background-color=#fffoda;position: relative;overflow:auto;scrollbar-face-color:#DADFE8;height:200px;WIDTH:auto;'><FONT SIZE='"+s[13]+"' FACE='"+s[9]+"' "+txtCol +">"+t[1]+"</FONT></div></TD></TR></TABLE>";

		//txt += "<table "+titBgCol+" border=0 WIDTH="+width_table+" height='22'><tr><td colspan=3 align=right width=90% ><A HREF='javascript:void(0)' onclick=\"HideTip('"+item+"');\"><img src=\"/P/imagesnew/ok_button.gif\" border=0></a><A HREF='javascript:void(0)' onclick=\"HideTip('"+item+"');reset('"+item+"',document.getElementById('frame_top').contentWindow.document);\"><img src=\"/P/imagesnew/cancel_button.gif\" border=0></a><A HREF='javascript:void(0)' onclick=\"reset('"+item+"',document.getElementById('frame_top').contentWindow.document);\"><img src=\"/P/imagesnew/reset_button.gif\" border=0></a></td></tr></table>";
		txt += "<table "+titBgCol+" border=0 WIDTH="+width_table+" height='22'><tr><td colspan=3 align=right width=90% ><input type=\"submit\" name=\"submit\" value=\"Ok\" style=\"background-color:#999999; border:1px #000000 solid; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF\" onclick=\"HideTip('"+item+"');\">&nbsp;<input type=\"submit\" name=\"cancel\" value=\"Cancel\" style=\"background-color:#999999; border:1px #000000 solid; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF\" onclick=\"HideTip('"+item+"');reset('"+item+"',document.getElementById('frame_top').contentWindow.document);\">&nbsp;<input type=\"submit\" name=\"reset\" value=\"Reset\" style=\"background-color:#999999; border:1px #000000 solid; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF\" onclick=\"reset('"+item+"',document.getElementById('frame_top').contentWindow.document);\"></td></tr></table>";
		if(nn4) {
			with(eval(obj+"document")) {
				open()
				write(txt)
				close()
			}
		}
		else {
			eval('frame_id=window.parent.document.getElementById(\'frame_top\')');
			eval(obj+"innerHTML=txt");
			}
		if ((screen.width>=1024) && (screen.height>=768))                         
		{
		         var width_layer=parseInt(eval(obj+iw)+3+sbw)                         
		}
                else
                {
			var width_layer=100
		}
		tbody = {
			Pos:s[10].toLowerCase(), 
			Xpos:s[18],
			Ypos:s[19], 
			Transition:s[20],
			Duration:s[21], 
			Alpha:s[22],
			ShadowType:s[23].toLowerCase(),
			ShadowColor:s[24],
			Width:parseInt(width_layer)
		}
		if(ie4) { 
			TipLayer.style.width = width_layer 
	 		tbody.Width = width_layer
		}
		move=1
 	 }
if(item=='caste')
 checkExistence(item,categoryvalue);
else if(item=='community')
 checkExistence(item,communityvalue);
else if(item=='city')
 checkExistence(item,cityvalue);

//checkExistence(item,categoryvalue);
}

function checkExistence(item,value) {
	oldVal = document.getElementById(item).value;
	oldVals = oldVal.split(";");
	if(item=='caste')
	{
		var chks = window.parent.document.getElementById('TipLayer').getElementsByTagName("input");
		oldVal_val=document.getElementById('Caste_display').value;
		oldVals_val = oldVal_val.split(",");
		for (i=0;i<value.length;i++)
	        {
                for (j=0;j<oldVals_val.length;j++)
                {
                        if ((value[i]) == (oldVals_val[j]))
                        {
                                chks[i+1].checked = true;
                        }
                }
        	}
	}
	else if(item=='community')
	{
		var chks = window.parent.document.getElementById('TipLayer1').getElementsByTagName("input");
		oldVal_val=document.getElementById('Mtongue').value;
                oldVals_val = oldVal_val.split(",");
                for (i=0;i<value.length;i++)
                {
                for (j=0;j<oldVals_val.length;j++)
                {
                        if ((value[i]) == (oldVals_val[j]))
                        {
                                chks[i+1].checked = true;
                        }
                }
                }

		/*for (i=0;i<chks.length;i++)
        	{
                for (j=0;j<oldVals.length;j++)
                {
                        if ((chks[i].value).toUpperCase() == (oldVals[j]).toUpperCase())
                        {
                                chks[i].checked = true;
                        }
                }
	        }

		oldVal_val=document.getElementById('Mtongue').value;
		oldVals_val = oldVal_val.split(",");*/
	}
	else if(item=='city')
	{
		var chks = window.parent.document.getElementById('TipLayer2').getElementsByTagName("input");
		for (i=0;i<chks.length;i++)
	        {
                for (j=0;j<oldVals.length;j++)
                {
                        if ((chks[i].value).toUpperCase() == (oldVals[j]).toUpperCase())
                        {
                                chks[i].checked = true;
                        }
                }
        	}

		oldVal_val=document.getElementById('City_res').value;
		oldVal_val+=','+document.getElementById('Country_res').value;
		
		oldVals_val = oldVal_val.split(",");
	}
	/*for (i=0;i<value.length;i++)
	{
		for (j=0;j<oldVals_val.length;j++)
		{
			if ((value[i]) == (oldVals_val[j]))
			{
				chks[i+1].checked = true;
			}
		}
	}*/
/*	for (i=0;i<chks.length;i++)
        {
                for (j=0;j<oldVals.length;j++)
                {
                        if ((chks[i].value).toUpperCase() == (oldVals[j]).toUpperCase())
                        {
                                chks[i].checked = true;
                        }
                }
        }
*/

}

function MoveTip(e) {
	if(move) {
		move=0
		my=240
		var X,Y,MouseX = eval(mx),MouseY = eval(my); tbody.Height = parseInt(eval(obj+ih)+3)
		tbody.wiw = parseInt(eval(ww+"+"+scl)); tbody.wih = parseInt(eval(wh+"+"+sct))
		
		switch(tbody.Pos) {
			case "left" : X=MouseX-tbody.Width-tbody.Xpos; Y=MouseY+tbody.Ypos; break
			case "center": X=MouseX-(tbody.Width/2); Y=MouseY+tbody.Ypos; break
			case "float": X=tbody.Xpos+eval(scl); Y=tbody.Ypos+eval(sct); break	
			case "fixed": X=tbody.Xpos; Y=tbody.Ypos; break		
			default: X=MouseX+tbody.Xpos; Y=MouseY+tbody.Ypos
		}
		Y=Y-30
		//to set left and top position of layer
		X=190
		eval(obj+sl+"=X+PX;"+obj+st+"=Y+PX")
		Y=Y+30
		ViewTip()
	}
}

function ViewTip() {
 		eval(obj+sv+vl)
}

function stickyhide(item) {
	HideTip(item);
}

function ReloadTip() {
	 if(nn4&&(evlw!=eval(ww)||evlh!=eval(wh))) location.reload()
	 else if(hs == "sticky") //eval(HideTip)
		HideTip(item);
}

function htm() {
	if(sNav) {
		if(hs!="keep") {
			move=0; 
			if(hs!="sticky") HideTip(item)
		}	
	} 
}



Style[9]=["#3061A3","#666666","#F6F8FC","#E7F4FD","","","","","","","","sticky","","",674,"",0,0,-389,0,12,0.3,95,"","#d6d6d6"]
Text[1] = ['Select Multiple <b>Communities</b>',MultiSelect(communitylist,'community',communityvalue)];
Text[2] = ["Select Multiple Castes",MultiSelect(categorylist,'caste',categoryvalue)];
Text[3] = ['Select Multiple <b>Cities</b>',MultiSelect(citylist,'city',cityvalue)];

var oldVal;	
var frame_id;	
function MultiSelect(forWhat,where,value) {
	var n = forWhat.length;
	var checkList = "";
	if ((screen.width>=1024) && (screen.height>=768))
	{
	 	var width_table=485
	}
	else
	{
		var width_table=450
        }

	if(where=='caste')
		var cols="4"
	else if(where=='community')
		 var cols="3"
	 else if(where=='city')
                 var cols="3"
	var chk = "";
	var k=9;	//variable to set main religion first then caste
	var j=0;

	for (i = 0;i < n;i++){
		if (i == 0)	
		{
			checkList += "<table bgcolor='#FFFFFF' border=0 cellpadding=0 cellspacing=0 valign=\"top\" width="+width_table+"><tr>";
			checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='All' style='color:#000000;' onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'All',this)\"></td><td width=32% class=mediumblack>All</td></tr><tr><td>&nbsp;</td></tr><tr>";
			if(where=='city')
				checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='All India' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'51',this,'y')\"></td><td width=32% class=mediumblack><b>All India</b></td></tr><tr><td>&nbsp</td></tr><tr>";	

		}	
		frame_id=window.parent.document.getElementById('frame_top')
		if(where=='city')
		{
		if(i<21)
			checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this)\"></td><td width=32% class=mediumblack>"+forWhat[i]+"</td>";
		else
		
			checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this,'y')\"></td><td class=mediumblack>"+forWhat[i]+"</td>";
		
		}
		else if(where=='community')
		{
			if(i<12)
                        checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this)\"></td><td width=32% class=mediumblack><b>"+forWhat[i]+"</b></td>";
                else
                                                                                                                             
                        checkList += "<td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this)\"></td><td class=mediumblack>"+forWhat[i]+"</td>";
		}
		else
		checkList += "<td width=3% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style='color:#000000;' id=city"+i+" onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this)\"></td><td width=30% class=mediumblack>"+forWhat[i]+"</td>";
	if(where=='caste')
	{	
		//loop to set main religion first then caste
		if ((i+1)%cols == 0 && i<9)
		{
			checkList += "</tr>";
		}
		var x;
		if((i)%4==0 && j-1==0)
	
			checkList += "</tr>";
		
		else if((i)%4==1 && j-1==1)
			checkList += "</tr>";
		else if((i)%4==0 && j-1==2)
			checkList += "</tr>";
		else if((i)%4==1 && j-1==3)
			checkList += "</tr>";
		
		if((i+1)==k)
		{
			if(j==0)
			{
				checkList += "<tr><td>&nbsp;</td></tr><tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;For more Caste/Sub Caste options scroll down</td><td></td></tr><tr><td>&nbsp;</td></tr>";
				checkList += "<tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;Hindu</td></tr>";
			}
			else if (j==1)
				checkList += "<tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;Muslim</td></tr>";
			else if (j==2)
				checkList += "<tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;Christian</td></tr>";
			else if (j==3)
				checkList += "<tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;Sikh</td></tr>";
			else if (j==4)
				checkList += "<tr><td class=mediumblack colspan=4>&nbsp;&nbsp;&nbsp;Jain</td></tr>";
				
			k=k+parseInt(count[j]);
			j++;
		}
	}
	else if(where=='city')
	{
		if((i+1) == 21)
			{
			 checkList += "</tr><tr><td>&nbsp;</td></tr><tr><td width=1% style=\"font-family:verdana;font-size:11;\" height=15 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='All Countries' style='color:#000000;' onclick=\"putSelected(document.getElementById('frame_top').contentWindow,document.getElementById('frame_top').contentWindow.document.getElementById('"+where+"'),'',this)\"></td><td colspan=2 class=mediumblack>&nbsp;&nbsp;&nbsp;<b>All Countries</b></td></tr>";}
		if ((i+1)%cols == 0 && (i+1) != 21)
		{
                        checkList += "</tr>";
		}
	}
	else if(where=='community')
	{
	 	if ((i+1)%cols == 0)
                        checkList += "</tr>";
		if(i==11)
			checkList += "<tr><td>&nbsp;</td></tr>";
	}
	}
	checkList += "<tr><td>&nbsp</td></tr></table>";
	return checkList;
}
function putSelected(frame,where,value,objchk,country)
{	
	var j=0;
	var checkedList='';
	var element = 'chk' + where.name;
	if(where.name=='caste')
	{
        	var elem = document.getElementsByName('chkcaste');
		var val=frame.document.search_partner.Caste;
		if(objchk.checked)
		{
			if(value=='All')
				checkedCaste=''
        	        checkedCaste+=','+value;
		}
	        else
		{
			checkedCaste_arr=checkedCaste.split(',');
                        checkedCaste='';
                        for(var i=0;i<checkedCaste_arr.length;i++)
                        {
                                if(checkedCaste_arr[i]==value)
                                {
                                        checkedCaste_arr[i]='';
                                }
                        }
                        for(var i=0;i<checkedCaste_arr.length;i++)
                        {
                                if(checkedCaste_arr[i]!='')
                                        checkedCaste+=','+checkedCaste_arr[i]
                        }
        	        //checkedCaste=checkedCaste.replace(value,"");
		}
        	checkedCaste=checkedCaste.replace(",,",",");
        	if(checkedCaste.indexOf(',')==0)
                	checkedCaste=checkedCaste.replace(",","");
		val.value=checkedCaste;
	}	
	if(where.name=='community')
	{
		var elem = document.getElementsByName('chkcommunity');
		var val=frame.document.search_partner.Mtongue;
		if(objchk.checked)
		{
			if(value=='All')
				checkedCommunity=''
			//else
		                checkedCommunity+=','+value;
		}
                else
		{
			checkedCommunity_arr=checkedCommunity.split(',');
                        checkedCommunity='';
                        for(var i=0;i<checkedCommunity_arr.length;i++)
                        {
                                if(checkedCommunity_arr[i]==value)
                                {
                                        checkedCommunity_arr[i]='';
                                }
                        }
                        for(var i=0;i<checkedCommunity_arr.length;i++)
                        {
                                if(checkedCommunity_arr[i]!='')
                                        checkedCommunity+=','+checkedCommunity_arr[i]
                        }
                        //checkedCommunity=checkedCommunity.replace(value,"");
		}
                checkedCommunity=checkedCommunity.replace(",,",",");
                if(checkedCommunity.indexOf(',')==0)
                        checkedCommunity=checkedCommunity.replace(",","");
                val.value=checkedCommunity;
	}
	if(where.name=='city')
	{
		/*for(var j=0;j<count(checkedCity);j++)
		{
			alert(checkedCity[j]);
		}*/
		if(country=='y')
		{
			var val1=frame.document.search_partner.Country_res;
			var val2=frame.document.search_partner.Country_res_val;
		}
		var val=frame.document.search_partner.City_res;
		var val3=frame.document.search_partner.City_res_val;
                var elem = document.getElementsByName('chkcity');
		if(objchk.checked)
		{
			if(value=='All')
			{
                                checkedCountry=''
				checkedCity=''
			}

			if(country=='y')
				checkedCountry+=','+value;
			else
                		checkedCity+=','+value;
		}
                else
		{
			if(country=='y')
			{
				checkedCountry_arr=checkedCountry.split(',');
				checkedCountry='';
				value_arr=value.split(',');
				for(var i=0;i<checkedCountry_arr.length;i++)
                                {
                                        for(var j=0;j<value_arr.length;j++)
                                        {
                                                if(checkedCountry_arr[i]==value_arr[j])
                                                {
                                                        checkedCountry_arr[i]='';
                                                }
                                        }
                                }
				for(var i=0;i<checkedCountry_arr.length;i++)
				{
					if(checkedCountry_arr[i]!='')
	                                        checkedCountry+=','+checkedCountry_arr[i]
                        	}
				//checkedCountry=checkedCountry.replace(value,"");
				val2_arr=val2.value.split(',');
                                val2.value='';
                                for(var i=0;i<val2_arr.length;i++)
                                {
                                        if(val2_arr[i]==value)
                                        {
                                                val2_arr[i]='';
                                        }
                                }
                                for(var i=0;i<val2_arr.length;i++)
                                {
                                        if(val2_arr[i]!='')
	                                        val2.value+=','+val2_arr[i]
                                }
				//val2.value=val2.value.replace(value,"");		
			}
			else
			{
				checkedCity_arr=checkedCity.split(',');
                                checkedCity='';
                                for(var i=0;i<checkedCity_arr.length;i++)
                                {
                                        if(checkedCity_arr[i]==value)
                                        {
                                                checkedCity_arr[i]='';
                                        }
                                }
                                for(var i=0;i<checkedCity_arr.length;i++)
                                {
                                        if(checkedCity_arr[i]!='')
                                                checkedCity+=','+checkedCity_arr[i]
                                }
                                //checkedCity=checkedCity.replace(value,"");
                                val3_arr=val3.value.split(',');
                                val3.value='';
                                for(var i=0;i<val3_arr.length;i++)
                                {
                                        if(val3_arr[i]==value)
                                        {
                                                val3_arr[i]='';
                                        }
                                }
                                for(var i=0;i<val3_arr.length;i++)
                                {
                                        if(val3_arr[i]!='')
                                                val3.value+=','+val3_arr[i]
                                }
                                //val3.value=val3.value.replace(value,"");
			}
		}
		checkedCountry=checkedCountry.replace(",,",",");
                checkedCity=checkedCity.replace(",,",",");
                if(checkedCity.indexOf(',')==0)
                        checkedCity=checkedCity.replace(",","");
		if(checkedCountry.indexOf(',')==0)
                        checkedCountry=checkedCountry.replace(",","");
		if(checkedCountry.indexOf(',')==(checkedCountry.length-1))
                        checkedCountry=checkedCountry.replace(",","");
		if(checkedCity.indexOf(',')==(checkedCity.length-1))
                        checkedCity=checkedCity.replace(",","");

                val.value=checkedCity;
		if(country=='y')
			val1.value=checkedCountry;
	}
        var n = eval(elem);

	n = n.length;
	for(i=0;i<n;i++)
	{
                var elemchk = elem[i];
                var elemchk_first = elem[0];
                var isChecked = elemchk.checked;
		if(isChecked && i==0) {
			checkedList='';
			
			checkedList+= elemchk.value+';';
			j++;
			checkbox_disabled(where.name,'disab');
		}
		else if(isChecked && i==23 && where.name=='city')
		{
			checkedList='';
			checkedList+= elemchk.value+';';
			j++;
			checkbox_disabled(where.name,'disab','All Countries');
		}
		else if(!elemchk_first.checked && i==0)
		{
			checkbox_disabled(where.name,'enab');
		}
		else if(i==23 && where.name=='city' && elemchk.disabled==false)
			checkbox_disabled(where.name,'enab');
		if(isChecked && !elemchk_first.checked && i>0)
		{
		 	checkedList+= elemchk.value+';';
                        j++;
		}

	}
	if(j>0){
		checkedList = checkedList.substring(0,checkedList.length-1);
		where.value = checkedList;
	} else {
			if(where.name=='community')
				where.value = "All Community";
			else if(where.name=='caste')
				where.value = "All Caste";
			else if(where.name=='city')
				where.value = "All City";
				
	}
	frame.document.getElementById(where.name).disabled = false;
}
function checkbox_disabled(item,type,all_countries)
{
	eval('frame_id=window.parent.document.getElementById(\'frame_top\').contentWindow.document');
	if(item=='community')
	{
		var elem = document.getElementsByName('chkcommunity');
		var n = eval(elem);
	        n = n.length;

		if(type=='disab')
		{
			frame_id.search_partner.Mtongue.value='All';
                        frame_id.search_partner.Mtongue_val.value='';
                        checkedCommunity='';

			for(var i=1;i<n;i++)
			{
					elem[i].checked=false;
					elem[i].disabled=true;
			}
		}
		else if(type=='enab')
		{
			for(var i=1;i<n;i++)
			{
					elem[i].disabled=false;
			}
		}
	}
	if(item=='caste')
        {
                var elem = document.getElementsByName('chkcaste');
                var n = eval(elem);
                n = n.length;
                                                                                                                             
                if(type=='disab')
                {
			frame_id.search_partner.Caste.value='All';
	                frame_id.search_partner.Caste_val.value='';
        	        checkedCaste='';

                        for(var i=1;i<n;i++)
                        {
                                        elem[i].checked=false;
                                        elem[i].disabled=true;
                        }
                }
                else if(type=='enab')
                {
                        for(var i=1;i<n;i++)
                        {
                                        elem[i].disabled=false;
                        }
                }
        }
	if(item=='city')
        {
                var elem = document.getElementsByName('chkcity');
                var n = eval(elem);
                n = n.length;
                                                                                                                             
                if(type=='disab')
                {
			frame_id.search_partner.Country_res.value='All';
			frame_id.search_partner.City_res.value='All';
                        frame_id.search_partner.Country_res_val.value='';
                        frame_id.search_partner.City_res_val.value='';
			
                        checkedCity='';
                        checkedCountry='';
			if(all_countries=='All Countries')
			{
				for(var i=0;i<n;i++)
				{
					if(i!=23)
					{
						elem[i].checked=false;
						elem[i].disabled=true;
					}
				}
			}
			else
			{
				for(var i=1;i<n;i++)
                                {
                                                elem[i].checked=false;
                                                elem[i].disabled=true;
                                }
			}
                }
                else if(type=='enab')
                {	
			for(var i=0;i<n;i++)
                        {
                                        elem[i].disabled=false;
                        }

                }
        }



}
/*function remove_text(item,frame_id)
{
	if(item=='caste')
	{
		var val=frame_id.search_partner.Caste;
		val.value='All';
		frame_id.getElementById('caste').value='All Castes';
	}
	if(item=='community')
        {
                var val=frame_id.search_partner.Mtongue;
                val.value='All';
                frame_id.getElementById('community').value='All Communities';
        }
	if(item=='city')
        {
                var val=frame_id.search_partner.City_res;
                val.value='All';
                frame_id.getElementById('city').value='All Cities';
        }
}*/
function reset(item,frame_id)
{
	checkbox_disabled(item,'enab');
	
	if(item=='caste')
	{
		var check=document.getElementsByName("chkcaste");
        	for(var i=0;i<check.length;i++)
                	check[i].checked=false;		
		var val=frame_id.search_partner.Caste;
                val.value='All';
		frame_id.getElementById('caste').value='All Castes';
		frame_id.search_partner.Caste.value='All';
		frame_id.search_partner.Caste_val.value='';
		checkedCaste='';
	}
	if(item=='community')
        {
                var check=document.getElementsByName("chkcommunity");
                for(var i=0;i<check.length;i++)
                        check[i].checked=false;
		var val=frame_id.search_partner.Mtongue;
                val.value='All';
                frame_id.getElementById('community').value='All Communities';
		frame_id.search_partner.Mtongue.value='All';
		frame_id.search_partner.Mtongue_val.value='';
		checkedCommunity='';
        }
	if(item=='city')
        {
                var check=document.getElementsByName("chkcity");
                for(var i=0;i<check.length;i++)
                        check[i].checked=false;
		var val=frame_id.search_partner.City_res;
                val.value='All';
                frame_id.getElementById('city').value='All Cities';
		frame_id.search_partner.City_res.value='All';
		frame_id.search_partner.Country_res.value='All';
		frame_id.search_partner.City_res_val.value='';
		frame_id.search_partner.Country_res_val.value='';
		checkedCity='';
		checkedCountry='';
        }

}
