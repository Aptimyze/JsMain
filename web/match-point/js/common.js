function removeChildrenFromNode(node)
{
        if(node == undefined && node === null)
                return;
        while (node.hasChildNodes())
                node.removeChild(node.firstChild);
}

function alertContents()
{
        if (httprequest.readyState == 4)
        {
                if (httprequest.status == 200)
                {
                        store=0;
                        var result = eval( "(" + httprequest.responseText + ")" );
                        var node=document.getElementById("narayan");
                        removeChildrenFromNode(node);
                        for(var i=0 ;i<result.i;i++)
                        {
                                var newHeading = document.createElement("li");
                                document.getElementById("narayan").appendChild(newHeading);

                                var anchor =document.createElement("a");                
                                if(i==0)
                                        anchor.className="active";
                                anchor.id='changer'+i;
                                anchor.tabIndex=i;
                                anchor.innerHTML=result[i].NAME;
                                anchor.href="#";
                                newHeading.appendChild(anchor);

                                var ulist = document.createElement("ul");
                                ulist.id='changed'+i;
                                ulist.className="address";
                                if(i==0)
                                        ulist.style.display='block'
                                else
                                        ulist.style.display='none'
                                newHeading.appendChild(ulist);

                                var list = document.createElement("li");
                                ulist.appendChild(list);

                                var span1 =document.createElement("span")
                                span1.className="black";
                                span1.innerHTML="Address of matchpoint center in ";
                                list.appendChild(span1);

                                var span2 =document.createElement("span")
                                span2.className="gry";
                                span2.innerHTML=result[i].NAME+"<br/><br/>"+result[i].ADDRESS+"<br/><br/>";
                                list.appendChild(span2);
                                
                                var span3 =document.createElement("span")
                                span3.className="fr";
                                list.appendChild(span3);
                                
                                var anchor2 =document.createElement("a");
                                anchor2.className="view_map";
                                anchor2.innerHTML="View Map directions";
                                anchor2.href="#"
                                anchor2.coords=result[i].LATITUDE+","+result[i].LONGITUDE;
                                anchor2.id="anched"+i;
                                span3.appendChild(anchor2);
                        }                       
                        dropper();
                        anchorji();
                }
                else 
                {
                        alert('There was a problem with the request.Please refresh the webpage');
                }
         }
}
function jnyl()
{
         var poststr =  "city=" + document.getElementById("lynj").value + "&submit=change city";
        //alert(poststr);        
        document.getElementById("city_menu").innerHTML=document.getElementById("lynj").value;
        createAjaxObj('index.php', poststr);       
}
function createAjaxObj(url,parameters)
{
        httprequest=false
        if (window.XMLHttpRequest)
        { // if Mozilla, Safari etc
                httprequest=new XMLHttpRequest()
                if (httprequest.overrideMimeType)
                        httprequest.overrideMimeType('text/html')
        }
        else if (window.ActiveXObject)
        { // if IE
                try
                {
                        httprequest=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                httprequest=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e){}
                }
        }
        if (!httprequest)
        {
                 alert('Cannot create XMLHTTP instance');
                return false;
        }

        httprequest.onreadystatechange = alertContents;
        httprequest.open('POST', url, true);
        httprequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httprequest.setRequestHeader("Content-length", parameters.length);
        httprequest.setRequestHeader("Connection", "close");
        httprequest.send(parameters);
}
function anchorji()
{
        for(var j=0;j<(document.getElementById("narayan").getElementsByTagName("li").length/2);j++)
        {       
                a=document.getElementById("anched"+j);
                a.onclick=function(event,a)//to be tested
                {
                        var coords = this.coords.split(",");
			if(document.getElementById("a1"))
	                        point_it(event,'l');
			else
				point_it(event,'');
                        ReverseContentDisplay('u9',coords[0],coords[1]);
                        return false;
                }
        }
}
function validation()
{
        no_error();
	
        if(name_check(document.form2.first_name.value)||name_check(document.form2.last_name.value))
        {
		document.getElementById("span2").innerHTML="Name cannot contain special characters and numbers";
	        document.getElementById("li2").style.display='block';
	}
	else if(trim(document.form2.first_name.value)=="")
	{
                document.getElementById("span2").innerHTML="Please enter the first name atleast";
                document.getElementById("li2").style.display='block';		
	}
        if(!checkemail(document.form2.email.value))
        {
                document.getElementById("span3").innerHTML="Please enter email address in proper format";
                document.getElementById("li3").style.display='block';   
        }
        else if(trim(document.form2.email.value)=="")
        {
                document.getElementById("span3").innerHTML="Please enter an email";
                document.getElementById("li3").style.display='block';
        }
        if(document.form2.location.value=="")
                document.getElementById("li4").style.display='block';
        if(trim(document.form2.phone.value)=="" && trim(document.form2.mobile.value)=="")
                document.getElementById("li6").style.display='block';
        else if(isNaN(document.form2.mobile.value)&&trim(document.form2.phone.value)=="")
        {
                document.getElementById("li6").style.display='block';
                document.getElementById("span6").innerHTML="Please enter digits only";
        }
        else if((trim(document.form2.mobile.value).length < 10)&&trim(document.form2.phone.value)=="")
        {
                document.getElementById("li6").style.display='block';
                document.getElementById("span6").innerHTML="Please enter a valid 10 digit mobile number";
        }
        else if(isNaN(document.form2.phone.value)&&(trim(document.form2.mobile.value).length<10||isNaN(document.form2.mobile.value)))
        {
                document.getElementById("li5").style.display='block';
                document.getElementById("span5").innerHTML="Please enter digits only";
        }
        else if((trim(document.form2.phone.value).length < 6)&&(trim(document.form2.mobile.value).length<10||isNaN(document.form2.mobile.value)))
        {
                document.getElementById("li5").style.display='block';
                document.getElementById("span5").innerHTML='Please enter a minimum six digit phone number';
        }
        else if((trim(document.form2.state_code.value).length < 3 || isNaN(document.form2.state_code.value))&&(trim(document.form2.mobile.value).length<10||isNaN(document.form2.mobile.value)))
        {
                document.getElementById("li5").style.display='block';
                document.getElementById("span5").innerHTML='Please enter a valid STD code';
        }
        
        TestFileType(document.form2.biod.value,['doc','docx'] );
        check_error();
        if(flag>0)
                return false;               
}
function check_error()
{
        for(i=1; i<8 ;i++)
                if(document.getElementById("li"+i).style.display=='block')
                        flag++;
}
function no_error()
{
        for(i=1; i<8 ;i++)
                document.getElementById("li"+i).style.display='none';
        flag=0;
}

