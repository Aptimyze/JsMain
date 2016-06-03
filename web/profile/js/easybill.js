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
function alertContents() 
{
        if (httprequest.readyState == 4) 
        {
                if (httprequest.status == 200) 
                {
                        var result = eval( "(" + httprequest.responseText + ")" );
                        document.getElementById("innershow").innerHTML ="Easy Bill Center in "+ result.locality + ", "+ result.city;
                        document.getElementById('showhide').style.display = 'block';
                        var node=document.getElementById("narayan");
                        removeChildrenFromNode(node);
                        for(var i=0 ;i<result.i;i++)
                        {
                                var newHeading = document.createElement("div");
                                newHeading.style.border="1px #CCCCCC";
                                newHeading.style.borderBottomStyle="solid";
                                newHeading.style.margin="0";
                                newHeading.style.paddingBottom="10px";
                                newHeading.style.width="420px";
                                newHeading.className="lf";

                                var oldHead =document.createElement("div");
                                oldHead.style.padding="6px 0px";
                                oldHead.className="gray b";
                                oldHead.innerHTML =result[i].SHOP_NAME
                                newHeading.appendChild(oldHead)

                                var oldspan =document.createElement("span");
                                oldspan.innerHTML =result[i].ADDRESS+'<br>'+result.locality+', '+result.city
                                 newHeading.appendChild(oldspan)

                                document.getElementById("narayan").appendChild(newHeading);
                        }

                } 
                else 
                {
                        alert('There was a problem with the request.');
                }
         }
}
function removeChildrenFromNode(node)
{
   if(node == undefined &&
        node === null)
   {
      return;
   }
   var len = node.childNodes.length;
        while (node.hasChildNodes())
        {
          node.removeChild(node.firstChild);
        }
}
function get() 
{
      var poststr ="CRM=" + document.form1.CRM.value + "&city=" +  document.form1.city.value  + "&checksum=" + document.form1.checksum.value +"&Submit="+ document.form1.Submit.value+ "&locality=" + document.form1.locality.value  ;
      createAjaxObj('revamp_easy_bill.php', poststr);
}

function PopLocality()
{
        docF = document.form1;
        var city = docF.city.value;
        var city_trim = city.split("|X|");
        var locality_all = city_trim[1].split("|#|");
        docF.locality.options.length = 0;
        for(var k=0; k<locality_all.length; k++)
        {
                var opt = new Option();
                opt.text = locality_all[k];
                opt.value = locality_all[k];
                docF.locality.options[k] = opt;
        }
}
function jinitializeBody() 
{//alert("------")
        PopLocality();
//alert("------")
         var dareDiv = document.getElementById("citypop")
                 dareDiv.onchange=PopLocality;
 }

function getOLs()
{
        if(typeof window.onload=='function')
        { // test to see if onload has been set
                if(typeof ol_ol=='undefined')
                        ol_ol=new Array(); // test if array variable already exists
                        ol_ol.push(window.onload); // this captures any previous onload function
        }
}


