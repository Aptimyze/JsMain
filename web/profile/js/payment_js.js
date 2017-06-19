var zz=0;
var mus;
var z=0;
var flag =0;
// this function needs to load before any window.onload assignments
// // call it before each window.onload assignment in other scripts
function getOLs()
{
        if(typeof window.onload=='function')
	{ // test to see if onload has been set
		 if(typeof ol_ol=='undefined')
                        ol_ol=new Array(); // test if array variable already exists
                        ol_ol.push(window.onload); // this captures any previous onload function
        }
}



getOLs()
function load1()
{
        mus =PRICE;
        print();
        if(document.getElementById('brem'))
                document.getElementById('brem').onclick=removeb;
        if(document.getElementById('trem'))
                document.getElementById('trem').onclick=removet;
        if(document.getElementById('arem'))
                document.getElementById('arem').onclick=removea;
        if(document.getElementById('mrem'))
                document.getElementById('mrem').onclick=removem;

}

function displaylist(showOrHide)
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
                memlayer='<div class="bnk fl t11"><i class="tp fl"></i><a href="#" onClick="displaylist(\'H\');return false;" class="b blink fr mr_10">Close [x]</a><p class="clr"></p>';
                memlayer+='<ul id="pop_bnk"><li>Allahabad Bank</li><li>Andhra Bank</li><li>Bank of Baroda</li><li>Bank of India</li><li>Bank of Maharashtra</li><li>Bank of Punjab Ltd</li><li>Bank of Rajasthan</li><li>Barclays Bank Plc</li><li>Canara Bank</li><li>Catholic Syrian Bank Ltd</li><li>Central Bank of India</li><li>Centurion Bank</li><li>Citibank</li><li>City Union Bank Ltd</li><li>Corporation Bank</li><li>Cosmos Co-Op Bank Ltd</li><li>Dena Bank</li><li>Deutsche Bank Ag,</li><li>Development Credit Bank Ltd</li><li>Dhanalakshmi Bank Ltd</li><li>Federal Bank Ltd</li><li>HDFC Bank</li></ul><ul id="pop_bnk"><li>HSBC Limited</li><li>Indian Bank</li><li>Indian Overseas Bank</li><li>Indusind Bank Ltd</li><li>ING Vysya Bank Limited</li><li>J & K Bank</li><li>Karnataka Bank Limited</li><li>Karur Vysya Bank Ltd</li><li>Kotak Mahindra Bank Ltd</li><li>Oriental Bank of Commerce</li><li>Punjab National Bank</li><li>Saraswat Co-Operative Bank Ltd</li><li>South Indian Bank Ltd</li><li>Standard Chartered Bank</li><li>State Bank of India</li><li>Syndicate Bank</li><li>Tamilnadu Mercantile Bank Ltd</li><li>The Royal Bank of Scotland</li><li>UCO Bank</li><li>Union Bank of India</li><li>United Bank of India</li><li>Vijaya Bank</li></ul> <i class="btm fl"></i></div>';

var hnt_bx=document.getElementById('hintbox1');                
hnt_bx.innerHTML=memlayer;
                check_window("displaylist('H')");
                hnt_bx.style.display="block";
                hnt_bx.style.position="absolute";
                common_check=1;
                function_to_call="displaylist('H')";
        }
}

function sendto()
{
        if(document.getElementById("r5").checked ==true)
        {
                document.form41.action="revamp_easy_bill.php";
        }
        else if(document.getElementById("r6").checked ==true)
        {
                document.form41.action="/P/pg/transecute/chequedrop.php";
        }
        else if(document.getElementById("r7").checked ==true)
        {       
                document.form41.action="/P/pg/order_paypal.php";
        }
        else if(document.getElementById("r1").checked ==true)
        {       
                if(CURRENCY=='RS')
                        document.form41.action="/P/pg/order_payseal.php";
                else
		{
                        if(GO_PAYSEAL==1)
                                document.form41.action="/P/pg/order_payseal.php";
                        else
                                document.form41.action="/P/pg/orderonline.php";
                }
        }
        else if(document.getElementById("r2").checked ==true)
        {
                document.form41.action="/P/pg/orderonline.php";
        }
        else if(document.getElementById("r3").checked ==true)
        {       
                document.form41.action="/P/pg/orderonline.php";
        }
        else if(document.getElementById("r4").checked ==true)
        {       
                document.form41.action="/P/pg/orderonline.php";
        }
        else if(document.getElementById("r8").checked ==true)
        {       
                document.form41.action="/P/pg/orderonphone.php";
        }
        /*else if(document.getElementById("r9").checked ==true)
        {       
                document.form41.action="/P/pg/orderonline.php";
        }*/
        else if(document.getElementById("r10").checked ==true)
        {       
                document.form41.action="/P/pg/order_payseal.php";
        }
        document.form41.submit();

}
function removeb()
{
        document.getElementById('bdiv').style.display="none";
        var tt=0;
        var mm=2;
        if(document.getElementById('fort') && (document.getElementById('tdiv').style.display!="none"))
        {
                document.getElementById('fort').innerHTML=FORB;
                tt=1;   
                mm=3;
        }
        if(document.getElementById('formm') && tt && (document.getElementById('mdiv').style.display!="none"))
        {
                document.getElementById('formm').innerHTML=mm;
                mm++;
        }
        else if(document.getElementById('formm') && (document.getElementById('mdiv').style.display!="none"))
        {
                document.getElementById('formm').innerHTML=FORB;
                mm=3;
        }
        
        if(document.getElementById('fora') && flag==2 && (document.getElementById('adiv').style.display!="none"))
        {
                document.getElementById('fora').innerHTML=FORB;
                
        }
        else if(document.getElementById('fora') && (document.getElementById('adiv').style.display!="none"))
        {
                document.getElementById('fora').innerHTML=mm;
        }

        var bprice=BPRICE;
	var bb=","+BOLD;
        str=str.replace(bb,"");
        if(bprice)
                mus=mus-parseInt(bprice);
        flag++;
        print();
        return false;
}

