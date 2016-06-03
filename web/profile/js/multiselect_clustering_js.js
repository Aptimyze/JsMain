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
var Style=[],Text=[],Count=0,sbw=0,move_cluster=0,hs="",mx,my,scl,sct,ww,wh,obj,sl,st,ih,iw,vl,hl,sv,evlh,evlw,tbody


function HideTip_cluster(item,type)
{
	if(item=='edu')
	{
		whichDog_4= null;
		offsetx_4= null;
		offsety_4= null;
		nowX_4= null;
		nowY_4= null;

		whichDog_5= null;
		offsetx_5= null;
		offsety_5= null;
		nowX_5= null;
		nowY_5= null;

		document.getElementById('TipLayer3').style.visibility='hidden'
	}
	if(item=='occupation')
	{
		whichDog_3= null;
                offsetx_3= null;
                offsety_3= null;
                nowX_3= null;
                nowY_3= null;
                                                                                                                             
                whichDog_5= null;
                offsetx_5= null;
                offsety_5= null;
                nowX_5= null;
                nowY_5= null;

		document.getElementById('TipLayer4').style.visibility='hidden'
	}
	if(item=='income')
	{
		whichDog_4= null;
                offsetx_4= null;
                offsety_4= null;
                nowX_4= null;
                nowY_4= null;
                                                                                                                             
                whichDog_3= null;
                offsetx_3= null;
                offsety_3= null;
                nowX_3= null;
                nowY_3= null;

		document.getElementById('TipLayer5').style.visibility='hidden'
	}
	if(type!='cancel' && type!='close')
	{
		var frame=eval('frame_id_1_1=window.parent.document.getElementById(\'frame_clustering\').contentWindow.document');
		//window.parent.document.getElementById('bread_crumb_hidden').value=item;
		//alert(window.parent);//.document.getElementById('bread_crumb_hidden'));
		frame.form_search_clustering.bread_crumb_hidden.value=item;
		//alert(frame.form_search_clustering.bread_crumb_hidden.value);
        	frame.form_search_clustering.submit();
	}
}
function HideLayer_cluster(item)
{
        if(item=='edu')
                window.parent.document.getElementById('TipLayer3').style.visibility='hidden'
        if(item=='occupation')
		window.parent.document.getElementById('TipLayer4').style.visibility='hidden'
        if(item =='income')
		window.parent.document.getElementById('TipLayer5').style.visibility='hidden'
}

var doc_root = ((ie5&&ua.indexOf("Opera")<0||ie4)&&document.compatMode=="CSS1Compat")? "document.documentElement":"document.body"
var PX = (nn6)? "px" :"" 
var oldVal = new String();
var oldVals = new Array();


var mx,my,scl,sct,obj,sl,st,ih,iw,vl,hl,sv,ww,wh,evlh,evlw,sbw

