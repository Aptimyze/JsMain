
function ltrim1(str) {
var l1=str.indexOf(" ");
return str.substring(l1,str.length); 
}

function ltrim(str, chars) {
        chars = chars || "\\s";
        return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function obtainvalue(val)
{
        var vak;
        var radios = document.getElementsByName(val);
        for(var i=0;i<radios.length;i++)
        {
                if(radios[i].checked)
                {
                        vak = radios[i];
                        break;
                }
        }
        return vak;
}


function onloadfun()
{
        var flag=12;
        var unlimit='unlimited';
        if(main_service==1)
	{
                document.getElementById('d1').style.display='none';
                document.getElementById("cb2").disabled=true;
                document.getElementById("asd").style.display = "none";
	}
	else
	{
        	document.getElementById('cb1').checked = true;
		var for_asd3;

		for(var i=0; i < document.form.main_service.length; i++)
		{
			if(document.getElementById("ankita" + i).value==main_service)
			{
				var unlimit1;
				unlimit=document.getElementById("ankita" + i).alt;
				var title;
				title=document.getElementById("ankita" + i).title;
				var service;
				service=main_service;
				var dprice;
				dprice=document.getElementById("ankita" + i).tabIndex;
				comm(unlimit,title,service,dprice);
				document.getElementById("ankita" + i).checked =true;
				next++;
			}

		}
	}
	if((obtainvalue('main_service')))
	{
		var duration = obtainvalue('main_service').value.substring(1);
		if(obtainvalue('main_service').value.substring(1) == "L")
                                duration = 12;
        }
        else
                duration = '12';
        getAddOn(duration);

	if(cb3=='0')
		document.getElementById('d3').style.display='none';
	else
                BL(duration);

        if(cb5!=0)
                MS();
        else
        {
                if(main_service==1)
		{
			if(CURRENCY=='RS')
			{
				document.getElementById('amprice').innerHTML="Rs. "+MP;
				document.getElementById('matrio').innerHTML="( Rs. "+MP+" )";
			}
			else
			{
				document.getElementById('amprice').innerHTML=MP+" $ "
				document.getElementById('matrio').innerHTML="( "+MP+" $ )";
			}
                        document.getElementById('cb5').value ='M';
                 }
	}
	
        if(cb2!=0)
	{
		var dur=cb2.substring(1);
                RB(duration,0,0,dur);
		document.getElementById('tid'+dur).checked=true;
	}
	else
		RB(duration,0,1);

	if(cb4!=0)
	{
		var dur=cb4.substring(1);
                AC(duration,0,0,dur);
		document.getElementById('aid'+dur).checked=true;
 	}
	else
		AC(duration,0,1);
        set_count();
        if(!LOGIN)
	{
                document.getElementById('pr1').onclick = function()
                {
			var fin_str;
			if((obtainvalue('main_service')))
				fin_str = obtainvalue('main_service').value;
			if(document.getElementById('cb3').checked)
				fin_str = fin_str+","+document.getElementById('cb3').value;
			if(document.getElementById('cb5').checked)
				fin_str = fin_str+",M";
			if(document.getElementById('cb2').checked)
				fin_str = fin_str+","+obtainvalue('T_arr').value
			if(document.getElementById('cb4').checked)
				fin_str = fin_str+","+obtainvalue('A_arr').value
			var url_str='login.php?SHOW_LOGIN_WINDOW=1&now=1&mem_str='+fin_str;
                        $.colorbox({href:url_str});   
			return false;
                }
        }
	else
	{
		document.getElementById('pr1').onclick = function()
                {
			document.form.submit();
                }

	}
        document.getElementById('sl1').onmouseover=func1;
        function func1()
        {
		document.getElementById("rs_lay").style.visibility = "visible";
        }       
        document.getElementById('sl1').onmouseout=func2;
        function func2()
        {
		document.getElementById("rs_lay").style.visibility = "hidden";
        }
        
        if(document.getElementById('aa'))
                document.getElementById('aaa').innerHTML = document.getElementById('aa').title;
                        set_count();
        for(var k=0; k < document.form.A_arr.length; k++)
        {
                document.getElementsByName('A_arr')[k].onclick=function()
                {
                        if(obtainvalue('A_arr'))
                        {
                                var str=obtainvalue('A_arr').id;
                                var duration=str.substring(3,str.length);
                        }
                        AC(duration,1);
                        set_count();
                }
        }
        for(var k=0; k < document.form.T_arr.length; k++)
        {
                document.getElementsByName('T_arr')[k].onclick=function()
                {
                        if(obtainvalue('T_arr'))
                        {
                                var str=obtainvalue('T_arr').id;
                                var duration=str.substring(3,str.length);
                        }
                        RB(duration,1);
                        set_count();
                }
        }

        var for_asd1;
        for(var i=0; i < document.form.main_service.length; i++)
        {
                document.getElementById("ankita" + i).onclick=function()
                {
			if(!document.getElementById('cb1').checked)
				enable_main();
                        document.getElementById('cb1').checked = "true";
                        if(obtainvalue('main_service'))
                        {
                                var unlimit1;
                                unlimit=obtainvalue('main_service').alt;
                                var title;
                                title=obtainvalue('main_service').title;
                                var service;
                                service=obtainvalue('main_service').value;
                                var dprice;
                                dprice=obtainvalue('main_service').tabIndex;
                                comm(unlimit,title,service,dprice);
                        }
                        if(obtainvalue('main_service'))
                                duration = obtainvalue('main_service').value.substring(1);
                        if(obtainvalue('main_service').value.substring(1) == "L")
                                duration = 12;
                        getAddOn(duration);
			if(document.getElementById('cb3').checked)
                        	BL(duration);
			else
                        	BL(duration,1);
			if(document.getElementById('cb2').checked)
                        	RB(duration);
			else
				RB(duration,0,1);
                        if(duration=='12' || duration=='9')
                                MS();
                        else
                                MS(1);
                        if(document.getElementById('cb4').checked)
                                AC(duration);
                        else
                                AC(duration,0,1);
                        set_count()     
                        
                }       
        }
        if(document.getElementById('aa'))
        document.getElementById('aa').onclick=function()
        {
                for(var q=0;q<document.form.main_service.length;q++)
                {
                        if(document.getElementById('aa').name == document.getElementsByName('main_service')[q].value)
                        {
                                document.getElementsByName('main_service')[q].checked = true;
                                document.getElementById('cb1').checked = true;
                                document.getElementById("d1").style.display = "inline";
                                if(DISC=='Y')
                                {
                                        document.getElementById("asd").style.display = "";
                                }

                                var unlimit1;
                                unlimit=document.getElementsByName('main_service')[q].alt;
                                var title;
                                title=document.getElementsByName('main_service')[q].title;
                                var service;
                                service=document.getElementsByName('main_service')[q].value;
                                var dprice;
                                dprice=document.getElementsByName('main_service')[q].tabIndex;
                                comm(unlimit,title,service,dprice);
                                if(obtainvalue('main_service'))
                                        var substring = obtainvalue('main_service').value.substring(1);
                                if(obtainvalue('main_service').value.substring(1) == "L")
                                        substring = 12;
                                document.getElementById("cb2").disabled=false;
				duration=substring;
				getAddOn(duration);
				BL(duration);
				RB(duration);
	
				if(duration=='12')
					MS();
				else
					MS(1);
				AC(duration,0,1);


                        }
                }
                set_count();
                var v="'" + window.location + "'";
                if(v.indexOf("c4")==-1)
                        window.location = window.location + "#c4";
                else
                        window.location = window.location;
        }
        document.getElementById('cb4').onclick=function()
        {
                if(document.getElementById('cb4').checked == false)
                {
                        if(obtainvalue('A_arr'))
                        {
                                document.getElementById('d4').style.display = "none";
                                obtainvalue('A_arr').checked = false;            
                        }
                }
                else
                {
			if(obtainvalue('main_service'))
				duration=obtainvalue('main_service').value.substring(1);
			else
                                duration=12;
				
                        if(duration =='L')
                                duration=12;
			
		/*	if(duration);
			else
			{
				if(!obtainvalue('A_arr'))
					document.getElementsByName('A_arr')[0].checked = true;
				var str=obtainvalue('A_arr').id;
				duration=str.substring(3,str.length);
			}*/
                        AC(duration,1);
                }
                set_count();    
        }
        document.getElementById('cb1').onclick=rahwedanu
        function rahwedanu()
        {
                if(obtainvalue('main_service'))
                {
                        obtainvalue('main_service').checked = false;            
                }
		duration=12;
                var radiosay = document.getElementsByName("bold");
                if(document.getElementById('cb1').checked == false)
                {
                        document.getElementById('cb2').checked = false;
                        document.getElementById('cb3').checked = false;
                        RB(duration,0,1);
			if(CURRENCY=='RS')
			{
                        	document.getElementById('amprice').innerHTML="Rs. "+MP+" "
                        	document.getElementById('matrio').innerHTML="( Add Rs. "+MP+" )";
			}
			else
			{
                        	document.getElementById('amprice').innerHTML=MP+" $ "
                        	document.getElementById('matrio').innerHTML="( Add "+MP+" $ )";
			}

                        document.getElementById('cb5').value ='M';
                        document.getElementById("d1").style.display = "none";
                        document.getElementById("d3").style.display = "none";
                        document.getElementById("d2").style.display = "none";
                        document.getElementById("asd0").style.display = "none";
			document.getElementById("cb2").disabled=true;
                        document.getElementById("cb3").disabled=true;
                        document.getElementById("cb3").disabled=true;
			for(var i=0;i<document.form.T_arr.length;i++)
			{
                        	document.form.T_arr[i].disabled=true;
			}
			tt=1;
			if(document.getElementById('cb5').checked)
			{
				document.getElementById('mp').innerHTML=tt;
				MS();	
				document.getElementById('cb5').value='M';
				tt++;
			}
			if(document.getElementById('cb4').checked)
				document.getElementById('ac').innerHTML=tt;
			else
                                AC(duration,0,1);
                }
                else
                {
                        var unlimit1;
                        unlimit=document.getElementsByName('main_service')[1].alt;
                        var title;
                        title=document.getElementsByName('main_service')[1].title;
                        var service;
                        service=document.getElementsByName('main_service')[1].value;
                        var dprice;
                        dprice=document.getElementsByName('main_service')[1].tabIndex;
                        comm(unlimit,title,service,dprice);
			enable_main();

                        document.getElementById("ankita1").checked =true;
			if((Fest==1) || (Spec!=0) || (DISC=='Y'))
	                        document.getElementById("asd0").style.display = "block";
			tt=2;
			if(document.getElementById('cb3').checked)
                        {
                                document.getElementById('bl').innerHTML=tt;
                                tt++;
                        }
			if(document.getElementById('cb2').checked)
                        {
                                document.getElementById('rb').innerHTML=tt;
                                tt++;
                        }
			else
				RB(duration,0,1);
			document.getElementById('matrio').innerHTML="( Free )";
			if(CURRENCY=='RS')
				document.getElementById('amprice').innerHTML="Rs. "+m_price;
			else
				document.getElementById('amprice').innerHTML= m_price+" $ ";
			if(document.getElementById('cb5').checked)
			{
				document.getElementById('mp').innerHTML=tt;
				tt++;
			}
			if(document.getElementById('cb4').checked)
				document.getElementById('ac').innerHTML=tt;
			
                }       
                set_count();    
        }
        document.getElementById('cb2').onclick=cb2f;
        function cb2f()
        {
                if(document.getElementById('cb2').checked == false)
                        remove2();
                else
                {
			if(obtainvalue('main_service'))
				duration=obtainvalue('main_service').value.substring(1);
			else
				duration=12;
                        if(duration =='L')
                                duration=12;
		/*	if(duration=='undefined');
                        else
                        {
				if(!obtainvalue('T_arr'))
					document.getElementsByName('T_arr')[0].checked = true;
				var str=obtainvalue('T_arr').id;
				var duration=str.substring(3,str.length);
				var duration=9;
			}*/
			RB(duration,1);
                }

                set_count()     
        }
        document.getElementById('cb5').onclick=function()
        {
                if(document.getElementById('cb5').checked==true)
                        MS();
                else
                        remove5();
                set_count()     
        }
        document.getElementById('cb3').onclick=bold;
        function bold()
        {
                if(document.getElementById('cb3').checked==false)
                        remove3();
                else
                {
                        BL(duration);
                        set_count();
                }
        }
        document.getElementById('r1').onclick=function()
        {
                document.getElementById('d1').style.display='none';
                document.getElementById('cb1').checked=false;
                rahwedanu();
                return false;
        }
        document.getElementById('r2').onclick= remove2;
        function remove2()
        {
                document.getElementById('d2').style.display='none';
                document.getElementById('cb2').checked=false;
                obtainvalue('T_arr').checked = false;
                var tt=document.getElementById('rb').innerHTML;
                if(document.getElementById('cb5').checked)
                {
                        document.getElementById('mp').innerHTML=tt;
                        tt++;
                }
                if(document.getElementById('cb4').checked)
                        document.getElementById('ac').innerHTML=tt;

                set_count();
                return false;
        }
        document.getElementById('r3').onclick=remove3;
        function remove3()
        {
                document.getElementById('d3').style.display='none';
                document.getElementById('cb3').checked=false;
                var tt=document.getElementById('bl').innerHTML;

                if(document.getElementById('cb2').checked)
                {
                        document.getElementById('rb').innerHTML=tt;
                        tt++;
                }
                if(document.getElementById('cb5').checked)
                {
                        document.getElementById('mp').innerHTML=tt;
                        tt++;
                }
                if(document.getElementById('cb4').checked)
                        document.getElementById('ac').innerHTML=tt;

                set_count();

                return false;
        }
        document.getElementById('r5').onclick=remove5;
        function remove5()
        {
                document.getElementById('d5').style.display='none';
                document.getElementById('cb5').checked=false;
                var tt=document.getElementById('mp').innerHTML;

                if(document.getElementById('cb4').checked)
                        document.getElementById('ac').innerHTML=tt;
                set_count();
                return false;
        }
        document.getElementById('r4').onclick=function()
        {
                document.getElementById('d4').style.display='none';
                document.getElementById('cb4').checked=false;
                obtainvalue('A_arr').checked = false;
                set_count();
                return false;
        }
}
var zz=0;
var mus;
var z=0;
function set_count()
{
        z=0;

        var count=1;
        var sum=0;
        if(document.getElementById('cb1').checked == true)
        {
                count++; 
                sum+=parseInt(obtainvalue('main_service').tabIndex);
        }
        if(document.getElementById('cb2').checked == true)
        {
                count++; 
		
		if(!obtainvalue('T_arr'))
		{
			var ss=obtainvalue('main_service').value.substring(1);
			if(ss =='L')
				ss=12;
			var s1='tid'+ss;
			document.getElementById(s1).checked='checked';
		}	
                var farray = obtainvalue('T_arr').title.split(" ");
                if(isNaN(farray[0]))
                        sum+=parseInt(farray[1]);
                else
                        sum+=parseInt(farray[0]);
        }
        if(document.getElementById('cb3').checked == true)
        {
                var farray = document.getElementById('bprice').innerHTML.split(" ");
                if(isNaN(farray[0]))
                        sum+=parseInt(farray[1]);
                else
			 sum+=parseInt(farray[0]);

        }
        if(document.getElementById('cb4').checked == true)
        {
                count++; 
		if(!obtainvalue('A_arr'))
                {
			if(obtainvalue('main_service'))
	                        var ss=obtainvalue('main_service').value.substring(1);
			else
				ss=12;
                        if(ss =='L')
                                ss=12;
                        var s1='aid'+ss;
                        document.getElementById(s1).checked='checked';
                }
                var farray = obtainvalue('A_arr').title.split(" ");
                if(isNaN(farray[0]))
                        sum+=parseInt(farray[1]);
                else
                        sum+=parseInt(farray[0]);
        }
        if(document.getElementById('cb5').checked == true)
        {
                count++;
                var farray = document.getElementById('amprice').innerHTML.split(" ");
                if(isNaN(farray[0]))
                        sum+=parseInt(farray[1]);
                else
                        sum+=parseInt(farray[0]);

        }
        if(sum == 0)
        {
                document.getElementById('d0').style.display = 'none';
                document.getElementById('pr2').style.display = 'block';
                document.getElementById('pr1').style.display = 'none';
        }
        else
        {
                document.getElementById('pr1').style.display = 'block';
                document.getElementById('pr2').style.display = 'none';
                document.getElementById('d0').style.display = 'block';
        }
        mus =sum
                if(sum<10)
                        zz=1;
                else
                        zz=Math.floor(sum/10);
                priunt();


}
function priunt(sum)
{
        if(zz<(mus-z))
        {
                document.getElementById('total').innerHTML=z;
                z=z+zz;
                if(z<mus)
                        setTimeout('priunt()',100);
        }
        else
        {
                document.getElementById('total').innerHTML=mus;
        }
}
/*function to check if a value already exists in an array*/
function in_array(needle,haystack)
{
        var found = false;
        var i1 = haystack.length;
        for(var i=0;i<i1;i++)
        {
                if(needle == haystack[i])
                {
                        found = true;
                        break;
                }
        }

        return found;
}


function comm(unlimit,title,service,dprice)
{
        var unlimit1;
        unlimit1=unlimit.split('@');
        for_asd1=unlimit1[0];
        var dur_off=new Array('P9','P6','P4','C9','C6','C4');
        var mon_off=new Array('PL','CL');
        var zzz;
        if(for_asd1.search('Unlimited')>0)
        {
                zzz=for_asd1.split('-Unlimited');
                for_asd1="Unlimited "+zzz[0];
                
        }
	else
	{
		zzz=for_asd1.split(' -');
		zzz[0]=trim(zzz[0]);
		zzz[1]=trim(zzz[1]);
		for_asd1=zzz[1]+" "+zzz[0]
	}
	 var dp=dprice;
        if(CURRENCY == 'RS')
                dprice = "Rs. "+ dprice; 
        else
                dprice =  dprice+" $";
        if(document.getElementById('dprice'))
                document.getElementById('dprice').innerHTML = dprice;
        if(unlimit1[2])
        {
                for_asd3=unlimit1[2];
                for_asd3=for_asd3.replace('\+','');
                for_asd3=ltrim1(for_asd3);
        }
	else
		unlimit1[2]="";	

        if((DISC== 'Y') || Spec)
	{
		document.getElementById('asd2').innerHTML = dprice;
			
		unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;&nbsp;&nbsp;&nbsp;"+unlimit1[1]+"</b>";
		if(Fest==1)
		{
			if(in_array(service,dur_off))
			{
				unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;"+unlimit1[1]+"</b><i class='mar_clr t12' style='font-style:normal'>&nbsp;"+ unlimit1[2]+"</i>";
				document.getElementById("asd5").style.display = "";
				var msg='Also get '+for_asd3+" on "+for_asd1+" memberships";
				document.getElementById('asd5').innerHTML=msg;
				document.getElementById('asd2').className ='ylw_bg_clr';
			}
			else if(in_array(service,mon_off))
			{
				if(DISC=='Y')
				document.getElementById("asd5").style.display = "none";
				else
				{
					unlimit1[0]=unlimit1[0]+' (After 10% Festival Discount)';
					unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;&nbsp;&nbsp;&nbsp;"+unlimit1[1]+"</b>";
					document.getElementById("asd5").style.display = "";
					var dis;
					var sp;
					if(service=='PL')
					{
						dis=document.getElementById('risdis').value;
					}
					else
						dis=document.getElementById('valdis').value;
					sp=title;
					var msg="Also get 10% more discount over this price as Festival discount, <span class=\"ylw_bg_clr\">Your Special Price "+dp+"</span>";
					if(CURRENCY == 'RS')
						dp = "Rs. "+ dp; 
					else
						dp =  dp+" $";
					document.getElementById('dprice').innerHTML =dp;
					document.getElementById('asd2').innerHTML =sp;
					document.getElementById('asd2').className ='';
					document.getElementById('asd5').innerHTML =msg;
				}
				
			}
			else
				document.getElementById("asd5").style.display = "none";
							
		} 
		if(for_asd1=='Unlimited e-Value Pack')
			document.getElementById('asd1').innerHTML='Unlimited e-Value';
		else
			document.getElementById('asd1').innerHTML=for_asd1;
	}
	else if(Fest==1)
	{
		document.getElementById("asd0").style.display = "";
		document.getElementById("asd5").style.display = "";
		if(in_array(service,mon_off))
		{
			unlimit1[0]=unlimit1[0]+" (After Festival Discount)";
			unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;&nbsp;&nbsp;&nbsp;"+unlimit1[1]+"</b>";
			var msg='Discounted (10%) price of '+for_asd1+" membership is <b class=\"ylw_bg_clr\"> "+dprice+"</b>";
		}
		else
		{
			if(in_array(service,dur_off))
			{
				unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;"+unlimit1[1]+"</b><i class='mar_clr t12' style='font-style:normal'>&nbsp;"+ unlimit1[2]+"</i>";
				var msg='Get '+for_asd3+" on "+for_asd1+" membership on this festival";
			}
			else
			{
				unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;&nbsp;&nbsp;&nbsp;"+unlimit1[1]+"</b>";
				document.getElementById("asd5").style.display = "none";
				document.getElementById("asd0").style.display = "none";
			}
		}
		document.getElementById('asd5').innerHTML="<b>"+msg+"</b>";
        }
	else
                unlimit=unlimit1[0]+"<br><b class=\"t12\">&nbsp;&nbsp;&nbsp;&nbsp;"+unlimit1[1]+"</b>";
        document.getElementById('sservice').innerHTML=" 1. "+unlimit;

}

function BL(duration,flag)
{
	if(!flag)
	{
		document.getElementById('cb3').checked = true;
		document.getElementById('d3').style.display='block';
	}
	if(CURRENCY=='RS')
	{
		document.getElementById('bold').innerHTML="( Rs. "+b_price+" )";
		document.getElementById('bprice').innerHTML="Rs. "+b_price;
	}
	else
	{
		document.getElementById('bold').innerHTML="( "+ b_price+" $ )";
		document.getElementById('bprice').innerHTML= b_price+" $";
	}
        document.getElementById('cb3').value="B"+duration;
	if(!flag)
	{
        if(document.getElementById('cb1').checked)
        {
                document.getElementById('bl').innerHTML='2';    
                tt=3;
        }
        else
        {
                document.getElementById('bl').innerHTML='1';    
		                tt=2;
        }
        if(document.getElementById('cb2').checked)
        {
                document.getElementById('rb').innerHTML=tt;     
                tt++;
        }
        if(document.getElementById('cb5').checked)
        {
                document.getElementById('mp').innerHTML=tt;     
                tt++;
        }
        if(document.getElementById('cb4').checked)
        {
                document.getElementById('ac').innerHTML=tt;     
                tt++;
        }
	}                                      
}
hide_arr= new Array();
//show_arr= new Array(3,6,9,12);
show_arr= new Array(3,4,6,9,12);
var i2=show_arr.length;
hide_arr[9]=new Array('12');
hide_arr[6]=new Array(9,12);
hide_arr[4]= new Array(6,9,12);
hide_arr[3]= new Array(4,6,9,12);
hide_arr[1]= new Array(3,4,6,9,12);
function RB(duration,flag,shrink,dur)
{
	if(!shrink)
        {
		document.getElementById('cb2').checked = true;
		document.getElementById('d2').style.display = "block";
	}
        var tid='tid'+duration;
        durr= new Array();
        if(flag)
        {
		for(var i in addon)
		{
			if(i == duration)
			{
				t_name=addon[i]['T']['name'];
				t_price=addon[i]['T']['price'];
			}
		}
        }
        else
        {
                if(duration != 12)
                {	
                        durr= hide_arr[duration];
                        var i1 = durr.length;
                        for(var i=0;i<i1;i++)
                        {
                                var a = durr[i];
                                document.getElementById('utid'+a).style.display='none';
                        }
                        for(var i=0;i<i2;i++)
                        {
                                var b=show_arr[i];
                                if(!in_array(b,durr))
                                        document.getElementById('utid'+b).style.display='inline';
                        }
                }
                else
                        for(var i=0;i<i2;i++)
                        {
                                var b=show_arr[i];
                                document.getElementById('utid'+b).style.display='inline';
                        }
		if(dur)
			for(var i in addon)
			{
				if(i == dur)
				{
					t_name=addon[i]['T']['name'];
					t_price=addon[i]['T']['price'];
				}
			}

        }
	if(!shrink)
	{
		var tt;
		if(document.getElementById('cb3').checked)
			tt=document.getElementById('bl').innerHTML;     
		else if(document.getElementById('cb1').checked)
			tt=1;
		else
			tt=0;
		tt++;
		
		document.getElementById(tid).checked =true;
		if(CURRENCY=='RS')
			document.getElementById('tprice').innerHTML="Rs. "+t_price;
		else
			document.getElementById('tprice').innerHTML= t_price+" $";
		document.getElementById('tservice').innerHTML="<span id=\"rb\">"+tt+"</span>. Response Booster";
		tt++;
		if(document.getElementById('cb5').checked)
		{
			document.getElementById('mp').innerHTML=tt;     
			tt++;
		}
		if(document.getElementById('cb4').checked)
		{
			document.getElementById('ac').innerHTML=tt;     
			tt++;
		}
	}                                      
}                 

function MS(flag)
{
	if(!document.getElementById('cb1').checked)
	{
		m_price=MP;
	}
        if(m_price!=0)
		if(CURRENCY=='RS')
	                document.getElementById('matrio').innerHTML="( Rs. "+m_price+" )";
		else
	                document.getElementById('matrio').innerHTML="( "+ m_price+" $ )";
        else                            
                document.getElementById('matrio').innerHTML="( Free )";
        document.getElementById('cb5').value= m_id;
	if(CURRENCY=='RS')
        	document.getElementById('amprice').innerHTML="Rs. "+m_price;
	else
        	document.getElementById('amprice').innerHTML= m_price+" $ ";
        if(flag)
        {
                document.getElementById('d5').style.display = "none"
                document.getElementById('cb5').checked = false;
        }
        else
        {
                var tt;
                if(document.getElementById('cb2').checked)
                        tt=document.getElementById('rb').innerHTML;     
                else if(document.getElementById('cb3').checked)
                        tt=document.getElementById('bl').innerHTML;     
                else if(document.getElementById('cb1').checked)
                        tt=1;
                else
                        tt=0;
                tt++;
                document.getElementById('d5').style.display = "inline"
                document.getElementById('mservice').innerHTML= "<span id=\"mp\">"+tt+"</span>. Matri Profile"
                document.getElementById('cb5').checked = true;
                tt++;
                if(document.getElementById('cb4').checked)
                {
                        document.getElementById('ac').innerHTML=tt;     
                        tt++;
                }
        }
}

function AC(duration,flag,shrink,dur)
{
        if(!shrink)
        {
                document.getElementById('cb4').checked = true;
                document.getElementById('d4').style.display = "inline";
        }
        var aid='aid'+duration;
        durr= new Array();
        if(flag)
        {
		for(var i in addon)
                {
			if(i == duration)
			{
				a_name=addon[i]['A']['name'];
				a_price=addon[i]['A']['price'];
			}
		}
        }
        else
        {
                if(duration != 12)
                {
                        durr= hide_arr[duration];
                        var i1 = durr.length;
                        for(var i=0;i<i1;i++)
                        {
                                var a = durr[i];
                                document.getElementById('uaid'+a).style.display='none';
                        }
                        for(var i=0;i<i2;i++)
                        {
                                var b=show_arr[i];
                                if(!in_array(b,durr))
                                        document.getElementById('uaid'+b).style.display='inline';
                        }
                }
                else
                        for(var i=0;i<i2;i++)
                        {
                                var b=show_arr[i];
                                document.getElementById('uaid'+b).style.display='inline';
                        }
		if(dur)
                        for(var i in addon)
                        {
                                if(i == dur)
                                {
                                        a_name=addon[i]['A']['name'];
                                        a_price=addon[i]['A']['price'];
                                }
                        }

        }

        if(!shrink)
        {
                var tt;
                if(document.getElementById('cb5').checked)
                        tt=document.getElementById('mp').innerHTML;     
                else if(document.getElementById('cb2').checked)
                        tt=document.getElementById('rb').innerHTML;     
                else if(document.getElementById('cb3').checked)
                        tt=document.getElementById('bl').innerHTML;     
                else if(document.getElementById('cb1').checked)
                        tt=1;
                else
                        tt=0;
                tt++;
                document.getElementById(aid).checked =true;
		if(CURRENCY=='RS')
	                document.getElementById('aprice').innerHTML="Rs. "+a_price;
		else
	                document.getElementById('aprice').innerHTML=a_price+" $";
                document.getElementById('aservice').innerHTML="<span id=\"ac\">"+tt+"</span>. Astro Compatibility";
                next=1;
        }
}

function enable_main()
{       
        document.getElementById("d1").style.display = "inline";
        document.getElementById("cb2").disabled=false;
        document.getElementById("cb3").disabled=false;
        for(var i=0;i<document.form.T_arr.length;i++)
        {       
                document.form.T_arr[i].disabled=false;
        }       
}       

