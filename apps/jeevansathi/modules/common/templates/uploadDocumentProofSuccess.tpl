<body>
    <!--start:header-->
    <div class="cover1">
        <div class="container mainwid pt35 pb48">
            ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
        </div>
    </div>
    <!--end:header-->
    <!--start:middle-->
    <div class="bg-4">
        <div class="container mainwid">
     ~if $done` 
    <!--start:changed succesfully -->   
<div class="pb400 pt50 fontlig txtc color11">
<div class="pb30 pt30 mauto wid70p">
<p>You have successfully uploaded your document,</p>
<p class="pt5"><a class="colr5" href="~sfConfig::get('app_site_url')`/">Click here</a> to access your account.</p>
</div>
</div>
~else`
	<div id="UploadDocumentJspc" class="uplaodDocContent">
                <p class="pt30 pb30 txtc fontlig color11 f15">Upload Divorce Decree</p>
            	<div class="setwid5 mauto pb30">                	
                    <div class="fullwid bg-white">
                    	<form action="/common/uploadDocumentProof?submitForm=1" method="POST" id="uploadDocForm" enctype="multipart/form-data">
                                <input type="hidden" name="emailStr" id="emailStr" value="~$emailStr`">
                                <input type="hidden" name="d" id="emailStr" value="~$d`">
                                <input type="hidden" name="MSTATUS" id="MSTATUS" value="D">
                        	<div class="setp2 fontlig">
                            	<!--start:field 1-->
                                <p id="topError" class="color5 f12 txtc vishid sethgt1">~$uploadValidDocument`</p>
                                <div class="">
                                	<input id="MSTATUS_PROOF" name="MSTATUS_PROOF" type="file" class="hgt30IE color12 fullwid brdr-0 outwhi lh40 pl20 wid90p f15 fontlig disp-none">
                                        <div class="pos-rel">
                                                <div class="bg_pink lh30 f14 colrw txtc brdr-0 cursp disp_ib fullwid pos-rel wid50p dispib" type="file" placeholder="Not filled in" id="idBtn_id_proof_val" autocomplete="off">Divorce Decree</div>
                                                <div class="f14 disp_ib color5 padl15 vertM dispib textTru wid40p" id="idlabel_id_proof_val">jpg/pdf only</div>                                                       
                                        </div>
                                </div>                                
                                <!--end:field 1-->
                            </div>
                            <div id="saveBtn" class="cursp applied1 brdr-0 fullwid lh50 txtc colrw f15 fontlig">Upload Document</div>
                        </form>
                    </div>                
                </div>               
            </div>

~/if`
            
        </div>
    </div>
    <!--end:middle-->
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`
    <!--end:footer-->
</body>