function stm_cluster(t,s,item,currentusername) {
	move_cluster=1
if(item=='edu')
{
	var frame=eval('frame_id_1_1=window.parent.document.getElementById(\'frame_clustering\').contentWindow.document');
        frame.form_search_clustering.EDU_BIG_LABEL.value='';
	HideLayer_cluster('occupation');
	HideLayer_cluster('income');
}
if(item=='occupation')
{
	var frame=eval('frame_id_1_1=window.parent.document.getElementById(\'frame_clustering\').contentWindow.document');
        frame.form_search_clustering.OCCUPATION_BIG_LABEL.value='';

	HideLayer_cluster('edu');
	HideLayer_cluster('income');
}
if(item=='income')
{
	var frame=eval('frame_id_1_1=window.parent.document.getElementById(\'frame_clustering\').contentWindow.document');
	frame.form_search_clustering.INCOME_BIG_LABEL.value='';

	HideLayer_cluster('edu');
	HideLayer_cluster('occupation');
}
/*if(sNav) {
        window.onresize = ReloadTip_cluster
        document.onclick = MoveTip_cluster(item,currentusername)
        if(nn4) document.captureEvents(Event.MOUSEMOVE)
}*/
if(nn4||nn6) {
        mx = "e.pageX"
        my = "e.pageY"
        scl = "window.pageXOffset"
        sct = "window.pageYOffset"
        if(nn4) {
                obj = "window.parent.document.TipLayer3."
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
		if(item=='edu')
	 		obj = "window.parent.document.getElementById('TipLayer3')."
		else if(item=='occupation')
                        obj = "window.parent.document.getElementById('TipLayer4')."
		else if(item=='income')
                        obj = "window.parent.document.getElementById('TipLayer5')."
	}
}
if(ie4||ie5) {
		if(item=='edu')
			obj = "window.parent.document.getElementById('TipLayer3')."
                else if(item=='occupation')
			obj = "window.parent.document.getElementById('TipLayer4')."
                else if(item=='income')
			obj = "window.parent.document.getElementById('TipLayer5')."

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
		var titBgCol = "BGCOLOR=#E4E4E4 : "	//to set background color of main table
		var titBgCol1 = "BGCOLOR=#B4BD62 : "	//to set background color of Refine your search result table
		var titBgCol2 = "BGCOLOR=#FFFFFF : "	//to set background color of click on image to select multiple option
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
                        //var width_table=500
			var width_table=470
                }
                else
                {
                        //var width_table=465
                        var width_table=450
                }
                

		hs = s[11].toLowerCase() 
		if(ps==20001108){
		if(s[2]) ab="STYLE='border:px solid"+" "+s[2]+"'"
		ap="STYLE='padding:"+s[17]+"px "+s[17]+"px "+s[17]+"px "+s[17]+"px'"}
		var closeLink=(hs=="sticky")? "<TD width='3%' ALIGN='right' valign='middle'><A HREF='javascript:void(0)' ONCLICK=\"stickyhide_cluster(\'"+item+"\','close')\" STYLE='text-decoration:none;color:"+s[0]+"'><img src=\"/P/imagesnew/close_box.gif\" border=0></A></TD>":""
                //var title=(t[0]||hs=="sticky")? "<TABLE "+titBgCol+" style=\"text-align: left;\" id=\"titleBar\" WIDTH=100% HEIGHT='15' BORDER='0' CELLPADDING='0' CELLSPACING='0' style='text-align:left;'><TR "+titBgCol1+"><TD width='97%' height=28 style=\"font-family: verdana; font-style: normal; font-variant: normal; font-weight: bold; font-size: 12px; line-height: normal; font-size-adjust: none; font-stretch: normal;\" "+titTxtAli+"><FONT color='#ffffff'>&nbsp;&nbsp;&nbsp;"+"Refine your Search Results"+"</font></TD>"+closeLink+"</TR><TR><TD style=\"font-family: verdana; font-style: normal; font-variant: normal; font-weight: bold; font-size: 11px; line-height: normal; font-size-adjust: none; font-stretch: normal;\" height=28 colspan=2 width='97%' style='font:bold 11px Arial;'"+titTxtAli+"><FONT color='#000000'>&nbsp;&nbsp;&nbsp;"+t[0]+"</font></TD></TR><TR "+titBgCol2+"><TD height=24 colspan=2 width='97%' style=\"font-family: verdana; font-style: normal; font-variant: normal; font-weight: normal; font-size: 11px; line-height: normal; font-size-adjust: none; font-stretch: normal;\" "+titTxtAli+"><FONT color='#a9a9a9'>&nbsp;&nbsp;&nbsp;"+"Click on checkbox "+"<img src=\"/P/imagesnew/arrow_box.gif\" align=\"absmiddle\" border=0>"+" to select multiple options"+"</font></TD></TR></TABLE>" : ""
                var title=(t[0]||hs=="sticky")? "<TABLE "+titBgCol+" style=\"text-align: left;\" id=\"titleBar\" WIDTH=100% HEIGHT='15' BORDER='0' CELLPADDING='0' CELLSPACING='0' style='text-align:left;'><TR "+titBgCol1+"><TD width='97%' height=20 style=\"font-family: verdana; font-style: normal; font-variant: normal; font-weight: bold; font-size: 12px; line-height: normal; font-size-adjust: none; font-stretch: normal;\" "+titTxtAli+"><FONT color='#ffffff'>&nbsp;&nbsp;&nbsp;"+t[0]+"</font></TD>"+closeLink+"</TR></TABLE>" : ""
                var txt="<TABLE "+titBgImg+" "+ab+" WIDTH="+width_table+" BORDER='0' CELLPADDING='0' CELLSPACING='0' "+titBgCol+" ><TR bgcolor='#fffoda'><TD>"+title+"</TD></TR><TR><TD><div style='background-color:#ffffff;position: relative;overflow:auto;scrollbar-face-color:#DADFE8;height:165px;WIDTH:auto;'><FONT SIZE='"+s[13]+"' FACE='"+s[9]+"' "+txtCol +">"+t[1]+"</FONT></div></TD></TR></TABLE>";

		txt += "<table "+titBgCol+" style=\"text-align: left;\" border=0 WIDTH="+width_table+" height='22'><tr><td colspan=3 align=right width=90%><input name=\"submit\" type=\"submit\" class=\"bten\" value=\"Refine Search\" onClick=\"HideTip_cluster('"+item+"');\">&nbsp;<input name=\"cancel\" type=\"submit\" class=\"bten\" value=\"Cancel\" onclick=\"HideTip_cluster('"+item+"','cancel');reset_cluster('"+item+"',document.getElementById('frame_clustering').contentWindow.document);\">&nbsp;</td></tr></table>";
		//txt += "<table "+titBgCol+" border=0 WIDTH="+width_table+" height='22'><tr><td colspan=3 align=right width=90% ><input type=\"image\" name=\"submit\" value=\"Ok\" img src=\"/P/imagesnew/ok_button.gif\" border='0' onClick=\"HideTip_cluster('"+item+"');\"><A HREF='javascript:void(0)' onclick=\"HideTip_cluster('"+item+"','cancel');reset_cluster('"+item+"',document.getElementById('frame_clustering').contentWindow.document);\"><img src=\"/P/imagesnew/cancel_button.gif\" border=0></a><A HREF='javascript:void(0)' onclick=\"reset_cluster('"+item+"',document.getElementById('frame_clustering').contentWindow.document);\"><img src=\"/P/imagesnew/reset_button.gif\" border=0></a></td></tr></table>";
		if(nn4) {
			with(eval(obj+"document")) {
				open()
				write(txt)
				close()
			}
		}
		else {
			eval('frame_id_1=window.parent.document.getElementById(\'frame_clustering\')');
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
			TipLayer3.style.width = width_layer 
	 		tbody.Width = width_layer
		}
		move_cluster=1
 	 }
