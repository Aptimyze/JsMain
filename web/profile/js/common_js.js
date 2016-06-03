function PopSPEC(thisform,element1,element2)
{
        var docF=document.search_partner;
        //if(!(document.forms['search_partner'].community.options[0].selected && document.forms['search_partner'].community.options[0].value=='All'))
        if(!(document.forms['search_partner'].community.options[0].selected && document.forms['search_partner'].community.options[0].value=='All') && !(document.forms['search_partner'].community.options[1].selected && document.forms['search_partner'].community.options[1].value=='All'))
        {
        for(i=0;i<docF.elements.length;i++)         
	{
                if(docF.elements[i].name=="community")
                        {element1=i;}
                if(docF.elements[i].name=="caste")
                        {element2=i;}
        }                 if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.elements[element1].options.length;
                for(var m1=0;m1<len_religion;m1++)
                {
                        if (docF.elements[element1].options[m1].selected == true)
                        {
                                c = docF.elements[element1].options[m1].value;
                                var mtongue_val=c.substr(0,c.indexOf("|X|"));
                                //docF.elements[element1].options[m1].value=mtongue_val;
                        }
                }
                docF.elements[element2].options.length = 0;
                var x_pos=c.indexOf("|X|")+3;
                var pos=c.indexOf("$");
                var str =c.substring(x_pos,pos);
                var spec_val =str.split(",");

                //section to append All in caste for every MTONGUE
                var opt = new Option();
                opt.text='All';
                opt.value='All';
                document.search_partner.caste.options[document.search_partner.caste.options.length]=opt;
                //end of section to append All in caste for every MTONGUE

                var str_label=c.substring(c.indexOf("$")+1,c.length);
                var spec_label=str_label.split(",");

                for(var k=0;k<spec_val.length;k++)
                {
                        var s = spec_val[k];
                        if(s)
                        {
                                var opt = new Option();
                                //opt.text=spec_label[k];
                                opt.value=spec_val[k];
                                if(spec_label[k].charAt(0)=='#')
                                {
                                        //section to give black option after the beginning of every RELIGION but not for the first RELIGION that will come
                                        if(k!=0)
                                        {
                                                var opt_blank = new Option();
                                                opt_blank.value='All';
                                                opt_blank.text='';
                                                docF.elements[element2].options[docF.elements[element2].options.length] = opt_blank;
                                        }
                                        //end of section to give black option after the end of every RELIGION

                                        opt.style.backgroundColor="#FFF1E3";
                                        opt.text=spec_label[k].substring(1,spec_label[k].length);
                                }
				else if(spec_label[k].charAt(0)=='-' && spec_label[k].charAt(1)=='-')//for SUBCASTE
                                {
                                        opt.text=spec_label[k].substring(2,spec_label[k].length);
                                }
                                else//for CASTE
                                {
                                        opt.text=spec_label[k].substring(1,spec_label[k].length);
                                }

                                docF.elements[element2].options[docF.elements[element2].options.length] = opt;
                        }
                }
                var opt = new Option();
                opt.text='More';
                opt.value='More';
                docF.elements[element2].options[docF.elements[element2].options.length] = opt;
        }

        return true;
        } 
	else    //if All has been clicked again in MTONGUE
        {
                //show all caste
                var len=document.search_partner.caste.options.length;
                for(var j=0;j<=len;j++)
                {
                        document.search_partner.caste.remove(document.search_partner.caste.options[j]);                         //document.search_partner.caste.removeChild(document.search_partner.caste.childNodes[j]);
                }
                var val_str=all_caste_str.substring(0,all_caste_str.indexOf("$"));
                var label_str=all_caste_str.substring(all_caste_str.indexOf("$")+1,all_caste_str.length);
                var val_arr = val_str.split(",");
                var label_arr = label_str.split(",");

                //section to append ALL in caste for every MTONGUE
                var opt = new Option();
                opt.text='All';
                opt.value='All';
                document.search_partner.caste.options[document.search_partner.caste.options.length]=opt;
                //end of section to append ALL in caste for every MTONGUE

                for(var k=0;k<val_arr.length;k++)
                {
                        var s = val_arr[k];
                        if(s)
                        {
                                var opt = new Option();
                                //opt.text=label_arr[k];
                                opt.value=val_arr[k];
                                if(label_arr[k].charAt(0)=='#')
                                {
                                        //section to give black option after the beginning of every RELIGION but not for the first RELIGION that will come
                                        if(k>8)
                                        {
                                                var opt_blank = new Option();
                                                opt_blank.value='All';
                                                opt_blank.text='';
                                                document.search_partner.caste.options[document.search_partner.caste.options.length]=opt_blank;
                                                opt.style.backgroundColor="#FFF1E3";
                                        }
                                        //end of section to give black option after the end of every RELIGION

                                        opt.text=label_arr[k].substring(1,label_arr[k].length);
                                }
                                else if(label_arr[k].charAt(0)=='-' && label_arr[k].charAt(1)=='-')
                                {
                                        opt.text=label_arr[k].substring(2,label_arr[k].length);
                                        //opt.style.color="orange";
                                }
                                else
                                {
                                        opt.text=label_arr[k].substring(1,label_arr[k].length);
                                }

                                /*if(label_arr[k].charAt(0)=='#')
                                {
                                        opt.style.backgroundColor="#FFF1E3";
                                        opt.text=label_arr[k].substring(1,label_arr[k].length);
                                }
                                else
                                        opt.text=label_arr[k];*/

                                document.search_partner.caste.options[document.search_partner.caste.options.length]=opt;
                        }                 
		}
        }
}

