<div id="resetp" class="outerdiv bg4" style="height: 627px;"> 
  <!--start:header-->
  <div id="overlayHead" class="fullwid bg1">
    <div class="pad5 clearfix white">
        <a href="/" class="white" ><div class="fl f14 fontlig wid20p txtl pt6 txtdec">Cancel</div></a>
      <div class="fl fontthin f19 wid60p txtc">Upload Proof</div>
      <div id="saveBtn" class="fl f14 fontlig wid20p txtr pt6 opa50">Save</div>
    </div>
  </div>
  <!--end:header--> 
  <form action="/common/uploadDocumentProof?submitForm=1" method="POST" id="uploadDocForm" enctype="multipart/form-data">
          <input type="hidden" name="MSTATUS" value="D">
  <!--start:div-->  
  <div class="fullwid  brdr1 back-Gray">
    <div class="pad18 clearfix frm_ele">
      <div class="fl wid88p">
        <input type="file" name="MSTATUS_PROOF" id="MSTATUS_PROOF" labelkey="MSTATUS_PROOF" style="width:0px;height:0px;position:absolute;" mstatuschange="">
        <div id="keyMSTATUS_PROOF" class="f17 fontthin upload-btn-jsms"style="display:inline;">Divorce Decree</div>
        <div id="label_keyMSTATUS_PROOF" class="f17 fontthin pad2" style="display:inline;">jpg/pdf only</div>
      </div>
    </div>
  </div>
  <!--end:div-->
  </form>
  <script>
          var done = "Y";
  </script>
  <style>
  .back-Gray{background-color: #e4e4e4;}
  .upload-btn-jsms{              display: inline;
    border: 1px solid #808080;
    padding: 8px;
    margin-right: 10px;
    color: #D9475D;
    font-weight: 400;
    background-color: #fff;
  }</style>
</div>