if(sNav) {
        window.onresize = ReloadTip_cluster
        //document.onclick = MoveTip_cluster(item,currentusername)
        MoveTip_cluster(item,currentusername)

        if(nn4) document.captureEvents(Event.MOUSEMOVE)

}
if(item=='edu')
 checkExistence_cluster(item,eduvalue);
if(item=='occupation')
 checkExistence_cluster(item,occvalue);
if(item=='income')
 checkExistence_cluster(item,incomevalue);

}

function checkExistence_cluster(item,value) {
	//oldVal = document.getElementById(item).value;
	//oldVals = oldVal.split(";");
	if(item=='edu')
	{
		var chks = window.parent.document.getElementById('TipLayer3').getElementsByTagName("input");
		oldVal_val=document.getElementById('edu').value;
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
	else if(item=='occupation')
        {
                var chks = window.parent.document.getElementById('TipLayer4').getElementsByTagName("input");
                oldVal_val=document.getElementById('occupation').value;
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
	else if(item=='income')
        {
                var chks = window.parent.document.getElementById('TipLayer5').getElementsByTagName("input");
                oldVal_val=document.getElementById('income').value;
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
	/*else if(item=='income')
        {
		oldVal_val = checkedincomelabel;//document.getElementById('income').value;
		oldVals_val = oldVal_val.split(";");
                var chks = window.parent.document.getElementById('TipLayer5').getElementsByTagName("input");
                for (i=0;i<chks.length;i++)
                {
                for (j=0;j<oldVals_val.length;j++)
                {
                        if ((chks[i].value).toUpperCase() == (oldVals_val[j]).toUpperCase())
                        {
                                chks[i].checked = true;
                        }
                }
                }
                //oldVal_val=document.getElementById('income').value;
                //oldVals_val = oldVal_val.split(",");
        }*/

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

function MoveTip_cluster(e,currentusername) {
	if(move_cluster) {
		move_cluster=0
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
		Y_temp=Y;
                if(currentusername=='')
                {
                        if(e=='edu')
                                Y=Y+5
                        else if(e=='occupation')
   	                        //Y=Y+75
				Y=Y+145
                        else if(e=='income')
                                //Y=Y+160
				Y=Y+75
                }
                else
                {       if(e=='edu')
			{
				if(leftpanel_div_open==1)
				{
					Y=Y+460
				}
				else
                                	Y=Y+270
			}
                        else if(e=='occupation')
			{
				if(leftpanel_div_open==1)
                                {
                                        Y=Y+610
                                }
				else
		                        Y=Y+410
			}
                        else if(e=='income')
			{
				if(leftpanel_div_open==1)
                                {
                                        Y=Y+540
                                }
				else
                                	Y=Y+340
			}
                }
                //to set left and top position of layer
		/*Y=350 //original
                X=200 //original*/
		Y=210
		X=200
		/*X=screen.width/2 - 200;
		Y=screen.height/2 - 180;*/
                                                                                                                             
                eval(obj+sl+"=X+PX;"+obj+st+"=Y+PX")
                Y=Y_temp
                ViewTip_cluster()
	}
}

function ViewTip_cluster() {
 		eval(obj+sv+vl)
}

function stickyhide_cluster(item,type) {
	HideTip_cluster(item,type);
}

function ReloadTip_cluster() {
	 if(nn4&&(evlw!=eval(ww)||evlh!=eval(wh))) location.reload()
	 else if(hs == "sticky") //eval(HideTip_cluster)
		HideTip_cluster(item);
}

function htm_cluster() {
	if(sNav) {
		if(hs!="keep") {
			move_cluster=0; 
			if(hs!="sticky") HideTip_cluster(item)
		}	
	} 
}



Style[9]=["#3061A3","#666666","#F6F8FC","#E7F4FD","","","","","","","","sticky","","",674,"",0,0,-389,0,12,0.3,95,"","#d6d6d6"]
Text[4] = ['Occupation / Profession',MultiSelect_cluster(occlist,'occupation',occvalue)];
Text[5] = ["Educational Qualification",MultiSelect_cluster(edulist,'edu',eduvalue)];
Text[6] = ['Annual Income',MultiSelect_cluster(incomelist,'income',incomevalue)];

var oldVal;	
var frame_id_1;	
function MultiSelect_cluster(forWhat,where,value) {
	var n = forWhat.length;
	var checkList = "";
	if ((screen.width>=1024) && (screen.height>=768))
	{
	 	//var width_table=485
	}
	else
	{
		var width_table=450
        }
	/*if(where=='edu')
		var cols="3"
	else
		var cols="2"*/
	var cols="2"
	var chk = "";
	var j=0;
	for (i = 0;i < n;i++){
		if (i == 0)	
		{
			//checkList += "<table bgcolor='#FFF0DA' border=0 cellpadding=0 cellspacing=0 valign=\"top\" width="+width_table+"><tr>";
			checkList += "<table bgcolor='#FFFFFF' class=\"bele\" border=0 cellpadding=0 cellspacing=0 valign=\"top\" width="+width_table+"><tr>";
			checkList += "<td width=1% style=\"font-family:verdana\" height=10 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='All' style=\"color: rgb(0, 0, 0);\" onclick=\"putSelected_cluster(document.getElementById('frame_clustering').contentWindow,document.getElementById('frame_clustering').contentWindow.document.getElementById('"+where+"'),'All',this)\"></td><td width=32% class=mediumblack>All</td></tr><tr>";

		}
		frame_id_1=window.parent.document.getElementById('frame_clustering')
		checkList += "<td width=3% style=\"font-family:verdana\" height=10 nowrap>&nbsp;&nbsp;<input name='chk"+where+"' type=checkbox value='"+forWhat[i]+"' style=\"color: rgb(0, 0, 0);\" onclick=\"putSelected_cluster(document.getElementById('frame_clustering').contentWindow,document.getElementById('frame_clustering').contentWindow.document.getElementById('"+where+"'),'"+value[i]+"',this)\"></td><td width=30% class=mediumblack>"+forWhat[i]+"</td>";
	if ((i+1)%cols == 0)
                        checkList += "</tr>";
	}
	//checkList += "<tr><td>&nbsp</td></tr></table>";
	checkList += "</table>";
	return checkList;
}
function putSelected_cluster(frame,where,value,objchk)
{
	var j=0;
	//var checkededu_arr,checkedoccupation_arr,checkedincome_arr;
	var checkedList='';
	var element = 'chk' + where.name;
	if(where.name=='edu')
	{
        	var elem = document.getElementsByName('chkedu');
		var val=frame.document.form_search_clustering.edu;
		if(objchk.checked)
		{
			if(value=='All')
				checkededu=''
			else
	        	        checkededu+=','+value;
		}
	        else
		{
			checkededu_arr=checkededu.split(',');
                        checkededu='';
                        for(var i=0;i<checkededu_arr.length;i++)
                        {
                                if(checkededu_arr[i]==value)
                                {
                                        checkededu_arr[i]='';
                                }
                        }
                        for(var i=0;i<checkededu_arr.length;i++)
                        {
                                if(checkededu_arr[i]!='')
                                        checkededu+=','+checkededu_arr[i]
                        }
        	        //checkededu=checkededu.replace(value,"");
		}
        	checkededu=checkededu.replace(",,",",");
        	if(checkededu.indexOf(',')==0)
                	checkededu=checkededu.replace(",","");
		val.value=checkededu;
	}	
	if(where.name=='occupation')
	{
		var elem = document.getElementsByName('chkoccupation');
		var val=frame.document.form_search_clustering.occupation;
		if(objchk.checked)
		{
			if(value=='All')
				checkedoccupation=''
			else
		                checkedoccupation+=','+value;
		}
                else
		{
			checkedoccupation_arr=checkedoccupation.split(',');
                        checkedoccupation='';
                        for(var i=0;i<checkedoccupation_arr.length;i++)
                        {
                                if(checkedoccupation_arr[i]==value)
                                {
                                        checkedoccupation_arr[i]='';
                                }
                        }
                        for(var i=0;i<checkedoccupation_arr.length;i++)
                        {
                                if(checkedoccupation_arr[i]!='')
                                        checkedoccupation+=','+checkedoccupation_arr[i]
                        }
                        //checkedoccupation=checkedoccupation.replace(value,"");
		}
                checkedoccupation=checkedoccupation.replace(",,",",");
                if(checkedoccupation.indexOf(',')==0)
                        checkedoccupation=checkedoccupation.replace(",","");
                val.value=checkedoccupation;
	}
	if(where.name=='income')
	{
                var elem = document.getElementsByName('chkincome');
		var val=frame.document.form_search_clustering.income;
		if(objchk.checked)
		{
			if(value=='All')
				checkedincome=''
			else
	                	checkedincome+=','+value;
		}
                else
		{
			checkedincome_arr=checkedincome.split(',');
			checkedincome='';
			for(var i=0;i<checkedincome_arr.length;i++)
			{
				if(checkedincome_arr[i]==value)
				{
					checkedincome_arr[i]='';
				}
			}
			for(var i=0;i<checkedincome_arr.length;i++)
                        {
				if(checkedincome_arr[i]!='')
					checkedincome+=','+checkedincome_arr[i]
			}
			//checkedincome=checkedincome.replace(value,"");
		}
                checkedincome=checkedincome.replace(",,",",");
                if(checkedincome.indexOf(',')==0)
                        checkedincome=checkedincome.replace(",","");
                val.value=checkedincome;
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
			checkbox_disabled_cluster(where.name,'disab');
		}
		else if(!elemchk_first.checked && i==0)
		{
			checkbox_disabled_cluster(where.name,'enab');
		}
		if(isChecked && !elemchk_first.checked && i>0)
		{
		 	checkedList+= elemchk.value+';';
                        j++;
		}

	}
	if(j>0){
		checkedList = checkedList.substring(0,checkedList.length-1);
		//where.value = checkedList;
	} else {
			if(where.name=='occupation')
				where.value = "All";
			else if(where.name=='edu')
				where.value = "All";
			else if(where.name=='income')
				where.value = "All";
				
	}
	frame.document.getElementById(where.name).disabled = false;
}
function checkbox_disabled_cluster(item,type)
{
	eval('frame_id_1=window.parent.document.getElementById(\'frame_clustering\').contentWindow.document');
	if(item=='occupation')
	{
		var elem = document.getElementsByName('chkoccupation');
		var n = eval(elem);
	        n = n.length;

		if(type=='disab')
		{
			frame_id_1.form_search_clustering.occupation.value='All';
                        //frame_id_1.form_search_clustering.occupation_val.value='';
                        checkedoccupation='';

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
	if(item=='edu')
        {
                var elem = document.getElementsByName('chkedu');
                var n = eval(elem);
                n = n.length;
                                                                                                                             
                if(type=='disab')
                {
			frame_id_1.form_search_clustering.edu.value='All';
	                //frame_id_1.form_search_clustering.edu_val.value='';
        	        checkededu='';

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
	if(item=='income')
        {
                var elem = document.getElementsByName('chkincome');
                var n = eval(elem);
                n = n.length;
                                                                                                                             
                if(type=='disab')
                {
			frame_id_1.form_search_clustering.income.value='All';
                        //frame_id_1.form_search_clustering.income_val.value='';
			
                        checkedincome='';
			for(var i=1;i<n;i++)
			{
				elem[i].checked=false;
				elem[i].disabled=true;
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
/*function remove_text(item,frame_id_1)
{
	if(item=='edu')
	{
		var val=frame_id_1.form_search_clustering.edu;
		val.value='All';
		frame_id_1.getElementById('edu').value='All edus';
	}
	if(item=='occupation')
        {
                var val=frame_id_1.form_search_clustering.occupation;
                val.value='All';
                frame_id_1.getElementById('occupation').value='All Communities';
        }
	if(item=='income')
        {
                var val=frame_id_1.form_search_clustering.income;
                val.value='All';
                frame_id_1.getElementById('income').value='All Cities';
        }
}*/
function reset_cluster(item,frame_id_1)
{
	checkbox_disabled_cluster(item,'enab');
	
	if(item=='edu')
	{
		var check=document.getElementsByName("chkedu");
        	for(var i=0;i<check.length;i++)
                	check[i].checked=false;		
		var val=frame_id_1.form_search_clustering.edu;
                val.value='';
		checkededu='';
	}
	if(item=='occupation')
        {
                var check=document.getElementsByName("chkoccupation");
                for(var i=0;i<check.length;i++)
                        check[i].checked=false;
		var val=frame_id_1.form_search_clustering.occupation;
                val.value='';
		checkedoccupation='';
        }
	if(item=='income')
        {
                var check=document.getElementsByName("chkincome");
                for(var i=0;i<check.length;i++)
                        check[i].checked=false;
		var val=frame_id_1.form_search_clustering.income;
                val.value='';
		checkedincome='';
        }

}
