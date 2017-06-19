<div class="lf" style="width:48%;~if $rightSect`margin-left:20px;_margin-left:10px;~/if`">
<div class="lf pd5 subhd~if !$viewPage`1~/if`">~$LabelHeading`&nbsp;~if $isEdit`<a href="/profile/editProfile?flag=~$editFlag`&width=700" style="font-size:14px; color:#0f71ae;" class="thickbox">[Edit]</a>~/if`</div>
~foreach from=$NameValueArr item=Value key=Label`
  ~if $Label neq 'RedLabels' && $Label neq 'NewFields'`
    <div class="row2 no_b">
    <label ~if $NameValueArr.RedLabels.$Label`style="color:red!important"~/if`>~$Label`~if $NameValueArr.NewFields.$Label`~/if`</label><div class="rf ~$CODEOWN.$Label`" style="width:~if $viewPage`175~else`220~/if`px">: ~$Value|decodevar`  
    </div></div>
	~/if`
	~/foreach`
	~if $LabelHeading eq "Astro/ Kundali Details"`
		~if $HOROSCOPE eq 'N' and !$HIDE_HORO`
		
				<br><br><div style="padding-left:5px" ><a href="#" class="f14 b underline" onclick ="return horos_ajax_request('~$PROFILECHECKSUM`',1);" id="horos">Request Horoscope</a>
</div>			
		~/if`
	~/if`	
~if $isAstro and $isEdit`
~else`
</div>
~/if`
