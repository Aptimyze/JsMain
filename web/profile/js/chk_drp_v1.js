function form1_submit()
{
	ff();
	gg();
	hh();
	kk();
	jj();
	if(flag1==1)
		get();
	return false;
}
function form3_submit()
{
	zz();
	yy();
	xx();
	ww();
	vv();
	if(flag3==1)
		get1();
	return false;

}

function onload1()
{
        var BrowserDetect = {
                init: function () {
                        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
                        flag4 = this.browser;
                        this.version = this.searchVersion(navigator.userAgent)
                                || this.searchVersion(navigator.appVersion)
                                || "an unknown version";
                        flag5 = this.version;
                        this.OS = this.searchString(this.dataOS) || "an unknown OS";
                },
                searchString: function (data) {
                        for (var i=0;i<data.length;i++) {
                                var dataString = data[i].string;
                                var dataProp = data[i].prop;
                                this.versionSearchString = data[i].versionSearch || data[i].identity;
                                if (dataString) {
                                        if (dataString.indexOf(data[i].subString) != -1)
                                                return data[i].identity;
                                }
                                else if (dataProp)
                                        return data[i].identity;
                        }
                },
                searchVersion: function (dataString) {
                        var index = dataString.indexOf(this.versionSearchString);
                        if (index == -1) return;
                        return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
                },
                dataBrowser: [
                        {
                                string: navigator.userAgent,
                                subString: "Chrome",
                                identity: "Chrome"
                        },
                        {       string: navigator.userAgent,
                                subString: "OmniWeb",
                                versionSearch: "OmniWeb/",
                                identity: "OmniWeb"
                        },
                        {
                                string: navigator.vendor,
                                subString: "Apple",
                                identity: "Safari",
                                versionSearch: "Version"
                        },
                        {
                                prop: window.opera,
                                identity: "Opera"
                        },
                        {
                                string: navigator.vendor,
                                subString: "iCab",
                                identity: "iCab"
                        },
                        {
                                string: navigator.vendor,
                                subString: "KDE",
                                identity: "Konqueror"
                        },
                        {
                                string: navigator.userAgent,
                                subString: "Firefox",
                                identity: "Firefox"
                        },
                        {
                                string: navigator.vendor,
                                subString: "Camino",
                                identity: "Camino"
                        },
                        {               // for newer Netscapes (6+)
                                string: navigator.userAgent,
                                subString: "Netscape",
                                identity: "Netscape"
                        },
                        {
                                string: navigator.userAgent,
                                subString: "MSIE",
                                identity: "Explorer",
                                versionSearch: "MSIE"
                        },
                        {
                                string: navigator.userAgent,
                                subString: "Gecko",
                                identity: "Mozilla",
                                versionSearch: "rv"
                        },
                        {               // for older Netscapes (4-)
                                string: navigator.userAgent,
                                subString: "Mozilla",
                                identity: "Netscape",
                                versionSearch: "Mozilla"
                        }
                ],
                dataOS : [
                        {
                                string: navigator.platform,
                                subString: "Win",
                                identity: "Windows"
                        },
                        {
                                string: navigator.platform,
                                subString: "Mac",
                                identity: "Mac"
                        },
                        {
                                string: navigator.platform,
                                subString: "Linux",
                                identity: "Linux"
                        }
                ]

        };
        BrowserDetect.init();
        if(flag4 == "Explorer" && flag5 !='8')
        {
                document.getElementById("oth").style.display = "none";
        }
        else
        {
                document.getElementById("oth").style.display = "block";
        }
        if(document.getElementById("lynj"))
                document.getElementById("lynj").onchange=jnyl;
        if(document.getElementById("risa1"))
                document.getElementById("risa1").onclick=risa1;
        if(document.getElementById("risa2"))
                document.getElementById("risa2").onclick=risa2;
        if(document.getElementById("risa3"))
                document.getElementById("risa3").onclick=risa3;
        document.getElementById("risa4").onclick=risa4;
        if(document.getElementById("f"))
                document.getElementById("f").onblur=ff;
        if(document.getElementById("g"))
                document.getElementById("g").onblur=gg;
        if(document.getElementById("h"))
                document.getElementById("h").onblur=hh;
        if(document.getElementById("j"))
                document.getElementById("j").onblur=jj;
        if(document.getElementById("k"))
        {
                document.getElementById("k").onblur=kk;
                document.getElementById("k").onchange=kk;
        }
        if(document.getElementById("l"))
        {
                document.getElementById("l").onblur=kk;
                document.getElementById("l").onchange=kk;
        }
        if(document.getElementById("m"))
        {
                document.getElementById("m").onblur=kk;
                document.getElementById("m").onchange=kk;
        }
        if(document.getElementById("z"))
                document.getElementById("z").onblur=zz;
        if(document.getElementById("ya"))
        {
                document.getElementById("ya").onblur=yy;
                document.getElementById("yb").onblur=yy;
                document.getElementById("yc").onblur=yy;
                document.getElementById("yc").onchange=yy;
                document.getElementById("yb").onchange=yy;
                document.getElementById("ya").onchange=yy;
        }
        if(document.getElementById("xa"))
        {
                document.getElementById("xb").onblur=xx;
                document.getElementById("xa").onchange=xx;
                document.getElementById("xa").onblur=xx;
        }
        if(document.getElementById("w"))
                document.getElementById("w").onblur=ww;
        if(document.getElementById("v"))
                document.getElementById("v").onblur=vv;
        if(document.getElementById("da1"))
                document.getElementById("da1").style.color="#117DAA";
}
function zz()
{
        document.getElementById("z1").style.display = 'none';
        if(document.form3.cdnum.value.length==0 || isNaN(document.form3.cdnum.value) || trim(document.form3.cdnum.value)=="")
        {
                document.getElementById("z1").style.display = 'block';
                if(focus2!=1)
                        document.form3.cdnum.focus();
                focus2=1;
                if(flag3%2!=0)
                        flag3*=2;
                return false;
        }
        if(flag3%2==0)
                flag3/=2;

}
function yy()
{
        var sel_day = document.form3.cd_day.value;
        var sel_month = document.form3.cd_month.value;
        var sel_year = document.form3.cd_year.value;
        document.getElementById("y1").style.display = 'none';
        document.getElementById("y2").style.display = 'none';
        document.getElementById("y3").style.display = 'none';
        if(sel_day == 31)
        {
                if(sel_month==2 || sel_month==4 || sel_month==6 || sel_month==9 || sel_month==11)
                {
                        document.getElementById("y1").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_day.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
        }
        else
        {
                if(sel_day > 28 && sel_month == 2)
                {
                        if(sel_year % 100 == 0)
                        {
                                if(sel_year % 400 != 0)
                                {
                                        document.getElementById("y1").style.display = 'block'
                                        if(focus2!=1)
                                                document.form3.cd_day.focus();
                                        focus2=1;
                                        if(flag3%3!=0)
                                                flag3*=3;
                                        return false;
                                }
                        }
                        else
                        {
                                if(sel_year % 4 != 0)
                                {
                                        document.getElementById("y1").style.display = 'block'
                                        if(focus2!=1)
                                                document.form3.cd_day.focus();
                                        focus2=1;
                                        if(flag3%3!=0)
                                                flag3*=3;
                                        return false;
                                }
                        }
                }
        }
        if(document.form3.cd_year.value == cur_year)
        {
                var old = cur_month-4;
                if(document.form3.cd_month.value < old)
                {
                        document.getElementById("y2").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_month.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                else if(document.form3.cd_month.value == old && document.form3.cd_day.value < cur_day)
                {
                        document.getElementById("y2").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_month.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                var next = cur_month+1;
                if(document.form3.cd_month.value > next)
                {
                        document.getElementById("y3").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_month.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                else if(document.form3.cd_month.value == next && document.form3.cd_day.value > cur_day)
                {
                        document.getElementById("y3").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_month.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
        }
        else if(document.form3.cd_year.value < cur_year)
        {
                var old = 12 + (cur_month - 4);
                if(document.form3.cd_month.value < old )
                {
                        document.getElementById("y2").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_month.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                else
                {
                        if(document.form3.cd_month.value == old && document.form3.cd_day.value < cur_day)
                        {
                                document.getElementById("y2").style.display = 'block'
                                if(focus2!=1)
                                        document.form3.cd_month.focus();
                                focus2=1;
                                if(flag3%3!=0)
                                        flag3*=3;
                                return false;
                        }
                }
        }
        else if(document.form3.cd_year.value > cur_year)
        {
                if(cur_month < 12)
                {
                        document.getElementById("y3").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_year.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                else if(cur_month == 12 && document.form3.cd_month.value > 1)
                {
                        document.getElementById("y3").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_year.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
                else if(cur_month == 12 && document.form3.cd_month.value == 1 && document.form3.cd_day.value > cur_day)
                {
                        document.getElementById("y3").style.display = 'block'
                        if(focus2!=1)
                                document.form3.cd_year.focus();
                        focus2=1;
                        if(flag3%3!=0)
                                flag3*=3;
                        return false;
                }
        }
        if(document.form3.cd_day.value.length==0 || document.form3.cd_month.value.length==0 || document.form3.cd_year.value.length==0)
        {
                document.getElementById("y1").style.display = 'block'
                if(focus2!=1)
                        document.form3.cd_day.focus();
                focus2=1;
                if(flag3%3!=0)
                        flag3*=3;
                return false;
        }
        if(flag3%3==0)
                flag3/=3;


}
function xx()
{
        document.getElementById("x1").style.display = 'none';
        if(document.form3.Bank.value.length==0 && (document.form3.obank.value.length==0 || trim(document.form3.obank.value)==""))
        {
                document.getElementById("x1").style.display = 'block'
                if(focus2!=1)
                        document.form3.Bank.focus();
                focus2=1;
                if(flag3%5!=0)
                        flag3*=5;
                return false;
        }
        if(document.form3.Bank.value=="Other" && trim(document.form3.obank.value).length==0)
        {
                document.getElementById("x1").style.display = 'block'
                if(focus2!=1)
                        document.form3.obank.focus();
                focus2=1;
                if(flag3%5!=0)
                        flag3*=5;
                return false;
        }
        if(flag3%5==0)
                flag3/=5;
        
}
function ww()
{
        document.getElementById("w1").style.display = 'none';
        if(document.form3.cd_city.value.length==0 || trim(document.form3.cd_city.value)=="")
        {
                document.getElementById("w1").style.display = 'block'
                if(focus2!=1)
                        document.form3.cd_city.focus();
                focus2=1;
                if(flag3%7!=0)
                        flag3*=7;
                return false;
        }
        if(flag3%7==0)
                flag3/=7;

}
function vv()
{
        document.getElementById("v1").style.display = 'none';
        document.getElementById("v2").style.display = 'none';
        if(isNaN(document.form3.MOB_NO.value) || document.form3.MOB_NO.value.length==0 || trim(document.form3.MOB_NO.value)=="")
        {
                document.getElementById("v1").style.display = 'block'
                if(focus2!=1)
                        document.form3.MOB_NO.focus();
                focus2=1;
                if(flag3%11!=0)
                        flag3*=11;
                return false;
        }
        else if(trim(document.form3.MOB_NO.value).length < 10)
        {
                document.getElementById("v2").style.display = 'block'
                if(focus2!=1)
                        document.form3.MOB_NO.focus();
                focus2=1;
                if(flag3%11!=0)
                        flag3*=11;
                return false;
        }
        if(flag3%11==0)
                flag3/=11;

}
function jnyl()
{
         var poststr =  "checksum=" + document.form2.checksum.value + "&city=" + document.form2.lynj.value + "&submit=change city";
        flag2 =2;
        document.getElementById("chc").innerHTML=document.getElementById("lynj").value;
        createAjaxObj('chequedrop.php', poststr);       
}
function ff()
{
         if(document.form1.NAME1.value.length==0 || !isNaN(document.form1.NAME1.value) || name_check(document.form1.NAME1.value) || trim(document.form1.NAME1.value) == "")
        {
                document.getElementById("f1").style.display = 'block';
                if(focus1!=1)
                        document.form1.NAME1.focus();
                focus1 =1;
                if(flag1%2!=0)
                        flag1*=2;
                        
        }
        else
        {
                document.getElementById("f1").style.display = 'none';
                if(flag1%2==0)
                        flag1/=2;
        }
}
function gg()
{
        if(isNaN(document.form1.PHONE_RES.value) || document.form1.PHONE_RES.value.length==0 || trim(document.form1.PHONE_RES.value)=="")
        {
                document.getElementById("g1").style.display = 'block';
                document.getElementById("g2").style.display = 'none';
                if(focus1!=1)
                        document.form1.PHONE_RES.focus();
                focus1=1;
                if(flag1%3!=0)
                        flag1*=3;       
        }
        else if(trim(document.form1.PHONE_RES.value).length < 6)
        {
                document.getElementById("g2").style.display = 'block';
                document.getElementById("g1").style.display = 'none';
                if(focus1!=1)
                        document.form1.PHONE_RES.focus();
                focus1=1;
                if(flag1%3!=0)
                        flag1*=3;       
        }
        else
        {
                document.getElementById("g1").style.display = 'none';
                document.getElementById("g2").style.display = 'none';
                if(flag1%3==0)
                        flag1/=3;
        }

}
function hh()
{
        if(isNaN(document.form1.PHONE_MOB.value) || document.form1.PHONE_MOB.value.length==0 ||  trim(document.form1.PHONE_MOB.value)=="")
        {
                document.getElementById("h1").style.display = 'block';
                document.getElementById("h2").style.display = 'none';
                if(focus1!=1)
                        document.form1.PHONE_MOB.focus();
                focus1=1;
                if(flag1%5!=0)
                        flag1*=5;       
        }
        else if(trim(document.form1.PHONE_MOB.value).length < 10)
        {
                document.getElementById("h2").style.display = 'block';
                document.getElementById("h1").style.display = 'none';
                if(focus1!=1)
                        document.form1.PHONE_MOB.focus();
                focus1=1;
                if(flag1%5!=0)
                        flag1*=5;       
        }
        else
        {
                document.getElementById("h1").style.display = 'none';
                document.getElementById("h2").style.display = 'none';
                if(flag1%5==0)
                        flag1/=5;
        }

}
function jj()
{
        if(document.form1.ADDRESS.value.length==0 || trim(document.form1.ADDRESS.value)=="" || trim_newline(document.form1.ADDRESS.value)=="")
        {
                document.getElementById("j1").style.display = 'block';
                if(focus1!=1)
                        document.form1.ADDRESS.focus();
                focus1=1;
                if(flag1%7!=0)
                        flag1*=7;       
        }
        else
        {
                document.getElementById("j1").style.display = 'none';
                if(flag1%7==0)
                        flag1/=7;
        }

}
function kk()
{
        document.getElementById("k1").style.display = 'none';
        document.getElementById("k2").style.display = 'none';
        document.getElementById("k3").style.display = 'none';
        if(document.form1.pref_day.value.length==0 || document.form1.pref_month.value.length==0 || document.form1.pref_year.value.length==0)
        {
                document.getElementById("k1").style.display = 'block';
                if(focus1!=1)
                        document.form1.pref_day.focus();
                focus1=1;
                if(flag1%11!=0)
                        flag1*=11;      
                return false;
        }
        var sel_day = document.form1.pref_day.value;
        var sel_month = document.form1.pref_month.value;
        var sel_year = document.form1.pref_year.value;
        if(sel_day == 31)
        {
                if(sel_month==2 || sel_month==4 || sel_month==6 || sel_month==9 || sel_month==11)
                {
                        document.getElementById("k2").style.display = 'block';
                        if(focus1!=1)
                                document.form1.pref_day.focus();
                        focus1=1;
                        if(flag1%11!=0)
                                flag1*=11;      
                        return false;
                }
        }
        else if(sel_day == 30 && sel_month == 2)
        {
                document.getElementById("k2").style.display = 'block';
                if(focus1!=1)
                        document.form1.pref_day.focus();
                focus1=1;
                if(flag1%11!=0)
                        flag1*=11;      
                return false;
        }
        else
        {
                if(sel_day > 28 && sel_month == 2)
                {
                        if(sel_year % 100 == 0)
                        {
                                if(sel_year % 400 != 0)
                                {
                                        document.getElementById("k2").style.display = 'block';
                                        if(focus1!=1)
                                                document.form1.pref_day.focus();
                                        focus1=1;
                                        if(flag1%11!=0)
                                                flag1*=11;      
                                        return false;
                                }
                        }
                        else
                        {
                                if(sel_year % 4 != 0)
                                {
                                        document.getElementById("k2").style.display = 'block';
                                        if(focus1!=1)
                                                document.form1.pref_day.focus();
                                        focus1=1;
                                        if(flag1%11!=0)
                                                flag1*=11;      
                                        return false;
                                }
                        }
                }
        }
        if(document.form1.pref_year.value == after2_year)
        {
                if(document.form1.pref_month.value == after2_month)
                {
                        if(document.form1.pref_day.value < after2_date)
                        {
                                document.getElementById("k3").style.display = 'block';
                                document.getElementById("1").innerHTML = date_select_string ;
                                document.getElementById("2").innerHTML = date_select_string ;
                                if(focus1!=1)
                                        document.form1.pref_day.focus();
                                focus1=1;
                                if(flag1%11!=0)
                                        flag1*=11;      
                                return false;
                        }
                }
                else
                {
                        if(document.form1.pref_month.value < after2_month)
                        {
                                document.getElementById("k3").style.display = 'block';
                                document.getElementById("1").innerHTML = date_select_string ;
                                document.getElementById("2").innerHTML = date_select_string ;
                                if(focus1!=1)
                                        document.form1.pref_month.focus();
                                focus1=1;
                                if(flag1%11!=0)
                                        flag1*=11;      
                                return false;
                        }
                }
        }
        else if(document.form1.pref_year.value < after2_year)
        {
                document.getElementById("k3").style.display = 'block';
                document.getElementById("1").innerHTML = date_select_string ;
                document.getElementById("2").innerHTML = date_select_string ;
                if(focus1!=1)
                        document.form1.pref_year.focus();
                focus1=1;
                if(flag1%11!=0)
                        flag1*=11;      
                return false;
        }
        document.getElementById("k1").style.display = 'none';
        document.getElementById("k2").style.display = 'none';
        document.getElementById("k3").style.display = 'none';
        if(flag1%11==0)
                flag1/=11;
} 
function trim_newline(string)
{
        return string.replace(/^\s*|\s*$/g, "");
}

function trim(inputString) 
{
        if (typeof inputString != "string")
        {
                return inputString;
        }
        var retValue = inputString;
        var ch = retValue.substring(0, 1);
        while (ch == " ")
        {
                retValue = retValue.substring(1, retValue.length);
                ch = retValue.substring(0, 1);
        }
        ch = retValue.substring(retValue.length-1, retValue.length);
        while (ch == " ")
        {
                retValue = retValue.substring(0, retValue.length-1);
                ch = retValue.substring(retValue.length-1, retValue.length);
        }
        while (retValue.indexOf("  ") != -1)
        {
                retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
        }
        return retValue;
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

function risa1()
{
        if(document.getElementById("d1"))
        {
                document.getElementById("d1").style.display="block";    
                document.getElementById("d1").style.width="450px";
        }
        if(document.getElementById("d2"))
                document.getElementById("d2").style.display="none";
        if(document.getElementById("d3"))
                document.getElementById("d3").style.display="none";
        document.getElementById("d4").style.display="none";

        if(document.getElementById("da1"))
                document.getElementById("da1").style.color="#117DAA";
        if(document.getElementById("da2"))
                document.getElementById("da2").style.color="#000000";
        if(document.getElementById("da3"))
                document.getElementById("da3").style.color="#000000";
        document.getElementById("da4").style.color="#000000";

	document.getElementById("pickup_image2").style.display="block";
}
function risa2()
{
        if(document.getElementById("d2"))
        {
                document.getElementById("d2").style.display="block";    
                document.getElementById("d2").style.width="450px";
        }
        if(document.getElementById("d1"))
                document.getElementById("d1").style.display="none";
        if(document.getElementById("d3"))
                document.getElementById("d3").style.display="none";
        document.getElementById("d4").style.display="none";

        if(document.getElementById("da1"))
                document.getElementById("da1").style.color="#000000";
        if(document.getElementById("da2"))
                document.getElementById("da2").style.color="#117DAA";
        if(document.getElementById("da3"))
                document.getElementById("da3").style.color="#000000";
        document.getElementById("da4").style.color="#000000";
}
function risa3()
{
        if(document.getElementById("d3"))
        {
                document.getElementById("d3").style.display="block";    
                document.getElementById("d3").style.width="450px";
        }
        if(document.getElementById("d2"))
                document.getElementById("d2").style.display="none";
        if(document.getElementById("d1"))
                document.getElementById("d1").style.display="none";
        document.getElementById("d4").style.display="none";

        if(document.getElementById("da1"))
                document.getElementById("da1").style.color="#000000";
        if(document.getElementById("da2"))
                document.getElementById("da2").style.color="#000000";
        if(document.getElementById("da3"))
                document.getElementById("da3").style.color="#117DAA";
        document.getElementById("da4").style.color="#000000";
	document.getElementById("pickup_image2").style.display="block";
}
function risa4()
{       
        document.getElementById("d4").style.display="block";    
        document.getElementById("d4").style.width="450px";
        if(document.getElementById("d2"))
                document.getElementById("d2").style.display="none";
        if(document.getElementById("d3"))
                document.getElementById("d3").style.display="none";
        if(document.getElementById("d1"))
                document.getElementById("d1").style.display="none";

        if(document.getElementById("da1"))
                document.getElementById("da1").style.color="#000000";
        if(document.getElementById("da2"))
                document.getElementById("da2").style.color="#000000";
        if(document.getElementById("da3"))
                document.getElementById("da3").style.color="#000000";
        document.getElementById("da4").style.color="#117DAA";
	document.getElementById("pickup_image2").style.display="block";
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
function alertContents() 
{//alert(httprequest.readyState)
        if (httprequest.readyState == 4) 
        {
                if (httprequest.status == 200) 
                {
                        if(flag2 ==1)
                        {
                                document.getElementById("container1").style.display ='block';
                                document.getElementById('mem_cont').style.display = 'none';
                                document.getElementById('con1').innerHTML = document.getElementById('f').value;
                                document.getElementById('con2').innerHTML = document.getElementById('g').value;
                                document.getElementById('con3').innerHTML = document.getElementById('h').value;
                                var w = document.getElementById('i').selectedIndex;
                                var selected_text = document.getElementById('i').options[w].text;
                                document.getElementById('con4').innerHTML = selected_text;
                                document.getElementById('con5').innerHTML = document.getElementById('j').value;
                                document.getElementById('con6').innerHTML = document.getElementById('k').value+"/"+document.getElementById('l').value+"/"+document.getElementById('m').value;
                                document.getElementById('con7').innerHTML = document.getElementById('n').value;
                                
                        }
                        else if(flag2 ==2)
                        {
                                var result = eval( "(" + httprequest.responseText + ")" );
                                var node=document.getElementById("narayan");
                                removeChildrenFromNode(node);
                                for(var i=0 ;i<result.i;i++)
                                {
                                        var newHeading = document.createElement("b");
                                        newHeading.innerHTML =result[i].NAME
                                        var lab1 =document.createElement("i");
                                        lab1.innerHTML='CONTACT';
                                        newHeading.appendChild(lab1);
                                        var div1 =document.createElement("s");
                                        div1.innerHTML=result[i].CONTACT_PERSON;
                                        var lab2 =document.createElement("i");
                                        lab2.innerHTML='ADDRESS';
                                        var div2 =document.createElement("s");
                                        div2.innerHTML=result[i].ADDRESS;
                                        var lab3 =document.createElement("i");
                                        lab3.innerHTML='PHONE';
                                        var div3 =document.createElement("s");
                                        div3.innerHTML=result[i].PHONE;
                                        var lab4 =document.createElement("i");
                                        lab4.innerHTML='MOBILE';
                                        var div4 =document.createElement("s");
                                        div4.innerHTML=result[i].MOBILE;
                                        document.getElementById("narayan").appendChild(newHeading);
                                        document.getElementById("narayan").appendChild(lab1);
                                        document.getElementById("narayan").appendChild(div1);
                                        document.getElementById("narayan").appendChild(lab2);
                                        document.getElementById("narayan").appendChild(div2);
                                        document.getElementById("narayan").appendChild(lab3);
                                        document.getElementById("narayan").appendChild(div3);
                                        document.getElementById("narayan").appendChild(lab4);
                                        document.getElementById("narayan").appendChild(div4);

                  
                                }
                        }
                        else if(flag2 == 3)
                        {
                                document.getElementById("container2").style.display ='block';
                                document.getElementById('mem_cont').style.display = 'none';
                                document.getElementById('pro1').innerHTML =document.getElementById('z').value;
                                document.getElementById('pro2').innerHTML =document.getElementById('ya').value+"/"+document.getElementById('yb').value+"/"+document.getElementById('yc').value;
                                if(document.getElementById('xa').value =='Other')
                                        document.getElementById('pro3').innerHTML =document.getElementById('xb').value;
                                else
                                        document.getElementById('pro3').innerHTML =document.getElementById('xa').value;
                                document.getElementById('pro4').innerHTML =document.getElementById('w').value;
                                document.getElementById('pro5').innerHTML =document.getElementById('v').value;

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
      var poststr = "NAME1=" + document.form1.NAME1.value + "&PHONE_RES=" + document.form1.PHONE_RES.value + "&SERVICE=" +  document.form1.SERVICE.value  + "&checksum=" + document.form1.checksum.value + "&EMAIL=" + document.form1.EMAIL.value + "&ADDRESS="+ document.form1.ADDRESS.value + "&city=" + document.form1.city_res.value + "&COMMENTS="+ document.form1.COMMENTS.value + "&pref_year=" + document.form1.pref_year.value + "&pref_month=" + document.form1.pref_month.value+ "&pref_day=" + document.form1.pref_day.value + "&REQUESTID=" + document.form1.REQUESTID.value + "&submit=Submit Request"+"&PHONE_MOB=" + document.form1.PHONE_MOB.value;
        flag2 =1;
        createAjaxObj('chequedrop.php', poststr);
}
function get1() 
{
        var poststr = "MOB_NO=" + document.form3.MOB_NO.value + "&cdnum=" + document.form3.cdnum.value + "&cd_day=" + document.form3.cd_day.value + "&cd_month=" + document.form3.cd_month.value+ "&cd_year=" + document.form3.cd_year.value + "&cd_city=" + document.form3.cd_city.value + "&Bank=" + document.form3.Bank.value + "&obank=" + document.form3.obank.value + "&REQUESTID=" + document.form3.REQUESTID.value + "&submit=Submit Cheque" + "&checksum=~$CHECKSUM`" ;
        flag2 =3;
        createAjaxObj('chequedrop.php', poststr);
}