function removet()
{
        document.getElementById('tdiv').style.display="none";
        var tt=document.getElementById('fort').innerHTML;
        if(document.getElementById('formm')  && (document.getElementById('mdiv').style.display!="none"))
        {
                document.getElementById('formm').innerHTML=tt;
                tt++;
        }       
        if(document.getElementById('fora') && (document.getElementById('adiv').style.display!="none"))
        {
                document.getElementById('fora').innerHTML=tt;
        }
        flag++;
        var tprice=TPRICE;
	var tt=","+RB;
        str=str.replace(tt,"");
        if(tprice)
                mus=mus-parseInt(tprice);
        print();
        return false;
}

function removea()
{
        document.getElementById('adiv').style.display="none";
        
        var aprice=APRICE;
	var aa=","+ASTRO;
        str=str.replace(aa,"");
        if(aprice)
                mus=mus-parseInt(aprice);
        print();
        return false;
}
function removem()
{
        document.getElementById('mdiv').style.display="none";

        var mm=document.getElementById('formm').innerHTML;
        if(document.getElementById('fora') && (document.getElementById('adiv').style.display!="none"))
        {
        
                document.getElementById('fora').innerHTML=mm;
        }
        flag++;

        var mprice=MAPRICE;
	var mm=","+MATRO;
        str=str.replace(MATRO,"");
        if(mprice)
                mus=mus-parseInt(mprice);
        print();
        return false;
}
function print()
{       
        if(mus == 0)
                window.location.href="mem_comparison.php?checksum="+CHECKSUM;
        if(document.getElementById('csdiv'))
        {
                var ram="mem_comparison.php?checksum="+CHECKSUM+"&var="+str;
                document.getElementById('aid').href=ram;
                document.getElementById('aid1').href=ram;
        }
        document.getElementById('services').value=str;
        document.getElementById('pl').value=mus;
        z=0;
        if(mus<10)
                zz=1;
        else
                zz=Math.floor(mus/10);
        priunt();
}
function priunt()
{
        if(zz<(mus-z))
        {
		if(CURRENCY=='RS')
                	document.getElementById('total').innerHTML="Rs. "+z;
		else
                	document.getElementById('total').innerHTML=z+" $";
			
                z=z+zz;
                if(z<mus)
                        setTimeout('priunt()',100);
        }
        else
        {
		if(CURRENCY=='RS')
                	document.getElementById('total').innerHTML="Rs. "+mus;
		else
                	document.getElementById('total').innerHTML=mus+" $";
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

function check_window(close_func)
{
        //This function is used to return value false if a tag is clicked.
        if (typeof(close_func)=='undefined' || typeof(close_func)=='object' )
        {
                if(common_check==1 && function_to_call!=""  && imediate==0)
                {
                        eval(function_to_call);
                }
                if(imediate==1)
                        imediate=0;

                if(!e) var e=window.event;
                if(!e) var e=close_func;
                var tg = (window.event) ? e.srcElement : e.target;

                if(tg.className=='thickbox')
                        return false;

                if(tg.nodeName=='INPUT')
                {
                        if(tg.value=="Express Interest - Free")
                                return false;
                        tg2=tg.parentNode;
                        if(tg2.nodeName!='A')
                                return true;
                }
                if(tg.nodeName=='DIV' || tg.nodeName=='SPAN' || tg.nodeName=='TD' || tg.nodeName=='TR' || tg.NodeName=='TABLE' || tg.nodeName=='IMG')
                {
                        tg=tg.parentNode;
                        if(tg.nodeName=='A' && !tg.onclick)
                                return true;
                        if(tg.nodeName=='A')
                                return false;
                        imediate=0;
                }
                else if(tg.nodeName=='A')
                {
                        if(!tg.onclick)
                                return true;
                        return false;
                }
        }

        if(typeof(close_func)!='object' && typeof(close_func)!='undefined')
        {
                if(common_check==1 && function_to_call!="" && function_to_call!=close_func )
                {
                        eval(function_to_call);

                }

        }
        if(typeof(close_func)!='object' && typeof(close_func)!='undefined')
        {
                if(close_func==function_to_call)
                        same_function_call=1;
                imediate=1
                return 1
        }
        if(imediate)
        {
                imediate=0
                return false;
        }
        if(same_function_call)
        {
                same_function_call=0;
                return false;
        }
        if(common_check==1)
        {
                if(function_to_call)
                        eval(function_to_call);
        }
        return true;
}