//function to show all CASTEs again when more is clicked in CASTE
function showallcaste(element2)
{         var docF=document.search_partner;
        for(i=0;i<docF.elements.length;i++)
        {
                if(docF.elements[i].name=="caste")
                        {element2=i;}
        }

        for(var i=0;i<document.forms['search_partner'].caste.options.length;i++)
        {
                var val=document.search_partner.caste.i;
                if((document.forms['search_partner'].caste.options[i].selected && document.forms['search_partner'].caste.options[i].value=='More'))
                {
                        var len=document.search_partner.caste.options.length
                        //var len=document.search_partner.caste.childNodes.length;
                        for(var j=0;j<=len;j++)
                        {
                                document.search_partner.caste.remove(document.search_partner.caste.options[j]);
                                //document.search_partner.caste.removeChild(document.search_partner.caste.childNodes[j]);
                        }

                        //section to append ALL in caste for every MTONGUE
                        var opt = new Option();
                        opt.text='All';
                        opt.value='All';
                        document.search_partner.caste.options[document.search_partner.caste.options.length]=opt;
                        //end of section to append ALL in caste for every MTONGUE

                        var val_str=all_caste_str.substring(0,all_caste_str.indexOf("$"));
                        var label_str=all_caste_str.substring(all_caste_str.indexOf("$")+1,all_caste_str.length);
                        var val_arr = val_str.split(",");
                        var label_arr = label_str.split(",");
                        for(var k=0;k<val_arr.length;k++)
                        {
                                var s = val_arr[k];
                                if(s)
                                {
                                        var opt = new Option();
                                        //opt.text=label_arr[k];
                                        opt.value=val_arr[k];

                                        if(label_arr[k].charAt(0)=='#')
                                        {
                                                //section to give black option after the beginning of every RELIGION but not for the first RELIGION that will come
                                                if(k>8)
                                                {
                                                        var opt_blank = new Option();
                                                        opt_blank.value='All';
                                                        opt_blank.text='';
                                                        docF.elements[element2].options[docF.elements[element2].options.length] = opt_blank;
                                                        opt.style.backgroundColor="#FFF1E3";
                                                }
                                                //end of section to give black option after the end of every RELIGION

                                                opt.text=label_arr[k].substring(1,label_arr[k].length);
                                        }
                                        else if(label_arr[k].charAt(0)=='-' && label_arr[k].charAt(1)=='-')
                                        {
                                                opt.text=label_arr[k].substring(2,label_arr[k].length);
                                                //opt.style.color="orange";
                                        }
                                        else
                                                opt.text=label_arr[k].substring(1,label_arr[k].length);
                                        document.search_partner.caste.options[document.search_partner.caste.options.length]=opt;
                                }
                        }
                }
        }
}

function openparentwindow(SITE_URL,type)
{
	if(type=='payment')
		window.opener.location=SITE_URL+"/profile/payment.php?skip_to_compatibility=1&ser_main=P";
	else if(type=='astro')
		window.opener.location=SITE_URL+"/profile/horoscope_details.php";
	//alert(parent.location);
	window.close();
}


