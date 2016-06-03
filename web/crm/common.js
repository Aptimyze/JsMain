function PopSPEC(thisform,element1,element2)
{
 
        var docF=document.insertForm;
        var len_el=docF.elements.length;
        for(i=0;i<len_el;i++)
        {
                if(docF.elements[i].name=="WILL_PAY")
                        {element1=i;}
                if(docF.elements[i].name=="REASON")
                        {element2=i;}
        }
        if(thisform.value != "")
        {
                var c,spec;
                var len_religion = docF.elements[element1].options.length;
                for(var m1=0;m1<len_religion;m1++) {
                if (docF.elements[element1].options[m1].selected == true) {
                c = docF.elements[element1].options[m1].value;
                }
                }
                docF.elements[element2].options.length = 0;
 
                var str      =c.split("|X|");
                var spec_val =str[1].split("#");
 
 
                for(var k=-1;k<spec_val.length;k++)
                {
 
                        if(k==-1)
                        {
                                var s = spec_val[0];
                                var val=s.split("$");
				var opt = new Option();
                                opt.text=val[1];
                                opt.value=val[0];
 
                                var s="$Select any One Option";
                        }
                        else
                        {
                                var s = spec_val[k];
                        }
 
                        if(!((k==-1) && ((opt.value==153)||(opt.value==148)||(opt.value==1)||(opt.value==162))))
                        {
                                if(s)
                                {
                                        var val=s.split("$");
                                        var opt = new Option();
                                        opt.text=val[1];
                                        opt.value=val[0];
 
                                        if(k==0)
                                        {
                                                if((opt.value==14)||(opt.value==149)||(opt.value==154)||(opt.value==173)||(opt.value==2))
                                                {
                                                        opt.disabled=true;
                                                        opt.style.color = "graytext";
                                                        opt.value=0;
                                                }
                                        }
 
                                        docF.elements[element2].options[docF.elements[element2].options.length] = opt;
                                }
                        }
                }
        }
 
        return true;
}