function name_check(str)
{
        var invalid =0;
        ValidChars = "abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for(i=0; i< str.length;i++)
        {
                str_char = str.charAt(i);
                if(ValidChars.indexOf(str_char) == -1)
                        invalid = 1;
        }
        if(invalid==1)
                return true;

        return false;
}
function checkemail(emailadd)
{
        var result = false;
        var theStr = new String(emailadd);
        var index = theStr.indexOf("@");
        if (index > 0)
        {
        var pindex = theStr.indexOf(".",index);
        if ((pindex > index+1) && (theStr.length > pindex+2))
                result = true;
        }

        return result;
}
function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}
function TestFileType( fileName, fileTypes ) 
{
	if(!fileName)
		return ;

	dots = fileName.split(".")
	fileType = "." + dots[dots.length-1];

	if(fileTypes.join(".").indexOf(fileType) == -1) 
	document.getElementById("li7").style.display='block';
}
function assign_code()
{
        document.form2.state_code.value=document.form2.location.options[document.form2.location.selectedIndex].className;
}
function dropper()
{
        for(var j=0;j<(document.getElementById("narayan").getElementsByTagName("li").length/2);j++)
        {       
                a=document.getElementById("changer"+j);
                a.onclick=function(a)//to be tested
                {
                        j=this.tabIndex;
                        if(j!=store)
                        {
                                document.getElementById("changer"+j).className='active';
                                document.getElementById("changer"+store).className='';
                                document.getElementById("changed"+j).style.display='block';
                                document.getElementById("changed"+store).style.display='none';
                                store=j;
                                return false;
                        }
                }
        }

}


